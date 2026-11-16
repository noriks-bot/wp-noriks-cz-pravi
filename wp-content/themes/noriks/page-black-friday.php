<?php
/**
 * Template Name: Black Friday
 *
 * Pristajalna stran: hero pasica, tekoci trak, kartice izdelkov z akcijskimi cenami,
 * izbira velikosti in dodajanje v kosarico neposredno s kartice.
 * Ista predloga za vseh pet strani (glej noriks_bf_strani()); slog in vsebino doloci
 * slug strani. Besedila in izbor izdelkov: functions/black-friday.php.
 */
get_header();

$l     = noriks_bf_l();
$stran = noriks_bf_stran();
$tip   = $stran['tip'];
$prag  = noriks_bf_prag();
$sub   = $l['sub'];
$rang  = false;

$razdelki = array();
switch ( $tip ) {
    case 'basics':
        $sub = $l['sub2'];
        $razdelki[] = array( 'k' => $l['b_k'], 'a' => $l['b_a'], 'b' => $l['b_b'], 'izdelki' => noriks_bf_oblacila( null, true ) );
        break;
    case 'majice':
        $sub = $l['sub_maj'];
        $razdelki[] = array( 'k' => 'BLACK FRIDAY · ' . $l['w_maj'], 'a' => $l['w_maj'], 'b' => $l['akcija'], 'izdelki' => noriks_bf_oblacila( array( 'majice', 'majica-darila' ) ) );
        break;
    case 'bokserice':
        $sub = $l['sub_box'];
        $razdelki[] = array( 'k' => 'BLACK FRIDAY · ' . $l['w_box'], 'a' => $l['w_box'], 'b' => $l['akcija'], 'izdelki' => noriks_bf_oblacila( array( 'bokserice' ) ) );
        break;
    case 'top9':
        $sub  = $l['sub_top'];
        $rang = true;
        $razdelki[] = array( 'k' => 'BLACK FRIDAY · TOP 9', 'a' => 'TOP 9', 'b' => $l['top_b'], 'izdelki' => noriks_bf_top( 9 ) );
        break;
    default:
        $razdelki[] = array( 'k' => $l['sek_k'], 'a' => $l['sek_a'], 'b' => $l['sek_b'], 'izdelki' => noriks_bf_novi() );
        $razdelki[] = array( 'k' => $l['b_k'],   'a' => $l['b_a'],   'b' => $l['b_b'],   'izdelki' => noriks_bf_oblacila() );
}
$najvec = 0;
foreach ( $razdelki as $r ) { foreach ( $r['izdelki'] as $x ) { if ( $x['pop'] > $najvec ) { $najvec = $x['pop']; } } }

$P = sprintf( $l['pct'], $najvec );
$D = $prag > 0 ? trim( html_entity_decode( wp_strip_all_tags( wc_price( $prag, array( 'decimals' => ( floor( $prag ) == $prag ? 0 : wc_get_price_decimals() ) ) ) ), ENT_QUOTES, 'UTF-8' ) ) : '';

$trak = array( 'BLACK FRIDAY 2026' );
if ( $najvec > 0 ) { $trak[] = str_replace( '{P}', $P, $l['trak_p'] ); }
if ( $D !== '' )   { $trak[] = str_replace( '{D}', $D, $l['trak_d'] ); }
$trak[] = $l['trak_30'];

