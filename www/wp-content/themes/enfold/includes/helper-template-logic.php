<?php

if(!function_exists('avia_modify_front'))
{
	/**
	*
	* This function checks what to display on the frontpage
	* Its a new and much simpler function to redirect front and blog pages, by simply filtering the settings->readings options and replacing them with the avia theme options
	*/

	add_action('init', 'avia_modify_front', 10);
	function avia_modify_front($wp_query)
	{
		if(!is_admin())
		{
			if(avia_get_option('frontpage'))
			{
				add_filter('pre_option_show_on_front', 'avia_show_on_front_filter');
				add_filter('pre_option_page_on_front', 'avia_page_on_front_filter');
				
				if(avia_get_option('blogpage'))
				{
					add_filter('pre_option_page_for_posts', 'avia_page_for_posts_filter');
				}
			}
		}
	}

	function avia_show_on_front_filter($val) { return 'page'; }
	function avia_page_on_front_filter($val) { return avia_get_option('frontpage'); }
	function avia_page_for_posts_filter($val){ return avia_get_option('blog_style') !== 'custom' ? avia_get_option('blogpage') : ""; } //use the layout editor to build a blog?
}


/*
* Function that makes sure that empty searches are sent to the search page as well
*/

if(!function_exists('avia_search_query_filter'))
{
	function avia_search_query_filter($query)
	{
		//don't check query on admin page - otherwise we'll break the sort/filter options on the Pages > All Pages screen, etc.
		if(is_admin()) return;
		
	   	// If 's' request variable is set but empty
		if (isset($_GET['s']) && empty($_GET['s']) && empty($_GET['adv_search']) && $query->is_main_query() && empty($query->queried_object))
		{
			//set all query conditional to false to prevent php notices
			foreach($query as $key => &$query_attr)
			{
				if(strpos($key, 'is_') === 0) $query_attr = false;
			}

			$query->is_search 	= true;
			$query->set( 'post_type', 'fake_search_no_results' );
	   }

	   return $query;

	}
	add_filter('pre_get_posts', 'avia_search_query_filter');
}

/*
*  Function that modifies the breadcrumb navigation of single portfolio entries and single blog entries
*/


if(!function_exists('avia_modify_breadcrumb'))
{
	function avia_modify_breadcrumb($trail)
	{
        $parent = get_post_meta(avia_get_the_ID(), 'breadcrumb_parent', true);

		if(get_post_type() === "portfolio")
		{
			$page 	= "";
			$front 	= avia_get_option('frontpage');

			if(empty($parent) && !current_theme_supports('avia_no_session_support') && session_id() && !empty($_SESSION['avia_portfolio']))
			{
				$page = $_SESSION['avia_portfolio'];
			}
            else
            {
                $page = $parent;
            }

			if(!$page || $page == $front)
			{
				$args = array( 'post_type' => 'page', 'meta_query' => array(
						array( 'key' => '_avia_builder_shortcode_tree', 'value' => 'av_portfolio', 'compare' => 'LIKE' ) ) );

				$query = new WP_Query( $args );

				if($query->post_count == 1)
				{
					$page = $query->posts[0]->ID;
				}
				else if($query->post_count > 1)
				{
					foreach($query->posts as $entry)
					{
						if ($front != $entry->ID)
						{
							$page = $entry->ID;
							break;
						}
					}
				}
			}

			if($page)
			{
				if($page == $front)
				{
					$newtrail[0] = $trail[0];
					$newtrail['trail_end'] = $trail['trail_end'];
					$trail = $newtrail;
				}
				else
				{
					$newtrail = avia_breadcrumbs_get_parents( $page, '' );
					array_unshift($newtrail, $trail[0]);
					$newtrail['trail_end'] = $trail['trail_end'];
					$trail = $newtrail;
				}
			}
		}
		else if(get_post_type() === "post" && (is_category() || is_archive() || is_tag()))
		{

			$front = avia_get_option('frontpage');
			$blog = !empty($parent) ? $parent : avia_get_option('blogpage');

			if($front && $blog && $front != $blog)
			{
				$blog = '<a href="' . get_permalink( $blog ) . '" title="' . esc_attr( get_the_title( $blog ) ) . '">' . get_the_title( $blog ) . '</a>';
				array_splice($trail, 1, 0, array($blog));
			}
		}
		else if(get_post_type() === "post")
		{
			$front 			= avia_get_option('frontpage');
			$blog 			= avia_get_option('blogpage');
			$custom_blog 	= avia_get_option('blog_style') === 'custom' ? true : false;
			
			if(!$custom_blog)
			{
				if($blog == $front)
				{
					unset($trail[1]);
				}
			}
			else
			{
				if($blog != $front)
				{
					$blog = '<a href="' . get_permalink( $blog ) . '" title="' . esc_attr( get_the_title( $blog ) ) . '">' . get_the_title( $blog ) . '</a>';
					array_splice($trail, 1, 0, array($blog));
				}
			}
		}
		
		return $trail;
	}


	add_filter('avia_breadcrumbs_trail','avia_modify_breadcrumb');
}




