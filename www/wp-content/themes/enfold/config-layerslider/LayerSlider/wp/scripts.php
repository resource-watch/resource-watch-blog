<?php

$lsPriority = (int) get_option('ls_scripts_priority', 3);
$lsPriority = ! empty($lsPriority) ? $lsPriority : 3;

add_action('wp_enqueue_scripts', 'layerslider_enqueue_content_res', $lsPriority);
add_action('wp_footer', 'layerslider_footer_scripts', ($lsPriority+1));
add_action('admin_enqueue_scripts', 'layerslider_enqueue_admin_res', $lsPriority);
add_action('admin_enqueue_scripts', 'ls_load_google_fonts', $lsPriority);
add_action('wp_enqueue_scripts', 'ls_load_google_fonts', ($lsPriority+1));
add_action('wp_head', 'ls_meta_generator', 9);

// Fix for CloudFlare's Rocket Loader
add_filter('script_loader_tag', 'layerslider_script_attributes', 10, 3);
function layerslider_script_attributes( $tag, $handle, $src ) {

	if(
		$handle === 'layerslider' ||
		$handle === 'layerslider-greensock' ||
		$handle === 'layerslider-transitions' ||
		$handle === 'layerslider-origami' ||
		$handle === 'layerslider-popup' ||
		$handle === 'ls-user-transitions'
	) {
		$tag = str_replace('src=', 'data-cfasync="false" src=', $tag);
	}


	return $tag;
}


function layerslider_enqueue_content_res() {

	// Include in the footer?
	$condsc = get_option('ls_conditional_script_loading', false) ? true : false;
	$footer = get_option('ls_include_at_footer', false) ? true : false;
	$footer = $condsc ? true : $footer;

	// Use Gogole CDN version of jQuery
	if(get_option('ls_use_custom_jquery', false)) {
		wp_deregister_script('jquery');
		wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', array(), '1.8.3');
	}

	// Enqueue admin front-end assets
	if( current_user_can(get_option('layerslider_custom_capability', 'manage_options')) ) {
		wp_enqueue_style('layerslider-front', LS_ROOT_URL.'/static/public/front.css', false, LS_PLUGIN_VERSION );
	}

	// Register LayerSlider resources
	wp_register_script('layerslider-greensock', LS_ROOT_URL.'/static/layerslider/js/greensock.js', false, '1.19.0', $footer );
	wp_register_script('layerslider', LS_ROOT_URL.'/static/layerslider/js/layerslider.kreaturamedia.jquery.js', array('jquery'), LS_PLUGIN_VERSION, $footer );
	wp_register_script('layerslider-transitions', LS_ROOT_URL.'/static/layerslider/js/layerslider.transitions.js', false, LS_PLUGIN_VERSION, $footer );
	wp_enqueue_style('layerslider', LS_ROOT_URL.'/static/layerslider/css/layerslider.css', false, LS_PLUGIN_VERSION );

	// LayerSlider Origami plugin
	wp_register_script('layerslider-origami', LS_ROOT_URL.'/static/layerslider/plugins/origami/layerslider.origami.js', array('jquery'), LS_PLUGIN_VERSION, $footer );
	wp_register_style('layerslider-origami', LS_ROOT_URL.'/static/layerslider/plugins/origami/layerslider.origami.css', false, LS_PLUGIN_VERSION );

	// LayerSlider Popup plugin
	wp_register_script('layerslider-popup', LS_ROOT_URL.'/static/layerslider/plugins/popup/layerslider.popup.js', array('jquery'), LS_PLUGIN_VERSION, $footer );
	wp_register_style('layerslider-popup', LS_ROOT_URL.'/static/layerslider/plugins/popup/layerslider.popup.css', false, LS_PLUGIN_VERSION );

	// 3rd-party: Font Awesome
	wp_register_style('layerslider-font-awesome', LS_ROOT_URL.'/static/font-awesome/css/font-awesome.min.css', false, LS_PLUGIN_VERSION );

	// Build LS_Meta object
	$LS_Meta = array('v' => LS_PLUGIN_VERSION);

	if( get_option('ls_gsap_sandboxing', false) ) {
		$LS_Meta['fixGSAP'] = true;
	}

	// Print LS_Meta object
	wp_localize_script('layerslider-greensock', 'LS_Meta', $LS_Meta);

	// User resources
	$uploads = wp_upload_dir();
	$uploads['baseurl'] = set_url_scheme( $uploads['baseurl'] );

	if(file_exists($uploads['basedir'].'/layerslider.custom.transitions.js')) {
		wp_register_script('ls-user-transitions', $uploads['baseurl'].'/layerslider.custom.transitions.js', false, LS_PLUGIN_VERSION, $footer );
	}

	if(file_exists($uploads['basedir'].'/layerslider.custom.css')) {
		wp_enqueue_style('ls-user', $uploads['baseurl'].'/layerslider.custom.css', false, LS_PLUGIN_VERSION );
	}

	if( ! $footer) {
		wp_enqueue_script('layerslider-greensock');
		wp_enqueue_script('layerslider');
		wp_enqueue_script('layerslider-transitions');
		wp_enqueue_script('ls-user-transitions');
	}
}



