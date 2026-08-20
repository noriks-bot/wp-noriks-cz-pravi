
<?php
/* Bunion / ortopas / fisiorest: vlastní why-sekce (bez return — poté běží
   společný systém recenzí). Ostatních produktů se nedotkne. */
if ( function_exists( 'noriks_is_type' ) ) {
    if ( noriks_is_type( 'bunion' ) ) {
        get_template_part( 'template_parts/product-bottom/why-bunion' );
    } elseif ( noriks_is_type( 'ortopas' ) ) {
        get_template_part( 'template_parts/product-bottom/why-ortopas' );
    } elseif ( noriks_is_type( 'fisiorest' ) ) {
        get_template_part( 'template_parts/product-bottom/why-fisiorest' );
    } elseif ( noriks_is_type( 'norikshers' ) ) {
        get_template_part( 'template_parts/product-bottom/why-norikshers' );
    } elseif ( noriks_is_type( 'leakboxers' ) ) {
        get_template_part( 'template_parts/product-bottom/why-leakboxers' );
    } elseif ( noriks_is_type( 'kompresijske-majice' ) ) {
        get_template_part( 'template_parts/product-bottom/why-kompresijske-majice' );
    } elseif ( noriks_is_type( 'kneefix' ) ) {
        get_template_part( 'template_parts/product-bottom/why-kneefix' );
    } elseif ( noriks_is_type( 'kidsnest' ) ) {
        get_template_part( 'template_parts/product-bottom/why-kidsnest' );
    } elseif ( noriks_is_type( 'ortopedski-jastuk' ) ) {
        get_template_part( 'template_parts/product-bottom/why-ortopedski-jastuk' );
    } elseif ( noriks_is_type( 'kompresijske-nogavice' ) ) {
        get_template_part( 'template_parts/product-bottom/why-kompresijske' );
    }
}
?>
<?php
if (  has_term( array( 'startovaci-balicek','orto-starter' ), 'product_cat', get_the_id() )  )   :
?>



<section  class="why-section">
  <div style="max-width: 1440px;" class="container why-container">

    <!-- Left Video -->
    <div class="why-col">
      <div class="video-wrapper">
          <img style="" src="https://noriks.com/cz/wp-content/uploads/2026/01/starter-1_Cz.png">
      </div>
    </div>

    <!-- Right Content -->
    <div class="why-col why-content">
      <h2 style="color: #222; text-align:left; margin-left: 20px; font-family: 'Barlow', sans-serif; color:#222223;">
PROČ LIDÉ VOLÍ STARTOVACÍ BALÍČEK?
      </h2>

      <div style="margin-left: 20px;" class="why-point">
        <p  style="    font-style: italic;
    line-height: 1.2;"  ><strong>“"„Vzal jsem ho, protože jsem si nebyl jistý, jestli mi bude sedět.“


</strong><span style="font-weight:normal;">Marko - Zagreb</span></p>
        <p class="description">Mnoho zákazníků začíná se startovacím balíčkem, protože si chtějí nejdřív ověřit, jak NORIKS sedí a jak se nosí. Jedno tričko a jedny boxerky stačí k rozhodnutí bez velké investice.



</p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p  style="    font-style: italic;
    line-height: 1.2;" ><strong>„Po prvním nošení jsem si hned objednal další.“



</strong><span style="font-weight:normal;">Pavle - Split</span></p>
        <p class="description">Více než 95 % zákazníků si po startovacím balíčku objedná znovu. Ne proto, že by to plánovali, ale protože už první den cítí rozdíl ve střihu, pohodlí a kvalitě.

</p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p  style="    font-style: italic;
    line-height: 1.2;"  ><strong>„Materiál a střih mě přesvědčily.“



</strong><span style="font-weight:normal;">Ante - Pula</span></p>
        <p class="description">Tričko i boxerky jsou měkké, lehké a příjemné na pokožce. Startovací balíček je nejčastějším důvodem, proč se NORIKS rychle stane součástí každodenního šatníku.



</p>
      </div>
    </div>

  </div>
</section>



<section style="background: white;" class="why-section">
   <div style="max-width: 1440px;" class="container why-container">

    <!-- Left Video -->
    <div class="why-col">
      <div class="video-wrapper">
           <img style="" src="https://noriks.com/cz/wp-content/uploads/2026/01/starter-2_cz.png">
      </div>
    </div>

    <!-- Right Content -->
    <div class="why-col why-content">
      <h2 style="color: #222; text-align:left; margin-left: 20px; font-family: 'Barlow', sans-serif; color:#222223;">
KOMBINACE, KTEROU NOSÍŠ KAŽDÝ DEN

      </h2>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Navrženo pro celodenní pohodlí


</strong></p>
        <p class="description">Tričko i boxerky jsou určené k nošení od rána do večera, bez upravování a nepohodlí. Vše drží na svém místě, nestahuje a umožňuje volný pohyb po celý den.


</p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Střih, který spolupracuje s tělem


</strong></p>
        <p class="description">Střih trička zvýrazňuje horní část těla, zatímco boxerky poskytují dostatek prostoru a stabilitu bez vyhrnování. Výsledkem je jistý, uvolněný pocit a upravený vzhled v každé situaci.

</p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Kvalita, kterou ucítíš hned


</strong></p>
        <p class="description">Materiály jsou měkké, prodyšné a příjemné na pokožce, aniž by po praní ztrácely tvar. Už při prvním nošení je jasné, proč se tato kombinace rychle stává součástí každodenního šatníku.


</p>
      </div>
    </div>

  </div>
</section>






<section class="why-section">
   <div style="max-width: 1440px;" class="container why-container">

    <!-- Left Video -->
    <div class="why-col">
      <div class="video-wrapper">
          <img style="" src="https://noriks.com/cz/wp-content/uploads/2026/01/starter-3_cz.png">
      </div>
    </div>

    <!-- Right Content -->
    <div class="why-col why-content">
      <h2 style="color: #222; text-align:left; margin-left: 20px; font-family: 'Barlow', sans-serif; color:#222223;">
NEJSNADNĚJŠÍ A NEJBEZPEČNĚJŠÍ ZAČÁTEK
      </h2>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Nejmenší možná rizika


</strong></p>
        <p class="description">Protože ti umožní poznat kvalitu bez velké investice. Místo nákupu více kusů dopředu si vezmeš jedno tričko a jedny boxerky — přesně tolik, abys zjistil, jak sedí, jak se nosí a jaký je materiál.

</p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Perfektní způsob, jak začít


</strong></p>
        <p class="description">Startovací balíček je vytvořen jako první zkušenost, ne jako zásoba. Je dostupný pouze jednou na zákazníka a za speciální cenu, aby bylo rozhodnutí jednoduché a bez přemýšlení.


</p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Zkušenost, ke které se vracíš


</strong></p>
        <p class="description">Většina mužů pak u NORIKS zůstane, protože pozná rozdíl ve střihu, pohodlí a kvalitě. Ale první krok je právě tento — nejmenší riziko, nejčistší dojem.
</p>
      </div>
    </div>

  </div>
</section>



<?php endif; ?>







<?php 
if (  has_term( array( 'tricka', 'orto-majice', 'orto-majica-darila' ), 'product_cat', get_the_id() )  ||  has_term( 'sady', 'product_cat', get_the_id() )) : 
?>





<section class="why-section">
  <div class="container why-container">

    <!-- Left Video -->
    <div class="why-col">
      <div class="video-wrapper">
        <video 
          autoplay muted loop playsinline 
          class="why-video">
          <source src="https://noriks.com/cz/wp-content/uploads/2025/09/noriks_gif_cz_2-1.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    </div>

    <!-- Right Content -->
    <div class="why-col why-content">
      <h2 style="color: #222; text-align:left; margin-left: 20px; font-family: 'Barlow', sans-serif; color:#222223;">
        <?php echo get_field( 'singlepp_content_part_h1', 'options' ); ?>
      </h2>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong><?php echo get_field( 'singlepp_content_part_t_1', 'options' ); ?></strong></p>
        <p class="description"><?php echo get_field( 'singlepp_content_part_t_2', 'options' ); ?></p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong><?php echo get_field( 'singlepp_content_part_t_3', 'options' ); ?></strong></p>
        <p class="description"><?php echo get_field( 'singlepp_content_part_t_4', 'options' ); ?></p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong><?php echo get_field( 'singlepp_content_part_t_5', 'options' ); ?></strong></p>
        <p class="description"><?php echo get_field( 'singlepp_content_part_t_6', 'options' ); ?></p>
      </div>
    </div>

  </div>
</section>


  
  
  
  
  
  
<!-- table section -->

  
  
<section class="comparison-section" style="padding-top: 30px;" >
    <div class="comparison-intro">
     <!-- <h4 class="highlight"><?php echo get_field("_comp_table_t1", "options"); ?></h4>-->
      <h1 style="color:white;"><?php echo get_field("_comp_table_t2", "options"); ?></h1>
      <p style="    opacity: 0.6;" class="note"><?php echo get_field("_comp_table_t3", "options"); ?></p>
    </div>
  </section>
  
  
<section class="comparison-table-section">
 
 <div class="comparison-container">
   <table class="comparison-table">
      <thead>
        <tr>
          <th></th>
          <th class="brand-column">
                <?php echo get_field("_comp_table_inside_1", "options"); ?><br>
            <div class="price"><?php echo get_field("_comp_table_inside_3", "options"); ?></div>
          </th>
          <th class="other-brand"><?php echo get_field("_comp_table_inside_2", "options"); ?><br><span><?php echo get_field("_comp_table_inside_4", "options"); ?></span></th>
        </tr>
      </thead>
      <tbody>
          
          <?php
          $_comp_table_fieldlines = get_field("_comp_table_fieldlines","options");
          ?>
          
            <?php if ($_comp_table_fieldlines): ?>
             <?php foreach ($_comp_table_fieldlines as $item): ?>
          
                    <tr>
                      <td><?php echo $item['text']; ?></td>
                      <td class="bg-best"><span  style="background: #496d8f;" class="checkmark">✔</span></td>
                      <td class="bg-bad"><span class="crossmark">✖</span></td>
                    </tr>
                    
            <?php endforeach; ?>
        <?php endif; ?>
       
       
      </tbody>
    </table>

    <p style="    opacity: 0.6;" class="small-note">
      <?php echo get_field("_comp_table_bottom_text", "options"); ?>
    </p>
  </div>
</section>



<section class="why-section">
  <div class="container why-container">

    <!-- Left Video -->
    <div class="why-col">
      <div class="video-wrapper">
          <img style="width: 100%;       
    aspect-ratio: 1/1; 
    object-fit: cover;  " src="<?php echo get_template_directory_uri(); ?>/img/majice-3 (1).jpeg">
      </div>
    </div>

    <!-- Right Content -->
    <div class="why-col why-content">
      <h2 style="color: #222; text-align:left; margin-left: 20px; font-family: 'Barlow', sans-serif; color:#222223;">
        PROČ SE Z TOHOTO TRIČKA STANE TVŮJ STANDARD?
      </h2>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Navrženo pro skutečný život
