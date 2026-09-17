<?php
/**
 * product-bottom: NORIKS KneeHeat — grijac, kompresija i masaza koljena (orto-kneeheat) — trg CZ.
 *
 * Sve sekcije su LIJEVO/DESNO (slika + tekst), po referentnoj stranici
 * (getmendable.com / Knee Triple Therapy Recovery System). Nikad slika na sredini.
 * Recenzije i FAQ renderira zajednicki reviews.php.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$kh      = get_template_directory_uri() . '/img/kneeheat/';
$kh_path = get_template_directory() . '/img/kneeheat/';

$kh_vid = function( $file, $poster, $alt ) use ( $kh, $kh_path ) {
  if ( ! file_exists( $kh_path . $file ) ) { return ''; }
  return '<video class="nkh-video" autoplay muted loop playsinline preload="metadata" '
       . 'poster="' . esc_url( $kh . $poster ) . '" aria-label="' . esc_attr( $alt ) . '">'
       . '<source src="' . esc_url( $kh . $file ) . '" type="video/mp4"></video>';
};

$kh_img = function( $file, $alt ) use ( $kh, $kh_path ) {
  if ( file_exists( $kh_path . $file ) ) {
    return '<img src="' . esc_url( $kh . $file ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
  }
  return '<div class="nkh-ph" role="img" aria-label="' . esc_attr( $alt ) . '"><span>' . esc_html( $alt ) . '</span></div>';
};
?>

<!-- 1) Tri koraka — videi -->
<section class="nkh-sec nkh-light">
  <div class="nkh-wrap">
    <p class="nkh-eyebrow nkh-center">Jak to funguje</p>
    <h2 class="nkh-h2 nkh-center">Úleva ve 3 jednoduchých krocích</h2>
    <p class="nkh-lead nkh-center">Bez nastavování, bez aplikace, bez složité rutiny. Nasaďte, stiskněte tlačítko a pokračujte ve dni.</p>
    <div class="nkh-steps3">
      <div class="nkh-step3">
        <?php echo $kh_vid( 'kh-step-1.mp4', 'kh-step-1.jpg', 'Nasazení bandáže na koleno' ); ?>
        <div class="nkh-step3-txt">
          <p class="nkh-step3-h"><span>1</span> Nasaďte bandáž</p>
          <p>Obtočte ji kolem kolena a utáhněte pásky. Trvá to 20 sekund a funguje na obou nohách.</p>
        </div>
      </div>
      <div class="nkh-step3">
        <?php echo $kh_vid( 'kh-step-2.mp4', 'kh-step-2.jpg', 'Spuštění sezení jedním tlačítkem' ); ?>
        <div class="nkh-step3-txt">
          <p class="nkh-step3-h"><span>2</span> Stiskněte tlačítko</p>
          <p>Jedno tlačítko spustí všechny tři terapie — teplo, kompresi a vibrace — přesně na 12 minut.</p>
        </div>
      </div>
      <div class="nkh-step3">
        <?php echo $kh_vid( 'kh-step-3.mp4', 'kh-step-3.jpg', 'Přístroj pracuje sám, zatímco odpočíváte' ); ?>
        <div class="nkh-step3-txt">
          <p class="nkh-step3-h"><span>3</span> Pokračujte ve dni</p>
          <p>Podívejte se na zprávy, vypijte kávu. Přístroj pracuje sám a zastaví se, když sezení skončí.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 2) Zacaran krug — video -->
<section class="nkh-sec nkh-white">
  <div class="nkh-wrap nkh-row">
    <div class="nkh-media"><?php echo $kh_vid( 'kh-vid-bol.mp4', 'kh-vid-bol.jpg', 'Bolest a ztuhlost kolena' ); ?></div>
    <div class="nkh-copy">
      <h2 class="nkh-h2">Přerušte začarovaný kruh. Vaše koleno je připravené na skutečnou úlevu.</h2>
      <p>Tupá bolest, ztuhlost při vstávání, opatrný krok před scházením ze schodů — to jsou signály tkáně, která léta potichu hladoví. Odpovědí není další tabletka, ale obnovení průtoku krve v kloubu.</p>
      <ul class="nkh-tri">
        <li><strong>12 minut denně:</strong> obnovení průtoku a uvolnění napětí, bez tablet.</li>
        <li><strong>Obnova a regenerace:</strong> teplo rozšiřuje cévy, komprese vyplavuje otok, vibrace uvolňují ztuhlost — vše v jednom sezení.</li>
        <li><strong>Pohyb bez počítání:</strong> schody bez plánování, dřep na zahradě, vnouče v náručí.</li>
      </ul>
    </div>
  </div>
</section>

<!-- Osjetite razliku — video desno -->
<section class="nkh-sec nkh-light">
  <div class="nkh-wrap nkh-row">
    <div class="nkh-copy">
      <h2 class="nkh-h2">Pocítíte rozdíl: trvalá úleva začíná dnes</h2>
      <p>Ne krátkodobý klid, ale změna, kterou zaznamenáte. <strong>Většina uživatelů pocítí skutečný rozdíl během prvních 7 až 14 dní</strong> pravidelného používání. Ranní ztuhlost povoluje a scházení ze schodů přestává být počítáním.</p>
      <ul class="nkh-tri">
        <li><strong>Méně ztuhlosti a tlaku:</strong> snižuje napětí a otok, které udržují potíže.</li>
        <li><strong>Lepší krevní oběh:</strong> obnovuje průtok v hlubší tkáni kolena — tam, kde problém skutečně vzniká.</li>
        <li><strong>Vše doma:</strong> bez docházení na terapie a bez čekání na termín.</li>
      </ul>
    </div>
    <div class="nkh-media"><?php echo $kh_vid( 'kh-vid-zglob.mp4', 'kh-vid-zglob.jpg', 'Kolenní kloub' ); ?></div>
  </div>
</section>

<!-- Pametna tehnologija — video lijevo -->
<section class="nkh-sec nkh-white">
  <div class="nkh-wrap nkh-row">
    <div class="nkh-media"><?php echo $kh_vid( 'kh-vid-led.mp4', 'kh-vid-led.jpg', 'Vyhřívací prvky v bandáži' ); ?></div>
        <div class="nkh-copy">
      <h2 class="nkh-h2">Chytrá technologie pro hlubší tkáň</h2>
      <p>Přizpůsobte NORIKS KneeHeat svému kolenu: <strong>3 stupně tepla</strong>, <strong>3 režimy vibrací</strong> a <strong>cyklická komprese</strong>. Bez kabelu, jedno tlačítko, dvanáct minut.</p>
      <ul class="nkh-tri">
        <li><strong>Vždy připravený:</strong> bezdrátový provoz a nabíjení přes USB-C kabel.</li>
        <li><strong>Přenosný:</strong> je lehký a vejde se do tašky — používejte ho, kdekoli jste.</li>
        <li><strong>Jednoduché ovládání:</strong> jedno tlačítko, bez aplikace a bez nastavování.</li>
      </ul>
    </div>
  </div>
</section>


<!-- 2) Trostruka metoda — slika desno -->
<section class="nkh-sec nkh-light">
  <div class="nkh-wrap nkh-row">
    <div class="nkh-copy">
      <h2 class="nkh-h2">Tři terapie v jednom sezení</h2>
      <p>Ohřev, komprese a vibrace nefungují jedno po druhém, ale současně — proto jedno sezení trvá jen 12 minut.</p>
      <ul class="nkh-tri">
        <li><strong>Teplo do 42 °C</strong> rozšiřuje cévy a změkčuje ztuhlou tkáň kolem kloubu.</li>
        <li><strong>Vzduchová komprese</strong> rytmicky stlačuje a povoluje, vytlačuje nahromaděnou tekutinu a přivádí čerstvou krev.</li>
        <li><strong>Vibrační masáž 60 Hz</strong> uvolňuje napětí a ztuhlost, která drží koleno „zamčené".</li>
      </ul>
      <p class="nkh-note">Na rozdíl od přístrojů TENS, které jen překrývají signál bolesti, KneeHeat působí na hlubší tkáň.</p>
    </div>
    <div class="nkh-media nkh-graf"><?php echo $kh_img( 'kh-04-metoda.jpg', 'Trojitá metoda: teplo, komprese a vibrace' ); ?></div>
  </div>
</section>


<!-- 4) Značajke uređaja — slika desno -->
<section class="nkh-sec nkh-white">
  <div class="nkh-wrap nkh-row">
    <div class="nkh-media nkh-graf"><?php echo $kh_img( 'kh-11-znacajke.jpg', 'Vlastnosti přístroje NORIKS KneeHeat' ); ?></div>
    <div class="nkh-copy">
      <h2 class="nkh-h2">Vyrobený k nošení, ne do šuplíku</h2>
      <p>Ovládací panel je na vnější straně, na dosah ruky: <strong>jeden dotyk mění stupeň tepla</strong>, druhý režim vibrací. Žádné menu ani aplikace, kterou je třeba hledat ve tmě.</p>
      <p>Bandáž se zapíná na dva pásky, takže pevnost si volíte sami — volněji, když sedíte, pevněji, když chodíte po domě. Přístroj pak zůstává na místě a neotáčí se kolem nohy.</p>
      <p class="nkh-strong">Je bezdrátový a nabíjí se USB-C kabelem; jedno nabití vydrží na více sezení, takže jde s vámi do práce i na cesty.</p>
    </div>
  </div>
</section>

<!-- 5) Što je u paketu — slika lijevo -->
<section class="nkh-sec nkh-light">
  <div class="nkh-wrap nkh-row">
    <div class="nkh-copy">
      <h2 class="nkh-h2">Co dostanete v balení</h2>
      <ul class="nkh-pack">
        <li><strong>Přístroj NORIKS KneeHeat</strong> — bandáž s ohřevem, kompresí a vibracemi</li>
        <li><strong>Opletený USB-C kabel</strong> k nabíjení</li>
        <li><strong>Prodlužovací pásek</strong> pro větší obvody nohy</li>
        <li><strong>2 roky záruka na výměnu</strong></li>
      </ul>
    </div>
    <div class="nkh-media nkh-graf"><?php echo $kh_img( 'kh-07-unboxing-h.jpg', 'Obsah balení NORIKS KneeHeat' ); ?></div>
  </div>
</section>


<!-- 7) Liječnik — slika desno -->
<section class="nkh-sec nkh-white">
  <div class="nkh-wrap nkh-row">
    <div class="nkh-media nkh-graf"><?php echo $kh_img( 'kh-02-lijecnik.jpg', 'Doporučení ortopeda' ); ?></div>
    <div class="nkh-copy">
      <h2 class="nkh-h2">Vytvořeno pro každodenní pohodlí a pohyb</h2>
      <p class="nkh-quote">„U chronických potíží s kolenem po 45. roce se nejvíce vyplatí to, co lidé mohou dělat každý den doma. Teplo, komprese a vibrace společně obnovují průtok v tkáni — a to je základ, na kterém funguje všechno ostatní.”</p>
      <p class="nkh-sign">MUDr. Marek Kovář, ortoped</p>
    </div>
  </div>
</section>

<!-- 8) Dodaci i jamstvo — slika lijevo -->
<!-- 9) Jamstvo — slika desno -->
<style>
.nkh-sec { padding: 46px 0; }
.nkh-light { background: #f3f0ea; color: #1f2a37; }
.nkh-white { background: #fff;    color: #1f2a37; }
.nkh-dark  { background: #12233b; color: #eef3f9; }
.nkh-dark h2, .nkh-dark h3, .nkh-dark p, .nkh-dark li, .nkh-dark strong { color: #eef3f9; }
.nkh-wrap { max-width: 1440px; margin: 0 auto; padding: 0 22px; }
.nkh-row { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; }
.nkh-media img { width: 100%; height: auto; display: block; border-radius: 14px; }
.nkh-eyebrow { text-transform: uppercase; letter-spacing: .14em; font-size: 12px; font-weight: 700; color: #c1601f; margin: 0 0 8px; }
.nkh-h2 { font-size: 27px; line-height: 1.22; margin: 0 0 12px; font-weight: 700; }
.nkh-sec p { font-size: 15.5px; line-height: 1.62; margin: 0 0 12px; }
.nkh-strong { font-weight: 600; }
.nkh-note { font-size: 14.5px; opacity: .85; margin: 4px 0 0; }
.nkh-quote { font-style: italic; font-size: 16.5px; }
.nkh-sign { font-size: 14px; opacity: .75; margin: 0; }
.nkh-ticks, .nkh-tri, .nkh-pack { list-style: none; padding: 0; margin: 14px 0 0; }
.nkh-ticks li { position: relative; padding-left: 24px; margin-bottom: 7px; font-size: 15px; }
.nkh-ticks li:before { content: ""; position: absolute; left: 0; top: 6px; width: 12px; height: 12px; border-radius: 50%; background: #c1601f; }
.nkh-tri li { border-left: 3px solid #c1601f; padding: 2px 0 2px 14px; margin-bottom: 12px; font-size: 15px; line-height: 1.55; }
.nkh-pack li { position: relative; padding-left: 22px; margin-bottom: 8px; font-size: 15px; line-height: 1.5; }
.nkh-pack li:before { content: "✓"; position: absolute; left: 0; top: 0; color: #1e8f4e; font-weight: 800; }
.nkh-steps { list-style: none; padding: 0; margin: 14px 0 0; }
.nkh-steps li { display: flex; gap: 12px; margin-bottom: 12px; font-size: 15px; line-height: 1.55; }
.nkh-steps span { flex: 0 0 auto; width: 28px; height: 28px; border-radius: 50%; background: #c1601f; color: #fff;
                  font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 14px; }

.nkh-three { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; align-items: start; }
.nkh-three figure { margin: 0; }
.nkh-three img { width: 100%; height: auto; display: block; border-radius: 10px; }
.nkh-three figcaption { text-align: center; font-size: 13px; margin-top: 7px; opacity: .72; }
.nkh-mini { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 14px; }
.nkh-mini figure { margin: 0; }
.nkh-mini img { width: 100%; height: auto; display: block; border-radius: 10px; }
.nkh-ph { display: flex; align-items: center; justify-content: center; min-height: 200px; background: #e3ded4; border-radius: 12px; color: #7a6a55; font-size: 14px; text-align: center; padding: 12px; }
@media (max-width: 820px) {
  .nkh-sec { padding: 22px 0; }
  .nkh-wrap { padding-left: 0; padding-right: 0; }
  .nkh-h2 { font-size: 22px; }
  .nkh-row { grid-template-columns: 1fr; gap: 18px; }
  .nkh-steps3 { grid-template-columns: 1fr; gap: 18px; }
  .nkh-three { grid-template-columns: 1fr; gap: 14px; }
  .nkh-row .nkh-media { order: -1; }
}

/* kratek opis izdelka: kljukice namesto pikic (kot pri udlagi proti hrkanju) */
.woocommerce-product-details__short-description ul,
.woocommerce div.product .woocommerce-product-details__short-description ul {
  list-style: none !important; margin: 8px 0 14px !important; padding-left: 0 !important; }
