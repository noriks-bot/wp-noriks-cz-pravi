<?php
/**
 * Thankyou page — Post-purchase upsell with two-step flow
 *
 * Step 1: Single product offer (crna majica, ali +1 kupljenega izdelka, npr. KOMPSFIT)
 * Step 2: product grid (after "Nechci" or after adding 1 item) — prenos s HR 17. 11. 2026
 *
 * Style: Red background, no border-radius, red buttons
 *
 * @package WooCommerce\Templates
 * @version 8.1.0
 * @var WC_Order $order
 */
defined( 'ABSPATH' ) || exit;

// Zapis cene v valuti trga (korak 1, korak 2 in vrstica kosarice uporabljajo istega).
if ( ! function_exists( 'noriks_ty_cena' ) ) {
    function noriks_ty_cena( $n ) { return number_format( (float) $n, 0, ',', ' ' ) . ' Kč'; }
}

// ─── Korak 1: kaj ponudimo ───
// Vedno CRNA MAJICA. Izjema: ce je kupec kupil izdelek s seznama noriks_ty2_step1_products()
// (npr. KOMPSFIT), mu ponudimo se en kos ISTEGA izdelka. Bokseric v koraku 1 ni vec (17. 11. 2026).
$s1_cards = function_exists( 'noriks_ty2_step1_cards' ) ? noriks_ty2_step1_cards( $order ) : array();
$upsell_is_majice = empty( $s1_cards );
$s1_keys = array(); $s1_regular = array(); $s1_color = ''; $s1_sizes = array(); $s1_colors = array();

if ( $s1_cards ) {
    // +1 istega izdelka: cene, slike in dodajanje gredo prek kartic (isti mehanizem kot korak 2)
    $s1_first          = reset( $s1_cards );
    $upsell_product_id = (int) $s1_first['product_id'];
    $upsell_name       = (string) $s1_first['label'];
    $upsell_qty_prices = $upsell_qty_names = $upsell_qty_images = array();
    foreach ( $s1_cards as $s1_q => $s1_c ) {
        $upsell_qty_prices[ $s1_q ] = (float) $s1_c['new'];
        $upsell_qty_names[ $s1_q ]  = (string) $s1_c['label'];
        $upsell_qty_images[ $s1_q ] = (string) $s1_c['img'];
        $s1_regular[ $s1_q ]        = (float) $s1_c['old'];
        $s1_keys[ $s1_q ]           = (string) $s1_c['key'];
    }
    $s1_color = (string) ( $s1_first['piece_colors'][0] ?? '' );
    $s1_sizes = (array) $s1_first['sizes'];
    // izdelki z izbiro barve (npr. Slim): kupec izbere barvo in velikost
    $s1_colors = ! empty( $s1_first['pick_color'] ) ? (array) $s1_first['colors'] : array();
    $upsell_title_text = ! empty( $s1_first['bf_test'] ) ? 'Přidejte FIT kompresní tričko – speciální cena' : 'Přidejte ještě jeden kus – speciální cena';
} else {
    $upsell_product_id = 250; // Crna majica (variable)
    $upsell_name       = 'Černá trička';
    $upsell_qty_prices = array( 1 => 199, 3 => 499, 6 => 829 );
    $upsell_qty_names  = array( 1 => '1x Černé tričko', 3 => '3x Černá trička', 6 => '6x Černých triček' );
    $upsell_qty_images = array(
        1 => 'https://noriks.com/hr/wp-content/uploads/2025/09/black-1.jpg',
        3 => 'https://noriks.com/hr/wp-content/uploads/2025/09/black-3x.jpg',
        6 => 'https://noriks.com/hr/wp-content/uploads/2026/01/15xcrnamajica.png',
    );
    $upsell_title_text = 'Přidejte trička nyní – 50% sleva';
}
$upsell_product    = wc_get_product( $upsell_product_id );
$upsell_image      = reset( $upsell_qty_images );
// Get unit price from first variation (variable products have empty parent price)
$upsell_unit_price = 15.99;
if ( $upsell_product && $upsell_product->is_type('variable') ) {
    $var_prices = $upsell_product->get_variation_prices();
    $upsell_unit_price = !empty($var_prices['regular_price']) ? (float) reset($var_prices['regular_price']) : (float) $upsell_product->get_price();
} elseif ( $upsell_product ) {
    $upsell_unit_price = (float) $upsell_product->get_regular_price() ?: (float) $upsell_product->get_price();
}
$upsell_sale_price = reset( $upsell_qty_prices );
// Regular prices per qty (unit price * qty)
$upsell_qty_regular = array();
foreach ($upsell_qty_prices as $q => $p) {
    $upsell_qty_regular[$q] = $upsell_unit_price * $q;
}
// +1 istega izdelka: precrtana cena je akcijska cena izdelka (iz kartice), znacka pa dejanski popust
$upsell_badge_pct = 50;
if ( $s1_cards ) {
    $upsell_qty_regular = $s1_regular;
    $s1_q1 = array_key_first( $upsell_qty_prices );
    if ( ! empty( $s1_regular[ $s1_q1 ] ) ) { $upsell_badge_pct = (int) round( ( 1 - $upsell_qty_prices[ $s1_q1 ] / $s1_regular[ $s1_q1 ] ) * 100 ); }
} else {
    // crna majica: znacka kaze dejanski popust prve opcije
    $m_q1 = array_key_first( $upsell_qty_prices );
    if ( ! empty( $upsell_qty_regular[ $m_q1 ] ) ) { $upsell_badge_pct = (int) round( ( 1 - $upsell_qty_prices[ $m_q1 ] / $upsell_qty_regular[ $m_q1 ] ) * 100 ); }
}
$default_qty = array_key_first($upsell_qty_prices); // 1

// Helper: extract a size value (S/M/L/XL/...) from any associative array.
// Works for both variation attribute arrays and line item meta arrays.
// Pass 1: attribute KEY hints (velicina / size / marime / vel / meret).
// Pass 2: scan VALUES — direct match against size patterns, OR token
//         match (so bundle / orto values like "Crna - M" or
//         "Μαύρο - 3XL" still yield the size).
if ( ! function_exists( 'noriks_extract_size_value' ) ) {
    function noriks_extract_size_value( $attrs ) {
        if ( ! is_array( $attrs ) || empty( $attrs ) ) return '';

        $size_key_hints = array( 'velicina', 'size', 'marime', 'vel', 'meret' );
        $size_patterns  = array( 'xs', 's', 'm', 'l', 'xl', 'xxl', 'xxxl', 'xxxxl', '2xl', '3xl', '4xl', '5xl' );

        // Pass 1: KEY hints — when an attribute key clearly names size,
        // accept its value (token-extracted if the value is composite)
        foreach ( $attrs as $k => $v ) {
            $kl = strtolower( (string) $k );
            $key_is_size = false;
            foreach ( $size_key_hints as $hint ) {
                if ( strpos( $kl, $hint ) !== false ) { $key_is_size = true; break; }
            }
            if ( ! $key_is_size ) continue;
            $vs = trim( (string) $v );
            if ( $vs === '' ) continue;
            if ( in_array( strtolower( $vs ), $size_patterns, true ) ) return $vs;
            // Token scan inside the value
            $tokens = preg_split( '/[\s\-,;\/|·_]+/u', $vs );
            if ( is_array( $tokens ) ) {
                foreach ( $tokens as $tok ) {
                    $tok = trim( $tok );
                    if ( $tok !== '' && in_array( strtolower( $tok ), $size_patterns, true ) ) {
                        return $tok;
                    }
                }
            }
            // Size-keyed attribute, no recognised token in value — return
            // the raw value so it still drives the dropdown selection.
            return $vs;
        }

        // Pass 2: scan all VALUES — direct or tokenised match
        foreach ( $attrs as $v ) {
            $vs = trim( (string) $v );
            if ( $vs === '' ) continue;
            if ( in_array( strtolower( $vs ), $size_patterns, true ) ) return $vs;
            $tokens = preg_split( '/[\s\-,;\/|·_]+/u', $vs );
            if ( is_array( $tokens ) ) {
                foreach ( $tokens as $tok ) {
                    $tok = trim( $tok );
                    if ( $tok !== '' && in_array( strtolower( $tok ), $size_patterns, true ) ) {
                        return $tok;
                    }
                }
            }
        }

        return '';
    }
}

// Variations for primary product
$upsell_variations = array();
if ( $upsell_product && $upsell_product->is_type('variable') ) {
    foreach ( $upsell_product->get_available_variations() as $v ) {
        $size = noriks_extract_size_value( isset( $v['attributes'] ) ? $v['attributes'] : array() );
        $upsell_variations[] = array( 'id' => $v['variation_id'], 'size' => $size );
    }
}

// Detect customer size from order — first ordered product that yields a
// recognised size wins. We try FIVE sources per item, in order:
//   (A) variation attributes (variable products: bokserice, tricou)
//   (B) raw line item meta (bundle / orto: "Crna - M", "Μαύρο - 3XL")
//   (C) formatted meta display values (covers private/underscored keys)
//   (D) product name (e.g. "Tricou Negru M")
//   (E) SKU (e.g. "BOX-3XL", "NORIKS-SHIRTS-M")
// If a sized clothing item is in the cart, one of these will hit.
$customer_size = '';
if ( $order ) {
    foreach ( $order->get_items() as $item ) {
        if ( ! is_a( $item, 'WC_Order_Item_Product' ) ) continue;

        $detected = '';

        // (A) Variation attributes
        if ( $item->get_variation_id() ) {
            $var = wc_get_product( $item->get_variation_id() );
            if ( $var ) {
                $detected = noriks_extract_size_value( (array) $var->get_attributes() );
            }
        }

        // (B) Raw line item meta (handles bundle / orto)
        if ( $detected === '' ) {
            $meta_values = array();
            foreach ( $item->get_meta_data() as $meta ) {
                $key = is_object( $meta ) && isset( $meta->key ) ? (string) $meta->key : '';
                $val = ( is_object( $meta ) && isset( $meta->value ) && is_scalar( $meta->value ) ) ? (string) $meta->value : '';
                if ( $key !== '' ) {
                    $meta_values[ $key ] = $val;
                }
            }
            if ( ! empty( $meta_values ) ) {
                $detected = noriks_extract_size_value( $meta_values );
            }
        }

        // (C) Formatted meta display values
        if ( $detected === '' ) {
            $fmt = array();
            $list = $item->get_formatted_meta_data( '', true );
            if ( is_array( $list ) ) {
                $i = 0;
                foreach ( $list as $m ) {
                    if ( ! is_object( $m ) ) continue;
                    $key = isset( $m->key ) ? (string) $m->key : '';
                    $val = isset( $m->display_value ) ? wp_strip_all_tags( (string) $m->display_value ) : '';
                    if ( $val === '' ) continue;
                    $fmt[ $key !== '' ? $key : 'fmt_' . $i ] = $val;
                    $i++;
                }
            }
            if ( ! empty( $fmt ) ) {
                $detected = noriks_extract_size_value( $fmt );
            }
        }

        // (D) Product name
        if ( $detected === '' ) {
            $name = (string) $item->get_name();
            if ( $name !== '' ) {
                $detected = noriks_extract_size_value( array( 'name' => $name ) );
            }
        }

        // (E) SKU
        if ( $detected === '' ) {
            $product = $item->get_product();
            if ( $product ) {
                $sku = (string) $product->get_sku();
                if ( $sku !== '' ) {
                    $detected = noriks_extract_size_value( array( 'sku' => $sku ) );
                }
            }
        }

        if ( $detected !== '' ) {
            $customer_size = $detected;
            break;
        }
    }
}

