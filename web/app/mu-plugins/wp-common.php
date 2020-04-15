<?php
/**
Plugin Name: WP Common
Version: 0.1.3
Plugin URI: https://icoders.co
Description: WP Custom functions
Author: Kovacs Daniel Akos <kovacsdanielakos@icoders.co>
Author URI: https://icoders.co
 */

/** Az osszes frissitesi notif-ot letiltjuk */
function remove_core_updates(){
    global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}
add_filter('pre_site_transient_update_core','remove_core_updates');
add_filter('pre_site_transient_update_plugins','remove_core_updates');
add_filter('pre_site_transient_update_themes','remove_core_updates');
add_filter('xmlrpc_enabled', false);

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_resource_hints', 2 );
remove_action('init', 'rest_api_init');
remove_action('rest_api_init', 'rest_api_default_filters', 10);
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('parse_request', 'rest_api_loaded');
// Remove the REST API endpoint.
remove_action('rest_api_init', 'wp_oembed_register_route');

// Turn off oEmbed auto discovery.
// Don't filter oEmbed results.
remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

// Remove oEmbed discovery links.
remove_action('wp_head', 'wp_oembed_add_discovery_links');

// Remove oEmbed-specific JavaScript from the front-end and back-end.
remove_action('wp_head', 'wp_oembed_add_host_js');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
