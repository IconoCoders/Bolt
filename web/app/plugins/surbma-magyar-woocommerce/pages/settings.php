<?php

add_action( 'admin_init', function() {
	register_setting(
		'surbma_hc_options',
		'surbma_hc_fields',
		'surbma_hc_fields_validate'
	);
} );

global $returntoshopcartposition_options;
global $returntoshopcheckoutposition_options;

$returntoshopcartposition_options = array(
	'beforecarttable' => array(
		'value' => 'beforecarttable',
		'label' => 'Termék táblázat előtt (üzenettel)'
	),
	'aftercarttable' => array(
		'value' => 'aftercarttable',
		'label' => 'Termék táblázat után (üzenettel)'
	),
	'cartactions' => array(
		'value' => 'cartactions',
		'label' => 'Kosár frissítése gomb mellett (üzenet nélkül)'
	),
	'proceedtocheckout' => array(
		'value' => 'proceedtocheckout',
		'label' => 'Tovább a pénztárhoz gomb után (üzenet nélkül)'
	)
);

$returntoshopcheckoutposition_options = array(
	'nocheckout' => array(
		'value' => 'nocheckout',
		'label' => 'A Pénztár oldalon ne jelenjen meg'
	),
	'beforecheckoutform' => array(
		'value' => 'beforecheckoutform',
		'label' => 'Pénztár űrlap előtt (üzenettel)'
	),
	'aftercheckoutform' => array(
		'value' => 'aftercheckoutform',
		'label' => 'Pénztár űrlap után (üzenettel)'
	)
);

