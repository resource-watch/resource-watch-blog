<?php
/**
 * Special Heading
 * 
 * Creates a special Heading
 */
 
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }



if ( !class_exists( 'avia_sc_heading' ) ) 
{
	class avia_sc_heading extends aviaShortcodeTemplate{
			
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'no';
				
				$this->config['name']		= __('Special Heading', 'avia_framework' );
				$this->config['tab']		= __('Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-heading.png";
				$this->config['order']		= 93;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_heading';
				$this->config['modal_data'] = array('modal_class' => 'mediumscreen');
				$this->config['tooltip'] 	= __('Creates a special Heading', 'avia_framework' );
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
						"name" 	=> __("Heading Text", 'avia_framework' ),
						"id" 	=> "heading",
						'container_class' =>"avia-element-fullwidth",
						"std" 	=> __("Hello", 'avia_framework' ),
						"type" 	=> "input"),
						
					 array(	
							"name" 	=> __("Heading Type", 'avia_framework' ),
							"desc" 	=> __("Select which kind of heading you want to display.", 'avia_framework' ),
							"id" 	=> "tag",
							"type" 	=> "select",
							"std" 	=> "h3",
							"subtype" => array("H1"=>'h1',"H2"=>'h2',"H3"=>'h3',"H4"=>'h4',"H5"=>'h5',"H6"=>'h6')
							), 
							
					
					array(	
							"name" 	=> __("Heading Style", 'avia_framework' ),
							"desc" 	=> __("Select a heading style", 'avia_framework' ),
							"id" 	=> "style",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array( __("Default Style", 'avia_framework' )=>'',  __("Heading Style Modern (left)", 'avia_framework' )=>'blockquote modern-quote' , __("Heading Style Modern (centered)", 'avia_framework' )=>'blockquote modern-quote modern-centered', __("Heading Style Classic (centered, italic)", 'avia_framework' )=>'blockquote classic-quote')
							),   
				            
							
					array(	"name" 	=> __("Heading Size", 'avia_framework' ),
							"desc" 	=> __("Size of your Heading in Pixel", 'avia_framework' ),
				            "id" 	=> "size",
				            "type" 	=> "select",
				            "subtype" => AviaHtmlHelper::number_array(20,90,1, array( __("Default Size", 'avia_framework' )=>'')),
				            "required" => array('style','not',''),
				            "std" => ""),
				            				            
				     array(	
							"name" 	=> __("Subheading", 'avia_framework' ),
							"desc" 	=> __("Add an extra descriptive subheading above or below the actual heading", 'avia_framework' ),
							"id" 	=> "subheading_active",
							"type" 	=> "select",
							"std" 	=> "",
				            "required" => array('style','not',''),
							"subtype" => array( __("No Subheading", 'avia_framework' )=>'',  __("Display subheading above", 'avia_framework' ) =>'subheading_above',  __("Display subheading below", 'avia_framework' )=>'subheading_below'),
							),  							  
							  
					array(
						"name" 	=> __("Subheading Text",'avia_framework' ),
						"desc" 	=> __("Add your subheading here",'avia_framework' ),
						"id" 	=> "content",
						"type" 	=> "textarea",
						"required" => array('subheading_active','not',''),
						"std" 	=> ""),   
						
					array(	"name" 	=> __("Subheading Size", 'avia_framework' ),
							"desc" 	=> __("Size of your subeading in Pixel", 'avia_framework' ),
				            "id" 	=> "subheading_size",
				            "type" 	=> "select",
				            "subtype" => AviaHtmlHelper::number_array(10,40,1),
				            "required" => array('subheading_active','not',''),
				            "std" => "15"), 
				            	
                    array(	"name" 	=> __("Padding Bottom", 'avia_framework' ),
							"desc" 	=> __("Bottom Padding in pixel", 'avia_framework' ),
				            "id" 	=> "padding",
				            "type" 	=> "select",
				            "subtype" => AviaHtmlHelper::number_array(0,120,1),
				            "std" => "10"),  
				     
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
							"name" 	=> __("Heading Color", 'avia_framework' ),
							"desc" 	=> __("Select a heading color", 'avia_framework' ),
							"id" 	=> "color",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array( __("Default Color", 'avia_framework' )=>'', __("Meta Color", 'avia_framework' )=>'meta-heading', __("Custom Color", 'avia_framework' )=>'custom-color-heading')
							), 
					
					array(	
							"name" 	=> __("Custom Font Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom font color for your Heading here", 'avia_framework' ),
							"id" 	=> "custom_font",
							"type" 	=> "colorpicker",
							"std" 	=> "",
							"required" => array('color','equals','custom-color-heading')
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
									"name" 	=> __("Subheading Font Size",'avia_framework' ),
									"desc" 	=> __("Set the font size for the subheading, based on the device screensize.", 'avia_framework' ),
									"type" 	=> "heading",
									"description_class" => "av-builder-note av-neutral",
									),
										
									array(	"name" 	=> __("Font Size for medium sized screens", 'avia_framework' ),
						            "id" 	=> "av-medium-font-size",
						            "type" 	=> "select",
						            "subtype" => AviaHtmlHelper::number_array(10,120,1, array( __("Default", 'avia_framework' )=>'', __("Hidden", 'avia_framework' )=>'hidden'), "px"),
						            "std" => ""),
						            
						            array(	"name" 	=> __("Font Size for small screens", 'avia_framework' ),
						            "id" 	=> "av-small-font-size",
						            "type" 	=> "select",
						            "subtype" => AviaHtmlHelper::number_array(10,120,1, array( __("Default", 'avia_framework' )=>'', __("Hidden", 'avia_framework' )=>'hidden'), "px"),
						            "std" => ""),
						            
									array(	"name" 	=> __("Font Size for very small screens", 'avia_framework' ),
						            "id" 	=> "av-mini-font-size",
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
				
				$params['args'] = shortcode_atts(array('tag' => 'h3', 'padding' => '5', 'heading'=>'', 'color'=>'', 'style'=>'', 'custom_font'=>'', 'size'=>'', 'subheading_active' => '', 'subheading_size'=>'', 'custom_class'=>'', 'admin_preview_bg'=>'',
				'av-desktop-hide'=>'',
				'av-medium-hide'=>'',
				'av-small-hide'=>'',
				'av-mini-hide'=>'',
				'av-medium-font-size-title'=>'',
				'av-small-font-size-title'=>'',
				'av-mini-font-size-title'=>'',
				'av-medium-font-size'=>'',
				'av-small-font-size'=>'',
				'av-mini-font-size'=>'',
				
				
				), $params['args'], $this->config['shortcode']);
				
				$templateNAME  	= $this->update_template("name", "{{name}}");
				
				$content = stripslashes(wpautop(trim(html_entity_decode( $params['content']) )));
				
				$params['class'] = "";
				$params['innerHtml']  = "<div class='avia_textblock avia_textblock_style avia-special-heading' >";
				
				$params['innerHtml'] .= 	"<div ".$this->class_by_arguments('tag, style, color, subheading_active' ,$params['args']).">";
				$params['innerHtml'] .= 		"<div class='av-subheading-top av-subheading' data-update_with='content'>".$content."</div>";
				$params['innerHtml'] .= 		"<div data-update_with='heading'>";
				$params['innerHtml'] .= 		stripslashes(trim(htmlspecialchars_decode($params['args']['heading'])));
				$params['innerHtml'] .= 		"</div>";
				$params['innerHtml'] .= 		"<div class='av-subheading-bottom av-subheading' data-update_with='content'>".$content."</div>";
				$params['innerHtml'] .= 	"</div>";
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
				
			    extract(shortcode_atts(array('tag' => 'h3', 'padding' => '5', 'heading'=>'', 'color'=>'', 'style'=>'', 'custom_font'=>'', 'size'=>'', 'subheading_active' => '', 'subheading_size'=>''), $atts, $this->config['shortcode']));
			
        		$output  = "";
        		$styling = "";
        		$subheading = "";
        		$border_styling = "";
        		$before = $after = "";
        		$class   = $meta['el_class'];
        		$subheading_extra = "";
        		
        		if($heading)
        		{
        			// add seo markup
                    $markup = avia_markup_helper(array('context' => 'entry_title','echo'=>false, 'custom_markup'=>$meta['custom_markup']));
					
					// filter heading for & symbol and convert them					
        			$heading = apply_filters('avia_ampersand', wptexturize($heading));
        			
        			//if the heading contains a strong tag make apply a custom class that makes the rest of the font appear smaller for a better effect
        			if( strpos($heading, '<strong>') !== false ) $class .= " av-thin-font";
        			
        			//apply the padding bottom styling
	        		$styling .= "padding-bottom:{$padding}px;";
	        		
	        		// if the color is a custom hex value add the styling for both border and font
	        		if($color == "custom-color-heading" && $custom_font)  
	        		{
	        			$styling .= "color:{$custom_font};";
	        			$border_styling = "style='border-color:{$custom_font}'";
	        			$subheading_extra = "av_custom_color";
	        		}
	        		
	        		// if a custom font size is set apply it to the container and also apply the inherit class so the actual heading uses the size
	        		if(!empty($style) && !empty($size)) { $styling .= "font-size:{$size}px;"; $class .= " av-inherit-size";}
	        		
	        		//finish up the styling string
	        		if(!empty($styling)) $styling = "style='{$styling}'";
	        		
	        		//check if we got a subheading
	        		if( !empty( $style ) && !empty( $subheading_active ) && !empty( $content ) )
	        		{
	        			
	        			$content = "<div class ='av-subheading av-{$subheading_active} {$subheading_extra} {$av_font_classes}' style='font-size:{$subheading_size}px;'>".ShortcodeHelper::avia_apply_autop(ShortcodeHelper::avia_remove_autop($content) )."</div>";
	        		
	        			if($subheading_active == "subheading_above")
	        			{
	        				$before = $content;
	        			}
	        			else
	        			{
	        				$after = $content;
	        			}
	        		}
	        	
	        		//html markup
	        		$output .= "<div {$styling} class='av-special-heading av-special-heading-{$tag} {$color} {$style} {$class} {$av_display_classes}'>";
	        		$output .= 		$before;
	        		$output .= 		"<{$tag} class='av-special-heading-tag {$av_title_font_classes}' $markup >{$heading}</{$tag}>";
	        		$output .= 		$after;
	        		$output .= 		"<div class='special-heading-border'><div class='special-heading-inner-border' {$border_styling}></div></div>";
	        		$output .= "</div>";
        		}
        		
        		return $output;
        	}
			
			
	}
}
