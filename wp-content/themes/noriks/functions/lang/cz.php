<?php
add_filter( 'gettext', 'translate_labels_cz', 20, 3 );
function translate_labels_cz( $translated_text, $text, $domain ) {
    $translations = array(
        'Choose your size' => 'Vyberte velikost',
        'Choose an option' => 'Vyberte možnost',
        'Add to cart' => 'Přidat do košíku',
        'Select options' => 'Výběr',
        'View cart' => 'Zobrazit košík',
        'Checkout' => 'Objednávka',
        'Proceed to checkout' => 'Přejít k objednávce',
        'Update cart' => 'Aktualizovat košík',
        'Apply coupon' => 'Použít kupón',
        'Coupon code' => 'Kód kupónu',
        'Cart totals' => 'Celkem košík',
        'Subtotal' => 'Mezisoučet',
        'Total' => 'Celkem',
        'Shipping' => 'Doručení',
        'Free shipping' => 'Doručení zdarma',
    );
    if ( isset( $translations[$text] ) ) return $translations[$text];
    return $translated_text;
}

// CZ: Override WC default placeholders
add_filter( 'gettext', 'noriks_cz_placeholders', 20, 3 );
function noriks_cz_placeholders( $translated, $text, $domain ) {
    $t = array(
        'House number and street name' => 'Ulice',
        'Apartment, suite, unit, etc.' => 'Číslo popisné',
        'Apartment, suite, unit, etc. (optional)' => 'Číslo popisné',
        'Street address' => 'Ulice',
        'Town / City' => 'Město',
        'Postcode / ZIP' => 'PSČ',
        'Phone' => 'Telefon',
        'Email address' => 'E-mailová adresa',
        'First name' => 'Jméno',
        'Last name' => 'Příjmení',
        'Place order' => 'Koupit nyní',
        'Country / Region' => 'Země',
        '(optional)' => '(nepovinné)',
        'Cash on delivery' => 'Dobírka',
        'Pay with cash upon delivery.' => 'Zaplaťte při doručení.',
    );
    return isset( $t[$text] ) ? $t[$text] : $translated;
}
