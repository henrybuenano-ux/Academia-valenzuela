<?php
/**
 * Plugin Name: Omnia — EvoCampus Subscription Sync
 * Description: Sincroniza el acceso al campus EvoCampus con el estado de las suscripciones de WooCommerce Subscriptions (baja por impago, reactivación al pagar) + conciliación diaria. Espejo opcional de eventos hacia GoHighLevel.
 * Version: 0.2.0
 * Author: Omnia (ómibu)
 * Requires Plugins: woocommerce
 *
 * Configuración — añadir en wp-config.php:
 *   define( 'OMNIA_EVO_CLIENTID', '83208' );
 *   define( 'OMNIA_EVO_KEY',      '...' );          // Key del panel EvoCampus (Config > Complementos > API)
 *   define( 'OMNIA_EVO_DRYRUN',   true );           // true = solo loguea, NO llama a updateEnrollment
 *   define( 'OMNIA_GHL_WEBHOOK_URL', 'https://...' ); // opcional: Inbound Webhook de un workflow GHL (espejo CRM)
 *
 * Logs: WooCommerce > Estado > Registros > fuente "omnia-evocampus-sync".
 *
 * ⚠ SCAFFOLD pendiente de validar en staging: los nombres exactos de los
 * campos de respuesta de la API (getEnrollments / updateEnrollment) se
 * confirman contra la documentación oficial y el entorno real antes de
 * desactivar el DRY-RUN.
 */

defined( 'ABSPATH' ) || exit;

final class Omnia_EvoCampus_Sync {

	const API_BASE        = 'https://api.evolcampus.com/api';
	const TOKEN_TRANSIENT = 'omnia_evo_api_token';
	const TOKEN_TTL       = 50 * MINUTE_IN_SECONDS; // el JWT dura ~1h; renovamos a los 50 min
	const CRON_HOOK       = 'omnia_evo_daily_reconciliation';
	const LOG_SOURCE      = 'omnia-evocampus-sync';

	// Estados de matrícula EvoCampus (updateEnrollment.status)
	const EVO_ACTIVA  = 0;
	const EVO_BAJA    = 2;

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		// Estados de WooCommerce Subscriptions que cortan el acceso.
		// Nota de negocio: si se acuerda periodo de gracia, quitar 'on-hold'
		// de esta lista y dejar que actúe solo la conciliación/reintentos.
		foreach ( array( 'on-hold', 'cancelled', 'expired' ) as $status ) {
			add_action( "woocommerce_subscription_status_{$status}", array( $this, 'handle_deactivation' ), 10, 1 );
		}
		add_action( 'woocommerce_subscription_status_active', array( $this, 'handle_reactivation' ), 10, 1 );

