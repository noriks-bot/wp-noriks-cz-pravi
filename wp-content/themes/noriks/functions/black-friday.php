<?php
/**
 * NORIKS — Black Friday pristajalni strani.
 *
 *   /black-friday/         vsi izdelki: novi izdelki + majice in bokserice (oranzna)
 *   /black-friday-basics/  majice, bokserice in SlabProof nogavice        (zlata)
 *   /black-friday-shirts/  samo majice                                    (modra)
 *   /black-friday-boxers/  samo bokserice                                 (zelena)
 *   /black-friday-top9/    9 najbolj prodajanih izdelkov nasploh          (rdeca)
 *   /black-friday-tees/    enojne majice, vsaka barva svoj izdelek, ena cena za kos + SlabProof (limeta)
 *   /cyber-monday/         Cyber Monday: ostali (novi) izdelki            (cian)
 *   /cyber-monday-basics/  Cyber Monday: majice in bokserice              (vijola)
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

/** Pristajalne strani: slug => (naslov strani, tip vsebine, slog, dogodek bf = Black Friday / cm = Cyber Monday). */
function noriks_bf_strani() {
    return array(
        'black-friday'        => array( 'naslov' => 'Black Friday',        'tip' => 'vse',       'slog' => 'oranzna', 'dogodek' => 'bf' ),
        'black-friday-basics' => array( 'naslov' => 'Black Friday Basics', 'tip' => 'basics',    'slog' => 'zlata',   'dogodek' => 'bf' ),
        'black-friday-shirts' => array( 'naslov' => 'Black Friday Shirts', 'tip' => 'majice',    'slog' => 'modra',   'dogodek' => 'bf' ),
        'black-friday-boxers' => array( 'naslov' => 'Black Friday Boxers', 'tip' => 'bokserice', 'slog' => 'zelena',  'dogodek' => 'bf' ),
        'black-friday-top9'   => array( 'naslov' => 'Black Friday Top 9',  'tip' => 'top9',      'slog' => 'rdeca',   'dogodek' => 'bf' ),
        'black-friday-tees'   => array( 'naslov' => 'Black Friday Tees',   'tip' => 'enojne',    'slog' => 'limeta',  'dogodek' => 'bf' ),
        'cyber-monday'        => array( 'naslov' => 'Cyber Monday',        'tip' => 'novi',      'slog' => 'cian',    'dogodek' => 'cm' ),
        'cyber-monday-basics' => array( 'naslov' => 'Cyber Monday Basics', 'tip' => 'oblacila',  'slog' => 'vijola',  'dogodek' => 'cm' ),
    );
}

/** Nastavitve trenutne strani (privzeto: vse). */
function noriks_bf_stran() {
    $s = noriks_bf_strani();
    foreach ( $s as $slug => $c ) { if ( is_page( $slug ) ) { $c['slug'] = $slug; return $c; } }
    $c = $s['black-friday']; $c['slug'] = 'black-friday';
    return $c;
}

