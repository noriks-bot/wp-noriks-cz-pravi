<?php
/**
 * Thank You — Post-Purchase Upsell System
 * 
 * - COD orders only: upsell popup shown
 * - COD orders go to "primary-hold" for 5 min (upsell window), then auto → processing
 * - Non-COD orders: no upsell, normal flow (processing/completed)
 * - 50% off SALE price, server-side calculated
 * - Metadata: _noriks_upsell = "thank you upsell"
 * - Emails (new order + customer processing) are DELAYED until primary-hold → processing
 *   so they contain the final order value (with any upsell items)
 */
if ( ! defined( 'ABSPATH' ) ) exit;


// ─── 0. SUPPRESS emails for COD orders during upsell window ─────────────

add_filter( 'woocommerce_email_enabled_new_order', 'noriks_suppress_cod_email_during_upsell', 10, 2 );
function noriks_suppress_cod_email_during_upsell( $enabled, $order ) {
    if ( ! $order || ! is_a( $order, 'WC_Order' ) ) return $enabled;
    if ( $order->get_payment_method() !== 'cod' ) return $enabled;
    if ( ! $order->get_meta( '_noriks_upsell_emails_sent' ) ) {
        return false;
    }
    return $enabled;
}

add_filter( 'woocommerce_email_enabled_customer_processing_order', 'noriks_suppress_cod_customer_email_during_upsell', 10, 2 );
function noriks_suppress_cod_customer_email_during_upsell( $enabled, $order ) {
    if ( ! $order || ! is_a( $order, 'WC_Order' ) ) return $enabled;
    if ( $order->get_payment_method() !== 'cod' ) return $enabled;
    if ( ! $order->get_meta( '_noriks_upsell_emails_sent' ) ) {
        return false;
    }
    return $enabled;
}

add_filter( 'woocommerce_email_enabled_customer_on_hold_order', 'noriks_suppress_cod_onhold_email_during_upsell', 10, 2 );
function noriks_suppress_cod_onhold_email_during_upsell( $enabled, $order ) {
    if ( ! $order || ! is_a( $order, 'WC_Order' ) ) return $enabled;
    if ( $order->get_payment_method() !== 'cod' ) return $enabled;
    if ( ! $order->get_meta( '_noriks_upsell_emails_sent' ) ) {
        return false;
    }
    return $enabled;
}

function noriks_send_delayed_order_emails( $order_id ) {
    $order = wc_get_order( $order_id );
    if ( ! $order ) return;

    // Ze poslano (npr. cron in varovalka socasno) — ne posiljaj dvakrat
    if ( $order->get_meta( '_noriks_upsell_emails_sent' ) === 'yes' ) return;

    $order->update_meta_data( '_noriks_upsell_emails_sent', 'yes' );
    $order->save();

    $mailer = WC()->mailer();
    $emails = $mailer->get_emails();

    if ( isset( $emails['WC_Email_New_Order'] ) ) {
        $emails['WC_Email_New_Order']->trigger( $order_id, $order );
    }

    if ( isset( $emails['WC_Email_Customer_Processing_Order'] ) ) {
        $emails['WC_Email_Customer_Processing_Order']->trigger( $order_id, $order );
    }
}


// ─── 1. Register custom order status "primary-hold" ─────────────────────

add_action( 'init', 'noriks_register_primary_hold_status' );
function noriks_register_primary_hold_status() {
    register_post_status( 'wc-primary-hold', array(
        'label'                     => 'Primary Hold',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Primary Hold <span class="count">(%s)</span>', 'Primary Hold <span class="count">(%s)</span>' ),
    ));
}

// Add to WC status list
add_filter( 'wc_order_statuses', 'noriks_add_primary_hold_to_statuses' );
function noriks_add_primary_hold_to_statuses( $statuses ) {
    $statuses['wc-primary-hold'] = 'Primary Hold';
    return $statuses;
}


// ─── 2. COD orders → primary-hold (instead of processing) ───────────────

