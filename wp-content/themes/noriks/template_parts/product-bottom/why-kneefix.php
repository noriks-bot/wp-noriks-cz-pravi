<?php
/**
 * product-bottom: NORIKS KneeFix — ortopedska steznica za koljeno (orto-kneefix).
 * Sekcije i redoslijed preslikani s referentne stranice, tekst na HR,
 * slike su NORIKS kreative iz img/kneefix/. Svaka sekcija ima sliku s jedne
 * i tekst s druge strane (naizmjenično) — nema sekcija koje su samo slika.
 *   1. Když se každý krok stane nepříjemným   slika lijevo   13_stepenice
 *   2. Možná nejde jen o opotřebení   slika desno    14_zglob
 *   3. Podpora pro aktivní kolena         slika lijevo   08_aktivno
 *   4. 4 funkce. Stabilnější pocit.    slika desno    03_funkcije
 *   5. Pohodlná opora ve 3 krocích          slika lijevo   04_koraki
 *   6. Více pohodlí v běžném dni      slika desno    05_lifestyle
 *   7. Preporučeno za potporu koljena     slika lijevo   06_zdravnik
 *   8. Rozdíl je cítit                  slika desno    07_vs
 *   9. Co říkají naši zákazníci                3 kartice      10/11/12
 * Recenzije i FAQ renderira zajednički reviews.php (ne ovdje).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$kf      = get_template_directory_uri() . '/img/kneefix/';
$kf_path = get_template_directory() . '/img/kneefix/';

/* Ako slika nije na serveru, prikaže se neutralni sivi placeholder. */
$kf_img = function( $file, $alt ) use ( $kf, $kf_path ) {
  if ( file_exists( $kf_path . $file ) ) {
    return '<img src="'.esc_url($kf.$file).'" alt="'.esc_attr($alt).'" loading="lazy">';
  }
  return '<div class="kfx-ph" role="img" aria-label="'.esc_attr($alt).'"><span>'.esc_html($alt).'</span></div>';
};
?>

<!-- ============ 1) Když se každý krok stane nepříjemným ============ -->
<section class="kfx-sec">
  <div class="kfx-wrap kfx-row2">
    <div class="kfx-media"><?php echo $kf_img('kf-cz-3.webp','Bolest kolena při chůzi ze schodů'); ?></div>
    <div class="kfx-copy">
      <h2 class="kfx-h2">Když se každý krok stane nepříjemným</h2>
      <p class="kfx-lead">Na začátku je to často jen mírné tahání.</p>
      <p>Pak přijdou chvíle, kdy koleno pocítíte mnohem silněji:</p>
      <ul class="kfx-list">
        <li>Při vstávání</li>
        <li>Na schodech</li>
        <li>Po delším sezení</li>
        <li>Při chůzi nebo delším stání</li>
      </ul>
      <p>Mnozí se pak automaticky začnou vyhýbat pohybu. Chodí pomaleji, nevědomě odlehčují koleno nebo se při běžných pohybech cítí nejistě.</p>
      <p class="kfx-strong">Problém je v tom: čím opatrněji se pohybujete, tím více se koleno stává středem vašeho dne.</p>
    </div>
  </div>
</section>

<!-- ============ 2) Možná nejde jen o opotřebení ============ -->
<section class="kfx-sec kfx-alt">
  <div class="kfx-wrap kfx-row2">
    <div class="kfx-copy">
      <h2 class="kfx-h2">Možná nejde jen o opotřebení</h2>
      <p>Mnohá běžná vysvětlení mluví jen o „opotřebení". Bolest kolena se ale často projevuje spíš jako <strong>tlak, podráždění nebo nestabilita</strong>.</p>
      <p>Jedním z možných důvodů je podrážděná kloubní výstelka — citlivá vnitřní blána kolenního kloubu. Když se tato tkáň podráždí, koleno může citlivěji reagovat na zátěž. Projevit se to může jako:</p>
      <ul class="kfx-inline-list">
        <li>Pocit tlaku kolem čéšky</li>
        <li>Ztuhlost po klidu</li>
        <li>Nejistota při pohybu</li>
        <li>Citlivost při zátěži</li>
      </ul>
      <p>Mnohé klasické ortézy se problém snaží řešit tuhou stabilizací. Tvrdé ortézy ale bývají nepohodlné, sjíždějí nebo omezují přirozený pohyb. Právě proto je <strong>NORIKS KneeFix</strong> vyvinut jinak.</p>
    </div>
    <div class="kfx-media"><?php echo $kf_img('14_zglob.jpg','Podrážděná kloubní výstelka kolenního kloubu'); ?></div>
  </div>
</section>

