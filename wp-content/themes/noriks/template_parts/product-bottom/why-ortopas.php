<?php
/**
 * product-bottom: ORTOPEDICKÝ PÁS NA ZÁDA (ortopas)
 *
 * Dedicated bottom-nicer for the NORIKS orthopedic back belt.
 * Shown via single-product-bottom-nicer.php when noriks_is_type('ortopas').
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------------
 * MEDIA po sekcích.
 * Videa 2, 3, 4, 6 jsou v tématu (git) — /img/ortopas-videos/.
 * TODO: obrázky 1 (koláž) a 5 (indikace) jsou zatím HR verze —
 *       potřeba CZ (české) obrázky.
 * ------------------------------------------------------------------ */
$opz_vid_dir      = get_template_directory_uri() . '/img/ortopas-videos/';
$opz_img_collage  = 'https://noriks.com/hr/wp-content/uploads/2026/07/ortopas-hr-9.png'; // 1) spokojené zákaznice (obrázek) — TODO CZ image
$opz_video_relief = $opz_vid_dir . 'relief.mp4';                                          // 2) přirozená úleva (video)
$opz_video_cause  = $opz_vid_dir . 'cause.mp4';                                           // 3) skutečná příčina (video)
$opz_img_indik    = get_template_directory_uri() . '/img/ortopas/ortopas-indikacije.png'; // lokalizirana grafika indikacij // lokalizirana grafika indikacij // 5) jak funguje (obrázek) — TODO CZ image
$opz_video_feat   = $opz_vid_dir . 'features.mp4';                                        // 6) inovativní vlastnosti (video)

/* Karty (kruhová videa) — 4) sekce se 3 kartami */
$opz_cards = array(
    array(
        'video' => $opz_vid_dir . 'card-1.mp4',
        'title' => 'Zmírňuje potíže',
        'text'  => 'Může poskytnout rychlou úlevu při ischiasu a bolestech zad',
    ),
    array(
        'video' => $opz_vid_dir . 'card-2.mp4',
        'title' => 'Odlehčení bederní páteře',
        'text'  => 'Stabilizuje a srovnává spodní část zad',
    ),
    array(
        'video' => $opz_vid_dir . 'card-3.mp4',
        'title' => 'Ověřená metoda',
        'text'  => 'Založeno na cílené kompresní technologii',
    ),
);

/* Porovnávací tabulka — 7) sekce. array( název, NORIKS(bool), Fyzio(bool) ) */
$opz_cmp_rows = array(
    array( 'Úleva od bolesti',                true,  true  ),
    array( 'Dlouhotrvající účinek',           true,  false ),
    array( 'Výhodná cena',                    true,  false ),
    array( 'Okamžité uvolnění',               true,  false ),
    array( 'Bez čekání',                      true,  false ),
    array( '60denní záruka vrácení peněz',    true,  false ),
    array( 'Dlouhodobé náklady',              false, true  ),
);
/* Recenze s obrázkem — 8) sekce */
$opz_reviews = array(
    array(
        'img'   => get_template_directory_uri() . '/img/ortopas-reviews/review-1.webp',
        'title' => 'Velká pomoc proti bolestem v spodní části zad',
        'text'  => 'Pás NORIKS mi skutečně velmi usnadnil život. Funguje přesně tak, jak slibuje. Můžu se opět předklonit bez bolesti.',
        'name'  => 'Alžběta M.',
    ),
    array(
        'img'   => get_template_directory_uri() . '/img/ortopas-reviews/review-2.jpg',
        'title' => 'Měkký a pohodlný',
        'text'  => 'Můj fyzioterapeut mi doporučil pás proti bolestem zad. Předtím jsem vyzkoušel i jiné pásy, ale tenhle je mnohem pohodlnější na sezení a předklánění. Přesto nabízí skvělou oporu!',
        'name'  => 'Julie U.',
    ),
    array(
        'img'   => get_template_directory_uri() . '/img/ortopas-reviews/review-3.webp',
        'title' => 'Vynikající!',
        'text'  => 'Pomáhá mi sedět vzpřímeně a mám pocit, že chodím vzpřímeněji. Bolesti se výrazně zmírnily a konečně mohu bez bolesti vstát i po delším sezení. Pás nosím zhruba 2–3 hodiny denně – většinou v práci.',
        'name'  => 'Ivan D.',
    ),
);

