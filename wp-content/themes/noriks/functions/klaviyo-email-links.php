<?php
/**
 * Povezave iz Klaviyo mailov
 *
 * 1) Obnova košarice (?wck_rebuild_cart=): Klaviyo vtičnik obnovi košarico le, če se URL ujema z wc_get_cart_url();
 *    ta na Noriks trgih vrne domačo stran (stran košarice v WooCommerce ni izbrana), zato obnova nikoli ne steče. Tu košarico obnovimo
 *    sami, PRED Klaviyem, in primerjamo samo pot (/xx/cart/). Nato preusmerimo na čisto košarico (Klaviyo ne teče).
 * 2) Samodejni kupon (?coupon=KODA): koda se shrani v sejo in uveljavi, ko je v košarici vsaj en izdelek.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_loaded', 'noriks_email_cart_rebuild', 5 ); // pred Klaviyo wck_rebuild_cart (prioriteta 10)
function noriks_email_cart_rebuild() {
	if ( is_admin() || wp_doing_ajax() || empty( $_GET['wck_rebuild_cart'] ) || ! function_exists( 'WC' ) || ! WC()->cart || ! WC()->session ) {
		return;
	}
	// V WooCommerce ni izbrane strani košarice (wc_get_cart_url() vrne domačo stran), tema uporablja /xx/cart/.
	$cart_url  = home_url( '/cart/' );
	$wc_cart   = wc_get_cart_url();
	if ( untrailingslashit( $wc_cart ) !== untrailingslashit( home_url() ) ) {
		$cart_url = $wc_cart; // če bo stran košarice kdaj nastavljena, velja ta
	}
	$cart_path = untrailingslashit( (string) wp_parse_url( $cart_url, PHP_URL_PATH ) );
	$req_path  = untrailingslashit( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ) );
	if ( '' === $cart_path || $cart_path !== $req_path ) {
		return;
	}
	$data = json_decode( base64_decode( sanitize_text_field( wp_unslash( $_GET['wck_rebuild_cart'] ) ) ), true );
	if ( ! is_array( $data ) || empty( $data['normal_products'] ) || ! is_array( $data['normal_products'] ) ) {
		return;
	}
	if ( ! WC()->session->has_session() ) {
		WC()->session->set_customer_session_cookie( true );
	}
	if ( ! empty( $_GET['coupon'] ) ) { // kupon najprej v sejo, uveljavi se ob dodajanju izdelkov
		WC()->session->set( 'noriks_email_coupon', wc_format_coupon_code( wp_unslash( $_GET['coupon'] ) ) );
	}
	WC()->cart->empty_cart( true );
	foreach ( $data['normal_products'] as $p ) {
		if ( empty( $p['product_id'] ) ) {
			continue;
		}
		WC()->cart->add_to_cart(
			absint( $p['product_id'] ),
			max( 1, absint( isset( $p['quantity'] ) ? $p['quantity'] : 1 ) ),
			absint( isset( $p['variation_id'] ) ? $p['variation_id'] : 0 ),
			isset( $p['variation'] ) && is_array( $p['variation'] ) ? $p['variation'] : array()
		);
	}
	noriks_email_coupon_apply();
	// UTM iz maila ohrani, da WooCommerce (in dash Origin) naročilo pripiše e-pošti, ne "Direct".
	$utm = array();
	foreach ( array( 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term' ) as $k ) {
		if ( isset( $_GET[ $k ] ) && '' !== $_GET[ $k ] ) {
			$utm[ $k ] = rawurlencode( sanitize_text_field( wp_unslash( $_GET[ $k ] ) ) );
		}
	}
	wp_safe_redirect( $utm ? add_query_arg( $utm, $cart_url ) : $cart_url );
	exit;
}

add_action( 'wp_loaded', 'noriks_email_coupon_capture', 20 );
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
