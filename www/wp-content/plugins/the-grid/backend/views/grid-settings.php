<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

// image size options
$size1_w = get_option('the_grid_size1_width', 500);
$size1_h = get_option('the_grid_size1_height', 500);
$size1_c = get_option('the_grid_size1_crop', true);
$size2_w = get_option('the_grid_size2_width', 500);
$size2_h = get_option('the_grid_size2_height', 1000);
$size2_c = get_option('the_grid_size2_crop', true);
$size3_w = get_option('the_grid_size3_width', 1000);
$size3_h = get_option('the_grid_size3_height', 500);
$size3_c = get_option('the_grid_size3_crop', true);
$size4_w = get_option('the_grid_size4_width', 1000);
$size4_h = get_option('the_grid_size4_height', 1000);
$size4_c = get_option('the_grid_size4_crop', true);
$size5_w = get_option('the_grid_size5_width', 500);
$size5_h = get_option('the_grid_size5_height', 99999);
$size5_c = get_option('the_grid_size5_crop', '');


$force_register = get_option('the_grid_force_registration', '');

// other options
$lightbox       = get_option('the_grid_lightbox', 'the_grid');
$lb_bg          = get_option('the_grid_ligthbox_background', 'rgba(0,0,0,0.8)');
$lb_color       = get_option('the_grid_ligthbox_color', '#ffffff');
$lb_autoplay    = get_option('the_grid_ligthbox_autoplay', '');
$media          = get_option('the_grid_mediaelement', '');
$media_css      = get_option('the_grid_mediaelement_css', '');
$debounce       = get_option('the_grid_debounce', '');
$debug_mode     = get_option('the_grid_debug', false);
$post_formats   = get_option('the_grid_post_formats', false);
$global_library = get_option('the_grid_global_library', true);
$caching        = get_option('the_grid_caching', false);

$dark_title  = get_option('the_grid_dark_title', '#444444');
$dark_text   = get_option('the_grid_dark_text', '#777777');
$dark_span   = get_option('the_grid_dark_span', '#999999');
$light_title = get_option('the_grid_light_title', '#ffffff');
$light_text  = get_option('the_grid_light_text', '#f6f6f6');
$light_span  = get_option('the_grid_light_span', '#f5f5f5');

$meta_data   = get_option('the_grid_custom_meta_data', '');

$px     = __( 'px', 'tg-text-domain' );
$width  = __( 'width:', 'tg-text-domain' );
$height = __( 'height:', 'tg-text-domain' );
$crop   = __( 'crop images:', 'tg-text-domain' );

/*****************************
OPTIONS HOLDER START
******************************/
$form  = '<div class="tomb-menu-options settings-options metabox-holder">';
$form .= '<div class="inside">';

/*****************************
LI TABS
******************************/

$form .= '<ul class="tomb-tabs-holder">';
	$form .= '<li class="tomb-tab selected" data-target="general"><i class="tomb-icon dashicons dashicons-admin-generic"></i>'. __( 'General', 'tg-text-domain' ) .'</li>';
	$form .= '<li class="tomb-tab" data-target="image-size"><i class="tomb-icon dashicons dashicons-format-image"></i>'. __( 'Image Sizes', 'tg-text-domain' ) .'</li>';
	$form .= '<li class="tomb-tab" data-target="colors"><i class="tomb-icon dashicons dashicons-admin-appearance"></i>'. __( 'Colors', 'tg-text-domain' ) .'</li>';
	$form .= '<li class="tomb-tab" data-target="lightbox"><i class="tomb-icon dashicons dashicons-search"></i>'. __( 'LightBox', 'tg-text-domain' ) .'</li>';
	$form .= '<li class="tomb-tab" data-target="meta-data"><i class="tomb-icon dashicons dashicons-clipboard"></i>'. __( 'Meta Data', 'tg-text-domain' ) .'</li>';
	$form .= '<li class="tomb-tab" data-target="social-api"><i class="tomb-icon dashicons dashicons-share-alt2"></i>'. __( 'Social API', 'tg-text-domain' ) .'</li>';
$form .= '</ul>';

