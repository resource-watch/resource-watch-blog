<?php
/*
Plugin Name: Avia Template Builder
Description: The Template Builder helps you create modern and unqiue page layouts with the help of a drag and drop interface
Version: 0.9.5
Author: Christian "Kriesi" Budschedl
Author URI: http://kriesi.at
Text Domain: avia_framework
License: 
*/



require_once( dirname(__FILE__) . '/php/template-builder.class.php' );

$builder = Avia_Builder(); 

//activates the builder safe mode. this hides the shortcodes that are built with the content builder from the default wordpress content editor. 
//can also be set to "debug", to show shortcode content and extra shortcode container field
$builder->setMode( 'safe' );