</strong></p>
        <p class="description">Toto tričko je určené pro celodenní nošení – od rána do večera. Nevyžaduje žádné upravování ani přemýšlení, jednoduše dobře sedí v každé situaci.
</p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Střih, který rozumí tělu
</strong></p>
        <p class="description">Střih byl vyvíjen tak, aby kopíroval linii těla bez stahování a zvýraznil to správné. Výsledkem je upravený, sebevědomý vzhled bez pocitu nepohodlí.
</p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Rozdíl poznáš už při prvním obléknutí
</strong></p>
        <p class="description">Materiál je na pokožce měkký, lehký a prodyšný. Už po prvním nošení je jasné, proč se toto tričko rychle stane tím, po kterém saháš nejčastěji.
</p>
      </div>
    </div>

  </div>
</section>

  
<!-- table section -->

<?php endif; ?>







<!-- here we include new file BOXERIRICE-->

<?php if ( has_term( array( 'boxerky', 'orto-bokserice' ), 'product_cat', get_the_ID() )  && !has_term( 'black-friday', 'product_cat', get_the_ID() ) ): ?>



<style>
    .why-container  {
    max-width: 1440px !important;
}
    
</style>


<?php 
if(  get_the_ID() == 39181 ): 
?>


<!-- invlude video views here -->


<?php 
endif; 
?>










<!-- 1 boksarica -->


<section class="why-section">
  <div class="container why-container">

    <!-- Left Video -->
    <div class="why-col">
       <img src="https://noriks.com/cz/wp-content/uploads/2026/04/2026-04-24-09.28.40-1.jpg">
    </div>

    <!-- Right Content -->
    <div class="why-col why-content">
      <h2 style="color: #222; text-align:left; margin-left: 20px; font-family: 'Barlow', sans-serif; color:#222223;">
Flexibilní střih pro silnější stehna
      </h2>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Celodenní pohodlí
</strong></p>
        <p class="description">Speciálně navrženo pro muže s mohutnějšími stehny. Elastický a pružný materiál poskytuje maximální komfort bez stahování nebo nepříjemného tlaku v pase. Spodní prádlo zůstává na místě a nevyhrnuje se, takže se můžete celý den volně pohybovat.</p>
      </div>

    
    
    </div>

  </div>
</section>
<style>
/* your styles */
</style>





<!-- 2 boksarica -->

<section  style="background: white;" class="why-section">
  <div class="container why-container">

    <!-- Left Video -->
    <div class="why-col">
       <img src="https://noriks.com/cz/wp-content/uploads/2026/04/cz-1.jpg">
    </div>

    <!-- Right Content -->
    <div class="why-col why-content">
      <h2 style="color: #222; text-align:left; margin-left: 20px; font-family: 'Barlow', sans-serif; color:#222223;">
      Menší opotřebení a poškození
      </h2>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Super odolné 💪
</strong></p>
        <p class="description">"Zapomeňte na prádlo, které se rychle roztrhá.
Šortky NORIKS jsou vyrobeny z pevnějšího materiálu – vydrží déle a ušetří vám zbytečné cesty do obchodu."
</p>
      </div>

      <div style="margin-left: 20px;" class="why-point">
        
        
        <p class="description">
        
        ✅ Méně trhání <br/>
        ✅ Méně pocení <br/>
        ✅ Pohodlí po celý den <br/>
                
        </p>
      </div>

     
    </div>

  </div>
</section>
<style>
/* your styles */
</style>




<!-- 3 boksarica -->

<section class="why-section">
  <div class="container why-container">

    <!-- Left Video -->
    <div class="why-col">
       <img style="width: 100%;       
    aspect-ratio: 1/1; 
    object-fit: cover;  " src="https://noriks.com/cz/wp-content/uploads/2026/04/2026-04-24-09.28.49-1.jpg">
    </div>

    <!-- Right Content -->
    <div class="why-col why-content">
      <h2 style="color: #222; text-align:left; margin-left: 20px; font-family: 'Barlow', sans-serif; color:#222223;">
      Dostatek prostoru pro všechno
      </h2>

      <div style="margin-left: 20px;" class="why-point">
        <p><strong>Pánové, dopřejte svému rozkroku volně dýchat!
</strong></p>Spodní prádlo NORIKS poskytuje oporu po celý den, aniž by ztrácelo tvar. Nebesky měkký modalový materiál se natahuje a perfektně sedí tam, kde má. Prostor pro vaše „vybavení“ je širší a flexibilnější, takže se nebudete cítit stísněně.</p>
      </div>

   
   
    </div>

  </div>
</section>
<style>
/* your styles */
</style>








<?php endif; ?>

<!-- end BOXERICE -->






<style>
    .most-popular {
    
        padding-top: 20px;
    
    }
</style>










<!--  BOXERICE stylee -->
















  
  
  <style>
      
      .comparison-section-gray  {
         border-radius: 5px;
        }
              
      .comparison-intro-gray  {
           margin-bottom: 0;
        }
      
  </style>
  <div  style="background: #f9f9f9; padding-top: 30px;" >
<section style="background: #f9f9f9; max-width: 1440px;" class="comparison-section comparison-section-gray">
    <div style="background: #f9f9f9;padding: 0;padding-left: 10px;
    padding-right: 10px;" class="comparison-intro comparison-intro-gray ">
      <!--<h4 style="" class="highlight"><?php echo get_field("singlepp_content_standard_reviews_t1","options"); ?></h4>-->
      <h1 style="color:black;     margin-bottom: 4px;">
          
          <?php if ( function_exists('noriks_is_type') && noriks_is_type('fisiorest') ): ?>
          
          
          
          Nejsi sám v hledání úlevy od napětí v krku.
          
          <?php elseif ( function_exists('noriks_is_type') && noriks_is_type('bunion') ): ?>
          
          
          
          Nejsi sám v hledání úlevy od vbočeného palce.
          
          <?php elseif ( function_exists('noriks_is_type') && noriks_is_type('ortopas') ): ?>
          
          
          
          Nejsi sám v hledání odlehčení zad.
          
          <?php elseif ( function_exists('noriks_is_type') && noriks_is_type('kompresijske-nogavice') ): ?>
          
          
          
          Nejsi sám v hledání dokonalých kompresních ponožek.
          
          <?php elseif ( function_exists('noriks_is_type') && noriks_is_type('bokserice') ): ?>
          
          
          
          Nejsi sám v hledání dokonalých boxerek.
          
          <?php elseif ( function_exists('noriks_is_type') && noriks_is_type('norikshers') ): ?>

          Nejste sami v hledání hladké pleti bez vrásek.

          <?php elseif ( function_exists('noriks_is_type') && noriks_is_type('leakboxers') ): ?>

          Nejste sami v hledání spolehlivé ochrany proti úniku moči.

          <?php elseif ( function_exists('noriks_is_type') && noriks_is_type('kompresijske-majice') ): ?>

          Nejste sami v hledání ostřejší siluety a lepšího držení těla.

          <?php elseif ( function_exists('noriks_is_type') && noriks_is_type('kneefix') ): ?>

           Nejste jediní, kdo hledá stabilnější koleno.

          <?php elseif ( function_exists('noriks_is_type') && noriks_is_type('kneefix') ): ?>Tisíce zákazníků už nosí ortézu NORIKS KneeFix pro stabilnější koleno – na schodech, na procházce i při dlouhém stání.<?php elseif ( function_exists('noriks_is_type') && noriks_is_type('kidsnest') ): ?>

          Nejste sami v hledání klidného dětského spánku.

          <?php elseif ( function_exists('noriks_is_type') && noriks_is_type('ortopedski-jastuk') ): ?>

          Nejste sami v hledání sezení bez bolesti.

          <?php elseif ( !has_term( array( 'bokserice', 'bokserice-sastavi-paket' ), 'product_cat', get_the_ID() ) ): ?>

          <?php echo get_field("singlepp_content_standard_reviews_t2","options"); ?>
          
          <?php else: ?>
          
          Nisi sam u potrazi za savršenim boksericama.
          
          <?php endif; ?>
          
          
          </h1>
    <p class="note" style="color: black; margin-top: 0px; margin-bottom: 5px;"><?php if ( function_exists('noriks_is_type') && noriks_is_type('fisiorest') ): ?>Tisíce lidí už používá NORIKS FisioRest pro menší bolest a napětí v krku – trakce, vibrace a teplo v jednom přístroji.<?php elseif ( function_exists('noriks_is_type') && noriks_is_type('bunion') ): ?>Tisíce lidí už nosí korektor NORIKS pro menší bolest a rovnější postavení palce – doma, u televize nebo během spánku.<?php elseif ( function_exists('noriks_is_type') && noriks_is_type('ortopas') ): ?>Tisíce lidí už nosí ortopedický pás NORIKS pro menší bolest a stabilnější záda – v práci, při zvedání a dlouhém sezení.<?php elseif ( function_exists('noriks_is_type') && noriks_is_type('kompresijske-nogavice') ): ?>Tisíce mužů už nosí kompresní ponožky NORIKS pro lehčí a méně unavené nohy – v práci, na cestách i při tréninku.<?php elseif ( function_exists('noriks_is_type') && noriks_is_type('bokserice') ): ?>Tisíce mužů už nosí boxerky NORIKS – pohodlí, které vydrží celý den.<?php elseif ( function_exists('noriks_is_type') && noriks_is_type('norikshers') ): ?>Tisíce žen už používají HERS silikonové kolagenové pásky pro hladší, pevnější a mladší vzhled pleti.<?php elseif ( function_exists('noriks_is_type') && noriks_is_type('leakboxers') ): ?>Tisíce mužů už nosí savé boxerky NORIKS pro sucho a sebevědomí – bez vložek a plen.<?php elseif ( function_exists('noriks_is_type') && noriks_is_type('kompresijske-majice') ): ?>Tisíce mužů už nosí kompresní tričko NORIKS pro vyhlazené břicho, lepší držení těla a více sebevědomí.<?php elseif ( function_exists('noriks_is_type') && noriks_is_type('kidsnest') ): ?>Tisíce rodičů už vyměnily obyčejný polštář za NORIKS KidsNest – tišší noci, dýchání nosem a spánek, který skutečně odpočine.<?php elseif ( function_exists('noriks_is_type') && noriks_is_type('ortopedski-jastuk') ): ?>Tisíce zákazníků už používají ortopedický polštář NORIKS ErgoSit pro sezení bez bolesti kostrče, zad a kyčlí – v autě, v kanceláři i doma.<?php else: ?><?php echo get_field("singlepp_content_standard_reviews_t3","options"); ?><?php endif; ?></p>
    </div>
  </section>
  </div>
  
  
  <style>
      @media (max-width: 768px) {
          
          .basic-reviews-section  {
               padding-left: 0px;
               padding-right: 0px;
            }
            .review .content {
                font-size: 13px;
            }
            .review .info {
                font-size: 13px;
                line-height: 1.3;
            }
            .review {
  
                padding-bottom: 15px;
                margin-bottom: 16px;

            }
      }
  </style>
  
  
  <style>
