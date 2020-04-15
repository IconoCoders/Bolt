<?php

include_once( SURBMA_HC_PLUGIN_DIR . '/pages/settings.php');

// Admin options menu
function surbma_hc_add_menus() {
	global $surbma_hc_settings_page;
	$surbma_hc_settings_page = add_submenu_page(
		'cps-plugins-menu',
		'HuCommerce',
		'HuCommerce',
		'manage_options',
		'surbma-hucommerce-menu',
		'surbma_hc_settings_page'
	);
}
add_action( 'admin_menu', 'surbma_hc_add_menus', 999 );

// Custom styles and scripts for admin pages
function surbma_hc_init( $hook ) {
	wp_register_style( 'surbma-hc-admin', SURBMA_HC_PLUGIN_URL . '/assets/css/admin.css', false, '18.4' );
	global $surbma_hc_settings_page;
	if ( $hook == $surbma_hc_settings_page ) {
		add_action( 'admin_enqueue_scripts', 'cps_admin_scripts', 9999 );
        wp_enqueue_style( 'surbma-hc-admin' );
	}
}
add_action( 'admin_enqueue_scripts', 'surbma_hc_init' );

function surbma_hc_admin_header_title( $url ) {
	return 'HuCommerce | Magyar WooCommerce kiegészítések <span>by HuCommerce</span>';
}

function surbma_hc_admin_header_facebook_url( $url ) {
	return 'https://www.facebook.com/groups/HuCommerce.hu/';
}

function surbma_hc_admin_header_facebook_title( $fbtitle ) {
	return 'Csatlakozz a HuCommerce Facebook csoportjához, ahol lehet kérdezni, ötletelni. Mindenkit szívesen látunk.';
}

function surbma_hc_admin_header_facebook_button_text( $fbbuttontext ) {
	return 'Csatlakozz a csoporthoz!';
}

function surbma_hc_admin_header_email( $email ) {
	return 'hello@hucommerce.hu';
}

function surbma_hc_admin_header_email_title( $emailtitle ) {
	return 'HuCommerce email ügyfélszolgálat';
}

function surbma_hc_admin_header_website( $website ) {
	return 'https://www.hucommerce.hu';
}

function surbma_hc_admin_header_website_title( $websitetitle ) {
	return 'HuCommerce hivatalos weboldal';
}

function surbma_hc_admin_sidebar() {
	$options = get_option( 'surbma_hc_fields' );
	$home_url = get_option( 'home' );
	$current_user = wp_get_current_user();

	?><div uk-sticky="offset: 42; bottom: #bottom">
	<div class="uk-card uk-card-small uk-card-default uk-card-hover">
		<div class="uk-card-header uk-background-muted">
			<h3 class="uk-card-title">Információk <a class="uk-float-right uk-margin-small-top" uk-icon="icon: more-vertical" uk-toggle="target: #informations"></a></h3>
		</div>
		<div id="informations" class="uk-card-body">
			<p>Iratkozz fel a HuCommerce hírlevélre, amiben a legújabb funkciókról, akciókról és különleges ajánlatainkról írunk.</p>
			<p><a class="uk-button uk-button-danger uk-button-large uk-width-1-1" href="https://hucommerce.us20.list-manage.com/subscribe?u=8e6a039140be449ecebeb5264&id=2f5c70bc50&EMAIL=<?php echo urlencode( $current_user->user_email ); ?>&FNAME=<?php echo urlencode( $current_user->user_firstname ); ?>&LNAME=<?php echo urlencode( $current_user->user_lastname ); ?>&URL=<?php echo urlencode( $home_url ); ?>" target="_blank"><span uk-icon="mail"></span> Hírlevél feliratkozás</a></p>
			<h4 class="uk-heading-divider">Bővítmény linkek</h4>
			<ul class="uk-list">
				<li><a href="https://wordpress.org/support/plugin/surbma-magyar-woocommerce" target="_blank">Hivatalos támogató fórum</a></li>
				<li><a href="https://hu.wordpress.org/plugins/surbma-magyar-woocommerce/#reviews" target="_blank">Olvasd el az értékeléseket (5/5 csillag)</a></li>
			</ul>
			<hr>
			<p>
				<strong>Tetszik a bővítmény? Kérlek értékeld 5 csillaggal:</strong>
				 <a href="https://wordpress.org/support/plugin/surbma-magyar-woocommerce/reviews/#new-post" target="_blank">Új értékelés létrehozása</a>
			</p>
			<h4 class="uk-heading-divider">Tervezett funkciók</h4>
			<ul class="uk-list">
				<li><span uk-icon="icon: check; ratio: 0.8"></span> Webáruházak kötelező jogi megfelelésének a technikai biztosítása.</li>
				<li><span uk-icon="icon: check; ratio: 0.8"></span> Globális adatok, amiket shortcode-dal lehet bárhol megjeleníteni.</li>
				<li><span uk-icon="icon: check; ratio: 0.8"></span> Köszönő oldal egyedi módosítási lehetősége.</li>
			</ul>
			<?php /*
			<h4 class="uk-heading-divider">Szerezd meg a PRO verziót</h4>
			<p>Aktiváld a HuCommerce bővítmény összes lehetőségét! A PRO verzió megvásárlásával további fantasztikus funkciókat és integrációkat kapsz.</p>
			<div class="uk-alert-success" style="display: none;" uk-alert>
				<?php _e( '<p>Use this special <strong>BEFOREGDPR</strong> coupon to get 50% OFF your first purchase, which is available till <strong>May 26, 2018</strong>. Hurry, GDPR is coming!</p>', 'surbma-magyar-woocommerce' ); ?>
			</div>
			<p><a class="uk-button uk-button-default uk-width-1-1" href="#">Vedd meg a PRO verziót!</a></p>
			<div class="uk-alert-primary" style="display: none;" uk-alert>
				<a class="uk-alert-close" uk-close></a>
				<h3><?php _e( 'Affiliate Program', 'surbma-magyar-woocommerce' ); ?></h3>
				<p><?php _e( 'Do you like this plugin? Let\'s make some money by referring new customers and get 20% commission, for the lifetime of the new customers! Good deal, hah?', 'surbma-magyar-woocommerce' ); ?></p>
				<p><a class="uk-button uk-button-primary uk-width-1-1" href="<?php echo esc_url( get_admin_url() ); ?>admin.php?page=surbma-gpga-menu-affiliation"><?php _e( 'Be an Affiliate!', 'surbma-magyar-woocommerce' ); ?></a></p>
			</div>
			*/ ?>
		</div>
		<div class="uk-card-footer uk-background-muted">
			<p class="uk-text-right"><?php _e( 'License: GPLv2', 'surbma-magyar-woocommerce' ); ?></p>
		</div>
	</div>
</div>
<?php
}

