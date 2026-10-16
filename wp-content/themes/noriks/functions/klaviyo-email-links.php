<?php
/**
 * Povezave iz Klaviyo mailov
 *
 * 1) Obnova košarice (?wck_rebuild_cart=): Klaviyo vtičnik obnovi košarico le, če trenutni URL zazna kot
 *    https. Za Cloudflarom $_SERVER['HTTPS'] ni nastavljen, zato se obnova nikoli ne sproži. Nastavimo ga
 *    samo za take zahteve (koda teče ob nalaganju teme, pred Klaviyo kljuko na wp_loaded).
 * 2) Samodejni kupon (?coupon=KODA): koda se shrani v sejo in uveljavi, ko je v košarici vsaj en izdelek
 *    (takoj, če košarica ni prazna; sicer ob prvem dodanem izdelku).
 */

defined( 'ABSPATH' ) || exit;

if ( ! empty( $_GET['wck_rebuild_cart'] ) && empty( $_SERVER['HTTPS'] ) ) {
	$noriks_proto = isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ? strtolower( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) : '';
	$noriks_cf    = isset( $_SERVER['HTTP_CF_VISITOR'] ) ? $_SERVER['HTTP_CF_VISITOR'] : '';
	if ( 'https' === $noriks_proto || false !== strpos( $noriks_cf, '"https"' ) ) {
		$_SERVER['HTTPS'] = 'on';
	}
}

add_action( 'wp_loaded', 'noriks_email_coupon_capture', 20 ); // po Klaviyo obnovi košarice (prioriteta 10)
function noriks_email_coupon_capture() {
	if ( is_admin() || wp_doing_ajax() || empty( $_GET['coupon'] ) || ! function_exists( 'WC' ) || ! WC()->session || ! WC()->cart ) {
		return;
	}
	$code = wc_format_coupon_code( wp_unslash( $_GET['coupon'] ) );
	if ( '' === $code ) {
		return;
	}
	if ( ! WC()->session->has_session() ) {
		WC()->session->set_customer_session_cookie( true );
	}
	WC()->session->set( 'noriks_email_coupon', $code );
	noriks_email_coupon_apply();
}

add_action( 'woocommerce_add_to_cart', 'noriks_email_coupon_apply', 20 );
add_action( 'woocommerce_before_cart', 'noriks_email_coupon_apply', 5 );
add_action( 'woocommerce_before_checkout_form', 'noriks_email_coupon_apply', 5 );
function noriks_email_coupon_apply() {
	if ( ! function_exists( 'WC' ) || ! WC()->session || ! WC()->cart ) {
		return;
	}
	$code = WC()->session->get( 'noriks_email_coupon' );
	if ( ! $code || WC()->cart->is_empty() ) {
		return;
	}
	WC()->session->set( 'noriks_email_coupon', null );
	if ( ! WC()->cart->has_discount( $code ) ) {
		WC()->cart->apply_coupon( $code );
	}
}
