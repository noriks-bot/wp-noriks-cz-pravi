<?php
/**
 * NORIKS — Black Friday pristajalni strani.
 *
 *   /black-friday/         vsi izdelki: novi izdelki + majice in bokserice (oranzna)
 *   /black-friday-basics/  majice, bokserice in SlabProof nogavice        (zlata)
 *   /black-friday-shirts/  samo majice                                    (modra)
 *   /black-friday-boxers/  samo bokserice                                 (zelena)
 *   /black-friday-top9/    9 najbolj prodajanih izdelkov nasploh          (rdeca)
 *
 * Po vzoru fitocanin.com/pages/black-friday-2026: temen hero, tekoci trak, kartice
 * z redno ceno, BF popustom in koncno ceno. Cen NE spreminjamo — stran prikaze
 * danasnjo redno in akcijsko ceno (pri izdelkih z izbirnikom paketov ceno prve ponudbe).
 *
 * Na kartici kupec izbere velikost (in barvo, kjer jih je vec) ter doda v kosarico
 * neposredno; zahteva je enaka tisti s strani izdelka (bundle_option + pairs[...]),
 * zato vticnik orto-product sam nastavi ceno in vrstice. Po dodajanju se odpre
 * stranska kosarica (dogodek added_to_cart). Pod gumbom je povezava na izdelek.
 *
 * Obe strani se ustvarita enkrat iz teme (predloga page-black-friday.php).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

const NORIKS_BF_NAJVEC = 30;   // najvec kartic v enem razdelku

/** Pet pristajalnih strani: slug => (naslov strani, tip vsebine, slog). */
function noriks_bf_strani() {
    return array(
        'black-friday'        => array( 'naslov' => 'Black Friday',        'tip' => 'vse',       'slog' => 'oranzna' ),
        'black-friday-basics' => array( 'naslov' => 'Black Friday Basics', 'tip' => 'basics',    'slog' => 'zlata' ),
        'black-friday-shirts' => array( 'naslov' => 'Black Friday Shirts', 'tip' => 'majice',    'slog' => 'modra' ),
        'black-friday-boxers' => array( 'naslov' => 'Black Friday Boxers', 'tip' => 'bokserice', 'slog' => 'zelena' ),
        'black-friday-top9'   => array( 'naslov' => 'Black Friday Top 9',  'tip' => 'top9',      'slog' => 'rdeca' ),
    );
}

/** Nastavitve trenutne strani (privzeto: vse). */
function noriks_bf_stran() {
    $s = noriks_bf_strani();
    foreach ( $s as $slug => $c ) { if ( is_page( $slug ) ) { return $c; } }
    return $s['black-friday'];
}

/** Besedila tega trga. {P} = najvecji popust, {D} = prag brezplacne dostave, {A} = ime atributa. */
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
        'sek_p'      => 'Vyberte si velikost a přidejte do košíku přímo z této stránky – nebo si produkt nejdřív prohlédněte.',
        'redna'      => 'Běžná cena:',
        'popust'     => 'BF sleva:',
        'placilo'    => 'K úhradě:',
        'gumb'       => 'OBJEDNAT NYNÍ',
        'd_free'     => 'Doprava zdarma',
        'd_nad'      => 'Doprava zdarma nad {D}',
        'sub2'       => 'Trička, boxerky a pracovní ponožky za akční ceny – na jednom místě.',
        'b_k'        => 'BLACK FRIDAY · TRIČKA A BOXERKY',
        'b_a'        => 'TRIČKA A',
        'b_b'        => 'BOXERKY',
        'dodaj'      => 'PŘIDAT DO KOŠÍKU',
        'poglej'     => 'Zobrazit produkt',
        'dodajam'    => 'PŘIDÁVÁM…',
        'dodano'     => '✓ PŘIDÁNO',
        'izberi'     => 'Vyberte: {A}',
        'napaka'     => 'Přidání se nezdařilo. Zkuste to znovu.',
        'w_maj'      => 'TRIČKA',
        'w_box'      => 'BOXERKY',
        'akcija'     => 'V AKCI',
        'top_b'      => 'NEJPRODÁVANĚJŠÍCH',
        'sub_maj'    => 'Trička za akční ceny – vyberte si velikost a objednejte.',
        'sub_box'    => 'Boxerky za akční ceny – vyberte si velikost a objednejte.',
        'sub_top'    => 'Devět nejprodávanějších produktů za akční ceny.',
        'vec'        => 'Zobrazit více',
        'zapri'      => 'Zavřít',
        'nalagam'    => 'Načítám…',
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