<!-- ============ 3) Podpora pro aktivní kolena ============ -->
<section class="kfx-sec">
  <div class="kfx-wrap kfx-row2">
    <div class="kfx-media"><?php echo $kf_img('08_aktivni_CZ.webp','Zůstaňte aktivní — bez omezení v kolenou'); ?></div>
    <div class="kfx-copy">
      <h2 class="kfx-h2">Podpora pro aktivní kolena</h2>
      <p><strong>NORIKS KneeFix</strong> spojuje více funkcí v jednom flexibilním systému podpory pro každý den. Místo těžké ortézy dostanete:</p>
      <ul class="kfx-check">
        <li>Kompresi, kterou si nastavíte sami</li>
        <li>Boční stabilizaci</li>
        <li>Gelový polštářek pro odlehčení čéšky</li>
        <li>Protiskluzový přilnavý okraj</li>
      </ul>
      <p>Cílem není znehybnit vaše koleno. KneeFix je vyvinut tak, aby koleno příjemněji podepřel při každodenním pohybu — při chůzi, v práci, na nákupu i na cestách.</p>
    </div>
  </div>
</section>

<!-- ============ 4) 4 funkce. Stabilnější pocit. ============ -->
<section class="kfx-sec kfx-alt">
  <div class="kfx-wrap kfx-row2">
    <div class="kfx-copy">
      <h2 class="kfx-h2">4 funkce. Stabilnější pocit.</h2>
      <p>KneeFix nedělá jen jedno — několik systémů podpory působí současně:</p>
      <ul class="kfx-check">
        <li><strong>Přesné kolečko pro kompresi</strong> — nastavitelná komprese a bezpečné dosednutí</li>
        <li><strong>Dvojité boční stabilizátory</strong> — boční stabilita kolena</li>
        <li><strong>Gelový polštářek na čéšku</strong> — odlehčení tlaku a tlumení nárazů</li>
        <li><strong>Silikonový úchop proti sjíždění</strong> — měkká silikonová textura brání sklouznutí a rolování</li>
      </ul>
    </div>
    <div class="kfx-media"><?php echo $kf_img('03_funkce_CZ.webp','Čtyři funkce ortézy NORIKS KneeFix'); ?></div>
  </div>
</section>

<!-- ============ 5) Pohodlná opora ve 3 krocích ============ -->
<section class="kfx-sec">
  <div class="kfx-wrap kfx-row2">
    <div class="kfx-media"><?php echo $kf_img('04_kroky_CZ.webp','Pohodlná opora ve třech krocích — natáhněte, zarovnejte, nastavte'); ?></div>
    <div class="kfx-copy">
      <h2 class="kfx-h2">Pohodlná opora ve 3 krocích</h2>
      <ol class="kfx-steps">
        <li><strong>Natáhněte ortézu přes koleno.</strong> Vytáhněte ji nahoru pro bezpečné a pohodlné dosednutí.</li>
        <li><strong>Zarovnejte gelový polštářek.</strong> Umístěte jej vycentrovaně kolem čéšky.</li>
        <li><strong>Nastavte kompresi.</strong> Otočením kolečka nastavíte oporu a stabilitu.</li>
      </ol>
      <p>Bez složitých pásků a nastavování — připraveni jste během několika sekund.</p>
    </div>
  </div>
</section>

<!-- ============ 6) Více pohodlí v běžném dni ============ -->
<section class="kfx-sec kfx-alt">
  <div class="kfx-wrap kfx-row2">
    <div class="kfx-copy">
      <h2 class="kfx-h2">Více pohodlí v běžném dni</h2>
      <p>Mnozí nechtějí těžkou sportovní ortézu. Chtějí prostě:</p>
      <ul class="kfx-check">
        <li>Bezpečněji chodit</li>
        <li>Uvolněněji chodit po schodech</li>
        <li>Déle stát</li>
        <li>Volněji se pohybovat</li>
      </ul>
      <p>NORIKS KneeFix je vyvinut tak, aby každodenní pohyby byly příjemnější — bez zbytečných omezení. Flexibilní materiál se lépe přizpůsobí vašemu dni a podepře koleno tam, kde to potřebujete.</p>
      <a class="kfx-cta" href="#bundle-selector">Vyber svou velikost →</a>
    </div>
    <div class="kfx-media"><?php echo $kf_img('kf-cz-1.webp','KneeFix v běžném dni — procházka, kolo, trénink'); ?></div>
  </div>
</section>

