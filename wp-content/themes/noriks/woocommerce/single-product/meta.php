<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     9.7.0
 */

use Automattic\WooCommerce\Enums\ProductType;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>

<style>

      .features2 {
    margin-top: 12px;
    margin-bottom: 12px;
      }

      .features__row {
        display: flex;
        justify-content: space-between;
        gap: 28px;
      }

      .feature {
        flex: 1 1 0;
        text-align: center;
      }

      .feature__icon {
 
        margin: 0 auto 0px;
        display: block;
        margin-bottom: 0 !important;
      }

      .feature__text {
        margin: 0;
        line-height: 1.1;
    font-size: 14px;
    margin: 0;
        font-family: 'Barlow', sans-serif;
      }

      /* Responsive: stack nicely on small screens */
      @media (max-width: 640px) {
        .features__row {
     
        }
      }
    </style>


 <section class=" features2" aria-label="Prednosti">
      <div class="features__row">
        <!-- 1) Truck -->
        
        
          <div class="feature">
          
  <img src="<?php echo get_template_directory_uri(); ?>/img/cod_icon_.png" alt="Customer Support Icon" class="feature__icon info-icon">
          <p class="feature__text">Platba na dobírku</p>
        </div>
        
        
        <div class="feature">
      <img src="https://noriks.com/hr/wp-content/uploads/2025/07/footer_icon1-1.png" alt="Shirt Icon" class="feature__icon info-icon">
          <p class="feature__text">Vyzkoušejte 30 dní, bez rizika</p>
        </div>
        
        

        <!-- 2) Smiley -->
        <div class="feature">
     
       
        <img src="https://noriks.com/hr/wp-content/uploads/2025/07/footer_icon3-1.png" alt="Shipping Icon" class="feature__icon info-icon">
          <p class="feature__text">Doprava zdarma při objednávkách nad 1700 Kč</p>
        </div>

    
    
      </div>
    </section>




<?php if ( noriks_is_type( 'ortopas' ) ) : ?>
<!-- Ortopas: kartica "preverjeno s strani zdravnika" (slika) -->
<div class="ortopas-doctor-card" style="margin:14px 0;">
  <img src="<?php echo esc_url( get_template_directory_uri() . '/img/ortopas/ortopas-zdravnik.png' ); ?>"
       alt="Ověřeno lékařem — ortopedický pás NORIKS"
       style="width:100%; height:auto; display:block; border-radius:10px;"
       loading="lazy" decoding="async">
</div>
<?php endif; ?>

<!-- date and countdown section -->

<div class="shipping-box">
  <h2 id="shipping-window" class="shipping-title"></h2>
  <p class="shipping-sub">
    Objednejte během následujících <span id="midnight-countdown" class="countdown"></span>
  </p>
</div>

