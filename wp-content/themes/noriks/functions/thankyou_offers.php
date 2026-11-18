<?php
/**
 * Thank you upsell, korak 2 — ponudbe izdelkov po nakupu.
 *
 * - Ponudbe se urejajo v adminu: WooCommerce → Ponude nakon kupnje
 *   (opcija noriks_ty2_offers). Dokler ni nic shranjeno, veljajo privzete spodaj.
 * - Cena se vpise NA KOS; paket = cena po komadu × kolicina. Brez vpisane cene velja
 *   privzeta cena po komadu po SKU (noriks_ty2_default_unit_prices), sicer 50 % akcijske cene.
 * - Boja je dolocena v ponudbi (kupec izbere samo velikost): ena barva (npr. "Črna")
 *   za vse kose ali "mix" = mesane barve (barve izdelka po vrsti, kos za kosom).
 * - Kartice za stran IN cena ob dodajanju se racunata na strezniku iz iste
 *   funkcije (noriks_ty2_build_cards), zato brskalnik cene ne more podtakniti.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// v2: 18. 9. 2026 ponudbe s fiksnimi barvami (stari zapis v noriks_ty2_offers se ne bere vec)
define( 'NORIKS_TY2_OPTION', 'noriks_ty2_offers_v2' );

/** Privzete cene po komadu po SKU (veljajo, ce v adminu cena po komadu ni vpisana). */
function noriks_ty2_default_unit_prices() {
    return array(
        'NORIKS-SHIRTS-ORTO' => 199,
        'NORIKS-KOMPSFIT'    => 229,
        'NORIKS-SLIM'        => 364,   // +1 Slim v koraku 1: priblizno 50 % akcijske cene
        // priblizno 50 % akcijske cene, zaokrozeno navzdol
        'NORIKS-KIDSNEST'    => 529,
        'NORIKS-KNEEFIX'     => 499,
    );
}

/**
 * Privzete cene PAKETA po SKU in kolicini — ista lestvica kot v koraku 1
 * (17. 11. 2026), da korak 2 ni drazji od koraka 1.
 * Veljajo, ce v adminu cena po komadu ni vpisana; za ostale kolicine velja cena po komadu.
 */
function noriks_ty2_default_pack_prices() {
    return array(
        'NORIKS-SHIRTS-ORTO' => array( 1 => 199, 3 => 499, 6 => 829 ),
    );
}

/**
 * Koncna cena paketa za vrstico ponudbe: cena po komadu × kolicina.
 * Vrne '' (= 50 % akcijske cene), ce ni ne vpisane ne privzete cene po komadu.
 */
function noriks_ty2_row_price( $row ) {
    $qty  = max( 1, (int) ( $row['qty'] ?? 1 ) );
    $unit = isset( $row['unit'] ) && is_numeric( $row['unit'] ) ? (float) $row['unit'] : 0;
    if ( $unit <= 0 ) {
        // starejsi zapis: koncna cena za celoten paket
        if ( isset( $row['price'] ) && is_numeric( $row['price'] ) && (float) $row['price'] > 0 ) { return (float) $row['price']; }
        $paketi = noriks_ty2_default_pack_prices();
        $sku_v  = strtoupper( $row['sku'] ?? '' );
        if ( isset( $paketi[ $sku_v ][ $qty ] ) ) { return (float) $paketi[ $sku_v ][ $qty ]; }
        $map  = noriks_ty2_default_unit_prices();
        $unit = $map[ strtoupper( $row['sku'] ?? '' ) ] ?? 0;
    }
    return $unit > 0 ? round( $unit * $qty, 2 ) : '';
}

/** Privzete ponudbe. img = datoteka v img/upsell/ ali poln URL; prazno = glavna slika proizvoda. */
function noriks_ty2_default_offers() {
    $rows = array(
        // brez bokseric (17. 11. 2026): KidsNest, KneeFix, majice in KOMPSFIT; slike so iz knjiznice medijev
        array( 'uid' => 'kidsnest1', 'sku' => 'NORIKS-KIDSNEST', 'qty' => 1, 'color' => '', 'cat' => '', 'label' => '1x KidsNest dětský polštář', 'img' => '' ),
        array( 'uid' => 'kneefix1',  'sku' => 'NORIKS-KNEEFIX',  'qty' => 1, 'color' => '', 'cat' => '', 'label' => '1x KneeFix kolenní ortéza', 'img' => '' ),
        array( 'sku' => 'NORIKS-SHIRTS-ORTO', 'qty' => 3, 'color' => 'Černá', 'cat' => '', 'label' => '3x Černá trička', 'img' => 'sku:NORIKS-ALL-BLACK-3-PACK' ),
        array( 'sku' => 'NORIKS-SHIRTS-ORTO', 'qty' => 6, 'color' => 'Černá', 'cat' => '', 'label' => '6x Černých triček', 'img' => 'sku:NORIKS-ALL-BLACK-6-PACK' ),
        array( 'sku' => 'NORIKS-SHIRTS-ORTO', 'qty' => 3, 'color' => 'Černá, Šedá, Bílá', 'cat' => '', 'label' => '3x Trička v různých barvách', 'img' => 'sku:NORIKS-MONOCHROME-3-PACK' ),
        array( 'sku' => 'NORIKS-SHIRTS-ORTO', 'qty' => 6, 'color' => 'Černá, Šedá, Tmavě modrá, Zelená, Béžová, Bílá', 'cat' => '', 'label' => '6x Triček v různých barvách', 'img' => 'everyday-6X.jpg' ),
        array( 'sku' => 'NORIKS-KOMPSFIT',    'qty' => 1, 'color' => 'Černá', 'cat' => '', 'label' => '1x Černé FIT kompresní tričko', 'img' => '' ),
        array( 'sku' => 'NORIKS-KOMPSFIT',    'qty' => 3, 'color' => 'Černá', 'cat' => '', 'label' => '3x Černá FIT kompresní trička', 'img' => '' )
    );
    foreach ( $rows as $i => &$r ) {
        if ( empty( $r['uid'] ) ) { $r['uid'] = 'd' . $i; }   // majice in KOMPSFIT obdrzijo stare kljuce d2–d7
        $r['active'] = 1;
        $r['unit']   = '';
        $r['price']  = '';
    }
    return $rows;
}

