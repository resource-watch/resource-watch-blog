<?php
/**
 * Easy Slider
 * 
 * Shortcode that allows to display a simple slideshow
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_slider' ) )
{
	class avia_sc_slider extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'no';
				
				$this->config['name']			= __('Easy Slider', 'avia_framework' );
				$this->config['tab']			= __('Media Elements', 'avia_framework' );
				$this->config['icon']			= AviaBuilder::$path['imagesURL']."sc-slideshow.png";
				$this->config['order']			= 85;
				$this->config['target']			= 'avia-target-insert';
				$this->config['shortcode'] 		= 'av_slideshow';
				$this->config['shortcode_nested'] = array('av_slide');
				$this->config['tooltip'] 	    = __('Display a simple slideshow element', 'avia_framework' );
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
							"type" 			=> "modal_group",
							"id" 			=> "content",
							'container_class' =>"avia-element-fullwidth avia-multi-img",
							"modal_title" 	=> __("Edit Form Element", 'avia_framework' ),
							"add_label"		=>  __("Add single image or video", 'avia_framework' ),
							"std"			=> array(),

							'creator'		=>array(

								"name" => __("Add Images", 'avia_framework' ),
								"desc" => __("Here you can add new Images to the slideshow.", 'avia_framework' ),
								"id" 	=> "id",
								"type" 	=> "multi_image",
								"title" => __("Add multiple Images",'avia_framework' ),
								"button" => __("Insert Images",'avia_framework' ),
								"std" 	=> ""),

							'subelements' 	=> array(
									
									
									array(
										"type" 	=> "tab_container", 'nodescription' => true
									),
									
									array(
										"type" 	=> "tab",
										"name"  => __("Content" , 'avia_framework'),
										'nodescription' => true
									),
													
									
									
									
									array(	
										"name" 	=> __("Which type of slide is this?",'avia_framework' ),
										"id" 	=> "slide_type",
										"type" 	=> "select",
										"std" 	=> "",
										"subtype" => array(   __('Image Slide','avia_framework' )	=>'image',
										                      __('Video Slide','avia_framework' )	=>'video',
										                      )
								    ),
									
									array(	
									"name" 	=> __("Choose another Image",'avia_framework' ),
									"desc" 	=> __("Either upload a new, or choose an existing image from your media library",'avia_framework' ),
									"id" 	=> "id",
									"fetch" => "id",
									"type" 	=> "image",
									"required"=> array('slide_type','is_empty_or','image'),
									"title" => __("Change Image",'avia_framework' ),
									"button" => __("Change Image",'avia_framework' ),
									"std" 	=> ""),
									
									array(	
									"name" 	=> __("Video URL", 'avia_framework' ),
									"desc" 	=> __('Enter the URL to the Video. Currently supported are Youtube, Vimeo and direct linking of web-video files (mp4, webm, ogv)', 'avia_framework' ) .'<br/><br/>'.
									__('Working examples Youtube & Vimeo:', 'avia_framework' ).'<br/>
								<strong>http://vimeo.com/1084537</strong><br/> 
								<strong>http://www.youtube.com/watch?v=5guMumPFBag</strong><br/><br/>',
									"required"=> array('slide_type','equals','video'),
									"id" 	=> "video",
									"std" 	=> "http://",
									"type" 	=> "video",
									"title" => __("Upload Video",'avia_framework' ),
									"button" => __("Use Video",'avia_framework' ),
									),
									
									 array(	
									"name" 	=> __("Choose fallback image for mobile devices",'avia_framework' ),
									"desc" 	=> __("Either upload a new, or choose an existing image from your media library",'avia_framework' )."<br/><small>".__("Video on most mobile devices can't be controlled properly with JavaScript, which is mandatory here, therefore you are required to select a fallback image which can be displayed instead", 'avia_framework' ) ."</small>" ,
									"id" 	=> "mobile_image",
									"fetch" => "id",
									"type" 	=> "image",
									"required"=> array('slide_type','equals','video'),
									"title" => __("Choose Image",'avia_framework' ),
									"button" => __("Choose Image",'avia_framework' ),
									"std" 	=> ""),
									
									/*
									array(	
									"name" 	=> __("Video Size", 'avia_framework' ),
									"desc" 	=> __("By default the video will try to match the default slideshow size that was selected in the slider settings at 'Slideshow Image and Video Size'", 'avia_framework' ),
									"id" 	=> "video_format",
									"type" 	=> "select",
									"std" 	=> "",
									"required"=> array('slide_type','equals','video'),
									"subtype" => array( 
														__('Try to match the default slideshow size (Video will not be cropped, but black borders will be visible at each side)',  'avia_framework' ) 	=>'',
														__('Try to match the default slideshow size but stretch the video to fill the whole slider (video will be cropped at top and bottom)',  'avia_framework' ) 	=>'stretch',
														__('Show the full Video without cropping',  'avia_framework' ) =>'full',
														)		
									),
									*/
									
									array(	
									"name" 	=> __("Video Aspect Ratio", 'avia_framework' ),
									"desc" 	=> __("In order to calculate the correct height and width for the video slide you need to enter a aspect ratio (width:height). usually: 16:9 or 4:3.", 'avia_framework' )."<br/>".__("If left empty 16:9 will be used", 'avia_framework' ) ,
									"id" 	=> "video_ratio",
									"std" 	=> "16:9",
									"type" 	=> "input"),
									
									
									 array(	
									"name" 	=> __("Hide Video Controls", 'avia_framework' ),
									"desc" 	=> __("Check if you want to hide the controls (works for youtube and self hosted videos)", 'avia_framework' ) ,
									"id" 	=> "video_controls",
									"required"=> array('slide_type','equals','video'),
									"std" 	=> "",
									"type" 	=> "checkbox"),
									
									array(	
									"name" 	=> __("Mute Video Player", 'avia_framework' ),
									"desc" 	=> __("Check if you want to mute the video", 'avia_framework' ) ,
									"id" 	=> "video_mute",
									"required"=> array('slide_type','equals','video'),
									"std" 	=> "",
									"type" 	=> "checkbox"),
									
									array(	
									"name" 	=> __("Loop Video Player", 'avia_framework' ),
									"desc" 	=> __("Check if you want to loop the video (instead of showing the next slide the video will play from the beginning again)", 'avia_framework' ) ,
									"id" 	=> "video_loop",
									"required"=> array('slide_type','equals','video'),
									"std" 	=> "",
									"type" 	=> "checkbox"),
									
									array(	
									"name" 	=> __("Disable Autoplay", 'avia_framework' ),
									"desc" 	=> __("Check if you want to disable video autoplay when this slide shows", 'avia_framework' ) ,
									"id" 	=> "video_autoplay",
									"required"=> array('slide_type','equals','video'),
									"std" 	=> "",
									"type" 	=> "checkbox"),
									
									array(	
									"name" 	=> __("Caption Title", 'avia_framework' ),
									"desc" 	=> __("Enter a caption title for the slide here", 'avia_framework' ) ,
									"id" 	=> "title",
									"std" 	=> "",
									"type" 	=> "input"),
									
									 array(	
									"name" 	=> __("Caption Text", 'avia_framework' ),
									"desc" 	=> __("Enter some additional caption text", 'avia_framework' ) ,
									"id" 	=> "content",
									"type" 	=> "textarea",
									"std" 	=> "",
									),
									
									array(	
									"name" 	=> __("Apply a link to the slide?", 'avia_framework' ),
									"desc" 	=> __("You can choose to apply the link to the whole image", 'avia_framework' ),
									"id" 	=> "link_apply",
									"type" 	=> "select",
									"std" 	=> "",
									"subtype" => array(
										__('No Link for this slide',  	'avia_framework' ) =>'',
										__('Apply Link to Image',  		'avia_framework' ) =>'image')),
									
									
									array(	
									"name" 	=> __("Image Link?", 'avia_framework' ),
									"desc" 	=> __("Where should the Image link to?", 'avia_framework' ),
									"id" 	=> "link",
									"required"=> array('link_apply','equals','image'),
									"type" 	=> "linkpicker",
									"fetchTMPL"	=> true,
									"subtype" => array(	
														__('Open Image in Lightbox', 'avia_framework' ) =>'lightbox',
														__('Set Manually', 'avia_framework' ) =>'manually',
														__('Single Entry', 'avia_framework' ) => 'single',
														__('Taxonomy Overview Page',  'avia_framework' ) => 'taxonomy',
														),
									"std" 	=> ""),
							
									array(	
									"name" 	=> __("Open Link in new Window?", 'avia_framework' ),
									"desc" 	=> __("Select here if you want to open the linked page in a new window", 'avia_framework' ),
									"id" 	=> "link_target",
									"type" 	=> "select",
									"std" 	=> "",
									"required"=> array('link','not_empty_and','lightbox'),
									"subtype" => AviaHtmlHelper::linking_options()),   
									
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
									"name" 	=> __("Caption Title Font Size",'avia_framework' ),
									"desc" 	=> __("Set the font size for the element title, based on the device screensize.", 'avia_framework' ),
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
									"name" 	=> __("Caption Content Font Size",'avia_framework' ),
									"desc" 	=> __("Set the font size for the element content, based on the device screensize.", 'avia_framework' ),
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
						)
					),




					array(
							"name" 	=> __("Slideshow Image Size", 'avia_framework' ),
							"desc" 	=> __("Choose the size of the image that loads into the slideshow.", 'avia_framework' ),
							"id" 	=> "size",
							"type" 	=> "select",
							"std" 	=> "featured",
							"subtype" =>  AviaHelper::get_registered_image_sizes(array('thumbnail','logo','widget','slider_thumb'))
							),

					array(
							"name" 	=> __("Slideshow Transition", 'avia_framework' ),
							"desc" 	=> __("Choose the transition for your Slideshow.", 'avia_framework' ),
							"id" 	=> "animation",
							"type" 	=> "select",
							"std" 	=> "slide",
							"subtype" => array(__('Slide sidewards','avia_framework' ) =>'slide', __('Slide up/down','avia_framework' ) =>'slide_up', __('Fade','avia_framework' ) =>'fade'),
							),

					array(
						"name" 	=> __("Autorotation active?",'avia_framework' ),
						"desc" 	=> __("Check if the slideshow should rotate by default",'avia_framework' ),
						"id" 	=> "autoplay",
						"type" 	=> "select",
						"std" 	=> "false",
						"subtype" => array(__('Yes','avia_framework' ) =>'true',__('No','avia_framework' ) =>'false')),
						
					array(	
						"name" 	=> __("Stop Autorotation with the last slide", 'avia_framework' ),
						"desc" 	=> __("Check if you want to disable autorotation when this last slide is displayed", 'avia_framework' ) ,
						"id" 	=> "autoplay_stopper",
						"required"=> array('autoplay','equals','true'),
						"std" 	=> "",
						"type" 	=> "checkbox"),

					array(
						"name" 	=> __("Slideshow autorotation duration",'avia_framework' ),
						"desc" 	=> __("Images will be shown the selected amount of seconds.",'avia_framework' ),
						"id" 	=> "interval",
						"type" 	=> "select",
						"std" 	=> "5",
						"subtype" =>
						array('2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','15'=>'15','20'=>'20','30'=>'30','40'=>'40','60'=>'60','100'=>'100')),
						
						array(	
						"name" 	=> __("Slideshow control styling?",'avia_framework' ),
						"desc" 	=> __("Here you can select if and how to display the slideshow controls",'avia_framework' ),
						"id" 	=> "control_layout",
						"type" 	=> "select",
						"std" 	=> "",
						"subtype" => array(__('Default','avia_framework' ) =>'av-control-default',__('Minimal White','avia_framework' ) =>'av-control-minimal', __('Minimal Black','avia_framework' ) =>'av-control-minimal av-control-minimal-dark',__('Hidden','avia_framework' ) =>'av-control-hidden')),	

						
					array(	
						"name" 	=> __("Use first slides caption as permanent caption", 'avia_framework' ),
						"desc" 	=> __("If checked the caption will be placed on top of the slider. Please be aware that all slideshow link settings and other captions will be ignored then", 'avia_framework' ) ,
						"id" 	=> "perma_caption",
						"std" 	=> "",
						"type" 	=> "checkbox"),
						
						
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
				return $params;
			}

			/**
			 * Editor Sub Element - this function defines the visual appearance of an element that is displayed within a modal window and on click opens its own modal window
			 * Works in the same way as Editor Element
			 * @param array $params this array holds the default values for $content and $args.
			 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
			 */
			function editor_sub_element($params)
			{	
				$img_template 		= $this->update_template("img_fakeArg", "{{img_fakeArg}}");
				$template 			= $this->update_template("title", "{{title}}");
				$content 			= $this->update_template("content", "{{content}}");
				$video 				= $this->update_template("video", "{{video}}");
				$thumbnail = isset($params['args']['id']) ? wp_get_attachment_image($params['args']['id']) : "";
				
		
				$params['innerHtml']  = "";
				$params['innerHtml'] .= "<div class='avia_title_container'>";
				$params['innerHtml'] .=	"	<div ".$this->class_by_arguments('slide_type' ,$params['args']).">";
				$params['innerHtml'] .= "		<span class='avia_slideshow_image' {$img_template} >{$thumbnail}</span>";
				$params['innerHtml'] .= "		<div class='avia_slideshow_content'>";
				$params['innerHtml'] .= "			<h4 class='avia_title_container_inner' {$template} >".$params['args']['title']."</h4>";
				$params['innerHtml'] .= "			<p class='avia_content_container' {$content}>".stripslashes($params['content'])."</p>";
				$params['innerHtml'] .= "			<small class='avia_video_url' {$video}>".stripslashes($params['args']['video'])."</small>";
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
				
				$atts = shortcode_atts(array(
				'size'			=> 'featured',
				'animation'		=> 'slide',
				'ids'    	 	=> '',
				'autoplay'		=> 'false',
				'interval'		=> 5,
				'control_layout'=> '',
				'perma_caption'	=> '',
				'handle'		=> $shortcodename,
				'content'		=> ShortcodeHelper::shortcode2array($content, 1),
				'class'			=> $meta['el_class']." ".$av_display_classes,
				'custom_markup' => $meta['custom_markup'],
				'autoplay_stopper'=>'',

				), $atts, $this->config['shortcode']);

				$slider = new avia_slideshow($atts);
				return $slider->html();
			}

	}
}
















