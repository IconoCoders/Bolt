<?php
/**
 * Plugin Name: CC Cookie Consent
 * Plugin URI: https://github.com/progcode/WPCookieConsent
 * Plugin Issues: https://github.com/progcode/WPCookieConsent/issues
 * Description: Cookie Consent Plugin for WordPress. Original javascript plugin developed by Silktide
 * Version: 1.6.0
 * Author: WebPositive <hello@progweb.hu>
 * Author URI: https://progweb.hu
 * Tags: cookie, cookie consent, wordpress, silktide
 * Author e-mail: developer@progweb.hu
 * Text Domain: cookie-consent
 * Domain Path: /locale
 */

if(!defined('ABSPATH')) exit('No direct script access allowed');
define('CC_VERSION','1.6.0');
define('CC_BUILD_DATE','2018-05-18');

global $theme;
global $message;
global $more_info;
global $more_link;
global $ok_button;

$theme = "light-bottom";
$message = __( 'Hello! This website uses cookies to ensure you get the best experience on our website', 'cookie-consent' );
$more_info = __( 'More info', 'cookie-consent' );
$more_link = null;
$ok_button = __( 'Got it!', 'cookie-consent' );

/**
 * Load plugin translations
 */
function loadPluginTranslation()
{
    load_plugin_textdomain( 'cookie-consent', FALSE, basename( dirname( __FILE__ ) ) . '/locale/' );
}
add_action( 'plugins_loaded', 'loadPluginTranslation' );

function wpSilktideCookieScripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
        wp_register_script('cc-js_v3', 'https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js', array(), CC_VERSION, true);
        wp_enqueue_script('cc-js_v3');
    }
}
add_action('wp_enqueue_scripts', 'wpSilktideCookieScripts');

/**
 * Load css to wp_head() without js/http request
 * Github issue: https://github.com/progcode/WPCookieConsent/issues/2
 */
function wpSilktideCookieStyle()
{
    wp_register_style('cc-css_v3', 'https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css', array(), CC_VERSION);
    wp_enqueue_style('cc-css_v3');
}
add_action('wp_enqueue_scripts', 'wpSilktideCookieStyle');

/** Add CC config js if cookie.consent.js loaded */
function wpSilktideCookieInlineScripts()
{ ?>
    <script>
        function clearCookie(d, b, c) {
            try {
                if (function(h) {
                        var e = document.cookie.split(";"),
                            a = "",
                            f = "",
                            g = "";
                        for (i = 0; i < e.length; i++) {
                            a = e[i].split("=");
                            f = a[0].replace(/^\s+|\s+$/g, "");
                            if (f == h) {
                                if (a.length > 1) g = unescape(a[1].replace(/^\s+|\s+$/g, ""));
                                return g
                            }
                        }
                        return null
                    }(d)) {
                    b = b || document.domain;
                    c = c || "/";
                    document.cookie = d + "=; expires=" + new Date + "; domain=" + b + "; path=" + c
                }
            } catch (j) {}
        }

        window.addEventListener("load", function(){
            window.cookieconsent.initialise({
                "palette": {
                    "popup": {
                        "background": "#383b75"
                    },
                    "button": {
                        "background": "#f1d600"
                    }
                },
                "showLink": true,
                "type": "opt-out",
                "content": {
                    "allow": "Cookiek<br/> engedélyezése",
                    "message": "<?php if(get_option('silktide_cc_text_headline')): echo esc_js(get_option('silktide_cc_text_headline')); else: global $message; echo esc_js($message); endif; ?>",
                    "dismiss": "<?php if(get_option('silktide_cc_text_button')): echo esc_js(get_option('silktide_cc_text_button')); else: global $ok_button; echo esc_js($ok_button); endif; ?>",
                    "deny": "Elutasítom / Decline",
                    "link": "<?php if(get_option('silktide_cc_text_more_button')): echo esc_js(get_option('silktide_cc_text_more_button')); else: global $more_info; echo esc_js($more_info); endif; ?>",
                    "href": "https://iconocoders.com/adatvedelem/"
                },
                //"revokeBtn": "<div class='cc-revoke cc-bottom cc-animate'>Adatvédelmi Tájékoztató</div>",
                onInitialise: function (status) {
                    console.log('cookie status init');

                    var type = this.options.type;
                    var didConsent = this.hasConsented();
                    if (didConsent) {
                        // enable cookies
                        window.dataLayer = window.dataLayer || [];
                        function gtag(){dataLayer.push(arguments);}
                        gtag('js', new Date());

                        //Hide user real ip
                        gtag('config', 'UA-64571746-7', { 'anonymize_ip': true });
                    }
                },

                onStatusChange: function(status, chosenBefore) {
                    console.log('cookie status change');

                    var type = this.options.type;
                    var didConsent = this.hasConsented();
                    if (didConsent) {
                        // enable cookies
                        window.dataLayer = window.dataLayer || [];
                        function gtag(){dataLayer.push(arguments);}
                        gtag('js', new Date());

                        //Hide user real ip
                        gtag('config', 'UA-64571746-7', { 'anonymize_ip': true });
                    }
                },

                onRevokeChoice: function() {
                    console.log('disable cookies');

                    var type = this.options.type;
                    if (type == 'opt-out') {
                        // disable cookies
                        clearCookie('_ga','.','/');
                        clearCookie('_gat_gtag_UA-64571746-7','.szerver.store','/');
                        clearCookie('_gid','.szerver.store','/');
                        clearCookie('gravatar','.szerver.store','/');
                        clearCookie('is-logged-in','.szerver.store','/');
                        clearCookie('wordpress_test_cookie','.szerver.store','/');
                        clearCookie('wp-settings-1','.szerver.store','/');
                        clearCookie('wp-settings-time-1','.szerver.store','/');
                        clearCookie('sc_is_visitor_unique','.szerver.store','/');
                        clearCookie('sc_is_visitor_unique','.szerver.store','/');
                        clearCookie('helpcrunch-device','.szerver.store','/');
                        clearCookie('is_unique','.szerver.store','/');
                        clearCookie('is_unique_1','.szerver.store','/');
                        clearCookie('device-referrer','.szerver.store','/');
                        clearCookie('device-source','.szerver.store','/');
                        // Get an array of cookies
                        var arrSplit = document.cookie.split(";");
                        for(var i = 0; i < arrSplit.length; i++)
                        {
                            var cookie = arrSplit[i].trim();
                            var cookieName = cookie.split("=")[0];
                            // If the prefix of the cookie's name matches the one specified, remove it
                            if(cookieName.indexOf("ssupp.") === 0) {
                                // Remove the cookie
                                document.cookie = cookieName + "=; Max-Age=-99999999;";
                            }
                        }
                    }
                }
            })}
        );
    </script>
    <?php
}
add_action('wp_footer', 'wpSilktideCookieInlineScripts');