add_action( 'woocommerce_thankyou', 'noriks_set_cod_primary_hold', 1 );
function noriks_set_cod_primary_hold( $order_id ) {
    if ( ! $order_id ) return;
    $order = wc_get_order( $order_id );
    if ( ! $order ) return;

    // Only COD orders
    if ( $order->get_payment_method() !== 'cod' ) return;

    // Only if currently on-hold or processing (fresh order)
    $status = $order->get_status();
    if ( ! in_array( $status, array( 'on-hold', 'processing', 'pending' ) ) ) return;

    // Don't re-apply if already in primary-hold
    if ( $status === 'primary-hold' ) return;

    $order->update_status( 'primary-hold', 'Upsell window: 5 min hold for post-purchase offers.' );

    // Schedule auto-transition to processing after 5 minutes
    if ( ! wp_next_scheduled( 'noriks_primary_hold_to_processing', array( $order_id ) ) ) {
        wp_schedule_single_event( time() + 300, 'noriks_primary_hold_to_processing', array( $order_id ) );
    }
}

// Auto-transition: primary-hold → processing after 5 min
add_action( 'noriks_primary_hold_to_processing', 'noriks_transition_to_processing' );
function noriks_transition_to_processing( $order_id ) {
    $order = wc_get_order( $order_id );
    if ( ! $order ) return;

    // Only transition if still in primary-hold
    if ( $order->get_status() !== 'primary-hold' ) return;

    $order->update_status( 'processing', 'Upsell window expired — auto-transitioned to processing.' );

    noriks_send_delayed_order_emails( $order_id );
}


// ─── FAILSAFE: sweep stuck primary-hold orders ──────────────────────────
// Glavni mehanizem je en wp_cron dogodek na narocilo (zgoraj). Ta se obcasno
// izgubi (vsi cron dogodki so v eni opciji; socasni zapisi pod obremenitvijo
// drug drugega prepisejo) in narocilo bi ostalo v primary-hold za vedno
// (19. 11. 2026: ~5-10 % COD narocil na HR/HU/SK/CZ/RO/GR).
// Zato: najvec enkrat na minuto ob kateremkoli PHP zahtevku (frontend, admin,
// AJAX, REST, cron) poberi narocila, starejsa od 7 min, in jih sprosti z istim
// postopkom kot cron (processing + odlozeni maili).

add_action( 'init', 'noriks_primary_hold_sweep', 20 );
add_action( 'woocommerce_order_list_table_prepare_items_query_args', 'noriks_failsafe_on_admin_orders' );

function noriks_primary_hold_sweep() {
    if ( ! function_exists( 'wc_get_orders' ) ) return;
    if ( get_transient( 'noriks_ph_sweep_lock' ) ) return;
    set_transient( 'noriks_ph_sweep_lock', 1, 60 );

    $ids = wc_get_orders( array(
        'status'       => 'primary-hold',
        'limit'        => 20,
        'date_created' => '<' . ( time() - 420 ), // starejsa od 7 min
        'return'       => 'ids',
    ));

    foreach ( $ids as $order_id ) {
        $order = wc_get_order( $order_id );
        if ( ! $order || $order->get_status() !== 'primary-hold' ) continue;
        $order->add_order_note( 'Failsafe: primary-hold > 7 min (cron dogodek se ni izvedel) — sproscam.' );
        noriks_transition_to_processing( $order_id );
    }
}

function noriks_failsafe_on_admin_orders( $args ) {
    noriks_primary_hold_sweep();
    return $args;
}


// ─── 3. AJAX: Manual fix stuck orders + auto-release ─────────────────────

add_action( 'wp_ajax_noriks_release_primary_hold', 'noriks_release_primary_hold' );
add_action( 'wp_ajax_nopriv_noriks_release_primary_hold', 'noriks_release_primary_hold' );

function noriks_release_primary_hold() {
    $order_id = absint( $_POST['order_id'] ?? 0 );
    if ( ! $order_id ) wp_send_json_error( 'Missing order_id' );

    $order = wc_get_order( $order_id );
    if ( ! $order ) wp_send_json_error( 'Order not found' );
    if ( $order->get_status() !== 'primary-hold' ) wp_send_json_success( 'Already released' );

    $order->update_status( 'processing', 'Released from primary-hold (timer expired on client).' );

    noriks_send_delayed_order_emails( $order_id );

    wp_send_json_success( 'Released to processing' );
}





// ─── 4. AJAX: Refresh order items HTML ───────────────────────────────────

