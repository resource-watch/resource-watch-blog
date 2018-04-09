<?php
/**
 * Button
 * 
 * Displays a colored button that links to any url of your choice
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_button' ) ) 
{
	class avia_sc_button extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'yes';
				
				$this->config['name']		= __('Button', 'avia_framework' );
				$this->config['tab']		= __('Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-button.png";
				$this->config['order']		= 85;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_button';
				$this->config['tooltip'] 	= __('Creates a colored button', 'avia_framework' );
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
					
					array(	"name" 	=> __("Button Label", 'avia_framework' ),
							"desc" 	=> __("This is the text that appears on your button.", 'avia_framework' ),
				            "id" 	=> "label",
				            "type" 	=> "input",
				            "std" => __("Click me", 'avia_framework' )),
				    array(	
							"name" 	=> __("Button Link?", 'avia_framework' ),
							"desc" 	=> __("Where should your button link to?", 'avia_framework' ),
							"id" 	=> "link",
							"type" 	=> "linkpicker",
							"fetchTMPL"	=> true,
							"subtype" => array(	
												__('Set Manually', 'avia_framework' ) =>'manually',
												__('Single Entry', 'avia_framework' ) =>'single',
												__('Taxonomy Overview Page',  'avia_framework' )=>'taxonomy',
												),
							"std" 	=> ""),
							
					array(	
							"name" 	=> __("Open Link in new Window?", 'avia_framework' ),
							"desc" 	=> __("Select here if you want to open the linked page in a new window", 'avia_framework' ),
							"id" 	=> "link_target",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => AviaHtmlHelper::linking_options()),	
																
						
					array(	
							"name" 	=> __("Button Size", 'avia_framework' ),
							"desc" 	=> __("Choose the size of your button here", 'avia_framework' ),
							"id" 	=> "size",
							"type" 	=> "select",
							"std" 	=> "small",
							"subtype" => array(
								__('Small',   'avia_framework' ) =>'small',
								__('Medium',  'avia_framework' ) =>'medium',
								__('Large',   'avia_framework' ) =>'large',
								__('X Large',   'avia_framework' ) =>'x-large',
							)),
							
					array(	
							"name" 	=> __("Button Position", 'avia_framework' ),
							"desc" 	=> __("Choose the alignment of your button here", 'avia_framework' ),
							"id" 	=> "position",
							"type" 	=> "select",
							"std" 	=> "center",
							"subtype" => array(
								__('Align Left',   'avia_framework' ) =>'left',
								__('Align Center',  'avia_framework' ) =>'center',
								__('Align Right',   'avia_framework' ) =>'right',
							)),		
					array(	
							"name" 	=> __("Button Icon", 'avia_framework' ),
							"desc" 	=> __("Should an icon be displayed at the left side of the button", 'avia_framework' ),
							"id" 	=> "icon_select",
							"type" 	=> "select",
							"std" 	=> "yes",
							"subtype" => array(
								__('No Icon',  'avia_framework' ) =>'no',
								__('Yes, display Icon to the left',  'avia_framework' ) => 'yes' ,	
								__('Yes, display Icon to the right',  'avia_framework' ) =>'yes-right-icon',
								)),
					array(	
							"name" 	=> __("Icon Visibility",'avia_framework' ),
							"desc" 	=> __("Check to only display icon on hover",'avia_framework' ),
							"id" 	=> "icon_hover",
							"type" 	=> "checkbox",
							"std" 	=> "",
							"required" => array('icon_select','not_empty_and','no')
							),
					array(	
							"name" 	=> __("Button Icon",'avia_framework' ),
							"desc" 	=> __("Select an icon for your Button below",'avia_framework' ),
							"id" 	=> "icon",
							"type" 	=> "iconfont",
							"std" 	=> "",
							"required" => array('icon_select','not_empty_and','no')
							),
					array(
							"type" 	=> "close_div",
							'nodescription' => true
						),
					
					array(
							"type" 	=> "tab",
							"name"	=> __("Colors",'avia_framework' ),
							'nodescription' => true
						),
					
					array(	
							"name" 	=> __("Button Color", 'avia_framework' ),
							"desc" 	=> __("Choose a color for your button here", 'avia_framework' ),
							"id" 	=> "color",
							"type" 	=> "select",
							"std" 	=> "theme-color",
							"subtype" => array(	
												__('Translucent Buttons', 'avia_framework' ) => array(
													__('Light Transparent', 'avia_framework' )=>'light',
													__('Dark Transparent', 'avia_framework' )=>'dark',
												),
														
												__('Colored Buttons', 'avia_framework' ) => array(
												__('Theme Color', 'avia_framework' )=>'theme-color',
												__('Theme Color Highlight', 'avia_framework' )=>'theme-color-highlight',
												__('Theme Color Subtle', 'avia_framework' )=>'theme-color-subtle',
												__('Blue', 'avia_framework' )=>'blue',
												__('Red',  'avia_framework' )=>'red',
												__('Green', 'avia_framework' )=>'green',
												__('Orange', 'avia_framework' )=>'orange',
												__('Aqua', 'avia_framework' )=>'aqua',
												__('Teal', 'avia_framework' )=>'teal',
												__('Purple', 'avia_framework' )=>'purple',
												__('Pink', 'avia_framework' )=>'pink',
												__('Silver', 'avia_framework' )=>'silver',
												__('Grey', 'avia_framework' )=>'grey',
												__('Black', 'avia_framework' )=>'black',
												__('Custom Color', 'avia_framework' )=>'custom',
												)),
								),

							
					array(	
							"name" 	=> __("Custom Background Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom background color for your Button here", 'avia_framework' ),
							"id" 	=> "custom_bg",
							"type" 	=> "colorpicker",
							"std" 	=> "#444444",
							"required" => array('color','equals','custom')
						),	
						
					array(	
							"name" 	=> __("Custom Font Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom font color for your Button here", 'avia_framework' ),
							"id" 	=> "custom_font",
							"type" 	=> "colorpicker",
							"std" 	=> "#ffffff",
							"required" => array('color','equals','custom')
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
				
				$inner  = "<div class='avia_button_box avia_hidden_bg_box avia_textblock avia_textblock_style'>";
				$inner .= "		<div ".$this->class_by_arguments('icon_select, color, size, position' ,$params['args']).">";
				$inner .= "			<span ".$this->class_by_arguments('font' ,$font).">";
				$inner .= "			<span data-update_with='icon_fakeArg' class='avia_button_icon avia_button_icon_left'>".$display_char."</span>";
				$inner .= "			</span>";
				$inner .= "			<span data-update_with='label' class='avia_iconbox_title' >".$params['args']['label']."</span>";
				$inner .= "			<span ".$this->class_by_arguments('font' ,$font).">";
				$inner .= "			<span data-update_with='icon_fakeArg' class='avia_button_icon avia_button_icon_right'>".$display_char."</span>";
				$inner .= "			</span>";
				$inner .= "		</div>";
				$inner .= "</div>";
				
				$params['innerHtml'] = $inner;
				$params['content'] = NULL;
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
			   $atts =  shortcode_atts(array('label' => 'Click me', 
			                                 'link' => '', 
			                                 'link_target' => '',
			                                 'color' => 'theme-color',
			                                 'custom_bg' => '#444444',
			                                 'custom_font' => '#ffffff',
			                                 'size' => 'small',
			                                 'position' => 'center',
			                                 'icon_select' => 'yes',
			                                 'icon' => '', 
			                                 'font' =>'',
			                                 'icon_hover' => '',
			                                 ), $atts, $this->config['shortcode']);
											 
				$display_char 	= av_icon($atts['icon'], $atts['font']);
				$extraClass 	= $atts['icon_hover'] ? "av-icon-on-hover" : "";
				
				if($atts['icon_select'] == "yes") $atts['icon_select'] = "yes-left-icon";
				
				$style = "";
				if($atts['color'] == "custom") 
				{
					$style .= "style='background-color:".$atts['custom_bg']."; border-color:".$atts['custom_bg']."; color:".$atts['custom_font']."; '";
				}

				
			    $blank = strpos($atts['link_target'], '_blank') !== false ? ' target="_blank" ' : "";
			    $blank .= strpos($atts['link_target'], 'nofollow') !== false ? ' rel="nofollow" ' : "";

			    $link  = AviaHelper::get_url($atts['link']);
			    $link  = ( ( $link == "http://" ) || ( $link == "manually" ) ) ? "" : $link;
			    
			    $content_html = "";
			    if('yes-left-icon' == $atts['icon_select']) $content_html .= "<span class='avia_button_icon avia_button_icon_left ' {$display_char}></span>";
				$content_html .= "<span class='avia_iconbox_title' >".$atts['label']."</span>";
			    if('yes-right-icon' == $atts['icon_select']) $content_html .= "<span class='avia_button_icon avia_button_icon_right' {$display_char}></span>";
			    
			    $output  = "";
				$output .= "<a href='{$link}' class='avia-button {$extraClass} {$av_display_classes} ".$this->class_by_arguments('icon_select, color, size, position' , $atts, true)."' {$blank} {$style} >";
				$output .= $content_html;
				$output .= "</a>";
				
				$output =  "<div class='avia-button-wrap avia-button-".$atts['position']." ".$meta['el_class']."'>".$output."</div>";
				
				return $output;
			}
			
			
			
	
	}
}
