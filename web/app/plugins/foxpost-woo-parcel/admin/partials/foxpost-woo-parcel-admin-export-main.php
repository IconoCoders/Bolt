<?php
/**
 * @var $ajaxurl string
 * @var $active_tab string
 */

use Foxpost_Woo_Parcel\Includes\Foxpost_Woo_Parcel;

if (!defined('ABSPATH')) {
    exit;
}
$active_tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : 'export';
?>
<?php if (isset($_REQUEST['save'])) { ?>
    <div class="update-nag"
         style="color: #008000; border-left: 4px solid green; display: block; width: 70%;"><?php echo Foxpost_Woo_Parcel::__('Settings saved', 'woo-order-export-lite') ?></div>
<?php } ?>
<h2 class="nav-tab-wrapper" id="tabs">
    <a class="nav-tab <?php echo $active_tab === 'export' ? 'nav-tab-active' : '' ?>"
       href="<?php echo admin_url('admin.php?page=foxpost-woo-parcel-wc-order-export&tab=export') ?>"><?php echo Foxpost_Woo_Parcel::__('Export now', 'woo-order-export-lite') ?></a>
</h2>

<script>
    var ajaxurl = "<?php echo $ajaxurl ?>"
</script>