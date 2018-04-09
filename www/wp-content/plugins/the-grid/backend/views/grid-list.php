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

$base = new The_Grid_Base();

// WPML query
$WPML              = new The_Grid_WPML();
$WPML_flags        = $WPML->WPML_flags();
$WPML_query_lang   = $WPML->WPML_query_lang();
$WPML_meta_query   = $WPML->WPML_meta_query();
 
$type      = 'the_grid';
$meta_key  = 'the_grid_favorite';
$order     = get_option('the_grid_order', 'DSC');
$orderby   = get_option('the_grid_orderby', 'modified');
$number    = get_option('the_grid_number', '5');
$pagenum   = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$offset    = ( $pagenum - 1 ) * $number;
$the_grids = null;
$my_query  = null;

$args = array(
	'post_type'           => $type,
	'ignore_sticky_posts' => 1,
	'posts_per_page'      => $number,
	'paged'               => $pagenum,
	'order'               => $order,
	'orderby'             => $orderby,
	'meta_query' => array(
		'relation' => 'AND',
        array(
            'key' => $meta_key
        ),
		$WPML_meta_query
    ),
	'no_found_rows' => true,
	'cache_results' => false
);

$my_query  = get_posts($args);

$args['posts_per_page'] = -1;
$args['fields'] = 'ids';
$nb_grids  = count(get_posts($args));
$nb_pages  = ceil($nb_grids/$number);
$page_links = paginate_links( array(
	'base'      => add_query_arg( 'pagenum', '%#%' ),
	'format'    => '',
	'add_args'  => array('limit' => $number),
	'prev_text' => __( '&lsaquo;', 'tg-text-domain' ),
	'next_text' => __( '&rsaquo;', 'tg-text-domain' ),
	'total'     => $nb_pages,
	'current'   => $pagenum,
	'type'      => 'array'
));

$pages = null;
$index = 0;
if ($page_links) {
	foreach($page_links as $page_link) {
		if (strpos($page_link,'current') === false) {
			$is_page = strpos($page_link,'</a>');
			$page_nb = ($is_page) ? $page_link : $page_links[$index-1];
			$page_nb = preg_match('/pagenum=([^&]*)/', $page_nb, $matches);
			$page_nb = ($is_page) ? $matches[1] : $matches[1]+1;
			$page_link  = str_replace('<span ', '<span data-page-nb="'.esc_attr($page_nb).'" data-action="tg_page_nb" ', $page_link);
			$page_link  = str_replace('<a ', '<a data-page-nb="'.esc_attr($page_nb).'" data-action="tg_page_nb" ', $page_link);
		}
		$pages .= $page_link.' ';
		$index++;
	}
}
$page_links = $pages;

$sort  = '<div class="tg-sort-table">';
	$sort .= '<span class="tg-sort-down" data-order="DSC" data-action="tg_order"></span>';
	$sort .= '<span class="tg-sort-up" data-order="ASC" data-action="tg_order"></span>';
$sort .= '</div>';

