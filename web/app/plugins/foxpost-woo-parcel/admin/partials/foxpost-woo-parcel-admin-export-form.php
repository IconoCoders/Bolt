<?php
/**
 * @var $ajaxurl string
 * @var $active_tab string
 * @var $shippingMethods array
 */

use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel;

if (!defined('ABSPATH')) {
    exit;
}
?>

<form method="post" id="foxpost_woo_parcel_export_form">
    <?php if ($show['date_filter']) { ?>

        <div id="my-date-filter" class="export-block">
            <div class="block-header">
                <label for="from_date"><?php echo Foxpost_Woo_Parcel::__('Date range') ?>
                    :</label>
            </div>
            <div>
                <input type=text class='date' name="settings[from_date]"
                       id="from_date"
                       value="">
                <?php echo Foxpost_Woo_Parcel::__('to') ?>
                <input type=text class='date' name="settings[to_date]"
                       id="to_date" value="">
            </div>
        </div>
    <?php } ?>

        <div id="my-shipping-method-filter" class="export-block">
            <div class="block-header">
                <label for="shipping_method_select"><?php echo Foxpost_Woo_Parcel::__('Shipping methods') ?>
                    :</label>
            </div>
            <select multiple class="shipping_method_select"
                    id="shipping_method_select"
                    name="settings[shipping_methods][]">
                <option value=""><?php echo Foxpost_Woo_Parcel::__('None') ?></option>
                <?php foreach ($shippingMethods as $value => $shipping_method) {
                    ?>
                    <option value="<?php echo esc_html($value) ?>"><?php echo esc_html($shipping_method) ?></option>
                    <?php
                } ?>
            </select>
        </div>

        <div id="my-export-file" class="export-block">
            <div class="block-header">
                <label for="export_filename"><?php echo Foxpost_Woo_Parcel::__('Export filename') ?>:</label>
            </div>
            <label id="export_filename" class="width-100">
                <input type="text" name="settings[export_filename]"
                       class="width-100"
                       value="<?php echo 'orders-%y-%m-%d-%h-%i-%s.xls' ?>">
            </label>
        </div>

        <div id="my-export-file" class="export-block">
            <div class="block-header">
                <label for="statuses"><?php echo Foxpost_Woo_Parcel::__('Order statuses') ?>:</label>
            </div>
            <select id="statuses" name="settings[statuses][]"
                    multiple="multiple">
                <option value=""><?php echo Foxpost_Woo_Parcel::__('None') ?></option>
                <?php foreach (apply_filters('foxpost_woo_parcel_settings_order_statuses', wc_get_order_statuses()) as $i => $status) { ?>
                    <option value="<?php echo esc_html($i) ?>"><?php echo esc_html($status) ?></option>
                <?php } ?>
            </select>
        </div>

        <input type="submit" id='export-btn' class="button-secondary"
               value="<?php Foxpost_Woo_Parcel::_e('Export') ?>"/>

</form>

<iframe id="export_download_frame" width="0" height="0"
        style="display: none;"></iframe>

<script>
    jQuery(document).ready(function ($) {

        $('.date').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        var $form = $('#foxpost_woo_parcel_export_form');

        $('#export-btn').click(function () {

            var $from_data = $('#from_date');
            var $to_date = $('#to_date');

            if (($from_data.val()) && ($to_date.val())) {
                var d1 = new Date($from_data.val());
                var d2 = new Date($to_date.val());
                if (d1.getTime() > d2.getTime()) {
                    alert(foxpost_woo_parcel_backend_messages.wrong_date_range);
                    return false;
                }
            }

            $('#export_download_frame').attr("src", ajaxurl + (ajaxurl.indexOf('?') === -1 ? '?' : '&') + 'action=foxpost_woo_parcel&method=export_download&' + $form.serialize());

            return false;
        });
    });
</script>