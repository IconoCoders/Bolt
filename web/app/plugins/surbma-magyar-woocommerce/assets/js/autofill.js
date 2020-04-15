jQuery(document).ready(function($){

	if(typeof surbma_hc_postcodes != 'undefined'){

		if($('.woocommerce-checkout #billing_postcode').length){
			var $postcodeFieldBilling = $('.woocommerce-checkout #billing_postcode');
			var $cityFieldBilling = $('.woocommerce-checkout #billing_city');
			var cityFieldBillingTouched = false;

			// If city is manually added, don't change it.
			$cityFieldBilling.keyup(function() {
				cityFieldBillingTouched = true;
			});

			$postcodeFieldBilling.on('input change focusout keyup', function(){
				var postcodeBilling = parseInt($postcodeFieldBilling.val());
				var cityIndexBilling = surbma_hc_postcodes.indexOf(postcodeBilling);
				var cityBilling = surbma_hc_cities[cityIndexBilling];
				if($postcodeFieldBilling.val().length == 4 && cityIndexBilling > -1 && ($cityFieldBilling.val() == '' || !cityFieldBillingTouched) && surbma_hc_postcodes[cityIndexBilling+1] != postcodeBilling){
					$cityFieldBilling.val( cityBilling );
					if($cityFieldBilling.val() != '' && $("#billing_city_field").hasClass("woocommerce-invalid woocommerce-invalid-required-field")){
						$("#billing_city_field").removeClass("woocommerce-invalid woocommerce-invalid-required-field");
					}
					if($cityFieldBilling.val() != ''){
						$("#billing_city_field").addClass("woocommerce-validated");
					}
					$('body').trigger('update_checkout');
				}
			});
		}

		if($('.woocommerce-checkout #shipping_postcode').length){
			var $postcodeFieldShipping = $('.woocommerce-checkout #shipping_postcode');
			var $cityFieldShipping = $('.woocommerce-checkout #shipping_city');
			var cityFieldShippingTouched = false;

			// If city is manually added, don't change it.
			$cityFieldShipping.keyup(function() {
				cityFieldShippingTouched = true;
			});

			$postcodeFieldShipping.on('input change focusout keyup', function(){
				var postcodeShipping = parseInt($postcodeFieldShipping.val());
				var cityIndexShipping = surbma_hc_postcodes.indexOf(postcodeShipping);
				var cityShipping = surbma_hc_cities[cityIndexShipping];
				if($postcodeFieldShipping.val().length == 4 && cityIndexShipping > -1 && ($cityFieldShipping.val() == '' || !cityFieldShippingTouched) && surbma_hc_postcodes[cityIndexShipping+1] != postcodeShipping){
					$cityFieldShipping.val( cityShipping );
					$('body').trigger('update_checkout');
				}
			});
		}

	}

});
