<?php
/**
 * Notification box
 * 
 * Creates a notification box to inform visitors
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_notification' ) ) 
{
	class avia_sc_notification extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'no';
				
				$this->config['name']		= __('Notification', 'avia_framework' );
				$this->config['tab']		= __('Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-notification.png";
				$this->config['order']		= 80;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_notification';
				$this->config['tooltip'] 	= __('Creates a notification box to inform visitors', 'avia_framework' );
				$this->config['tinyMCE']    = array('tiny_always'=>true);	
				$this->config['preview'] 	= true;
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
					
					array(	"name" 	=> __("Title", 'avia_framework' ),
							"desc" 	=> __("This is the small title at the top of your Notification.", 'avia_framework' ),
				            "id" 	=> "title",
				            "type" 	=> "input",
				            "std" => __("Note", 'avia_framework' )),
					
					array(	"name" 	=> __("Message", 'avia_framework' ),
							"desc" 	=> __("This is the text that appears in your Notification.", 'avia_framework' ),
				            "id" 	=> "content",
				            "type" 	=> "textarea",
				            "std" => __("This is a notification of some sort.", 'avia_framework' )),
											
					array(	
							"name" 	=> __("Message Colors", 'avia_framework' ),
							"desc" 	=> __("Choose the color for your Box here", 'avia_framework' ),
							"id" 	=> "color",
							"type" 	=> "select",
							"std" 	=> "green",
							"subtype" => array(	
												__('Success (Green)', 'avia_framework' )=>'green',
												__('Notification (Blue)', 'avia_framework' )=>'blue',
												__('Warning (Red)',  'avia_framework' )=>'red',
												__('Alert (Orange)', 'avia_framework' )=>'orange',
												__('Neutral (Light Grey)', 'avia_framework' )=>'silver',
												__('Neutral (Dark Grey)', 'avia_framework' )=>'grey',
												__('Custom Color', 'avia_framework' )=>'custom',
												)),
												
					array(	
							"name" 	=> __("Notification Box Border", 'avia_framework' ),
							"desc" 	=> __("Choose the border for your Box here", 'avia_framework' ),
							"id" 	=> "border",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array(	
												__('None', 'avia_framework' )=>'',
												__('Solid', 'avia_framework' ) => 'solid',
												__('Dashed', 'avia_framework' ) =>'dashed',
												)),
							
					array(	
							"name" 	=> __("Custom Background Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom background color for your Notification here", 'avia_framework' ),
							"id" 	=> "custom_bg",
							"type" 	=> "colorpicker",
							"std" 	=> "#444444",
							"required" => array('color','equals','custom')
						),	
						
					array(	
							"name" 	=> __("Custom Font Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom font color for your Notification here", 'avia_framework' ),
							"id" 	=> "custom_font",
							"type" 	=> "colorpicker",
							"std" 	=> "#ffffff",
							"required" => array('color','equals','custom')
						),		
						
						
					array(	
							"name" 	=> __("Box Size", 'avia_framework' ),
							"desc" 	=> __("Choose the size of your Box here", 'avia_framework' ),
							"id" 	=> "size",
							"type" 	=> "select",
							"std" 	=> "large",
							"subtype" => array(
								__('Normal',   'avia_framework' ) =>'normal',
								__('Large',   'avia_framework' ) =>'large',
							)),
					
					array(	
							"name" 	=> __("Button Icon", 'avia_framework' ),
							"desc" 	=> __("Should an icon be displayed at the left side of the button", 'avia_framework' ),
							"id" 	=> "icon_select",
							"type" 	=> "select",
							"std" 	=> "yes",
							"subtype" => array(
								__('No Icon',  'avia_framework' ) =>'no',
								__('Yes, display Icon',  'avia_framework' ) =>'yes')),	
					
					array(	
							"name" 	=> __("Button Icon",'avia_framework' ),
							"desc" 	=> __("Select an icon for your Button below",'avia_framework' ),
							"id" 	=> "icon",
							"type" 	=> "iconfont",
							"std" 	=> "",
							"required" => array('icon_select','equals','yes')
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

			}
			
			
			
			/**
			 * Editor Element - this function defines the visual appearance of an element on the AviaBuilder Canvas
			 * Most common usage is to define some markup in the $params['innerHtml'] which is then inserted into the drag and drop container
			 * Less often used: $params['data'] to add data attributes, $params['class'] to modify the className
			 *
			 *
			 * @param array $params this array holds the default values for $content and $args. 
			 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
			 */
			function editor_element($params)
			{
				extract(av_backend_icon($params)); // creates $font and $display_char if the icon was passed as param "icon" and the font as "font"
				
				$inner  = "<div class='avia_message_box avia_hidden_bg_box avia_textblock avia_textblock_style'>";
				$inner .= "		<div ".$this->class_by_arguments('color, size, icon_select, border' ,$params['args']).">";
				$inner .= "			<span ".$this->class_by_arguments('font' ,$font).">";
				$inner .= "				<span data-update_with='icon_fakeArg' class='avia_message_box_icon'>{$display_char}</span>";
				$inner .= "			</span>";
				$inner .= "			<span data-update_with='title' class='avia_message_box_title' >".$params['args']['title']."</span>";
				$inner .= "			<span data-update_with='content' class='avia_message_box_content' >".$params['content']."</span>";
				$inner .= "		</div>";
				$inner .= "</div>";
				
				$params['innerHtml'] = $inner;
				$params['class'] = "";
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
				
				$atts =  shortcode_atts(array(	 'title' => '', 
				                                 'color' => 'green', 
				                                 'border' => '',
				                                 'custom_bg' => '#444444',
				                                 'custom_font' => '#ffffff',
				                                 'size' => 'large',
				                                 'icon_select' => 'yes',
				                                 'icon' => '',
				                                 'font' => '',
				                                 ), $atts, $this->config['shortcode']);
			
				$display_char = av_icon($atts['icon'], $atts['font']);
				
				$output  = "";
				$style   = "";
				
				if($atts['color'] == "custom") 
				{
					$style .= "style='background-color:".$atts['custom_bg']."; color:".$atts['custom_font']."; '";
				}
				
				$output .= "<div {$style} class='avia_message_box {$av_display_classes} ".$this->class_by_arguments('color, size, icon_select, border' , $atts, true).$meta['el_class']."'>";
				
				
				if($atts['title']) 
				{
					$output .= "<span class='avia_message_box_title' >".$atts['title']."</span>";
				}
				
				$output .= "<div class='avia_message_box_content' >";
				
				if($atts['icon_select'] == 'yes') 
				{
					$output .= "<span class='avia_message_box_icon' $display_char></span>";
				}
				$output .= ShortcodeHelper::avia_apply_autop(ShortcodeHelper::avia_remove_autop($content) )."</div>";
				$output .= "</div>";
				
				return $output;
			}
			
			
			
	
	}
}
