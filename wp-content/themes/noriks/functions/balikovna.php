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
const NORIKS_BALIKOVNA_LOGO    = 'https://images.vigo-shop.com/general/curriers/balikovna_transaprent.png';
/* Logotip za dostavo na naslov, ko je Balíkovna VKLOPLJENA. Logotip z
   ceskaposta.cz se ne nalozi (streznik blokira vroce povezave), zato je tam
   logotip "Balíkovna na adresu" — isti prevoznik. */
const NORIKS_BALIKOVNA_LOGO_CP = 'https://images.vigo-shop.com/general/curriers/balikovna_adres_transparent.png';
/* Logotip, kakrsen je bil na blagajni PRED nadgradnjo. Uporablja ga rezervna
   kartica (izklopljeno stanje), da se na CZ ne spremeni prav nicesar. */
const NORIKS_BALIKOVNA_LOGO_PREJ = 'https://www.ceskaposta.cz/documents/20117/1310244/Logo_Ceska_posta_horizontalni.svg/dfc68dee-9367-d5d9-c3e5-4087f47d7a7f?version=1.0&t=1754915125425';

/*
 * STIKALO: functions/paketomati-stikalo.php (en sam vnos v temi).
 * Ce je izklopljeno, se ta modul sploh ne nalozi in blagajna dela natanko
 * tako kot pred nadgradnjo.
 */
if ( ! function_exists( 'noriks_balikovna_vklopljena' ) || ! noriks_balikovna_vklopljena() ) { return; }


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
	if ( $q === '' ) { return noriks_balikovna_prva( $limit ); }
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

/**
 * Prvih $limit mest s seznama — kar kupec vidi, preden kaj natipka.
 * Datoteka je urejena po sifri mesta, ta pa se zacne s postno stevilko, zato
 * so na vrhu praska mesta (enako kot pri konkurenci).
 */
