<?php

function surbma_hc_legal_registration_fields() {
	$options = get_option( 'surbma_hc_fields' );
	$regacceptppValue = isset( $options['regacceptpp'] ) ? stripslashes( $options['regacceptpp'] ) : 'Elolvastam és elfogadom az <a href="/adatkezeles/" target="_blank">Adatkezelési tájékoztatót</a>';

	if( $regacceptppValue != '' ) {
		woocommerce_form_field( 'reg_accept_pp', array(
			'type'          => 'checkbox',
			'class'         => array('woocommerce-form-row woocommerce-form-row--wide form-row-wide privacy'),
			'label'         => $regacceptppValue,
			'required'      => true
		) );
	}
}
// A 20-asnál az adatkezelési tájékoztató szöveg fölé kerül a checkbox.
add_action( 'woocommerce_register_form', 'surbma_hc_legal_registration_fields', 21 );

function surbma_hc_legal_registration_fields_validation( $errors, $username, $email ) {
	$options = get_option( 'surbma_hc_fields' );

	if ( !is_checkout() && isset( $options['regacceptpp'] ) && $options['regacceptpp'] != '' && !$_POST['reg_accept_pp'] )
		$errors->add( 'reg_accept_pp_error', '<strong>Adatkezelési Tájékoztató</strong> elfogadása kötelező.' );
	return $errors;
}
add_filter( 'woocommerce_registration_errors', 'surbma_hc_legal_registration_fields_validation', 10, 3 );

