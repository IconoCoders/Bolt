<?php

// Add new fields
add_filter( 'woocommerce_billing_fields', function( $fields ) {
	$fields['billing_tax_number'] = array(
		'label' 		=> __( 'Tax number', 'surbma-magyar-woocommerce' ),
		'required' 		=> false,
		'class' 		=> array( 'form-row-wide' ),
		'priority' 		=> 32,
		'clear' 		=> true
	);
	return $fields;
} );

add_action( 'woocommerce_checkout_process', function() {
	if ( ( get_option( 'woocommerce_checkout_company_field' ) == 'required' || $_POST['billing_company_check'] == 1 || $_POST['billing_company'] ) && !$_POST['billing_tax_number'] ) {
		$field_label = __( 'Tax number', 'surbma-magyar-woocommerce' );
		$field_label = sprintf( _x( 'Billing %s', 'checkout-validation', 'woocommerce' ), $field_label );
		$noticeError = sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' );
		wc_add_notice( $noticeError, 'error' );
	}
} );

add_action( 'woocommerce_checkout_update_user_meta', function( $customer_id ) {
	$billing_tax_number = !empty( $_POST['billing_tax_number'] ) ? sanitize_text_field( $_POST['billing_tax_number'] ) : '';
	update_user_meta( $customer_id, 'billing_tax_number', $billing_tax_number );
} );

// Add extra user meta to edit order page.
// This is not needed anymore, as woocommerce_localisation_address_formats filter adds it to the edit order page also.
// add_action( 'woocommerce_admin_order_data_after_billing_address', function( $order ) {
// 	global $post;
// 	$customer_user = get_post_meta( $post->ID, '_customer_user', true );
// 	$taxnumber = get_user_meta( $customer_user, 'billing_tax_number', true );
// 	$taxnumber = $taxnumber != '' ? $taxnumber : 'Nincs megadva';
// 	echo '<p><strong>Adószám:</strong> ' . $taxnumber . '</p>';
// } );

add_filter( 'woocommerce_localisation_address_formats', function( $formats ) {
	foreach ( $formats as $key => &$format ) {
		$format .= "\n{tax_number}";
	}
	return $formats;
} );

// Replacement value for My Account page.
add_filter( 'woocommerce_my_account_my_address_formatted_address', function( $address, $customer_id, $address_type ) {
	$taxnumber = get_user_meta( $customer_id, 'billing_tax_number', true );
	$address['tax_number'] = $address_type == 'billing' && $taxnumber != '' ? __( 'Tax number', 'surbma-magyar-woocommerce' ) . ': ' . $taxnumber : null;
	return $address;
}, 10, 3 );

// Replacement value for Billing address on Thank you page.
add_filter( 'woocommerce_order_formatted_billing_address', function( $address, $wc_order ) {
	$taxnumber = $wc_order->get_meta( '_billing_tax_number' );
	$address['tax_number'] = $taxnumber != '' ? __( 'Tax number', 'surbma-magyar-woocommerce' ) . ': ' . $taxnumber : null;
	return $address;
}, 10, 2 );

// Replacement value for Shipping address on Thank you page.
add_filter( 'woocommerce_order_formatted_shipping_address', function( $address ) {
	$address['tax_number'] = null;
	return $address;
} );

// Replacement value for Billing & Shipping address on Thank you page.
// add_filter( 'woocommerce_get_order_address', function( $address, $type, $order ) {
// 	$address['tax_number'] = __( 'Tax number', 'surbma-magyar-woocommerce' ) . ': ' . $order->get_meta( '_billing_tax_number' );
// 	return $address;
// }, 10, 3 );

// Replacement for the new tax_number field.
add_filter( 'woocommerce_formatted_address_replacements', function( $replacements, $args ) {
	$taxnumber = isset( $args['tax_number'] ) ? $args['tax_number'] : null;
	$replacements['{tax_number}'] = $taxnumber;
	return $replacements;
}, 10, 2 );

// Adding Tax number to user profile.
add_filter( 'woocommerce_customer_meta_fields', function( $profileFieldArray ) {
	$fieldData = array(
		'label'			=> __( 'Tax number', 'surbma-magyar-woocommerce' ),
		'description'   => ''
	);
	$profileFieldArray['billing']['fields']['billing_tax_number'] = $fieldData;
	return $profileFieldArray;
} );

add_action( 'wp_footer', function() {
	if( is_checkout() ) {
		$options = get_option( 'surbma_hc_fields' );
		$billingcompanycheckValue = isset( $options['billingcompanycheck'] ) ? $options['billingcompanycheck'] : 0;
		$companytaxnumberpairValue = isset( $options['companytaxnumberpair'] ) ? $options['companytaxnumberpair'] : 0;
?>
<script>
jQuery(document).ready(function($){

	// Add required sign and remove the "not required" text from billing_tax_number_field
	$("#billing_tax_number_field").children('label').append( ' <abbr class="required" title="required">*</abbr>' );
	$("#billing_tax_number_field label span").hide();

	$("#billing_tax_number_field").addClass('validate-required');

	<?php if( $billingcompanycheckValue == 0 && $companytaxnumberpairValue == 1 && get_option( 'woocommerce_checkout_company_field' ) != 'required' ) { ?>
		$("#billing_tax_number_field").children('label').children('abbr').hide();
		$("#billing_tax_number_field label span").show();
	<?php } ?>

	<?php if( get_option( 'woocommerce_checkout_company_field' ) == 'requireds' ) { ?>
		// Add required sign and remove the "not required" text from billing_tax_number_field
		$("#billing_tax_number_field label span").hide();
		$("#billing_tax_number_field").children('label').children('abbr').show();
		$("#billing_tax_number_field").addClass('validate-required');
	<?php } ?>

	// Check Company field value
	$('#billing_company').keyup(function() {
		if( $(this).val().length == 0 ) {
			<?php if( $billingcompanycheckValue == 0 && $companytaxnumberpairValue == 0 ) { ?>
				// If Company is empty, hide Tax number
				$('#billing_tax_number_field').hide();
				// If Company is empty, empty Tax number
				$('#billing_tax_number').val('');
				// Set Tax number field to invalid, as it is empty again
				$("#billing_tax_number_field").removeClass('woocommerce-validated');
				$("#billing_tax_number_field").removeClass('woocommerce-invalid woocommerce-invalid-required-field');
			<?php } ?>
			<?php if( $billingcompanycheckValue == 0 && $companytaxnumberpairValue == 1 && get_option( 'woocommerce_checkout_company_field' ) != 'required' ) { ?>
				$("#billing_tax_number_field").removeClass('validate-required');
				// $("#billing_tax_number_field").removeClass('woocommerce-invalid woocommerce-invalid-required-field');
				$("#billing_tax_number_field label span").show();
				$("#billing_tax_number_field").children('label').children('abbr').hide();
			<?php } ?>
		} else {
			<?php if( $billingcompanycheckValue == 0 && $companytaxnumberpairValue == 0 ) { ?>
				$('#billing_tax_number_field').show();
			<?php } ?>
			<?php if( $billingcompanycheckValue == 0 && $companytaxnumberpairValue == 1 ) { ?>
				// Add required sign and remove the "not required" text from billing_tax_number_field
				$("#billing_tax_number_field label span").hide();
				$("#billing_tax_number_field").children('label').children('abbr').show();
				$("#billing_tax_number_field").addClass('validate-required');
			<?php } ?>
		}
	}).keyup();

});
</script>
<?php
	}
} );

