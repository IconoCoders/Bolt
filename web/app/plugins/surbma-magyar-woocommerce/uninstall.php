<?php

// if uninstall.php is not called by WordPress, die.
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) die;

delete_option( 'surbma_hc_fields' );
delete_option( 'pand-' . md5( 'surbma-hc-notice-welcome' ) );
