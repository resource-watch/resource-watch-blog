<?php
/*function that checks if the avia builder was already included*/
function avia_builder_plugin_enabled()
{
	if (class_exists( 'AviaBuilder' )) { return true; }
	return false;
}


//set the folder that contains the shortcodes
function add_shortcode_folder($paths)
{
	$paths = array(dirname(__FILE__) ."/avia-shortcodes/");
	return $paths;
}

add_filter('avia_load_shortcodes','add_shortcode_folder');



//set the folder that contains assets like js and imgs
function avia_builder_plugins_url($url)
{
	$url = get_template_directory_uri()."/config-templatebuilder/avia-template-builder/";
	return $url;
}


add_filter('avia_builder_plugins_url','avia_builder_plugins_url');


//check if the builder was included via plugin. if not include it now via theme
if(!avia_builder_plugin_enabled())
{
	require_once( dirname(__FILE__) . '/avia-template-builder/php/template-builder.class.php' );
	
	//define( 'AVIA_BUILDER_TEXTDOMAIN',  'avia_framework' );
	
	$builder = Avia_Builder(); 
	
	//activates the builder safe mode. this hides the shortcodes that are built with the content builder from the default wordpress content editor. 
	//can also be set to "debug", to show shortcode content and extra shortcode container field
	$builder->setMode( 'safe' ); 
	
	//set all elements that are fullwidth and need to interact with the section shortcode. av_section is included automatically
	$builder->setFullwidthElements( array('av_revolutionslider', 'av_layerslider' ,'av_slideshow_full', 'av_fullscreen', 'av_masonry_entries','av_masonry_gallery', 'av_google_map', 'av_slideshow_accordion', 'av_image_hotspot', 'av_portfolio', 'av_submenu', 'av_layout_row', 'av_button_big','av_feature_image_slider','av_tab_section','av_horizontal_gallery') ); 
}