// ─── Korak 2: ponudba izdelkov ───────────────────────────────────────
// Ponudbe se urejajo v adminu: WooCommerce → Ponudbe po nakupu (functions/thankyou_offers.php).
// Kartice in cene se racunajo na strezniku; ob dodajanju strežnik ceno izracuna znova po kljucu kartice.
$grid_cards = function_exists( 'noriks_ty2_build_cards' )
    ? noriks_ty2_build_cards( $order, isset( $upsell_product_id ) ? $upsell_product_id : 0 )
    : array();

$grid_products = $grid_cards; // zdruzljivost z obstojecim pogojem nize

// Najvecji popust med karticami (za glavo "Do X % popusta") — racuna se iz dejanskih cen
$grid_max_pct = 0;
foreach ( $grid_cards as $_gc ) {
    if ( $_gc['old'] > 0 ) { $grid_max_pct = max( $grid_max_pct, (int) floor( ( 1 - $_gc['new'] / $_gc['old'] ) * 100 ) ); }
}
if ( $grid_max_pct <= 0 ) { $grid_max_pct = 50; }
?>

<!-- vendor upsell CSS removed — using inline styles only -->

<style>
/* ═══ RESET: hide WP chrome ═══ */
.top-header, .marquee, header.navbar.header, #languageModal,
.xoo-wsc-markup, .xoo-wsc-overlay, .footer-wrap, footer.footer,
footer.footer-mobile, .hs_loader, .entry-header,
.storefront-breadcrumb, .storefront-sorting,
#secondary, .site-footer, .xoo-wsc-container,
.checkout--my-header,
.woocommerce-order-details,
.woocommerce-customer-details { display: none !important; }

body.woocommerce-order-received {
    background: #F5F5F5 !important;
    font-family: 'Roboto', sans-serif !important;
    color: #333 !important;
    -webkit-font-smoothing: antialiased;
}
body.woocommerce-order-received .site-main,
body.woocommerce-order-received .hentry {
    margin: 0 !important; padding: 0 !important;
}
body.woocommerce-order-received .woocommerce {
    background: transparent !important; padding: 0 !important;
}

/* border-radius handled per element — removed global kill */

/* ═══ Container ═══ */
.ty-container { max-width: 520px; margin: 30px auto; padding: 0 4px; }
/* korak 2 potrebuje siroko postavitev — mreza gre cez celo sirino strani */
.ty-container.grid-active { max-width: 1440px; padding: 0 16px; }

