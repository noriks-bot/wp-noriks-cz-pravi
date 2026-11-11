<?php
/**
 * Balíkovna — výdejny, pošty a boxy (Česká pošta) na blagajni CZ.
 *
 * Celotna logika izbire dostave na blagajni:
 *   - privzeto "Doručení na adresu" (obstojeci flat_rate / free_shipping),
 *   - druga moznost "Výdejny, pošty a boxy (Balíkovna)" z iskalnikom mest.
 * Izbrano mesto se shrani k narocilu, pokaze v adminu, v mailu, na strani
 * zahvale in se zapise v naslov za dostavo tako, kot zahteva Metakocka.
 *
 * ---------------------------------------------------------------------------
 * METAKOCKA — pogodba o podatkih (https://metakocka.freshdesk.com/sl/support/
 * solutions/articles/3000106618):
 *   Metakocka vrsto dostave Ceske poste prepozna po nazivu nacina dostave v
 *   WooCommerce (shipping_lines[].method_title):
 *       "Balík na poštu"      -> NP  (dostava na posto)
 *       "Balík do Balíkovny"  -> NB  (dostava v Balikovno / box / partnerja)
 *       "Standardní"          -> DR  (dostava na naslov)
 *   Zato je privzeti naziv tega nacina dostave tocno "Balík do Balíkovny".
 *   Napis na kartici na blagajni je locen (NORIKS_BALIKOVNA_NAPIS) in nanj ne
 *   vpliva.
 *   Pri NB/NP so ulica, hisna stevilka in mesto prazni — steje samo postna
 *   stevilka, v kateri je sifra prevzemnega mesta. To naredi
 *   noriks_balikovna_naslov_za_metakocko().
 *
 * Za delovanje na strani Metakocke je potrebna se povezava z Ceskim postom
 * (Dodatne nastavitve -> Dostavne sluzbe -> Posta Ceska: Pogodba (ID) +
 * ID klienta), ki jo dobis od Ceske poste.
 * ---------------------------------------------------------------------------
 *
 * Podatki: data/balikovna-mista.tsv (12 149 mest), pretvorjeno iz uradnega
 * CSV Ceske poste ZV_BALIKOVNY.zip:
 *   https://www.balikovna.cz/documents/20124/15302876/ZV_BALIKOVNY.zip
 * Osvezitev seznama: prenesi ZIP, razpakiraj in pozeni
 *   python3 data/balikovna-pretvori.py ZV_BALIKOVNY.csv data/balikovna-mista.tsv
 * Depoji in prazne vrstice so izpusceni; ostanejo poste, partnerji in boksi.
 *
 * Iskanje tece na strezniku (REST /wp-json/noriks/v1/balikovna?q=…), ker je
 * seznam 2,8 MB in ga ni smiselno posiljati v brskalnik.
 *
 * VKLOP: kartica Balíkovna se pojavi sele, ko nacin dostave dodas v
 * WooCommerce -> Nastavitve -> Doprava -> obmocje CZ. Dokler ga ni, blagajna
 * izgleda in dela tako kot prej.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

const NORIKS_BALIKOVNA_ID    = 'noriks_balikovna';
const NORIKS_BALIKOVNA_META  = '_balikovna';
/** Naziv nacina dostave, po katerem Metakocka prepozna NB. Ne spreminjaj. */
const NORIKS_BALIKOVNA_MK    = 'Balík do Balíkovny';
/** Napis na kartici na blagajni (kupcu). */
const NORIKS_BALIKOVNA_NAPIS = 'Výdejny, pošty a boxy';
/** Napis na kartici za dostavo na naslov. */
const NORIKS_BALIKOVNA_DOMA  = 'Doručení na adresu';

/* ------------------------------------------------------------------ *
 * 1) Podatki o mestih
 * ------------------------------------------------------------------ */

function noriks_balikovna_datoteka() {
	return get_template_directory() . '/data/balikovna-mista.tsv';
}

/** Razclenitev ene vrstice TSV v polje. */
function noriks_balikovna_vrstica( $vrstica ) {
	$d = explode( "\t", rtrim( $vrstica, "\r\n" ) );
	if ( count( $d ) < 8 ) { return null; }
	return array(
		'id'     => $d[0],
		'naziv'  => $d[1],
		'naslov' => $d[2],
		'tip'    => $d[3],
		'ure'    => explode( '|', $d[4] ),
		'x'      => $d[5],
		'y'      => $d[6],
	);
}