/**
 * Podatki za eno kartico ali null, ce izdelek ni za prikaz.
 * nacin: orto  = izbirnik paketov (posljemo bundle_option + pairs)
 *        var   = variabilen izdelek z enim atributom (posljemo atribut)
 *        simple= navaden izdelek brez izbire
 *        ogled = prevec izbir za kartico -> samo povezava na izdelek
 */
function noriks_bf_kartica( $p ) {
    if ( ! $p || $p->get_status() !== 'publish' || ! $p->is_in_stock() ) { return null; }
    $id = $p->get_id();
    $k  = array( 'p' => $p, 'nacin' => 'ogled', 'attrs' => array(), 'offer' => '', 'kos' => 1, 'paket' => '', 'ponudbe' => array() );
    $red = 0; $akc = 0; $orto = false;

    if ( function_exists( 'gck_is_orto_bundle_enabled' ) && function_exists( 'gck_get_bundle_offers' ) && gck_is_orto_bundle_enabled( $id ) ) {
        $ponudbe = gck_get_bundle_offers( $id );
        if ( ! empty( $ponudbe ) ) {
            $o    = reset( $ponudbe );
            $orto = true;
            $red  = (float) $o['regular'];
            $akc  = (float) $o['total'];
            $k['offer'] = (string) $o['id'];
            $k['kos']   = max( 1, (int) $o['qty'] );
            $k['paket'] = trim( (string) $o['title'] );
            foreach ( $ponudbe as $po ) {
                $pr = (float) $po['regular']; $pa = (float) $po['total'];
                if ( $pr <= 0 || $pa <= 0 || $pa >= $pr ) { continue; }
                $k['ponudbe'][] = array( 'id' => (string) $po['id'], 'kos' => max( 1, (int) $po['qty'] ), 'paket' => trim( (string) $po['title'] ), 'red' => $pr, 'akc' => $pa );
            }
            $lastni = function_exists( 'gck_get_custom_attributes_in_order' ) ? gck_get_custom_attributes_in_order( $p ) : array();
            if ( count( $lastni ) <= 2 && function_exists( 'gck_split_attrs_color_size' ) && ! has_term( 'orto-majica-darila', 'product_cat', $id ) ) {
                $deli = gck_split_attrs_color_size( $lastni );
                foreach ( array( 'colors', 'sizes', 'others' ) as $sk ) {
                    foreach ( (array) $deli[ $sk ] as $a ) {
                        $vr = array();
                        foreach ( (array) $a['values'] as $x ) { $x = trim( (string) $x ); if ( $x !== '' ) { $vr[] = array( $x, $x ); } }
                        if ( $vr ) { $k['attrs'][] = array( 'p' => (string) $a['field_key'], 'ime' => (string) $a['label'], 'v' => $vr ); }
                    }
                }
                $k['nacin'] = 'orto';
            }
        }
    }

    if ( ! $orto ) {
        if ( $p->is_type( 'variable' ) ) {
            $red = (float) $p->get_variation_regular_price( 'min', true );
            $akc = (float) $p->get_variation_price( 'min', true );
            $va  = $p->get_variation_attributes();
            if ( count( $va ) === 1 ) {
                $ime = (string) key( $va );
                $vr  = array();
                foreach ( (array) current( $va ) as $x ) {
                    $x = (string) $x; if ( $x === '' ) { continue; }
                    $napis = $x;
                    if ( taxonomy_exists( $ime ) ) { $t = get_term_by( 'slug', $x, $ime ); if ( $t && ! is_wp_error( $t ) ) { $napis = $t->name; } }
                    $vr[] = array( $x, $napis );
                }
                if ( $vr ) {
                    $k['attrs'][] = array( 'p' => 'attribute_' . sanitize_title( $ime ), 'ime' => wc_attribute_label( $ime, $p ), 'v' => $vr );
                    $k['nacin']   = 'var';
                }
            }
        } else {
            if ( $p->get_regular_price() === '' || $p->get_price() === '' ) { return null; }
            $red = (float) wc_get_price_to_display( $p, array( 'price' => $p->get_regular_price() ) );
            $akc = (float) wc_get_price_to_display( $p, array( 'price' => $p->get_price() ) );
            if ( $p->is_type( 'simple' ) && $p->is_purchasable() ) { $k['nacin'] = 'simple'; }
        }
    }

    if ( $red <= 0 || $akc <= 0 || $akc >= $red ) { return null; }
    $k['red'] = $red; $k['akc'] = $akc;
    $k['pop'] = (int) round( ( 1 - $akc / $red ) * 100 );
    // Kolicine: pri izbirniku paketov prave ponudbe izdelka, sicer 1x-4x iste cene.
    if ( ! $orto && in_array( $k['nacin'], array( 'var', 'simple' ), true ) ) {
        for ( $n = 1; $n <= 4; $n++ ) { $k['ponudbe'][] = array( 'id' => '', 'kos' => $n, 'paket' => '', 'red' => $red * $n, 'akc' => $akc * $n ); }
    }
    if ( empty( $k['ponudbe'] ) ) { $k['ponudbe'][] = array( 'id' => $k['offer'], 'kos' => $k['kos'], 'paket' => $k['paket'], 'red' => $red, 'akc' => $akc ); }
    $k['pop_max'] = $k['pop'];
    foreach ( $k['ponudbe'] as $i => $po ) {
        $pp = (int) round( ( 1 - $po['akc'] / $po['red'] ) * 100 );
        $k['ponudbe'][ $i ]['pop'] = $pp;
        if ( $k['nacin'] !== 'ogled' && $pp > $k['pop_max'] ) { $k['pop_max'] = $pp; }
    }
    $k['prodaja'] = (int) $p->get_total_sales();
    return $k;
}