.woocommerce-product-details__short-description ul li,
.woocommerce div.product .woocommerce-product-details__short-description ul li {
  list-style: none !important; list-style-type: none !important; padding-left: 24px !important;
  text-indent: -24px !important; margin-left: 0 !important; line-height: 1.55 !important; margin-bottom: 8px !important; }
.woocommerce-product-details__short-description ul li::marker { content: "" !important; }
.woocommerce-product-details__short-description ul li::before { content: none !important; }
.woocommerce-product-details__short-description .nkh-tick {
  display: inline-block !important; width: 24px !important; text-indent: 0 !important;
  color: #c1601f !important; font-weight: 800 !important; }
/* slika naj bo poravnana na rob kontejnerja, ne na sredino stolpca */
.nkh-center { text-align: center; }

.nkh-steps3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
.nkh-step3 { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.07); }
.nkh-step3 .nkh-video { width: 100%; height: auto; display: block; }
.nkh-step3-txt { padding: 16px 18px 20px; }
.nkh-step3-h { display: flex; align-items: center; gap: 10px; font-size: 17px; font-weight: 700; margin: 0 0 6px !important; }
.nkh-step3-h span { flex: 0 0 auto; width: 27px; height: 27px; border-radius: 50%; background: #c1601f; color: #fff;
                    font-size: 13px; font-weight: 800; display: flex; align-items: center; justify-content: center; }
.nkh-step3-txt p:last-child { font-size: 14.5px; margin: 0 !important; }
.nkh-media .nkh-video { width: 100%; height: auto; display: block; border-radius: 14px; }
.nkh-sec p.nkh-lead { max-width: 720px; margin: 0 auto 22px !important; opacity: .85; text-align: center; }
.nkh-sec .nkh-center { text-align: center; }
.nkh-sec .nkh-lead-copy { max-width: 820px; margin: 0 auto 22px !important; text-align: center; }
.nkh-sec .nkh-lead-copy p { margin-left: auto !important; margin-right: auto !important; }
.nkh-sec .nkh-lead-copy h2 { text-align: center; }
</style>