/** Iskanje po seznamu. Vrne najvec $limit zadetkov. */
function noriks_balikovna_isci( $q, $limit = 25 ) {
	$pot = noriks_balikovna_datoteka();
	if ( ! is_readable( $pot ) ) { return array(); }

	$q = trim( mb_strtolower( $q, 'UTF-8' ) );
	if ( $q === '' ) { return array(); }
	$q = noriks_balikovna_brez_sumnikov( $q );
	// vec besed: vse morajo biti v zadetku (PSC + mesto, "praha vrsovice" ...)
	$deli = preg_split( '/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY );
	if ( ! $deli ) { return array(); }

	$ven = array();
	$f = @fopen( $pot, 'r' );
	if ( ! $f ) { return array(); }
	while ( ( $vrstica = fgets( $f ) ) !== false ) {
		$tab = strrpos( $vrstica, "\t" );
		if ( $tab === false ) { continue; }
		$iskalno = substr( $vrstica, $tab + 1 );   // zadnji stolpec = polje za iskanje
		$vse = true;
		foreach ( $deli as $del ) {
			if ( strpos( $iskalno, $del ) === false ) { $vse = false; break; }
		}
		if ( ! $vse ) { continue; }
		$m = noriks_balikovna_vrstica( $vrstica );
		if ( $m ) { $ven[] = $m; }
		if ( count( $ven ) >= $limit ) { break; }
	}
	fclose( $f );
	return $ven;
}

/** Eno mesto po sifri. */
function noriks_balikovna_mesto( $id ) {
	$pot = noriks_balikovna_datoteka();
	$id  = trim( (string) $id );
	if ( $id === '' || ! is_readable( $pot ) ) { return null; }
	$iskano = $id . "\t";
	$f = @fopen( $pot, 'r' );
	if ( ! $f ) { return null; }
	while ( ( $vrstica = fgets( $f ) ) !== false ) {
		if ( strncmp( $vrstica, $iskano, strlen( $iskano ) ) === 0 ) {
			fclose( $f );
			return noriks_balikovna_vrstica( $vrstica );
		}
	}
	fclose( $f );
	return null;
}

/** Odstrani sumnike (iskanje naj dela tudi brez njih). */
function noriks_balikovna_brez_sumnikov( $s ) {
	$zamenjaj = array(
		'á'=>'a','č'=>'c','ď'=>'d','é'=>'e','ě'=>'e','í'=>'i','ň'=>'n','ó'=>'o','ř'=>'r',
		'š'=>'s','ť'=>'t','ú'=>'u','ů'=>'u','ý'=>'y','ž'=>'z','ä'=>'a','ô'=>'o','ĺ'=>'l','ľ'=>'l',
	);
	return strtr( $s, $zamenjaj );
}

/**
 * Odpiralni cas kot polje berljivih vrstic.
 * Uradni CSV Ceske poste je pri odpiralnem casu luknjast (npr. nonstop box
 * 10039 ima vpisane samo sredo, petek, soboto in nedeljo), zato praznega dneva
 * ne razglasimo za zaprtega, ampak ga pokazemo kot "—".
 */
function noriks_balikovna_ure_besedilo( $ure ) {
	$dnevi = array( 'Po', 'Út', 'St', 'Čt', 'Pá', 'So', 'Ne' );
	$vrstice = array();
	foreach ( $dnevi as $i => $dan ) {
		$v = isset( $ure[ $i ] ) ? trim( $ure[ $i ] ) : '';
		$vrstice[] = $dan . ': ' . ( $v === '' ? '—' : str_replace( ',', ', ', $v ) );
	}
	return $vrstice;
}

/* ------------------------------------------------------------------ *
 * 2) REST iskanje
 * ------------------------------------------------------------------ */

add_action( 'rest_api_init', function () {
	register_rest_route( 'noriks/v1', '/balikovna', array(
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function ( $zahteva ) {
			$q = (string) $zahteva->get_param( 'q' );
			if ( mb_strlen( $q, 'UTF-8' ) < 2 ) { return rest_ensure_response( array() ); }
			$ven = array();
			foreach ( noriks_balikovna_isci( $q, 25 ) as $m ) {
				$ven[] = array(
					'id'     => $m['id'],
					'naziv'  => $m['naziv'],
					'naslov' => $m['naslov'],
					'tip'    => $m['tip'],
					'ure'    => implode( ' · ', noriks_balikovna_ure_besedilo( $m['ure'] ) ),
				);
			}
			return rest_ensure_response( $ven );
		},
	) );
} );

/* ------------------------------------------------------------------ *
 * 3) Nacin dostave
 * ------------------------------------------------------------------ */

add_action( 'woocommerce_shipping_init', function () {
	if ( class_exists( 'Noriks_Balikovna_Shipping' ) || ! class_exists( 'WC_Shipping_Method' ) ) { return; }

	class Noriks_Balikovna_Shipping extends WC_Shipping_Method {
		public function __construct( $instance_id = 0 ) {
			$this->id                 = NORIKS_BALIKOVNA_ID;
			$this->instance_id        = absint( $instance_id );
			$this->method_title       = 'Balíkovna — výdejny, pošty a boxy';
			$this->method_description = 'Vyzvednutí na pobočce Balíkovny, na poště nebo v boxu. Zákazník si místo vybere v pokladně. Název musí zůstat „' . NORIKS_BALIKOVNA_MK . '“ — podle něj MetaKocka pozná typ zásilky NB.';
			$this->supports           = array( 'shipping-zones', 'instance-settings', 'instance-settings-modal' );
			$this->init();
		}
		public function init() {
			$this->instance_form_fields = array(
				'title' => array(
					'title'       => 'Název (MetaKocka)',
					'type'        => 'text',
					'default'     => NORIKS_BALIKOVNA_MK,
					'description' => 'MetaKocka podle tohoto názvu pozná zásilku NB. Neměňte.',
				),
				'cost' => array(
					'title'       => 'Cena (Kč)',
					'type'        => 'text',
					'default'     => '69',
					'description' => 'Cena dopravy. Prázdné = zdarma.',
				),
				'free_over' => array(
					'title'       => 'Zdarma od (Kč)',
					'type'        => 'text',
					'default'     => '1700',
					'description' => 'Nad touto hodnotou košíku je doprava zdarma. Prázdné = nikdy.',
				),
			);
			$this->title = $this->get_option( 'title' );
			if ( $this->title === '' ) { $this->title = NORIKS_BALIKOVNA_MK; }
			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		}
		public function calculate_shipping( $package = array() ) {
			$cena      = $this->get_option( 'cost', '' );
			$free_over = $this->get_option( 'free_over', '' );
			$strosek   = ( $cena === '' ) ? 0 : (float) str_replace( ',', '.', $cena );
			if ( $free_over !== '' ) {
				$meja = (float) str_replace( ',', '.', $free_over );
				$vsota = isset( $package['contents_cost'] ) ? (float) $package['contents_cost'] : 0;
				if ( $meja > 0 && $vsota >= $meja ) { $strosek = 0; }
			}
			$this->add_rate( array(
				'id'      => $this->id . ':' . $this->instance_id,
				'label'   => $this->title,
				'cost'    => $strosek,
				'package' => $package,
			) );
		}
	}
} );

add_filter( 'woocommerce_shipping_methods', function ( $nacini ) {
	$nacini[ NORIKS_BALIKOVNA_ID ] = 'Noriks_Balikovna_Shipping';
	return $nacini;
} );

/** Je ta kljuc ponudbe nacin Balíkovna? */
function noriks_balikovna_je_kljuc( $kljuc ) {
	return strpos( (string) $kljuc, NORIKS_BALIKOVNA_ID ) === 0;
}

/** Ali je v tej kosarici izbrana Balíkovna? */
function noriks_balikovna_izbrana() {
	$izbrani = ( function_exists( 'WC' ) && WC()->session ) ? WC()->session->get( 'chosen_shipping_methods' ) : array();
	if ( ! is_array( $izbrani ) ) { return false; }
	foreach ( $izbrani as $m ) {
		if ( noriks_balikovna_je_kljuc( $m ) ) { return true; }
	}
	return false;
}

/** Sifra mesta iz seje (prezivi AJAX osvezitev blagajne). */
function noriks_balikovna_seja_id() {
	if ( ! function_exists( 'WC' ) || ! WC()->session ) { return ''; }
	return (string) WC()->session->get( NORIKS_BALIKOVNA_META . '_id' );
}

/* ------------------------------------------------------------------ *
 * 4) Blagajna — kartice nacinov dostave + izbirnik mesta
 *    Kliče se iz woocommerce/checkout/form-checkout.php.
 * ------------------------------------------------------------------ */

/** Zelena kljukica na kartici (ista kot prej). */
function noriks_balikovna_kljukica() {
	return '<svg viewBox="0 0 19 14" fill="#3DBD00"><path fill-rule="evenodd" clip-rule="evenodd" d="M18.5725 3.40179L8.14482 13.5874C7.5815 14.1375 6.66839 14.1375 6.1056 13.5874L0.422493 8.03956C-0.140831 7.48994-0.140831 6.59748 0.422493 6.04707L1.44121 5.05126C2.00471 4.50094 2.91854 4.50094 3.48132 5.05126L7.12254 8.60835L15.5145 0.412609C16.078-0.137536 16.9909-0.137536 17.5537 0.412609L18.5733 1.40842C19.1424 1.95795 19.1424 2.8505 18.5725 3.40179Z"/></svg>';
}

/** Logotip Ceske poste na kartici (isti kot prej). */
function noriks_balikovna_logo() {
	return '<span class="delivery_img"><img decoding="async" class="ceska_posta standard" style="height:15px !important; width:70px !important;" src="https://www.ceskaposta.cz/documents/20117/1310244/Logo_Ceska_posta_horizontalni.svg/dfc68dee-9367-d5d9-c3e5-4087f47d7a7f?version=1.0&t=1754915125425"/></span>';
}

/**
 * Izrise seznam nacinov dostave na blagajni.
 * Brez Balíkovne izgleda enako kot prej (ena kartica, isti razredi CSS).
 */
function noriks_balikovna_nacini() {
	$ponudbe = array();
	if ( function_exists( 'WC' ) && WC()->shipping() ) {
		$paketi = WC()->shipping()->get_packages();
		if ( isset( $paketi[0]['rates'] ) && is_array( $paketi[0]['rates'] ) ) {
			$ponudbe = $paketi[0]['rates'];
		}
	}

	// Ce WooCommerce ne vrne nobene ponudbe, obdrzi videz blagajne kot prej.
	if ( ! $ponudbe ) {
		noriks_balikovna_rezervna_kartica();
		return;
	}

	// Locimo dostavo na naslov od Balíkovne.
	$doma = array();
	$bal  = array();
	foreach ( $ponudbe as $kljuc => $p ) {
		if ( noriks_balikovna_je_kljuc( $kljuc ) ) { $bal[ $kljuc ] = $p; } else { $doma[ $kljuc ] = $p; }
	}

	$izbrani  = WC()->session ? (array) WC()->session->get( 'chosen_shipping_methods' ) : array();
	$trenutni = isset( $izbrani[0] ) ? (string) $izbrani[0] : '';

	/* Na naslov pokazemo eno samo kartico — tisto, ki jo WooCommerce ze
	   uporablja (ob kosarici nad mejo je to brezplacna dostava). Tako je
	   blagajna enaka kot pred Balíkovno, le z eno novo moznostjo. */
	if ( $doma ) {
		$kljuc_doma = ( isset( $doma[ $trenutni ] ) ) ? $trenutni : key( $doma );
		$doma = array( $kljuc_doma => $doma[ $kljuc_doma ] );
	}

	$vrstni = $doma + $bal;   // dostava na naslov najprej = privzeta
	if ( $trenutni === '' || ! isset( $vrstni[ $trenutni ] ) ) {
		$kljuci   = array_keys( $vrstni );
		$trenutni = $kljuci ? $kljuci[0] : '';
	}

	$shranjen = noriks_balikovna_seja_id();
	$mesto    = $shranjen !== '' ? noriks_balikovna_mesto( $shranjen ) : null;
	$i        = 0;
	?>
	<ul class="shipping_method_custom">
		<?php foreach ( $vrstni as $kljuc => $p ) :
			$je_bal = noriks_balikovna_je_kljuc( $kljuc );
			$cena   = (float) $p->get_cost();
			$id_el  = 'shipping_method_0_' . sanitize_html_class( str_replace( ':', '_', $kljuc ) );
			$naslov = $je_bal ? NORIKS_BALIKOVNA_NAPIS : ( count( $vrstni ) > 1 ? NORIKS_BALIKOVNA_DOMA : '' );
			$i++;
			?>
			<li class="standard-shipping shipping-tab<?php echo $kljuc === $trenutni ? ' is-on' : ''; ?><?php echo $je_bal ? ' nbal-tab' : ''; ?>">
				<input name="shipping_method[0]" data-index="0" id="<?php echo esc_attr( $id_el ); ?>"
					value="<?php echo esc_attr( $kljuc ); ?>"
					class="shipping_method shipping_method_field"
					data-balikovna="<?php echo $je_bal ? '1' : '0'; ?>"
					type="radio" <?php checked( $kljuc, $trenutni ); ?>>
				<label for="<?php echo esc_attr( $id_el ); ?>" class="checkedlabel">
					<?php echo noriks_balikovna_kljukica(); ?>
					<div class="outer-wrapper">
						<div class="inner-wrapper-dates">
							<?php if ( $naslov !== '' ) : ?>
								<span class="nbal-naslov"><?php echo esc_html( $naslov ); ?></span>
							<?php endif; ?>
							<strong class="hs-custom-date js-delivery-dates"<?php echo $i === 1 ? ' id="js-delivery-dates"' : ''; ?>></strong>
						</div>
						<div class="inner-wrapper-img">
							<span class="shipping_method_delivery_price tag tag--red"><?php
								echo $cena > 0 ? wp_kses_post( wc_price( $cena ) ) : 'Zdarma';
							?></span>
							<?php echo noriks_balikovna_logo(); ?>
						</div>
					</div>
				</label>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php if ( $bal ) : ?>
	<div id="nbal-pick" class="nbal-pick"<?php echo noriks_balikovna_je_kljuc( $trenutni ) ? '' : ' hidden'; ?>>
		<input type="hidden" id="nbal_id" name="nbal_id" value="<?php echo esc_attr( $shranjen ); ?>">
		<label class="nbal-q-lab" for="nbal_q">Vyberte výdejní místo</label>
		<input type="text" id="nbal_q" class="nbal-q" autocomplete="off"
			placeholder="Zadejte PSČ, ulici nebo město odběrného místa.">
		<ul id="nbal_list" class="nbal-list" hidden></ul>

		<div id="nbal_vybrano" class="nbal-sel"<?php echo $mesto ? '' : ' hidden'; ?>>
			<div class="nbal-sel-h">Místo vyzvednutí:</div>
			<table class="nbal-tab">
				<tr><th>Název:</th>  <td id="nbal_naziv"><?php echo $mesto ? esc_html( $mesto['naziv'] ) : ''; ?></td></tr>
				<tr><th>Adresa:</th> <td id="nbal_naslov"><?php echo $mesto ? esc_html( $mesto['naslov'] ) : ''; ?></td></tr>
				<tr><th>Typ:</th>    <td id="nbal_tip"><?php echo $mesto ? esc_html( $mesto['tip'] ) : ''; ?></td></tr>
				<tr><th>Otevírací doba:</th>
					<td><a href="#" id="nbal_ure_gumb">Zobrazit</a>
						<div id="nbal_ure" class="nbal-ure" hidden><?php
							if ( $mesto ) { echo esc_html( implode( ' · ', noriks_balikovna_ure_besedilo( $mesto['ure'] ) ) ); }
						?><div class="nbal-ure-vir">Zdroj: Česká pošta</div></div></td></tr>
			</table>
			<a href="#" id="nbal_zmena" class="nbal-zmena">Změnit místo</a>
		</div>
		<div id="nbal_napaka" class="nbal-napaka" hidden>Vyberte prosím výdejní místo.</div>
	</div>

	<style>
	#custom_shipping .shipping_method_custom li + li { margin-top: 6px !important; }
	#custom_shipping .shipping_method_custom li:not(.is-on) .checkedlabel {
		background: #fff !important; border-color: #d8d8d8 !important;
	}
	#custom_shipping .shipping_method_custom li:not(.is-on) label svg { fill: #cfcfcf !important; }
	#custom_shipping .nbal-naslov {
		display: block !important; font-size: 14px; font-weight: 700; line-height: 1.3;
	}
	#custom_shipping .inner-wrapper-dates .hs-custom-date { color: #555; font-weight: 500 !important; }
	.nbal-pick { margin: 10px 0 0; position: relative; }
	.nbal-q-lab { display: block; font-size: 13.5px; color: #555; margin: 0 0 5px; }
	.nbal-q { width: 100%; padding: 12px 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 15px; box-sizing: border-box; }
	.nbal-list { position: absolute; z-index: 30; left: 0; right: 0; margin: 2px 0 0; padding: 0; list-style: none;
		background: #fff; border: 1px solid #ccc; border-radius: 6px; max-height: 320px; overflow: auto;
		box-shadow: 0 10px 28px rgba(0,0,0,.12); }
	.nbal-list li { padding: 10px 14px; cursor: pointer; font-size: 14px; line-height: 1.35; border-bottom: 1px solid #f0f0f0; }
	.nbal-list li:last-child { border-bottom: 0; }
	.nbal-list li:hover, .nbal-list li.is-on { background: #1f6fbf; color: #fff; }
	.nbal-list li .nbal-li-t { font-weight: 600; }
	.nbal-list li .nbal-li-a { opacity: .85; }
	.nbal-sel { margin-top: 12px; border: 1px solid #e3e3e3; border-radius: 8px; padding: 12px 14px; background: #fafafa; }
	.nbal-sel-h { font-weight: 700; margin-bottom: 8px; }
	.nbal-tab { width: 100%; border-collapse: collapse; font-size: 14px; }
	.nbal-tab th { text-align: left; font-weight: 400; color: #666; padding: 4px 10px 4px 0; vertical-align: top; width: 130px; }
	.nbal-tab td { padding: 4px 0; font-weight: 600; }
	.nbal-ure { font-weight: 400; color: #444; margin-top: 6px; font-size: 13px; }
	.nbal-ure-vir { color: #888; font-size: 12px; margin-top: 4px; }
	.nbal-zmena { display: inline-block; margin-top: 8px; font-size: 13.5px; }
	.nbal-napaka { color: #c0392b; font-size: 13.5px; margin-top: 6px; }
	</style>

	<script>
	(function(){
	  var koren = <?php echo wp_json_encode( esc_url_raw( rest_url( 'noriks/v1/balikovna' ) ) ); ?>;
	  var wrap  = document.getElementById('custom_shipping'); if (!wrap) return;
	  var q     = document.getElementById('nbal_q');
	  var lista = document.getElementById('nbal_list');
	  var skrit = document.getElementById('nbal_id');
	  var okvir = document.getElementById('nbal_vybrano');
	  var pick  = document.getElementById('nbal-pick');
	  var napaka= document.getElementById('nbal_napaka');
	  var cas;

	  function izbran(){ return wrap.querySelector('input[name="shipping_method[0]"]:checked'); }
	  function jeBal(){ var r = izbran(); return !!r && r.getAttribute('data-balikovna') === '1'; }

	  function osvezi(){
	    var r = izbran();
	    wrap.querySelectorAll('.shipping_method_custom li').forEach(function(li){
	      li.classList.toggle('is-on', !!r && li.contains(r));
	    });
	    if (pick) pick.hidden = !jeBal();
	    if (napaka) napaka.hidden = true;
	  }
	  osvezi();

	  wrap.addEventListener('change', function(e){
	    if (!e.target.matches('input[name="shipping_method[0]"]')) return;
	    osvezi();
	    jQuery(document.body).trigger('update_checkout');
	  });

	  if (q) q.addEventListener('input', function(){
	    clearTimeout(cas);
	    var v = q.value.trim();
	    if (v.length < 2) { lista.hidden = true; lista.innerHTML = ''; return; }
	    cas = setTimeout(function(){
	      fetch(koren + '?q=' + encodeURIComponent(v))
	        .then(function(r){ return r.json(); })
	        .then(function(d){
	          lista.innerHTML = '';
	          if (!d || !d.length) {
	            lista.innerHTML = '<li class="nbal-prazno">Nic nenalezeno.</li>';
	          } else {
	            d.forEach(function(m){
	              var li = document.createElement('li');
	              li.setAttribute('data-id', m.id);
	              li.innerHTML = '<span class="nbal-li-t"></span> <span class="nbal-li-a"></span>';
	              li.querySelector('.nbal-li-t').textContent = m.naziv;
	              li.querySelector('.nbal-li-a').textContent = m.naslov;
	              li.addEventListener('click', function(){ izberi(m); });
	              lista.appendChild(li);
	            });
	          }
	          lista.hidden = false;
	        })
	        .catch(function(){ lista.hidden = true; });
	    }, 220);
	  });

	  function izberi(m){
	    skrit.value = m.id;
	    document.getElementById('nbal_naziv').textContent  = m.naziv;
	    document.getElementById('nbal_naslov').textContent = m.naslov;
	    document.getElementById('nbal_tip').textContent    = m.tip;
	    var ure = document.getElementById('nbal_ure');
	    if (ure) { ure.hidden = true; ure.textContent = m.ure || ''; }
	    q.value = '';
	    lista.hidden = true;
	    lista.innerHTML = '';
	    q.hidden = true;
	    document.querySelector('.nbal-q-lab').hidden = true;
	    okvir.hidden = false;
	    if (napaka) napaka.hidden = true;
	    /* shrani v sejo, da izbira prezivi osvezitev blagajne */
	    jQuery(document.body).trigger('update_checkout');
	  }

	  var zmena = document.getElementById('nbal_zmena');
	  if (zmena) zmena.addEventListener('click', function(e){
	    e.preventDefault();
	    okvir.hidden = true;
	    q.hidden = false;
	    document.querySelector('.nbal-q-lab').hidden = false;
	    q.focus();
	  });

	  var gumb = document.getElementById('nbal_ure_gumb');
	  if (gumb) gumb.addEventListener('click', function(e){
	    e.preventDefault();
	    var ure = document.getElementById('nbal_ure');
	    if (ure) ure.hidden = !ure.hidden;
	  });

	  document.addEventListener('click', function(e){
	    if (lista && !lista.hidden && !lista.contains(e.target) && e.target !== q) { lista.hidden = true; }
	  });

	  /* ne pusti oddati narocila brez izbranega mesta */
	  jQuery('form.checkout').on('checkout_place_order', function(){
	    if (jeBal() && (!skrit.value || skrit.value === '')) {
	      if (napaka) napaka.hidden = false;
	      if (pick) pick.scrollIntoView({ behavior: 'smooth', block: 'center' });
	      return false;
	    }
	    return true;
	  });
	})();
	</script>
	<?php
	endif;
}

/** Ko WooCommerce ne vrne ponudb: ista kartica kot pred Balíkovno. */
function noriks_balikovna_rezervna_kartica() {
	$cena = ( function_exists( 'WC' ) && WC()->cart ) ? (float) WC()->cart->get_shipping_total() : 0;
	?>
	<ul class="shipping_method_custom">
		<li class="standard-shipping shipping-tab is-on">
			<input name="shipping_method[0]" data-index="0" id="shipping_method_0_standard_custom"
				value="standard" class="shipping_method shipping_method_field" type="radio" checked>
			<label for="shipping_method_0_standard_custom" class="checkedlabel">
				<?php echo noriks_balikovna_kljukica(); ?>
				<div class="outer-wrapper">
					<div class="inner-wrapper-dates">
						<strong class="hs-custom-date js-delivery-dates" id="js-delivery-dates"></strong>
					</div>
					<div class="inner-wrapper-img">
						<span class="shipping_method_delivery_price tag tag--red"><?php
							echo $cena > 0 ? wp_kses_post( wc_price( $cena ) ) : 'Zdarma';
						?></span>
						<?php echo noriks_balikovna_logo(); ?>
					</div>
				</div>
			</label>
		</li>
	</ul>
	<?php
}

/* Izbrano mesto shrani v sejo, da prezivi osvezitev blagajne. */
add_action( 'woocommerce_checkout_update_order_review', function ( $podatki ) {
	parse_str( $podatki, $p );
	if ( isset( $p['nbal_id'] ) && function_exists( 'WC' ) && WC()->session ) {
		WC()->session->set( NORIKS_BALIKOVNA_META . '_id', sanitize_text_field( $p['nbal_id'] ) );
	}
} );

/* ------------------------------------------------------------------ *
 * 5) Preverjanje in shranjevanje k narocilu
 * ------------------------------------------------------------------ */

/** Sifra mesta iz POST ali iz seje. */
function noriks_balikovna_poslan_id() {
	$id = isset( $_POST['nbal_id'] ) ? sanitize_text_field( wp_unslash( $_POST['nbal_id'] ) ) : '';
	if ( $id === '' ) { $id = noriks_balikovna_seja_id(); }
	return $id;
}

add_action( 'woocommerce_checkout_process', function () {
	if ( ! noriks_balikovna_izbrana() ) { return; }
	$id = noriks_balikovna_poslan_id();
	if ( $id === '' || ! noriks_balikovna_mesto( $id ) ) {
		wc_add_notice( 'Vyberte prosím výdejní místo Balíkovny.', 'error' );
	}
}, 5 );

/**
 * Naslov za dostavo po zahtevi Metakocke:
 * pri NB sta ulica in mesto prazna, sifra mesta je v postni stevilki.
 * Izklop: add_filter( 'noriks_balikovna_prazen_naslov', '__return_false' );
 */
function noriks_balikovna_naslov_za_metakocko( $narocilo, $m ) {
	$narocilo->set_shipping_postcode( $m['id'] );
	if ( apply_filters( 'noriks_balikovna_prazen_naslov', true, $narocilo, $m ) ) {
		$narocilo->set_shipping_address_1( '' );
		$narocilo->set_shipping_address_2( '' );
		$narocilo->set_shipping_city( '' );
	}
}

add_action( 'woocommerce_checkout_create_order', function ( $narocilo ) {
	if ( ! noriks_balikovna_izbrana() ) { return; }
	$m = noriks_balikovna_mesto( noriks_balikovna_poslan_id() );
	if ( ! $m ) { return; }

	$narocilo->update_meta_data( NORIKS_BALIKOVNA_META . '_id',     $m['id'] );
	$narocilo->update_meta_data( NORIKS_BALIKOVNA_META . '_nazev',  $m['naziv'] );
	$narocilo->update_meta_data( NORIKS_BALIKOVNA_META . '_adresa', $m['naslov'] );
	$narocilo->update_meta_data( NORIKS_BALIKOVNA_META . '_typ',    $m['tip'] );

	noriks_balikovna_naslov_za_metakocko( $narocilo, $m );
}, 10, 1 );

/* Zapis v opombo narocila, da se mesto ne izgubi, tudi ce se naslov pobrise. */
add_action( 'woocommerce_checkout_order_created', function ( $narocilo ) {
	$m = noriks_balikovna_iz_narocila( $narocilo );
	if ( ! $m ) { return; }
	$narocilo->add_order_note( sprintf(
		'Balíkovna (%s): %s, %s — kód %s',
		$m['tip'], $m['naziv'], $m['naslov'], $m['id']
	) );
}, 10, 1 );

/* ------------------------------------------------------------------ *
 * 6) Prikaz izbranega mesta
 * ------------------------------------------------------------------ */

function noriks_balikovna_iz_narocila( $narocilo ) {
	if ( ! $narocilo || ! is_a( $narocilo, 'WC_Order' ) ) { return null; }
	$id = $narocilo->get_meta( NORIKS_BALIKOVNA_META . '_id' );
	if ( ! $id ) { return null; }
	return array(
		'id'     => $id,
		'naziv'  => $narocilo->get_meta( NORIKS_BALIKOVNA_META . '_nazev' ),
		'naslov' => $narocilo->get_meta( NORIKS_BALIKOVNA_META . '_adresa' ),
		'tip'    => $narocilo->get_meta( NORIKS_BALIKOVNA_META . '_typ' ),
	);
}

/* admin: pod naslovom za dostavo */
add_action( 'woocommerce_admin_order_data_after_shipping_address', function ( $narocilo ) {
	$m = noriks_balikovna_iz_narocila( $narocilo );
	if ( ! $m ) { return; }
	echo '<p><strong>Balíkovna:</strong><br>' . esc_html( $m['naziv'] ) . '<br>'
	   . esc_html( $m['naslov'] ) . '<br><small>' . esc_html( $m['tip'] )
	   . ' · kód ' . esc_html( $m['id'] ) . '</small></p>';
}, 10, 1 );

/* mail kupcu in trgovini */
add_action( 'woocommerce_email_after_order_table', function ( $narocilo ) {
	$m = noriks_balikovna_iz_narocila( $narocilo );
	if ( ! $m ) { return; }
	echo '<h2>Místo vyzvednutí</h2><p>' . esc_html( $m['naziv'] ) . '<br>'
	   . esc_html( $m['naslov'] ) . '<br>' . esc_html( $m['tip'] ) . '</p>';
}, 15, 1 );

/* stran zahvale */
add_action( 'woocommerce_thankyou', function ( $id_narocila ) {
	$m = noriks_balikovna_iz_narocila( wc_get_order( $id_narocila ) );
	if ( ! $m ) { return; }
	echo '<section class="nbal-ty"><h2>Místo vyzvednutí</h2><p><strong>'
	   . esc_html( $m['naziv'] ) . '</strong><br>' . esc_html( $m['naslov'] ) . '</p></section>';
}, 15, 1 );

/* opozorilo v adminu, ce seznam mest manjka */
add_action( 'admin_notices', function () {
	if ( ! current_user_can( 'manage_woocommerce' ) ) { return; }
	if ( is_readable( noriks_balikovna_datoteka() ) ) { return; }
	echo '<div class="notice notice-warning"><p><strong>Balíkovna:</strong> manjka '
	   . '<code>data/balikovna-mista.tsv</code>, zato izbirnik prevzemnih mest ne dela.</p></div>';
} );
