<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @package    Foxpost_Woo_Parcel
 * @subpackage Foxpost_Woo_Parcel/public/partials
 * @since      1.0.0
 *
 * @var $shipping_method string
 * @var $shipping_method_name string
 * @var $aptData \StdClass
 */
if (!defined('ABSPATH')) {
    die;
}

use Foxpost_Woo_Parcel\Admin\Includes\Foxpost_Woo_Parcel_Foxpost_Api_Manager;
use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel;
use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel_Shipping_Method;


if (isset($clfox_id) && !empty($clfox_id)) {
    $foxpostApiManager = new Foxpost_Woo_Parcel_Foxpost_Api_Manager();

    echo strtr('<div class="foxpost_woo_parcel_order_clfoxid_block"><p><strong>{label}:</strong> '
               . '<a title="{title}" href="{url}" target="_blank">{text}</a></p></div>',
        array(
            '{url}' => $foxpostApiManager->getFoxpostApi()->getParcelTrackUrlByClfoxId($clfox_id),
            '{text}' => esc_html($clfox_id),
            '{title}' => Foxpost_Woo_Parcel::__('Click to see the parcel status.'),
            '{label}' => Foxpost_Woo_Parcel::__('CLFOXID'),
        )
    );
}
?>
    <h2>
        <?php echo Foxpost_Woo_Parcel::__('The selected shippind mode is:') ?><?php echo esc_html($shipping_method_name) ?>
    </h2>

<?php

if (
    is_object($aptData)
    && Foxpost_Woo_Parcel_Shipping_Method::isMethodAptShippingMethod($shipping_method)
) {
    ?>
    <address>
        <div>
            <?php echo Foxpost_Woo_Parcel::esc_html__('Választott csomagpont:') ?>
        </div>
        <b><?php echo esc_html($aptData->name) ?></b>
        <div><?php echo esc_html($aptData->city) ?></div>
        <div><?php echo esc_html($aptData->address) ?></div>

    </address>
    <?php
}
?>
