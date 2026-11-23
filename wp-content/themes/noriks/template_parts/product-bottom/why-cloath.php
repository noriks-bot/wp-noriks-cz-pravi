<?php
/**
 * product-bottom: NORIKS DuoDry (prej »Polar NORIKS Cloth XXL«) — dvostrana krpa od mikrovlakana (orto-cloath).
 *
 * 23.11.2026 predelano: vsi stari opisni bloki (prevodi konkurenčne strani — tuš-kabina,
 * ogledalo, 4× teža, garancija 60 dni …) zamenjani z novimi sekcijami iz
 * ~/Desktop/cloath-za-programerja/sekcije-CZ.html (sistem fis-*, kot why-winecooler.php).
 *
 * Vrstni red: 4 razlogi → video hero → gosta mikrovlakna + 3 videi → 3 koraki (videi)
 * → 4 podrobnosti (ikone) → pranje (video) → paket 6 kosov (slika + cena).
 * Sredstva: img/duodry/. Cene v zadnji sekciji (data-price) so iz README-ja — ob spremembi cene popravi.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$dd = esc_url( get_template_directory_uri() . '/img/duodry/' );
?>
<style>
/* 2) Video hero */
  .fis-hero { position: relative; width: 100vw; left: 50%; margin-left: -50vw; min-height: 520px; display: flex; align-items: center; overflow: hidden; }
  .fis-hero-vid { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
  .fis-hero-overlay { position: absolute; inset: 0; background: linear-gradient(90deg, rgba(0,0,0,0.5), rgba(0,0,0,0.05) 55%); }
  .fis-hero-inner { position: relative; z-index: 2; max-width: 1180px; margin: 0 auto; padding: 0 40px; width: 100%; box-sizing: border-box; }
  .fis-hero-title { color: #fff; font-weight: 800; font-size: clamp(27px,4vw,46px); line-height: 1.15; max-width: 640px; margin: 0; text-shadow: 0 2px 14px rgba(0,0,0,0.4); }
  @media (max-width: 768px) { .fis-hero { min-height: 380px; } .fis-hero-inner { padding: 0 22px; } }

  /* zajednički */
  .fis-h2 { font-size: clamp(24px,3vw,34px); font-weight: 800; color: #1c1c1c; line-height: 1.2; margin: 0 0 18px; }
  .fis-center { text-align: center; }
  .fis-eyebrow { font-size: 13px; letter-spacing: 1px; text-transform: uppercase; color: #555; margin: 0 0 8px; }

  /* 3) Stručnjaci */
  .fis-experts { padding: 44px 0; }
  .fis-exp-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 40px; align-items: start; }
  .fis-exp-quote p { font-size: 15.5px; line-height: 1.6; color: #333; margin: 0 0 14px; }
  .fis-exp-author { color: #1c1c1c; }
  .fis-exp-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
  .fis-exp-card { position: relative; border-radius: 14px; overflow: hidden; aspect-ratio: 3/4; background: #222; }
  .fis-exp-card video,
  .fis-exp-card img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .fis-exp-cap { position: absolute; left: 0; right: 0; bottom: 0; padding: 14px; color: #fff; background: linear-gradient(0deg, rgba(0,0,0,.75), rgba(0,0,0,0)); }
  .fis-exp-name { font-weight: 800; font-size: 18px; }
  .fis-exp-role { font-size: 13px; }
  .fis-exp-org { font-size: 12px; font-style: italic; opacity: .9; }

  /* 4) UGC */
  .fis-ugc { background: #223047; padding: 40px 0; }
  .fis-ugc-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
  .fis-ugc-media { border-radius: 12px; overflow: hidden; aspect-ratio: 3/4; background: #000; }
  .fis-ugc-media video,
  .fis-ugc-media img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .fis-ugc-cap { color: #eee; font-size: 14px; line-height: 1.5; margin: 10px 0 0; }

  /* 5) ThermoTrac */
  .fis-thermo { background: #f0efe9; padding: 44px 0; }
  .fis-thermo-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
  .fis-thermo-card { position: relative; border-radius: 16px; overflow: hidden; aspect-ratio: 4/3; background: #111; }
  .fis-thermo-card video { width: 100%; height: 100%; object-fit: cover; display: block; }
  .fis-thermo-label { position: absolute; left: 16px; bottom: 14px; color: #fff; font-weight: 800; font-size: 18px; text-shadow: 0 1px 8px rgba(0,0,0,.6); }

  /* 6) Upgrades */
  .fis-upg { padding: 44px 0; }
  .fis-upg-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; text-align: center; }
  .fis-upg-ico { width: 56px; height: 56px; object-fit: contain; display: block; margin: 0 auto 14px; }
  .fis-upg-title { font-weight: 800; color: #1c1c1c; margin: 0 0 8px; font-size: 16px; }
  .fis-upg-text { font-size: 14px; line-height: 1.5; color: #444; margin: 0; }

  /* 7 + 8) row2 */
  .fis-eng, .fis-cheaper { padding: 40px 0; }
  .fis-cheaper { background: #f4f4f4; }
  .fis-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 44px; align-items: center; }
  .fis-row2-copy p { font-size: 15.5px; line-height: 1.65; color: #333; }
  .fis-row2-media { border-radius: 16px; overflow: hidden; }
  .fis-row2-media video,
  .fis-row2-media img { width: 100%; height: auto; display: block; }
  .fis-cheaper .fis-row2-media { aspect-ratio: 1 / 1; }
  .fis-cheaper .fis-row2-media video,
  .fis-cheaper .fis-row2-media img { height: 100%; object-fit: cover; }

  @media (max-width: 900px) {
    .fis-exp-grid { grid-template-columns: 1fr; gap: 24px; }
    .fis-ugc-grid { grid-template-columns: 1fr 1fr; }
    .fis-thermo-grid { grid-template-columns: 1fr; }
    .fis-upg-grid { grid-template-columns: 1fr 1fr; gap: 22px; }
    .fis-row2 { grid-template-columns: 1fr; gap: 22px; }
    .fis-cheaper .fis-row2-media { order: 2; }
  }
  @media (max-width: 560px) {
    .fis-exp-cards { grid-template-columns: 1fr 1fr; }
    .fis-ugc-grid { grid-template-columns: 1fr; }
  }

  /* 1) Znanstveno potkrijepljeno — siva kartica */
  .fis-science { background: #f4f4f4; padding: 44px 0; }
  .fis-wrap { max-width: 1180px; margin: 0 auto; padding: 0 16px; }
  .fis-box { background: transparent; border-radius: 0; padding: 0; }
  .fis-title { font-size: clamp(23px,2.9vw,32px); font-weight: 800; color: #1c1c1c; line-height: 1.2; margin: 0 0 26px; }
  .fis-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0; }
  .fis-card { padding: 0 22px; border-left: 1px solid #dcdcdc; }
  .fis-card:first-child { border-left: 0; padding-left: 0; }
  .fis-card-title { font-size: 17px; font-weight: 800; color: #1c1c1c; margin: 0 0 10px; }
  .fis-card-text { font-size: 15px; line-height: 1.55; color: #333; margin: 0; }
  @media (max-width: 820px) {
    .fis-grid { grid-template-columns: 1fr 1fr; gap: 22px 0; }
    .fis-card:nth-child(odd) { border-left: 0; padding-left: 0; }
  }
  @media (max-width: 480px) {
    .fis-grid { grid-template-columns: 1fr; }
    .fis-card { border-left: 0; padding-left: 0; }
  }

  /* Nema "Tablica veličina" linka na FisioRest-u (identično kao bunion). */
  .noriks-global-sizechart, .gck-size-link, .gck-size-link-wrap,
  #open-size-chart, #open-size-chartCustom { display: none !important; }

  /* Kratki opis: sakrij standardne točke (•), razmaci. */
  .woocommerce-product-details__short-description ul { list-style: none; margin: 8px 0 26px; padding-left: 0; }
  .woocommerce-product-details__short-description ul li { list-style: none; padding-left: 0; margin-left: 0; }
  .woocommerce-product-details__short-description p:has(+ ul) { margin-top: 20px; margin-bottom: 4px; }
/* NORIKS majica — dodatki za nove sekcije */
.nk-media{position:relative;overflow:hidden;background:#eee}
.nk-media>video,.nk-media>img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block}
.fis-cheaper .nk-media{aspect-ratio:1/1}
.pv-wide,.nk-wide{aspect-ratio:16/10}
.fis-hero{left:auto!important;margin-left:0!important;width:100%!important}

/* ===== NORIKS – mobilna prilagoditev sekcij (telefon, do 767 px) =====
   Velja za majico in boksarice (isti razredi). Dodaj na KONEC sekcije.css. */
@media (max-width:767px){
  /* 1) 4 razlike: kartice 2 x 2 namesto dolgega seznama */
  .fis-science{padding:30px 0!important}
  .fis-science .fis-title{font-size:22px!important;line-height:1.25!important;margin-bottom:16px!important}
  .fis-science .fis-grid{grid-template-columns:1fr 1fr!important;gap:10px!important}
  .fis-science .fis-card{background:#fff;border:0!important;border-radius:14px;padding:14px 12px!important}
  .fis-science .fis-card-title{font-size:15px!important;line-height:1.25;margin:0 0 6px!important}
  .fis-science .fis-card-text{font-size:13.5px!important;line-height:1.45!important}

  /* 2) hero video: višji, besedilo spodaj */
  .fis-hero{min-height:62vh!important;align-items:flex-end!important;padding-bottom:28px}
  .fis-hero-overlay{background:linear-gradient(0deg,rgba(0,0,0,.6),rgba(0,0,0,0) 60%)!important}
  .fis-hero-title{font-size:clamp(24px,7.4vw,32px)!important}

  /* 3) vrstice, ki se drsajo vstran (videi materiala, mnenja kupcev, 3 stvari) */
  .fis-exp-cards,.fis-ugc-grid,.fis-thermo-grid{display:flex!important;gap:12px!important;overflow-x:auto;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;scrollbar-width:none;margin:0 -16px!important;padding:0 16px 4px!important;scroll-padding-left:16px}
  .fis-exp-cards::-webkit-scrollbar,.fis-ugc-grid::-webkit-scrollbar,.fis-thermo-grid::-webkit-scrollbar{display:none}
  .fis-exp-card{flex:0 0 64%!important;scroll-snap-align:start}
  .fis-ugc-card{flex:0 0 76%!important;scroll-snap-align:start}
  .fis-thermo-card{flex:0 0 80%!important;scroll-snap-align:start;aspect-ratio:4/5!important}
  .fis-ugc-cap{font-size:14px!important;line-height:1.4}
  .fis-exp-grid{gap:18px!important}

  /* 4) barve: polne vrstice, zadnja na sredini */
  .nk-col-grid{display:flex!important;flex-wrap:wrap;justify-content:center;gap:10px!important}
  .nk-col{flex:0 0 calc((100% - 30px)/4);box-sizing:border-box;padding:6px!important}
  .nk-col-grid:has(> .nk-col:nth-child(5):last-child) .nk-col,
  .nk-col-grid:has(> .nk-col:nth-child(6):last-child) .nk-col{flex-basis:calc((100% - 20px)/3)}
  .nk-col{min-width:0}
  .nk-col span{font-size:clamp(10px,3.1vw,12px)!important;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block}
  .nk-col-note{gap:6px 14px!important;font-size:13.5px}

  /* 5) primerjalna tabela: ozki stolpci, brez lomljenja besed */
  .nk-cmp-row{grid-template-columns:minmax(0,1fr) 96px 84px!important}
  .nk-cmp-row>div{padding:12px 8px!important;font-size:13.5px!important;line-height:1.35}
  .nk-cmp-head>div{padding:14px 6px!important}
  .nk-cmp-brand{font-size:12px!important;letter-spacing:0!important;line-height:1.2;word-break:keep-all;overflow-wrap:normal;hyphens:none}
  .nk-cmp-price{font-size:15px!important;line-height:1.2}
  .nk-cmp-note{font-size:11px!important;line-height:1.25}

  /* 6) splošno: manj praznega prostora, berljivi naslovi */
  .fis-h2{font-size:clamp(21px,6.4vw,26px)!important;line-height:1.2!important}
  .fis-thermo,.fis-ugc,.fis-experts,.nk-colors,.fis-upg,.fis-eng,.fis-cheaper,.nk-cmp{padding-top:36px!important;padding-bottom:36px!important}

  /* 7) sekcija ocen */
  .nk-rev .nk-sum{gap:10px!important}
  .nk-rev .nk-sum .nk-box{padding:16px!important}
  .nk-rev .nk-feat{display:flex!important;gap:12px!important;overflow-x:auto;scroll-snap-type:x mandatory;-webkit-overflow-scrolling:touch;scrollbar-width:none;margin:0 -16px!important;padding:0 16px 4px!important;scroll-padding-left:16px}
  .nk-rev .nk-feat::-webkit-scrollbar,.nk-rev .nk-chips::-webkit-scrollbar{display:none}
  .nk-rev .nk-feat>.nk-box{flex:0 0 80%;scroll-snap-align:start}
  .nk-rev .nk-chips{flex-wrap:nowrap!important;overflow-x:auto;scrollbar-width:none;margin-left:-16px!important;margin-right:-16px!important;padding:0 16px!important}
  .nk-rev .nk-chip{flex:none;white-space:nowrap}
  .nk-rev .nk-list .nk-card:nth-child(n+4){display:none}  /* na telefonu prve 3, ostale z gumbom »Več ocen« */
}

/* zelo ozki telefoni (320–374 px, npr. iPhone SE 1. gen., manjši Android) */
@media (max-width:374px){
  .fis-wrap{padding-left:12px!important;padding-right:12px!important}
  .fis-science .fis-grid{gap:8px!important}
  .fis-science .fis-card{padding:12px 10px!important}
  .fis-science .fis-card-title{font-size:14px!important}
  .fis-science .fis-card-text{font-size:12.5px!important}
  .fis-exp-cards,.fis-ugc-grid,.fis-thermo-grid,.nk-rev .nk-feat{margin:0 -12px!important;padding:0 12px 4px!important;scroll-padding-left:12px}
  .fis-exp-card{flex-basis:72%!important}.fis-ugc-card{flex-basis:82%!important}.fis-thermo-card{flex-basis:86%!important}.nk-rev .nk-feat>.nk-box{flex-basis:86%}
  .nk-col-grid{gap:6px!important}.nk-col{flex-basis:calc((100% - 18px)/4);padding:4px!important}
  .nk-col-grid:has(> .nk-col:nth-child(5):last-child) .nk-col,.nk-col-grid:has(> .nk-col:nth-child(6):last-child) .nk-col{flex-basis:calc((100% - 12px)/3)}
  .nk-cmp-row{grid-template-columns:minmax(0,1fr) 86px 72px!important}
  .nk-cmp-row>div{font-size:12.5px!important;padding:10px 6px!important}
  .nk-cmp-price{font-size:13.5px!important}
}
/* veliki telefoni (430–767 px, npr. Pro Max, Galaxy Ultra, zložljivi): več kartic naenkrat */
@media (min-width:430px) and (max-width:767px){
  .fis-exp-card{flex-basis:46%!important}.fis-ugc-card{flex-basis:58%!important}.fis-thermo-card{flex-basis:64%!important}.nk-rev .nk-feat>.nk-box{flex-basis:62%}
}
@media (min-width:560px) and (max-width:767px){
  .fis-exp-card{flex-basis:36%!important}.fis-ugc-card{flex-basis:40%!important}.fis-thermo-card{flex-basis:46%!important;aspect-ratio:3/4!important}.nk-rev .nk-feat>.nk-box{flex-basis:46%}
  .fis-science .fis-grid{grid-template-columns:repeat(4,1fr)!important}
  .nk-col{flex-basis:calc((100% - 60px)/7)!important}
}
</style>
<div class="ndd-why">
<section class="fis-science"><div class="fis-wrap"><div class="fis-box"><h2 class="fis-title">4 důvody, proč ho už nepustíte z ruky</h2><div class="fis-grid">
<div class="fis-card"><h3 class="fis-card-title">Méně chemie</h3><p class="fis-card-text">Sklo, obklady a baterie otřete <strong>s menším množstvím prostředků</strong>.</p></div>
<div class="fis-card"><h3 class="fis-card-title">Bez šmouh</h3><p class="fis-card-text">Síťovaná strana <strong>vyleští do lesku</strong> – bez matného filmu a vláken.</p></div>
<div class="fis-card"><h3 class="fis-card-title">Vysoká savost</h3><p class="fis-card-text">Hustá vlákna <strong>zachytí vodu</strong> v několika tazích.</p></div>
<div class="fis-card"><h3 class="fis-card-title">Dvě strany</h3><p class="fis-card-text">Chlupatá strana <strong>čistí</strong>, síťovaná <strong>leští</strong>.</p></div>
</div></div></div></section>

<section class="fis-hero"><video class="fis-hero-vid" src="<?php echo $dd; ?>noriks-duodry-hero.mp4" muted autoplay loop playsinline preload="metadata"></video><div class="fis-hero-overlay"></div><div class="fis-hero-inner"><h2 class="fis-hero-title">Linka, baterie, sklo – jeden hadřík na všechno.</h2></div></section>

<section class="fis-experts"><div class="fis-wrap fis-exp-grid"><div class="fis-exp-quote"><h2 class="fis-h2">Hustá mikrovlákna, která pracují za vás</h2>
<p>Hadřík o rozměru <strong>60 × 40 cm</strong> zachytí špínu, vodní kámen i vodu jedním tahem a síťovaná strana sklo vyleští <strong>bez šmouh</strong>.</p>
<p>✔ Nepouští vlákna a nenechává pruhy<br>✔ Nasaje hodně vody najednou<br>✔ Vydrží stovky praní</p>
<p class="fis-exp-author"><strong>Podívejte se zblízka</strong><br>Jak hadřík funguje v praxi.</p></div>
<div class="fis-exp-cards">
<div class="fis-exp-card nk-media"><video src="<?php echo $dd; ?>noriks-duodry-sides.mp4" muted autoplay loop playsinline preload="metadata"></video><div class="fis-exp-cap"><div class="fis-exp-name">Dvě strany</div><div class="fis-exp-role">Čistí a leští</div></div></div>
<div class="fis-exp-card nk-media"><video src="<?php echo $dd; ?>noriks-duodry-absorb.mp4" muted autoplay loop playsinline preload="metadata"></video><div class="fis-exp-cap"><div class="fis-exp-name">Saje vodu</div><div class="fis-exp-role">Bez louží a kapání</div></div></div>
<div class="fis-exp-card nk-media"><video src="<?php echo $dd; ?>noriks-duodry-faucet.mp4" muted autoplay loop playsinline preload="metadata"></video><div class="fis-exp-cap"><div class="fis-exp-name">Baterie a chrom</div><div class="fis-exp-role">Bez skvrn od vody</div></div></div>
</div></div></section>

<section class="fis-thermo"><div class="fis-wrap"><p class="fis-eyebrow">Jak ho používat</p><h2 class="fis-h2 fis-center">3 kroky k čistému sklu</h2><div class="fis-thermo-grid">
<div class="fis-thermo-card nk-media"><video src="<?php echo $dd; ?>noriks-duodry-wring.mp4" muted autoplay loop playsinline preload="metadata"></video><div class="fis-thermo-label">1. Namočte a vyždímejte</div></div>
<div class="fis-thermo-card nk-media"><video src="<?php echo $dd; ?>noriks-duodry-door.mp4" muted autoplay loop playsinline preload="metadata"></video><div class="fis-thermo-label">2. Otřete chlupatou stranou</div></div>
<div class="fis-thermo-card nk-media"><video src="<?php echo $dd; ?>noriks-duodry-table.mp4" muted autoplay loop playsinline preload="metadata"></video><div class="fis-thermo-label">3. Vyleštěte síťovanou</div></div>
</div></div></section>

<section class="fis-upg"><div class="fis-wrap"><h2 class="fis-h2 fis-center">Detaily, které dělají rozdíl</h2><div class="fis-upg-grid">
<div class="fis-upg-card"><img class="fis-upg-ico" alt="" src="<?php echo $dd; ?>ikona-1.svg"><div class="fis-upg-title">Hustá mikrovlákna</div><p class="fis-upg-text">Zachytí špínu a vodní kámen bez drhnutí.</p></div>
<div class="fis-upg-card"><img class="fis-upg-ico" alt="" src="<?php echo $dd; ?>ikona-2.svg"><div class="fis-upg-title">XXL 60 × 40 cm</div><p class="fis-upg-text">Pokryje celé sklo, méně vymáchávání.</p></div>
<div class="fis-upg-card"><img class="fis-upg-ico" alt="" src="<?php echo $dd; ?>ikona-3.svg"><div class="fis-upg-title">Praní na 40 °C</div><p class="fis-upg-text">Perte v pračce, připravený k dalšímu použití.</p></div>
<div class="fis-upg-card"><img class="fis-upg-ico" alt="" src="<?php echo $dd; ?>ikona-4.svg"><div class="fis-upg-title">Poutko na pověšení</div><p class="fis-upg-text">Vždy po ruce v koupelně i kuchyni.</p></div>
</div></div></section>

<section class="fis-eng"><div class="fis-wrap fis-row2"><div class="fis-row2-copy"><h2 class="fis-h2">Vydrží praní za praním</h2><p>Hadřík jednoduše vyperete v pračce na 40 °C. Nebledne, nepáře se a nepouští vlákna – vydrží stovky použití.</p></div><div class="fis-row2-media nk-media nk-wide"><video src="<?php echo $dd; ?>noriks-duodry-wash.mp4" muted autoplay loop playsinline preload="metadata"></video></div></div></section>

<section class="fis-cheaper"><div class="fis-wrap fis-row2"><div class="fis-row2-media nk-media"><img src="<?php echo $dd; ?>noriks-duodry-6-kosov.webp" alt="NORIKS DuoDry" loading="lazy"></div><div class="fis-row2-copy"><p class="fis-eyebrow">VÝHODNÝ NÁKUP</p><h2 class="fis-h2">Hadřík do koupelny, kuchyně, na okna i do auta</h2><p>Balení 2 hadříků stojí <span data-price="1">579 Kč</span>. Balení 6 hadříků stojí <span data-price="3">1 219 Kč</span>, tedy jen <span data-price="3-unit">203 Kč</span> za kus. S 30 dny na vrácení peněz.</p></div></div></section>
</div>