function noriks_balikovna_prva( $limit = 150 ) {
	$pot = noriks_balikovna_datoteka();
	if ( ! is_readable( $pot ) ) { return array(); }
	$ven = array();
	$f = @fopen( $pot, 'r' );
	if ( ! $f ) { return array(); }
	while ( ( $vrstica = fgets( $f ) ) !== false ) {
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
			$q = trim( (string) $zahteva->get_param( 'q' ) );
			if ( $q !== '' && mb_strlen( $q, 'UTF-8' ) < 2 ) { return rest_ensure_response( array() ); }
			$limit = ( $q === '' ) ? 150 : 25;
			$ven = array();
			foreach ( noriks_balikovna_isci( $q, $limit ) as $m ) {
				$ven[] = array(
					'id'     => $m['id'],
					'naziv'  => $m['naziv'],
					'naslov' => $m['naslov'],
					'tip'    => $m['tip'],
					'obec'   => noriks_balikovna_obec( $m ),
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

/**
 * Ali je izbrana Balíkovna? Gleda oddani obrazec (ob oddaji narocila je
 * merodajen POST, ker seja se ni osvezena) in nato sejo.
 */
function noriks_balikovna_izbrana() {
	// ko je modul izklopljen, prevzemno mesto ni mogoce izbrati
	if ( function_exists( 'noriks_balikovna_vklopljena' ) && ! noriks_balikovna_vklopljena() ) { return false; }
	if ( isset( $_POST['shipping_method'] ) ) {
		foreach ( (array) $_POST['shipping_method'] as $m ) {
			if ( noriks_balikovna_je_kljuc( sanitize_text_field( wp_unslash( $m ) ) ) ) { return true; }
		}
	}
	$wc = function_exists( 'WC' ) ? WC() : null;
	$izbrani = ( $wc && $wc->session ) ? $wc->session->get( 'chosen_shipping_methods' ) : array();
	if ( ! is_array( $izbrani ) ) { return false; }
	foreach ( $izbrani as $m ) {
		if ( noriks_balikovna_je_kljuc( $m ) ) { return true; }
	}
	return false;
}

/** Sifra mesta iz seje (prezivi AJAX osvezitev blagajne). */
function noriks_balikovna_seja_id() {
	$wc = function_exists( 'WC' ) ? WC() : null;
	if ( ! $wc || ! $wc->session ) { return ''; }
	return (string) $wc->session->get( NORIKS_BALIKOVNA_META . '_id' );
}

/* ------------------------------------------------------------------ *
 * 4) Blagajna
 *    Razporeditev sledi vigoshop.cz:
 *      telefon -> e-posta -> "Způsob doručení" (kartici levo/desno)
 *      -> ime/priimek -> izbirnik mesta (ko je izbrana Balíkovna; takrat so
 *      polja naslova skrita) -> "Doprava" (datumi + cena + logotip).
 * ------------------------------------------------------------------ */


/** Ponudbe dostave, locene na "na naslov" in "Balíkovna". */
function noriks_balikovna_ponudbe() {
	$ponudbe = array();
	$wc = function_exists( 'WC' ) ? WC() : null;
	if ( $wc && $wc->shipping() ) {
		$paketi = $wc->shipping()->get_packages();
		if ( isset( $paketi[0]['rates'] ) && is_array( $paketi[0]['rates'] ) ) {
			$ponudbe = $paketi[0]['rates'];
		}
	}
	$doma = array();
	$bal  = array();
	foreach ( $ponudbe as $kljuc => $p ) {
		if ( noriks_balikovna_je_kljuc( $kljuc ) ) { $bal[ $kljuc ] = $p; } else { $doma[ $kljuc ] = $p; }
	}

	$izbrani  = ( $wc && $wc->session ) ? (array) $wc->session->get( 'chosen_shipping_methods' ) : array();
	$trenutni = isset( $izbrani[0] ) ? (string) $izbrani[0] : '';

	/* Na naslov pokazemo eno samo kartico — najcenejso. Tako kupec nikoli ne
	   vidi placljive dostave, ce je brezplacna ze dosezena (izbira v seji je
	   lahko stara in bi sicer obvisela na placljivi). */
	if ( $doma ) {
		$kljuc_doma = null;
		foreach ( $doma as $k => $r ) {
			if ( $kljuc_doma === null || (float) $r->get_cost() < (float) $doma[ $kljuc_doma ]->get_cost() ) {
				$kljuc_doma = $k;
			}
		}
		$doma = array( $kljuc_doma => $doma[ $kljuc_doma ] );
	}
	$vse = $doma + $bal;
	if ( $trenutni === '' || ! isset( $vse[ $trenutni ] ) ) {
		$kljuci   = array_keys( $vse );
		$trenutni = $kljuci ? $kljuci[0] : '';   // privzeto dostava na naslov
	}
	return array( 'doma' => $doma, 'bal' => $bal, 'vse' => $vse, 'izbran' => $trenutni );
}

/** Zelena kljukica na kartici dostave. */
function noriks_balikovna_kljukica() {
	return '<svg viewBox="0 0 19 14" fill="#3DBD00"><path fill-rule="evenodd" clip-rule="evenodd" d="M18.5725 3.40179L8.14482 13.5874C7.5815 14.1375 6.66839 14.1375 6.1056 13.5874L0.422493 8.03956C-0.140831 7.48994-0.140831 6.59748 0.422493 6.04707L1.44121 5.05126C2.00471 4.50094 2.91854 4.50094 3.48132 5.05126L7.12254 8.60835L15.5145 0.412609C16.078-0.137536 16.9909-0.137536 17.5537 0.412609L18.5733 1.40842C19.1424 1.95795 19.1424 2.8505 18.5725 3.40179Z"/></svg>';
}

/** "Obec" v povzetku mesta: sifra + kraj (kot ima vigoshop.cz). */
function noriks_balikovna_obec( $m ) {
	$deli = array_map( 'trim', explode( ',', (string) $m['naslov'] ) );
	$kraj = '';
	$mesto = '';
	foreach ( $deli as $i => $d ) {
		if ( preg_match( '/^\d{3} ?\d{2}$/', $d ) ) {
			$kraj  = isset( $deli[ $i - 1 ] ) ? $deli[ $i - 1 ] : '';
			$mesto = isset( $deli[ $i + 1 ] ) ? $deli[ $i + 1 ] : '';
			break;
		}
	}
	if ( $kraj === '' ) { return $m['id']; }
	return $m['id'] . ' ' . $kraj . ( $mesto !== '' && $mesto !== $kraj ? ' (' . $mesto . ')' : '' );
}

/**
 * "Způsob doručení" — kartici levo/desno. Izrise se nad polji za ime in
 * naslov, tako kot pri konkurenci.
 */
function noriks_balikovna_izbira() {
	$p = noriks_balikovna_ponudbe();
	if ( ! $p['bal'] ) { return; }   // dokler nacin ni vklopljen, se blagajna ne spremeni

	$kljuc_doma = $p['doma'] ? key( $p['doma'] ) : '';
	$kljuc_bal  = key( $p['bal'] );
	$je_bal     = noriks_balikovna_je_kljuc( $p['izbran'] );
	?>
	<!-- nbal 2026-11-12-d -->
	<div class="hs-delivery-type-container cz-balikovna">
		<div class="container__title">Způsob doručení</div>
		<div class="container__buttons">
			<?php if ( $kljuc_doma !== '' ) : ?>
			<div class="delivery-type hs-delivery-home enabled<?php echo $je_bal ? '' : ' active'; ?>"
				data-rate="<?php echo esc_attr( $kljuc_doma ); ?>" data-balikovna="0">
				<p>Doručení na adresu</p>
				<img decoding="async" src="<?php echo esc_url( NORIKS_BALIKOVNA_LOGO_CP ); ?>" alt="Česká pošta">
			</div>
			<?php endif; ?>
			<div class="delivery-type hs-delivery-machine enabled<?php echo $je_bal ? ' active' : ''; ?>"
				data-rate="<?php echo esc_attr( $kljuc_bal ); ?>" data-balikovna="1">
				<p><?php echo esc_html( NORIKS_BALIKOVNA_NAPIS ); ?></p>
				<img decoding="async" src="<?php echo esc_url( NORIKS_BALIKOVNA_LOGO ); ?>" alt="Balíkovna">
			</div>
		</div>
		<input type="hidden" name="shipping_method[0]" id="nbal_rate" value="<?php echo esc_attr( $p['izbran'] ); ?>">
	</div>
	<?php
}

/** Izbirnik prevzemnega mesta + povzetek izbranega mesta. */
function noriks_balikovna_izbirnik() {
	$p = noriks_balikovna_ponudbe();
	if ( ! $p['bal'] ) { return; }

	$je_bal   = noriks_balikovna_je_kljuc( $p['izbran'] );
	$shranjen = noriks_balikovna_seja_id();
	$mesto    = $shranjen !== '' ? noriks_balikovna_mesto( $shranjen ) : null;
	?>
	<div class="form-row form-row-wide col-xs-12 nbal-row" id="nbal_field"<?php echo $je_bal ? '' : ' style="display:none"'; ?>>
		<span class="woocommerce-input-wrapper">
			<select name="nbal_id" id="nbal_select" class="select form-input"
				data-placeholder="Vyberte balíkovnu">
				<option value=""></option>
				<?php if ( $mesto ) : ?>
					<option value="<?php echo esc_attr( $mesto['id'] ); ?>" selected="selected"><?php
						echo esc_html( $mesto['naziv'] . ' ' . $mesto['naslov'] );
					?></option>
				<?php endif; ?>
			</select>
			<input type="text" id="nbal_q" class="nbal-q" autocomplete="off" hidden
				placeholder="Vyberte balíkovnu">
			<ul id="nbal_list" class="nbal-list" hidden></ul>
		</span>
		<img class="nbal-logo" decoding="async" src="<?php echo esc_url( NORIKS_BALIKOVNA_LOGO ); ?>" alt="Balíkovna">
		<span id="nbal_napaka" class="nbal-napaka" hidden>Vyberte prosím výdejní místo.</span>
	</div>

	<div id="nbal_vybrano" class="nbal-sel"<?php echo ( $je_bal && $mesto ) ? '' : ' style="display:none"'; ?>>
		<div class="nbal-sel-h">Místo vyzvednutí:</div>
		<table class="nbal-tab">
			<tr><th>Název:</th>  <td id="nbal_naziv"><?php echo $mesto ? esc_html( $mesto['naziv'] ) : ''; ?></td></tr>
			<tr><th>Adresa:</th> <td id="nbal_naslov"><?php echo $mesto ? esc_html( $mesto['naslov'] ) : ''; ?></td></tr>
			<tr><th>Obec:</th>   <td id="nbal_obec"><?php echo $mesto ? esc_html( noriks_balikovna_obec( $mesto ) ) : ''; ?></td></tr>
			<tr><th>Otevírací doba:</th>
				<td><a href="#" id="nbal_ure_gumb">Zobrazit</a>
					<div id="nbal_ure" class="nbal-ure" hidden><?php
						if ( $mesto ) { echo esc_html( implode( ' · ', noriks_balikovna_ure_besedilo( $mesto['ure'] ) ) ); }
					?></div>
					<div class="nbal-ure-vir" hidden>Zdroj: Česká pošta</div></td></tr>
		</table>
	</div>
	<?php
	noriks_balikovna_slog_in_skripta();
}

/** Blok "Doprava" v drugem stolpcu: datumi, cena, logotip prevoznika. */
function noriks_balikovna_nacini() {
	$p = noriks_balikovna_ponudbe();

	/* Izklopljeno (nacina Balíkovna ni v coni) ali brez ponudb: izrisemo
	   natanko tisto kartico, kot je bila na blagajni pred to nadgradnjo. */
	if ( ! $p['vse'] || ! $p['bal'] ) { noriks_balikovna_rezervna_kartica(); return; }

	$je_bal  = noriks_balikovna_je_kljuc( $p['izbran'] );
	$ponudba = $p['vse'][ $p['izbran'] ];
	$cena    = (float) $ponudba->get_cost();
	?>
	<ul class="shipping_method_custom">
		<li class="standard-shipping shipping-tab is-on">
			<label class="checkedlabel">
				<?php echo noriks_balikovna_kljukica(); ?>
				<div class="outer-wrapper">
					<div class="inner-wrapper-dates">
						<strong class="hs-custom-date js-delivery-dates" id="js-delivery-dates"></strong>
					</div>
					<div class="inner-wrapper-img">
						<span class="shipping_method_delivery_price tag tag--red"><?php
							echo $cena > 0 ? wp_kses_post( wc_price( $cena ) ) : 'Zdarma';
						?></span>
						<span class="delivery_img">
							<img decoding="async" class="ceska_posta standard" id="nbal_doprava_logo"
								style="height:15px !important; width:70px !important;"
								src="<?php echo esc_url( $je_bal ? NORIKS_BALIKOVNA_LOGO : NORIKS_BALIKOVNA_LOGO_CP ); ?>">
						</span>
					</div>
				</div>
			</label>
		</li>
	</ul>
	<?php
}

/** Ko WooCommerce ne vrne ponudb: ista kartica kot pred Balíkovno. */
function noriks_balikovna_rezervna_kartica() {
	$wc   = function_exists( 'WC' ) ? WC() : null;
	$cena = ( $wc && $wc->cart ) ? (float) $wc->cart->get_shipping_total() : 0;
	?>
	<ul class="shipping_method_custom">
		<li class="standard-shipping shipping-tab">
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
						<span class="delivery_img"><img decoding="async" class="ceska_posta standard"
							style="height:15px !important; width:70px !important;"
							src="<?php echo esc_url( NORIKS_BALIKOVNA_LOGO_PREJ ); ?>"></span>
					</div>
				</div>
			</label>
		</li>
	</ul>
	<?php
}

/* Vrivanje v obrazec: kartici nad imenom, izbirnik pod priimkom. */
add_filter( 'woocommerce_form_field', function ( $polje, $kljuc ) {
	if ( ! is_checkout() ) { return $polje; }
	if ( $kljuc === 'billing_first_name' ) {
		ob_start(); noriks_balikovna_izbira(); return ob_get_clean() . $polje;
	}
	if ( $kljuc === 'billing_last_name' ) {
		ob_start(); noriks_balikovna_izbirnik(); return $polje . ob_get_clean();
	}
	return $polje;
}, 10, 2 );

/**
 * Slog izbirnika (enkrat) + prijava skripte v nogo.
 * Skripta mora v nogo: v obrazcu bi tekla, preden WooCommerce nalozi
 * jQuery.selectWoo, in izbirnik bi padel na rezervno besedilno polje.
 */
function noriks_balikovna_slog_in_skripta() {
	static $enkrat = false;
	if ( $enkrat ) { return; }
	$enkrat = true;
	add_action( 'wp_footer', 'noriks_balikovna_skripta', 99 );
	?>
	<style>
	/* Kartici nacina dostave — enak slog na CZ in PL.
	   Osnovo (razporeditev, barve, mobilno) da vgrajeni CSS blagajne, tu samo
	   poenotimo visino, logotip in napis, da sta trga enaka in da se daljsi
	   napis ne prelije cez rob. */
	.hs-delivery-type-container .container__buttons { align-items: stretch; }
	.hs-delivery-type-container .delivery-type {
		height: auto !important; min-height: 69px; padding: 8px !important;
		box-sizing: border-box; }
	.hs-delivery-type-container .delivery-type p {
		font-size: 14px !important; line-height: 18px !important; margin: 4px 0 0 !important;
		width: 100%; }
	.hs-delivery-type-container .delivery-type img {
		max-width: 72px !important; max-height: 32px !important; width: auto !important;
		padding: 3px !important; margin: 0 !important; object-fit: contain; }

	/* kljukica v bloku "Doprava" (CSS teme jo meri prek "label svg") */
	#custom_shipping .checkedlabel svg { width: 19px !important; height: 14px !important;
		flex: 0 0 19px !important; margin-right: 10px !important; }

	/* Izbirnik mesta — mere so vzete iz nasih polj na blagajni
	   (visina 60px, pisava 18px, obroba 1,5px #c9c9c9, zaobljenost 4px),
	   da stoji v vrsti enako kot Jméno, Ulice in ostala polja. */
	.nbal-row { margin: 0 0 10px !important; position: relative; }
	.nbal-row .select2-container { width: 100% !important; }
	.nbal-row .select2-container--default .select2-selection--single {
		height: 60px !important; border: 1.5px solid #c9c9c9 !important;
		border-radius: 4px !important; background: #fff !important; outline: none !important; }
	.nbal-row .select2-container--default .select2-selection--single .select2-selection__rendered {
		line-height: 57px !important; font-size: 18px !important; color: #333 !important;
		padding: 0 34px 0 18px !important; }
	.nbal-row .select2-container--default .select2-selection--single .select2-selection__placeholder {
		color: #8a8a8a !important; font-size: 18px !important; }
	.nbal-row .select2-container--default .select2-selection--single .select2-selection__arrow {
		height: 57px !important; right: 12px !important; }
	.nbal-row.is-filled .select2-container--default .select2-selection--single {
		border-color: #47b426 !important; background: #f2feee !important; }
	.select2-dropdown { border: 1.5px solid #c9c9c9 !important; border-radius: 4px !important; }
	.select2-container--default .select2-results__option { font-size: 15px !important; line-height: 1.35 !important; }
	.select2-container--default .select2-search--dropdown .select2-search__field {
		height: 44px !important; font-size: 16px !important; padding: 0 12px !important;
		border: 1.5px solid #c9c9c9 !important; border-radius: 4px !important; }
	.nbal-row .nbal-logo { max-width: 86px; height: auto; display: block; margin: 8px 0 0; }
	/* rezervno polje (ce selectWoo ni na voljo) — enake mere kot ostala polja */
	.nbal-q { width: 100%; height: 60px; padding: 0 18px; border: 1.5px solid #c9c9c9; border-radius: 4px;
		font-size: 18px; box-sizing: border-box; }
	.nbal-list { position: absolute; z-index: 30; left: 0; right: 0; margin: 2px 0 0; padding: 0; list-style: none;
		background: #fff; border: 1.5px solid #c9c9c9; border-radius: 4px; max-height: 320px; overflow: auto;
		box-shadow: 0 10px 28px rgba(0,0,0,.12); }
	.nbal-list li { padding: 10px 14px; cursor: pointer; font-size: 15px; line-height: 1.35; border-bottom: 1px solid #f0f0f0; }
	.nbal-list li:last-child { border-bottom: 0; }
	.nbal-list li:hover { background: #1f6fbf; color: #fff; }
	.nbal-napaka { color: #c0392b; font-size: 13.5px; display: block; margin-top: 6px; }

	/* povzetek izbranega mesta */
	.nbal-sel { margin: 4px 0 14px; }
	.nbal-sel-h { font-size: 19.6px; font-weight: 700; margin: 0 0 9.8px; }
	.nbal-tab { width: 100%; border-collapse: collapse; font-size: 14px; border: 1px solid #e3e3e3; }
	.nbal-tab th { text-align: left; font-weight: 400; color: #555; padding: 10px 12px; vertical-align: top; width: 140px;
		border-bottom: 1px solid #ededed; }
	.nbal-tab td { padding: 10px 12px; font-weight: 700; border-bottom: 1px solid #ededed; }
	.nbal-tab tr:last-child th, .nbal-tab tr:last-child td { border-bottom: 0; }
	.nbal-ure { font-weight: 400; color: #444; margin-top: 6px; font-size: 13px; }
	.nbal-ure-vir { color: #888; font-size: 12px; font-weight: 400; margin-top: 4px; }
	</style>
	<?php
}

/** Skripta izbirnika — v nogi, ko sta jQuery in selectWoo ze nalozena. */
function noriks_balikovna_skripta() {
	?>
	<script>
	(function(){
	  var koren = <?php echo wp_json_encode( esc_url_raw( rest_url( 'noriks/v1/balikovna' ) ) ); ?>;
	  /* WooCommerce nalozi selectWoo z "defer", zato se ga ob zagonu te
	     skripte se ni. Pocakamo nanj, sicer pade izbirnik na rezervno polje. */
	  function zazeni(){
	  var $ = window.jQuery;
	  function el(id){ return document.getElementById(id); }

	  var kartice = document.querySelectorAll('.hs-delivery-type-container .delivery-type');
	  var rate    = el('nbal_rate');
	  var polje   = el('nbal_field');
	  var okvir   = el('nbal_vybrano');
	  var napaka  = el('nbal_napaka');
	  var sel     = el('nbal_select');
	  if (!kartice.length || !rate) return;

	  /* Skripta blagajne po nalaganju prestavi vse .form-row na konec ovoja,
	     zato nase bloke po tem postavimo na pravo mesto:
	     kartice nad "Jméno", izbirnik mesta pod "Příjmení". */
	  function postavi(){
	    var kart  = document.querySelector('.hs-delivery-type-container');
	    var ime   = el('billing_first_name_field');
	    var prii  = el('billing_last_name_field');
	    if (kart && ime && kart.nextElementSibling !== ime) {
	      ime.parentNode.insertBefore(kart, ime);
	    }
	    if (prii && polje && prii.nextElementSibling !== polje) {
	      prii.parentNode.insertBefore(polje, prii.nextElementSibling);
	    }
	    if (polje && okvir && polje.nextElementSibling !== okvir) {
	      polje.parentNode.insertBefore(okvir, polje.nextElementSibling);
	    }
	  }
	  postavi();
	  [60, 300, 900, 2000].forEach(function(t){ setTimeout(postavi, t); });
	  if ($) $(document.body).on('updated_checkout country_to_state_changed', function(){ postavi(); osvezi(); });

	  /* vrstice naslova, ki se pri Balíkovni skrijejo (kot na vigoshop.cz) */
	  function vrsticeNaslova(){
	    var v = [];
	    ['billing_address_1_field','billing_address_2_field','billing_postcode_field','billing_city_field']
	      .forEach(function(id){ var e = el(id); if (e) v.push(e); });
	    var prva = el('billing_address_1_field');
	    if (prva && prva.previousElementSibling &&
	        prva.previousElementSibling.classList.contains('form-row')) { v.push(prva.previousElementSibling); }
	    return v;
	  }

	  function jeBal(){
	    var a = document.querySelector('.hs-delivery-type-container .delivery-type.active');
	    return !!a && a.getAttribute('data-balikovna') === '1';
	  }

	  /* Skrita polja naslova pocistimo (Metakocka pri Balíkovni hoce prazno ulico
	     in mesto, stare vrednosti pa ne smejo v narocilo), a si kupcev naslov
	     zapomnimo in ga vrnemo, ce se premisli in izbere dostavo na naslov. */
	  var imenaNaslova = ['billing_address_1','billing_address_2','billing_postcode','billing_city'];
	  var shranjenNaslov = null;

	  function pocistiNaslov(){
	    if (shranjenNaslov === null) {
	      shranjenNaslov = imenaNaslova.map(function(id){ var e = el(id); return e ? e.value : ''; });
	    }
	    imenaNaslova.forEach(function(id){ var e = el(id); if (e && e.value !== '') { e.value = ''; } });
	  }

	  function vrniNaslov(){
	    if (shranjenNaslov === null) { return; }
	    imenaNaslova.forEach(function(id, i){
	      var e = el(id);
	      if (e && e.value === '' && shranjenNaslov[i]) {
	        e.value = shranjenNaslov[i];
	        e.dispatchEvent(new Event('change', {bubbles:true}));
	      }
	    });
	    shranjenNaslov = null;
	  }

	  function osvezi(){
	    var bal = jeBal();
	    vrsticeNaslova().forEach(function(e){ e.style.display = bal ? 'none' : ''; });
	    if (bal) { pocistiNaslov(); } else { vrniNaslov(); }
	    if (polje) polje.style.display = bal ? '' : 'none';
	    if (okvir) okvir.style.display = (bal && sel && sel.value) ? '' : 'none';
	    var logo = el('nbal_doprava_logo');
	    if (logo) logo.src = bal
	      ? <?php echo wp_json_encode( NORIKS_BALIKOVNA_LOGO ); ?>
	      : <?php echo wp_json_encode( NORIKS_BALIKOVNA_LOGO_CP ); ?>;
	    if (napaka) napaka.hidden = true;
	    if (polje) polje.classList.toggle('is-filled', !!(sel && sel.value));
	  }

	  kartice.forEach(function(k){
	    k.addEventListener('click', function(){
	      kartice.forEach(function(x){ x.classList.remove('active'); });
	      k.classList.add('active');
	      rate.value = k.getAttribute('data-rate');
	      osvezi();
	      if ($) $(document.body).trigger('update_checkout');
	    });
	  });

	  function izbrano(m){
	    el('nbal_naziv').textContent  = m.naziv;
	    el('nbal_naslov').textContent = m.naslov;
	    el('nbal_obec').textContent   = m.obec || '';
	    var ure = el('nbal_ure');
	    if (ure) { ure.hidden = true; ure.textContent = m.ure || ''; }
	    var vir = document.querySelector('.nbal-ure-vir'); if (vir) vir.hidden = true;
	    if (okvir) okvir.style.display = '';
	    if (polje) polje.classList.add('is-filled');
	    if (napaka) napaka.hidden = true;
	    if ($) $(document.body).trigger('update_checkout');
	  }

	  /* Izbirnik: selectWoo (enak videz kot drugod v blagajni), sicer rezervno polje. */
	  if ($ && $.fn.selectWoo && sel) {
	    $(sel).selectWoo({
	      placeholder: 'Vyberte balíkovnu',
	      width: '100%',
	      minimumInputLength: 0,
	      language: {
	        inputTooShort: function(){ return 'Zadejte PSČ, ulici nebo město…'; },
	        searching:     function(){ return 'Hledám…'; },
	        noResults:     function(){ return 'Nic nenalezeno.'; }
	      },
	      ajax: {
	        url: koren, dataType: 'json', delay: 220,
	        data: function(p){ return { q: p.term }; },
	        processResults: function(d){
	          return { results: (d || []).map(function(m){
	            return { id: m.id, text: m.naziv + ' ' + m.naslov, mesto: m };
	          }) };
	        }
	      }
	    });
	    $(sel).on('select2:select', function(e){ izbrano(e.params.data.mesto); });
	  } else if (sel) {
	    /* rezervno: besedilno polje z lastnim seznamom */
	    sel.hidden = true;
	    var q = el('nbal_q'), lista = el('nbal_list'), cas;
	    if (q) {
	      q.hidden = false;
	      q.addEventListener('input', function(){
	        clearTimeout(cas);
	        var v = q.value.trim();
	        if (v.length < 2) { lista.hidden = true; lista.innerHTML = ''; return; }
	        cas = setTimeout(function(){
	          fetch(koren + '?q=' + encodeURIComponent(v)).then(function(r){ return r.json(); })
	            .then(function(d){
	              lista.innerHTML = '';
	              if (!d || !d.length) { lista.innerHTML = '<li>Nic nenalezeno.</li>'; }
	              else d.forEach(function(m){
	                var li = document.createElement('li');
	                li.textContent = m.naziv + ' — ' + m.naslov;
	                li.addEventListener('click', function(){
	                  sel.innerHTML = '<option value="' + m.id + '" selected></option>';
	                  sel.options[0].textContent = m.naziv + ' ' + m.naslov;
	                  q.value = m.naziv + ', ' + m.naslov;
	                  lista.hidden = true;
	                  izbrano(m);
	                });
	                lista.appendChild(li);
	              });
	              lista.hidden = false;
	            }).catch(function(){ lista.hidden = true; });
	        }, 220);
	      });
	      document.addEventListener('click', function(e){
	        if (lista && !lista.hidden && !lista.contains(e.target) && e.target !== q) { lista.hidden = true; }
	      });
	    }
	  }

	  var gumb = el('nbal_ure_gumb');
	  if (gumb) gumb.addEventListener('click', function(e){
	    e.preventDefault();
	    var ure = el('nbal_ure'); var vir = document.querySelector('.nbal-ure-vir');
	    if (ure) { ure.hidden = !ure.hidden; if (vir) vir.hidden = ure.hidden; }
	  });

	  if ($) $('form.checkout').on('checkout_place_order', function(){
	    if (jeBal() && (!sel || !sel.value)) {
	      if (napaka) napaka.hidden = false;
	      if (polje) polje.scrollIntoView({ behavior: 'smooth', block: 'center' });
	      return false;
	    }
	    return true;
	  });

	  osvezi();
	  }
	  var cakam = 0;
	  (function cakaj(){
	    if ( ( window.jQuery && window.jQuery.fn && window.jQuery.fn.selectWoo ) || ++cakam > 60 ) { zazeni(); }
	    else { setTimeout( cakaj, 100 ); }
	  })();
	})();
	</script>
	<?php
}

/* Izbrano mesto shrani v sejo, da prezivi osvezitev blagajne. */
add_action( 'woocommerce_checkout_update_order_review', function ( $podatki ) {
	parse_str( $podatki, $p );
	$wc = function_exists( 'WC' ) ? WC() : null;
	if ( ! $wc || ! $wc->session ) { return; }
	if ( isset( $p['nbal_id'] ) ) {
		$wc->session->set( NORIKS_BALIKOVNA_META . '_id', sanitize_text_field( $p['nbal_id'] ) );
	}
	/* Zapomni si tudi izbrani nacin dostave. WooCommerce ga ob ponovnem
	   nalaganju strani ne obdrzi vedno, zato ga spodaj obnovimo sami —
	   sicer se blagajna tiho vrne na dostavo na naslov, izbrano prevzemno
	   mesto pa ostane v polju. */
	if ( isset( $p['shipping_method'][0] ) ) {
		$nacin = sanitize_text_field( $p['shipping_method'][0] );
		$wc->session->set( NORIKS_BALIKOVNA_META . '_nacin', $nacin );
		/* Ta razlicica WooCommerca pri osvezitvi blagajne bere nacin dostave
		   samo iz vrhnjega polja zahteve, ne iz post_data — izmerjeno. Ker
		   kartice posiljajo izbiro v obrazcu, jo moramo uveljaviti sami,
		   sicer bi izbira ostala na prejsnjem nacinu. */
		$izbrani = (array) $wc->session->get( 'chosen_shipping_methods' );
		if ( ! isset( $izbrani[0] ) || $izbrani[0] !== $nacin ) {
			$izbrani[0] = $nacin;
			$wc->session->set( 'chosen_shipping_methods', $izbrani );
		}
	}
} );

/* Pri Balíkovni ulica, hisna stevilka, PSC in mesto niso obvezni (skriti so). */
add_filter( 'woocommerce_checkout_fields', function ( $polja ) {
	if ( ! noriks_balikovna_izbrana() ) { return $polja; }
	foreach ( array( 'billing_address_1', 'billing_address_2', 'billing_postcode', 'billing_city' ) as $k ) {
		if ( isset( $polja['billing'][ $k ] ) ) { $polja['billing'][ $k ]['required'] = false; }
	}
	return $polja;
}, 20 );


/* Obnovi izbrani nacin dostave ob nalaganju blagajne. */
add_action( 'woocommerce_before_calculate_totals', 'noriks_balikovna_obnovi_izbiro', 5 );
function noriks_balikovna_obnovi_izbiro() {
	if ( is_admin() && ! wp_doing_ajax() ) { return; }
	/* Obnovimo samo pri navadnem nalaganju strani. Ce zahteva ze nosi izbrani
	   nacin (oddaja narocila ali osvezitev blagajne prek post_data), je ta
	   mocnejsi — sicer bi povozili kupcev pravkar izbrani nacin. */
	if ( isset( $_POST['shipping_method'] ) ) { return; }
	if ( isset( $_POST['post_data'] ) ) {
		parse_str( wp_unslash( $_POST['post_data'] ), $pd );
		if ( isset( $pd['shipping_method'] ) ) { return; }
	}
	$wc = function_exists( 'WC' ) ? WC() : null;
	if ( ! $wc || ! $wc->session ) { return; }

	$nacin = (string) $wc->session->get( NORIKS_BALIKOVNA_META . '_nacin' );
	if ( $nacin === '' || ! noriks_balikovna_je_kljuc( $nacin ) ) { return; }

	$izbrani = (array) $wc->session->get( 'chosen_shipping_methods' );
	if ( isset( $izbrani[0] ) && $izbrani[0] === $nacin ) { return; }

	$izbrani[0] = $nacin;
	$wc->session->set( 'chosen_shipping_methods', $izbrani );
}

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
	$narocilo->set_billing_postcode( $m['id'] );
	if ( apply_filters( 'noriks_balikovna_prazen_naslov', true, $narocilo, $m ) ) {
		$narocilo->set_shipping_address_1( '' );
		$narocilo->set_shipping_address_2( '' );
		$narocilo->set_shipping_city( '' );
		// polja naslova so na blagajni skrita, zato so prazna tudi pri racunu
		$narocilo->set_billing_address_1( '' );
		$narocilo->set_billing_address_2( '' );
		$narocilo->set_billing_city( '' );
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
