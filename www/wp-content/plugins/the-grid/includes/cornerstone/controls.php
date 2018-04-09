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

require_once(TG_PLUGIN_PATH . '/includes/wpml.class.php');
			
$WPML = new The_Grid_WPML();
$WPML_meta_query = $WPML->WPML_meta_query();
$post_args = array(
	'post_type'      => 'the_grid',
	'post_status'    => 'any',
	'posts_per_page' => -1,
	'orderby'        => 'modified',
	'meta_query' => array(
	'relation' => 'AND',
		$WPML_meta_query
	),
	'suppress_filters' => true,
	'no_found_rows' => true,
	'cache_results' => false
);
			
$grids   = get_posts($post_args);
$grid_nb = count($grids);
$count = 0;
$first_grid = null;
$choices = array();
			
if(!empty($grids)){
	foreach($grids as $grid){
		$choice    = array( 'value' => $grid->post_title, 'label' => $grid->post_title);
		$choices[] = $choice;
		if ($count == 0) {
			$first_grid = $grid->post_title;
		}
		$count++;
	}
			
}

return array(
	'common' => array( '!id', '!class', '!style' ),
	'name' => array(
		'type'    => 'select',
		'ui' => array(
			'title'   => __( 'Select a grid from the following list:', 'tg-text-domain' ),
		),
		'options' => array(
			'choices' => $choices
		)
	),
);