<?php

namespace Foxpost_Woo_Parcel\Includes;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Foxpost_Woo_Parcel
 * @subpackage Foxpost_Woo_Parcel/includes
 */


if (!defined('ABSPATH')) {
    die;
}


use Foxpost_Woo_Parcel\Admin\Includes\Foxpost_Woo_Parcel_Admin;


/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package    Foxpost_Woo_Parcel
 * @subpackage Foxpost_Woo_Parcel/includes
 * @since      1.0.0
 */
class Foxpost_Woo_Parcel {

    const SELLER_OWN_DATA_MAX_LENGTH = 50;

    /**
     * @var Foxpost_Woo_Parcel
     *
     * @since 1.0.0
     */
    static protected $instance = null;

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @var      Foxpost_Woo_Parcel_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    protected function __construct()
    {
        if (defined('FOXPOST_WOO_PARCEL_VERSION')) {
            $this->version = FOXPOST_WOO_PARCEL_VERSION;
        } else {
            $this->version = '1.1.0';
        }
        $this->plugin_name = 'foxpost-woo-parcel';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        self::provide_session();
    }

    /**
     * Gets object instance.
     *
     * @since 1.0.0
     *
     * @return Foxpost_Woo_Parcel
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Foxpost_Woo_Parcel_Loader. Orchestrates the hooks of the plugin.
     * - Foxpost_Woo_Parcel_i18n. Defines internationalization functionality.
     * - Foxpost_Woo_Parcel_Admin. Defines all hooks for the admin area.
     * - Foxpost_Woo_Parcel_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since 1.0.0
     *
     * @since    1.0.0
     */
    private function load_dependencies()
    {
        $this->loader = new Foxpost_Woo_Parcel_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Foxpost_Woo_Parcel_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     */
    private function set_locale()
    {

        $plugin_i18n = new Foxpost_Woo_Parcel_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Foxpost_Woo_Parcel_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        $this->loader->add_filter('woocommerce_settings_tabs_array', $plugin_admin, 'woo_add_settings_tab', 50);

        $this->loader->add_action('woocommerce_sections_foxpost_woo_parcel', $plugin_admin, 'output_sections');

        $this->loader->add_filter('woocommerce_get_sections_foxpost_woo_parcel', $plugin_admin, 'woo_parcel_add_section');

        $this->loader->add_action('woocommerce_settings_foxpost_woo_parcel', $plugin_admin, 'woo_settings_tabs_settings');

        $this->loader->add_action('woocommerce_settings_save_foxpost_woo_parcel', $plugin_admin, 'woo_settings_save');

        $this->loader->add_action('admin_menu', $plugin_admin, 'woo_parcel_add_menu');
        $this->loader->add_action('admin_init', $plugin_admin, 'woo_parcel_admin_init');

        $this->loader->add_filter('woocommerce_shipping_methods', $plugin_admin, 'define_shipping_methods');

        $this->loader->add_action('wp_ajax_foxpost_woo_parcel', $plugin_admin, 'woo_parcel_ajax_request');

        $this->loader->add_action('manage_shop_order_posts_custom_column', $plugin_admin, 'add_order_status_column_content', 20);

        $this->loader->add_action('manage_edit-shop_order_columns', $plugin_admin, 'add_order_status_column_header', 20);

        $this->loader->add_filter('bulk_actions-edit-shop_order', $plugin_admin, 'woo_parcel_bulk_action_export', 20, 1);

        $this->loader->add_filter('handle_bulk_actions-edit-shop_order', $plugin_admin, 'woo_parcel_export_action', 10, 3);

        $this->loader->add_action('admin_notices', $plugin_admin, 'woo_parcel_export_action_notices');

//        $this->loader->add_filter('woocommerce_structured_data_order', $plugin_admin, 'woo_parcel_structured_data_order', 10);

        $this->loader->add_action('woocommerce_admin_order_data_after_order_details', $plugin_admin, 'woo_parcel_admin_order_data_after_order_details');

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function define_public_hooks()
    {

        $plugin_public = new Foxpost_Woo_Parcel_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        $this->loader->add_action('woocommerce_checkout_update_order_meta', $plugin_public, 'woo_parcel_checkout_update_order_meta');

        $this->loader->add_action('woocommerce_thankyou', $plugin_public, 'woo_parcel_thankyou_page');

        $this->loader->add_action('woocommerce_checkout_process', $plugin_public, 'woo_parcel_checkout_process');

        $this->loader->add_action('woocommerce_review_order_after_order_total', $plugin_public, 'woo_parcel_review_order_after_order_total');

        $this->loader->add_action('wp_footer', $plugin_public, 'woo_parcel_wp_footer');

        $this->loader->add_action('wp_ajax_nopriv_woo_parcel_map_iframe', $plugin_public, 'woo_parcel_map_iframe');

        $this->loader->add_action('rest_api_init', $plugin_public, 'woo_parcel_post_api_route');

        $this->loader->add_filter('woocommerce_checkout_fields', $plugin_public, 'woo_parcel_override_checkout_fields');

        $this->loader->add_action('woocommerce_view_order', $plugin_public, 'woo_parcel_view_order');

        $this->loader->add_action('woocommerce_email_before_order_table', $plugin_public, 'woo_parcel_email_before_order_table', 20, 4);

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since  1.0.0
     * @return string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Foxpost_Woo_Parcel_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Translates text.
     *
     * @param string $text text to translate
     *
     * @since 1.0.0
     *
     * @return string
     */
    public static function __($text)
    {
        return __($text, 'foxpost-woo-parcel');
    }

    /**
     * @param string $text
     *
     * @since 1.0.0
     *
     * @return string
     */
    public static function esc_html__($text)
    {
        return esc_html__($text, 'foxpost-woo-parcel');
    }

    /**
     * @since 1.0.0
     *
     * @param string $domain
     */
    public static function _e($text)
    {
        _e($text, 'foxpost-woo-parcel');
    }

    /**
     * @since 1.1.0
     */
    private static function provide_session()
    {
        if (!session_id()) {
            session_start();
        }
    }
}
