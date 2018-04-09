<?php
/**
 * Accordion and toggles
 * 
 * Creates toggles or accordions
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_toggle' ) )
{
    class avia_sc_toggle extends aviaShortcodeTemplate
    {
        static $toggle_id = 1;
        static $counter = 1;
        static $initial = 0;
        static $tags = array();
        static $atts = array();

        /**
         * Create the config array for the shortcode button
         */
        function shortcode_insert_button()
        {
			$this->config['self_closing']	=	'no';
			
            $this->config['name']		= __('Accordion', 'avia_framework' );
            $this->config['tab']		= __('Content Elements', 'avia_framework' );
            $this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-accordion.png";
            $this->config['order']		= 70;
            $this->config['target']		= 'avia-target-insert';
            $this->config['shortcode'] 	= 'av_toggle_container';
            $this->config['shortcode_nested'] = array('av_toggle');
            $this->config['tooltip'] 	= __('Creates toggles or accordions', 'avia_framework' );
        }


        function extra_assets()
        {
            if(is_admin())
            {
                $ver = AviaBuilder::VERSION;
                wp_enqueue_script('avia_tab_toggle_js' , AviaBuilder::$path['assetsURL'].'js/avia-tab-toggle.js' , array('avia_modal_js'), $ver, TRUE );
            }
        }

        /**
         * Popup Elements
         *
         * If this function is defined in a child class the element automatically gets an edit button, that, when pressed
         * opens a modal window that allows to edit the element properties
         *
         * @return void
         */
        function popup_elements()
        {
            $this->elements = array(

				array(
					"type" 	=> "tab_container", 'nodescription' => true
				),
						
				array(
					"type" 	=> "tab",
					"name"  => __("Content" , 'avia_framework'),
					'nodescription' => true
				),
					
                array(
                    "name" => __("Add/Edit Toggles", 'avia_framework' ),
                    "desc" => __("Here you can add, remove and edit the toggles you want to display.", 'avia_framework' ),
                    "type" 			=> "modal_group",
                    "id" 			=> "content",
                    "modal_title" 	=> __("Edit Form Element", 'avia_framework' ),
                    "std"			=> array(

                        array('title'=>__('Toggle 1', 'avia_framework' ), 'tags'=>''),
                        array('title'=>__('Toggle 2', 'avia_framework' ), 'tags'=>''),
                    ),


                    'subelements' 	=> array(

                        array(
                            "name" 	=> __("Toggle Title", 'avia_framework' ),
                            "desc" 	=> __("Enter the toggle title here (Better keep it short)", 'avia_framework' ) ,
                            "id" 	=> "title",
                            "std" 	=> "Toggle Title",
                            "type" 	=> "input"),


                        array(
                            "name" 	=> __("Toggle Content", 'avia_framework' ),
                            "desc" 	=> __("Enter some content here", 'avia_framework' ) ,
                            "id" 	=> "content",
                            "type" 	=> "tiny_mce",
                            "std" 	=> __("Toggle Content goes here", 'avia_framework' ),
                        ),

                        array(
                            "name" 	=> __("Toggle Sorting Tags", 'avia_framework' ),
                            "desc" 	=> __("Enter any number of comma separated tags here. If sorting is active the user can filter the visible toggles with the help of these tags", 'avia_framework' ) ,
                            "id" 	=> "tags",
                            "std" 	=> "",
                            "type" 	=> "input"),

                    )
                ),

                array(
                    "name" 	=> __("Initial Open", 'avia_framework' ),
                    "desc" 	=> __("Enter the Number of the Accordion Item that should be open initially. Set to Zero if all should be close on page load ", 'avia_framework' ),
                    "id" 	=> "initial",
                    "std" 	=> "0",
                    "type" 	=> "input"),


                array(
                    "name" 	=> __("Behavior", 'avia_framework' ),
                    "desc" 	=> __("Should only one toggle be active at a time and the others be hidden or can multiple toggles be open at the same time?", 'avia_framework' ),
                    "id" 	=> "mode",
                    "type" 	=> "select",
                    "std" 	=> "accordion",
                    "subtype" => array( __('Only one toggle open at a time (Accordion Mode)', 'avia_framework' ) =>'accordion', __("Multiple toggles open allowed (Toggle Mode)", 'avia_framework' ) => 'toggle')
                ),

                array(
                    "name" 	=> __("Sorting", 'avia_framework' ),
                    "desc" 	=> __("Display the toggle sorting menu? (You also need to add a number of tags to each toggle to make sorting possible)", 'avia_framework' ),
                    "id" 	=> "sort",
                    "type" 	=> "select",
                    "std" 	=> "",
                    "subtype" => array( __('No Sorting', 'avia_framework' ) =>'', __("Sorting Active", 'avia_framework' ) => 'true')
                ),
                
                array(
							"type" 	=> "close_div",
							'nodescription' => true
				),
				
				array(
					"type" 	=> "tab",
					"name"  => __("Styling" , 'avia_framework'),
					'nodescription' => true
				),
				
				
				 array(
                    "name" 	=> __("Styling", 'avia_framework' ),
                    "desc" 	=> __("Select the styling of the toggles", 'avia_framework' ),
                    "id" 	=> "styling",
                    "type" 	=> "select",
                    "std" 	=> "",
                    "subtype" => array( __('Default', 'avia_framework' ) =>'', __("Minimal", 'avia_framework' ) => 'av-minimal-toggle')
                ),
                
                
                array(
					"name" 	=> __("Colors", 'avia_framework' ),
					"desc" 	=> __("Either use the themes default colors or apply some custom ones", 'avia_framework' ),
					"id" 	=> "colors",
					"type" 	=> "select",
					"std" 	=> "",
					"subtype" => array( __('Default', 'avia_framework' )=>'',
										__('Define Custom Colors', 'avia_framework' )=>'custom'),
				),
			
				array(	
					"name" 	=> __("Custom Font Color", 'avia_framework' ),
					"desc" 	=> __("Select a custom font color. Leave empty to use the default", 'avia_framework' ),
					"id" 	=> "font_color",
					"type" 	=> "colorpicker",
					"std" 	=> "",
					"rgba" 	=> true,
					"container_class" => 'av_third av_third_first',
					"required" => array('colors','equals','custom')
				),	
					
				array(	
					"name" 	=> __("Custom Background Color", 'avia_framework' ),
					"desc" 	=> __("Select a custom background color. Leave empty to use the default", 'avia_framework' ),
					"id" 	=> "background_color",
					"type" 	=> "colorpicker",
					"std" 	=> "",
					"rgba" 	=> true,
					"container_class" => 'av_third',
					"required" => array('colors','equals','custom')
				),	
				
				array(	
					"name" 	=> __("Custom Border Color", 'avia_framework' ),
					"desc" 	=> __("Select a custom border color. Leave empty to use the default", 'avia_framework' ),
					"id" 	=> "border_color",
					"type" 	=> "colorpicker",
					"std" 	=> "",
					"rgba" 	=> true,
					"container_class" => 'av_third',
					"required" => array('colors','equals','custom')
				),	
				
				
				array(
							"type" 	=> "close_div",
							'nodescription' => true
				),		
						
								array(
									"type" 	=> "tab",
									"name"	=> __("Screen Options",'avia_framework' ),
									'nodescription' => true
								),
								
								
								array(
								"name" 	=> __("Element Visibility",'avia_framework' ),
								"desc" 	=> __("Set the visibility for this element, based on the device screensize.", 'avia_framework' ),
								"type" 	=> "heading",
								"description_class" => "av-builder-note av-neutral",
								),
							
								array(	
										"desc" 	=> __("Hide on large screens (wider than 990px - eg: Desktop)", 'avia_framework'),
										"id" 	=> "av-desktop-hide",
										"std" 	=> "",
										"container_class" => 'av-multi-checkbox',
										"type" 	=> "checkbox"),
								
								array(	
									
										"desc" 	=> __("Hide on medium sized screens (between 768px and 989px - eg: Tablet Landscape)", 'avia_framework'),
										"id" 	=> "av-medium-hide",
										"std" 	=> "",
										"container_class" => 'av-multi-checkbox',
										"type" 	=> "checkbox"),
										
								array(	
									
										"desc" 	=> __("Hide on small screens (between 480px and 767px - eg: Tablet Portrait)", 'avia_framework'),
										"id" 	=> "av-small-hide",
										"std" 	=> "",
										"container_class" => 'av-multi-checkbox',
										"type" 	=> "checkbox"),
										
								array(	
									
										"desc" 	=> __("Hide on very small screens (smaller than 479px - eg: Smartphone Portrait)", 'avia_framework'),
										"id" 	=> "av-mini-hide",
										"std" 	=> "",
										"container_class" => 'av-multi-checkbox',
										"type" 	=> "checkbox"),
									
								
							  
				
							
								
							array(
									"type" 	=> "close_div",
									'nodescription' => true
								),	
								
								
						
						
					array(
						"type" 	=> "close_div",
						'nodescription' => true
					),

            );


            if(current_theme_supports('avia_template_builder_custom_tab_toogle_id'))
            {
                $this->elements[2]['subelements'][] = array(
                    "name" 	=> __("For Developers: Custom Toggle ID",'avia_framework' ),
                    "desc" 	=> __("Insert a custom ID for the element here. Make sure to only use allowed characters",'avia_framework' ),
                    "id" 	=> "custom_id",
                    "type" 	=> "input",
                    "std" 	=> "");
            }


        }

        /**
         * Editor Sub Element - this function defines the visual appearance of an element that is displayed within a modal window and on click opens its own modal window
         * Works in the same way as Editor Element
         * @param array $params this array holds the default values for $content and $args.
         * @return $params the return array usually holds an innerHtml key that holds item specific markup.
         */
        function editor_sub_element($params)
        {
            $template = $this->update_template("title", "{{title}}");

            $params['innerHtml']  = "";
            $params['innerHtml'] .= "<div class='avia_title_container' {$template}>".$params['args']['title']."</div>";


            return $params;
        }



        /**
         * Frontend Shortcode Handler
         *
         * @param array $atts array of attributes
         * @param string $content text within enclosing form of shortcode element
         * @param string $shortcodename the shortcode found, when == callback name
         * @return string $output returns the modified html string
         */
        function shortcode_handler($atts, $content = "", $shortcodename = "", $meta = "")
        {
	        extract(AviaHelper::av_mobile_sizes($atts)); //return $av_font_classes, $av_title_font_classes and $av_display_classes 
	        
            $atts =  shortcode_atts(array('initial' => '0', 'mode' => 'accordion', 'sort'=>'', 'styling'=>'', 'colors'=>'', 'border_color'=>'', 'font_color'=>'', 'background_color'=>''), $atts, $this->config['shortcode']);
            extract($atts);

            $output = "";
            $addClass = '';
            if($mode == 'accordion') $addClass = 'toggle_close_all ';
			
            $output  = '<div class="togglecontainer '.$av_display_classes.' '.$styling.' '.$addClass.$meta['el_class'].'" >';
            avia_sc_toggle::$counter = 1;
            avia_sc_toggle::$initial = $initial;
            avia_sc_toggle::$tags 	 = array();
            avia_sc_toggle::$atts 	 = $atts;

            $content  = ShortcodeHelper::avia_remove_autop($content, true);
            $sortlist = !empty($sort) ? $this->sort_list($atts) : "";

            $output .= $sortlist.$content.'</div>';

            return $output;
        }


        function av_toggle($atts, $content = "", $shortcodename = "")
        {
            $output = $titleClass = $contentClass = "";
            $toggle_atts = shortcode_atts(array('title' => '', 'tags' => '', 'custom_id' => '', 'custom_markup' =>''), $atts, 'av_toggle');
			$toggle_init_open_style = "";
			
            if(is_numeric(avia_sc_toggle::$initial) && avia_sc_toggle::$counter == avia_sc_toggle::$initial)
            {
                $titleClass   = "activeTitle";
                $contentClass = "active_tc";
                $toggle_init_open_style = "style='display:block;'";
            }

            if(empty($toggle_atts['title']))
            {
                $toggle_atts['title'] = avia_sc_toggle::$counter;
            }

            if(empty($toggle_atts['custom_id']))
            {
                $toggle_atts['custom_id'] = 'toggle-id-'.avia_sc_toggle::$toggle_id++;
            }
            
            //custom colors
            $colors = $inherit = $icon_color = "";
            if(!empty(avia_sc_toggle::$atts['colors']) && avia_sc_toggle::$atts['colors'] == "custom")
            {
	            if(!empty(avia_sc_toggle::$atts['background_color']))
	            {
		            $colors = "background-color: ".avia_sc_toggle::$atts['background_color']."; ";
	            }
	            
	            if(!empty(avia_sc_toggle::$atts['font_color']))
	            {
		            $colors .= "color: ".avia_sc_toggle::$atts['font_color']."; ";
		            $icon_color = "style='border-color:".avia_sc_toggle::$atts['font_color'].";'";
		            $inherit .= " av-inherit-font-color ";
	            }
	            
	            if(!empty(avia_sc_toggle::$atts['border_color']))
	            {
		            $colors .= "border-color: ".avia_sc_toggle::$atts['border_color']."; ";
		            $inherit .= " av-inherit-border-color ";
	            }
            }
            
            if(!empty($colors))
            {
	            $colors = "style='{$colors}'";
            }
            

            $markup_tab = avia_markup_helper(array('context' => 'entry','echo'=>false, 'custom_markup'=>$toggle_atts['custom_markup']));
            $markup_title = avia_markup_helper(array('context' => 'entry_title','echo'=>false, 'custom_markup'=>$toggle_atts['custom_markup']));
            $markup_text = avia_markup_helper(array('context' => 'entry_content','echo'=>false, 'custom_markup'=>$toggle_atts['custom_markup']));

            $output .= '<section class="av_toggle_section" '.$markup_tab.' >';
            $output .= '    <div class="single_toggle" '.$this->create_tag_string($toggle_atts['tags'], $toggle_atts).'  >';
            $output .= '        <p data-fake-id="#'.$toggle_atts['custom_id'].'" class="toggler '.$titleClass.$inherit.'" '.$markup_title.' '.$colors.'>'.$toggle_atts['title'].'<span class="toggle_icon" '.$icon_color.'>';
            $output .= '        <span class="vert_icon"></span><span class="hor_icon"></span></span></p>';
            $output .= '        <div id="'.$toggle_atts['custom_id'].'-container" class="toggle_wrap '.$contentClass.'"  '.$toggle_init_open_style.'>';
            $output .= '            <div class="toggle_content invers-color '.$inherit.'" '.$markup_text.' '.$colors.' >';
            $output .= ShortcodeHelper::avia_apply_autop(ShortcodeHelper::avia_remove_autop($content));
            $output .= '            </div>';
            $output .= '        </div>';
            $output .= '    </div>';
            $output .= '</section>';

            avia_sc_toggle::$counter ++;

            return $output;
        }

        function create_tag_string($tags, $toggle_atts)
        {
            $first_item_text = apply_filters('avf_toggle_sort_first_label', __('All','avia_framework'), $toggle_atts);

            $tag_string = "{".$first_item_text."} ";
            if(trim($tags) != "")
            {
                $tags = explode(',', $tags);

                foreach($tags as $tag)
                {
                    $tag = esc_html(trim($tag));
                    if(!empty($tag))
                    {
                        $tag_string .= "{".$tag."} ";
                        avia_sc_toggle::$tags[$tag] = true;
                    }
                }
            }

            $tag_string = 'data-tags="'.$tag_string.'"';
            return $tag_string;
        }



        function sort_list($toggle_atts)
        {
            $output = "";
            $first = "activeFilter";
            if(!empty(avia_sc_toggle::$tags))
            {
                ksort(avia_sc_toggle::$tags);
                $first_item_text = apply_filters('avf_toggle_sort_first_label', __('All','avia_framework'), $toggle_atts);
                $start = array($first_item_text => true);
                avia_sc_toggle::$tags = $start + avia_sc_toggle::$tags;
				
				$sep = apply_filters( 'avf_toggle_sort_seperator', '/', $toggle_atts );
				
                foreach(avia_sc_toggle::$tags as $key => $value)
                {
                    $output .= '<a href="#" data-tag="{'.$key.'}" class="'.$first.'">'.$key.'</a>';
                    $output .= "<span class='tag-seperator'>{$sep}</span>";
                    $first = "";
                }
            }

            if( !empty($output) ) 
            { 
	            $output = "<div class='taglist'>{$output}</div>";
	        }
            return $output;
        }




    }
}
