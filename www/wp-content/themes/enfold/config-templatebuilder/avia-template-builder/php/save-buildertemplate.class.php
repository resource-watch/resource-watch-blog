<?php
/**
* Central Class for creating and saving template snippets via ajax
*/

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

if ( !class_exists( 'aviaSaveBuilderTemplate' ) ) {

    class aviaSaveBuilderTemplate
	{
	   var $builder;
	
		function __construct($builder)
		{
			$this->builder = $builder;
			
			if($this->builder->disable_drag_drop == true) return;
			
			$this->actions_and_filters();
		    
		}
		
		/** 
		* filter and action hooks
		*/
		protected function actions_and_filters()
		{
		    $ver = AviaBuilder::VERSION;
		
            #js
			wp_enqueue_script('avia_template_save_js' , AviaBuilder::$path['assetsURL'].'js/avia-template-saving.js' , array('avia_element_js'), $ver, TRUE );
			
			#ajax
			add_action('wp_ajax_avia_ajax_save_builder_template', array($this,'save_builder_template'), 10);
			add_action('wp_ajax_avia_ajax_delete_builder_template', array($this,'delete_builder_template'), 10);
			add_action('wp_ajax_avia_ajax_fetch_builder_template', array($this,'fetch_builder_template'), 10);
			
			
		}
        
		
		/** 
		* save button html
		*/
		public function create_save_button()
        {
            $names = $this->template_names();
            $list = "";
            if(empty($names))
            {
                $list = "<li class='avia-no-template'>" .__('No Templates saved yet','avia_framework' ) ."</li>\n";
            }
            else
            {
                foreach($names as $name)
                {
                    $list .= "<li><a href='#'>{$name}</a><span class='avia-delete-template'></span></li>\n";
                }
            }
            
            $output  = "";
            $output .= "<div class='avia-template-save-button-container avia-attach-template-save avia-hidden-dropdown'>";
            $output .= "    <a class='open-template-button button' href='#open'>".__('Templates','avia_framework' )."</a>";
            $output .= "    <div class='avia-template-save-button-inner'> <span class='avia-arrow'></span>";
            $output .= "        <a class='save-template-button button button-primary button-large' href='#save'>".__('Save Entry as Template','avia_framework' )."</a>";
            $output .= "        <div class='avia-template-list-wrap'>";
            $output .= "            <span class='avia-tempaltes-miniheader'>".__('Load Template','avia_framework' ).":</span>";
            $output .= "            <ul>";
            $output .=                 $list;
            $output .= "            </ul>";
            $output .= "        </div>";
            $output .= "    </div>";
            $output .= "</div>";
            
            return $output;
        }

        
        /**
		 * Helper function that fetches all template names
		 *
		 */
        protected function template_names()
        {
            $templates  = $this->get_meta_values();
            $names      = array();
            
            foreach($templates as $template)
            {
                $name = explode("}}}", $template);
                $names[] = str_replace('{{{', "", $name[0]);
            }
            
            natcasesort($names);
            return $names;
        }
        
        
         /**
		 * Ajax Function that checks if template can be saved
		 *
		 */
        public function save_builder_template($name = "%", $value = "")
		{   

            check_ajax_referer('avia_nonce_save','avia-save-nonce');
            
            $name   = isset($_POST['templateName'])     ? $_POST['templateName']    : $name;
            $value  = isset($_POST['templateValue'])    ? $_POST['templateValue']   : $value;
            $id     = AviaStoragePost::get_custom_post('template_builder_snippets');
            
            $key = $this->generate_key($name);
            $old = $this->get_meta_values($key);

            
            if(!empty($old))
            {
                echo __('Template name already in use. Please delete the template with this name first or choose a different name', 'avia_framework' );
            }
            else
            {
				Avia_Builder()->get_shortcode_parser()->set_builder_save_location( 'none' );
                $value = ShortcodeHelper::clean_up_shortcode($value);
                $result = update_post_meta($id, $key, '{{{'.$name.'}}}'.$value);
                echo 'avia_template_saved';
            }
            
            die();
		}
		
        /**
		 * Ajax Function that deletes a template
		 *
		 */
        public function delete_builder_template($name = "%")
		{
		    check_ajax_referer('avia_nonce_save','avia-save-nonce');
		
            $name   = isset($_POST['templateName'])     ? $_POST['templateName']    : $name;
            $id     = AviaStoragePost::get_custom_post('template_builder_snippets');

            
            $key = $this->generate_key($name);
            $result = delete_post_meta($id, $key);
            echo 'avia_template_deleted';
            die();
		}
		
		/**
		 * Retrieve a saved template via ajax. The JS will then insert it into the canvas area
		 *
		 */
		public function fetch_builder_template()
		{
		    $error  = false;
            $name   = isset($_POST['templateName']) ? $_POST['templateName'] : false;
            if(empty($name)) 
                $error = true;
            
            $key = $this->generate_key($name);
            $template = $this->get_meta_values($key);
            
            if(empty($template)) $error = true;
            
            
            if($error)
            {
                echo "avia_fetching_error";
            }
            else
            {
                $text = str_replace('{{{'.$name.'}}}','',$template[0]);
                $return = $this->builder->text_to_interface($text);
                
                echo $return;
            }
            
            die();
		}
		
		
		/**
		 * Helper function that creates the post meta key
		 *
		 */
        protected function generate_key($name)
        {
            return "_avia_builder_template_".str_replace(" ", "_", strtolower($name));
        }
        
        
        /**
		 * Helper function that fetches all meta values with a specific key (cross post)
		 *
		 */
		protected function get_meta_values( $key = '_avia_builder_template_%' ) 
		{
            global $wpdb;
            if( empty( $key ) ) return;
            
            $compare_by = strpos($key, '%') !== false ? "LIKE" : "=";
            $id     = AviaStoragePost::get_custom_post('template_builder_snippets');
    
            $r = $wpdb->get_col( $wpdb->prepare( "
                SELECT meta_value FROM {$wpdb->postmeta}
                WHERE  meta_key {$compare_by} '%s'
                AND post_id = '%s'
            ", $key, $id) );

            return $r;
        }
		
		
		
		
		
				
	} // end class

} // end if !class_exists