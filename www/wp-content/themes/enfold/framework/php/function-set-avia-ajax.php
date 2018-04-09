<?php  if ( ! defined('AVIA_FW')) exit('No direct script access allowed');
/**
 * This file holds various ajax functions that hook into wordpress admin-ajax.php script with the generic "wp_".$_POST['action'] hook
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright (c) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.0
 * @package 	AviaFramework
 */


/**
 * Helper that decodes ajax submitted forms
 */
function ajax_decode_deep($value)
{
	$charset = get_bloginfo('charset');
    $value = is_array($value) ? array_map('ajax_decode_deep', $value) : stripslashes(htmlentities(urldecode($value), ENT_QUOTES, $charset));
    return $value;
}



/**
 * This function modifies the option array based on an ajax request and returns the modified option array to the browser
 * If the add method is set the function also returns the element that should be added so jquery can inject it to the dom
 */

 
if(!function_exists('avia_ajax_modify_set'))
{
	function avia_ajax_modify_set()
	{	
		$check = 'avia_nonce_save_backend';
		
		if($_POST['context'] =='metabox')
		{
			$check = "avia_nonce_save_metabox";
		}
		
		check_ajax_referer($check);
		
		if(isset($_POST['ajax_decode'])) $_POST = ajax_decode_deep($_POST);
	
		//add a new set
		if($_POST['method'] == 'add')
		{
			$html = new avia_htmlhelper();
			$sets = new avia_database_set();
			
			if(isset($_POST['context']))
			{
				//change the output context for meta boxes and custom sets
				$html->context = $_POST['context'];
				if($_POST['context'] =='metabox')
				{
					include( AVIA_BASE.'/includes/admin/register-admin-metabox.php' );
					$sets->elements = $elements;
				}
				
				//retrieving a custom set of elements (eg for dynamic elements from a custom file)
				if($_POST['context'] =='custom_set')
				{
					$inclusion_link = sanitize_text_field($_POST['configFile']);
					$link			= false;
					
					switch($inclusion_link)
					{
						case "dynamic" :
						case AVIA_BASE."includes/admin/register-admin-dynamic-options.php" :
						case "includes/admin/register-admin-dynamic-options.php" : $link = AVIA_BASE."includes/admin/register-admin-dynamic-options.php"; break;
						case "one_page": 
						case "includes/admin/register-admin-dynamic-one-page-portfolio.php": $link = AVIA_BASE."includes/admin/register-admin-dynamic-one-page-portfolio.php"; break;

					}
					
					if($link)
					{
						@include($link);
						$sets->elements = $elements;
					}
				}
			}

		
			$element = $sets->get($_POST['elementSlug']);

			if($element)
			{
				if(isset($_POST['context']) && $_POST['context'] =='custom_set')
				{
					$element['slug'] = $_POST['optionSlug'];
					$element['id']   = $_POST['optionSlug'] . $element['id'];
				
					$sets->add_element_to_db($element, $_POST);
				}
				
				if(isset($_POST['std']))
				{
					$element['std'][0] = $_POST['std'];
				}
				
				if(isset($_POST['apply_all']))
				{
					$element['apply_all'] = $_POST['apply_all'];
				}
				
				
				$element['ajax_request'] = 1;
				
				
				
				if(isset($_POST['activate_filter']))
				{
					add_filter('avia_ajax_render_element_filter', $_POST['activate_filter'], 10, 2);
				}
				
				$element = apply_filters('avia_ajax_render_element_filter', $element, $_POST);
				
				//render element for output
				echo "{avia_ajax_element}" .$html->render_single_element($element) ."{/avia_ajax_element}";
				
			}
		}
			

		die();
	}
	
	//hook into wordpress admin.php
	add_action('wp_ajax_avia_ajax_modify_set', 'avia_ajax_modify_set');
}

//helper function for the gallery that fetches all image atachment ids of a post
function avia_ajax_fetch_all($element, $sent_data)
{
	$post_id = $sent_data['apply_all'];
	$args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $post_id); 
	$attachments = get_posts($args);
	

	if($attachments && is_array($attachments))
	{
		$counter = 0;
		$element['ajax_request'] = count($attachments);
		foreach($attachments as $attachment)
		{
			$element['std'][$counter]['slideshow_image'] = $attachment->ID;
			$counter++;
		}
	}


	return $element;
}



/**
 * This function receives the values entered into the option page form elements. All values are submitted via ajax (js/avia_framwork.js)
 * The function first checks if the user is allowed to edit the options with a wp nonce, 
 * then double explodes the post array ( exploding by "&" creates option sets, exploding by "=" the key/value pair). 
 * Those are then stored in the database options table
 */