if(!function_exists('avia_layout_class'))
{

/*
* support function that checks if the current page
* should have a post or page layout and returns the
* string so avia_template_set_page_layout can check it
*
* the function is called for each main layout div
* and then delivers the grid classes defined in functions.php
*/

	function avia_layout_class($key, $echo = true)
	{
		global $avia_config;

		if(!isset($avia_config['layout']['current']['main'])) { avia_set_layout_array(); }

		$return = $avia_config['layout']['current'][$key];

		if( $echo == true )
		{
            echo $return;
		}
		else
		{
            return $return;
        }
	}
}

if(!function_exists('avia_offset_class'))
{

/*
* retrieves the offset length of an element based on the current page layout
*/
	function avia_offset_class($key, $echo = true)
	{
		$alpha  = "";
		$offset = avia_layout_class($key, false);
		if(strpos($offset, 'alpha') !== false)
		{
			$offset = str_replace('alpha',"",$offset);
			$alpha = " alpha";
		}

		$offset = 'offset-by-'.trim($offset).$alpha;
		if( $echo == true ){ echo $offset; } else { return $offset; }
	}
}



if(!function_exists('avia_set_layout_array'))
{

	/*
	* The function checks which layout is applied to the template (eg: fullwidth, right_sidebar, left_sidebar)
	* If no layout is applied it checks for the default layout, set in the general options
	*
	* The final value is then stored in $avia_config['layout']['current'] where it can be accessed by the avia_layout function
	*/


	function avia_set_layout_array($post_type = false, $post_id = false)
	{
		global $avia_config;

		//check which string to use
		$result = false;
		$layout = 'blog_layout';
		
		if(empty($post_id)) $post_id = avia_get_the_ID();
		
		if(is_page() || is_search() || is_404() || is_attachment()) $layout = 'page_layout';
		if(is_archive()) $layout = 'archive_layout';
		if(is_single()) $layout = 'single_layout';

		//on a single page check if the layout is overwritten
		if(is_singular())
		{
            $result = get_post_meta($post_id, 'layout', true);
		}

		//if we got no result from the previous get_pst_meta query or we are not on a single post get the setting defined on the option page
		if(!$result)
		{
            $result = avia_get_option($layout);
		}

		//if we stil got no result, probably because no option page was saved
		if(!$result)
		{
            $result = 'sidebar_right';
		}
		
		if($result)
		{
			$avia_config['layout']['current'] = $avia_config['layout'][$result];
			$avia_config['layout']['current']['main'] = $result;
		}
		
		$avia_config['layout'] = apply_filters('avia_layout_filter', $avia_config['layout'], $post_id);
	}
}


if(!function_exists('avia_has_sidebar'))
{
	function avia_has_sidebar()
	{
		global $avia_config;

		return strpos($avia_config['layout']['current']['main'], 'sidebar') !== false ? true : false;
	}
}