<style>
  .shipping-box { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; color:#222; margin-top: 13px;
    margin-bottom: 13px; 
      
    background: #f4f4f4;
    padding: 8px 6px 8px 12px;
    border-radius: 5px;
          text-align: center;
      
      
      
  }
  .shipping-title { font-family: 'Roboto', sans-serif;
    font-size: 14px !important;
    font-weight: 700 !important;
    line-height: 1.4 !important; margin-bottom: 0px;
    color: #222 !important; }
  .shipping-sub { font-size: 14px; margin: 0; }
  .countdown { color: #22a155; font-weight: 700; }
</style>

<script>
  (function () {
    const weekdays = ['nedjelja','ponedjeljak','utorak','srijeda','četvrtak','petak','subota'];

    // Helper to add business days (skip Saturday/Sunday)
    function addBusinessDays(date, days) {
      let result = new Date(date);
      let added = 0;
      while (added < days) {
        result.setDate(result.getDate() + 1);
        const day = result.getDay();
        if (day !== 0 && day !== 6) { // skip Sunday(0) + Saturday(6)
          added++;
        }
      }
      return result;
    }

    // Get shipping days: today +2 business days, today +3 business days
    const today = new Date();
    const first  = addBusinessDays(today, 2);
    const second = addBusinessDays(today, 5);

    function formatDayMonth(d) {
      return `${d.getDate()}.${d.getMonth()+1}.`; // e.g. 21.8.
    }

    const windowEl = document.getElementById('shipping-window');
    windowEl.textContent = `Doručení od ${weekdays[first.getDay()]}  ${formatDayMonth(first)} do ${weekdays[second.getDay()]}, ${formatDayMonth(second)}`;

    // Countdown to midnight
    const cdEl = document.getElementById('midnight-countdown');

    function nextMidnight(now) {
      const n = new Date(now);
      n.setHours(24, 0, 0, 0);
      return n;
    }

    function updateCountdown() {
      const now = new Date();
      const end = nextMidnight(now);
      let diff = Math.max(0, end - now);

      const h = Math.floor(diff / 3_600_000); diff -= h * 3_600_000;
      const m = Math.floor(diff / 60_000);    diff -= m * 60_000;
      const s = Math.floor(diff / 1000);

      cdEl.textContent = `${h}h ${m}min ${s}s`;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
  })();
</script>


<!-- date and countdown section -->





<?php 

$is_singles_boxers = has_term( 'singles-boxers', 'product_cat', $current_product_id );

$is_boxers = has_term( array( 'boxerky','1-ks-boxerky','orto-bokserice','balicek-3-ks-boxerky','balicek-5-ks' ), 'product_cat', $current_product_id ) && ! has_term( array( 'black-friday' ), 'product_cat', $current_product_id );

$is_carape = has_term( array( 'ponozky' ), 'product_cat', $current_product_id );

$is_mixed_bundle = has_term( array( 'sady','orto-starter','orto-majica-bokserica','startovaci-balicek' ), 'product_cat', $current_product_id );

?>



<?php if( !$is_boxers && !$is_carape ): ?>


<!-- my thre icons content -->


<div class="features">
    <div class="feature-card">
      <img src="<?php echo get_field("singlepp_icon_img1","option"); ?>" alt="Perfect Fit">
      <p><?php echo get_field("singlepp_icon_t1","option"); ?></p>
    </div>
    <div class="feature-card">
      <img src="<?php echo get_field("singlepp_icon_img2","option"); ?>" alt="Hides Dad Bod">
      <p><?php echo get_field("singlepp_icon_t2","option"); ?></p>
    </div>
    <div class="feature-card">
      <img src="<?php echo get_field("singlepp_icon_img3","option"); ?>" alt="Breathes">
       <p><?php echo get_field("singlepp_icon_t3","option"); ?></p>
    </div>
  </div>


<style>


    .features {
      display: flex;
    justify-content: space-between;
    gap: 16px;
    margin-top: 15px;
    margin-bottom: 15px;
    }

    .feature-card {
    display: flex
;
    flex-direction: column;
    align-items: center;
    flex: 1;
    gap: 8px;
    border-radius: 5px;
    background: #F4F4F4;
    padding: 16px;
    font-size: 14px;
    font-weight: 400;
    color: #111213;
    line-height: 1.2;
    text-align: center;
    }

    .feature-card img {
      width: 32px;
      height: 32px;
      margin-bottom: 0px;
    }

    .feature-card p {
      margin: 0;
      font-weight: 500;
      font-size: 14px;
      color: #222;
       letter-spacing: -0.5px !important;
    }
  </style>
  
 <?php endif; ?>


<!--
<div style="margin-bottom: 15px;" class="woocommerce-product-details__short-description">
    
    
	<?php echo apply_filters( 'the_content', $product->get_description() );  ?>
	
	
</div>
-->



 <!-- icons -->
 
 <!--
 <div class="info-section">

    <div class="info-box">
     
     
     
      

     <img src="<?php echo get_field("singlepp_bottomicons_img1","options"); ?>" alt="" width="25" height="25">
     <?php echo get_field("singlepp_bottomicons_t1","options"); ?>

    
     
     
    </div>
    
    
    
     <div class="info-box">
    
         <a href="tel:+38517776471" style="
    color: #7b8a9b;
    font-weight: 500;
    font-size: 14px;
    font-family: 'Roboto', sans-serif; display: flex; align-items: center; text-decoration: none; ">
  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" style="margin-right: 6px;    width: 17px;
    height: 17px;" viewBox="0 0 16 16">
    <path d="M3.654 1.328a.678.678 0 0 1 .737-.07l2.547 1.272a.678.678 0 0 1 .291.901L6.29 5.72a.678.678 0 0 0 .145.776l2.457 2.457a.678.678 0 0 0 .776.145l2.29-1.24a.678.678 0 0 1 .901.291l1.272 2.547a.678.678 0 0 1-.07.737l-1.175 1.769c-.46.692-1.232 1.043-2.036.964-2.322-.238-4.96-2.223-6.856-4.12C1.77 7.667-.214 5.03.024 2.707c.079-.804.272-1.577.964-2.036L3.654 1.33z"/>
  </svg>
  01 777 64 71
</a>

<a href="mailto:info@noriks.com" style="
    color: #7b8a9b;
    font-weight: 500;
    font-size: 14px;
    font-family: 'Roboto', sans-serif; display: flex; align-items: center; text-decoration: none;">
  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" style="margin-right: 6px;    width: 17px;
    height: 17px;" viewBox="0 0 16 16">
    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v.217L8 8.083 0 4.217V4zm0 1.383v6.234l5.803-3.122L0 5.383zM6.761 8.83 0 12.383V12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.383l-6.761-3.553L8 9.917l-1.239-.653zM16 5.383l-5.803 3.112L16 11.617V5.383z"/>
  </svg>
  info@noriks.com
</a>
         
   
     </div>
     

    <div class="info-grid">
      
      
      
      
      <div class="info-box">
       <img src="<?php echo get_field("singlepp_bottomicons_img2","options"); ?>" alt=""  width="25" height="25">
        <?php echo get_field("singlepp_bottomicons_t2","options"); ?>
      </div>
      <div class="info-box">
  
<img src="<?php echo get_field("singlepp_bottomicons_img3","options"); ?>" alt=""  width="25" height="25">
<?php echo get_field("singlepp_bottomicons_t3","options"); ?>
      </div>
    </div>

  </div>
  -->
  
  <style>


    .info-section {
      display: flex;
      flex-direction: column;
      gap: 7px;
      max-width: 800px;
      margin: auto;
      margin-bottom: 25px;
    }
    
    .info-section img {
      width: 25px;
    }


    .info-box {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      background-color: #f5f6f8;
      border-radius: 3px;
      padding: 16px;
      color: #7b8a9b;
      font-weight: 500;
      font-size: 14px;
          font-family: 'Roboto', sans-serif; 
      text-align: center;
    }

    .info-grid {
      display: flex;
      gap: 7px;
    }

    .info-grid .info-box {
      flex: 1;
    }

    .info-box svg {
      width: 24px;
      height: 24px;
      fill: #7b8a9b;
    }
  </style>









 <!-- icons -->


 <div class="accordion">


    <!-- KidsNest: prvni dve accordion mista (dlouhy obsah ze summary) -->
    <?php if ( function_exists('noriks_is_type') && noriks_is_type( 'kidsnest', $current_product_id ) ) : ?>
    <div class="accordion-item">
      <div class="accordion-header" onclick="toggleAccordion(this)">
        <h3>Obličej vašeho dítěte se formuje právě teď — a čas máte do 9 let</h3>
        <div class="toggle">+</div>
      </div>
      <div class="accordion-content">
        <p>Výzkumníci dýchacích cest a dětští stomatologové roky upozorňují na stejný vzorec — a většina rodičů o něm nikdy neslyšela. Říká se mu <strong>syndrom protáhlého obličeje</strong> (adenoidní obličej).</p>
        <p>Každou noc, kdy dítě spí s otevřenými ústy na nesprávném polštáři, se dějí čtyři věci najednou: jazyk padá dozadu, čelist se stahuje, patro se zužuje do vysokého oblouku a obličej začíná růst vertikálně místo horizontálně. Po tisících takových nocí mezi 3. a 9. rokem se změny upevní.</p>
        <p>Proto se dnes devítileté děti objevují u ortodontisty se zataženou bradou, kruhy pod očima, natěsnanými zuby — a drahým účtem za rovnátka. Způsob, jakým dítě dýchá mezi 3. a 9. rokem, silně ovlivňuje obličej, který ponese celý život.</p>
        <p>NORIKS <strong>KidsNest</strong> je navržen tak, aby působil na základní příčinu — nesprávnou polohu hlavy a čelisti během 9 hodin spánku — díky <strong>3zónové ergonomické struktuře</strong>, která drží hlavu, krk a čelist ve správném zarovnání od první noci.</p>
        <p><strong>Co uvidíte u svého dítěte:</strong></p>
        <ul style="margin:6px 0 12px;padding-left:18px;">
          <li style="margin:0 0 7px;"><strong>Méně dýchání ústy:</strong> rty zavřené během noci, návrat dýchání nosem, konec sucha v ústech po ránu.</li>
          <li style="margin:0 0 7px;"><strong>Tišší noci:</strong> chrápání se u většiny dětí zklidní během 1–2 týdnů.</li>
          <li style="margin:0 0 7px;"><strong>Podpora vyvíjející se čelisti:</strong> správná poloha noc co noc, v letech, kdy na tom nejvíce záleží.</li>
          <li style="margin:0 0 7px;"><strong>Chytrá prevence:</strong> jeden polštář dnes — místo drahých korekcí zítra.</li>
        </ul>
        <p><strong>Jeden polštář dnes večer. Nebo tisíce později.</strong></p>
      </div>
    </div>
    <div class="accordion-item">
      <div class="accordion-header" onclick="toggleAccordion(this)">
        <h3>Starší než 9 let? Okno se zužuje. Škody nekončí.</h3>
        <div class="toggle">+</div>
      </div>
      <div class="accordion-content">
        <p>Rada, kterou jste slyšeli, je pravdivá jen napůl. Ano, horní patro se zpevní kolem 9. roku. Ale obličej se vyvíjí do 20 let, dolní čelist roste do 17 a dýchací cesty se neustále přizpůsobují.</p>
        <p>Proto každá noc dýchání ústy po 9. roce vrší nové škody na staré: skřípání zubů, bolesti hlavy, spánek, který neodpočine, pokles koncentrace — a únavu, kterou si všichni pletou s leností. Váš teenager není líný. Každou noc šest hodin sotva dýchá.</p>
        <p>KidsNest ve velikosti <strong>9–14 let</strong> je vyroben pro starší hlavu, krk a ramena. Jiná kontura, jiná výška, jiná opora. Stejný základní mechanismus: správné zarovnání hlavy, krku a čelisti, celou noc, na těle, které ještě roste.</p>
        <p>Co rodiče pozorují: chrápání se zklidní za 7 až 14 nocí, vrací se skutečná ranní energie, bolesti hlavy slábnou, soustředění se vrací.</p>
        <p>Nejlepší okno je stále od 3 do 9 let. Silné okno je od 8 do 18. Žádné není úplně zavřené — ale každá noc čekání přidává tlak tělu, které se snaží zotavit.</p>
        <p><strong>Včerejšek je pryč. Dnešní večer je stále váš.</strong></p>
      </div>
    </div>
    <?php endif; ?>


    <!-- ErgoSit ortopedicky polstar: prvni dve accordion mista (kopie originalu, CZ) -->
    <?php if ( function_exists('noriks_is_type') && noriks_is_type( 'ortopedski-jastuk', $current_product_id ) ) : ?>
    <div class="accordion-item">
      <div class="accordion-header" onclick="toggleAccordion(this)">
        <h3>Specifikace produktu</h3>
        <div class="toggle">+</div>
      </div>
      <div class="accordion-content">
        <ul style="margin:8px 0 12px; padding-left:18px;">
          <li style="margin:0 0 8px;"><strong>Vnější potah:</strong> Prodyšná pletenina, snímatelný a pratelný v pračce, hypoalergenní</li>
          <li style="margin:0 0 8px;"><strong>Jádro:</strong> Adaptivní pěna OrthoFlex™ | Netoxická, s certifikací OEKO-TEX® | Navržená pro odlehčení tlaku + zarovnání držení těla</li>
        </ul>
      </div>
    </div>
    <div class="accordion-item">
      <div class="accordion-header" onclick="toggleAccordion(this)">
        <h3>Čím je tak výjimečný?</h3>
        <div class="toggle">+</div>
      </div>
      <div class="accordion-content">
        <ul style="margin:8px 0 12px; padding-left:18px;">
          <li style="margin:0 0 10px;"><strong>Paměťová pěna OrthoFlex™:</strong> Pěna s vysokou hustotou, která odlehčuje tlak a přizpůsobí se bez proležení — podpírá kostrč, kyčle a páteř pro celodenní pohodlí.</li>
          <li style="margin:0 0 10px;"><strong>Potah BreatheEase™:</strong> Měkký, prodyšný a šetrný k pokožce. Sundává se a pere v pračce, aby polštář zůstal vždy svěží.</li>
          <li style="margin:0 0 10px;"><strong>Vyvážená opora:</strong> Ani příliš měkká, ani příliš tvrdá. Navržená tak, aby srovnala držení těla a zmírnila bolestivá místa z dlouhých hodin sezení.</li>
        </ul>
      </div>
    </div>
    <?php endif; ?>


    <!-- 1 - detajli --> <!-- skryto na norikshers + ortopedicky polstar -->
    <?php if ( ! ( function_exists('noriks_is_type') && ( noriks_is_type('norikshers', $current_product_id) || noriks_is_type('ortopedski-jastuk', $current_product_id) ) ) ) : ?>
    <div class="accordion-item">
      <div class="accordion-header" onclick="toggleAccordion(this)">
        <h3><?php echo get_field("singlepp_acc_h_1","options"); ?></h3>
        <div class="toggle">+</div>
      </div>
      <div class="accordion-content">
          
         <?php if( !$is_boxers &&  !$is_carape &&   !$is_mixed_bundle && ! ( function_exists('noriks_is_type') && ( noriks_is_type('fisiorest', $current_product_id) || noriks_is_type('bunion', $current_product_id) || noriks_is_type('ortopas', $current_product_id) || noriks_is_type('kidsnest', $current_product_id) || noriks_is_type('leakboxers', $current_product_id) || noriks_is_type('kompresijske-majice', $current_product_id) ) ) ): ?>



        <?php echo get_field("singlepp_acc_t_1","options"); ?>


         <?php elseif( function_exists('noriks_is_type') && noriks_is_type('kneefix', $current_product_id) ): ?>

                NORIKS KneeFix je flexibilní ortéza na koleno, která spojuje čtyři funkce v jednom systému opory: nastavitelnou kompresi pomocí přesného kolečka, dvojité boční stabilizátory, gelovou vložku odlehčující čéšku a silikonový protiskluzový okraj, který ortézu udrží na místě.<br><br>Na rozdíl od tuhých ortéz KneeFix koleno neznehybní — podpírá ho během přirozeného pohybu. Kompresi nastavíte během vteřiny: ráno pevněji, odpoledne volněji, podle toho, kolik jste na nohou. Koleno tak získá stabilitu při vstávání, na schodech, při chůzi i při delším stání.<br><br>Látka je lehká, prodyšná a odvádí vlhkost, takže ortézu můžete nosit hodiny bez pocení a bez zařezávání. Je tenká a diskrétní — pod kalhotami ji téměř není vidět.<br><br>K dispozici je ve velikostech od S do 2XL podle tělesné hmotnosti a ve verzi pro levé i pravé koleno, takže sedí přesně.

        <?php elseif( function_exists('noriks_is_type') && noriks_is_type('fisiorest', $current_product_id) ): ?>

                NORIKS FisioRest je terapeutický polštář na krk, který kombinuje trakci, teplo a vibrační masáž v ergonomickém designu z paměťové pěny. Jemně protahuje krk pod správným úhlem, odlehčuje krční páteř a teplem i masáží uvolňuje svalové napětí. Bezdrátový, dobíjecí a obalený měkkým chladivým hedvábím – bezpečný i pro spánek.

        <?php elseif( function_exists('noriks_is_type') && noriks_is_type('bunion', $current_product_id) ): ?>

                NORIKS korektor vbočeného palce s pokročilou terapií srovnání a patentovaným kloubovým mechanismem jemně vrací palec do přirozené polohy, zmírňuje nepohodlí a zabraňuje dalšímu růstu výrůstku. Pohyblivá konstrukce umožňuje v něm i chodit. Přizpůsobí se všem velikostem chodidel, bez levé či pravé strany. Určen pro použití v klidu – při odpočinku, sledování TV, čtení nebo spánku.

        <?php elseif( function_exists('noriks_is_type') && noriks_is_type('ortopas', $current_product_id) ): ?>

                NORIKS ortopedický pás cíleně stabilizuje spodní část zad pomocí cílené komprese, správně srovnává pánev a odlehčuje ischiadický nerv. Tenký a nenápadný pod oblečením, s nastavitelnou mírou opory. Vhodný při bolestech v kříži, ischiasu, svalovém napětí a potížích se SI kloubem.

        <?php elseif( function_exists('noriks_is_type') && noriks_is_type('kidsnest', $current_product_id) ): ?>

                NORIKS KidsNest je vyroben z hypoalergenní paměťové pěny s certifikací OEKO-TEX® — bez formaldehydu, těžkých kovů a BPA — s prodyšným, pratelným potahem, který se jednoduše sundává.<br><br>Jeho 3zónová ergonomická struktura jemně přijímá hlavu, podpírá krk a pomáhá udržet páteř v přirozeném zarovnání — i když se dítě během noci hodně otáčí. Podporuje tak dýchání nosem a klidnější, hlubší spánek.<br><br>Je k dispozici ve třech velikostech (1–3, 3–9 a 9–14 let), roste s vaším dítětem a poskytuje správnou výšku opory v každé fázi vývoje.

        <?php elseif( function_exists('noriks_is_type') && noriks_is_type('leakboxers', $current_product_id) ): ?>

                Inkontinenční boxerky NORIKS jsou vyrobeny z měkkého antibakteriálního bambusového vlákna s voděodolnou vnější vrstvou. Uprostřed je 7vrstvé jádro PureDry™, které okamžitě absorbuje a uzamkne až 300 ml tekutiny, takže kůže zůstává suchá a únik zůstane uvnitř.<br><br>Střih je tenký a diskrétní — vypadá a působí jako běžné prádlo, bez objemnosti a bez pocitu „pleny“. Ochrana kolem nohou brání bočnímu protečení a kontrola zápachu udržuje svěžest po celý den.<br><br>Jsou pratelné a opakovaně použitelné — savost si udrží i po stovkách praní, jako ekologická a úsporná alternativa jednorázových vložek a plen.

        <?php elseif( function_exists('noriks_is_type') && noriks_is_type('kompresijske-majice', $current_product_id) ): ?>

                NORIKS FIT je vyroben z pokročilé iontové kompresní tkaniny, která poskytuje přiléhavý, podpůrný střih. Cílená komprese rovnoměrně stahuje břicho a boky, vyhlazuje siluetu a podporuje vzpřímené držení těla — bez škrcení, které omezuje dýchání nebo pohyb.<br><br>Mikrotkaná vlákna podporují cirkulaci a pomáhají vám během dne stát vzpřímeněji a cítit se jistěji. Tkanina je lehká, prodyšná a odvádí vlhkost, takže zůstáváte v suchu a pohodlí.<br><br>Tenký a diskrétní střih ho činí neviditelným pod jakoukoli košilí a zároveň může posloužit i jako sportovní tričko. Výsledek: ostřejší vzhled, lepší držení těla a sebevědomí — jakmile si ho oblečete.

        <?php elseif(  has_term( array( 'orto-starter', 'orto-majica-bokserica' ), 'product_cat', $current_product_id )  ): ?>
        
        
        
              Naše prémiová trička jsou vyrobena z prvotřídní směsi 60 % prstencově předené bavlny a 40 % polyesteru, která zajišťuje mimořádně měkkou tkaninu odolnou proti mačkání.
<br><br>
Boxerky NORIKS jsou vyrobeny z prvotřídní směsi 95 % modalu a 5 % elastanu, která zajišťuje výjimečně jemnou a elastickou tkaninu, jež se dokonale přizpůsobí tělu. Elastický pas je navržen pro optimální padnutí, poskytuje pohodlí bez stahování a perfektní vzhled pod oblečením.
<br>

        
        <?php else: ?>
        
        
        
            <?php echo get_field("__overwrite_sekcije_bellow_1"); ?>
            
            
        <?php endif; ?>
        
        
        
      </div>
    </div>
    
    
    
     
     <?php endif; // /skryto na norikshers (detajli) ?>

     <!-- 2 - slika tablica velicina -->
     <?php if ( ! ( function_exists('noriks_is_type') && ( noriks_is_type('bunion', $current_product_id) || noriks_is_type('fisiorest', $current_product_id) || noriks_is_type('norikshers', $current_product_id) || noriks_is_type('ortopedski-jastuk', $current_product_id) ) )  && ! ( function_exists('noriks_is_type') && noriks_is_type('kneefix', $current_product_id) )) : // žádná tabulka velikostí pro bunion + fisiorest + norikshers + ortopedicky polstar ?>
     <div class="accordion-item">
      <div class="accordion-header" onclick="toggleAccordion(this)">
        <h3>Tabulka velikostí</h3>
        <div class="toggle">+</div>
      </div>
      <div class="accordion-content">

           <?php if( function_exists('noriks_is_type') && noriks_is_type('kidsnest', $current_product_id) ): ?>

          <div class="kn-size">
            <img src="<?php echo get_template_directory_uri(); ?>/img/kidsnest/tablica-velicine-cz.webp" alt="KidsNest velikosti podle věku" style="width:100%;height:auto;border-radius:10px;display:block;margin:0 0 12px;" loading="lazy">
            <p style="margin:0;line-height:1.6;"><strong>Je dítě mezi dvěma velikostmi?</strong> Vždy zvolte větší. Polštář je navržen tak, aby podpíral zdravé zarovnání, zatímco dítě roste — větší velikost dává více prostoru a delší dobu používání.</p>
          </div>

        <?php elseif( function_exists('noriks_is_type') && noriks_is_type('leakboxers', $current_product_id) ): ?>

          <div class="lbx-size">
            <p style="margin:0 0 6px;font-weight:700;">Jak změřit boky</p>
            <p style="margin:0 0 14px;line-height:1.6;">Obtočte krejčovský metr kolem nejširší části boků (přes hýždě), bez utahování. Stůjte uvolněně a vzpřímeně a zapište si míru v centimetrech.</p>
            <table style="width:100%;border-collapse:collapse;font-size:14px;">
              <thead>
                <tr style="background:#12233b;color:#fff;">
                  <th style="padding:8px 10px;text-align:left;background:#e9e9e9 !important;color:#111 !important;">Velikost</th>
                  <th style="padding:8px 10px;text-align:left;background:#e9e9e9 !important;color:#111 !important;">Boky (cm)</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $lbx_sizes = array(
                  array('S','do 76 cm','do 30"'),
                  array('M','77 – 85 cm','30 – 33"'),
                  array('L','86 – 94 cm','34 – 37"'),
                  array('XL','95 – 102 cm','37 – 40"'),
                  array('2XL','103 – 114 cm','41 – 45"'),
                  array('3XL','115 – 121 cm','45 – 48"'),
                  array('4XL','122 – 129 cm','48 – 51"'),
                  array('5XL','130 – 137 cm','51 – 54"'),
                  array('6XL','138 – 145 cm','54 – 57"'),
                  array('7XL','146 – 153 cm','57 – 60"'),
                  array('8XL','154 cm a více','61" a více'),
                );
                foreach ( $lbx_sizes as $i => $r ) :
                  $bg = ( $i % 2 ) ? '#f7fafb' : '#fff'; ?>
                  <tr style="background:<?php echo $bg; ?>;border-bottom:1px solid #eee;">
                    <td style="padding:8px 10px;font-weight:700;"><?php echo esc_html($r[0]); ?></td>
                    <td style="padding:8px 10px;"><?php echo esc_html($r[1]); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <p style="margin:14px 0 0;line-height:1.6;"><strong>Mezi dvěma velikostmi?</strong> Vždy doporučujeme větší velikost pro optimální pohodlí a maximální savost.</p>
          </div>

        <?php elseif( function_exists('noriks_is_type') && noriks_is_type('kompresijske-majice', $current_product_id) ): ?>

          <div class="kmf-size">
            <table style="width:100%;border-collapse:collapse;font-size:15px;">
              <thead>
                <tr style="background:#111;color:#fff;">
                  <th style="padding:9px 12px;text-align:left;background:#e9e9e9 !important;color:#111 !important;">Velikost</th>
                  <th style="padding:9px 12px;text-align:left;background:#e9e9e9 !important;color:#111 !important;">Odpovídající hmotnost</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $kmf_sizes = array(
                  array('S','50 – 70 kg'), array('M','70 – 90 kg'), array('L','90 – 110 kg'), array('XL','110 – 130 kg'),
                  array('2XL','130 – 150 kg'), array('3XL','150 – 170 kg'), array('4XL','170 – 190 kg'), array('5XL','190 – 210 kg'),
                );
                foreach ( $kmf_sizes as $i => $r ) :
                  $bg = ( $i % 2 ) ? '#f4f4f4' : '#fff'; ?>
                  <tr style="background:<?php echo $bg; ?>;border-bottom:1px solid #eaeaea;">
                    <td style="padding:9px 12px;font-weight:800;"><?php echo esc_html($r[0]); ?></td>
                    <td style="padding:9px 12px;font-weight:700;"><?php echo esc_html($r[1]); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <p style="margin:12px 0 0;line-height:1.6;">Zvolte velikost podle své hmotnosti. Mezi dvěma velikostmi? Pro silnější kompresi zvolte menší velikost.</p>
          </div>

        <?php elseif( function_exists('noriks_is_type') && noriks_is_type('ortopas', $current_product_id) ): ?>

          <div style="line-height:1.9;">
            <strong>S/M</strong> : obvod boků 75–110 cm<br>
            <strong>L/XL</strong> : obvod boků 110–140 cm<br><br>
            Změřte prosím obvod boků, abyste našli svou velikost.
          </div>

        <?php elseif( $is_boxers ): ?>
       
        
          <img class="js-open-size-chart" style="cursor:pointer;" src="https://noriks.com/cz/wp-content/uploads/2026/01/boxers_size_Cz.png">
          
          
          
        
        <?php elseif( noriks_is_type( 'kompresijske-nogavice', $current_product_id ) ): ?>

          <div style="line-height:1.9;">
            <strong>S/M</strong> : velikost obuvi 36–40 / obvod lýtka : 23–36 cm<br>
            <strong>L/XL</strong> : velikost obuvi 40–44 / obvod lýtka : 36–45 cm<br>
            <strong>2XL</strong> : velikost obuvi 44–48 / obvod lýtka : 45–56 cm<br><br>
            Změřte prosím obvod lýtka v nejširším místě, abyste zjistili svou velikost.<br><br>
            Doporučujeme vybrat velikost podle obvodu lýtka, nikoli podle běžné velikosti obuvi.
          </div>

        <?php elseif(  $is_carape ): ?>
        
        
                  <img class="js-open-size-chart" style="cursor:pointer;" src="https://noriks.com/cz/wp-content/uploads/2026/01/Nogavice_tabela_velikosti_Cz.png">
                  
    <?php elseif(  $is_mixed_bundle ): ?>
    
     <img class="js-open-size-chart" style="cursor:pointer;" src="<?php echo get_template_directory_uri(); ?>/img/tabela-velikosti-majice.jpg">
        
         <img class="js-open-size-chart" style="cursor:pointer;" src="https://noriks.com/cz/wp-content/uploads/2026/01/boxers_size_Cz.png">
          
          <?php else: ?>
      
      
     <img class="js-open-size-chart" style="cursor:pointer;" src="<?php echo get_template_directory_uri(); ?>/img/tabela-velikosti-majice.jpg">
        
        
            
        <?php endif; ?>
      </div>
    </div>
    <?php endif; // /žádná tabulka velikostí pro bunion + fisiorest ?>


    <!-- 3 - savjeti za pranje-->
    <?php if ( ! ( function_exists('noriks_is_type') && ( noriks_is_type('ortopas', $current_product_id) || noriks_is_type('bunion', $current_product_id) || noriks_is_type('fisiorest', $current_product_id) || noriks_is_type('norikshers', $current_product_id) || noriks_is_type('kidsnest', $current_product_id) || noriks_is_type('ortopedski-jastuk', $current_product_id) ) )  && ! ( function_exists('noriks_is_type') && noriks_is_type('kneefix', $current_product_id) )) : // žádné tipy na praní pro pás/bunion/fisiorest + norikshers + kidsnest + ortopedicky polstar ?>
    <div class="accordion-item">
      <div class="accordion-header" onclick="toggleAccordion(this)">
        <h3><?php echo get_field("singlepp_acc_h_2","options"); ?></h3>
        <div class="toggle">+</div>
      </div>
      <div class="accordion-content">
             <?php if( function_exists('noriks_is_type') && noriks_is_type('leakboxers', $current_product_id) ): ?>

                Perte na 30–40 °C na programu pro jemné prádlo. Bez aviváže a bělidla. Sušte na vzduchu. Savost si udrží i po stovkách praní.

             <?php elseif( function_exists('noriks_is_type') && noriks_is_type('kompresijske-majice', $current_product_id) ): ?>

                Perte v pračce ve studené vodě na šetrný program. Bez bělidla a aviváže. Nesušte v sušičce — sušte na vzduchu, aby se zachovala komprese a tvar.

             <?php elseif( !$is_boxers &&  !$is_carape &&   !$is_mixed_bundle ): ?>
        <?php echo get_field("singlepp_acc_t_2","options"); ?>


        <?php elseif(  has_term( array( 'orto-starter', 'orto-majica-bokserica' ), 'product_cat', $current_product_id )  ): ?>



                         Perite boje s bojama.  Program za nježno pranje na hladnoj vodi.  Sušite ravno položeno ili u sušilici na niskoj temperaturi.  Ne izbjeljivati


          <?php else: ?>
            <?php echo get_field("__overwrite_sekcije_bellow_3"); ?>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; // /žádné tipy na praní pro pás/bunion/fisiorest ?>



    <!-- 4 povrati in menjave -->
    <div class="accordion-item">
      <div class="accordion-header" onclick="toggleAccordion(this)">
        <h3><?php echo get_field("singlepp_acc_h_3","options"); ?></h3>
        <div class="toggle">+</div>
      </div>
    <div class="accordion-content">
  <p></p>
  Věříme natolik, že se ti NORIKS bude líbit, že máš <b data-stringify-type="bold">30 dní</b> na vrácení nebo výměnu.
  Bez papírování, bez stresu – vyřešíme to na pár kliknutí.
  </p>

  <p>
    <a href="mailto:info@noriks.com" style="display: flex; align-items: center; text-decoration: none; color: #333;">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#333" style="margin-right: 6px;" viewBox="0 0 16 16">
        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v.217L8 8.083 0 4.217V4zm0 1.383v6.234l5.803-3.122L0 5.383zM6.761 8.83 0 12.383V12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.383l-6.761-3.553L8 9.917l-1.239-.653zM16 5.383l-5.803 3.112L16 11.617V5.383z"/>
      </svg>
      info@noriks.com
    </a>
  </p>

  <p>
    Stačí nám napsat e-mail, že chceš výměnu, a <b data-stringify-type="bold">hned všechno zařídíme.</b>
  </p>
</div>

    </div>



    <!-- 5 - infomraicje o dostavi -->
    <div class="accordion-item">
      <div class="accordion-header" onclick="toggleAccordion(this)">
        <h3><?php echo get_field("singlepp_acc_h_4","options"); ?></h3>
        <div class="toggle">+</div>
      </div>
      <div class="accordion-content">
        <?php echo get_field("singlepp_acc_t_4","options"); ?>
      </div>
    </div>
    
    
        <!-- 6 - navodila za uporabo (PDF) -->
    <?php
    $noriks_manuals = array(
        'kidsnest' => array( 'noriks-kidsnest.pdf', 'NORIKS KidsNest — dětský ortopedický polštář' ),
        'kneefix' => array( 'noriks-kneefix.pdf', 'NORIKS KneeFix — ortopedická kolenní ortéza' ),
        'ortopedski-jastuk' => array( 'noriks-ergosit.pdf', 'NORIKS ErgoSit — ortopedický sedací polštář' ),
        'fisiorest' => array( 'noriks-fisiorest.pdf', 'NORIKS FisioRest — přístroj na krk' ),
        'bunion' => array( 'noriks-bunion-fix.pdf', 'NORIKS Bunion Fix — korektor vbočeného palce' ),
        'ortopas' => array( 'noriks-ortopas.pdf', 'NORIKS ortopedický pás na záda' ),
    );
    $noriks_manual = null;
    if ( function_exists('noriks_is_type') ) {
        foreach ( $noriks_manuals as $noriks_t => $noriks_m ) {
            if ( noriks_is_type( $noriks_t, $current_product_id ) ) { $noriks_manual = $noriks_m; break; }
        }
    }
    if ( $noriks_manual && file_exists( get_template_directory() . '/manuals/' . $noriks_manual[0] ) ) : ?>
    <div class="accordion-item noriks-manual-acc">
      <div class="accordion-header" onclick="toggleAccordion(this)">
        <h3>Návod k použití</h3>
        <div class="toggle">+</div>
      </div>
      <div class="accordion-content">
        <a class="noriks-manual-link" href="<?php echo esc_url( get_template_directory_uri() . '/manuals/' . $noriks_manual[0] ); ?>" target="_blank" rel="noopener">
          <span class="noriks-manual-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7l-5-5z" fill="#e53935"/>
              <path d="M14 2v5h5" fill="#b71c1c"/>
              <text x="12" y="17" text-anchor="middle" font-family="Arial, sans-serif" font-size="6.5" font-weight="700" fill="#fff">PDF</text>
            </svg>
          </span>
          <span class="noriks-manual-txt">
            <strong><?php echo esc_html( $noriks_manual[1] ); ?></strong>
            <small>PDF · otevře se na nové kartě</small>
          </span>
          <span class="noriks-manual-dl" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
          </span>
        </a>
        <style>
        .noriks-manual-acc { border-bottom: none !important; }
        .noriks-manual-acc .accordion-content { padding-bottom: 0 !important; }
        .noriks-manual-link { display:flex; align-items:center; gap:12px; padding:10px 12px; margin:0 0 2px;
          border:1px solid #e5e7eb; border-radius:8px; text-decoration:none; background:#fafafa; color:#12233b;
          cursor:pointer; transition:background .15s ease, border-color .15s ease, transform .15s ease; }
        .noriks-manual-link:hover { background:#eef2f7; border-color:#c2cddd; transform:translateY(-1px); }
        .noriks-manual-link:active { transform:none; }
        .noriks-manual-ico { flex:0 0 auto; display:flex; }
        .noriks-manual-txt { display:flex; flex-direction:column; gap:1px; min-width:0; }
        .noriks-manual-txt strong { font-size:14px; line-height:1.25; font-weight:600; }
        .noriks-manual-txt small { color:#6b7280; font-size:12px; }
        .noriks-manual-dl { margin-left:auto; flex:0 0 auto; display:flex; color:#6b7280; transition:color .15s ease; }
        .noriks-manual-link:hover .noriks-manual-dl { color:#12233b; }
        </style>
      </div>
    </div>
    <?php endif; ?>

    <!-- konec 5 acrodinov -->

  </div>

  <script>
    function toggleAccordion(header) {
      const item = header.parentElement;
      const isOpen = item.classList.contains('open');
      document.querySelectorAll('.accordion-item').forEach(el => el.classList.remove('open'));
      if (!isOpen) item.classList.add('open');
    }
  </script>
  
  
  <style>
      
       .accordion {
      border-top: 1px solid #ddd;
    }

    .accordion-item {
      border-bottom: 1px solid #ddd;
    }

    .accordion-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 5px 5px 0px;
      cursor: pointer;
    }

    .accordion-header h3 {
      display: flex;
      align-items: center;
      font-weight: 500;
      font-size: 16px;
      margin: 0;
      gap: 2px;
      font-family: 'Roboto', sans-serif;  
    }

    .accordion-content {
      padding: 0 0 0 0;
      display: none;
      font-size: 14px;
      font-family: 'Roboto', sans-serif;  
      line-height: 1.6;
      color: black;
    }

    .accordion-item.open .accordion-content {
      display: block;
    }

    .icon {
      width: 24px;
      height: 24px;
      display: inline-block;
      background-size: contain;
      background-repeat: no-repeat;

    }
    
    .icon-details {
   
      margin: 0 0px 0 10px !important;
    }
    
    .icon-size {
   
      margin: 0 0px 0 10px !important;
    }

    /* Placeholder icons using emojis 
    
    .icon.details::before { content: "📝"; }
     .icon.size::before { content: "👕"; }
    .icon.laundry::before { content: "🧺"; }
    .icon.returns::before { content: "↩️"; }
    .icon.shipping::before { content: "📦"; }
*/
    .toggle {
      font-size: 24px;
      transition: transform 0.3s ease;
    }

    .accordion-item.open .toggle {
      transform: rotate(45deg);
    }
  </style>








<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( ProductType::VARIABLE ) ) ) : ?>

		<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ); ?></span></span>

	<?php endif; ?>

	<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>

	<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
