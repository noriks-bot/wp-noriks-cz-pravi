<?php
/**
 * Template Name: Black Friday
 *
 * Pristajalna stran: hero pasica, tekoci trak, kartice izdelkov z akcijskimi cenami,
 * izbira velikosti in dodajanje v kosarico neposredno s kartice.
 * Ista predloga za vse strani (glej noriks_bf_strani()); slog in vsebino doloci
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
    case 'novi':
        $razdelki[] = array( 'k' => $l['sek_k'], 'a' => $l['sek_a'], 'b' => $l['sek_b'], 'izdelki' => noriks_bf_novi() );
        break;
    case 'oblacila':
        $sub = $l['sub_ob'];
        $razdelki[] = array( 'k' => $l['b_k'], 'a' => $l['b_a'], 'b' => $l['b_b'], 'izdelki' => noriks_bf_oblacila() );
        break;
    case 'enojne':
        $C   = trim( html_entity_decode( wp_strip_all_tags( wc_price( noriks_bf_kos_cena() ) ), ENT_QUOTES, 'UTF-8' ) );
        $sub = str_replace( '{C}', $C, $l['sub_kos'] );
        $razdelki[] = array( 'k' => 'BLACK FRIDAY · ' . $l['w_maj'], 'a' => $l['w_maj'], 'b' => str_replace( '{C}', $C, $l['kos_b'] ), 'izdelki' => noriks_bf_enojne() );
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
foreach ( $razdelki as $r ) { foreach ( $r['izdelki'] as $x ) { if ( $x['pop_max'] > $najvec ) { $najvec = $x['pop_max']; } } }

$P = sprintf( $l['pct'], $najvec );
$D = $prag > 0 ? trim( html_entity_decode( wp_strip_all_tags( wc_price( $prag, array( 'decimals' => ( floor( $prag ) == $prag ? 0 : wc_get_price_decimals() ) ) ) ), ENT_QUOTES, 'UTF-8' ) ) : '';

// Cyber Monday: isti izbor in besedila, drug napis dogodka.
$cm      = ( isset( $stran['dogodek'] ) && $stran['dogodek'] === 'cm' );
$dogodek = $cm ? 'CYBER MONDAY' : 'BLACK FRIDAY';
if ( $cm ) {
    $l['popust'] = str_replace( 'BF', 'CM', $l['popust'] );
    foreach ( $razdelki as $ri => $r ) { $razdelki[ $ri ]['k'] = str_replace( 'BLACK FRIDAY', 'CYBER MONDAY', $r['k'] ); }
}

$trak = array( $dogodek . ' 2026' );
if ( $najvec > 0 ) { $trak[] = str_replace( '{P}', $P, $l['trak_p'] ); }
if ( $D !== '' )   { $trak[] = str_replace( '{D}', $D, $l['trak_d'] ); }
$trak[] = $l['trak_30'];

$js = array( 'dodajam' => $l['dodajam'], 'dodano' => $l['dodano'], 'dodaj' => $l['dodaj'], 'izberi' => $l['izberi'], 'napaka' => $l['napaka'], 'zapri' => $l['zapri'], 'nalagam' => $l['nalagam'], 'poglej' => $l['poglej'], 'placilo' => $l['placilo'] );
$st = 0;
?>
<!-- nbf 2026-11-17-w -->
<div class="nbf nbf--<?php echo esc_attr( $stran['slog'] ); ?> nbf--<?php echo esc_attr( $tip ); ?> nbf--<?php echo $cm ? 'cm' : 'bf'; ?>" data-ajax="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-stran="<?php echo esc_attr( $stran['slug'] ); ?>" data-t="<?php echo esc_attr( wp_json_encode( $js ) ); ?>">

  <section class="nbf-hero">
    <div class="nbf-okvir"><b><?php echo $cm ? 'CYBER' : 'BLACK'; ?></b><i><?php echo $cm ? 'MONDAY' : 'FRIDAY'; ?></i></div>
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
          $ime   = $p->get_name();
          $slika = $p->get_image( 'woocommerce_single', array( 'loading' => $st < 4 ? 'eager' : 'lazy', 'alt' => $ime ) );

          $st++;
          // podatki za modal gredo v stran (brez cakanja na streznik ob kliku)
          if ( ! isset( $nbf_info ) || ! is_array( $nbf_info ) ) { $nbf_info = array(); }
          if ( ! isset( $nbf_info[ $p->get_id() ] ) ) { $nbf_info[ $p->get_id() ] = noriks_bf_info_podatki( $p ); }
          $hitro = in_array( $x['nacin'], array( 'orto', 'var', 'simple' ), true );
          $cfg   = array( 'id' => $p->get_id(), 'nacin' => $x['nacin'], 'attrs' => array(), 'pon' => array() );
          foreach ( $x['attrs'] as $a ) { $cfg['attrs'][] = array( 'p' => $a['p'], 'v' => array_map( function ( $v ) { return $v[0]; }, $a['v'] ) ); }
          // Vrstica z imenom paketa je prisotna pri vseh kolicinah (tudi 1x), da kartica ob preklopu ne skoci.
          $ima_pk = false;
          foreach ( $x['ponudbe'] as $po ) { if ( $po['paket'] !== '' ) { $ima_pk = true; } }
          foreach ( $x['ponudbe'] as $po ) {
              $cfg['pon'][] = array(
                  'id'  => $po['id'], 'kos' => $po['kos'], 'pk' => ( $po['paket'] !== '' ? $po['paket'] : ( $ima_pk ? "\xC2\xA0" : '' ) ),
                  'red' => wc_price( $po['red'] ), 'pri' => '−' . wc_price( $po['red'] - $po['akc'] ), 'akc' => wc_price( $po['akc'] ),
                  'pc'  => '−' . sprintf( $l['pct'], $po['pop'] ),
                  'd'   => $prag > 0 ? ( $po['akc'] >= $prag ? $l['d_free'] : str_replace( '{D}', $D, $l['d_nad'] ) ) : '',
              );
          }
          $vec = $hitro && count( $x['ponudbe'] ) > 1;
          ?>
        <article class="nbf-k" data-id="<?php echo (int) $p->get_id(); ?>"<?php if ( $hitro ) : ?> data-bf="<?php echo esc_attr( wp_json_encode( $cfg ) ); ?>"<?php endif; ?>>
          <a class="nbf-k__s" href="<?php echo esc_url( $url ); ?>"><?php echo $slika; // phpcs:ignore -- WooCommerce vrne varen <img> ?></a>
          <div class="nbf-k__t">
            <?php if ( $rang && empty( $x['dodatni'] ) ) : ?><div class="nbf-k__rang">#<?php echo (int) $st; ?></div><?php endif; ?>
            <h3 class="nbf-k__n"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $ime ); ?></a></h3>
            <div class="nbf-k__pk<?php echo ! empty( $x['vsebina'] ) ? ' nbf-k__pk--vs' : ''; ?>"><?php echo esc_html( $x['paket'] !== '' ? $x['paket'] : ( $ima_pk ? "\xC2\xA0" : '' ) ); ?></div>
            <div class="nbf-k__c">
              <div class="nbf-r"><span><?php echo esc_html( $l['redna'] ); ?></span><s class="nbf-c-red"><?php echo wp_kses_post( wc_price( $x['red'] ) ); ?></s></div>
              <div class="nbf-r nbf-r--p"><span><?php echo esc_html( $l['popust'] ); ?><b class="nbf-c-pc">−<?php echo esc_html( $pc ); ?></b></span><em class="nbf-c-pri">−<?php echo wp_kses_post( wc_price( $x['red'] - $x['akc'] ) ); ?></em></div>
              <div class="nbf-r nbf-r--z"><span><?php echo esc_html( $l['placilo'] ); ?></span><strong class="nbf-c-akc"><?php echo wp_kses_post( wc_price( $x['akc'] ) ); ?></strong></div>
            </div>
            <?php if ( $vec ) : ?>
              <div class="nbf-kol<?php echo count( $x['ponudbe'] ) > 5 ? ' nbf-kol--vec' : ''; ?>" role="group">
                <?php foreach ( $x['ponudbe'] as $pi => $po ) : ?><button type="button" class="nbf-kol__g<?php echo $pi === 0 ? ' je-izbran' : ''; ?>" data-i="<?php echo (int) $pi; ?>"><?php echo (int) $po['kos']; ?>×</button><?php endforeach; ?>
              </div>
            <?php endif; ?>
            <?php if ( $hitro ) : ?>
              <?php $izbirni = array_filter( $x['attrs'], function ( $a ) { return count( $a['v'] ) > 1; } ); $modalno = count( $izbirni ) > 2; // vec kot 2 izbiri: izbira v modalu ?>
              <?php if ( $izbirni ) : ?>
              <div class="nbf-k__izb<?php echo $modalno ? ' nbf-k__izb--modal' : ''; ?>">
                <?php foreach ( $izbirni as $a ) : ?>
                <div class="nbf-dd">
                  <select class="nbf-sel" data-polje="<?php echo esc_attr( $a['p'] ); ?>" data-ime="<?php echo esc_attr( $a['ime'] ); ?>" aria-label="<?php echo esc_attr( $a['ime'] ); ?>" tabindex="-1">
                    <option value=""><?php echo esc_html( $a['ime'] ); ?></option>
                    <?php foreach ( $a['v'] as $v ) : ?><option value="<?php echo esc_attr( $v[0] ); ?>"><?php echo esc_html( $v[1] ); ?></option><?php endforeach; ?>
                  </select>
                  <button type="button" class="nbf-dd__g" aria-haspopup="listbox" aria-expanded="false"><i class="nbf-dd__b" hidden></i><span class="nbf-dd__n"><?php echo esc_html( $a['ime'] ); ?></span></button>
                  <div class="nbf-dd__l" role="listbox" aria-label="<?php echo esc_attr( $a['ime'] ); ?>">
                    <?php foreach ( $a['v'] as $vi => $v ) : $barva = ! empty( $a['b'][ $vi ] ) ? $a['b'][ $vi ] : ''; ?><div class="nbf-dd__o" role="option" data-v="<?php echo esc_attr( $v[0] ); ?>"<?php if ( $barva ) : ?> data-b="<?php echo esc_attr( $barva ); ?>"<?php endif; ?>><?php if ( $barva ) : ?><i style="background:<?php echo esc_attr( $barva ); ?>"></i><?php endif; ?><span><?php echo esc_html( $v[1] ); ?></span></div><?php endforeach; ?>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
              <?php if ( $modalno ) : ?><button type="button" class="nbf-k__g nbf-k__odpri"><?php echo esc_html( $l['dodaj'] ); ?></button><?php endif; ?>
              <button type="button" class="nbf-k__g nbf-k__add"<?php echo $modalno ? ' hidden' : ''; ?>><?php echo esc_html( $l['dodaj'] ); ?></button>
              <div class="nbf-k__err" role="alert"></div>
            <?php else : ?>
              <a class="nbf-k__g" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $l['gumb'] ); ?></a>
            <?php endif; ?>
            <a class="nbf-k__o nbf-k__vec" href="<?php echo esc_url( $url ); ?>" data-id="<?php echo (int) $p->get_id(); ?>"><?php echo esc_html( $l['vec'] ); ?></a>
            <?php if ( $prag > 0 ) : ?>
              <div class="nbf-k__d">🚚 <span class="nbf-c-d"><?php echo esc_html( $x['akc'] >= $prag ? $l['d_free'] : str_replace( '{D}', $D, $l['d_nad'] ) ); ?></span></div>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div></section>
  <?php endforeach; ?>

</div>
<script type="application/json" id="nbf-info" data-no-optimize="1" data-no-defer="1" data-cfasync="false"><?php echo wp_json_encode( isset( $nbf_info ) && is_array( $nbf_info ) && $nbf_info ? $nbf_info : new stdClass(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); // phpcs:ignore ?></script>
<style>.nbf{--a:#ff6d2e;--a2:#e85a1c;--ar:255,109,46;--hg:#3a1a08;--at:#fff;--tt:#0a0a0b;--bb:#fff0e8;--bt:#e2530f;background:#0a0a0b;color:#fff;font-family:inherit;line-height:1.4}
.nbf *{box-sizing:border-box}
.nbf-hero{position:relative;overflow:hidden;text-align:center;padding:76px 20px 68px;background:radial-gradient(70% 90% at 50% 0%,var(--hg) 0%,#0a0a0b 72%)}
.nbf-hero::before,.nbf-hero::after{content:"";position:absolute;width:420px;height:420px;border-radius:50%;background:rgba(var(--ar),.16);filter:blur(90px);pointer-events:none}
.nbf-hero::before{left:-140px;top:-120px}.nbf-hero::after{right:-140px;bottom:-200px}
.nbf-hero>*{position:relative;z-index:1}
.nbf-okvir{display:inline-block;border:2px solid var(--a);border-radius:6px;padding:20px 46px 18px;margin:0 0 30px;box-shadow:0 0 26px rgba(var(--ar),.55),inset 0 0 26px rgba(var(--ar),.22)}
.nbf-okvir b{display:block;font-size:clamp(42px,8vw,86px);font-weight:900;letter-spacing:5px;line-height:1;color:#fff}
.nbf-okvir i{display:block;font-style:normal;font-size:clamp(38px,7.3vw,78px);font-weight:900;letter-spacing:7px;line-height:1.08;color:#0d0a09;-webkit-text-stroke:4px var(--a);paint-order:stroke fill}
.nbf h1.nbf-h1{font-size:clamp(27px,5vw,56px);font-weight:900;line-height:1.12;margin:0 0 16px;color:#fff;letter-spacing:.5px;text-transform:none}
.nbf h1.nbf-h1 em{font-style:normal;color:var(--a);white-space:nowrap}
.nbf p.nbf-sub{max-width:640px;margin:0 auto 28px;font-size:17px;line-height:1.55;color:#d9d9dc}
.nbf a.nbf-gumb{display:inline-block;background:var(--a);color:var(--at);font-weight:800;font-size:15px;letter-spacing:.6px;padding:16px 36px;border-radius:13px;text-decoration:none;transition:transform .15s,box-shadow .15s;box-shadow:0 8px 26px rgba(var(--ar),.4)}
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
.nbf-k{background:#fff;color:#16181d;border-radius:9px;position:relative;display:flex;flex-direction:column;box-shadow:0 10px 30px rgba(0,0,0,.35);transition:transform .18s}
.nbf-k:hover{transform:translateY(-4px)}
.nbf a.nbf-k__s{position:relative;display:block;background:#f4f4f5;aspect-ratio:1/1;overflow:hidden;border-radius:inherit;border-bottom-left-radius:0;border-bottom-right-radius:0}
.nbf a.nbf-k__s img{width:100%;height:100%;object-fit:cover;display:block;margin:0;border-radius:0}
.nbf-k__t{padding:16px 16px 18px;display:flex;flex-direction:column;flex:1}
.nbf h3.nbf-k__n{font-size:16px;font-weight:800;line-height:1.3;margin:0 0 14px;min-height:42px;text-transform:none;letter-spacing:0}
.nbf h3.nbf-k__n a{color:#16181d;font-weight:800;font-size:inherit;line-height:inherit;text-decoration:none;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.nbf-k__c{margin-top:auto;border-top:1px solid #ececee;padding-top:12px}
.nbf-r{display:flex;justify-content:space-between;align-items:baseline;gap:10px;font-size:14px;color:#55595f;margin:0 0 7px}
.nbf-r s{color:#8a8e95;text-decoration:line-through}
.nbf-r--p{color:#16181d}.nbf-r--p b{display:inline-block;background:var(--bb);color:var(--bt);border-radius:6px;padding:2px 9px;font-weight:800;font-size:13px;margin-left:4px}
.nbf-r--p em{font-style:normal;font-weight:700;color:var(--bt);white-space:nowrap}
.nbf-r--z{font-size:15px;font-weight:800;color:#16181d;margin:10px 0 0;align-items:center}
.nbf-r--z strong{font-size:22px;font-weight:900;white-space:nowrap}
.nbf-r s .woocommerce-Price-amount,.nbf-r em .woocommerce-Price-amount,.nbf-r strong .woocommerce-Price-amount{color:inherit;font:inherit}
.nbf a.nbf-k__g{display:block;text-align:center;background:var(--a);color:var(--at);font-weight:800;font-size:15px;letter-spacing:.6px;padding:14px 10px;border-radius:6px;text-decoration:none;margin-top:14px;transition:background .15s}
.nbf a.nbf-k__g:hover{background:var(--a2);color:var(--at)}
.nbf-k__d{text-align:center;font-size:13px;color:#55595f;margin-top:10px}
.nbf-k__pk{font-size:13px;line-height:1.35;color:#6b7078;margin:-8px 0 12px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.nbf-brez-drsenja{overflow:hidden}
.nbf-m{position:fixed;inset:0;z-index:999990;background:rgba(8,8,10,.8);display:none;align-items:center;justify-content:center;padding:24px}
.nbf-m.je-odprt{display:flex}
.nbf-m__p{position:relative;background:#fff;color:#16181d;border-radius:10px;width:100%;max-width:980px;max-height:calc(100vh - 48px);display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);overflow:hidden;box-shadow:0 30px 80px rgba(0,0,0,.5)}
.nbf .nbf-m button.nbf-m__x{position:absolute;right:12px;top:12px;z-index:3;width:38px;height:38px;border-radius:50%;border:0;background:#16181d;color:#fff;font-size:24px;line-height:1;cursor:pointer;padding:0;margin:0;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(0,0,0,.25)}
.nbf-m__g{background:#f4f4f5;display:flex;flex-direction:column;min-height:0}
.nbf-m__sl{flex:1 1 auto;min-height:0;aspect-ratio:1/1;display:flex;align-items:center;justify-content:center}
.nbf-m__sl img{width:100%;height:100%;object-fit:contain;display:block;margin:0}
.nbf-m__sm{display:flex;gap:8px;padding:10px;overflow-x:auto;scrollbar-width:thin}
.nbf-m__sm:empty{display:none}
.nbf .nbf-m__sm button{flex:0 0 62px;width:62px;height:62px;padding:0;margin:0;border:2px solid transparent;border-radius:5px;overflow:hidden;background:#fff;cursor:pointer;box-shadow:none}
.nbf .nbf-m__sm button.je-izbran{border-color:var(--a)}
.nbf-m__sm img{width:100%;height:100%;object-fit:cover;display:block;margin:0}
.nbf-m__b{display:flex;flex-direction:column;min-height:0;padding:26px 26px 0}
.nbf .nbf-m h3.nbf-m__n{font-size:22px;font-weight:800;line-height:1.25;margin:0 44px 14px 0;color:#16181d;text-transform:none;letter-spacing:0}
.nbf-m__c{border:1px solid #ececee;border-radius:7px;padding:12px 14px 12px;margin-bottom:14px}
.nbf-m__c .nbf-r:last-child{margin-bottom:0}
.nbf-m__o{flex:1 1 auto;min-height:60px;overflow-y:auto;font-size:15px;line-height:1.6;color:#33363b;padding-right:6px}
.nbf-m__o p{margin:0 0 12px;font-size:15px;line-height:1.6;color:#33363b}
.nbf-m__o ul{list-style:none;margin:0 0 12px;padding:0}.nbf-m__o li{margin:0 0 7px;padding:0;list-style:none}
.nbf-m__o strong{color:#16181d}
.nbf-m__nal{color:#8a8e95}
.nbf-m__f{padding:14px 0 20px;background:#fff;border-top:1px solid #ececee;margin-top:8px}
.nbf-m__ctrl .nbf-kol{margin-top:0}.nbf .nbf-m__ctrl .nbf-k__izb{margin-top:8px;flex-direction:row;gap:8px}.nbf .nbf-m .nbf-m__ctrl .nbf-dd{flex:1 1 0;min-width:0;width:auto}.nbf-m__ctrl:not(:empty){margin-bottom:10px}
.nbf-m__f .nbf-k__g{margin-top:0}
@media (max-width:760px){.nbf-m{padding:0;align-items:flex-end}.nbf-m__p{grid-template-columns:1fr;max-width:none;max-height:94vh;border-radius:10px 10px 0 0;overflow-y:auto;display:block}
.nbf-m__sl{aspect-ratio:1/1;max-height:46vh}.nbf-m__b{padding:18px 16px 0}.nbf .nbf-m h3.nbf-m__n{font-size:18px;margin-bottom:12px}.nbf-m__o{overflow:visible;font-size:14.5px}
.nbf-m__f{position:sticky;bottom:0;padding:12px 0 14px}.nbf-m__f a.nbf-k__o{margin-top:8px}.nbf .nbf-m__sm button{flex-basis:54px;width:54px;height:54px}}
.nbf-kol{display:flex;gap:6px;margin-top:12px}
.nbf .nbf-kol button.nbf-kol__g{flex:1 1 0;min-width:0;height:38px;border:1.5px solid #d3d5d9;background:#fff;color:#16181d;border-radius:5px;font-weight:800;font-size:14px;line-height:1;cursor:pointer;padding:0;margin:0;font-family:inherit;box-shadow:none;transition:background .12s,border-color .12s,color .12s;text-transform:none;letter-spacing:0}
.nbf .nbf-kol button.nbf-kol__g:hover{border-color:#16181d}
.nbf-kol.nbf-kol--vec{flex-wrap:wrap}.nbf .nbf-kol.nbf-kol--vec button.nbf-kol__g{flex:0 0 calc((100% - 24px)/5);max-width:calc((100% - 24px)/5)}
.nbf .nbf-kol button.nbf-kol__g.je-izbran{background:#16181d;border-color:#16181d;color:#fff}
.nbf-k.je-dd{z-index:30}
.nbf-dd{position:relative;flex:1 1 0;min-width:0}
.nbf .nbf-dd select.nbf-sel{position:absolute;left:0;bottom:0;width:1px;height:1px;min-height:0;padding:0;margin:0;border:0;opacity:0;pointer-events:none;flex:none}
.nbf .nbf-dd button.nbf-dd__g{position:relative;display:flex;align-items:center;gap:8px;width:100%;height:44px;min-height:0;padding:0 32px 0 11px;margin:0;border:1.5px solid #d3d5d9;border-radius:5px;background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='9' viewBox='0 0 14 9'%3E%3Cpath d='M1.5 1.5l5.5 5.5 5.5-5.5' fill='none' stroke='%2316181d' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") no-repeat right 12px center;background-size:13px 8px;color:#6b7078;font-family:inherit;font-size:14px;font-weight:600;line-height:1.2;text-align:left;text-transform:none;letter-spacing:0;cursor:pointer;box-shadow:none;transition:border-color .12s}
.nbf .nbf-dd.je-izbrano button.nbf-dd__g{color:#16181d;font-weight:700}
.nbf .nbf-dd button.nbf-dd__g:hover,.nbf .nbf-dd button.nbf-dd__g:focus,.nbf .nbf-dd.je-odprt button.nbf-dd__g{outline:none;border-color:#16181d;background-color:#fff;color:#16181d}
.nbf .nbf-dd select.nbf-sel--err ~ button.nbf-dd__g{border-color:#d92d20;background-color:#fff5f4}
.nbf-dd__b{flex:0 0 20px;width:20px;height:20px;border-radius:4px;border:1px solid rgba(0,0,0,.2);display:block;box-sizing:border-box}
.nbf-dd__b[hidden]{display:none}
.nbf-dd__n{flex:1 1 auto;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.nbf-dd__l{display:none;position:absolute;left:0;top:calc(100% + 4px);z-index:60;box-sizing:border-box;min-width:100%;width:max-content;max-width:290px;max-height:268px;overflow-y:auto;background:#fff;border:1px solid #e0e0e0;border-radius:8px;box-shadow:0 10px 26px rgba(0,0,0,.16);padding:4px;text-align:left}
.nbf-dd.je-odprt .nbf-dd__l{display:block}
.nbf-dd__o{display:flex;align-items:center;gap:10px;padding:9px 12px 9px 10px;border-radius:4px;cursor:pointer;font-size:15px;font-weight:500;color:#33363b;line-height:1.25}
.nbf-dd__o i{flex:0 0 20px;width:20px;height:20px;border-radius:4px;border:1px solid rgba(0,0,0,.15);box-sizing:border-box}
.nbf-dd__o:hover{background:#f6f6f6}
.nbf-dd__o.je-izbran{background:var(--bb);color:#16181d;font-weight:700}
.nbf-m .nbf-dd__l{top:auto;bottom:calc(100% + 4px);max-height:240px}
@media (max-width:560px){.nbf .nbf-dd{flex:0 0 auto}.nbf .nbf-dd button.nbf-dd__g{height:42px;font-size:13.5px;padding-left:10px}.nbf-k .nbf-dd__l{right:0;width:auto;max-width:none}.nbf-dd__o{font-size:14px;padding:9px 10px 9px 8px;gap:8px}.nbf .nbf-m .nbf-m__ctrl .nbf-dd{flex:1 1 0}}
.nbf--cian .nbf-dd button.nbf-dd__g{border-radius:3px}.nbf--zlata .nbf-dd button.nbf-dd__g{border-radius:4px}.nbf--zelena .nbf-dd button.nbf-dd__g{border-radius:11px;padding-left:16px}
.nbf-k__pk:empty{display:none}
.nbf-k__pk--vs{white-space:normal;overflow:visible;text-overflow:clip}
.nbf-k__c,.nbf-k__d,.nbf-k__pk{box-sizing:border-box}
.nbf-k .nbf-k__izb--modal{display:none}
.nbf button.nbf-k__add[hidden]{display:none}
.nbf .nbf-m__ctrl .nbf-k__izb--vec{flex-wrap:wrap}
.nbf .nbf-m .nbf-m__ctrl .nbf-k__izb--vec .nbf-dd{flex:1 1 calc(50% - 4px)}
.nbf a.nbf-k__s,.nbf h3.nbf-k__n a{cursor:pointer}
.nbf-k__izb{display:flex;gap:8px;margin-top:8px}
.nbf select.nbf-sel{flex:1 1 0;min-width:0;width:100%;height:44px;border:1.5px solid #d3d5d9;border-radius:5px;background:#fff;color:#16181d;font-size:14px;font-weight:600;padding:0 28px 0 11px;margin:0;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1.5l5 5 5-5' fill='none' stroke='%2316181d' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;cursor:pointer;box-shadow:none;line-height:1.2}
.nbf select.nbf-sel:focus{outline:none;border-color:var(--a)}
.nbf select.nbf-sel.nbf-sel--err{border-color:#d92d20;background-color:#fff5f4}
.nbf button.nbf-k__g{display:block;width:100%;border:0;cursor:pointer;text-align:center;background:var(--a);color:var(--at);font-weight:800;font-size:15px;letter-spacing:.6px;padding:14px 10px;border-radius:6px;margin-top:10px;transition:background .15s;font-family:inherit;line-height:1.3;text-transform:none}
.nbf button.nbf-k__g:hover{background:var(--a2)}
.nbf button.nbf-k__g.je-zaseden{opacity:.75;cursor:progress}
.nbf button.nbf-k__g.je-dodano{background:#1f8a4c}
.nbf-k__err{min-height:0;font-size:12.5px;color:#d92d20;font-weight:600;text-align:center;margin-top:6px}
.nbf-k__err:empty{display:none}
.nbf a.nbf-k__o{display:block;text-align:center;font-size:13.5px;font-weight:700;color:#16181d;text-decoration:underline;text-underline-offset:3px;margin-top:10px}
.nbf a.nbf-k__o:hover{color:var(--bt)}
.nbf-sek + .nbf-trak + .nbf-sek{padding-top:60px}
@media (max-width:560px){.nbf-k__pk{font-size:11.5px;margin:-5px 0 8px}.nbf-kol{gap:5px;margin-top:9px}.nbf .nbf-kol button.nbf-kol__g{height:36px;font-size:13px}.nbf-k__izb{gap:6px;margin-top:6px;flex-direction:column}.nbf select.nbf-sel{flex:0 0 auto;height:42px;min-height:42px;font-size:13.5px;padding-left:10px}
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
.nbf--cian{--a:#00d9ff;--a2:#00b9da;--ar:0,217,255;--hg:#04283c;--at:#04121a;--tt:#04121a;--bb:#e0f9ff;--bt:#007e99}
.nbf--limeta{--a:#c6f432;--a2:#a9d61c;--ar:198,244,50;--hg:#27310a;--at:#141b02;--tt:#141b02;--bb:#f3fcd4;--bt:#5c7700}
.nbf--vijola{--a:#a855f7;--a2:#9333ea;--ar:168,85,247;--hg:#260b4a;--at:#fff;--tt:#fff;--bb:#f1e6ff;--bt:#7e22ce}
.nbf--cm .nbf-hero{background-image:linear-gradient(rgba(var(--ar),.075) 1px,transparent 1px),linear-gradient(90deg,rgba(var(--ar),.075) 1px,transparent 1px),radial-gradient(70% 90% at 50% 0%,var(--hg) 0%,#0a0a0b 72%);background-size:46px 46px,46px 46px,auto}
.nbf--cian .nbf-okvir{border-radius:2px;box-shadow:0 0 34px rgba(var(--ar),.6),inset 0 0 30px rgba(var(--ar),.22)}
.nbf--cian .nbf-k{border-radius:4px}.nbf--cian a.nbf-k__g,.nbf--cian button.nbf-k__g,.nbf--cian a.nbf-gumb{border-radius:3px}.nbf--cian select.nbf-sel,.nbf--cian .nbf-kol button.nbf-kol__g{border-radius:3px}
.nbf--vijola .nbf-okvir{transform:skewX(-7deg);border-radius:3px;border-width:3px}
.nbf--vijola .nbf-okvir i{color:var(--a);-webkit-text-stroke:0}
.nbf--vijola .nbf-k{border-bottom:5px solid var(--a)}
.nbf--zlata .nbf-okvir{transform:rotate(-2deg);border-width:3px}
.nbf--zlata .nbf-okvir i{color:var(--a);-webkit-text-stroke:0}
.nbf--zlata .nbf-kol button.nbf-kol__g{border-radius:4px}.nbf--zelena .nbf-kol button.nbf-kol__g{border-radius:10px}
.nbf--zlata .nbf-k{border-radius:5px}.nbf--zlata a.nbf-k__g,.nbf--zlata button.nbf-k__g{border-radius:4px}.nbf--zlata select.nbf-sel{border-radius:4px}
.nbf--modra .nbf-okvir{border:0;border-bottom:5px solid var(--a);border-radius:0;box-shadow:none;padding:0 6px 16px}
.nbf--modra .nbf-okvir i{color:var(--a);-webkit-text-stroke:0}
.nbf--modra .nbf-k{border-top:5px solid var(--a)}
.nbf--zelena .nbf-okvir{border-radius:13px}
.nbf--zelena a.nbf-k__g,.nbf--zelena button.nbf-k__g,.nbf--zelena a.nbf-gumb{border-radius:12px}.nbf--zelena select.nbf-sel{border-radius:11px;padding-left:16px}
.nbf--rdeca .nbf-okvir{background:var(--a);border-color:var(--a);box-shadow:0 0 44px rgba(var(--ar),.55)}
.nbf--rdeca .nbf-okvir i{color:#fff;-webkit-text-stroke:0}
.nbf-k__rang{align-self:flex-start;background:var(--a);color:var(--at);font-weight:900;font-size:13px;line-height:1;border-radius:6px;padding:6px 11px;margin:0 0 10px;letter-spacing:.6px}
.nbf--top9 .nbf-grid{grid-template-columns:repeat(3,minmax(0,1fr));max-width:1060px;margin-left:auto;margin-right:auto}
.nbf--enojne .nbf-grid{display:flex;flex-wrap:wrap;justify-content:center}.nbf--enojne .nbf-grid>.nbf-k{flex:0 0 calc((100% - 66px)/4);max-width:calc((100% - 66px)/4)}
.nbf--limeta .nbf-okvir i{color:var(--a);-webkit-text-stroke:0}.nbf--limeta .nbf-k{border-radius:7px}
@media (max-width:1180px){.nbf--enojne .nbf-grid>.nbf-k{flex-basis:calc((100% - 44px)/3);max-width:calc((100% - 44px)/3)}}
@media (max-width:860px){.nbf--enojne .nbf-grid>.nbf-k{flex-basis:calc((100% - 14px)/2);max-width:calc((100% - 14px)/2)}}
@media (max-width:359px){.nbf--enojne .nbf-grid>.nbf-k{flex-basis:100%;max-width:100%}}
@media (max-width:560px){.nbf .nbf-kol.nbf-kol--vec button.nbf-kol__g{flex-basis:calc((100% - 20px)/5);max-width:calc((100% - 20px)/5);font-size:12.5px;letter-spacing:-.2px}}
@media (max-width:860px){.nbf--top9 .nbf-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width:560px){.nbf-k__rang{font-size:11.5px;padding:5px 9px;margin-bottom:8px}.nbf--modra .nbf-okvir{padding:0 4px 12px}}
@media (max-width:359px){.nbf--top9 .nbf-grid{grid-template-columns:1fr}}</style>
<script data-no-optimize="1" data-no-defer="1" data-cfasync="false">
(function () {
  var koren = document.querySelector('.nbf'); if (!koren) { return; }
  var ajax = koren.getAttribute('data-ajax'), T = {};
  try { T = JSON.parse(koren.getAttribute('data-t')) || {}; } catch (e) {}
  // slike in opisi za modal so ze v strani
  var INFO = {};
  try { var ie = document.getElementById('nbf-info'); if (ie) { INFO = JSON.parse(ie.textContent) || {}; } } catch (e) { INFO = {}; }

  // ---- izbirniki (kot na straneh majic in bokseric): gumb z barvnim vzorcem + seznam; pravi <select> ostane vir vrednosti ----
  function osvezi(sel) {
    var dd = sel.closest ? sel.closest('.nbf-dd') : null; if (!dd) { return; }
    var n = dd.querySelector('.nbf-dd__n'), b = dd.querySelector('.nbf-dd__b'), izbran = null;
    dd.querySelectorAll('.nbf-dd__o').forEach(function (o) { var je = o.getAttribute('data-v') === sel.value && sel.value !== ''; o.classList.toggle('je-izbran', je); o.setAttribute('aria-selected', je ? 'true' : 'false'); if (je) { izbran = o; } });
    n.textContent = izbran ? izbran.textContent.trim() : (sel.getAttribute('data-ime') || '');
    dd.classList.toggle('je-izbrano', !!izbran);
    var barva = izbran ? izbran.getAttribute('data-b') : '';
    if (barva) { b.style.background = barva; b.hidden = false; } else { b.hidden = true; }
  }
  function zapriDD(razen) {
    koren.querySelectorAll('.nbf-dd.je-odprt').forEach(function (d) {
      if (d === razen) { return; }
      d.classList.remove('je-odprt'); d.querySelector('.nbf-dd__g').setAttribute('aria-expanded', 'false');
      var kk = d.closest('.nbf-k'); if (kk) { kk.classList.remove('je-dd'); }
    });
  }
  document.addEventListener('click', function (e) { if (!(e.target.closest && e.target.closest('.nbf-dd'))) { zapriDD(null); } });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { zapriDD(null); } });
  koren.addEventListener('click', function (e) {
    var g = e.target.closest ? e.target.closest('.nbf-dd__g') : null, o = e.target.closest ? e.target.closest('.nbf-dd__o') : null;
    if (!g && !o) { return; }
    e.preventDefault();
    var dd = (g || o).closest('.nbf-dd'), sel = dd.querySelector('select.nbf-sel');
    if (g) {
      var odpri = !dd.classList.contains('je-odprt');
      zapriDD(odpri ? dd : null);
      dd.classList.toggle('je-odprt', odpri); g.setAttribute('aria-expanded', odpri ? 'true' : 'false');
      var kk = dd.closest('.nbf-k'); if (kk) { kk.classList.toggle('je-dd', odpri); }
      return;
    }
    sel.value = o.getAttribute('data-v');
    sel.dispatchEvent(new Event('change', { bubbles: true }));
    zapriDD(null);
  });

  koren.addEventListener('change', function (e) {
    if (!(e.target.classList && e.target.classList.contains('nbf-sel'))) { return; }
    e.target.classList.remove('nbf-sel--err'); osvezi(e.target);
    var vModalu = e.target.closest('.nbf-m'), k = vModalu ? odprtaKartica : e.target.closest('.nbf-k');
    if (!k) { return; }
    if (vModalu) {
      var polje = e.target.getAttribute('data-polje'), ciljni = null;
      k.querySelectorAll('select.nbf-sel').forEach(function (s) { if (s.getAttribute('data-polje') === polje) { ciljni = s; } });
      if (ciljni) { ciljni.value = e.target.value; ciljni.classList.remove('nbf-sel--err'); osvezi(ciljni); }
      var nm = vModalu.querySelector('.nbf-m__err'); if (nm) { nm.textContent = ''; }
    }
    var n = k.querySelector('.nbf-k__err'); if (n) { n.textContent = ''; }
  });

  // ---- modal "Poglej vec" ----
  var modal = null, predpomnilnik = INFO, odprtaKartica = null;
  function zapriModal() { if (modal) { modal.classList.remove('je-odprt'); document.documentElement.classList.remove('nbf-brez-drsenja'); } odprtaKartica = null; }
  function narediModal() {
    modal = document.createElement('div'); modal.className = 'nbf-m'; modal.setAttribute('role', 'dialog'); modal.setAttribute('aria-modal', 'true');
    modal.innerHTML = '<div class="nbf-m__p"><button type="button" class="nbf-m__x" aria-label="' + (T.zapri || '') + '">&times;</button>' +
      '<div class="nbf-m__g"><div class="nbf-m__sl"></div><div class="nbf-m__sm"></div></div>' +
      '<div class="nbf-m__b"><h3 class="nbf-m__n"></h3><div class="nbf-m__c"></div><div class="nbf-m__o"></div>' +
      '<div class="nbf-m__f"><div class="nbf-m__ctrl"></div><button type="button" class="nbf-k__g nbf-m__dodaj"></button><div class="nbf-k__err nbf-m__err" role="alert"></div><a class="nbf-k__o nbf-m__stran" href="#"></a></div></div></div>';
    koren.appendChild(modal);
    modal.addEventListener('click', function (e) {
      if (e.target === modal || (e.target.closest && e.target.closest('.nbf-m__x'))) { zapriModal(); return; }
      var m = e.target.closest ? e.target.closest('.nbf-m__sm button') : null;
      if (m) { modal.querySelector('.nbf-m__sl').innerHTML = '<img src="' + m.getAttribute('data-v') + '" alt="">'; modal.querySelectorAll('.nbf-m__sm button').forEach(function (x) { x.classList.toggle('je-izbran', x === m); }); return; }
      var gm = e.target.closest ? e.target.closest('.nbf-m__dodaj') : null;
      if (gm) {
        var k = odprtaKartica, g = k ? k.querySelector('.nbf-k__add') : null, nm = modal.querySelector('.nbf-m__err');
        if (!g || gm.classList.contains('je-zaseden')) { return; }
        nm.textContent = '';
        var manjka = null;
        modal.querySelectorAll('.nbf-m__ctrl select.nbf-sel').forEach(function (s) { if (!s.value && !manjka) { manjka = s; } });
        if (manjka) { manjka.classList.add('nbf-sel--err'); nm.textContent = (T.izberi || '{A}').replace('{A}', manjka.getAttribute('data-ime')); try { manjka.parentNode.querySelector('.nbf-dd__g').focus(); } catch (x) {} return; }
        gm.classList.add('je-zaseden'); gm.textContent = T.dodajam || '…';
        g.click();
        var n = 0, cakaj = setInterval(function () {
          n++;
          var napaka = k.querySelector('.nbf-k__err').textContent;
          if (g.classList.contains('je-dodano')) { clearInterval(cakaj); gm.classList.remove('je-zaseden'); gm.textContent = T.dodaj || ''; zapriModal(); }
          else if (napaka || (!g.classList.contains('je-zaseden') && n > 3) || n > 150) { clearInterval(cakaj); gm.classList.remove('je-zaseden'); gm.textContent = T.dodaj || ''; nm.textContent = napaka || T.napaka || ''; }
        }, 100);
      }
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { zapriModal(); } });
  }
  function napolniModal(d) {
    var sl = modal.querySelector('.nbf-m__sl'), sm = modal.querySelector('.nbf-m__sm');
    sl.innerHTML = d.slike && d.slike.length ? '<img src="' + d.slike[0][0] + '" alt="">' : '';
    sm.innerHTML = '';
    if (d.slike && d.slike.length > 1) { d.slike.forEach(function (s, i) { var b = document.createElement('button'); b.type = 'button'; b.setAttribute('data-v', s[0]); if (i === 0) { b.className = 'je-izbran'; } b.innerHTML = '<img src="' + s[1] + '" alt="" loading="lazy">'; sm.appendChild(b); }); }
    modal.querySelector('.nbf-m__o').innerHTML = (d.kratek || '') + (d.opis || '');
  }
  // Klik na sliko, naslov, "Poglej vec" ali gumb pri izdelkih z vec izbirami odpre modal — nakup ostane na tej strani.
  koren.addEventListener('click', function (e) {
    var v = e.target.closest ? e.target.closest('.nbf-k__vec, a.nbf-k__s, .nbf-k__n a, .nbf-k__odpri') : null; if (!v) { return; }
    var k = v.closest('.nbf-k'); if (!k) { return; }
    if (e.metaKey || e.ctrlKey || e.shiftKey) { return; }
    e.preventDefault();
    if (!modal) { narediModal(); }
    var id = k.getAttribute('data-id'), povezava = k.querySelector('.nbf-k__vec'), href = povezava ? povezava.href : '#'; odprtaKartica = k;
    modal.querySelector('.nbf-m__n').textContent = k.querySelector('.nbf-k__n').textContent.trim();
    var c = k.querySelector('.nbf-k__c'); modal.querySelector('.nbf-m__c').innerHTML = c ? c.innerHTML : '';
    var gk = k.querySelector('.nbf-k__add'), gm = modal.querySelector('.nbf-m__dodaj'), ctrl = modal.querySelector('.nbf-m__ctrl');
    gm.style.display = gk ? '' : 'none'; gm.textContent = T.dodaj || ''; gm.classList.remove('je-zaseden');
    modal.querySelector('.nbf-m__err').textContent = ''; ctrl.innerHTML = '';
    if (gk) {
      ['.nbf-kol', '.nbf-k__izb'].forEach(function (sel) {
        var izv = k.querySelector(sel); if (!izv) { return; }
        var kop = izv.cloneNode(true);
        var s1 = izv.querySelectorAll('select'), s2 = kop.querySelectorAll('select');
        kop.querySelectorAll('.nbf-dd').forEach(function (d) { d.classList.remove('je-odprt'); });
        for (var i = 0; i < s1.length; i++) { s2[i].value = s1[i].value; s2[i].classList.remove('nbf-sel--err'); osvezi(s2[i]); }
        kop.classList.remove('nbf-k__izb--modal'); if (s2.length > 2) { kop.classList.add('nbf-k__izb--vec'); }
        ctrl.appendChild(kop);
      });
    }
    // povezava na stran izdelka ostane samo pri izdelkih, ki jih s te strani ni mogoce dodati
    var st = modal.querySelector('.nbf-m__stran'); st.textContent = T.poglej || ''; st.href = href; st.style.display = gk ? 'none' : '';
    modal.querySelector('.nbf-m__sm').innerHTML = '';
    if (predpomnilnik[id]) {
      napolniModal(predpomnilnik[id]);
      modal.classList.add('je-odprt'); document.documentElement.classList.add('nbf-brez-drsenja');
      return;
    }
    // rezerva (podatkov ni v strani): takoj pokazi sliko s kartice, opis pride s streznika
    var ks = k.querySelector('.nbf-k__s img'), ksrc = ks ? (ks.currentSrc || ks.src || '') : '';
    modal.querySelector('.nbf-m__sl').innerHTML = ksrc && ksrc.indexOf('data:') !== 0 ? '<img src="' + ksrc + '" alt="">' : '';
    modal.querySelector('.nbf-m__o').innerHTML = '<p class="nbf-m__nal">' + (T.nalagam || '') + '</p>';
    modal.classList.add('je-odprt'); document.documentElement.classList.add('nbf-brez-drsenja');
    fetch(ajax + '?action=noriks_bf_info&id=' + encodeURIComponent(id) + '&stran=' + encodeURIComponent(koren.getAttribute('data-stran') || ''), { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (res) { if (res && res.success && res.data) { predpomnilnik[id] = res.data; if (odprtaKartica === k) { napolniModal(res.data); } } else if (odprtaKartica === k) { modal.querySelector('.nbf-m__o').innerHTML = ''; } })
      .catch(function () { if (odprtaKartica === k) { modal.querySelector('.nbf-m__o').innerHTML = ''; } });
  });

  function prikazi(k, po) {
    function html(s, h) { var el = k.querySelector(s); if (el) { el.innerHTML = h; } }
    function txt(s, t) { var el = k.querySelector(s); if (el) { el.textContent = t; } }
    html('.nbf-c-red', po.red); html('.nbf-c-pri', po.pri); html('.nbf-c-akc', po.akc);
    txt('.nbf-c-pc', po.pc); txt('.nbf-k__pk', po.pk || ''); if (po.d) { txt('.nbf-c-d', po.d); }
  }
  // Cenovni blok, vrstica paketa in vrstica o dostavi dobijo visino najdaljse ponudbe,
  // da kartica ob preklopu kolicine (1x / 2x / 3x) ne skoci.
  function rezerviraj() {
    koren.querySelectorAll('.nbf-k[data-bf]').forEach(function (k) {
      var cfg; try { cfg = JSON.parse(k.getAttribute('data-bf')); } catch (x) { return; }
      if (!cfg.pon || cfg.pon.length < 2) { return; }
      var deli = [k.querySelector('.nbf-k__c'), k.querySelector('.nbf-k__d'), k.querySelector('.nbf-k__pk')], naj = [0, 0, 0];
      deli.forEach(function (el) { if (el) { el.style.minHeight = ''; } });
      cfg.pon.forEach(function (po) { prikazi(k, po); deli.forEach(function (el, i) { if (el) { naj[i] = Math.max(naj[i], el.getBoundingClientRect().height); } }); });
      prikazi(k, cfg.pon[parseInt(k.getAttribute('data-izb'), 10) || 0] || cfg.pon[0]);
      deli.forEach(function (el, i) { if (el && naj[i] > 0) { el.style.minHeight = Math.ceil(naj[i]) + 'px'; } });
    });
  }
  rezerviraj();
  var casR = null; window.addEventListener('resize', function () { clearTimeout(casR); casR = setTimeout(rezerviraj, 200); });
  if (document.fonts && document.fonts.ready) { document.fonts.ready.then(rezerviraj); }

  // izbira kolicine / ponudbe: preracuna cene na kartici
  koren.addEventListener('click', function (e) {
    var q = e.target.closest ? e.target.closest('.nbf-kol__g') : null; if (!q) { return; }
    e.preventDefault();
    var vModalu = q.closest('.nbf-m'), k = vModalu ? odprtaKartica : q.closest('.nbf-k'), cfg, i = parseInt(q.getAttribute('data-i'), 10) || 0;
    if (!k) { return; }
    try { cfg = JSON.parse(k.getAttribute('data-bf')); } catch (x) { return; }
    var po = cfg.pon[i]; if (!po) { return; }
    k.setAttribute('data-izb', i);
    function oznaci(koren2) { koren2.querySelectorAll('.nbf-kol__g').forEach(function (b) { b.classList.toggle('je-izbran', (parseInt(b.getAttribute('data-i'), 10) || 0) === i); }); }
    oznaci(k); if (modal && odprtaKartica === k) { oznaci(modal); }
    prikazi(k, po);
    if (modal && odprtaKartica === k) { var c = k.querySelector('.nbf-k__c'); modal.querySelector('.nbf-m__c').innerHTML = c ? c.innerHTML : ''; }
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
      try { manjka.parentNode.querySelector('.nbf-dd__g').focus(); } catch (x) {}
      return;
    }

    var po = cfg.pon[parseInt(k.getAttribute('data-izb'), 10) || 0] || cfg.pon[0];
    var fd = new FormData();
    fd.append('action', 'noriks_bf_add');
    fd.append('product_id', cfg.id);
    fd.append('nacin', cfg.nacin);
    fd.append('stran', koren.getAttribute('data-stran') || '');
    if (cfg.nacin === 'orto') { fd.append('bundle_option', po.id); } else { fd.append('quantity', po.kos); }
    (cfg.attrs || []).forEach(function (a) {
      var v = a.v.length === 1 ? a.v[0] : izbrano[a.p];
      if (cfg.nacin === 'orto') {
        for (var i = 1; i <= po.kos; i++) { fd.append('pairs[' + po.id + '][' + i + '][' + a.p + ']', v); }
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
