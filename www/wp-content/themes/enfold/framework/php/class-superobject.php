<?php  if ( ! defined('AVIA_FW')) exit('No direct script access allowed');
/**
 * This file holds the avia_superobject class which is the core of the framework
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright (c) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.0
 * @package 	AviaFramework
 */


/**
 * AVIA Superobject
 *
 * This class loads the default data of the files in the theme_option_pages folder and builds the option pages accordingly.
 * The class is responsible for loading the options data and adding it to the $avia superobject.
 *
 * The class only gets loaded if it wasnt already defined by a Wordpress Plugin based on the Avia Plugin Framework which uses a similar function set
 *  
 * @package AviaFramework
 * 
 */

if( ! class_exists( 'avia_superobject' ) )
{
	class avia_superobject
	{
	
		/**
		 * This object holds basic information like theme or plugin name, version, description etc
		 * @var obj
		 */
		var $base_data;
		
		
		/**
		 * This object holds the information which parent admin page holds which slugs
		 * @var obj
		 */
		var $subpages = array();
	
	
		/**
		 * After calling the constructor this variable holds the framework data stored in the database & config files to render the frontend
		 * @var array
		 */
		var $options;
		
		/**
		 * prefix for database savings, makes sure that multiple plugins and themes can be installed without overwriting each others options
		 * @var string
		 */
		var $option_prefix;
		
		/**
		 * option pages retrieved from the config files in theme_option_pages, used to create the avia admin options panel.
		 * @var array
		 */
		var $option_pages = array();
		
		/**
		 * option page data retrieved from the config files in theme_option_pages, used to create the items at the avia admin options panel.
		 * @var array
		 */
		var $option_page_data = array();
		
		/**
		 * This object holds the avia style informations for php generated styles in the backend
		 * @var obj
		 */
		var $style;

		
		
	    /**
         * The constructor sets up  $base_data and $option_prefix. It then gets database values and if we are viewing the backend it calls the option page creator as well
         */
		public function __construct( $base_data )
		{	
			$this->base_data = $base_data;
			$this->option_prefix = 'avia_options_'.avia_backend_safe_string( $this->base_data['prefix'] );
			
			//set option array
			$this->_create_option_arrays();
			
			if(current_theme_supports( 'avia_mega_menu' ) ) { new avia_megamenu($this); }
			
			$this->style = new avia_style_generator($this);
			
			add_action('wp_footer',array(&$this, 'set_javascript_framework_url'));
			
			if( is_admin() ) 
			{
				add_action('admin_print_scripts',array(&$this, 'set_javascript_framework_url'));
				new avia_adminpages($this);
				new avia_meta_box($this);
				new avia_wp_export($this);
			}
			
			if(get_theme_support( 'avia_sidebar_manager' )) new avia_sidebar();
		}

		
		
		
		
		/**
         *  Create the config options to render the admin pages, merge the config files with the database.
         *  @todo: perform a deep merge of nested arrays
         */
		protected function _create_option_arrays()
		{
			//in case we got an option file as well include it and set the options for the theme
			include(AVIA_BASE.'/includes/admin/register-admin-options.php');
			if(isset($avia_pages)) $this->option_pages = apply_filters( 'avf_option_page_init', $avia_pages);
			if(isset($avia_elements)) $this->option_page_data = apply_filters( 'avf_option_page_data_init', $avia_elements);
			
			//retrieve option pages that were built dynamically as well as those elements
			$dynamic_pages 	  = get_option($this->option_prefix.'_dynamic_pages');
			$dynamic_elements = get_option($this->option_prefix.'_dynamic_elements');
			
			//merge them together
			if(is_array($dynamic_pages))	 $this->option_pages = array_merge($this->option_pages, $dynamic_pages);
			if(is_array($dynamic_elements))  $this->option_page_data = array_merge($this->option_page_data, $dynamic_elements);
			

			
			//saved option values		
			$database_option = get_option($this->option_prefix);
			
			//create an array that tells us which parent pages hold which subpages
			foreach($this->option_pages as $page)
			{
				$this->subpages[$page['parent']][] = $page['slug'];
			}
			
			//iterate over all non dynamic option pages for default values
			foreach($avia_pages as $page)
			{
				if(!isset($database_option[$page['parent']]) || $database_option[$page['parent']] == "") 
				{	
					$database_option[$page['parent']] = $this->extract_default_values($this->option_page_data, $page, $this->subpages);
				}
			}
			
			/*
			 *   filter in case user wants to manipulate the default array 
			 *	 (eg: stylswitch plugin wants to filter the options and overrule them)
			 */
			$this->options = apply_filters( 'avia_filter_global_options', $database_option );
		
			
		}
		
		public function reset_options()
		{
			unset($this->options, $this->subpages, $this->option_page_data, $this->option_pages);
			$this->_create_option_arrays();
		}
		
		/**
		 *  Extracts the default values from the option_page_data array in case no database savings were done yet
		 *  The functions calls itself recursive with a subset of elements if groups are encountered within that array
		 */
		public function extract_default_values($elements, $page, $subpages)
		{
			$values = array();
			foreach($elements as $element)
			{
				if(in_array($element['slug'], $subpages[$page['parent']]))
				{
					if($element['type'] == 'group')
					{
						$values[0][$element['id']] = $this->extract_default_values($element['subelements'], $page, $subpages);
					}
					else if(isset($element['id']))
					{
						if(!isset($element['std'])) $element['std'] = "";
						$values[$element['id']] = $element['std'];
						
					}
				}
			}
			
			return $values;
		}
		

		
		/**
         * This function is executed when the admin header is printed and will add the avia_framework_globals to javascript 
         * The avia_framework_globals object contains information about the framework
         */
		function set_javascript_framework_url()
		{
			echo "\n <script type='text/javascript'>\n /* <![CDATA[ */  \n";
			echo "var avia_framework_globals = avia_framework_globals || {};\n";
			echo "    avia_framework_globals.frameworkUrl = '".AVIA_FW_URL."';\n";
			echo "    avia_framework_globals.installedAt = '".AVIA_BASE_URL."';\n";
			echo "    avia_framework_globals.ajaxurl = '".apply_filters('avia_ajax_url_filter', admin_url( 'admin-ajax.php' ))."';\n";
			echo "/* ]]> */ \n";
			echo "</script>\n \n ";
		}
		
		
	}
}






