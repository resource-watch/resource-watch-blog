<?php  if ( ! defined('AVIA_FW')) exit('No direct script access allowed');
/**
 * This file holds the class that creates the meta boxes for posts, pages and other custom post types
 *
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright (c) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.0
 * @package 	AviaFramework
 */

/**
 *
 */


if( !class_exists( 'avia_meta_box' ) )
{


	/**
	 *  The meta box class holds all methods necessary to create and sva and edit meta boxes for new posts, pages and custom post types
	 *  @package 	AviaFramework
	 */
 
	class avia_meta_box
	{
		/**
		 * Default boxes holds the information which meta boxes to create after the init_boxes method was called
		 * @var obj
		 */
		var $default_boxes;
		
		/**
		 * $box_elements holds the information which elements to add to each meta box
		 * @var obj
		 */
		var $box_elements;
		
		/**
		 * Object of class avia_htmlhelper, necessary to render the different elements
		 * @var obj
		 */
		var $html;
		
		/**
		 * A saftey check to prevent wordpress from calling the save function twice. it seems it gets applied once for each meta box which is not necessary here
		 * @var bool
		 */
		var $saved = false;
		
		/**
		 * A check to prevent wordpress from adding the hidden data to each metabox, since its unneccesary
		 * @var bool
		 */
		var $hidden_data_set = false;
		
		/**
		 * The theme name in escaped version so the options get only saved to the active theme
		 * @var string
		 */
		var $meta_prefix = false;
		
		
		/**
		 * The constructor 
		 * checks if we are currently viewing a post creation site and hooks into the admin_menu as well as into the save_post to create and safe the meta boxes
		 * It also creates the html object necessary to render the boxes 
		 */
		function __construct($avia_superobject)
		{	
			
			if(basename( $_SERVER['PHP_SELF']) == "post-new.php" 
			|| basename( $_SERVER['PHP_SELF']) == "post.php")
			{	
				$this->superobject = $avia_superobject;
				$this->html = new avia_htmlhelper($avia_superobject);
				$this->html->context = "metabox";
				$this->meta_prefix = avia_backend_safe_string($avia_superobject->base_data['prefix']);
				
				add_action('admin_menu', array(&$this, 'init_boxes'));
				add_action('save_post', array(&$this, 'save_post'));

			}
		}
		
		
		/**
		 * Meta Box initialization
		 * This function checks if we already got metabox data stored in the posts meta table or if we need to get the data from the config file
		 * We then loop over the retrieved option array and create the according meta boxes, The callback for each metabox is set to create_meta_box
		 * which renders the elements within the box. To know which box we are currently rendering a callback argument is passed on initialization
		 */
		function init_boxes()
		{	
			if(isset($_GET['post']))
			{
				$postId = $_GET['post'];
			}
			else
			{
				$postId = "";
			}
			
			//load the options array
			include( AVIA_BASE.'/includes/admin/register-admin-metabox.php' );
			
			if(isset($boxes) && isset($elements))
			{
				$this->default_boxes = apply_filters('avia_metabox_filter',$boxes);
				$this->box_elements  = apply_filters('avia_metabox_element_filter',$elements);
				
				//loop over the box array
				foreach($this->default_boxes as $key => $box)
				{				
					foreach ($box['page'] as $area)
					{	
						$box['iteration'] = $key;				
						add_meta_box( 	
							$box['id'], 							// HTML 'id' attribute of the edit screen section 
							$box['title'],							// Title of the edit screen section, visible to user 
							array(&$this, 'create_meta_box'),		// Function that prints out the HTML for the edit screen section. 
							$area, 									// The type of Write screen on which to show the edit screen section ('post', 'page', etc) 
							$box['context'], 						// The part were box is shown: ('normal', 'advanced', or 'side').				
							$box['priority'],						// The priority within the context where the boxes should show ('high' or 'low')
							array('avia_current_box'=>$box) 	// callback arguments so we know which box we are in
						);  
					}
				}
			}
		}
		
		
		
		/**
		 * Meta Box Creation
		 * This function iterates over the options array and creates the elments for each array entry
		 */
		function create_meta_box($currentPost, $metabox)
		{	
			global $post;
			$output = "";
			$box = $metabox['args']['avia_current_box'];
			
			if(!is_object($post)) return;
			
			$key = '_avia_elements_'.$this->superobject->option_prefix;
			
			if(current_theme_supports( 'avia_post_meta_compat' )) 
			{
				$key = '_avia_elements_theme_compatibility_mode'; //actiavates a compatibility mode for easier theme switching and keeping post options
			}

			
			$custom_fields = get_post_meta($post->ID, $key, true);
			$custom_fields = apply_filters('avia_meta_box_filter_custom_fields', $custom_fields, $post->ID);
			

			//calls the helping function based on value of 'type'
			foreach ($this->box_elements as $element)
			{	
				if($element['slug'] == $box['id'])
				{
					if (method_exists($this->html, $element['type']))
					{	
						//replace default values
						if(isset($custom_fields[$element['id']]))
						{
							$element['std'] = $custom_fields[$element['id']];
						}
					
						$output .= '<div class="avia_meta_box avia_meta_box_'.$element['type'].' meta_box_'.$box['context'].'">';
						$output .= $this->html->render_single_element($element);
						
						if($element['type'] != 'visual_group_start')
							$output .= '</div>';
							
						if($element['type'] == 'visual_group_end')
							$output .= '</div>';
					}
				}
			}
			//creates hidden data, nonce fields etc
			if(!$this->hidden_data_set)
			{
				$output .= $this->html->hidden_data();
				$this->hidden_data_set = true;
			}
			echo $output;
			
		}
		
		
		/**
		 * Meta box saving 
		 * This function hooks into the native wordpress post saving. Once a user saves a post the function first checks if we got new cloned option sets, 
		 * creates them and saves them to the post meta table. That way each post can have an individual set of options. Then we iterate over each array
		 * entry and save, edit or delete the according post data
		 */
		function save_post()
		{
			if(isset($_POST['post_ID']))
			{
				$must_check = false;
				
				if(!is_array($this->default_boxes) || !isset($_POST['post_ID']) || !isset($_POST['post_type']) || $this->saved) return;
				
				//check if a metabox was attached to this post type
				foreach($this->default_boxes as $default_box)
				{
					if(in_array( $_POST['post_type'] ,$default_box['page']))
					{
						$must_check = true;
					}
				}
		
				if(!$must_check) return;
				
				if(function_exists('check_ajax_referer')) { check_ajax_referer('avia_nonce_save_metabox','avia-nonce'); }
	
				//check if we got an options array and a post id or if it was already saved: if wordpress does an ajax save or creates a new page one of them might be unavailable
				
				
				//check which capability is needed to edit the current post/page
				$post_id = $_POST['post_ID'];
				$capability = "edit_post";
				
				if ( 'page' == $_POST['post_type'] ) { $capability = "edit_page"; }
				
				//does the user have the capability?
				if ( !current_user_can( $capability, $post_id  )) return $post_id ;
				

				
				$this->saved = true;
				$meta_array = array();
				
				foreach($this->box_elements as $box)
				{
					foreach($_POST as $key=>$value)
					{
						if(strpos($key, $box['id']) !== false)
						{							
							if(strpos($key, 'on_save_') !== false)
							{
								$function = str_replace('on_save_', "", $key);
								$meta_array = apply_filters('avia_filter_save_meta_box_'.$function, $meta_array, $_POST);
							}
						
						
							$meta_array[$key] = $value;
						}
					}
				}
				
				$result = avia_ajax_save_options_create_array($meta_array, true);
				update_post_meta($post_id , '_avia_elements_'.$this->superobject->option_prefix, $result);
				
				//also save the data to a neutral field that can be used for compatibility with other themes
				update_post_meta($post_id , '_avia_elements_theme_compatibility_mode', $result);
				
				//hook in case the value should be processed otherwise by an external function (example: slideshow first entry should be saved as post thumb)
				do_action('avia_meta_box_save_post', $post_id, $result);
			}
		}
	}
}