function noriks_bf_uredi( $seznam, $najvec = NORIKS_BF_NAJVEC ) {
    usort( $seznam, function ( $a, $b ) {
        if ( $a['prodaja'] !== $b['prodaja'] ) { return $b['prodaja'] <=> $a['prodaja']; }
        return $a['vrsta'] <=> $b['vrsta'];
    } );
    return array_slice( $seznam, 0, $najvec );
}

/** Novi izdelki (seznam SKU), po prodaji padajoce. */
function noriks_bf_novi() {
    $ven = array(); $videni = array();
    if ( ! function_exists( 'wc_get_product_id_by_sku' ) ) { return $ven; }
    foreach ( noriks_bf_skus() as $i => $sku ) {
        $id = wc_get_product_id_by_sku( $sku );
        if ( ! $id ) { continue; }
        $p = wc_get_product( $id );
        if ( $p && $p->is_type( 'variation' ) ) { $p = wc_get_product( $p->get_parent_id() ); }
        if ( ! $p || isset( $videni[ $p->get_id() ] ) ) { continue; }
        $k = noriks_bf_kartica( $p );
        if ( ! $k ) { continue; }
        $videni[ $p->get_id() ] = true;
        $k['vrsta'] = $i;
        $ven[] = $k;
    }
    return noriks_bf_uredi( $ven );
}

/** Ali je izdelek enega od danih tipov (po razresevalniku tipov tega trga). */
function noriks_bf_je_tipa( $id, $tipi ) {
    if ( ! function_exists( 'noriks_is_type' ) ) { return false; }
    foreach ( (array) $tipi as $t ) {
        if ( noriks_is_type( $t, $id ) ) { return true; }
    }
    return false;
}

/**
 * Majice, bokserice in kompleti: objavljeni, z akcijsko ceno. Vkljuceni so tudi
 * izdelki, skriti iz kataloga — glavne strani ponudb (SHIRTS-ORTO, BOXERS-ORTO, SHBOX)
 * so skrite na skoraj vseh trgih. Izloceni so samo izdelki za upsell (SKU z UPSELL/MYSTERY);
 * po prodaji padajoce. $tipi = kateri tipi (privzeto vsi), $s_slabproof = dodaj se
 * SlabProof nogavice (tudi ce so skrite).
 */