.loader {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #f5a623;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  animation: spin 0.8s linear infinite;
  margin: 0 auto;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.extra-review-group {
  opacity: 0;
  transition: opacity 0.5s ease;
}

.extra-review-group.show {
  opacity: 1;
}
</style>







<?php 
  // ===== CONFIG: LANGUAGE & DATA =====
  $reviews_language = get_field("webshop_language", "options");
  if (!$reviews_language) { $reviews_language = "EN"; }

  // Detect if current product belongs to bokserice group
  $current_product_id = (function_exists('is_product') && is_product()) ? get_queried_object_id() : get_the_id();
  $is_bokserice_page  = has_term( array( 'boxerky','orto-bokserice', 'bokserice-sastavi-paket' ), 'product_cat', $current_product_id );

  // Special orto products: dedicated Czech review pools (text-only, no avatars).
  $is_ortopas_page    = ( function_exists('noriks_is_type') && noriks_is_type('ortopas', $current_product_id) );
  $is_bunion_page     = ( function_exists('noriks_is_type') && noriks_is_type('bunion', $current_product_id) );
  $is_fisiorest_page  = ( function_exists('noriks_is_type') && noriks_is_type('fisiorest', $current_product_id) );
  $is_norikshers_review_page = ( function_exists('noriks_is_type') && noriks_is_type('norikshers', $current_product_id) );
  $is_leakboxers_page = ( function_exists('noriks_is_type') && noriks_is_type('leakboxers', $current_product_id) );
  $is_kompmajice_page = ( function_exists('noriks_is_type') && noriks_is_type('kompresijske-majice', $current_product_id) );
  $is_kidsnest_page   = ( function_exists('noriks_is_type') && noriks_is_type('kidsnest', $current_product_id) );
  $is_kneefix_page    = ( function_exists('noriks_is_type') && noriks_is_type('kneefix', $current_product_id) );
  $is_jastuk_page     = ( function_exists('noriks_is_type') && noriks_is_type('ortopedski-jastuk', $current_product_id) );

  // Fallback product name shown in review cards.
  $rv_fallback_title = ( function_exists('noriks_is_type') && noriks_is_type('kompresijske-nogavice') ) ? 'Kompresní ponožky NORIKS'
                     : ( $is_ortopas_page ? 'Ortopedický pás NORIKS'
                     : ( $is_bunion_page ? 'Korektor palce NORIKS'
                     : ( $is_fisiorest_page ? 'NORIKS FisioRest'
                     : ( $is_kneefix_page ? 'NORIKS KneeFix kolenní ortéza'
                     : ( $is_kidsnest_page ? 'NORIKS KidsNest polštář'
                     : ( $is_jastuk_page ? 'NORIKS ErgoSit ortopedický polštář'
                     : ( $is_leakboxers_page ? 'NORIKS savé boxerky'
                     : ( $is_kompmajice_page ? 'NORIKS FIT kompresní tričko'
                     : ( $is_norikshers_review_page ? 'NORIKS HERS' : 'Jedna Siva Majica' ) ) ) ) ) ) ) ) );

  // Include review pools
  if ( $is_kneefix_page ) {
    include get_stylesheet_directory() . '/auto_reviews/CZ_kneefix.php';
  } elseif ( $is_kidsnest_page ) {
    include get_stylesheet_directory() . '/auto_reviews/CZ_kidsnest.php';
  } elseif ( $is_jastuk_page ) {
    include get_stylesheet_directory() . '/auto_reviews/CZ_ortopedski_jastuk.php';
  } elseif ( $is_leakboxers_page ) {
    include get_stylesheet_directory() . '/auto_reviews/CZ_leakboxers.php';
  } elseif ( $is_kompmajice_page ) {
    include get_stylesheet_directory() . '/auto_reviews/CZ_kompresijske-majice.php';
  } elseif ( $is_norikshers_review_page ) {
    include get_stylesheet_directory() . '/auto_reviews/CZ_norikshers.php';
  } elseif ( $is_fisiorest_page ) {
    include get_stylesheet_directory() . '/auto_reviews/CZ_fisiorest.php';
  } elseif ( $is_bunion_page ) {
    include get_stylesheet_directory() . '/auto_reviews/CZ_bunion.php';
  } elseif ( $is_ortopas_page ) {
    include get_stylesheet_directory() . '/auto_reviews/CZ_ortopas.php';
  } elseif ( ( function_exists('noriks_is_type') && noriks_is_type('kompresijske-nogavice') ) ) {
      include get_stylesheet_directory() . '/auto_reviews/CZ_nogavice.php';
  } elseif ( ! $is_bokserice_page )  {
    include get_stylesheet_directory() . '/auto_reviews/'.$reviews_language.'.php';
  } else {
    include get_stylesheet_directory() . '/auto_reviews/CZ_bokserice.php';
  }

  include get_stylesheet_directory() . '/auto_reviews/'.$reviews_language.'-2.php';

  // Ensure arrays exist
  $auto_reviews_en   = is_array($auto_reviews_en)   ? $auto_reviews_en   : [];
  $auto_reviews_ship = isset($auto_reviews_ship) && is_array($auto_reviews_ship) ? $auto_reviews_ship : [];

  // ===== HELPERS: STABLE DAILY RANDOMIZATION =====

  /**
   * Get WP/Woo timezone (fallback Europe/Ljubljana).
   */
  function reviews_wp_tz(): DateTimeZone {
    $tz_string = function_exists('wp_timezone_string') ? wp_timezone_string() : (get_option('timezone_string') ?: 'Europe/Ljubljana');
    return new DateTimeZone($tz_string ?: 'Europe/Ljubljana');
  }

  /**
   * Deterministic "random" integer in [0, $mod-1] from a seed string.
   */
  function stable_mod_index(string $seed, int $mod): int {
    if ($mod <= 0) return 0;
    $h = substr(sha1($seed), 0, 8); // 32-bit slice
    $n = hexdec($h);
    return (int) ($n % $mod);
  }

  /**
   * Deterministic shuffle based on a seed string. (Stable for a given seed.)
   */
  function shuffle_with_seed(array $arr, string $seed): array {
    if (empty($arr)) return $arr;
    $keys = array_keys($arr);
    usort($keys, function($a, $b) use ($seed) {
      $ha = sha1($seed . ':' . $a);
      $hb = sha1($seed . ':' . $b);
      return strcmp($ha, $hb);
    });
    $out = [];
    foreach ($keys as $k) { $out[] = $arr[$k]; }
    return $out;
  }

  /**
   * Build/caches a pool of products: [['title'=>..., 'url'=>...], ...]
   */
  function get_wc_product_pool(
      $transient_key = 'reviews_product_pool_cache_v3',
      $ttl = 12 * HOUR_IN_SECONDS
  ) {
      if ( ! function_exists( 'wc_get_products' ) ) {
          return [];
      }

      $product_id = 0;
      if ( function_exists( 'is_product' ) && is_product() ) {
          $product_id = get_queried_object_id();
      }

      $is_bokserice = false;
      $is_norikshers = false;
      $is_kidsnest  = false;
      $is_jastuk    = false;
      $is_fisiorest = false;
      $is_bunion = false;
      $is_ortopas = false;
      $is_nogavice = false;
      if ( $product_id ) {
          $is_bokserice = has_term( array( 'bokserice','orto-bokserice', 'bokserice-sastavi-paket' ), 'product_cat', $product_id );
          $is_norikshers = ( function_exists('noriks_is_type') && noriks_is_type('norikshers', $product_id) );
          $is_kidsnest  = ( function_exists('noriks_is_type') && noriks_is_type('kidsnest', $product_id) );
          $is_jastuk    = ( function_exists('noriks_is_type') && noriks_is_type('ortopedski-jastuk', $product_id) );
          $is_fisiorest = ( function_exists('noriks_is_type') && noriks_is_type('fisiorest', $product_id) );
          $is_bunion = ( function_exists('noriks_is_type') && noriks_is_type('bunion', $product_id) );
          $is_ortopas = ( function_exists('noriks_is_type') && noriks_is_type('ortopas', $product_id) );
          $is_nogavice = ( function_exists('noriks_is_type') && noriks_is_type('kompresijske-nogavice', $product_id) );
      }

      $cache_key = $transient_key . ( $is_kidsnest ? '_kidsnest' : ( $is_jastuk ? '_jastuk' : ( $is_norikshers ? '_norikshers' : ( $is_bokserice ? '_bokserice' : ( $is_fisiorest ? '_fisiorest' : ( $is_bunion ? '_bunion' : ( $is_ortopas ? '_ortopas' : ( $is_nogavice ? '_nogavice' : '_all' ) ) ) ) ) ) ) );

      if ( function_exists( 'get_transient' ) ) {
          $cached = get_transient( $cache_key );
          if ( ! empty( $cached ) && is_array( $cached ) ) {
              return $cached;
          }
      }

      $args = [
          'status'  => 'publish',
          'limit'   => -1,
          'return'  => 'ids',
          'orderby' => 'date',
          'order'   => 'DESC',
      ];

      if ( $is_kidsnest ) {
          $args['category'] = [ 'orto-kidsnest' ];
      } elseif ( $is_jastuk ) {
          $args['category'] = [ 'orto-ortopedski-jastuk' ];
      } elseif ( $is_norikshers ) {
          $args['category'] = [ 'orto-norikshers', 'orto-noriks-hers' ];
      } elseif ( $is_bokserice ) {
          $args['category'] = [ 'boxerky' ];
      } elseif ( $is_fisiorest ) {
        $args['category'] = [ 'orto-fisiorest', 'fisiorest' ];
    } elseif ( $is_bunion ) {
        $args['category'] = [ 'orto-bunion', 'bunion' ];
    } elseif ( $is_ortopas ) {
        $args['category'] = [ 'orto-ortopas', 'ortopas' ];
    } elseif ( $is_nogavice ) {
        $args['category'] = [ 'ponozky', 'orto-kompresijske-carape' ];
    } else {
          // Stranice majica: bazen SAMO iz kategorije majica (s podkategorijama).
          // Prije je uzimao sve osim bokserica, pa su recenzije o majicama
          // zavrsavale pod orto proizvodima (Cloth XXL, Cool Curl…).
          $args['category'] = [ 'tricka' ];
      }

      $ids = wc_get_products( $args );

      $pool = [];
      if ( ! empty( $ids ) ) {
          foreach ( $ids as $pid ) {
              $title = get_the_title( $pid );
              $url   = get_permalink( $pid );
              if ( $title && $url ) {
                  $pool[] = [
                      'title' => $title,
                      'url'   => $url,
                  ];
              }
          }
      }

      if ( function_exists( 'set_transient' ) ) {
          set_transient( $cache_key, $pool, $ttl );
      }

      return $pool;
  }

  /**
   * Load avatar images from theme folder and return URLs.
   * Expected folders:
   *  - /auto_reviews/bokserice-slike/
   *  - /auto_reviews/majice-slike/
   */
  function get_review_avatar_pool(string $type = 'majice'): array {
    $type = ($type === 'bokserice') ? 'bokserice' : 'majice';

    $dir_path = trailingslashit(get_stylesheet_directory()) . 'auto_reviews/' . $type . '-slike/';
    $dir_url  = trailingslashit(get_stylesheet_directory_uri()) . 'auto_reviews/' . $type . '-slike/';

    if ( ! is_dir($dir_path) ) return [];

    $files = glob($dir_path . '*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE);
    if (empty($files)) return [];

    $urls = [];
    foreach ($files as $f) {
      $base = basename($f);
      if ($base && $base[0] !== '.') {
        $urls[] = $dir_url . rawurlencode($base);
      }
    }
    return $urls;
  }

  /**
   * Assign avatars (some real, some placeholder) deterministically per day + review index.
   * If real image is chosen, sets $r['avatar_url'].
   */
  function assign_avatars_stable(array $reviews, array $avatar_pool, string $daily_seed, string $context_seed = 'product', int $real_probability_percent = 60): array {
    $count = count($avatar_pool);
    foreach ($reviews as $i => &$r) {
      $r['avatar_url'] = '';

      if ($count <= 0) continue;

      $roll = stable_mod_index($daily_seed . ':avatar-roll:' . $context_seed . ':' . $i, 100);
      if ($roll < max(0, min(100, $real_probability_percent))) {
        $pick_i = stable_mod_index($daily_seed . ':avatar-pick:' . $context_seed . ':' . $i, $count);
        $r['avatar_url'] = $avatar_pool[$pick_i] ?? '';
      }
    }
    return $reviews;
  }
  
  
  
  /**
 * Avatar images rules:
 * - First $first_n reviews ALWAYS get an image (if available)
 * - Remaining images (unique) are placed randomly within reviews [$range_start .. $range_end]
 * - Each image can appear ONLY once
 * - Deterministic per day (stable daily seed)
 */
function assign_unique_avatars_first3_then_random_until30(
  array $reviews,
  array $avatar_pool,
  string $daily_seed,
  string $context_seed = 'product',
  int $first_n = 3,
  int $range_start = 3,   // 0-based index: review #4
  int $range_end = 30     // 1-based count: up to review #30 -> last index 29
): array {
  $total = count($reviews);
  if ($total <= 0) return $reviews;

  // Ensure key exists and default is placeholder
  foreach ($reviews as &$r) { $r['avatar_url'] = ''; }
  unset($r);

  if (empty($avatar_pool)) return $reviews;

  // Deterministic shuffle of images (stable per day)
  $pool = shuffle_with_seed($avatar_pool, 'avatar-pool:' . $daily_seed . ':' . $context_seed);
  $pool_count = count($pool);

  // 1) First N reviews always get images (as many as available)
  $first_n = max(0, min($first_n, $total, $pool_count));
  for ($i = 0; $i < $first_n; $i++) {
    $reviews[$i]['avatar_url'] = $pool[$i] ?? '';
  }

  // If no more images left, finish
  if ($pool_count <= $first_n) return $reviews;

  // 2) Randomly place remaining images from review #4 to #30 (indexes 3..29)
  $last_index = min($total - 1, $range_end - 1);
  if ($last_index < $range_start) return $reviews;

  $eligible = range($range_start, $last_index);

  // Deterministic shuffle of eligible positions (stable per day)
  $eligible = shuffle_with_seed($eligible, 'avatar-positions:' . $daily_seed . ':' . $context_seed);

  $remaining_images = array_slice($pool, $first_n);
  $take = min(count($remaining_images), count($eligible));

  for ($j = 0; $j < $take; $j++) {
    $pos = $eligible[$j];
    $reviews[$pos]['avatar_url'] = $remaining_images[$j] ?? '';
  }

  return $reviews;
}
  
  
  
  /**
 * Assign avatars for first N reviews:
 * - Use each real image at most once (no repeats).
 * - Only apply to first $use_first_n reviews.
 * - After that (or if pool runs out), use placeholder (avatar_url = '').
 * Deterministic per day.
 */
function assign_unique_avatars_first_n(array $reviews, array $avatar_pool, string $daily_seed, string $context_seed = 'product', int $use_first_n = 10): array {
  $total = count($reviews);
  if ($total <= 0) return $reviews;

  // Ensure every review has the key
  foreach ($reviews as &$r) { $r['avatar_url'] = ''; }
  unset($r);

  if (empty($avatar_pool)) return $reviews;

  // Deterministic shuffled image order for the day + context
  $shuffled_pool = shuffle_with_seed($avatar_pool, 'avatar-pool:' . $daily_seed . ':' . $context_seed);

  // We can only place as many images as we have, and only in first N reviews
  $limit = min($use_first_n, $total, count($shuffled_pool));

  for ($i = 0; $i < $limit; $i++) {
    $reviews[$i]['avatar_url'] = $shuffled_pool[$i] ?? '';
  }

  return $reviews;
}

  /**
   * Assign a deterministic product (title+url) to each review for the day.
   * Stable per day AND per review index.
   */
  function assign_products_stable(array $reviews, array $product_pool, string $daily_seed): array {
    $count = count($product_pool);
    foreach ($reviews as $i => &$r) {
      if ($count > 0) {
        $pick = $product_pool[ stable_mod_index($daily_seed . ':prod:' . $i, $count) ];
        $r['product_title'] = $pick['title'];
        $r['product_url']   = $pick['url'];
      } else {
        $r['product_title'] = $r['product_title'] ?? '';
        $r['product_url']   = $r['product_url']   ?? '';
      }
    }
    return $reviews;
  }

  /**
   * Distribute review dates backward from today to a cutoff date (inclusive),
   * with a deterministic per-day count using the daily seed.
   */
  function assign_dates_stable(array $reviews, string $cutoff_date_string = '20.6.2025', int $min_per_day = 2, int $max_per_day = 9, string $display_format = 'j.n.Y'): array {
    if (empty($reviews)) return $reviews;

    $tz      = reviews_wp_tz();
    $today   = new DateTime('today', $tz);
    $today->modify('-7 days'); // newest review date = today - 7 days
    $cutoff  = DateTime::createFromFormat('j.n.Y', $cutoff_date_string, $tz) ?: new DateTime('20.6.2025', $tz);
    if ($cutoff > $today) $cutoff = clone $today;

    $daily_seed = $today->format('Y-m-d');
    $reviews    = shuffle_with_seed($reviews, 'reviews-order:' . $daily_seed);

    $total    = count($reviews);
    $assigned = 0;
    $day_off  = 0;

    while ($assigned < $total) {
      $date = (clone $today)->modify("-{$day_off} days");
      if ($date < $cutoff) $date = clone $cutoff;

      $span   = max(0, $max_per_day - $min_per_day);
      $add    = ($span > 0) ? (stable_mod_index('perday:'.$daily_seed.':'.$day_off, $span + 1)) : 0;
      $perday = $min_per_day + $add;

      $take = min($perday, $total - $assigned);
      for ($i = 0; $i < $take; $i++) {
        $reviews[$assigned]['assigned_date'] = $date->format($display_format);
        $assigned++;
      }

      $day_off++;
      if ($date == $cutoff && $assigned >= $total) break;
    }

    foreach ($reviews as &$r) {
      if (empty($r['assigned_date'])) $r['assigned_date'] = $cutoff->format($display_format);
    }
    return $reviews;
  }

  // ===== BUILD FOR TODAY =====
  $tz         = reviews_wp_tz();
  $today_obj  = new DateTime('today', $tz);
  $daily_seed = $today_obj->format('Y-m-d');

  // Avatar pools based on page category
  $avatar_type = $is_bokserice_page ? 'bokserice' : 'majice';
  // Belt + bunion + fisiorest + norikshers + leak boxers + kompresni tricka + kidsnest + ortopedicky polstar: text-only reviews (no avatar images).
  $avatar_pool = ( $is_bokserice_page || ( function_exists('noriks_is_type') && ( noriks_is_type('majice') || noriks_is_type('majica-darila') || noriks_is_type('starter') ) ) )
      ? get_review_avatar_pool($avatar_type) : array();  // fotografije samo na majicama i boksericama

  // On single-product landing pages (leak boxers / kompresni tricka) the cards should
  // reference THIS product (via $rv_fallback_title), not random pool products.
  $product_pool = ( $is_leakboxers_page || $is_kompmajice_page || $is_kneefix_page ) ? array() : get_wc_product_pool();

  // 1) Stable daily shuffle of review pools
  $auto_reviews_en   = shuffle_with_seed($auto_reviews_en,   'pool-en:'   . $daily_seed);
  $auto_reviews_ship = shuffle_with_seed($auto_reviews_ship, 'pool-ship:' . $daily_seed);

  // 2) Stable product assignment for the day
  $auto_reviews_en   = assign_products_stable($auto_reviews_en,   $product_pool, $daily_seed);
  $auto_reviews_ship = assign_products_stable($auto_reviews_ship, $product_pool, $daily_seed);

  // 3) Deterministic date distribution back to cutoff 20.06.2025
  $auto_reviews_en   = assign_dates_stable($auto_reviews_en,   '20.6.2025', 2, 9, 'j.n.Y');
  $auto_reviews_ship = assign_dates_stable($auto_reviews_ship, '20.6.2025', 2, 9, 'j.n.Y');


  // 4) Deterministic avatars (some real, some placeholder)
$auto_reviews_en   = assign_unique_avatars_first3_then_random_until30($auto_reviews_en,   $avatar_pool, $daily_seed, 'product', 3, 3, 30);

$auto_reviews_ship = assign_unique_avatars_first_n($auto_reviews_ship, $avatar_pool, $daily_seed, 'shipping', 0);

  
  

  // ===== PAGINATION CHUNKS =====
  $initial_count = 18;   // show on load
  $load_count    = 9;    // per "load more"

  $initial_product   = array_slice($auto_reviews_en, 0, $initial_count);
  $remaining_product = array_slice($auto_reviews_en, $initial_count);
  $chunks_product    = array_chunk($remaining_product, $load_count);

  $initial_ship   = array_slice($auto_reviews_ship, 0, $initial_count);
  $remaining_ship = array_slice($auto_reviews_ship, $initial_count);
  $chunks_ship    = array_chunk($remaining_ship, $load_count);

  // Dynamic counts
  $prod_count = count($auto_reviews_en);
  $ship_count = count($auto_reviews_ship);
?>

<?php if ( $is_ortopas_page || $is_bunion_page || $is_fisiorest_page || $is_norikshers_review_page || $is_leakboxers_page || $is_kompmajice_page || $is_kidsnest_page || $is_jastuk_page || $is_kneefix_page ) : ?>
<style>/* belt + bunion + fisiorest + norikshers + leak boxers + kompresni tricka + kidsnest + ortopedicky polstar: text-only reviews, no avatar */ #reviews-section .avatar { display: none !important; }</style>
<?php endif; ?>

<section id="reviews-section" class="basic-reviews-section" style="margin-bottom:40px!important;padding-bottom:40px!important;">
  <div class="container basic-reviews-section-container" style="width:100%;max-width:1440px;padding-top:20px!important;margin:0 auto;padding-left: 10px; padding-right: 10px;">

    <!-- Tabs -->
    <div class="reviews-tabs" style="display:flex;gap:18px;border-bottom:1px solid #cbc8c8;margin-bottom:18px;">
      <button type="button" class="reviews-tab is-active" data-tab="product"
        style="appearance:none;background:#00000008;border:1px solid #cbc8c8;border-bottom:0;padding:8px 14px;border-radius:0;font-weight:700;">
        <?php echo esc_html__('Recenze produktu', 'your-textdomain'); ?> (692)
      </button>
      <button type="button" class="reviews-tab" data-tab="shipping"
        style="appearance:none;background:transparent;border:1px solid transparent;border-bottom:0;padding:8px 14px;border-radius:0;font-weight:700;">
        <?php echo esc_html__('Recenze doručení', 'your-textdomain'); ?> (389)
      </button>
    </div>

    <!-- PRODUCT GRID (default visible) -->
    <div class="reviews-grid" id="reviews-grid-product">
      <?php if (!empty($initial_product)) : foreach ($initial_product as $review) :
        $name  = $review['name'] ?? 'Anonymní';
        $text  = $review['text'] ?? '';
        $title = !empty($review['product_title']) ? $review['product_title'] : $rv_fallback_title;
        $url   = !empty($review['product_url'])   ? $review['product_url']   : '#';
        $stars = '★★★★★';
        $date_display = $review['assigned_date'] ?? '';
        $avatar_url   = !empty($review['avatar_url']) ? $review['avatar_url'] : '';
      ?>
        <article class="review-card">
          <div class="card-top">
            <h3 class="product-title"><a href="<?php echo esc_url($url); ?>">
              <?php echo esc_html($title); ?>
            </a></h3>
            <div class="date">
              <?php echo esc_html($date_display); ?>
            </div>
          </div>
          <div class="stars"><?php echo $stars; ?></div>
          <div class="identity">
            <?php if ($avatar_url) : ?>
              <div class="avatar"><img src="<?php echo esc_url($avatar_url); ?>" alt="" loading="lazy" /></div>
            <?php else : ?>
              <div class="avatar">👤</div>
            <?php endif; ?>
            <div class="name"><?php echo esc_html($name); ?></div>
            <span class="verified"><?php _e('Potvrzeno','your-textdomain'); ?></span>
          </div>
          <div class="content"><?php echo esc_html($text); ?></div>
        </article>
      <?php endforeach; endif; ?>
    </div>

    <!-- SHIPPING GRID (hidden initially) -->
    <div class="reviews-grid" id="reviews-grid-shipping" style="display:none;">
      <?php if (!empty($initial_ship)) : foreach ($initial_ship as $review) :
        $name  = $review['name'] ?? 'Anonymní';
        $text  = $review['text'] ?? '';
        $title = !empty($review['product_title']) ? $review['product_title'] : 'Jedna Siva Majica';
        $url   = !empty($review['product_url'])   ? $review['product_url']   : '#';
        $stars = '★★★★★';
        $date_display = $review['assigned_date'] ?? '';
        $avatar_url   = !empty($review['avatar_url']) ? $review['avatar_url'] : '';
      ?>
        <article class="review-card">
          <div class="card-top">
            <h3 class="product-title">
              <a href="<?php echo esc_url($url); ?>">
                <?php echo esc_html($title); ?>
              </a>
            </h3>
            <div class="date">
              <?php echo esc_html($date_display); ?>
            </div>
          </div>
          <div class="stars"><?php echo $stars; ?></div>
          <div class="identity">
            <?php if ($avatar_url) : ?>
              <div class="avatar"><img src="<?php echo esc_url($avatar_url); ?>" alt="" loading="lazy" /></div>
            <?php else : ?>
              <div class="avatar">👤</div>
            <?php endif; ?>
            <div class="name"><?php echo esc_html($name); ?></div>
            <span class="verified"><?php _e('Potvrzeno','your-textdomain'); ?></span>
          </div>
          <?php if (!empty($review['headline'])) : ?>
            <div class="headline"><?php echo esc_html($review['headline']); ?></div>
          <?php endif; ?>
          <div class="content"><?php echo esc_html($text); ?></div>
        </article>
      <?php endforeach; endif; ?>
    </div>

  </div>

  <!-- Controls: one CTA row, reused per tab -->
  <div class="container basic-reviews-section-container" style="width:100%;max-width:1100px;margin-top:30px!important;margin:0 auto;">
    <div class="cta-button" style="background:transparent;padding:0;justify-content:left;">
      <a class="cta-button2 button button--xl"
         style="margin:0 auto;text-align:left;background:black;font-family:'Roboto',sans-serif;color:#fff;text-transform:none;font-size:15px;padding:10px 25px;"
         href="#">Načíst více</a>
    </div>
    <div id="reviews-loading" style="display:none;text-align:center;padding:15px;">
      <div class="loader"></div>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function(){
    // Data from PHP (already include product_title/product_url/assigned_date/avatar_url)
    const chunksProduct = <?php echo json_encode($chunks_product); ?>;
    const chunksShip    = <?php echo json_encode($chunks_ship); ?>;

    let nextProduct = 0;
    let nextShip    = 0;

    const tabs    = document.querySelectorAll('.reviews-tab');
    const gridP   = document.getElementById('reviews-grid-product');
    const gridS   = document.getElementById('reviews-grid-shipping');
    const seeMore = document.querySelector('.cta-button2');
    const loader  = document.getElementById('reviews-loading');

    let activeTab = 'product';

    function setTab(tab){
      activeTab = tab;
      tabs.forEach(t=>{
        if(t.dataset.tab === tab){ t.classList.add('is-active'); t.style.background='#00000008'; t.style.borderColor='#e6e6e6'; }
        else{ t.classList.remove('is-active'); t.style.background='transparent'; t.style.borderColor='transparent'; }
      });
      if(tab === 'product'){ gridP.style.display='grid'; gridS.style.display='none'; }
      else{ gridP.style.display='none'; gridS.style.display='grid'; }

      const moreAvail = tab === 'product'
        ? (nextProduct < (chunksProduct?.length || 0))
        : (nextShip < (chunksShip?.length || 0));
      if (seeMore) seeMore.style.display = moreAvail ? 'inline-block' : 'none';
    }

    setTab('product');
    tabs.forEach(btn => btn.addEventListener('click', ()=> setTab(btn.dataset.tab)));

    // Escape helper
    const esc = (str) => String(str ?? '').replace(/[&<>"']/g, s => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'
    }[s]));

    function avatarHtml(avatarUrl){
      if(avatarUrl){
        return `<div class="avatar"><img src="${esc(avatarUrl)}" alt="" loading="lazy" /></div>`;
      }
      return `<div class="avatar">👤</div>`;
    }

    // Append a chunk of cards into a grid
    function appendChunk(grid, chunk){
      chunk.forEach(function(review){
        const article = document.createElement('article');
        article.className = 'review-card is-new';

        const url       = review.product_url   || '#';
        const title     = review.product_title || '<?php echo esc_js($rv_fallback_title); ?>';
        const name      = review.name          || 'Anonymní';
        const text      = review.text          || '';
        const headline  = review.headline      || '';
        const date      = review.assigned_date || '';
        const avatarUrl = review.avatar_url    || '';

        article.innerHTML = `
          <div class="card-top">
            <h3 class="product-title"><a href="${esc(url)}">${esc(title)}</a></h3>
            <div class="date">${esc(date)}</div>
          </div>
          <div class="stars">★★★★★</div>
          <div class="identity">
            ${avatarHtml(avatarUrl)}
            <div class="name">${esc(name)}</div>
            <span class="verified"><?php _e('Potvrzeno','your-textdomain'); ?></span>
          </div>
          ${headline ? `<div class="headline">${esc(headline)}</div>` : ''}
          <div class="content">${esc(text)}</div>
        `;
        grid.appendChild(article);
      });
    }

    seeMore && seeMore.addEventListener('click', function(e){
      e.preventDefault();
      seeMore.style.display='none';
      loader.style.display='block';

      setTimeout(function(){
        if(activeTab === 'product' && nextProduct < (chunksProduct?.length || 0)){
          appendChunk(gridP, chunksProduct[nextProduct]);
          nextProduct++;
        }else if(activeTab === 'shipping' && nextShip < (chunksShip?.length || 0)){
          appendChunk(gridS, chunksShip[nextShip]);
          nextShip++;
        }
        loader.style.display='none';
        const moreAvail = activeTab === 'product'
          ? (nextProduct < (chunksProduct?.length || 0))
          : (nextShip < (chunksShip?.length || 0));
        if(moreAvail) seeMore.style.display='inline-block';
      }, 400);
    });
  });