add_action( 'wp_ajax_noriks_refresh_order_items', 'noriks_refresh_order_items' );
add_action( 'wp_ajax_nopriv_noriks_refresh_order_items', 'noriks_refresh_order_items' );

function noriks_refresh_order_items() {
    $order_id = absint( $_POST['order_id'] ?? 0 );
    if ( ! $order_id ) wp_send_json_error( 'Missing order_id' );

    $order = wc_get_order( $order_id );
    if ( ! $order ) wp_send_json_error( 'Order not found' );

    // Build items HTML
    $items_html = '';
    foreach ( $order->get_items() as $item ) {
        $qty = $item->get_quantity();
        $meta_parts = array();
        foreach ( $item->get_formatted_meta_data( '_', true ) as $m ) {
            $meta_parts[] = wp_strip_all_tags( $m->display_key . ': ' . $m->display_value );
        }
        $is_upsell = $item->get_meta( '_noriks_upsell' ) === 'thank you upsell';
        $items_html .= '<div class="ty-item">';
        $items_html .= '<div>';
        $items_html .= '<div class="ty-item-name">' . $qty . '× ' . esc_html( $item->get_name() ) . '</div>';
        if ( $meta_parts ) {
            $items_html .= '<div class="ty-item-meta">' . esc_html( implode( ', ', $meta_parts ) ) . '</div>';
        }
        $items_html .= '</div>';
        $items_html .= '<div style="display:flex;align-items:center;gap:8px;">';
        $items_html .= '<div class="ty-item-price">' . $order->get_formatted_line_subtotal( $item ) . '</div>';
        /* remove button disabled */
        $items_html .= '</div>';
        $items_html .= '</div>';
    }

    // Build totals HTML
    $totals_html = '<div class="ty-totals">';
    foreach ( $order->get_order_item_totals() as $key => $total ) {
        $class = $key === 'order_total' ? 'ty-row ty-total-final' : 'ty-row';
        $totals_html .= '<div class="' . $class . '">';
        $totals_html .= '<span class="ty-row-label">' . $total['label'] . '</span>';
        $totals_html .= '<span class="ty-row-value">' . $total['value'] . '</span>';
        $totals_html .= '</div>';
    }
    $totals_html .= '</div>';

    wp_send_json_success( array(
        'items_html'  => $items_html . $totals_html,
        'item_count'  => $order->get_item_count(),
        'total'       => $order->get_formatted_order_total(),
    ));
}


// ─── 5. AJAX: Remove upsell item from order ─────────────────────────────

add_action( 'wp_ajax_noriks_remove_upsell', 'noriks_remove_upsell' );
add_action( 'wp_ajax_nopriv_noriks_remove_upsell', 'noriks_remove_upsell' );

function noriks_remove_upsell() {
    $order_id = absint( $_POST['order_id'] ?? 0 );
    $item_id  = absint( $_POST['item_id'] ?? 0 );
    if ( ! $order_id || ! $item_id ) wp_send_json_error( 'Missing data' );
    if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'noriks_upsell_' . $order_id ) ) {
        wp_send_json_error( 'Neplatný požadavek' );
    }

    $order = wc_get_order( $order_id );
    if ( ! $order ) wp_send_json_error( 'Order not found' );

    $item = $order->get_item( $item_id );
    if ( ! $item ) wp_send_json_error( 'Item not found' );

    // Only allow removing upsell items
    if ( ! $item->get_meta( '_noriks_upsell' ) ) {
        wp_send_json_error( 'Pouze upsell produkty mohou být odstraněny' );
    }

    // Only allow while in primary-hold
    if ( $order->get_status() !== 'primary-hold' ) {
        wp_send_json_error( 'Čas na úpravy vypršel' );
    }

    $product_name = $item->get_name();
    $order->remove_item( $item_id );
    $order->calculate_totals();
    $order->save();

    $order->add_order_note( sprintf( 'Upsell odstraněn: %s', $product_name ) );

    wp_send_json_success( array( 'message' => 'Odstraněno' ) );
}


// ─── 6. AJAX: Add upsell product to order ───────────────────────────────