<!-- ============ 7) Doporučeno pro každodenní podporu kolena ============ -->
<section class="kfx-sec">
  <div class="kfx-wrap kfx-row2">
    <div class="kfx-media"><?php echo $kf_img('kf-cz-2.webp','Doporučeno pro každodenní podporu kolena'); ?></div>
    <div class="kfx-copy">
      <h2 class="kfx-h2">Doporučeno pro každodenní podporu kolena</h2>
      <ul class="kfx-check">
        <li>Nastavitelná kompresní podpora</li>
        <li>Stabilizuje a chrání koleno</li>
        <li>Pohodlné pro každodenní nošení</li>
      </ul>
      <p>KneeFix je určen jako každodenní podpora, nikoli jako léčba. Máte-li akutní zranění nebo trvalé potíže, poraďte se o nošení se svým lékařem.</p>
    </div>
  </div>
</section>

<!-- ============ 8) Rozdíl je cítit ============ -->
<section class="kfx-sec kfx-alt">
  <div class="kfx-wrap kfx-row2">
    <div class="kfx-copy">
      <h2 class="kfx-h2">Rozdíl je cítit</h2>
      <p>Tradiční ortézy problém často řeší tím, že koleno znehybní. KneeFix jde jinou cestou — pohyb podporuje, místo aby jej blokoval.</p>
      <ul class="kfx-check">
        <li>Přirozená chůze místo ztuhlosti při pohybu</li>
        <li>Uvolněné držení těla místo nepohodlné polohy</li>
        <li>Volnost pohybu a pohodlí místo viditelného zatížení kolena</li>
      </ul>
      <a class="kfx-cta" href="#bundle-selector">Objednat KneeFix</a>
    </div>
    <div class="kfx-media"><?php echo $kf_img('07_vs_CZ.webp','Kolenní ortéza NORIKS ve srovnání s tradiční ortézou'); ?></div>
  </div>
</section>