if(!function_exists('avia_ajax_save_options_page'))
{
	function avia_ajax_save_options_page()
	{
		
		//check if user is allowed to save and if its his intention with a nonce check
		if(function_exists('check_ajax_referer')) { check_ajax_referer('avia_nonce_save_backend'); }
		
		//if we got no post data or no database key abort the script
		if(!isset($_POST['data']) || !isset($_POST['prefix']) || !isset($_POST['slug'])) { die(); }
		
		$optionkey = $_POST['prefix'];
			
		$data_sets = explode("&",$_POST['data']);
		$store_me = avia_ajax_save_options_create_array($data_sets);
		
		$current_options = get_option($optionkey);
		$current_options[$_POST['slug']] = $store_me;
		

		//if a dynamic order was passed by javascript convert the string to an array and re order the items of the set controller to match the order array
		if(isset($_POST['dynamicOrder']) && $_POST['dynamicOrder'] != "")
		{
			global $avia;
			$current_elments = array();
			$options = get_option($optionkey.'_dynamic_elements');	
			
			//split dynamic options into elements of this page and others
			foreach($options as $key => $element)
			{
				if(in_array($element['slug'], $avia->subpages[$_POST['slug']]))
				{
					$current_elments[$key] = $element;
					unset($options[$key]);
				} 
			}
		
		
			$sortedOptions = array();
			$neworder = explode('-__-',$_POST['dynamicOrder']);

			foreach($neworder as $key)
			{
				if($key != "" && array_key_exists($key, $current_elments)) 
				{
					$sortedOptions[$key] = $current_elments[$key];
				}
			}

			
			$options = array_merge($options, $sortedOptions);
			//save the resorted options
			update_option($optionkey.'_dynamic_elements', $options);
		}

		
		//hook in case we want to do somethin with the new options
		do_action( 'avia_ajax_save_options_page', $current_options );
		
		//remove old option set and save those key/value pairs in the database
		update_option($optionkey, $current_options);	
		
		//flush rewrite rules for custom post types
		update_option('avia_rewrite_flush', 1);
		
		//hook in case we want to do somethin after saving
		do_action( 'avia_ajax_after_save_options_page', $current_options );
		
		die('avia_save');
	}
	
	//hook into wordpress admin.php
	add_action('wp_ajax_avia_ajax_save_options_page', 'avia_ajax_save_options_page');
}



/**
 *  avia_ajax_save_options_create_array
 *  
 *  This function uses the data string passed from the ajax script and creates an array with unlimited depth with the key/value pairs
 *  @param array $data_sets This array contains the exploded string that was passed by an ajax script
 *  @return array $store_me The $store_me array holds the array entries necessary to build the front end and is returned so it can be saved to the database
 */
if(!function_exists('avia_ajax_save_options_create_array'))
{
	function avia_ajax_save_options_create_array($data_sets, $global_post_array = false)
	{
		$result = array();
		$charset = get_bloginfo('charset');
		
		
		//iterate over the data sets that were passed
		foreach($data_sets as $key => $set)
		{
			$temp_set = array();
			//if a post array was passed set the array
			if($global_post_array)
			{
				$temp_set[0] = $key;
				$temp_set[1] = $set;
				$set = $temp_set;
			}
			else //if an ajax data array was passed create the array by exploding the key/value pair
			{
				//create key/value pairs
				$set = explode("=", $set);
			}
			
			//escape and convert the value
			$set[1] = stripslashes($set[1]);
			$set[1] = htmlentities(urldecode($set[1]), ENT_QUOTES, $charset);
			
			/*
			 *  check if the element is a group element. 
			 *  If so create an array by exploding the string and then iterating over the results and using them as array keys
			 */
			 
			if($set[0] != "") //values with two colons are reserved for js controlling and saving is not needed 
			{
				if(strpos($set[0], '-__-') !== false)
				{
					$set[0] = explode('-__-',$set[0]);
					
					//http://stackoverflow.com/questions/20259773/nested-numbering-to-array-keys
					avia_ajax_helper_set_nested_value($result, $set[0], $set[1]);
				}
				else
				{
					$result[$set[0]] = $set[1];
				}
			}
		}

	return $result;
	}
}



	
function avia_ajax_helper_set_nested_value(array &$array, $index, $value)
{
    $node = &$array;

    foreach ($index as $path) {
        $node = &$node[$path];
    }

    $node = $value;
}


/**
 * This function resets the whole admin backend, the page is reloaded on success by javascript.
 */
