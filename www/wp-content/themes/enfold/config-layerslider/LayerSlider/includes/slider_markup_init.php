<?php

// Get init code
foreach($slides['properties']['attrs'] as $key => $val) {

	if(is_bool($val)) {
		$val = $val ? 'true' : 'false';
		$init[] = $key.': '.$val;
	} elseif(is_numeric($val)) { $init[] = $key.': '.$val;
	} else { $init[] = "$key: '$val'"; }
}

// Full-size sliders
if( ( !empty($slides['properties']['attrs']['type']) && $slides['properties']['attrs']['type'] === 'fullsize' ) && ( empty($slides['properties']['attrs']['fullSizeMode']) || $slides['properties']['attrs']['fullSizeMode'] !== 'fitheight' ) ) {
	$init[] = 'height: '.$slides['properties']['props']['height'].'';
}

// Popup
if( !empty($slides['properties']['attrs']['type']) && $slides['properties']['attrs']['type'] === 'popup' ) {
	$lsPlugins[] = 'popup';
}

if( ! empty( $lsPlugins ) ) {
	$init[] = 'plugins: ' . json_encode( array_unique( $lsPlugins ) );
}

$separator = apply_filters( 'layerslider_init_props_separator', ', ');
$init = implode( $separator, $init );


// Fix multiple jQuery issue
$lsInit[] = '<script data-cfasync="false" type="text/javascript">';
$lsInit[] = 'var lsjQuery = jQuery;';
$lsInit[] = '</script>';

// Include JS files to body option
if(get_option('ls_put_js_to_body', false)) {
	$lsInit[] = '<script type="text/javascript" data-cfasync="false" src="'.LS_ROOT_URL.'/static/layerslider/js/layerslider.transitions.js?ver='.LS_PLUGIN_VERSION.'"></script>' . NL;
    $lsInit[] = '<script type="text/javascript" data-cfasync="false" src="'.LS_ROOT_URL.'/static/layerslider/js/layerslider.kreaturamedia.jquery.js?ver='.LS_PLUGIN_VERSION.'"></script>' . NL;
    $lsInit[] = '<script type="text/javascript" data-cfasync="false" src="'.LS_ROOT_URL.'/static/layerslider/js/greensock.js?ver=1.11.8"></script>' . NL;
}

$lsInit[] = '<script data-cfasync="false" type="text/javascript">' . NL;
	$lsInit[] = 'lsjQuery(document).ready(function() {' . NL;
		$lsInit[] = 'if(typeof lsjQuery.fn.layerSlider == "undefined") {' . NL;
			$lsInit[] = 'if( window._layerSlider && window._layerSlider.showNotice) { ' . NL;
				$lsInit[] = 'window._layerSlider.showNotice(\''.$sliderID.'\',\'jquery\');' . NL;
			$lsInit[] = '}' . NL;
		$lsInit[] = '} else {' . NL;
			$lsInit[] = 'lsjQuery("#'.$sliderID.'")';
			if( !empty($slides['callbacks']) && is_array($slides['callbacks']) ) {
				foreach($slides['callbacks'] as $event => $function) {
					$lsInit[] = '.on(\''.$event.'\', '.stripslashes($function).')';
				}
			}
			$lsInit[] = '.layerSlider({'.$init.'});' . NL;
		$lsInit[] = '}' . NL;
	$lsInit[] = '});' . NL;
$lsInit[] = '</script>';