$js = array( 'dodajam' => $l['dodajam'], 'dodano' => $l['dodano'], 'dodaj' => $l['dodaj'], 'izberi' => $l['izberi'], 'napaka' => $l['napaka'] );
$st = 0;
?>
<!-- nbf 2026-11-16-d -->
<div class="nbf nbf--<?php echo esc_attr( $stran['slog'] ); ?> nbf--<?php echo esc_attr( $tip ); ?>" data-ajax="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-t="<?php echo esc_attr( wp_json_encode( $js ) ); ?>">

  <section class="nbf-hero">
    <div class="nbf-okvir"><b>BLACK</b><i>FRIDAY</i></div>
    <h1 class="nbf-h1"><?php echo wp_kses( str_replace( '{P}', '<em>' . esc_html( $P ) . '</em>', esc_html( $l['h1'] ) ), array( 'em' => array() ) ); ?></h1>
    <p class="nbf-sub"><?php echo esc_html( $sub ); ?></p>
    <a class="nbf-gumb" href="#nbf-ponudbe"><?php echo esc_html( $l['gumb_hero'] ); ?></a>
  </section>

  <?php foreach ( $razdelki as $ri => $r ) : if ( empty( $r['izdelki'] ) ) { continue; } ?>

  <div class="nbf-trak" aria-hidden="true"><div class="nbf-trak__t">
    <?php for ( $z = 0; $z < 6; $z++ ) : foreach ( $trak as $t ) : ?><span><?php echo esc_html( $t ); ?></span><span class="nbf-zv">✦</span><?php endforeach; endfor; ?>
  </div></div>

  <section class="nbf-sek"<?php echo $ri === 0 ? ' id="nbf-ponudbe"' : ''; ?>><div class="nbf-v">
    <div class="nbf-k1"><?php echo esc_html( $r['k'] ); ?></div>
    <h2 class="nbf-h2"><?php echo esc_html( $r['a'] ); ?> <em><?php echo esc_html( $r['b'] ); ?></em></h2>
    <p class="nbf-p"><?php echo esc_html( $l['sek_p'] ); ?></p>

    <div class="nbf-grid">
      <?php foreach ( $r['izdelki'] as $x ) :
          $p     = $x['p'];
          $url   = get_permalink( $p->get_id() );
          $pc    = sprintf( $l['pct'], $x['pop'] );
          $slika = $p->get_image( 'woocommerce_single', array( 'loading' => $st < 4 ? 'eager' : 'lazy', 'alt' => $p->get_name() ) );
          $st++;
          $hitro = in_array( $x['nacin'], array( 'orto', 'var', 'simple' ), true );
          $cfg   = array( 'id' => $p->get_id(), 'nacin' => $x['nacin'], 'offer' => $x['offer'], 'kos' => $x['kos'], 'attrs' => array() );
          foreach ( $x['attrs'] as $a ) { $cfg['attrs'][] = array( 'p' => $a['p'], 'v' => array_map( function ( $v ) { return $v[0]; }, $a['v'] ) ); }
          ?>
        <article class="nbf-k"<?php if ( $hitro ) : ?> data-bf="<?php echo esc_attr( wp_json_encode( $cfg ) ); ?>"<?php endif; ?>>
          <a class="nbf-k__s" href="<?php echo esc_url( $url ); ?>"><?php echo $slika; // phpcs:ignore -- WooCommerce vrne varen <img> ?></a>
          <div class="nbf-k__t">
            <?php if ( $rang ) : ?><div class="nbf-k__rang">#<?php echo (int) $st; ?></div><?php endif; ?>
            <h3 class="nbf-k__n"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $p->get_name() ); ?></a></h3>
            <?php if ( $x['paket'] !== '' && $x['kos'] > 1 ) : ?><div class="nbf-k__pk"><?php echo esc_html( $x['paket'] ); ?></div><?php endif; ?>
            <div class="nbf-k__c">
              <div class="nbf-r"><span><?php echo esc_html( $l['redna'] ); ?></span><s><?php echo wp_kses_post( wc_price( $x['red'] ) ); ?></s></div>
              <div class="nbf-r nbf-r--p"><span><?php echo esc_html( $l['popust'] ); ?><b>−<?php echo esc_html( $pc ); ?></b></span><em>−<?php echo wp_kses_post( wc_price( $x['red'] - $x['akc'] ) ); ?></em></div>
              <div class="nbf-r nbf-r--z"><span><?php echo esc_html( $l['placilo'] ); ?></span><strong><?php echo wp_kses_post( wc_price( $x['akc'] ) ); ?></strong></div>
            </div>
            <?php if ( $hitro ) : ?>
              <?php $izbirni = array_filter( $x['attrs'], function ( $a ) { return count( $a['v'] ) > 1; } ); ?>
              <?php if ( $izbirni ) : ?>
              <div class="nbf-k__izb">
                <?php foreach ( $izbirni as $a ) : ?>
                <select class="nbf-sel" data-polje="<?php echo esc_attr( $a['p'] ); ?>" data-ime="<?php echo esc_attr( $a['ime'] ); ?>" aria-label="<?php echo esc_attr( $a['ime'] ); ?>">
                  <option value=""><?php echo esc_html( $a['ime'] ); ?></option>
                  <?php foreach ( $a['v'] as $v ) : ?><option value="<?php echo esc_attr( $v[0] ); ?>"><?php echo esc_html( $v[1] ); ?></option><?php endforeach; ?>
                </select>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
              <button type="button" class="nbf-k__g nbf-k__add"><?php echo esc_html( $l['dodaj'] ); ?></button>
              <div class="nbf-k__err" role="alert"></div>
              <a class="nbf-k__o" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $l['poglej'] ); ?></a>
            <?php else : ?>
              <a class="nbf-k__g" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $l['gumb'] ); ?></a>
            <?php endif; ?>
            <?php if ( $prag > 0 ) : ?>
              <div class="nbf-k__d">🚚 <?php echo esc_html( $x['akc'] >= $prag ? $l['d_free'] : str_replace( '{D}', $D, $l['d_nad'] ) ); ?></div>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div></section>
  <?php endforeach; ?>

