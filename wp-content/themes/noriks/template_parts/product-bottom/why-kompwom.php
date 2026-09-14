<?php
/**
 * product-bottom: NORIKS FIT Woman — oblikujuca majica s 3D linijama (orto-kompwom).
 * Pravilo: svaka sekcija ima TOCNO JEDNU sliku, naizmjenicno lijevo/desno.
 * Recenzije su preslikane s originala (leonieandco): bordo pas + vodoravni klizac.
 *   1) Zagladen trbuh (lijevo)     5) Tkanina i kroj (desno)
 *   2) Mi vs drugi (desno)         6) Kako je nositi (lijevo)
 *   3) RECENZIJE — klizac          7) Boje (desno)
 *   4) Osjecajte se sigurno (lijevo)
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$kw      = get_template_directory_uri() . '/img/kompwom/';
$kw_path = get_template_directory() . '/img/kompwom/';
$kw_img  = function( $file, $alt, $cls = '' ) use ( $kw, $kw_path ) {
  if ( ! file_exists( $kw_path . $file ) ) { return ''; }
  return '<img class="' . esc_attr( $cls ) . '" src="' . esc_url( $kw . $file ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
};
?>

<!-- 1) ZAGLAĐEN TRBUH — slika lijevo -->
<section class="nkw-sec nkw-tint">
  <div class="nkw-wrap nkw-row2">
    <div class="nkw-media"><?php echo $kw_img( 'kwm-trbuh.jpg', 'Vyhlazené břicho v tričku NORIKS FIT Woman' ); ?></div>
    <div class="nkw-copy">
      <p class="nkw-kicker">Hned, od první minuty</p>
      <h2 class="nkw-h2">Vyhlazené břicho <em>bez stlačování</em></h2>
      <p>Naše 3D linie formují tělo tak, že jemně stáhnou oblast břicha a boků a podporují cirkulaci — bez pásu, který se zařezává, a bez pocitu stažení.</p>
      <p>Bez trička je břicho ochablé a záhyby jsou vidět pod oblečením. S ním je břicho hned vyhlazené a držení vzpřímenější.</p>
      <ul class="nkw-check">
        <li>Hned vyhlazené břicho</li>
        <li>Vzpřímené držení bez přemýšlení</li>
        <li>Neviditelné pod oblečením</li>
      </ul>
      <a class="nkw-cta" href="#bundle-selector">Vyberte barvu a velikost</a>
    </div>
  </div>
</section>

<!-- 2) MI VS DRUGI — slika desno -->
<section class="nkw-sec">
  <div class="nkw-wrap nkw-row2 nkw-row2--rev">
    <div class="nkw-copy">
      <p class="nkw-kicker">Rozdíl</p>
      <h2 class="nkw-h2">Proč běžná komprese nefunguje</h2>
      <p>Klasická stahovací trička tlačí v jedné linii. Výsledkem je vyboulení nad okrajem, záhyby na ramenou a okraj, který je vidět pod oblečením.</p>
      <p>U nás je reliéf <strong>vpletený do tkaniny</strong> a rozložený do šířky, takže se tlak rozlije místo toho, aby se nasbíral.</p>
      <ul class="nkw-vs">
        <li class="is-yes">3D technologie vpletená do úpletu</li>
        <li class="is-yes">Jemná 360° komprese bez vyboulení</li>
        <li class="is-yes">Podpírá bedra</li>
        <li class="is-no">Běžná komprese, která vytváří vyboulení</li>
        <li class="is-no">Materiál se během dne roluje</li>
        <li class="is-no">Okraj, který je vidět pod oblečením</li>
      </ul>
    </div>
    <div class="nkw-media"><?php echo $kw_img( 'kwm-usporedba.jpg', 'NORIKS FIT Woman ve srovnání s běžným stahovacím tričkem' ); ?></div>
  </div>
</section>

<!-- 3) OSJEĆAJTE SE SIGURNO — slika lijevo -->
<section class="nkw-rev">
  <div class="nkw-rev__head">
    <span class="nkw-rev__badge">★★★★★ Vynikající · Hodnocení 4,9/5</span>
    <h2 class="nkw-rev__title">Recenze žen, jako jste vy</h2>
  </div>
  <div class="nkw-rev__track">
    <?php
    $kw_reviews = array(
      array( 'img' => 'kwm-ugc-1.jpg', 'name' => 'Karolína B.', 'meta' => 'Velikost M · 46 · Praha', 'worn' => 'Nosí 7 týdnů',
             'text' => '„Košile mi sedí rovně, zepředu i zezadu. Ty, které jsem zkoušela předtím, nevydržely ani jeden celý den.”' ),
      array( 'img' => 'kwm-ugc-2.jpg', 'name' => 'Daniela P.', 'meta' => 'Velikost 2XL · 48 · Brno', 'worn' => 'Nosí 5 týdnů',
             'text' => '„První stahovací tričko, které se mi neroluje nahoru. Okraj drží a látka je dost tenká na léto.”' ),
      array( 'img' => 'kwm-ugc-3.jpg', 'name' => 'Hana T.', 'meta' => 'Velikost 3XL · 51 · Ostrava', 'worn' => 'Nosí 6 týdnů',
             'text' => '„Koupila jsem ho na svatbu a teď ho nosím do práce. Sezení už nemění to, jak šaty padnou.”' ),
      array( 'img' => 'kwm-ugc-4.jpg', 'name' => 'Laura D.', 'meta' => 'Velikost M · 43 · Plzeň', 'worn' => 'Nosí 8 týdnů',
             'text' => '„Obléknu si ho po snídani a zapomenu, že ho mám. Do poledne si ho vůbec nevšimnu.”' ),
      array( 'img' => 'kwm-ugc-5.jpg', 'name' => 'Natálie A.', 'meta' => 'Velikost 2XL · 37 · Olomouc', 'worn' => 'Nosí 9 týdnů',
             'text' => '„Nikdy mi to neslušelo, když jsem si tričko zastrčila do kalhot. S tímhle pod tím vypadá pas užší a celý den se neupravuji.”' ),
      array( 'img' => 'kwm-ugc-6.jpg', 'name' => 'Nikola M.', 'meta' => 'Velikost L · 48 · Liberec', 'worn' => 'Nosí 4 týdny',
             'text' => '„Objednala jsem si ho na jeden outfit a skončilo pod většinou svetrů. Úplet vypadá hladce místo pomačkaně v pase.”' ),
      array( 'img' => 'kwm-ugc-7.jpg', 'name' => 'Petra J.', 'meta' => 'Velikost XL · 50 · Hradec Králové', 'worn' => 'Nosí 6 týdnů',
             'text' => '„U stolu sedím devět hodin denně a zůstává pohodlné. Nejsou tam švy, které by byly cítit, a halenka vzadu zůstává hladká.”' ),
      array( 'img' => 'kwm-ugc-8.jpg', 'name' => 'Sofie K.', 'meta' => 'Velikost M · 45 · Zlín', 'worn' => 'Nosí 10 týdnů',
             'text' => '„Záda jsou mi vděčná. Tričko mi jemně připomíná, abych se narovnala, a přitom mě nikde nestahuje.”' ),
    );
    foreach ( $kw_reviews as $r ) : ?>
    <article class="nkw-rev__card">
      <div class="nkw-rev__img"><?php echo $kw_img( $r['img'], 'Kupka u NORIKS FIT Woman majici' ); ?></div>
      <div class="nkw-rev__body">
        <div class="nkw-rev__top">
          <div>
            <p class="nkw-rev__name"><?php echo esc_html( $r['name'] ); ?>
              <svg width="15" height="15" viewBox="0 0 16 16" aria-hidden="true"><circle cx="8" cy="8" r="8" fill="#3aa06a"/><path d="M5 8l2 2 4-4" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </p>
            <p class="nkw-rev__meta"><?php echo esc_html( $r['meta'] ); ?></p>
          </div>
          <span class="nkw-rev__worn"><?php echo esc_html( $r['worn'] ); ?></span>
        </div>
        <p class="nkw-rev__text"><?php echo esc_html( $r['text'] ); ?></p>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
  <p class="nkw-rev__hint">Přetáhněte do strany pro další recenze →</p>
</section>

<!-- 7) BOJE — slika desno -->
<section class="nkw-sec nkw-tint">
  <div class="nkw-wrap nkw-row2">
    <div class="nkw-media"><?php echo $kw_img( 'kwm-drzanje.jpg', 'Užší pas a vzpřímené držení' ); ?></div>
    <div class="nkw-copy">
      <p class="nkw-kicker">Tři věci naráz</p>
      <h2 class="nkw-h2">Ciťte se <em>jistě ve své kůži</em></h2>
      <div class="nkw-points">
        <div class="nkw-point"><h3>Užší pas</h3><p>3D linie formují pas a vyhlazují vyboulení nad kalhotami nebo sukní.</p></div>
        <div class="nkw-point"><h3>Hned ploché břicho</h3><p>Jemná komprese drží břicho pod jakýmkoli oblečením, bez tlaku do jednoho bodu.</p></div>
        <div class="nkw-point"><h3>Vzpřímené držení</h3><p>Opora na zádech pomáhá stát vzpřímeně a odlehčuje bedra.</p></div>
      </div>
    </div>
  </div>
</section>

<!-- 4) TKANINA I KROJ — slika desno -->
<section class="nkw-sec">
  <div class="nkw-wrap nkw-row2 nkw-row2--rev">
    <div class="nkw-copy">
      <p class="nkw-kicker">Látka a střih</p>
      <h2 class="nkw-h2">Reliéf je <em>vpletený</em>, ne potištěný</h2>
      <p>Široký pás přechází přes břicho a boky, druhý jde přes záda. Proto nic nepraská a nic se neodlupuje.</p>
      <div class="nkw-facts">
        <div><h3>3D linie</h3><p>Strukturované, vpletené do úpletu — časem neodpadnou.</p></div>
        <div><h3>Pás na zádech</h3><p>Druhý pás přechází přes záda a podporuje vzpřímené držení.</p></div>
        <div><h3>Rukávy</h3><p>Přiléhavý střih, který se neroluje a nestahuje nahoru.</p></div>
        <div><h3>Materiál</h3><p>Tenký, matný a prodyšný — zmizí pod košilí nebo sakem.</p></div>
      </div>
    </div>
    <div class="nkw-media"><?php echo $kw_img( 'kwm-detalji.jpg', 'Detaily: 3D linie, pás, rukávy, materiál' ); ?></div>
  </div>
</section>

<!-- 5) KAKO JE NOSITI — slika lijevo -->
<section class="nkw-sec nkw-tint">
  <div class="nkw-wrap nkw-row2">
    <div class="nkw-media"><?php echo $kw_img( 'kwm-siva.jpg', 'NORIKS FIT Woman tmavě šedá' ); ?></div>
    <div class="nkw-copy">
      <p class="nkw-kicker">Jak ho nosit</p>
      <h2 class="nkw-h2">Oblečte si ho ráno a zapomeňte na něj do večera</h2>
      <p>Musíte normálně dýchat a jíst, aniž byste myslela na tričko. Pokud je stopa na kůži vidět dvacet minut po svlečení, velikost je příliš malá.</p>
      <ul class="nkw-check">
        <li><strong>Celý den</strong> — komprese je rozložená, takže se nic nezařezává</li>
        <li><strong>Pod všechno</strong> — bez linie a bez okraje pod oblečením</li>
        <li><strong>Jednoduchá péče</strong> — praní v pračce na 30 °C</li>
      </ul>
      <p class="nkw-note nkw-note--left">Velikost vybírejte podle obvodu hrudníku. Pokud jste mezi dvěma, vezměte větší.</p>
    </div>
  </div>
</section>

<!-- 6) RECENZIJE (postavitev z originala — bordo pas z drsnikom) -->
<section class="nkw-sec">
  <div class="nkw-wrap nkw-row2 nkw-row2--rev">
    <div class="nkw-copy">
      <p class="nkw-kicker">Tři barvy</p>
      <h2 class="nkw-h2">Černá, tmavě šedá a <em>růžová</em></h2>
      <p>Černá pod všechno, tmavě šedá na běžné nošení, růžová, když chcete něco teplejšího. Všechny tři mají stejný úplet a stejný reliéf.</p>
      <p>Barvu a velikost vybíráte na této stránce, před přidáním do košíku.</p>
      <a class="nkw-cta" href="#bundle-selector">Vyberte barvu a velikost</a>
    </div>
    <div class="nkw-media"><?php echo $kw_img( 'kwm-roza.jpg', 'NORIKS FIT Woman růžová' ); ?></div>
  </div>
</section>

<style>
.nkw-sec { padding: 62px 0; background: #fff; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; color: #241c22; }
.nkw-sec * { box-sizing: border-box; }
.nkw-tint { background: #fbf3f4; }
.nkw-wrap { width: 100%; max-width: 1240px; margin: 0 auto; padding: 0 24px; }
.nkw-kicker { font-size: 12.5px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; color: #a8536b; margin: 0 0 10px; }
.nkw-h2 { font-size: clamp(25px, 3.2vw, 36px); font-weight: 800; line-height: 1.18; letter-spacing: -.01em; margin: 0 0 16px; color: #241c22; }
.nkw-h2 em { font-style: italic; font-weight: 800; color: #a8536b; }
.nkw-copy p { font-size: 16px; line-height: 1.7; color: #56494f; margin: 0 0 14px; }
.nkw-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 52px; align-items: center; }
.nkw-media img { width: 100%; display: block; border-radius: 14px; box-shadow: 0 2px 4px rgba(36,28,34,.05), 0 14px 40px rgba(36,28,34,.10); }
.nkw-check { list-style: none; padding: 0; margin: 4px 0 22px; display: flex; flex-direction: column; gap: 11px; }
.nkw-check li { position: relative; padding-left: 28px; font-size: 15.5px; line-height: 1.5; }
.nkw-check li::before { content: "\2713"; position: absolute; left: 0; top: -1px; width: 20px; height: 20px; border-radius: 50%; background: #2f9e5f; color: #fff; font-size: 11px; font-weight: 800; display: flex; align-items: center; justify-content: center; }
.nkw-vs { list-style: none; padding: 0; margin: 4px 0 0; display: flex; flex-direction: column; gap: 10px; }
.nkw-vs li { position: relative; padding-left: 28px; font-size: 15px; line-height: 1.5; }
.nkw-vs li::before { position: absolute; left: 0; top: -1px; width: 20px; height: 20px; border-radius: 50%; font-size: 11px; font-weight: 800; display: flex; align-items: center; justify-content: center; }
.nkw-vs .is-yes::before { content: "\2713"; background: #2f9e5f; color: #fff; }
.nkw-vs .is-no { color: #8b7b83; }
.nkw-vs .is-no::before { content: "\2715"; background: #ece0e4; color: #a8949c; }
.nkw-points { display: flex; flex-direction: column; gap: 20px; }
.nkw-point h3 { font-size: 17.5px; font-weight: 800; margin: 0 0 6px; color: #a8536b; }
.nkw-point p { font-size: 15.5px; color: #56494f; line-height: 1.6; margin: 0; }
.nkw-facts { display: grid; grid-template-columns: 1fr 1fr; gap: 22px 26px; margin-top: 6px; }
.nkw-facts h3 { font-size: 16px; font-weight: 800; margin: 0 0 6px; color: #241c22; }
.nkw-facts p { font-size: 14.5px; color: #6b5f66; line-height: 1.6; margin: 0; }
.nkw-note { font-size: 13.5px; color: #8b7b83; font-style: italic; margin: 20px 0 0; }
.nkw-note--left { text-align: left; }
.nkw-cta { display: inline-block; background: #241c22; color: #fff !important; font-size: 15px; font-weight: 700; padding: 15px 30px; border-radius: 8px; text-decoration: none; }
.nkw-cta:hover { background: #a8536b; color: #fff !important; }

/* ── recenzije: bordo pas + vodoravni klizac (kot na originalu) ────── */
.nkw-rev { background: #5c2331; padding: 62px 0 54px; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; }
.nkw-rev * { box-sizing: border-box; }
.nkw-rev__head { max-width: 1240px; margin: 0 auto 30px; padding: 0 24px; text-align: center; }
.nkw-rev__badge { display: inline-block; border: 1px solid rgba(255,255,255,.45); border-radius: 100px; padding: 8px 18px; font-size: 12.5px; font-weight: 700; letter-spacing: .04em; color: #fff; }
.nkw-rev__title { font-family: Georgia, 'Times New Roman', serif; font-size: clamp(27px, 3.4vw, 40px); font-weight: 400; color: #fff; margin: 16px 0 0; line-height: 1.2; }
.nkw-rev__track { display: flex; gap: 20px; overflow-x: auto; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;
  padding: 4px 24px 18px; margin: 0 auto; max-width: 1240px; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.4) transparent; }
.nkw-rev__track::-webkit-scrollbar { height: 6px; }
.nkw-rev__track::-webkit-scrollbar-track { background: rgba(255,255,255,.12); border-radius: 100px; }
.nkw-rev__track::-webkit-scrollbar-thumb { background: rgba(255,255,255,.45); border-radius: 100px; }
.nkw-rev__card { flex: 0 0 310px; width: 310px; scroll-snap-align: start; background: #fff; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; }
.nkw-rev__img img { width: 100%; aspect-ratio: 1/1; object-fit: cover; display: block; }
.nkw-rev__body { padding: 16px 18px 20px; }
.nkw-rev__top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
.nkw-rev__name { display: flex; align-items: center; gap: 6px; font-size: 15px; font-weight: 800; color: #241c22; margin: 0; }
.nkw-rev__name svg { flex: 0 0 15px; }
.nkw-rev__meta { font-size: 12.5px; color: #7b6d73; margin: 3px 0 0; }
.nkw-rev__worn { flex: 0 0 auto; background: #f3eaed; color: #5c2331; font-size: 10.5px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; padding: 5px 9px; border-radius: 100px; white-space: nowrap; }
.nkw-rev__text { font-size: 14.5px; line-height: 1.6; color: #46393f; margin: 13px 0 0; }
.nkw-rev__hint { text-align: center; font-size: 12.5px; color: rgba(255,255,255,.6); margin: 8px 0 0; }

@media (max-width: 980px) {
  .nkw-row2 { grid-template-columns: 1fr; gap: 30px; }
  .nkw-row2--rev .nkw-media { order: -1; }
  .nkw-facts { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 560px) {
  .nkw-sec { padding: 44px 0; }
  .nkw-wrap { padding: 0 16px; }
  .nkw-facts { grid-template-columns: 1fr; gap: 16px; }
  .nkw-cta { width: 100%; text-align: center; }
  .nkw-rev { padding: 46px 0 40px; }
  .nkw-rev__head { padding: 0 16px; }
  .nkw-rev__track { padding: 4px 16px 16px; gap: 14px; }
  .nkw-rev__card { flex: 0 0 300px; width: 300px; }
}

/* ── kratek opis izdelka: kljukice namesto pik ─────────────────────── */
.woocommerce div.product .woocommerce-product-details__short-description ul,
.woocommerce-product-details__short-description ul {
  list-style: none !important; margin: 10px 0 14px !important; padding-left: 0 !important; }
.woocommerce div.product .woocommerce-product-details__short-description ul li,
.woocommerce-product-details__short-description ul li {
  list-style: none !important; text-indent: 0 !important; margin: 0 0 7px !important;
  line-height: 1.45 !important; font-size: 15.5px !important;
  display: block !important; position: relative !important; padding-left: 31px !important; }
.woocommerce-product-details__short-description ul li::marker { content: "" !important; }
.woocommerce-product-details__short-description ul li::before { content: none !important; }
.woocommerce-product-details__short-description .nsg-tick {
  position: absolute !important; left: 0 !important; top: 1px !important;
  width: 21px; height: 21px; border-radius: 50%;
  background: #2f9e5f !important; color: #fff !important;
  font-size: 12px !important; font-weight: 800 !important; line-height: 21px !important;
  text-align: center !important; display: inline-block !important; }
.woocommerce-product-details__short-description p:first-of-type { font-size: 16px; line-height: 1.55; }
</style>
