<?php

namespace Foxpost_Woo_Parcel\Includes;

/**
 * Register all actions and filters for the plugin
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
 * Class Foxpost_Woo_Parcel_Shipping_Method
 */
abstract class Foxpost_Woo_Parcel_Shipping_Method extends \WC_Shipping_Method {

    /**
     * Phone required.
     *
     * @var bool
     *
     * @since 1.0.0
     */
    public $phone_required = true;
    /**
     * Default parcel size.
     *
     * @var string
     *
     * @since 1.0.0
     */
    public $default_parcel_size;
    /**
     * Seller remarks.
     *
     * @var string
     *
     * @since 1.0.0
     */
    public $seller_own_data = '';
    /**
     * Maximum weight.
     *
     * @var int
     *
     * @since 1.0.0
     */
    public $max_weight = 0;
    /**
     * Free delivery fee.
     *
     * @var int
     *
     * @since 1.0.0
     */
    public $free_fee = 0;
    /**
     * APT shipping method.
     *
     * @since 1.0.0
     */
    const FOXPOST_WOO_PARCEL_APT_SHIPPING_METHOD
        = 'foxpost_woo_parcel_apt_shipping';
    /**
     * Home delivery shipping merthod.
     *
     * @since 1.0.0
     */
    const FOXPOST_WOO_PARCEL_HOME_DELIVERY_SHIPPING_METHOD
        = 'foxpost_woo_parcel_home_delivery_shipping';
    /**
     * XS size.
     *
     * @since 1.0.0
     */
    const PARCEL_SIZE_XS = 'XS';
    /**
     * S size.
     *
     * @since 1.0.0
     */
    const PARCEL_SIZE_S = 'S';
    /**
     * M size.
     *
     * @since 1.0.0
     */
    const PARCEL_SIZE_M = 'M';
    /**
     * L size.
     *
     * @since 1.0.0
     */
    const PARCEL_SIZE_L = 'L';
    /**
     * XL size.
     *
     * @since 1.0.0
     */
    const PARCEL_SIZE_XL = 'XL';
    /**
     * S size.
     *
     * @since 1.0.0
     */
    const DEFAULT_PARCEL_SIZE = 'S';

    /**
     * Shipping methods.
     *
     * @var array
     *
     * @since 1.0.0
     */
    protected static $shippingMethods = array();


    /**
     * Available shipping methods.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public static $availableShippingMethods = array();

    /**
     * Is the given shipping name apt shipping method.
     *
     * @param string $method Method to check.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public static function isMethodAptShippingMethod($method)
    {
        return strpos($method, self::FOXPOST_WOO_PARCEL_APT_SHIPPING_METHOD) === 0;
    }

    /**
     * Is the given shipping name home delivery shipping method.
     *
     * @param string $method Method to check.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public static function isMethodHomeDeliveryShipingMethod($method)
    {
        return strpos($method, self::FOXPOST_WOO_PARCEL_HOME_DELIVERY_SHIPPING_METHOD) === 0;
    }

    /**
     * Gets available shipping methods.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public static function getAvailableShippingMethods()
    {
        return array_keys(self::getAvailableShippingMethodsForRegister());
    }

    /**
     * Gets available shipping methods.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public static function getAvailableShippingMethodsForRegister()
    {
        if (empty(self::$availableShippingMethods)) {
            $shipping_method_dir = __DIR__ . '/shipping-methods';
            foreach (glob($shipping_method_dir . '/*.php') as $file) {
                $file_name                                 = pathinfo($file, PATHINFO_FILENAME);
                $file_name                                 = str_replace('class-', '', $file_name);
                $class_id                                  = str_replace('-', '_', $file_name);
                $class_name                                = mb_convert_case($class_id, MB_CASE_TITLE, "UTF-8");
                $class_id                                  = str_replace('_method', '', $class_id);
                self::$availableShippingMethods[$class_id] = '\\Foxpost_Woo_Parcel\\Includes\\ShippingMethods\\' . $class_name;
            }
        }

        return self::$availableShippingMethods;
    }

    /**
     * @since 1.0.0
     *
     * @return array
     */
    public static function getLabels()
    {
        static $labels;
        if (null === $labels) {
            $labels = array(
                self::FOXPOST_WOO_PARCEL_APT_SHIPPING_METHOD           => Foxpost_Woo_Parcel::__('Foxpost APT shipping'),
                self::FOXPOST_WOO_PARCEL_HOME_DELIVERY_SHIPPING_METHOD => Foxpost_Woo_Parcel::__('Foxpost home delivery'),
            );
        }

        return $labels;
    }

