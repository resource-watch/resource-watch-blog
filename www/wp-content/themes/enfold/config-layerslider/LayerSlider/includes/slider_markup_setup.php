<?php

if(!defined('LS_ROOT_FILE')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$slider = array();

// Filter to override the defaults
if(has_filter('layerslider_override_defaults')) {
	$newDefaults = apply_filters('layerslider_override_defaults', $lsDefaults);
	if(!empty($newDefaults) && is_array($newDefaults)) {
		$lsDefaults = $newDefaults;
		unset($newDefaults);
	}
}

// Hook to alter slider data *before* filtering with defaults
if(has_filter('layerslider_pre_parse_defaults')) {
	$result = apply_filters('layerslider_pre_parse_defaults', $slides);
	if(!empty($result) && is_array($result)) {
		$slides = $result;
	}
}

// Filter slider data with defaults
$slides['properties'] = apply_filters('ls_parse_defaults', $lsDefaults['slider'], $slides['properties']);
$skin = !empty($slides['properties']['attrs']['skin']) ? $slides['properties']['attrs']['skin'] : $lsDefaults['slider']['skin']['value'];
$slides['properties']['attrs']['skinsPath'] = dirname(LS_Sources::urlForSkin($skin)) . '/';
if(isset($slides['properties']['autoPauseSlideshow'])) {
	switch($slides['properties']['autoPauseSlideshow']) {
		case 'auto': $slides['properties']['autoPauseSlideshow'] = 'auto'; break;
		case 'enabled': $slides['properties']['autoPauseSlideshow'] = true; break;
		case 'disabled': $slides['properties']['autoPauseSlideshow'] = false; break;
	}
}

if( ! empty( $slides['properties']['props']['globalBGImageId'] ) ) {
	$tempSrc = wp_get_attachment_image_src( $slides['properties']['props']['globalBGImageId'], 'full' );
	$slides['properties']['attrs']['globalBGImage'] = $tempSrc[0];
}

// Old and without type
if( empty($slides['properties']['attrs']['sliderVersion']) && empty($slides['properties']['attrs']['type']) ) {

	if( !empty($slides['properties']['props']['forceresponsive']) ) {
		$slides['properties']['attrs']['type'] = 'fullwidth';
	} elseif( empty($slides['properties']['props']['responsive']) ) {
		$slides['properties']['attrs']['type'] = 'fixedsize';
	} else {
		$slides['properties']['attrs']['type'] = 'responsive';
	}
}


// Override firstSlide if it is specified in embed params
if( ! empty( $embed['firstslide'] ) ) {
	$slides['properties']['attrs']['firstSlide'] = '[firstSlide]';
}

// Make sure that width & height are set correctly
if( empty( $slides['properties']['props']['width'] ) ) { $slides['properties']['props']['width'] = 1280; }
if( empty( $slides['properties']['props']['height'] ) ) { $slides['properties']['props']['height'] = 720; }

// Slides and layers
if(isset($slides['layers']) && is_array($slides['layers'])) {
	foreach($slides['layers'] as $slidekey => $slide) {

		// 6.6.1: Fix PHP undef notice
		$slide['properties'] = ! empty( $slide['properties'] ) ? $slide['properties'] : array();

		$slider['slides'][$slidekey] = apply_filters('ls_parse_defaults', $lsDefaults['slides'], $slide['properties']);

		if(isset($slide['sublayers']) && is_array($slide['sublayers'])) {

			foreach($slide['sublayers'] as $layerkey => $layer) {

				// Ensure that magic quotes will not mess with JSON data
				if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
					$layer['styles'] = stripslashes($layer['styles']);
					$layer['transition'] = stripslashes($layer['transition']);
				}

				if( ! empty( $layer['transition'] ) ) {
					$layer = array_merge($layer, json_decode(stripslashes($layer['transition']), true));
				}


				if( ! empty( $layer['styles'] ) ) {
					$layerStyles = json_decode($layer['styles'], true);

					if( empty( $layerStyles ) ) {
						$layerStyles = json_decode(stripslashes($layer['styles']), true);
					}

					$layer['styles'] = ! empty( $layerStyles ) ? $layerStyles : array();
				}

				if( ! empty( $layer['top'] ) ) {
					$layer['styles']['top']  = $layer['top'];
				}

				if( ! empty( $layer['left'] ) ) {
					$layer['styles']['left']  = $layer['left'];
				}

				if( ! empty($layer['wordwrap']) || ! empty($layer['styles']['wordwrap']) ) {
					$layer['styles']['white-space'] = 'normal';
				}



				// Marker for Font Awesome
				if( empty( $lsFonts['font-awesome'] ) && ! empty( $layer['html'] ) ) {
					if( strpos( $layer['html'], 'fa fa-') !== false ) {
						$lsFonts['font-awesome'] = 'font-awesome';
					}
				}

				// v6.5.6: Compatibility mode for media layers that used the
				// old checkbox based media settings.
				if( isset( $layer['controls'] ) ) {
					if( true === $layer['controls'] ) {
						$layer['controls'] = 'auto';
					} elseif( false === $layer['controls'] ) {
						$layer['controls'] = 'disabled';
					}
				}

				// Remove unwanted style options
				$keys = array_keys( $layer['styles'], 'unset', true );
				foreach( $keys as $key) {
					unset( $layer['styles'][$key] );
				}

				if( isset($layer['styles']['opacity']) && $layer['styles']['opacity'] === '1') {
					unset($layer['styles']['opacity']);
				}

				unset($layer['styles']['wordwrap']);

				$slider['slides'][$slidekey]['layers'][$layerkey] = apply_filters('ls_parse_defaults', $lsDefaults['layers'], $layer);
			}
		}
	}
}

// Hook to alter slider data *after* filtering with defaults
if(has_filter('layerslider_post_parse_defaults')) {
	$result = apply_filters('layerslider_post_parse_defaults', $slides);
	if(!empty($result) && is_array($result)) {
		$slides = $result;
	}
}

// Fix circle timer
if( empty($slides['properties']['attrs']['sliderVersion']) && empty($slides['properties']['attrs']['showCircleTimer']) ) {
	$slides['properties']['attrs']['showCircleTimer'] = false;
}