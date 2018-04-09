<?php
/**
* Class that lets you add new tiny mce buttons
*
* recources:
* http://wp.tutsplus.com/tutorials/theme-development/wordpress-shortcodes-the-right-way/
*/

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

if ( !class_exists( 'avia_tinyMCE_button' ) ) 
{
	class avia_tinyMCE_button
	{
		static $count = 0;
		var $button;
		
		function __construct($button = array())
		{
			$defaults 		= array(
									'id'			 => '',
									'title'			 => '',
									'image'			 => '',
									'js_plugin_file' => '',
									'shortcodes'	 => array() 
									);
									
			$this->button 	= array_merge($defaults, $button);
			
			$this->add_button();
		}
		
		// add button
		function add_button() 
		{  
			if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') && self::$count == 0)  
			{  
				add_filter( 'mce_external_plugins' 	, array( &$this, 'add_javascript' ) );  
				add_filter( 'mce_buttons' 			, array( &$this, 'display_in_editor' ) );  
				add_filter( 'admin_print_scripts' 	, array( &$this, 'create_js_globals' ) );  
				self::$count ++;
			}  
		}  
		
		//displays all buttons that are added to the $buttons array in the tinymce visual editor
		function display_in_editor($buttons) 
		{  
			array_push($buttons, $this->button['id']);
			return $buttons;  
		}  
		
		
		
		// add the javascript that holds the tinyce plugin
		function add_javascript($plugin_array) 
		{  
			$plugin_array[$this->button['id']] = $this->button['js_plugin_file'];
			
			return $plugin_array;  
		} 
		
		//print js globals so the tinymce plugin can fetch them
		function create_js_globals()
		{
			$theme = wp_get_theme();
	 		
	 		global $post_ID;
			echo "\n <script type='text/javascript'>\n /* <![CDATA[ */  \n";
			echo "var avia_globals = avia_globals || {};\n";
			echo "    avia_globals.sc = avia_globals.sc || {};\n";
			echo "    avia_globals.sc['".$this->button['id']."'] = [];\n";
			echo "    avia_globals.sc['".$this->button['id']."'].title = '".$this->button['title']."';\n";
			echo "    avia_globals.sc['".$this->button['id']."'].image = '".$this->button['image']."';\n";
			echo "    avia_globals.sc['".$this->button['id']."'].config = [];\n";
			foreach($this->button['shortcodes'] as $config)
			{    
			    if(empty($config['tinyMCE']['disable']))
			    {
				    echo "    avia_globals.sc['".$this->button['id']."'].config['".$config['php_class']."'] = ".json_encode($config).";\n";
			    }
			}
			echo "/* ]]> */ \n";
			echo "</script>\n \n ";
		}
	
	
		
	} // end class

} // end if !class_exists