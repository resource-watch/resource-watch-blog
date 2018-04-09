<?php

/*
Plugin Name: LayerSlider WP
Plugin URI: https://codecanyon.net/item/layerslider-responsive-wordpress-slider-plugin-/1362246
Description: LayerSlider is a premium multi-purpose content creation and animation platform. Easily create sliders, image galleries, slideshows with mind-blowing effects, popups, landing pages, animated page blocks, or even a full website. It empowers more than 1.5 million active websites on a daily basis with stunning visuals and eye-catching effects.
Version: 6.6.7
Author: Kreatura Media
Author URI: https://layerslider.kreaturamedia.com
Text Domain: LayerSlider
*/


// Prevent direct file access.
if( ! defined('ABSPATH') ) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}


// Attempting to detect duplicate versions of LayerSlider to offer
// a more user-friendly error message explaining the situation.
if( defined('LS_PLUGIN_VERSION') || isset($GLOBALS['lsPluginPath']) ) {
	die('ERROR: It looks like you already have one instance of LayerSlider installed. WordPress cannot activate and handle two instanced at the same time, you need to remove the old version first.');
}


// Basic configuration
define('LS_DB_TABLE', 'layerslider');
define('LS_DB_VERSION', '6.5.5');
define('LS_PLUGIN_VERSION', '6.6.7');


// Path info
// v6.2.0: LS_ROOT_URL is now set in the after_setup_theme action
// hook to provide a way for theme authors to override its value
define('LS_ROOT_FILE', __FILE__);
define('LS_ROOT_PATH', dirname(__FILE__));


// Other constants
define('LS_WP_ADMIN', true);
define('LS_PLUGIN_SLUG', basename(dirname(__FILE__)));
define('LS_PLUGIN_BASE', plugin_basename(__FILE__));
define('LS_MARKETPLACE_ID', '1362246');
define('LS_TEXTDOMAIN', 'LayerSlider');
define('LS_REPO_BASE_URL', 'https://repository.kreaturamedia.com/v4/');


if( ! defined('NL')  ) { define('NL', "\r\n"); }
if( ! defined('TAB') ) { define('TAB', "\t");  }


// Load & initialize plugin config class
include LS_ROOT_PATH.'/classes/class.ls.config.php';
LS_Config::init();

// Shared
include LS_ROOT_PATH.'/wp/scripts.php';
include LS_ROOT_PATH.'/wp/menus.php';
include LS_ROOT_PATH.'/wp/hooks.php';
include LS_ROOT_PATH.'/wp/widgets.php';
include LS_ROOT_PATH.'/wp/shortcodes.php';
include LS_ROOT_PATH.'/wp/compatibility.php';
include LS_ROOT_PATH.'/includes/slider_utils.php';
include LS_ROOT_PATH.'/classes/class.ls.posts.php';
include LS_ROOT_PATH.'/classes/class.ls.sliders.php';
include LS_ROOT_PATH.'/classes/class.ls.sources.php';
include LS_ROOT_PATH.'/classes/class.ls.popups.php';

// Back-end only
if( is_admin() ) {

	include LS_ROOT_PATH.'/wp/actions.php';
	include LS_ROOT_PATH.'/wp/activation.php';
	include LS_ROOT_PATH.'/wp/tinymce.php';
	include LS_ROOT_PATH.'/wp/notices.php';
	include LS_ROOT_PATH.'/classes/class.ls.revisions.php';

	LS_Revisions::init();
}

if( ! class_exists('KM_PluginUpdatesV3') ) {
	require_once LS_ROOT_PATH.'/classes/class.km.autoupdate.plugins.v3.php';
}

// Register [layerslider] shortcode
LS_Shortcode::registerShortcode();


// Add default skins.
// Reads all sub-directories (individual skins) from the given path.
LS_Sources::addSkins(LS_ROOT_PATH.'/static/layerslider/skins/');

// Popup
LS_Popups::init();


// Setup auto updates. This class also has additional features for
// non-activated sites such as fetching update info.
$GLOBALS['LS_AutoUpdate'] = new KM_PluginUpdatesV3( array(
	'name' 			=> 'LayerSlider WP',
	'repoUrl' 		=> LS_REPO_BASE_URL,
	'root' 			=> LS_ROOT_FILE,
	'version' 		=> LS_PLUGIN_VERSION,
	'itemID' 		=> LS_MARKETPLACE_ID,
	'codeKey' 		=> 'layerslider-purchase-code',
	'authKey' 		=> 'layerslider-authorized-site',
	'channelKey' 	=> 'layerslider-release-channel'
));


// Load locales
add_action('plugins_loaded', 'layerslider_plugins_loaded');
function layerslider_plugins_loaded() {
	load_plugin_textdomain('LayerSlider', false, LS_PLUGIN_SLUG . '/locales/' );
}


// Offering a way for authors to override LayerSlider resources by
// triggering filter and action hooks after the theme has loaded.
add_action('after_setup_theme', 'layerslider_after_setup_theme');
function layerslider_after_setup_theme() {

	// Set the LS_ROOT_URL constant
	$url = apply_filters('layerslider_root_url', plugins_url('', __FILE__));
	define('LS_ROOT_URL', $url);

	// Trigger the layerslider_ready action hook
	layerslider_loaded();

	// Backwards compatibility for theme authors
	LS_Config::checkCompatibility();
}



// Sets up LayerSlider as theme-bundled version by
// disabling certain features and hiding premium notices.
function layerslider_set_as_theme() {

	LS_Config::setAsTheme();
}
