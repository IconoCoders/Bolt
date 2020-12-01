<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="icon" type="image/png" href="https://assets-github.s3.amazonaws.com/bolt/assets/favicon-16x16.png" sizes="16x16">
<link rel="icon" type="image/png" href="https://assets-github.s3.amazonaws.com/bolt/assets/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="https://assets-github.s3.amazonaws.com/bolt/assets/favicon.ico" sizes="96x96">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

<!-- custom analytics -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<?php
$gaCode = getenv('GA_CODE');

if($gaCode): ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', '<?php echo $gaCode; ?>', 'auto');
        ga('send', 'pageview');
    </script>
<?php endif;

$owaUrl = getenv('OWA_URL');
$owaCode = getenv('OWA_CODE');
$owaTracking = getenv('OWA_TRACKING');
$hotjarId = getenv('HOTJAR_ID');

//OWA
if($owaTracking && $owaCode): ?>
    <!-- Start Open Web Analytics Tracker -->
    <script type="text/javascript">
        //<![CDATA[
        var owa_baseUrl = '<?php echo $owaUrl; ?>';
        var owa_cmds = owa_cmds || [];
        owa_cmds.push(['setSiteId', '<?php echo $owaCode; ?>']);
        owa_cmds.push(['trackPageView']);
        owa_cmds.push(['trackClicks']);

        (function() {
            var _owa = document.createElement('script'); _owa.type = 'text/javascript'; _owa.async = true;
            owa_baseUrl = ('https:' == document.location.protocol ? window.owa_baseSecUrl || owa_baseUrl.replace(/http:/, 'https:') : owa_baseUrl );
            _owa.src = owa_baseUrl + 'modules/base/js/owa.tracker-combined-min.js';
            var _owa_s = document.getElementsByTagName('script')[0]; _owa_s.parentNode.insertBefore(_owa, _owa_s);
        }());
        //]]>
    </script>
    <!-- End Open Web Analytics Code -->
<?php endif;

//hotjar
if($hotjarId): ?>
    <!-- Hotjar Tracking Code -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:<?php echo $hotjarId; ?>,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
<?php endif; ?>

</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site">
	<?php do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner" style="<?php storefront_header_styles(); ?>">

		<?php
		/**
		 * Functions hooked into storefront_header action
		 *
		 * @hooked storefront_header_container                 - 0
		 * @hooked storefront_skip_links                       - 5
		 * @hooked storefront_social_icons                     - 10
		 * @hooked storefront_site_branding                    - 20
		 * @hooked storefront_secondary_navigation             - 30
		 * @hooked storefront_product_search                   - 40
		 * @hooked storefront_header_container_close           - 41
		 * @hooked storefront_primary_navigation_wrapper       - 42
		 * @hooked storefront_primary_navigation               - 50
		 * @hooked storefront_header_cart                      - 60
		 * @hooked storefront_primary_navigation_wrapper_close - 68
		 */
		do_action( 'storefront_header' );
		?>

	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 * @hooked woocommerce_breadcrumb - 10
	 */
	do_action( 'storefront_before_content' );
	?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		do_action( 'storefront_content_top' );