if(!function_exists('avia_ajax_reset_options_page'))
{
	function avia_ajax_reset_options_page()
	{
		//check if user is allowed to reset and if its his intention with a nonce check
		if(function_exists('check_ajax_referer')) { check_ajax_referer('avia_nonce_reset_backend'); }
		
		global $avia, $wpdb;
		$slugs = array($avia->option_prefix, $avia->option_prefix.'_dynamic_elements', $avia->option_prefix.'_dynamic_pages');
		
		//get all option keys of the framework
		/*
		foreach($avia->option_pages as $option_page)
				{
					if($option_page['slug'] == $option_page['parent'])
					{
						$slugs[$avia->option_prefix.'_'.$option_page['slug']] = true;
					}
				}
		*/

		//iterate over all option keys and delete them
		foreach($slugs as $key )
		{
			delete_option($key);
		}
		
		//flush rewrite rules for custom post types
		update_option('avia_rewrite_flush', 1);
		
		//hook in case user wants to execute code afterwards
		do_action('avia_ajax_reset_options_page');
		
		//end php execution and return avia_reset to the javascript
		die('avia_reset');
	}
	
	//hook into wordpress admin.php
	add_action('wp_ajax_avia_ajax_reset_options_page', 'avia_ajax_reset_options_page');
}





/**
 * This function gets an attachment image based on its id and returns the image url to the javascript. Needed for advanced image uploader
 */
if(!function_exists('avia_ajax_get_image'))
{
	function avia_ajax_get_image()
	{
		#backend single post/page/portfolio item: add multiple preview pictures. get a preview picture via ajax request and display it
		
		$attachment_id = (int) $_POST['attachment_id'];
		$attachment = get_post($attachment_id);
		$mime_type = $attachment->post_mime_type;
				
		if (strpos($mime_type, 'flash') !== false || substr($mime_type, 0, 5) == 'video')
		{
			$output = $attachment->guid;
		}
		else
		{
			$output = wp_get_attachment_image($attachment_id, array(100,100));
		}

		die($output);
	}
	
	//hook into wordpress admin.php
	add_action('wp_ajax_avia_ajax_get_image', 'avia_ajax_get_image');
}



if(!function_exists('avia_ajax_get_gallery'))
{
	function avia_ajax_get_gallery()
	{
		#backend single post/page/portfolio item: add multiple preview pictures. get a preview picture via ajax request and display it
		
		$postId = (int) $_POST['attachment_id'];
		$output = "";
		$image_url_array = array();
		
		
		$attachments = get_children(array('post_parent' => $postId,
                    'post_status' => 'inherit',
                    'post_type' => 'attachment',
                    'post_mime_type' => 'image',
                    'order' => 'ASC',
                    'orderby' => 'menu_order ID'));


		foreach($attachments as $key => $attachment) 
		{
			$image_url_array[] = avia_image_by_id($attachment->ID, array('width'=>80,'height'=>80));
		}
		
		if(isset($image_url_array[0]))
		{
			foreach($image_url_array as $key => $img) 
			{
				$output  .= "<div class='avia_gallery_thumb'><div class='avia_gallery_thumb_inner'>".$img."</div></div>";
			}
			
			$output  .= "<div class='avia_clear'></div>";
		}
		


		die($output);
	}
	
	//hook into wordpress admin.php
	add_action('wp_ajax_avia_ajax_get_gallery', 'avia_ajax_get_gallery');
}





/**
 * This function gets the color of an attachment or a url image
 */
if(!function_exists('avia_ajax_get_image_color'))
{
	function avia_ajax_get_image_color()
	{
		#backend single post/page/portfolio item: add multiple preview pictures. get a preview picture via ajax request and display it
		$colorString = "";
		$attachment_id = (int) $_POST['attachment_id'];
		if($attachment_id != 0)
		{
			$src = wp_get_attachment_image_src($attachment_id, array(5500,5500));
			$src = $src[0];
		}
		else
		{
			$src = $_POST['attachment_id'];
		}
		
		if(function_exists('imagecolorat'))
		{
			$extension = substr($src, strrpos($src, '.') + 1);
			switch($extension)
			{
				case 'jpeg': $image = imagecreatefromjpeg($src); break;
				case 'jpg': $image  = imagecreatefromjpeg($src); break;
				case 'png': $image  = imagecreatefrompng($src);  break;
				case 'gif': $image  = imagecreatefromgif($src);  break;
				default: die();
			}
			
			$rgb = imagecolorat($image, 0, 0);
			$colors = imagecolorsforindex($image, $rgb);
			
			$colorString = avia_backend_get_hex_from_rgb($colors['red'],$colors['green'],$colors['blue']);
		}
		
		die($colorString);
	}
	
	//hook into wordpress admin.php
	add_action('wp_ajax_avia_ajax_get_image_color', 'avia_ajax_get_image_color');
}



