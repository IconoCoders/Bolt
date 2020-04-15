<?php

namespace Foxpost_Woo_Parcel\Includes;
/**
 * The public-facing functionality of the plugin.
 *
 * @since 1.0.0
 *
 * @package    Foxpost_Woo_Parcel
 * @subpackage Foxpost_Woo_Parcel/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Foxpost_Woo_Parcel
 * @subpackage Foxpost_Woo_Parcel/public
 */
class Foxpost_Woo_Parcel_Public {

    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string $version The current version of this plugin.
     */
    private $version;

    /**
     * Default view path.
     *
     * @since 1.0.0
     *
     * @var string
     */
    private $path_views_default;

    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     *
     * @return void
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->path_views_default = dirname(plugin_dir_path(__FILE__))
                                    . '/public/partials/';

    }

    /**
     * Sets routing for iframe source.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function woo_parcel_post_api_route()
    {
        register_rest_route('foxpost-woo-parcel/v2', '/get-map-apt-finder/', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_content_by_slug'),
            'args' => array(
                'slug' => array(
                    'required' => false
                )
            )
        ));
    }

    /**
     * Get content by slug
     *
     * @param \WP_REST_Request $request
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_content_by_slug(\WP_REST_Request $request)
    {
        header('Content-Type: text/html');

        $cssVersion = $this->version . Foxpost_Woo_Parcel_Tools::getAssetRandom();
        $jsVersion = $this->version . Foxpost_Woo_Parcel_Tools::getAssetRandom();

        echo $this->render('foxpost-woo-parcel-apt-finder-new', array(
            'apiKey' => Foxpost_Woo_Parcel_Shipping_Method::getGoogleMapsApiKey(),
            'cssUri' => dirname(plugin_dir_url(__FILE__)) . '/public/css/',
            'jsUri' => dirname(plugin_dir_url(__FILE__)) . '/public/js/',
            'imgUri' => dirname(plugin_dir_url(__FILE__)) . '/public/img/',
            'cssVersion' => $cssVersion,
            'jsVersion' => $jsVersion,
        ), false);
        die();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function enqueue_styles()
    {
        $cssVersion = $this->version . Foxpost_Woo_Parcel_Tools::getAssetRandom();

        wp_enqueue_style($this->plugin_name,
            dirname(plugin_dir_url(__FILE__)) . '/public/css/foxpost-woo-parcel-public.css',
            array(), $cssVersion, 'all');

        wp_enqueue_style('wp-jquery-ui-dialog', '', array('jquery'), null, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        $jsVersion = $this->version . Foxpost_Woo_Parcel_Tools::getAssetRandom();

        if ($this->isCheckout()) {

            $apiKey = Foxpost_Woo_Parcel_Shipping_Method::getGoogleMapsApiKey();
            if (!empty(get_option('foxpost_woo_parcel_google_maps_api_key', false))) {
                wp_enqueue_script('maps_googleapis_com',
                    'https://maps.googleapis.com/maps/api/js?key=' . $apiKey);
            }
        }

        wp_enqueue_script('jquery-ui-dialog', '', array('jquery'), null, true);

        wp_register_script($this->plugin_name . '_frontend',
            dirname(plugin_dir_url(__FILE__)) . '/public/js/foxpost-woo-parcel-public.js',
            array('jquery'), $jsVersion);

        $translation_array = array(
            'iframe_src' => '/?rest_route=/foxpost-woo-parcel/v2/get-map-apt-finder',
        );
        wp_localize_script($this->plugin_name . '_frontend', 'foxpost_woo_parcel_frontend_messages', $translation_array);

        wp_enqueue_script($this->plugin_name . '_frontend');
    }

    /**
     * @since 1.0.0
     *
     * @return bool
     */
    public function woo_parcel_checkout_process()
    {
        $chosen_methods = WC()->session->get('chosen_shipping_methods');
        if (!isset($chosen_methods[0])) {
            wc_add_notice(Foxpost_Woo_Parcel::__('Please select shipping method'), 'error');

            return false;
        }

        if (Foxpost_Woo_Parcel_Shipping_Method::isValidShippingMethod($chosen_methods[0])) {
            $shipping_methods = WC()->shipping()->get_shipping_methods();
            if (is_array($shipping_methods)) {
                foreach ($shipping_methods as $shipping_method) {
                    if (Foxpost_Woo_Parcel_Shipping_Method::normalizeShippingMethodId($chosen_methods[0]) === $shipping_method->id) {
                        if (isset($shipping_method->max_weight)) {
                            if ($shipping_method->max_weight > 0) {
                                $total_weight = WC()->cart->get_cart_contents_weight();
                                if ($total_weight > $shipping_method->max_weight) {
                                    wc_add_notice(sprintf(Foxpost_Woo_Parcel::__('For the chosen shipping method the maximum weight is %s'), $shipping_method->max_weight), 'error');

                                    return false;
                                }
                            }
                        }
                    }
                }
            }
        }

        if (Foxpost_Woo_Parcel_Shipping_Method::isMethodAptShippingMethod($chosen_methods[0])) {
            if (!isset($_POST['foxpost_woo_parcel_apt_id'])) {
                wc_add_notice(Foxpost_Woo_Parcel::__('Please select an APT'), 'error');

                return false;
            }
            if (!Foxpost_Woo_Parcel_Tools::isValidAptId($_POST['foxpost_woo_parcel_apt_id'])) {
                wc_add_notice(Foxpost_Woo_Parcel::__('Please select a valid APT'), 'error');

                return false;
            }
            if ($this->isPhoneNumberRequired()) {
                if (!isset($_POST['billing_phone']) || empty($_POST['billing_phone'])) {
                    wc_add_notice(Foxpost_Woo_Parcel::__('Please provide a mobile phone number'), 'error');

                    return false;
                }

                if (!preg_match('/^\+36(20|30|31|70)\d{7}$/', $_POST['billing_phone'])) {
                    wc_add_notice(Foxpost_Woo_Parcel::__('Please provide a valid mobile phone number'), 'error');

                    return false;
                }
            }
        }
    }

    /**
     * @param int $order_id Order id.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function woo_parcel_checkout_update_order_meta($order_id)
    {
        $order = wc_get_order($order_id);
        $shipping = $order->get_items('shipping');
        $shipping_method = Foxpost_Woo_Parcel_Shipping_Method::normalizeShippingMethodId(reset($shipping)->get_method_id());
        if (!Foxpost_Woo_Parcel_Shipping_Method::isValidShippingMethod($shipping_method)) {
            return;
        }

        $foxpost_woo_parcel_apt_id = isset($_POST['foxpost_woo_parcel_apt_id']) ? $_POST['foxpost_woo_parcel_apt_id'] : '';
        if (Foxpost_Woo_Parcel_Shipping_Method::isMethodHomeDeliveryShipingMethod($shipping_method)) {
            $foxpost_woo_parcel_apt_id = '';
        }

        update_post_meta($order_id, 'foxpost_woo_parcel_apt_id', sanitize_text_field($foxpost_woo_parcel_apt_id));
        update_post_meta($order_id, 'foxpost_woo_parcel_selected_parcel_size',
            strtolower(sanitize_text_field(get_option('default_parcel_size',
                Foxpost_Woo_Parcel_Shipping_Method::getDefaultParcelSize()))));

        $unique_barcode = apply_filters('foxpost_order_api_create_parcel_set_unique_barcode', '');
        $reference_code = apply_filters('foxpost_order_api_create_parcel_set_reference_code', '');

        update_post_meta($order_id, 'foxpost_woo_parcel_unique_barcode', $unique_barcode);
        update_post_meta($order_id, 'foxpost_woo_parcel_reference_code', $reference_code);
    }

    /**
     * @param int $order_id
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function woo_parcel_thankyou_page($order_id)
    {
        $order = wc_get_order($order_id);
        $shipping = $order->get_items('shipping');
        $shipping_method = Foxpost_Woo_Parcel_Shipping_Method::normalizeShippingMethodId(reset($shipping)->get_method_id());
        if (!Foxpost_Woo_Parcel_Shipping_Method::isValidShippingMethod($shipping_method)) {
            return;
        }
        $aptData = array();
        if (Foxpost_Woo_Parcel_Shipping_Method::isMethodAptShippingMethod($shipping_method)) {
            $foxpost_woo_parcel_apt_id = get_post_meta($order_id, 'foxpost_woo_parcel_apt_id', true);
            $aptData = Foxpost_Woo_Parcel_Tools::getAptDataById($foxpost_woo_parcel_apt_id);
        }

        $this->render('foxpost-woo-parcel-order-thankyou', array(
                'shipping_method_name' => $order->get_shipping_method(),
                'shipping_method' => $shipping_method,
                'aptData' => $aptData,
            )
        );
    }

    /**
     * @param int $order_id
     *
     * @since 1.1.0
     *
     * @return void
     */
    public function woo_parcel_view_order($order_id)
    {
        $order = wc_get_order($order_id);
        $shipping = $order->get_items('shipping');
        $shipping_method = Foxpost_Woo_Parcel_Shipping_Method::normalizeShippingMethodId(reset($shipping)->get_method_id());
        if (!Foxpost_Woo_Parcel_Shipping_Method::isValidShippingMethod($shipping_method)) {
            return;
        }
        $aptData = array();
        if (Foxpost_Woo_Parcel_Shipping_Method::isMethodAptShippingMethod($shipping_method)) {
            $foxpost_woo_parcel_apt_id = get_post_meta($order_id, 'foxpost_woo_parcel_apt_id', true);
            $aptData = Foxpost_Woo_Parcel_Tools::getAptDataById($foxpost_woo_parcel_apt_id);
        }
        $clfox_id = get_post_meta($order->get_id(), 'foxpost_order_clfox_id', true);
        $this->render('foxpost-woo-parcel-order-thankyou', array(
                'shipping_method_name' => $order->get_shipping_method(),
                'shipping_method' => $shipping_method,
                'aptData' => $aptData,
                'clfox_id' => $clfox_id,
            )
        );
    }

    /**
     * @param \WC_Order $order
     * @param bool $sent_to_admin
     * @param bool $plain_text
     * @param string $email
     *
     * @since 1.1.0
     */
    public function woo_parcel_email_before_order_table($order, $sent_to_admin, $plain_text, $email)
    {
        $shipping = $order->get_items('shipping');
        $shipping_method = Foxpost_Woo_Parcel_Shipping_Method::normalizeShippingMethodId(reset($shipping)->get_method_id());
        if (!Foxpost_Woo_Parcel_Shipping_Method::isValidShippingMethod($shipping_method)) {
            return;
        }
        $aptData = array();
        if (Foxpost_Woo_Parcel_Shipping_Method::isMethodAptShippingMethod($shipping_method)) {
            $foxpost_woo_parcel_apt_id = get_post_meta($order->get_id(), 'foxpost_woo_parcel_apt_id', true);
            $aptData = Foxpost_Woo_Parcel_Tools::getAptDataById($foxpost_woo_parcel_apt_id);
        }
        $clfox_id = get_post_meta($order->get_id(), 'foxpost_order_clfox_id', true);
        $this->render('foxpost-woo-parcel-order-thankyou', array(
                'shipping_method_name' => $order->get_shipping_method(),
                'shipping_method' => $shipping_method,
                'aptData' => $aptData,
                'clfox_id' => $clfox_id,
            )
        );
    }

    /**
     * Order checkout page.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function woo_parcel_review_order_after_order_total()
    {
        $apiKey = Foxpost_Woo_Parcel_Shipping_Method::getGoogleMapsApiKey();
        ?>
        <tr class="foxpost_woo_parcel_apt_select_row">
            <td colspan="2" class="foxpost_woo_parcel_apt_select_td">
                <p>
                    <?php echo Foxpost_Woo_Parcel::__('Please select an <strong>APT</strong> from dropdown'); ?>
                    <?php echo !empty($apiKey) ? Foxpost_Woo_Parcel::__(', or use the map based finder') : '' ?>
                    .
                </p>
                <?php echo Foxpost_Woo_Parcel_Tools::getAptSelect('foxpost_woo_parcel_apt_id', 'foxpost_woo_parcel_apt_id', 'foxpost_woo_parcel_apt_select') ?>
                <?php
                if ( !empty($apiKey) ) {
                ?>
                <a class="button alt map-chooser-button"
                   data-toggle="modal"
                   href="#"><?php echo Foxpost_Woo_Parcel::__('Select from map') ?></a>
                <div>
                    <?php } ?>
                </div>
            </td>
        </tr>
        <?php

    }

    /**
     * Renders a partial template.
     *
     * @param string $view
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
        $params = apply_filters('foxpost_woo_parcel_public_render_params', $params);
        $params = apply_filters('foxpost_woo_parcel_public_render_params_' . $view, $params);

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
     * Footer hook.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function woo_parcel_wp_footer()
    {
        if ($this->isCheckout()) {
            $this->render('foxpost-woo-parcel-apt-finder-modal', array());
        }
    }

    /**
     * Add extra fields to checkout form.
     *
     * @param array $fields
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function woo_parcel_override_checkout_fields($fields)
    {
        if ($this->isPhoneNumberRequired()) {
            $fields['billing']['billing_phone']['placeholder'] = '+36301234567';
            $fields['billing']['billing_phone']['description'] = Foxpost_Woo_Parcel::__('Please use mobile phone number.');
            $fields['billing']['billing_phone']['required'] = true;
        }

        return $fields;
    }

    /**
     * Whether the phone number filed is required or not.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    private function isPhoneNumberRequired()
    {
        $chosen_methods = WC()->session->get('chosen_shipping_methods');
        if (!isset($chosen_methods[0])) {
            wc_add_notice(Foxpost_Woo_Parcel::__('Please select shipping method'), 'error');

            return false;
        }

        $shipping_methods = WC()->shipping->get_shipping_methods();

        foreach ($shipping_methods as $id => $shipping_method) {
            if (isset($shipping_method->enabled) && 'yes' === $shipping_method->enabled) {
                if (Foxpost_Woo_Parcel_Shipping_Method::normalizeShippingMethodId($shipping_method->id) === $chosen_methods[0]) {
                    if (isset($shipping_method->phone_required)) {
                        if ('yes' === $shipping_method->phone_required) {
                            return true;
                        }

                    }
                }
            }
        }

        return false;
    }

    /*
     * Check if is checkout and not payment page and order recieved page
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function isCheckout()
    {
        return is_checkout() && !is_wc_endpoint_url('order-received') && !is_wc_endpoint_url('order-pay');
    }
}
