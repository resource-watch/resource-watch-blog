<?php
/**
 * Horizontal Gallery
 * 
 * Creates a horizontal scrollable gallery
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_gallery_horizontal' ) )
{
	class avia_sc_gallery_horizontal extends aviaShortcodeTemplate
	{
			static $hor_gallery = 0;

			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'no';
				
				$this->config['name']			= __('Horizontal Gallery', 'avia_framework' );
				$this->config['tab']			= __('Media Elements', 'avia_framework' );
				$this->config['icon']			= AviaBuilder::$path['imagesURL']."sc-accordion-slider.png";
				$this->config['order']			= 6;
				$this->config['target']			= 'avia-target-insert';
				$this->config['shortcode'] 		= 'av_horizontal_gallery';
				$this->config['tooltip']        = __('Creates a horizontal scrollable gallery ', 'avia_framework' );
				$this->config['preview'] 		= false;
				$this->config['drag-level'] 	= 3;
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
							"name" 	=> __("Edit Gallery",'avia_framework' ),
							"desc" 	=> __("Create a new Gallery by selecting existing or uploading new images",'avia_framework' ),
							"id" 	=> "ids",
							"type" 	=> "gallery",
							"modal_class" => 'av-show-image-custom-link',
							"title" => __("Add/Edit Gallery",'avia_framework' ),
							"button" => __("Insert Images",'avia_framework' ),
							"std" 	=> ""),
					
					array(
						"name" 	=> __("Gallery Height", 'avia_framework' ),
						"desc" 	=> __("Set the gallery height in relation to the gallery container width", 'avia_framework' ),
						"id" 	=> "height",
						"type" 	=> "select",
						"std" 	=> "25",
						"subtype" => AviaHtmlHelper::number_array(0,50,5, array() ,'%')
					),
					
					
					array(
                        "name" 	=> __("Image Size", 'avia_framework' ),
                        "desc" 	=> __("Choose size for each image", 'avia_framework' ),
                        "id" 	=> "size",
                        "type" 	=> "select",
                        "std" 	=> "large",
                        "subtype" =>  AviaHelper::get_registered_image_sizes(array('logo'))
                    ),

					array(
						"name" 	=> __("Image Link", 'avia_framework' ),
						"desc" 	=> __("By default images got a small link to a larger image version in a lightbox. You can deactivate that link. You can also set custom links when editing the images in the gallery", 'avia_framework' ),
						"id" 	=> "links",
						"type" 	=> "select",
						"std" 	=> "active",
						"subtype" => array(
							__('Lightbox linking active',  'avia_framework' ) =>'active',
							__('Lightbox linking deactivated',  'avia_framework' ) =>'',
						)
					),
					
					array(
						"name"		=> __("Lightbox image description text", 'avia_framework' ),
						"desc"		=> __("Select which text defined in the media gallery is displayed below the lightbox image.", 'avia_framework' ),
						"id"		=> "lightbox_text",
						"type"		=> "select",
						"std"		=> "",
						"required" 	=> array( 'links', 'equals', 'active'),
						"subtype"	=> array(
								__('No text', 'avia_framework' )										=> 'no_text',
								__('Image title', 'avia_framework' )									=> '',
								__('Image description (or image title if empty)', 'avia_framework' )	=> 'description',
								__('Image caption (or image title if empty)', 'avia_framework' )		=> 'caption'
						)
					),
					
					array(
						"name"		=> __("Custom link destination", 'avia_framework' ),
						"desc"		=> __("Select where an existing custom link should be opend.", 'avia_framework' ),
						"id"		=> "link_dest",
						"type"		=> "select",
						"std"		=> "",
						"required" 	=> array( 'links', 'equals', ''),
						"subtype"	=> array(
								__('Open in same window', 'avia_framework' )		=> '',
								__('Open in a new window', 'avia_framework' )		=> '_blank'
						)
					),
	                    	
	                array(
					"name" 	=> __("Gap between images", 'avia_framework' ),
					"desc" 	=> __("Select the gap between the images", 'avia_framework' ),
					"id" 	=> "gap",
					"type" 	=> "select",
					"std" 	=> "large",
					"subtype" => array(
						__('No Gap',  'avia_framework' ) =>'no',
						__('1 Pixel Gap',  'avia_framework' ) =>'1px',
						__('Large Gap',  'avia_framework' ) =>'large',
					)),    	
	                
	                
	                array(
					"name" 	=> __("Active Image Style", 'avia_framework' ),
					"desc" 	=> __("How do you want to display the active image", 'avia_framework' ),
					"id" 	=> "active",
					"type" 	=> "select",
					"std" 	=> "enlarge",
					"subtype" => array(
						__('No effect',  'avia_framework' ) =>'',
						__('Enlarge Image',  'avia_framework' ) =>'enlarge',
					)),  
	                
	                array(
                    "name" 	=> __("Initial Active Image", 'avia_framework' ),
                    "desc" 	=> __("Enter the Number of the image that should be open initially.", 'avia_framework' ),
                    "id" 	=> "initial",
                    "std" 	=> "",
                    "type" 	=> "input"),
	                
	                
	                array(	
						"name" 	=> __("Gallery control styling?",'avia_framework' ),
						"desc" 	=> __("Here you can select if and how to display the slideshow controls",'avia_framework' ),
						"id" 	=> "control_layout",
						"type" 	=> "select",
						"std" 	=> "",
						"subtype" => array(__('Default','avia_framework' ) =>'av-control-default',__('Minimal White','avia_framework' ) =>'av-control-minimal', __('Minimal Black','avia_framework' ) =>'av-control-minimal av-control-minimal-dark',__('Hidden','avia_framework' ) =>'av-control-hidden')),
	                
					
					array(	
						"name" 	=> __("For Developers: Section ID", 'avia_framework' ),
						"desc" 	=> __("Apply a custom ID Attribute to the section, so you can apply a unique style via CSS. This option is also helpful if you want to use anchor links to scroll to a sections when a link is clicked", 'avia_framework' )."<br/><br/>".
								   __("Use with caution and make sure to only use allowed characters. No special characters can be used.", 'avia_framework' ),
			            "id" 	=> "id",
			            "type" 	=> "input",
			            "std"	=> "" ),
					
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
				$params['innerHtml'] = "<img src='".$this->config['icon']."' title='".$this->config['name']."' />";
				$params['innerHtml'].= "<div class='avia-element-label'>".$this->config['name']."</div>";
				
				
				$params['innerHtml'].= "<div class='avia-flex-element'>"; 
				$params['innerHtml'].= 		__('This element will stretch across the whole screen by default.','avia_framework')."<br/>";
				$params['innerHtml'].= 		__('If you put it inside a color section or column it will only take up the available space','avia_framework');
				$params['innerHtml'].= "	<div class='avia-flex-element-2nd'>".__('Currently:','avia_framework');
				$params['innerHtml'].= "	<span class='avia-flex-element-stretched'>&laquo; ".__('Stretch fullwidth','avia_framework')." &raquo;</span>";
				$params['innerHtml'].= "	<span class='avia-flex-element-content'>| ".__('Adjust to content width','avia_framework')." |</span>";
				$params['innerHtml'].= "</div></div>";
				
				return $params;
			}
			
			
			
			
			protected function slide_navigation_arrows()
			{
				$html  = "";
				$html .= "<div class='avia-slideshow-arrows avia-slideshow-controls'>";
				$html .= 	"<a href='#prev' class='prev-slide av-horizontal-gallery-prev' ".av_icon_string('prev_big').">".__('Previous','avia_framework' )."</a>";
				$html .= 	"<a href='#next' class='next-slide av-horizontal-gallery-next' ".av_icon_string('next_big').">".__('Next','avia_framework' )."</a>";
				$html .= "</div>";
	
				return $html;
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
				$output = "";
				
				extract(AviaHelper::av_mobile_sizes($atts)); //return $av_font_classes, $av_title_font_classes and $av_display_classes 
				
				extract(shortcode_atts(array(
						'height'      		=> '400',
						'size' 				=> 'large',
						'links' 			=> 'active',
						'lightbox_text'		=> '',				//	default to title
						'link_dest'			=> '',
						'gap'				=> 'large',
						'ids'    	 		=> '',
						'active'    		=> 'enlarge',
						'control_layout'	=> 'av-control-default',
						'initial'			=> '',
						'id'				=> ''
				
				), $atts, $this->config['shortcode']));
					

				$attachments = get_posts(array(
				'include' => $ids,
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => 'DESC',
				'orderby' => 'post__in')
				);
				
				$display_char = av_icon('ue869', 'entypo-fontello');
				$padding = "";
				
				if($active == "enlarge")
				{
					$enlarge_by = 1.3;
					$padding 	= (( $height * $enlarge_by ) - $height ) / 2;
					$padding 	= "style='padding: {$padding}% 0px;' data-av-enlarge='{$enlarge_by}' ";
				}
				
				if(!empty($attachments) && is_array($attachments))
				{
					self::$hor_gallery++;
					
					if( ! is_numeric( $initial ) || ( (int) $initial <= 0 ) )
					{
						$initial = '';
					}

					if( ! empty( $initial ) )
					{
						$initial = ( (int) $initial > count( $attachments ) ) ? count( $attachments ) : $initial;
						$initial = "data-av-initial='{$initial}' ";
					}

					$counter 	= 0;
                    $markup 	= avia_markup_helper(
                    	array('context' => 'image','echo'=>false, 'custom_markup'=>$meta['custom_markup'])
                    );
                    
					$output .= "<div class='av-horizontal-gallery av-horizontal-gallery-{$gap}-gap av-horizontal-gallery-{$active}-effect av-horizontal-gallery-".self::$hor_gallery." ".$meta['el_class']." {$control_layout} {$av_display_classes}' {$markup} {$padding} {$initial}>";
					
					$output .= $this->slide_navigation_arrows();

					
					$output .= "<div class='av-horizontal-gallery-inner' style='padding-bottom:{$height}%' data-av-height='{$height}'>";
					$output .= "<div class='av-horizontal-gallery-slider'>";
					
					foreach($attachments as $attachment)
					{
						$counter ++;
						$img  	 		= wp_get_attachment_image_src($attachment->ID, $size);
						$lightbox	 	= wp_get_attachment_image_src($attachment->ID, 'large');
						$lightbox		= $lightbox[0];
						
						$alt			= get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
						$alt			= ! empty($alt) ? esc_attr($alt) : '';
						
						$title			= trim($attachment->post_title) ? esc_attr($attachment->post_title) : "";
						$description	= trim($attachment->post_content) ? esc_attr($attachment->post_content) : '';
						$caption		= trim($attachment->post_excerpt) ? esc_attr($attachment->post_excerpt) : '';
						
						$custom_link	= get_post_meta( $attachment->ID, 'av-custom-link', true );
						$custom_link	= ! empty( $custom_link ) ? esc_attr( $custom_link ) : '';
						
						$lightbox_title = $title;
						switch( $lightbox_text )
						{
							case 'caption':
								$lightbox_title = ( '' != $caption ) ? $caption : $title;
								break;
							case 'description':
								$lightbox_title = ( '' != $description ) ? $description : $title;
								break;
							case 'no_text':
								$lightbox_title = '';
						}
						
						if( $links != '' )		//	ignore custom link, if lightbox is active
						{
							$custom_link = '';
						}
						else if( $custom_link != '' )
						{
							if( '' != $title )
							{
								$title = ' - ' . $title;
							}
							$title = __( 'Click to show details', 'avia_framework' ) . $title;
						}
						
						$output .= "<div class='av-horizontal-gallery-wrap noHover'>";
								
								if( ( '' == $links ) && ( $custom_link != '' ) ) 
								{
									$target = ( $link_dest != '' ) ?  ' target="' . $link_dest . '"' : '';
									$output .= '<a href="' . $custom_link . '"' . $target . '>';
								}
								
								$output .= "<img class='av-horizontal-gallery-img' ";
								$output .= "width='".$img[1]."' height='".$img[2]."' src='".$img[0]."' title='".$title."' alt='".$alt."' />";	
								
								if( $links != "" )
								{
									$output .= "<a href='{$lightbox}'  class='av-horizontal-gallery-link' {$display_char} title='{$lightbox_title}'>";		
									$output .= "</a>";
								}
								else if( $custom_link != '' )
								{
									$output .= "</a>";
								}
								
						$output .= "</div>";

					}
					
					$output .= "</div>";
					$output .= "</div>";
					$output .= "</div>";

				}

				if(!ShortcodeHelper::is_top_level()) return $output;
				$params = array();
				$params['class'] = "main_color av-horizontal-gallery-fullwidth avia-no-border-styling {$av_display_classes} ".$meta['el_class'];
				$params['open_structure'] = false;
				$params['id'] = !empty($atts['id']) ? AviaHelper::save_string($atts['id'],'-') : "";
				$params['custom_markup'] = $meta['custom_markup'];
				if($meta['index'] == 0) $params['class'] .= " avia-no-border-styling";
				
				//we dont need a closing structure if the element is the first one or if a previous fullwidth element was displayed before
				if($meta['index'] == 0) $params['close'] = false;
				if(!empty($meta['siblings']['prev']['tag']) && in_array($meta['siblings']['prev']['tag'], AviaBuilder::$full_el_no_section )) $params['close'] = false;
				
				$html = $output;
				
				$output  =  avia_new_section($params);
				$output .= $html;
				$output .= "</div><!-- close section -->"; //close section
				
				
				//if the next tag is a section dont create a new section from this shortcode
				if(!empty($meta['siblings']['next']['tag']) && in_array($meta['siblings']['next']['tag'], AviaBuilder::$full_el ))
				{
				    $skipSecond = true;
				}

				//if there is no next element dont create a new section.
				if(empty($meta['siblings']['next']['tag']))
				{
				    $skipSecond = true;
				}
				
				if(empty($skipSecond)) {
				
				$output .= avia_new_section(array('close'=>false, 'id' => "after_horizontal_gallery"));
				
				}
				
				return $output;
				
				
				
			}


	}
}

