<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package Foxpost_Woo_Parcel
 * @since   1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Foxpost Parcel
 * Plugin URI:        http://wiki.foxpost.hu/doku.php?id=woocommerce
 * Description:       This plugin provides foxpost shipping functions and order export to FoxPost.
 * Version:           1.1.1
 * Author:            Foxpost-GZ
 * Author URI:        http://foxpost.hu
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       foxpost-woo-parcel
 * Domain Path:       /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 3.6.5
 */


use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel;
use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel_Activator;
use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel_Autoloader;
use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel_Deactivator;

if (!defined('ABSPATH')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('FOXPOST_WOO_PARCEL_VERSION', '1.1.3');

require_once plugin_dir_path(__FILE__)
             . 'includes/class-foxpost-woo-parcel-autoloader.php';

new Foxpost_Woo_Parcel_Autoloader(plugin_dir_path(__FILE__));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-foxpost-woo-parcel-activator.php
 *
 * @since 1.0.0
 *
 * @return void
 */
function activate_foxpost_woo_parcel()
{
    Foxpost_Woo_Parcel_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-foxpost-woo-parcel-deactivator.php
 *
 * @since 1.0.0
 *
 * @return void
 */
function deactivate_foxpost_woo_parcel()
{

    Foxpost_Woo_Parcel_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_foxpost_woo_parcel');
register_deactivation_hook(__FILE__, 'deactivate_foxpost_woo_parcel');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 *
 * @return void
 */
function run_foxpost_woo_parcel()
{
    $plugin = Foxpost_Woo_Parcel::get_instance();
    $plugin->run();
}

run_foxpost_woo_parcel();
