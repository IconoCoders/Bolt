<?php

namespace Foxpost_Woo_Parcel\Includes;
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 *
 * @package    Foxpost_Woo_Parcel
 * @subpackage Foxpost_Woo_Parcel/includes
 */

if (!defined('ABSPATH')) {
    die;
}


/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Foxpost_Woo_Parcel
 * @subpackage Foxpost_Woo_Parcel/includes
 */
class Foxpost_Woo_Parcel_i18n {

    /**
     * Load the plugin text domain for translation.
     *
     * @since 1.0.0
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            'foxpost-woo-parcel',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );

    }
}
