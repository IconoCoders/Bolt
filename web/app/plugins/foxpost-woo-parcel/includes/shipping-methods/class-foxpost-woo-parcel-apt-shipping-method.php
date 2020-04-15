<?php

namespace Foxpost_Woo_Parcel\Includes\ShippingMethods;

/**
 * Register all actions and filters for the plugin
 *
 * @since      1.0.0
 *
 * @package    Foxpost_Woo_Parcel
 * @subpackage Foxpost_Woo_Parcel/includes
 */

use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel;
use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel_Shipping_Method;

if (!defined('ABSPATH')) {
    die;
}

/**
 * Class Foxpost_Woo_Parcel_Apt_Shipping_Method
 */
class Foxpost_Woo_Parcel_Apt_Shipping_Method extends Foxpost_Woo_Parcel_Shipping_Method {

    /**
     * Constructor for your shipping class
     *
     * @param int $instance_id
     *
     * @since 1.0.0
     */
    public function __construct($instance_id = 0)
    {
        $this->id                 = self::FOXPOST_WOO_PARCEL_APT_SHIPPING_METHOD;
        $this->instance_id        = absint($instance_id);
        $this->method_title       = Foxpost_Woo_Parcel::__('Foxpost APT shipping');
        $this->method_description = Foxpost_Woo_Parcel::__('Foxpost APT shipping');

        $this->supports = array(
            'shipping-zones',
            'instance-settings',
            'instance-settings-modal',
        );

        $this->init();
    }

    /**
     * Init your settings
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function init()
    {
        $this->init_form_fields();

        $this->enabled             = $this->get_option('enabled');
        $this->title               = $this->get_option('title');
        $this->fee                 = $this->get_option('fee');
        $this->free_fee            = $this->get_option('free_fee');
        $this->max_weight          = $this->get_option('max_weight');
        $this->phone_required      = $this->get_option('phone_required');
        $this->seller_own_data     = $this->get_option('seller_own_data');
        $this->default_parcel_size = $this->get_option('default_parcel_size');

        // Save settings in admin if you have any defined
        add_action(
            'woocommerce_update_options_shipping_' . $this->id, array(
                $this,
                'process_admin_options'
            )
        );
    }

    /**
     * Define settings field for this shipping.
     *
     * @since 1.0.0
     *
     * @return void
     */
    function init_form_fields()
    {
        $this->instance_form_fields = array(
            'enabled'             => array(
                'title'   => Foxpost_Woo_Parcel::__('Enable/Disable'),
                'type'    => 'checkbox',
                'label'   => Foxpost_Woo_Parcel::__('Enable this shipping method'),
                'default' => 'no',
            ),
            'title'               => array(
                'title'       => Foxpost_Woo_Parcel::__('Method Title'),
                'type'        => 'text',
                'description' => Foxpost_Woo_Parcel::__('This controls the title which the user sees during checkout.'),
                'default'     => Foxpost_Woo_Parcel::__('Foxpost - Delivery to APT Size S'),
            ),
            'type'                => array(
                'title'       => Foxpost_Woo_Parcel::__('Fee Type'),
                'type'        => 'select',
                'description' => Foxpost_Woo_Parcel::__('How to calculate delivery charges'),
                'default'     => 'fixed',
                'options'     => array(
                    'fixed'   => Foxpost_Woo_Parcel::__('Fixed amount'),
                    'product' => Foxpost_Woo_Parcel::__('Fixed amount per product'),
                ),
                'desc_tip'    => true,
            ),
            'fee'                 => array(
                'title'       => Foxpost_Woo_Parcel::__('Delivery Fee'),
                'type'        => 'price',
                'description' => Foxpost_Woo_Parcel::__('What fee do you want to charge for Foxpost APT delivery, disregarded if you choose free. Leave blank to disable.'),
                'default'     => 100,
                'desc_tip'    => true,
                'placeholder' => wc_format_localized_price(0)
            ),
            'free_fee'            => array(
                'title'       => sprintf(Foxpost_Woo_Parcel::esc_html__('Free delivery over x %s'), self::get_currency_symbol()),
                'type'        => 'price',
                'description' => sprintf(Foxpost_Woo_Parcel::esc_html__('Free delivery over x %s'), self::get_currency_symbol()),
                'default'     => 10000000,
                'desc_tip'    => true,
                'placeholder' => wc_format_localized_price(0)
            ),
            'max_weight'          => array(
                'title'       => Foxpost_Woo_Parcel::__('Max Weight'),
                'type'        => 'text',
                'description' => Foxpost_Woo_Parcel::__('The maximum weight of a parcel is 25kg.'),
                'default'     => Foxpost_Woo_Parcel::__('25'),
                'desc_tip'    => true,
            ),
            'phone_required'      => array(
                'title'   => Foxpost_Woo_Parcel::__('Yes/No'),
                'type'    => 'checkbox',
                'label'   => Foxpost_Woo_Parcel::__('Phone number required or not'),
                'default' => 'yes',
            ),
            'default_parcel_size' => array(
                'type'    => 'select',
                'title'   => Foxpost_Woo_Parcel::__('Default parcel size'),
                'default' => Foxpost_Woo_Parcel_Shipping_Method::getDefaultParcelSize(),
                'options' => Foxpost_Woo_Parcel_Shipping_Method::getParcelSizes(),
            ),
            'seller_own_data'     => array(
                'type'        => 'textarea',
                'title'       => Foxpost_Woo_Parcel::__('Seller remarks for Foxpost'),
                'description' => Foxpost_Woo_Parcel::__('This remark will be placed into Foxpost export. Max 50 chars.'),
                'default'     => '',
                'desc_tip'    => true,
            ),
        );
    }

    /**
     * This function is used to calculate the shipping cost.
     * Within this function we can check for weights, dimensions and other
     * parameters.
     *
     * @param mixed $package
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function calculate_shipping($package = array())
    {
        $shipping_total = 0;
        $fee            = (int) $this->fee;
        $free_fee       = (int) $this->get_option('free_fee');
        $calculation_type = $this->get_option('type', 'fixed');

        if ($calculation_type === 'fixed') {
            if ($free_fee > 0 && WC()->cart->subtotal >= $free_fee) {
                $shipping_total = 0;
            } else {
                $shipping_total = $fee;
            }
        } elseif ($calculation_type === 'product') {
            foreach (WC()->cart->get_cart() as $item_id => $values) {
                $product = $values['data'];

                if ($values['quantity'] > 0 && $product->needs_shipping()) {
                    $shipping_total += ( $fee * $values['quantity'] );
                }
            }
        }

        $this->add_rate(
            array(
                'label'   => $this->title,
                'package' => $package,
                'cost'    => $shipping_total,
            )
        );
    }
}
