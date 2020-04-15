<?php

namespace Foxpost_Woo_Parcel\Includes;

if (!defined('ABSPATH')) {
    die;
}

/**
 * Class Foxpost_Woo_Parcel_Tools
 */
class Foxpost_Woo_Parcel_Tools {

    const TRANSIENT_KEY_APT_DATA = 'foxpost-woo-parcel-apt-data';
    /**
     * @var float|int
     */
    public static $aptDataTransientExpiration = HOUR_IN_SECONDS;

    /**
     * @var string
     */
    public static $aptId = 'operator_id';

    /**
     * Gets APT data array.
     *
     * @param bool $force Reload data instead of transient.
     *
     * @return array|bool
     */
    public static function getAptData($force = false)
    {
        $aptList = get_transient(self::TRANSIENT_KEY_APT_DATA);
        if (false === $aptList || $force) {
            $aptResponse = Foxpost_Woo_Parcel_Shipping_Method::getAptDataFromApi();
            if (is_wp_error($aptResponse)) {
                return false;
            }
            set_transient(self::TRANSIENT_KEY_APT_DATA, $aptResponse['body'], self::$aptDataTransientExpiration);
            $aptList = $aptResponse['body'];
        }

        return json_decode($aptList);
    }

    /**
     * @param string $aptId
     * @param bool $force Reload data instead of transient.
     *
     * @return bool|\StdClass
     */
    public static function getAptDataById($aptId, $force = false)
    {
        $aptData = self::getAptData($force);
        foreach ($aptData as $apt) {
            if ((string) $aptId === (string) $apt->{self::$aptId}) {
                return $apt;
            }
        }

        return false;
    }

    /**
     * Whether the given apt Id is valid or not.
     *
     * @param string $aptId
     * @param bool $force Reload data instead of transient.
     *
     * @return bool
     */
    public static function isValidAptId($aptId, $force = false)
    {
        $aptData = self::getAptData($force);
        foreach ($aptData as $apt) {
            if ((string) $aptId === (string) $apt->{self::$aptId}) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets apt dropdown html.
     *
     * @param string $name
     * @param string $id
     * @param string $class
     *
     * @return string
     */
    public static function getAptSelect($name, $id, $class)
    {
        $select  = '';
        $aptData = self::getAptData();
        if (is_array($aptData)) {

            setlocale(LC_ALL, 'hu_HU.utf8');
            usort($aptData, function ($a, $b) {
                return strcoll($a->city . $a->name, $b->city . $b->name);
            });

            $select = '<select name="' . $name . '" id="' . $id . '" class="' . $class . '">';
            $select .= '<optgroup label=""><option>' . Foxpost_Woo_Parcel::__('Please select APT') . '</option></optgroup>';
            $city   = '';
            foreach ($aptData as $apt) {
                $aptId = $apt->{self::$aptId};
                if ($city !== $apt->city) {
                    if ($city !== '') {
                        $select .= '</optgroup>';
                    }
                    $select .= '<optgroup label="' . esc_html($apt->city) . '">';
                }
                $select .= '<option value="' . esc_html($aptId) . '">' . esc_html($apt->name) . '</option>';
                $city   = $apt->city;
            }
            if ($city !== '') {
                $select .= '</optgroup>';
            }
            $select .= '</select>';
        }

        return $select;
    }

    /**
     * Gets un-qualified class name from namespaced class.
     *
     * @param string $class
     *
     * @return string
     */
    public static function getUnQualifiedClassName($class)
    {
        return substr($class, strrpos($class, '\\') + 1);
    }

    /**
     * Debug mode is turned on or not.
     *
     * @return bool
     */
    public static function isDebugMode()
    {
        return defined('WP_DEBUG') && WP_DEBUG;
    }

    /**
     * Gets random string for asset version if debug mode is enabled.
     *
     * @return string
     */
    public static function getAssetRandom()
    {
        return self::isDebugMode() ? '.'.time() : '' ;
    }
}