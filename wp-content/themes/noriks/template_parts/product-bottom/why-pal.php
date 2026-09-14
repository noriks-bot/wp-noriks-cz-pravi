<?php
/**
 * product-bottom: NORIKS Pal — stap s dvije rucke, svjetiljkom i alarmom (orto-pal).
 * Pravilo: svaka sekcija ima TOCNO JEDNU sliku, naizmjenicno lijevo/desno,
 * pozadine se izmjenjuju (prva tonirana). Iznimka je galerija kupaca (12).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$pl      = get_template_directory_uri() . '/img/pal/';
$pl_path = get_template_directory() . '/img/pal/';
$pl_vid  = function( $file, $poster, $alt ) use ( $pl, $pl_path ) {
  if ( ! file_exists( $pl_path . $file ) ) { return ''; }
  return '<video class="npl-video" autoplay muted loop playsinline preload="metadata" poster="'
       . esc_url( $pl . $poster ) . '" aria-label="' . esc_attr( $alt ) . '">'
       . '<source src="' . esc_url( $pl . $file ) . '" type="video/mp4"></video>';
};
$pl_img  = function( $file, $alt, $cls = '' ) use ( $pl, $pl_path ) {
  if ( ! file_exists( $pl_path . $file ) ) { return ''; }
  return '<img class="' . esc_attr( $cls ) . '" src="' . esc_url( $pl . $file ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
};
?>

<!-- 1) USTAJANJE -->
<!-- 1) PROBLEM — slika lijevo -->
<section class="npl-sec npl-tint">
  <div class="npl-wrap npl-row2">
    <div class="npl-media"><?php echo $pl_img( 'pal-rucke.jpg', 'Ortopedické rukojeti k opoře' ); ?></div>
    <div class="npl-copy">
      <p class="npl-kicker">Druhá rukojeť</p>
      <h2 class="npl-h2">Opora přesně tam, kde ji potřebujete</h2>
      <p>Kromě horní rukojeti má hůl i <strong>druhou rukojeť níž</strong>. Té se chytíte, když vstáváte z křesla, z postele nebo z nízké židle.</p>
      <p>Tlak jde svisle dolů, do stabilní základny — ne dopředu, kde by vás vytáhl z rovnováhy. Proto vstanete jedním pohybem, bez předklánění a bez cizí pomoci.</p>
      <ul class="npl-check">
        <li>Vstávání z křesla, postele nebo z lavičky</li>
        <li>Rukojeti neodírají dlaň ani po delší chůzi</li>
        <li>Bez čekání, až vám někdo pomůže</li>
      </ul>
      <a class="npl-cta" href="#bundle-selector">Podívejte se na nabídku</a>
    </div>
  </div>
</section>

<!-- 3) PREGLED ŠTAPA — slika lijevo -->
<section class="npl-sec">
  <div class="npl-wrap npl-row2 npl-row2--rev">
    <div class="npl-copy">
      <p class="npl-kicker">Stabilita</p>
      <h2 class="npl-h2">Stojí sama — nemusíte se pro hůl ohýbat</h2>
      <p>Čtyři gumové nožičky drží hůl vzpřímeně, když ji pustíte. Nespadne na zem u gauče, u stolu ani v čekárně, takže se pro ni nemusíte ohýbat.</p>
      <p>To je maličkost, které si všimnete hned první den: hůl na vás čeká tam, kde jste ji nechali.</p>
    </div>
    <div class="npl-media"><?php echo $pl_vid( 'pal-video.mp4', 'pal-video.jpg', 'Hůl stojí sama na čtyřech nožičkách' ); ?></div>
  </div>
</section>

<!-- 5) PROTUKLIZNA BAZA — slika lijevo -->
<section class="npl-sec npl-tint">
  <div class="npl-wrap npl-row2">
    <div class="npl-media"><?php echo $pl_img( 'pal-pregled.jpg', 'Přehled hole: dvě rukojeti, svítilna, alarm, čtyři nožičky' ); ?></div>
    <div class="npl-copy">
      <p class="npl-kicker">Co dostanete</p>
      <h2 class="npl-h2">Pět věcí v jedné holi</h2>
      <div class="npl-points">
        <div class="npl-point"><h3>Dvě rukojeti</h3><p>Horní na chůzi, dolní na vstávání.</p></div>
        <div class="npl-point"><h3>Čtyři nožičky</h3><p>Hůl stojí sama a nepadá na zem.</p></div>
        <div class="npl-point"><h3>Svítilna</h3><p>Osvětluje cestu před vámi ve tmě.</p></div>
        <div class="npl-point"><h3>Alarm</h3><p>Hlasitý signál, který uslyší domácí.</p></div>
        <div class="npl-point"><h3>Skládací provedení</h3><p>Vejde se do tašky i do přihrádky v autě.</p></div>
      </div>
    </div>
  </div>
</section>

<!-- 4) STOJI SAM (video) — video desno -->
<section class="npl-sec">
  <div class="npl-wrap npl-row2 npl-row2--rev">
    <div class="npl-copy">
      <p class="npl-kicker">Základna</p>
      <h2 class="npl-h2">Drží na dlažbě, parketách i venku</h2>
      <p>Gumové nožičky jsou <strong>protiskluzové</strong> a nekloužou na hladkých podlahách. Venku se základna přizpůsobí nerovnému terénu a zůstává stabilní.</p>
      <ul class="npl-check">
        <li>Neklouže na dlažbě, parketách ani laminátu</li>
        <li>Přizpůsobí se nerovnému terénu</li>
        <li>Nožičky lze vyměnit, až se opotřebují</li>
      </ul>
    </div>
    <div class="npl-media"><?php echo $pl_img( 'pal-nozice.jpg', 'Čtyři protiskluzové gumové nožičky' ); ?></div>
  </div>
</section>

<!-- 6) SVJETILJKA — slika desno -->
<section class="npl-sec npl-tint">
  <div class="npl-wrap npl-row2">
    <div class="npl-media"><?php echo $pl_img( 'pal-sklopivo.jpg', 'Skládací a nastavitelná hůl' ); ?></div>
    <div class="npl-copy">
      <p class="npl-kicker">Přenosnost</p>
      <h2 class="npl-h2">Složí se za vteřinu a vejde se do tašky</h2>
      <p>Díly jsou spojené vnitřní gumou, takže se hůl rozebírá a skládá <strong>jedním pohybem</strong>, bez nářadí a bez cizí pomoci. Složená se vejde do tašky nebo do přihrádky v autě.</p>
      <p>Výšku nastavíte za pár vteřin, takže stejná hůl vyhovuje člověku se 155 cm i se 190 cm.</p>
      <ul class="npl-check">
        <li>Rozebrání a složení bez nářadí</li>
        <li>Nastavitelná délka pro všechny výšky</li>
        <li>Díly zůstávají spojené — nic se neztratí</li>
      </ul>
    </div>
  </div>
</section>

<section class="npl-sec npl-rev">
  <div class="npl-wrap">
    <p class="npl-kicker npl-center">U našich zákazníků</p>
    <h2 class="npl-h2 npl-center">Hůl ve skutečných domácnostech</h2>
    <p class="npl-sub">Fotografie a komentáře zákazníků — u křesla, na chodbě, ve tmě a složená na cestu.</p>
    <div class="npl-rev__grid">
      <?php
      $pl_reviews = array(
        array( 'img' => 'pal-ugc-1.jpg',    'name' => 'Marie K.',  'meta' => 'Praha · koupila před 2 měsíci',
               'text' => '„Mám ji u křesla. Dřív jsem vstávala na tři pokusy, teď se chytím dolní rukojeti a vstanu napoprvé.”' ),
        array( 'img' => 'pal-ugc-3.jpg',    'name' => 'Zdeněk P.', 'meta' => 'Brno · koupil před 3 měsíci',
               'text' => '„Stojí sama u stolu a nepadá. To je pro mě největší věc — neohýbám se pro hůl každých pět minut.”' ),
        array( 'img' => 'pal-noc.jpg',      'name' => 'Anna M.',  'meta' => 'Ostrava · koupila před měsícem',
               'text' => '„Svítilnu zapínám, když jdu v noci do koupelny. Nebudím manžela velkým světlem a vidím podlahu před sebou.”' ),
        array( 'img' => 'pal-ugc-baza.jpg', 'name' => 'Ivan Š.',    'meta' => 'Plzeň · koupil před 6 týdny',
               'text' => '„Základna je široká a neklouže. Vyzkoušel jsem ji na dlažbě v koupelně i na mokré terase — drží.”' ),
        array( 'img' => 'pal-ugc-6.jpg',    'name' => 'Naděžda B.',    'meta' => 'Olomouc · koupila před 2 měsíci',
               'text' => '„Koupila jsem ji mamince k 78. narozeninám. Sama si nastaví výšku a sama ji složí, bez cizí pomoci.”' ),
        array( 'img' => 'pal-ugc-5.jpg',    'name' => 'Štěpán L.', 'meta' => 'Liberec · koupil před 4 měsíci',
               'text' => '„Vozím ji v autě, když jedu k lékaři. Složí se za vteřinu a vejde se do tašky, v čekárně nepřekáží.”' ),
      );
      foreach ( $pl_reviews as $r ) : ?>
      <article class="npl-rev__card">
        <div class="npl-rev__img"><?php echo $pl_img( $r['img'], 'Fotografija kupca — NORIKS Pal' ); ?></div>
        <div class="npl-rev__body">
          <div class="npl-rev__stars" aria-label="Ocjena 5 od 5">★★★★★</div>
          <p class="npl-rev__text"><?php echo esc_html( $r['text'] ); ?></p>
          <p class="npl-rev__name"><?php echo esc_html( $r['name'] ); ?>
            <svg width="15" height="15" viewBox="0 0 16 16" aria-hidden="true"><circle cx="8" cy="8" r="8" fill="#2f9e5f"/><path d="M5 8l2 2 4-4" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </p>
          <p class="npl-rev__meta"><?php echo esc_html( $r['meta'] ); ?></p>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="npl-sec npl-tint">
  <div class="npl-wrap npl-row2 npl-row2--rev">
    <div class="npl-copy">
      <p class="npl-kicker">Rozdíl</p>
      <h2 class="npl-h2">Od „potřebuji pomoc” k „jdu sám”</h2>
      <p>Rozdíl není v síle nohou, ale v tom, že máte čeho se chytit. Druhá rukojeť nese váhu místo vašich ramen a zápěstí.</p>
      <p class="npl-strong">Samostatné vstávání, a i procházka parkem.</p>
      <a class="npl-cta" href="#bundle-selector">Objednejte bez rizika — 30 dní</a>
    </div>
    <div class="npl-media"><?php echo $pl_img( 'pal-prije-poslije.jpg', 'Předtím a potom — samostatný pohyb' ); ?></div>
  </div>
</section>

<!-- 14) ŠEST RAZLOGA — slika desno -->
<style>
.npl-sec { padding: 62px 0; background: #fff; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; color: #12212b; }
.npl-sec * { box-sizing: border-box; }
.npl-tint { background: #eef6f8; }
.npl-wrap { width: 100%; max-width: 1240px; margin: 0 auto; padding: 0 24px; }
.npl-kicker { font-size: 12.5px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; color: #2b8fa6; margin: 0 0 10px; }
.npl-h2 { font-size: clamp(25px, 3.2vw, 36px); font-weight: 800; line-height: 1.18; letter-spacing: -.01em; margin: 0 0 16px; }
.npl-center { text-align: center; }
.npl-copy p { font-size: 16px; line-height: 1.7; color: #465863; margin: 0 0 14px; }
.npl-strong { font-weight: 800; color: #12212b !important; font-size: 17px !important; }
.npl-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 52px; align-items: center; }
.npl-media img { width: 100%; display: block; border-radius: 14px; box-shadow: 0 2px 4px rgba(18,33,43,.05), 0 14px 40px rgba(18,33,43,.10); }
.npl-check { list-style: none; padding: 0; margin: 4px 0 0; display: flex; flex-direction: column; gap: 11px; }
.npl-check li { position: relative; padding-left: 28px; font-size: 15.5px; line-height: 1.5; }
.npl-check li::before { content: "✓"; position: absolute; left: 0; top: -1px; width: 20px; height: 20px; border-radius: 50%; background: #2f9e5f; color: #fff; font-size: 11px; font-weight: 800; display: flex; align-items: center; justify-content: center; }
.npl-points { display: flex; flex-direction: column; gap: 16px; margin-top: 4px; }
.npl-point h3 { font-size: 16.5px; font-weight: 800; margin: 0 0 4px; color: #2b8fa6; }
.npl-point p { font-size: 15px; color: #465863; line-height: 1.6; margin: 0; }
.npl-six { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 4px; }
.npl-reason { background: #fff; border: 1px solid #d9e8ec; border-radius: 12px; padding: 18px 16px; }
.npl-reason span { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; background: #2b8fa6; color: #fff; font-weight: 800; font-size: 13px; margin-bottom: 9px; }
.npl-reason h3 { font-size: 15.5px; font-weight: 800; margin: 0 0 5px; line-height: 1.3; }
.npl-reason p { font-size: 14px; color: #465863; line-height: 1.55; margin: 0; }
.npl-cta { display: inline-block; background: #12212b; color: #fff !important; font-size: 15px; font-weight: 700; padding: 15px 30px; border-radius: 8px; text-decoration: none; }
.npl-cta:hover { background: #2b8fa6; color: #fff !important; }
.npl-rev__grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.npl-rev__card { background: #fff; border: 1px solid #d9e8ec; border-radius: 14px; overflow: hidden;
  display: flex; flex-direction: column; box-shadow: 0 1px 2px rgba(18,33,43,.04), 0 8px 24px rgba(18,33,43,.06); }
.npl-rev__img img { width: 100%; aspect-ratio: 1/1; object-fit: cover; display: block; border-radius: 0; box-shadow: none; }
.npl-rev__body { padding: 16px 18px 18px; }
.npl-rev__stars { color: #f0a020; font-size: 14px; letter-spacing: 1px; margin: 0 0 8px; }
.npl-rev__text { font-size: 14.5px; line-height: 1.6; color: #46545e; margin: 0 0 12px; }
.npl-rev__name { display: flex; align-items: center; gap: 6px; font-size: 14.5px; font-weight: 800; color: #12212b; margin: 0; }
.npl-rev__name svg { flex: 0 0 15px; }
.npl-rev__meta { font-size: 12.5px; color: #7b8b94; margin: 3px 0 0; }
.npl-sub { text-align: center; font-size: 16px; color: #5b6d78; max-width: 60ch; margin: 0 auto 34px; line-height: 1.6; }
.npl-video { width: 100%; display: block; border-radius: 14px; }
@media (max-width: 980px) {
  .npl-rev__grid { grid-template-columns: 1fr 1fr; }
    .npl-row2 { grid-template-columns: 1fr; gap: 30px; }
  .npl-row2--rev .npl-media { order: -1; }
  .npl-six { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 560px) {
  .npl-rev__grid { grid-template-columns: 1fr; gap: 16px; }
  .npl-sec { padding: 44px 0; }
  .npl-wrap { padding: 0 16px; }
  .npl-six { grid-template-columns: 1fr; gap: 14px; }
  .npl-cta { width: 100%; text-align: center; }
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
