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

// Custom translations
function bolt_custom_strings( $translation, $text, $domain ) {
    switch ( $translation ) {
        case "'You\'re viewing:'" :
            $translation = 'Termék:';
            break;
    }
    return $translation;
}
add_filter( 'gettext', 'bolt_custom_strings', 20, 3 );
