# Balíkovna na blagajni CZ — kaj je narejeno in kaj je treba vklopiti

Dostava Česká pošta ostane privzeta (**Doručení na adresu**), poleg nje pa je
nova možnost **Výdejny, pošty a boxy (Balíkovna)** z iskalnikom prevzemnih
mest — tako kot ima vigoshop.cz.

## Datoteke

| Datoteka | Kaj je |
|---|---|
| `functions/balikovna.php` | celotna logika: način dostave, kartice na blagajni, iskalnik, preverjanje, zapis k naročilu, prikaz v adminu/mailu/zahvali |
| `data/balikovna-mista.tsv` | 12 149 prevzemnih mest (2 924 pošt, 4 242 partnerjev, 4 983 boksov) |
| `data/balikovna-pretvori.py` | pretvorba uradnega CSV → TSV |
| `woocommerce/checkout/form-checkout.php` | kliče `noriks_balikovna_nacini()` namesto trdo zapisane kartice |
| `woocommerce/checkout/review-order.php` | napis dostave sledi izbranemu načinu + pokaže izbrano mesto |
| `functions/checkout_mods.php` | osveževanje cene dostave velja samo za izbrano kartico |

## 0. Stikalo za vklop/izklop — `functions/paketomati-stikalo.php`

Kodo lahko mirno daš na produkcijo, ne da bi kupci karkoli videli. Stikalo je
**ena vrstica v temi**, v `functions/paketomati-stikalo.php`:

```php
$noriks_balikovna_stikalo = array( 'devcz.noriks.com' );
```

| vrednost | pomen |
|---|---|
| `false` | izklopljeno povsod |
| `true` | vklopljeno povsod |
| `array( 'devcz.noriks.com' )` | vklopljeno samo na naštetih domenah |

Trenutno je vklopljeno **samo na devcz**, produkcija je ugasnjena. Za zagon na
produkciji dodaj `'noriks.com'` na seznam (ali nastavi `true`).

**Kaj pomeni izklopljeno:** modul se sploh ne naloži — način dostave
»Balíkovna« se ne registrira v WooCommerce, ni kartic za izbiro, ni izbirnika
mest, ne naložita se slog in skripta, preverjanja naslova ostanejo
nespremenjena. Blok »Doprava« se izriše z natanko isto kodo kot pred
nadgradnjo, vključno s starim gumbom `value="standard"`.

> Opomba za razvoj: konstante so namenoma definirane **nad** stikalom. PHP
> funkcije z vrha datoteke prevede tudi, če datoteka prej naredi `return`, zato
> bi klic iz `form-checkout.php` na izklopljeni strani sicer padel na
> nedefinirani konstanti.

## 1. Vklop v WooCommerce (brez tega se nič ne spremeni)

WooCommerce → Nastavitve → **Doprava** → območje **CZ** → Dodaj način dostave →
**Balíkovna — výdejny, pošty a boxy**.

Nastavitve primerka:

| Polje | Vrednost |
|---|---|
| Název (MetaKocka) | `Balík do Balíkovny` — **ne spreminjaj** |
| Cena (Kč) | `69` (enako kot Standardní) |
| Zdarma od (Kč) | `1700` (enako kot obstoječa brezplačna dostava) |

Vrstni red pusti **pod** obstoječima načinoma, da ostane dostava na naslov
privzeta.

## 2. Metakocka

