<?php  if ( ! defined('AVIA_FW')) exit('No direct script access allowed');
/**
 * This file holds the shortcode generating class. The class is a modified version of the free plugin VisualShortcodes.com
 * The classname was changed to avoid any conflicts with plugins that are maybe installed
 * @since		Version 1.0
 * @package 	AviaFramework
 */


/*

    Copyright 2010 VisualShortcodes.com  (email : info@visualshortcodes.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

class avia_shortcodes{
	
	function __construct() {
		
		add_action( 'admin_init', array( &$this, 'action_admin_init' ) );
		add_action( 'wp_ajax_scn_check_url_action', array( &$this, 'ajax_action_check_url' ) );
		add_action( 'admin_print_scripts', array( &$this, 'extra_shortcodes' ),20);
		add_action( 'admin_print_scripts', array( &$this, 'extra_styles' ),20);
		add_action( 'admin_print_scripts', array( &$this, 'avia_preview_nonce' ),20);
	}
	
	function action_admin_init() {
		
		if(get_theme_support( 'avia-disable-default-shortcodes' ) == true) return;
		
		if ( current_user_can( 'edit_posts' ) 
		  && current_user_can( 'edit_pages' ) 
		  && get_user_option('rich_editing') == 'true' )  {
		  	
			add_filter( 'mce_buttons',          array( &$this, 'filter_mce_buttons'          ) );
			add_filter( 'mce_external_plugins', array( &$this, 'filter_mce_external_plugins' ) );
			//add_filter( 'wp_fullscreen_buttons', array( &$this, 'filter_mce_external_plugins' ) );
			
			wp_register_style('scnStyles', $this->plugin_url() . 'css/styles.css');
			wp_enqueue_style('scnStyles');
		}
	}
	
	function avia_preview_nonce()
	{	
		if(!current_user_can('edit_files')) return;
		
		$nonce = wp_create_nonce ('avia_shortcode_preview');
	
		echo "\n <script type='text/javascript'>\n /* <![CDATA[ */  \n";
		echo "var avia_shortcode_preview  = '".$nonce."'; \n /* ]]> */ \n ";
		echo "</script>\n \n ";
	}
	
	
	function filter_mce_buttons( $buttons ) {
		
		array_push( $buttons, '|', 'scn_button');
		return $buttons;
	}
	
	function filter_mce_external_plugins( $plugins ) {
		
		//if we are using tinymce 4 or higher change the javascript file
		global $tinymce_version;
		
		if(version_compare($tinymce_version[0], 4, ">="))
		{
			$plugins['ShortcodeNinjaPlugin'] = $this->plugin_url() . 'tinymce/editor_plugin.js';
		}
		else
		{
        	$plugins['ShortcodeNinjaPlugin'] = $this->plugin_url() . 'tinymce/editor_plugin_3.js';
        }
        return $plugins;
	}
	
	/**
	 * Returns the full URL of this plugin including trailing slash.
	 */
	function plugin_url() {
		
		return AVIA_PHP_URL.'avia_shortcodes/';
	}
	
	
	// AJAX ACTION ///////////////
	
	/**
	 * Checks if a given url (via GET or POST) exists.
	 * Returns JSON
	 * 
	 * NOTE: for users that are not logged in this is not called.
	 *       The client receives <code>-1</code> in that case.
	 */
	function ajax_action_check_url() {

		$hadError = true;

		$url = isset( $_REQUEST['url'] ) ? $_REQUEST['url'] : '';

		if ( strlen( $url ) > 0  && function_exists( 'get_headers' ) ) {
				
			$file_headers = @get_headers( $url );
			$exists       = $file_headers && $file_headers[0] != 'HTTP/1.1 404 Not Found';
			$hadError     = false;
		}

		echo '{ "exists": '. ($exists ? '1' : '0') . ($hadError ? ', "error" : 1 ' : '') . ' }';

		die();
	}
	
	function extra_shortcodes()
	{
		$theme_support = get_theme_support( 'avia-shortcodes' );
		
		if(is_array($theme_support) && !empty($theme_support[0]))
		{
			$supports = "";
			
			foreach($theme_support[0] as $main_key => $supportitem)
			{
				$supports .= $main_key.":";
				if(is_array($supportitem))
				{
					$supports .= "{";
					foreach($supportitem as $key => $subitem)
					{
						$supports .=  $key.": '".$subitem."', ";
					}
					$supports = rtrim($supports,', ');
					$supports .= "}, ";
				}
				else
				{
					
					$supports .= "'";
					$supports .=  $supportitem;
					$supports .= "', ";
				}
				
				
			}
			
			$supports = rtrim($supports, ', ');
			
			echo "\n <script type='text/javascript'>\n /* <![CDATA[ */  \n";
			echo "avia_framework_globals.shortcodes = { $supports }; \n /* ]]> */ \n ";
			echo "</script>\n \n ";
		}
	}
	
	function extra_styles()
	{
		global $avia_config;
		if(isset($avia_config['backend_style']))
		{
			//$string = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $avia_config['backend_style']);
			$string = preg_replace('/\r|\n|\t/', ' ', $avia_config['backend_style']);
		
			echo "\n <script type='text/javascript'>\n /* <![CDATA[ */  \n";
			echo "avia_framework_globals.backend_style = '".$string."'; \n /* ]]> */ \n ";
			echo "</script>\n \n ";
		}
		
	}

}

new avia_shortcodes();
?>