/** Vse vrstice (tudi neaktivne) — za admin. */
function noriks_ty2_all_offers() {
    $saved = get_option( NORIKS_TY2_OPTION, null );
    return is_array( $saved ) ? $saved : noriks_ty2_default_offers();
}

/** Samo aktivne vrstice, v shranjenem vrstnem redu. */
function noriks_ty2_active_offers() {
    return array_values( array_filter( noriks_ty2_all_offers(), function ( $r ) {
        return ! empty( $r['active'] ) && ! empty( $r['sku'] );
    } ) );
}

/** Enotna akcijska cena izdelka (za variabilne najnizja). */
function noriks_ty2_unit_price( $p ) {
    $unit = (float) $p->get_price();
    if ( ! $unit && $p->is_type( 'variable' ) ) { $unit = (float) $p->get_variation_price( 'min', true ); }
    if ( ! $unit ) { $unit = (float) $p->get_regular_price(); }
    return $unit;
}

/**
 * Barve in velikosti iz atributov izdelka. Tretja vrednost je izbira, ki je ponudba ne
 * more dolociti namesto kupca (stran pri KneeFix: Lijevo / Desno): array( name, options ) ali null.
 */
function noriks_ty2_attr_options( $p ) {
    $colors = array(); $sizes = array(); $choice = null;
    foreach ( $p->get_attributes() as $attr ) {
        if ( ! is_object( $attr ) ) { continue; }
        $aname = mb_strtolower( $attr->get_name() );
        $opts  = $attr->is_taxonomy()
            ? wp_list_pluck( wc_get_product_terms( $p->get_id(), $attr->get_name(), array( 'fields' => 'all' ) ), 'name' )
            : (array) $attr->get_options();
        // imena atributov se razlikujejo po trgih: Barva/Boja/Color, Velikost/Velicina/Size
        $je = function ( $kljuci ) use ( $aname ) { foreach ( $kljuci as $k ) { if ( mb_strpos( $aname, $k ) !== false ) { return true; } } return false; };
        if ( $je( array( 'stran', 'stron', 'oldal', 'parte', 'πλευρ', 'side' ) ) ) { $choice = array( 'name' => $attr->get_name(), 'options' => array_values( $opts ) ); }
        elseif ( $je( array( 'barv', 'boj', 'color', 'kolor', 'szín', 'farb', 'culoare', 'χρώμα' ) ) ) { $colors = $opts; }
        elseif ( $je( array( 'veliko', 'veličin', 'velicin', 'size', 'rozmiar', 'méret', 'veľkos', 'velkos', 'velikost', 'mărime', 'marime', 'μέγεθος' ) ) ) { $sizes = $opts; }
    }
    return array( $colors, $sizes, $choice );
}

/** Ena kartica iz izdelka; null, ce izdelka ni ali nima cene. */
/**
 * Barve po kosih. $color je:
 *  - ena barva ("Črna")            → vsi kosi te barve
 *  - seznam ("Črna, Siva, Modra")  → kosi po vrsti v teh barvah (seznam se ponavlja)
 *  - "mix" ali prazno              → barve izdelka po vrsti
 * Vrne null, ce katere barve na izdelku ni (ponudbe potem ne pokazemo).
 */
