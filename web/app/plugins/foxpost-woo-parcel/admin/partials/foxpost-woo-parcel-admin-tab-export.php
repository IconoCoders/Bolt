<?php

use Foxpost_Woo_Parcel\Admin\Includes\Foxpost_Woo_Parcel_Admin;

/**
 * @var $Foxpost_Woo_Parcel_Admin Foxpost_Woo_Parcel_Admin
 * @var $ajaxurl string
 * @var $shippingMethods array
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="tabs-content">
    <?php
    $show = array(
        'date_filter'         => true,
        'export_button'       => true,
    );
    $Foxpost_Woo_Parcel_Admin->render('foxpost-woo-parcel-admin-export-form', array(
        'mode'                     => 'export',
        'id'                       => 0,
        'Foxpost_Woo_Parcel_Admin' => $Foxpost_Woo_Parcel_Admin,
        'ajaxurl'                  => $ajaxurl,
        'show'                     => $show,
        'shippingMethods'          => $shippingMethods,
    ));
    ?>
</div>