add_action( 'wp_ajax_noriks_add_upsell', 'noriks_handle_add_upsell' );
add_action( 'wp_ajax_nopriv_noriks_add_upsell', 'noriks_handle_add_upsell' );

function noriks_handle_add_upsell() {
    $order_id     = absint( $_POST['order_id'] ?? 0 );
    $product_id   = absint( $_POST['product_id'] ?? 0 );
    $variation_id = absint( $_POST['variation_id'] ?? 0 );
    $nonce        = $_POST['nonce'] ?? '';

    if ( ! wp_verify_nonce( $nonce, 'noriks_upsell_' . $order_id ) ) {
        wp_send_json_error( 'Neplatný požadavek' );
    }

    $order = wc_get_order( $order_id );
    if ( ! $order ) wp_send_json_error( 'Objednávka nenalezena' );

    // Only allow upsell on COD orders in primary-hold
    if ( $order->get_payment_method() !== 'cod' ) {
        wp_send_json_error( 'Upsell je dostupný pouze při dobírce' );
    }
    if ( $order->get_status() !== 'primary-hold' ) {
        wp_send_json_error( 'Čas na přidání vypršel' );
    }

    // Time limit: 5 min from order creation (safety check)
    $created = $order->get_date_created();
    if ( $created && ( time() - $created->getTimestamp() ) > 330 ) { // 5.5 min grace
        wp_send_json_error( 'Čas na přidání vypršel' );
    }

    // Get the actual product (variation or simple)
    $product = $variation_id ? wc_get_product( $variation_id ) : wc_get_product( $product_id );
    if ( ! $product ) wp_send_json_error( 'Produkt nenalezen' );

    // Duplicate check
    $check_product_id = $variation_id ? $product_id : $product->get_id();
    foreach ( $order->get_items() as $item ) {
        $item_product_id = $item->get_product_id();
        $item_variation_id = $item->get_variation_id();
        if ( $item_product_id == $check_product_id || ( $variation_id && $item_variation_id == $variation_id ) ) {
            if ( $item->get_meta( '_noriks_upsell' ) ) {
                wp_send_json_error( 'Tento produkt jste již přidali' );
            }
        }
    }

    // ─── Calculate 50% off SALE price ───
    $sale_price = (float) $product->get_sale_price();
    $current_price = (float) $product->get_price();

    if ( $sale_price && $current_price ) {
        $active_price = min( $sale_price, $current_price );
    } else {
        $active_price = $current_price ?: $sale_price;
    }

    if ( ! $active_price ) {
        $active_price = (float) $product->get_regular_price();
    }
    if ( ! $active_price ) {
        wp_send_json_error( 'Cena produktu není dostupná' );
    }

    $quantity = max( 1, absint( $_POST['quantity'] ?? 3 ) );
    // Korak 1 ponuja samo crno majico (250) v kolicinah iz cenika. Vse drugo zavrnemo -
    // sicer bi se z rocno zahtevo dal dodati poljuben izdelek ali poljubna kolicina
    // po ceni enega kosa. Bokseric v ponudbi ni vec (17. 11. 2026).
    if ( $product_id !== 250 ) {
        wp_send_json_error( 'Tento produkt není v nabídce' );
    }
    if ( ! $variation_id || (int) wp_get_post_parent_id( $variation_id ) !== $product_id ) {
        wp_send_json_error( 'Neplatná varianta' );
    }
    $qty_prices = array( 1 => 199, 3 => 499, 6 => 829 );
    if ( ! isset( $qty_prices[ $quantity ] ) ) {
        wp_send_json_error( 'Neplatné množství' );
    }
    $total_price = $qty_prices[ $quantity ];
    $upsell_price = $total_price / $quantity;

    // Add to order
    $item_id = $order->add_product( $product, $quantity, array(
        'subtotal' => $upsell_price * $quantity,
        'total'    => $upsell_price * $quantity,
    ));

    if ( ! $item_id ) wp_send_json_error( 'Chyba při přidávání' );

    // Mark as upsell
    $item = $order->get_item( $item_id );
    $upsell_type = sanitize_text_field( $_POST['upsell_type'] ?? 'post_purchase_step1' );
    $item->add_meta_data( '_noriks_upsell', $upsell_type, true );
    $item->save();

    $order->calculate_totals();
    $order->save();

    $order->add_order_note(
        sprintf(
            'Thank you upsell: %s přidáno s 50%% slevou — akční cena: %s, upsell cena: %s',
            $product->get_name(),
            wc_price( $active_price ),
            wc_price( $upsell_price )
        )
    );

    wp_send_json_success( array(
        'message'      => 'Přidáno',
        'item_id'      => $item_id,
        'product_name' => $product->get_name(),
        'upsell_price' => $upsell_price,
        'total'        => $order->get_formatted_order_total(),
    ));
}


