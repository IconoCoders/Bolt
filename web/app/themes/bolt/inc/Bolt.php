<?php

/**
 * Change the default state and country on the checkout page
 */
add_filter( 'default_checkout_billing_country', 'change_default_checkout_country' );
add_filter( 'default_checkout_billing_state', 'change_default_checkout_state' );
function change_default_checkout_country()
{
    return 'HU'; // country code
}

function change_default_checkout_state()
{
    return 'HU'; // state code
}

// Custom translations
function bolt_custom_strings( $translation, $text, $domain )
{
    switch ( $translation ) {
        case 'You\'re viewing:':
            $translation = 'Termék:';
            break;
        case 'Company billing':
            $translation = 'ÁFÁ-s számlát kérek';
            break;
        case 'Company name':
            $translation = 'Számlázási név / cégnév';
            break;
        case 'Tax number':
            $translation = 'Adószám';
            break;
        case 'Order received':
            $translation = 'Sikeres megrendelés!';
            break;
        case 'Shop order number:':
            $translation = 'Rendelésszám';
            break;
        case 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.':
            $translation = 'Ha már vásároltál nálunk add meg adataid a gyors és egyszerű vásárlási folyamathoz.';
            break;
    }

    return $translation;
}
add_filter( 'gettext', 'bolt_custom_strings', 20, 3 );

add_filter( 'gettext', 'filter_gettext', 10, 3 );
function filter_gettext( $translated, $original, $domain ) {
    switch($translated) {
        case 'Cégnév':
            $translated = 'Cégnév/számlázási név';
            break;
        case 'Utcanév, házszám':
            $translated = '';
            break;
    }

    return $translated;
}