function noriks_bf_oblacila( $tipi = null, $s_slabproof = false ) {
    if ( ! $tipi ) { $tipi = array( 'majice', 'bokserice', 'starter', 'majice-bokserice-paketi', 'majica-darila' ); }
    $ven = array(); $videni = array(); $dodani = array();
    if ( ! function_exists( 'wc_get_product_ids_on_sale' ) ) { return $ven; }
    $n = 0;
    foreach ( (array) wc_get_product_ids_on_sale() as $id ) {
        $p = wc_get_product( $id );
        if ( ! $p ) { continue; }
        if ( $p->is_type( 'variation' ) ) { $p = wc_get_product( $p->get_parent_id() ); if ( ! $p ) { continue; } }
        $pid = $p->get_id();
        if ( isset( $videni[ $pid ] ) ) { continue; }
        $videni[ $pid ] = true;
        if ( preg_match( '/UPSELL|MYSTERY/i', (string) $p->get_sku() ) || ! noriks_bf_je_tipa( $pid, $tipi ) ) { continue; }
        $k = noriks_bf_kartica( $p );
        if ( ! $k ) { continue; }
        $k['vrsta'] = $n++;
        $ven[] = $k;
        $dodani[ $pid ] = true;
    }
    $ven = noriks_bf_uredi( $ven );
    if ( $s_slabproof && function_exists( 'wc_get_product_id_by_sku' ) ) {
        $sid = wc_get_product_id_by_sku( 'NORIKS-SLABPROOF' );
        if ( $sid && ! isset( $dodani[ $sid ] ) ) {
            $k = noriks_bf_kartica( wc_get_product( $sid ) );
            if ( $k ) { $k['vrsta'] = -1; $ven[] = $k; }
        }
    }
    return $ven;
}

/** Najbolj prodajani izdelki nasploh (novi + majice in bokserice skupaj). */
function noriks_bf_top( $n = 9 ) {
    $vsi = array(); $videni = array();
    foreach ( array_merge( noriks_bf_novi(), noriks_bf_oblacila() ) as $k ) {
        $id = $k['p']->get_id();
        if ( isset( $videni[ $id ] ) ) { continue; }
        $videni[ $id ] = true;
        $vsi[] = $k;
    }
    return noriks_bf_uredi( $vsi, $n );
}

/** Podatki za modal "Poglej vec": slike galerije in opis izdelka. */
add_action( 'wp_ajax_noriks_bf_info', 'noriks_bf_info' );
add_action( 'wp_ajax_nopriv_noriks_bf_info', 'noriks_bf_info' );
function noriks_bf_info() {
    $id = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : 0;
    $p  = $id ? wc_get_product( $id ) : null;
    if ( ! $p || $p->get_status() !== 'publish' ) { wp_send_json_error(); }
    $slike = array();
    $ids   = array_values( array_unique( array_filter( array_merge( array( (int) $p->get_image_id() ), array_map( 'intval', (array) $p->get_gallery_image_ids() ) ) ) ) );
    foreach ( array_slice( $ids, 0, 8 ) as $iid ) {
        $v = wp_get_attachment_image_url( $iid, 'woocommerce_single' );
        $m = wp_get_attachment_image_url( $iid, 'woocommerce_thumbnail' );
        if ( $v ) { $slike[] = array( $v, $m ? $m : $v ); }
    }
    wp_send_json_success( array(
        'ime'    => $p->get_name(),
        'url'    => get_permalink( $id ),
        'slike'  => $slike,
        'kratek' => wp_kses_post( wpautop( (string) $p->get_short_description() ) ),
        'opis'   => wp_kses_post( wpautop( (string) $p->get_description() ) ),
    ) );
}

