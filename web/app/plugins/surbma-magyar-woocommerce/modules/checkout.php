<?php

// Add new fields
function surbma_hc_checkout_custom_billing_fields( $fields ) {
	$options = get_option( 'surbma_hc_fields' );
	$billingcompanycheckValue = isset( $options['billingcompanycheck'] ) ? $options['billingcompanycheck'] : 0;
	if( get_option( 'woocommerce_checkout_company_field' ) != 'required' && $billingcompanycheckValue == 1 ) {
		$fields['billing_company_check'] = array(
			'type' 			=> 'checkbox',
			'label' 		=> __( 'Company billing', 'surbma-magyar-woocommerce' ),
			'required' 		=> false,
			'class' 		=> array( 'form-row-wide' ),
			'priority' 		=> 29,
			'clear' 		=> true
		);
	}
	return $fields;
}
add_filter( 'woocommerce_billing_fields', 'surbma_hc_checkout_custom_billing_fields' );

add_action( 'woocommerce_checkout_process', function() {
	if ( get_option( 'woocommerce_checkout_company_field' ) != 'required' && $_POST['billing_company_check'] == 1 && !$_POST['billing_company'] ) {
		$field_label = __( 'Company name', 'woocommerce' );
		$field_label = sprintf( _x( 'Billing %s', 'checkout-validation', 'woocommerce' ), $field_label );
		$noticeError = sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' );
		wc_add_notice( $noticeError, 'error' );
	}
} );

// Pre-populate billing_country field, if it's hidden
function surbma_hc_checkout_default_billing_country( $value ) {
	$options = get_option( 'surbma_hc_fields' );
	$nocountryValue = isset( $options['nocountry'] ) ? $options['nocountry'] : 0;
	if( $nocountryValue == 1 ) {
		// The country/state
		$store_raw_country = get_option( 'woocommerce_default_country' );
		// Split the country/state
		$split_country = explode( ":", $store_raw_country );
		// Country and state separated:
		$store_country = $split_country[0];
		// $store_state = $split_country[1];

		$value = $store_country;
	}
	return $value;
}
add_filter( 'default_checkout_billing_country', 'surbma_hc_checkout_default_billing_country' );

// Customize the checkout default address fields
function surbma_hc_checkout_filter_default_address_fields( $address_fields ) {
	$options = get_option( 'surbma_hc_fields' );

	// Put Postcode and City fields before Address fields
	$address_fields['postcode']['priority'] = 69;
	// $address_fields['city']['priority'] = 60;
	$address_fields['address_1']['priority'] = 95;
	$address_fields['address_2']['priority'] = 96;

	$postcodecitypairValue = isset( $options['postcodecitypair'] ) ? $options['postcodecitypair'] : 0;
	if ( $postcodecitypairValue == 1 ) {
		$address_fields['postcode']['class'] = array( 'form-row-first' );
		$address_fields['city']['class'] = array( 'form-row-last' );
	}

	return $address_fields;
}
add_filter( 'woocommerce_default_address_fields' , 'surbma_hc_checkout_filter_default_address_fields' );

// Customize the checkout fields
function surbma_hc_checkout_filter_checkout_fields( $fields ) {
	$options = get_option( 'surbma_hc_fields' );

	$companytaxnumberpairValue = isset( $options['companytaxnumberpair'] ) ? $options['companytaxnumberpair'] : 0;
	if ( isset( $fields['billing']['billing_company'] ) && isset( $fields['billing']['billing_tax_number'] ) && $companytaxnumberpairValue == 1 ) {
		$fields['billing']['billing_company']['class'] = array( 'form-row-first' );
		$fields['billing']['billing_tax_number']['class'] = array( 'form-row-last' );
	}

	$phoneemailpairValue = isset( $options['phoneemailpair'] ) ? $options['phoneemailpair'] : 0;
	if ( isset( $fields['billing']['billing_phone'] ) && isset( $fields['billing']['billing_email'] ) && $phoneemailpairValue == 1 ) {
		$fields['billing']['billing_phone']['class'] = array( 'form-row-first' );
		$fields['billing']['billing_email']['class'] = array( 'form-row-last' );
	}

	$noordercommentsValue = isset( $options['noordercomments'] ) ? $options['noordercomments'] : 0;
	if ( isset( $fields['order']['order_comments'] ) && $noordercommentsValue == 1 ) {
		unset( $fields['order']['order_comments'] );
	}

	return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'surbma_hc_checkout_filter_checkout_fields', 9999 );

add_action( 'wp_footer', function() {
	if( is_checkout() ) {
		$options = get_option( 'surbma_hc_fields' );
		$nocountryValue = isset( $options['nocountry'] ) ? $options['nocountry'] : 0;
		$billingcompanycheckValue = isset( $options['billingcompanycheck'] ) ? $options['billingcompanycheck'] : 0;
		$companytaxnumberpairValue = isset( $options['companytaxnumberpair'] ) ? $options['companytaxnumberpair'] : 0;
?>
<script>
jQuery(document).ready(function($){
	<?php if( $nocountryValue == 1 ) { ?>
		$("#billing_country_field").hide();
	<?php } ?>

	<?php if( $billingcompanycheckValue == 1 ) { ?>
		$('#billing_company_check_field label span').hide();

		<?php if( get_option( 'woocommerce_checkout_company_field' ) != 'required' ) { ?>
			// Add required sign and remove the "not required" text from billing_company_field
			$("#billing_company_field").children('label').append( ' <abbr class="required" title="required">*</abbr>' );
			$("#billing_company_field label span").hide();
			$("#billing_company_field").addClass('validate-required');
		<?php } ?>

		if($('#billing_company_check').prop('checked') == false){
			$('#billing_company_field').hide();
			$('#billing_tax_number_field').hide();
		}

		$('#billing_company_check').click(function(){
			if($(this).prop('checked') == true){
				$('#billing_company_field').show();
				$('#billing_tax_number_field').show();
			}
			else if($(this).prop('checked') == false){
				// Hiding the company related fields
				$('#billing_company_field').hide();
				$('#billing_tax_number_field').hide();

				// Empty the company related field values, because we don't want to save company details
				$('#billing_company').val('');
				$('#billing_tax_number').val('');

				// Reset classes, as they are empty again
				$("#billing_company_field").removeClass('woocommerce-validated');
				$("#billing_tax_number_field").removeClass('woocommerce-validated');
				$("#billing_company_field").removeClass('woocommerce-invalid woocommerce-invalid-required-field');
				$("#billing_tax_number_field").removeClass('woocommerce-invalid woocommerce-invalid-required-field');
			}
		});
	<?php } ?>
});
</script>
<?php
	}
} );