		// Conciliación diaria (red de seguridad por si un hook no dispara).
		add_action( self::CRON_HOOK, array( $this, 'run_reconciliation' ) );
		register_activation_hook( __FILE__, array( __CLASS__, 'activate' ) );
		register_deactivation_hook( __FILE__, array( __CLASS__, 'deactivate' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	public static function activate() {
		if ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
			// 04:30 hora del sitio, fuera de horas de uso del campus.
			wp_schedule_event( strtotime( 'tomorrow 04:30' ), 'daily', self::CRON_HOOK );
		}
	}

	public static function deactivate() {
		wp_clear_scheduled_hook( self::CRON_HOOK );
	}

	/* ---------------------------------------------------------------------
	 * Handlers de WooCommerce Subscriptions
	 * ------------------------------------------------------------------- */

	public function handle_deactivation( $subscription ) {
		$this->sync_subscription( $subscription, self::EVO_BAJA, 'baja' );
	}

	public function handle_reactivation( $subscription ) {
		$this->sync_subscription( $subscription, self::EVO_ACTIVA, 'reactivacion' );
	}

	/**
	 * Modelo de negocio actual: 1 suscripción = acceso a todo el campus,
	 * así que se opera sobre TODAS las matrículas del email del alumno.
	 * (Para cortar curso a curso: usar el mapeo Producto→Grupo del conector.)
	 */
	private function sync_subscription( $subscription, $target_status, $reason ) {
		if ( ! is_a( $subscription, 'WC_Subscription' ) ) {
			return;
		}

		$email = $subscription->get_billing_email();
		if ( empty( $email ) ) {
			$this->log( 'error', "Suscripción #{$subscription->get_id()} sin email de facturación; no se sincroniza." );
			return;
		}

		$this->log( 'info', sprintf(
			'Evento "%s" (suscripción #%d, estado Woo "%s") para %s → EvoCampus status=%d%s',
			$reason,
			$subscription->get_id(),
			$subscription->get_status(),
			$email,
			$target_status,
			$this->is_dryrun() ? ' [DRY-RUN]' : ''
		) );

		$enrollments = $this->get_enrollments_by_email( $email );
		if ( is_wp_error( $enrollments ) ) {
			$this->log( 'error', "No se pudieron obtener matrículas de {$email}: " . $enrollments->get_error_message() );
			$this->notify_ghl( $reason . '_error', $email, $subscription, array( 'error' => $enrollments->get_error_message() ) );
			return;
		}
		if ( empty( $enrollments ) ) {
			$this->log( 'warning', "Sin matrículas en EvoCampus para {$email}; nada que actualizar." );
			return;
		}

		$updated = array();
		foreach ( $enrollments as $enrollment ) {
			$eid = $this->enrollment_id( $enrollment );
			if ( ! $eid ) {
				$this->log( 'warning', 'Matrícula sin id reconocible: ' . wp_json_encode( $enrollment ) );
				continue;
			}
			$result = $this->update_enrollment_status( $eid, $target_status );
			if ( is_wp_error( $result ) ) {
				$this->log( 'error', "updateEnrollment {$eid} → status={$target_status} FALLÓ: " . $result->get_error_message() );
			} else {
				$updated[] = $eid;
			}
		}

		$this->log( 'info', sprintf(
			'%s completada para %s: %d/%d matrículas actualizadas%s.',
			ucfirst( $reason ), $email, count( $updated ), count( $enrollments ),
			$this->is_dryrun() ? ' [DRY-RUN: no se escribió nada]' : ''
		) );

		$this->notify_ghl( $reason, $email, $subscription, array( 'enrollments' => $updated ) );
	}

	/* ---------------------------------------------------------------------
	 * Conciliación diaria: estado Woo (fuente de verdad del pago)
	 * vs estado de matrícula en EvoCampus.
	 * ------------------------------------------------------------------- */

	public function run_reconciliation() {
		if ( ! function_exists( 'wcs_get_subscriptions' ) ) {
			$this->log( 'error', 'WooCommerce Subscriptions no está activo; conciliación abortada.' );
			return;
		}

		$this->log( 'info', 'Conciliación diaria: inicio' . ( $this->is_dryrun() ? ' [DRY-RUN]' : '' ) );

		$batches = array(
			// estado Woo => estado EvoCampus esperado
			array( array( 'active' ), self::EVO_ACTIVA, 'reactivacion' ),
			array( array( 'on-hold', 'cancelled', 'expired' ), self::EVO_BAJA, 'baja' ),
		);

		foreach ( $batches as list( $woo_statuses, $target, $reason ) ) {
			$subscriptions = wcs_get_subscriptions( array(
				'subscription_status' => $woo_statuses,
				'subscriptions_per_page' => -1,
			) );
			foreach ( $subscriptions as $subscription ) {
				$email = $subscription->get_billing_email();
				if ( empty( $email ) ) {
					continue;
				}
				$enrollments = $this->get_enrollments_by_email( $email );
				if ( is_wp_error( $enrollments ) || empty( $enrollments ) ) {
					continue;
				}
				foreach ( $enrollments as $enrollment ) {
					$eid     = $this->enrollment_id( $enrollment );
					$current = $this->enrollment_status( $enrollment );
					if ( ! $eid || null === $current || (int) $current === (int) $target ) {
						continue;
					}
					$this->log( 'warning', sprintf(
						'DESAJUSTE: %s matrícula %s está en status=%s, debería ser %d (Woo: %s). Corrigiendo (%s)…',
						$email, $eid, $current, $target, $subscription->get_status(), $reason
					) );
					$this->update_enrollment_status( $eid, $target );
				}
			}
		}

		$this->log( 'info', 'Conciliación diaria: fin' );
	}

	/* ---------------------------------------------------------------------
	 * Cliente API EvoCampus
	 * ------------------------------------------------------------------- */

	private function get_token() {
		$token = get_transient( self::TOKEN_TRANSIENT );
		if ( $token ) {
			return $token;
		}

		$response = wp_remote_post( self::API_BASE . '/v1/token', array(
			'headers' => array( 'Content-Type' => 'application/json' ),
			'body'    => wp_json_encode( array(
				'clientid' => OMNIA_EVO_CLIENTID,
				'key'      => OMNIA_EVO_KEY,
			) ),
			'timeout' => 20,
		) );

		$data = $this->parse_response( $response, 'token' );
		if ( is_wp_error( $data ) ) {
			return $data;
		}

		// Campo del token: confirmar nombre exacto contra la doc oficial.
		$token = $data['token'] ?? $data['access_token'] ?? $data['jwt'] ?? null;
		if ( ! $token ) {
			return new WP_Error( 'evo_token', 'Respuesta de /token sin campo de token reconocible: ' . wp_json_encode( $data ) );
		}

		set_transient( self::TOKEN_TRANSIENT, $token, self::TOKEN_TTL );
		return $token;
	}

	private function api_post( $endpoint, array $payload, $retry_on_401 = true ) {
		$token = $this->get_token();
		if ( is_wp_error( $token ) ) {
			return $token;
		}

		$response = wp_remote_post( self::API_BASE . '/v1/' . ltrim( $endpoint, '/' ), array(
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $token,
			),
			'body'    => wp_json_encode( $payload ),
			'timeout' => 20,
		) );

		// Token caducado → renovar una vez y reintentar.
		if ( $retry_on_401 && ! is_wp_error( $response ) && 401 === wp_remote_retrieve_response_code( $response ) ) {
			delete_transient( self::TOKEN_TRANSIENT );
			return $this->api_post( $endpoint, $payload, false );
		}

		return $this->parse_response( $response, $endpoint );
	}