$opz_yes = '<svg class="opz-yes" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path d="M5 12.5l4 4 10-10" fill="none" stroke="#22a45d" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>';
$opz_no  = '<svg class="opz-no" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path d="M7 7l10 10M17 7L7 17" fill="none" stroke="#dc3545" stroke-width="2.4" stroke-linecap="round"/></svg>';
?>

<!-- ============ 1) Více než 14 000 spokojených zákazníků ============ -->
<section class="opz-why opz-customers">
  <div class="opz-wrap opz-row">
    <div class="opz-col opz-media">
      <img loading="lazy" decoding="async" src="<?php echo esc_url( $opz_img_collage ); ?>" alt="Spokojení zákazníci NORIKS ortopedického pásu" />
    </div>
    <div class="opz-col opz-copy">
      <div class="opz-stars" aria-hidden="true">★★★★★</div>
      <h2 class="opz-title">Více než 14 000 spokojených zákazníků</h2>
      <p class="opz-sub">Tisíce lidí už každodenní bolest zad vyměnily za stabilitu a úlevu — v práci, při řízení i doma.</p>
    </div>
  </div>
</section>

<!-- ============ 2) Přirozená úleva od bolesti ============ -->
<section class="opz-why">
  <div class="opz-wrap opz-row">
    <div class="opz-col opz-media">
      <video src="<?php echo esc_url( $opz_video_relief ); ?>" muted autoplay loop playsinline preload="metadata"></video>
    </div>
    <div class="opz-col opz-copy">
      <h2 class="opz-title">Přirozená úleva od bolesti</h2>
      <p>Když si nasadíte pás NORIKS, pokročilá technologie se <strong>dvěma kompresními zónami</strong> zajistí správné srovnání vašich kyčlí a spodní části zad. To může stabilizovat vaši páteř a odlehčit ischiadický nerv.</p>
      <p>Obvykle byste museli absolvovat rozsáhlou fyzioterapii, abyste dosáhli této úlevy. Pás NORIKS umožňuje, abyste <strong>úlevu pocítili v reálném čase</strong> — zatímco pracujete nebo jste v pohybu s nejbližšími.</p>
      <p>Jakmile jsou vaše spodní část zad a kyčle správně podepřeny, může se tlak na ischiadický nerv snížit. To může znamenat <strong>méně bolesti a větší pohyblivost</strong>.</p>
    </div>
  </div>
</section>

<!-- ============ 3) Skutečná příčina bolesti zad a ischiasu ============ -->
<section class="opz-why opz-cause">
  <div class="opz-wrap opz-row">
    <div class="opz-col opz-media">
      <video src="<?php echo esc_url( $opz_video_cause ); ?>" muted autoplay loop playsinline preload="metadata"></video>
    </div>
    <div class="opz-col opz-copy">
      <h2 class="opz-title">Skutečná příčina bolesti zad a ischiasu</h2>
      <p>Hodiny strávené za psacím stolem, opakující se pohyby nebo těžká fyzická práce mohou vytvořit <strong>nerovnoměrný tlak na meziobratlové ploténky</strong>. V kombinaci s nesprávným držením těla to může během let způsobit značné poškození páteře.</p>
      <p>V důsledku toho mohou ploténky vyklouznout ze své polohy a zatlačit na ischiadický nerv, což způsobí <strong>palčivou bolest, brnění a dokonce slabost</strong>, které se šíří ze spodní části zad dolů po nohou.</p>
    </div>
  </div>
</section>

<!-- ============ 4) Přirozená úleva (3 karty) ============ -->
<section class="opz-why opz-cards">
  <div class="opz-wrap">
    <h2 class="opz-cards-title">Přirozená úleva při ischiasu a bolestech zad</h2>
    <div class="opz-cards-grid">
      <?php foreach ( $opz_cards as $opz_card ) : ?>
        <div class="opz-card">
          <div class="opz-card-media">
            <video src="<?php echo esc_url( $opz_card['video'] ); ?>" muted autoplay loop playsinline preload="metadata"></video>
          </div>
          <div class="opz-card-head">
            <span class="opz-check" aria-hidden="true">
              <svg viewBox="0 0 24 24" width="22" height="22"><circle cx="12" cy="12" r="12" fill="#28a745"/><path d="M7 12.5l3 3 7-7" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3 class="opz-card-title"><?php echo esc_html( $opz_card['title'] ); ?></h3>
          </div>
          <p class="opz-card-text"><?php echo esc_html( $opz_card['text'] ); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ 5) Jak pás NORIKS funguje? ============ -->
