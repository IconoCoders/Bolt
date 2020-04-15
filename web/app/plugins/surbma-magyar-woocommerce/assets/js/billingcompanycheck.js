jQuery(document).ready(function($){

	$('#billing_company_check_field label span').hide();

	// Add required sign and remove the "not required" text from billing_company_field
	$("#billing_company_field").children('label').append( ' <abbr class="required" title="required">*</abbr>' );
	$("#billing_company_field label span").hide();
	$("#billing_company_field").addClass('validate-required');

	// Set company field to invalid, as it is empty
	if(! $('#billing_company').val()){
		$("#billing_company_field").addClass('woocommerce-invalid woocommerce-invalid-required-field');
	}

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

			// Set company related fields to invalid, as they are empty again
			$("#billing_company_field").addClass('woocommerce-invalid woocommerce-invalid-required-field');
			$("#billing_tax_number_field").addClass('woocommerce-invalid woocommerce-invalid-required-field');
		}
	});

});