function noriks_ty2_piece_colors( $color, $available, $qty ) {
    $qty = max( 1, (int) $qty );
    if ( ! $available ) { return array_fill( 0, $qty, '' ); }
    $color = trim( (string) $color );
    if ( $color === '' || strtolower( $color ) === 'mix' ) {
        $list = array_values( $available );
    } else {
        $list = array();
        foreach ( array_filter( array_map( 'trim', explode( ',', $color ) ), 'strlen' ) as $want ) {
            $hit = null;
            // ujemanje brez velikih crk; zapisemo tako, kot je barva poimenovana na izdelku
            foreach ( $available as $a ) {
                if ( mb_strtolower( $a ) === mb_strtolower( $want ) ) { $hit = $a; break; }
            }
            if ( null === $hit ) { return null; }
            $list[] = $hit;
        }
        if ( ! $list ) { return null; }
    }
    $out = array();
    for ( $i = 0; $i < $qty; $i++ ) { $out[] = $list[ $i % count( $list ) ]; }
    return $out;
}

/** Velikost iz narocila, prevedena na moznost izdelka (2XL = XXL, 3XL = XXXL …). */
function noriks_ty2_pick_size( $sizes, $customer_size ) {
    if ( ! $sizes ) { return ''; }
    $norm = function ( $s ) {
        $s = strtoupper( trim( (string) $s ) );
        $map = array( 'XXL' => '2XL', 'XXXL' => '3XL', 'XXXXL' => '4XL', 'XXXXXL' => '5XL' );
        return $map[ $s ] ?? $s;
    };
    $want = $norm( $customer_size );
    if ( $want !== '' ) {
        foreach ( $sizes as $sz ) { if ( $norm( $sz ) === $want ) { return $sz; } }
    }
    return '';
}

/**
 * Barve kosov za vrstico pod naslovom kartice: "Czarny" (vsi kosi iste barve),
 * "Czarny, Szary, Biały" (vsak kos svoje barve) ali "2x Czarny, 2x Szary, 2x Biały".
 * Brez barv (KidsNest, KneeFix) vrne prazno — vrstica se ne izpise.
 */
function noriks_ty2_contents( $piece_colors, $sku, $fallback_name ) {
    $counts = array_count_values( array_filter( (array) $piece_colors, 'strlen' ) );
    if ( ! $counts ) { return ''; }
    if ( count( $counts ) === 1 || max( $counts ) === 1 ) { return implode( ', ', array_keys( $counts ) ); }
    $parts = array();
    foreach ( $counts as $col => $n ) { $parts[] = $n . 'x ' . $col; }
    return implode( ', ', $parts );
}

/** "Črna ×5" / "Črna, Modra, Siva …" za admin. */
function noriks_ty2_colors_summary( $pc ) {
    $pc = array_filter( (array) $pc, 'strlen' );
    if ( ! $pc ) { return array( '—' ); }
    $out = array();
    foreach ( array_count_values( $pc ) as $c => $n ) { $out[] = $n > 1 ? $c . ' ×' . $n : $c; }
    return $out;
}

/**
 * URL slike ponudbe — vedno iz obstojece knjiznice medijev:
 *  - "sku:NORIKS-BOX-BLACK-5-PACK" → glavna slika tega izdelka
 *  - "everyday-6X.jpg"             → datoteka v knjiznici medijev (po imenu)
 *  - 1234                          → ID priponke
 *  - https://…                     → poln URL
 */
function noriks_ty2_img_url( $img ) {
    static $cache = array();
    $img = trim( (string) $img );
    if ( $img === '' ) { return ''; }
    if ( isset( $cache[ $img ] ) ) { return $cache[ $img ]; }
    $url = '';
    if ( preg_match( '#^https?://#i', $img ) ) {
        $url = $img;
    } elseif ( stripos( $img, 'sku:' ) === 0 ) {
        $pid = wc_get_product_id_by_sku( trim( substr( $img, 4 ) ) );
        $iid = $pid ? get_post_thumbnail_id( $pid ) : 0;
        $url = $iid ? (string) wp_get_attachment_url( $iid ) : '';
    } elseif ( ctype_digit( $img ) ) {
        $url = (string) wp_get_attachment_url( (int) $img );
    } else {
        global $wpdb;
        $name = basename( $img );
        $aid  = (int) $wpdb->get_var( $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND ( meta_value = %s OR meta_value LIKE %s ) ORDER BY post_id DESC LIMIT 1",
            $name, '%/' . $wpdb->esc_like( $name )
        ) );
        $url = $aid ? (string) wp_get_attachment_url( $aid ) : '';
    }
    return $cache[ $img ] = $url;
}