<section class="opz-why">
  <div class="opz-wrap opz-row">
    <div class="opz-col opz-media">
      <img loading="lazy" decoding="async" src="<?php echo esc_url( $opz_img_indik ); ?>" alt="Indikace — při čem pomáhá NORIKS ortopedický pás" />
    </div>
    <div class="opz-col opz-copy">
      <h2 class="opz-title">Jak pás NORIKS funguje?</h2>
      <p>Pás NORIKS <strong>cíleně stabilizuje</strong> oblast L5 páteře pomocí <strong>cílené komprese</strong>, správně srovnává pánev a vrací SI kloub do jeho přirozeného rozsahu pohybu.</p>
      <p><strong>Podpírá problematickou oblast</strong>, může odlehčit meziobratlové ploténky a tím snížit tlak na ischiadický nerv.</p>
      <p>Cílená komprese podporuje krevní oběh, čímž lze podpořit proces samoléčení.</p>
      <p>Tato kombinace může poskytnout rychlou úlevu při ischiasu, bolestech zad a SI potížích a <strong>dlouhotrvající zmírnění bolesti</strong> při pravidelném používání.</p>
    </div>
  </div>
</section>

<!-- ============ 6) Inovativní vlastnosti ============ -->
<section class="opz-why">
  <div class="opz-wrap opz-row">
    <div class="opz-col opz-media">
      <video src="<?php echo esc_url( $opz_video_feat ); ?>" muted autoplay loop playsinline preload="metadata"></video>
    </div>
    <div class="opz-col opz-copy">
      <h2 class="opz-title">Inovativní vlastnosti</h2>
      <p><strong>Tenký a praktický:</strong> Vyvinut pro každodenní použití a pohodlně se přizpůsobí pod většinu oblečení, takže nikdo nepozná, že ho nosíte!</p>
      <p><strong>Nastavitelná komprese:</strong> Umožňuje vám přizpůsobit míru opory svým potřebám a nabízí maximální pohodlí.</p>
      <p>Přístup k fyzioterapeutům a odborníkům na bolest je často omezený a spojený s vysokými náklady i časovou náročností. <strong>Pás NORIKS nabízí profesionální řešení na nejvyšší úrovni</strong> a představuje účinnou a dostupnou alternativu.</p>
    </div>
  </div>
</section>

<!-- ============ 7) Pás NORIKS v porovnání (tabulka) ============ -->
<section class="opz-why opz-compare">
  <div class="opz-wrap opz-row">
    <div class="opz-col opz-copy">
      <h2 class="opz-title">Pás NORIKS v porovnání</h2>
      <p class="opz-sub">Cíleně působí na spodní část zad, aby snížil zatížení.</p>
    </div>
    <div class="opz-col">
      <table class="opz-table">
        <thead>
          <tr>
            <th class="opz-th-feat"></th>
            <th class="opz-th-brand">NORIKS</th>
            <th class="opz-th-alt">Fyzio</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ( $opz_cmp_rows as $opz_r ) : ?>
            <tr>
              <th class="opz-feat"><?php echo esc_html( $opz_r[0] ); ?></th>
              <td class="opz-brand"><?php echo $opz_r[1] ? $opz_yes : $opz_no; ?></td>
              <td class="opz-alt"><?php echo $opz_r[2] ? $opz_yes : $opz_no; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- ============ 8) Recenze zákazníků (s obrázkem) ============ -->
