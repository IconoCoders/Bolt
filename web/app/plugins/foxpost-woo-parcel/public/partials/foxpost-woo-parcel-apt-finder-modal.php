<?php
/**
 *
 */

if (!defined('ABSPATH')) {
    die;
}
use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel;
?>
<div class="modal fade" id="foxpost_woo_parcel_dialog" title="<?php echo Foxpost_Woo_Parcel::__('Foxpost map based APT finder') ?>">
    <div class="modal-dialog mainmodal">
        <div class="modal-content">
            <div class="modal-body">
                <iframe id="foxpost_woo_parcel_dialog_iframe" src="" width="970px"
                        height="700"></iframe>
            </div>
        </div>
    </div>
</div>