/**
 * This function is a clone of the admin-ajax.php files case:"add-menu-item" with modified walker. We call this function by hooking into wordpress generic "wp_".$_POST['action'] hook. To execute this script rather than the default add-menu-items a javascript overwrites default request with the request for this script
 */
if(!function_exists('avia_ajax_switch_menu_walker'))
{
	function avia_ajax_switch_menu_walker()
	{	
		if ( ! current_user_can( 'edit_theme_options' ) )
		die('-1');

		check_ajax_referer( 'add-menu_item', 'menu-settings-column-nonce' );
	
		require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
	
		$item_ids = wp_save_nav_menu_items( 0, $_POST['menu-item'] );
		if ( is_wp_error( $item_ids ) )
			die('-1');
	
		foreach ( (array) $item_ids as $menu_item_id ) {
			$menu_obj = get_post( $menu_item_id );
			if ( ! empty( $menu_obj->ID ) ) {
				$menu_obj = wp_setup_nav_menu_item( $menu_obj );
				$menu_obj->label = $menu_obj->title; // don't show "(pending)" in ajax-added items
				$menu_items[] = $menu_obj;
			}
		}
	
		if ( ! empty( $menu_items ) ) {
			$args = array(
				'after' => '',
				'before' => '',
				'link_after' => '',
				'link_before' => '',
				'walker' => new avia_backend_walker,
			);
			echo walk_nav_menu_tree( $menu_items, 0, (object) $args );
		}
		
		die('end');
	}
	
	//hook into wordpress admin.php
	add_action('wp_ajax_avia_ajax_switch_menu_walker', 'avia_ajax_switch_menu_walker');
}


/**
 * This function imports the dummy data from the dummy.xml file
 */
if(!function_exists('avia_ajax_import_data'))
{
	function avia_ajax_import_data()
	{				
		//check if user is allowed to save and if its his intention with a nonce check
		if(function_exists('check_ajax_referer')) { check_ajax_referer('avia_nonce_import_dummy_data'); }

		require_once AVIA_PHP . 'inc-avia-importer.php';

		die('avia_import');
	}
	
	//hook into wordpress admin.php
	add_action('wp_ajax_avia_ajax_import_data', 'avia_ajax_import_data');
}



/**
 * This function imports the parent theme data
 */
if(!function_exists('avia_ajax_import_parent_data'))
{
	function avia_ajax_import_parent_data()
	{	
		//check if user is allowed to save and if its his intention with a nonce check
		if(function_exists('check_ajax_referer')) { check_ajax_referer('avia_nonce_import_parent_settings'); }
		
		if(is_child_theme())
		{	
			global $avia;
			
			$theme  = wp_get_theme();
			$parent = wp_get_theme( $theme->get('Template') );
			$parent_option_prefix = 'avia_options_'.avia_backend_safe_string( $parent->get('Name') );
			
			$parent_options = get_option($parent_option_prefix);
			
			if(empty($parent_options))
			{
				die('No Parent Theme Options Found. There is nothing to import');
			}
			
			update_option($avia->option_prefix, $parent_options);
	
		}
		else
		{
			die('No Parent Theme found');
		}

		die('avia_import');
	}
	
	//hook into wordpress admin.php
	add_action('wp_ajax_avia_ajax_import_parent_settings', 'avia_ajax_import_parent_data');
}


	




/**
 * This function controlls option page creation 
 */
if(!function_exists('avia_ajax_create_dynamic_options'))
{
	function avia_ajax_create_dynamic_options()
	{
		//check if user is allowed to save and if its his intention with a nonce check
		if(function_exists('check_ajax_referer')) { check_ajax_referer('avia_nonce_save_backend'); }
		$options = new avia_database_set();
		
		if($_POST['method'] == 'add_option_page')
		{
			$result = $options->add_option_page($_POST);

			if(is_array($result))
			{
				$html = new avia_htmlhelper();
				$new_slug = $result['slug'];
				$result = "{avia_ajax_option_page}" .$html->create_container_based_on_slug($result) ."{/avia_ajax_option_page}";
				
				if(isset($_POST['defaul_elements']))
				{	
					$elements = unserialize( base64_decode( $_POST['defaul_elements'] ) );
					
					$result .= "{avia_ajax_element}";
					foreach($elements as &$element)
					{
						$element['id']   = $new_slug . $element['id'];
						$element['slug'] = $new_slug;
						
						//create frontend output
						$result .=  $html->render_single_element($element);
						
						//save the element to the database as well
						$options->add_element_to_db($element, $_POST);
					}
					$result .= "{/avia_ajax_element}";

				}
			}
		}
		
		

		
		die($result);
	}
	
	//hook into wordpress admin.php
	add_action('wp_ajax_avia_ajax_create_dynamic_options', 'avia_ajax_create_dynamic_options');
}