    /**
     * Gets label.
     *
     * @param string $method  Shipping method.
     * @param string $default Default value.
     *
     * @since 1.0.0
     *
     * @return mixed|string
     */
    public static function getLabel($method, $default = '')
    {
        $labels = self::getLabels();

        return isset($labels[$method]) ? $labels[$method] : $default;
    }

    /**
     * Whether the given method is a valid method or not.
     *
     * @param string $method Shipping method.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public static function isValidShippingMethod($method)
    {
        $method = self::normalizeShippingMethodId($method);

        return in_array($method, self::getAvailableShippingMethods(), true);
    }

    /**
     * @param string $shipping_method
     *
     * @return string
     */
    public static function normalizeShippingMethodId($shipping_method)
    {
        return preg_replace('#(^.*?)\:\d+#', "$1", $shipping_method);
    }

    /**
     * Check if shipping is available
     *
     * @param array $package
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function is_available($package)
    {
        return !( 'no' === $this->enabled );
    }

    /**
     * Gets current currency.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public static function get_currency()
    {
        return get_woocommerce_currency();
    }

    /**
     * Gets currency symbol.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public static function get_currency_symbol()
    {
        return get_woocommerce_currency_symbol();
    }

    /**
     * Gets Google Maps API key.
     *
     * @since 1.0.0
     *
     * @return string|false
     */
    public static function getGoogleMapsApiKey()
    {
        return get_option('foxpost_woo_parcel_google_maps_api_key', false);
    }

    /**
     * Has Google Maps API key or not.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public static function hasGoogleMapsApiKey()
    {
        return !empty(self::getGoogleMapsApiKey());
    }

    /**
     * Gets Csomagvarazslo API user name.
     *
     * @since 1.1.0
     *
     * @return string|false
     */
    public static function getCsvApiUserName()
    {
        return get_option('foxpost_woo_parcel_csv_api_user_name', false);
    }

    /**
     * Gets Csomagvarazslo API password.
     *
     * @since 1.1.0
     *
     * @return string|false
     */
    public static function getCsvApiPassword()
    {
        return get_option('foxpost_woo_parcel_csv_api_password', false);
    }

    /**
     * Has Csomagvarazslo API credentials or not.
     *
     * @since 1.1.0
     *
     * @return bool
     */
    public static function hasCsvApiCredentials()
    {
        return !empty(self::getCsvApiUserName())
               && !empty(self::getCsvApiPassword());
    }

    /**
     * @since 1.0.0
     *
     * @return array|\WP_Error
     */
    public static function getAptDataFromApi()
    {
        $apiUrl = 'https://cdn.foxpost.hu/foxpost_terminals_extended_v3.json';

        return wp_remote_get($apiUrl);
    }

    /**
     * @since 1.0.0
     *
     * @return array
     */
    public static function getParcelSizes()
    {
        return array(
            self::PARCEL_SIZE_XS => Foxpost_Woo_Parcel::__('XS size'),
            self::PARCEL_SIZE_S  => Foxpost_Woo_Parcel::__('S size'),
            self::PARCEL_SIZE_M  => Foxpost_Woo_Parcel::__('M size'),
            self::PARCEL_SIZE_L  => Foxpost_Woo_Parcel::__('L size'),
            self::PARCEL_SIZE_XL => Foxpost_Woo_Parcel::__('XL size'),
        );
    }

    /**
     * @since 1.0.0
     *
     * @return string
     */
    public static function getDefaultParcelSize()
    {
        return self::DEFAULT_PARCEL_SIZE;
    }
}