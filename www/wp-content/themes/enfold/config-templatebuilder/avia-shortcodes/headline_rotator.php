<?php
/**
 * Headline Rotator
 * 
 * Creates a text rotator for dynamic headings
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_headline_rotator' ) )
{
	class avia_sc_headline_rotator extends aviaShortcodeTemplate
	{
			var $count;
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'no';
				
				$this->config['name']		= __('Headline Rotator', 'avia_framework' );
				$this->config['tab']		= __('Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-heading.png";
				$this->config['order']		= 85;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_headline_rotator';
				$this->config['shortcode_nested'] = array('av_rotator_item');
				$this->config['tooltip'] 	= __('Creates a text rotator for dynamic headings', 'avia_framework' );
				$this->config['preview'] 	= "large";
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
							"name"  => __("Text" , 'avia_framework'),
							'nodescription' => true
						),
						
						array(
									"name" 	=> __("Prepended static text", 'avia_framework' ),
									"desc" 	=> __("Enter static text that should be displayed before the rotating text", 'avia_framework' ) ,
									"id" 	=> "before_rotating",
									"std" 	=> __('We are ', 'avia_framework' ),
									"type" 	=> "input"
							),
						
						array(
							"name" => __("Add/Edit rotating text", 'avia_framework' ),
							"desc" => __("Here you can add, remove and edit the rotating text", 'avia_framework' ),
							"type" 			=> "modal_group",
							"id" 			=> "content",
							"modal_title" 	=> __("Edit Text Element", 'avia_framework' ),
							"std"			=> array(

													array('title'=>__('great', 'avia_framework' )),
													array('title'=>__('smart', 'avia_framework' )),
													array('title'=>__('fast', 'avia_framework' )),

													),


							'subelements' 	=> array(

									array(
									"name" 	=> __("Rotating Text", 'avia_framework' ),
									"desc" 	=> __("Enter the rotating text here (Better keep it short)", 'avia_framework' ) ,
									"id" 	=> "title",
									"std" 	=> "",
									"type" 	=> "input"),


                                array(
                                    "name" 	=> __("Text Link?", 'avia_framework' ),
                                    "desc" 	=> __("Do you want to apply  a link to the title?", 'avia_framework' ),
                                    "id" 	=> "link",
                                    "type" 	=> "linkpicker",
                                    "fetchTMPL"	=> true,
                                    "std"	=> "",
                                    "subtype" => array(
                            			__('No Link', 'avia_framework' ) =>'',
                                        __('Set Manually', 'avia_framework' ) =>'manually',
                                        __('Single Entry', 'avia_framework' ) =>'single',
                                        __('Taxonomy Overview Page',  'avia_framework' )=>'taxonomy',
                                    ),
                                    "std" 	=> ""),

                                array(
                                    "name" 	=> __("Open in new window", 'avia_framework' ),
                                    "desc" 	=> __("Do you want to open the link in a new window", 'avia_framework' ),
                                    "id" 	=> "linktarget",
                                    "required" 	=> array('link', 'not', ''),
                                    "type" 	=> "select",
                                    "std" 	=> "no",
									"subtype" => AviaHtmlHelper::linking_options()),
								
								array(	
								"name" 	=> __("Custom Font Color", 'avia_framework' ),
								"desc" 	=> __("Select a custom font color. Leave empty to use the default", 'avia_framework' ),
								"id" 	=> "custom_title",
								"type" 	=> "colorpicker",
								"std" 	=> "",
							),	


						)
					),
					
					array(
									"name" 	=> __("Appended static text", 'avia_framework' ),
									"desc" 	=> __("Enter static text that should be displayed after the rotating text", 'avia_framework' ) ,
									"id" 	=> "after_rotating",
									"std" 	=> "",
									"type" 	=> "input"
							),
					
					array(
						"name" 	=> __("Activate Multiline?",'avia_framework' ),
						"desc" 	=> __("Check if prepended, rotating and appended text should each be displayed on its own line",'avia_framework' ),
						"id" 	=> "multiline",
						"type" 	=> "checkbox",
						"std" 	=> "",
						),
					
					
					array(
							"type" 	=> "close_div",
							'nodescription' => true
						),
					
					array(
							"type" 	=> "tab",
							"name"	=> __("Rotation",'avia_framework' ),
							'nodescription' => true
						),
						
					array(	
						"name" 	=> __("Autorotation duration",'avia_framework' ),
						"desc" 	=> __("Each rotating textblock will be shown the selected amount of seconds.",'avia_framework' ),
						"id" 	=> "interval",
						"type" 	=> "select",
						"std" 	=> "5",
						"subtype" => 
						array('2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','15'=>'15','20'=>'20','30'=>'30','40'=>'40','60'=>'60','100'=>'100')),
					
	
					array(	
							"name" 	=> __("Rotation Animation", 'avia_framework' ),
							"desc" 	=> __("Select the rotation animation", 'avia_framework' ),
							"id" 	=> "animation",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array(
												"Top to bottom"=>'',
												"Bottom to top"=>'reverse',
												"Fade only" => 'fade'
												)
							), 
					
					array(
							"type" 	=> "close_div",
							'nodescription' => true
						),
					
					array(
							"type" 	=> "tab",
							"name"	=> __("Style",'avia_framework' ),
							'nodescription' => true
						),
					
					array(	
							"name" 	=> __("HTML Markup", 'avia_framework' ),
							"desc" 	=> __("Select which kind of HTML markup you want to apply to set the importance of the headline for search engines", 'avia_framework' ),
							"id" 	=> "tag",
							"type" 	=> "select",
							"std" 	=> "h3",
							"subtype" => array("H1"=>'h1',"H2"=>'h2',"H3"=>'h3',"H4"=>'h4',"H5"=>'h5',"H6"=>'h6', __('Paragraph','avia_framework') => 'p')
							), 
                    
                    array(	"name" 	=> __("Text Size", 'avia_framework' ),
							"desc" 	=> __("Size of your Text in Pixel", 'avia_framework' ),
				            "id" 	=> "size",
				            "type" 	=> "select",
				            "subtype" => AviaHtmlHelper::number_array(11,150,1, array( __("Default Size", 'avia_framework' )=>'')),
				            "std" => ""),                
                    
                    array(	
							"name" 	=> __("Text align", 'avia_framework' ),
							"desc" 	=> __("Alignment of the text", 'avia_framework' ),
							"id" 	=> "align",
							"type" 	=> "select",
							"std" 	=> "left",
							"subtype" => array(	__('Center', 'avia_framework' ) =>'center',
												__('Left', 'avia_framework' )  =>'left',
												__('Right', 'avia_framework' ) =>'right',
												)),
					
					array(	
							"name" 	=> __("Custom Font Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom font color. Leave empty to use the default", 'avia_framework' ),
							"id" 	=> "custom_title",
							"type" 	=> "colorpicker",
							"std" 	=> "",
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
									"name" 	=> __("Heading Font Size",'avia_framework' ),
									"desc" 	=> __("Set the font size for the heading, based on the device screensize.", 'avia_framework' ),
									"type" 	=> "heading",
									"description_class" => "av-builder-note av-neutral",
									),
										
									array(	"name" 	=> __("Font Size for medium sized screens", 'avia_framework' ),
						            "id" 	=> "av-medium-font-size-title",
						            "type" 	=> "select",
						            "subtype" => AviaHtmlHelper::number_array(10,120,1, array( __("Default", 'avia_framework' )=>'' , __("Hidden", 'avia_framework' )=>'hidden' ), "px"),
						            "std" => ""),
						            
						            array(	"name" 	=> __("Font Size for small screens", 'avia_framework' ),
						            "id" 	=> "av-small-font-size-title",
						            "type" 	=> "select",
						            "subtype" => AviaHtmlHelper::number_array(10,120,1, array( __("Default", 'avia_framework' )=>'', __("Hidden", 'avia_framework' )=>'hidden'), "px"),
						            "std" => ""),
						            
									array(	"name" 	=> __("Font Size for very small screens", 'avia_framework' ),
						            "id" 	=> "av-mini-font-size-title",
						            "type" 	=> "select",
						            "subtype" => AviaHtmlHelper::number_array(10,120,1, array( __("Default", 'avia_framework' )=>'', __("Hidden", 'avia_framework' )=>'hidden'), "px"),
						            "std" => ""),

							
								
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
			 * Editor Sub Element - this function defines the visual appearance of an element that is displayed within a modal window and on click opens its own modal window
			 * Works in the same way as Editor Element
			 * @param array $params this array holds the default values for $content and $args.
			 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
			 */
			function editor_sub_element($params)
			{
				$template = $this->update_template("title", "{{title}}");

				$params['innerHtml']  = "";
				$params['innerHtml'] .= "<div class='avia_title_container'>";
				$params['innerHtml'] .= "<span {$template} >".$params['args']['title']."</span></div>";

				return $params;
			}

			/**
			 * Returns false by default.
			 * Override in a child class if you need to change this behaviour.
			 * 
			 * @since 4.2.1
			 * @param string $shortcode
			 * @return boolean
			 */
			public function is_nested_self_closing( $shortcode )
			{
				if( in_array( $shortcode, $this->config['shortcode_nested'] ) )
				{
					return true;
				}

				return false;
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
				$this->screen_options = AviaHelper::av_mobile_sizes($atts);
				extract($this->screen_options); //return $av_font_classes, $av_title_font_classes and $av_display_classes	
							
				extract(shortcode_atts(array(
				
				'align'=>'left', 
				'before_rotating'=>'', 
				'after_rotating'=>'', 
				'interval'=>'5', 
				'tag'=>'h3',
				'size' => "",
				'custom_title' => '',
				'multiline' => 'disabled',
				'animation' => ''
				
				), $atts, $this->config['shortcode']));
				
				
				
				$this->count = 0;
				$style = "";
				$style .= AviaHelper::style_string($atts, 'align', 'text-align');
				$style .= AviaHelper::style_string($atts, 'custom_title', 'color');
				$style .= AviaHelper::style_string($atts, 'size', 'font-size', 'px');
				$style  = AviaHelper::style_string($style);
				
				$multiline = $multiline == 'disabled' ? "off" : "on";
				
				switch($animation)
				{
					case 'reverse': $animation = -1; break;
					case 'fade': 	$animation = 0; break;
					default: 		$animation = 1;
				}
				
				$data = "data-interval='{$interval}' data-animation='{$animation}'";
				
				if(empty($after_rotating) && $align == 'center' ) { $data .= " data-fixWidth='1'"; $meta['el_class'] .= " av-fixed-rotator-width"; } 
				
				
				$output	 = "";
				$output .= "<div {$style} class='av-rotator-container av-rotation-container-".$atts['align']." {$av_display_classes} ".$meta['el_class']."' {$data}>";
				$output .= "<{$tag} class='av-rotator-container-inner {$av_title_font_classes}'>";
				$output .= apply_filters('avia_ampersand', $before_rotating);
				$output .= "<span class='av-rotator-text av-rotator-multiline-{$multiline} '>";
				$output .= ShortcodeHelper::avia_remove_autop( $content, true );
				$output .= "</span>";
				$output .= apply_filters('avia_ampersand', $after_rotating);
				$output .= "</{$tag}>";
				$output .= "</div>";


				return $output;
			}

			function av_rotator_item($atts, $content = "", $shortcodename = "")
			{
				extract($this->screen_options); //return $av_font_classes, $av_title_font_classes and $av_display_classes	
				
                $atts = shortcode_atts(
                array(	
                	'title' 		=> '',
                	'link' 			=> '',
                	'linktarget' 	=> '',
                	'custom_title' 	=> '',
                ), 
                $atts, 'av_rotator_item');
                
                extract($atts);
                
                $this->count++;
                
				$style  = AviaHelper::style_string($atts, 'custom_title', 'color');
				$style  = AviaHelper::style_string($style);
				
				$link = AviaHelper::get_url($link);
				$blank = (strpos($linktarget, '_blank') !== false || $linktarget == 'yes') ? ' target="_blank" ' : "";
				$blank .= strpos($linktarget, 'nofollow') !== false ? ' rel="nofollow" ' : "";
           
            
            	$tags = !empty($link) ? array("a href='{$link}' {$blank} ",'a') : array('span','span');
				

				$output  = "";
				$output .= "<{$tags[0]} {$style} class='av-rotator-text-single av-rotator-text-single-{$this->count}'>";
				$output .= ShortcodeHelper::avia_remove_autop( $title , true );
				$output .= "</{$tags[1]}>";
				return $output;
			}


	}
}
