<?php
/**
 * Tabela velikosti se sme izrisati SAMO enkrat na stran.
 *
 * Prej so jo vkljucevala tri mesta (functions.php, price.php, single_product_mods.php),
 * zato so na kompletih nastali podvojeni ID-ji #custom-size-chart-modal in klik na
 * spodnjo povezavo je odprl obe tabeli hkrati.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'noriks_size_chart_once' ) ) {
    function noriks_size_chart_once() {
        static $done = false;
        if ( $done ) { return; }
        $done = true;
        // NORIKS FIT Woman: zenska tablica po opsegu grudi i struka, ne muska po visini i tezini.
        if ( function_exists( 'noriks_is_type' ) && noriks_is_type( 'kompwom' ) ) {
            get_template_part( 'template_parts/size-chart-kompwom' );
            return;
        }
        get_template_part( 'template_parts/size-chart-modal' );
    }
}

if ( ! function_exists( 'noriks_size_chart_secondary_once' ) ) {
    function noriks_size_chart_secondary_once() {
        static $done = false;
        if ( $done ) { return; }
        $done = true;
        get_template_part( 'template_parts/size-chart-secondary' );
    }
}
