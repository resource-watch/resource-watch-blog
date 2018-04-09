<?php
/**
 * Featured Image Slider
 * 
 * Display a Slideshow of featured images from various posts
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_featureimage_slider' ))
{
	class avia_sc_featureimage_slider extends aviaShortcodeTemplate
	{
		
		static $slide_count = 0;
		
		/**
		 * Create the config array for the shortcode button
		 */
		function shortcode_insert_button()
		{
			/**
			 * inconsistent behaviour up to 4.2: a new element was created with a close tag, after editing it was self closing !!!
			 * @since 4.2.1: We make new element self closing now because no id='content' exists.
			 */
			$this->config['self_closing']	=	'yes';
			
			$this->config['name']		= __('Featured Image Slider', 'avia_framework' );
			$this->config['tab']		= __('Media Elements', 'avia_framework' );
			$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-postslider.png";
			$this->config['order']		= 30;
			$this->config['target']		= 'avia-target-insert';
			$this->config['shortcode'] 	= 'av_feature_image_slider';
			$this->config['tooltip'] 	= __('Display a Slideshow of featured images from various posts', 'avia_framework' );
			$this->config['drag-level'] = 3;
			$this->config['preview'] 		= 0;
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
					"name"  => __("Slider Content" , 'avia_framework'),
					'nodescription' => true
				),
				
				array(
						"name" 	=> __("Which Entries?", 'avia_framework' ),
						"desc" 	=> __("Select which entries should be displayed by selecting a taxonomy", 'avia_framework' ),
						"id" 	=> "link",
						"fetchTMPL"	=> true,
						"type" 	=> "linkpicker",
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
						"name" 	=> __("Entry Number", 'avia_framework' ),
						"desc" 	=> __("How many items should be displayed?", 'avia_framework' ),
						"id" 	=> "items",
						"type" 	=> "select",
						"std" 	=> "3",
						"subtype" => AviaHtmlHelper::number_array(1,100,1, array('All'=>'-1'))),

                array(
                    "name" 	=> __("Offset Number", 'avia_framework' ),
                    "desc" 	=> __("The offset determines where the query begins pulling posts. Useful if you want to remove a certain number of posts because you already query them with another element.", 'avia_framework' ),
                    "id" 	=> "offset",
                    "type" 	=> "select",
                    "std" 	=> "enforce_duplicates",
                    "subtype" => AviaHtmlHelper::number_array(1,100,1, 
                    
	                    array(
		                    
	                    __('Deactivate offset','avia_framework')=>'0', 
	                    __('Do not allow duplicate posts on the entire page (set offset automatically)', 'avia_framework' ) =>'no_duplicates',
	                    __('Enforce duplicates (if a blog element on the page should show the same entries as this slider use this setting)', 'avia_framework' ) =>'enforce_duplicates'
	                    
	                    	)
                    )
                    ),

				array(
						"name" 	=> __("Title and Read More Button",'avia_framework' ),
						"desc" 	=> __("Choose if you want to only display the post title or title and a call to action button",'avia_framework' ),
						"id" 	=> "contents",
						"type" 	=> "select",
						"std" 	=> "title",
						"subtype" => array(
							__('Only Title',  'avia_framework' ) =>'title',
							__('Title + Read More Button',  'avia_framework' ) =>'title_read_more',
							__('Title + Excerpt + Read More Button',  'avia_framework' ) =>'title_excerpt_read_more',
							)
					),

				array(
		                    "name"  => __("Slider Width/Height Ratio", 'avia_framework' ),
		                    "desc"  => __("The slider will always stretch the full available width. Here you can enter the corresponding height (eg: 4:3, 16:9) or a fixed height in px (eg: 300px)", 'avia_framework' ),
		                    "id"    => "slider_size",
		                    "type" 	=> "input",
							"std" 	=> "16:9",
					),				


				array(
							"name" 	=> __("Preview Image Size", 'avia_framework' ),
							"desc" 	=> __("Set the image size of the preview images", 'avia_framework' ),
							"id" 	=> "preview_mode",
							"type" 	=> "select",
							"std" 	=> "auto",
							"subtype" => array(
							
							__('Set the preview image size automatically based on slider height','avia_framework' ) =>'auto',
							__('Choose the preview image size manually (select thumbnail size)','avia_framework' ) =>'custom')),

				array(
							"name" 	=> __("Select custom preview image size", 'avia_framework' ),
							"desc" 	=> __("Choose image size for Preview Image", 'avia_framework' ),
							"id" 	=> "image_size",
							"type" 	=> "select",
							"required" 	=> array('preview_mode','equals','custom'),
							"std" 	=> "portfolio",
							"subtype" =>  AviaHelper::get_registered_image_sizes(array('logo'))
							),
				

				array(	
						"name" 	=> __("Slideshow control styling?",'avia_framework' ),
						"desc" 	=> __("Here you can select if and how to display the slideshow controls",'avia_framework' ),
						"id" 	=> "control_layout",
						"type" 	=> "select",
						"std" 	=> "",
						"subtype" => array(__('Default','avia_framework' ) =>'av-control-default',__('Minimal White','avia_framework' ) =>'av-control-minimal', __('Minimal Black','avia_framework' ) =>'av-control-minimal av-control-minimal-dark',__('Hidden','avia_framework' ) =>'av-control-hidden')),	
					
					
					
				array(
				"type" 	=> "close_div",
				'nodescription' => true
					),
				
				array(
						"type" 	=> "tab",
						"name"	=> __("Slider Transitions",'avia_framework' ),
						'nodescription' => true
					),
					
				array(
							"name" 	=> __("Transition", 'avia_framework' ),
							"desc" 	=> __("Choose the transition for your Slider.", 'avia_framework' ),
							"id" 	=> "animation",
							"type" 	=> "select",
							"std" 	=> "fade",
							"subtype" => array(__('Slide','avia_framework' ) =>'slide',__('Fade','avia_framework' ) =>'fade'),
							),

				
				
				array(
						"name" 	=> __("Autorotation active?",'avia_framework' ),
						"desc" 	=> __("Check if the slideshow should rotate by default",'avia_framework' ),
						"id" 	=> "autoplay",
						"type" 	=> "select",
						"std" 	=> "no",
						"subtype" => array(__('Yes','avia_framework' ) =>'yes',__('No','avia_framework' ) =>'no')),

				array(
					"name" 	=> __("Slideshow autorotation duration",'avia_framework' ),
					"desc" 	=> __("Slideshow will rotate every X seconds",'avia_framework' ),
					"id" 	=> "interval",
					"type" 	=> "select",
					"std" 	=> "5",
					"required" 	=> array('autoplay','equals','yes'),
					"subtype" =>
					array('3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','15'=>'15','20'=>'20','30'=>'30','40'=>'40','60'=>'60','100'=>'100')),
					
				array(
				"type" 	=> "close_div",
				'nodescription' => true
				),	
				
				
				array(
						"type" 	=> "tab",
						"name"	=> __("Slide Overlay",'avia_framework' ),
						'nodescription' => true
					),	
					
				
				
				array(	
										"name" 	=> __("Enable Overlay?", 'avia_framework' ),
										"desc" 	=> __("Check if you want to display a transparent color and/or pattern overlay above your slideshow image/video", 'avia_framework' ),
										"id" 	=> "overlay_enable",
										"std" 	=> "",
										"type" 	=> "checkbox"),
								
								 array(
									"name" 	=> __("Overlay Opacity",'avia_framework' ),
									"desc" 	=> __("Set the opacity of your overlay: 0.1 is barely visible, 1.0 is opaque ", 'avia_framework' ),
									"id" 	=> "overlay_opacity",
									"type" 	=> "select",
									"std" 	=> "0.5",
			                        "required" => array('overlay_enable','not',''),
									"subtype" => array(   __('0.1','avia_framework' )=>'0.1',
									                      __('0.2','avia_framework' )=>'0.2',
									                      __('0.3','avia_framework' )=>'0.3',
									                      __('0.4','avia_framework' )=>'0.4',
									                      __('0.5','avia_framework' )=>'0.5',
									                      __('0.6','avia_framework' )=>'0.6',
									                      __('0.7','avia_framework' )=>'0.7',
									                      __('0.8','avia_framework' )=>'0.8',
									                      __('0.9','avia_framework' )=>'0.9',
									                      __('1.0','avia_framework' )=>'1',
									                      )
							  		),
							  		
							  	array(
										"name" 	=> __("Overlay Color", 'avia_framework' ),
										"desc" 	=> __("Select a custom  color for your overlay here. Leave empty if you want no color overlay", 'avia_framework' ),
										"id" 	=> "overlay_color",
										"type" 	=> "colorpicker",
			                        	"required" => array('overlay_enable','not',''),
										"std" 	=> "",
									),
							  	
							  	array(
			                        "required" => array('overlay_enable','not',''),
									"id" 	=> "overlay_pattern",
									"name" 	=> __("Background Image", 'avia_framework'),
									"desc" 	=> __("Select an existing or upload a new background image", 'avia_framework'),
									"type" 	=> "select",
									"subtype" => array(__('No Background Image', 'avia_framework')=>'',__('Upload custom image', 'avia_framework')=>'custom'),
									"std" 	=> "",
									"folder" => "images/background-images/",
									"folderlabel" => "",
									"group" => "Select predefined pattern",
									"exclude" => array('fullsize-', 'gradient')
								),
							  	
							  	
							  	array(
										"name" 	=> __("Custom Pattern",'avia_framework' ),
										"desc" 	=> __("Upload your own seamless pattern",'avia_framework' ),
										"id" 	=> "overlay_custom_pattern",
										"type" 	=> "image",
										"fetch" => "url",
										"secondary_img"=>true,
			                        	"required" => array('overlay_pattern','equals','custom'),
										"title" => __("Insert Pattern",'avia_framework' ),
										"button" => __("Insert",'avia_framework' ),
										"std" 	=> ""),
				
				
					
					
					
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


				if(current_theme_supports('add_avia_builder_post_type_option'))
                {
                    $element = array(
                        "name" 	=> __("Select Post Type", 'avia_framework' ),
                        "desc" 	=> __("Select which post types should be used. Note that your taxonomy will be ignored if you do not select an assign post type.
                                      If yo don't select post type all registered post types will be used", 'avia_framework' ),
                        "id" 	=> "post_type",
                        "type" 	=> "select",
                        "multiple"	=> 6,
                        "std" 	=> "",
                        "subtype" => AviaHtmlHelper::get_registered_post_type_array()
                    );

                    array_unshift($this->elements, $element);
                }
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
		 * Frontend Shortcode Handler
		 *
		 * @param array $atts array of attributes
		 * @param string $content text within enclosing form of shortcode element
		 * @param string $shortcodename the shortcode found, when == callback name
		 * @return string $output returns the modified html string
		 */
		function shortcode_handler($atts, $content = "", $shortcodename = "", $meta = "")
		{
			if(isset($atts['link']))
			{
				$atts['link'] = explode(',', $atts['link'], 2 );
				$atts['taxonomy'] = $atts['link'][0];

				if(isset($atts['link'][1]))
				{
					$atts['categories'] = $atts['link'][1];
				}
			}

			// $atts['class'] = $meta['el_class'];
			
			
			extract(AviaHelper::av_mobile_sizes($atts)); //return $av_font_classes, $av_title_font_classes and $av_display_classes 
			extract($atts);
			$output  	= "";
		    $class = "";
		    
		    
			$skipSecond = false;
			avia_sc_featureimage_slider::$slide_count++;
			
			$params['class'] = "avia-featureimage-slider-wrap main_color  {$av_display_classes} ".$meta['el_class'].$class;
			$params['open_structure'] = false;

			$params['custom_markup'] = $atts['custom_markup'] = $meta['custom_markup'];
			
			//we dont need a closing structure if the element is the first one or if a previous fullwidth element was displayed before
			if($meta['index'] == 0) $params['close'] = false;
			if(!empty($meta['siblings']['prev']['tag']) && in_array($meta['siblings']['prev']['tag'], AviaBuilder::$full_el_no_section )) $params['close'] = false;
			
			if($meta['index'] != 0) $params['class'] .= " slider-not-first";
			
			$params['id'] = "avia_feature_image_slider_".avia_sc_slider_full::$slide_count;
			
			
			$slider  = new avia_feature_image_slider($atts);
			$slider->query_entries();
			$slide_html = $slider->html();
			
			
			//if the element is nested within a section or a column dont create the section shortcode around it
			if(!ShortcodeHelper::is_top_level()) return $slide_html;
			
			// $slide_html  = "<div class='container'>" . $slide_html . "</div>";
			
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


if ( !class_exists( 'avia_feature_image_slider' ) )
{
	class avia_feature_image_slider
	{
		static  $slider = 0;
		protected $slide_count = 0;
		protected $atts;
		protected $entries;

		function __construct($atts = array())
		{
			
			$this->screen_options = AviaHelper::av_mobile_sizes($atts); //return $av_font_classes, $av_title_font_classes and $av_display_classes 
			
			$this->atts = shortcode_atts(array(	'items' 		=> '16',
		                                 		'taxonomy'  	=> 'category',
		                                 		'post_type'		=> get_post_types(),
		                                 		'contents' 		=> 'title',
		                                 		'preview_mode' 	=> 'auto',
												'image_size' 	=> 'portfolio',
		                                 		'autoplay'  	=> 'no',
												'animation' 	=> 'fade',
												'paginate'		=> 'no',
                                                'use_main_query_pagination' => 'no',
												'interval'  	=> 5,
												'class'			=> '',
		                                 		'categories'	=> array(),
												'wc_prod_visible'	=>	'',
												'prod_order_by'		=>	'',
												'prod_order'		=>	'',
		                                 		'custom_query'	=> array(),
		                                 		'lightbox_size' => 'large',
                                                'offset' 		=> 0,
                                                'bg_slider'		=>true,
                                                'keep_pading' 	=> true,
                                                'custom_markup' => '',
                                                'slider_size' 	=> '16:9',
                                                'control_layout'	=> '',
                                                'overlay_enable' 	=> '',
				    							'overlay_opacity' 	=> '',
				    							'overlay_color' 	=> '',
				    							'overlay_pattern' 	=> '',
				    							'overlay_custom_pattern' => '',
                                                
		                                 		), $atts, 'av_feature_image_slider');
		                                 		
		   if($this->atts['autoplay'] == "no")   
		   	$this->atts['autoplay'] = false;                               		
		                                 		
		}

		public function html()
		{
			$html 		= "";
			$counter 	= 0;
			$style   	= "";
			$extraClass = "";
			$style 		= "";
			avia_feature_image_slider::$slider++;
			
			if($this->slide_count == 0) return $html;
			
			if(!empty($this->atts['default-height']))
			{
				$style = "style='padding-bottom: {{av-default-heightvar}}%;'";
				$extraClass .= " av-default-height-applied";
			}
			
			if(strpos( $this->atts['slider_size'] , ":") !== false)
			{
				$ratio = explode(':',trim($this->atts['slider_size']));
				if(empty($ratio[0])) $ratio[0] = 16;
				if(empty($ratio[1])) $ratio[1] = 9;
				$final_ratio = ((int) $ratio[0] / (int) $ratio[1]);
				$def_height = "padding-bottom:" . (100/$final_ratio). "%";
				
			}
			else
			{
				$def_height  = (int) $this->atts['slider_size'];
				$def_height  = "height: {$def_height}px";
			}
			
			extract($this->screen_options);
			
			$style = "style='{$def_height}'";
			if(!empty($this->atts['control_layout'])) $extraClass .= " ".$this->atts['control_layout'];
			
            $markup = avia_markup_helper(array('context' => 'image','echo'=>false, 'custom_markup'=>$this->atts['custom_markup']));

			$data = AviaHelper::create_data_string($this->atts);

			$html .= "<div {$data} class='avia-slideshow avia-featureimage-slideshow avia-animated-caption {$av_display_classes} avia-slideshow-".avia_sc_featureimage_slider::$slide_count." {$extraClass} avia-slideshow-".$this->atts['image_size']."  ".$this->atts['class']." avia-".$this->atts['animation']."-slider ' $markup>";
			
			
			$html .= "<ul class='avia-slideshow-inner avia-slideshow-fixed-height' {$style}>";

			$html .= $this->default_slide();

			$html .= "</ul>";

			if($this->slide_count > 1)
			{
				$html .= $this->slide_navigation_arrows();
				$html .= $this->slide_navigation_dots();
			}
			
			
			if(!empty($this->atts['caption_override'])) $html .= $this->atts['caption_override'];
			

			$html .= "</div>";
			
			if(!empty($this->atts['default-height']))
			{
				$html = str_replace('{{av-default-heightvar}}', $this->atts['default-height'], $html);
			}
			
			return $html;
		}
		
		//function that renders the usual slides. use when we didnt use sub-shorcodes to define the images but ids
		protected function default_slide()
		{
			$html = "";
			$counter = 0;
			
			extract($this->screen_options);

            $markup_url = avia_markup_helper(array('context' => 'image_url','echo'=>false, 'custom_markup'=>$this->atts['custom_markup']));

			foreach ($this->entries->posts as $slide)
			{
					$counter ++;
					$thumb_id = get_post_thumbnail_id( $slide->ID );
					$slide_class = "";
					
					$img 	 = wp_get_attachment_image_src($thumb_id, $this->atts['image_size']);
					$link	 = get_post_meta( $slide->ID ,'_portfolio_custom_link', true ) != "" ? get_post_meta( $slide->ID ,'_portfolio_custom_link_url', true ) : get_permalink( $slide->ID );
					$title	 = get_the_title( $slide->ID );
					
					$caption  = "";
 					$caption .= ' <div class="caption_fullwidth av-slideshow-caption caption_center">';
					$caption .= ' <div class="container caption_container">';
					$caption .= ' <div class="slideshow_caption">';
					$caption .= ' <div class="slideshow_inner_caption">';
					$caption .= ' <div class="slideshow_align_caption">';
					$caption .= ' <h2 class="avia-caption-title '.$av_title_font_classes.'"><a href="'.$link.'">'.$title.'</a></h2>';
			
					if(strpos($this->atts['contents'], 'excerpt')  !== false)
					{
						$excerpt = !empty($slide->post_excerpt) ? $slide->post_excerpt : avia_backend_truncate($slide->post_content, apply_filters( 'avf_feature_image_slider_excerpt_length' , 320) , apply_filters( 'avf_feature_image_slider_excerpt_delimiter' , " "), "…", true, '');
						
						if(!empty($excerpt)){
							$caption .= ' <div class="avia-caption-content '.$av_font_classes.'" itemprop="description">';
							$caption .= wpautop($excerpt);
							$caption .= ' </div>';
						}
					}
		
					
					if(strpos($this->atts['contents'], 'read_more')  !== false)
					{
						$caption .= ' <a href="'.$link.'" class="avia-slideshow-button avia-button avia-color-light " data-duration="800" data-easing="easeInOutQuad">'.__('Read more', 'avia_framework').'</a>';
					
					}
					$caption .= ' </div>';
					$caption .= ' </div>';
					$caption .= ' </div>';
					$caption .= ' </div>';
					$caption .= $this->create_overlay();
					$caption .= ' </div>';
					
					$slide_data = "data-img-url='".$img[0]."'";
					
					if(empty($img)) $slide_class .= " av-no-image-slider";
					
					$html .= "<li {$slide_data} class='slide-{$counter} {$slide_class} slide-id-".$slide->ID."'>";
					$html .= $caption;
					$html .= "</li>";
			}

			return $html;
		}
		
		protected function slide_navigation_dots()
		{
			$html   = "";
			$html  .= "<div class='avia-slideshow-dots avia-slideshow-controls'>";
			$active = "active";

			for($i = 1; $i <= $this->slide_count; $i++)
			{
				$html .= "<a href='#{$i}' class='goto-slide {$active}' >{$i}</a>";
				$active = "";
			}

			$html .= "</div>";

			return $html;
		}
		

		protected function slide_navigation_arrows()
		{
			$html  = "";
			$html .= "<div class='avia-slideshow-arrows avia-slideshow-controls'>";
			$html .= 	"<a href='#prev' class='prev-slide' ".av_icon_string('prev_big').">".__('Previous','avia_framework' )."</a>";
			$html .= 	"<a href='#next' class='next-slide' ".av_icon_string('next_big').">".__('Next','avia_framework' )."</a>";
			$html .= "</div>";

			return $html;
		}
		
		protected function create_overlay()
		{
			extract($this->atts);
			
			/*check/create overlay*/
			$overlay = "";
			if(!empty($overlay_enable))
			{
				$overlay_src = "";
				$overlay = "opacity: {$overlay_opacity}; ";
				if(!empty($overlay_color)) $overlay .= "background-color: {$overlay_color}; ";
				if(!empty($overlay_pattern))
				{
					if($overlay_pattern == "custom")
					{
						$overlay_src = $overlay_custom_pattern;
					}
					else
					{
						$overlay_src = str_replace('{{AVIA_BASE_URL}}', AVIA_BASE_URL, $overlay_pattern);
					}
				}
				
				if(!empty($overlay_src)) $overlay .= "background-image: url({$overlay_src}); background-repeat: repeat;";
				$overlay = "<div class='av-section-color-overlay' style='{$overlay}'></div>";
			}
			
			return $overlay;
		}

		//fetch new entries
		public function query_entries($params = array())
		{
			global $avia_config;

			if(empty($params)) $params = $this->atts;

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
                    $no_duplicates = true;
                }
                
                if($params['offset'] == 'enforce_duplicates')
                {
                    $params['offset'] = 0;
                    $no_duplicates = false;
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


			$query = apply_filters('avia_feature_image_slider_query', $query, $params);

			$this->entries = new WP_Query( $query );
			
			$this->slide_count = count($this->entries->posts);
			
		    // store the queried post ids in
            if( $this->entries->have_posts() && $params['offset'] != 'enforce_duplicates')
            {
                while( $this->entries->have_posts() )
                {
                    $this->entries->the_post();
                    $avia_config['posts_on_current_page'][] = get_the_ID();
                }
            }
			
			if( function_exists( 'WC' ) )
			{
				avia_wc_clear_catalog_ordering_args_filters();
				$avia_config['woocommerce']['disable_sorting_options'] = false;
			}

		}
	}
}
