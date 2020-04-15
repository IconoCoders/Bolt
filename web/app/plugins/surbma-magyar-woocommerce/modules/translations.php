<?php

// Custom translations
function surbma_hc_custom_strings( $translation, $text, $domain ) {
	switch ( $translation ) {
		case '' :
			$translation = '';
			break;
	}
	return $translation;
}
// add_filter( 'gettext', 'surbma_hc_custom_strings', 20, 3 );

// Custom translations for plural strings without context
function surbma_hc_custom_plural_strings( $translation, $single, $plural, $number, $domain ) {
	// _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'storefront' )
	if( get_locale() == 'hu_HU' && $domain === 'storefront' ) {
		switch ( $single ) {
			case '%d item':
				$single = '%d termék';
				break;
		}
		return $single;

		switch ( $plural ) {
			case '%d items':
				$plural = '%d termék';
				break;
		}
		return $plural;
	}
	return $translation;
}
add_filter( 'ngettext', 'surbma_hc_custom_plural_strings', 20, 5 );

// Custom translations for plural strings with context
function surbma_hc_custom_plural_strings_context( $translation, $single, $plural, $number, $context, $domain ) {
	// _nx( '%1$s Item', '%1$s Items', $items_number, 'WooCommerce items number', 'Divi' )
	if( get_locale() == 'hu_HU' && $domain === 'Divi' && $context == 'WooCommerce items number' ) {
		switch ( $single ) {
			case '%1$s Item':
				$single = '%1$s Termék';
				break;
		}
		return $single;

		switch ( $plural ) {
			case '%1$s Items':
				$plural = '%1$s Termék';
				break;
		}
		return $plural;
	}
	return $translation;
}
add_filter( 'ngettext_with_context', 'surbma_hc_custom_plural_strings_context', 20, 6 );