function surbma_hc_settings_page() {
	// add_filter( 'cps_admin_header_title', 'surbma_hc_admin_header_title' );
	add_filter( 'cps_admin_header_facebook_url', 'surbma_hc_admin_header_facebook_url' );
	add_filter( 'cps_admin_header_facebook_title', 'surbma_hc_admin_header_facebook_title' );
	add_filter( 'cps_admin_header_facebook_button_text', 'surbma_hc_admin_header_facebook_button_text' );
	add_filter( 'cps_admin_header_email', 'surbma_hc_admin_header_email' );
	add_filter( 'cps_admin_header_email_title', 'surbma_hc_admin_header_email_title' );
	add_filter( 'cps_admin_header_website', 'surbma_hc_admin_header_website' );
	add_filter( 'cps_admin_header_website_title', 'surbma_hc_admin_header_website_title' );

	global $returntoshopcartposition_options;
	global $returntoshopcheckoutposition_options;

	$freeNotification = '<p class="uk-text-meta uk-text-center">Ezek a modulok a bővítmény PRO kiegészítőjével érhetők el, amelyet külön kell megvásárolni a bővítményhez.</p>';

	$szamlazzhu_options = get_option( 'woocommerce_wc_szamlazz_settings' );
	$billingo_options = get_option( 'woocommerce_wc_billingo_plus_settings' );

?>
<div class="cps-admin surbma-hc-settings-page">
	<?php cps_admin_header( SURBMA_HC_PLUGIN_FILE ); ?>
	<div class="wrap">
		<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true ) { ?>
			<div class="updated notice is-dismissible"><p><strong><?php _e( 'Settings saved.' ); ?></strong></p></div>
		<?php } ?>

		<div class="uk-grid-small" uk-grid>
			<div class="uk-width-3-4@l">
				<form class="uk-form-horizontal" method="post" action="options.php">
					<?php settings_fields( 'surbma_hc_options' ); ?>
					<?php $options = get_option( 'surbma_hc_fields' ); ?>

					<div class="uk-card uk-card-small uk-card-default uk-card-hover uk-margin-bottom">
						<div class="uk-card-header uk-background-muted">
							<h3 class="uk-card-title">HuCommerce modulok <a class="uk-float-right uk-margin-small-top" uk-icon="icon: more-vertical" uk-toggle="target: #hc-modules"></a></h3>
						</div>
						<div id="hc-modules" class="uk-card-body">

							<div class="uk-margin">
								<div class="uk-form-label">Magyar formátum javítások <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: A keresztnév és vezetéknév sorrendjének a megfordítása a Pénztár oldalon akkor, ha a webáruház magyar nyelvű. Megye mező elrejtése ha a cím Magyarország.; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $huformatfixValue = isset( $options['huformatfix'] ) ? $options['huformatfix'] : 1; ?>
											<input id="surbma_hc_fields[huformatfix]" name="surbma_hc_fields[huformatfix]" type="checkbox" value="1" <?php checked( '1', $huformatfixValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label">Pénztár oldal <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Pénztár oldali mezők és egyéb funkciók módosításai.; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $moduleCheckoutValue = isset( $options['module-checkout'] ) ? $options['module-checkout'] : 0; ?>
											<input id="module-checkout" name="surbma_hc_fields[module-checkout]" type="checkbox" value="1" <?php checked( '1', $moduleCheckoutValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label">Plusz/minusz mennyiségi gombok <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: A mennyiség mezőnél megjelenít egy plusz/minusz gombot, amivel lehet módosítani a mennyiséget.; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $plusminusValue = isset( $options['plusminus'] ) ? $options['plusminus'] : 0; ?>
											<input id="surbma_hc_fields[plusminus]" name="surbma_hc_fields[plusminus]" type="checkbox" value="1" <?php checked( '1', $plusminusValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label">Kosár automatikus frissítése darabszám módosítás után <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: A kosár oldalon, ha megváltozik a termék mennyisége, akkor automatikusan frissíti a kosarat. Nincs szükség a kosár frissítése gomb megnyomására.; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $updatecartValue = isset( $options['updatecart'] ) ? $options['updatecart'] : 0; ?>
											<input id="surbma_hc_fields[updatecart]" name="surbma_hc_fields[updatecart]" type="checkbox" value="1" <?php checked( '1', $updatecartValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label">Vásárlás folytatása gombok <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Egy gombot helyez el a Kosár és/vagy a Pénztár oldalon, amivel folytathatja a látogató a vásárlást. A gomb az üzlet oldalra viszi a látogatót.; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $returntoshopValue = isset( $options['returntoshop'] ) ? $options['returntoshop'] : 0; ?>
											<input id="surbma_hc_fields[returntoshop]" name="surbma_hc_fields[returntoshop]" type="checkbox" value="1" <?php checked( '1', $returntoshopValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label">Belépés és regisztráció utáni átirányítás <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Beállítható, hogy a látogatók a belépés és regisztráció után a meghatározott oldalra legyenek automatikusan átirányítva.; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $loginregistrationredirectValue = isset( $options['loginregistrationredirect'] ) ? $options['loginregistrationredirect'] : 0; ?>
											<input id="surbma_hc_fields[loginregistrationredirect]" name="surbma_hc_fields[loginregistrationredirect]" type="checkbox" value="1" <?php checked( '1', $loginregistrationredirectValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label">Ingyenes szállítás értesítés <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: A Kosár oldalon kijelzi, hogy mennyi vásárlási összeg hiányzik még az ingyenes szállításhoz.; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $freeshippingnoticeValue = isset( $options['freeshippingnotice'] ) ? $options['freeshippingnotice'] : 0; ?>
											<input id="surbma_hc_fields[freeshippingnotice]" name="surbma_hc_fields[freeshippingnotice]" type="checkbox" value="1" <?php checked( '1', $freeshippingnoticeValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label">Adószám megjelenítése <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: A vásárlásnál nem csak a Cégnevet, hanem Adószámot is meg lehet adni.; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $taxnumberValue = isset( $options['taxnumber'] ) ? $options['taxnumber'] : 0; ?>
											<input id="surbma_hc_fields[taxnumber]" name="surbma_hc_fields[taxnumber]" type="checkbox" value="1" <?php checked( '1', $taxnumberValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
									<?php if( $szamlazzhu_options['vat_number_form'] == 'yes' ) { ?>
										<p class="uk-text-meta uk-text-right uk-text-warning"><span uk-icon="warning"></span> Az adószám mezőt a Szamlazz.hu bővítmény már megjeleníti a pénztár oldalon.</p>
									<?php } ?>
									<?php if( $billingo_options['vat_number_form'] == 'yes' ) { ?>
										<p class="uk-text-meta uk-text-right uk-text-warning"><span uk-icon="warning"></span> Az adószám mezőt a Billingo bővítmény már megjeleníti a pénztár oldalon.</p>
									<?php } ?>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label">Jogi megfelelés (Fogyasztóvédelem, GDPR, ePrivacy, stb.) <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Vásárlásnál meg kell erősítenie a vásárlónak, hogy elfogadja-e az Általános Szerződési Feltételeket és/vagy az Adatvédelmi tájékoztatót.; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $legalcheckoutValue = isset( $options['legalcheckout'] ) ? $options['legalcheckout'] : 0; ?>
											<input id="surbma_hc_fields[legalcheckout]" name="surbma_hc_fields[legalcheckout]" type="checkbox" value="1" <?php checked( '1', $legalcheckoutValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label">Város automatikus kitöltése az irányítószám alapján <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: A Pénztár oldalon az irányítószám mező kitöltése után automatikusan megjeleníti a várost.; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $autofillcityValue = isset( $options['autofillcity'] ) ? $options['autofillcity'] : 0; ?>
											<input id="surbma_hc_fields[autofillcity]" name="surbma_hc_fields[autofillcity]" type="checkbox" value="1" <?php checked( '1', $autofillcityValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<div class="uk-form-label">Fordítási hiányosságok javítása <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Ideiglenes fordítási hiányosságok javítása, amíg a hivatalos fordításban esetleg nem jelenik meg vagy nem frissíti a rendszer.; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $translationsValue = isset( $options['translations'] ) ? $options['translations'] : 1; ?>
											<input id="surbma_hc_fields[translations]" name="surbma_hc_fields[translations]" type="checkbox" value="1" <?php checked( '1', $translationsValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<?php /*
							<h4 class="uk-heading-divider">PRO modulok</h4>

							<div class="uk-margin<?php if ( SURBMA_HC_PLUGIN_VERSION == 'free' || SURBMA_HC_PLUGIN_LICENSE != 'valid' ) echo ' disabled'; ?>">
								<div class="uk-form-label">Árukereső és Árgép integráció</div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $aaintegrationValue = isset( $options['aaintegration'] ) ? $options['aaintegration'] : 1; ?>
											<input id="surbma_hc_fields[aaintegration]" name="surbma_hc_fields[aaintegration]" type="checkbox" value="1" <?php checked( '1', $aaintegrationValue ); ?><?php if ( SURBMA_HC_PLUGIN_VERSION == 'free' || SURBMA_HC_PLUGIN_LICENSE != 'valid' ) echo ' disabled'; ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>
							<?php if ( SURBMA_HC_PLUGIN_VERSION == 'free' || SURBMA_HC_PLUGIN_LICENSE != 'valid' ) { ?>
								<?php echo $freeNotification; ?>
							<?php } ?>
							*/ ?>
						</div>
						<div class="uk-card-footer uk-background-muted">
							<p><input type="submit" class="uk-button uk-button-primary" value="<?php _e( 'Save Changes' ); ?>" /></p>
						</div>
					</div>

					<div class="uk-card uk-card-small uk-card-default uk-card-hover uk-margin-bottom">
						<div class="uk-card-header uk-background-muted">
							<h3 class="uk-card-title">További beállítások a modulokhoz <a class="uk-float-right uk-margin-small-top" uk-icon="icon: more-vertical" uk-toggle="target: #modulesettings"></a></h3>
						</div>
						<div id="modulesettings" class="uk-card-body">

							<h4 class="uk-heading-divider">Magyar formátum javítások</h4>

							<div class="uk-margin">
								<div class="uk-form-label">Megye mező elrejtése magyar cím esetén <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Mert ezt nálunk nem szokás használni, így csak plusz felesleges lépés.; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $nocountyValue = isset( $options['nocounty'] ) ? $options['nocounty'] : 1; ?>
											<input id="surbma_hc_fields[nocounty]" name="surbma_hc_fields[nocounty]" type="checkbox" value="1" <?php checked( '1', $nocountyValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<h4 class="uk-heading-divider">Pénztár oldal</h4>

							<div class="uk-margin">
								<div class="uk-form-label">Céges számlázási adatok feltételes megjelenítése</div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $billingcompanycheckValue = isset( $options['billingcompanycheck'] ) ? $options['billingcompanycheck'] : 0; ?>
											<input id="billingcompanycheck" name="surbma_hc_fields[billingcompanycheck]" type="checkbox" value="1" <?php checked( '1', $billingcompanycheckValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<div class="uk-margin">
								<div class="uk-form-label">Ország mező elrejtése</div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $nocountryValue = isset( $options['nocountry'] ) ? $options['nocountry'] : 0; ?>
											<input id="nocountry" name="surbma_hc_fields[nocountry]" type="checkbox" value="1" <?php checked( '1', $nocountryValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<div class="uk-margin">
								<div class="uk-form-label">Rendelés jegyzetek mező elrejtése</div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $noordercommentsValue = isset( $options['noordercomments'] ) ? $options['noordercomments'] : 0; ?>
											<input id="noordercomments" name="surbma_hc_fields[noordercomments]" type="checkbox" value="1" <?php checked( '1', $noordercommentsValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<div class="uk-margin">
								<div class="uk-form-label">Cégnév és Adószám mezők egymás mellé rendezése</div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $companytaxnumberpairValue = isset( $options['companytaxnumberpair'] ) ? $options['companytaxnumberpair'] : 0; ?>
											<input id="companytaxnumberpair" name="surbma_hc_fields[companytaxnumberpair]" type="checkbox" value="1" <?php checked( '1', $companytaxnumberpairValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<div class="uk-margin">
								<div class="uk-form-label">Irányítószám és Város mezők egymás mellé rendezése</div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $postcodecitypairValue = isset( $options['postcodecitypair'] ) ? $options['postcodecitypair'] : 0; ?>
											<input id="postcodecitypair" name="surbma_hc_fields[postcodecitypair]" type="checkbox" value="1" <?php checked( '1', $postcodecitypairValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<div class="uk-margin">
								<div class="uk-form-label">Telefonszám és Email cím mezők egymás mellé rendezése</div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $phoneemailpairValue = isset( $options['phoneemailpair'] ) ? $options['phoneemailpair'] : 0; ?>
											<input id="phoneemailpair" name="surbma_hc_fields[phoneemailpair]" type="checkbox" value="1" <?php checked( '1', $phoneemailpairValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<h4 class="uk-heading-divider">Vásárlás folytatása gombok</h4>

							<div class="uk-margin">
								<div class="uk-form-label">Gomb pozíciója a Kosár oldalon</div>
								<div class="uk-form-controls">
									<select class="uk-select" name="surbma_hc_fields[returntoshopcartposition]">
										<?php
											$returntoshopcartpositionValue = isset( $options['returntoshopcartposition'] ) ? $options['returntoshopcartposition'] : 'cartactions';
											$selected = $returntoshopcartpositionValue;
											$p = '';
											$r = '';

											foreach ( $returntoshopcartposition_options as $option ) {
												$label = $option['label'];
												if ( $selected == $option['value'] ) // Make default first in list
													$p = "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
												else
													$r .= "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
											}
											echo $p . $r;
										?>
									</select>
								</div>
							</div>

							<div class="uk-margin">
								<div class="uk-form-label">Gomb pozíciója a Pénztár oldalon</div>
								<div class="uk-form-controls">
									<select class="uk-select" name="surbma_hc_fields[returntoshopcheckoutposition]">
										<?php
											$returntoshopcheckoutpositionValue = isset( $options['returntoshopcheckoutposition'] ) ? $options['returntoshopcheckoutposition'] : 'nocheckout';
											$selected = $returntoshopcheckoutpositionValue;
											$p = '';
											$r = '';

											foreach ( $returntoshopcheckoutposition_options as $option ) {
												$label = $option['label'];
												if ( $selected == $option['value'] ) // Make default first in list
													$p = "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
												else
													$r .= "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
											}
											echo $p . $r;
										?>
									</select>
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[returntoshopmessage]">Üzenet szövege</label>
								<div class="uk-form-controls">
									<?php $returntoshopmessageValue = isset( $options['returntoshopmessage'] ) ? $options['returntoshopmessage'] : 'Szeretnél még körbenézni a webáruházunkban?'; ?>
									<input id="surbma_hc_fields[returntoshopmessage]" class="uk-input" type="text" name="surbma_hc_fields[returntoshopmessage]" value="<?php echo stripslashes( $returntoshopmessageValue ); ?>" />
								</div>
							</div>

							<h4 class="uk-heading-divider">Belépés és regisztráció utáni átirányítás</h4>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[loginredirecturl]">Belépés utáni átirányítási URL <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Abszolút URL megadása. Ha üres a mező, akkor az alapértelmezett WooCommerce átirányítás működik.; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $loginredirecturlValue = isset( $options['loginredirecturl'] ) ? $options['loginredirecturl'] : wc_get_page_permalink( 'shop' ); ?>
									<input id="surbma_hc_fields[loginredirecturl]" class="uk-input" type="text" name="surbma_hc_fields[loginredirecturl]" value="<?php echo stripslashes( $loginredirecturlValue ); ?>" />
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[registrationredirecturl]">Regisztráció utáni átirányítási URL <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Abszolút URL megadása. Ha üres a mező, akkor az alapértelmezett WooCommerce átirányítás működik.; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $registrationredirecturlValue = isset( $options['registrationredirecturl'] ) ? $options['registrationredirecturl'] : wc_get_page_permalink( 'shop' ); ?>
									<input id="surbma_hc_fields[registrationredirecturl]" class="uk-input" type="text" name="surbma_hc_fields[registrationredirecturl]" value="<?php echo stripslashes( $registrationredirecturlValue ); ?>" />
								</div>
							</div>

							<h4 class="uk-heading-divider">Ingyenes szállítás értesítés</h4>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[freeshippingnoticemessage]">Üzenet szövege</label>
								<div class="uk-form-controls">
									<?php $freeshippingnoticemessageValue = isset( $options['freeshippingnoticemessage'] ) && ( $options['freeshippingnoticemessage'] != '' ) ? $options['freeshippingnoticemessage'] : 'Az Ingyenes szállításhoz szükséges további vásárlás értéke'; ?>
									<input id="surbma_hc_fields[freeshippingnoticemessage]" class="uk-input" type="text" name="surbma_hc_fields[freeshippingnoticemessage]" value="<?php echo stripslashes( $freeshippingnoticemessageValue ); ?>" />
								</div>
							</div>

							<h4 class="uk-heading-divider">Jogi megfelelés (Fogyasztóvédelem, GDPR, ePrivacy, stb.)</h4>

							<div class="uk-margin">
								<div class="uk-form-label">IP cím mentése regisztrációkor <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Ha szeretnéd elmenteni a vásárló IP címét, amikor regisztrál a weboldalon, kapcsold be ezt az opciót!; pos: right"></span></div>
								<div class="uk-form-controls">
									<p class="switch-wrap">
										<label class="switch">
											<?php $regipValue = isset( $options['regip'] ) ? $options['regip'] : 0; ?>
											<input id="surbma_hc_fields[regip]" name="surbma_hc_fields[regip]" type="checkbox" value="1" <?php checked( '1', $regipValue ); ?> />
											<span class="slider round"></span>
										</label>
									</p>
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[regacceptpp]">Adatvédelmi tájékoztató elfogadásának a szövege a Regisztrációnál (akár linkkel) <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Ha üres a mező, akkor ez a checkbox nem jelenik meg.; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $regacceptppValue = isset( $options['regacceptpp'] ) ? $options['regacceptpp'] : esc_attr( 'Elolvastam és elfogadom az <a href="/adatkezeles/" target="_blank">Adatkezelési tájékoztatót</a>' ); ?>
									<textarea id="surbma_hc_fields[regacceptpp]" class="uk-textarea" cols="50" rows="5" name="surbma_hc_fields[regacceptpp]"><?php echo stripslashes( $regacceptppValue ); ?></textarea>
									<p class="uk-text-meta">HTML használata engedélyezett</p>
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[legalcheckouttitle]">Szakasz elnevezése a Pénztár oldalon <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Ez a cím jelenik meg a checbox-ok fölött. Ha üres, akkor nem jelenik meg.; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $legalcheckouttitleValue = isset( $options['legalcheckouttitle'] ) ? $options['legalcheckouttitle'] : 'Vásárlással kapcsolatos megerősítések'; ?>
									<input id="surbma_hc_fields[legalcheckouttitle]" class="uk-input" type="text" name="surbma_hc_fields[legalcheckouttitle]" value="<?php echo stripslashes( $legalcheckouttitleValue ); ?>" />
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[accepttos]">Általános Szerződési Feltételek elfogadásának a szövege a Pénztár oldalon (akár linkkel) <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Ha üres a mező, akkor ez a checkbox nem jelenik meg.; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $accepttosValue = isset( $options['accepttos'] ) ? $options['accepttos'] : esc_attr( 'Elolvastam és elfogadom az <a href="/aszf/" target="_blank">Általános Szerződési Feltételeket</a>' ); ?>
									<textarea id="surbma_hc_fields[accepttos]" class="uk-textarea" cols="50" rows="5" name="surbma_hc_fields[accepttos]"><?php echo stripslashes( $accepttosValue ); ?></textarea>
									<p class="uk-text-meta">HTML használata engedélyezett</p>
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[acceptpp]">Adatvédelmi tájékoztató elfogadásának a szövege a Pénztár oldalon (akár linkkel) <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: Ha üres a mező, akkor ez a checkbox nem jelenik meg.; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $acceptppValue = isset( $options['acceptpp'] ) ? $options['acceptpp'] : esc_attr( 'Elolvastam és elfogadom az <a href="/adatkezeles/" target="_blank">Adatkezelési tájékoztatót</a>' ); ?>
									<textarea id="surbma_hc_fields[acceptpp]" class="uk-textarea" cols="50" rows="5" name="surbma_hc_fields[acceptpp]"><?php echo stripslashes( $acceptppValue ); ?></textarea>
									<p class="uk-text-meta">HTML használata engedélyezett</p>
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[beforeorderbuttonmessage]">Megrendelés gomb fölötti szöveg <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: A megadott szöveg a Pénztár oldalon a Megrendelés gomb fölött jelenik meg. Ha üres, akkor nem jelenik meg semmi.; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $beforeorderbuttonmessageValue = isset( $options['beforeorderbuttonmessage'] ) ? $options['beforeorderbuttonmessage'] : null; ?>
									<textarea id="surbma_hc_fields[beforeorderbuttonmessage]" class="uk-textarea" cols="50" rows="5" name="surbma_hc_fields[beforeorderbuttonmessage]"><?php echo stripslashes( $beforeorderbuttonmessageValue ); ?></textarea>
									<p class="uk-text-meta">HTML használata engedélyezett</p>
								</div>
							</div>

							<div class="uk-margin">
								<label class="uk-form-label" for="surbma_hc_fields[afterorderbuttonmessage]">Megrendelés gomb alatti szöveg <span uk-icon="icon: info; ratio: 1" uk-tooltip="title: A megadott szöveg a Pénztár oldalon a Megrendelés gomb alatt jelenik meg. Ha üres, akkor nem jelenik meg semmi.; pos: right"></span></label>
								<div class="uk-form-controls">
									<?php $afterorderbuttonmessageValue = isset( $options['afterorderbuttonmessage'] ) ? $options['afterorderbuttonmessage'] : null; ?>
									<textarea id="surbma_hc_fields[afterorderbuttonmessage]" class="uk-textarea" cols="50" rows="5" name="surbma_hc_fields[afterorderbuttonmessage]"><?php echo stripslashes( $afterorderbuttonmessageValue ); ?></textarea>
									<p class="uk-text-meta">HTML használata engedélyezett</p>
								</div>
							</div>

							<hr>

							<div class="uk-margin">
								<label class="uk-form-label">Megengedett HTML tagok</label>
								<div class="uk-form-controls">
									<pre><?php echo allowed_tags(); ?></pre>
								</div>
							</div>

						</div>
						<div class="uk-card-footer uk-background-muted">
							<p><input type="submit" class="uk-button uk-button-primary" value="<?php _e( 'Save Changes' ); ?>" /></p>
						</div>
					</div>

				</form>
			</div>
			<div class="uk-width-1-4@l">
				<?php surbma_hc_admin_sidebar(); ?>
			</div>
		</div>
		<div class="uk-margin-bottom" id="bottom"></div>
	</div>
	<?php cps_admin_footer( SURBMA_HC_PLUGIN_FILE ); ?>
</div>
<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function surbma_hc_fields_validate( $input ) {
	global $returntoshopcartposition_options;
	global $returntoshopcheckoutposition_options;

	$options = get_option( 'surbma_hc_fields' );

	// Checkbox validation.
	$input['huformatfix'] = isset( $input['huformatfix'] ) && $input['huformatfix'] == 1 ? 1 : 0;
	$input['module-checkout'] = isset( $input['module-checkout'] ) && $input['module-checkout'] == 1 ? 1 : 0;
	$input['plusminus'] = isset( $input['plusminus'] ) && $input['plusminus'] == 1 ? 1 : 0;
	$input['updatecart'] = isset( $input['updatecart'] ) && $input['updatecart'] == 1 ? 1 : 0;
	$input['translations'] = isset( $input['translations'] ) && $input['translations'] == 1 ? 1 : 0;
	$input['returntoshop'] = isset( $input['returntoshop'] ) && $input['returntoshop'] == 1 ? 1 : 0;
	$input['loginregistrationredirect'] = isset( $input['loginregistrationredirect'] ) && $input['loginregistrationredirect'] == 1 ? 1 : 0;
	$input['freeshippingnotice'] = isset( $input['freeshippingnotice'] ) && $input['freeshippingnotice'] == 1 ? 1 : 0;
	$input['taxnumber'] = isset( $input['taxnumber'] ) && $input['taxnumber'] == 1 ? 1 : 0;
	$input['legalcheckout'] = isset( $input['legalcheckout'] ) && $input['legalcheckout'] == 1 ? 1 : 0;
	$input['autofillcity'] = isset( $input['autofillcity'] ) && $input['autofillcity'] == 1 ? 1 : 0;
	$input['nocounty'] = isset( $input['nocounty'] ) && $input['nocounty'] == 1 ? 1 : 0;
	$input['nocountry'] = isset( $input['nocountry'] ) && $input['nocountry'] == 1 ? 1 : 0;
	$input['noordercomments'] = isset( $input['noordercomments'] ) && $input['noordercomments'] == 1 ? 1 : 0;
	$input['billingcompanycheck'] = isset( $input['billingcompanycheck'] ) && $input['billingcompanycheck'] == 1 ? 1 : 0;
	$input['companytaxnumberpair'] = isset( $input['companytaxnumberpair'] ) && $input['companytaxnumberpair'] == 1 ? 1 : 0;
	$input['postcodecitypair'] = isset( $input['postcodecitypair'] ) && $input['postcodecitypair'] == 1 ? 1 : 0;
	$input['phoneemailpair'] = isset( $input['phoneemailpair'] ) && $input['phoneemailpair'] == 1 ? 1 : 0;
	$input['regip'] = isset( $input['regip'] ) && $input['regip'] == 1 ? 1 : 0;

	// Our select option must actually be in our array of select options
	if ( !array_key_exists( $input['returntoshopcartposition'], $returntoshopcartposition_options ) )
		$input['returntoshopcartposition'] = 'cartactions';
	if ( !array_key_exists( $input['returntoshopcheckoutposition'], $returntoshopcheckoutposition_options ) )
		$input['returntoshopcheckoutposition'] = 'nocheckout';

	// Say our text option must be safe text with no HTML tags
	$input['returntoshopmessage'] = wp_filter_nohtml_kses( $input['returntoshopmessage'] );
	$input['loginredirecturl'] = wp_filter_nohtml_kses( $input['loginredirecturl'] );
	$input['registrationredirecturl'] = wp_filter_nohtml_kses( $input['registrationredirecturl'] );
	$input['freeshippingnoticemessage'] = wp_filter_nohtml_kses( $input['freeshippingnoticemessage'] );
	$input['legalcheckouttitle'] = wp_filter_nohtml_kses( $input['legalcheckouttitle'] );

	// Say our text/textarea option must be safe text with the allowed tags for posts
	$input['regacceptpp'] = wp_filter_post_kses( $input['regacceptpp'] );
	$input['accepttos'] = wp_filter_post_kses( $input['accepttos'] );
	$input['acceptpp'] = wp_filter_post_kses( $input['acceptpp'] );
	$input['beforeorderbuttonmessage'] = wp_filter_post_kses( $input['beforeorderbuttonmessage'] );
	$input['afterorderbuttonmessage'] = wp_filter_post_kses( $input['afterorderbuttonmessage'] );

	return $input;
}