</div>
<style>.nbf{--a:#ff6d2e;--a2:#e85a1c;--ar:255,109,46;--hg:#3a1a08;--at:#fff;--tt:#0a0a0b;--bb:#fff0e8;--bt:#e2530f;background:#0a0a0b;color:#fff;font-family:inherit;line-height:1.4}
.nbf *{box-sizing:border-box}
.nbf-hero{position:relative;overflow:hidden;text-align:center;padding:76px 20px 68px;background:radial-gradient(70% 90% at 50% 0%,var(--hg) 0%,#0a0a0b 72%)}
.nbf-hero::before,.nbf-hero::after{content:"";position:absolute;width:420px;height:420px;border-radius:50%;background:rgba(var(--ar),.16);filter:blur(90px);pointer-events:none}
.nbf-hero::before{left:-140px;top:-120px}.nbf-hero::after{right:-140px;bottom:-200px}
.nbf-hero>*{position:relative;z-index:1}
.nbf-okvir{display:inline-block;border:2px solid var(--a);border-radius:12px;padding:20px 46px 18px;margin:0 0 30px;box-shadow:0 0 26px rgba(var(--ar),.55),inset 0 0 26px rgba(var(--ar),.22)}
.nbf-okvir b{display:block;font-size:clamp(42px,8vw,86px);font-weight:900;letter-spacing:5px;line-height:1;color:#fff}
.nbf-okvir i{display:block;font-style:normal;font-size:clamp(38px,7.3vw,78px);font-weight:900;letter-spacing:7px;line-height:1.08;color:#0d0a09;-webkit-text-stroke:4px var(--a);paint-order:stroke fill}
.nbf h1.nbf-h1{font-size:clamp(27px,5vw,56px);font-weight:900;line-height:1.12;margin:0 0 16px;color:#fff;letter-spacing:.5px;text-transform:none}
.nbf h1.nbf-h1 em{font-style:normal;color:var(--a);white-space:nowrap}
.nbf p.nbf-sub{max-width:640px;margin:0 auto 28px;font-size:17px;line-height:1.55;color:#d9d9dc}
.nbf a.nbf-gumb{display:inline-block;background:var(--a);color:var(--at);font-weight:800;font-size:15px;letter-spacing:.6px;padding:16px 36px;border-radius:999px;text-decoration:none;transition:transform .15s,box-shadow .15s;box-shadow:0 8px 26px rgba(var(--ar),.4)}
.nbf a.nbf-gumb:hover{transform:translateY(-2px);color:var(--at)}
.nbf-trak{background:var(--a);color:var(--tt);overflow:hidden;white-space:nowrap;padding:13px 0;font-weight:800;font-size:14px;letter-spacing:1.2px}
.nbf-trak__t{display:inline-flex;align-items:center;animation:nbf-tek 46s linear infinite;will-change:transform}
.nbf-trak__t span{padding:0 18px}.nbf-trak__t span.nbf-zv{padding:0;opacity:.75}
@keyframes nbf-tek{to{transform:translateX(-50%)}}
@media (prefers-reduced-motion:reduce){.nbf-trak__t{animation:none}}
.nbf-sek{padding:60px 20px 76px}
.nbf-v{max-width:1320px;margin:0 auto}
.nbf-k1{text-align:center;color:var(--a);font-size:13px;font-weight:800;letter-spacing:2px;margin:0 0 10px}
.nbf h2.nbf-h2{text-align:center;font-size:clamp(26px,3.8vw,44px);font-weight:900;line-height:1.15;margin:0 0 12px;color:#fff;text-transform:none}
.nbf h2.nbf-h2 em{font-style:normal;color:var(--a)}
.nbf p.nbf-p{text-align:center;color:#c9c9cd;font-size:16px;margin:0 auto 36px;max-width:620px}
.nbf-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:22px}
.nbf-k{background:#fff;color:#16181d;border-radius:18px;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 10px 30px rgba(0,0,0,.35);transition:transform .18s}
.nbf-k:hover{transform:translateY(-4px)}
.nbf a.nbf-k__s{position:relative;display:block;background:#f4f4f5;aspect-ratio:1/1;overflow:hidden}
.nbf a.nbf-k__s img{width:100%;height:100%;object-fit:cover;display:block;margin:0;border-radius:0}
.nbf-k__t{padding:16px 16px 18px;display:flex;flex-direction:column;flex:1}
.nbf h3.nbf-k__n{font-size:16px;font-weight:800;line-height:1.3;margin:0 0 14px;min-height:42px;text-transform:none;letter-spacing:0}
.nbf h3.nbf-k__n a{color:#16181d;font-weight:800;font-size:inherit;line-height:inherit;text-decoration:none;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.nbf-k__c{margin-top:auto;border-top:1px solid #ececee;padding-top:12px}
.nbf-r{display:flex;justify-content:space-between;align-items:baseline;gap:10px;font-size:14px;color:#55595f;margin:0 0 7px}
.nbf-r s{color:#8a8e95;text-decoration:line-through}
.nbf-r--p{color:#16181d}.nbf-r--p b{display:inline-block;background:var(--bb);color:var(--bt);border-radius:999px;padding:2px 9px;font-weight:800;font-size:13px;margin-left:4px}
.nbf-r--p em{font-style:normal;font-weight:700;color:var(--bt);white-space:nowrap}
.nbf-r--z{font-size:15px;font-weight:800;color:#16181d;margin:10px 0 0;align-items:center}
.nbf-r--z strong{font-size:22px;font-weight:900;white-space:nowrap}
.nbf-r s .woocommerce-Price-amount,.nbf-r em .woocommerce-Price-amount,.nbf-r strong .woocommerce-Price-amount{color:inherit;font:inherit}
.nbf a.nbf-k__g{display:block;text-align:center;background:var(--a);color:var(--at);font-weight:800;font-size:15px;letter-spacing:.6px;padding:14px 10px;border-radius:12px;text-decoration:none;margin-top:14px;transition:background .15s}
.nbf a.nbf-k__g:hover{background:var(--a2);color:var(--at)}
.nbf-k__d{text-align:center;font-size:13px;color:#55595f;margin-top:10px}
.nbf-k__pk{font-size:13px;color:#6b7078;margin:-8px 0 12px;font-weight:600}
.nbf-k__izb{display:flex;gap:8px;margin-top:12px}
.nbf select.nbf-sel{flex:1 1 0;min-width:0;width:100%;height:44px;border:1.5px solid #d3d5d9;border-radius:10px;background:#fff;color:#16181d;font-size:14px;font-weight:600;padding:0 28px 0 11px;margin:0;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1.5l5 5 5-5' fill='none' stroke='%2316181d' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;cursor:pointer;box-shadow:none;line-height:1.2}
.nbf select.nbf-sel:focus{outline:none;border-color:var(--a)}
.nbf select.nbf-sel.nbf-sel--err{border-color:#d92d20;background-color:#fff5f4}
.nbf button.nbf-k__g{display:block;width:100%;border:0;cursor:pointer;text-align:center;background:var(--a);color:var(--at);font-weight:800;font-size:15px;letter-spacing:.6px;padding:14px 10px;border-radius:12px;margin-top:10px;transition:background .15s;font-family:inherit;line-height:1.3;text-transform:none}
.nbf button.nbf-k__g:hover{background:var(--a2)}
.nbf button.nbf-k__g.je-zaseden{opacity:.75;cursor:progress}
.nbf button.nbf-k__g.je-dodano{background:#1f8a4c}
.nbf-k__err{min-height:0;font-size:12.5px;color:#d92d20;font-weight:600;text-align:center;margin-top:6px}
.nbf-k__err:empty{display:none}
.nbf a.nbf-k__o{display:block;text-align:center;font-size:13.5px;font-weight:700;color:#16181d;text-decoration:underline;text-underline-offset:3px;margin-top:10px}
.nbf a.nbf-k__o:hover{color:var(--bt)}
.nbf-sek + .nbf-trak + .nbf-sek{padding-top:60px}
@media (max-width:560px){.nbf-k__pk{font-size:11.5px;margin:-5px 0 8px}.nbf-k__izb{gap:6px;margin-top:9px;flex-direction:column}.nbf select.nbf-sel{flex:0 0 auto;height:42px;min-height:42px;font-size:13.5px;padding-left:10px}
.nbf button.nbf-k__g{font-size:12.5px;padding:12px 4px;letter-spacing:.2px}.nbf a.nbf-k__o{font-size:12px;margin-top:8px}.nbf-k__err{font-size:11.5px}}
@media (max-width:1180px){.nbf-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
@media (max-width:860px){.nbf-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.nbf-hero{padding:54px 16px 50px}.nbf-sek{padding:44px 14px 56px}}
@media (max-width:560px){.nbf-okvir{padding:14px 26px 12px;margin-bottom:22px}.nbf-okvir i{-webkit-text-stroke:3px var(--a)}.nbf p.nbf-sub{font-size:15.5px}
.nbf-k__t{padding:12px 11px 14px}.nbf h3.nbf-k__n{font-size:14px;min-height:37px;margin-bottom:10px}.nbf-r{font-size:12px;margin-bottom:5px;gap:6px}
.nbf-r--p b{font-size:11.5px;padding:1px 7px;margin-left:2px}.nbf-r--z{font-size:12.5px}.nbf-r--z strong{font-size:17px}
.nbf a.nbf-k__g{font-size:13px;padding:12px 6px;letter-spacing:.3px}.nbf-k__d{font-size:11.5px}.nbf-trak{font-size:12.5px;padding:11px 0}}
@media (max-width:359px){.nbf-grid{grid-template-columns:1fr}}
/* ---- slogi posameznih strani ---- */
.nbf--zlata{--a:#f5b301;--a2:#dba000;--ar:245,179,1;--hg:#3a2c05;--at:#16181d;--tt:#16181d;--bb:#fff6d9;--bt:#9a6b00}
.nbf--modra{--a:#2f7bff;--a2:#1f65e0;--ar:47,123,255;--hg:#0a1f47;--at:#fff;--tt:#fff;--bb:#e8f0ff;--bt:#1f5fd6}
.nbf--zelena{--a:#19c37d;--a2:#12a468;--ar:25,195,125;--hg:#06301f;--at:#06231a;--tt:#06231a;--bb:#e3f8ee;--bt:#0d7f52}
.nbf--rdeca{--a:#ff2d46;--a2:#e01a33;--ar:255,45,70;--hg:#3d0710;--at:#fff;--tt:#fff;--bb:#ffe9ec;--bt:#d0162e}
.nbf--zlata .nbf-okvir{transform:rotate(-2deg);border-width:3px}
.nbf--zlata .nbf-okvir i{color:var(--a);-webkit-text-stroke:0}
.nbf--zlata .nbf-k{border-radius:10px}.nbf--zlata a.nbf-k__g,.nbf--zlata button.nbf-k__g{border-radius:8px}.nbf--zlata select.nbf-sel{border-radius:8px}
.nbf--modra .nbf-okvir{border:0;border-bottom:5px solid var(--a);border-radius:0;box-shadow:none;padding:0 6px 16px}
.nbf--modra .nbf-okvir i{color:var(--a);-webkit-text-stroke:0}
.nbf--modra .nbf-k{border-top:5px solid var(--a)}
.nbf--zelena .nbf-okvir{border-radius:26px}
.nbf--zelena a.nbf-k__g,.nbf--zelena button.nbf-k__g,.nbf--zelena a.nbf-gumb{border-radius:999px}.nbf--zelena select.nbf-sel{border-radius:999px;padding-left:16px}
.nbf--rdeca .nbf-okvir{background:var(--a);border-color:var(--a);box-shadow:0 0 44px rgba(var(--ar),.55)}
.nbf--rdeca .nbf-okvir i{color:#fff;-webkit-text-stroke:0}
.nbf-k__rang{align-self:flex-start;background:var(--a);color:var(--at);font-weight:900;font-size:13px;line-height:1;border-radius:999px;padding:6px 11px;margin:0 0 10px;letter-spacing:.6px}
.nbf--top9 .nbf-grid{grid-template-columns:repeat(3,minmax(0,1fr));max-width:1060px;margin-left:auto;margin-right:auto}
@media (max-width:860px){.nbf--top9 .nbf-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width:560px){.nbf-k__rang{font-size:11.5px;padding:5px 9px;margin-bottom:8px}.nbf--modra .nbf-okvir{padding:0 4px 12px}}
@media (max-width:359px){.nbf--top9 .nbf-grid{grid-template-columns:1fr}}</style>
<script>
(function () {
  var koren = document.querySelector('.nbf'); if (!koren) { return; }
  var ajax = koren.getAttribute('data-ajax'), T = {};
  try { T = JSON.parse(koren.getAttribute('data-t')) || {}; } catch (e) {}

  koren.addEventListener('change', function (e) {
    if (e.target.classList && e.target.classList.contains('nbf-sel')) {
      e.target.classList.remove('nbf-sel--err');
      var n = e.target.closest('.nbf-k').querySelector('.nbf-k__err'); if (n) { n.textContent = ''; }
    }
  });

  koren.addEventListener('click', function (e) {
    var g = e.target.closest ? e.target.closest('.nbf-k__add') : null; if (!g) { return; }
    e.preventDefault();
    if (g.classList.contains('je-zaseden')) { return; }
    var k = g.closest('.nbf-k'), nap = k.querySelector('.nbf-k__err'), cfg;
    try { cfg = JSON.parse(k.getAttribute('data-bf')); } catch (x) { return; }
    nap.textContent = '';

    var izbrano = {}, manjka = null;
    k.querySelectorAll('select.nbf-sel').forEach(function (s) {
      izbrano[s.getAttribute('data-polje')] = s.value;
      if (!s.value && !manjka) { manjka = s; }
    });
    if (manjka) {
      manjka.classList.add('nbf-sel--err');
      nap.textContent = (T.izberi || '{A}').replace('{A}', manjka.getAttribute('data-ime'));
      try { manjka.focus(); } catch (x) {}
      return;
    }

    var fd = new FormData();
    fd.append('action', 'noriks_bf_add');
    fd.append('product_id', cfg.id);
    fd.append('nacin', cfg.nacin);
    if (cfg.nacin === 'orto') { fd.append('bundle_option', cfg.offer); }
    (cfg.attrs || []).forEach(function (a) {
      var v = a.v.length === 1 ? a.v[0] : izbrano[a.p];
      if (cfg.nacin === 'orto') {
        for (var i = 1; i <= cfg.kos; i++) { fd.append('pairs[' + cfg.offer + '][' + i + '][' + a.p + ']', v); }
      } else { fd.append(a.p, v); }
    });

    g.classList.add('je-zaseden'); g.textContent = T.dodajam || '…';
    function nazaj() { g.classList.remove('je-zaseden', 'je-dodano'); g.textContent = T.dodaj || ''; }

    fetch(ajax, { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (res && res.fragments) {
          g.classList.remove('je-zaseden'); g.classList.add('je-dodano'); g.textContent = T.dodano || '✓';
          if (window.jQuery) {
            var $ = window.jQuery;
            $.each(res.fragments, function (kljuc, vrednost) { $(kljuc).replaceWith(vrednost); });
            $(document.body).trigger('added_to_cart', [res.fragments, res.cart_hash, $(g)]);
            $(document.body).trigger('wc_fragment_refresh');
          }
          setTimeout(nazaj, 2200);
        } else {
          nazaj();
          nap.textContent = (res && res.data && res.data.message) ? res.data.message : (T.napaka || '');
        }
      })
      .catch(function () { nazaj(); nap.textContent = T.napaka || ''; });
  });
})();
</script>
<?php get_footer(); ?>