</script>

<!-- new review styling -->
<style>
/* ===== Reviews: Full corrected CSS ===== */

/* Section + container */
#reviews-section{
  font-family: "Roboto", system-ui, -apple-system, Segoe UI, Arial, sans-serif;
  background:#f9f9f9;
}
.basic-reviews-section-container{
  max-width:1440px;
  margin:0 auto;
  padding:0 0px;
}

/* Tabs */
.reviews-tabs{ display:flex; gap:18px; border-bottom:1px solid #eee; margin-bottom:18px; }
.reviews-tab{
  appearance:none; background:transparent; border:1px solid transparent; border-bottom:0;
  padding:8px 14px; font-weight:700; cursor:pointer;
}
.reviews-tab.is-active{ background:#00000008; border-color:#e6e6e6; }

/* Grid */
.reviews-grid{
  display:grid;
  grid-template-columns:repeat(3, 1fr);
  gap:10px;
  width:100%;
}
@media (max-width:1100px){
  .reviews-grid{ grid-template-columns:repeat(2, 1fr); }
}
@media (max-width:640px){
  .reviews-grid{ grid-template-columns:1fr; }
}

/* Card */
.review-card{
  width:100%;
  height:100%;
  background:#fff;
  border:1px solid #efefef;
  border-radius:4px;
  box-shadow: 0px 4px 16px rgba(0, 0, 0, 0.1);
  padding:18px 20px;
  display:flex;
  flex-direction:column;
}

/* Card top */
.review-card .card-top{
  display:flex; align-items:flex-start; justify-content:space-between; gap:12px;
  margin:-2px 0 6px;
}
.review-card .product-title{
  margin:0; font-weight:800; font-size:16px; line-height:1.25;
}
.review-card .product-title a{
  color:#0e0e0e; text-decoration:underline; text-underline-offset:2px;
}
.review-card .date{
  color:#8c8c8c; font-size:13px; white-space:nowrap; margin-top:2px;
}

/* Stars */
.review-card .stars{
  letter-spacing:3px; font-size:18px; color:#0f0f0f; margin:2px 0 10px;
}

/* Identity */
.review-card .identity{
    
  display:flex;
  align-items:flex-start;   /* ⬅️ top-align items */
  gap:12px;
  margin:2px 0 12px;
  
  
}
.review-card .avatar{
  width:32px; height:32px;
  border:1px solid #dfdfdf;
  border-radius:0px;
  display:flex; align-items:center; justify-content:center;
  font-size:18px; color:#000; background:#fff;
  overflow:hidden;
}
.review-card .avatar img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
}
.review-card .name{ font-weight:700; color:#111; font-size:15px; }
.review-card .verified{
  display:inline-block; background:#0f0f0f; color:#fff;
  font-size:12px; font-weight:700; line-height:1;
  padding:5px 8px 4px; border-radius:3px; margin-left:6px;
}

/* Headline + body */
.review-card .headline{ font-weight:800; font-size:16px; color:#111; margin:6px 0 6px; }
.review-card .content{ color:#2b2b2b; font-size:15px; line-height:1.7; }

/* Reveal for appended cards */
.review-card.is-new{ animation:rv-fade .28s ease-out both; }
@keyframes rv-fade{ from{opacity:0; transform:translateY(6px);} to{opacity:1; transform:none;} }

/* Loader */
#reviews-loading .loader{
  width:28px; height:28px; border:3px solid #e6e6e6; border-top-color:#111; border-radius:50%;
  margin:0 auto; animation:rv-spin .75s linear infinite;
}
@keyframes rv-spin{ to{ transform:rotate(360deg);} }



/* Default avatar (placeholder) stays 32x32 */
.review-card .avatar{
  width:32px;
  height:32px;
  border:1px solid #dfdfdf;
  border-radius:0px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:18px;
  color:#000;
  background:#fff;
  overflow:hidden;
}

/* If avatar contains a real image -> make it 80x80 */
.review-card .avatar:has(img){
  width:250px;
  height:250px;
  font-size:0; /* hide any accidental text spacing */
  align-items:stretch;
  justify-content:stretch;
}

.review-card .avatar img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
}

/* ONLY reviews with real image */
.review-card .identity:has(.avatar img){
  display:block;              /* ⬅️ image gets own row */
}

/* Real image wrapper */
.review-card .avatar:has(img){
  width:100%;
  height:auto;
  border:none;
  margin-bottom:10px;
}

/* Real image itself */
.review-card .avatar img{
  width:100%;
  max-width:320px;
  height:auto;
  display:block;
  object-fit:cover;
  border:1px solid #dfdfdf;
  border-radius:4px;
}

/* Name + verified BELOW image */
.review-card .identity:has(.avatar img) .name,
.review-card .identity:has(.avatar img) .verified{
  display:inline-block;
  vertical-align:middle;
}


@media (max-width: 991px){

  /* ONLY reviews with real image */
  .review-card .avatar:has(img){
    max-width:100%;
  }

  .review-card .avatar img{
    width:100%;        /* ✅ full width on mobile */
    max-width:100%;
    height:auto;
  }

}


</style>






<?php
$faq_list = get_field('faq_list', 'option');
$faq_list2 = get_field('faq_list_2', 'option');
$faq_list3 = get_field('faq_list_3', 'option');

// Special orto products: swap ONLY the product-info FAQ container ("...o produktu");
// delivery/returns containers stay untouched.
$is_ortopas_faq    = ( function_exists('noriks_is_type') && noriks_is_type('ortopas') );
$is_bunion_faq     = ( function_exists('noriks_is_type') && noriks_is_type('bunion') );
$is_fisiorest_faq  = ( function_exists('noriks_is_type') && noriks_is_type('fisiorest') );
$is_norikshers_faq = ( function_exists('noriks_is_type') && noriks_is_type('norikshers') );
$is_leakboxers_faq = ( function_exists('noriks_is_type') && noriks_is_type('leakboxers') );
$is_kompmajice_faq = ( function_exists('noriks_is_type') && noriks_is_type('kompresijske-majice') );
$is_kidsnest_faq   = ( function_exists('noriks_is_type') && noriks_is_type('kidsnest') );
$is_kneefix_faq   = ( function_exists('noriks_is_type') && noriks_is_type('kneefix') );
$is_jastuk_faq     = ( function_exists('noriks_is_type') && noriks_is_type('ortopedski-jastuk') );

// NORIKS FIT (kompresní/tvarující tričko) — FAQ o produktu (překlad, NORIKS FIT).
$kompmajice_faq = array(
  array(
    'questioon' => 'Pro koho je NORIKS FIT určen?',
    'answer'    => 'NORIKS FIT je stvořen pro muže, kteří chtějí štíhlejší vzhled, vrátit si sebevědomí ve vlastní tělo, napravit držení těla, cítit se během dne energičtěji a vypadat štíhleji pod jakýmkoli oblečením.'
  ),
  array(
    'questioon' => 'Jak tričko NORIKS FIT vlastně funguje?',
    'answer'    => 'NORIKS FIT používá pokročilou iontovou kompresní tkaninu, která aktivuje přirozenou reakci těla. Mikrotkaná vlákna podporují zdravou cirkulaci a pomáhají vám udržet vzpřímené držení těla od rána do večera. Při pravidelném nošení přináší viditelně tvarovanější trup, lepší zarovnání páteře a více sebevědomí.'
  ),
  array(
    'questioon' => 'Jak rychle si všimnu výsledků?',
    'answer'    => 'Každé tělo je jiné, ale většina zákazníků hlásí viditelnou změnu během prvních 30 dnů. Pro nejlepší účinek noste NORIKS FIT každý den a kombinujte ho s vyváženou stravou a pravidelným pohybem.'
  ),
  array(
    'questioon' => 'Je vidět pod košilí?',
    'answer'    => 'Ne. NORIKS FIT je tenký, diskrétní a neviditelný pod jakoukoli košilí, a přitom tvaruje břicho a hrudník a podporuje držení těla.'
  ),
  array(
    'questioon' => 'Jak se pere a z čeho je vyroben?',
    'answer'    => 'Je vyroben z 80 % nylonu a 20 % elastanu. Perte ho ve studené vodě na šetrný program, abyste zachovali kompresi a prodloužili životnost tkaniny.'
  ),
);

// NORIKS LEAK BOXERS (inkontinenční boxerky) — FAQ o produktu (překlad, NORIKS).
$leakboxers_faq = array(
  array(
    'questioon' => 'Proč si NORIKS vybralo více než 123 000 mužů?',
    'answer'    => 'NORIKS jsou nejsavější pratelné boxerky pro mužský únik moči: pojmou až 300 ml, mají certifikaci Oeko-Tex® a neobsahují škodlivé látky, jsou pratelné a opakovaně použitelné (ekologická alternativa jednorázových vložek), navržené pro celodenní pohodlí a sebevědomí. Celých 87 % zákazníků si po prvním nákupu objedná znovu.'
  ),
  array(
    'questioon' => 'Kolik absorbují?',
    'answer'    => 'Až 300 ml — téměř 3× více než většina produktů na trhu. Díky 7vrstvému jádru PureDry™ se tekutina okamžitě absorbuje a uzamkne hluboko uvnitř, kůže zůstává suchá a vnější vrstva je voděodolná.'
  ),
  array(
    'questioon' => 'Jsou vidět pod oblečením?',
    'answer'    => 'Ne. Boxerky NORIKS jsou tenké, diskrétní a pružné — vypadají a působí jako běžné prádlo, bez objemnosti a bez pocitu „pleny“.'
  ),
  array(
    'questioon' => 'Jak se perou?',
    'answer'    => 'Perte na 30–40 °C, bez aviváže a bělidla, sušte na vzduchu. Savost si udrží i po stovkách praní.'
  ),
  array(
    'questioon' => 'Je doručení diskrétní?',
    'answer'    => 'Ano. Všechny objednávky posíláme v neutrálním, diskrétním balení bez viditelného označení obsahu, abychom chránili vaše soukromí.'
  ),
  array(
    'questioon' => 'Z čeho jsou vyrobeny?',
    'answer'    => 'Vnější vrstva z bambusového vlákna s elastanem, 7vrstvé savé jádro z technických mikrovláken a voděodolná prodyšná membrána.'
  ),
);

// KidsNest dětský polštář — FAQ o produktu (NORIKS, zmírněná tvrzení).
$kneefix_faq = array(
  array(
    'questioon' => 'Je KneeFix vhodný pro každodenní nošení?',
    'answer'    => 'Ano. NORIKS KneeFix byl vyvinut právě pro běžné pohyby — chůzi, práci, chození po schodech či delší stání.',
  ),
  array(
    'questioon' => 'Mohu si kompresi nastavit sám?',
    'answer'    => 'Ano. Vestavěným přesným kolečkem si kompresi nastavíte sami — tolik opory, kolik je vám příjemné.',
  ),
  array(
    'questioon' => 'Sjíždí ortéza při chůzi?',
    'answer'    => 'KneeFix má protiskluzový silikonový okraj, který pomáhá omezit sjíždění a rolování ortézy během nošení.',
  ),
  array(
    'questioon' => 'Mohu ortézu nosit pod oblečením?',
    'answer'    => 'Ano. Pružný a tenký střih umožňuje pohodlné nošení pod většinou běžného oblečení.',
  ),
  array(
    'questioon' => 'Je ortéza vhodná pro obě kolena?',
    'answer'    => 'Při objednávce vyberete stranu (levá nebo pravá), takže ortéza sedne přesně tomu kolenu, které chcete podepřít.',
  ),
  array(
    'questioon' => 'Mohu ortézu nosit delší dobu?',
    'answer'    => 'Ortéza je vyvinuta pro každodenní nošení. Mnozí zákazníci ji nosí v práci, na procházce i při běžných činnostech.',
  ),
  array(
    'questioon' => 'Jak vybrat velikost?',
    'answer'    => 'Velikosti jsou určeny podle tělesné hmotnosti: M (50–75 kg), L (76–90 kg), XL (91–110 kg) a 2XL (110 kg+).',
  ),
);

$kidsnest_faq = array(
  array( 'questioon' => 'Jak rychle uvidím, že dýchání ústy přestává?', 'answer' => 'Většina rodičů si všimne tiššího dýchání a méně probouzení s otevřenými ústy během prvních 5–7 nocí. Do 14. noci se u většiny dětí zklidní chrápání a rty zůstávají zavřené. Plný rozdíl — viditelně lepší polohu a klidnější spánek — rodiče nejčastěji popisují kolem 21. až 30. dne. Používejte ho každou noc.' ),
  array( 'questioon' => 'Pro jaký věk je KidsNest určen?', 'answer' => 'KidsNest je k dispozici ve třech velikostech: 1–3, 3–9 a 9–18 let. Nejdůležitější okno je mezi 3. a 9. rokem, kdy se patro a čelist vyvíjejí nejintenzivněji — ale každý věk má svou velikost a svůj přínos.' ),
  array( 'questioon' => 'Je bezpečný? Co je uvnitř?', 'answer' => 'KidsNest je vyroben z hypoalergenní paměťové pěny s certifikací OEKO-TEX® — bez formaldehydu, těžkých kovů a BPA. Je odolný vůči roztočům a prodyšný a potah se dá sundat a vyprat v pračce.' ),
  array( 'questioon' => 'Bude ho mé dítě opravdu používat?', 'answer' => 'Ano. Ergonomický tvar působí jako opora, ne jako něco zvláštního — většina dětí si zvykne za 1–2 noci. Rodiče často hlásí, že děti po prvním týdnu nechtějí spát bez něj. 3zónová struktura přirozeně přijímá hlavu — neexistuje „správný způsob“, žádné boje před spaním.' ),
  array( 'questioon' => 'Funguje, i když mé dítě už dýchá ústy?', 'answer' => 'Ano — právě pro takové děti je navržen. 3zónová struktura pomáhá zabránit zaklánění hlavy, kvůli kterému se ústa ve spánku otevírají. U většiny dětí se během 7–14 nocí rty přirozeně zavřou a dýchání nosem se vrátí.' ),
  array( 'questioon' => 'Co když mému dítěti nepomůže?', 'answer' => 'Nechte dítě spát na KidsNestu 30 nocí. Pokud nevidíte rozdíl — méně dýchání ústy, tišší noci, klidnější spánek — ozvěte se nám a vrátíme vám peníze. Bez otázek a bez drobného písma.' ),
);

// ErgoSit ortopedický polštář — FAQ o produktu (NORIKS).
$jastuk_faq = array(
  array( 'questioon' => 'Jak NORIKS ErgoSit zmírňuje bolest při sezení?', 'answer' => 'ErgoSit má výřez pro kostrč, který odstraňuje přímý tlak na kostrční kost a sedací nerv, zatímco anatomický tvar z paměťové pěny s vysokou hustotou rovnoměrně rozkládá váhu na kyčle a stehna. Tím se odlehčí citlivá místa a podpoří zdravé, vzpřímené držení těla.' ),
  array( 'questioon' => 'Kde všude mohu polštář používat?', 'answer' => 'Kdekoli sedíte — v autě, na kancelářské židli, na jídelní židli, na invalidním vozíku nebo doma. Stabilní protiskluzová základna ho drží na místě, takže pohodlí jde s vámi celý den.' ),
  array( 'questioon' => 'Neproleží se pěna časem?', 'answer' => 'Ne. ErgoSit používá paměťovou pěnu s vysokou hustotou, která si udrží tvar a pevnost i po dlouhodobém každodenním používání — na rozdíl od levných polštářů, které se rychle proleží.' ),
  array( 'questioon' => 'Dá se potah prát?', 'answer' => 'Ano. Potah se sundává a lze ho prát v pračce, takže polštář zůstává svěží a čistý. Tkanina je prodyšná, hypoalergenní a certifikovaná OEKO-TEX®.' ),
  array( 'questioon' => 'Sedne na moji židli nebo sedadlo?', 'answer' => 'ErgoSit má univerzální tvar a sedne na většinu autosedadel, kancelářských a kuchyňských židlí i invalidních vozíků. Žádné velikosti — jeden model padne všem.' ),
  array( 'questioon' => 'Za jak dlouho pocítím rozdíl?', 'answer' => 'Většina uživatelů pocítí menší tlak na kostrč a pohodlnější sezení už od prvního dne. Lepší držení těla a méně bolesti zad se dále buduje pravidelným používáním.' ),
  array( 'questioon' => 'Existuje záruka vrácení peněz?', 'answer' => 'Ano, každý NORIKS ErgoSit přichází se zárukou pohodlí na 60 dní. Pokud nepocítíte méně bolesti a více pohodlí, kontaktujte nás a vyřešíme to.' ),
);

// Korektor vbočeného palce — FAQ o produktu (překlad, NORIKS).
$bunion_faq = array(
  array( 'questioon' => 'Jak rychle se budu cítit lépe?', 'answer' => 'Přibližně 30 minut — tolik času je potřeba, aby se zmírnilo nepohodlí. Při pravidelném používání po dobu dvou týdnů pocítíte výraznou úlevu při každodenních činnostech, jako je chůze, stání nebo spánek.' ),
  array( 'questioon' => 'Jak rychle si všimnu rozdílu na vbočeném palci?', 'answer' => 'V závislosti na závažnosti vbočeného palce si většina zákazníků všimne viditelného zlepšení po 4–8 týdnech. Mírný stav: 4 týdny. Střední stav: 4 týdny. Těžší stav: 8 týdnů.' ),
  array( 'questioon' => 'Dá se nosit v botách? Můžu v něm chodit?', 'answer' => 'Ne, do boty se nevejde. Ano, můžete v něm chodit. Je však určen pro klidový režim — když ležíte na gauči, díváte se na TV, čtete nebo spíte.' ),
  array( 'questioon' => 'Co když mi to bude nepříjemné?', 'answer' => 'To je zcela normální! NORIKS korektor je navržen dostatečně pevně, aby srovnal kloub palce, zastavil zánět a zmírnil nepohodlí. Možná budete potřebovat 1–2 relace, než si zvyknete, poté se budete cítit mnohem lépe!' ),
  array( 'questioon' => 'Jak dlouho ho mám používat?', 'answer' => 'Doporučujeme začít 30 minutami denně a postupně zvyšovat až na relaci 1 až 3 hodiny. Až vám bude pohodlně, můžete ho začít nosit i během spánku. Noste ho při odpočinku — na gauči, u TV, při čtení nebo spánku.' ),
  array( 'questioon' => 'Pomůže mi u mého konkrétního stavu?', 'answer' => 'NORIKS korektor je ideální pro: zmírnění nepohodlí, které ovlivňuje každodenní činnosti, jako je chůze nebo stání; úlevu od nepohodlí způsobeného vbočeným palcem během odpočinku či spánku; řešení vbočeného palce v rané fázi, který může postupovat; vbočený palec, který se vrátil po operaci; pomoc u těžšího stavu připraveného k operaci; a jako účinnou nechirurgickou možnost.' ),
  array( 'questioon' => 'Bude vyhovovat mému chodidlu? Existuje levá a pravá strana?', 'answer' => 'Bez ohledu na velikost chodidla — od nejmenšího dětského po velké chodidlo dospělého — se NORIKS korektor pohodlně přizpůsobí. Nemá strany! Díky přizpůsobitelné konstrukci se stejně snadno přizpůsobí levému i pravému chodidlu.' ),
);

// Ortopedický pás — FAQ o produktu (překlad, NORIKS).
$ortopas_faq = array(
  array( 'questioon' => 'Jak rychle pocítím úlevu od bolesti?', 'answer' => 'Mnoho uživatelů pocítí znatelnou úlevu od ischiasu a bolesti v kříži ihned po nasazení pásu NORIKS. Jeho cílená komprese nabízí okamžitou oporu, stabilizuje páteř a snižuje tlak na nervy. Pro dlouhotrvající účinek doporučujeme pás nosit důsledně podle návodu alespoň dva týdny. Postupem času můžete při správném používání a zdravých návycích pocítit trvalou úlevu a lepší pohyblivost.' ),
  array( 'questioon' => 'Jak pás správně nasadit?', 'answer' => 'Pás NORIKS noste kolem kyčlí, kousek pod linií pasu. Měl by se nacházet nad křížovou oblastí (spodní část zad, těsně nad hýžděmi) a pod hřebenem pánve (horní část bočních kyčlí). Více informací najdete v návodu k použití.' ),
  array( 'questioon' => 'Neoslabí pás moje svaly?', 'answer' => 'Ne, pás NORIKS neoslabuje svaly jako klasický korzet na záda. Pouze pomáhá držet SI klouby pohromadě a obnovuje normální napětí vazů. Můžete ho nosit týdny nebo měsíce bez obav z atrofie svalů.' ),
  array( 'questioon' => 'Můžu pás nosit i během spánku?', 'answer' => 'Ano, pás lze nosit i v noci. Doba nošení není omezena a delší nošení nemá negativní účinky.' ),
  array( 'questioon' => 'Jak pevně ho mám nasadit?', 'answer' => 'Pás by měl těsně přiléhat, ale ne příliš, abyste se vyhnuli nepohodlí. Měli byste se bez problémů pohybovat, aniž by se pás zařezával nebo sklouzával. Napětí se snadno nastavuje elastickými pásky.' ),
  array( 'questioon' => 'Komu ho doporučujete?', 'answer' => 'Všem, kdo se potýkají s bolestmi v kříži, ischiasem, svalovým napětím, výhřezem meziobratlové ploténky, bolestmi kyčlí nebo pánve a potížemi se SI kloubem. Bez ohledu na věk, pohlaví, výšku a váhu.' ),
  array( 'questioon' => 'Existuje záruka vrácení peněz?', 'answer' => 'Nabízíme záruku spokojenosti! Pokud nejste s pásem NORIKS spokojeni, kontaktujte nás na info@noriks.com pro vrácení a proplacení do 90 dnů. Lhůta se počítá od převzetí pásu.' ),
);

// FisioRest — FAQ o produktu (překlad, NORIKS).
$fisiorest_faq = array(
  array( 'questioon' => 'Jak NORIKS FisioRest funguje?', 'answer' => 'FisioRest kombinuje trakci, teplo a vibrační masáž s ergonomickým designem z paměťové pěny. Tato technologie protahuje krk pod přesně správným úhlem a odlehčuje krční páteř. Poté uklidňující teplá masáž podpoří přísun krve bohaté na kyslík a živiny do svalů a pomáhá tak regeneraci tkání.' ),
  array( 'questioon' => 'V čem je FisioRest lepší než jiné přístroje?', 'answer' => 'NORIKS FisioRest je výjimečný, protože spojuje <strong>tři terapie v jedné</strong> — teplo, masáž a jemnou trakci — které uvolní svaly a znovu srovnají krk pro dlouhotrvající úlevu. Navíc je <strong>bezdrátový, bezpečný pro spánek a obalený chladivým hedvábím</strong> pro pohodlí, jaké jinde nenajdete.' ),
  array( 'questioon' => 'Jak se FisioRest používá?', 'answer' => '1. Nabijte ho přiloženým USB-C kabelem a nabíječkou přibližně 4 až 6 hodin. 2. Podržte tlačítko masáže nebo tepla 5 sekund, dokud se nerozsvítí kontrolka. 3. Opětovným stiskem tlačítek měníte rychlost masáže a nastavení tepla. 4. Užijte si uvolňující masáž!' ),
  array( 'questioon' => 'Jak dlouho mám FisioRest používat?', 'answer' => 'Doporučujeme začít 15 minutami, aby si krk zvykl. Postupem času můžete přejít na plnou relaci. Pro orientaci: cyklus jemného tepla, masáže a trakce trvá 30 minut, což je obvykle ideální doba, aby se krk uvolnil a obnovil své přirozené zakřivení.' ),
  array( 'questioon' => 'Je FisioRest bezdrátový?', 'answer' => 'Ano! NORIKS FisioRest je zcela bezdrátový a dobíjecí pro každodenní použití.' ),
  array( 'questioon' => 'Jak se FisioRest čistí?', 'answer' => 'Látka je odolná vůči olejům a prachu, ale doporučujeme FisioRest po použití otřít dezinfekčním ubrouskem, protože potah polštáře není pratelný.' ),
  array( 'questioon' => 'Je bezpečný pro každého?', 'answer' => 'NORIKS FisioRest je navržen tak, aby vyhovoval všem bez ohledu na věk nebo pohlaví. Každá situace je však jiná. Pro podrobné pokyny přizpůsobené vašim potřebám doporučujeme konzultaci s lékařem.' ),
  array( 'questioon' => 'Můžu ho vrátit, pokud nevidím výsledky?', 'answer' => 'Samozřejmě! Nabízíme plnou záruku vrácení peněz do 90 dnů od doručení, pokud nejste s produktem spokojeni. Napište nám na info@noriks.com a odpovíme do 12 hodin od přijetí zprávy!' ),
);

// NORIKS HERS (silikonové kolagenové pásky na vrásky) — FAQ o produktu (překlad, NORIKS HERS).
$norikshers_faq = array(
  array( 'questioon' => 'Čím se liší od běžných náplastí na vrásky nebo krémů na jizvy?', 'answer' => 'Většina náplastí na vrásky je vyrobena z papíru nebo hydrokoloidu a krémy na jizvy často zůstávají jen na povrchu pokožky. NORIKS HERS používá silikon klinické kvality, kterému dermatologové léta důvěřují při viditelném zlepšení textury jizev a pružnosti pokožky — a nyní se používá i k redukci vrásek.' ),
  array( 'questioon' => 'Může jedna páska opravdu působit na vrásky i jizvy zároveň?', 'answer' => 'Ano, protože vrásky i jizvy jsou známkou rozpadu kolagenu nebo slabé regenerace pokožky. Silikon podporuje udržení vlhkosti, obnovu kolagenu a vyhlazení textury pokožky, což prospívá oběma.' ),
  array( 'questioon' => 'Za jak dlouho uvidím výsledky?', 'answer' => 'Většina uživatelek zaznamená viditelné vyhlazení jemných linií už po 1–3 použitích a vzhled jizev se zlepší během 2–3 týdnů pravidelného používání. Hlubší jizvy a vrásky mohou trvat déle, ale výsledky se v čase budují.' ),
  array( 'questioon' => 'Je bezpečný pro citlivou nebo k akné náchylnou pokožku?', 'answer' => 'Rozhodně. NORIKS HERS je hypoalergenní, bez latexu a dostatečně šetrný pro citlivé oblasti, jako je okolí očí nebo úst, i pro hojící se stopy po akné. Pokud máte velmi reaktivní pokožku, vždy nejprve otestujte na malé ploše.' ),
  array( 'questioon' => 'Jak dlouho ho mohu nosit?', 'answer' => 'Pro nejlepší výsledky doporučujeme nosit NORIKS HERS 6–8 hodin přes noc. Můžete ho použít i přes den — jen dbejte na to, aby byla pokožka pod ním čistá a bez olejů či sér.' ),
  array( 'questioon' => 'Jak dlouho vydrží jedno balení?', 'answer' => 'V závislosti na tom, jak často a kde ho používáte, jeden kus vydrží 3–6 týdnů. Protože je opakovaně použitelný, je mnohem úspornější než jednorázové náplasti nebo krémy.' ),
  array( 'questioon' => 'Zůstane na místě, když spím?', 'answer' => 'Ano! NORIKS HERS je vyroben s šetrným, odolným lepidlem, které kopíruje vaše pohyby. Je prodyšný a zůstane na místě, i když spíte na boku.' ),
  array( 'questioon' => 'Na které oblasti ho mohu použít?', 'answer' => 'Kdekoli! Většina zákaznic používá NORIKS HERS na: vrásky na čele, vrásky mezi obočím, vrásky od úsměvu, vrásky na krku, stopy po akné, jizvy po císařském řezu, strie a jizvy po operacích či úrazech.' ),
  array( 'questioon' => 'Čím je NORIKS HERS lepší než levné náplasti z internetu?', 'answer' => 'Mnoho náplastí prodávaných online má nízkou kvalitu, jsou tenké nebo mají špatné lepidlo. NORIKS HERS používá prémiový silikon, testovaný v laboratoři na bezpečnost a odolnost, a zůstává na místě celou noc. Navíc nabízíme vyhrazenou zákaznickou podporu a rychlejší výměnu, pokud budete potřebovat pomoc.' ),
  array( 'questioon' => 'Existuje záruka vrácení peněz?', 'answer' => 'Ano, nabízíme 30denní záruku bez rizika. Pokud nejste spokojeni, jednoduše nás kontaktujte a vyřešíme to.' ),
);

$faq_pick = function( $title, $list ) use ( $is_ortopas_faq, $ortopas_faq, $is_bunion_faq, $bunion_faq, $is_fisiorest_faq, $fisiorest_faq, $is_norikshers_faq, $norikshers_faq, $is_leakboxers_faq, $leakboxers_faq, $is_kompmajice_faq, $kompmajice_faq, $is_kidsnest_faq, $kidsnest_faq, $is_jastuk_faq, $jastuk_faq, $is_kneefix_faq, $kneefix_faq ) {
  $is_info = ( stripos( (string) $title, 'produktu' ) !== false );
  if ( $is_kneefix_faq && $is_info )    { return $kneefix_faq; }
  if ( $is_kidsnest_faq && $is_info )  { return $kidsnest_faq; }
  if ( $is_jastuk_faq && $is_info )    { return $jastuk_faq; }
  if ( $is_leakboxers_faq && $is_info ) { return $leakboxers_faq; }
  if ( $is_kompmajice_faq && $is_info ) { return $kompmajice_faq; }
  if ( $is_norikshers_faq && $is_info ) { return $norikshers_faq; }
  if ( $is_fisiorest_faq && $is_info ) { return $fisiorest_faq; }
  if ( $is_bunion_faq && $is_info )    { return $bunion_faq; }
  if ( $is_ortopas_faq && $is_info )   { return $ortopas_faq; }
  return $list;
};
?>





<section class="faq-section">
  <h2>Často kladené otázky</h2>
  

   <!-- first faq container --> 
      <div class="faq-container">
         <h4 style="text-align:left; font-size: 1rem;
            font-weight: 700;
            color: #222223;
            margin-bottom: 10px; "><?php echo get_field('faq_title_1', 'option'); ?></h4>
            <?php
              $faq_list = $faq_pick( get_field('faq_title_1', 'option'), $faq_list );
              if( $faq_list && is_array($faq_list) ):
                      foreach( $faq_list as $faq_item ):
              ?>
                    <div class="faq-item">
                      <button class="faq-question">
                         <?php echo $faq_item["questioon"]; ?>
                        <span class="arrow">&#9660;</span>
                      </button>
                      <div class="faq-answer">
                        <p>  <?php echo $faq_item["answer"]; ?></p>
                      </div>
                    </div>
          <?php endforeach;
            endif;
            ?>
      </div>
    <!-- first faq container --> 
  
     <!-- 2 faq container --> 
      <div class="faq-container">
          <br/>
         <h4 style="text-align:left; font-size: 1rem;
            font-weight: 700;
            color: #001e36;
            margin-bottom: 10px; "><?php echo get_field('faq_title_2', 'option'); ?></h4>
            <?php
              $faq_list2 = $faq_pick( get_field('faq_title_2', 'option'), $faq_list2 );
              if( $faq_list2 && is_array($faq_list2) ):
                      foreach( $faq_list2 as $faq_item ):
              ?>
                    <div class="faq-item">
                      <button class="faq-question">
                         <?php echo $faq_item["questioon"]; ?>
                        <span class="arrow">&#9660;</span>
                      </button>
                      <div class="faq-answer">
                        <p>  <?php echo $faq_item["answer"]; ?></p>
                      </div>
                    </div>
          <?php endforeach;
            endif;
            ?>
      </div>
        <!-- 2 faq container --> 
  
     <!-- 3 faq container --> 
      <div class="faq-container">
          <br/>
         <h4 style="text-align:left; font-size: 1rem;
            font-weight: 700;
            color: #001e36;
            margin-bottom: 10px; "><?php echo get_field('faq_title_3', 'option'); ?></h4>
            <?php
              $faq_list3 = $faq_pick( get_field('faq_title_3', 'option'), $faq_list3 );
              if( $faq_list3 && is_array($faq_list3) ):
                      foreach( $faq_list3 as $faq_item ):
              ?>
                    <div class="faq-item">
                      <button class="faq-question">
                         <?php echo $faq_item["questioon"]; ?>
                        <span class="arrow">&#9660;</span>
                      </button>
                      <div class="faq-answer">
                        <p>  <?php echo $faq_item["answer"]; ?></p>
                      </div>
                    </div>
          <?php endforeach;
            endif;
            ?>
      </div>
  <!-- 3 faq container --> 
  
</section>

<script>
  document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {
      const faqAnswer = button.nextElementSibling;
      const arrow = button.querySelector('.arrow');

      if (faqAnswer.style.maxHeight) {
        faqAnswer.style.maxHeight = null;
        arrow.style.transform = 'rotate(0deg)';
      } else {
        document.querySelectorAll('.faq-answer').forEach(item => {
          item.style.maxHeight = null;
        });
        document.querySelectorAll('.arrow').forEach(item => {
          item.style.transform = 'rotate(0deg)';
        });
        faqAnswer.style.maxHeight = faqAnswer.scrollHeight + 'px';
        arrow.style.transform = 'rotate(180deg)';
      }
    });
  });
</script>
		