// ─── 7. AJAX: korak 2 — dodaj ponudbo iz mreze ──────────────────────────
//
// Brskalnik poslje samo kljuc ponudbe ter izbrano barvo in velikost.
// Izdelek, kolicina in cena se preberejo iz ponudbe na strezniku
// (noriks_ty2_find_card), zato jih ni mogoce podtakniti.
//
// Metapodatki postavke so v ISTI obliki kot pri nakupu orto ponudbe na produktni
// strani (gck_order_item_meta), da jih skladisce in Metakocka bereta enako:
//   1, 2, … N          "Barva - Velikost" za vsak kos (brez atributov: ime izdelka)
//   _bundle_pairs      N
//   _offer_id          "N__ty2"
// in dodatno kot ostali upselli (product_page_upsell):
//   _noriks_upsell         post_purchase_step2
//   _noriks_upsell_pieces  N
//   _noriks_upsell_sku     SKU izdelka
//   _noriks_upsell_label   naslov kartice
//   _noriks_upsell_offer   kljuc ponudbe (za podvojitve in porocila)

add_action( 'wp_ajax_noriks_add_upsell_step2', 'noriks_handle_add_upsell_step2' );
add_action( 'wp_ajax_nopriv_noriks_add_upsell_step2', 'noriks_handle_add_upsell_step2' );

