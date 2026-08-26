<?php
/**
 * Batería de pruebas de Omnia_EvoCampus_Sync::verdict_for().
 *
 * PHP puro, sin WordPress: se ejecuta con  php wp-plugin/tests/test-verdict.php
 *
 * Por qué existe: hasta v0.8.0 el veredicto vivía dentro del bucle de
 * reconcile() y solo se podía comprobar ejecutando la conciliación entera
 * contra un WordPress real. El fallo del 26-ago-2026 (prueba gratuita +
 * cobros sincronizados al día 1 → hasta 60 días entre pagos legítimos) se
 * habría visto aquí en un segundo.
 */

define( 'DAY_IN_SECONDS', 86400 );

// Se carga solo el cuerpo de la función pura, sin arrancar el plugin.
$src = file_get_contents( __DIR__ . '/../evocampus-subscription-sync/evocampus-subscription-sync.php' );
$ini = strpos( $src, 'public static function verdict_for(' );
$fin = strpos( $src, 'private static function next_payment_timestamp(' );
if ( false === $ini || false === $fin ) {
	fwrite( STDERR, "No se encuentra verdict_for() en el plugin.\n" );
	exit( 2 );
}
eval( 'class Omnia_EvoCampus_Sync { ' . substr( $src, $ini, $fin - $ini ) . ' }' );

const GRACE    = 38;
const COURTESY = 7;

$d = function ( $n ) { return $n * DAY_IN_SECONDS; };

/**
 * Cada caso lleva SU PROPIA fecha de evaluación. Es deliberado: el fallo que
 * motiva el parche no se manifiesta el día de la compra, sino ~40 días después,
 * cuando la ventana fija de 38 días se agota mientras el cobro previsto sigue
 * en el futuro. Con un único "ahora" fijo el caso pasaba en verde por el motivo
 * equivocado.
 *
 * [ descripción, ahora, último pago, próximo cobro, esperado ]
 */
$casos = array(

	// === El fallo que motiva el parche, EN LA FECHA EN QUE MUERDE ===========
	array(
		'Compró el 2-sep (prueba + cobro sincronizado al 1-nov), evaluado el 11-oct',
		mktime( 12, 0, 0, 10, 11, 2026 ),   // 39 días desde el pago
		mktime( 12, 0, 0, 9,   2, 2026 ),
		mktime( 12, 0, 0, 11,  1, 2026 ),
		'activo',
	),
	array(
		'El mismo alumno, evaluado el 31-oct (59 días desde su pago)',
		mktime( 12, 0, 0, 10, 31, 2026 ),
		mktime( 12, 0, 0, 9,   2, 2026 ),
		mktime( 12, 0, 0, 11,  1, 2026 ),
		'activo',
	),
	array(
		'El mismo alumno, si el cobro del 1-nov falla: evaluado el 10-nov',
		mktime( 12, 0, 0, 11, 10, 2026 ),   // 9 días pasada la fecha prevista
		mktime( 12, 0, 0, 9,   2, 2026 ),
		mktime( 12, 0, 0, 11,  1, 2026 ),
		'baja',
	),
	array(
		'Compró el 26-ago (cobro al 1-oct), evaluado el 5-oct',
		mktime( 12, 0, 0, 10, 5, 2026 ),
		mktime( 12, 0, 0, 8, 26, 2026 ),
		mktime( 12, 0, 0, 10, 1, 2026 ),
		'activo',
	),
);

