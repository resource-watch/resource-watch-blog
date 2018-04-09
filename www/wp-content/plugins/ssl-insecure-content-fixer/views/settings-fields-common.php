<?php
if (!defined('ABSPATH')) {
	exit;
}
?>

<tr valign="top">
	<th scope="row"><?php esc_html_e('Fix insecure content', 'ssl-insecure-content-fixer'); ?></th>
	<td id="sslfix-levels">
		<p><em><?php esc_html_e('Select the level of fixing. Try the Simple level first, it has the least impact on your website performance.', 'ssl-insecure-content-fixer'); ?></em></p>
		<ul>

			<li>
				<input type="radio" name="ssl_insecure_content_fixer[fix_level]" id="fix_level_off" value="off" <?php checked($options['fix_level'], 'off'); ?> />
				<label for="fix_level_off"><?php echo esc_html_x('Off', 'fix level settings', 'ssl-insecure-content-fixer'); ?></label>
				<p class="sslfix-level-desc"><?php echo esc_html_x('No insecure content will be fixed', 'fix level settings', 'ssl-insecure-content-fixer'); ?></p>
			</li>

			<li>
				<input type="radio" name="ssl_insecure_content_fixer[fix_level]" id="fix_level_simple" value="simple" <?php checked($options['fix_level'], 'simple'); ?> />
				<label for="fix_level_simple"><?php echo esc_html_x('Simple', 'fix level settings', 'ssl-insecure-content-fixer'); ?></label>
				<p class="sslfix-level-desc"><?php echo esc_html_x('The fastest method with the least impact on website performance', 'fix level settings', 'ssl-insecure-content-fixer'); ?></p>
				<ul class="sslfix-bullets">
					<li><?php echo _x('scripts registered using <code>wp_register_script()</code> or <code>wp_enqueue_script()</code>', 'fix level settings', 'ssl-insecure-content-fixer'); ?></li>
					<li><?php echo _x('stylesheets registered using <code>wp_register_style()</code> or <code>wp_enqueue_style()</code>', 'fix level settings', 'ssl-insecure-content-fixer'); ?></li>
					<li><?php echo _x('images and other media loaded by calling <code>wp_get_attachment_image()</code>, <code>wp_get_attachment_image_src()</code>, etc.', 'fix level settings', 'ssl-insecure-content-fixer'); ?></li>
					<li><?php echo _x('data returned from <code>wp_upload_dir()</code> (e.g. for some CAPTCHA images)', 'fix level settings', 'ssl-insecure-content-fixer'); ?></li>
					<li><?php echo esc_html_x('images loaded by the plugin Image Widget', 'fix level settings', 'ssl-insecure-content-fixer'); ?></li>
				</ul>
			</li>

			<li>
				<input type="radio" name="ssl_insecure_content_fixer[fix_level]" id="fix_level_content" value="content" <?php checked($options['fix_level'], 'content'); ?> />
				<label for="fix_level_content"><?php echo esc_html_x('Content', 'fix level settings', 'ssl-insecure-content-fixer'); ?></label>
				<p class="sslfix-level-desc"><?php echo esc_html_x('Everything that Simple does, plus:', 'fix level settings', 'ssl-insecure-content-fixer'); ?></p>
				<ul class="sslfix-bullets">
					<li><?php echo esc_html_x('resources in the page content', 'fix level settings', 'ssl-insecure-content-fixer'); ?></li>
					<li><?php echo esc_html_x('resources in "Text" widgets', 'fix level settings', 'ssl-insecure-content-fixer'); ?></li>
				</ul>
			</li>

			<li>
				<input type="radio" name="ssl_insecure_content_fixer[fix_level]" id="fix_level_widgets" value="widgets" <?php checked($options['fix_level'], 'widgets'); ?> />
				<label for="fix_level_widgets"><?php echo esc_html_x('Widgets', 'fix level settings', 'ssl-insecure-content-fixer'); ?></label>
				<p class="sslfix-level-desc"><?php echo esc_html_x('Everything that Content does, plus:', 'fix level settings', 'ssl-insecure-content-fixer'); ?></p>
				<ul class="sslfix-bullets">
					<li><?php echo esc_html_x('resources in any widgets', 'fix level settings', 'ssl-insecure-content-fixer'); ?></li>
				</ul>
			</li>

			<li>
				<input type="radio" name="ssl_insecure_content_fixer[fix_level]" id="fix_level_capture" value="capture" <?php checked($options['fix_level'], 'capture'); ?> />
				<label for="fix_level_capture"><?php echo esc_html_x('Capture', 'fix level settings', 'ssl-insecure-content-fixer'); ?></label>
				<p class="sslfix-level-desc"><?php echo esc_html_x('Everything on the page, from the header to the footer:', 'fix level settings', 'ssl-insecure-content-fixer'); ?></p>
				<ul class="sslfix-bullets">
					<li><?php echo esc_html_x('capture the whole page and fix scripts, stylesheets, and other resources', 'fix level settings', 'ssl-insecure-content-fixer'); ?></li>
					<li><?php echo esc_html_x('excludes AJAX calls, which can cause compatibility and performance problems', 'fix level settings', 'ssl-insecure-content-fixer'); ?></li>
				</ul>
			</li>

			<li>
				<input type="radio" name="ssl_insecure_content_fixer[fix_level]" id="fix_level_capture_all" value="capture_all" <?php checked($options['fix_level'], 'capture_all'); ?> />
				<label for="fix_level_capture_all"><?php echo esc_html_x('Capture All', 'fix level settings', 'ssl-insecure-content-fixer'); ?></label>
				<p class="sslfix-level-desc"><?php echo esc_html_x('The biggest potential to break things, but sometimes necessary', 'fix level settings', 'ssl-insecure-content-fixer'); ?></p>
				<ul class="sslfix-bullets">
					<li><?php echo esc_html_x('capture the whole page and fix scripts, stylesheets, and other resources', 'fix level settings', 'ssl-insecure-content-fixer'); ?></li>
					<li><?php echo esc_html_x('includes AJAX calls, which can cause compatibility and performance problems', 'fix level settings', 'ssl-insecure-content-fixer'); ?></li>
				</ul>
			</li>

		</ul>
	</td>