$grid_list  = '<div id="tg-grid-list-holder">';

	$grid_list .= $WPML_flags;

	$grid_list .= '<div id="tg-grid-list-inner">';
	
		$grid_list .= '<table class="wp-list-table widefat fixed" id="tg-grids-list-holder">';
		
			$grid_list .= '<thead>';
				$grid_list .= '<tr>';
				
					$grid_list .= '<th width="35px" data-orderby="meta_value modified">';
					$grid_list .= $sort;
					$grid_list .= '</th>';
					
					$grid_list .= '<th width="40px">'. __('ID', 'tg-text-domain') .'</th>';
					
					if (function_exists('icl_get_languages')) {
						$grid_list .= '<th width="25px"></th>';
					}
					
					$grid_list .= '<th width="20%" data-orderby="title">';
						$grid_list .= __('Name', 'tg-text-domain');
						$grid_list .= $sort;
					$grid_list .= '</th>';
						
					$grid_list .= '<th width="22%" data-orderby="id">';
						$grid_list .= __('Shortcode', 'tg-text-domain');
						$grid_list .= $sort;
					$grid_list .= '</th>';
					
					$grid_list .= '<th width="300px" >'. __('Actions', 'tg-text-domain') .'</th>';
					
					$grid_list .= '<th width="20%">'. __('Settings', 'tg-text-domain') .'</th>';
					
					$grid_list .= '<th width="12%" data-orderby="modified">';
						$grid_list .= __('Modified', 'tg-text-domain');
						$grid_list .= $sort;
					$grid_list .= '</th>';
					
				$grid_list .= '</tr>';
			$grid_list .= '</thead>';
	

		foreach ($my_query as $grid ) :
			$new       = null;
			$to_time   = strtotime(mysql2date('Y-m-d g:i:s', $grid->post_date));
			$from_time = strtotime(current_time('Y-m-d g:i:s'));
			$ago_time  =  round(abs($to_time - $from_time) / 60,2);
			if ($ago_time < 10) {
				$new = '<div class="tg-grid-list-new">'. __('New!', 'tg-text-domain') .'</div>';
			}
			$grid_ID   = $grid->ID;
			$grid_tile = $grid->post_title;
			$favorited   = get_post_meta($grid_ID, 'the_grid_favorite', true);
			$source_type = get_post_meta($grid_ID, 'the_grid_source_type', true);
			$post_types  = get_post_meta($grid_ID, 'the_grid_post_type', true);
			$post_types  = (isset($post_types) && is_array($post_types)) ? $post_types : array('post' => 'post');
			if ($source_type == 'post_type' || empty($source_type)) {
				$posts = null;
				foreach ($post_types as $post_type) {
					if (post_type_exists($post_type)) {
						$obj  = get_post_type_object($post_type);
						$name = $obj->labels->name;
						$posts .= ', '.$name;
					}
				}
			} else {
				$posts = ', '.$source_type;
			}
			$post_type = ucwords(substr($posts,2));
			$style     = ucfirst(get_post_meta($grid_ID, 'the_grid_style', true));
			$layout    = get_post_meta($grid_ID, 'the_grid_layout', true);
			$layout    = ($layout) ? ucwords($layout) : 'Vertical';
			$skins     = json_decode(get_post_meta($grid_ID, 'the_grid_skins', true), TRUE);
			$post_skin = null;
			if (is_array($skins)) {
				foreach ($skins as $skin) {
					$post_skin .= ', '.$skin;
				}
			} else {
				$post_skin = $skins;
			}
			$skins     = ucwords(substr($post_skin,2));
			$settings  = $post_type.', '.$style.', '.$layout.', '.$skins;

			$the_grids .= '<tr>';
			$the_grids .= '<td class="tg-grid-list-favorite"><i class="dashicons dashicons-star-empty '.$favorited.'" data-grid-id="'. $grid_ID .'" data-favorite="'.$favorited.'" data-action="tg_favorite"></i></td>';
			$the_grids .= '<td class="tg-grid-list-id">'. $grid_ID .'</td>';
			if (function_exists('icl_get_languages')) {
				$WPML_flag_data = $WPML->WPML_flag_data($grid_ID);
				$the_grids .= '<td width="25px">';
				$the_grids .= '<img src="'.$WPML_flag_data['url'].'" alt="'.$WPML_flag_data['alt'].'"/>';
				$the_grids .= '</td>';
			}
			$the_grids .= '<td class="tg-grid-list-name"><span>'. $grid_tile . $new .'</span></td>';
			$the_grids .= '<td class="tg-grid-list-sc">[the_grid name="'. $grid_tile .'"]</td>';
			$the_grids .= '<td class="tg-grid-list-button">';
			$the_grids .= '<a class="tg-button tg-edit" href="'. admin_url( 'admin.php?page=the_grid_settings&id='.$grid_ID.$WPML->WPML_post_query_lang($grid_ID)) .'">';
			$the_grids .= '<i class="dashicons dashicons-admin-tools"></i>';
			$the_grids .= __('Edit', 'tg-text-domain') .'</a>';
			$the_grids .= '<a class="tg-button tg-clone" data-grid-id="'. $grid_ID .'" data-action="tg_clone">';
			$the_grids .= '<i class="dashicons dashicons-images-alt2"></i>';
			$the_grids .= __('Clone', 'tg-text-domain') .'</a>';
			$the_grids .= '<a class="tg-button tg-delete" data-grid-id="'. $grid_ID .'" data-action="tg_delete">';
			$the_grids .= '<i class="dashicons dashicons-trash"></i>';
			$the_grids .= __('Delete', 'tg-text-domain') .'</a>';
			$the_grids .= '</td>';
			$the_grids .= '<td class="tg-grid-list-settings">'. $settings .'</td>';
			$the_grids .= '<td class="tg-grid-list-date">'. $grid->post_modified .'</td>';
			$the_grids .= '</tr>';
	endforeach;

			$grid_list .= '<tbody class="tg-grids-list">';
				$grid_list .= $the_grids;
			$grid_list .= '</tbody>';
		$grid_list .= '</table>';

		$grid_list .= '<div id="tg-info-box">';
			$grid_list .= '<div class="tg-info-overlay"></div>';
			$grid_list .= '<div class="tg-info-inner">';
				$grid_list .= '<div class="tg-info-box-msg"></div>';
			$grid_list .= '</div>';
		$grid_list .= '</div>';
		
	$grid_list .= '</div>';

	$nb_query  = '<select class="tg-list-number" data-action="tg_per_page">';
		$nb_query .= '<option '.selected(  5, $number, false ).' value="5" >5</option>';
		$nb_query .= '<option '.selected( 10, $number, false ).' value="10">10</option>';
		$nb_query .= '<option '.selected( 25, $number, false ).' value="25">25</option>';
		$nb_query .= '<option '.selected( 50, $number, false ).' value="50">50</option>';
		$nb_query .= '<option '.selected( -1, $number, false ).' value="-1">'.__('All', 'tg-text-domain').'</option>';
	$nb_query .= '</select>';


	$grid_list .= '<div id="tg-pagination-holder">';
		$grid_list .= '<a class="tg-button tg-create" href="'.admin_url( 'admin.php?page=the_grid_settings&create=true'.$WPML_query_lang).'"><i class="dashicons dashicons-plus"></i>'.__('Create a new Grid', 'tg-text-domain').'</a>';
		if ( $page_links ) {
		$grid_list .= '<div class="tg-pages-holder">';
			$grid_list .= '<div class="tg-pages" style="margin: 1em 0">';
				$grid_list .= $page_links;
			$grid_list .= '</div>';
		$grid_list .= '</div>';
		}
		$grid_list .= $nb_query;
	$grid_list .= '</div>';