/** Besedila tega trga. {P} = najvecji popust, {D} = prag brezplacne dostave, {A} = ime atributa. */
function noriks_bf_l() {
    return array(
        'pct'        => '%d %%',
        'h1'         => 'NABÍDKY AŽ O {P} LEVNĚJI!',
        'sub'        => 'Naše nejprodávanější produkty za akční ceny – na jednom místě.',
        'gumb_hero'  => 'ZOBRAZIT NABÍDKY',
        'trak_p'     => 'Nabídka končí za',
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
        'sub2'       => 'Trička a pracovní ponožky za akční ceny – na jednom místě.',
        'b_k'        => 'BLACK FRIDAY · TRIČKA',
        'b_a'        => 'TRIČKA',
        'b_b'        => 'V AKCI',
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
        'sub_ob'     => 'Trička za akční ceny – na jednom místě.',
        'sub_kos'    => 'Všechna trička po {C} za kus – barvy a velikosti kombinujte libovolně.',
        'kos_b'      => 'PO {C}',
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

/* ------------------------------------------------------------------ *
 * Dejansko znizanje — SAMO za nakupe s teh pristajalnih strani.
 * Strani izdelkov, katalog in cene izdelkov ostanejo nespremenjeni.
 * ------------------------------------------------------------------ */
// Dodatni kolicinski popust (v %) na ceno za kos pri 2, 3 ter 4 in vec kosih — velja za izdelke brez izbirnika paketov.
if ( ! defined( 'NORIKS_BF_KOL_2' ) ) { define( 'NORIKS_BF_KOL_2', 5 ); }
if ( ! defined( 'NORIKS_BF_KOL_3' ) ) { define( 'NORIKS_BF_KOL_3', 10 ); }
if ( ! defined( 'NORIKS_BF_KOL_4' ) ) { define( 'NORIKS_BF_KOL_4', 15 ); }
if ( ! defined( 'NORIKS_BF_POPUST' ) ) { define( 'NORIKS_BF_POPUST', 35 ); }   // ciljni odstotek; lepa cena pristane med 30 in 40 %

/**
 * Cena za pristajalno stran: trenutna akcijska cena, znizana za ciljni odstotek in
 * zaokrozena na "lepo" ceno v istem slogu kot izhodiscna (39,99 -> 25,99; 9 990 -> 6 490;
 * 749 -> 489). Dejanski popust ostane med 30 in 40 %. Isto funkcijo uporabljata prikaz
 * na kartici in kosarica, zato se prikazana in zaracunana cena vedno ujemata.
 */
function noriks_bf_cena( $cena ) {
    return noriks_bf_lepa_cena( $cena, (float) apply_filters( 'noriks_bf_popust', NORIKS_BF_POPUST ), 5.5 );
}

/**
 * Ceno zniza za $pct odstotkov in jo zaokrozi na lepo ceno v slogu izhodiscne; ce bi lepa cena
 * od cilja odstopala za vec kot $odstop odstotnih tock, vrne natancno zaokrozen izracun.
 */
function noriks_bf_lepa_cena( $cena, $pct, $odstop ) {
    $cena = (float) $cena; $pct = (float) $pct;
    if ( $cena <= 0 || $pct <= 0 || $pct >= 100 ) { return $cena; }
    $dec  = function_exists( 'wc_get_price_decimals' ) ? (int) wc_get_price_decimals() : 2;
    $cilj = $cena * ( 1 - $pct / 100 );
    $cel  = floor( $cena + 0.0001 );
    $ost  = round( $cena - $cel, 2 );
    if ( $ost > 0.004 ) {
        $korak = 1;                                             // 39,99 -> X,99
    } else {
        $c = (int) round( $cena );
        if ( $c >= 1000 && in_array( $c % 100, array( 0, 90 ), true ) ) { $korak = 100; $ost = $c % 100; }   // 9 990 -> X 490
        elseif ( $c >= 100 ) { $korak = 10; $ost = $c % 10; }                                                // 749 -> XX9
        else { $korak = 1; $ost = 0; }
    }
    $spodaj = floor( ( $cilj - $ost ) / $korak ) * $korak + $ost;
    $kand   = array( $spodaj, $spodaj + $korak );
    if ( abs( $kand[1] - $cilj ) < abs( $kand[0] - $cilj ) - 0.0001 ) { $kand = array_reverse( $kand ); }
    foreach ( $kand as $k ) {
        if ( $k <= 0 ) { continue; }
        $d = ( 1 - $k / $cena ) * 100;
        if ( $d >= $pct - $odstop && $d <= $pct + $odstop ) { return round( $k, $dec ); }
    }
    return round( $cilj, $dec );
}

/**
 * Kolicinski popust pri izdelkih brez izbirnika paketov (paketi majic/bokseric, 1x-4x):
 * cena za kos pri $n kosih. Odstotki so v NORIKS_BF_KOLICINA (2 kosa, 3 kosi, 4 in vec).
 * Isto funkcijo uporabljata kartica in kosarica (tudi ko kupec kolicino spremeni v kosarici).
 */
function noriks_bf_cena_kolicina( $enota, $n ) {
    $enota = (float) $enota; $n = max( 1, (int) $n );
    $st    = (array) apply_filters( 'noriks_bf_kolicina', array( 2 => NORIKS_BF_KOL_2, 3 => NORIKS_BF_KOL_3, 4 => NORIKS_BF_KOL_4 ) );
    $dec   = function_exists( 'wc_get_price_decimals' ) ? (int) wc_get_price_decimals() : 2;
    $cena  = $enota;
    for ( $i = 2; $i <= min( $n, 4 ); $i++ ) {
        $pct = isset( $st[ $i ] ) ? (float) $st[ $i ] : 0;
        if ( $pct <= 0 || $pct >= 100 ) { continue; }
        $c = round( $enota * ( 1 - $pct / 100 ), $dec );
        if ( $c > 0 && $c < $cena ) { $cena = $c; }     // cena za kos z vecjo kolicino nikoli ne naraste
    }
    return $cena;
}

/** Cena za kos na strani enojnih majic (/black-friday-tees/), v valuti trga. */
function noriks_bf_kos_cena() {
    return (float) apply_filters( 'noriks_bf_kos_cena', 169 );
}

/** Ali je izdelek enojna majica (vsaka barva svoj izdelek: NORIKS-ONE-BLACK, -WHITE ...). */
function noriks_bf_je_enojna( $p ) {
    return $p && preg_match( '/^NORIKS-ONE-[A-Z]+$/', strtoupper( (string) $p->get_sku() ) ) === 1;
}

/**
 * Fiksna cena za kos, ce je nakup s strani enojnih majic IN je izdelek res enojna majica
 * (stran pride iz zahteve, zato izdelek preverimo — drug izdelek te cene ne dobi). Sicer 0.
 */
function noriks_bf_fiksna_cena( $slug, $product_id ) {
    $strani = noriks_bf_strani();
    if ( ! isset( $strani[ $slug ] ) || $strani[ $slug ]['tip'] !== 'enojne' ) { return 0.0; }
    return noriks_bf_je_enojna( wc_get_product( $product_id ) ) ? noriks_bf_kos_cena() : 0.0;
}

/** Slug pristajalne strani, s katere tece trenutno dodajanje v kosarico ('' = ni s teh strani). */
function noriks_bf_trenutna_stran( $nastavi = null ) {
    static $s = '';
    if ( $nastavi !== null ) { $s = (string) $nastavi; }
    return $s;
}

/** Vrstico kosarice oznaci (black friday / cyber monday) in ji zapise znizano ceno. */
add_filter( 'woocommerce_add_cart_item_data', 'noriks_bf_cart_item_data', 30, 3 );
function noriks_bf_cart_item_data( $data, $product_id, $variation_id ) {
    $slug = noriks_bf_trenutna_stran();
    if ( $slug === '' ) { return $data; }
    $strani = noriks_bf_strani();
    $data['_noriks_bf']       = ( isset( $strani[ $slug ]['dogodek'] ) && $strani[ $slug ]['dogodek'] === 'cm' ) ? 'cyber monday' : 'black friday';
    $data['_noriks_bf_stran'] = $slug;
    if ( isset( $data['_orto_bundle_unit_price'] ) ) {
        // izbirnik paketov: vticnik orto-product ceno vrstice bere iz teh dveh polj
        $nova = noriks_bf_cena( (float) $data['_orto_bundle_unit_price'] );
        $data['_orto_bundle_unit_price'] = $nova;
        $data['_orto_bundle_total']      = $nova;
    } else {
        $fiksna = noriks_bf_fiksna_cena( $slug, $product_id );
        if ( $fiksna > 0 ) {
            // enojne majice: ena cena za kos, brez kolicinskih stopenj
            $data['_noriks_bf_cena']   = $fiksna;
            $data['_noriks_bf_fiksna'] = 1;
        } else {
            $p = wc_get_product( $variation_id ? $variation_id : $product_id );
            if ( $p ) { $data['_noriks_bf_cena'] = noriks_bf_cena( (float) $p->get_price() ); }   // cena za 1 kos; kolicinski popust se doda po kolicini v kosarici
        }
    }
    return $data;
}

function noriks_bf_uveljavi_ceno( $item ) {
    if ( isset( $item['_noriks_bf_cena'], $item['data'] ) && $item['data'] instanceof WC_Product && (float) $item['_noriks_bf_cena'] > 0 ) {
        $kol = isset( $item['quantity'] ) ? (int) $item['quantity'] : 1;
        $item['data']->set_price( ! empty( $item['_noriks_bf_fiksna'] ) ? (float) $item['_noriks_bf_cena'] : noriks_bf_cena_kolicina( (float) $item['_noriks_bf_cena'], $kol ) );
    }
    return $item;
}
add_filter( 'woocommerce_add_cart_item', 'noriks_bf_uveljavi_ceno', 30 );
add_filter( 'woocommerce_get_cart_item_from_session', 'noriks_bf_iz_seje', 30, 2 );
function noriks_bf_iz_seje( $item, $values ) {
    foreach ( array( '_noriks_bf', '_noriks_bf_stran', '_noriks_bf_cena', '_noriks_bf_fiksna' ) as $k ) {
        if ( isset( $values[ $k ] ) ) { $item[ $k ] = $values[ $k ]; }
    }
    return noriks_bf_uveljavi_ceno( $item );
}
add_action( 'woocommerce_before_calculate_totals', 'noriks_bf_pred_izracunom', 10000 );
function noriks_bf_pred_izracunom( $cart ) {
    if ( ! $cart instanceof WC_Cart ) { return; }
    foreach ( $cart->get_cart() as $item ) { noriks_bf_uveljavi_ceno( $item ); }
}

/**
 * Kosarica: pri izdelkih brez izbire barve/velikosti (RedRelief, Bunion ...) vticnik v vrstici ne
 * pokaze, kateri paket je kupec izbral (kolicina vrstice ostane 1, cena je paketna). Za nakupe s
 * pristajalnih strani zato pod imenom izpisemo ime paketa, npr. "3x NORIKS RedRelief".
 */
add_filter( 'woocommerce_get_item_data', 'noriks_bf_prikaz_paketa', 20, 2 );
function noriks_bf_prikaz_paketa( $item_data, $cart_item ) {
    if ( empty( $cart_item['_noriks_bf'] ) ) { return $item_data; }
    if ( isset( $cart_item['_noriks_bf_cena'] ) ) {
        $pid = isset( $cart_item['product_id'] ) ? (int) $cart_item['product_id'] : 0;
        $kol = isset( $cart_item['quantity'] ) ? max( 1, (int) $cart_item['quantity'] ) : 1;
        // enojna majica: isti zapis kot pri orto izdelkih — ena ostevilcena vrstica na kos ("1: Siva - L")
        $kosi = noriks_bf_vrstice_enojne( $pid, $kol, $cart_item );
        if ( $kosi ) {
            // velikost je ze v vsaki vrstici, zato loceno "Veličina: L" iz razlicice umaknemo
            $vel = $kosi['velikost']; $ostalo = array();
            foreach ( $item_data as $d ) {
                if ( $vel !== '' && isset( $d['key'], $d['value'] ) && trim( wp_strip_all_tags( (string) $d['value'] ) ) === $vel ) { continue; }
                $ostalo[] = $d;
            }
            $ostalo[] = array( 'name' => false, 'display' => implode( '<br>', array_map( 'esc_html', $kosi['vrstice'] ) ) );
            return $ostalo;
        }
        // paket majic/bokseric: stevilo paketov in barve, npr. "2x (1x crna + 1x siva + 1x bijela)"
        $vrstica = noriks_bf_vrstica_kosov( $pid, $kol );
        if ( $vrstica !== '' ) { $item_data[] = array( 'name' => false, 'display' => esc_html( $vrstica ) ); }
        return $item_data;
    }
    if ( ! empty( $cart_item['_orto_lines'] ) || empty( $cart_item['_orto_bundle_pairs'] ) ) { return $item_data; }
    $naslov = isset( $cart_item['_orto_offer_title'] ) ? trim( (string) $cart_item['_orto_offer_title'] ) : '';
    if ( $naslov === '' ) { $naslov = (int) $cart_item['_orto_bundle_pairs'] . 'x'; }
    $item_data[] = array( 'name' => false, 'display' => esc_html( $naslov ) );
    return $item_data;
}

/**
 * Enojna majica v kosarici po kosih, v zapisu orto izdelkov: "1: Siva - L", "2: Siva - L" ...
 * Vrne array( 'vrstice' => [...], 'velikost' => 'L' ) ali null, ce izdelek ni en sam kos ene barve.
 * Pri vec kot 12 kosih ena vrstica ("15x Siva - L"), da kosarica ostane pregledna.
 */
function noriks_bf_vrstice_enojne( $product_id, $kol, $cart_item ) {
    $p  = $product_id ? wc_get_product( $product_id ) : null;
    $vs = $p ? noriks_bf_sestava( $p->get_sku() ) : '';
    if ( $vs === '' || preg_match( '/^1x ([^+·]+)$/u', $vs, $m ) !== 1 ) { return null; }
    $barva = trim( $m[1] );
    $barva = mb_strtoupper( mb_substr( $barva, 0, 1 ) ) . mb_substr( $barva, 1 );
    $vel = '';
    if ( ! empty( $cart_item['variation'] ) && is_array( $cart_item['variation'] ) ) {
        foreach ( $cart_item['variation'] as $ime => $vrednost ) {
            $vrednost = (string) $vrednost;
            if ( $vrednost === '' ) { continue; }
            $tax = str_replace( 'attribute_', '', urldecode( (string) $ime ) );
            if ( taxonomy_exists( $tax ) ) {
                $term = get_term_by( 'slug', $vrednost, $tax );
                if ( $term && ! is_wp_error( $term ) ) { $vrednost = $term->name; }
            }
            $vel = trim( $vrednost );
            break;
        }
    }
    $kos = $vel !== '' ? $barva . ' - ' . $vel : $barva;
    $kol = max( 1, (int) $kol );
    $vrstice = array();
    if ( $kol > 12 ) {
        $vrstice[] = $kol . 'x ' . $kos;
    } else {
        for ( $i = 1; $i <= $kol; $i++ ) { $vrstice[] = $i . ': ' . $kos; }
    }
    return array( 'vrstice' => $vrstice, 'velikost' => $vel );
}

/**
 * Vrstica "koliko kosov in kaj" za navaden izdelek s teh strani: enojna majica "4x siva",
 * paket pri vec kosih "2x (1x crna + 1x siva + 1x bijela)". Prazno, ce sestave izdelka ne poznamo.
 */
function noriks_bf_vrstica_kosov( $product_id, $kol ) {
    $p = $product_id ? wc_get_product( $product_id ) : null;
    if ( ! $p ) { return ''; }
    $vs  = noriks_bf_sestava( $p->get_sku() );
    $kol = max( 1, (int) $kol );
    if ( $vs === '' ) { return ''; }
    if ( preg_match( '/^1x [^+·]+$/u', $vs ) === 1 ) { return preg_replace( '/^1x /', $kol . 'x ', $vs ); }
    return $kol > 1 ? $kol . 'x (' . $vs . ')' : $vs;
}

/** Meta v narocilu: na postavki (_noriks_bf) in na narocilu (_noriks_bf + stran, s katere je kupec kupil). */
add_action( 'woocommerce_checkout_create_order_line_item', 'noriks_bf_meta_postavke', 20, 4 );
function noriks_bf_meta_postavke( $item, $cart_item_key, $values, $order ) {
    if ( empty( $values['_noriks_bf'] ) ) { return; }
    $dogodek = sanitize_text_field( $values['_noriks_bf'] );
    $item->add_meta_data( '_noriks_bf', $dogodek, true );
    if ( $order instanceof WC_Order ) {
        $order->update_meta_data( '_noriks_bf', $dogodek );
        $strani = array_filter( array_map( 'trim', explode( ',', (string) $order->get_meta( '_noriks_bf_stran' ) ) ) );
        $slug   = isset( $values['_noriks_bf_stran'] ) ? sanitize_title( $values['_noriks_bf_stran'] ) : '';
        if ( $slug !== '' && ! in_array( $slug, $strani, true ) ) { $strani[] = $slug; }
        $order->update_meta_data( '_noriks_bf_stran', implode( ',', $strani ) );
    }
}

/**
 * Barva vzorca za vrednost atributa (npr. "Crna" -> #000). Ista paleta kot barvni krogci
 * na strani izdelka: prepisana iz vticnika orto-product tega trga (.color-<slug>).
 */
function noriks_bf_barva( $vrednost ) {
    static $po_slugu = null, $po_vrednosti = null;
    if ( $po_slugu === null ) {
        $po_slugu     = array( 'black' => '#000', 'crna' => '#000', 'cerna' => '#000', 'blue' => '#203240', 'modra' => '#203240', 'plava' => '#203240', 'modra-cz' => '#203240', 'modr' => '#203240', 'tamnoplava' => '#2a3262', 'tmavemodra' => '#2a3262', 'tmave-modra' => '#2a3262', 'green' => '#294d3b', 'zelena' => '#294d3b', 'zelena-cz' => '#294d3b', 'zelen' => '#294d3b', 'gray' => '#706d78', 'siva' => '#706d78', 'seda' => '#706d78', 'crvena' => '#ba212f', 'cervena' => '#ba212f', 'white' => '#fff', 'bela' => '#fff', 'bijela' => '#fff', 'bila' => '#fff', 'b' => '#fff', 'bez' => '#e4e0cf', 'bezova' => '#e4e0cf', 'smeda' => '#9f6f4e', 'hneda' => '#9f6f4e', 'hn' => '#9f6f4e', 'roza' => '#e6a8b8', 'ljubicasta' => '#b48fc4', 'ruzova' => '#e6a8b8', 'fialova' => '#b48fc4', 'telova' => '#ecd9bd', 'fuchsiova' => '#d81b72', 'svestkova' => '#5b2147', 'tamnosiva' => '#4a4a4f', 'tmave-seda' => '#4a4a4f' );
        $po_vrednosti = array(  );
    }
    $vrednost = trim( (string) $vrednost );
    if ( isset( $po_vrednosti[ $vrednost ] ) ) { return $po_vrednosti[ $vrednost ]; }
    $slug = sanitize_title( $vrednost );
    return isset( $po_slugu[ $slug ] ) ? $po_slugu[ $slug ] : '';
}

/**
 * Vsebina paketa po SKU: majice (npr. "2x siva + 1x crna"), bokserice ("2x crne + 3x plave")
 * in kompleti ("Majice: ... · Bokserice: ..."). Sestave so preverjene po produktnih slikah
 * (tabela iz dash/fetch-sales.js PACK_COLORS + pregled slik 17. 11.); za SKU, ki ga tu ni, se ne izpise nic.
 * Majice:   K=crna S=siva W=bela B=temno modra G=zelena R=rjava E=bez
 * Bokserice: K=crne S=sive B=modre G=zelene D=rdece
 */
function noriks_bf_sestava( $sku ) {
    $sku = strtoupper( trim( (string) $sku ) );
    if ( $sku === '' ) { return ''; }
    $b5 = 'K1 S1 B1 G1 D1'; $b10 = 'K2 S2 B2 G2 D2';
    $t = array(
        // majice
        'NORIKS-MONOCHROME-3-PACK'    => array( 'K1 S1 W1', '' ),
        'NORIKS-MONOCHROME-6-PACK'    => array( 'K2 S2 W2', '' ),
        'NORIKS-MONOCHROME-9-PACK'    => array( 'K3 S3 W3', '' ),
        'NORIKS-MONOCHROME-DOZEN'     => array( 'K4 S4 W4', '' ),
        'NORIKS-ALL-BLACK-9-PACK-2'   => array( 'K3 S3 W3', '' ),   // SK/CZ/GR: Monochrome mix 9 (SKU je zavajajoc)
        'NORIKS-MIDNIGHT-3-PACK'      => array( 'K1 S1 B1', '' ),
        'NORIKS-URBAN-3-PACK'         => array( 'G1 S1 B1', '' ),
        'NORIKS-COASTAL-3-PACK'       => array( 'K1 W1 B1', '' ),
        'NORIKS-CITY-COMBO-6-PACK'    => array( 'K2 W2 S1 B1', '' ),
        'NORIKS-EVERYDAY-6-PACK'      => array( 'K1 S1 B1 G1 E1 W1', '' ),
        'NORIKS-EVERYDAY-MIX-12-PACK' => array( 'K2 S2 B2 G2 E2 W2', '' ),
        'NORIKS-EARTH-TONES-6-PACK'   => array( 'K3 B1 G1 E1', '' ),
        'NORIKS-EARTH-DOZEN'          => array( 'K6 B2 G2 E2', '' ),
        'NORIKS-NEUTRAL-MIX-9-PACK'   => array( 'S2 B2 G2 K1 R1 W1', '' ),
        'NORIKS-FULL-SPECTRUM-9-PACK' => array( 'K2 W2 S1 B1 G1 R1 E1', '' ),
        'NORIKS-STREET-PACK-9-PACK'   => array( 'K3 S1 B1 G1 R1 E1 W1', '' ),
        'NORIKS-FULL-BASICS-12-PACK'  => array( 'K3 S2 B2 W2 G1 R1 E1', '' ),
        'NORIKS-FULL-BASICS-15-PACK'  => array( 'K3 S2 B2 G2 R2 E2 W2', '' ),
        // bokserice
        'NORIKS-BOX-BUNDLE-3-FIRST'   => array( '', 'K1 S1 B1' ),
        'NORIKS-BOX-BUNDLE-3-SECOND'  => array( '', 'K1 S1 G1' ),
        'NORIKS-BOX-BUNDLE-3-THIRD'   => array( '', 'K1 B2' ),
        'NORIKS-BOX-BUNDLE-5-FIRST'   => array( '', 'K2 B3' ),
        'NORIKS-BOX-BUNDLE-5-SECOND'  => array( '', 'K2 S2 G1' ),
        'NORIKS-BOX-BUNDLE-7-FIRST'   => array( '', 'K2 B2 G2 S1' ),
        'NORIKS-BOX-BUNDLE-7-SECOND'  => array( '', 'K2 S2 B3' ),
        'NORIKS-BOX-BUNDLE-10-FIRST'  => array( '', 'K2 S2 B3 G2 D1' ),
        'NORIKS-BOX-BUNDLE-10-SECOND' => array( '', 'K5 B5' ),
        'NORIKS-BOX-BLACK-7-PACK-2'   => array( '', 'K2 S2 B3 G2 D1' ),
        'NORIKS-BOX-BLACK-7-PACK-3'   => array( '', 'K5 B5' ),
        'NORIKS-BOX-BUNDLE-15-FIRST'  => array( '', 'K5 B3 G3 S2 D2' ),
        'NORIKS-BOX-BUNDLE-15-SECOND' => array( '', 'K10 B5' ),
        // kompleti majice + bokserice
        'NORIKS-BUNDLE-SHIRTS-BOX-P-1'  => array( 'K1 W1', $b5 ),
        'NORIKS-BUNDLE-SHIRTS-BOX-P-2'  => array( 'K1 B1', $b5 ),
        'NORIKS-BUNDLE-SHIRTS-BOX-P-3'  => array( 'S1 W1', $b5 ),
        'NORIKS-BUNDLE-SHIRTS-BOX-P-4'  => array( 'K1 S1', $b5 ),
        'NORIKS-BUNDLE-SHIRTS-BOX-P-5'  => array( 'K1 S1', $b5 ),
        'NORIKS-BUNDLE-SHIRTS-BOX-P-4-1' => array( 'K1 S1', $b5 ),   // PL: ponudba za obstojece kupce
        'NORIKS-BUNDLE-SH-BOX-5-5-P-1'  => array( 'K2 S2 B1', $b5 ),
        'NORIKS-BUNDLE-SH-BOX-5-5-P-2'  => array( 'K2 W2 S1', $b5 ),
        'NORIKS-BUNDLE-SH-BOX-5-5-P-3'  => array( 'K1 R1 E1 G1 W1', $b5 ),
        'NORIKS-BUNDLE-SH-BOX-5-5-P-4'  => array( 'K5', 'K5' ),
        'NORIKS-BUNDLE-SH-BOX-4-10-P-1' => array( 'K2 W2', $b10 ),
        'NORIKS-BUNDLE-SH-BOX-4-10-P-2' => array( 'K2 B2', $b10 ),
        'NORIKS-BUNDLE-SH-BOX-4-10-P-3' => array( 'K2 S2', $b10 ),
        'NORIKS-BUNDLE-SH-BOX-4-10-P-4' => array( 'K1 S1 B1 W1', $b10 ),
    );
    $z = isset( $t[ $sku ] ) ? $t[ $sku ] : null;
    if ( ! $z ) {
        $enobarvne_m = array( 'BLACK' => 'K', 'WHITE' => 'W', 'GRAY' => 'S', 'GREY' => 'S', 'DARKBLUE' => 'B', 'GREEN' => 'G', 'BROWN' => 'R', 'BEIGE' => 'E' );
        $enobarvne_b = array( 'BLACK' => 'K', 'GRAY' => 'S', 'GREY' => 'S', 'BLUE' => 'B', 'GREEN' => 'G', 'RED' => 'D' );
        if ( preg_match( '/^NORIKS-ALL-(BLACK|WHITE)-(\d+)-PACK$/', $sku, $m ) ) { $z = array( $enobarvne_m[ $m[1] ] . (int) $m[2], '' ); }
        elseif ( preg_match( '/^NORIKS-BOX-BLACK-(\d+)-PACK$/', $sku, $m ) ) { $z = array( '', 'K' . (int) $m[1] ); }
        elseif ( preg_match( '/^NORIKS-ONE-([A-Z]+)$/', $sku, $m ) && isset( $enobarvne_m[ $m[1] ] ) ) { $z = array( $enobarvne_m[ $m[1] ] . '1', '' ); }
        elseif ( preg_match( '/^NORIKS-BOXERS-([A-Z]+)$/', $sku, $m ) && isset( $enobarvne_b[ $m[1] ] ) ) { $z = array( '', $enobarvne_b[ $m[1] ] . '1' ); }
    }
    if ( ! $z ) { return ''; }
    $barve = array( array( 'K' => 'černé', 'S' => 'šedé', 'W' => 'bílé', 'B' => 'tmavě modré', 'G' => 'zelené', 'R' => 'hnědé', 'E' => 'béžové' ), array( 'K' => 'černé', 'S' => 'šedé', 'B' => 'modré', 'G' => 'zelené', 'D' => 'červené' ) );
    $vrsti = array( 'Trička', 'Boxerky' );
    $deli  = array();
    foreach ( array( 0, 1 ) as $v ) {
        if ( $z[ $v ] === '' ) { $deli[ $v ] = ''; continue; }
        $kosi = array();
        foreach ( explode( ' ', $z[ $v ] ) as $d ) {
            $crka = substr( $d, 0, 1 ); $n = (int) substr( $d, 1 );
            if ( $n > 0 && isset( $barve[ $v ][ $crka ] ) ) { $kosi[] = $n . 'x ' . $barve[ $v ][ $crka ]; }
        }
        $deli[ $v ] = implode( ' + ', $kosi );
    }
    if ( $deli[0] !== '' && $deli[1] !== '' ) { return $vrsti[0] . ': ' . $deli[0] . ' · ' . $vrsti[1] . ': ' . $deli[1]; }
    return $deli[0] !== '' ? $deli[0] : $deli[1];
}

/**
 * 20.11.2026: izdelki, ki na strani izdelka nimajo izbire (seznam $gck_no_attrs v orto-product.php).
 * Odvecni atributi (npr. velikost bokov pri NeckRelax/ErgoSit) se zato tudi na BF kartici ne prikazejo.
 */
function noriks_bf_brez_izbire( $id ) {
    return has_term( array( 'orto-red', 'orto-kneeheat', 'orto-pal', 'orto-bowl', 'orto-winecooler', 'orto-cloath', 'orto-hyd', 'orto-cloud', 'orto-controlpro', 'orto-bunion', 'orto-fisiorest', 'orto-norikshers', 'orto-noriks-hers', 'orto-ortopedski-jastuk' ), 'product_cat', $id );
}

/**
 * Podatki za eno kartico ali null, ce izdelek ni za prikaz.
 * nacin: orto  = izbirnik paketov (posljemo bundle_option + pairs)
 *        var   = variabilen izdelek z enim atributom (posljemo atribut)
 *        simple= navaden izdelek brez izbire
 *        ogled = prevec izbir za kartico -> samo povezava na izdelek
 * $fiksna > 0: ena cena za kos (stran enojnih majic) namesto odstotnega znizanja in kolicinskih stopenj.
 */
/**
 * 22. 11. 2026 (Dejan): na nobeni Black Friday strani ni bokseric — ne samih bokseric
 * ne paketov, v katerih so bokserice (majice + bokserice, darila z boksericami, LeakBox).
 */
function noriks_bf_je_bokserica( $p ) {
    if ( ! $p ) { return false; }
    $id = $p->get_id();
    if ( noriks_bf_je_tipa( $id, array( 'bokserice', 'majice-bokserice-paketi' ) ) ) { return true; }
    if ( preg_match( '/BOX|SHGIFTS/i', (string) $p->get_sku() ) ) { return true; }
    return (bool) preg_match( '/bokser|boxer|boxerk|bokserk|μπόξερ/iu', (string) $p->get_name() );
}

function noriks_bf_kartica( $p, $fiksna = 0 ) {
    if ( ! $p || $p->get_status() !== 'publish' || ! $p->is_in_stock() ) { return null; }
    if ( noriks_bf_je_bokserica( $p ) ) { return null; }   // 22. 11. 2026: bokseric na BF straneh ni
    $id = $p->get_id();
    $k  = array( 'p' => $p, 'nacin' => 'ogled', 'attrs' => array(), 'offer' => '', 'kos' => 1, 'paket' => '', 'ponudbe' => array(), 'vsebina' => false );
    $red = 0; $akc = 0; $orto = false; $enota = 0;

    if ( function_exists( 'gck_is_orto_bundle_enabled' ) && function_exists( 'gck_get_bundle_offers' ) && gck_is_orto_bundle_enabled( $id ) ) {
        $ponudbe = gck_get_bundle_offers( $id );
        if ( ! empty( $ponudbe ) ) {
            $o    = reset( $ponudbe );
            $orto = true;
            $red  = (float) $o['regular'];
            $akc  = noriks_bf_cena( (float) $o['total'] );
            $k['offer'] = (string) $o['id'];
            $k['kos']   = max( 1, (int) $o['qty'] );
            $k['paket'] = trim( (string) $o['title'] );
            foreach ( $ponudbe as $po ) {
                $pr = (float) $po['regular']; $pa = (float) $po['total'];
                if ( $pr <= 0 || $pa <= 0 || $pa >= $pr ) { continue; }
                $k['ponudbe'][] = array( 'id' => (string) $po['id'], 'kos' => max( 1, (int) $po['qty'] ), 'paket' => trim( (string) $po['title'] ), 'red' => $pr, 'akc' => noriks_bf_cena( $pa ) );
            }
            $lastni = function_exists( 'gck_get_custom_attributes_in_order' ) ? gck_get_custom_attributes_in_order( $p ) : array();
            if ( noriks_bf_brez_izbire( $id ) ) { $lastni = array(); } // 20.11.: kot stran izdelka - npr. NeckRelax/ErgoSit brez izbire velikosti
            if ( count( $lastni ) > 2 && count( $lastni ) <= 4 && ! has_term( 'orto-majica-darila', 'product_cat', $id ) ) {
                // 3-4 izbire (npr. barva in velikost majice + bokseric): v vrstnem redu izdelka, izbira se v modalu
                foreach ( $lastni as $lk => $la ) {
                    $vr = array();
                    foreach ( (array) $la->get_options() as $x ) { $x = trim( (string) $x ); if ( $x !== '' ) { $vr[] = array( $x, $x ); } }
                    if ( $vr ) { $k['attrs'][] = array( 'p' => 'attribute_' . $lk, 'ime' => method_exists( $la, 'get_name' ) ? (string) $la->get_name() : (string) $lk, 'v' => $vr ); }
                }
                $k['nacin'] = 'orto';
            } elseif ( count( $lastni ) <= 2 && function_exists( 'gck_split_attrs_color_size' ) && ! has_term( 'orto-majica-darila', 'product_cat', $id ) ) {
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
            $enota = $fiksna > 0 ? (float) $fiksna : noriks_bf_cena( (float) $p->get_variation_price( 'min', false ) );
            $akc   = (float) wc_get_price_to_display( $p, array( 'price' => $enota ) );
            $va  = $p->get_variation_attributes();
            if ( count( $va ) >= 1 && count( $va ) <= 2 ) {
                $vsi = true;
                foreach ( $va as $ime => $vrednosti ) {
                    $ime = (string) $ime;
                    $vr  = array();
                    foreach ( (array) $vrednosti as $x ) {
                        $x = (string) $x; if ( $x === '' ) { continue; }
                        $napis = $x;
                        if ( taxonomy_exists( $ime ) ) { $t = get_term_by( 'slug', $x, $ime ); if ( $t && ! is_wp_error( $t ) ) { $napis = $t->name; } }
                        $vr[] = array( $x, $napis );
                    }
                    if ( ! $vr ) { $vsi = false; break; }
                    $k['attrs'][] = array( 'p' => 'attribute_' . sanitize_title( $ime ), 'ime' => wc_attribute_label( $ime, $p ), 'v' => $vr );
                }
                if ( $vsi ) { $k['nacin'] = 'var'; } else { $k['attrs'] = array(); }
            }
        } else {
            if ( $p->get_regular_price() === '' || $p->get_price() === '' ) { return null; }
            $red = (float) wc_get_price_to_display( $p, array( 'price' => $p->get_regular_price() ) );
            $enota = noriks_bf_cena( (float) $p->get_price() );
            $akc   = (float) wc_get_price_to_display( $p, array( 'price' => $enota ) );
            if ( $p->is_type( 'simple' ) && $p->is_purchasable() ) { $k['nacin'] = 'simple'; }
        }
    }

    if ( $red <= 0 || $akc <= 0 || $akc >= $red ) { return null; }
    $k['red'] = $red; $k['akc'] = $akc;
    $k['pop'] = (int) round( ( 1 - $akc / $red ) * 100 );
    // Kolicine: pri izbirniku paketov prave ponudbe izdelka, sicer 1x-4x iste cene.
    if ( ! $orto && in_array( $k['nacin'], array( 'var', 'simple' ), true ) ) {
        // $enota = znizana cena za 1 kos (brez davcnega prikaza), da kartica racuna enako kot kosarica
        for ( $n = 1; $n <= ( $fiksna > 0 ? 10 : 4 ); $n++ ) {      // enojne majice: 1-10 kosov (dve vrstici po 5)
            $kos = (float) wc_get_price_to_display( $p, array( 'price' => ( $fiksna > 0 ? $enota : noriks_bf_cena_kolicina( $enota, $n ) ) ) );
            $k['ponudbe'][] = array( 'id' => '', 'kos' => $n, 'paket' => '', 'red' => $red * $n, 'akc' => $kos * $n );
        }
    }
    if ( empty( $k['ponudbe'] ) ) { $k['ponudbe'][] = array( 'id' => $k['offer'], 'kos' => $k['kos'], 'paket' => $k['paket'], 'red' => $red, 'akc' => $akc ); }
    // Izdelki brez imena ponudbe (paketi majic, bokseric, kompleti): v vrstici pod naslovom pise vsebina paketa (barve).
    $brez_imena = true;
    foreach ( $k['ponudbe'] as $po ) { if ( $po['paket'] !== '' ) { $brez_imena = false; } }
    if ( $brez_imena ) {
        $vs = noriks_bf_sestava( $p->get_sku() );
        if ( $vs !== '' ) {
            $k['paket'] = $vs; $k['vsebina'] = true;
            // en kos ene barve ("1x crna"): pri vecji kolicini se stevilo ujema z izbrano kolicino ("3x crna")
            $en_kos = ! $orto && preg_match( '/^1x [^+·]+$/u', $vs ) === 1;
            foreach ( $k['ponudbe'] as $i => $po ) { $k['ponudbe'][ $i ]['paket'] = $en_kos ? preg_replace( '/^1x /', (int) $po['kos'] . 'x ', $vs ) : $vs; }
        }
    }
    $k['pop_max'] = $k['pop'];
    foreach ( $k['ponudbe'] as $i => $po ) {
        $pp = (int) round( ( 1 - $po['akc'] / $po['red'] ) * 100 );
        $k['ponudbe'][ $i ]['pop'] = $pp;
        if ( $k['nacin'] !== 'ogled' && $pp > $k['pop_max'] ) { $k['pop_max'] = $pp; }
    }
    // Barvni vzorci v izbirniku: atribut je barvni, ce ima vsaj polovica vrednosti znano barvo.
    foreach ( $k['attrs'] as $ai => $a ) {
        $b = array(); $zadetki = 0;
        foreach ( $a['v'] as $v ) { $c = noriks_bf_barva( $v[0] ); $b[] = $c; if ( $c !== '' ) { $zadetki++; } }
        $k['attrs'][ $ai ]['b'] = ( $zadetki > 0 && $zadetki * 2 >= count( $a['v'] ) ) ? $b : array();
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
 * Izloceni so izdelki za upsell (SKU z UPSELL/MYSTERY), starter paketi in vse ORTO strani ponudb
 * (tudi Black Friday kopija ORTO majice, ki je samostojen izdelek zunaj teh strani);
 * po prodaji padajoce. $tipi = kateri tipi (privzeto vsi), $s_slabproof = dodaj se
 * SlabProof in Tradesman nogavice (tudi ce so skrite).
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
        if ( preg_match( '/UPSELL|MYSTERY|STARTER/i', (string) $p->get_sku() ) || ! noriks_bf_je_tipa( $pid, $tipi ) ) { continue; }
        if ( has_term( array( 'starter-paketi', 'orto-starter' ), 'product_cat', $pid ) ) { continue; }   // starter paketov na teh straneh ni
        // ORTO strani ponudb (izbirnik paketov) niso na teh straneh — tudi Black Friday kopija majice (NORIKS-SHIRTS-ORTO-BF) ne; ta je samostojen izdelek.
        if ( function_exists( 'gck_is_orto_bundle_enabled' ) && gck_is_orto_bundle_enabled( $pid ) ) { continue; }
        $k = noriks_bf_kartica( $p );
        if ( ! $k ) { continue; }
        $k['vrsta'] = $n++;
        $ven[] = $k;
        $dodani[ $pid ] = true;
    }
    $ven = noriks_bf_uredi( $ven );
    if ( $s_slabproof && function_exists( 'wc_get_product_id_by_sku' ) ) {
        // na koncu se delovne nogavice: SlabProof in Tradesman (z njima je zadnja vrsta polna)
        foreach ( array( 'NORIKS-SLABPROOF', 'NORIKS-SLABPROOF-2' ) as $ssku ) {
            $sid = wc_get_product_id_by_sku( $ssku );
            if ( $sid && ! isset( $dodani[ $sid ] ) ) {
                $k = noriks_bf_kartica( wc_get_product( $sid ) );
                if ( $k ) { $k['vrsta'] = -1; $ven[] = $k; $dodani[ $sid ] = true; }
            }
        }
    }
    return $ven;
}

/** Enojne majice (vsaka barva svoj izdelek) po eni ceni za kos, po prodaji padajoce; na koncu SlabProof nogavice. */
function noriks_bf_enojne() {
    $ven = array();
    if ( ! function_exists( 'wc_get_product_id_by_sku' ) ) { return $ven; }
    $cena = noriks_bf_kos_cena();
    foreach ( array( 'BLACK', 'DARKBLUE', 'WHITE', 'GRAY', 'BROWN', 'GREEN', 'BEIGE' ) as $i => $barva ) {
        $id = wc_get_product_id_by_sku( 'NORIKS-ONE-' . $barva );
        $p  = $id ? wc_get_product( $id ) : null;
        if ( ! $p || ! $p->is_type( 'variable' ) ) { continue; }
        $k = noriks_bf_kartica( $p, $cena );
        if ( ! $k || $k['nacin'] !== 'var' ) { continue; }
        $k['vrsta'] = $i;
        $ven[] = $k;
    }
    $ven = noriks_bf_uredi( $ven );
    // osmi izdelek (polna zadnja vrsta): SlabProof nogavice po Black Friday ceni kot na ostalih straneh
    $sid = wc_get_product_id_by_sku( 'NORIKS-SLABPROOF' );
    $sk  = $sid ? noriks_bf_kartica( wc_get_product( $sid ) ) : null;
    if ( $sk ) { $sk['vrsta'] = -1; $ven[] = $sk; }
    return $ven;
}

/** Najbolj prodajani izdelki nasploh (novi + majice in bokserice skupaj). */
function noriks_bf_top( $n = 9 ) {
    $vsi = array(); $videni = array();
    $oblacila = noriks_bf_oblacila();
    foreach ( array_merge( noriks_bf_novi(), $oblacila ) as $k ) {
        $id = $k['p']->get_id();
        if ( isset( $videni[ $id ] ) ) { continue; }
        $videni[ $id ] = true;
        $vsi[] = $k;
    }
    $top = noriks_bf_uredi( $vsi, $n );
    // Za najboljsimi $n se trije stalni: najbolje prodajani 6-paket majic, najbolje prodajani
    // paket bokseric in Tradesman nogavice (brez podvajanja, ce je kateri ze med najboljsimi).
    $v_top = array();
    foreach ( $top as $k ) { $v_top[ $k['p']->get_id() ] = true; }
    $dodatni = array(); $majice = null; $bokserice = null;
    foreach ( $oblacila as $k ) {      // ze urejeno po prodaji padajoce
        $sku = (string) $k['p']->get_sku(); $id = $k['p']->get_id();
        if ( ! $majice && preg_match( '/-6-PACK/i', $sku ) && noriks_bf_je_tipa( $id, array( 'majice' ) ) ) { $majice = $k; }
        // namesto paketa bokseric (22. 11. 2026): drugi najbolje prodajani paket majic
        if ( $majice && ! $bokserice && $k !== $majice && preg_match( '/PACK|BUNDLE/i', $sku ) && noriks_bf_je_tipa( $id, array( 'majice' ) ) ) { $bokserice = $k; }
    }
    if ( $majice ) { $dodatni[] = $majice; }
    if ( $bokserice ) { $dodatni[] = $bokserice; }
    if ( function_exists( 'wc_get_product_id_by_sku' ) ) {
        $tid = wc_get_product_id_by_sku( 'NORIKS-SLABPROOF-2' );   // NORIKS Tradesman
        $tk  = $tid ? noriks_bf_kartica( wc_get_product( $tid ) ) : null;
        if ( $tk ) { $tk['vrsta'] = 0; $dodatni[] = $tk; }
    }
    foreach ( $dodatni as $k ) {
        $id = $k['p']->get_id();
        if ( isset( $v_top[ $id ] ) ) { continue; }
        $v_top[ $id ] = true; $k['dodatni'] = true; $top[] = $k;
    }
    return $top;
}

/**
 * Podatki za modal "Poglej vec": slike galerije in opis izdelka.
 * Vgradijo se ze v stran (page-black-friday.php), da se modal odpre takoj — klic
 * admin-ajax je trajal 0,7–1,3 s na odpiranje (izmerjeno 17. 11. 2026) in ostane le kot rezerva.
 */
function noriks_bf_info_podatki( $p ) {
    $slike = array();
    $ids   = array_values( array_unique( array_filter( array_merge( array( (int) $p->get_image_id() ), array_map( 'intval', (array) $p->get_gallery_image_ids() ) ) ) ) );
    foreach ( array_slice( $ids, 0, 8 ) as $iid ) {
        $v = wp_get_attachment_image_url( $iid, 'woocommerce_single' );
        $m = wp_get_attachment_image_url( $iid, 'woocommerce_thumbnail' );
        if ( $v ) { $slike[] = array( $v, $m ? $m : $v ); }
    }
    return array(
        'slike'  => $slike,
        'kratek' => wp_kses_post( wpautop( (string) $p->get_short_description() ) ),
        'opis'   => wp_kses_post( wpautop( (string) $p->get_description() ) ),
    );
}
add_action( 'wp_ajax_noriks_bf_info', 'noriks_bf_info' );
add_action( 'wp_ajax_nopriv_noriks_bf_info', 'noriks_bf_info' );
function noriks_bf_info() {
    $id = isset( $_REQUEST['id'] ) ? absint( $_REQUEST['id'] ) : 0;
    $p  = $id ? wc_get_product( $id ) : null;
    if ( ! $p || $p->get_status() !== 'publish' ) { wp_send_json_error(); }
    wp_send_json_success( array( 'ime' => $p->get_name(), 'url' => get_permalink( $id ) ) + noriks_bf_info_podatki( $p ) );
}

/**
 * Stran izdelka Black Friday majice (kopija NORIKS-SHIRTS-ORTO-BF): crn trak z odstevalnikom na vrhu strani.
 * Cas vzame iz odstevalnika, ki ga na strani ze izpise vticnik (oba kazeta isto); ce ga ni, odsteva do polnoci.
 */
add_action( 'woocommerce_before_main_content', 'noriks_bf_trak_izdelka', 4 );
function noriks_bf_trak_izdelka() {
    if ( ! function_exists( 'is_product' ) || ! is_product() ) { return; }
    $p = wc_get_product( get_queried_object_id() );
    if ( ! $p || $p->get_sku() !== 'NORIKS-SHIRTS-ORTO-BF' ) { return; }
    $l = noriks_bf_l();
    ?>
    <div class="nbfp" role="region" aria-label="Black Friday">
      <span class="nbfp__pika" aria-hidden="true"></span>
      <span class="nbfp__bf">BLACK <b>FRIDAY</b></span>
      <span class="nbfp__t"><?php echo esc_html( $l['trak_p'] ); ?></span>
      <span class="nbfp__cas" id="nbfp-cas" aria-live="off">--:--</span>
    </div>
    <style>
      .nbfp{background:#000;color:#fff;display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:6px 12px;padding:11px 14px;border-top:2px solid #ff8a00;border-bottom:2px solid #ff8a00;font-family:inherit;line-height:1.2;text-align:center}
      .nbfp__pika{width:10px;height:10px;border-radius:50%;background:#ff3b30;box-shadow:0 0 0 0 rgba(255,59,48,.7);animation:nbfpPika 1.4s ease-out infinite;flex:none}
      .nbfp__bf{font-weight:900;font-size:18px;letter-spacing:3px}
      .nbfp__bf b{color:#ff8a00;font-weight:900}
      .nbfp__t{font-weight:600;font-size:15px;color:#f1f1f1}
      .nbfp__cas{background:#ffd400;color:#000;font-weight:900;font-size:19px;padding:4px 11px;border-radius:5px;font-variant-numeric:tabular-nums;letter-spacing:1px;min-width:74px}
      @keyframes nbfpPika{0%{box-shadow:0 0 0 0 rgba(255,59,48,.7)}100%{box-shadow:0 0 0 12px rgba(255,59,48,0)}}
      @media (prefers-reduced-motion:reduce){.nbfp__pika{animation:none}}
      @media (max-width:560px){.nbfp{padding:9px 10px;gap:5px 9px}.nbfp__bf{font-size:15px;letter-spacing:2px}.nbfp__t{font-size:13px}.nbfp__cas{font-size:16px;padding:3px 9px;min-width:62px}}
    </style>
    <script data-no-optimize="1" data-no-defer="1" data-cfasync="false">
    (function(){
      var el = document.getElementById('nbfp-cas'); if (!el) { return; }
      function p(n){ return (n < 10 ? '0' : '') + n; }
      function tik(){
        var v = document.querySelector('.gck-countdown__timer'), t = v ? (v.textContent || '').trim() : '';
        if (/^\d{1,2}:\d{2}/.test(t)) { el.textContent = t; return; }
        var d = new Date(), k = new Date(d.getFullYear(), d.getMonth(), d.getDate() + 1), s = Math.max(0, Math.floor((k - d) / 1000));
        el.textContent = p(Math.floor(s / 3600)) + ':' + p(Math.floor(s % 3600 / 60)) + ':' + p(s % 60);
      }
      tik(); setInterval(tik, 500);
    })();
    </script>
    <?php
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
    // Vse, kar pride po tej poti, je nakup s pristajalne strani: znizana cena + oznaka v kosarici/narocilu.
    $slug   = isset( $_POST['stran'] ) ? sanitize_title( wp_unslash( $_POST['stran'] ) ) : '';
    $strani = noriks_bf_strani();
    noriks_bf_trenutna_stran( isset( $strani[ $slug ] ) ? $slug : 'black-friday' );

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

    noriks_bf_trenutna_stran( '' );
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

/** Enkrat ustvari vse WP strani in jim dodeli predlogo page-black-friday.php. */
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
