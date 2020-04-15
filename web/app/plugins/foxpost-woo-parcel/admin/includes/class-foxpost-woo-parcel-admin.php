<?php

namespace Foxpost_Woo_Parcel\Admin\Includes;

/**
 * The admin-specific functionality of the plugin.
 *
 * @since 1.0.0
 *
 * @package    Foxpost_Woo_Parcel
 * @subpackage Foxpost_Woo_Parcel/admin
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/foxpostApi/FoxpostApi.php';

use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel;
use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel_Shipping_Method;
use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel_Tools;
use Foxpost_Woo_Parcel\Admin\Includes\Foxpost_Woo_Parcel_Foxpost_Api_Manager;

/**
 * The admin-specific functionality of the plugin.
 */
class Foxpost_Woo_Parcel_Admin {

    /**
     * The ID of this plugin.
     *
     * @since 1.0.0
     * @var   string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since 1.1.0
     * @var   string $version The current version of this plugin.
     */
    private $version;

    /**
     * @var string
     *
     * @since 1.0.0
     */
    private $path_views_default;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     *
     * @since 1.0.0
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->path_views_default = dirname(dirname(plugin_dir_path(__FILE__)))
                                    . '/admin/partials/';
    }

    public function woo_parcel_admin_init()
    {
        register_setting(
            'woocommerce_foxpost_woo_parcel_apt_shipping_settings',
            'woocommerce_foxpost_woo_parcel_apt_shipping_settings',
            array($this, 'validate_setting')
        );
        register_setting(
            'woocommerce_foxpost_woo_parcel_home_delivery_shipping_settings',
            'woocommerce_foxpost_woo_parcel_home_delivery_shipping_settings',
            array($this, 'validate_setting')
        );
    }

    /**
     * @param array $input
     *
     * @return array
     */
    public function validate_setting($input)
    {
        foreach ($input as $key => $value) {
            if ($key === 'seller_own_data' && isset($input[$key])) {
                $input[$key] = mb_substr($input[$key], 0, Foxpost_Woo_Parcel::SELLER_OWN_DATA_MAX_LENGTH);
            }
        }

        return $input;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook_suffix)
    {
        if ('woocommerce_page_foxpost-woo-parcel-wc-order-export' === $hook_suffix) {
            wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
            wp_enqueue_style('jquery-ui');
        }

        $cssVersion = $this->version . Foxpost_Woo_Parcel_Tools::getAssetRandom();

        wp_enqueue_style($this->plugin_name, dirname(plugin_dir_url(__FILE__)) . '/css/foxpost-woo-parcel-admin.css', array(), $cssVersion, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script('jquery-ui-datepicker');

        $jsVersion = $this->version . Foxpost_Woo_Parcel_Tools::getAssetRandom();

        wp_register_script($this->plugin_name, dirname(plugin_dir_url(__FILE__)) . '/js/foxpost-woo-parcel-admin.js', array('jquery'), $jsVersion, false);

        $translation_array = array(
            'empty_column_name' => Foxpost_Woo_Parcel::__('empty column name'),
            'wrong_date_range' => Foxpost_Woo_Parcel::__('Date From is greater than Date To'),
            'no_results' => Foxpost_Woo_Parcel::__('Nothing to export. Please, adjust your filters'),
            'empty' => Foxpost_Woo_Parcel::__('empty'),
        );

        wp_localize_script($this->plugin_name, 'foxpost_woo_parcel_backend_messages', $translation_array);

        wp_enqueue_script($this->plugin_name);
    }

    /**
     * Ajax entry point.
     *
     * @since 1.0.0
     */
    public function woo_parcel_ajax_request()
    {
        if (isset($_REQUEST['method'])) {
            $method = $_REQUEST['method'];
            if (
            in_array($method, array(
                'generate_stickers',
                'export_download',
                true
            ))) {
                $ajax = new Foxpost_Woo_Parcel_Export_Ajax();
                $ajax->$method();
            }
        }
        die();
    }

    /**
     * Defines shipping methods.
     *
     * @param array $methods
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function define_shipping_methods($methods)
    {
        $methods = array_merge($methods,
            Foxpost_Woo_Parcel_Shipping_Method::getAvailableShippingMethodsForRegister()
        );

        return $methods;
    }

    /**
     * Add admin menu item.
     *
     * @since 1.0.0
     */
    public function woo_parcel_add_menu()
    {
        return false;
        if (apply_filters('foxpost_woo_parcel_current_user_can_export', true)) {
            if (current_user_can('manage_woocommerce')) {
                add_submenu_page('woocommerce',
                    Foxpost_Woo_Parcel::__('Foxpost export'),
                    Foxpost_Woo_Parcel::__('Foxpost export'),
                    'view_woocommerce_reports',
                    'foxpost-woo-parcel-wc-order-export', array(
                        $this,
                        'render_menu'
                    ));
            } else // add after Sales Report!
            {
                add_menu_page(
                    Foxpost_Woo_Parcel::__('Foxpost export'),
                    Foxpost_Woo_Parcel::__('Foxpost export'),
                    'view_woocommerce_reports',
                    'foxpost-woo-parcel-wc-order-export', array(
                    $this,
                    'render_menu'
                ), null, '55.7');
            }
        }
    }

    /**
     * Render menu.
     *
     * @since 1.0.0
     */
    public function render_menu()
    {
        $this->render('foxpost-woo-parcel-admin-export-main', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
        $active_tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : 'export';
        if (method_exists($this, 'render_tab_' . $active_tab)) {
            $this->{'render_tab_' . $active_tab}();
        }
    }

    /**
     * Renders a partial template.
     *
     * @param  string $view
     * @param array $params
     * @param bool $echo
     * @param null|string $path_views
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function render($view, $params = array(), $echo = true, $path_views = null)
    {
        $params = apply_filters('foxpost_woo_parcel_admin_render_params', $params);
        $params = apply_filters('foxpost_woo_parcel_admin_render_params_' . $view, $params);

        extract($params, EXTR_SKIP);
        ob_start();

        if ($path_views) {
            include $path_views . "{$view}.php";
        } else {
            include $this->path_views_default . "{$view}.php";
        }

        $html = ob_get_clean();

        if ($echo) {
            echo $html;
        }

        return $html;
    }

    /**
     * Render export tab.
     *
     * @since 1.0.0
     */
    public function render_tab_export()
    {
        $shippingMethods = array();
        foreach (Foxpost_Woo_Parcel_Shipping_Method::getAvailableShippingMethods() as $available_shipping_method) {
            $shippingMethods[$available_shipping_method] = Foxpost_Woo_Parcel_Shipping_Method::getLabel($available_shipping_method);
        }
        $this->render('foxpost-woo-parcel-admin-tab-export', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'Foxpost_Woo_Parcel_Admin' => $this,
                'shippingMethods' => $shippingMethods,
            )
        );
    }

    /**
     * Add new section.
     *
     * @param array $settings
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function woo_parcel_add_section($settings)
    {
        $sections['foxpost_woo_parcel'] = Foxpost_Woo_Parcel::__('Foxpost');

        return $sections;
    }

    /**
     * Add new setting tab.
     *
     * @param array $settings_tabs
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function woo_add_settings_tab($settings_tabs)
    {
        $settings_tabs['foxpost_woo_parcel'] = Foxpost_Woo_Parcel::__('Foxpost settings');

        return $settings_tabs;
    }

    /**
     * Output sections.
     *
     * @since 1.0.0
     */
    public function output_sections()
    {
        global $current_section;

        $sections = $this->get_sections();

        if (empty($sections) || 1 === count($sections)) {
            return;
        }

        echo '<ul class="subsubsub">';

        $array_keys = array_keys($sections);

        foreach ($sections as $id => $label) {
            echo '<li><a href="' . admin_url('admin.php?page=wc-settings&tab=foxpost_woo_parcel' . '&section=' . sanitize_title($id)) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end($array_keys) == $id ? '' : '|' ) . ' </li>';
        }

        echo '</ul><br class="clear" />';
    }

    /**
     * Output the settings.
     *
     * @since 1.0.0
     */
    public function woo_settings_tabs_settings()
    {
        woocommerce_admin_fields($this->get_settings());
    }

    /**
     * Get sections.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function get_sections()
    {
        return apply_filters('woocommerce_get_sections_foxpost_woo_parcel', array());
    }

    /**
     * Saves settings.
     *
     * @since 1.0.0
     */
    public function woo_settings_save()
    {
        global $current_section;

        woocommerce_update_options($this->get_settings());

        if ($current_section) {
            do_action('woocommerce_update_options_foxpost_woo_parcel_' . $current_section);
        }
    }

    /**
     * Gets settings.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function get_settings()
    {
        $settings = array(
            'section_title' => array(
                'name' => Foxpost_Woo_Parcel::__('General'),
                'type' => 'title',
                'desc' => '',
                'id' => 'foxpost_woo_parcel_section_title',
                'class' => 'foxpost_woo_parcel_section_title',
            ),
            'title' => array(
                'name' => Foxpost_Woo_Parcel::__('Google Maps API key'),
                'type' => 'text',
                'desc' => Foxpost_Woo_Parcel::__('If you provide a Google Maps API key you will be able to display the map based APT chooser.'),
                'id' => 'foxpost_woo_parcel_google_maps_api_key',
                'class' => 'foxpost_woo_parcel_google_maps_api_key',
            ),
            'csv_api_user_name' => array(
                'name' => Foxpost_Woo_Parcel::__('Foxpost API username'),
                'type' => 'text',
                'desc' => Foxpost_Woo_Parcel::__('If you provide a Foxpost API username you will be able to send parcels to Foxpost API.'),
                'id' => 'foxpost_woo_parcel_csv_api_user_name',
                'class' => 'foxpost_woo_parcel_csv_api_user_name',
            ),
            'csv_api_password' => array(
                'name' => Foxpost_Woo_Parcel::__('Foxpost API password'),
                'type' => 'text',
                'desc' => Foxpost_Woo_Parcel::__('If you provide a Foxpost API password you will be able to send parcels to Foxpost API.'),
                'id' => 'foxpost_woo_parcel_csv_api_password',
                'class' => 'foxpost_woo_parcel_csv_api_password',
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'foxpost_woo_parcel_section_end',
                'class' => 'foxpost_woo_parcel_section_end',
            )
        );

        return apply_filters('woocommerce_get_settings_foxpost_woo_parcel', $settings);
    }

    /**
     * Adds foxpost export status column to order table.
     *
     * @param string $column
     *
     * @since 1.1.0
     */
    public function add_order_status_column_content($column)
    {
        global $post;

        if ('order_status' === $column) {
            $is_foxpost_parcel = metadata_exists('post', $post->ID, 'foxpost_woo_parcel_apt_id');
            if (!$is_foxpost_parcel) {
                return;
            }

            $foxpost_woo_parcel_apt_id = get_post_meta($post->ID, 'foxpost_woo_parcel_apt_id', true);

            if ($foxpost_woo_parcel_apt_id) {
                $aptData = Foxpost_Woo_Parcel_Tools::getAptDataById($foxpost_woo_parcel_apt_id);
            }

            if (isset($aptData->name)) {
                $html = strtr('<div class="fox-woo-order-selected-apt-block"><b>APT:</b> {aptName}, {aptAddress}, ({aptId})</div>', [
                        '{aptName}'    => esc_html($aptData->name),
                        '{aptCity}'    => esc_html($aptData->city),
                        '{aptAddress}' => esc_html($aptData->address),
                        '{aptId}'      => esc_html($foxpost_woo_parcel_apt_id),
                    ]
                );
                echo apply_filters('foxpost_woo_parcel_display_order_apt', $html, $aptData, $foxpost_woo_parcel_apt_id);
            } else {
                $html = strtr('<div class="fox-woo-order-hd-block">{hd}</div>', ['{hd}' => Foxpost_Woo_Parcel::__('Foxpost - Home Delivery'),]);
                echo apply_filters('foxpost_woo_parcel_display_order_hd', $html);
            }
        }

        if ('foxpost_export_status' === $column) {

            $is_foxpost_parcel = metadata_exists('post', $post->ID, 'foxpost_woo_parcel_apt_id');

            if (!$is_foxpost_parcel) {
                echo '<span>' . Foxpost_Woo_Parcel::__('Not Foxpost shipping order') . '</span>';

                return;
            }

            $api_export_date = get_post_meta($post->ID, 'foxpost_order_exported_api', true);

            $xls_export_date = get_post_meta($post->ID, 'foxpost_order_exported_xls', true);

            $foxpost_order_export_api_error = get_post_meta($post->ID, 'foxpost_order_export_api_error', true);
            $foxpost_order_generate_sticker_error = get_post_meta($post->ID, 'foxpost_order_generate_sticker_error', true);
            $clfox_id = get_post_meta($post->ID, 'foxpost_order_clfox_id', true);

            echo '<div class="fox-woo-p-block-export-status">';

            if (!empty($clfox_id)) {
                $foxpostApiManager = new Foxpost_Woo_Parcel_Foxpost_Api_Manager();
                echo '<div class="fox-woo-p-block-api"><span class="fox-woo-p-title-api" >' . Foxpost_Woo_Parcel::__('CLFOXID') . ': </span>';
                echo '<span class="fox-woo-p-color-green" >';
                echo strtr('<span class="fox-woo-p-color-green" ><a title="{title}" href="{url}" target="_blank">{text}</a></span>',
                    array(
                        '{url}' => $foxpostApiManager->getFoxpostApi()->getParcelTrackUrlByClfoxId($clfox_id),
                        '{text}' => esc_html($clfox_id),
                        '{title}' => Foxpost_Woo_Parcel::__('Click to see the parcel status.'),
                    )
                );
                echo '</span>';
                echo '</div>';
            }

            echo '<div class="fox-woo-p-block-api"><span class="fox-woo-p-title-api" >' . Foxpost_Woo_Parcel::__('API') . ': </span>';
            if (!empty($api_export_date)) {

                echo '<span class="fox-woo-p-color-green" >' . esc_html($api_export_date) . '</span>';
            } else {
                echo '<span class="fox-woo-p-color-no" >' . Foxpost_Woo_Parcel::__('No') . '</span>';
            }
            echo '</div>';

            echo ' <div class="fox-woo-p-block-api"><span class="fox-woo-p-title-xls" >' . Foxpost_Woo_Parcel::__('XLS') . ': </span>';
            if (!empty($xls_export_date)) {
                echo '<span class="fox-woo-p-color-green" >' . esc_html($xls_export_date) . '</span>';
            } else {
                echo '<span class="fox-woo-p-color-no" >' . Foxpost_Woo_Parcel::__('No') . '</span>';
            }
            echo '</div>';

            if (!empty($foxpost_order_export_api_error)) {
                $foxpost_order_export_api_error = apply_filters('foxpost_woo_parcel_admin_process_api_error', $foxpost_order_export_api_error);
                echo '<div class="fox-woo-p-title-api-errors" >' . Foxpost_Woo_Parcel::__('API errors') . ': </div>';
                echo '<span class="fox-woo-p-error" >(' . $foxpost_order_export_api_error . ')</span>';
            }
            if (!empty($foxpost_order_generate_sticker_error)) {
                $foxpost_order_generate_sticker_error = apply_filters('foxpost_woo_parcel_admin_generate_sticker_error', $foxpost_order_generate_sticker_error);
                echo '<div class="fox-woo-p-title-api-errors" >' . Foxpost_Woo_Parcel::__('Sticker API errors') . ': </div>';
                echo '<span class="fox-woo-p-error" >(' . $foxpost_order_generate_sticker_error . ')</span>';
            }

            echo '</div>';
        }
    }

    /**
     * Adds foxpost export status header column to order table.
     *
     * @param array $columns
     *
     * @since 1.1.0
     *
     * @return array
     */
    public function add_order_status_column_header($columns)
    {
        $new_columns = array();

        foreach ($columns as $column_name => $column_info) {
            if (
            in_array($column_name, array(
                'order_actions',
                'wc_actions'
            ), true)) {
                $label = Foxpost_Woo_Parcel::__('Foxpost export');
                $new_columns['foxpost_export_status'] = $label;
            }
            $new_columns[$column_name] = $column_info;
        }

        return $new_columns;
    }

    /**
     * @param array $actions
     *
     * @return mixed
     */
    public function woo_parcel_bulk_action_export($actions)
    {
        $actions['foxpost_export_to_xls'] = Foxpost_Woo_Parcel::__('Foxpost export to XLS');
        if (Foxpost_Woo_Parcel_Shipping_Method::hasCsvApiCredentials()) {
            $actions['foxpost_send_to_api'] = Foxpost_Woo_Parcel::__('Foxpost send to API');
            $actions['foxpost_generate_stickers'] = Foxpost_Woo_Parcel::__('Foxpost generate stickers');
        }

        return $actions;
    }

    public function woo_parcel_export_action($redirect_to, $action, $post_ids)
    {
        if ($action === 'foxpost_export_to_xls') {

            return add_query_arg(array(
                'foxpost_export_to_xls' => 1,
            ), $redirect_to);
        }

        if ($action === 'foxpost_send_to_api') {

            $foxpostApiManager = new Foxpost_Woo_Parcel_Foxpost_Api_Manager();

            $foxpostApiManager->createParcels($post_ids);

            return add_query_arg(array(
                'foxpost_send_to_api' => 1,
            ), $redirect_to);

        }
        if ($action === 'foxpost_generate_stickers') {

            return add_query_arg(array(
                'foxpost_generate_stickers' => 1,
            ), $redirect_to);

        }

        return $redirect_to;
    }

    /**
     *
     */
    public function woo_parcel_export_action_notices()
    {
        global $post_type, $pagenow;
        if ($pagenow === 'edit.php' && $post_type === 'shop_order') {

            if (isset($_REQUEST['foxpost_export_to_xls'])) {
                if (isset($_SESSION['foxpost_order_export_xls_success_message'])) {
                    echo strtr(
                        '<div class="notice updated is-dismissible"><p>{message}</p></div>'
                        , array('{message}' => esc_html($_SESSION['foxpost_order_export_xls_success_message']),));

                    unset($_SESSION['foxpost_order_export_xls_success_message']);
                }
            } elseif (isset($_REQUEST['foxpost_send_to_api'])) {
                if (isset($_SESSION['foxpost_order_export_api_global_error'])) {
                    echo strtr(
                        '<div class="notice error"><p>{message}</p></div>'
                        , array('{message}' => esc_html($_SESSION['foxpost_order_export_api_global_error']),));

                    unset($_SESSION['foxpost_order_export_api_global_error']);
                }

                if (isset($_SESSION['foxpost_order_export_api_success_message'])) {
                    echo strtr(
                        '<div class="notice updated is-dismissible"><p>{message}</p></div>'
                        , array('{message}' => esc_html($_SESSION['foxpost_order_export_api_success_message']),));

                    unset($_SESSION['foxpost_order_export_api_success_message']);
                }
            } else {
                if (isset($_SESSION['foxpost_order_sticker_api_global_error'])) {
                    echo strtr(
                        '<div class="notice error"><p>{message}</p></div>'
                        , array('{message}' => esc_html($_SESSION['foxpost_order_sticker_api_global_error']),));

                    unset($_SESSION['foxpost_order_sticker_api_global_error']);
                }

                if (isset($_SESSION['foxpost_order_sticker_api_success_message'])) {
                    echo strtr(
                        '<div class="notice updated is-dismissible"><p>{message}</p></div>'
                        , array('{message}' => esc_html($_SESSION['foxpost_order_sticker_api_success_message']),));

                    unset($_SESSION['foxpost_order_sticker_api_success_message']);
                }
            }
        }
    }

    /**
     * @param \WC_Order $order
     */
    function woo_parcel_admin_order_data_after_order_details($order)
    {
        $clfox_id = get_post_meta($order->get_id(), 'foxpost_order_clfox_id', true);
        if (!empty($clfox_id)) {
            $foxpostApiManager = new Foxpost_Woo_Parcel_Foxpost_Api_Manager();
            echo strtr('<div class="order_data_column"><h4>{caption}</h4><p><strong>{label}:</strong> '
                       . '<a title="{title}" href="{url}" target="_blank">{text}</a></p></div>',
                array(
                    '{url}' => $foxpostApiManager->getFoxpostApi()->getParcelTrackUrlByClfoxId($clfox_id),
                    '{text}' => esc_html($clfox_id),
                    '{title}' => Foxpost_Woo_Parcel::__('Click to see the parcel status.'),
                    '{caption}' => Foxpost_Woo_Parcel::__('Extra Details'),
                    '{label}' => Foxpost_Woo_Parcel::__('CLFOXID'),
                )
            );
        }
    }
}