<section class="opz-why opz-reviews">
  <div class="opz-wrap">
    <div class="opz-reviews-grid">
      <?php foreach ( $opz_reviews as $opz_rev ) : ?>
        <div class="opz-review">
          <div class="opz-review-media">
            <img loading="lazy" decoding="async" src="<?php echo esc_url( $opz_rev['img'] ); ?>" alt="NORIKS pás — recenze zákazníka <?php echo esc_attr( $opz_rev['name'] ); ?>" />
          </div>
          <div class="opz-review-stars" aria-hidden="true">★★★★★</div>
          <h3 class="opz-review-title"><?php echo esc_html( $opz_rev['title'] ); ?></h3>
          <p class="opz-review-text"><?php echo esc_html( $opz_rev['text'] ); ?></p>
          <div class="opz-review-name"><?php echo esc_html( $opz_rev['name'] ); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<style>
  /* Žádný odkaz "Tabulka velikostí" na pásu (ani plugin, ani globální). */
  .noriks-global-sizechart, .gck-size-link, .gck-size-link-wrap,
  #open-size-chart, #open-size-chartCustom { display: none !important; }

  /* Krátký popis (short description) pásu: skryj standardní odrážky (•),
     zůstane jen ✅ z textu; trochu rozestupu mezi "Výhody:" a seznamem.
     (Tato šablona se načte pouze na orto-ortopas stránkách.) */
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

  .opz-why { padding: 44px 0; }
  .opz-why.opz-customers { background: #f7f7f7; }
  .opz-wrap { max-width: 1180px; margin: 0 auto; padding: 0 16px; }
  .opz-row { display: grid; grid-template-columns: 1fr 1fr; gap: 44px; align-items: center; }
  .opz-media img,
  .opz-media video { width: 100%; height: auto; border-radius: 12px; display: block; }
  .opz-stars { color: #f5a623; font-size: 24px; letter-spacing: 2px; margin-bottom: 10px; }
  .opz-title { font-size: clamp(26px,3.2vw,40px); font-weight: 800; color: #1c1c1c; line-height: 1.15; margin: 0 0 16px; }
  .opz-copy p { font-size: 16px; line-height: 1.7; color: #333; margin: 0 0 14px; }
  .opz-sub { font-size: 17px; line-height: 1.6; color: #333; margin: 0; }

  /* --- 4) sekce s kartami (šedé pozadí / noriks styl) --- */
  .opz-why.opz-cards { background: #f7f7f7; }
  .opz-cards-title { text-align: center; font-size: clamp(22px,2.6vw,30px); font-weight: 800; color: #1c1c1c; margin: 0 0 32px; line-height: 1.2; }
  .opz-cards-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
  .opz-card { background: #fff; border-radius: 14px; padding: 26px 22px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
  .opz-card-media { width: 108px; height: 108px; margin: 0 auto 18px; border-radius: 50%; overflow: hidden; }
  .opz-card-media video { width: 100%; height: 100%; object-fit: cover; display: block; }
  .opz-card-head { display: flex; align-items: center; justify-content: center; gap: 8px; margin: 0 0 10px; }
  .opz-check { flex: 0 0 auto; line-height: 0; }
  .opz-card-title { font-size: 18px; font-weight: 800; color: #1c1c1c; margin: 0; line-height: 1.2; }
  .opz-card-text { font-size: 14px; line-height: 1.55; color: #555; margin: 0; }

  /* --- porovnávací tabulka (noriks zelený styl) --- */
  .opz-why.opz-compare { background: #f7f7f7; }
  .opz-table { width: 100%; border-collapse: separate; border-spacing: 0; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,0.07); }
  .opz-table th, .opz-table td { padding: 13px 14px; text-align: center; vertical-align: middle; }
  .opz-table thead th { background: #22a45d; color: #fff; font-size: 15px; font-weight: 800; }
  .opz-table thead .opz-th-feat { background: #22a45d; }
  .opz-table .opz-feat { background: #22a45d; color: #fff; font-weight: 700; text-align: left; font-size: 14px; line-height: 1.25; width: 55%; }
  .opz-table tbody tr td { border-bottom: 1px solid #eee; background: #fff; }
  .opz-table tbody tr:last-child td,
  .opz-table tbody tr:last-child .opz-feat { border-bottom: 0; }
  .opz-table .opz-brand { background: #f2fbf6; }
  .opz-yes, .opz-no { display: inline-block; vertical-align: middle; }

  /* --- 8) recenze zákazníků (s obrázkem) --- */
  .opz-reviews-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 26px; }
  .opz-review { background: #fafafa; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05); text-align: center; }
  .opz-review-media { width: 100%; aspect-ratio: 1 / 1; background: #eee; }
  .opz-review-media img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .opz-review-stars { color: #f5b301; font-size: 20px; letter-spacing: 2px; margin: 16px 0 8px; }
  .opz-review-title { font-size: 17px; font-weight: 800; color: #1c1c1c; margin: 0 14px 10px; line-height: 1.25; }
  .opz-review-text { font-size: 14px; line-height: 1.6; color: #444; margin: 0 16px 14px; }
  .opz-review-name { font-size: 13px; font-style: italic; font-weight: 700; color: #333; border-top: 1px solid #e6e6e6; margin: 0 16px; padding: 12px 0 18px; }

  @media (max-width: 820px) {
    .opz-row { grid-template-columns: 1fr; gap: 22px; }
    .opz-title { text-align: left; }
    .opz-cards-grid { grid-template-columns: 1fr; gap: 16px; }
    .opz-reviews-grid { grid-template-columns: 1fr; gap: 18px; }
    .opz-table th, .opz-table td { padding: 11px 10px; }
    .opz-table .opz-feat { font-size: 13px; }
    .opz-table thead th { font-size: 14px; }
  }
</style>
