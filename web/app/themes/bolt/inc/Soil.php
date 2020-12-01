<?php

/**
 * Enable features from Soil when plugin is activated
 * @link https://roots.io/plugins/soil/
 */
add_theme_support('soil', [
    'clean-up',
    'disable-rest-api',
    'disable-asset-versioning',
    'disable-trackbacks',
    'js-to-footer',
    'nav-walker',
    'nice-search',
    'relative-urls'
]);
