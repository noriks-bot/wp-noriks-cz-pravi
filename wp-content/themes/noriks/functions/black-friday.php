<?php
/**
 * NORIKS — Black Friday pristajalna stran (/black-friday/).
 *
 * Po vzoru fitocanin.com/pages/black-friday-2026: temen hero, tekoci trak, kartice
 * izdelkov z redno ceno, BF popustom in koncno ceno. Cen NE spreminjamo — stran
 * samo prikaze danasnjo redno in akcijsko ceno iz WooCommerca.
 *
 * Izbor: 25 novih izdelkov (brez majic in bokseric), razvrscenih po prodaji tega
 * trga (total_sales). Prikaze se le tisto, kar je objavljeno, na zalogi in ima
 * akcijsko ceno. Seznam je v noriks_bf_skus().
 *
 * Stran se ustvari enkrat iz teme (slug black-friday, predloga page-black-friday.php).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Besedila tega trga. {P} = najvecji popust, {D} = prag brezplacne dostave. */
function noriks_bf_l() {
    return array(
        'pct'        => '%d %%',
        'h1'         => 'NABÍDKY AŽ O {P} LEVNĚJI!',
        'sub'        => 'Naše nejprodávanější produkty za akční ceny – na jednom místě.',
        'gumb_hero'  => 'ZOBRAZIT NABÍDKY',
        'trak_p'     => 'NABÍDKY AŽ O {P} LEVNĚJI',
        'trak_d'     => 'DOPRAVA ZDARMA NAD {D}',
        'trak_30'    => '30 DNÍ NA VRÁCENÍ PENĚZ',
        'sek_k'      => 'BLACK FRIDAY · NEJPRODÁVANĚJŠÍ',
        'sek_a'      => 'NEJLEPŠÍ NABÍDKY',
        'sek_b'      => 'PRO VÁS',
        'sek_p'      => 'Vyberte si svůj produkt a využijte Black Friday cenu.',
        'redna'      => 'Běžná cena:',
        'popust'     => 'BF sleva:',
        'placilo'    => 'K úhradě:',
        'gumb'       => 'OBJEDNAT NYNÍ',
        'd_free'     => 'Doprava zdarma',
        'd_nad'      => 'Doprava zdarma nad {D}',
    );
}

/** SKU-ji novih izdelkov (enaki na vseh trgih). */
function noriks_bf_skus() {
    return array( 'NORIKS-KOMPSFIT', 'NORIKS-KNEEFIX', 'NORIKS-KIDSNEST', 'NORIKS-ORTOPAS', 'NORIKS-KOMZIPS', 'NORIKS-FISIOREST', 'NORIKS-CLOATH', 'NORIKS-BUNION', 'NORIKS-CONTROLPRO', 'NORIKS-PAL', 'NORIKS-SLIM', 'NORIKS-RED', 'NORIKS-ERGOSIT', 'NORIKS-SNUG', 'NORIKS-CLOUD', 'NORIKS-KNEEHEAT', 'NORIKS-SLABPROOF', 'NORIKS-SLABPROOF-2', 'NORIKS-HYD', 'NORIKS-BOWL', 'NORIKS-SLIM-2', 'NORIKS-WINECOOLER', 'NORIKS-KOMPWOM', 'NORIKS-PREGNANCY', 'NORIKS-NORIKSHERS' );
}

/** Prag brezplacne dostave iz dostavnih con (0 = ni brezplacne dostave). */
function noriks_bf_prag() {
    $v = get_transient( 'noriks_bf_prag' );
    if ( $v !== false ) { return (float) $v; }
    $v = 0;
    if ( class_exists( 'WC_Shipping_Zones' ) ) {
        foreach ( WC_Shipping_Zones::get_zones() as $cona ) {
            foreach ( $cona['shipping_methods'] as $m ) {
                if ( $m->id === 'free_shipping' && $m->enabled === 'yes' ) {
                    $min = (float) str_replace( ',', '.', (string) $m->get_option( 'min_amount' ) );
                    if ( $min > 0 && ( $v === 0 || $min < $v ) ) { $v = $min; }
                }
            }
        }
    }
    set_transient( 'noriks_bf_prag', $v, 6 * HOUR_IN_SECONDS );
    return (float) $v;
}

/** Izdelki za stran: objavljeni, na zalogi, z akcijsko ceno; po prodaji padajoce, najvec 30. */
function noriks_bf_izdelki() {
    $ven = array();
    if ( ! function_exists( 'wc_get_product_id_by_sku' ) ) { return $ven; }
    $videni = array();
    foreach ( noriks_bf_skus() as $i => $sku ) {
        $id = wc_get_product_id_by_sku( $sku );
        if ( ! $id ) { continue; }
        $p = wc_get_product( $id );
        if ( $p && $p->is_type( 'variation' ) ) { $p = wc_get_product( $p->get_parent_id() ); }
        if ( ! $p || $p->get_status() !== 'publish' || isset( $videni[ $p->get_id() ] ) ) { continue; }
        if ( ! $p->is_in_stock() ) { continue; }
        if ( $p->is_type( 'variable' ) ) {
            $red = (float) $p->get_variation_regular_price( 'min', true );
            $akc = (float) $p->get_variation_price( 'min', true );
        } else {
            if ( $p->get_regular_price() === '' || $p->get_price() === '' ) { continue; }
            $red = (float) wc_get_price_to_display( $p, array( 'price' => $p->get_regular_price() ) );
            $akc = (float) wc_get_price_to_display( $p, array( 'price' => $p->get_price() ) );
        }
        if ( $red <= 0 || $akc <= 0 || $akc >= $red ) { continue; }
        $videni[ $p->get_id() ] = true;
        $ven[] = array(
            'p'       => $p,
            'red'     => $red,
            'akc'     => $akc,
            'pop'     => (int) round( ( 1 - $akc / $red ) * 100 ),
            'prodaja' => (int) $p->get_total_sales(),
            'vrsta'   => $i,
        );
    }
    usort( $ven, function ( $a, $b ) {
        if ( $a['prodaja'] !== $b['prodaja'] ) { return $b['prodaja'] <=> $a['prodaja']; }
        return $a['vrsta'] <=> $b['vrsta'];
    } );
    return array_slice( $ven, 0, 30 );
}

/** Enkrat ustvari pravo WP stran in ji dodeli predlogo page-black-friday.php. */
function noriks_bf_ensure_page() {
    $opt = 'noriks_bf_page_id';
    $id  = (int) get_option( $opt );
    if ( $id && get_post_status( $id ) ) { return; }
    $obst = get_page_by_path( 'black-friday' );
    if ( $obst ) {
        update_post_meta( $obst->ID, '_wp_page_template', 'page-black-friday.php' );
        update_option( $opt, $obst->ID );
        return;
    }
    $id = wp_insert_post( array(
        'post_type'    => 'page',
        'post_status'  => 'publish',
        'post_title'   => 'Black Friday',
        'post_name'    => 'black-friday',
        'post_content' => '',
        'meta_input'   => array( '_wp_page_template' => 'page-black-friday.php' ),
    ) );
    if ( $id && ! is_wp_error( $id ) ) { update_option( $opt, $id ); }
}
add_action( 'init', 'noriks_bf_ensure_page' );