/* ═══ Success ═══ */
.ty-success {
    background: #e8f5e9;
    padding: 6px 8px; margin-bottom: 0; text-align: center;
    border-radius: 4px !important;
}
@media (max-width:576px) { .ty-success { margin-left:0; margin-right:0; padding-left:0; padding-right:0; border-radius:0 !important; } }
.ty-success-icon {
    width: 20px; height: 20px; background: #4CAF50;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 12px; color: #fff; border-radius: 4px;
    vertical-align: middle; margin-right: 6px;
}
.ty-success h1 {
    font-size: 16px !important; font-weight: 700 !important;
    color: #232f3e !important; margin: 0 0 4px !important; display: inline; vertical-align: middle;
}
.ty-success p { font-size: 12px; color: #5f6061; margin: 4px 0 0; }
.ty-success .ty-order-num {
    display: inline-block; margin-top: 6px;
    background: #fff; padding: 4px 12px;
    font-size: 11px; color: #333; font-weight: 600;
}

    /* ═══ UPSELL STEP 1 — inline, no modal ═══ */
    .ty_upsell_one_wrapper { background:#fff; border-radius:4px; margin:0; }
    .ty_upsell_one_wrapper.show { display:block; }
    .ty_upsell_one_wrapper.hide { display:none; }
    .ty_upsell_one_wrapper__popup-content { background:#fff; }
    .tyuo_timer { background-color:#f39c1217; border:2px solid #f39c12; border-radius:4px; color:#000; padding:10px; margin-bottom:15px; text-align:center; }
    .tyuo_timer .timer_wrapper { display:flex; align-items:baseline; justify-content:center; }
    .tyuo_timer .special_offer_txt { font-size:16px; margin-right:5px; color:#000; }
    .tyuo_timer .time { background-color:#e22b26; border-radius:4px; color:#fff; padding:2px 8px; }
    .tyuo_timer .title { font-size:22px; font-weight:700; line-height:26px; margin-bottom:5px; padding-top:10px; text-align:center; color:#000; }
    .tyuo_middle_section { padding:0 5px; text-align:center; }
    .tyuo_middle_section svg { margin-right:5px; max-width:15px; }
    .tyuo_middle_section .sub_title { color:#000; font-size:16px; font-weight:500; }
    .tyuo_middle_section .clue_text { color:#000; font-size:16px; font-weight:500; margin:6px 0; }
    .tyuo_product_section { margin:15px 0; padding:0 15px; }
    .tyuo_product_section .product_data { display:flex; margin-bottom:10px; }
    .tyuo_product_section .product_data .img { margin-right:15px; max-width:150px; width:50%; }
    .tyuo_product_section .product_data .img img { border-radius:4px; aspect-ratio:1/1; object-fit:cover; object-position:center; width:100%; }
    .tyuo_product_section .right_section_wrapper { display:flex; flex-direction:column; line-height:29px; width:50%; }
    .tyuo_product_section .quantity { display:flex; flex-direction:column; font-size:2em; }
    .tyuo_product_section .product_name { font-size:18px; font-weight:700; color:#1A1A1A; line-height:20px; margin:4px 0 10px; }
    .tyuo_product_section .product_regular_price { color:#8f8f8f; font-size:17px; text-decoration:line-through; }
    .tyuo_product_section .product_new_sale_price { color:#c00; font-size:25px; font-weight:700; }
    .wrapper_selectbox { color:#5f6060; font-size:1.1em; font-weight:500; padding:0 0 10px; }
    .wrapper_selectbox { text-align:right; padding:0 0 10px; }
    .wrapper_selectbox select { -webkit-appearance:none; -moz-appearance:none; background:#fff url("data:image/svg+xml;utf8,<svg fill='black' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>") no-repeat right 12px center; background-size:20px; border:1px solid #ccc; border-radius:4px; cursor:pointer; font-size:14px; font-weight:500; width:50%; outline:0; padding:10px; box-sizing:border-box; }
    @media (max-width:576px) { .wrapper_selectbox select { width:100%; } }
    .buttons-section { display:flex !important; flex-direction:row !important; gap:10px; padding:0 0 15px; box-sizing:border-box; flex-wrap:nowrap !important; }
    .pass-btn { flex:1 !important; min-width:0 !important; }
    .buy-btn { flex:2.8 !important; min-width:0 !important; }
    /* removed — buttons always side by side */
    .pass-btn { background:#fff; border:1px solid #04ac00; border-radius:4px; color:#04ac00; padding:12px 0; text-align:center; text-decoration:none; cursor:pointer; font-size:14px; line-height:1.2; box-sizing:border-box; }
    .buy-btn { background:#04ac00; border:1px solid #04ac00; border-radius:4px; color:#fff; cursor:pointer; flex:2.8; font-size:14px; font-weight:700; padding:14px 0; text-align:center; line-height:1.2; box-sizing:border-box; }
    .buy-btn.added { background:#2E7D32; }
    .buy-btn:disabled { background:#999; cursor:not-allowed; }
    .ty-upsell-status:empty { display:none; }

.tyu3-banner { background:#1a9c3c; color:#fff; text-align:center; font-size:16px; font-weight:700;
  padding:14px 16px; border-radius:8px; margin-bottom:14px; }

/* ══════════════════════════════════════════════════════════════
   KORAK 1 — ponudba enega izdelka s 50 % popusta
   ══════════════════════════════════════════════════════════════ */
.tyu1 { background:#fff; border-radius:10px; box-shadow:0 2px 14px rgba(0,0,0,.08); }
/* brez overflow:hidden, da seznam izbirnika velikosti ni odrezan; zaobljen vrh dobi traka sama */
.tyu1 > .tyu1-bar { border-radius:10px 10px 0 0; }
.tyu1-card__select .tyu2-dd__btn { min-height:42px; }
.tyu1-card__select .tyu2-dd__list { max-height:262px; }
.tyu1-card__select .tyu2-dd + select + .tyu2-dd { margin-top:8px; }
.tyu1-bar { background:#fdecec; color:#111; text-align:center; padding:11px 14px; font-size:15px; line-height:1.3; }
.tyu1-bar__hurry { color:#e02020; font-weight:800; }
.tyu1-bar__txt { font-weight:600; }
.tyu1-bar__time { display:inline-block; background:#f5811f; color:#fff; font-weight:800; font-size:14px;
  padding:2px 8px; border-radius:4px; margin-left:6px; font-variant-numeric:tabular-nums; }
.tyu1-head { background:#fdecec; padding:0 16px 16px; text-align:center; }
.tyu1-title { margin:0; font-size:23px; line-height:1.3; font-weight:800; color:#111; }
.tyu1-stripe { height:9px; background:repeating-linear-gradient(-45deg,#e02020 0 10px,#fff 10px 20px); }
.tyu1-trust { padding:14px 16px 4px; text-align:center; }
.tyu1-trust__row { font-size:14px; font-weight:600; color:#1a8f3c; margin-bottom:6px; }
.tyu1-trust__row:nth-child(2) { color:#e02020; }
.tyu1-trust__ico { margin-right:5px; }
.tyu1-body { padding:10px 16px 18px; }
.tyu1-qty__head { display:flex; align-items:baseline; justify-content:space-between; gap:10px;
  flex-wrap:wrap; margin-bottom:7px; }
.tyu1-qty__lab { font-size:14px; font-weight:700; color:#111; }
.tyu1-qty__hint { font-size:12.5px; font-weight:600; color:#1a8f3c; }
.tyu1-qty { display:flex; gap:8px; margin-bottom:14px; }
.tyu1-qty__btn { flex:1; display:flex; align-items:center; justify-content:center; gap:6px;
  padding:10px 0; border:2px solid #ddd; border-radius:5px;
  font-weight:700; font-size:14px; cursor:pointer; background:#fff; color:#666; }
.tyu1-qty__btn.is-active { border-color:#f5811f; background:#f5811f14; color:#000; }
.tyu1-qty__tick { display:none; width:18px; height:18px; border-radius:50%; background:#f5811f; color:#fff;
  font-size:11px; font-weight:800; align-items:center; justify-content:center; }
.tyu1-qty__btn.is-active .tyu1-qty__tick { display:flex; }
.tyu1-qty__btn input { display:none; }
.tyu1-card { display:flex; gap:14px; align-items:flex-start; }
.tyu1-card__img { flex:0 0 132px; width:132px; height:132px; border-radius:8px; overflow:hidden; background:#f4f4f4; }
.tyu1-card__img img { width:100%; height:100%; object-fit:cover; display:block; }
.tyu1-card__info { flex:1; min-width:0; }
.tyu1-card__name { font-size:15px; font-weight:700; color:#111; line-height:1.35; margin-bottom:8px; }
.tyu1-card__prices { display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:10px; }
.tyu1-card__old { font-size:14px; color:#9a9a9a; text-decoration:line-through; }
.tyu1-card__new { font-size:21px; font-weight:800; color:#e02020; }
.tyu1-card__badge { background:#1668dc; color:#fff; font-size:12px; font-weight:800; padding:3px 7px; border-radius:4px; }
.tyu1-card__select select { width:100%; height:40px; border:1px solid #cfcfcf; border-radius:5px;
  padding:0 10px; font-size:14px; background:#fff; color:#111; }
.tyu1-card__stock { display:inline-block; margin-top:9px; background:#fdecec; color:#e02020;
  font-size:12.5px; font-weight:700; padding:4px 9px; border-radius:4px; }
.tyu1-actions { display:flex; gap:10px; margin-top:16px; }
.tyu1-btn { display:flex; align-items:center; justify-content:center; height:52px; border-radius:6px;
  font-weight:700; font-size:15px; cursor:pointer; text-decoration:none; }
.tyu1-btn--pass { flex:0 0 34%; background:#fdecec; color:#e02020; }
.tyu1-btn--buy { flex:1; background:#111; color:#fff; gap:8px; }
.tyu1-btn--buy.added { background:#1a8f3c; }

/* ══════════════════════════════════════════════════════════════
   KORAK 2 — ponudba izdelkov, ki jih kupec doda k naročilu
   ══════════════════════════════════════════════════════════════ */
.tyu2 { background:#fff; border-radius:10px; overflow:hidden; }
.tyu2-bar { background:#1a9c3c; color:#fff; text-align:center; font-size:14.5px; font-weight:700; padding:11px 14px; }
.tyu2-bar__time { display:inline-block; background:#1668dc; color:#fff; padding:2px 9px; border-radius:4px;
  margin-left:8px; font-variant-numeric:tabular-nums; }
.tyu2-head { position:relative; background:linear-gradient(180deg,#1c1c1c 0%,#000 100%); padding:26px 32px 30px; }
.tyu2-head__inner { display:flex; align-items:center; justify-content:space-between; gap:16px; }
.tyu2-head__kicker { color:#fff; font-size:15px; font-weight:700; margin-bottom:5px; }
.tyu2-head__title { color:#f2c14b; font-size:30px; font-weight:800; line-height:1.2; }
.tyu2-head__note { color:#cfcfcf; font-size:13px; font-style:italic; margin-top:7px; }
.tyu2-head__badge { flex:none; width:96px; height:96px; border-radius:12px; background:#f5811f; color:#fff;
  display:flex; flex-direction:column; align-items:center; justify-content:center; transform:rotate(-6deg);
  box-shadow:0 8px 20px rgba(0,0,0,.35); }
.tyu2-head__badge span { font-size:26px; font-weight:800; line-height:1; }
.tyu2-head__badge small { font-size:11px; font-weight:800; letter-spacing:.08em; margin-top:2px; }
.tyu2-head__badge small.tyu2-head__badge-do { margin:0 0 2px; }
.tyu2-head__arrow { position:absolute; left:50%; bottom:-13px; transform:translateX(-50%);
  border-left:15px solid transparent; border-right:15px solid transparent; border-top:14px solid #000; }
.tyu2-hero { padding-top:2px; }
.tyu2-hero .tyu1-title { font-size:30px; }
.tyu2-hero__br { display:none; } /* namizje: naslov v eni vrstici */
.tyu2-info__br { display:none; }
@media (max-width:900px){ .tyu2-hero__br { display:inline; } }
@media (max-width:560px){ .tyu2-hero .tyu1-title { font-size:20px; } }
.tyu2-hero__kicker { font-size:15px; font-weight:700; color:#e02020; margin-bottom:4px; }
.tyu2-hero__note { font-size:13px; font-style:italic; color:#555; margin-top:6px; }
.tyu2-info { text-align:center; padding:16px 20px 0; }
.tyu2-info__ok { display:inline-flex; align-items:center; gap:8px; font-size:15px; font-weight:700; color:#1a8f3c; }
.tyu2-info__ico { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:50%;
  background:#1a9c3c; color:#fff; font-size:13px; font-weight:800; }
.tyu2-info__txt { margin:6px auto 0; max-width:980px; font-size:14px; line-height:1.5; color:#333; }
.tyu2-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:22px; padding:26px 28px 28px; background:#fff; }
.tyu2-card { background:#f4f4f4; border-radius:10px; padding:14px 14px 16px; display:flex; flex-direction:column; }
/* elementi mreze se smejo skrciti pod sirino vsebine — sicer izbirniki na mobilcu
   potisnejo kartico cez rob zaslona (min-width:auto pri grid/flex elementih) */
.tyu2-card, .tyu2-field > *, .tyu2-dd, .tyu2-dd__btn { min-width:0; }
.tyu2-card__fields { margin-bottom:2px; }
/* cena, velikost in gumb so vedno poravnani na dno kartice — enak odmik ne glede na dolzino besedila zgoraj */
.tyu2-card__prices { margin-top:auto; }
.tyu2-card__btns { margin-top:4px; }
.tyu2-card__imgwrap { position:relative; border-radius:8px; overflow:hidden; background:#fff; }
.tyu2-card__img { width:100%; aspect-ratio:1/1; object-fit:cover; display:block; transition:filter .2s ease; }
.tyu2-card__done { position:absolute; inset:0; display:none; flex-direction:column; align-items:center;
  justify-content:center; gap:12px; background:rgba(233,247,236,.92); color:#1a9c3c;
  font-size:15px; font-weight:700; text-align:center; line-height:1.35; }
.tyu2-card__check { width:62px; height:62px; border-radius:50%; background:#3fbb52; color:#fff;
  display:flex; align-items:center; justify-content:center; font-size:32px;
  box-shadow:0 4px 14px rgba(63,187,82,.35); }
.tyu2-card.is-added .tyu2-card__imgwrap { box-shadow:0 0 0 2px #3fbb52; border-radius:8px; }
.tyu2-card.is-added .tyu2-card__img { filter:grayscale(.15) opacity(.35); }
.tyu2-card.is-added .tyu2-card__done { display:flex; }
.tyu2-card__name { font-size:15.5px; font-weight:600; color:#111; line-height:1.35; margin:14px 0 10px; min-height:44px; }
/* "Komplet sadrži: …" pod naslovom — ko je, prevzame prostor, ki ga je drzal naslov */
.tyu2-card__name:has(+ .tyu2-card__contents) { min-height:0; margin-bottom:4px; }
.tyu2-card__contents { font-size:13px; color:#555; line-height:1.4; min-height:36px; margin:0 0 10px; }
.tyu2-card__prices { display:flex; align-items:baseline; gap:8px; margin-bottom:12px; }
.tyu2-card__new { font-size:23px; font-weight:800; color:#e02020; }
.tyu2-card__old { font-size:14px; color:#9a9a9a; text-decoration:line-through; }
.tyu2-field { display:grid; grid-template-columns:62px 1fr; align-items:center; gap:8px; margin-bottom:8px; }
.tyu2-field__lab { font-size:13px; color:#444; }
.tyu2-select { width:100%; min-width:0; height:42px; border:1px solid #111; border-radius:4px;
  padding:0 10px; font-size:14px; background:#fff; color:#333; }
/* izbirnik v enakem slogu kot alternativni izbirniki na produktni strani */
.tyu2-dd { position:relative; display:block; width:100%; }
.tyu2-dd__btn { display:flex; align-items:center; gap:8px; width:100%; box-sizing:border-box;
  border:1px solid #111; border-radius:4px; background:#fff; cursor:pointer;
  padding:4px 26px 4px 10px; font-size:15px; font-weight:600; color:#333;
  text-align:left; min-height:40px; line-height:1.3; }
.tyu2-dd__sw { flex:0 0 20px; width:20px; height:20px; border-radius:4px; border:1px solid rgba(0,0,0,.2); }
.tyu2-dd__lab { flex:1 1 auto; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; line-height:1.2; }
.tyu2-dd__car { position:absolute; right:10px; top:50%; transform:translateY(-50%); pointer-events:none;
  width:0; height:0; border-left:5px solid transparent; border-right:5px solid transparent; border-top:6px solid #444; }
.tyu2-dd__list { display:none; position:absolute; left:0; top:calc(100% + 4px); z-index:60;
  width:max-content; min-width:100%; max-height:268px; overflow-y:auto; background:#fff;
  border:1px solid #e0e0e0; border-radius:8px; box-shadow:0 10px 26px rgba(0,0,0,.16); padding:4px; }
.tyu2-dd__list [role=option] { display:flex; align-items:center; gap:10px; padding:8px 12px 8px 10px;
  border-radius:4px; cursor:pointer; font-size:15px; font-weight:600; color:#333; line-height:1.25; white-space:nowrap; }
.tyu2-dd__list [role=option]:hover { background:#f6f6f6; }
.tyu2-dd__list [role=option] { font-weight:500; }
.tyu2-dd__list [role=option].is-on { background:#fff1e9; font-weight:700; }
.tyu2-dd__list::-webkit-scrollbar { width:8px; }
.tyu2-dd__list::-webkit-scrollbar-thumb { background:#d8d8d8; border-radius:8px; }
.tyu2-dd.is-locked { opacity:.55; pointer-events:none; }
.tyu2-btn { display:flex; align-items:center; justify-content:center; gap:6px; width:100%; height:46px;
  border:0; border-radius:5px; font-size:14px; font-weight:700; cursor:pointer; text-decoration:none; margin-top:8px; }
.tyu2-btn--add { background:#111; color:#fff; }
.tyu2-btn--remove { background:#9b9b9b; color:#fff; }
.tyu2-btn__plus { font-size:17px; font-weight:800; }
.tyu2-cartbar { position:sticky; bottom:0; background:#fff; padding:14px 20px 18px; border-top:1px solid #e2e2e2; }
.tyu2-cartbar__inner { display:flex; align-items:center; justify-content:center; gap:22px; flex-wrap:wrap; }
.tyu2-cartbar__left { display:flex; align-items:center; gap:12px; }
.tyu2-cartbar__ico { position:relative; font-size:24px; }
.tyu2-cartbar__count { position:absolute; top:-4px; right:-8px; min-width:18px; height:18px; border-radius:50%;
  background:#e02020; color:#fff; font-size:11px; font-weight:800; display:flex; align-items:center; justify-content:center; }
.tyu2-cartbar__sums { display:flex; flex-direction:column; line-height:1.35; }
.tyu2-cartbar__save { font-size:13px; color:#e02020; font-weight:600; }
.tyu2-cartbar__total { font-size:17px; font-weight:800; color:#111; }
.tyu2-cartbar__cta { background:#1a9c3c; color:#fff; border:0; border-radius:6px; padding:16px 44px;
  font-size:17px; font-weight:700; cursor:pointer; box-shadow:0 4px 12px rgba(26,156,60,.3); }
.tyu2-cartbar__note { text-align:center; font-size:12px; font-style:italic; color:#777; margin-top:8px; }
.tyu2-cartbar__skip { display:block; margin:6px auto 0; padding:4px 10px; background:none; border:0; cursor:pointer;
  color:#666; font-size:13px; font-weight:600; text-decoration:underline; text-underline-offset:3px; }
.tyu2-cartbar__skip:hover { color:#111; }
.tyu2-cartbar__skip[hidden] { display:none !important; }

@media (max-width:1180px){
  .tyu2-grid { grid-template-columns:repeat(3,1fr); gap:22px; padding:32px 20px 160px; }

  /* mobilec IN tablica: vrstica z zakljuckom je vedno pripeta na dno zaslona */
  .tyu2-cartbar { position:fixed; left:0; right:0; bottom:0; z-index:9999;
    background:#fff; border-top:1px solid #e2e2e2; box-shadow:0 -4px 16px rgba(0,0,0,.12);
    padding:12px 20px calc(12px + env(safe-area-inset-bottom)); }
  .tyu2-cartbar__inner { max-width:760px; margin:0 auto; justify-content:space-between; flex-wrap:nowrap; }
  .tyu2-cartbar__note { margin-top:6px; }
}
@media (max-width:900px){
  .tyu2-grid { grid-template-columns:repeat(2,1fr); gap:16px; padding:26px 14px 160px; }
  .tyu2-head { padding:18px 14px 22px; }
  .tyu2-head__title { font-size:20px; }
  .tyu2-head__badge { width:76px; height:76px; }
  .tyu2-head__badge span { font-size:21px; }
}
@media (max-width:560px){
  .tyu1-title { font-size:19px; }
  .tyu2-hero__kicker { font-size:13.5px; }
  .tyu2-hero__note { font-size:12px; }
  .tyu2-info { padding:14px 16px 0; }
  .tyu2-info__ok { font-size:14px; }
  .tyu2-info__txt { font-size:13px; }
  .tyu2-info__br { display:none; }
  .tyu1-card__img { flex-basis:104px; width:104px; height:104px; }
  .tyu1-btn--pass { flex-basis:38%; }

  /* korak 2 na mobilcu: glava cez celo sirino, belo ozadje, vidnejse kartice */
  .ty-container.grid-active { padding:0 !important; margin:0 !important; }
  .tyu2 { border-radius:0; background:#fff; }
  .tyu2-bar { border-radius:0; }
  .tyu2-head { padding:18px 16px 22px; }
  .tyu2-grid { grid-template-columns:1fr 1fr; gap:12px; padding:24px 12px 124px; background:#fff; }
  .tyu2-card { background:#f4f4f4; border:0; border-radius:10px; padding:10px 10px 12px; box-shadow:none; }
  .tyu2-card__imgwrap { background:#f6f6f6; }
  .tyu2-card__name { font-size:13.5px; min-height:36px; margin:10px 0 8px; }
  .tyu2-card__name:has(+ .tyu2-card__contents) { min-height:0; margin-bottom:3px; }
  .tyu2-card__contents { font-size:12px; min-height:34px; margin-bottom:8px; }
  .tyu2-card__new { font-size:18px; }
  .tyu2-card__old { font-size:12.5px; }
  .tyu2-field { grid-template-columns:54px 1fr; gap:6px; }
  .tyu2-field__lab { font-size:12px; }
  .tyu2-select { height:38px; font-size:12.5px; padding:0 8px; }
  .tyu2-dd__btn { min-height:38px; font-size:13.5px; padding:4px 24px 4px 8px; gap:6px; }
  .tyu2-dd__sw { flex-basis:17px; width:17px; height:17px; }
  .tyu2-dd__list [role=option] { font-size:13.5px; padding:7px 10px; }
  .tyu2-btn { height:42px; font-size:13px; }

  /* vrstica z zakljuckom je pripeta na dno (pravilo za <=1180px), tu samo bolj kompaktna */
  .tyu2-cartbar { padding:10px 14px calc(10px + env(safe-area-inset-bottom)); }
  .tyu2-cartbar__inner { flex-direction:row; gap:12px; justify-content:space-between; flex-wrap:nowrap; }
  .tyu2-cartbar__left { gap:9px; }
  .tyu2-cartbar__save { font-size:12px; }
  .tyu2-cartbar__total { font-size:15px; }
  .tyu2-cartbar__cta { flex:1; max-width:56%; padding:14px 12px; font-size:15px; }
  .tyu2-cartbar__note { display:none; }
  .tyu2-cartbar__skip { margin-top:4px; font-size:12.5px; }
}
    /* Blur everything except upsell when visible */
    .ty-container.upsell-active .ty-success,
    .ty-container.grid-active .ty-success { margin-bottom:0 !important; }
    .ty-container.upsell-active > *:not(.ty_upsell_one_wrapper):not(#ty-grid-section) {
        filter: blur(3px);
        opacity: 0.5;
        pointer-events: none;
        user-select: none;
    }
    /* korak 2 je samostojna stran — pod gumbom "Zakljucite nakup" se stran konca */
    .ty-container.grid-active > *:not(#ty-grid-section) { display: none !important; }
/* ═══════════════════════════════════════════════
   STEP 2: 6-PRODUCT GRID (inline, not overlay)
   ═══════════════════════════════════════════════ */
.ty-grid-section { margin-bottom: 15px; display: none; }
.ty-grid-section.show { display: block; }


/* ═══ Collapsible sections ═══ */
.ty-section {
    background: #fff;
    margin-bottom: 15px; overflow: hidden;
    border-radius: 4px !important;
}
/* Section headers — matches product page collapsibles (Detalji o proizvodu, etc.) */
.ty-section-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 20px; cursor: pointer; user-select: none;
    font-family: 'Roboto', sans-serif;
    font-size: 15px; font-weight: 700; color: #222;
    font-style: italic;
    border-bottom: 1px solid #eee;
    transition: border-color 0.2s;
}
.ty-section-header.open { border-bottom-color: #eee; }
.ty-section-header .ty-chevron {
    font-size: 18px; color: #222; font-weight: 300; font-style: normal;
    transition: transform 0.25s; display: inline-block;
}
.ty-section-header.open .ty-chevron { transform: rotate(45deg); }
.ty-section-body {
    max-height: 0; overflow: hidden; transition: max-height 0.3s ease;
}
.ty-section-body.open { max-height: 2000px; }
.ty-section-body-inner { padding: 14px 20px; }
.ty-row {
    display: flex; justify-content: space-between; align-items: baseline;
    padding: 8px 0; font-family: 'Roboto', sans-serif;
    font-size: 14px; border-bottom: 1px solid #f0f0f0;
}
.ty-row:last-child { border-bottom: none; }
.ty-row-label { color: #888; font-weight: 400; }
.ty-row-value { font-weight: 600; color: #222; text-align: right; max-width: 60%; }
.ty-item {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0; border-bottom: 1px solid #f0f0f0;
}
.ty-item:last-child { border-bottom: none; }
.ty-item-name { font-family: 'Roboto', sans-serif; font-size: 14px; color: #222; flex: 1; }
.ty-item-meta { font-size: 12px; color: #999; margin-top: 2px; border: none; }
.ty-item-price { font-family: 'Roboto', sans-serif; font-weight: 600; font-size: 14px; color: #222; margin-left: 12px; white-space: nowrap; }
.ty-totals { margin-top: 0; border-top: none; padding-top: 0; }
.ty-totals .ty-row { padding: 5px 0; }
.ty-totals .ty-total-final { font-size: 16px; font-weight: 700; }

/* ═══ Mobile ═══ */
@media (max-width: 560px) {
    .ty-container { margin: 0 auto; padding: 0 10px; }
    .ty-success { padding: 22px 16px; }
    .ty-success h1 { font-size: 19px !important; }
    .tyuo_timer .title { font-size: 17px; }
    .tyuo_product_section .product_data { gap: 12px; }
    .tyuo_product_section .product_data .img { width: 90px; min-width: 90px; height: 90px; }
    .tyuo_product_section .qty { font-size: 20px; }
    .tyuo_product_section .product_new_sale_price { font-size: 20px; }
    /* removed — buttons always side by side */
    .tyuo_product_section .variation-select-wrap { padding: 0 0 8px; justify-content: center; }
    .tyuo_product_section .variation-select { width: 100%; }
    .ty-section-header { padding: 14px 16px; font-size: 14px; }
    .ty-section-body-inner { padding: 12px 16px; }
    .ty-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

<?php if ( $order ) : ?>

<!-- Order confirmed fullscreen splash -->
<div id="order-splash" style="position:fixed;top:0;left:0;width:100%;height:100%;background:#04ac00;z-index:999999;display:flex;flex-direction:column;align-items:center;justify-content:center;transition:opacity 0.6s ease;">
    <div style="width:80px;height:80px;border:4px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </div>
    <h1 style="color:#fff;font-family:'Roboto',sans-serif;font-size:28px;font-weight:700;margin:0;">Objednávka přijata!</h1>
    <p style="color:rgba(255,255,255,0.85);font-family:'Roboto',sans-serif;font-size:15px;margin:10px 0 0;">Číslo objednávky: #<?php echo $order->get_order_number(); ?></p>
</div>
<script>(function(){var k='splash_<?php echo $order->get_id(); ?>';if(sessionStorage.getItem(k)){document.getElementById('order-splash').style.display='none';return;}sessionStorage.setItem(k,'1');setTimeout(function(){var s=document.getElementById('order-splash');s.style.opacity='0';setTimeout(function(){s.style.display='none';},600);},2000);})();</script>

<div class="ty-container">

        <!-- potrditev po zakljucku ponudbe -->
        <div class="tyu3-banner" id="ty-added-banner" style="display:none;">✔ Produkt ze speciální nabídky byl přidán!</div>

    <?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

    <?php if ( $order->has_status( 'failed' ) ) : ?>
        <div class="ty-success" style="background:#fde8e8;">
            <div class="ty-success-icon" style="background:#dc3545;">✕</div>
            <h1>Objednávka se nezdařila</h1>
            <p>Banka odmítla transakci. Zkuste to znovu.</p>
            <p style="margin-top:16px;">
                <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" style="display:inline-block;background:#E8450E;color:#fff;padding:12px 32px;text-decoration:none;font-weight:700;">Zkuste znovu</a>
            </p>
        </div>
    <?php else : ?>

        <!-- ✅ Success -->
        <div class="ty-success">
            <div class="ty-success-icon">✓</div>
            <h1>Vaše objednávka byla přijata!</h1>
            <p>Potvrzení bylo odesláno na <?php echo esc_html( $order->get_billing_email() ); ?></p>
            <span class="ty-order-num">Objednávka #<?php echo $order->get_order_number(); ?></span>
        </div>

        <!-- ═══ STEP 1: VIGOSHOP UPSELL (COD only) ═══ -->
        <?php if ( $order->get_payment_method() === 'cod' ) : ?>
        <div class="ty_upsell_one_wrapper show" id="ty-upsell"
             style="position:static !important;display:block !important;width:100% !important;max-width:520px !important;height:auto !important;top:auto !important;left:auto !important;transform:none !important;opacity:1 !important;visibility:visible !important;z-index:auto !important;backdrop-filter:none !important;margin:0 !important;padding:0 !important;"
             data-order-id="<?php echo $order->get_id(); ?>"
             data-nonce="<?php echo wp_create_nonce('noriks_upsell_' . $order->get_id()); ?>">
            <div class="ty_upsell_one_wrapper__popup-content tyu1">

                <!-- rdeca traka z odstevalnikom -->
                <div class="tyu1-bar">
                    <span class="tyu1-bar__hurry">Pospěšte si!</span>
                    <span class="tyu1-bar__txt">Speciální nabídka vyprší za</span>
                    <span class="tyu1-bar__time" id="ty-timer">05:00</span>
                </div>

                <div class="tyu1-head">
                    <h2 class="tyu1-title">Přidejte ještě jeden produkt s<br>dodatečnou slevou 50 %!</h2>
                </div>
                <div class="tyu1-stripe"></div>

                <div class="tyu1-trust">
                    <div class="tyu1-trust__row"><span class="tyu1-trust__ico">🚚</span> Pošleme ho ve stejném balíku</div>
                    <div class="tyu1-trust__row"><span class="tyu1-trust__ico">🎁</span> Skvělý jako dárek — nebo si ho nechte pro sebe</div>
                </div>

                <div class="tyu1-body">

                    <!-- izbira kolicine -->
                    <div class="tyu1-qty__head">
                        <span class="tyu1-qty__lab">Vyberte množství</span>
                        <span class="tyu1-qty__hint">Větší množství — větší úspora</span>
                    </div>
                    <div class="tyu1-qty">
                        <?php $qty_keys = array_keys($upsell_qty_prices); foreach ($qty_keys as $i => $q) : ?>
                        <label class="tyu1-qty__btn<?php echo $i === 0 ? ' is-active' : ''; ?>">
                            <input type="radio" name="ty_qty" value="<?php echo $q; ?>"<?php echo $i === 0 ? ' checked' : ''; ?>>
                            <span class="tyu1-qty__tick">✓</span><?php echo $q; ?>x ks
                        </label>
                        <?php endforeach; ?>
                    </div>

                    <div class="tyu1-card">
                        <div class="tyu1-card__img">
                            <img id="ty-upsell-img" alt="<?php echo esc_attr($upsell_name); ?>" src="<?php echo esc_url($upsell_qty_images[$default_qty]); ?>">
                        </div>
                        <div class="tyu1-card__info">
                            <div class="tyu1-card__name" id="ty-upsell-name"><?php echo esc_html($upsell_qty_names[$default_qty]); ?></div>
                            <div class="tyu1-card__prices">
                                <span class="tyu1-card__old" id="ty-upsell-regular"><?php echo esc_html( noriks_ty_cena( $upsell_qty_regular[$default_qty] ) ); ?></span>
                                <span class="tyu1-card__new" id="ty-upsell-price"><?php echo esc_html( noriks_ty_cena( $upsell_qty_prices[$default_qty] ) ); ?></span>
                                <span class="tyu1-card__badge">-<?php echo (int) $upsell_badge_pct; ?>%</span>
                            </div>
                            <div class="tyu1-card__select">
                                <?php if ( $s1_cards && $s1_colors ) : /* barva po izbiri kupca */ ?>
                                <select class="variation-select" id="ty-color-select" data-dd="size">
                                    <?php foreach ( $s1_colors as $s1_cl ) : ?>
                                    <option value="<?php echo esc_attr( $s1_cl ); ?>" <?php selected( $s1_cl, $s1_color ); ?>><?php echo esc_html( $s1_cl ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php endif; ?>
                                <select class="variation-select" id="ty-variation-select" data-dd="size">
                                    <?php if ( $s1_cards ) : ?>
                                        <?php $s1_pick = function_exists( 'noriks_ty2_pick_size' ) ? noriks_ty2_pick_size( $s1_sizes, $customer_size ) : ''; ?>
                                        <?php if ( ! empty( $s1_first['pick'] ) ) : /* starost / teza (KidsNest): kupec mora izbrati sam */ ?>
                                        <option value="" selected>Vyberte</option>
                                        <?php endif; ?>
                                        <?php foreach ( $s1_sizes as $s1_sz ) : ?>
                                        <option value="<?php echo esc_attr( $s1_sz ); ?>" <?php selected( $s1_sz, $s1_pick ); ?>><?php echo esc_html( $s1_colors ? $s1_sz : trim( $s1_color . ', ' . $s1_sz, ', ' ) ); ?></option>
                                        <?php endforeach; ?>
                                    <?php elseif ( $upsell_variations ) : ?>
                                        <?php foreach ( $upsell_variations as $v ) : ?>
                                        <option value="<?php echo $v['id']; ?>" <?php selected( strtolower($v['size']), strtolower($customer_size) ); ?>>Černá, <?php echo esc_html( $v['size'] ); ?></option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">Černá, S</option>
                                        <option value="">Černá, M</option>
                                        <option value="">Černá, L</option>
                                        <option value="">Černá, XL</option>
                                        <option value="">Černá, XXL</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="tyu1-card__stock">🔥 Platí jen k této objednávce</div>
                        </div>
                    </div>

                    <script>
                    (function(){
                        var prices   = <?php echo json_encode(array_map('noriks_ty_cena', $upsell_qty_prices)); ?>;
                        var names    = <?php echo json_encode($upsell_qty_names); ?>;
                        var images   = <?php echo json_encode($upsell_qty_images); ?>;
                        var regulars = <?php echo json_encode(array_map('noriks_ty_cena', $upsell_qty_regular)); ?>;
                        document.querySelectorAll('.tyu1-qty__btn').forEach(function(btn){
                            btn.addEventListener('click', function(){
                                document.querySelectorAll('.tyu1-qty__btn').forEach(function(b){ b.classList.remove('is-active'); });
                                btn.classList.add('is-active');
                                var q = btn.querySelector('input').value;
                                document.getElementById('ty-upsell-price').textContent   = prices[q]   || '';
                                document.getElementById('ty-upsell-name').textContent    = names[q]    || '';
                                document.getElementById('ty-upsell-img').src             = images[q]   || '';
                                document.getElementById('ty-upsell-regular').textContent = regulars[q] || '';
                            });
                        });
                    })();
                    </script>

                    <div class="ty-upsell-status" id="ty-upsell-status"></div>

                    <div class="tyu1-actions">
                        <a class="tyu1-btn tyu1-btn--pass" id="ty-btn-skip">Nechci</a>
                        <div class="tyu1-btn tyu1-btn--buy" id="ty-btn-add" data-product-id="<?php echo esc_attr( $upsell_product_id ); ?>">Přidat k objednávce <span>→</span></div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ═══ KORAK 2: PONUDBA IZDELKOV ═══ -->
        <?php if ( ! empty( $grid_cards ) ) : ?>
        <div class="ty-grid-section tyu2" id="ty-grid-section">

            <!-- traka z odstevalnikom — enak slog kot korak 1 -->
            <div class="tyu1-bar">
                <span class="tyu1-bar__hurry">Pospěšte si!</span>
                <span class="tyu1-bar__txt">Speciální nabídka vyprší za</span>
                <span class="tyu1-bar__time" id="ty-timer-2">05:00</span>
            </div>

            <div class="tyu1-head tyu2-hero">
                <div class="tyu2-hero__kicker">Protože vyprodáváme sklad, nabízíme:</div>
                <h2 class="tyu1-title">Až <?php echo (int) $grid_max_pct; ?> % sleva na <br class="tyu2-hero__br">nejprodávanější produkty</h2>
                <div class="tyu2-hero__note">*Bez dalších nákladů na doručení!</div>
            </div>
            <div class="tyu1-stripe"></div>

            <div class="tyu2-info">
                <div class="tyu2-info__ok"><span class="tyu2-info__ico">✓</span> Vaše objednávka byla přijata a bude odeslána.</div>
                <p class="tyu2-info__txt">Níže uvedené produkty můžete přidat k <strong>stávající objednávce</strong> — pošleme je <strong>ve stejném balíku</strong>. <br class="tyu2-info__br">Pokud o nabídku nemáte zájem, jednoduše ji přeskočte.</p>
            </div>

            <!-- mreza izdelkov -->
            <div class="tyu2-grid">
                <?php foreach ( $grid_cards as $c ) : ?>
                <div class="tyu2-card" data-key="<?php echo esc_attr( $c['key'] ); ?>" data-old="<?php echo esc_attr( $c['old'] ); ?>">
                    <div class="tyu2-card__imgwrap">
                        <img class="tyu2-card__img" src="<?php echo esc_url( $c['img'] ); ?>" alt="<?php echo esc_attr( $c['label'] ); ?>" loading="lazy">
                        <div class="tyu2-card__done"><span class="tyu2-card__check">✓</span>Produkt přidán<br>do objednávky</div>
                    </div>
                    <div class="tyu2-card__name"><?php echo esc_html( $c['label'] ); ?></div>
                    <?php if ( ! empty( $c['contents'] ) ) : ?>
                    <div class="tyu2-card__contents">Barva: <?php echo esc_html( $c['contents'] ); ?></div>
                    <?php endif; ?>
                    <div class="tyu2-card__prices">
                        <span class="tyu2-card__new"><?php echo esc_html( noriks_ty_cena( $c['new'] ) ); ?></span>
                        <span class="tyu2-card__old"><?php echo esc_html( noriks_ty_cena( $c['old'] ) ); ?></span>
                    </div>

                    <?php /* barva je dolocena v ponudbi — kupec izbere samo velikost */ ?>

                    <?php if ( ! empty( $c['choice']['options'] ) ) : /* npr. KneeFix: Stranica (Lijevo / Desno) */ ?>
                    <div class="tyu2-field">
                        <span class="tyu2-field__lab"><?php echo esc_html( $c['choice']['name'] ); ?>:</span>
                        <select class="tyu2-select tyu2-choice" data-dd="size" data-ime="<?php echo esc_attr( $c['choice']['name'] ); ?>">
                            <option value="">Vyberte</option>
                            <?php foreach ( $c['choice']['options'] as $c_opt ) : ?>
                            <option value="<?php echo esc_attr( $c_opt ); ?>"><?php echo esc_html( $c_opt ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <?php if ( $c['sizes'] ) : ?>
                    <div class="tyu2-field">
                        <span class="tyu2-field__lab">Velikost:</span>
                        <select class="tyu2-select tyu2-size" data-dd="size">
                            <?php $c_pick = function_exists( 'noriks_ty2_pick_size' ) ? noriks_ty2_pick_size( $c['sizes'], $customer_size ) : $customer_size; ?>
                            <?php if ( ! empty( $c['pick'] ) ) : /* starost / teza: kupec mora izbrati sam */ ?>
                            <option value="">Vyberte</option>
                            <?php endif; ?>
                            <?php foreach ( $c['sizes'] as $sz ) : ?>
                            <option value="<?php echo esc_attr( $sz ); ?>" <?php selected( $sz, $c_pick ); ?>><?php echo esc_html( $sz ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="tyu2-card__btns">
                    <button class="tyu2-btn tyu2-btn--add"
                            data-offer="<?php echo esc_attr( $c['key'] ); ?>"
                            data-price="<?php echo esc_attr( $c['new'] ); ?>">
                        <span class="tyu2-btn__plus">+</span> Přidat k objednávce
                    </button>
                    <button class="tyu2-btn tyu2-btn--remove" style="display:none;">
                        <span class="tyu2-btn__bin">🗑</span> Odstranit
                    </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- lepljiva vrstica -->
            <div class="tyu2-cartbar" id="ty-cartbar">
                <div class="tyu2-cartbar__inner">
                    <div class="tyu2-cartbar__left">
                        <span class="tyu2-cartbar__ico">🛒<span class="tyu2-cartbar__count" id="ty-cart-count">0</span></span>
                        <span class="tyu2-cartbar__sums">
                            <span class="tyu2-cartbar__save">Úspora: <b id="ty-cart-save"><?php echo esc_html( noriks_ty_cena( 0 ) ); ?></b></span>
                            <span class="tyu2-cartbar__total">Celkem: <b id="ty-cart-total"><?php echo esc_html( noriks_ty_cena( 0 ) ); ?></b></span>
                        </span>
                    </div>
                    <button class="tyu2-cartbar__cta" id="ty-grid-finish">Dokončit nákup</button>
                </div>
                <div class="tyu2-cartbar__note">* Bez dalších nákladů na doručení!</div>
                <button type="button" class="tyu2-cartbar__skip" id="ty-grid-skip">Přeskočit nabídku</button>
            </div>

        </div>
        <?php endif; ?>
        <?php endif; /* COD only */ ?>

        <!-- 📋 Order items -->
        <div class="ty-section" id="ty-order-items-section">
            <div class="ty-section-header open" onclick="tyToggle(this)">
                <span id="ty-order-items-header">Položky objednávky (<?php echo $order->get_item_count(); ?>)</span>
                <span class="ty-chevron">+</span>
            </div>
            <div class="ty-section-body open">
                <div class="ty-section-body-inner" id="ty-order-items-body">
                    <?php foreach ( $order->get_items() as $item ) :
                        $qty = $item->get_quantity();
                        $meta_parts = array();
                        foreach ( $item->get_formatted_meta_data('_', true) as $m ) {
                            $meta_parts[] = wp_strip_all_tags( $m->display_key . ': ' . $m->display_value );
                        }
                    ?>
                    <?php $is_upsell_item = $item->get_meta( '_noriks_upsell' ) === 'thank you upsell'; ?>
                    <div class="ty-item">
                        <div>
                            <div class="ty-item-name"><?php echo $qty; ?>× <?php echo esc_html( $item->get_name() ); ?></div>
                            <?php if ( $meta_parts ) : ?>
                            <div class="ty-item-meta"><?php echo esc_html( implode( ', ', $meta_parts ) ); ?></div>
                            <?php endif; ?>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div class="ty-item-price"><?php echo $order->get_formatted_line_subtotal( $item ); ?></div>
                            <!-- remove button disabled -->
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <div class="ty-totals">
                        <?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
                        <div class="ty-row <?php echo $key === 'order_total' ? 'ty-total-final' : ''; ?>">
                            <span class="ty-row-label"><?php echo $total['label']; ?></span>
                            <span class="ty-row-value"><?php echo $total['value']; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📍 Address -->
        <div class="ty-section">
            <div class="ty-section-header open" onclick="tyToggle(this)">
                <span>Doručovací adresa</span>
                <span class="ty-chevron">+</span>
            </div>
            <div class="ty-section-body open">
                <div class="ty-section-body-inner">
                    <div class="ty-row"><span class="ty-row-label">Jméno</span><span class="ty-row-value"><?php echo esc_html( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); ?></span></div>
                    <div class="ty-row"><span class="ty-row-label">Adresa</span><span class="ty-row-value"><?php echo esc_html( $order->get_billing_address_1() . ' ' . $order->get_billing_address_2() ); ?></span></div>
                    <div class="ty-row"><span class="ty-row-label">Město</span><span class="ty-row-value"><?php echo esc_html( $order->get_billing_postcode() . ' ' . $order->get_billing_city() ); ?></span></div>
                    <?php if ( $order->get_billing_phone() ) : ?>
                    <div class="ty-row"><span class="ty-row-label">Telefon</span><span class="ty-row-value"><?php echo esc_html( $order->get_billing_phone() ); ?></span></div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    <?php endif; ?>

    <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
    <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

</div>

<?php else : ?>
    <div class="ty-container">
        <div class="ty-success"><h1>Objednávka</h1>
        <?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>
        </div>
    </div>
<?php endif; ?>

<script>
(function(){
    var wrap     = document.getElementById('ty-upsell');
    var overlay  = document.getElementById('ty-grid-section');
    if (!wrap) return;

    // Blur background when upsell is visible
    var tyContainer = document.querySelector('.ty-container');
    if (tyContainer) tyContainer.classList.add('upsell-active');

    var orderId  = wrap.dataset.orderId;
    var nonce    = wrap.dataset.nonce;
    var ajaxUrl  = '<?php echo admin_url("admin-ajax.php"); ?>';

    // ─── Countdown ───
    var timerEl   = document.getElementById('ty-timer');
    var barEl     = null; // no bottom bar in vigoshop layout
    var key       = 'ty_' + orderId;
    var rem       = 300;
    var saved     = localStorage.getItem(key);
    if (saved) { rem = Math.max(0, 300 - Math.floor((Date.now() - parseInt(saved)) / 1000)); }
    else { localStorage.setItem(key, Date.now().toString()); }

    function tick() {
        if (rem <= 0) {
            // Hide everything — upsell over
            wrap.style.display = 'none';
            if (overlay) overlay.style.display = 'none';
            if (tyContainer) { tyContainer.classList.remove('upsell-active'); tyContainer.classList.remove('grid-active'); }
            // Clear all upsell localStorage
            localStorage.removeItem('ty_added_' + orderId);
            localStorage.removeItem(stepKey);
            localStorage.removeItem(key);
            // Auto-open "Postavke naročila" and "Adresa za dostavu" sections
            document.querySelectorAll('.ty-section .ty-section-header').forEach(function(h) {
                if (!h.classList.contains('open')) {
                    h.classList.add('open');
                    h.nextElementSibling.classList.add('open');
                }
            });
            // Release primary-hold → processing via AJAX
            var releaseFd = new FormData();
            releaseFd.append('action', 'noriks_release_primary_hold');
            releaseFd.append('order_id', orderId);
            releaseFd.append('nonce', (document.getElementById('ty-upsell')||{dataset:{}}).dataset.nonce || '');
            fetch(ajaxUrl, { method: 'POST', body: releaseFd }).catch(function(){});
            return;
        }
        var m = Math.floor(rem/60), s = rem%60;
        var display = (m<10?'0':'')+m+':'+(s<10?'0':'')+s;
        if (timerEl) timerEl.textContent = display;
        if (barEl) barEl.textContent = display;
        var timer2 = document.getElementById('ty-timer-2');
        if (timer2) timer2.textContent = display;
        rem--; setTimeout(tick, 1000);
    }
    tick();

    // ─── Step transitions ───
    var stepKey = 'ty_step_' + orderId;

    // If user already passed step 1, skip to step 2
    // If user dismissed step 2 ('done'), hide everything
    var stepState = localStorage.getItem(stepKey);
    if (stepState === 'done' || stepState === '2') {
        wrap.style.display = 'none';
        if (overlay) overlay.style.display = 'none';
        if (tyContainer) { tyContainer.classList.remove('upsell-active'); tyContainer.classList.remove('grid-active'); }
    }

    function showGrid() {
        if (!overlay || !overlay.querySelector('.tyu2-card')) { closeAll(); return; }
        wrap.style.display = 'none';
        localStorage.setItem(stepKey, '2');
        if (overlay) overlay.classList.add('show');
        if (overlay) overlay.scrollIntoView({ behavior: 'smooth', block: 'start' });
        if (tyContainer) { tyContainer.classList.remove('upsell-active'); tyContainer.classList.add('grid-active'); }
    }
    function closeAll() {
        if (overlay) overlay.classList.remove('show');
        if (wrap) wrap.style.display = 'none';
        if (tyContainer) { tyContainer.classList.remove('upsell-active'); tyContainer.classList.remove('grid-active'); }
        localStorage.setItem(stepKey, 'done');
        // Release order — process it
        var relFd = new FormData();
        relFd.append('action', 'noriks_release_primary_hold');
        relFd.append('order_id', orderId);
        relFd.append('nonce', (document.getElementById('ty-upsell')||{dataset:{}}).dataset.nonce || '');
        fetch(ajaxUrl, { method: 'POST', body: relFd }).catch(function(){});
    }

    // ─── Refresh order items after upsell add ───
    function refreshOrderItems() {
        var rfd = new FormData();
        rfd.append('action', 'noriks_refresh_order_items');
        rfd.append('order_id', orderId);
        rfd.append('nonce', (document.getElementById('ty-upsell')||{dataset:{}}).dataset.nonce || '');
        fetch(ajaxUrl, { method: 'POST', body: rfd })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (d.success) {
                    // Update items section content by ID
                    var itemsBody = document.getElementById('ty-order-items-body');
                    if (itemsBody) itemsBody.innerHTML = d.data.items_html;
                    // Update item count in header by ID
                    var headerSpan = document.getElementById('ty-order-items-header');
                    if (headerSpan) {
                        headerSpan.textContent = 'Položky objednávky (' + d.data.item_count + ')';
                    }
                    // Make sure section stays open
                    var section = document.getElementById('ty-order-items-section');
                    if (section) {
                        var h = section.querySelector('.ty-section-header');
                        var b = section.querySelector('.ty-section-body');
                        if (h && !h.classList.contains('open')) h.classList.add('open');
                        if (b && !b.classList.contains('open')) b.classList.add('open');
                    }
                }
            })
            .catch(function(){});
    }

    // ─── Korak 1: "Ne želim" → pokaži ponudbo izdelkov (korak 2) ───
    var skipBtn = document.getElementById('ty-btn-skip');
    if (skipBtn) {
        skipBtn.addEventListener('click', function() {
            if (overlay) { showGrid(); } else { closeAll(); }
        });
    }

    // ─── Korak 1: "Dodaj u narudžbu" → doda izdelek, nato korak 2 ───
    var addBtn = document.getElementById('ty-btn-add');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            if (addBtn.dataset.busy === '1') return;
            addBtn.dataset.busy = '1';
            addBtn.textContent = "Přidávám …";

            var select   = document.getElementById('ty-variation-select');
            var qtyRadio = document.querySelector('input[name="ty_qty"]:checked');
            var qty      = qtyRadio ? parseInt(qtyRadio.value, 10) : 1;

            var fd = new FormData();
            <?php if ( $s1_cards ) : /* +1 istega izdelka: izdelek, kolicino in ceno doloci streznik po kljucu kartice */ ?>
            var s1Keys = <?php echo wp_json_encode( $s1_keys ); ?>;
            fd.append('action', 'noriks_add_upsell_step2');
            fd.append('order_id', orderId);
            fd.append('offer_key', s1Keys[qty] || '');
            fd.append('upsell_size', select ? select.value : '');
            var colSel = document.getElementById('ty-color-select');
            if (colSel) { fd.append('upsell_color', colSel.value); }
            fd.append('nonce', nonce);
            <?php else : ?>
            fd.append('action', 'noriks_add_upsell');
            fd.append('order_id', orderId);
            fd.append('product_id', <?php echo (int) $upsell_product_id; ?>);
            fd.append('variation_id', select ? select.value : '');
            fd.append('sale_price', '<?php echo $upsell_sale_price; ?>');
            fd.append('quantity', qty);
            fd.append('upsell_type', 'post_purchase_step1_majica');
            fd.append('nonce', nonce);
            <?php endif; ?>

            fetch(ajaxUrl, { method: 'POST', body: fd })
                .then(function(r) { return r.json(); })
                .then(function(d) {
                    <?php if ( $s1_cards ) : ?>
                    if (!d || !d.success) {
                        addBtn.dataset.busy = '0';
                        addBtn.innerHTML = 'Přidat k objednávce <span>→</span>';
                        if (d && d.data) { alert(d.data); }
                        return;
                    }
                    <?php endif; ?>
                    addBtn.textContent = "✓ Přidáno";
                    addBtn.classList.add('added');
                    if (select) select.disabled = true;
                    var colSelD = document.getElementById('ty-color-select'); if (colSelD) colSelD.disabled = true;
                    refreshOrderItems();
                    setTimeout(function() { if (overlay) { showGrid(); } else { closeAll(); } }, 700);
                })
                .catch(function() {
                    addBtn.dataset.busy = '0';
                    addBtn.innerHTML = 'Přidat k objednávce <span>→</span>';
                });
        });
    }

    // ─── Izbirniki v enakem slogu kot na produktni strani (korak 1 in korak 2) ───
    function tyDropdown(sel) {
            var isColor = sel.getAttribute('data-dd') === 'color';

            var wrap = document.createElement('span');
            wrap.className = 'tyu2-dd';

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'tyu2-dd__btn';

            var sw = document.createElement('span');
            sw.className = 'tyu2-dd__sw';
            if (!isColor) sw.style.display = 'none';

            var lab = document.createElement('span');
            lab.className = 'tyu2-dd__lab';

            var car = document.createElement('span');
            car.className = 'tyu2-dd__car';

            btn.appendChild(sw); btn.appendChild(lab);

            var list = document.createElement('div');
            list.className = 'tyu2-dd__list';
            list.setAttribute('role', 'listbox');

            function close() { list.style.display = 'none'; }
            function open() {
                document.querySelectorAll('.tyu2-dd__list').forEach(function(o){ o.style.display = 'none'; });
                list.style.display = 'block';
            }
            btn.addEventListener('click', function(e){
                e.preventDefault(); e.stopPropagation();
                if (sel.disabled) return;
                (list.style.display === 'block') ? close() : open();
            });
            document.addEventListener('click', function(e){ if (!wrap.contains(e.target)) close(); });
            document.addEventListener('keydown', function(e){ if (e.key === 'Escape') close(); });

            function setCurrent(opt) {
                sel.value = opt.value;
                lab.textContent = opt.textContent;
                var col = opt.getAttribute('data-color');
                if (isColor && col) { sw.style.background = col; sw.style.display = 'block'; }
                list.querySelectorAll('[role=option]').forEach(function(it){
                    it.classList.toggle('is-on', it.dataset.val === opt.value);
                });
                sel.dispatchEvent(new Event('change', { bubbles: true }));
            }

            Array.prototype.forEach.call(sel.options, function(opt){
                var it = document.createElement('div');
                it.setAttribute('role', 'option');
                it.dataset.val = opt.value;
                var col = opt.getAttribute('data-color');
                if (isColor && col) {
                    var c = document.createElement('span');
                    c.className = 'tyu2-dd__sw';
                    c.style.background = col;
                    it.appendChild(c);
                }
                var tx = document.createElement('span');
                tx.textContent = opt.textContent;
                it.appendChild(tx);
                it.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); setCurrent(opt); close(); });
                list.appendChild(it);
            });

            wrap.appendChild(btn); wrap.appendChild(car); wrap.appendChild(list);
            sel.style.display = 'none';
            sel.parentNode.insertBefore(wrap, sel.nextSibling);

            var cur = sel.options[sel.selectedIndex] || sel.options[0];
            if (cur) setCurrent(cur);

            // ob zaklenjenem izdelku se izbirnik ne odpira vec
            new MutationObserver(function(){
                wrap.classList.toggle('is-locked', sel.disabled);
            }).observe(sel, { attributes: true, attributeFilter: ['disabled'] });

            // ce se vrednost nastavi iz kode (ne s klikom), uskladi napis in oznaceno vrstico
            sel._tySync = function(){
                var o = sel.options[sel.selectedIndex]; if (!o) return;
                lab.textContent = o.textContent;
                list.querySelectorAll('[role=option]').forEach(function(it){ it.classList.toggle('is-on', it.dataset.val === o.value); });
            };
    }

    // korak 1: izbira velikosti z istim izbirnikom kot v koraku 2
    var s1Sel = document.getElementById('ty-variation-select');
    if (s1Sel && s1Sel.options.length) { tyDropdown(s1Sel); }
    var s1Col = document.getElementById('ty-color-select');
    if (s1Col && s1Col.options.length) { tyDropdown(s1Col); }

    if (overlay) {
        overlay.querySelectorAll('select[data-dd]').forEach(tyDropdown);

        var addedItems = {};   // key -> { itemId, price, old, label }

        function money(n) {
            var s = n.toFixed(0).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, " ");
            return s + " Kč";
        }

        function updateCartBar() {
            var count = 0, total = 0, save = 0;
            Object.keys(addedItems).forEach(function(k) {
                count += 1;
                total += addedItems[k].price;
                save  += (addedItems[k].old - addedItems[k].price);
            });
            var elC = document.getElementById('ty-cart-count');
            var elT = document.getElementById('ty-cart-total');
            var elS = document.getElementById('ty-cart-save');
            if (elC) elC.textContent = count;
            if (elT) elT.textContent = money(total);
            if (elS) elS.textContent = money(save);
        }

        overlay.querySelectorAll('.tyu2-card').forEach(function(card) {
            var key      = card.getAttribute('data-key');
            var addBtnG  = card.querySelector('.tyu2-btn--add');
            var remBtnG  = card.querySelector('.tyu2-btn--remove');
            var oldPrice = parseFloat(card.getAttribute('data-old')) || 0;

            if (!addBtnG) return;

            addBtnG.addEventListener('click', function() {
                if (addBtnG.dataset.busy === '1') return;
                addBtnG.dataset.busy = '1';
                addBtnG.textContent = "Přidávám …";

                var colorSel = card.querySelector('.tyu2-color');
                var sizeSel  = card.querySelector('.tyu2-size');
                var choiceSel = card.querySelector('.tyu2-choice');
                // izbira strani / velikosti je obvezna, kjer ni privzete vrednosti
                var manjka = (choiceSel && !choiceSel.value) ? ("Vyberte: " + (choiceSel.getAttribute('data-ime') || '')) : ((sizeSel && !sizeSel.value) ? "Vyberte velikost" : '');
                if (manjka) {
                    addBtnG.dataset.busy = '0';
                    addBtnG.innerHTML = '<span class="tyu2-btn__plus">+</span> Přidat k objednávce';
                    alert(manjka);
                    return;
                }

                // cena in izdelek se na strezniku preberejo iz ponudbe po kljucu — brskalnik ju ne posilja
                var fd = new FormData();
                fd.append('action', 'noriks_add_upsell_step2');
                fd.append('order_id', orderId);
                fd.append('offer_key', addBtnG.getAttribute('data-offer'));
                if (colorSel) fd.append('upsell_color', colorSel.value);
                if (sizeSel)  fd.append('upsell_size', sizeSel.value);
                if (choiceSel) fd.append('upsell_choice', choiceSel.value);
                fd.append('nonce', nonce);

                fetch(ajaxUrl, { method: 'POST', body: fd })
                    .then(function(r) { return r.json(); })
                    .then(function(d) {
                        addBtnG.dataset.busy = '0';
                        if (!d || !d.success) {
                            addBtnG.innerHTML = '<span class="tyu2-btn__plus">+</span> Přidat k objednávce';
                            if (d && d.data) { alert(d.data); }
                            return;
                        }
                        addedItems[key] = {
                            itemId: d.data.item_id,
                            price:  parseFloat(addBtnG.getAttribute('data-price')) || 0,
                            old:    oldPrice
                        };
                        card.classList.add('is-added');
                        addBtnG.style.display = 'none';
                        if (remBtnG) remBtnG.style.display = 'flex';
                        card.querySelectorAll('.tyu2-select').forEach(function(sel){ sel.disabled = true; });
                        updateCartBar();
                        refreshOrderItems();
                    })
                    .catch(function() {
                        addBtnG.dataset.busy = '0';
                        addBtnG.innerHTML = '<span class="tyu2-btn__plus">+</span> Přidat k objednávce';
                    });
            });

            if (remBtnG) {
                remBtnG.addEventListener('click', function() {
                    var entry = addedItems[key];
                    if (!entry) return;
                    if (remBtnG.dataset.busy === '1') return;
                    remBtnG.dataset.busy = '1';
                    remBtnG.textContent = "Odstraňuji …";

                    var fd = new FormData();
                    fd.append('action', 'noriks_remove_upsell');
                    fd.append('order_id', orderId);
                    fd.append('item_id', entry.itemId);
                    fd.append('nonce', nonce);

                    fetch(ajaxUrl, { method: 'POST', body: fd })
                        .then(function(r) { return r.json(); })
                        .then(function(d) {
                            remBtnG.dataset.busy = '0';
                            remBtnG.innerHTML = '<span class="tyu2-btn__bin">🗑</span> Odstranit';
                            if (!d || !d.success) { if (d && d.data) alert(d.data); return; }
                            delete addedItems[key];
                            card.classList.remove('is-added');
                            remBtnG.style.display = 'none';
                            addBtnG.style.display = 'flex';
                            addBtnG.innerHTML = '<span class="tyu2-btn__plus">+</span> Přidat k objednávce';
                            card.querySelectorAll('.tyu2-select').forEach(function(sel){ sel.disabled = false; });
                            updateCartBar();
                            refreshOrderItems();
                        })
                        .catch(function() {
                            remBtnG.dataset.busy = '0';
                            remBtnG.innerHTML = '<span class="tyu2-btn__bin">🗑</span> Odstranit';
                        });
                });
            }
        });

        // "Završite kupnju" — zapre ponudbo in sprosti naročilo
        var finishBtn = document.getElementById('ty-grid-finish');
        if (finishBtn) {
            finishBtn.addEventListener('click', function() {
                finishBtn.disabled = true;
                finishBtn.textContent = "Dokončuji …";
                closeAll();
                var banner = document.getElementById('ty-added-banner');
                if (Object.keys(addedItems).length && banner) { banner.style.display = 'block'; }
                var items = document.getElementById('ty-order-items-section');
                if (items) items.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }

        // "Preskoci ponudbo" — zapre ponudbo brez dodajanja in sprosti naročilo
        var skipGridBtn = document.getElementById('ty-grid-skip');
        if (skipGridBtn) {
            skipGridBtn.addEventListener('click', function() {
                skipGridBtn.disabled = true;
                if (finishBtn) finishBtn.disabled = true;
                closeAll();
                var items = document.getElementById('ty-order-items-section');
                if (items) items.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }

        updateCartBar();
    }

    // Restore step 1 button from localStorage
    setTimeout(function() {
        var addedKey2 = 'ty_added_' + orderId;
        var addedMap = JSON.parse(localStorage.getItem(addedKey2) || '{}');
        if (typeof addedMap !== 'object' || Array.isArray(addedMap)) addedMap = {};
        var mainBtn = document.getElementById('ty-btn-add');
        if (mainBtn) {
            var mainPid = mainBtn.getAttribute('data-product-id');
            if (addedMap.hasOwnProperty(mainPid) || addedMap.hasOwnProperty(String(mainPid))) {
                mainBtn.textContent = "✓ PŘIDÁNO";
                mainBtn.classList.add('added');
                mainBtn.disabled = true;
                var mainDd = document.getElementById('ty-variation-select');
                var savedVal = addedMap[mainPid] || addedMap[String(mainPid)];
                if (mainDd && savedVal) { mainDd.value = savedVal; if (mainDd._tySync) { mainDd._tySync(); } mainDd.disabled = true; }
            }
        }
    }, 100);
    // Backup: if user leaves page while timer still running, use sendBeacon to release
    window.addEventListener('pagehide', function() {
        if (rem <= 0) {
            var data = new URLSearchParams();
            data.append('action', 'noriks_release_primary_hold');
            data.append('order_id', orderId);
            data.append('nonce', (document.getElementById('ty-upsell')||{dataset:{}}).dataset.nonce || '');
            navigator.sendBeacon(ajaxUrl, data);
        }
    });
})();

function removeUpsellItem(btn) {
    if (btn.disabled) return;
    btn.disabled = true;
    btn.textContent = '…';
    var itemId = btn.getAttribute('data-item-id');
    var orderId = btn.getAttribute('data-order-id');
    // funkcija je globalna (zunaj IIFE), zato nonce preberemo iz ovoja upsella
    var wrapEl  = document.getElementById('ty-upsell');
    var nonceEl = btn.getAttribute('data-nonce') || ( wrapEl ? wrapEl.dataset.nonce : '' );
    var fd = new FormData();
    fd.append('action', 'noriks_remove_upsell');
    fd.append('order_id', orderId);
    fd.append('item_id', itemId);
    fd.append('nonce', nonceEl);
    fetch('<?php echo admin_url("admin-ajax.php"); ?>', { method: 'POST', body: fd })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.success) {
                // Stay on step 2 — never go back to step 1
                // Just reset the grid add buttons so user can re-add
                document.querySelectorAll('.g-add-btn.added').forEach(function(gb) {
                    gb.disabled = false;
                    gb.classList.remove('added');
                    gb.textContent = "PŘIDAT";
                });
                // Refresh order items
                var rfd = new FormData();
                rfd.append('action', 'noriks_refresh_order_items');
                rfd.append('order_id', orderId);
                fetch('<?php echo admin_url("admin-ajax.php"); ?>', { method: 'POST', body: rfd })
                    .then(function(r2) { return r2.json(); })
                    .then(function(d2) {
                        if (d2.success) {
                            var itemsBody = document.getElementById('ty-order-items-body');
                            if (itemsBody) itemsBody.innerHTML = d2.data.items_html;
                            var headerSpan = document.getElementById('ty-order-items-header');
                            if (headerSpan) {
                                headerSpan.textContent = 'Položky objednávky (' + d2.data.item_count + ')';
                            }
                        }
                    });
            } else {
                btn.disabled = false;
                btn.textContent = '✕';
                alert(d.data || "Chyba");
            }
        })
        .catch(function() {
            btn.disabled = false;
            btn.textContent = '✕';
        });
}

function tyToggle(h) {
    h.classList.toggle('open');
    h.nextElementSibling.classList.toggle('open');
}
</script>
<!-- variation dropdown now visible -->
