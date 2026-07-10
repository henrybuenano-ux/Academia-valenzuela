<?php
/**
 * Plugin Name: EvoCampus ↔ WooCommerce Subscriptions Sync (Omnia)
 * Description: Da de baja / reactiva automáticamente las matrículas en EvoCampus según el estado de las suscripciones de WooCommerce. Complementa al conector oficial de Evolmind (que solo gestiona el alta). Espejo opcional de eventos hacia GoHighLevel.
 * Version:     0.4.0  (detección de impago por PEDIDOS pagados — hallazgo B4-bis)
 * Author:      Omnia
 * Requires Plugins: woocommerce
 *
 * ─────────────────────────────────────────────────────────────────────────────
 *  CÓMO FUNCIONA
 *  Señal PRINCIPAL (conciliación diaria por PEDIDOS): en esta tienda el
 *  estado de la suscripción NO refleja el pago (las 60 viven "en espera"
 *  aunque sus renovaciones se cobren por Redsys). Por eso el veredicto se
 *  calcula por pedidos: último pedido PAGADO (processing/completed) del
 *  alumno hace ≤ OMNIA_EVO_GRACE_DAYS días → acceso activo; si no → baja.
 *  Señal de REFUERZO (hooks): los cambios de estado de la suscripción
 *  siguen escuchándose por si el flujo de estados se corrige en el futuro.
 *  1) Token JWT de la API EvoCampus (cacheado ~50 min).
 *  2) enrollmentid(s) del alumno por email (getEnrollments).
 *  3) updateEnrollment con status=2 (baja) o status=0 (activa).
 *  + (Opcional) Espejo de eventos a Inbound Webhooks de GoHighLevel.
 *  Prueba manual de la conciliación: /wp-admin/?omnia_evo_reconcile_now=1
 * ─────────────────────────────────────────────────────────────────────────────
 *  Estados de matrícula EvoCampus:  0=activa · 1=archivada · 2=baja · 3=solo lectura
 * ─────────────────────────────────────────────────────────────────────────────
 *  Configuración en wp-config.php:
 *    define( 'OMNIA_EVO_CLIENTID', '83208' );
 *    define( 'OMNIA_EVO_KEY',      '...' );
 *    define( 'OMNIA_EVO_DRYRUN',   true );            // empezar SIEMPRE en true
 *    define( 'OMNIA_EVO_GRACE_DAYS', 35 );            // ventana de pago (días)
 *    // Espejo CRM (opcional): un workflow GHL por tipo de evento.
 *    define( 'OMNIA_GHL_WEBHOOK_URL_BAJA',         'https://...' );
 *    define( 'OMNIA_GHL_WEBHOOK_URL_REACTIVACION', 'https://...' );
 *    // (compat: OMNIA_GHL_WEBHOOK_URL como URL única para ambos eventos)
 * ─────────────────────────────────────────────────────────────────────────────
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * MODO DE PRUEBA (DRY-RUN).
 * true  = NO cambia nada en EvoCampus; solo escribe en los logs lo que HARÍA.
 * false = actúa de verdad (da de baja / reactiva).
 */
if ( ! defined( 'OMNIA_EVO_DRYRUN' ) ) {
	define( 'OMNIA_EVO_DRYRUN', true );
}

class Omnia_EvoCampus_Sync {

	const API_BASE        = 'https://api.evolcampus.com/api/v1';
	const TOKEN_TRANSIENT = 'omnia_evo_token';
	const LOG_SOURCE      = 'omnia-evocampus-sync';

