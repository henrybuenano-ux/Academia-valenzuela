<?php
/**
 * Plugin Name: EvoCampus ↔ WooCommerce Subscriptions Sync (Omnia)
 * Description: Da de baja / reactiva automáticamente las matrículas en EvoCampus según el estado de las suscripciones de WooCommerce. Complementa al conector oficial de Evolmind (que solo gestiona el alta).
 * Version:     0.1.0  (scaffold de arranque — validar en staging)
 * Author:      Omnia
 * Requires Plugins: woocommerce, woocommerce-subscriptions
 *
 * ─────────────────────────────────────────────────────────────────────────────
 *  CÓMO FUNCIONA
 *  1) Escucha los cambios de estado de WooCommerce Subscriptions (hooks).
 *  2) Pide un token JWT a la API de EvoCampus (cacheado ~50 min).
 *  3) Resuelve el/los enrollmentid del alumno por su email (getEnrollments).
 *  4) Llama a updateEnrollment con status=2 (baja) o status=0 (activa).
 *  + Cron diario de conciliación como red de seguridad.
 * ─────────────────────────────────────────────────────────────────────────────
 *  Estados de matrícula EvoCampus:  0=activa · 1=archivada · 2=baja · 3=solo lectura
 * ─────────────────────────────────────────────────────────────────────────────
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * MODO DE PRUEBA (DRY-RUN).
 * true  = NO cambia nada en EvoCampus; solo escribe en los logs lo que HARÍA. (Empezar siempre así.)
 * false = actúa de verdad (da de baja / reactiva).
 * Se puede sobreescribir desde wp-config.php con: define('OMNIA_EVO_DRYRUN', false);
 */
if ( ! defined( 'OMNIA_EVO_DRYRUN' ) ) {
	define( 'OMNIA_EVO_DRYRUN', true );
}

class Omnia_EvoCampus_Sync {

	const API_BASE         = 'https://api.evolcampus.com/api/v1';
	const TOKEN_TRANSIENT  = 'omnia_evo_token';
	const LOG_SOURCE       = 'omnia-evocampus-sync';

	/** Bootstrap */
	public static function init() {
		// --- Hooks de estado de la suscripción --------------------------------
		// Baja: impago (tras reintentos), cancelación y expiración.
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
	}

	/**
	 * Credenciales de la API. Orden de preferencia:
	 *   1) Constantes en wp-config.php:  OMNIA_EVO_CLIENTID / OMNIA_EVO_KEY   ← lo más simple
	 *   2) Option del conector de Evolmind (confirmar nombre real)
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
	private static function token() {
		$cached = get_transient( self::TOKEN_TRANSIENT );
		if ( $cached ) { return $cached; }

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
		}
		return $token;
	}

	/** Llamada genérica POST autenticada a la API. */
	private static function api( $endpoint, $params ) {
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
	 */
	private static function set_status_for_email( $email, $status ) {
		if ( empty( $email ) ) { return; }

		// Para baja (2) buscamos las activas; para activar (0) buscamos las que están en baja.
		$want_active = ( 0 === (int) $status ) ? false : true;
		$ids = self::enrollment_ids( $email, $want_active );

		foreach ( $ids as $eid ) {
			if ( OMNIA_EVO_DRYRUN ) {
				self::log( sprintf( '[DRY-RUN] %s → pondría matrícula #%d en status=%d', $email, $eid, $status ), 'info' );
				continue;
			}
			$res = self::api( 'updateEnrollment', array(
				'enrollmentid' => $eid,
				'status'       => (int) $status,
			) );
			$ok = ( $res && isset( $res['result'] ) && (int) $res['result'] === 1 );
			self::log( sprintf(
				'updateEnrollment #%d → status=%d : %s',
				$eid, $status, $ok ? 'OK' : wp_json_encode( $res )
			), $ok ? 'info' : 'error' );
		}
	}

	/** Handler de baja. */
	public static function on_revoke( $subscription ) {
		$email = self::email_from( $subscription );
		self::set_status_for_email( $email, 2 ); // 2 = Baja
	}

	/** Handler de reactivación. */
	public static function on_activate( $subscription ) {
		$email = self::email_from( $subscription );
		self::set_status_for_email( $email, 0 ); // 0 = Activa
	}

	/** Extrae el email del objeto/ID de suscripción. */
	private static function email_from( $subscription ) {
		if ( is_numeric( $subscription ) && function_exists( 'wcs_get_subscription' ) ) {
			$subscription = wcs_get_subscription( $subscription );
		}
		return ( $subscription && is_a( $subscription, 'WC_Subscription' ) )
			? $subscription->get_billing_email()
			: '';
	}

	/**
	 * Conciliación diaria: recorre las suscripciones y asegura coherencia con EvoCampus.
	 * Activas → matrícula status=0 ; impago/cancelada/expirada → status=2.
	 */
	public static function reconcile() {
		if ( ! function_exists( 'wcs_get_subscriptions' ) ) { return; }

		$revoke_states = array( 'on-hold', 'cancelled', 'expired' );
		$page = 1;
		do {
			$subs = wcs_get_subscriptions( array(
				'subscriptions_per_page' => 50,
				'paged'                  => $page,
			) );
			foreach ( $subs as $sub ) {
				$email  = $sub->get_billing_email();
				$status = $sub->get_status();
				if ( 'active' === $status ) {
					self::set_status_for_email( $email, 0 );
				} elseif ( in_array( $status, $revoke_states, true ) ) {
					self::set_status_for_email( $email, 2 );
				}
			}
			$page++;
		} while ( ! empty( $subs ) && count( $subs ) === 50 );

		self::log( 'Conciliación diaria completada.', 'info' );
	}

	/** Log en WooCommerce → Estado → Registros (source: omnia-evocampus-sync). */
	private static function log( $message, $level = 'info' ) {
		if ( function_exists( 'wc_get_logger' ) ) {
			wc_get_logger()->log( $level, $message, array( 'source' => self::LOG_SOURCE ) );
		}
	}
}

add_action( 'plugins_loaded', array( 'Omnia_EvoCampus_Sync', 'init' ) );

/** Limpieza del cron al desactivar. */
register_deactivation_hook( __FILE__, function () {
	$ts = wp_next_scheduled( 'omnia_evo_reconcile' );
	if ( $ts ) { wp_unschedule_event( $ts, 'omnia_evo_reconcile' ); }
} );