/** Add Settings link */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpSilktideCookieSettingsLinks' );
function wpSilktideCookieSettingsLinks( $links )
{
    $links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=cookie-consent') ) .'">'.__( 'Settings', 'cookie-consent' ).'</a>';
    return $links;
}

/**
 * Add Settings Page
 */
add_action('admin_menu', 'wpSilktideCookieSettings');
function wpSilktideCookieSettings() {
    add_menu_page(__('Cookie Consent','cookie-consent'), __('Cookie Consent','cookie-consent'), 'manage_options', 'cookie-consent', 'wpSilktideCookieSettingsPage');
}

/** option template for settings pages */
function wpSilktideCustomOptionTemplate($option_section, $option_options)
{
    ?>
    <div class="wrap">
        <h1>GDPR Cookie Settings</h1>

        <form class="cc" method="post" action="options.php" id="cookieConsentSettings">
            <?php
            settings_fields($option_section);
            do_settings_sections($option_options);
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function wpSilktideInputField($input, $placeholder)
{
    echo '<input class="regular-text" type="text" name="'.$input.'" id="'.$input.'" value="'.get_option($input).'" placeholder="'.$placeholder.'" />';
}

/** Plugin Settings Tab */
function wpSilktideCookieSettingsPage()
{
    $option_section = "silktide-cc-plugin-section";
    $option_options = "silktide-cc-plugin-options";
    wpSilktideCustomOptionTemplate($option_section, $option_options);
}

function wpSilktideCookieTextHeadline()
{
    $input = "silktide_cc_text_headline";
    $placeholder = "Headline text";
    wpSilktideInputField($input, $placeholder);
}

function wpSilktideCookieTextAcceptButton()
{
    $input = "silktide_cc_text_button";
    $placeholder = "Accept button text";
    wpSilktideInputField($input, $placeholder);
}

function wpSilktideCookieTextReadMoreButton()
{
    $input = "silktide_cc_text_more_button";
    $placeholder = "Read more button text";
    wpSilktideInputField($input, $placeholder);
}

/**
 * Save and get options
 */
function wpSilktideCookieFields()
{
    add_settings_section("silktide-cc-plugin-section", null, null, "silktide-cc-plugin-options");

    add_settings_field("silktide_cc_text_headline", __('Headline text', 'cookie-consent'), "wpSilktideCookieTextHeadline", "silktide-cc-plugin-options", "silktide-cc-plugin-section");
    add_settings_field("silktide_cc_text_button", __('Accept button text', 'cookie-consent'), "wpSilktideCookieTextAcceptButton", "silktide-cc-plugin-options", "silktide-cc-plugin-section");
    add_settings_field("silktide_cc_text_more_button", __('Read more button text', 'cookie-consent'), "wpSilktideCookieTextReadMoreButton", "silktide-cc-plugin-options", "silktide-cc-plugin-section");

    register_setting("silktide-cc-plugin-section", "silktide_cc_text_headline");
    register_setting("silktide-cc-plugin-section", "silktide_cc_text_button");
    register_setting("silktide-cc-plugin-section", "silktide_cc_text_more_button");
}
add_action("admin_init", "wpSilktideCookieFields");