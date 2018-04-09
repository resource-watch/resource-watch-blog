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

$ajax_method  = $tg_grid_data['ajax_method'];
$source_type  = $tg_grid_data['source_type'];
	
if ($ajax_method != 'on_scroll' && $source_type == 'post_type') {

	$big   = 999999999;
			
	$pagination   = null;
	$next_text    = $tg_grid_data['pagination_next'];
	$prev_text    = $tg_grid_data['pagination_prev'];
	$current_page = (get_query_var('paged')) ? max(1, get_query_var('paged')) : max(1, get_query_var('page'));
	$total_pages  = $tg_grid_data['max_num_pages'];
			
	$ajax = $tg_grid_data['ajax_pagination'];
	$type = $tg_grid_data['pagination_type'];
	$type = ($ajax) ? 'ajax' : $type;
		
	switch ($type) {
		case 'number':

			$show_all  = $tg_grid_data['pagination_show_all'];
			$end_size  = $tg_grid_data['pagination_end_size'];
			$mid_size  = $tg_grid_data['pagination_mid_size'];
			$prev_next = $tg_grid_data['pagination_prev_next'];

			$pages = paginate_links(array(
				'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
				'format'    => '?paged=%#%',
				'current'   => $current_page,
				'total'     => $total_pages,
				'show_all'  => $show_all,
				'end_size'  => $end_size,
				'mid_size'  => $mid_size,
				'type'      => 'array',
				'prev_next' => $prev_next,
				'prev_text' => $prev_text,
				'next_text' => $next_text,
			));

			if(is_array($pages)) {
				$pagination .= '<div class="tg-pagination-holder">';
					$pagination .= '<ul class="tg-pagination-number">';
					$first = 'first';
					foreach ($pages as $page) {
							preg_match("'<(.*?)>(.*?)</(.*?)>'si", $page, $matches);
							$page_nb = (isset($matches[2]) && (int)$matches[2]) ? (int) $matches[2] : 0;
							$page_nb = ' data-page="'.esc_attr($page_nb).'"';
							$current = ' data-current="'.esc_attr($current_page).'"';
							$pagination .= '<li class="tg-page '.esc_attr($first).'"'.$page_nb.$current.'>'.str_replace(array('page-numbers', 'current'), array('tg-page-number tg-nav-color tg-nav-border tg-nav-font','tg-page-current'), $page).'</li>';
							$first = null;
					}
					$pagination .= '</ul>';
				$pagination .= '</div>';
			}
			break;
					
		case 'button':
			if ($total_pages > 1) {
				if ($current_page == 1) {
					$prev_link = null;
					$next_link = '<a href="'. esc_url(get_pagenum_link($current_page+1)) .'" class="tg-nav-color">'. esc_attr($next_text) .'</a>';
				} else if ($current_page == $total_pages) {
					$prev_link = '<a href="'. esc_url(get_pagenum_link($current_page-1)) .'" class="tg-nav-color">'. esc_attr($prev_text) .'</a>';
					$next_link = null;
				} else {
					$prev_link = '<a href="'. esc_url(get_pagenum_link($current_page-1)) .'" class="tg-nav-color">'. esc_attr($prev_text) .'</a>';
					$next_link = '<a href="'. esc_url(get_pagenum_link($current_page+1)) .'" class="tg-nav-color">'. esc_attr($next_text) .'</a>';
				}
				$pagination .= '<div class="tg-pagination-holder">';
					$pagination .= (!empty($prev_link)) ? '<div class="tg-pagination-prev tg-nav-color tg-nav-border tg-nav-font">'. $prev_link .'</div>' : null;
					$pagination .= (!empty($next_link)) ? '<div class="tg-pagination-next tg-nav-color tg-nav-border tg-nav-font">'. $next_link .'</div>' : null;
				$pagination .= '</div>';
			}
			break;
				
		case 'ajax':
			$current = ' tg-page-current';
			$pagination .= '<div class="tg-pagination-holder">';
				$pagination .= '<ul class="tg-pagination-number">';
				for ($i = 1; $i <= $total_pages; $i++) {
					$pagination .= '<li class="tg-page">';
						$pagination .= '<span class="tg-page-number tg-page-ajax tg-nav-color tg-nav-border tg-nav-font'.esc_attr($current).'" data-page="'.esc_attr($i-1).'">'.esc_attr($i).'</span>';
					$pagination .= '</li>';
					$current = null;
				}
				$pagination .= '</ul>';
			$pagination .= '</div>';
			break;
	}
	
	echo $pagination;
		
}