**Preslikava dostavne službe.** Poleg naziva, po katerem Metakocka loči NP/NB/DR,
je treba v povezavi s spletno trgovino klikniti **»Preberi dostavne službe«** in
novemu načinu `Balík do Balíkovny` določiti vrsto dostave Pošta Češka
([navodila](https://metakocka.freshdesk.com/sl/support/solutions/articles/3000086935-spletne-trgovine-preslikava-dostavne-slu%C5%BEbe)).
Pri več vrstah dostave v eni trgovini je treba Metakocko **vnaprej obvestiti** —
na računu ti morajo vklopiti dodatno funkcionalnost.

Dve stvari, ki jih koda ne more narediti:

1. **Pogodba s Češko pošto za Balíkovno.** V Metakocki: Dodatne nastavitve →
   Dostavne službe → Novo → Pošta Češka → vpišeš *Pogodba (ID)* in
   *ID klienta (Technological number)*, ki ju dobiš od Češke pošte.
   ([navodila](https://metakocka.freshdesk.com/sl/support/solutions/articles/3000117610-integracija-po%C5%A1ta-%C4%8De%C5%A1ka))
2. **Preveri, da Metakocka naročilo prepozna kot NB.** Metakocka tip pošiljke
   bere iz naziva načina dostave (`shipping_lines[].method_title`):
   `Balík na poštu` = NP, `Balík do Balíkovny` = NB, `Standardní` = DR.
   Zato je naziv načina točno `Balík do Balíkovny`.
   ([navodila](https://metakocka.freshdesk.com/sl/support/solutions/articles/3000106618-instructions-czech-post))

Po navodilih Metakocke so pri NB **ulica, hišna številka in mesto prazni**,
šteje samo poštna številka. Katera poštna številka to je, pove Češka pošta sama:
v opisu šifranta `ZV_BALIKOVNY.csv` (*Seznam Balíkoven pro uložení balíků*,
odobrila centrala České pošty) je prvi stolpec opredeljen kot

> `PSC` · Charakter · 5 · **PSČ provozovny**

torej poštna številka **same poslovalnice**, ne kraja (OX Point na postaji v
Litoměřicah ima `41214`, kraj pa `41201`). Za primerjavo: v njihovem šifrantu
poslovalnic za oddajo (`ZV_DR_CE_podani.csv`) je isti stolpec opisan samo kot
»PSČ«. To kodo torej zapišemo v poštno številko naročila. To naredi
`noriks_balikovna_naslov_za_metakocko()` ob nastanku naročila. Polna pot do
mesta ostane v `_balikovna_*` meta, v opombi naročila, v adminu, v mailu in na
strani zahvale, tako da se podatek nikjer ne izgubi.

Če bi Metakocka vseeno želela polni naslov, se prazno pisanje izklopi z
`add_filter( 'noriks_balikovna_prazen_naslov', '__return_false' );`.

**Preveri na prvem testnem naročilu:** v Metakocki mora biti pri naročilu
zabeležena oznaka **NB** (stolpec N v izvozu za Češko pošto).

## 3. Osvežitev seznama mest

```bash
curl -O https://www.balikovna.cz/documents/20124/15302876/ZV_BALIKOVNY.zip
unzip ZV_BALIKOVNY.zip
python3 data/balikovna-pretvori.py ZV_BALIKOVNY.csv data/balikovna-mista.tsv
```

Depoji in vrstice brez tipa se izpustijo. Iskanje teče na strežniku
(`/wp-json/noriks/v1/balikovna?q=…`), ker je seznam 2,8 MB; poizvedba vzame
0–4 ms.

**Opomba o odpiralnem času:** uradni CSV je pri urah luknjast (nonstop box
10039 ima vpisane samo sredo, petek, soboto in nedeljo), zato prazen dan
pokažemo kot `—` in ne kot »zavřeno«.

## Naslov naročila pri prevzemu (NAPISANO, A UGASNJENO)

> **Stikalo:** `NORIKS_BALIKOVNA_NASLOV_MESTA` v `balikovna.php` je **`false`** —
> ulica, hišna številka in kraj so prazni, torej natanko tako, kot je bilo prej.
> Čakamo na odgovor Metakockine podpore. Prižge se s spremembo te ene vrstice
> v `true`; logika spodaj je napisana in preverjena.

Poštna številka ostane **šifra prevzemnega mesta** — po njej Metakocka usmeri
pošiljko. Ko je stikalo prižgano, **ulico in kraj napolnimo z naslovom
prevzemnega mesta**.

Metakockina dokumentacija (3000106618) pravi nasprotno:

> »V kolikor je zabeležena dostava v NB ali NP so vrednosti: ulica, hišna št.,
> mesto, prazne. Za dostavo je pomembna zgolj poštna številka.«

V praksi pa Metakocka brez ulice in kraja **nalepke ne zgenerira** — Dejan je
to izmeril na pravem naročilu; ko je polji vpisal ročno, je nalepka nastala.
Zato velja izmerjeno stanje, ne dokumentacija.

Naslov v šifrantu Češke pošte je en sam niz v treh oblikah, zato ga
`noriks_balikovna_razclen_naslov()` bere **od zadaj**:

| oblika | primer | rezultat |
|---|---|---|
| ulica, četrt, PSČ, kraj | `Černokostelecká 2020/20, Strašnice, 10000, Praha` | ulica `Černokostelecká`, hišna `2020/20`, kraj `Praha` |
| ulica, PSČ, kraj | `Toušeňská 2042, 25088, Čelákovice` | ulica `Toušeňská`, hišna `2042`, kraj `Čelákovice` |
| PSČ, kraj | `25301, Hostivice` | ulica = **ime mesta**, kraj `Hostivice` |

Preverjeno na vseh 12 149 vrsticah: 0 brez kraja, 69 brez ulice (tam se vpiše
ime prevzemnega mesta, npr. `Hostivice BOX OX Point Floriánova`), 1072 brez
hišne številke (ulica brez številke — polje ostane prazno).

Posamičen preklop brez urejanja konstante:
`add_filter( 'noriks_balikovna_naslov_mesta', '__return_true' );`