/*****************************
GENERAL TAB
******************************/
$form .= '<div class="tomb-tab-content general tomb-tab-show">';

	// Post formats
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Post Formats', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
		$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
		$form .= '<label class="tomb-label tomb-label-outside">'. __( 'Enable post formats on any post type', 'tg-text-domain' ) .'</label>';
		$form .= '<div class="tomb-row tomb-type-checkbox tomb-field">';
			$form .= '<p class="tomb-desc">'. __( 'This options allows to add post formats options on any post types.', 'tg-text-domain' ). '<br>'. __( 'You should check this option if your theme doesn\'t handle post formats options.', 'tg-text-domain' ) .'<br>'. __( 'Learn more about post formats in Wordpress:', 'tg-text-domain' ) .' <a href="https://en.support.wordpress.com/posts/post-formats/" target="_blank">'. __( 'Post Formats', 'tg-text-domain' ) .'</a></p>';
			$form .= '<div class="tomb-switch">';
				$form .= '<input type="checkbox" class="tomb-checkbox" name="the_grid_post_formats" id="the_grid_post_formats" data-default="" '.checked(!empty($post_formats), 1, false).'>';
				$form .= '<label for="the_grid_post_formats"></label>';
			$form .= '</div>';
		$form .= '</div>';
	$form .= '</div>';
	
	$form .= '<div class="tomb-clearfix"></div>';
	
	// Include The Grid Library Globally
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Load Library', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
		$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
		$form .= '<label class="tomb-label tomb-label-outside">'. __( 'Include The Grid library globally', 'tg-text-domain' ) .'</label>';
		$form .= '<div class="tomb-row tomb-type-checkbox tomb-field">';
			$form .= '<p class="tomb-desc">'. __( 'If enabled, CSS and JS files of The Grid will be loaded in all pages.', 'tg-text-domain' ). '<br>'. __( 'If disabled, CSS and JS files of The Grid will be only loaded on pages where the_grid shortcode exists.', 'tg-text-domain' ) .'</p>';
			$form .= '<div class="tomb-switch">';
				$form .= '<input type="checkbox" class="tomb-checkbox" name="the_grid_global_library" id="the_grid_global_library" data-default="true" '.checked(!empty($global_library), 1, false).'>';
				$form .= '<label for="the_grid_global_library"></label>';
			$form .= '</div>';
		$form .= '</div>';
	$form .= '</div>';

	$form .= '<div class="tomb-clearfix"></div>';
	
	// Mediaelement
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Medialement', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
		$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
		$form .= '<label class="tomb-label tomb-label-outside">'. __( 'Mediaelement', 'tg-text-domain' ) .'</label>';
		$form .= '<div class="tomb-row tomb-field">';
			$form .= '<p class="tomb-desc">'. __( 'Use mediaelement for HTML audio (mp3/ogg), video (mp4/ogv/webm) elements in the grid.', 'tg-text-domain' ) .'<br>'. __( 'The style of Mediaelement will depends of your current theme.', 'tg-text-domain' ).'</p>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-spacer"></div>';
	
		$form .= '<div class="tomb-row tomb-type-checkbox tomb-field the_grid_mediaelement">';
			$form .= '<label class="tomb-label">'. __( 'Enable Mediaelement player', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-switch">';
				$form .= '<input type="checkbox" class="tomb-checkbox" name="the_grid_mediaelement" id="the_grid_mediaelement" data-default="" value="'.$media.'" '.checked( ! empty( $media ), 1, false ).'>';
				$form .= '<label for="the_grid_mediaelement"></label>';
			$form .= '</div>';
		$form .= '</div>';
		
		$form .= '<div class="tomb-spacer"></div>';
	
		$form .= '<div class="tomb-row tomb-type-checkbox tomb-field" data-tomb-required="the_grid_mediaelement,==,true">';
			$form .= '<label class="tomb-label">'. __( 'Add Mediaelement Grid StyleSheet', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-switch">';
				$form .= '<input type="checkbox" class="tomb-checkbox" name="the_grid_mediaelement_css" id="the_grid_mediaelement_css" data-default="" value="'.$media_css.'" '.checked( ! empty( $media_css ), 1, false ).'>';
				$form .= '<label for="the_grid_mediaelement_css"></label>';
			$form .= '</div>';
			$form .= '<p class="sub-desc">'. __( 'We cannot be responsible of layout problem while using a theme with custom css stylesheet for mediaelement.', 'tg-text-domain' ) .'<br>'. __( 'For any appearance problem, you should contact the theme author.', 'tg-text-domain' ) .'</p>';
		$form .= '</div>';
			
	$form .= '</div>';

	$form .= '<div class="tomb-clearfix"></div>';
	
	// Debounde resize (smart resize)
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Smart Resize', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
		$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
		$form .= '<label class="tomb-label tomb-label-outside">'. __( 'Debounce resize', 'tg-text-domain' ) .'</label>';
		$form .= '<div class="tomb-row tomb-type-checkbox tomb-field">';
			$form .= '<p class="tomb-desc">'. __( 'By using smart resize, you will reduce the number of calculation during resizing the browser.', 'tg-text-domain' ) .'<br>'. __( 'This allows you to improve performance while resizing browser.', 'tg-text-domain' ) .'<br>'. __( 'This feature can create an horizontal scrollbar during resizing depending of the theme layout.', 'tg-text-domain' ) .'<br>'. __( 'If you encounter any problem, please deactivate this option.', 'tg-text-domain' ) .'</p>';
			$form .= '<div class="tomb-switch">';
				$form .= '<input type="checkbox" class="tomb-checkbox" name="the_grid_debounce" id="the_grid_debounce" data-default="" value="'.$debounce.'" '.checked( ! empty( $debounce ), 1, false ).'>';
				$form .= '<label for="the_grid_debounce"></label>';
			$form .= '</div>';
		$form .= '</div>';
	$form .= '</div>';
	
	$form .= '<div class="tomb-clearfix"></div>';
	
	// Caching grid
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Caching System', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
		$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
		$form .= '<label class="tomb-label tomb-label-outside">'. __( 'Enable Caching Grid', 'tg-text-domain' ) .'</label>';
		$form .= '<div class="tomb-row tomb-type-checkbox tomb-field">';
			$form .= '<p class="tomb-desc">'. __( 'This caching system will cache the entire Grid markup and queries. It will improve drastically performance', 'tg-text-domain' ) .'<br>'. __( 'This cache should be cleared after any changes which can affect the grid.', 'tg-text-domain' ) .'</p>';
			$form .= '<div class="tomb-switch">';
				$form .= '<input type="checkbox" class="tomb-checkbox" name="the_grid_caching" id="the_grid_caching" data-default="" value="'.$caching.'" '.checked( ! empty( $caching ), 1, false ).'>';
				$form .= '<label for="the_grid_caching"></label>';
			$form .= '</div>';
		$form .= '</div>';
		$form .= '<div class="tomb-clearfix"></div>';
		$form .= '<a class="tg-button" data-action="tg_delete_cache" id="tg_clear_cache"><i class="dashicons dashicons-trash"></i>'.__( 'Clear Cache', 'tg-text-domain' ) .'</a>';
		$form .= '<span id="tg_clear_cache_msg"><div class="spinner"></div><strong></strong></span>';
	$form .= '</div>';
	
	$form .= '<div class="tomb-clearfix"></div>';
	
	// Debug mode
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Debug Mode', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
		$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
		$form .= '<label class="tomb-label tomb-label-outside">'. __( 'Enable Debug Mode', 'tg-text-domain' ) .'</label>';
		$form .= '<div class="tomb-row tomb-type-checkbox tomb-field">';
			$form .= '<p class="tomb-desc">'. __( 'This options allows to use un-minified css and javascripts for developers or in order to debug an issue.', 'tg-text-domain' ) .'</p>';
			$form .= '<div class="tomb-switch">';
				$form .= '<input type="checkbox" class="tomb-checkbox" name="the_grid_debug" id="the_grid_debug" data-default="" value="'.$debug_mode.'" '.checked( ! empty( $debug_mode ), 1, false ).'>';
				$form .= '<label for="the_grid_debug"></label>';
			$form .= '</div>';
		$form .= '</div>';
	$form .= '</div>';
	
	$form .= '<div class="tomb-clearfix"></div>';

	if (apply_filters('tg_grid_unregister', false)) {
	
		// Force Registration
		$form .= '<div class="tg-box-side">';
			$form .= '<h3>'. __( 'Force Registration', 'tg-text-domain' ) .'</h3>';
		$form .= '</div>';
		$form .= '<div class="inside tg-box-inside">';
			$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
			$form .= '<label class="tomb-label tomb-label-outside">'. __( 'Force Registration Panel to be displayed', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-row tomb-type-checkbox tomb-field">';
				$form .= '<p class="tomb-desc">'. __( 'This options allows to register The Grid even if your theme hides the registration panel', 'tg-text-domain' ) .'</p>';
				$form .= '<div class="tomb-switch">';
					$form .= '<input type="checkbox" class="tomb-checkbox" name="the_grid_force_registration" id="the_grid_force_registration" data-default="" value="'.$force_register.'" '.checked( ! empty( $force_register ), 1, false ).'>';
					$form .= '<label for="the_grid_force_registration"></label>';
				$form .= '</div>';
			$form .= '</div>';
		$form .= '</div>';
	
	}

$form .= '</div>';

/*****************************
IMAGE SIZES TAB
******************************/
$form .= '<div class="tomb-tab-content image-size">';

	// Thumbnail
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Image Sizes', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
		$form .= '<div class="tomb-row tomb-field">';
			$form .= '<label class="tomb-label">'. __( 'Image Sizes Settings', 'tg-text-domain' ) .'</label>';
			$form .= '<p class="tomb-desc">'. __( 'Following image sizes can be set and used to load fitted images in each grid item.','tg-text-domain' ) .'<br>'.  __( 'These sizes are accessible in each grid settings (under media tab).', 'tg-text-domain' ) .'<br>'.  __( 'It allows to preserve loading speed while preserving optimal image quality.', 'tg-text-domain' ).'<br><br><strong>'.  __( 'N.B.','tg-text-domain' ).'</strong>: '. __( 'By setting an empty or "0" value to the width and height, image size will not be generated.', 'tg-text-domain' ).'<br><br>'. __( 'Learn more about image size in Wordpress:', 'tg-text-domain' ) .'<i> <a href="https://codex.wordpress.org/Function_Reference/add_image_size" target="_blank">'. __( 'add_image_size', 'tg-text-domain' ) .'</a></i></p>';
		$form .= '</div>';
		$form .= '<div class="tomb-clearfix"></div>';
		$form .= '<div class="tomb-spacer" style="height: 5px"></div>';
	
		// thumbnail size1
		$form .= '<label class="tomb-label tomb-label-outside">'. __( 'The Grid Image Size 1', 'tg-text-domain' ) .'</label>';
		$form .= '<div class="tomb-row tomb-type-number tomb-field">';
			$form .= '<label class="tomb-number-label">'. $width .'</label>';
			$form .= '<input type="number" class="tomb-text number mini" name="the_grid_size1_width" data-default="500" value="'.$size1_w.'" step="1" min="0">';
			$form .= '<label class="tomb-number-label">'. $px .'</label>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-row tomb-type-number tomb-field">';
			$form .= '<label class="tomb-number-label">'. $height .'</label>';
			$form .= '<input type="number" class="tomb-text number mini" name="the_grid_size1_height" data-default="500" value="'.$size1_h.'" step="1" min="0">';
			$form .= '<label class="tomb-number-label">'. $px .'</label>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-row tomb-type-number tomb-type-checkbox tomb-field">';
			$form .= '<label class="tomb-number-label">'. $crop .'</label>';
			$form .= '<div class="tomb-switch">';
				$form .= '<input type="checkbox" class="tomb-checkbox" name="the_grid_size1_crop" id="the_grid_size1_crop" data-default="true" value="'.$size1_c.'" '.checked( ! empty( $size1_c ), 1, false ).'>';
				$form .= '<label for="the_grid_size1_crop"></label>';
			$form .= '</div>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-clearfix"></div>';
		$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
	
		// thumbnail size2
		$form .= '<label class="tomb-label tomb-label-outside">'. __( 'The Grid Image Size 2', 'tg-text-domain' ) .'</label>';
	
		$form .= '<div class="tomb-row tomb-type-number tomb-field">';
			$form .= '<label class="tomb-number-label">'. $width .'</label>';
			$form .= '<input type="number" class="tomb-text number mini" name="the_grid_size2_width" data-default="500" value="'.$size2_w.'" step="1" min="0">';
			$form .= '<label class="tomb-number-label">'. $px .'</label>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-row tomb-type-number tomb-field">';
			$form .= '<label class="tomb-number-label">'. $height .'</label>';
			$form .= '<input type="number" class="tomb-text number mini" name="the_grid_size2_height" data-default="1000" value="'.$size2_h.'" step="1" min="0">';
			$form .= '<label class="tomb-number-label">'. $px .'</label>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-row tomb-type-number tomb-type-checkbox tomb-field">';
			$form .= '<label class="tomb-number-label">'. $crop .'</label>';
			$form .= '<div class="tomb-switch">';
				$form .= '<input type="checkbox" class="tomb-checkbox" name="the_grid_size2_crop" id="the_grid_size2_crop" data-default="true" value="'.$size2_c.'" '.checked( ! empty( $size2_c ), 1, false ).'>';
				$form .= '<label for="the_grid_size2_crop"></label>';
			$form .= '</div>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-clearfix"></div>';
		$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
	
		// thumbnail size3
		$form .= '<label class="tomb-label tomb-label-outside">'. __( 'The Grid Image Size 3', 'tg-text-domain' ) .'</label>';
	
		$form .= '<div class="tomb-row tomb-type-number tomb-field">';
			$form .= '<label class="tomb-number-label">'. $width .'</label>';
			$form .= '<input type="number" class="tomb-text number mini" name="the_grid_size3_width" data-default="1000" value="'.$size3_w.'" step="1" min="0">';
			$form .= '<label class="tomb-number-label">'. $px .'</label>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-row tomb-type-number tomb-field">';
			$form .= '<label class="tomb-number-label">'. $height .'</label>';
			$form .= '<input type="number" class="tomb-text number mini" name="the_grid_size3_height" data-default="500" value="'.$size3_h.'" step="1" min="0">';
			$form .= '<label class="tomb-number-label">'. $px .'</label>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-row tomb-type-number tomb-type-checkbox tomb-field">';
			$form .= '<label class="tomb-number-label">'. $crop .'</label>';
			$form .= '<div class="tomb-switch">';
				$form .= '<input type="checkbox" class="tomb-checkbox" name="the_grid_size3_crop" id="the_grid_size3_crop" data-default="true" value="'.$size3_c.'" '.checked( ! empty( $size3_c ), 1, false ).'>';
				$form .= '<label for="the_grid_size3_crop"></label>';
			$form .= '</div>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-clearfix"></div>';
		$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
	
		// thumbnail size4
		$form .= '<label class="tomb-label tomb-label-outside">'. __( 'The Grid Image Size 4', 'tg-text-domain' ) .'</label>';
	
		$form .= '<div class="tomb-row tomb-type-number tomb-field">';
			$form .= '<label class="tomb-number-label">'. $width .'</label>';
			$form .= '<input type="number" class="tomb-text number mini" name="the_grid_size4_width" data-default="1000" value="'.$size4_w.'" step="1" min="0">';
			$form .= '<label class="tomb-number-label">'. $px .'</label>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-row tomb-type-number tomb-field">';
			$form .= '<label class="tomb-number-label">'. $height .'</label>';
			$form .= '<input type="number" class="tomb-text number mini" name="the_grid_size4_height" data-default="1000" value="'.$size4_h.'" step="1" min="0">';
			$form .= '<label class="tomb-number-label">'. $px .'</label>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-row tomb-type-number tomb-type-checkbox tomb-field">';
			$form .= '<label class="tomb-number-label">'. $crop .'</label>';
			$form .= '<div class="tomb-switch">';
				$form .= '<input type="checkbox" class="tomb-checkbox" name="the_grid_size4_crop" id="the_grid_size4_crop" data-default="true" value="'.$size4_c.'" '.checked( ! empty( $size4_c ), 1, false ).'>';
				$form .= '<label for="the_grid_size4_crop"></label>';
			$form .= '</div>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-clearfix"></div>';
		$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
	
		// thumbnail size5
		$form .= '<label class="tomb-label tomb-label-outside">'. __( 'The Grid Image Size 5', 'tg-text-domain' ) .'</label>';
	
		$form .= '<div class="tomb-row tomb-type-number tomb-field">';
			$form .= '<label class="tomb-number-label">'. $width .'</label>';
			$form .= '<input type="number" class="tomb-text number mini" name="the_grid_size5_width" data-default="500" value="'.$size5_w.'" step="1" min="0">';
			$form .= '<label class="tomb-number-label">'. $px .'</label>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-row tomb-type-number tomb-field">';
			$form .= '<label class="tomb-number-label">'. $height .'</label>';
			$form .= '<input type="number" class="tomb-text number mini" name="the_grid_size5_height" data-default="9999" value="'.$size5_h.'" step="1" min="0">';
			$form .= '<label class="tomb-number-label">'. $px .'</label>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-row tomb-type-number tomb-type-checkbox tomb-field">';
			$form .= '<label class="tomb-number-label">'. $crop .'</label>';
			$form .= '<div class="tomb-switch">';
				$form .= '<input type="checkbox" class="tomb-checkbox" name="the_grid_size5_crop" id="the_grid_size5_crop" data-default="" value="'.$size5_c.'" '.checked( ! empty( $size5_c ), 1, false ).'>';
				$form .= '<label for="the_grid_size5_crop"></label>';
			$form .= '</div>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-info-box">';
			$form .= '<div class="dashicons dashicons-lightbulb"></div>';
			$form .= '<div class="tomb-info-box-holder">';
				$form .= '<h3 class="tomb-info-box-title">'. __( 'Regenerate your images!', 'tg-text-domain' ) .'</h3>';
				$form .= '<p class="tomb-info-box-content">'. __( 'We highly recommend to regenerate your thumbnail images in order to correctly apply the previous settings.', 'tg-text-domain' ) .'<br>'. __( 'If you change these settings or just install this plugin on an old wordpress installation then you must regenerate your thumbnail.', 'tg-text-domain' ) .'<br>'. __( 'It exists a lot of plugins that easily allows you to regenerate thumbnails like:', 'tg-text-domain' ) .'<i> <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">'. __( 'Regenerate Thumbnails Plugin', 'tg-text-domain' ) .'</a></i></p>';
				$form .= '<div style="clear:both"></div>';
			$form .= '</div>';
		$form .= '</div>';
		
	$form .= '</div>';
	
	$form .= '<div class="tomb-clearfix"></div>';

$form .= '</div>';

/*****************************
COLORS TAB
******************************/
$form .= '<div class="tomb-tab-content colors">';

	// Color Scheme
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Color Scheme', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
	
		$form .= '<div class="tomb-row tomb-field">';
			$form .= '<label class="tomb-label">'. __( 'Color Scheme Settings', 'tg-text-domain' ) .'</label>';
			$form .= '<p class="tomb-desc">'. __( 'Set your color scheme for title and text insed grid item','tg-text-domain' ).'</p>';
		$form .= '</div>';
	
		$form .= '<div class="tomb-clearfix"></div>';
	
		// Dark Title color
		$form .= '<div class="tomb-row tomb-field tomb-type-color">';
			$form .= '<label class="tomb-label">'. __( 'Dark Title Color', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 5px"></div>';
			$form .= '<input class="tomb-colorpicker" name="the_grid_dark_title" type="text" data-default="#444444" value="'.$dark_title.'" />';
		$form .= '</div>';
	
		// Dark text color
		$form .= '<div class="tomb-row tomb-field tomb-type-color">';
			$form .= '<label class="tomb-label">'. __( 'Dark Text Color', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 5px"></div>';
			$form .= '<input class="tomb-colorpicker" name="the_grid_dark_text" type="text" data-default="#777777" value="'.$dark_text.'" />';
		$form .= '</div>';
	
		// Dark span color
		$form .= '<div class="tomb-row tomb-field tomb-type-color">';
			$form .= '<label class="tomb-label">'. __( 'Dark Span Color', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 5px"></div>';
			$form .= '<input class="tomb-colorpicker" name="the_grid_dark_span" type="text" data-default="#999999" value="'.$dark_span.'" />';
		$form .= '</div>';
	
		$form .= '<div class="tomb-clearfix"></div>';
	
		// Light Title color
		$form .= '<div class="tomb-row tomb-field tomb-type-color">';
			$form .= '<label class="tomb-label">'. __( 'Light Title Color', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 5px"></div>';
			$form .= '<input class="tomb-colorpicker" name="the_grid_light_title" type="text" data-default="#ffffff" value="'.$light_title.'" />';
		$form .= '</div>';
	
		// Light text color
		$form .= '<div class="tomb-row tomb-field tomb-type-color">';
			$form .= '<label class="tomb-label">'. __( 'Light Text Color', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 5px"></div>';
			$form .= '<input class="tomb-colorpicker" name="the_grid_light_text" type="text" data-default="#f6f6f6" value="'.$light_text.'" />';
		$form .= '</div>';
	
		// Light span color
		$form .= '<div class="tomb-row tomb-field tomb-type-color">';
			$form .= '<label class="tomb-label">'. __( 'Light Span Color', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 5px"></div>';
			$form .= '<input class="tomb-colorpicker" name="the_grid_light_span" type="text" data-default="#f5f5f5" value="'.$light_span.'" />';
		$form .= '</div>';
	
	$form .= '</div>';
	
	$form .= '<div class="tomb-clearfix"></div>';

$form .= '</div>';

/*****************************
LIGHTBOX TAB
******************************/

$form .= '<div class="tomb-tab-content lightbox">';

	$prettyphoto    = (is_plugin_active( 'prettyphoto/prettyphoto.php')) ? null : 'disabled';
	$prettyphoto_no = (is_plugin_active( 'prettyphoto/prettyphoto.php')) ? null : __( '(not available)', 'tg-text-domain' );
	$fancybox       = (is_plugin_active( 'fancybox-for-wordpress/fancybox.php')) ?  null : 'disabled';
	$fancybox_no    = (is_plugin_active( 'fancybox-for-wordpress/fancybox.php')) ? null : __( '(not available)', 'tg-text-domain' );
	$foobox         = (is_plugin_active( 'fooboxV2/foobox.php') || is_plugin_active( 'foobox-image-lightbox-premium/foobox-free.php' ) || is_plugin_active( 'foobox-image-lightbox/foobox-free.php' ) ) ?  null : 'disabled';
	$foobox_no      = (is_plugin_active( 'fooboxV2/foobox.php') || is_plugin_active( 'foobox-image-lightbox-premium/foobox-free.php' ) || is_plugin_active( 'foobox-image-lightbox/foobox-free.php' ) ) ? null : __( '(not available)', 'tg-text-domain' );
	$modulobox      = (is_plugin_active( 'modulobox/modulobox.php')) ? null : 'disabled';
	$modulobox_no   = (is_plugin_active( 'modulobox/modulobox.php')) ? null : __( '(not available)', 'tg-text-domain' );

	// Lightbox type
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Lightbox', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';

	
	$form .= '<div class="tomb-row tomb-field the_grid_lightbox">';
		$form .= '<label class="tomb-label">'. __( 'LightBox Type', 'tg-text-domain' ) .'</label>';
		$form .= '<p class="tomb-desc">'. __( 'Select the default LightBox to be used.', 'tg-text-domain' ).'<p>';
		
		$form .= '<div class="tomb-select-holder" data-noresult="'.__('No results found', 'tomb-text-domain').'">';
			$form .= '<div class="tomb-select-fake">';
				$form .= '<span class="tomb-select-value"></span>';
				$form .= '<span class="tomb-select-arrow"><i></i></span>';
			$form .= '</div>';
			$form .= '<select class="tomb-select" name="the_grid_lightbox" data-default="the_grid" data-value="'.$lightbox.'" data-clear="">';
				$form .= '<option value="the_grid" '.selected('the_grid', $lightbox, false ).'>'.__( 'The Grid Lightbox', 'tg-text-domain' ).'</option>';
				$form .= '<option value="modulobox" '.selected('modulobox', $lightbox, false ).' '.$modulobox.'>'.__( 'ModuloBox (Premium)', 'tg-text-domain' ).' '.$modulobox_no.'</option>';
				$form .= '<option value="prettyphoto" '.selected('prettyphoto', $lightbox, false ).' '.$prettyphoto.'>'.__( 'PrettyPhoto', 'tg-text-domain' ).' '.$prettyphoto_no.'</option>';
				$form .= '<option value="fancybox" '.selected('fancybox', $lightbox, false ).' '.$fancybox.'>'.__( 'FancyBox', 'tg-text-domain' ).' '.$fancybox_no.'</option>';
				$form .= '<option value="foobox" '.selected('foobox', $lightbox, false ).' '.$foobox.'>'.__( 'Foobox (v2)', 'tg-text-domain' ).' '.$foobox_no.'</option>';
			$form .= '</select>';
		$form .= '</div>';
		$form .= '<p class="sub-desc">'. __( 'To use FancyBox or Prettyphoto you must install/activate them.', 'tg-text-domain' ).'<br>';
			$form .= '<em>- ' .__( 'ModuloBox WordPress plugin:', 'tg-text-domain' ).' <a target="_blank" href="https://theme-one.com/modulobox/">'. __( 'Link', 'tg-text-domain' ).'</a><br>';
			$form .= '- '.__( 'FancyBox WordPress plugin:', 'tg-text-domain' ).' <a target="_blank" href="https://wordpress.org/plugins/fancybox-for-wordpress/">'. __( 'Link', 'tg-text-domain' ).'</a><br>';
			$form .= '- '.__( 'PrettyPhoto WordPress plugin:', 'tg-text-domain' ).' <a target="_blank" href="https://wordpress.org/plugins/prettyphoto/">'. __( 'Link', 'tg-text-domain' ).'</a><br>';
			$form .= '- '.__( 'FooBoxV2 WordPress plugin:', 'tg-text-domain' ).' <a target="_blank" href="http://fooplugins.com/foobox-wordpress-lightbox-version-2-0-released/">'. __( 'Link', 'tg-text-domain' ).'</a></em>';
			
		$form .= '<p>';
	$form .= '</div>';
	
	$form .= '<div class="tomb-clearfix"></div>';
	
	// Lightbox autoplay
	$form .= '<div class="tomb-row tomb-field" data-tomb-required="the_grid_lightbox,==,the_grid">';
		$form .= '<label class="tomb-label">'. __( 'AutoPlay Video', 'tg-text-domain' ) .'</label>';
		$form .= '<p class="tomb-desc">'. __( 'Automatically play video (hosted & embedded) in the lightbox.', 'tg-text-domain' ) .'</p>';
		$form .= '<div class="tomb-switch">';
			$form .= '<input type="checkbox" class="tomb-checkbox" name="the_grid_ligthbox_autoplay" id="the_grid_ligthbox_autoplay" data-default="" value="'.$lb_autoplay.'" '.checked( ! empty( $lb_autoplay ), 1, false ).'>';
			$form .= '<label for="the_grid_ligthbox_autplay"></label>';
		$form .= '</div>';
	$form .= '</div>';
	
	$form .= '<div class="tomb-clearfix"></div>';
	
	// Lightbox background color
	$form .= '<div class="tomb-row tomb-field" data-tomb-required="the_grid_lightbox,==,the_grid">';
		$form .= '<label class="tomb-label">'. __( 'Background Color', 'tg-text-domain' ) .'</label>';
		$form .= '<p class="tomb-desc">'. __( 'Please, select the overlay background color of the lightbox.', 'tg-text-domain' ) .'</p>';
		$form .= '<input class="tomb-colorpicker" name="the_grid_ligthbox_background" type="text" data-alpha="1" data-default="rgba(0,0,0,0.8)" value="'.$lb_bg.'" />';
	$form .= '</div>';
	
	$form .= '<div class="tomb-clearfix"></div>';
	
	// Lightbox text color
	$form .= '<div class="tomb-row tomb-field" data-tomb-required="the_grid_lightbox,==,the_grid">';
		$form .= '<label class="tomb-label">'. __( 'Text Color', 'tg-text-domain' ) .'</label>';
		$form .= '<p class="tomb-desc">'. __( 'Please, select text color of the lightbox.', 'tg-text-domain' ) .'</p>';
		$form .= '<input class="tomb-colorpicker" name="the_grid_ligthbox_color" type="text" data-default="#ffffff" value="'.$lb_color.'" />';
	$form .= '</div>';
	
	$form .= '<div class="tomb-clearfix"></div>';

	$form .= '</div>';

$form .= '</div>';

/*****************************
META DATA TAB
******************************/
$form .= '<div class="tomb-tab-content meta-data tomb-tab-show">';

	// meta data
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Meta Data', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
	
		$form .= '<div class="tomb-row tomb-field the_grid_meta_data">';
			$form .= '<label class="tomb-label">'. __( 'Add custom meta data', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
			$form .= '<p class="tomb-desc">';
			$form .= __( 'In this panel setting, you can add any custom meta data.', 'tg-text-domain' ) .'<br>';
			$form .= __( 'These meta data can be used to add new sorter in order to sort the grid by a custom meta data.', 'tg-text-domain' );

		$form .= '</div>';
		
		$form .= '<div class="tomb-clearfix"></div>';
		
		function meta_data($name, $key, $remove) {
			
			$meta_data = '<div class="tg-meta-key-holder">';
			
				$meta_data .= '<div class="tomb-row tomb-field">';
					$meta_data .= '<label class="tomb-label">'. __( 'Name', 'tg-text-domain' ) .'</label>';
					$meta_data .= '<div class="tomb-spacer" style="height: 5px"></div>';
					$meta_data .= '<input type="text" class="tomb-text tg-meta-name" value="'.$name.'">';
				$meta_data .= '</div>';
					
				$meta_data .= '<div class="tomb-row tomb-field">';
					$meta_data .= '<label class="tomb-label">'. __( 'Meta key name', 'tg-text-domain' ) .'</label>';
					$meta_data .= '<div class="tomb-spacer" style="height: 5px"></div>';
					$meta_data .= '<input type="text" class="tomb-text tg-meta-key-name" value="'.$key.'">';
				$meta_data .= '</div>';
				
				$remove = ($remove != true) ? ' style="display: none"' : null;
				$meta_data .= '<div class="tomb-row tomb-field">';
					$meta_data .= '<div class="tomb-spacer" style="height: 23px"></div>';
					$meta_data .= '<a class="tg-button" id="tg_settings_remove_metadata"'.$remove.'><i class="dashicons dashicons-no"></i>'. __( 'Delete', 'tg-text-domain' ) .'</a>';
				$meta_data .= '</div>';
					
				$meta_data .= '<div class="tomb-clearfix"></div>';
			
			$meta_data .= '</div>';
			
			return $meta_data;
			
		}
		
		$count = 0;
		if (isset($meta_data) && !empty($meta_data) && json_decode($meta_data) != null) {
			$meta = json_decode($meta_data, true);
			foreach($meta as $data) {
				$remove = ($count == 0) ? false : true;
				$form .= meta_data($data['name'], $data['key'], $remove);
				$count++;
			}
		} else {
			$meta_data = '';
			$form .= meta_data('', '', false);
		}
		
		$form .= '<div class="tomb-row tomb-field">';
			$form .= '<div class="tomb-clearfix"></div>';
			$form .= '<div class="tomb-spacer" style="height: 5px"></div>';
			$form .= '<a class="tg-button" id="tg_settings_add_metadata"><i class="dashicons dashicons-plus"></i>'. __( 'Add meta data', 'tg-text-domain' ) .'</a>';
			$form .= '<input type="hidden" class="tomb-text the_grid_custom_meta_data" name="the_grid_custom_meta_data" value=\''.$meta_data.'\'>';
		$form .= '</div>';
	
	$form .= '</div>';
		
$form .= '</div>';

/*****************************
SOCIAL API TAB
******************************/

$intagram_token = get_option('the_grid_instagram_api_key', '');
if (isset($_GET['instagram_access_token']) && !empty($_GET['instagram_access_token'])) {
	update_option('the_grid_instagram_access_token', $_GET['instagram_access_token']);
	$intagram_token = $_GET['instagram_access_token'];
}
if (isset($_GET['instagram_logout']) && $_GET['instagram_logout'] == true) {
	update_option('the_grid_instagram_api_key', null);
	$intagram_token = null;
}
// access token instagram http://instagram.pixelunion.net/
$form .= '<div class="tomb-tab-content social-api tomb-tab-show">';

	// meta data
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Instagram API', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
		$form .= '<div class="tomb-row tomb-field the_grid_instagram">';
			$form .= '<label class="tomb-label">'. __( 'Connect to Instagram', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
			$form .= '<p class="tomb-desc">'.__( 'Please enter your Access Token:', 'tg-text-domain' ).'</p>';
			$form .= '<input type="text" style="width: 420px;" class="tomb-text the_grid_instagram_api_key" name="the_grid_instagram_api_key" value=\''.$intagram_token.'\'>';
			$form .= '<p class="tomb-sub-desc">';
			
			$plugin_info   = get_option('the_grid_plugin_info', '');
			$purchase_code = (isset($plugin_info['purchase_code'])) ? $plugin_info['purchase_code'] : null;
			if (!$purchase_code) {
				$form .= __( 'By creating your own Instagram App you can get your Access Token', 'tg-text-domain' ).' <a target="_blank" href="https://www.instagram.com/developer/">'. __( 'Create your App', 'tg-text-domain' ).'</a></p>';
			} else {
				$form .= __( 'Get your Instagram Access Token ', 'tg-text-domain' ).' <a target="_blank" href="http://theme-one.com/services/instagram/?get_access_token">'. __( 'here', 'tg-text-domain' ).'</a><br>';
			}
			
			$form .= '</p>';			
		$form .= '</div>';		
	$form .= '</div>';
	
	$form .= '<div class="tomb-clearfix"></div>';
	
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Youtube API', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
		$youtube_api_key  = get_option('the_grid_youtube_api_key', '');
		$form .= '<div class="tomb-row tomb-field the_grid_youtube">';
			$form .= '<label class="tomb-label">'. __( 'Connect to Youtube', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
			$form .= '<p class="tomb-desc">'.__( 'Please enter your Youtube API key:', 'tg-text-domain' ).'</p>';
			$form .= '<input type="text" style="width: 420px;" class="tomb-text the_grid_youtube_api_key" name="the_grid_youtube_api_key" value=\''.$youtube_api_key.'\'>';	
			$form .= '<p class="tomb-sub-desc">'.__( 'You can find more information about the Youtube API key', 'tg-text-domain' ).' <a target="_blank" href="https://developers.google.com/youtube/v3/getting-started#before-you-start">'.__( 'here', 'tg-text-domain' ).'</a></p>';					
		$form .= '</div>';
	$form .= '</div>';
	
	$form .= '<div class="tomb-clearfix"></div>';
	
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Vimeo API', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
		
		$vimeo_client_id  = get_option('the_grid_vimeo_client_id', '');
		$form .= '<div class="tomb-row tomb-field the_grid_vimeo">';
			$form .= '<label class="tomb-label">'. __( 'Vimeo Client ID', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
			$form .= '<p class="tomb-desc">'.__( 'Please enter your Vimeo Client ID:', 'tg-text-domain' ).'</p>';
			$form .= '<input type="text" style="width: 420px;" class="tomb-text the_grid_vimeo_client_id" name="the_grid_vimeo_client_id" value=\''.$vimeo_client_id.'\'>';					
		$form .= '</div>';

		$form .= '<div class="tomb-clearfix"></div>';
	
		$vimeo_client_secrets  = get_option('the_grid_vimeo_client_secrets', '');
		$form .= '<div class="tomb-row tomb-field the_grid_vimeo">';
			$form .= '<label class="tomb-label">'. __( 'Vimeo Client Secrets', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
			$form .= '<p class="tomb-desc">'.__( 'Please enter your Vimeo Client Secrets:', 'tg-text-domain' ).'</p>';
			$form .= '<input type="text" style="width: 420px;" class="tomb-text the_grid_vimeo_client_secrets" name="the_grid_vimeo_client_secrets" value=\''.$vimeo_client_secrets.'\'>';
			$form .= '<p class="tomb-sub-desc">'.__( 'You can find more information about the Vimeo Client Secrets & ID', 'tg-text-domain' ).' <a target="_blank" href="https://developer.vimeo.com/apps">'.__( 'here', 'tg-text-domain' ).'</a></p>';
		$form .= '</div>';

		$form .= '<div class="tomb-clearfix"></div>';

		$vimeo_api_key  = get_option('the_grid_vimeo_api_key', '');
		$form .= '<div class="tomb-row tomb-field the_grid_vimeo">';
			$form .= '<label class="tomb-label">'. __( 'Vimeo API Token (Deprecated)', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
			$form .= '<p class="tomb-desc">'.__( 'Please enter your Vimeo Personal Access Token:', 'tg-text-domain' ).'</p>';
			$form .= '<input type="text" style="width: 420px;" class="tomb-text the_grid_vimeo_api_key" name="the_grid_vimeo_api_key" value=\''.$vimeo_api_key.'\' disabled>';	
			$form .= '<p class="tomb-sub-desc">'.__( 'Vimeo API Key is deprecated, please use Client Secrets & ID', 'tg-text-domain' ) . '</p>';					
		$form .= '</div>';
	$form .= '</div>';
		
	$form .= '<div class="tomb-clearfix"></div>';
	
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Facebook API', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
	
		$facebook_app_ID  = get_option('the_grid_facebook_app_ID', '');
		$form .= '<div class="tomb-row tomb-field the_grid_facebook">';
			$form .= '<label class="tomb-label">'. __( 'Facebook App ID', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
			$form .= '<p class="tomb-desc">'.__( 'Please enter your Facebook App ID:', 'tg-text-domain' ).'</p>';
			$form .= '<input type="text" style="width: 420px;" class="tomb-text the_grid_facebook_app_ID" name="the_grid_facebook_app_ID" value=\''.$facebook_app_ID.'\'>';						
		$form .= '</div>';
		
		$form .= '<div class="tomb-clearfix"></div>';
		
		$facebook_app_secret  = get_option('the_grid_facebook_app_secret', '');
		$form .= '<div class="tomb-row tomb-field the_grid_facebook">';
			$form .= '<label class="tomb-label">'. __( 'Facebook App Secret', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
			$form .= '<p class="tomb-desc">'.__( 'Please enter your Facebook App Secret:', 'tg-text-domain' ).'</p>';
			$form .= '<input type="text" style="width: 420px;" class="tomb-text the_grid_facebook_app_secret" name="the_grid_facebook_app_secret" value=\''.$facebook_app_secret.'\'>';	
			$form .= '<p class="tomb-sub-desc">'.__( 'Please register your Website app with Facebook to get these values:', 'tg-text-domain' ).' <a target="_blank" href="https://developers.facebook.com/docs/apps/register">'.__( 'Register an App', 'tg-text-domain' ).'</a></p>';					
		$form .= '</div>';
		
	$form .= '</div>';
	
	$form .= '<div class="tomb-clearfix"></div>';
	
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Twitter API', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
	
		$twitter_consumer_key  = get_option('the_grid_twitter_consumer_key', '');
		$form .= '<div class="tomb-row tomb-field the_grid_twitter">';
			$form .= '<label class="tomb-label">'. __( 'Twitter Consumer Key', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
			$form .= '<p class="tomb-desc">'.__( 'Please enter your Twitter consumer key:', 'tg-text-domain' ).'</p>';
			$form .= '<input type="text" style="width: 420px;" class="tomb-text the_grid_twitter_consumer_key" name="the_grid_twitter_consumer_key" value=\''.$twitter_consumer_key.'\'>';					
		$form .= '</div>';
		
		$form .= '<div class="tomb-clearfix"></div>';
		
		$twitter_consumer_secret  = get_option('the_grid_twitter_consumer_secret', '');
		$form .= '<div class="tomb-row tomb-field the_grid_facebook">';
			$form .= '<label class="tomb-label">'. __( 'Twitter Consumer Secret', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
			$form .= '<p class="tomb-desc">'.__( 'Please enter your Twitter consumer secret:', 'tg-text-domain' ).'</p>';
			$form .= '<input type="text" style="width: 420px;" class="tomb-text the_grid_twitter_consumer_secret" name="the_grid_twitter_consumer_secret" value=\''.$twitter_consumer_secret.'\'>';	
			$form .= '<p class="tomb-sub-desc">'.__( 'Register your Twitter App to generate your Consumer Key & Secret:', 'tg-text-domain' ).' <a target="_blank" href="https://apps.twitter.com/">'.__( 'Register an App', 'tg-text-domain' ).'</a></p>';					
		$form .= '</div>';
		
	$form .= '</div>';

	$form .= '<div class="tomb-clearfix"></div>';
	
	$form .= '<div class="tg-box-side">';
		$form .= '<h3>'. __( 'Flickr API', 'tg-text-domain' ) .'</h3>';
	$form .= '</div>';
	$form .= '<div class="inside tg-box-inside">';
	
		$flickr_api_key  = get_option('the_grid_flickr_api_key', '');
		$form .= '<div class="tomb-row tomb-field the_grid_flickr">';
			$form .= '<label class="tomb-label">'. __( 'Flickr API Key', 'tg-text-domain' ) .'</label>';
			$form .= '<div class="tomb-spacer" style="height: 15px"></div>';
			$form .= '<p class="tomb-desc">'.__( 'Please enter your Flickr API key:', 'tg-text-domain' ).'</p>';
			$form .= '<input type="text" style="width: 420px;" class="tomb-text the_grid_flickr_api_key" name="the_grid_flickr_api_key" value=\''.$flickr_api_key.'\'>';	
			$form .= '<p class="tomb-sub-desc">'.__( 'Register your Flickr App to generate your API Key:', 'tg-text-domain' ).' <a target="_blank" href="https://www.flickr.com/services/api/misc.api_keys.html">'.__( 'Register an App', 'tg-text-domain' ).'</a></p>';			
		$form .= '</div>';
		
		$form .= '<div class="tomb-clearfix"></div>';
		
	$form .= '</div>';

$form .= '</div>';

/*****************************
OPTIONS HOLDER END
******************************/
$form .= '</div>';
$form .= '</div>';

/*****************************
BUILD PANEL SETTINGS
******************************/
$form_holder_start  = '<div class="metabox-holder tg-settings">';
	$form_holder_start .= '<div class="postbox">';
	$form_holder_end = '</div>';
$form_holder_end .= '</div>';

echo $form_holder_start;
echo $form;
echo $form_holder_end;