$grid_list .= '</div>';

// form if empty grid list and for new user
$new_form  = '<div id="tg-grid-list-holder">';
$new_form .= $WPML_flags;
$new_form .= '<div id="tg-empty-grid-list">';
$new_form .= '<h2>'.__('You don\'t have any grid yet!', 'tg-text-domain').'</h2>';
$new_form .= '<a class="tg-button tg-create-empty" href="'.admin_url( 'admin.php?page=the_grid_settings&create=true'.$WPML_query_lang).'"><i class="dashicons dashicons-plus"></i>'.__('Create a Grid', 'tg-text-domain').'</a>';
$new_form .= '<a class="tg-button" id="tg-import-demo" data-action="tg_import_items" data-grid-demo="1"><i class="dashicons dashicons-download"></i>'.__('Import Demo', 'tg-text-domain').'</a>';
$new_form .= '</div>';
$new_form .= '</div>';

$new_form .= '<div id="tg-info-box">';
	$new_form .= '<div class="tg-info-overlay"></div>';
	$new_form .= '<div class="tg-info-inner">';
		$new_form .= '<div class="tg-info-box-msg"></div>';
	$new_form .= '</div>';
$new_form .= '</div>';

// build overview page
$form  = '<div id="tg-grid-list-wrap">';
if (!empty($the_grids)) {
	$form .= $grid_list;
} else {
	$form .= $new_form;
}
$form .= '</div>';

echo $form;

add_option('the_grid_new', true);