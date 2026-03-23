<?php
/**
 * Plugin Name: Inline Bundle Builder (CZ)
 * Description: Sestavte balíček v responzním modálu. Ukládá každý slot jako očíslovanou položku (např. "1: Černé tričko - L") do objednávek. V košíku/pokladně se zobrazuje stejný výpis bez štítku.
 * Version:     3.1.1-cz
 * Author:      You
 */

if (!defined('ABSPATH')) exit;

class IBB_Plugin_SimpleMeta_CZ {

    public function __construct() {
        add_shortcode('inline_bundle_builder', [$this, 'shortcode']);

        // AJAX: resolve variation from attributes
        add_action('wp_ajax_ibb_find_variation',        [$this, 'ajax_find_variation']);
        add_action('wp_ajax_nopriv_ibb_find_variation', [$this, 'ajax_find_variation']);

        // Save/display meta
        add_filter('woocommerce_add_cart_item_data',              [$this, 'hook_add_cart_item_data'], 10, 3);
        add_filter('woocommerce_get_item_data',                   [$this, 'hook_display_item_data'],  10, 2);
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'hook_order_item_meta'],    10, 4);
    }

    /* -------------------------------------------------
     * Shortcode (enqueues assets ONLY when rendered)
     * ------------------------------------------------*/
    /**
     * Usage: echo do_shortcode('[inline_bundle_builder products="504,505,506" slots="3"]');
     */
    public function shortcode($atts) {
        if (!class_exists('WooCommerce')) return '';

        $a = shortcode_atts([
            'products' => '',
            'slots'    => 3,
        ], $atts);

        $ids   = array_filter(array_map('absint', explode(',', $a['products'])));
        $slots = max(1, absint($a['slots']));
        if (!$ids) return '<em>Žádné produkty nejsou nastaveny.</em>';

        // Enqueue assets only when shortcode renders
        wp_enqueue_style ('ibb-style', plugins_url('ibb.css', __FILE__), [], '3.1.1-cz');
        wp_enqueue_script('ibb-script', plugins_url('ibb.js?dsdsds',  __FILE__), ['jquery'], '3.1.1-cz', true);

        // Hide the stray ":" dt in variation blocks
        $ibb_inline_css = '
          .widget_shopping_cart .variation dt.variation-,
          .woocommerce-mini-cart .variation dt.variation-,
          .woocommerce-cart .variation dt.variation-,
          .woocommerce-checkout .variation dt.variation-,
          .xoo-wsc-container .variation dt.variation- { display:none!important; }
          .widget_shopping_cart .variation dd.variation-,
          .woocommerce-mini-cart .variation dd.variation-,
          .woocommerce-cart .variation dd.variation-,
          .woocommerce-checkout .variation dd.variation-,
          .xoo-wsc-container .variation dd.variation- { margin:0; padding:0; }
        ';
        wp_add_inline_style('ibb-style', $ibb_inline_css);

        wp_localize_script('ibb-script', 'IBB', [
            'ajax'  => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ibb'),
        ]);

        // Minimal inline helper that ensures hidden ibb_cart_data exists & stays synced
        $inline_js = <<<JS
jQuery(function(\$){
  var \$wrap = \$('.ibb-wrapper').first();
  if (!\$wrap.length) return;

  var \$form = \$wrap.closest('form.cart');
  if (!\$form.length) \$form = \$('form.cart').first();

  var \$btn = \$form.find('.single_add_to_cart_button');
  if (\$btn.length && !\$form.closest('#yith-quick-view-content').length) {
    \$btn.prop('disabled', true).addClass('ibb-disabled');
  }

  function ensureHidden(){
    var \$field = \$form.find('input.ibb-cart-data[name="ibb_cart_data"]');
    if(!\$field.length){
      \$field = \$('<input type="hidden" name="ibb_cart_data" class="ibb-cart-data" value="[]">');
      \$form.append(\$field);
    }
    return \$field;
  }

  function syncHidden(){
    var data='[]';
    try{
      if (window.IBB && window.IBB.carts){
        var wid = \$wrap.attr('data-ibb-id');
        if (wid && window.IBB.carts[wid]) data = JSON.stringify(window.IBB.carts[wid]);
      }
    }catch(e){}
    ensureHidden().val(data);
  }

  syncHidden();
  \$(document).on('ibb:slotsFilled ibb:slotsNotFilled', '.ibb-wrapper', function(e){
    syncHidden();
    if (!\$btn.length) return;
    if (e.type === 'ibb:slotsFilled'){
      \$btn.prop('disabled', false).removeClass('ibb-disabled');
    } else if (!\$form.closest('#yith-quick-view-content').length) {
      \$btn.prop('disabled', true).addClass('ibb-disabled');
    }
  });

  \$(document).on('click', '.ibb-add, .ibb-slot-remove', function(){ setTimeout(syncHidden, 0); });
  \$form.on('submit', syncHidden);
});
JS;
        wp_add_inline_script('ibb-script', $inline_js, 'after');

        // Build product cards for modal
        $cards = [];
        foreach ($ids as $pid) {
            $p = wc_get_product($pid);
            if (!$p || !$p->is_purchasable()) continue;

            $c = [
                'id'    => $pid,
                'type'  => $p->get_type(),
                'title' => $p->get_name(),
                'price' => $p->get_price_html(),
                'img'   => wp_get_attachment_image_url($p->get_image_id() ?: get_post_thumbnail_id($pid), 'woocommerce_single'),
                'attrs' => [],
            ];

            if ($p->is_type('variable')) {
                $vattrs = $p->get_variation_attributes();
                foreach ($vattrs as $tax_raw => $options) {
                    $tax_norm = str_replace('attribute_', '', $tax_raw); // may still include pa_
                    $label = wc_attribute_label($tax_norm) ?: wc_attribute_label($tax_raw) ?: 'Možnost';
                    $opts  = [];
                    foreach ((array)$options as $opt) {
                        $tax_for_terms = taxonomy_exists($tax_norm) ? $tax_norm : (taxonomy_exists('pa_' . $tax_norm) ? 'pa_' . $tax_norm : $tax_norm);
                        $term = taxonomy_exists($tax_for_terms) ? get_term_by('slug', $opt, $tax_for_terms) : null;
                        $opts[] = [
                            'value' => $term ? $term->slug : (string)$opt,
                            'label' => $term ? $term->name : (string)$opt,
                        ];
                    }
                    $c['attrs'][] = [
                        'raw'   => $tax_raw,
                        'norm'  => ltrim($tax_norm, '_'),
                        'label' => $label,
                        'opts'  => $opts,
                    ];
                }
            }

            $cards[] = $c;
        }

        // subtitle text: slots + current product price
        global $product;
        $bundle_price = '';
        if ($product instanceof WC_Product) {
            $price = $product->get_sale_price() ?: $product->get_regular_price();
            if ($price) $bundle_price = wc_price($price);
        }

        ob_start(); ?>
        <div class="ibb-wrapper" data-slots="<?php echo esc_attr($slots); ?>">
          <div class="ibb-slots">
            <?php for ($i=0; $i<$slots; $i++): ?>
              <div class="ibb-slot-wrap">
                <div class="ibb-slot-header"><?php echo sprintf(esc_html__('Vyberte tričko %1$d z %2$d', 'ibb'), $i+1, $slots); ?></div>
                <div class="ibb-slot is-empty" data-index="<?php echo $i; ?>" tabindex="0">
                  <div class="ibb-slot-thumb"><span class="ibb-slot-plus" aria-hidden="true">+</span></div>
                  <div class="ibb-slot-info">
                    <span class="ibb-slot-attr"><?php esc_html_e('Vyberte tričko','ibb'); ?></span>
                    <span class="ibb-slot-price" hidden></span>
                  </div>
                </div>
              </div>
            <?php endfor; ?>
          </div>

          <!-- Modal scaffold (handled by ibb.js) -->
          <div class="ibb-modal" aria-hidden="true">
            <div class="ibb-modal__box" role="dialog" aria-modal="true" aria-label="Sestavení balíčku">
              <div class="ibb-modal__head">
                <div class="ibb-steps-nav">
                  <button type="button" class="ibb-nav ibb-prev">‹</button>
                  <div class="ibb-steps"></div>
                  <button type="button" class="ibb-nav ibb-next">›</button>
                </div>
                <button type="button" class="ibb-modal__close">×</button>
                <div class="ibb-titlebar">
                  <h2 class="ibb-heading"><?php esc_html_e('Vyberte', 'ibb'); ?> <span class="ibb-step-num">1</span> <?php esc_html_e('tričko', 'ibb'); ?></h2>
                  <?php if ($bundle_price): ?>
                    <p class="ibb-sub">
                      <?php printf(esc_html__('Přidejte %1$d triček a získejte balíček za %2$s', 'ibb'), $slots, $bundle_price); ?>
                    </p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="ibb-modal__body">
                <div class="ibb-grid">
                  <?php foreach ($cards as $c): ?>
                    <div class="ibb-card" data-id="<?php echo esc_attr($c['id']); ?>" data-type="<?php echo esc_attr($c['type']); ?>">
                      <img src="<?php echo esc_url($c['img']); ?>" alt="<?php echo esc_attr($c['title']); ?>">
                      <div class="ibb-card-body">
                        <div class="ibb-card-title"><?php echo esc_html($c['title']); ?></div>
                        <?php if ($c['type'] === 'variable' && !empty($c['attrs'])): ?>
                          <?php foreach ($c['attrs'] as $a): ?>
                            <label class="ibb-label"><?php echo esc_html($a['label']); ?></label>
                            <select class="ibb-attr"
                              data-taxonomy="<?php echo esc_attr($a['norm']); ?>"
                              data-taxonomy-raw="<?php echo esc_attr('attribute_' . $a['norm']); ?>">
                              <option value=""><?php echo esc_html__('Vyberte variantu', 'ibb'); ?></option>
                              <?php foreach ($a['opts'] as $o): ?>
                                <option value="<?php echo esc_attr($o['value']); ?>"><?php echo esc_html($o['label']); ?></option>
                              <?php endforeach; ?>
                            </select>
                          <?php endforeach; ?>
                        <?php endif; ?>
                        <button type="button" class="ibb-add"><?php esc_html_e('Přidat do balíčku','ibb'); ?></button>
                        <div class="ibb-msg" aria-live="polite"></div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
          <!-- /modal -->
        </div>
        <?php
        return ob_get_clean();
    }

    /* -------------------------------------------------
     * Variation AJAX
     * ------------------------------------------------*/
    public function ajax_find_variation() {
        check_ajax_referer('ibb', 'nonce');

        $product_id = absint($_POST['product_id'] ?? 0);
        $attrs_in   = isset($_POST['attributes']) ? (array) $_POST['attributes'] : [];

        $product = wc_get_product($product_id);
        if (!$product || !$product->is_type('variable')) {
            wp_send_json_error(['message' => 'Invalid product']);
        }

        // Map incoming keys to Woo's expected "attribute_{taxonomy}" keys,
        // fixing cases where the client already sent "attribute_*".
        $attrs = [];
        foreach ($attrs_in as $k => $v) {
            $k = sanitize_title($k);
            if (strpos($k, 'attribute_') === 0) {
                $k = substr($k, 10);
            }
            if (strpos($k, 'pa_') === 0) {
                $k = substr($k, 3);
            }

            $tax = taxonomy_exists('pa_' . $k) ? 'pa_' . $k : $k;
            $v_clean = taxonomy_exists($tax) ? sanitize_title($v) : wc_clean($v);

            $attrs['attribute_' . $tax] = $v_clean;
        }

        $ds = new WC_Product_Data_Store_CPT();
        $variation_id = $ds->find_matching_product_variation($product, $attrs);

        if (!$variation_id) {
            wp_send_json_error(['message' => 'Tato varianta není dostupná.']);
        }

        $vp = wc_get_product($variation_id);
        wp_send_json_success([
            'variation_id' => $variation_id,
            'price'        => $vp ? $vp->get_price_html() : '',
        ]);
    }

    /* -------------------------------------------------
     * Helpers
     * ------------------------------------------------*/
    private function canon_key($k) : string {
        // Lowercase, remove diacritics, drop Woo prefixes (attribute_, pa_)
        $k = is_string($k) ? $k : '';
        if (function_exists('remove_accents')) {
            $k = remove_accents($k);
        }
        $k = strtolower($k);
        $k = preg_replace('~^(attribute_|pa_)~', '', $k);
        return $k; // e.g. "velikost", "size", "velkost", "velicina"
    }

    private function parse_slots_from_post() : array {
        if (empty($_POST['ibb_cart_data'])) return [];
        $raw   = wp_unslash($_POST['ibb_cart_data']);
        $slots = json_decode($raw, true);
        return is_array($slots) ? $slots : [];
    }

    private function slot_line(array $slot) : string {
        $title = !empty($slot['title']) ? trim((string)$slot['title']) : '';
        $size  = '';
        if (!empty($slot['attr']) && is_array($slot['attr'])) {
            foreach ($slot['attr'] as $k => $v) {
                $val = is_scalar($v) ? (string)$v : '';
                if ($val === '') continue;

                $ck = $this->canon_key($k);
                // Treat these as "size" attributes
                if (in_array($ck, ['size','velikost','velkost','velicina'], true)
                    || preg_match('~size|velikost|velkost|velicina~i', $ck)) {
                    $size = function_exists('mb_strtoupper') ? mb_strtoupper($val, 'UTF-8') : strtoupper($val);
                    break;
                }
            }
        }
        return trim($title . ($size ? " - $size" : ''));
    }

    private function build_slot_lines(array $slots) : array {
        $filled = array_values(array_filter($slots, function($s){ return !empty($s['product_id']); }));
        $lines = [];
        foreach ($filled as $slot) {
            $lines[] = $this->slot_line($slot);
        }
        return $lines;
    }

    /* -------------------------------------------------
     * Cart & Order Meta
     * ------------------------------------------------*/
    public function hook_add_cart_item_data($cart_item_data, $product_id, $variation_id) {
        $slots = $this->parse_slots_from_post();
        if (!$slots) return $cart_item_data;

        // Keep raw slots JSON for later display/order meta
        $cart_item_data['_ibb_slots_json'] = wp_json_encode($slots);

        // Ensure uniqueness so cart items don't merge
        $lines = $this->build_slot_lines($slots);
        $cart_item_data['ibb_unique_key'] = md5(wp_json_encode([$lines]) . '|' . microtime(true) . '|' . wp_rand());

        return $cart_item_data;
    }

    public function hook_display_item_data($item_data, $cart_item) {
        $slots_json = !empty($cart_item['_ibb_slots_json']) ? $cart_item['_ibb_slots_json'] : '';
        $slots = $slots_json ? json_decode($slots_json, true) : [];
        if (!is_array($slots)) $slots = [];

        $lines = $this->build_slot_lines($slots);
        if ($lines) {
            $numbered = [];
            foreach ($lines as $i => $line) {
                $numbered[] = ($i+1) . ': ' . $line;
            }
            $escaped = array_map('esc_html', $numbered);
            $value = implode('<br>', $escaped);

            // name=false -> Woo prints only the value (no dt)
            $item_data[] = ['name' => false, 'display' => $value];
        }
        return $item_data;
    }

    public function hook_order_item_meta($item, $cart_item_key, $values, $order) {
        $slots_json = !empty($values['_ibb_slots_json']) ? $values['_ibb_slots_json'] : '';
        $slots = $slots_json ? json_decode($slots_json, true) : [];
        if (!is_array($slots)) $slots = [];

        $lines = $this->build_slot_lines($slots);
        if ($lines) {
            foreach ($lines as $i => $line) {
                $item->add_meta_data((string)($i+1), sanitize_text_field($line), true);
            }
        }
    }
}

new IBB_Plugin_SimpleMeta_CZ();
