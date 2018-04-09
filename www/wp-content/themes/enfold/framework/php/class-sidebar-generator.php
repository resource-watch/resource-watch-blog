<?php  if (  ! defined( 'AVIA_FW' ) ) exit( 'No direct script access allowed' );
/**
 * This file holds the avia_sidebar class which is needed to build sidebars on the fly
 *
 * @author		Christian "Kriesi" Budschedl
 * @copyright	Copyright ( c ) Christian Budschedl
 * @link		http://kriesi.at
 * @link		http://aviathemes.com
 * @since		Version 1.9
 * @package 	AviaFramework
 */


/**
 * AVIA Sidebar
 * A simple class that adds a "add sidebar area" form to the widget page and allows to create widgets on the fly
 * 
 */
 
if( ! class_exists( 'avia_sidebar' ) )
{
	class avia_sidebar
	{
	    var $paths     = array();
	    var $sidebars  = array();
	    var $stored    = "";
	    
	    //constructor, makes sure that we only load most of the stuff on the widget page, except for the register sidebar function
		function __construct()
		{
		    $this->paths['css']   = AVIA_CSS_URL;
		    $this->paths['js']    = AVIA_JS_URL;
		    $this->stored         = 'avia_sidebars';
		    $this->title          = THEMENAME ." ". __('Custom Widget Area','avia_framework');
		    
			add_action('load-widgets.php', array(&$this, 'load_assets') , 5 );
			add_action('widgets_init', array(&$this, 'register_custom_sidebars') , 1000 );
			add_action('wp_ajax_avia_ajax_delete_custom_sidebar', array(&$this, 'delete_sidebar_area') , 1000 );
		}
		
		
		
		//load backend css, js and add hooks to the widget page
		function load_assets()
		{
			add_action('admin_print_scripts', array(&$this, 'template_add_widget_field') );
			add_action('load-widgets.php', array(&$this, 'add_sidebar_area'), 100);
			
			wp_enqueue_script('avia_sidebar' , $this->paths['js'] . 'avia_sidebar.js');  
			wp_enqueue_style( 'avia_sidebar' , $this->paths['css'] . 'avia_sidebar.css');
		}
		
		
		
		//js template that gets attached to the widget area so the user can add widget names
		function template_add_widget_field()
		{
		    $nonce =  wp_create_nonce ('avia-delete-custom-sidebar-nonce');
            $nonce = '<input type="hidden" name="avia-delete-custom-sidebar-nonce" value="'.$nonce.'" />';
		
            echo "\n<script type='text/html' id='avia-tmpl-add-widget'>";
            echo "\n  <form class='avia-add-widget' method='POST'>";
            echo "\n  <h3>". $this->title ."</h3>";
            echo "\n    <span class='avia_style_wrap'><input type='text' value='' placeholder = '".__('Enter Name of the new Widget Area here')."' name='avia-add-widget' /></span>";
            echo "\n    <input class='avia_button' type='submit' value='".__('Add Widget Area')."' />";
            echo "\n    ".$nonce;
            echo "\n  </form>";
            echo "\n</script>\n";
		}

		
		
		
		//adds a sidebar area to the database
		function add_sidebar_area()
		{
            if(!empty($_POST['avia-add-widget']))
            {
                $this->sidebars = get_option($this->stored);
                $name           = $this->get_name($_POST['avia-add-widget']);
                
                if(empty($this->sidebars)) 
                {
                    $this->sidebars = array($name);
                }
                else
                {
                    $this->sidebars = array_merge($this->sidebars, array($name));
                }
                
                update_option($this->stored, $this->sidebars);
                wp_redirect( admin_url('widgets.php') );
                die();
            }
		}
		
		//delete a sidebar area from the database
		function delete_sidebar_area()
		{
            check_ajax_referer('avia-delete-custom-sidebar-nonce');
		      
            if(!empty($_POST['name']))
            {
                $name = stripslashes($_POST['name']);
                $this->sidebars = get_option($this->stored);
                
                if(($key = array_search($name, $this->sidebars)) !== false) 
                {
                    unset($this->sidebars[$key]);
                    update_option($this->stored, $this->sidebars);
                    echo "sidebar-deleted";
                }
            }
            
            die();
		}
		
		
		
		//checks the user submitted name and makes sure that there are no colitions
		function get_name($name)
		{
            if(empty($GLOBALS['wp_registered_sidebars'])) return $name;
		
		    $taken = array();
            foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar )
		    {
                $taken[] = $sidebar['name'];
		    }
		    
            if(empty($this->sidebars)) $this->sidebars = array();
		    $taken = array_merge($taken, $this->sidebars);
		    
		    if(in_array($name, $taken))
		    {
                 $counter  = substr($name, -1);  
                 $new_name = "";
                    
                if(!is_numeric($counter))
                {
                    $new_name = $name . " 1";
                }
                else
                {
                    $new_name = substr($name, 0, -1) . ((int) $counter + 1);
                }
                
                $name = $this->get_name($new_name);
		    }
		    
		    return $name;
		}
		
		
		
		//register custom sidebar areas
		function register_custom_sidebars()
		{
            if(empty($this->sidebars)) $this->sidebars = get_option($this->stored);

            $args = array(
				'before_widget' => '<div id="%1$s" class="widget clearfix %2$s">', 
				'after_widget'  => '</div>', 
				'before_title'  => '<h3 class="widgettitle">', 
				'after_title'   => '</h3>'
				);
			
			
				
			$args = apply_filters('avia_custom_widget_args', $args);
            
            if(is_array($this->sidebars))
            {
	            foreach ($this->sidebars as $sidebar)
				{	
				    $args['name']  	= $sidebar;

				    $args['id']  	= avia_backend_safe_string($sidebar,'-'); 				    
				    $args['class'] 	= 'avia-custom';
					register_sidebar($args);
				}
			}
		}
	}
}