</tr>

<tr valign="top">
	<th scope="row"><?php echo esc_html_x('Fixes for specific plugins and themes', 'plugin fix settings', 'ssl-insecure-content-fixer'); ?></th>
	<td>
		<p><em><?php echo esc_html_x('Select only the fixes your website needs.', 'plugin fix settings', 'ssl-insecure-content-fixer'); ?></em></p>
		<ul>
			<li>
				<input type="checkbox" name="ssl_insecure_content_fixer[fix_specific][lcpwp]" id="fix_specific_lcpwp" value="1" <?php checked(!empty($options['fix_specific']['lcpwp'])); ?> />
				<label for="fix_specific_lcpwp">List category posts with pagination</label>
			</li>
			<li>
				<input type="checkbox" name="ssl_insecure_content_fixer[fix_specific][woo_https]" id="fix_specific_woo_https" value="1" <?php checked(!empty($options['fix_specific']['woo_https'])); ?> />
				<label for="fix_specific_woo_https"><?php echo esc_html_x('WooCommerce  + Google Chrome HTTP_HTTPS bug (fixed in WooCommerce v2.3.13)', 'plugin fix settings', 'ssl-insecure-content-fixer'); ?></label>
			</li>
		</ul>
	</td>
</tr>

<tr valign="top">
	<th scope="row"><?php echo esc_html_x('Ignore external sites', 'ignore external settings', 'ssl-insecure-content-fixer'); ?></th>
	<td>
		<p><em><?php echo esc_html_x('Select only if you wish to leave content pointing to external sites as http', 'ignore external settings', 'ssl-insecure-content-fixer'); ?></em></p>
		<ul>
			<li>
				<input type="checkbox" name="ssl_insecure_content_fixer[site_only]" id="site_only" value="1" <?php checked(!empty($options['site_only'])); ?> />
				<label for="site_only"><?php echo esc_html_x('Only fix content pointing to this WordPress site', 'ignore external settings', 'ssl-insecure-content-fixer'); ?></label>
			</li>
		</ul>
	</td>
</tr>

<tr valign="top">
	<th scope="row"><?php echo esc_html_x('HTTPS detection', 'proxy settings', 'ssl-insecure-content-fixer'); ?><i id="sslfix-https-detection" aria-hidden="true"></i></th>
	<td>
		<p><em><?php echo esc_html_x('Select how WordPress should detect that a page is loaded via HTTPS', 'proxy settings', 'ssl-insecure-content-fixer'); ?></em></p>
		<p><?php
		$proxies = array(
			/* translators: standard WordPress function means no reverse proxy, just plain website access */
			'normal'							=> _x('standard WordPress function', 'proxy settings', 'ssl-insecure-content-fixer'),
			'HTTP_X_FORWARDED_PROTO'			=> _x('HTTP_X_FORWARDED_PROTO (e.g. load balancer, reverse proxy, NginX)', 'proxy settings', 'ssl-insecure-content-fixer'),
			'HTTP_X_FORWARDED_SSL'				=> _x('HTTP_X_FORWARDED_SSL (e.g. reverse proxy)', 'proxy settings', 'ssl-insecure-content-fixer'),
			'HTTP_CLOUDFRONT_FORWARDED_PROTO'	=> _x('HTTP_CLOUDFRONT_FORWARDED_PROTO (Amazon CloudFront HTTPS cached content)', 'proxy settings', 'ssl-insecure-content-fixer'),
			'HTTP_X_FORWARDED_SCHEME'			=> _x('HTTP_X_FORWARDED_SCHEME (e.g. KeyCDN)', 'proxy settings', 'ssl-insecure-content-fixer'),
			'HTTP_X_ARR_SSL'					=> _x('HTTP_X_ARR_SSL (Windows Azure ARR)', 'proxy settings', 'ssl-insecure-content-fixer'),
			'HTTP_CF_VISITOR'					=> _x('HTTP_CF_VISITOR (Cloudflare Flexible SSL); deprecated, since Cloudflare sends HTTP_X_FORWARDED_PROTO now', 'proxy settings', 'ssl-insecure-content-fixer'),
			'detect_fail'						=> _x('unable to detect HTTPS', 'proxy settings', 'ssl-insecure-content-fixer'),
		);

		foreach ($proxies as $value => $label) {
			$id = "proxy_fix_{$value}";

			?><input type="radio" name="ssl_insecure_content_fixer[proxy_fix]" id="<?php echo esc_attr($id); ?>"
				value="<?php echo esc_attr($value); ?>" <?php checked($options['proxy_fix'], $value); ?> />
			<label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label><br /><?php

		}
		?></p>
	</td>
</tr>