// Extra user metas to save after registration.
function surbma_hc_legal_registration_fields_update_user_meta( $user_id ) {
	$options = get_option( 'surbma_hc_fields' );

	if( !empty( $_POST['reg_accept_pp'] ) )
		update_user_meta( $user_id, 'reg_accept_pp', 1 );

	$regipValue = isset( $options['regip'] ) ? $options['regip'] : 0;
	if( $regipValue == 1 ) {
		// Get real visitor IP behind CloudFlare network
		if( isset( $_SERVER["HTTP_CF_CONNECTING_IP"] ) ) {
			$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		$client = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote = $_SERVER['REMOTE_ADDR'];

		if( filter_var( $client, FILTER_VALIDATE_IP ) ) {
			$ip = $client;
		} elseif( filter_var($forward, FILTER_VALIDATE_IP)) {
			$ip = $forward;
		} else {
			$ip = $remote;
		}
		update_user_meta( $user_id, 'reg_ip', $ip );
	}
}
add_action( 'user_register', 'surbma_hc_legal_registration_fields_update_user_meta', 10, 1 );

// Let's show the registration extra user meta values in admin.
function surbma_hc_admin_reg_user_profile_fields( $profileuser ) {
	$regacceptpp = get_the_author_meta( 'reg_accept_pp', $profileuser->ID ) == 1 ? 'Elfogadva' : 'Nincs elfogadva';
	$regdate = date( "r", strtotime( $profileuser->user_registered ) ) != '' ? date( "r", strtotime( $profileuser->user_registered ) ) : 'Nincs dátum eltárolva';
	$regip = get_the_author_meta( 'reg_ip', $profileuser->ID ) != '' ? get_the_author_meta( 'reg_ip', $profileuser->ID ) : 'Nincs IP cím eltárolva';
?>
	<h2>Regisztrációs adatok</h2>
	<table class="form-table">
		<tr>
			<th>
				<label for="reg_accept_pp">Adatkezelési tájékoztató</label>
			</th>
			<td>
				<input type="text" name="reg_accept_pp" id="reg_accept_pp" value="<?php echo esc_attr( $regacceptpp ); ?>" class="regular-text" readonly />
			</td>
		</tr>
		<tr>
			<th>
				<label for="reg_date">Regisztráció időpontja</label>
			</th>
			<td>
				<input type="text" name="reg_date" id="reg_date" value="<?php echo esc_attr( $regdate ); ?>" class="regular-text" readonly />
			</td>
		</tr>
		<tr>
			<th>
				<label for="reg_ip">Regisztrációs IP cím</label>
			</th>
			<td>
				<input type="text" name="reg_ip" id="reg_ip" value="<?php echo esc_attr( $regip ); ?>" class="regular-text" readonly />
			</td>
		</tr>
	</table>
<?php
}
add_action( 'show_user_profile', 'surbma_hc_admin_reg_user_profile_fields', 20, 1 );
add_action( 'edit_user_profile', 'surbma_hc_admin_reg_user_profile_fields', 20, 1 );

// Let's show the registration extra user meta values on front-end account page.
function surbma_hc_woocommerce_reg_user_profile_fields() {
	$user_id = get_current_user_id();
	$user = get_userdata( $user_id );

	if ( !$user )
		return;

	$regacceptpp = get_user_meta( $user_id, 'reg_accept_pp', true ) == 1 ? 'Elfogadva' : 'Nincs elfogadva';
	$regdate = date( "r", strtotime( $user->user_registered ) ) != '' ? date( "r", strtotime( $user->user_registered ) ) : 'Nincs dátum eltárolva';
	$regip = get_user_meta( $user_id, 'reg_ip', true ) != '' ? get_user_meta( $user_id, 'reg_ip', true ) : 'Nincs IP cím eltárolva';
?>
	<fieldset class="hc-reg-fields">
		<legend>Regisztrációs adatok</legend>
		<p>Ezek az információk tájékoztató jellegűek, módosítani nem lehet őket.</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="reg_accept_pp">Adatkezelési tájékoztató:</label>
			<input type="text" name="reg_accept_pp" value="<?php echo esc_attr( $regacceptpp ); ?>" class="input-text" readonly />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="reg_date">Regisztráció időpontja:</label>
			<input type="text" name="reg_date" value="<?php echo esc_attr( $regdate ); ?>" class="input-text" readonly />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="reg_ip">Regisztrációs IP cím:</label>
			<input type="text" name="reg_ip" value="<?php echo esc_attr( $regip ); ?>" class="input-text" readonly />
		</p>
	</fieldset>
<?php
}
add_action( 'woocommerce_edit_account_form', 'surbma_hc_woocommerce_reg_user_profile_fields', 10 );

function surbma_hc_legal_checkout_fields( $checkout ) {
	$options = get_option( 'surbma_hc_fields' );
	$legalcheckouttitleValue = isset( $options['legalcheckouttitle'] ) ? $options['legalcheckouttitle'] : 'Vásárláshoz szükséges jogi megerősítések';
	if( $legalcheckouttitleValue != '' ) $legalcheckouttitleValue = '<h3>' . $legalcheckouttitleValue . '</h3>';
	$accepttosValue = isset( $options['accepttos'] ) ? stripslashes( $options['accepttos'] ) : 'Elolvastam és elfogadom az <a href="/aszf/" target="_blank">Általános Szerződési Feltételeket</a>';
	$acceptppValue = isset( $options['acceptpp'] ) ? stripslashes( $options['acceptpp'] ) : 'Elolvastam és elfogadom az <a href="/adatkezeles/" target="_blank">Adatkezelési tájékoztatót</a>';

	echo '<div id="surbma_hc_gdpr_checkout">' . $legalcheckouttitleValue;

	if( $accepttosValue != '' ) {
		woocommerce_form_field( 'accept_tos', array(
			'type'          => 'checkbox',
			'class'         => array( 'form-row-wide', 'tos' ),
			'label'         => $accepttosValue,
			'required'      => true
		), $checkout->get_value( 'accept_tos' ));
	}

	if( $acceptppValue != '' ) {
		woocommerce_form_field( 'accept_pp', array(
			'type'          => 'checkbox',
			'class'         => array( 'form-row-wide', 'pp' ),
			'label'         => $acceptppValue,
			'required'      => true
		), $checkout->get_value( 'accept_pp' ) );
	}

	echo '</div>';
}
add_action( 'woocommerce_after_order_notes', 'surbma_hc_legal_checkout_fields' );

function surbma_hc_legal_checkout_fields_validation() {
	$options = get_option( 'surbma_hc_fields' );

	if ( isset( $options['accepttos'] ) && $options['accepttos'] != '' && !$_POST['accept_tos'] )
		wc_add_notice( '<strong>Általános Szerződési Feltételek</strong> elfogadása kötelező.', 'error' );

	if ( isset( $options['acceptpp'] ) && $options['acceptpp'] != '' && !$_POST['accept_pp'] )
		wc_add_notice( '<strong>Adatkezelési Tájékoztató</strong> elfogadása kötelező.', 'error' );
}
add_action( 'woocommerce_checkout_process', 'surbma_hc_legal_checkout_fields_validation' );

function surbma_hc_legal_checkout_fields_update_order_meta( $order_id ) {
	if ( !empty( $_POST['accept_tos'] ) )
		update_post_meta( $order_id, 'accept_tos', 'Elfogadva' );
	if ( !empty( $_POST['accept_pp'] ) )
		update_post_meta( $order_id, 'accept_pp', 'Elfogadva' );
}
add_action( 'woocommerce_checkout_update_order_meta', 'surbma_hc_legal_checkout_fields_update_order_meta' );

function surbma_hc_legal_checkout_fields_display_admin_order_meta( $order ) {
	$accepttos = get_post_meta( $order->get_id(), 'accept_tos', true );
	$acceptpp = get_post_meta( $order->get_id(), 'accept_pp', true );

	if( $accepttos )
		echo '<p><strong>Általános Szerződési Feltételek:</strong> ' . $accepttos . '</p>';
	if( $acceptpp )
		echo '<p><strong>Adatkezelési Tájékoztató:</strong> ' . $acceptpp . '</p>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'surbma_hc_legal_checkout_fields_display_admin_order_meta', 10, 1 );

function surbma_hc_legal_checkout_before_submit() {
	$options = get_option( 'surbma_hc_fields' );
	$beforeorderbuttonmessageValue = isset( $options['beforeorderbuttonmessage'] ) ? stripslashes( $options['beforeorderbuttonmessage'] ) : null;
	if ( $beforeorderbuttonmessageValue )
		echo '<div class="surbma-hc-before-submit" style="margin: 0 0 1em;text-align: center;">' . $beforeorderbuttonmessageValue . '</div>';
}
add_action( 'woocommerce_review_order_before_submit', 'surbma_hc_legal_checkout_before_submit' );

function surbma_hc_legal_checkout_after_submit() {
	$options = get_option( 'surbma_hc_fields' );
	$afterorderbuttonmessageValue = isset( $options['afterorderbuttonmessage'] ) ? stripslashes( $options['afterorderbuttonmessage'] ) : null;
	if ( $afterorderbuttonmessageValue )
		echo '<div class="surbma-hc-before-submit" style="margin: 1em 0 0;text-align: center;">' . $afterorderbuttonmessageValue . '</div>';
}
add_action( 'woocommerce_review_order_after_submit', 'surbma_hc_legal_checkout_after_submit' );