/**
 * This function controlls option page deletion
 */
if(!function_exists('avia_ajax_delete_dynamic_options'))
{
	function avia_ajax_delete_dynamic_options()
	{
		//check if user is allowed to save and if its his intention with a nonce check
		if(function_exists('check_ajax_referer')) { check_ajax_referer('avia_nonce_save_backend'); }
		$options = new avia_database_set();

		$options->remove_dynamic_page($_POST);
		
		die("avia_removed_page");
	}
	
	//hook into wordpress admin.php
	add_action('wp_ajax_avia_ajax_delete_dynamic_options', 'avia_ajax_delete_dynamic_options');
}



/**
 * This function controlls option element deletion
 */
if(!function_exists('avia_ajax_delete_dynamic_element'))
{
	function avia_ajax_delete_dynamic_element()
	{
		//check if user is allowed to save and if its his intention with a nonce check
		if(function_exists('check_ajax_referer')) { check_ajax_referer('avia_nonce_save_backend'); }
		$options = new avia_database_set();

		$options->remove_element_from_db($_POST);
		
		die('avia_removed_element');
	}
	
	//hook into wordpress admin.php
	add_action('wp_ajax_avia_ajax_delete_dynamic_element', 'avia_ajax_delete_dynamic_element');
}




if(!function_exists('avia_ajax_verify_input'))
{
	function avia_ajax_verify_input()
	{
		//check if user is allowed to save and if its his intention with a nonce check
		if(function_exists('check_ajax_referer')) { check_ajax_referer('avia_nonce_save_backend'); }
		
		$result = "";
		$callback = "";
		
		global $avia;
		foreach($avia->option_page_data as $option)
		{
			if(isset($option['id']) && $option['id'] == $_POST['key'] && isset($option['ajax']))
			{
				$callback = $option['ajax'];
			}
		}
		
		if(function_exists($callback))
		{
			$js_callback_value = isset($_POST['js_value']) ? $_POST['js_value'] : NULL;
			$result = $callback( $_POST['value'] , true, $js_callback_value );
		}
		
		die($result);
	}
	
	//hook into wordpress admin.php
	add_action('wp_ajax_avia_ajax_verify_input', 'avia_ajax_verify_input');
}







/**
 * This function imports the config file
 */
if(!function_exists('avia_import_config_file'))
{
    add_action('wp_ajax_avia_ajax_import_config_file', 'avia_import_config_file');
    function avia_import_config_file()
    {
        global $avia;

        //check if referer is ok
        if(function_exists('check_ajax_referer')) { check_ajax_referer('avia_nonce_save_backend'); }

        //check if capability is ok
        $cap = apply_filters('avf_file_upload_capability', 'update_plugins');
        if(!current_user_can($cap))
        {
            exit( "Using this feature is reserved for Super Admins. You unfortunately don't have the necessary permissions." );
        }

        $attachment = $_POST['values'];
        $path 		= realpath(get_attached_file($attachment['id']));
        $options 	= @file_get_contents($path);

        if($options)
        {
	        @ini_set('max_execution_time', 1500);
	        
            if(!class_exists('WP_Import'))
            {
                if(!defined('WP_LOAD_IMPORTERS')) define('WP_LOAD_IMPORTERS', true);

                $class_wp_import = AVIA_PHP . 'wordpress-importer/wordpress-importer.php';
                if(file_exists($class_wp_import))
                {
                    require_once($class_wp_import);
                }
            }

            if(class_exists('WP_Import'))
            {
                $class_avia_import = AVIA_PHP . 'wordpress-importer/avia-import-class.php';
                if(file_exists($class_avia_import))
                {
                    require_once($class_avia_import);
                    $avia_import = new avia_wp_import();
                }

                $options = unserialize(base64_decode($options));

                if(is_array($options))
                {
                    foreach($avia->option_pages as $page)
                    {
                        $database_option[$page['parent']] = $avia_import->extract_default_values($options[$page['parent']], $page, $avia->subpages);
                    }

                    if(!empty($database_option))
                    {
                        update_option($avia->option_prefix, $database_option);
                    }
                }
				
				// currently no deletion. seems counter intuitive atm. also since the file upload button will only show txt files user can switch between settings easily
                // wp_delete_attachment($attachment['id'], true); 
            }
        }

        exit('avia_config_file_imported');
    }
}







