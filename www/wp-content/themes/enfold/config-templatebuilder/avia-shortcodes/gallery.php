<?php
/**
 * Gallery
 * 
 * Shortcode that allows to create a gallery based on images selected from the media library
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_gallery' ) )
{
	class avia_sc_gallery extends aviaShortcodeTemplate
	{
			static $gallery = 0;
			var $extra_style = "";
			var $non_ajax_style = "";

			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'yes';
				
				$this->config['name']			= __('Gallery', 'avia_framework' );
				$this->config['tab']			= __('Media Elements', 'avia_framework' );
				$this->config['icon']			= AviaBuilder::$path['imagesURL']."sc-gallery.png";
				$this->config['order']			= 6;
				$this->config['target']			= 'avia-target-insert';
				$this->config['shortcode'] 		= 'av_gallery';
				$this->config['modal_data']     = array('modal_class' => 'mediumscreen');
				$this->config['tooltip']        = __('Creates a custom gallery', 'avia_framework' );
				$this->config['preview'] 		= 1;
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
							"title" => __("Add/Edit Gallery",'avia_framework' ),
							"button" => __("Insert Images",'avia_framework' ),
							"std" 	=> ""),

					array(
							"name" 	=> __("Gallery Style", 'avia_framework' ),
							"desc" 	=> __("Choose the layout of your Gallery", 'avia_framework' ),
							"id" 	=> "style",
							"type" 	=> "select",
							"std" 	=> "thumbnails",
							"subtype" => array(
												__('Small Thumbnails',  'avia_framework' ) =>'thumbnails',
												__('Big image with thumbnails below', 'avia_framework' ) =>'big_thumb',
												__('Big image only, other images can be accessed via lightbox', 'avia_framework' ) =>'big_thumb lightbox_gallery',
												)
							),

					array(
							"name" 	=> __("Gallery Big Preview Image Size", 'avia_framework' ),
							"desc" 	=> __("Choose image size for the Big Preview Image", 'avia_framework' ),
							"id" 	=> "preview_size",
							"type" 	=> "select",
							"std" 	=> "portfolio",
							"required" 	=> array('style','contains','big_thumb'),
							"subtype" =>  AviaHelper::get_registered_image_sizes(array('logo'))
							),

					array(
							"name" 	=> __("Force same size for all big preview images?", 'avia_framework' ),
							"desc" 	=> __("Depending on the size you selected above, preview images might differ in size. Should the theme force them to display at exactly the same size?", 'avia_framework' ),
							"id" 	=> "crop_big_preview_thumbnail",
							"type" 	=> "select",
							"std" 	=> "yes",
							"required" 	=> array('style','equals','big_thumb'),
							"subtype" =>  array(__('Yes, force same size on all Big Preview images, even if they use a different aspect ratio', 'avia_framework') => 'avia-gallery-big-crop-thumb', __('No, do not force the same size', 'avia_framework') => 'avia-gallery-big-no-crop-thumb')),

					array(
                        "name" 	=> __("Gallery Preview Image Size", 'avia_framework' ),
                        "desc" 	=> __("Choose image size for the small preview thumbnails", 'avia_framework' ),
                        "id" 	=> "thumb_size",
                        "type" 	=> "select",
                        "std" 	=> "portfolio",
							"required" 	=> array('style','not','big_thumb lightbox_gallery'),
                        "subtype" =>  AviaHelper::get_registered_image_sizes(array('logo'))
                    ),

					array(
							"name" 	=> __("Thumbnail Columns", 'avia_framework' ),
							"desc" 	=> __("Choose the column count of your Gallery", 'avia_framework' ),
							"id" 	=> "columns",
							"type" 	=> "select",
							"std" 	=> "5",
							"required" 	=> array('style','not','big_thumb lightbox_gallery'),
							"subtype" => AviaHtmlHelper::number_array(1,12,1)
							),

					array(
	                        "name" 	=> __("Use Lighbox", 'avia_framework' ),
	                        "desc" 	=> __("Do you want to activate the lightbox", 'avia_framework' ),
	                        "id" 	=> "imagelink",
	                        "type" 	=> "select",
	                        "std" 	=> "5",
							"required" 	=> array('style','not','big_thumb lightbox_gallery'),
	                        "subtype" => array(
	                            __('Yes',  'avia_framework' ) =>'lightbox',
	                            __('No, open the images in the browser window', 'avia_framework' ) =>'aviaopeninbrowser noLightbox',
	                            __('No, open the images in a new browser window/tab', 'avia_framework' ) =>'aviaopeninbrowser aviablank noLightbox',
	                            __('No, don\'t add a link to the images at all', 'avia_framework' ) =>'avianolink noLightbox')
	                    	),

	                    array(
		                        "name" 	=> __("Thumbnail fade in effect", 'avia_framework' ),
		                        "desc" 	=> __("You can set when the gallery thumbnail animation starts", 'avia_framework' ),
		                        "id" 	=> "lazyload",
		                        "type" 	=> "select",
		                        "std" 	=> "avia_lazyload",
							"required" 	=> array('style','not','big_thumb lightbox_gallery'),
		                        "subtype" => array(
		                            __('Show the animation when user scrolls to the gallery',  'avia_framework' ) =>'avia_lazyload',
		                            __('Activate animation on page load (might be preferable on large galleries)', 'avia_framework' ) =>'deactivate_avia_lazyload')
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
				$params['innerHtml'] = "<img src='".$this->config['icon']."' title='".$this->config['name']."' />";
				$params['innerHtml'].= "<div class='avia-element-label'>".$this->config['name']."</div>";
				$params['content'] 	 = NULL; //remove to allow content elements
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
				
				$output  = "";
				$first   = true;
				
				if(empty($atts['columns']) && isset($atts['ids']))
				{
					$atts['columns'] = count(explode(",", $atts['ids']));
					if($atts['columns'] > 10) { $atts['columns'] = 10; }
				}
				
				extract(shortcode_atts(array(
				'order'      	=> 'ASC',
				'thumb_size' 	=> 'thumbnail',
				'size' 			=> '',
				'lightbox_size' => 'large',
				'preview_size'	=> 'portfolio',
				'ids'    	 	=> '',
				'ajax_request'	=> false,
				'imagelink'     => 'lightbox',
				'style'			=> 'thumbnails',
				'columns'		=> 5,
                'lazyload'      => 'avia_lazyload',
                'crop_big_preview_thumbnail' => 'avia-gallery-big-crop-thumb'
				), $atts, $this->config['shortcode']));
					

				$attachments = get_posts(array(
				'include' => $ids,
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $order,
				'orderby' => 'post__in')
				);
				
				
				//compatibility mode for default wp galleries
				if(!empty($size)) $thumb_size = $size;
				
				
				if('big_thumb lightbox_gallery' == $style)
				{
					$imagelink = 'lightbox';
					$lazyload  = 'deactivate_avia_lazyload';
					$meta['el_class'] .= " av-hide-gallery-thumbs";
				}
				
				

				if(!empty($attachments) && is_array($attachments))
				{
					self::$gallery++;
					$thumb_width = round(100 / $columns, 4);

                    $markup = avia_markup_helper(array('context' => 'image','echo'=>false, 'custom_markup'=>$meta['custom_markup']));
					$output .= "<div class='avia-gallery {$av_display_classes} avia-gallery-".self::$gallery." ".$lazyload." avia_animate_when_visible ".$meta['el_class']."' $markup>";
					$thumbs = "";
					$counter = 0;

					foreach($attachments as $attachment)
					{
						$link	 =  apply_filters('avf_avia_builder_gallery_image_link', wp_get_attachment_image_src($attachment->ID, $lightbox_size), $attachment, $atts, $meta);
						$custom_link_class = !empty($link['custom_link_class']) ? $link['custom_link_class'] : '';
						$class	 = $counter++ % $columns ? "class='$imagelink $custom_link_class'" : "class='first_thumb $imagelink $custom_link_class'";
						$img  	 = wp_get_attachment_image_src($attachment->ID, $thumb_size);
						$prev	 = wp_get_attachment_image_src($attachment->ID, $preview_size);

						$caption = trim($attachment->post_excerpt) ? wptexturize($attachment->post_excerpt) : "";
						$tooltip = $caption ? "data-avia-tooltip='".$caption."'" : "";

                        $alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
                        $alt = !empty($alt) ? esc_attr($alt) : '';
                        $title = trim($attachment->post_title) ? esc_attr($attachment->post_title) : "";
                        $description = trim($attachment->post_content) ? esc_attr($attachment->post_content) : esc_attr(trim($attachment->post_excerpt));

                        $markup_url = avia_markup_helper(array('context' => 'image_url','echo'=>false, 'id'=>$attachment->ID, 'custom_markup'=>$meta['custom_markup']));

						if( strpos($style, "big_thumb") !== false && $first)
						{
							$output .= "<a class='avia-gallery-big fakeLightbox $imagelink $crop_big_preview_thumbnail $custom_link_class' href='".$link[0]."'  data-onclick='1' title='".$description."' ><span class='avia-gallery-big-inner' $markup_url>";
							$output .= "	<img width='".$prev[1]."' height='".$prev[2]."' src='".$prev[0]."' title='".$title."' alt='".$alt."' />";
			   if($caption) $output .= "	<span class='avia-gallery-caption'>{$caption}</span>";
							$output .= "</span></a>";
						}

						$thumbs .= " <a href='".$link[0]."' data-rel='gallery-".self::$gallery."' data-prev-img='".$prev[0]."' {$class} data-onclick='{$counter}' title='".$description."' $markup_url><img {$tooltip} src='".$img[0]."' width='".$img[1]."' height='".$img[2]."'  title='".$title."' alt='".$alt."' /></a>";
						$first = false;
					}

					$output .= "<div class='avia-gallery-thumb'>{$thumbs}</div>";
					$output .= "</div>";
					
					$selector = !empty($atts['ajax_request']) ? ".ajax_slide" : "";
					
					//generate thumb width based on columns
					$this->extra_style .= "<style type='text/css'>";
					$this->extra_style .= "#top #wrap_all {$selector} .avia-gallery-".self::$gallery." .avia-gallery-thumb a{width:{$thumb_width}%;}";
					$this->extra_style .= "</style>";
					
					if(!empty($this->extra_style))
					{
						
						if(!empty($atts['ajax_request']) || !empty($_POST['avia_request']))
						{
							$output .= $this->extra_style;
							$this->extra_style = "";
						}
						else
						{
							$this->non_ajax_style = $this->extra_style;
							add_action('wp_footer', array($this, 'print_extra_style'));
						}
					}

				}

				return $output;
			}


			function print_extra_style()
			{
				echo $this->non_ajax_style;
			}

	}
}