/** Dodajanje v kosarico s kartice (isti podatki kot obrazec na strani izdelka). */
add_action( 'wp_ajax_noriks_bf_add', 'noriks_bf_add' );
add_action( 'wp_ajax_nopriv_noriks_bf_add', 'noriks_bf_add' );
function noriks_bf_add() {
    $l = noriks_bf_l();
    if ( ! function_exists( 'WC' ) || ! WC()->cart ) { wp_send_json_error( array( 'message' => $l['napaka'] ) ); }
    $id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    $p  = $id ? wc_get_product( $id ) : null;
    if ( ! $p || $p->get_status() !== 'publish' || ! $p->is_purchasable() || ! $p->is_in_stock() ) {
        wp_send_json_error( array( 'message' => $l['napaka'] ) );
    }
    $nacin  = isset( $_POST['nacin'] ) ? sanitize_key( wp_unslash( $_POST['nacin'] ) ) : '';
    $kol    = isset( $_POST['quantity'] ) ? min( 10, max( 1, absint( $_POST['quantity'] ) ) ) : 1;
    $kljuc  = false;

    if ( $nacin === 'orto' ) {
        $ponudba = isset( $_POST['bundle_option'] ) ? sanitize_text_field( wp_unslash( $_POST['bundle_option'] ) ) : '';
        $ponudbe = function_exists( 'gck_get_bundle_offers' ) ? gck_get_bundle_offers( $id ) : array();
        if ( $ponudba === '' || ! isset( $ponudbe[ $ponudba ] ) ) { wp_send_json_error( array( 'message' => $l['napaka'] ) ); }
        // vticnik orto-product prebere bundle_option in pairs[...] neposredno iz $_POST
        $kljuc = WC()->cart->add_to_cart( $id, 1 );
    } elseif ( $nacin === 'var' && $p->is_type( 'variable' ) ) {
        $atr = array();
        foreach ( $_POST as $kk => $vv ) {
            if ( strpos( (string) $kk, 'attribute_' ) === 0 && is_scalar( $vv ) ) { $atr[ wc_clean( wp_unslash( $kk ) ) ] = wc_clean( wp_unslash( $vv ) ); }
        }
        $vid = $atr ? WC_Data_Store::load( 'product' )->find_matching_product_variation( $p, $atr ) : 0;
        if ( ! $vid ) { wp_send_json_error( array( 'message' => $l['napaka'] ) ); }
        $kljuc = WC()->cart->add_to_cart( $id, $kol, $vid, $atr );
    } elseif ( $nacin === 'simple' && $p->is_type( 'simple' ) ) {
        $kljuc = WC()->cart->add_to_cart( $id, $kol );
    }

    if ( ! $kljuc ) {
        $sporocilo = $l['napaka'];
        $obv = function_exists( 'wc_get_notices' ) ? wc_get_notices( 'error' ) : array();
        if ( ! empty( $obv[0]['notice'] ) ) { $sporocilo = wp_strip_all_tags( $obv[0]['notice'] ); }
        if ( function_exists( 'wc_clear_notices' ) ) { wc_clear_notices(); }
        wp_send_json_error( array( 'message' => $sporocilo ) );
    }
    do_action( 'woocommerce_ajax_added_to_cart', $id );
    WC_AJAX::get_refreshed_fragments();   // poslje fragmente (tudi stranske kosarice) in konca
}

/** Enkrat ustvari vseh pet WP strani in jim dodeli predlogo page-black-friday.php. */
function noriks_bf_ensure_page() {
    foreach ( noriks_bf_strani() as $slug => $nast ) {
        $naslov = $nast['naslov'];
        $opt = 'noriks_bf_page_' . md5( $slug );
        $id  = (int) get_option( $opt );
        if ( $id && get_post_status( $id ) ) { continue; }
        $obst = get_page_by_path( $slug );
        if ( $obst ) {
            update_post_meta( $obst->ID, '_wp_page_template', 'page-black-friday.php' );
            update_option( $opt, $obst->ID );
            continue;
        }
        $id = wp_insert_post( array(
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => $naslov,
            'post_name'    => $slug,
            'post_content' => '',
            'meta_input'   => array( '_wp_page_template' => 'page-black-friday.php' ),
        ) );
        if ( $id && ! is_wp_error( $id ) ) { update_option( $opt, $id ); }
    }
}
add_action( 'init', 'noriks_bf_ensure_page' );
