<?php
/**
 * Plugin Name: NORIKS – Klaviyo obnova košarice za Cloudflarom
 * Description: Klaviyo vtičnik (wck_rebuild_cart) obnovi košarico samo, če trenutni URL zazna kot https.
 *              Za Cloudflarom strežnik vidi http, zato se obnova iz maila nikoli ne sproži. Velja SAMO za
 *              zahteve s parametrom ?wck_rebuild_cart= — ostala stran ostane nespremenjena.
 */

defined( 'ABSPATH' ) || exit;

if ( ! empty( $_GET['wck_rebuild_cart'] ) && empty( $_SERVER['HTTPS'] ) ) {
	$noriks_proto = isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ? strtolower( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) : '';
	$noriks_cf    = isset( $_SERVER['HTTP_CF_VISITOR'] ) ? $_SERVER['HTTP_CF_VISITOR'] : '';
	if ( 'https' === $noriks_proto || false !== strpos( $noriks_cf, '"https"' ) ) {
		$_SERVER['HTTPS'] = 'on';
	}
}
