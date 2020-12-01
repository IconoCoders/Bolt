<?php

add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );
function remove_dashboard_widgets()
{
    global $wp_meta_boxes;

    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}

/**
 * Change the default state and country on the checkout page
 */
add_filter( 'default_checkout_billing_country', 'change_default_checkout_country' );
add_filter( 'default_checkout_billing_state', 'change_default_checkout_state' );
function change_default_checkout_country()
{
    return 'HU'; // country code
}

function change_default_checkout_state()
{
    return 'HU'; // state code
}

// Custom translations
function bolt_custom_strings( $translation, $text, $domain )
{
    switch ( $translation ) {
        case 'You\'re viewing:':
            $translation = 'Termék:';
            break;
        case 'Company billing':
            $translation = 'ÁFÁ-s számlát kérek';
            break;
        case 'Company name':
            $translation = 'Számlázási név / cégnév';
            break;
        case 'Tax number':
            $translation = 'Adószám';
            break;
        case 'Order received':
            $translation = 'Sikeres megrendelés!';
            break;
        case 'Shop order number:':
            $translation = 'Rendelésszám';
            break;
        case 'Please select an APT from dropdown .':
            $translation = 'Kérjük válasszon csomagpontot:';
            break;
        case 'Please select an APT from dropdown':
            $translation = 'Kérjük válasszon csomagpontot:';
            break;
        case 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.':
            $translation = 'Ha már vásároltál nálunk add meg adataid a gyors és egyszerű vásárlási folyamathoz.';
            break;
    }

    return $translation;
}
add_filter( 'gettext', 'bolt_custom_strings', 20, 3 );

add_filter( 'gettext', 'filter_gettext', 10, 3 );
function filter_gettext( $translated, $original, $domain ) {
    switch($translated) {
        case 'Cégnév':
            $translated = 'Cégnév/számlázási név';
            break;
        case 'Utcanév, házszám':
            $translated = '';
            break;
    }

    return $translated;
}

function iconic_remove_sidebar( $is_active_sidebar, $index )
{
    if( $index !== "sidebar-1" ) {
        return $is_active_sidebar;
    }

    if( is_product() || is_cart() || is_checkout() || is_order_received_page() || is_account_page()) {
        return false;
    }

    return $is_active_sidebar;
}
add_filter( 'is_active_sidebar', 'iconic_remove_sidebar', 10, 2 );

//hide out-of-stock products from listing
add_action( 'pre_get_posts', 'hide_out_of_stock_products' );
function hide_out_of_stock_products( $query ) {
    if ( ! $query->is_main_query() || is_admin() ) {
        return;
    }

    if ( $outofstock_term = get_term_by( 'name', 'outofstock', 'product_visibility' ) ) {

        $tax_query = (array) $query->get('tax_query');
        $tax_query[] = array(
            'taxonomy' => 'product_visibility',
            'field' => 'term_taxonomy_id',
            'terms' => array( $outofstock_term->term_taxonomy_id ),
            'operator' => 'NOT IN'
        );

        $query->set( 'tax_query', $tax_query );

    }

    remove_action( 'pre_get_posts', 'hide_out_of_stock_products' );
}

//hide out-of-stock products from related
add_filter( 'woocommerce_related_products', 'filter_related_products', 10, 1 );
function filter_related_products( $related_product_ids ) {
    foreach( $related_product_ids as $key => $value ) {
        $relatedProduct = wc_get_product( $value );
        if( ! $relatedProduct->is_in_stock() ) {
            unset( $related_product_ids["$key"] );
        }
    }

    return $related_product_ids;
}