	private function parse_response( $response, $context ) {
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );
		if ( $code < 200 || $code >= 300 ) {
			return new WP_Error( 'evo_http', "HTTP {$code} en {$context}: " . substr( $body, 0, 500 ) );
		}
		$data = json_decode( $body, true );
		if ( null === $data && 'null' !== trim( $body ) ) {
			return new WP_Error( 'evo_json', "Respuesta no-JSON en {$context}: " . substr( $body, 0, 500 ) );
		}
		return $data;
	}

	private function get_enrollments_by_email( $email ) {
		// Nombre del filtro (email/user) a confirmar contra la doc oficial.
		$data = $this->api_post( 'getEnrollments', array( 'email' => $email ) );
		if ( is_wp_error( $data ) ) {
			return $data;
		}
		// La lista puede venir en raíz o envuelta; se aceptan ambas formas.
		if ( isset( $data['enrollments'] ) && is_array( $data['enrollments'] ) ) {
			return $data['enrollments'];
		}
		return is_array( $data ) ? $data : array();
	}

	private function update_enrollment_status( $enrollment_id, $status ) {
		if ( $this->is_dryrun() ) {
			$this->log( 'info', "[DRY-RUN] updateEnrollment id={$enrollment_id} status={$status} (no enviado)" );
			return true;
		}
		return $this->api_post( 'updateEnrollment', array(
			'enrollmentid' => $enrollment_id,
			'status'       => (int) $status,
		) );
	}

	private function enrollment_id( $enrollment ) {
		return $enrollment['enrollmentid'] ?? $enrollment['id'] ?? $enrollment['enrollment_id'] ?? null;
	}

	private function enrollment_status( $enrollment ) {
		return $enrollment['status'] ?? null;
	}

	/* ---------------------------------------------------------------------
	 * Espejo hacia GoHighLevel (opcional): tag de estado + dunning.
	 * ------------------------------------------------------------------- */

	private function notify_ghl( $event, $email, $subscription, array $extra = array() ) {
		if ( ! defined( 'OMNIA_GHL_WEBHOOK_URL' ) || empty( OMNIA_GHL_WEBHOOK_URL ) ) {
			return;
		}
		wp_remote_post( OMNIA_GHL_WEBHOOK_URL, array(
			'headers'  => array( 'Content-Type' => 'application/json' ),
			'body'     => wp_json_encode( array_merge( array(
				'event'           => $event, // baja | reactivacion | *_error
				'email'           => $email,
				'first_name'      => $subscription->get_billing_first_name(),
				'last_name'       => $subscription->get_billing_last_name(),
				'phone'           => $subscription->get_billing_phone(),
				'subscription_id' => $subscription->get_id(),
				'woo_status'      => $subscription->get_status(),
				'dryrun'          => $this->is_dryrun(),
				'timestamp'       => current_time( 'mysql', true ),
			), $extra ) ),
			'timeout'  => 10,
			'blocking' => false, // no frenar el hook por el CRM
		) );
	}

	/* ---------------------------------------------------------------------
	 * Utilidades
	 * ------------------------------------------------------------------- */

	private function is_dryrun() {
		// Seguro por defecto: si la constante no existe, NO se escribe nada.
		return ! defined( 'OMNIA_EVO_DRYRUN' ) || (bool) OMNIA_EVO_DRYRUN;
	}

	public function admin_notices() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! defined( 'OMNIA_EVO_CLIENTID' ) || ! defined( 'OMNIA_EVO_KEY' ) ) {
			echo '<div class="notice notice-error"><p><strong>Omnia EvoCampus Sync:</strong> faltan <code>OMNIA_EVO_CLIENTID</code> / <code>OMNIA_EVO_KEY</code> en <code>wp-config.php</code>. El plugin no hará nada.</p></div>';
		} elseif ( $this->is_dryrun() ) {
			echo '<div class="notice notice-warning"><p><strong>Omnia EvoCampus Sync:</strong> modo <strong>DRY-RUN</strong> activo — se loguea todo pero no se modifica ninguna matrícula. Definir <code>OMNIA_EVO_DRYRUN</code> como <code>false</code> para activar la sincronización real.</p></div>';
		}
	}

	private function log( $level, $message ) {
		if ( function_exists( 'wc_get_logger' ) ) {
			wc_get_logger()->log( $level, $message, array( 'source' => self::LOG_SOURCE ) );
		} else {
			error_log( '[' . self::LOG_SOURCE . "] {$level}: {$message}" );
		}
	}
}

add_action( 'plugins_loaded', function () {
	if ( defined( 'OMNIA_EVO_CLIENTID' ) && defined( 'OMNIA_EVO_KEY' ) ) {
		Omnia_EvoCampus_Sync::instance();
	}
} );