function layerslider_footer_scripts() {

	$condsc = get_option('ls_conditional_script_loading', false) ? true : false;

	if( ! $condsc || ! empty( $GLOBALS['lsSliderInit'] ) ) {

		// Enqueue scripts
		wp_print_scripts('layerslider-greensock');
		wp_print_scripts('layerslider');
		wp_print_scripts('layerslider-transitions');

		if(wp_script_is('ls-user-transitions', 'registered')) {
			wp_print_scripts('ls-user-transitions');
		}
	}

	// Conditionally load LayerSlider plugins
	if( ! empty( $GLOBALS['lsLoadPlugins'] ) ) {

		// Filter out duplicates
		$GLOBALS['lsLoadPlugins'] = array_unique($GLOBALS['lsLoadPlugins']);

		// Load plugins
		foreach( $GLOBALS['lsLoadPlugins'] as $item ) {
			wp_print_scripts('layerslider-'.$item);
			wp_print_styles('layerslider-'.$item);
		}
	}


	// Load used fonts
	if( ! empty( $GLOBALS['lsLoadFonts'] ) ) {

		// Filter out duplicates
		$GLOBALS['lsLoadFonts'] = array_unique($GLOBALS['lsLoadFonts']);

		// Load fonts
		foreach( $GLOBALS['lsLoadFonts'] as $item ) {
			wp_print_styles('layerslider-'.$item);
		}
	}


	if( ! empty( $GLOBALS['lsSliderInit'] ) ) {
		echo implode('', $GLOBALS['lsSliderInit']);
	}
}



