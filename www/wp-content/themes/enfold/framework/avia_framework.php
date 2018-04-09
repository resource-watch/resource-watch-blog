<?php
/**
 * AVIA Framework
 *
 * A flexible Wordpress Framework, created by Kriesi
 *
 * This file includes the superobject class and loads the parameters neccessary for the backend pages.
 * A new $avia superobject is then created that holds all data necessary for either front or backend, depending what page you are browsing
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright (c) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.0
 * @package 	AviaFramework
 * @version 	4.6

*/ 
define( 'AV_FRAMEWORK_VERSION', "4.6" ); 



/**
 *  
 * Action for plugins and functions that should be executed before any of the framework loads
 * 
 */
do_action( 'avia_action_before_framework_init' );
 
 
 
/**
 *  Config File
 *  Load the autoconfig file that will set some 
 *  constants based on the installation type (plugin or theme)
 * 
 */
 
 require( 'php/inc-autoconfig.php' );



/**
 *  Superobject Class
 *  Load the super object class, but only if it hasn't been
 *  already loaded by an avia plugin with newer version
 * 
 */
 
if( ! defined('AVIA_PLUGIN_FW') || ! defined('AVIA_THEME_FW') || ( version_compare(AVIA_THEME_FW, AVIA_PLUGIN_FW, '>=') ) )
{ 
	require( AVIA_PHP.'class-superobject.php' );
}


/**
 *  Include Backend default Function set
 *  Loads the autoincluder function to be able to retrieve the 
 *  predefined page options and to be able to include
 *  files based on option arrays
 * 
 */
 
require( AVIA_PHP.'function-set-avia-backend.php' );


/*
 * ------------------------------------------------------
 *  Load the options array with manually passed functions
 *  in functions.php for theme or plugin specific scripts
 * ------------------------------------------------------
 */
 
 if(isset($avia_autoload) && is_array($avia_autoload)) avia_backend_load_scripts_by_option($avia_autoload);



/*
 * ------------------------------------------------------
 *  Filter the base data array that is passed
 *  upon creation of the superobject
 * ------------------------------------------------------
 */
 
$avia_base_data = apply_filters( 'avia_filter_base_data', $avia_base_data );



/**
 * ------------------------------------------------------
 *  create a new superobject, pass the options name that
 *  should be used to save and retrieve database entries
 * ------------------------------------------------------
 */
 
 $avia = new avia_superobject($avia_base_data);


// ------------------------------------------------------------------------

