<?php
/**
 * product-bottom: KOREKTOR VBOČENÉHO PALCE (bunion / hallux valgus)
 *
 * Dedicated bottom-nicer for the NORIKS bunion corrector.
 * Shown via single-product-bottom-nicer.php when noriks_is_type('bunion').
 *
 * Media jsou v tématu (git), relativně přes get_template_directory_uri():
 *   img/bunion-videos/section-1.mp4, funkcionira.mp4, step-1..3.mp4
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$bun_vid_dir = get_template_directory_uri() . '/img/bunion-videos/';
$bun_video_1 = $bun_vid_dir . 'section-1.mp4'; // 1) One foot away
$bun_video_2 = $bun_vid_dir . 'funkcionira.mp4'; // 2) Jak funguje

$bun_img_features = get_template_directory_uri() . '/img/bunion/why-2026.webp';

// Skutečné výsledky — procenta
$bun_results = array(
    array( 'pct' => 91, 'text' => 'uživatelů uvedlo zmírnění bolesti způsobené vbočeným palcem již od 2. relace' ),
    array( 'pct' => 90, 'text' => 'uživatelů zcela odstranilo bolest způsobenou vbočeným palcem již po 14 dnech pravidelného používání (30 min/den)' ),
    array( 'pct' => 88, 'text' => 'uživatelů zaznamenalo viditelné zlepšení v postavení prstů již po 30 dnech pravidelného používání (30 min/den)' ),
);

// Proč si vybrat nás — porovnání (stejný styl jako knc-table na ponožkách se zipem)
$bun_cmp = array(
    '30denní záruka vrácení peněz',
    'Zmírňuje nepohodlí',
    'Zabraňuje růstu vbočeného palce',
    'Postupem času zlepšuje stav vbočeného palce',
    'Pohyblivá konstrukce — můžete v něm chodit',
    'Odolný a dlouhotrvající',
);

// Jak se používá — 3 kroky (video + popis)
$bun_steps = array(
    array( 'video' => $bun_vid_dir . 'step-1.mp4', 'caption' => 'Připevněte NORIKS korektor na palec a chodidlo' ),
    array( 'video' => $bun_vid_dir . 'step-2.mp4', 'caption' => 'Nastavte intenzitu protažení podle potřeby' ),
    array( 'video' => $bun_vid_dir . 'step-3.mp4', 'caption' => 'Uvolněte se a nechte NORIKS korektor pracovat' ),
);
?>

<!-- ============ 1) Jste jen krok… ============ -->
<section class="bun-why bun-intro">
  <div class="bun-wrap bun-row">
    <div class="bun-col bun-media">
      <video src="<?php echo esc_url( $bun_video_1 ); ?>" muted autoplay loop playsinline preload="metadata"></video>
    </div>
    <div class="bun-col bun-copy">
      <h2 class="bun-title">Jste jen krok od toho, abyste se zbavili <span class="bun-hl">nepohodlí způsobeného vbočeným palcem</span>, oteklých prstů a bolestí chodidel…</h2>
      <p>Pokud tohle čtete, je velká pravděpodobnost, že trpíte přetrvávajícím <strong class="bun-red">nepohodlím způsobeným vbočeným palcem</strong>.</p>
      <p>Výsledek? Bolest a nepohodlí ovlivňují vaše každodenní činnosti.</p>
      <p>Pokud se neléčí, mohou se zhoršit. Prsty se překříží, mohou se vyvinout kladívkové prsty a kostní výrůstky.</p>
      <p>Vbočený palec je <strong class="bun-red">postupující problém</strong> a sám od sebe nezmizí.</p>
      <p>Časem může vést k vážnějším problémům, jako jsou <u>invazivní operace, potíže s kyčlemi, koleny a bederní páteří, ba dokonce nepohyblivost</u>.</p>
      <p>S pomocí klinicky ověřené pokročilé terapie srovnání a patentovaného kloubového mechanismu <strong>NORIKS korektor vbočeného palce</strong> účinně zmírňuje nepohodlí na postižené části chodidla a obnovuje zdraví vašeho chodidla pouhými 30 minutami denního používání.</p>
      <p class="bun-stat"><span class="bun-check" aria-hidden="true">✔</span> <em>91 % uživatelů uvedlo <strong>zmírnění bolesti chodidel</strong> již v prvním týdnu</em></p>
    </div>
  </div>
</section>

<!-- ============ 2) Jak to funguje? ============ -->
<section class="bun-why">
  <div class="bun-wrap bun-row bun-reverse">
    <div class="bun-col bun-media">
      <video src="<?php echo esc_url( $bun_video_2 ); ?>" muted autoplay loop playsinline preload="metadata"></video>
    </div>
    <div class="bun-col bun-copy">
      <h2 class="bun-title">Jak to funguje?</h2>
      <p><strong>NORIKS korektor vbočeného palce</strong> využívá pokročilou terapii srovnání. Je navržen tak, aby <strong class="bun-red">podpořil znovusrovnání</strong> palce a postupně zmírnil zánět pomocí silného patentovaného kloubového mechanismu.</p>
      <p>Pomáhá uvolnit svalové napětí tím, že jemně vrací palec do jeho přirozené polohy, což časem vede k bezbolestnému přirozenému srovnání kloubu prstu.</p>
      <p>Tak se uvolní roky nahromaděné napětí, výrůstek se napraví a zmenší, bolest se zmírní a zabrání se dalšímu růstu — abyste se opět postavili na nohy, vzpřímeně a sebejistě.</p>
      <p>Někteří uživatelé mohou potřebovat relaci nebo dvě, aby si zvykli, protože <strong class="bun-red">pocit může být výraznější</strong> ve srovnání s jinými metodami.</p>
      <p>Je to přirozený a neinvazivní způsob, jak obnovit přirozenou polohu prstu a chodidla a odstranit škody způsobené nevhodnou obuví nebo genetikou.</p>
      <p>Ať už jde o malé dětské chodidlo nebo velké chodidlo dospělého člověka, <u>korektor je vyroben tak, aby se pohodlně přizpůsobil všem velikostem chodidel</u>.</p>
      <p class="bun-stat"><span class="bun-check" aria-hidden="true">✔</span> <em>87 % uživatelů uvedlo <strong>viditelné zlepšení</strong> již v prvním měsíci</em></p>
    </div>
  </div>
</section>

<!-- ============ 3) Jak se používá (šedé, 3 kroky) ============ -->
<section class="bun-why bun-howto">
  <div class="bun-wrap">
    <h2 class="bun-howto-title">Jak se používá</h2>
    <div class="bun-howto-intro">
      <p>Doporučujeme začít 30 minutami denně a postupně zvyšovat až na relaci 1 až 3 hodiny.</p>
      <p>Až se budete cítit pohodlně, můžete ho začít nosit i během spánku každou noc.</p>
      <p>Nejlepší je pro klid — když ležíte na gauči, díváte se na TV, čtete nebo spíte.</p>
      <p>Ale na rozdíl od jiných produktů na trhu se můžete i pohybovat, aniž by vás NORIKS korektor omezoval v pohybu, díky své pohyblivé konstrukci.</p>
    </div>
    <div class="bun-steps-grid">
      <?php $bun_n = 0; foreach ( $bun_steps as $bun_step ) : $bun_n++; ?>
        <div class="bun-step">
          <div class="bun-step-media">
            <video src="<?php echo esc_url( $bun_step['video'] ); ?>" muted autoplay loop playsinline preload="metadata"></video>
          </div>
          <div class="bun-step-num"><?php echo (int) $bun_n; ?></div>
          <p class="bun-step-caption"><?php echo esc_html( $bun_step['caption'] ); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 4) 8 důvodů, proč si ho zamilujete ============ -->
<section class="bun-why">
  <div class="bun-wrap bun-row">
    <div class="bun-col bun-copy">
      <h2 class="bun-title">8 důvodů, proč si ho zamilujete</h2>
      <ul class="bun-reasons">
        <li><strong>Úleva od nepohodlí</strong> při chůzi, cvičení, stání a spánku</li>
        <li><strong>Zabraňuje</strong> dalšímu růstu vbočeného palce</li>
        <li><strong>Nechirurgická možnost</strong> úlevy</li>
        <li>Pevné srovnání kloubu, které <strong>skutečně zlepší váš stav</strong></li>
        <li><strong>Nastavitelná</strong> intenzita protažení</li>
        <li>Navržen a doporučen <strong>lékařskými odborníky</strong></li>
        <li><strong>Snadné použití</strong> a přenosný</li>
        <li><strong>30denní záruka vrácení peněz</strong> („výsledky nebo plné vrácení peněz"), protože jsme si svým produktem tak jistí a víme, že vám pomůže</li>
      </ul>
    </div>
    <div class="bun-col bun-media">
      <img loading="lazy" decoding="async" src="<?php echo esc_url( $bun_img_features ); ?>" alt="Proč je NORIKS korektor vbočeného palce jiný" />
    </div>
  </div>
</section>

<!-- ============ 5) Skutečné výsledky, skuteční lidé ============ -->
<section class="bun-why bun-results-sec">
  <div class="bun-wrap bun-row">
    <div class="bun-col bun-copy">
      <h2 class="bun-title">Skutečné <span class="bun-hl">výsledky</span>, skuteční lidé</h2>
      <p>Provedli jsme spotřebitelský test, ve kterém jsme NORIKS korektor vbočeného palce poslali do více než <strong>37 podiatrických ordinací</strong>. Celkem ho vyzkoušelo <strong>432 pacientů</strong> s vbočeným palcem. Zde jsou výsledky.</p>
    </div>
    <div class="bun-col">
      <div class="bun-results">
        <?php foreach ( $bun_results as $bun_r ) : $bun_dash = round( $bun_r['pct'] * 1.6336, 1 ); ?>
          <div class="bun-result">
            <svg class="bun-ring" viewBox="0 0 60 60" aria-hidden="true">
              <circle cx="30" cy="30" r="26" fill="none" stroke="#dfe6ee" stroke-width="5"/>
              <circle cx="30" cy="30" r="26" fill="none" stroke="#1a86d0" stroke-width="5" stroke-linecap="round"
                      stroke-dasharray="<?php echo esc_attr( $bun_dash ); ?> 163.4" transform="rotate(-90 30 30)"/>
              <text x="30" y="34" text-anchor="middle" class="bun-ring-txt"><?php echo (int) $bun_r['pct']; ?>%</text>
            </svg>
            <p class="bun-result-text"><?php echo esc_html( $bun_r['text'] ); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ============ 6) Proč si vybrat nás? (porovnávací tabulka, knc styl) ============ -->
<section class="bun-cmp-section">
  <div class="bun-cmp-wrap">
    <h2 class="bun-cmp-title">Proč si vybrat nás?</h2>
    <p class="bun-cmp-lead">Nenaleťte <span class="bun-hl">LEVNÝM napodobeninám</span></p>
    <p class="bun-cmp-sub">Jak se <strong>NORIKS korektor vbočeného palce</strong> srovnává s ostatními:</p>
    <div class="bun-cmp-scroll">
      <table class="bun-cmp-table">
        <thead>
          <tr>
            <th></th>
            <th class="bun-us">NORIKS</th>
            <th class="bun-comp">Ostatní korektory</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ( $bun_cmp as $bun_row ) : ?>
            <tr>
              <td><?php echo esc_html( $bun_row ); ?></td>
              <td class="us ok">✓</td>
              <td class="no">✕</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<style>
  /* Žádný odkaz "Tabulka velikostí" na korektoru vbočeného palce (ani plugin, ani globální). */
  .noriks-global-sizechart, .gck-size-link, .gck-size-link-wrap,
  #open-size-chart, #open-size-chartCustom { display: none !important; }

  /* Krátký popis (short description): skryj standardní odrážky (•), zůstane jen ✅;
     rozestup nad "Výhody:" a více prostoru pod seznamem.
     (Tato šablona se načte pouze na orto-bunion stránkách.) */
  .woocommerce-product-details__short-description ul {
      list-style: none;
      margin: 8px 0 26px;
      padding-left: 0;
  }
  .woocommerce-product-details__short-description ul li {
      list-style: none;
      padding-left: 0;
      margin-left: 0;
  }
  .woocommerce-product-details__short-description p:has(+ ul) {
      margin-top: 20px;
      margin-bottom: 4px;
  }

  .bun-why { padding: 44px 0; }
  .bun-why.bun-intro { background: #fbf9f4; }
  .bun-wrap { max-width: 1180px; margin: 0 auto; padding: 0 16px; }
  .bun-row { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; }
  .bun-media video { width: 100%; height: auto; border-radius: 12px; display: block; }
  .bun-title { font-size: clamp(24px,2.9vw,34px); font-weight: 800; color: #1c1c1c; line-height: 1.2; margin: 0 0 18px; }
  .bun-hl { color: #1a86d0; }
  .bun-red { color: #e0563f; }
  .bun-copy p { font-size: 16px; line-height: 1.7; color: #333; margin: 0 0 12px; }
  .bun-stat { display: flex; align-items: flex-start; gap: 8px; margin-top: 6px !important; }
  .bun-check { color: #1a86d0; font-weight: 800; }
  .bun-stat em { font-style: italic; color: #333; }

  /* section 2: media on the right */
  .bun-reverse .bun-media { order: 2; }
  .bun-reverse .bun-copy { order: 1; }

  /* 3) Jak se používá (šedé pozadí) */
  .bun-why.bun-howto { background: #f0f2f5; }
  .bun-howto-title { text-align: center; font-size: clamp(24px,2.9vw,34px); font-weight: 800; color: #1c1c1c; margin: 0 0 18px; }
  .bun-howto-intro { max-width: 820px; margin: 0 auto 34px; text-align: center; }
  .bun-howto-intro p { font-size: 16px; line-height: 1.6; color: #333; margin: 0 0 12px; }
  .bun-steps-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 26px; }
  .bun-step { text-align: center; }
  .bun-step-media { width: 100%; aspect-ratio: 1 / 1; border-radius: 14px; overflow: hidden; background: #e6e9ee; }
  .bun-step-media video { width: 100%; height: 100%; object-fit: cover; display: block; }
  .bun-step-num { font-size: 22px; font-weight: 800; color: #1c1c1c; margin: 14px 0 6px; }
  .bun-step-caption { font-size: 15px; line-height: 1.5; color: #333; margin: 0 8px; }

  /* 4) 8 důvodů */
  .bun-media img { width: 100%; height: auto; border-radius: 12px; display: block; }
  .bun-reasons { list-style: none; margin: 0; padding: 0; }
  .bun-reasons li { position: relative; padding: 0 0 16px 34px; font-size: 15.5px; line-height: 1.5; color: #333; }
  .bun-reasons li:before {
      content: ""; position: absolute; left: 0; top: 1px; width: 22px; height: 22px; border-radius: 50%;
      background: #1a86d0 url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><path d='M6 12.5l4 4 8-8' fill='none' stroke='white' stroke-width='2.6' stroke-linecap='round' stroke-linejoin='round'/></svg>") center/15px no-repeat;
  }

  /* 5) Skutečné výsledky */
  .bun-results { display: flex; flex-direction: column; gap: 18px; }
  .bun-result { display: flex; align-items: center; gap: 16px; border-bottom: 1px solid #e6e6e6; padding-bottom: 16px; }
  .bun-result:last-child { border-bottom: 0; padding-bottom: 0; }
  .bun-ring { width: 70px; height: 70px; flex: 0 0 70px; }
  .bun-ring-txt { font-size: 16px; font-weight: 800; fill: #1a86d0; }
  .bun-result-text { font-size: 14.5px; line-height: 1.5; color: #333; margin: 0; }

  /* 6) Proč si vybrat nás — porovnávací tabulka (stejný styl jako knc-table) */
  .bun-cmp-section { background:#fff; padding:44px 0; }
  .bun-cmp-wrap { max-width:940px; margin:0 auto; padding:0 16px; }
  .bun-cmp-title { text-align:center; font-size:clamp(24px,3vw,34px); font-weight:800; color:#111; margin:0 0 8px; }
  .bun-cmp-lead { text-align:center; font-size:18px; font-weight:800; color:#111; margin:0 0 6px; }
  .bun-cmp-sub { text-align:center; font-size:14px; color:#444; margin:0 0 24px; }
  .bun-cmp-scroll { border-radius:16px; overflow:hidden; box-shadow:0 12px 34px rgba(18,48,90,.12); border:1px solid #edf0f4; }
  .bun-cmp-table { width:100%; border-collapse:collapse; table-layout:fixed; margin:0 !important; }
  .bun-cmp-table th, .bun-cmp-table td { padding:15px 12px; text-align:center; font-size:15px; }
  .bun-cmp-table thead th { color:#fff; font-weight:700; vertical-align:middle; font-size:14px; }
  .bun-cmp-table thead th:first-child { width:52%; background:#fff; }
  .bun-cmp-table .bun-comp { background:#767676; }
  .bun-cmp-table .bun-us { background:#111; }
  .bun-cmp-table tbody td:first-child { text-align:left; font-weight:600; color:#111; font-size:14px; line-height:1.3; padding-left:18px; }
  .bun-cmp-table tbody tr { border-bottom:1px solid #eef0f4; }
  .bun-cmp-table tbody tr:nth-child(even) { background:#fafbfc; }
  .bun-cmp-table td.ok { color:#1a9e5f; font-size:19px; font-weight:700; }
  .bun-cmp-table td.no { color:#d64545; font-size:18px; font-weight:700; }
  .bun-cmp-table td.us { background:#f3f3f3 !important; }
  .bun-cmp-table td.us.ok { color:#1a9e5f; }
  @media (max-width:600px) {
    .bun-cmp-table th, .bun-cmp-table td { padding:12px 6px; font-size:13px; }
    .bun-cmp-table thead th { font-size:12px; }
    .bun-cmp-table tbody td:first-child { font-size:12px; padding-left:10px; }
  }

  @media (max-width: 820px) {
    .bun-row { grid-template-columns: 1fr; gap: 22px; }
    .bun-reverse .bun-media { order: 0; }
    .bun-reverse .bun-copy { order: 0; }
    .bun-steps-grid { grid-template-columns: 1fr; gap: 18px; }
  }
</style>