function noriks_ty2_card( $key, $p, $qty, $cat, $label, $price, $color = 'mix', $img = '' ) {
    if ( ! $p || 'publish' !== get_post_status( $p->get_id() ) ) { return null; }
    if ( ! $p->is_in_stock() ) { return null; }   // ponujamo samo, kar je na zalogi
    $qty  = max( 1, (int) $qty );
    $unit = noriks_ty2_unit_price( $p );
    if ( ! $unit ) { return null; }

    $old = round( $unit * $qty, 2 );
    $new = ( $price === '' || $price === null ) ? round( $old * 0.5, 2 ) : round( (float) $price, 2 );
    if ( $new <= 0 ) { return null; }

    list( $colors, $sizes, $choice ) = noriks_ty2_attr_options( $p );
    $piece_colors = noriks_ty2_piece_colors( $color, $colors, $qty );
    if ( null === $piece_colors ) { return null; }
    // velikosti, ki niso oblacilne (starost, teza): kupec jih mora izbrati sam, brez privzete
    $oblacilne = false;
    foreach ( (array) $sizes as $sz ) { if ( preg_match( '/^(XS|S|M|L|XL|[2-6]XL|X{2,6}L)$/i', trim( (string) $sz ) ) ) { $oblacilne = true; break; } }
    $img_id  = $p->get_image_id();
    $img_url = noriks_ty2_img_url( $img );

    return array(
        'key'        => $key,
        'product_id' => $p->get_id(),
        'sku'        => $p->get_sku(),
        'qty'        => $qty,
        'cat'        => (string) $cat,
        'label'      => $label !== '' ? (string) $label : $p->get_name(),
        'img'        => $img_url ?: ( $img_id ? wp_get_attachment_url( $img_id ) : wc_placeholder_img_src() ),
        'old'        => $old,
        'new'        => $new,
        'unit_new'   => round( $new / $qty, 2 ),
        'colors'       => $colors,        // sve boje proizvoda (samo za informaciju)
        'piece_colors' => $piece_colors,  // barva vsakega kosa — doloci jo ponuda, ne kupec
        'contents'     => noriks_ty2_contents( $piece_colors, $p->get_sku(), $p->get_name() ),
        'sizes'      => $sizes,
        'choice'     => $choice,                                   // npr. Stranica: Lijevo / Desno (izbere kupec)
        'pick'       => ( $sizes && ! $oblacilne ),               // velikost brez privzete vrednosti
        'variable'   => $p->is_type( 'variable' ),
        'link'       => get_permalink( $p->get_id() ),
    );
}

/**
 * Kartice za korak 2. Enaka funkcija se klice na strani in ob dodajanju v naročilo.
 *
 * @param WC_Order|false $order
 * @param int            $exclude_id izdelek iz koraka 1 (ne ponujamo ga se enkrat)
 */
function noriks_ty2_build_cards( $order = false, $exclude_id = 0 ) {
    $cards = array();
    foreach ( noriks_ty2_active_offers() as $row ) {
        $pid = wc_get_product_id_by_sku( $row['sku'] );
        if ( ! $pid ) { continue; }
        $card = noriks_ty2_card( 'o' . $row['uid'], wc_get_product( $pid ), $row['qty'], $row['cat'] ?? '', $row['label'] ?? '', noriks_ty2_row_price( $row ), $row['color'] ?? 'mix', $row['img'] ?? '' );
        if ( $card ) { $cards[] = $card; }
    }

    // Varovalka: ce na trgu kaksnega SKU ni, mrezo dopolnimo z najbolj prodajanimi,
    // da korak 2 nikoli ne ostane prazen.
    if ( count( $cards ) < 4 ) {
        $exclude = array_merge( wp_list_pluck( $cards, 'product_id' ), array( (int) $exclude_id ) );
        if ( $order ) {
            foreach ( $order->get_items() as $oi ) { $exclude[] = $oi->get_product_id(); }
        }
        $fill = wc_get_products( array(
            'status'  => 'publish',
            'limit'   => 8 - count( $cards ),
            'exclude' => array_filter( $exclude ),
            'orderby' => 'popularity',
            'type'    => array( 'simple', 'variable' ),
        ) );
        foreach ( $fill as $fp ) {
            $card = noriks_ty2_card( 'f' . $fp->get_id(), $fp, 1, '', $fp->get_name(), '' );
            if ( $card ) { $cards[] = $card; }
        }
    }
    return $cards;
}

/**
 * Korak 1 kot "+1 istega izdelka": izdelki, pri katerih kupcu ponudimo se en kos tega,
 * kar je pravkar kupil (namesto crne majice). Za dodatni izdelek dodaj vrstico sem;
 * cena na kos je v noriks_ty2_default_unit_prices().
 */
function noriks_ty2_step1_products() {
    return array(
        'NORIKS-KOMPSFIT' => array(
            'imena' => array( 1 => '1x FIT kompresní tričko', 2 => '2x FIT kompresní trička', 3 => '3x FIT kompresní trička' ),
            'img'   => '',
        ),
        // Slim: kupec izbere barvo IN velikost (privzeto barva kupljenega kosa)
        'NORIKS-SLIM' => array(
            'imena' => array( 1 => '1x Slim tvarující kalhotky', 2 => '2x Slim tvarující kalhotky', 3 => '3x Slim tvarující kalhotky' ),
            'img'   => '',
            'izbira_barve' => true,
        ),
    );
}

/**
 * Kartice koraka 1 za to narocilo (kolicina => kartica) ali prazno polje, ce v narocilu
 * ni izdelka s seznama — takrat korak 1 ponudi crno majico. Barva je barva kupljenega kosa.
 */
