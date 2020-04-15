<?php

/*
Plugin Name: HuCommerce | Magyar WooCommerce kiegészítések
Plugin URI: https://www.hucommerce.hu/
Description: Hasznos javítások a magyar nyelvű WooCommerce webáruházakhoz.

Version: 23.0

Author: HuCommerce
Author URI: https://www.hucommerce.hu/

License: GPLv2

WC requires at least: 4.0
WC tested up to: 4.0

Text Domain: surbma-magyar-woocommerce
Domain Path: /languages/
*/

// Prevent direct access
defined( 'ABSPATH' ) || exit;

define( 'SURBMA_HC_PLUGIN_VERSION_NUMBER', '23.0' );
define( 'SURBMA_HC_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'SURBMA_HC_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'SURBMA_HC_PLUGIN_FILE', __FILE__ );

// Freemius SDK wrap to prevent conflicts with premium version.
if ( function_exists( 'hucommerce_fs' ) ) {

	// Check if premium version is used.
	if( hucommerce_fs()->is__premium_only() ) {
		define( 'SURBMA_HC_PLUGIN_VERSION', 'premium' );
	}

	// Set license.
	if( hucommerce_fs()->can_use_premium_code() ) {
		define( 'SURBMA_HC_PLUGIN_LICENSE', 'valid' );
	} elseif( defined( 'SURBMA_HC_PLUGIN_VERSION' ) && SURBMA_HC_PLUGIN_VERSION == 'premium' ) {
		define( 'SURBMA_HC_PLUGIN_LICENSE', 'expired' );
	}

}

// Set plugin veryion to free if premium version is not active.
if( !defined( 'SURBMA_HC_PLUGIN_VERSION' ) ) {
	define( 'SURBMA_HC_PLUGIN_VERSION', 'free' );
}

// Set plugin license to free if premium version is not active.
if( !defined( 'SURBMA_HC_PLUGIN_LICENSE' ) ) {
	define( 'SURBMA_HC_PLUGIN_LICENSE', 'free' );
}

// Check WooCommerce.
function surbma_hc_check_woocommerce() {
	if ( class_exists( 'WooCommerce' ) ) {
		// Start HuCommerce.
		require_once SURBMA_HC_PLUGIN_DIR . '/lib/start.php';
	} else {
		// Notify user, that WooCommerce is not active.
		add_action( 'admin_notices', 'surbma_hc_admin_notice__no_woocommerce' );
	}
}
add_action( 'plugins_loaded', 'surbma_hc_check_woocommerce' );

// Localization
function surbma_hc_localization() {
	load_plugin_textdomain( 'surbma-magyar-woocommerce', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'surbma_hc_localization' );

function surbma_hc_admin_notice__no_woocommerce() {
	?>
	<div class="notice notice-error">
		<div style="padding: 20px;">
			<img src="<?php echo SURBMA_HC_PLUGIN_URL; ?>/images/hucommerce-logo.png" alt="HuCommerce">
			<p>A HuCommerce bővítmény használatához mindenképpen aktiválnod kell először a WooCommerce bővítményt.
			<br>Ha nem szeretnéd használni a WooCommerce bővítményt, akkor kapcsold ki a HuCommerce bővítményt is!</p>
			<p>A WooCommerce aktiválásához vagy a HuCommerce kikapcsolásához kattints a <a href="<?php admin_url(); ?>plugins.php">Bővítmények</a> menüpontra.</p>
		</div>
	</div>
	<?php
}
