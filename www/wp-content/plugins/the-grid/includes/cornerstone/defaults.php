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
	'posts_per_page' => 1,
	'meta_query' => array(
		'relation' => 'AND',
		$WPML_meta_query
	),
	'suppress_filters' => true 
);
			
$grid = get_posts($post_args);

$name = (isset($grid[0]) && !empty($grid[0])) ? get_post_meta($grid[0]->ID, 'the_grid_name', true) : '';

return array(
	'name'  => $name,
	'style' => '',
	'class' => '',
	'id'    => ''
);