function noriks_ty2_step1_cards( $order ) {
    if ( ! $order ) { return array(); }
    $seznam = noriks_ty2_step1_products();
    $cene   = noriks_ty2_default_unit_prices();
    foreach ( $order->get_items() as $it ) {
        if ( ! is_a( $it, 'WC_Order_Item_Product' ) || $it->get_meta( '_noriks_upsell' ) ) { continue; }
        $p = wc_get_product( $it->get_product_id() );
        $sku = $p ? strtoupper( (string) $p->get_sku() ) : '';
        if ( ! isset( $seznam[ $sku ] ) ) { continue; }
        list( $colors ) = noriks_ty2_attr_options( $p );
        $barva = '';
        $prvi  = (string) $it->get_meta( '1' );               // "Crna - L"
        foreach ( (array) $colors as $c ) {
            if ( $prvi !== '' && mb_stripos( $prvi, (string) $c ) === 0 ) { $barva = (string) $c; break; }
        }
        if ( $barva === '' && $colors ) { $barva = in_array( 'Černá', $colors, true ) ? 'Černá' : (string) reset( $colors ); }
        $kljuc = 's1' . strtolower( preg_replace( '/[^A-Za-z0-9]/', '', str_replace( 'NORIKS-', '', $sku ) ) );
        $unit  = isset( $cene[ $sku ] ) ? (float) $cene[ $sku ] : 0;
        $ven   = array();
        foreach ( $seznam[ $sku ]['imena'] as $q => $ime ) {
            $card = noriks_ty2_card( $kljuc . $q, $p, $q, '', $ime, $unit > 0 ? round( $unit * $q, 2 ) : '', $barva, $seznam[ $sku ]['img'] );
            if ( $card ) {
                // barvo izbere kupec (strezni del jo preveri proti barvam izdelka)
                $card['pick_color'] = ! empty( $seznam[ $sku ]['izbira_barve'] ) && count( (array) $card['colors'] ) > 1;
                $ven[ (int) $q ] = $card;
            }
        }
        if ( $ven ) { return $ven; }
    }
    return array();
}

/** Kartica po kljucu — cena in izdelek za dodajanje pridejo od tu, ne iz brskalnika. */
function noriks_ty2_find_card( $key, $order = false ) {
    if ( strpos( $key, 's1' ) === 0 ) {          // korak 1 (+1 istega izdelka)
        foreach ( noriks_ty2_step1_cards( $order ) as $c ) { if ( $c['key'] === $key ) { return $c; } }
        return null;
    }
    foreach ( noriks_ty2_build_cards( $order ) as $c ) {
        if ( $c['key'] === $key ) { return $c; }
    }
    // izpolnilna kartica je lahko ob dodajanju ze izpadla iz izbora — izdelek preverimo neposredno
    if ( strpos( $key, 'f' ) === 0 ) {
        $p = wc_get_product( absint( substr( $key, 1 ) ) );
        return $p ? noriks_ty2_card( $key, $p, 1, '', $p->get_name(), '' ) : null;
    }
    return null;
}

/** Variacija, ki se ujema z izbrano barvo in velikostjo (primerjava brez velikih crk). */
function noriks_ty2_match_variation( $product, $color, $size ) {
    if ( ! $product || ! $product->is_type( 'variable' ) ) { return 0; }
    $want = array_filter( array( mb_strtolower( $color ), mb_strtolower( $size ) ) );
    foreach ( $product->get_children() as $vid ) {
        $v = wc_get_product( $vid );
        if ( ! $v || ! $v->is_purchasable() ) { continue; }
        $vals = array();
        foreach ( $v->get_attributes() as $tax => $val ) {
            $term = taxonomy_exists( $tax ) ? get_term_by( 'slug', $val, $tax ) : false;
            $vals[] = mb_strtolower( $term ? $term->name : (string) $val );
        }
        if ( ! array_diff( $want, $vals ) ) { return $vid; }
    }
    return 0;
}


/* ══════════════════════════════════════════════════════════════════
   ADMIN: WooCommerce → Ponude nakon kupnje
   ══════════════════════════════════════════════════════════════════ */

add_action( 'admin_menu', function () {
    add_submenu_page( 'woocommerce', 'Ponude nakon kupnje', 'Ponude nakon kupnje', 'manage_woocommerce', 'noriks-ty2-offers', 'noriks_ty2_admin_page' );
}, 60 );

// izbirnik slik iz knjiznice medijev (samo na tej strani)
add_action( 'admin_enqueue_scripts', function () {
    if ( isset( $_GET['page'] ) && $_GET['page'] === 'noriks-ty2-offers' ) { wp_enqueue_media(); }
} );