	/** Bootstrap */
	public static function init() {
		// --- Hooks de estado de la suscripción --------------------------------
		// Baja: impago (tras reintentos), cancelación y expiración.
		// Nota de negocio: si se pacta periodo de gracia, quitar 'on-hold'.
		add_action( 'woocommerce_subscription_status_on-hold',   array( __CLASS__, 'on_revoke' ), 10, 1 );
		add_action( 'woocommerce_subscription_status_cancelled', array( __CLASS__, 'on_revoke' ), 10, 1 );
		add_action( 'woocommerce_subscription_status_expired',   array( __CLASS__, 'on_revoke' ), 10, 1 );
		// Reactivación: vuelve a estar al corriente de pago.
		add_action( 'woocommerce_subscription_status_active',    array( __CLASS__, 'on_activate' ), 10, 1 );

		// --- Conciliación diaria (red de seguridad) ---------------------------
		add_action( 'omnia_evo_reconcile', array( __CLASS__, 'reconcile' ) );
		if ( ! wp_next_scheduled( 'omnia_evo_reconcile' ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'omnia_evo_reconcile' );
		}

		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );

		// Disparador manual para pruebas: /wp-admin/?omnia_evo_reconcile_now=1
		add_action( 'admin_init', array( __CLASS__, 'maybe_manual_reconcile' ) );
	}

	/** Ejecuta la conciliación bajo demanda (solo administradores). */
	public static function maybe_manual_reconcile() {
		if ( empty( $_GET['omnia_evo_reconcile_now'] ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		self::log( 'Conciliación lanzada MANUALMENTE desde el admin.', 'info' );
		self::reconcile();
		wp_die(
			'Conciliación ejecutada. Revisa el log en WooCommerce → Estado → Registros → fuente <code>omnia-evocampus-sync</code>. <a href="' . esc_url( admin_url() ) . '">Volver al escritorio</a>',
			'Omnia EvoCampus Sync'
		);
	}

	/**
	 * Credenciales de la API. Orden de preferencia:
	 *   1) Constantes en wp-config.php:  OMNIA_EVO_CLIENTID / OMNIA_EVO_KEY   ← lo más simple
	 *   2) Option del conector de Evolmind (confirmar nombre real en staging)
	 *   3) Filtros omnia_evo_clientid / omnia_evo_key
	 */
	private static function creds() {
		$clientid = defined( 'OMNIA_EVO_CLIENTID' ) ? OMNIA_EVO_CLIENTID : get_option( 'evolcampus_clientid', '83208' );
		$key      = defined( 'OMNIA_EVO_KEY' )      ? OMNIA_EVO_KEY      : get_option( 'evolcampus_key', '' );
		$clientid = apply_filters( 'omnia_evo_clientid', $clientid );
		$key      = apply_filters( 'omnia_evo_key', $key );
		return array( $clientid, $key );
	}

	/** Devuelve un token JWT (cacheado en transient para no re-autenticar en cada evento). */
	private static function token( $force = false ) {
		if ( ! $force ) {
			$cached = get_transient( self::TOKEN_TRANSIENT );
			if ( $cached ) { return $cached; }
		}

		list( $clientid, $key ) = self::creds();
		$res = wp_remote_post( self::API_BASE . '/token', array(
			'timeout' => 20,
			'body'    => array( 'clientid' => $clientid, 'key' => $key ),
		) );

		if ( is_wp_error( $res ) ) {
			self::log( 'token error: ' . $res->get_error_message(), 'error' );
			return false;
		}
		$body  = json_decode( wp_remote_retrieve_body( $res ), true );
		$token = isset( $body['token'] ) ? $body['token'] : false;
		if ( $token ) {
			set_transient( self::TOKEN_TRANSIENT, $token, 50 * MINUTE_IN_SECONDS );
		} else {
			self::log( 'token: respuesta sin campo token: ' . substr( wp_remote_retrieve_body( $res ), 0, 300 ), 'error' );
		}
		return $token;
	}

	/** Llamada genérica POST autenticada a la API (reintenta 1 vez si el token caducó). */
	private static function api( $endpoint, $params, $retry = true ) {
		$token = self::token();
		if ( ! $token ) { return false; }

		$res = wp_remote_post( self::API_BASE . '/' . $endpoint, array(
			'timeout' => 20,
			'headers' => array( 'Authorization' => 'Bearer ' . $token ),
			'body'    => $params,
		) );

		if ( is_wp_error( $res ) ) {
			self::log( "$endpoint error: " . $res->get_error_message(), 'error' );
			return false;
		}

		if ( $retry && 401 === wp_remote_retrieve_response_code( $res ) ) {
			delete_transient( self::TOKEN_TRANSIENT );
			return self::api( $endpoint, $params, false );
		}

		return json_decode( wp_remote_retrieve_body( $res ), true );
	}

	/**
	 * Devuelve los enrollmentid de un email según si están activos o no.
	 * @param string $email
	 * @param bool   $active  true = matrículas activas · false = no activas (en baja)
	 * @return int[]
	 */
	private static function enrollment_ids( $email, $active ) {
		$ids  = array();
		$page = 1;
		do {
			$res = self::api( 'getEnrollments', array(
				'email'         => $email,
				'active'        => $active ? 'true' : 'false',
				'page'          => $page,
				'regs_per_page' => 100,
			) );
			if ( ! $res || empty( $res['data'] ) ) { break; }

			foreach ( $res['data'] as $row ) {
				if ( ! empty( $row['person']['enrollmentid'] ) ) {
					$ids[] = (int) $row['person']['enrollmentid'];
				}
			}
			$pages = isset( $res['pages'] ) ? (int) $res['pages'] : 1;
			$page++;
		} while ( $page <= $pages );

		return array_values( array_unique( $ids ) );
	}

	/**
	 * Cambia el estado de TODAS las matrículas del alumno.
	 * Modelo de esta academia: 1 suscripción = acceso a todo → operamos sobre todas.
	 * (Para cortar curso a curso se usaría el mapeo Producto→Grupo del conector.)
	 * @return int[] ids actualizados (o que se actualizarían en DRY-RUN)
	 */
	private static function set_status_for_email( $email, $status ) {
		if ( empty( $email ) ) { return array(); }

		// Para baja (2) buscamos las activas; para activar (0) buscamos las que están en baja.
		$want_active = ( 0 === (int) $status ) ? false : true;
		$ids  = self::enrollment_ids( $email, $want_active );
		$done = array();

		foreach ( $ids as $eid ) {
			if ( OMNIA_EVO_DRYRUN ) {
				self::log( sprintf( '[DRY-RUN] %s → pondría matrícula #%d en status=%d', $email, $eid, $status ), 'info' );
				$done[] = $eid;
				continue;
			}
			$res = self::api( 'updateEnrollment', array(
				'enrollmentid' => $eid,
				'status'       => (int) $status,
			) );
			$ok = ( $res && isset( $res['result'] ) && (int) $res['result'] === 1 );
			if ( $ok ) { $done[] = $eid; }
			self::log( sprintf(
				'updateEnrollment #%d → status=%d : %s',
				$eid, $status, $ok ? 'OK' : wp_json_encode( $res )
			), $ok ? 'info' : 'error' );
		}

		return $done;
	}

	/** Handler de baja. */
	public static function on_revoke( $subscription ) {
		$subscription = self::normalize( $subscription );
		$email        = self::email_from( $subscription );
		$ids          = self::set_status_for_email( $email, 2 ); // 2 = Baja
		self::notify_ghl( 'baja', $email, $subscription, $ids );
	}

	/** Handler de reactivación. */
	public static function on_activate( $subscription ) {
		$subscription = self::normalize( $subscription );
		$email        = self::email_from( $subscription );
		$ids          = self::set_status_for_email( $email, 0 ); // 0 = Activa
		self::notify_ghl( 'reactivacion', $email, $subscription, $ids );
	}

	/** Normaliza ID numérico → objeto WC_Subscription. */
	private static function normalize( $subscription ) {
		if ( is_numeric( $subscription ) && function_exists( 'wcs_get_subscription' ) ) {
			$subscription = wcs_get_subscription( $subscription );
		}
		return $subscription;
	}

	/** Extrae el email del objeto de suscripción. */
	private static function email_from( $subscription ) {
		return ( $subscription && is_a( $subscription, 'WC_Subscription' ) )
			? $subscription->get_billing_email()
			: '';
	}

	/**
	 * Conciliación diaria — SEÑAL PRINCIPAL de esta tienda.
	 *
	 * El estado de la suscripción no refleja el pago (hallazgo 10-jul-2026:
	 * las 60 suscripciones viven "en espera" con renovaciones cobradas por
	 * Redsys), así que el veredicto se calcula por PEDIDOS: si el último
	 * pedido pagado del alumno tiene ≤ GRACE_DAYS días → activo; si no → baja.
	 */
	public static function reconcile() {
		if ( ! function_exists( 'wcs_get_subscriptions' ) ) { return; }

		$grace = defined( 'OMNIA_EVO_GRACE_DAYS' ) ? (int) OMNIA_EVO_GRACE_DAYS : 35;
		self::log( sprintf(
			'Conciliación diaria: inicio (ventana de pago: %d días)%s',
			$grace, OMNIA_EVO_DRYRUN ? ' [DRY-RUN]' : ''
		) );

		// 1. Último pago por alumno (email), mirando TODAS sus suscripciones.
		$students = array();
		$page     = 1;
		do {
			$subs = wcs_get_subscriptions( array(
				'subscriptions_per_page' => 50,
				'paged'                  => $page,
			) );
			foreach ( $subs as $sub ) {
				$email = $sub->get_billing_email();
				if ( empty( $email ) ) { continue; }
				$key  = strtolower( $email );
				$last = self::last_paid_timestamp( $sub );
				if ( ! isset( $students[ $key ] )
					|| ( $last && $last > (int) $students[ $key ]['last_paid'] ) ) {
					$students[ $key ] = array( 'email' => $email, 'last_paid' => $last, 'sub' => $sub );
				}
			}
			$page++;
		} while ( ! empty( $subs ) && count( $subs ) === 50 );

		// 2. Veredicto por alumno + acción idempotente + espejo GHL si cambió.
		$verdicts = get_option( 'omnia_evo_verdicts', array() );
		$now      = time();
		foreach ( $students as $key => $info ) {
			$days = $info['last_paid']
				? (int) floor( ( $now - $info['last_paid'] ) / DAY_IN_SECONDS )
				: null;
			$ok      = ( null !== $days && $days <= $grace );
			$verdict = $ok ? 'activo' : 'baja';

			self::log( sprintf(
				'%s — último pago %s → %s',
				$info['email'],
				null === $days ? 'NUNCA' : "hace {$days} días",
				$verdict
			) );

			self::set_status_for_email( $info['email'], $ok ? 0 : 2 );

			if ( ( $verdicts[ $key ] ?? '' ) !== $verdict ) {
				self::notify_ghl( $ok ? 'reactivacion' : 'baja', $info['email'], $info['sub'] );
				$verdicts[ $key ] = $verdict;
			}
		}

		// En DRY-RUN no se persisten veredictos: el primer pase real
		// notificará a GHL el estado inicial de todos los alumnos.
		if ( ! OMNIA_EVO_DRYRUN ) {
			update_option( 'omnia_evo_verdicts', $verdicts, false );
		}

		self::log( sprintf( 'Conciliación diaria: fin (%d alumnos evaluados)', count( $students ) ) );
	}

	/** Timestamp del último pedido PAGADO (processing/completed) de una suscripción. */
	private static function last_paid_timestamp( $subscription ) {
		$latest = null;
		$ids    = $subscription->get_related_orders( 'ids', array( 'parent', 'renewal' ) );
		foreach ( $ids as $oid ) {
			$order = wc_get_order( $oid );
			if ( ! $order || ! $order->is_paid() ) { continue; }
			$date = $order->get_date_paid();
			if ( ! $date ) { $date = $order->get_date_created(); }
			if ( $date && ( null === $latest || $date->getTimestamp() > $latest ) ) {
				$latest = $date->getTimestamp();
			}
		}
		return $latest;
	}

	/* ---------------------------------------------------------------------
	 * Espejo hacia GoHighLevel (opcional): tag de estado + dunning.
	 * Requiere OMNIA_GHL_WEBHOOK_URL (Inbound Webhook de un workflow GHL).
	 * ------------------------------------------------------------------- */
	private static function notify_ghl( $event, $email, $subscription, array $enrollment_ids = array() ) {
		// URL por evento; OMNIA_GHL_WEBHOOK_URL sirve de fallback común.
		$url = '';
		if ( 'baja' === $event && defined( 'OMNIA_GHL_WEBHOOK_URL_BAJA' ) ) {
			$url = OMNIA_GHL_WEBHOOK_URL_BAJA;
		} elseif ( 'reactivacion' === $event && defined( 'OMNIA_GHL_WEBHOOK_URL_REACTIVACION' ) ) {
			$url = OMNIA_GHL_WEBHOOK_URL_REACTIVACION;
		} elseif ( defined( 'OMNIA_GHL_WEBHOOK_URL' ) ) {
			$url = OMNIA_GHL_WEBHOOK_URL;
		}
		if ( empty( $url ) || empty( $email ) ) {
			return;
		}
		$is_sub = $subscription && is_a( $subscription, 'WC_Subscription' );
		// Bloqueante a propósito: fire-and-forget se pierde en algunos hostings
		// y son ~2 eventos/alumno/mes — la latencia es irrelevante.
		$res = wp_remote_post( $url, array(
			'timeout'  => 10,
			'blocking' => true,
			'headers'  => array( 'Content-Type' => 'application/json' ),
			'body'     => wp_json_encode( array(
				'event'           => $event, // baja | reactivacion
				'email'           => $email,
				'first_name'      => $is_sub ? $subscription->get_billing_first_name() : '',
				'last_name'       => $is_sub ? $subscription->get_billing_last_name() : '',
				'phone'           => $is_sub ? $subscription->get_billing_phone() : '',
				'subscription_id' => $is_sub ? $subscription->get_id() : 0,
				'woo_status'      => $is_sub ? $subscription->get_status() : '',
				'enrollments'     => $enrollment_ids,
				'dryrun'          => (bool) OMNIA_EVO_DRYRUN,
				'timestamp'       => current_time( 'mysql', true ),
			) ),
		) );

		if ( is_wp_error( $res ) ) {
			self::log( "webhook GHL ({$event}) FALLÓ: " . $res->get_error_message(), 'error' );
		} else {
			$code = wp_remote_retrieve_response_code( $res );
			self::log( "webhook GHL ({$event}) para {$email} → HTTP {$code}", ( $code >= 200 && $code < 300 ) ? 'info' : 'error' );
		}
	}

	/** Avisos en el admin: credenciales ausentes o DRY-RUN activo. */
	public static function admin_notices() {
		if ( ! current_user_can( 'manage_options' ) ) { return; }
		list( , $key ) = self::creds();
		if ( empty( $key ) ) {
			echo '<div class="notice notice-error"><p><strong>Omnia EvoCampus Sync:</strong> falta la key de la API (definir <code>OMNIA_EVO_KEY</code> en <code>wp-config.php</code>). El plugin no hará nada.</p></div>';
		} elseif ( OMNIA_EVO_DRYRUN ) {
			echo '<div class="notice notice-warning"><p><strong>Omnia EvoCampus Sync:</strong> modo <strong>DRY-RUN</strong> activo — se loguea todo pero no se modifica ninguna matrícula. Definir <code>OMNIA_EVO_DRYRUN</code> como <code>false</code> en <code>wp-config.php</code> para activar en real.</p></div>';
		}
	}

	/** Log en WooCommerce → Estado → Registros (source: omnia-evocampus-sync). */
	private static function log( $message, $level = 'info' ) {
		if ( function_exists( 'wc_get_logger' ) ) {
			wc_get_logger()->log( $level, $message, array( 'source' => self::LOG_SOURCE ) );
		} else {
			error_log( '[' . self::LOG_SOURCE . "] {$level}: {$message}" );
		}
	}
}

add_action( 'plugins_loaded', array( 'Omnia_EvoCampus_Sync', 'init' ) );

/** Limpieza del cron al desactivar. */
register_deactivation_hook( __FILE__, function () {
	$ts = wp_next_scheduled( 'omnia_evo_reconcile' );
	if ( $ts ) { wp_unschedule_event( $ts, 'omnia_evo_reconcile' ); }
} );
