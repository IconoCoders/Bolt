jQuery(document).ready(function($){

	// Add required sign and remove the "not required" text from billing_tax_number_field
	$("#billing_tax_number_field").children('label').append( ' <abbr class="required" title="required">*</abbr>' );
	$("#billing_tax_number_field label span").hide();
	$("#billing_tax_number_field").addClass('validate-required');

	// Set Tax number field to invalid, as it is empty.
	if(! $('#billing_tax_number').val()){
		$("#billing_tax_number_field").addClass('woocommerce-invalid woocommerce-invalid-required-field');
	}

	$('#billing_company').keyup(function() {
		if( $(this).val().length == 0 ) {
			$('#billing_tax_number_field').hide();
			$('#billing_tax_number').val('');
			// Set Tax number field to invalid, as it is empty again.
			$("#billing_tax_number_field").addClass('woocommerce-invalid woocommerce-invalid-required-field');
		} else {
			$('#billing_tax_number_field').show();
		}
	}).keyup();
});