// El resto de casos comparten una fecha de referencia fija.
$now = mktime( 12, 0, 0, 9, 20, 2026 );
foreach ( array(
	// === Cortesía A6 sobre la fecha prevista ===============================
	array( 'Cobro previsto vencido hace 3 días (dentro de cortesía)',       $now - $d( 33 ), $now - $d( 3 ),  'activo' ),
	array( 'Cobro previsto vencido hace exactamente 7 días (límite)',       $now - $d( 37 ), $now - $d( 7 ),  'activo' ),
	array( 'Cobro previsto vencido hace 8 días (cortesía agotada)',         $now - $d( 38 ), $now - $d( 8 ),  'baja' ),

	// === Alumno normal al corriente ========================================
	array( 'Pagó hace 5 días, próximo cobro dentro de 25',                  $now - $d( 5 ),  $now + $d( 25 ), 'activo' ),
	array( 'Próximo cobro justo hoy',                                      $now - $d( 30 ), $now,            'activo' ),

	// === Rama de reserva: sin fecha de próximo cobro =======================
	array( 'Sin próximo cobro, pagó hace 20 días',                         $now - $d( 20 ), 0, 'activo' ),
	array( 'Sin próximo cobro, pagó hace exactamente 38 días (límite)',     $now - $d( 38 ), 0, 'activo' ),
	array( 'Sin próximo cobro, pagó hace 39 días',                         $now - $d( 39 ), 0, 'baja' ),
	array( 'Las 39 en espera desde junio: sin cobro previsto, pagó hace 90 d', $now - $d( 90 ), 0, 'baja' ),
	array( 'Sin próximo cobro y sin ningún pedido pagado',                 null, 0, 'baja' ),

	// === Bordes ============================================================
	array( 'Nunca pagó pero tiene cobro previsto futuro (alta manual)',     null, $now + $d( 10 ), 'activo' ),
	array( 'Nunca pagó y el cobro previsto venció hace 30 días',           null, $now - $d( 30 ), 'baja' ),
) as $c ) {
	$casos[] = array( $c[0], $now, $c[1], $c[2], $c[3] );
}

$fallos = 0;
$ancho  = 0;
foreach ( $casos as $c ) { $ancho = max( $ancho, mb_strlen( $c[0] ) ); }
$linea = '  ' . str_repeat( '-', $ancho + 34 ) . "\n";

echo "\n  verdict_for()  ·  cortesía " . COURTESY . " d sobre el cobro previsto  ·  reserva " . GRACE . " d\n";
echo $linea;

foreach ( $casos as $c ) {
	list( $desc, $ahora, $last_paid, $next, $esperado ) = $c;
	$r  = Omnia_EvoCampus_Sync::verdict_for( $ahora, $last_paid, $next, GRACE, COURTESY );
	$ok = ( $r['verdict'] === $esperado );
	if ( ! $ok ) { $fallos++; }
	printf(
		"  %s  %s%s  →  %-6s %s\n",
		$ok ? 'ok  ' : 'FALLO',
		$desc,
		str_repeat( ' ', $ancho - mb_strlen( $desc ) ),
		$r['verdict'],
		$ok ? '' : "(esperado: {$esperado})"
	);
}

echo $linea;
printf( "  %d casos · %d fallos\n\n", count( $casos ), $fallos );

// Diferencias reales frente a la regla vieja: es lo que hay que revisar antes
// de subir esto a producción.
echo "  Veredictos que CAMBIAN respecto a v0.8.0 (ventana fija de " . GRACE . " días):\n";
$cambios = 0;
foreach ( $casos as $c ) {
	list( $desc, $ahora, $last_paid, $next ) = $c;
	$dias  = ( null === $last_paid ) ? null : (int) floor( ( $ahora - $last_paid ) / DAY_IN_SECONDS );
	$viejo = ( null !== $dias && $dias <= GRACE ) ? 'activo' : 'baja';
	$nuevo = Omnia_EvoCampus_Sync::verdict_for( $ahora, $last_paid, $next, GRACE, COURTESY )['verdict'];
	if ( $viejo !== $nuevo ) {
		$cambios++;
		printf( "    %s%s  %s → %s\n", $desc, str_repeat( ' ', $ancho - mb_strlen( $desc ) ), $viejo, $nuevo );
	}
}
printf( "    (%d de %d casos cambian)\n\n", $cambios, count( $casos ) );

exit( $fallos > 0 ? 1 : 0 );