function noriks_handle_add_upsell_step2() {
    $order_id  = absint( $_POST['order_id'] ?? 0 );
    $offer_key = sanitize_key( wp_unslash( $_POST['offer_key'] ?? '' ) );
    $nonce     = $_POST['nonce'] ?? '';

    if ( ! wp_verify_nonce( $nonce, 'noriks_upsell_' . $order_id ) ) {
        wp_send_json_error( 'Neplatný požadavek' );
    }
    $order = wc_get_order( $order_id );
    if ( ! $order ) { wp_send_json_error( 'Objednávka nenalezena' ); }
    if ( $order->get_payment_method() !== 'cod' ) {
        wp_send_json_error( 'Upsell je dostupný pouze při dobírce' );
    }
    if ( $order->get_status() !== 'primary-hold' ) {
        wp_send_json_error( 'Čas na přidání vypršel' );
    }
    $created = $order->get_date_created();
    if ( $created && ( time() - $created->getTimestamp() ) > 330 ) {
        wp_send_json_error( 'Čas na přidání vypršel' );
    }
    if ( ! function_exists( 'noriks_ty2_find_card' ) ) {
        wp_send_json_error( 'Nabídka není dostupná' );
    }

    $card = noriks_ty2_find_card( $offer_key, $order );
    if ( ! $card ) { wp_send_json_error( 'Nabídka již není dostupná' ); }

    // ista ponuda samo jednom
    foreach ( $order->get_items() as $it ) {
        if ( $it->get_meta( '_noriks_upsell_offer' ) === $offer_key ) {
            wp_send_json_error( 'Tuto nabídku jste již přidali' );
        }
    }

    $product = wc_get_product( $card['product_id'] );
    if ( ! $product ) { wp_send_json_error( 'Produkt nenalezen' ); }

    // boje odreduje ponuda (fiksna ili mijesane), kupac bira samo velicinu
    $size = sanitize_text_field( wp_unslash( $_POST['upsell_size'] ?? '' ) );
    if ( $card['sizes'] && ! in_array( $size, $card['sizes'], true ) ) { wp_send_json_error( 'Vyberte velikost' ); }
    if ( ! $card['sizes'] ) { $size = ''; }
    // izbira, ki je ponudba ne doloci namesto kupca (stran pri KneeFix)
    $choice = sanitize_text_field( wp_unslash( $_POST['upsell_choice'] ?? '' ) );
    if ( ! empty( $card['choice']['options'] ) ) {
        if ( ! in_array( $choice, $card['choice']['options'], true ) ) { wp_send_json_error( 'Vyberte: ' . $card['choice']['name'] ); }
    } else {
        $choice = '';
    }
    $piece_colors = array_values( (array) $card['piece_colors'] );
    $color = $piece_colors[0] ?? '';
    // korak 1 z izbiro barve (npr. Slim): barvo izbere kupec, veljavna je samo barva izdelka
    if ( ! empty( $card['pick_color'] ) ) {
        $izbrana = sanitize_text_field( wp_unslash( $_POST['upsell_color'] ?? '' ) );
        if ( ! in_array( $izbrana, (array) $card['colors'], true ) ) { wp_send_json_error( 'Vyberte barvu' ); }
        $piece_colors = array_fill( 0, max( 1, (int) $card['qty'] ), $izbrana );
        $color = $izbrana;
    }

    // varijabilan proizvod: u narudžbu ide prava varijacija, ne prva po redu
    $line_product = $product;
    if ( $product->is_type( 'variable' ) ) {
        $vid = noriks_ty2_match_variation( $product, $color, $size );
        if ( ! $vid ) { wp_send_json_error( 'Tato kombinace není skladem' ); }
        $line_product = wc_get_product( $vid );
    }

    $pieces = (int) $card['qty'];
    $total  = (float) $card['new'];
    $piece_labels = array();
    for ( $i = 0; $i < $pieces; $i++ ) {
        // isti zapis kot na produktni strani: "Crna - L", pri KneeFix "Desno - M (50–75 kg)"
        $pl = implode( ' - ', array_filter( array( $choice !== '' ? $choice : ( $piece_colors[ $i ] ?? $color ), $size ) ) );
        $piece_labels[] = $pl !== '' ? $pl : $product->get_name();
    }
    $piece_label = implode( ', ', array_unique( $piece_labels ) );

    // kolicina stavke je 1, cijena je paketna — isto kao orto ponuda s produktne stranice
    $item_id = $order->add_product( $line_product, 1, array(
        'subtotal' => $total,
        'total'    => $total,
    ) );
    if ( ! $item_id ) { wp_send_json_error( 'Chyba při přidávání' ); }

    $item = $order->get_item( $item_id );
    for ( $i = 1; $i <= $pieces; $i++ ) {
        $item->add_meta_data( (string) $i, $piece_labels[ $i - 1 ], true );
    }
    $item->add_meta_data( '_bundle_pairs', $pieces, true );
    $item->add_meta_data( '_offer_id', $pieces . '__ty2', true );
    // kljuc "s1…" = korak 1 (+1 istega izdelka), npr. post_purchase_step1_kompsfit
    $upsell_tip = ( strpos( $offer_key, 's1' ) === 0 )
        ? 'post_purchase_step1_' . strtolower( preg_replace( '/[^A-Za-z0-9]/', '', str_replace( 'NORIKS-', '', strtoupper( (string) $card['sku'] ) ) ) )
        : 'post_purchase_step2';
    $item->add_meta_data( '_noriks_upsell', $upsell_tip, true );
    $item->add_meta_data( '_noriks_upsell_pieces', $pieces, true );
    $item->add_meta_data( '_noriks_upsell_sku', $card['sku'], true );
    $item->add_meta_data( '_noriks_upsell_label', $card['label'], true );
    $item->add_meta_data( '_noriks_upsell_offer', $offer_key, true );
    $item->save();

    $order->calculate_totals();
    $order->save();

    $order->add_order_note( sprintf(
        'Thank you upsell (%s): %s [%s] — %d kom, %s, ukupno %s',
        strpos( $offer_key, 's1' ) === 0 ? 'korak 1' : 'korak 2',
        $product->get_name(),
        $card['label'],
        $pieces,
        $piece_label,
        wc_price( $total )
    ) );

    wp_send_json_success( array(
        'message'      => 'Přidáno',
        'item_id'      => $item_id,
        'product_name' => $product->get_name(),
        'price'        => $total,
        'total'        => $order->get_formatted_order_total(),
    ) );
}