/** Celica s sliko: predogled + gumba Izberi / Ukloni + polje (ID priponke, sku:…, ime datoteke ali URL). */
function noriks_ty2_img_cell( $val ) {
    $url = noriks_ty2_img_url( $val );
    ob_start(); ?>
    <div class="ty2-img">
        <img class="ty2-img__prev" src="<?php echo esc_url( $url ); ?>" alt="" <?php echo $url ? '' : 'hidden'; ?>>
        <div>
            <button type="button" class="button button-small ty2-img__pick">Odaberi sliku</button>
            <button type="button" class="button-link ty2-img__clear" <?php echo $val ? '' : 'hidden'; ?>>Ukloni</button>
            <input type="text" data-f="img" value="<?php echo esc_attr( $val ); ?>" placeholder="slika proizvoda" class="ty2-img__val">
        </div>
    </div>
    <?php return ob_get_clean();
}

function noriks_ty2_clean_color( $c ) {
    $c = trim( sanitize_text_field( (string) $c ) );
    return ( $c === '' || in_array( mb_strtolower( $c ), array( 'mix', 'miješano', 'mesano', 'mešane', 'mesane' ), true ) ) ? 'mix' : $c;
}
function noriks_ty2_clean_img( $i ) {
    $i = trim( (string) $i );
    if ( $i === '' ) { return ''; }
    if ( preg_match( '#^https?://#i', $i ) ) { return esc_url_raw( $i ); }
    if ( stripos( $i, 'sku:' ) === 0 ) { return 'sku:' . strtoupper( sanitize_text_field( trim( substr( $i, 4 ) ) ) ); }
    if ( ctype_digit( $i ) ) { return $i; }
    return sanitize_file_name( basename( $i ) );
}

add_action( 'admin_post_noriks_ty2_save', function () {
    if ( ! current_user_can( 'manage_woocommerce' ) ) { wp_die( 'Nemate dopuštenje.' ); }
    check_admin_referer( 'noriks_ty2_save' );

    if ( ! empty( $_POST['ty2_reset'] ) ) {
        delete_option( NORIKS_TY2_OPTION );
        wp_safe_redirect( admin_url( 'admin.php?page=noriks-ty2-offers&shranjeno=ponastavljeno' ) );
        exit;
    }

    $in   = isset( $_POST['ty2'] ) && is_array( $_POST['ty2'] ) ? wp_unslash( $_POST['ty2'] ) : array();
    $rows = array();
    foreach ( $in as $r ) {
        $sku = strtoupper( trim( sanitize_text_field( $r['sku'] ?? '' ) ) );
        if ( $sku === '' ) { continue; }
        $unit = trim( str_replace( ',', '.', sanitize_text_field( $r['unit'] ?? '' ) ) );
        $rows[] = array(
            'uid'    => preg_replace( '/[^a-z0-9]/i', '', $r['uid'] ?? '' ) ?: substr( md5( uniqid( '', true ) ), 0, 8 ),
            'active' => empty( $r['active'] ) ? 0 : 1,
            'sku'    => $sku,
            'qty'    => max( 1, min( 50, absint( $r['qty'] ?? 1 ) ) ),
            'cat'    => sanitize_text_field( $r['cat'] ?? '' ),
            'label'  => sanitize_text_field( $r['label'] ?? '' ),
            'color'  => noriks_ty2_clean_color( $r['color'] ?? '' ),
            'img'    => noriks_ty2_clean_img( $r['img'] ?? '' ),
            'unit'   => ( $unit !== '' && is_numeric( $unit ) && (float) $unit > 0 ) ? (string) round( (float) $unit, 2 ) : '',
            'price'  => '',
        );
    }
    update_option( NORIKS_TY2_OPTION, $rows, false );
    wp_safe_redirect( admin_url( 'admin.php?page=noriks-ty2-offers&shranjeno=1' ) );
    exit;
} );

