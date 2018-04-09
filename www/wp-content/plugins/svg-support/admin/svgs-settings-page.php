<div class="wrap">

	<div id="icon-upload" class="icon32"></div>
	<h2><?php _e( 'SVG Support Settings and Usage', 'svg-support' ); ?><span class="svgs-version">Version <?php global $svgs_plugin_version; echo $svgs_plugin_version; ?></span></h2>

	<div id="poststuff">

		<div class="meta-box-sortables ui-sortable">

			<div class="postbox">

				<h3><span><?php _e( 'Introduction', 'svg-support' ); ?></span></h3>
				<div class="inside">

					<p><?php _e( 'When using SVG images on your WordPress site, it can be hard to style elements within the SVG using CSS. <strong>Now you can, easily!</strong>', 'svg-support' ); ?></p>
					<p><?php _e( 'When you enable advanced mode, this plugin not only provides SVG Support like the name says, it also allows you to easily embed your full SVG file\'s code using a simple IMG tag. By adding the class <code>style-svg</code> to your IMG elements, this plugin dynamically replaces any IMG elements containing the <code>style-svg</code> class with your complete SVG.', 'svg-support' ); ?></p>
					<p><?php _e( 'The main purpose of this is to allow styling of SVG elements. Usually your styling options are restricted when using <code>embed</code>, <code>object</code> or <code>img</code> tags alone.', 'svg-support' ); ?></p>
					<p><strong><?php _e( 'Now with featured image support for inlining your featured images!</strong> (since 2.3)', 'svg-support' ); ?></p>

				</div> <!-- .inside -->

			</div> <!-- .postbox -->

		</div> <!-- .meta-box-sortables .ui-sortable -->

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">

						<h3><span><?php _e( 'Settings', 'svg-support' ); ?></span></h3>
						<div class="inside">

							<form name="bodhi_svgs_settings_form" method="post" action="options.php">

								<?php settings_fields('bodhi_svgs_settings_group'); ?>

								<table class="form-table svg-settings">

									<tr valign="top" class="svgs-simple">
										<!-- simple mode selector -->
										<th scope="row">
											<label for="bodhi_svgs_settings[advanced_mode]"><strong><?php _e( 'Enable Advanced Mode?', 'svg-support' ); ?></strong></label>
										</th>
										<td>
											<?php printf(
												'<input id="bodhi_svgs_settings[advanced_mode]" name="bodhi_svgs_settings[advanced_mode]" type="checkbox" %2$s />', 'bodhi_svgs_settings_advanced_mode', checked( isset( $bodhi_svgs_options['advanced_mode'] ), true, false ) ); ?>
											<?php _e( 'Yes', 'svg-support' ); ?><br /><small class="description"><?php _e(' You don\'t need to enable this to simply use SVG files as images. Enabling this will trigger advanced options and SVG functionality such as inline rendering.', 'svg-support' ); ?></small>
										</td>
									</tr>

									<tr valign="top">
										<!-- Swap with future feature: Multiselect Roles -->
										<th scope="row">
											<label for="bodhi_svgs_settings[restrict]"><strong><?php _e( 'Restrict to Administrators?', 'svg-support' ); ?></strong></label>
										</th>
										<td>
											<?php printf(
												'<input id="bodhi_svgs_settings[restrict]" name="bodhi_svgs_settings[restrict]" type="checkbox" %2$s />', 'bodhi_svgs_settings_restrict', checked( isset( $bodhi_svgs_options['restrict'] ), true, false ) ); ?>
											<?php _e( 'Yes', 'svg-support' ); ?><br /><small class="description"><?php _e(' Restricts SVG upload priveledges to Administrators.', 'svg-support' ); ?></small>
										</td>
									</tr>

									<tr valign="top" class="svgs-advanced">
										<!-- Allow choice of js in footer true or false -->
										<th scope="row">
											<label for="bodhi_svgs_settings[js_foot_choice]"><strong><?php _e( 'Output JS in Footer?', 'svg-support' ); ?></strong></label>
										</th>
										<td>
											<?php printf(
												'<input id="bodhi_svgs_settings[js_foot_choice]" name="bodhi_svgs_settings[js_foot_choice]" type="checkbox" %2$s />', 'bodhi_svgs_settings_js_foot_choice', checked( isset( $bodhi_svgs_options['js_foot_choice'] ), true, false ) ); ?>
											<?php _e( 'Yes', 'svg-support' ); ?><br /><small class="description"><?php _e(' Normally, scripts are placed in <code>head</code> of the HTML document. If this parameter is true, the script is placed before the closing <code>body</code> tag. This requires the theme to have the <code>wp_footer()</code> template tag in the appropriate place.', 'svg-support' ); ?></small>
										</td>
									</tr>

									<tr valign="top" class="svgs-advanced">
										<!-- Select whether to use minified or expanded JS file  -->
										<th scope="row">
											<label for="bodhi_svgs_settings[use_expanded_js]"><strong><?php _e( 'Use Expanded JS?', 'svg-support' ); ?></strong></label>
										</th>
										<td>
											<?php printf(
												'<input id="bodhi_svgs_settings[use_expanded_js]" name="bodhi_svgs_settings[use_expanded_js]" type="checkbox" %2$s />', 'bodhi_svgs_settings_use_expanded_js', checked( isset( $bodhi_svgs_options['use_expanded_js'] ), true, false ) ); ?>
											<?php _e( 'Yes', 'svg-support' ); ?><br /><small class="description"><?php _e(' Checking this will use the expanded JS file instead of the minified JS file. Useful if you want to minify this externally using a caching plugin or similar.', 'svg-support' ); ?></small>
										</td>
									</tr>

									<tr valign="top" class="svgs-advanced">
										<!-- Automatically insert class to target in images when inserting into posts/pages from admin edit screen -->
										<th scope="row">
											<label for="bodhi_svgs_settings[auto_insert_class]"><strong><?php _e( 'Automatically insert class?', 'svg-support' ); ?></strong></label>
										</th>
										<td>
											<?php printf(
												'<input id="bodhi_svgs_settings[auto_insert_class]" name="bodhi_svgs_settings[auto_insert_class]" type="checkbox" %2$s />', 'bodhi_svgs_settings_auto_insert_class', checked( isset( $bodhi_svgs_options['auto_insert_class'] ), true, false ) ); ?>
											<?php _e( 'Yes', 'svg-support' ); ?><br /><small class="description"><?php _e(' Checking this will make sure that either the default class or the custom one you set below is inserted into the style attributes of <code>img</code> tags when you insert SVG images. Additionally, it will remove all of the default WordPress classes. It will leave normal image types as default and only affect SVG files.', 'svg-support' ); ?></small>
										</td>
									</tr>

									<tr valign="top" class="svgs-advanced">
										<!-- Custom CSS target field so users can set their own class to target -->
										<th scope="row">
											<label for="bodhi_svgs_settings[css_target]"><strong><?php _e( 'CSS Class to target', 'svg-support' ); ?></strong></label>
										</th>
										<td>
											<input id="bodhi_svgs_settings[css_target]" class="all-options code" name="bodhi_svgs_settings[css_target]" type="text" value="<?php if( isset( $bodhi_svgs_options['css_target'] ) ) echo $bodhi_svgs_options['css_target']; ?>"><br /><small class="description"><?php _e( 'The default target class is <code>style-svg</code>. You can change it to your own class such as <code>my-class</code> by typing it here.<br />Leave blank to use the default class.', 'svg-support' ); ?></small>
										</td>
									</tr>

									<tr valign="top" class="svgs-advanced">
										<!-- Automatically insert class to target in images on front end page via jQuery -->
										<th scope="row">
											<label for="bodhi_svgs_settings[force_inline_svg]"><strong><?php _e( 'Force Inline SVG?', 'svg-support' ); ?></strong></label>
										</th>
										<td>
											<?php printf(
												'<input id="bodhi_svgs_settings[force_inline_svg]" name="bodhi_svgs_settings[force_inline_svg]" type="checkbox" %2$s />', 'bodhi_svgs_settings_force_inline_svg', checked( isset( $bodhi_svgs_options['force_inline_svg'] ), true, false ) ); ?>
											<?php _e( 'Yes', 'svg-support' ); ?><br /><small class="description"><?php _e(' <strong>Use with caution!</strong> Checking this will automatically add the SVG class to ALL image tags containing SVG file sources in the rendered HTML via javascript and will therefore render all of your SVG files inline.<br /><em>Use case scenario: When using a visual builder such as in the Divi Theme or The Divi Builder, the class is not automatically added with the "Automatically insert class?" option selected or the builder module doesn\'t give you the option to manually add a CSS class directly to your image.</em>', 'svg-support' ); ?></small>
										</td>
									</tr>

								</table>

								<p>
									<input class="button-primary" type="submit" name="bodhi_svgs_settings_submit" value="<?php _e( 'Save Changes', 'svg-support' ); ?>" />
								</p>

							</form>

						</div> <!-- .inside -->

					</div> <!-- .postbox -->

					<div class="postbox">

						<?php

						if ( empty( $bodhi_svgs_options['advanced_mode'] ) ) {
							echo '<h3><span>';
							_e( 'Usage', 'svg-support' );
							echo '</span></h3>';
						} else {
							echo '<h3><span>';
							_e( 'Advanced Usage', 'svg-support' );
							echo '</span></h3>';
						}

						?>

						<div class="inside">

							<p><?php _e( 'You can simply upload SVG files to your media library like any other image. Make sure to select "Restrict to Administrators" if you only want to allow admins to upload SVG files.', 'svg-support' ); ?></p>

							<div class="svgs-advanced">
								<p><?php _e( 'Now, embed your SVG image like a standard image with the addition of adding the class <code>style-svg</code> (or your custom class from above) to any IMG tags that you want this plugin to swap out with your actual SVG code.', 'svg-support' ); ?><br />
								<?php _e( 'For example:', 'svg-support' ); ?></p>

								<pre><code>&lt;img class="style-svg" alt="alt-text" src="image-source.svg" /&gt;</code></pre>
								<?php _e( 'or', 'svg-support' ); ?>
								<pre><code>&lt;img class="your-custom-class" alt="alt-text" src="image-source.svg" /&gt;</code></pre>

								<p><?php _e( 'The whole IMG tag element will now be dynamically replaced by the actual code of your SVG, making the inner content targetable.', 'svg-support' ); ?><br />
								<?php _e( 'This allows you to target elements within your SVG using CSS.', 'svg-support' ); ?></p>

								<p><em><?php _e( 'Please Note:', 'svg-support' ); ?></em>
								<br><em><?php _e( '- You will need to set your own height and width in your CSS for SVG files to display correctly.', 'svg-support' ); ?></em>
								<br><em><?php _e( '- Your uploaded image needs to be an SVG file for this plugin to replace the img tag with the inline SVG code. It will not create SVG files for you.', 'svg-support' ); ?></em></p>
							</div>

						</div> <!-- .inside -->

					</div> <!-- .postbox -->

					<div class="postbox">
						<h3><span><?php _e( 'Compress and Optimize Images with ShortPixel', 'svg-support' ); ?></span></h3>
						<div class="inside">
							<?php echo '<a target="_blank" class="shortpixel-logo" href="https://shortpixel.com/h/af/OLKMLXE207471"><img src="' . BODHI_SVGS_PLUGIN_URL . '/admin/img/shortpixel.png" /></a>'; ?>
							<p><?php _e( 'Now that you\'ve set up SVG Support on your site, it\'s time to look at optimizing your existing images (jpg & png).', 'svg-support' ); ?></p>
							<p><?php _e( 'ShortPixel improves website performance by reducing the size of your images. The results are no different in quality from the original, plus your originals are stored in a backup folder for you.', 'svg-support' ); ?></p>
							<p><a class="shortpixel-button button-primary" href="https://shortpixel.com/h/af/OLKMLXE207471"><?php _e( 'Try ShortPixel WordPress Plugin for FREE', 'svg-support' ); ?></a></p>
						</div> <!-- .inside -->
					</div> <!-- .postbox -->

					<div class="postbox">
						<h3><span><?php _e( 'Animate and Optimize your SVG files using these open source projects', 'svg-support' ); ?></span></h3>
						<div class="inside">
							<p><a href="https://maxwellito.github.io/vivus-instant/" target="_blank">Vivus Instant for SVG animation</a> <?php _e( 'Upload your SVG files and use the tools provided to animate strokes.', 'svg-support' ); ?></p>
							<p><a href="https://jakearchibald.github.io/svgomg/" target="_blank">SVGOMG for SVG optimisation</a> <?php _e( 'An online tool to optimize your SVG files.', 'svg-support' ); ?></p>
						</div> <!-- .inside -->
					</div> <!-- .postbox -->

				</div> <!-- .meta-box-sortables .ui-sortable -->

			</div> <!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

					<div class="postbox">
						<h3><span><?php _e( 'Ratings & Reviews', 'svg-support' ); ?></span></h3>
						<div class="inside">
							<p><?php _e( 'If you like <strong>SVG Support</strong> please consider leaving a', 'svg-support' ); ?> <a href="https://wordpress.org/support/view/plugin-reviews/svg-support?filter=5#postform" target="_blank" class="svgs-rating-link">&#9733;&#9733;&#9733;&#9733;&#9733;</a> <?php _e( 'rating.', 'svg-support' ); ?><br><?php _e( 'A huge thanks in advance!', 'svg-support' ); ?></p>
							<p><a href="https://wordpress.org/support/view/plugin-reviews/svg-support?filter=5#postform" target="_blank" class="button-primary">Leave a rating</a></p>
						</div> <!-- .inside -->
					</div> <!-- .postbox -->

					<div class="postbox">
						<h3><span><?php _e( 'SVG Support Features', 'svg-support' ); ?></span></h3>
						<div class="inside">
							<ul>
								<li><strong><?php _e( 'Basic Use', 'svg-support' ); ?></strong></li>
								<li><?php _e( 'SVG Support for your media library', 'svg-support' ); ?></li>
								<li><?php _e( 'Restrict to Administrators only', 'svg-support' ); ?></li>
								<hr>
								<li><strong><?php _e( 'Advanced Mode', 'svg-support' ); ?></strong></li>
								<li><?php _e( 'Style SVG elements using CSS', 'svg-support' ); ?></li>
								<li><?php _e( 'Animate SVG using CSS or JS', 'svg-support' ); ?></li>
								<li><?php _e( 'Include multiple URL\'s inside single SVG', 'svg-support' ); ?></li>
								<li><?php _e( 'Use odd shapes as links', 'svg-support' ); ?></li>
								<li><?php _e( 'Inline SVG featured image support', 'svg-support' ); ?></li>
								<li><?php _e( 'Force all SVG files to be rendered inline', 'svg-support' ); ?></li>
							</ul>
						</div> <!-- .inside -->
					</div> <!-- .postbox -->

					<div class="postbox">
						<h3><span><?php _e( 'About The Plugin', 'svg-support' ); ?></span></h3>
						<div class="inside">
							<p><?php _e( 'You can read about this plugin in detail on', 'svg-support' ); ?> <a target="_blank" href="http://wordpress.org/plugins/svg-support/"><?php _e( 'The WordPress Plugin Repository', 'svg-support' ); ?></a>.</p>
							<p><?php _e( 'Need help?', 'svg-support' ); ?> <a target="_blank" href="http://wordpress.org/support/plugin/svg-support"><?php _e( 'Visit Support', 'svg-support' ); ?></a>.</p>
							<p><a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Z9R7JERS82EQQ"><?php _e( 'Donate to author &rarr;', 'svg-support' ); ?></a>
							<p>&copy; <a target="_blank" href="http://benbodhi.com">Benbodhi</a> <?php _e( 'from', 'svg-support' ); ?> <a target="_blank" href="http://gowebben.com">GoWebben</a>.
						</div> <!-- .inside -->
					</div> <!-- .postbox -->

					<div class="postbox">
						<h3><span><?php _e( 'GoWebben Hosting', 'svg-support' ); ?></span></h3>
						<div class="inside">
							<p><a target="_blank" href="https://secure.gowebben.com/cart.php?promocode=svg-support"><?php _e( 'Claim your FREE $25 credit from', 'svg-support' ); ?> GoWebben</a> - <?php _e( 'No catch, just free credit for using this plugin! It will be applied automatically using the link provided, but in any case you can simply use code: SVGSUPPORT during checkout.', 'svg-support' ); ?></p>
						</div> <!-- .inside -->
					</div> <!-- .postbox -->

				</div> <!-- .meta-box-sortables -->

			</div> <!-- #postbox-container-1 .postbox-container -->

		</div> <!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div> <!-- #poststuff -->

</div> <!-- .wrap -->
