<?php
/**
 * Promo Box
 * 
 * Creates a notification box with call to action button
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_promobox' ) )
{
	class avia_sc_promobox extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'no';
				
				$this->config['name']			= __('Promo Box', 'avia_framework' );
				$this->config['tab']			= __('Content Elements', 'avia_framework' );
				$this->config['icon']			= AviaBuilder::$path['imagesURL']."sc-promobox.png";
				$this->config['order']			= 50;
				$this->config['target']			= 'avia-target-insert';
				$this->config['shortcode'] 		= 'av_promobox';
				$this->config['tooltip'] 	    = __('Creates a notification box with call to action button', 'avia_framework' );
				$this->config['preview'] 		= "xlarge";
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
							"name" 	=> __("Content",'avia_framework' ),
							"desc" 	=> __("Enter some content for Promo Box",'avia_framework' ),
							"id" 	=> "content",
							"type" 	=> "tiny_mce",
							"std" 	=> __("Welcome Stranger! This is an example Text for your fantastic Promo Box! Feel Free to delete it and replace it with your own fancy Message!", "avia_framework" )),

					array(
							"name" 	=> __("Promo Box Button", 'avia_framework' ),
							"desc" 	=> __("Do you want to display a Call to Action Button on the right side of the box?", 'avia_framework' ),
							"id" 	=> "button",
							"type" 	=> "select",
							"std" 	=> "yes",
							"subtype" => array(
								__('yes',  'avia_framework' ) =>'yes',
								__('no',  'avia_framework' ) =>'no',
								)
								),


					array(	"name" 	=> __("Button Label", 'avia_framework' ),
							"desc" 	=> __("This is the text that appears on your button.", 'avia_framework' ),
				            "id" 	=> "label",
				            "type" 	=> "input",
				            "required" => array('button','equals','yes'),
				            "std" => __("Click me", 'avia_framework' )),
				    array(
							"name" 	=> __("Button Link?", 'avia_framework' ),
							"desc" 	=> __("Where should your button link to?", 'avia_framework' ),
							"id" 	=> "link",
							"type" 	=> "linkpicker",
				            "required" => array('button','equals','yes'),
							"fetchTMPL"	=> true,
							"subtype" => array(
												__('Set Manually', 'avia_framework' ) =>'manually',
												__('Single Entry', 'avia_framework' ) =>'single',
												__('Taxonomy Overview Page',  'avia_framework' )=>'taxonomy',
												),
							"std" 	=> "single"),

					array(
							"name" 	=> __("Open Link in new Window?", 'avia_framework' ),
							"desc" 	=> __("Select here if you want to open the linked page in a new window", 'avia_framework' ),
							"id" 	=> "link_target",
							"type" 	=> "select",
							"std" 	=> "",
				            "required" => array('button','equals','yes'),
							"subtype" => AviaHtmlHelper::linking_options()),   

					array(
							"name" 	=> __("Button Color", 'avia_framework' ),
							"desc" 	=> __("Choose a color for your button here", 'avia_framework' ),
							"id" 	=> "color",
							"type" 	=> "select",
							"std" 	=> "theme-color",
				            "required" => array('button','equals','yes'),
							"subtype" => array(
												__('Theme Color', 'avia_framework' )=>'theme-color',
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
							"name" 	=> __("Button Size", 'avia_framework' ),
							"desc" 	=> __("Choose the size of your button here", 'avia_framework' ),
							"id" 	=> "size",
							"type" 	=> "select",
							"std" 	=> "large",
				            "required" => array('button','equals','yes'),
							"subtype" => array(
								__('Small',   'avia_framework' ) =>'small',
								__('Medium',  'avia_framework' ) =>'medium',
								__('Large',   'avia_framework' ) =>'large',
							)),

					array(
							"name" 	=> __("Button Icon", 'avia_framework' ),
							"desc" 	=> __("Should an icon be displayed at the left side of the button", 'avia_framework' ),
							"id" 	=> "icon_select",
							"type" 	=> "select",
							"std" 	=> "no",
				            "required" => array('button','equals','yes'),
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
							"name"	=> __("Colors",'avia_framework' ),
							'nodescription' => true
						),
					
					
					array(
							"name" 	=> __("Colors", 'avia_framework' ),
							"desc" 	=> __("Either use the themes default colors or apply some custom ones", 'avia_framework' ),
							"id" 	=> "box_color",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array( __('Default', 'avia_framework' )=>'',
												__('Define Custom Colors', 'avia_framework' )=>'custom'),
					),
					
					array(	
							"name" 	=> __("Custom Font Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom font color here", 'avia_framework' ),
							"id" 	=> "box_custom_font",
							"type" 	=> "colorpicker",
							"std" 	=> "#ffffff",
							"required" => array('box_color','equals','custom')
						),	
					
					array(	
							"name" 	=> __("Custom Background Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom background color here", 'avia_framework' ),
							"id" 	=> "box_custom_bg",
							"type" 	=> "colorpicker",
							"std" 	=> "#444444",
							"required" => array('box_color','equals','custom')
						),	
						
					array(	
							"name" 	=> __("Custom Border Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom border color here", 'avia_framework' ),
							"id" 	=> "box_custom_border",
							"type" 	=> "colorpicker",
							"std" 	=> "#333333",
							"required" => array('box_color','equals','custom')
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
				
				$params['class'] = "";
				$params['innerHtml']  = "";
				$params['innerHtml'] .= "<div class='avia_textblock avia_textblock_style'>";
				$params['innerHtml'] .= "	<div ".$this->class_by_arguments('button' ,$params['args']).">";
				$params['innerHtml'] .= "		<div data-update_with='content' class='avia-promocontent'>".stripslashes(wpautop(trim($params['content'])))."</div>";
				$params['innerHtml'] .= "		<div class='avia_button_box avia_hidden_bg_box'>";
				$params['innerHtml'] .= "			<div ".$this->class_by_arguments('icon_select, color, size' ,$params['args']).">";
				$params['innerHtml'] .= "				<span ".$this->class_by_arguments('font' ,$font).">";
				$params['innerHtml'] .= "					<span data-update_with='icon_fakeArg' class='avia_button_icon'>".$display_char."</span>";
				$params['innerHtml'] .= "				</span>";
				$params['innerHtml'] .= "				<span data-update_with='label' class='avia_iconbox_title' >".$params['args']['label']."</span>";
				$params['innerHtml'] .= "			</div>";
				$params['innerHtml'] .= "		</div>";
				$params['innerHtml'] .= "	</div>";
				$params['innerHtml'] .= "</div>";

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
				
				$atts =  shortcode_atts(array(
											 'button' => 'yes',
											 'label' => 'Click me',
			                                 'link' => '',
			                                 'link_target' => '',
			                                 'color' => 'theme-color',
			                                 'custom_bg' => '#444444',
			                                 'custom_font' => '#ffffff',
			                                 'size' => 'small',
			                                 'position' => 'center',
			                                 'icon_select' => 'yes',
			                                 'icon' => '',
			                                 'font' => '',
			                                 'box_color' => '',
			                                 'box_custom_bg' => '',
			                                 'box_custom_font'=>'',
			                                 'box_custom_border'=>'',
			                                 
			                                 ), $atts, $this->config['shortcode']);
				extract($atts);
			
				$style = "";
				if($box_color == "custom")
				{
					if( $box_custom_bg )   $style .= "background:$box_custom_bg;";
					if( $box_custom_font ) $style .= "color:$box_custom_font;";
					if( $box_custom_border ) $style .= "border-color:$box_custom_border;";
				}
				if(!empty($style)) $style = "style='{$style}'";
				
				$atts['position'] = 'right';
				
				
				$output = "";

				$output = "";
				$output.= "	<div {$style} class='av_promobox {$av_display_classes} ".$this->class_by_arguments('button' , $atts, true).$meta['el_class']."'>";
				$output.= "		<div class='avia-promocontent'>".stripslashes(wpautop(trim($content)))."</div>";
				
				
				
				if($atts['button'] == "yes")
				{
					global $shortcode_tags;
					$fake   = true;
					$output.= call_user_func( $shortcode_tags['av_button'], $atts, null, 'av_button', $fake);
				}

				$output.= "	</div>";

				return do_shortcode($output);

			}

	}
}
