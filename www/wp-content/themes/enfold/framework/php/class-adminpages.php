<?php  if ( ! defined('AVIA_FW')) exit('No direct script access allowed');
/**
 * This file holds the avia_adminpages class which is needed to build the avia admin option pages for the wordpress backend
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright (c) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.0
 * @package 	AviaFramework
 */


/**
 * AVIA Adminpages
 *
 * This class sets the javascript and css links, hooks into wordpress to create the option pages and calls the html creating class to render the different form elements
 *  
 * @package AviaFramework
 * 
 */
 
if( ! class_exists( 'avia_adminpages' ) )
{
	class avia_adminpages
	{
		/**
		 * This object holds the $avia_superobject with all the previously stored informations like theme/plugin data, options data, default values etc
		 * @var obj
		 */
		var $avia_superobject;
	
		/**
         * avia_adminpages Constructor
         *
         * The constructor sets up the superobject and then hooks into wordpress and creates the option pages based on the $this->avia_superobject->option_page_data array.
         * The method that gets attached to the hook is attach_options_to_menu.
         */
		function __construct(&$avia_superobject)
		{
			$this->avia_superobject = $avia_superobject;
			add_action('admin_menu', array(&$this, 'attach_options_to_menu'));
			add_action('admin_menu', array(&$this, 'non_option_page_scripts'));
		}
		
		
		/**
		* Register javascripts in the framework/js folder so they can easily be called when needed 
		*/
		function add_scripts()
		{	
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'jquery-ui-sortable');
			
			//get new wp35 uploader but only on admin option pages. on all other pages the script is called by wordpress
			if(function_exists('wp_enqueue_media') && (isset($_REQUEST['page']) && $_REQUEST['page'] == 'avia'))
				wp_enqueue_media();
		
			$files = avia_backend_load_scripts_by_folder( AVIA_JS );
			foreach ( $files as $index => $file ) 
			{ 
				$file_info = pathinfo($file);
				
				if(isset($file_info['extension']) && $file_info['extension'] == "js")
				{
					$filename = basename($file_info['basename'], ".".$file_info['extension']) ;
					wp_enqueue_script(  $filename, AVIA_JS_URL . $file , false, AV_FRAMEWORK_VERSION ); 
				}
			}
			

		}
		
		/**
		* Print css stylesheets in the framework/css folder to the admin head
		*/
		function add_styles()
		{
			wp_enqueue_style( 'thickbox' );
		
			$files = avia_backend_load_scripts_by_folder( AVIA_CSS );
			
			foreach ( $files as $index => $file ) 
			{ 
				$file_info = pathinfo($file);
				if(isset($file_info['extension']) && $file_info['extension'] == "css")
				{
					$filename = basename($file_info['basename'], ".".$file_info['extension']) ;
					wp_enqueue_style($filename , AVIA_CSS_URL . $file, false, AV_FRAMEWORK_VERSION); 
				}
			}
			
			//load the new css styles if the theme supports them
			if(current_theme_supports('avia_improved_backend_style'))
			{
				wp_enqueue_style(  'avia_admin_new', AVIA_CSS_URL . 'conditional_load/avia_admin_modern.css',  false, AV_FRAMEWORK_VERSION); 
			}
		}
		
		
		
		/**
		* This function adds the scripts and styles to non framework generated page (for example the media uploader or default posts/pages)
		*/
		function non_option_page_scripts()
		{
			if(basename( $_SERVER['PHP_SELF']) == "post-new.php" 
			|| basename( $_SERVER['PHP_SELF']) == "post.php"
			|| basename( $_SERVER['PHP_SELF']) == "widgets.php"
			|| basename( $_SERVER['PHP_SELF']) == "media-upload.php")
			{	
				$this->add_styles();
				$this->add_scripts();
			}
		}
		
		
		/**
		* attach_options_to_menu
		*
		* Sorts the $this->avia_superobject->option_page_data array based on index, 
		* then loops over that array and creates a option page for each entry that is a parent.
		* It automatically loads scripts and styles for all option pages as well.
		* The very first entry that is called will be set as the "master menu" item with the themes name, 
		* then each parent item (including the first one) will be attached to this item
		* For each menu item the $this->initialize function is set as the page rendering function.
		*/
		function attach_options_to_menu()
		{	
			if(!isset($this->avia_superobject->option_pages)) return;
				
			$page_creation_method = 'add_object_page'; //deprecated since 4.5
			if(function_exists('add_menu_page')) { $page_creation_method = 'add_menu_page'; }
		    
			
			foreach( $this->avia_superobject->option_pages as $key => $data_set )
			{
			
				//if its the very first option item make it a main menu with theme or plugin name, then as first submenu add it again with real menu name 
				if($key === 0)
				{	
					$the_title  = apply_filters( 'avia_filter_backend_page_title', $this->avia_superobject->base_data['Title'] );
					$menu_title = apply_filters( 'avia_filter_backend_menu_title', $the_title );
					
					$top_level = $data_set['slug'];
					$avia_page = $page_creation_method(	$the_title, 									// page title
														$menu_title, 									// menu title
														'manage_options', 								// capability
														$top_level, 									// menu slug (and later also database options key)
														array(&$this, 'render_page'),					// executing function
														"dashicons-admin-home",
														26
													);
				}
				
				if($data_set['parent'] == $data_set['slug'])
				{
				
					$avia_page = add_submenu_page(	$top_level,								// parent page slug to attach
													$data_set['title'], 					// page title
													$data_set['title'], 					// menu title
													'manage_options', 						// capability
													$data_set['slug'], 						// menu slug (and later also database options key)
													array(&$this, 'render_page'));			// executing function
				}
				
				if(!empty($avia_page))
				{
					//add scripts and styles to all avia options pages
					add_action('admin_print_scripts-' . $avia_page, array(&$this, 'add_scripts'));
					add_action('admin_print_styles-'  . $avia_page, array(&$this, 'add_styles'));
				}
			}
		}
		
		
		/**
		* render_page
		*
		* This is the function that is called when a framework option page gets opened. 
		* It checks the current page slug and based on that slug filters the $this->avia_superobject->option_page_data options array.
		* All option sets with the same slug get renderd with the help of the avia_htmlhelper class.
		*/
		function render_page()
		{
			

		
			$current_slug = $_GET['page'];
			$firstClass = 'avia_active_container';
			
			//make page title accessible
			foreach( $this->avia_superobject->option_pages as $key => $data_set )
			{
				if($data_set['parent'] == $data_set['slug'] && $data_set['slug'] == $current_slug)
				{
					$this->avia_superobject->currentpage = $data_set['title'];
					break;
				}
			}
			

			$this->avia_superobject->page_slug = $current_slug;
			$html = new avia_htmlhelper($this->avia_superobject);
			
			echo $html->page_header();
			
			foreach($this->avia_superobject->option_pages as $option_page)
			{
				if($current_slug == $option_page['parent'])
				{
					echo $html->create_container_based_on_slug($option_page, $firstClass);
					$firstClass = "";
				}
			}
			
			echo $html->page_footer();
		}
	}
}