function noriks_ty2_admin_page() {
    if ( ! current_user_can( 'manage_woocommerce' ) ) { return; }
    $rows     = noriks_ty2_all_offers();
    $is_saved = is_array( get_option( NORIKS_TY2_OPTION, null ) );
    $cur      = get_woocommerce_currency_symbol();
    ?>
    <style>
        .noriks-ty2 .ty2-img { display:flex; gap:8px; align-items:flex-start; min-width:210px; }
        .noriks-ty2 .ty2-img__prev { width:56px; height:56px; object-fit:cover; border-radius:4px; border:1px solid #ddd; cursor:pointer; background:#f6f6f6; flex:none; }
        .noriks-ty2 .ty2-img__val { width:100%; margin-top:4px; font-size:11px; color:#666; }
        .noriks-ty2 .ty2-img__clear { color:#b32d2e; margin-left:4px; }
    </style>
    <div class="wrap noriks-ty2">
        <h1>Ponude nakon kupnje <small style="font-weight:400;color:#666">— thank you stranica, korak 2</small></h1>

        <?php if ( isset( $_GET['shranjeno'] ) ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php echo $_GET['shranjeno'] === 'ponastavljeno' ? 'Ponude su vraćene na zadane.' : 'Ponude su spremljene.'; ?></p></div>
        <?php endif; ?>

        <p>Redoslijed u tablici je redoslijed kartica na stranici. Neaktivni retci ostaju spremljeni, ali se ne prikazuju.
           <strong>Boja</strong>: jedna boja (npr. Crna) za sve komade, popis boja redom (npr. Crna, Siva, Bijela) ili »miješano« = boje proizvoda redom. Kupac bira samo veličinu.
           <strong>Slika</strong>: kliknite <em>Odaberi sliku</em> pa je odaberite ili učitajte u knjižnicu medija. Prazno = glavna slika proizvoda iz ponude.
           (U polje ispod gumba možete upisati i <code>sku:SKU-PROIZVODA</code> = glavna slika tog proizvoda ili ime datoteke iz knjižnice.)<br>
           <strong>Cijena po komadu</strong> množi se s količinom (npr. 4,99 × 3 = 14,97). Prazno = zadana cijena iz koda (vidi se sivo u polju; majice 3x i 6x imaju cijenu paketa kao u koraku 1), za proizvode bez zadane cijene 50&nbsp;% akcijske cijene.
           <?php if ( ! $is_saved ) : ?><br><em>Trenutno vrijede zadane ponude iz koda — nakon prvog spremanja postaju uredive ovdje.</em><?php endif; ?></p>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="noriks_ty2_save">
            <?php wp_nonce_field( 'noriks_ty2_save' ); ?>

            <table class="widefat striped" id="ty2-table">
                <thead><tr>
                    <th style="width:54px">Redoslijed</th>
                    <th style="width:60px">Aktivna</th>
                    <th>SKU</th>
                    <th style="width:70px">Količina</th>
                    <th>Oznaka</th>
                    <th>Naslov kartice</th>
                    <th style="width:110px">Boja</th>
                    <th>Slika</th>
                    <th style="width:110px">Cijena po komadu (<?php echo esc_html( $cur ); ?>)</th>
                    <th>Proizvod / provjera</th>
                    <th style="width:40px"></th>
                </tr></thead>
                <tbody>
                <?php foreach ( $rows as $i => $r ) :
                    $pid  = $r['sku'] ? wc_get_product_id_by_sku( $r['sku'] ) : 0;
                    $prod = $pid ? wc_get_product( $pid ) : null;
                    $card = $prod ? noriks_ty2_card( 'x', $prod, $r['qty'], '', '', noriks_ty2_row_price( $r ), $r['color'] ?? 'mix', $r['img'] ?? '' ) : null;
                    $dmap = noriks_ty2_default_unit_prices();
                    $ph   = isset( $dmap[ $r['sku'] ] ) ? number_format( $dmap[ $r['sku'] ], 2, ',', '' ) : '50 %';
                    $pmap = noriks_ty2_default_pack_prices();
                    if ( isset( $pmap[ $r['sku'] ][ (int) $r['qty'] ] ) ) { $ph = 'paket ' . number_format( $pmap[ $r['sku'] ][ (int) $r['qty'] ], 2, ',', '' ); }
                ?>
                    <tr>
                        <td><button type="button" class="button ty2-up" title="Gor">↑</button> <button type="button" class="button ty2-down" title="Dol">↓</button></td>
                        <td style="text-align:center"><input type="checkbox" data-f="active" value="1" <?php checked( ! empty( $r['active'] ) ); ?>><input type="hidden" data-f="uid" value="<?php echo esc_attr( $r['uid'] ); ?>"></td>
                        <td><input type="text" data-f="sku" value="<?php echo esc_attr( $r['sku'] ); ?>" style="width:100%"></td>
                        <td><input type="number" data-f="qty" min="1" max="50" value="<?php echo esc_attr( $r['qty'] ); ?>" style="width:100%"></td>
                        <td><input type="text" data-f="cat" value="<?php echo esc_attr( $r['cat'] ); ?>" style="width:100%"></td>
                        <td><input type="text" data-f="label" value="<?php echo esc_attr( $r['label'] ); ?>" style="width:100%"></td>
                        <td><input type="text" data-f="color" value="<?php echo esc_attr( ( $r['color'] ?? 'mix' ) === 'mix' ? 'miješano' : $r['color'] ); ?>" placeholder="miješano" style="width:100%"></td>
                        <td><?php echo noriks_ty2_img_cell( $r['img'] ?? '' ); // phpcs:ignore ?></td>
                        <td><input type="text" data-f="unit" value="<?php echo esc_attr( $r['unit'] ?? '' ); ?>" placeholder="<?php echo esc_attr( $ph ); ?>" style="width:100%"></td>
                        <td style="font-size:12px;line-height:1.4">
                            <?php if ( ! $prod ) : ?>
                                <span style="color:#b32d2e">✗ SKU ne postoji</span>
                            <?php elseif ( ! $card ) : ?>
                                <span style="color:#b32d2e">✗ <?php echo esc_html( $prod->get_name() ); ?> — nije objavljen, nema cijenu ili nema boju »<?php echo esc_html( $r['color'] ?? '' ); ?>«</span>
                            <?php else : ?>
                                <a href="<?php echo esc_url( get_edit_post_link( $pid ) ); ?>" target="_blank"><?php echo esc_html( $prod->get_name() ); ?></a><br>
                                <?php if ( $card['img'] ) : ?><img src="<?php echo esc_url( $card['img'] ); ?>" alt="" style="width:44px;height:44px;object-fit:cover;float:left;margin:0 8px 0 0;border-radius:4px"><?php endif; ?>
                                <span style="color:#666">komadi: <?php echo esc_html( implode( ', ', noriks_ty2_colors_summary( $card['piece_colors'] ) ) ); ?></span><br>
                                <span style="color:#666">redovna <?php echo esc_html( number_format( $card['old'], 2, ',', '.' ) . ' ' . $cur ); ?> → ponuda <strong><?php echo esc_html( number_format( $card['new'], 2, ',', '.' ) . ' ' . $cur ); ?></strong> (<?php echo esc_html( number_format( $card['unit_new'], 2, ',', '.' ) ); ?> po komadu)</span>
                            <?php endif; ?>
                        </td>
                        <td><button type="button" class="button-link-delete ty2-del" title="Izbriši">✕</button></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <p>
                <button type="button" class="button" id="ty2-add">+ Dodaj ponudu</button>
            </p>
            <p>
                <button type="submit" class="button button-primary">Spremi ponude</button>
                <?php if ( $is_saved ) : ?>
                    <button type="submit" name="ty2_reset" value="1" class="button" onclick="return confirm('Vratiti na zadane ponude iz koda?');" style="margin-left:8px">Vrati na zadane</button>
                <?php endif; ?>
            </p>
        </form>
    </div>

    <script>
    (function(){
        var tb = document.querySelector('#ty2-table tbody');
        function renumber(){
            tb.querySelectorAll('tr').forEach(function(tr, i){
                tr.querySelectorAll('[data-f]').forEach(function(el){
                    el.name = 'ty2[' + i + '][' + el.getAttribute('data-f') + ']';
                });
            });
        }
        var imgCellTpl = <?php echo wp_json_encode( noriks_ty2_img_cell( '' ) ); ?>;

        // izbira slike iz knjiznice medijev — v polje gre ID priponke
        tb.addEventListener('click', function(e){
            var cell = e.target.closest('.ty2-img'); if (!cell) return;
            var prev = cell.querySelector('.ty2-img__prev'), val = cell.querySelector('.ty2-img__val'), clr = cell.querySelector('.ty2-img__clear');
            if (e.target.classList.contains('ty2-img__clear')) {
                val.value = ''; prev.hidden = true; prev.removeAttribute('src'); clr.hidden = true; return;
            }
            if (!e.target.classList.contains('ty2-img__pick') && e.target !== prev) return;
            if (!window.wp || !wp.media) { alert('Knjižnica medija se nije učitala.'); return; }
            var frame = wp.media({ title: 'Odaberi sliku ponude', button: { text: 'Upotrijebi ovu sliku' }, library: { type: 'image' }, multiple: false });
            frame.on('select', function(){
                var a = frame.state().get('selection').first().toJSON();
                val.value = a.id;
                prev.src = (a.sizes && a.sizes.thumbnail) ? a.sizes.thumbnail.url : a.url;
                prev.hidden = false; clr.hidden = false;
            });
            frame.open();
        });

        tb.addEventListener('click', function(e){
            var tr = e.target.closest('tr'); if (!tr) return;
            if (e.target.classList.contains('ty2-up') && tr.previousElementSibling) tb.insertBefore(tr, tr.previousElementSibling);
            if (e.target.classList.contains('ty2-down') && tr.nextElementSibling) tb.insertBefore(tr.nextElementSibling, tr);
            if (e.target.classList.contains('ty2-del') && confirm('Izbrisati ovu ponudu?')) tr.remove();
            renumber();
        });
        document.getElementById('ty2-add').addEventListener('click', function(){
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td><button type="button" class="button ty2-up">↑</button> <button type="button" class="button ty2-down">↓</button></td>' +
                '<td style="text-align:center"><input type="checkbox" data-f="active" value="1" checked><input type="hidden" data-f="uid" value=""></td>' +
                '<td><input type="text" data-f="sku" style="width:100%"></td>' +
                '<td><input type="number" data-f="qty" min="1" max="50" value="1" style="width:100%"></td>' +
                '<td><input type="text" data-f="cat" style="width:100%"></td>' +
                '<td><input type="text" data-f="label" style="width:100%"></td>' +
                '<td><input type="text" data-f="color" placeholder="miješano" style="width:100%"></td>' +
                '<td>' + imgCellTpl + '</td>' +
                '<td><input type="text" data-f="unit" placeholder="po komadu" style="width:100%"></td>' +
                '<td style="font-size:12px;color:#666">provjera nakon spremanja</td>' +
                '<td><button type="button" class="button-link-delete ty2-del">✕</button></td>';
            tb.appendChild(tr);
            renumber();
            tr.querySelector('[data-f=sku]').focus();
        });
        renumber();
    })();
    </script>
    <?php
}
