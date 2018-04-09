<?php
/**
* Central AviaHelper class which holds quite a few unrelated functions
*/

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

if ( !class_exists( 'AviaHelper' ) ) {
	
	class AviaHelper
	{
		static $cache = array(); 		//holds database requests or results of complex functions
		static $templates = array(); 	//an array that holds all the templates that should be created when the print_media_templates hook is called
		static $mobile_styles = array(); 		//an array that holds mobile styling rules that are appened to the end of the page
		
		/**
    	 * get_url - Returns a url based on a string that holds either post type and id or taxonomy and id
    	 */
    	static function get_url($link, $post_id = false) 
    	{
    		$link = explode(',', $link, 2);
    		
    		if($link[0] == 'lightbox')        
    		{
    			$link = wp_get_attachment_image_src($post_id, apply_filters('avf_avia_builder_helper_lightbox_size','large'));
    			return $link[0];
    		}
    		
    		if(empty($link[1]))
    		{
    			return $link[0];
    		}
    		
    		if($link[0] == 'manually')
    		{
    			if(strpos($link[1], "@") !== false && strpos($link[1], "://") === false){ $link[1] = "mailto:".$link[1]; }
    			return $link[1];
    		}
    		
            if(post_type_exists( $link[0] ))
            {
            	return get_permalink($link[1]);
            }
            
            if(taxonomy_exists( $link[0]  ))  
            {
            	$return = get_term_link(get_term($link[1], $link[0]));
            	if(is_object($return)) $return = ""; //if an object is returned it is a WP_Error object and something was not found
            	return $return;
            } 
            
            
    	}
    	
    	/**
    	 * get_entry - fetches an entry based on a post type and id
    	 */
    	static function get_entry($entry) 
    	{
    		$entry = explode(',', $entry);
    		
    		if(empty($entry[1]))              return false;
    		if($entry[0] == 'manually')        return false;
            if(post_type_exists( $entry[0] ))  return get_post($entry[1]);
    	}
    	
    	/**
    	 * fetch all available sidebars
    	 */
    	static function get_registered_sidebars($sidebars = array(), $exclude = array())
    	{
    		//fetch all registered sidebars and save them to the sidebars array
			global $wp_registered_sidebars;
			
			foreach($wp_registered_sidebars as $sidebar)
			{
				if( !in_array($sidebar['name'], $exclude))
				{
					$sidebars[$sidebar['name']] = $sidebar['name']; 
				}
			}
			
			return $sidebars;
    	}
    	
    	static function get_registered_image_sizes($exclude = array(), $enforce_both = false, $exclude_default = false)
    	{
    		global $_wp_additional_image_sizes;
    		
    		 // Standard sizes
	        $image_sizes = array(   'no scaling'=> array("width"=>"Original Width ", "height"=>" Original Height"),
	        						'thumbnail' => array("width"=>get_option('thumbnail_size_w'), "height"=>get_option('thumbnail_size_h')),
	        						'medium' 	=> array("width"=>get_option('medium_size_w'), "height"=>get_option('medium_size_h')), 
	        						'large' 	=> array("width"=>get_option('large_size_w'), "height"=>get_option('large_size_h')));
	        
	        
	        if(!empty($exclude_default)) unset($image_sizes['no scaling']);
	        
	        if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) )
	                $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes  );
	                
    		$result = array();
    		foreach($image_sizes as $key => $image)
			{
				if( (is_array($exclude) && !in_array($key, $exclude)) || (is_numeric($exclude) && ($image['width'] > $exclude || $image['height'] > $exclude)) || !is_numeric($image['height']))
				{
					if($enforce_both == true && is_numeric($image['height']))
					{
						if($image['width'] < $exclude || $image['height'] < $exclude) continue;
					}
					
					
					$title = str_replace("_",' ', $key) ." (".$image['width']."x".$image['height'].")";
					
					$result[ucwords( $title )] =  $key; 
				}
			}
    		
    		return $result;
    	}
    	
    	static function list_menus()
    	{
    		$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
    		$result = array();
    		
    		if(!empty($menus))
    		{
	    		foreach ($menus as $menu)
	    		{
	    			$result[$menu->name] = $menu->term_id;
	    		}
    		}
    		
    		return $result;
    	}


    	/**
    	 * is_ajax - Returns true when the page is loaded via ajax.
    	 */
    	static function is_ajax() 
    	{
    		if ( defined('DOING_AJAX') )
    			return true;
    
    		return ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) ? true : false;
    	}
		
		
		/**
		 * function that gets called on backend pages and hooks other functions into wordpress
		 *
		 * @return void
		 */
		static function backend()
		{
			add_action( 'print_media_templates', array('AviaHelper', 'print_templates' )); 		//create js templates for AviaBuilder Canvas Elements
		}
		
		
		/**
		 * Helper function that prints an array as js object. can call itself in case of nested arrays
		 *
		 * @return void
		 */
		
		static function print_javascript($objects = array(), $print = true, $passed = "")
		{	
			$output = "";
			if($print) $output .=  "\n<script type='text/javascript' class='av-php-sent-to-frontend'>/* <![CDATA[ */ \n";
			
			foreach($objects as $key => $object)
			{
				if(is_array($object))
				{	
					if(empty($passed))
					{
						$output .= "var {$key} = {};\n";
						$pass    = $key;
					}
					else
					{
						$output .= "{$passed}['{$key}'] = {};\n";
						$pass    = "{$passed}['{$key}']";
					}
					$output .= AviaHelper::print_javascript($object, false, $pass);
				}
				else
				{
					if(!is_numeric($object) && !is_bool($object)) $object = json_encode($object);
					if(empty($object)) $object = "false";
					
					if(empty($passed))
					{
						$output .= "var {$key} = {$object};\n";	
					}
					else
					{
						$output .= "{$passed}['{$key}'] = {$object};\n";
					}
				}
			}
			
			if($print) 
			{
				$output .=  "\n /* ]]> */</script>\n\n";
				echo $output;
			}
			
			return  $output;
		}
		
		
		/**
		 * Helper function that prints all the javascript templates
		 *
		 * @return void
		 */
		
		static function print_templates()
		{
			foreach (self::$templates as $key => $template)
			{
				echo "\n<script type='text/html' id='avia-tmpl-{$key}'>\n";
				echo $template;
				echo "\n</script>\n\n";
			}
			
			//reset the array
			self::$templates = array();
		}
		
		/**
		 * Helper function that creates a new javascript template to be called
		 *
		 * @return void
		 */
		
		static function register_template($key, $html)
		{
			self::$templates[$key] = $html;
		}
		
		
		
		/**
		 * Helper function that fetches all "public" post types.
		 *
		 * @return array $post_types example output: data-modal='true'
		 */
		
		static function public_post_types()
		{
			$post_types 		= get_post_types(array('public' => false, 'name' => 'attachment', 'show_ui'=>false, 'publicly_queryable'=>false), 'names', 'NOT');
			$post_types['page'] = 'page';
			$post_types 		= array_map("ucfirst", $post_types);
			$post_types			= apply_filters('avia_public_post_types', $post_types);
			self::$cache['post_types'] = $post_types;
			
			return $post_types;
		}
		
				
		
		/**
		 * Helper function that fetches all taxonomies attached to public post types.
		 *
		 * @return array $taxonomies
		 */
		
		static function public_taxonomies($post_types = false, $merged = false)
		{	
			$taxonomies = array();
			
			if(!$post_types)
				$post_types = empty(self::$cache['post_types']) ? self::public_post_types() : self::$cache['post_types'];
				
			if(!is_array($post_types))
				$post_types = array($post_types => ucfirst($post_types));
				
			foreach($post_types as $type => $post)
			{
				$taxonomies[$type] = get_object_taxonomies($type);
			}	
			
			$taxonomies = apply_filters('avia_public_taxonomies', $taxonomies);
			self::$cache['taxonomies'] = $taxonomies;
			
			if($merged)
			{
				$new = array();
				foreach($taxonomies as $taxonomy)
				{
					foreach($taxonomy as $tax)
					{
						$new[$tax] = ucwords(str_replace("_", " ",$tax));
					}
				}
				
				$taxonomies = $new;
			}
			
			return $taxonomies;
		}
		
		
	
		/**
		 * Helper function that converts an array into a html data string
		 *
		 * @param array $data example input: array('modal'=>'true')
		 * @return string $data_string example output: data-modal='true'
		 */
		 
		static function create_data_string($data = array())
		{
			$data_string = "";
			
			foreach($data as $key=>$value)
			{
				if(is_array($value)) $value = implode(", ",$value);
				$data_string .= " data-$key='$value' ";
			}
		
			return $data_string;
		}

    	/**
    	* Create a lower case version of a string without spaces so we can use that string for database settings
    	* 
    	* @param string $string to convert
    	* @return string the converted string
    	*/
    	static function save_string( $string , $replace = "_")
    	{
    		$string = strtolower($string);
    	
    		$trans = array(
    					'&\#\d+?;'				=> '',
    					'&\S+?;'				=> '',
    					'\s+'					=> $replace,
    					'ä'						=> 'ae',
    					'ö'						=> 'oe',
    					'ü'						=> 'ue',
    					'Ä'						=> 'Ae',
    					'Ö'						=> 'Oe',
    					'Ü'						=> 'Ue',
    					'ß'						=> 'ss',
    					'[^a-z0-9\-\._]'		=> '',
    					//$replace.'+'			=> $replace, //allow doubles like -- or __
    					$replace.'$'			=> $replace,
    					'^'.$replace			=> $replace,
    					'\.+$'					=> ''
    				  );
    				  
    		$trans = apply_filters('avf_save_string_translations', $trans, $string, $replace);
    
    		$string = strip_tags($string);
    
    		foreach ($trans as $key => $val)
    		{
    			$string = preg_replace("#".$key."#i", $val, $string);
    		}
    		
    		return stripslashes($string);
    	}
		
		/**
		 * Create a lower case version of a string without spaces and special characters so we can use that string for a href anchor link.
		 * Returns a default if the remaining string is empty.
		 * 
		 * @param string $link
		 * @param string $replace
		 * @param string $default
		 * @return string
		 */
		static public function valid_href( $link, $replace = '_', $default = '-' )
		{
			$new_link = AviaHelper::save_string( $link, $replace );
			if( '' == trim( $new_link ) )
			{
				$new_link = $default;
			}
			
			return $new_link;
		}
		
    	
    	/**
		 * Helper function that fetches the active value of the builder. also adds a filter
		 *
		 * @deprecated since version 4.2.1
		 */
		static function builder_status($post_ID)
		{
			_deprecated_function( 'builder_status', '4.2.1', 'AviaBuilder::get_alb_builder_status()');
			
			$status = get_post_meta($post_ID, '_aviaLayoutBuilder_active', true);
			$status = apply_filters('avf_builder_active', $status, $post_ID);
			
			return $status;
		}
		
    	/**
		 * Helper function that builds css styling strings which are applied to html elements
		 *
		 */
		static function style_string($atts, $key = false, $new_key = false, $append_value = "")
		{
			$style_string = "";
			
			//finish the style string by wrapping the arguments into a style string
			if((is_string($atts) || ! $atts ) && false == $key)
			{
				if(!empty($atts))
				{
					$style_string = "style='".$atts."'";
				}
			}
			else //otherwise build only the styling argument
			{
				if(empty($new_key)) $new_key = $key;
				
				if(isset($atts[$key]) && $atts[$key] !== "")
				{
					switch($new_key)
					{
						case "background-image": $style_string = $new_key.":url(".$atts[$key].$append_value."); "; break;
						case "background-repeat": if($atts[$key] == "stretch") $atts[$key] = "no-repeat"; $style_string = $new_key.":".$atts[$key].$append_value."; "; break;
						default: $style_string = $new_key.":".$atts[$key].$append_value."; "; break;
					}
				}
			}
			
			return $style_string;
		}
	
		

	static function backend_post_type()
	{
		global $post, $typenow, $current_screen;
		
		$posttype = "";
		
		//we have a post so we can just get the post type from that
		if ($post && $post->post_type)
		{
			$posttype = $post->post_type;
		}
		//check the global $typenow - set in admin.php
		elseif($typenow)
		{
			$posttype = $typenow;
		}
		//check the global $current_screen object - set in sceen.php
		elseif($current_screen && $current_screen->post_type)
		{
			$posttype = $current_screen->post_type;
		}
		//lastly check the post_type querystring
		elseif(isset($_REQUEST['post_type']))
		{
			$posttype = sanitize_key($_REQUEST['post_type']);
		}
		
		return $posttype;	
	}
	
	
	
	static function av_mobile_sizes($atts = array())
	{
		$result		= array('av_font_classes'=>'', 'av_title_font_classes'=>'', 'av_display_classes' => '', 'av_column_classes' => '');
		$fonts 		= array('av-medium-font-size', 'av-small-font-size', 'av-mini-font-size'); 
		$title_fonts= array('av-medium-font-size-title', 'av-small-font-size-title', 'av-mini-font-size-title'); 
		$displays	= array('av-desktop-hide', 'av-medium-hide', 'av-small-hide', 'av-mini-hide');
		$columns	= array('av-medium-columns', 'av-small-columns', 'av-mini-columns');
		
		
		if(empty($atts)) $atts = array();
		
		foreach($atts as $key => $attribute)
		{
			if(in_array($key, $fonts) && $attribute != "")
			{
				$result['av_font_classes'] .= " ".$key."-overwrite";
				$result['av_font_classes'] .= " ".$key."-".$attribute;
				
				if($attribute != "hidden") self::$mobile_styles['av_font_classes'][$key][$attribute] = $attribute;
			}
			
			if(in_array($key, $title_fonts) && $attribute != "")
			{
				$newkey = str_ireplace('-title', "", $key);
				
				$result['av_title_font_classes'] .= " ".$newkey."-overwrite";
				$result['av_title_font_classes'] .= " ".$newkey."-".$attribute;
				
				
				if($attribute != "hidden") 
				{ 
					self::$mobile_styles['av_font_classes'][$newkey][$attribute] = $attribute;
				}
			}
			
			if(in_array($key, $displays) && $attribute != "")
			{
				$result['av_display_classes'] .= " ".$key;
			}
			
			if(in_array($key, $columns) && $attribute != "")
			{
				$result['av_column_classes'] .= " ".$key."-overwrite";
				$result['av_column_classes'] .= " ".$key."-".$attribute;
			}
		}

		return $result;
	}
	
	
	
	static function av_print_mobile_sizes()
	{
		$print 			= "";
		
		//rules are created dynamically, otherwise we would need to predefine more than 500 csss rules of which probably only 2-3 would be used per page
		$media_queries 	= apply_filters('avf_mobile_font_size_queries' , array(
			
			"av-medium-font-size" 	=> "only screen and (min-width: 768px) and (max-width: 989px)",
			"av-small-font-size" 	=> "only screen and (min-width: 480px) and (max-width: 767px)",
			"av-mini-font-size" 	=> "only screen and (max-width: 479px)",  

 		));
		

		if(isset(self::$mobile_styles['av_font_classes']) && is_array(self::$mobile_styles['av_font_classes']))
		{
			$print .="<style type='text/css'>\n";
			
			foreach($media_queries as $key => $query)
			{
				if( isset(self::$mobile_styles['av_font_classes'][$key]) )
				{
					$print .="@media {$query} { \n";
					
					if( isset(self::$mobile_styles['av_font_classes'][$key]))
					{
						foreach(self::$mobile_styles['av_font_classes'][$key] as $size)
						{
							$print .= ".responsive #top #wrap_all .{$key}-{$size}{font-size:{$size}px !important;} \n";
						}
					}
					
					$print .= "} \n";
				}
			}
			
			$print .="</style>";
		}
		
		return $print; 
	}
		
		
		
	}
	
}
