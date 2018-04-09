<?php
// settings form

if (!defined('ABSPATH')) {
	exit;
}
?>

<div class="wrap">

	<h1><?php esc_html_e('SSL Insecure Content Fixer tests', 'ssl-insecure-content-fixer'); ?></h1>

	<p><?php esc_html_e('This page checks to see whether WordPress can detect HTTPS.', 'ssl-insecure-content-fixer'); ?></p>

	<div id="sslfix-loading">
		<p><?php esc_html_e('Running tests...', 'ssl-insecure-content-fixer'); ?>
		<img src="<?php echo esc_url(plugins_url('images/ajax-loader.gif', SSLFIX_PLUGIN_FILE)); ?>" aria-hidden="true" />
		</p>
	</div>

	<h2 id="sslfix-test-result-head" aria-hidden="true"><?php esc_html_e('Tests completed.', 'ssl-insecure-content-fixer'); ?><i id="sslfix-https-detection"></i></h2>

	<div class="sslfix-test-result" id="sslfix-normal" aria-hidden="true">
		<p><?php printf(esc_html__('Your server can detect HTTPS normally. The recommended setting for HTTPS detection is %s.', 'ssl-insecure-content-fixer'), sprintf('<strong>%s</strong>', _x('standard WordPress function', 'proxy settings', 'ssl-insecure-content-fixer'))); ?></p>
	</div>

	<div class="sslfix-test-result" id="sslfix-HTTP_X_FORWARDED_PROTO" aria-hidden="true">
		<p><?php printf(esc_html__('It looks like your server is behind a reverse proxy. The recommended setting for HTTPS detection is %s.', 'ssl-insecure-content-fixer'), '<strong>HTTP_X_FORWARDED_PROTO</strong>'); ?></p>
	</div>

	<div class="sslfix-test-result" id="sslfix-HTTP_X_FORWARDED_SSL" aria-hidden="true">
		<p><?php printf(esc_html__('It looks like your server is behind a reverse proxy. The recommended setting for HTTPS detection is %s.', 'ssl-insecure-content-fixer'), '<strong>HTTP_X_FORWARDED_SSL</strong>'); ?></p>
	</div>

	<div class="sslfix-test-result" id="sslfix-HTTP_X_FORWARDED_SCHEME" aria-hidden="true">
		<p><?php printf(esc_html__('It looks like your server is behind a reverse proxy. The recommended setting for HTTPS detection is %s.', 'ssl-insecure-content-fixer'), '<strong>HTTP_X_FORWARDED_SCHEME</strong>'); ?></p>
	</div>

	<div class="sslfix-test-result" id="sslfix-HTTP_CLOUDFRONT_FORWARDED_PROTO" aria-hidden="true">
		<p><?php printf(esc_html__('It looks like your server is behind Amazon CloudFront, not configured to send HTTP_X_FORWARDED_PROTO. The recommended setting for HTTPS detection is %s.', 'ssl-insecure-content-fixer'), '<strong>HTTP_CLOUDFRONT_FORWARDED_PROTO</strong>'); ?></p>
	</div>

	<div class="sslfix-test-result" id="sslfix-HTTP_X_ARR_SSL" aria-hidden="true">
		<p><?php printf(esc_html__('It looks like your server is behind Windows Azure ARR. The recommended setting for HTTPS detection is %s.', 'ssl-insecure-content-fixer'), '<strong>HTTP_X_ARR_SSL</strong>'); ?></p>
	</div>

	<?php /* TODO: remove this when removing deprecated HTTP_CF_VISITOR setting */ ?>
	<div class="sslfix-test-result" id="sslfix-HTTP_CF_VISITOR" aria-hidden="true">
		<p><?php printf(esc_html__('It looks like your server uses Cloudflare Flexible SSL. The recommended setting for HTTPS detection is %s.', 'ssl-insecure-content-fixer'), '<strong>HTTP_CF_VISITOR</strong>'); ?></p>
	</div>

	<div class="sslfix-test-result" id="sslfix-detect_fail" aria-hidden="true">
		<p><?php printf(esc_html__('Your server cannot detect HTTPS. The recommended setting for HTTPS detection is %s.', 'ssl-insecure-content-fixer'), sprintf('<strong>%s</strong>', _x('unable to detect HTTPS', 'proxy settings', 'ssl-insecure-content-fixer'))); ?></p>
		<p><?php printf(__('If you know of a way to detect HTTPS on your server, please <a href="%s" target="_blank" rel="noopener">tell me about it</a>.', 'ssl-insecure-content-fixer'), 'https://shop.webaware.com.au/support/'); ?></p>
	</div>

	<div class="sslfix-test-result" id="sslfix-environment" aria-hidden="true">
		<p><?php esc_html_e('Your server environment shows this:', 'ssl-insecure-content-fixer'); ?></p>
		<pre></pre>
	</div>

</div>
