<?php
/**
 * STIKALO ZA PAKETOMATE (Balíkovna) — CZ
 * =====================================
 *
 * Spremeni **samo vrednost spodaj**. Nic drugega ni treba.
 *
 *   false                         izklopljeno povsod
 *   true                          vklopljeno povsod
 *   array( 'devcz.noriks.com' )   vklopljeno samo na nastetih domenah
 *
 * Ko je izklopljeno, blagajna dela natanko tako kot pred nadgradnjo:
 * ni kartic za izbiro nacina, ni izbirnika prevzemnih mest, nacin dostave
 * "Balíkovna" se sploh ne registrira v WooCommerce, ne nalozi se slog ne
 * skripta in nobeno preverjanje naslova se ne spremeni.
 *
 * Za vklop na produkciji: dodaj 'noriks.com' na seznam ali nastavi true.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$noriks_balikovna_stikalo = false;

/** Ali so paketomati vklopljeni na tej strani? */
function noriks_balikovna_vklopljena() {
	global $noriks_balikovna_stikalo;
	$s = $noriks_balikovna_stikalo;

	if ( is_bool( $s ) ) { return $s; }
	if ( ! is_array( $s ) ) { return false; }

	$gostitelj = '';
	if ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
		$gostitelj = strtolower( (string) $_SERVER['HTTP_HOST'] );
	} elseif ( function_exists( 'home_url' ) ) {
		$gostitelj = strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) );
	}
	$gostitelj = preg_replace( '/^www\./', '', $gostitelj );

	foreach ( $s as $d ) {
		if ( preg_replace( '/^www\./', '', strtolower( (string) $d ) ) === $gostitelj ) { return true; }
	}
	return false;
}
