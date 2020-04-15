<?php

namespace Foxpost_Woo_Parcel\Includes;

/**
 * Fired during plugin activation
 *
 * @package    Foxpost_Woo_Parcel
 * @subpackage Foxpost_Woo_Parcel/includes
 * @since      1.0.0
 *
 */

if (!defined('ABSPATH')) {
    die;
}

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    Foxpost_Woo_Parcel
 * @subpackage Foxpost_Woo_Parcel/includes
 * @since      1.0.0
 */
class Foxpost_Woo_Parcel_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since 1.0.0
     */
    public static function activate()
    {
        if (!self::woocommerce_active()) {
            wp_die('Sorry, but this plugin requires the Woocommerce Plugin to be installed and active. <br><a href="' . admin_url('plugins.php') . '">&laquo; Return to Plugins</a>');
        }
    }

    /**
     * @since 1.0.0
     *
     * @return bool
     */
    private static function woocommerce_active()
    {
        return (
            class_exists('WooCommerce')
            || in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))
            || is_plugin_active('woocommerce/woocommerce.php')
            || is_plugin_active_for_network('woocommerce/woocommerce.php')
            || is_plugin_active('__woocommerce/woocommerce.php')
            || is_plugin_active_for_network('__woocommerce/woocommerce.php')
        );
    }
}
