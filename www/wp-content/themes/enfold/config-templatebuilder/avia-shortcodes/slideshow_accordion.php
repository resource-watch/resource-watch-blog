<?php
/**
 * Accordion Slider
 * 
 * Display an accordion slider with images or post entries
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_slider_accordion' ) ) 
{
	class avia_sc_slider_accordion extends aviaShortcodeTemplate
	{
			static $slide_count = 0;
	
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'no';
				
				$this->config['name']			= __('Accordion Slider', 'avia_framework' );
				$this->config['tab']			= __('Media Elements', 'avia_framework' );
				$this->config['icon']			= AviaBuilder::$path['imagesURL']."sc-accordion-slider.png";
				$this->config['order']			= 20;
				$this->config['target']			= 'avia-target-insert';
				$this->config['shortcode'] 		= 'av_slideshow_accordion';
				$this->config['shortcode_nested'] = array('av_slide_accordion');
				$this->config['tooltip'] 	    = __('Display an accordion slider with images or post entries', 'avia_framework' );
				$this->config['drag-level'] 	= 3;
				$this->config['preview'] 		= false;
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
							"name"  => __("Slide Content" , 'avia_framework'),
							'nodescription' => true
						),
					
					
					array(	
							"name" 	=> __("Which type of slider is this?",'avia_framework' ),
							"desc" 	=> __("Slides can either be generated based on images you choose or on recent post entries", 'avia_framework' ),
							"id" 	=> "slide_type",
							"type" 	=> "select",
							"std" 	=> "image-based",
							"subtype" => array(   __('Image based Slider','avia_framework' )	=>'image-based',
							                      __('Entry based Slider','avia_framework' )	=>'entry-based',
							                      )
					    ),
					
					array(
						"name" 	=> __("Which Entries?", 'avia_framework' ),
						"desc" 	=> __("Select which entries should be displayed by selecting a taxonomy", 'avia_framework' ),
						"id" 	=> "link",
						"fetchTMPL"	=> true,
						"type" 	=> "linkpicker",
						"required"=> array('slide_type','is_empty_or','entry-based'),
						"subtype"  => array( __('Display Entries from:',  'avia_framework' )=>'taxonomy'),
						"multiple"	=> 6,
						"std" 	=> "category"
					),
					
					array(
						"name" 	=> __("WooCommerce Product visibility?", 'avia_framework' ),
						"desc" 	=> __("Select the visibility of WooCommerce products. Default setting can be set at Woocommerce -&gt Settings -&gt Products -&gt Inventory -&gt Out of stock visibility", 'avia_framework' ),
						"id" 	=> "wc_prod_visible",
						"type" 	=> "select",
						"std" 	=> "",
						"required" => array( 'link', 'parent_in_array', implode( ' ', get_object_taxonomies( 'product', 'names' ) ) ),
						"subtype" => array(
							__('Use default WooCommerce Setting (Settings -&gt; Products -&gt; Out of stock visibility)',  'avia_framework' ) => '',
							__('Hide products out of stock',  'avia_framework' ) => 'hide',
							__('Show products out of stock',  'avia_framework' )  => 'show')
					),
					
					array(
						"name" 	=> __( "Sorting Options", 'avia_framework' ),
						"desc" 	=> __( "Here you can choose how to sort the products. Default setting can be set at Woocommerce -&gt Settings -&gt Products -&gt Display -&gt Default product sorting", 'avia_framework' ),
						"id" 	=> "prod_order_by",
						"type" 	=> "select",
						"std" 	=> "",
						"required" => array( 'link', 'parent_in_array', implode( ' ', get_object_taxonomies( 'product', 'names' ) ) ),
						"subtype" => array( 
								__('Use defaut (defined at Woocommerce -&gt; Settings -&gt Default product sorting) ', 'avia_framework' ) =>	'',
								__('Sort alphabetically', 'avia_framework' )			=>	'title',
								__('Sort by most recent', 'avia_framework' )			=>	'date',
								__('Sort by price', 'avia_framework' )					=>	'price',
								__('Sort by popularity', 'avia_framework' )				=>	'popularity',
								__('Sort randomly', 'avia_framework' )					=>	'rand'
							)
					),
				
				array(
						"name" 	=> __( "Sorting Order", 'avia_framework' ),
						"desc" 	=> __( "Here you can choose the order of the result products. Default setting can be set at Woocommerce -&gt Settings -&gt Products -&gt Display -&gt Default product sorting", 'avia_framework' ),
						"id" 	=> "prod_order",
						"type" 	=> "select",
						"std" 	=> "",
						"required" => array( 'link', 'parent_in_array', implode( ' ', get_object_taxonomies( 'product', 'names' ) ) ),
						"subtype" => array( 
								__('Use defaut (defined at Woocommerce -&gt Settings -&gt Default product sorting)', 'avia_framework' ) =>	'',
								__('Ascending', 'avia_framework' )			=>	'ASC',
								__('Descending', 'avia_framework' )			=>	'DESC'
							)
					),
					
					array(
							"name" 	=> __("Number of entries", 'avia_framework' ),
							"desc" 	=> __("How many entries should be displayed?", 'avia_framework' ),
							"id" 	=> "items",
							"type" 	=> "select",
							"std" 	=> "5",
							"required"=> array('slide_type','is_empty_or','entry-based'),
							"subtype" => AviaHtmlHelper::number_array(1,12,1)),
					
					array(
                        "name" 	=> __("Offset Number", 'avia_framework' ),
                        "desc" 	=> __("The offset determines where the query begins pulling entries. Useful if you want to remove a certain number of entries because you already query them with another element.", 'avia_framework' ),
                        "id" 	=> "offset",
                        "type" 	=> "select",
                        "std" 	=> "0",
						"required"=> array('slide_type','is_empty_or','entry-based'),
                        "subtype" => AviaHtmlHelper::number_array(1,100,1, array(__('Deactivate offset','avia_framework')=>'0', __('Do not allow duplicate posts on the entire page (set offset automatically)', 'avia_framework' ) =>'no_duplicates'))),
					
					
					array(	
							"type" 			=> "modal_group", 
							"id" 			=> "content",
							'container_class' =>"avia-element-fullwidth avia-multi-img",
							"modal_title" 	=> __("Edit Form Element", 'avia_framework' ),
							"add_label"		=>  __("Add single image", 'avia_framework' ),
							"std"			=> array(),
							"required"=> array('slide_type','equals','image-based'),
							'creator'		=>array(
								
										"name" => __("Add Images", 'avia_framework' ),
										"desc" => __("Here you can add new Images to the slideshow.", 'avia_framework' ),
										"id" 	=> "id",
										"type" 	=> "multi_image",
										"title" => __("Add multiple Images",'avia_framework' ),
										"button" => __("Insert Images",'avia_framework' ),
										"std" 	=> ""
										),
															
							'subelements' 	=> array(
									
									array(	
									"name" 	=> __("Choose another Image",'avia_framework' ),
									"desc" 	=> __("Either upload a new, or choose an existing image from your media library",'avia_framework' ),
									"id" 	=> "id",
									"fetch" => "id",
									"type" 	=> "image",
									"title" => __("Change Image",'avia_framework' ),
									"button" => __("Change Image",'avia_framework' ),
									"std" 	=> ""),

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
									"name" 	=> __("Image Link?", 'avia_framework' ),
									"desc" 	=> __("Where should the Image link to?", 'avia_framework' ),
									"id" 	=> "link",
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
								)   
										
					),
							
					array(	
							"name" 	=> __("Accordion Image Size", 'avia_framework' ),
							"desc" 	=> __("Choose image and Video size for your slideshow.", 'avia_framework' ),
							"id" 	=> "size",
							"type" 	=> "select",
							"std" 	=> "featured",
							"subtype" =>  AviaHelper::get_registered_image_sizes(500, false, true)		
							),
					
					
					
							
					array(	
						"name" 	=> __("Autorotation active?",'avia_framework' ),
						"desc" 	=> __("Check if the slideshow should rotate by default",'avia_framework' ),
						"id" 	=> "autoplay",
						"type" 	=> "select",
						"std" 	=> "false",
						"subtype" => array(__('Yes','avia_framework' ) =>'true',__('No','avia_framework' ) =>'false')),	
			
					array(	
						"name" 	=> __("Slideshow autorotation duration",'avia_framework' ),
						"desc" 	=> __("Images will be shown the selected amount of seconds.",'avia_framework' ),
						"id" 	=> "interval",
						"type" 	=> "select",
						"std" 	=> "5",
						"required"=> array('autoplay','contains','true'),
						"subtype" => 
						array('3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','15'=>'15','20'=>'20','30'=>'30','40'=>'40','60'=>'60','100'=>'100')),
					
					array(
							"type" 	=> "close_div",
							'nodescription' => true
						),
					
					array(
						"type" 	=> "tab",
						"name"	=> __("Slide Caption",'avia_framework' ),
						'nodescription' => true
					),
					
					
					array(	
						"name" 	=> __("Slide Title",'avia_framework' ),
						"desc" 	=> __("Display the entry title by default?",'avia_framework' ),
						"id" 	=> "title",
						"type" 	=> "select",
						"std" 	=> "true",
						"subtype" => array(	__('Yes - display everywhere','avia_framework' ) =>'active',
											__('Yes - display, but remove title on mobile devices','avia_framework' ) =>'no-mobile',
											__('Display only on active slides' ) =>'on-hover',
											__('No, never display title','avia_framework' ) =>'inactive')),	
					
					array(	
							"name" 	=> __("Display Excerpt?", 'avia_framework' ),
							"desc" 	=> __("Check if excerpt/caption of the slide should also be displayed", 'avia_framework' ) ."</small>" ,
							"id" 	=> "excerpt",
							"required"=> array('title','not','inactive'),
							"std" 	=> "",
							"type" 	=> "checkbox"),
					
					array(	
						"name" 	=> __("Alignment",'avia_framework' ),
						"desc" 	=> __("Change the alignment of title and excerpt here",'avia_framework' ),
						"id" 	=> "accordion_align",
						"type" 	=> "select",
						"std" 	=> "true",
						"subtype" => array(	__('Default' ) =>'',
											__('Centered','avia_framework' ) =>'av-accordion-text-center',
					)),
					
					array(	
							"name" 	=> __("Title Font Size", 'avia_framework' ),
							"desc" 	=> __("Select a custom font size. Leave empty to use the default", 'avia_framework' ),
							"id" 	=> "custom_title_size",
							"type" 	=> "select",
							"required"=> array('title','not','inactive'),
							"std" 	=> "",
							"subtype" => AviaHtmlHelper::number_array(10,40,1, array( __("Default Size", 'avia_framework' )=>''), 'px'),
						),
					
					array(	
							"name" 	=> __("Excerpt Font Size", 'avia_framework' ),
							"desc" 	=> __("Select a custom font size. Leave empty to use the default", 'avia_framework' ),
							"id" 	=> "custom_excerpt_size",
							"type" 	=> "select",
							"required"=> array('excerpt','not',''),
							"std" 	=> "",
							"subtype" => AviaHtmlHelper::number_array(10,40,1, array( __("Default Size", 'avia_framework' )=>''), 'px'),
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
				$thumbnail = isset($params['args']['id']) ? wp_get_attachment_image($params['args']['id']) : "";
				
		
				$params['innerHtml']  = "";
				$params['innerHtml'] .= "<div class='avia_title_container'>";
				$params['innerHtml'] .= "		<span class='avia_slideshow_image' {$img_template} >{$thumbnail}</span>";
				$params['innerHtml'] .= "		<div class='avia_slideshow_content'>";
				$params['innerHtml'] .= "			<h4 class='avia_title_container_inner' {$template} >".$params['args']['title']."</h4>";
				$params['innerHtml'] .= "			<p class='avia_content_container' {$content}>".stripslashes($params['content'])."</p>";
				$params['innerHtml'] .= "		</div>";
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
				'slide_type'	=> 'image-based',
				'link'			=> '',
				'wc_prod_visible'	=>	'',
				'prod_order_by'		=>	'',
				'prod_order'		=>	'',
				'size'			=> '',
				'items'    	 	=> '',
				'autoplay'		=> 'false',
				'title'			=> 'active',
				'excerpt'		=> '',
				'interval'		=> 5,
				'offset'		=> 0,
				'custom_title_size' => '',
				'custom_excerpt_size' => '',
				'accordion_align'	=> '',
				
				'av-desktop-hide'	=>'',
				'av-medium-hide'	=>'',
				'av-small-hide'		=>'',
				'av-mini-hide'		=>'',
				
				'av-medium-font-size-title'	=>'',
				'av-small-font-size-title'	=>'',
				'av-mini-font-size-title'	=>'',
				
				'av-medium-font-size'	=>'',
				'av-small-font-size'	=>'',
				'av-mini-font-size'		=>'',
								
				'handle'		=> $shortcodename,
				'content'		=> ShortcodeHelper::shortcode2array($content, 1)
				
				), $atts, $this->config['shortcode']);
				
				extract($atts);
				$output  	= "";
			    $class = "";
			    
			    
				$skipSecond = false;
				avia_sc_slider_accordion::$slide_count++;
				
				$params['class'] = "avia-accordion-slider-wrap main_color avia-shadow {$av_display_classes} ".$meta['el_class'].$class;
				$params['open_structure'] = false;

				$params['custom_markup'] = $atts['custom_markup'] = $meta['custom_markup'];
				
				//we dont need a closing structure if the element is the first one or if a previous fullwidth element was displayed before
				if($meta['index'] == 0) $params['close'] = false;
				if(!empty($meta['siblings']['prev']['tag']) && in_array($meta['siblings']['prev']['tag'], AviaBuilder::$full_el_no_section )) $params['close'] = false;
				
				if($meta['index'] != 0) $params['class'] .= " slider-not-first";
				
				$params['id'] = "accordion_slider_".avia_sc_slider_full::$slide_count;
				
				
				$slider  = new aviaccordion($atts);
				$slide_html = $slider->html();
				
				
				//if the element is nested within a section or a column dont create the section shortcode around it
				if(!ShortcodeHelper::is_top_level()) return $slide_html;
				
				
				$output .=  avia_new_section($params);
				$output .= 	$slide_html;
				$output .= "</div>"; //close section
				
				
				//if the next tag is a section dont create a new section from this shortcode
				if(!empty($meta['siblings']['next']['tag']) && in_array($meta['siblings']['next']['tag'],  AviaBuilder::$full_el ))
				{
				    $skipSecond = true;
				}

				//if there is no next element dont create a new section.
				if(empty($meta['siblings']['next']['tag']))
				{
				    $skipSecond = true;
				}
				
				if(empty($skipSecond)) {
				
				$output .= avia_new_section(array('close'=>false, 'id' => "after_full_slider_".avia_sc_slider_full::$slide_count));
				
				}
				
				return $output;

			}
			
	}
}





if ( !class_exists( 'aviaccordion' ) )
{
	class aviaccordion
	{
		static  $slider = 0; 				//slider count for the current page
		protected $config;	 				//base config set on initialization
		protected $slides = array();	 	//entries or image slides
		protected $slide_count = 0;			//number of slides
		protected $id_array = array();
		function __construct($config)
		{
		
			$this->screen_options = AviaHelper::av_mobile_sizes($config); //return $av_font_classes, $av_title_font_classes and $av_display_classes 
			
			$this->config = array_merge(array(
				'slide_type'	=> 'image-based',
				'link'			=> '',
				'wc_prod_visible'	=>	'',
				'prod_order_by'		=>	'',
				'prod_order'		=>	'',
				'size'			=> '',
				'items'    	 	=> '',
				'autoplay'		=> 'false',
				'interval'		=> 5,
				'offset'		=> 0,
				'title'			=> 'active',
				'excerpt'		=> '',
				'content'		=> array(),
				'custom_title_size' => '',
				'custom_excerpt_size' => '',
				'custom_markup' => '',
				'accordion_align'=> ''
				), $config);

			$this->config = apply_filters('avf_aviaccordion_config', $this->config);
			
			$this->get_height();
			$this->get_slides();
		}
		
		function get_height()
		{
			//check how large the slider is and change the classname accordingly
			global $_wp_additional_image_sizes;

			if(isset($_wp_additional_image_sizes[$this->config['size']]['width']))
			{
				$width  = $_wp_additional_image_sizes[$this->config['size']]['width'];
				$height = $_wp_additional_image_sizes[$this->config['size']]['height'];
			}
			else if($width = get_option( $this->config['size'].'_size_w' ))
			{
				$height = get_option( $this->config['size'].'_size_h' );
			}
			
			$this->config['max-height']		= $height;
			$this->config['default-height'] = (100/$width) * $height;
		}
		
		function get_slides()
		{	
			if($this->config['slide_type'] == "image-based")
			{
				$this->get_image_based_slides();
			}
			else
			{
				$this->extract_terms();
				$this->query_entries();
				foreach($this->slides as $key => $slide)
				{
					$this->slides[$key]->av_attachment 	= wp_get_attachment_image( get_post_thumbnail_id($slide->ID) , $this->config['size'], false, array('class' => 'aviaccordion-image') );
					$this->slides[$key]->av_permalink	= get_post_meta( $slide->ID ,'_portfolio_custom_link', true ) != "" ? get_post_meta( $slide->ID ,'_portfolio_custom_link_url', true ) : get_permalink( $slide->ID );
					$this->slides[$key]->av_target		= "";
					$this->slides[$key]->post_excerpt	= !empty($slide->post_excerpt) ? $slide->post_excerpt : avia_backend_truncate($slide->post_content, apply_filters( 'avf_aviaccordion_excerpt_length' , 120) , apply_filters( 'avf_aviaccordion_excerpt_delimiter' , " "), "…", true, '');

				}
			}
		}
		
		function get_image_based_slides()
		{
			foreach($this->config['content'] as $key => $slide)
			{
				if(!isset($slide['attr']['link'])) $slide['attr']['link'] = "lightbox";
				
				$this->slides[$key] = new stdClass();
				$this->slides[$key]->post_title		= isset($slide['attr']['title']) ? $slide['attr']['title'] : "";
				$this->slides[$key]->post_excerpt	= $slide['content'];
				$this->slides[$key]->av_attachment	= wp_get_attachment_image( $slide['attr']['id'] , $this->config['size'] , false, array('class' => 'aviaccordion-image'));
				$this->slides[$key]->av_permalink	= isset($slide['attr']['link']) ? AviaHelper::get_url($slide['attr']['link'], $slide['attr']['id']) : "";
				$this->slides[$key]->av_target		= empty($slide['attr']['link_target']) ? "" : "target='".$slide['attr']['link_target']."'" ;
			}
		}
		
		
		function extract_terms()
		{
			if(isset($this->config['link']))
			{
				$this->config['link'] = explode(',', $this->config['link'], 2 );
				$this->config['taxonomy'] = $this->config['link'][0];

				if(isset($this->config['link'][1]))
				{
					$this->config['categories'] = $this->config['link'][1];
				}
				else
				{
					$this->config['categories'] = array();
				}
			}
		}
		
		function query_entries($params = array(), $return = false)
		{
			global $avia_config;

			if(empty($params)) $params = $this->config;
		
			if(empty($params['custom_query']))
            {
				$query = array();
				
				if(!empty($params['categories']))
				{
					//get the portfolio categories
					$terms 	= explode(',', $params['categories']);
				}

				$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
				if(!$page || $params['paginate'] == 'no') $page = 1;

				//if we find no terms for the taxonomy fetch all taxonomy terms
				if(empty($terms[0]) || is_null($terms[0]) || $terms[0] === "null")
				{
					$terms = array();
					$allTax = get_terms( $params['taxonomy']);
					foreach($allTax as $tax)
					{
						$terms[] = $tax->term_id;
					}
				}
				
				if($params['offset'] == 'no_duplicates')
                {
                    $params['offset'] = 0;
                    if(empty($params['ignore_dublicate_rule'])) $no_duplicates = true;
                }
							
				if(empty($params['post_type'])) $params['post_type'] = get_post_types();
				if(is_string($params['post_type'])) $params['post_type'] = explode(',', $params['post_type']);
									
				$orderby = 'date';
				$order = 'DESC';
		
				// Meta query - replaced by Tax query in WC 3.0.0
				$meta_query = array();
				$tax_query = array();

				// check if taxonomy are set to product or product attributes
				$tax = get_taxonomy( $params['taxonomy'] );
				
				if( is_object( $tax ) && isset( $tax->object_type ) && in_array( 'product', (array) $tax->object_type ) )
				{
					$avia_config['woocommerce']['disable_sorting_options'] = true;
					
					avia_wc_set_out_of_stock_query_params( $meta_query, $tax_query, $params['wc_prod_visible'] );
					
						//	sets filter hooks !!
					$ordering_args = avia_wc_get_product_query_order_args( $params['prod_order_by'], $params['prod_order'] );
							
					$orderby = $ordering_args['orderby'];
					$order = $ordering_args['order'];
				}

				if( ! empty( $terms ) )
				{
					$tax_query[] =  array(
										'taxonomy' 	=>	$params['taxonomy'],
										'field' 	=>	'id',
										'terms' 	=>	$terms,
										'operator' 	=>	'IN'
								);
				}				
				
				
				$query = array(	'orderby'		=>	$orderby,
								'order'			=>	$order,
								'paged'			=>	$page,
								'post_type'		=>	$params['post_type'],
//								'post_status'	=>	'publish',
								'offset'		=>	$params['offset'],
								'posts_per_page' =>	$params['items'],
								'post__not_in'	=>	( ! empty( $no_duplicates ) ) ? $avia_config['posts_on_current_page'] : array(),
								'meta_query'	=>	$meta_query,
								'tax_query'		=>	$tax_query
							);				
				
			}
			else
			{
				$query = $params['custom_query'];
			}


			$query   = apply_filters('avf_accordion_entries_query', $query, $params);
			$result = new WP_Query( $query );
			$entries = $result->posts;
			
			if(!empty($entries) && empty($params['ignore_dublicate_rule']))
			{
				foreach($entries as $entry)
	            {
					 $avia_config['posts_on_current_page'][] = $entry->ID;
	            }
			}
			
			if( function_exists( 'WC' ) )
			{
				avia_wc_clear_catalog_ordering_args_filters();
				$avia_config['woocommerce']['disable_sorting_options'] = false;
			}
			
			if($return)
			{
				return $entries;
			}
			else
			{
				$this->slides = $entries;
			}
		}
		
		
		
		
		function html()
		{
			extract($this->screen_options);
		
			$slideCount = count($this->slides);
			$output 	= "";
			
			if($slideCount == 0) return $output;
			$left 	  	   = 100 / $slideCount;
			$overlay_class = "aviaccordion-title-".$this->config['title'];
			$accordion_align = $this->config['accordion_align'];
			
			
			$data = "data-av-maxheight='".$this->config['max-height']."' data-autoplay='".$this->config['autoplay']."' data-interval='".$this->config['interval']."' ";
			$markup = avia_markup_helper(array('context' => 'blog','echo'=>false, 'custom_markup'=>$this->config['custom_markup']));

			$output .= "<div class='aviaccordion {$overlay_class} {$av_display_classes}' style='max-height:".$this->config['max-height']."px' {$data} $markup>";
			$output .= 		"<ul class='aviaccordion-inner'>";
			
			foreach($this->slides as $key => $slide)
			{
			
				$counter  = $key + 1;
				$left_pos = $left * $key;
				$slide_id = isset($slide->ID) ? $slide->ID : "";
				
				$data  = "data-av-left='{$left_pos}'";
				$style = "style='left:{$left_pos}%'";
				
				$markup = avia_markup_helper(array('context' => 'entry','echo'=>false, 'id'=>$slide_id, 'custom_markup'=>$this->config['custom_markup']));

				$output .= "<li class='aviaccordion-slide aviaccordion-slide-{$counter}' {$style} {$data} {$markup}>";
				$output .= "<a class='aviaccordion-slide-link noHover' href='".$slide->av_permalink."' ".$slide->av_target.">";
				$output .= "<div class='aviaccordion-preview  {$accordion_align}' style='width:".(ceil($left) +0.1)."%'>";
				
			
				if($this->config['title'] !== "inactive" && (!empty($slide->post_title) || !empty($slide->post_excerpt)))
				{
					$markup_title = avia_markup_helper(array('context' => 'entry_title','echo'=>false, 'id'=>$slide_id, 'custom_markup'=>$this->config['custom_markup']));
					$markup_content = avia_markup_helper(array('context' => 'entry_content','echo'=>false, 'id'=>$slide_id, 'custom_markup'=>$this->config['custom_markup']));
					
					
					$title_style = !empty( $this->config['custom_title_size'] ) ? "style='font-size:".$this->config['custom_title_size']."px'" : "";
					$excerpt_style = !empty( $this->config['custom_excerpt_size'] ) ? "style='font-size:".$this->config['custom_excerpt_size']."px'" : "";
					

					$output .= "<div class='aviaccordion-preview-title-pos'><div class='aviaccordion-preview-title-wrap'><div class='aviaccordion-preview-title'>";
					$output .= !empty($slide->post_title) ? "<h3 class='aviaccordion-title {$av_title_font_classes}' {$markup_title} {$title_style}>".$slide->post_title."</h3>" : "";
					$output .= !empty($slide->post_excerpt) && !empty($this->config['excerpt']) ? "<div class='aviaccordion-excerpt {$av_font_classes}' {$excerpt_style} {$markup_content}>".wpautop($slide->post_excerpt)."</div>" : "";
					$output .= "</div></div></div>";
				}
				$output .= "</div>";
				$output .= $slide->av_attachment;
				$output .= "</a>";
				$output .= "</li>";
			}
			
			
			$output .= 		"</ul>";
			$output .= 		"<div class='aviaccordion-spacer' style='padding-bottom:".$this->config['default-height']."%'></div>";
			$output .= "</div>";
			
			return $output;
		}
	}
	
	
	
	
	
	
	
	
	
}



















