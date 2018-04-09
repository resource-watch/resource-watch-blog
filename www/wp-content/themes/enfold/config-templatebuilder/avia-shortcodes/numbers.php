<?php
/**
 * Animated Numbers
 * 
 * Display Numbers that count from 0 to the number you entered
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_animated_numbers' ) ) 
{
	
	class avia_sc_animated_numbers extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'no';
				
				$this->config['name']		= __('Animated Numbers', 'avia_framework' );
				$this->config['tab']		= __('Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-numbers.png";
				$this->config['order']		= 15;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_animated_numbers';
				$this->config['tooltip'] 	= __('Display an animated Number with subtitle', 'avia_framework' );
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
													
					array(	
							"name" 	=> __("Number",'avia_framework' ),
							"desc" 	=> __("Add a Number here. It will be animated. You can also add non numerical characters. Valid examples: 24/7, 99.9$, 90&percnt;, 35k, 200mm etc ",'avia_framework' ),
							"id" 	=> "number",
							"type" 	=> "input",
							"std" 	=> __("100",'avia_framework' )),

										
					array(	
							"name" 	=> __("Description",'avia_framework' ),
							"desc" 	=> __("Add some content to be displayed below the number",'avia_framework' ),
							"id" 	=> "content",
							"type" 	=> "textarea",
							"std" 	=> __("Click here to add your own text", "avia_builder" )),
					
					array(	
							"name" 	=> __("Icon", 'avia_framework' ),
							"desc" 	=> __("Add an icon to the element?", 'avia_framework' ),
							"id" 	=> "icon_select",
							"type" 	=> "select",
							"std" 	=> "no",
							"subtype" => array(
								__('No Icon',  'avia_framework' ) =>'no',
								__('Yes, display an icon in front of number',  'avia_framework' ) =>'av-icon-before',	
								__('Yes, display an icon after the number',  'avia_framework' ) =>'av-icon-after')),	
					
					array(	
							"name" 	=> __("Icon",'avia_framework' ),
							"desc" 	=> __("Select an icon for the element here",'avia_framework' ),
							"id" 	=> "icon",
							"type" 	=> "iconfont",
							"std" 	=> "",
							"required" => array('icon_select','not','no')
							),
							
                    
                    array(	"name" 	=> __("Number custom font size?", 'avia_framework' ),
							"desc" 	=> __("Size of your number in pixel", 'avia_framework' ),
				            "id" 	=> "font_size",
				            "type" 	=> "select",
				            "subtype" => AviaHtmlHelper::number_array(16,100,2,array('Default' =>''),'px'),
				            "std" => ""),
                    
                    array(	"name" 	=> __("Description custom font size?", 'avia_framework' ),
							"desc" 	=> __("Size of your description in pixel", 'avia_framework' ),
				            "id" 	=> "font_size_description",
				            "type" 	=> "select",
				            "subtype" => AviaHtmlHelper::number_array(10,40,1, array('Default' =>''),'px'),
				            "std" => ""),
                    
					
                    array(
                        "name" 	=> __("Apply link?", 'avia_framework' ),
                        "desc" 	=> __("Do you want to apply  a link to the element?", 'avia_framework' ),
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
                        "subtype" => array(
                            __('Yes',  'avia_framework' ) =>'yes',
                            __('No', 'avia_framework' ) =>'no')),
                      
                      
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
                        "name" 	=> __("Font color?", 'avia_framework' ),
                        "desc" 	=> __("You can use the default font colors and styles or use a custom font color for the element (in case you use a background image for example)", 'avia_framework' ),
                        "id" 	=> "color",
                        "type" 	=> "select",
                        "std"	=> "",
                        "subtype" => array(
                            __('Default', 'avia_framework' ) =>'',
                            __('Light', 'avia_framework' ) =>'font-light',
                            __('Dark', 'avia_framework' ) =>'font-dark',
                            __('Custom', 'avia_framework' ) =>'font-custom'
                        ),
                        "std" 	=> ""),
                    
                    array(	
							"name" 	=> __("Custom Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom color for your text here", 'avia_framework' ),
							"id" 	=> "custom_color",
							"type" 	=> "colorpicker",
							"std" 	=> "#444444",
							"required" => array('color','equals','font-custom')
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
				
				$char = "";
				$char .= "			<span ".$this->class_by_arguments('font' ,$font).">";
				$char .= "			<span data-update_with='icon_fakeArg' class='avia_big_numbers_icon'>".$display_char."</span>";
				$char .= "			</span>";
				
				$inner  = "<div class='avia_iconbox avia_big_numbers avia_textblock avia_textblock_style avia_center_text'>";
				$inner .= "		<div ".$this->class_by_arguments('icon_select' ,$params['args']).">";
				$inner .= "				<h2><span class='avia_big_numbers_icon_before'>".$char."</span><span data-update_with='number'>".html_entity_decode($params['args']['number'])."</span><span class='avia_big_numbers_icon_after'>".$char."</span></h2>";
				$inner .= "				<div class='' data-update_with='content'>".stripslashes(wpautop(trim(html_entity_decode($params['content']))))."</div>";
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
				extract(shortcode_atts(array(	'number' 		=> '100', 
											 	'icon' 			=> '1', 
											 	'position' 		=> 'left', 
											 	'link' 			=> '', 
											 	'linktarget'	=> 'no', 
											 	'color' 		=> '', 
											 	'custom_color'	=> '', 
											 	'icon_select'	=> '', 
											 	'icon' 			=> 'no',
											 	'font'			=> '',
											 	'font_size'		=> '',
											 	'font_size_description'=>''
											 	)
											 , $atts));
				
				$tags  		= array('div', 'div');
				$style 		= "";
				$font_style = "";
				$font_style2= "";
                $linktarget = ($linktarget == 'no') ? '' : 'target="_blank"';
                $link 		= aviaHelper::get_url($link);
                $display_char = $before = $after = "";
                
                if(!empty($link))
                {
                    $tags[0] = "a href='$link' title='' $linktarget";
                    $tags[1] = "a";
                }
                
                if($color == "font-custom")
                {
                	$style = "style='color:{$custom_color}'";
                }
                
                if($font_size)
                {
                	$font_style = "style='font-size:{$font_size}px'";
                }
                
                if($font_size_description)
                {
                	$font_style2 = "style='font-size:{$font_size_description}px'";
                }
                
                
				
				if($icon_select !== 'no')
				{
					$char 		  = av_icon($icon, $font);
					$display_char = "<span class='avia-animated-number-icon {$icon_select}-number av-icon-char' {$char}></span>";
					if($icon_select == 'av-icon-before') $before = $display_char;
					if($icon_select == 'av-icon-after')  $after  = $display_char;
				}
				
        		// add blockquotes to the content
        		$output  = '<'.$tags[0].' '.$style.' class="avia-animated-number av-force-default-color '.$av_display_classes.' avia-color-'.$color.' '.$meta['el_class'].' avia_animate_when_visible" '.$style.'>';
        		$output .= 		'<strong class="heading avia-animated-number-title" '.$font_style.'>';
        		$output .= 		$before.$this->extract_numbers($number).$after;
        		$output .= 		"</strong>";
        		$output .= 		"<div class='avia-animated-number-content' {$font_style2}>";
        		$output .= 		wpautop( ShortcodeHelper::avia_remove_autop( $content ) );
        		$output .= 	'</div></'.$tags[1].'>';
        		
        		return $output;
			}
			
			
			function extract_numbers($number)
			{
				$number = strip_tags(apply_filters('avf_big_number', $number));
				$number = preg_replace('!(\D+)!','<span class="avia-no-number">$1</span>',$number);
				$number = preg_replace('!(\d+)!','<span class="avia-single-number __av-single-number" data-number="$1">$1</span>',$number);
				return $number;
			}
			
			
	}
}