function layerslider_enqueue_admin_res() {

	// Load global LayerSlider CSS
	wp_enqueue_style('layerslider-global', LS_ROOT_URL.'/static/admin/css/global.css', false, LS_PLUGIN_VERSION );

	// Load global LayerSlider JS
	include LS_ROOT_PATH.'/wp/tinymce_l10n.php';
	wp_enqueue_script('layerslider-global', LS_ROOT_URL.'/static/admin/js/ls-admin-global.js', false, LS_PLUGIN_VERSION );
	wp_localize_script('layerslider-global', 'LS_MCE_l10n', $l10n_ls_mce);


	// Embed CSS. Hides the admin menu bar and the sidebar.
	if( ! empty( $_GET['ls-embed'] ) ) {
		wp_enqueue_style('layerslider-embed', LS_ROOT_URL.'/static/admin/css/embed.css', false, LS_PLUGIN_VERSION);
	}

	// Use Gogole CDN version of jQuery
	if(get_option('ls_use_custom_jquery', false)) {
		wp_deregister_script('jquery');
		wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', array(), '1.8.3');
	}

	// Load LayerSlider-only resources
	$screen = get_current_screen();

	if( strpos( $screen->base, 'layerslider' ) !== false ) {

		// New Media Library
		if( function_exists( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

		// Load some bundled WP resources
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		wp_enqueue_script('wp-pointer');
		wp_enqueue_style('wp-pointer');

		// Dashicons
		if( version_compare( get_bloginfo('version'), '3.8', '<') ) {
			wp_enqueue_style('dashicons', LS_ROOT_URL.'/static/dashicons/dashicons.css', false, LS_PLUGIN_VERSION );
		}

		// Global scripts & stylesheets
		wp_enqueue_script('layerslider-greensock', LS_ROOT_URL.'/static/layerslider/js/greensock.js', false, '1.18.0' );
		wp_enqueue_script('kreaturamedia-ui', LS_ROOT_URL.'/static/admin/js/km-ui.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_script('ls-admin-global', LS_ROOT_URL.'/static/admin/js/ls-admin-common.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_style('layerslider-admin', LS_ROOT_URL.'/static/admin/css/admin.css', false, LS_PLUGIN_VERSION );
		wp_enqueue_style('layerslider-admin-new', LS_ROOT_URL.'/static/admin/css/admin_new.css', false, LS_PLUGIN_VERSION );
		wp_enqueue_style('kreaturamedia-ui', LS_ROOT_URL.'/static/admin/css/km-ui.css', false, LS_PLUGIN_VERSION );

		// 3rd-party: Font Awesome
		wp_enqueue_style('layerslider-font-awesome', LS_ROOT_URL.'/static/font-awesome/css/font-awesome.min.css', false, LS_PLUGIN_VERSION );

		// 3rd-party: CodeMirror
		wp_enqueue_style('codemirror', LS_ROOT_URL.'/static/codemirror/lib/codemirror.css', false, LS_PLUGIN_VERSION );
		wp_enqueue_script('codemirror', LS_ROOT_URL.'/static/codemirror/lib/codemirror.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_style('codemirror-solarized', LS_ROOT_URL.'/static/codemirror/theme/solarized.mod.css', false, LS_PLUGIN_VERSION );
		wp_enqueue_script('codemirror-syntax-css', LS_ROOT_URL.'/static/codemirror/mode/css/css.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_script('codemirror-syntax-javascript', LS_ROOT_URL.'/static/codemirror/mode/javascript/javascript.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_script('codemirror-foldcode', LS_ROOT_URL.'/static/codemirror/addon/fold/foldcode.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_script('codemirror-foldgutter', LS_ROOT_URL.'/static/codemirror/addon/fold/foldgutter.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_script('codemirror-brace-fold', LS_ROOT_URL.'/static/codemirror/addon/fold/brace-fold.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_script('codemirror-active-line', LS_ROOT_URL.'/static/codemirror/addon/selection/active-line.js', array('jquery'), LS_PLUGIN_VERSION );

		// Localize admin scripts
		include LS_ROOT_PATH.'/wp/scripts_l10n.php';
		wp_localize_script('ls-admin-global', 'LS_l10n', $l10n_ls);


		// Settings Page
		if( strpos( $screen->base, 'layerslider-options' ) !== false ) {

			// Avoid PHP undef notice
			$section = ! empty( $_GET['section'] ) ? $_GET['section'] : false;

			switch( $section ) {

				case 'about':
					wp_enqueue_style('ls-about-page', LS_ROOT_URL.'/static/admin/css/about.css', false, LS_PLUGIN_VERSION );
					break;

				case 'skin-editor':
				case 'css-editor':
					wp_enqueue_style('ls-skin-editor', LS_ROOT_URL.'/static/admin/css/skin.editor.css', false, LS_PLUGIN_VERSION );
					break;


				case 'transition-builder':
					ls_require_builder_assets();
					wp_enqueue_script('layerslider_tr_builder', LS_ROOT_URL.'/static/admin/js/ls-admin-transition-builder.js', array('jquery'), LS_PLUGIN_VERSION );
					break;

				default:
					wp_enqueue_script('layerslider-settings', LS_ROOT_URL.'/static/admin/js/ls-admin-settings.js', array('jquery'), LS_PLUGIN_VERSION );
					wp_enqueue_style('layerslider-settings', LS_ROOT_URL.'/static/admin/css/plugin_settings.css', false, LS_PLUGIN_VERSION );
					break;
			}



		// Add-Ons Page
		} elseif( strpos( $screen->base, 'layerslider-addons' ) !== false ) {
			wp_enqueue_script('layerslider-addons', LS_ROOT_URL.'/static/admin/js/ls-admin-addons.js', array('jquery'), LS_PLUGIN_VERSION );
			wp_enqueue_style('layerslider-addons', LS_ROOT_URL.'/static/admin/css/addons.css', false, LS_PLUGIN_VERSION );

			wp_enqueue_style('ls-revisions', LS_ROOT_URL.'/static/admin/css/revisions.css', false, LS_PLUGIN_VERSION );
			wp_enqueue_script('ls-revisions', LS_ROOT_URL.'/static/admin/js/ls-admin-revisions.js', array('jquery'), LS_PLUGIN_VERSION );

			if( ! empty( $_GET['section'] ) && $_GET['section'] === 'revisions' ) {
				ls_require_builder_assets();
			}

		// Sliders list page
		} elseif( empty( $_GET['action'] ) ) {
			wp_enqueue_script('ls-admin-sliders', LS_ROOT_URL.'/static/admin/js/ls-admin-sliders.js', array('jquery'), LS_PLUGIN_VERSION );
			wp_enqueue_script('ls-shuffle', LS_ROOT_URL.'/static/shuffle/shuffle.min.js', array('jquery'), LS_PLUGIN_VERSION );

		// Slider & Transition Builder
		} else {
			ls_require_builder_assets();
		}
	}
}


function ls_require_builder_assets() {

	// Load some bundled WP resources
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-selectable');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-resizable');
	wp_enqueue_script('jquery-ui-slider');

	wp_register_script('layerslider-admin', LS_ROOT_URL.'/static/admin/js/ls-admin-slider-builder.js', array('jquery', 'json2'), LS_PLUGIN_VERSION );

	//  Don't load automatically the Slider Builder JS file other than the Slider Builder itself.
	if( empty( $_GET['section'] ) ) {
		wp_enqueue_script('layerslider-admin');
	}

	// LayerSlider includes for preview
	wp_enqueue_script('layerslider', LS_ROOT_URL.'/static/layerslider/js/layerslider.kreaturamedia.jquery.js', array('jquery'), LS_PLUGIN_VERSION );
	wp_enqueue_script('layerslider-transitions', LS_ROOT_URL.'/static/layerslider/js/layerslider.transitions.js', false, LS_PLUGIN_VERSION );
	wp_enqueue_script('layerslider-tr-gallery', LS_ROOT_URL.'/static/admin/js/layerslider.transition.gallery.js', array('jquery'), LS_PLUGIN_VERSION );
	wp_enqueue_style('layerslider', LS_ROOT_URL.'/static/layerslider/css/layerslider.css', false, LS_PLUGIN_VERSION );
	wp_enqueue_style('layerslider-tr-gallery', LS_ROOT_URL.'/static/admin/css/layerslider.transitiongallery.css', false, LS_PLUGIN_VERSION );

	// LayerSlider Timeline plugin
	wp_enqueue_script('layerslider-timeline', LS_ROOT_URL.'/static/layerslider/plugins/timeline/layerslider.timeline.js', array('jquery'), LS_PLUGIN_VERSION );
	wp_enqueue_style('layerslider-timeline', LS_ROOT_URL.'/static/layerslider/plugins/timeline/layerslider.timeline.css', false, LS_PLUGIN_VERSION );

	// LayerSlider Origami plugin
	wp_enqueue_script('layerslider-origami', LS_ROOT_URL.'/static/layerslider/plugins/origami/layerslider.origami.js', array('jquery'), LS_PLUGIN_VERSION );
	wp_enqueue_style('layerslider-origami', LS_ROOT_URL.'/static/layerslider/plugins/origami/layerslider.origami.css', false, LS_PLUGIN_VERSION );

	// LayerSlider Popup plugin
	wp_enqueue_script('layerslider-popup', LS_ROOT_URL.'/static/layerslider/plugins/popup/layerslider.popup.js', array('jquery'), LS_PLUGIN_VERSION );
	wp_enqueue_style('layerslider-popup', LS_ROOT_URL.'/static/layerslider/plugins/popup/layerslider.popup.css', false, LS_PLUGIN_VERSION );

	// 3rd-party: MiniColor
	wp_enqueue_script('minicolor', LS_ROOT_URL.'/static/minicolors/jquery.minicolors.js', array('jquery'), LS_PLUGIN_VERSION );
	wp_enqueue_style('minicolor', LS_ROOT_URL.'/static/minicolors/jquery.minicolors.css', false, LS_PLUGIN_VERSION );

	// 3rd-party: CC Image Editor
	wp_enqueue_script('cc-image-sdk', 'https://dme0ih8comzn4.cloudfront.net/imaging/v3/editor.js', false, LS_PLUGIN_VERSION );

	// 3rd-party: Air Datepicker
	wp_enqueue_style('air-datepicker', LS_ROOT_URL.'/static/air-datepicker/datepicker.min.css', false, '2.1.0' );
	wp_enqueue_script('air-datepicker', LS_ROOT_URL.'/static/air-datepicker/datepicker.min.js', array('jquery'), '2.1.0' );
	wp_enqueue_script('air-datepicker-en', LS_ROOT_URL.'/static/air-datepicker/i18n/datepicker.en.js', array('jquery'), '2.1.0' );


	// User CSS
	$uploads = wp_upload_dir();
	$uploads['baseurl'] = set_url_scheme( $uploads['baseurl'] );

	if(file_exists($uploads['basedir'].'/layerslider.custom.transitions.js')) {
		wp_enqueue_script('ls-user-transitions', $uploads['baseurl'].'/layerslider.custom.transitions.js', false, LS_PLUGIN_VERSION );
	}

	// User transitions
	if(file_exists($uploads['basedir'].'/layerslider.custom.css')) {
		wp_enqueue_style('ls-user', $uploads['baseurl'].'/layerslider.custom.css', false, LS_PLUGIN_VERSION );
	}
}



function ls_load_google_fonts() {

	// Get font list
	$fonts = get_option('ls-google-fonts', array());
	$scripts = get_option('ls-google-font-scripts', array('latin', 'latin-ext'));

	// Check fonts if any
	if(!empty($fonts) && is_array($fonts)) {
		$lsFonts = array();
		foreach($fonts as $item) {
			if( is_admin() || !$item['admin'] ) {
				$lsFonts[] = htmlspecialchars($item['param']);
			}
		}

		if(!empty($lsFonts)) {
			$lsFonts = implode('%7C', $lsFonts);
			$protocol = is_ssl() ? 'https' : 'http';
			$query_args = array(
				'family' => $lsFonts,
				'subset' => implode('%2C', $scripts),
			);

			wp_enqueue_style('ls-google-fonts',
				add_query_arg($query_args, "$protocol://fonts.googleapis.com/css" ),
				array(), null
			);
		}
	}
}

function ls_meta_generator() {
	$str = '<meta name="generator" content="Powered by LayerSlider '.LS_PLUGIN_VERSION.' - Multi-Purpose, Responsive, Parallax, Mobile-Friendly Slider Plugin for WordPress." />' . NL;
	$str.= '<!-- LayerSlider updates and docs at: https://layerslider.kreaturamedia.com -->' . NL;

	echo apply_filters('ls_meta_generator', $str);
}