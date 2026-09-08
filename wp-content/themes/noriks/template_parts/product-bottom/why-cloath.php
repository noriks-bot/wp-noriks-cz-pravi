<?php
/**
 * product-bottom: Polar NORIKS Cloth XXL — krpa za ciscenje (orto-cloath).
 *
 * Broj i redoslijed sekcija preslikani s referentne stranice (6 sekcija):
 *   1. A Crystal-Clear Shower in Under a Minute   animacija cl-anim-1
 *   2. Your Mirror, Perfect in Seconds            animacija cl-anim-2
 *   3. Keep Your Bathroom Sparkling               slika 09_zena_drzi_krpu
 *   4. Holds Up To 4X Its Weight In Water         animacija cl-anim-3
 *   5. Lint-Free. Tough. Built to Last.           animacija cl-anim-4
 *   6. 60-Day Guarantee                           slika 10_zena_lice
 * Recenzije i FAQ renderira zajednicki reviews.php (ne ovdje).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$cl      = get_template_directory_uri() . '/img/cloath/';
$cl_path = get_template_directory() . '/img/cloath/';

$cl_img = function( $file, $alt ) use ( $cl, $cl_path ) {
  if ( file_exists( $cl_path . $file ) ) {
    return '<img src="'.esc_url($cl.$file).'" alt="'.esc_attr($alt).'" loading="lazy">';
  }
  return '<div class="ncl-ph" role="img" aria-label="'.esc_attr($alt).'"><span>'.esc_html($alt).'</span></div>';
};
$cl_anim = function( $mp4, $fallback, $alt ) use ( $cl, $cl_path, $cl_img ) {
  if ( file_exists( $cl_path . $mp4 ) ) {
    $poster = str_replace( '.mp4', '-poster.webp', $mp4 );
    return '<video class="ncl-video" src="'.esc_url($cl.$mp4).'" poster="'.esc_url($cl.$poster).'"'
         . ' autoplay muted loop playsinline preload="metadata" aria-label="'.esc_attr($alt).'"></video>';
  }
  return $cl_img( $fallback, $alt );
};
?>

<!-- ============ 1) Kristalno cist tus u manje od minute ============ -->
<section class="ncl-sec ncl-alt">
  <div class="ncl-wrap ncl-row2">
    <div class="ncl-media"><?php echo $cl_anim('cl-anim-1.mp4','cl-01-hero-3plus3.webp','Čištění sprchového koutu hadříkem Polar NORIKS Cloth'); ?></div>
    <div class="ncl-copy">
      <h2 class="ncl-h2">Křišťálově čistý sprchový kout za méně než minutu — bez chemie, jen voda</h2>
      <p>Pořád bojujete se skvrnami od vody, vodním kamenem a mýdlovým povlakem, i po stěrce nebo levném hadříku z obchodu?</p>
      <p>Proto jsme vytvořili <strong>Polar NORIKS Cloth</strong> — pro všechny, kdo mají dost drhnutí a utrácení za spreje.</p>
      <p>Pár rychlých tahů a sklo, obklady i baterie zůstanou bezchybné. Bez agresivních prostředků, bez šmouh, bez nervů.</p>
    </div>
  </div>
</section>

<!-- ============ 2) Ogledalo savrseno u nekoliko sekundi ============ -->
<section class="ncl-sec">
  <div class="ncl-wrap ncl-row2">
    <div class="ncl-copy">
      <h2 class="ncl-h2">Dokonalé zrcadlo za pár sekund — bez šmouh a bez námahy</h2>
      <p>Máte dost skvrn od vody, kapek a zamlžení, které zanechávají šmouhy nebo vlákna?</p>
      <p>Hustá pletená vrstva sebere nečistoty a vodu jedním tahem, takže zrcadlo zůstane křišťálově čisté — bez sprejů a bez rozmazávání.</p>
      <p class="ncl-strong">Bezchybný lesk pokaždé a hadřík, který to vydrží praní za praním.</p>
    </div>
    <div class="ncl-media"><?php echo $cl_anim('cl-anim-2.mp4','cl-08-dvostrani.webp','Utírání zrcadla bez šmouh'); ?></div>
  </div>
</section>

<!-- ============ 3) Kupaonica koja blista ============ -->
<section class="ncl-sec ncl-alt">
  <div class="ncl-wrap ncl-row2">
    <div class="ncl-media"><?php echo $cl_img('cl-09-zena-krpa.webp','Koupelna vyčištěná hadříkem Polar NORIKS Cloth'); ?></div>
    <div class="ncl-copy">
      <h2 class="ncl-h2">Zářící koupelna bez zvednutí prstu</h2>
      <p>Čisté sklo, lesklé baterie, bezchybné zrcadlo — bez drhnutí, bez sprejů, bez stresu.</p>
      <p>Silnější a hustší vlákna seberou vodní kámen, nečistoty a skvrny od vody na pár tahů — jen s vodou.</p>
      <ul class="ncl-check">
        <li>Skleněné a terasové dveře</li>
        <li>Zrcadla a velká okna</li>
        <li>Obklady, baterie a pracovní desky</li>
      </ul>
      <a class="ncl-cta" href="#bundle-selector">Vyberte si balíček</a>
    </div>
  </div>
</section>

<!-- ============ 4) Upija do 4x svoje tezine ============ -->
<section class="ncl-sec">
  <div class="ncl-wrap ncl-row2">
    <div class="ncl-copy">
      <h2 class="ncl-h2">Nasaje až 4× svou hmotnost ve vodě a povrchy nechá suché</h2>
      <p>Většina hadříků vodu jen rozetře. Polar NORIKS Cloth nasaje <strong>až 600 ml</strong> najednou — téměř celou láhev vody.</p>
      <p>Pár tahů a sprchový kout, obklady i baterie jsou suché. Bez šmouh, bez skvrn, bez čekání.</p>
      <p class="ncl-strong">Výsledek: koupelna, která zůstane čistá déle, bez nepořádku.</p>
    </div>
    <div class="ncl-media"><?php echo $cl_anim('cl-anim-3.mp4','cl-07-dimenzije.webp','Nasávání vody — až 600 ml najednou'); ?></div>
  </div>
</section>

<!-- ============ 5) Bez vlakana, izdrzljiva ============ -->
<section class="ncl-sec ncl-alt">
  <div class="ncl-wrap ncl-row2">
    <div class="ncl-media"><?php echo $cl_anim('cl-anim-4.mp4','cl-02-stack.webp','Hadřík po stovkách praní'); ?></div>
    <div class="ncl-copy">
      <h2 class="ncl-h2">Bez vláken. Odolný. Vydrží praní za praním.</h2>
      <p>Hadřík je vyrobený na stovky použití. Hoďte ho do pračky a je zase připravený.</p>
      <p>Na rozdíl od běžných hadříků <strong>nepouští vlákna</strong> — žádné chloupky, žádné šmouhy, žádné nervy.</p>
      <ul class="ncl-check">
        <li>Nevybledne a netřepí se</li>
        <li>Pratelný v pračce na 40 °C</li>
        <li>Oboustranný design: myje a leští</li>
      </ul>
    </div>
  </div>
</section>

<!-- ============ 6) 30 dní jamstva ============ -->
<section class="ncl-sec">
  <div class="ncl-wrap ncl-row2">
    <div class="ncl-copy">
      <p class="ncl-eyebrow">30 dní bez rizika</p>
      <h2 class="ncl-h2">Platíte, jen když se vám bude líbit</h2>
      <p>Pořád pochybujete? Rozumíme — zní to příliš dobře, než aby to byla pravda.</p>
      <p>Proto si hadřík můžete vyzkoušet zcela bez rizika <strong>30 dní</strong>. Pokud sklo nebude křišťálově čisté, pokud se obklady nebudou udržovat snáz nebo se vám výsledek jednoduše nelíbí — pošlete ho zpět.</p>
      <p class="ncl-strong">Buď dostanete koupelnu, která září jako nová, nebo dostanete peníze zpět.</p>
      <a class="ncl-cta" href="#bundle-selector">Objednejte bez rizika</a>
    </div>
    <div class="ncl-media"><?php echo $cl_img('cl-10-zena-lice.webp','30denní záruka vrácení peněz'); ?></div>
  </div>
</section>

<style>
  .ncl-sec { padding: 46px 0; background: #fff; }
  .ncl-alt { background: #f1f4ef; }
  .ncl-wrap { max-width: 1180px; margin: 0 auto; padding: 0 18px; }
  .ncl-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; }
  .ncl-h2 { font-size: clamp(24px,3.1vw,34px); font-weight: 800; color: #2b4636; line-height: 1.2; margin: 0 0 16px; }
  .ncl-eyebrow { font-size: 12.5px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; color: #6f8f74; margin: 0 0 8px; }
  .ncl-copy p { font-size: 16px; line-height: 1.7; color: #3a3a3a; margin: 0 0 14px; }
  .ncl-strong { font-weight: 700; color: #2b4636 !important; }
  .ncl-media img, .ncl-video { width: 100%; height: auto; display: block; border-radius: 16px; }

  .ncl-ph { width: 100%; aspect-ratio: 1/1; background: #e8eee7; border: 1px dashed #cfdccd; border-radius: 16px;
            display: flex; align-items: center; justify-content: center; padding: 18px; box-sizing: border-box; }
  .ncl-ph span { font-size: 13px; line-height: 1.45; color: #8ba38f; text-align: center; }

  .ncl-check { list-style: none; margin: 0 0 16px; padding: 0; }
  .ncl-check li { position: relative; padding: 0 0 11px 30px; font-size: 15.5px; color: #2b4636; line-height: 1.5; }
  .ncl-check li:before { content: "✓"; position: absolute; left: 0; top: 0; width: 20px; height: 20px; background: #6f8f74; color: #fff; border-radius: 50%; font-size: 12px; text-align: center; line-height: 20px; }

  .ncl-cta { display: inline-block; margin-top: 8px; background: #2b4636; color: #fff; font-weight: 700; font-size: 16px; padding: 14px 30px; border-radius: 10px; text-decoration: none; }
  .ncl-cta:hover { background: #6f8f74; color: #fff; }

  @media (max-width: 820px) {
    .ncl-sec { padding: 30px 0; }
    .ncl-row2 { grid-template-columns: 1fr; gap: 20px; }
    .ncl-row2 .ncl-media { order: -1; }
    .ncl-h2 { font-size: 1.85rem; }
    /* tema vec ima svoj razmak na kontejneru — nas prepolovimo */
    .ncl-wrap { padding: 0 9px !important; }
  }

  /* Krpa nema velicina — bez linka na tablicu velicina. */
  .noriks-global-sizechart, .gck-size-link, .gck-size-link-wrap,
  #open-size-chart, #open-size-chartCustom { display: none !important; }

  /* Kratki opis: zelene kvacice, a prelomljeni redak pocinje ispod teksta (viseci uvlak). */
  .woocommerce-product-details__short-description ul { list-style: none; margin: 8px 0 14px; padding-left: 0; }

  /* Razmak iznad i ispod cijene izjednacen. */
  .single-product div.product .summary .price,
  .single-product div.product .summary p.price { margin: 14px 0 14px !important; }
  .woocommerce-product-details__short-description ul li {
      list-style: none; margin-left: 0; line-height: 1.55; margin-bottom: 8px;
      padding-left: 17px; text-indent: -17px;
  }
  .woocommerce-product-details__short-description .ncl-tick {
      display: inline-block; width: 17px; text-indent: 0; color: #3f8b57; font-weight: 800;
  }
  .woocommerce-product-details__short-description p:has(+ ul) { margin-top: 20px; margin-bottom: 4px; }

  /* CTA gumb na sredini sekcije. */
  .ncl-copy .ncl-cta { display: block; width: max-content; margin-left: auto; margin-right: auto; }
</style>

<script>
(function(){
  document.querySelectorAll('a.ncl-cta[href="#bundle-selector"]').forEach(function(a){
    a.addEventListener('click', function(e){
      e.preventDefault();
      var t = document.getElementById('bundle-selector') || document.querySelector('.single_add_to_cart_button');
      if (t) t.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
  });
})();
</script>
