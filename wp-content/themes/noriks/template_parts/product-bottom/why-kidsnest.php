<?php
/**
 * product-bottom: NORIKS KidsNest — detsky polstar pro spravne dychani (orto-kidsnest).
 * Kopie sekci tryneedo.com/products/kids-pillow, CZ preklad (zmirnena med. tvrzeni).
 * Poradi:
 *   1. Trust marquee (modra)  2. "Zacnete jiz dnes vecer..." (obrazek L / text P, modry nadpis)
 *   3. "Spravna opora..." (text L / obrazek P)  4. Statistika 94/60/98 (svetle modra, 3 karty s kruhy)
 *   5. "#1 detsky polstar 2026" + hvezdicky + posuvny foto pas
 * Modra: #2b3fb0, svetla: #eef1fb, navy: #1b2450. Obrazky: img/kidsnest/
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$kn = get_template_directory_uri() . '/img/kidsnest/';
?>

<!-- ============ 1) Trust marquee (modrá lišta, roluje) ============ -->
<div class="kn-marquee" aria-hidden="true">
  <div class="kn-marquee-track">
    <?php $kn_ticker = array('DOPORUČENÍ PEDIATRŮ','OEKO-TEX® PAMĚŤOVÁ PĚNA','3ZÓNOVÁ STRUKTURA','90 NOCÍ NA VYZKOUŠENÍ','HYPOALERGENNÍ','PRATELNÝ POTAH');
    for ( $r = 0; $r < 2; $r++ ) { foreach ( $kn_ticker as $t ) { echo '<span class="kn-tick">'.esc_html($t).'</span><span class="kn-dot">•</span>'; } } ?>
  </div>
</div>

<!-- ============ 2) Začněte již dnes večer — obrázek VLEVO, text VPRAVO ============ -->
<section class="kn-sec">
  <div class="kn-wrap kn-row2">
    <div class="kn-media"><img src="<?php echo esc_url( $kn.'01-poravnan.webp' ); ?>" alt="Dokonale zarovnáno — hlava, krk a páteř během spánku" loading="lazy" onerror="this.style.display='none'"></div>
    <div class="kn-copy">
      <p class="kn-eyebrow">Vyvinuto se stomatology pro dětské dýchací cesty</p>
      <h2 class="kn-h2 kn-h2-blue">Začněte již dnes večer napravovat skryté škody.</h2>
      <p>Dětští stomatologové specializovaní na dýchací cesty upozorňují rodiče na stejný tichý problém: děti, které chrápou a dýchají ústy, „nespí jen hůř“. Jejich čelist, patro a struktura obličeje se mohou pomalu vyvíjet špatným směrem.</p>
      <p><strong>A okno pro nápravu nezůstává otevřené navždy.</strong></p>
      <p>NORIKS <strong>polštář KidsNest</strong> je navržen tak, aby <strong>podpíral hlavu, čelist a dýchací cesty ve správné poloze během spánku</strong> — a podporoval tak dýchání nosem a zdravější vývoj obličeje, dokud na tom ještě záleží.</p>
      <p><strong>Tohle není jen polštář.<br>Je to noční opora dýchacích cest v letech, která formují obličej vašeho dítěte.</strong></p>
    </div>
  </div>
</section>

<!-- ============ 3) Správná opora — text VLEVO, obrázek VPRAVO ============ -->
<section class="kn-sec">
  <div class="kn-wrap kn-row2">
    <div class="kn-copy">
      <h2 class="kn-h2 kn-h2-blue">Správná opora hlavy a krku je klíčová pro zdravý spánek.</h2>
      <p>Ergonomický dětský polštář drží <strong>hlavu a krk v přirozeném zarovnání a pomáhá zabránit naklánění hlavy</strong> během noci. Páteř tak zůstává správně zarovnaná — i když se dítě ve spánku hodně vrtí.</p>
      <p><strong>Výsledkem je klidnější spánek a lepší regenerace.</strong></p>
    </div>
    <div class="kn-media"><img src="<?php echo esc_url( $kn.'02-san.jpg' ); ?>" alt="Dítě klidně spí na polštáři KidsNest" loading="lazy" onerror="this.style.display='none'"></div>
  </div>
</section>

<!-- ============ 4) Statistika — světle modrá, 3 karty s kruhy ============ -->
<section class="kn-sec kn-stats-sec">
  <div class="kn-wrap">
    <h2 class="kn-h2 kn-h2-blue kn-center">Stvořen k ochraně vyvíjejícího se obličeje vašeho dítěte</h2>
    <p class="kn-sub kn-center"><strong>Spánek s otevřenými ústy v dětství může přetvarovat rostoucí obličej. KidsNest drží hlavu vašeho dítěte zarovnanou, aby dýchalo nosem.</strong></p>
    <div class="kn-stats">
      <?php
      $kn_stats = array(
        array('94','165.3','rodičů si všimne, že dítě spí <strong>se zavřenými ústy</strong> do 2 týdnů'),
        array('60','105.5','vývoje obličeje vašeho <strong>dítěte</strong> se formuje do 6 let — toto okno se už znovu neotevře'),
        array('98','172.3','rodičů by doporučilo <strong>KidsNest</strong>, aby ochránili úsměv dalšího dítěte'),
      );
      foreach ( $kn_stats as $st ) : ?>
      <div class="kn-stat-card">
        <svg class="kn-ring" viewBox="0 0 64 64" aria-hidden="true">
          <circle cx="32" cy="32" r="28" fill="none" stroke="#dfe5f5" stroke-width="5"/>
          <circle cx="32" cy="32" r="28" fill="none" stroke="#2b3fb0" stroke-width="5" stroke-linecap="round" stroke-dasharray="<?php echo esc_attr($st[1]); ?> 175.9" transform="rotate(-90 32 32)"/>
          <text x="32" y="38" text-anchor="middle" class="kn-ring-t"><?php echo esc_html($st[0]); ?>%</text>
        </svg>
        <p><?php echo wp_kses_post($st[2]); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 5) #1 detsky polstar + hvezdicky + posuvny foto pas ============ -->
<section class="kn-sec kn-rated-sec">
  <div class="kn-wrap">
    <h2 class="kn-h2 kn-h2-blue kn-center">Zvolen dětským polštářem na spaní #1 v roce 2026.</h2>
    <p class="kn-sub kn-center">Podpořte jejich spánek — podpořte roky, kdy vyrůstají.</p>
    <p class="kn-stars kn-center"><span aria-hidden="true">★★★★★</span> Hodnocení 4,8/5 na základě 140+ recenzí</p>
  </div>
  <div class="kn-strip">
    <div class="kn-strip-track">
      <?php for ( $r = 0; $r < 2; $r++ ) : for ( $i = 1; $i <= 5; $i++ ) : ?>
        <img src="<?php echo esc_url( $kn.'traka/t'.$i.'.webp' ); ?>" alt="NORIKS KidsNest — děti a rodiče" loading="lazy" onerror="this.style.display='none'">
      <?php endfor; endfor; ?>
    </div>
  </div>
</section>

<!-- ============ 6) Kvalita materiálů — obrázek VLEVO, text VPRAVO ============ -->
<section class="kn-sec">
  <div class="kn-wrap kn-row2">
    <div class="kn-media"><img src="<?php echo esc_url( $kn.'03-detalj.webp' ); ?>" alt="KidsNest — 3zónová struktura a prodyšná tkanina zblízka" loading="lazy" onerror="this.style.display='none'"></div>
    <div class="kn-copy">
      <h2 class="kn-h2 kn-h2-blue">Kvalita, kterou pocítíte — noc co noc.</h2>
      <p>Hustá, prodyšná pletenina a pečlivě tvarovaný povrch tu nejsou kvůli vzhledu — <strong>každá zóna má svou úlohu</strong>. Střed jemně přijímá hlavu, okraje podpírají krk a struktura si drží tvar i po měsících každodenního používání.</p>
      <p>Potah se dá sundat a vyprat v pračce, pěna je <strong>hypoalergenní a odolná vůči roztočům</strong> — polštář tak zůstává svěží, čistý a připravený na každou noc. Bez prohlubní, bez zplošťování, bez kompromisů.</p>
      <p><strong>Polštář, který i po roce vypadá — a podpírá — jako první den.</strong></p>
    </div>
  </div>
</section>

<style>
  .kn-wrap { max-width: 1440px; margin: 0 auto; padding: 0 22px; } /* isti container kao gornji .product */
  .kn-sec { padding: 60px 0; }
  .kn-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
  .kn-h2 { font-size: clamp(26px,3.2vw,40px); font-weight: 800; color: #1b2450; line-height: 1.14; margin: 0 0 16px; }
  .kn-h2-blue { color: #2b3fb0; }
  .kn-center { text-align: center; }
  .kn-eyebrow { font-size: 13px; font-weight: 800; letter-spacing: .02em; color: #1b2450; margin: 0 0 6px; }
  .kn-copy p { font-size: 15.5px; line-height: 1.65; color: #33394f; margin: 0 0 14px; }
  .kn-sub { font-size: 16px; line-height: 1.55; color: #33394f; max-width: 680px; margin: 0 auto 10px; }
  .kn-media img { width: 100%; height: auto; display: block; border-radius: 18px; box-shadow: 0 14px 40px rgba(27,36,80,.10); }

  /* 1) marquee */
  .kn-marquee { background: #2b3fb0; overflow: hidden; white-space: nowrap; margin-top: 26px; }
  @media (min-width: 861px) { .kn-marquee { margin-top: -20px; } } /* desktop: prepolovljen razmik do vsebine zgoraj */
  .kn-marquee + .kn-sec { padding-top: 26px; }
  .kn-marquee-track { display: inline-block; padding: 13px 0; animation: knScroll 28s linear infinite; }
  .kn-tick { color: #fff; font-weight: 800; font-style: italic; font-size: 15px; letter-spacing: .06em; text-transform: uppercase; }
  .kn-dot { color: #aebafe; margin: 0 22px; font-weight: 800; }
  @keyframes knScroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }

  /* 4) statistika */
  .kn-stats-sec { background: #eef1fb; }
  .kn-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 24px; max-width: 1180px; margin: 30px auto 0; }
  .kn-stat-card { background: #fff; border-radius: 16px; padding: 34px 26px; text-align: center; box-shadow: 0 10px 28px rgba(27,36,80,.07); }
  .kn-ring { width: 150px; height: 150px; margin: 0 auto 18px; display: block; }
  .kn-ring-t { font-size: 15px; font-weight: 800; fill: #2b3fb0; }
  .kn-stat-card p { font-size: 15px; line-height: 1.5; color: #33394f; margin: 0; }
  .kn-stat-card p strong { color: #2b3fb0; }

  /* 5) rated + strip */
  .kn-rated-sec { background: #eef1fb; padding-bottom: 0; }
  .kn-stars { font-size: 16px; color: #1b2450; font-weight: 600; margin: 6px 0 26px; }
  .kn-stars span { color: #f5a623; letter-spacing: 2px; margin-right: 8px; }
  .kn-strip { overflow: hidden; width: 100vw; margin-left: calc(50% - 50vw); padding-bottom: 34px; }
  .kn-strip-track { display: flex; gap: 8px; width: max-content; animation: knScroll 60s linear infinite; }
  .kn-strip:hover .kn-strip-track { animation-play-state: paused; }
  .kn-strip-track img { width: 350px; aspect-ratio: 1/1; object-fit: cover; border-radius: 10px; display: block; flex: 0 0 auto; }

  @media (max-width: 860px) {
    .kn-sec { padding: 30px 0; }
    .kn-row2 { grid-template-columns: 1fr; gap: 18px; }
    .kn-row2 .kn-media { order: -1; }
    .kn-h2 { font-size: 2rem; }
    .kn-stats { grid-template-columns: 1fr; gap: 14px; margin-top: 18px; }
    .kn-ring { width: 120px; height: 120px; }
    .kn-strip-track img { width: 240px; }
  }
</style>