/*
// Admin notice classes:
// notice-success
// notice-success notice-alt
// notice-info
// notice-warning
// notice-error
// Without a class, there is no colored left border.
*/

// Welcome notice
function surbma_hc_admin_notice__welcome() {
	if ( ! PAnD::is_admin_notice_active( 'surbma-hc-notice-welcome-forever' ) ) return;

	$home_url = get_option( 'home' );
	$current_user = wp_get_current_user();
	?>
	<div data-dismissible="surbma-hc-notice-welcome-forever" class="notice notice-info is-dismissible">
		<div style="padding: 20px;">
			<img src="<?php echo SURBMA_HC_PLUGIN_URL; ?>/assets/images/hucommerce-logo.png" alt="HuCommerce">
			<p><strong>Köszönjük, hogy telepítetted a HuCommerce bővítményt!</strong></p>
			<p>Első lépésként aktiváld a szükséges modulokat és nézd meg az egyes modulok egyedi beállításait!
			<br>A HuCommerce beállításait a <a href="<?php admin_url(); ?>admin.php?page=surbma-hucommerce-menu">CPS Plugins -> HuCommerce</a> menüpont alatt találod.</p>
			<p><strong>FIGYELEM!</strong> Ez az értesítés a lezárást követően nem jelenik meg újra. Kérünk, hogy csatlakozz a Facebook csoportunkhoz és iratkozz fel a HuCommerce hírlevelünkre!</p>
			<p><a href="https://hucommerce.us20.list-manage.com/subscribe?u=8e6a039140be449ecebeb5264&id=2f5c70bc50&EMAIL=<?php echo urlencode( $current_user->user_email ); ?>&FNAME=<?php echo urlencode( $current_user->user_firstname ); ?>&LNAME=<?php echo urlencode( $current_user->user_lastname ); ?>&URL=<?php echo urlencode( $home_url ); ?>" target="_blank" class="button button-secondary"><span class="dashicons dashicons-email" style="position: relative;top: 3px;left: -3px;"></span> Hírlevél feliratkozás</a> <a href="https://www.facebook.com/groups/HuCommerce.hu/" target="_blank" class="button button-primary"><span class="dashicons dashicons-facebook-alt" style="position: relative;top: 3px;left: -3px;"></span> Facebook csoport</a></p>
		</div>
	</div>
	<?php
}
add_action( 'admin_notices', 'surbma_hc_admin_notice__welcome' );