<!-- ============ 9) Co říkají naši zákazníci ============ -->
<section class="kfx-sec kfx-revs">
  <div class="kfx-wrap">
    <h2 class="kfx-h2 kfx-center">Co říkají naši zákazníci</h2>
    <p class="kfx-sub kfx-center"><strong>Tisíce zákazníků už denně nosí NORIKS KneeFix</strong> protože je vyvinut tak, aby koleno cíleně podepřel — místo zbytečného omezování pohybu nebo krátkodobého zakrytí potíží.</p>
    <div class="kfx-rev-grid">
      <?php foreach ( array(
        array( '10_review-1.jpg', 'Konečně stabilnější chůze', 'Vyzkoušel jsem už několik ortéz, ale byly buď příliš tuhé, nebo neustále sjížděly. Tahle sedí citelně pohodlněji a kolenu při chůzi i na schodech dává mnohem více stability.', 'Damir P.' ),
        array( '11_review-3.jpg', 'Více jistoty na schodech', 'Schody pro mě byly roky utrpením, protože se mi koleno zdálo nestabilní. Od té doby, co nosím KneeFix, se cítím mnohem jistěji. Téměř nesjíždí ani na delších procházkách.', 'Sanja M.' ),
        array( '12_review-6.jpg', 'Příjemné v běžném dni', 'Nosím ji v práci a nemyslela jsem si, že bude tak pohodlná. Materiál je flexibilní, komprese se snadno nastaví a pod kalhotami ji téměř není vidět.', 'Vesna N.' ),
      ) as $rv ) : ?>
        <article class="kfx-rev">
          <div class="kfx-rev-img"><?php echo $kf_img( $rv[0], 'Zákazník nosí ortézu NORIKS KneeFix' ); ?></div>
          <div class="kfx-rev-body">
            <div class="kfx-stars" aria-label="Ocjena 5 od 5">★★★★★</div>
            <p class="kfx-rev-title"><?php echo esc_html( $rv[1] ); ?></p>
            <p class="kfx-rev-text"><?php echo esc_html( $rv[2] ); ?></p>
            <p class="kfx-rev-name"><?php echo esc_html( $rv[3] ); ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<style>
  .kfx-sec { padding: 48px 0; }
  .kfx-alt { background: #f5f6f7; }
  .kfx-wrap { max-width: 1180px; margin: 0 auto; padding: 0 18px; }
  .kfx-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; }
  .kfx-h2 { font-size: clamp(24px,3.1vw,36px); font-weight: 800; color: #141414; line-height: 1.15; margin: 0 0 16px; }
  .kfx-center { text-align: center; }
  .kfx-copy p, .kfx-sub { font-size: 16px; line-height: 1.65; color: #3a3a3a; margin: 0 0 14px; }
  .kfx-sub { max-width: 820px; margin: 0 auto 26px; }
  .kfx-lead { font-weight: 700; color: #141414; }
  .kfx-strong { font-weight: 700; color: #141414; }
  .kfx-media img { width: 100%; height: auto; display: block; border-radius: 16px; }

  .kfx-ph { width: 100%; aspect-ratio: 1/1; background: #ededed; border: 1px dashed #d3d3d3; border-radius: 16px;
            display: flex; align-items: center; justify-content: center; padding: 18px; box-sizing: border-box; }
  .kfx-ph span { font-size: 13px; line-height: 1.45; color: #9a9a9a; text-align: center; }

  .kfx-list { margin: 0 0 16px; padding-left: 20px; }
  .kfx-list li { font-size: 16px; line-height: 1.6; color: #3a3a3a; margin: 0 0 6px; }
  .kfx-inline-list { list-style: none; display: flex; flex-wrap: wrap; gap: 8px 10px; margin: 0 0 16px; padding: 0; }
  .kfx-inline-list li { background: #fff; border: 1px solid #e4e4e4; border-radius: 999px; padding: 8px 16px; font-size: 14px; color: #141414; }
  .kfx-check { list-style: none; margin: 0 0 16px; padding: 0; }
  .kfx-check li { position: relative; padding: 0 0 11px 30px; font-size: 15.5px; color: #141414; line-height: 1.5; }
  .kfx-check li:before { content: "✓"; position: absolute; left: 0; top: 0; width: 20px; height: 20px; background: #141414; color: #fff; border-radius: 50%; font-size: 12px; text-align: center; line-height: 20px; }
  .kfx-steps { list-style: none; counter-reset: kfxstep; margin: 0 0 16px; padding: 0; }
  .kfx-steps li { counter-increment: kfxstep; position: relative; padding: 0 0 14px 40px; font-size: 15.5px; line-height: 1.55; color: #3a3a3a; }
  .kfx-steps li:before { content: counter(kfxstep); position: absolute; left: 0; top: 0; width: 26px; height: 26px; background: #141414; color: #fff; border-radius: 50%; font-size: 13px; font-weight: 700; text-align: center; line-height: 26px; }

  .kfx-cta { display: inline-block; margin-top: 8px; background: #141414; color: #fff; font-weight: 700; font-size: 16px; padding: 14px 30px; border-radius: 10px; text-decoration: none; }
  .kfx-cta:hover { background: #E8450E; color: #fff; }

  /* 9) recenzije s fotografijama kupaca */
  .kfx-rev-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 22px; }
  .kfx-rev { background: #fff; border: 1px solid #e8e8e8; border-radius: 14px; overflow: hidden; }
  .kfx-rev-img img { width: 100%; height: 100%; aspect-ratio: 3/4; object-fit: cover; display: block; border-radius: 0; }
  .kfx-rev-body { padding: 16px 18px 18px; text-align: center; }
  .kfx-stars { color: #f5a623; font-size: 15px; letter-spacing: 1px; }
  .kfx-rev-title { font-weight: 700; color: #141414; font-size: 15px; margin: 8px 0 8px; }
  .kfx-rev-text { font-size: 14px; line-height: 1.6; color: #4a4a4a; margin: 0 0 12px; }
  .kfx-rev-name { font-size: 13px; font-style: italic; font-weight: 700; color: #6b6b6b; margin: 0; padding-top: 10px; border-top: 1px solid #ededed; }

  @media (max-width: 820px) {
    .kfx-sec { padding: 30px 0; }
    .kfx-row2 { grid-template-columns: 1fr; gap: 20px; }
    .kfx-row2 .kfx-media { order: -1; }
    .kfx-h2 { font-size: 2rem; }
    .kfx-rev-grid { grid-template-columns: 1fr; }
    .kfx-rev-img img { aspect-ratio: 4/3; }
  }

  /* Nema "Tablica veličina" linka na KneeFixu (ni plugin ni globalni). */
  .noriks-global-sizechart, .gck-size-link, .gck-size-link-wrap,
  #open-size-chart, #open-size-chartCustom { display: none !important; }

  /* Kratki opis (short description): sakrij standardne točke (•), ostaje samo ✅
     iz teksta; razmak između "Prednosti:" i liste te ispod liste.
     (Ovaj se predložak učitava samo na orto-kneefix stranicama.) */
  .woocommerce-product-details__short-description ul {
      list-style: none;
      margin: 8px 0 26px;
      padding-left: 0;
  }
  .woocommerce-product-details__short-description ul li {
      list-style: none;
      padding-left: 0;
      margin-left: 0;
      line-height: 1.55;
      margin-bottom: 6px;
  }
  /* razmak iznad "Prednosti:" (paragraf neposredno prije liste) */
  .woocommerce-product-details__short-description p:has(+ ul) {
      margin-top: 20px;
      margin-bottom: 4px;
  }
</style>

<script>
(function(){
  document.querySelectorAll('a.kfx-cta[href="#bundle-selector"]').forEach(function(a){
    a.addEventListener('click', function(e){ e.preventDefault(); var t=document.getElementById('bundle-selector')||document.querySelector('.single_add_to_cart_button'); if(t) t.scrollIntoView({behavior:'smooth',block:'center'}); });
  });
})();
</script>
