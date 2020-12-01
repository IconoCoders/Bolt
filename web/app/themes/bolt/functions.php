<?php
/**
 * Storechild engine room
 *
 * @package storechild
 */

/**
 * Set the theme version number as a global variable
 */
$theme              = wp_get_theme( 'storechild' );
$storechild_version = $theme['Version'];

$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Load the individual classes required by this theme
 */
require_once( 'inc/class-storechild.php' );
require_once( 'inc/class-storechild-integrations.php' );
require_once( 'inc/storechild-template-hooks.php' );
require_once( 'inc/storechild-template-functions.php' );
require_once( 'inc/plugged.php' );
require_once( 'inc/Bolt.php' );
require_once( 'inc/Soil.php' );

function woocommerce_quantity_input_min_callback( $min, $product )
{
    $min = 1;
    return $min;
}
add_filter( 'woocommerce_quantity_input_min', 'woocommerce_quantity_input_min_callback', 10, 2 );

function woocommerce_quantity_input_max_callback( $max, $product )
{
    $max = 5;
    return $max;
}
add_filter( 'woocommerce_quantity_input_max', 'woocommerce_quantity_input_max_callback', 10, 2 );

function wc_qty_add_product_field()
{
    echo '<div class="options_group">';
    woocommerce_wp_text_input(
        array(
            'id'          => '_wc_min_qty_product',
            'label'       => __( 'Minimum vasarolhato mennyiseg', 'woocommerce-max-quantity' ),
            'placeholder' => '',
            'desc_tip'    => 'true',
        )
    );
    echo '</div>';

    echo '<div class="options_group">';
    woocommerce_wp_text_input(
        array(
            'id'          => '_wc_max_qty_product',
            'label'       => __( 'Maximum vasarolhato mennyiseg', 'woocommerce-max-quantity' ),
            'placeholder' => '',
            'desc_tip'    => 'true',
        )
    );
    echo '</div>';
}
add_action( 'woocommerce_product_options_inventory_product_data', 'wc_qty_add_product_field' );

function wc_qty_save_product_field( $post_id )
{
    $val_min = trim( get_post_meta( $post_id, '_wc_min_qty_product', true ) );
    $new_min = sanitize_text_field( $_POST['_wc_min_qty_product'] );

    $val_max = trim( get_post_meta( $post_id, '_wc_max_qty_product', true ) );
    $new_max = sanitize_text_field( $_POST['_wc_max_qty_product'] );

    if ( $val_min != $new_min ) {
        update_post_meta( $post_id, '_wc_min_qty_product', $new_min );
    }

    if ( $val_max != $new_max ) {
        update_post_meta( $post_id, '_wc_max_qty_product', $new_max );
    }
}
add_action( 'woocommerce_process_product_meta', 'wc_qty_save_product_field' );
