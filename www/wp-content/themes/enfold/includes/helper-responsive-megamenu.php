<?php

if( !class_exists( 'avia_responsive_mega_menu' ) )
{

	/**
	 * The avia walker is the frontend walker and necessary to display the menu, this is a advanced version of the wordpress menu walker
	 * @package WordPress
	 * @since 1.0.0
	 * @uses Walker
	 */
	class avia_responsive_mega_menu extends Walker {
		/**
		 * @see Walker::$tree_type
		 * @var string
		 */
		var $tree_type = array( 'post_type', 'taxonomy', 'custom' );

		/**
		 * @see Walker::$db_fields
		 * @todo Decouple this.
		 * @var array
		 */
		var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

		/**
		 * @var int $columns
		 */
		var $columns = 0;

		/**
		 * @var int $max_columns maximum number of columns within one mega menu
		 */
		var $max_columns = 0;

		/**
		 * @var int $rows holds the number of rows within the mega menu
		 */
		var $rows = 1;

		/**
		 * @var array $rowsCounter holds the number of columns for each row within a multidimensional array
		 */
		var $rowsCounter = array();

		/**
		 * @var string $mega_active hold information whetever we are currently rendering a mega menu or not
		 */
		var $mega_active = 0;

		/**
		 * @var array $grid_array holds the grid classes that get applied to the mega menu depending on the number of columns
		 */
		var $grid_array = array();

		/**
		 * @var stores if we already have an active first level main menu item.
		 */
		var $active_item = false;
		
		
		/**
		 * @var stores if we got a top or a sidebar main menu.
		 */
		var $top_menu = true;
		
		
		/**
		 * @var stores if we got a text menu or a single burger icon
		 */
		var $icon_menu = true;
		
		/**
		 * @var stores if we got a top or a sidebar main menu.
		 */
		var $blog_id = false;
		
		/**
		 * @var stores the number of first level menu items
		 */
		var $first_level_count = 0;
		
		/**
		 * @var stores if mega menu is active
		 */
		var $mega_allowed = true;
		

		/**
		*
		* Constructor that sets the grid variables
		*
		*/
		function __construct($options = array())
		{
			$this->grid_array = array(

				1 => "three units",
				2 => "six units",
				3 => "nine units",
				4 => "twelve units",
				5 => "twelve units",
				6 => "twelve units"
			);
			
			$this->top_menu 	= avia_get_option('header_position','header_top') == 'header_top' ? true : false;
			$this->icon_menu 	= avia_is_burger_menu();
			
			if(avia_get_option('frontpage') && avia_get_option('blogpage'))
			{
				$this->blog_id = avia_get_option('blogpage');
			}
			
			if(isset($options['megamenu']) && $options['megamenu'] == "disabled") $this->mega_allowed = false;
			
			if($this->icon_menu)
			{
				//$this->mega_active = false;
				//$this->mega_allowed = false;
			}
		}


		/**
		 * @see Walker::start_lvl()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
		function start_lvl(&$output, $depth = 0, $args = array()) {
			$indent = str_repeat("\t", $depth);
			if($depth === 0) $output .= "\n{replace_one}\n";
			$output .= "\n$indent<ul class=\"sub-menu\">\n";
		}

		/**
		 * @see Walker::end_lvl()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
		function end_lvl(&$output, $depth = 0, $args = array()) {
			$indent = str_repeat("\t", $depth);
			$output .= "$indent</ul>\n";

			if($depth === 0)
			{
				if($this->mega_active && $this->mega_allowed)
				{

					$output .= "\n</div>\n";
					$output = str_replace("{replace_one}", "<div class='avia_mega_div avia_mega".$this->max_columns." ".$this->grid_array[$this->max_columns]."'>", $output);
					$output = str_replace("{last_item}", "avia_mega_menu_columns_last", $output);

					foreach($this->rowsCounter as $row => $columns)
					{
						$output = str_replace("{current_row_".$row."}", "avia_mega_menu_columns_".$columns." ".$this->grid_array[1], $output);
					}

					$this->columns = 0;
					$this->max_columns = 0;
					$this->rowsCounter = array();

				}
				else
				{
					$output = str_replace("{replace_one}", "", $output);
				}
			}
		}

		/**
		 * @see Walker::start_el()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param int $current_page Menu item ID.
		 * @param object $args
		 */
		function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {
			global $wp_query;

			//set maxcolumns
			if(!isset($args->max_columns)) $args->max_columns = 6;

			$item = apply_filters( 'avf_menu_items' , $item  );
			$item_output = $li_text_block_class = $column_class = "";

			if($depth === 0)
			{
				$this->first_level_count ++;
				$this->mega_active	= get_post_meta( $item->ID, '_menu-item-avia-megamenu', true);
				$style 				= get_post_meta( $item->ID, '_menu-item-avia-style', true);
			}
			
			
			if(!empty($item->url) && strpos($item->url, "[domain]") !== false)
			{
				$replace = str_replace( "http://", "", get_home_url() );
				$replace = str_replace( "https://", "", $replace );
				$item->url = str_replace( "[domain]", $replace, $item->url);
			}


			if($depth === 1 && $this->mega_active && $this->mega_allowed)
			{
				$this->columns ++;

				//check if we have more than $args['max_columns'] columns or if the user wants to start a new row
				if($this->columns > $args->max_columns || (get_post_meta( $item->ID, '_menu-item-avia-division', true) && $this->columns != 1))
				{
					$this->columns = 1;
					$this->rows ++;
					$output .= "\n</ul><ul class=\"sub-menu avia_mega_hr\">\n";
					$output = str_replace("{last_item}", "avia_mega_menu_columns_last", $output);
				}
				else
				{
					$output = str_replace("{last_item}", "", $output);
				}

				$this->rowsCounter[$this->rows] = $this->columns;

				if($this->max_columns < $this->columns) $this->max_columns = $this->columns;


				$title = apply_filters( 'the_title', $item->title, $item->ID );

				if($title != "&#8211;" && trim($title) != "-" && $title != '"-"' ) //fallback for people who copy the description o_O
				{
					$heading_title = do_shortcode($title);
					
					if(!empty($item->url) && $item->url != "#" && $item->url != 'http://')
					{
						$heading_title = "<a href='".$item->url."'>{$title}</a>";
					}

					$item_output .= "<span class='mega_menu_title heading-color av-special-font'>".$heading_title."</span>";
				}


				$column_class  = ' {current_row_'.$this->rows.'} {last_item}';

				if($this->columns == 1)
				{
					$column_class  .= " avia_mega_menu_columns_first";
				}
			}
			else if($depth >= 2 && $this->mega_active && $this->mega_allowed && get_post_meta( $item->ID, '_menu-item-avia-textarea', true) )
			{
				$li_text_block_class = 'avia_mega_text_block ';

				$item_output.= do_shortcode($item->post_content);


			}
			else
			{
				$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
				$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
				$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
				$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
				
				if('inactive' != avia_get_option('markup') )
				{
					$attributes .= ' itemprop="url"';
				}
				

				$item_output .= $args->before;
				$item_output .= '<a'. $attributes .'><span class="avia-bullet"></span>';
				$item_output .= $args->link_before .'<span class="avia-menu-text">'. do_shortcode(apply_filters('the_title', $item->title, $item->ID)) ."</span>". $args->link_after;
				if($depth === 0) 
				{
					if(!empty($item->description))
					{
						$item_output .= '<span class="avia-menu-subtext">'. do_shortcode($item->description) ."</span>";
					}
				
					$item_output .= '<span class="avia-menu-fx"><span class="avia-arrow-wrap"><span class="avia-arrow"></span></span></span>';
				}
				
				$item_output .= '</a>';
				$item_output .= $args->after;
			}
			
			
			

			$class_names = $value = '';
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			if(isset($style)) $classes[] = $style;

			if($depth === 0 && $key = array_search('current-menu-item', $classes))
			{
				if($this->active_item)
				{
					 unset($classes[$key]);
				}
				else
				{
					$this->active_item = true;
				}
			}
			
			

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
			if($depth === 0 && $this->mega_active && $this->mega_allowed) $class_names .= " menu-item-mega-parent ";
			if($depth === 0 ) $class_names .= " menu-item-top-level menu-item-top-level-".$this->first_level_count;
			
			//highlight correct blog page
			if($depth === 0 && $this->blog_id && $this->blog_id == $item->object_id && is_singular('post'))
			{
				$class_names .= " current-menu-item";
			}
			
			
			
			$class_names = ' class="'.$li_text_block_class. esc_attr( $class_names ) . $column_class.'"';
			
			$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';



			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}

		/**
		 * @see Walker::end_el()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Page data object. Not used.
		 * @param int $depth Depth of page. Not Used.
		 */
		function end_el(&$output, $item, $depth = 0, $args = array()) {
			$output .= "</li>\n";
		}
	}
}





if( !function_exists( 'avia_responsive_fallback_menu' ) )
{
	/**
	 * Create a navigation out of pages if the user didnt create a menu in the backend
	 *
	 */
	function avia_responsive_fallback_menu()
	{
		$current = "";
		$exclude = avia_get_option('frontpage');
		if (is_front_page()){$current = "class='current-menu-item'";}
		if ($exclude) $exclude ="&exclude=".$exclude;

		echo "<div class='fallback_menu'>";
		echo "<ul class='avia_mega menu'>";
		echo "<li $current><a href='".get_bloginfo('url')."'>Home</a></li>";
		wp_list_pages('title_li=&sort_column=menu_order'.$exclude);
		echo "</ul></div>";
	}
}
