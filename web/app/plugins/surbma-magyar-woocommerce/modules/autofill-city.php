<?php

function surbma_hc_autofill_city_scripts() {
	if( is_checkout() ) {
		wp_enqueue_script( 'surbma_hc_zipcodes', SURBMA_HC_PLUGIN_URL . '/assets/js/zipcodes.js', array( 'jquery' ), SURBMA_HC_PLUGIN_VERSION_NUMBER, true );
		wp_enqueue_script( 'surbma_hc_autofill', SURBMA_HC_PLUGIN_URL . '/assets/js/autofill.js', array( 'jquery' ), SURBMA_HC_PLUGIN_VERSION_NUMBER, true );
	}
}
add_action( 'wp_enqueue_scripts', 'surbma_hc_autofill_city_scripts' );
