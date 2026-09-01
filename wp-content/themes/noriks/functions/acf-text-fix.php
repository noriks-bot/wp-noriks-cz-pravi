<?php
/**
 * Popravki besedil, ki so shranjena v ACF nastavitvah.
 *
 * Do ACF moznosti ni dostopa prek REST API-ja, zato jih popravimo ob izpisu.
 * Vsak filter deluje SAMO, dokler je v bazi se staro besedilo - takoj ko ga
 * Dejan popravi v wp-adminu, filter nima vec kaj zamenjati in se sam umakne.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_filter( 'acf/load_value', function ( $value, $post_id, $field ) {
    if ( ! is_string( $value ) || $value === '' ) { return $value; }
    $map = array(
        'singlepp_acc_h_3' => array( 'Jednoduché vrácení a bezplatné výměny' => 'Jednoduché vrácení a výměny' ),
    );
    $name = isset( $field['name'] ) ? $field['name'] : '';
    if ( isset( $map[ $name ] ) ) {
        foreach ( $map[ $name ] as $staro => $novo ) {
            if ( strpos( $value, $staro ) !== false ) {
                $value = str_replace( $staro, $novo, $value );
            }
        }
    }
    return $value;
}, 20, 3 );
