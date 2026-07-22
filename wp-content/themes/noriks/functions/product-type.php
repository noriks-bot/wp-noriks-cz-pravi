<?php
/**
 * ============================================================
 * NORIKS — Central product-type resolver (CZ)
 * ------------------------------------------------------------
 * ONE place that decides which product categories map to which
 * "type". Everywhere in the theme use:
 *     noriks_is_type( 'bunion', $id )   // -> bool
 *     noriks_product_type( $id )        // -> string
 *
 * TYPE KEYS are identical across markets so shared templates work
 * unchanged; only the SLUG VALUES are Czech (+ universal orto-*).
 * ============================================================
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'noriks_product_type_map' ) ) :

function noriks_product_type_map() : array {
    return array(
        // --- primary product types (order = resolution priority) ---
        'starter'   => array( 'startovaci-balicek', 'starter-paketi', 'orto-starter', 'orto-majica-bokserica' ),
        'majice'    => array( 'tricka', 'majice', 'orto-tricka', 'orto-majice' ),
        'bokserice' => array( 'boxerky', 'bokserice', 'orto-boxerky', 'orto-bokserice', 'bokserice-sastavi-paket' ),
        'carape'    => array( 'ponozky', 'orto-kompresijske-carape' ),

        // --- sub-variants / special buckets ---
        'kompresijske-nogavice'   => array( 'ponozky', 'orto-kompresijske-carape' ),
        'ortopas'                 => array( 'orto-ortopas', 'ortopas' ),
        'bunion'                  => array( 'orto-bunion', 'bunion' ),
        'fisiorest'               => array( 'orto-fisiorest', 'fisiorest' ),
        'norikshers'              => array( 'orto-norikshers', 'orto-noriks-hers', 'norikshers' ),
        'leakboxers'              => array( 'orto-leak-boxers', 'leak-boxers' ),
        'kompresijske-majice'     => array( 'orto-kompresijske-majice', 'kompresijske-majice' ),
        'majica-darila'           => array( 'orto-majica-darila', 'majica-darila' ),
        'majice-bokserice-paketi' => array( 'sady', 'kompleti', 'majice-i-bokserice-paketi' ),
        'black-friday'            => array( 'black-friday' ),
        'orto'                    => array( 'orto' ),
    );
}

endif;

if ( ! function_exists( 'noriks_primary_types' ) ) :

function noriks_primary_types() : array {
    return array( 'starter', 'majice', 'bokserice', 'carape' );
}

endif;

if ( ! function_exists( 'noriks_resolve_product_id' ) ) :

function noriks_resolve_product_id( $product_id = null ) : int {
    if ( $product_id ) {
        return (int) $product_id;
    }
    if ( function_exists( 'is_product' ) && is_product() ) {
        return (int) get_queried_object_id();
    }
    return (int) get_the_ID();
}

endif;

if ( ! function_exists( 'noriks_is_type' ) ) :

function noriks_is_type( string $type, $product_id = null ) : bool {
    $map = noriks_product_type_map();
    if ( empty( $map[ $type ] ) ) {
        return false;
    }
    $product_id = noriks_resolve_product_id( $product_id );
    if ( ! $product_id ) {
        return false;
    }
    return has_term( $map[ $type ], 'product_cat', $product_id );
}

endif;

if ( ! function_exists( 'noriks_product_type' ) ) :

function noriks_product_type( $product_id = null ) : string {
    $product_id = noriks_resolve_product_id( $product_id );
    foreach ( noriks_primary_types() as $type ) {
        if ( noriks_is_type( $type, $product_id ) ) {
            return $type;
        }
    }
    return '';
}

endif;

if ( ! function_exists( 'noriks_is_black_friday' ) ) :

function noriks_is_black_friday( $product_id = null ) : bool {
    return noriks_is_type( 'black-friday', $product_id );
}

endif;

if ( ! function_exists( 'noriks_is_mixed_bundle' ) ) :

function noriks_is_mixed_bundle( $product_id = null ) : bool {
    return noriks_is_type( 'black-friday', $product_id )
        || noriks_is_type( 'majice-bokserice-paketi', $product_id )
        || noriks_is_type( 'starter', $product_id );
}

endif;
