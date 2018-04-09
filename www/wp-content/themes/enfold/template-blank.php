<?php 
	/*
	Template Name: Blank - No Header, no Footer
	*/
	
	if ( !defined('ABSPATH') ){ die(); }

/*
 * A blank Template that allows you to build landing pages, coming soon pages etc
 */	
	 
 global $avia_config;
 $avia_config['template'] = "avia-blank"; //important part. this var is checked in header and footer php and if set prevents them from rendering. also an additional class is applied to the body
 
 
 
 
 if(!empty($avia_config['conditionals']['is_builder']))
 {
 	$avia_config['conditionals']['is_builder_template'] = true;
 	get_template_part('template-builder');
    exit();
 }
 else
 {
 	get_template_part('page');
    exit();
 }