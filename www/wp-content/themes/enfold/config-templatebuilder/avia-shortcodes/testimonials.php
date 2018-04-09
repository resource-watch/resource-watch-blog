<?php
/**
 * Testimonials
 * 
 * Creates a Testimonial Grid
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_testimonial' ) )
{
	class avia_sc_testimonial extends aviaShortcodeTemplate
	{
			static $columnClass;
			static $rows;
			static $counter;
			static $columns;
			static $style;
			static $grid_style;

			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'no';
				
				$this->config['name']		= __('Testimonials', 'avia_framework' );
				$this->config['tab']		= __('Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-testimonials.png";
				$this->config['order']		= 20;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_testimonials';
				$this->config['shortcode_nested'] = array('av_testimonial_single');
				$this->config['tooltip'] 	= __('Creates a Testimonial Grid', 'avia_framework' );
				$this->config['preview'] 	= "xlarge";
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
							"name" => __("Add/Edit Testimonial", 'avia_framework' ),
							"desc" => __("Here you can add, remove and edit your Testimonials.", 'avia_framework' ),
							"type" 			=> "modal_group",
							"id" 			=> "content",
							"modal_title" 	=> __("Edit Testimonial", 'avia_framework' ),
							"std"			=> array(

													array('name'=>__('Name', 'avia_framework' ), 'Subtitle'=>'', 'check'=>'is_empty'),

													),


							'subelements' 	=> array(

									array(
									"name" 	=> __("Image",'avia_framework' ),
									"desc" 	=> __("Either upload a new, or choose an existing image from your media library",'avia_framework' ),
									"id" 	=> "src",
									"type" 	=> "image",
									"fetch" => "id",
									"title" =>  __("Insert Image",'avia_framework' ),
									"button" => __("Insert",'avia_framework' ),
									"std" 	=> ""),

									array(
									"name" 	=> __("Name", 'avia_framework' ),
									"desc" 	=> __("Enter the Name of the Person to quote", 'avia_framework' ),
									"id" 	=> "name",
									"std" 	=> "",
									"type" 	=> "input"),

									array(
									"name" 	=> __("Subtitle below name", 'avia_framework' ),
									"desc" 	=> __("Can be used for a job description", 'avia_framework' ),
									"id" 	=> "subtitle",
									"std" 	=> "",
									"type" 	=> "input"),

							        array(
									"name" 	=> __("Quote", 'avia_framework' ),
									"desc" 	=> __("Enter the testimonial here", 'avia_framework' ),
									"id" 	=> "content",
									"std" 	=> "",
									"type" 	=> "tiny_mce"
									),

									array(
									"name" 	=> __("Website Link", 'avia_framework' ),
									"desc" 	=> __("Link to the Persons website", 'avia_framework' ),
									"id" 	=> "link",
									"std" 	=> "http://",
									"type" 	=> "input"),

									array(
									"name" 	=> __("Website Name", 'avia_framework' ),
									"desc" 	=> __("Linktext for the above Link", 'avia_framework' ),
									"id" 	=> "linktext",
									"std" 	=> "",
									"type" 	=> "input"),

						)
					),


array(
							"name" 	=> __("Testimonial Style", 'avia_framework' ),
							"desc" 	=> __("Here you can select how to display the testimonials. You can either create a testimonial slider or a testimonial grid with multiple columns", 'avia_framework' ) ,
							"id" 	=> "style",
							"type" 	=> "select",
							"std" 	=> "grid",
							"subtype" => array(	__('Testimonial Grid', 'avia_framework' ) =>'grid',
												__('Testimonial Slider (Compact)', 'avia_framework' ) =>'slider',
												__('Testimonial Slider (Large)', 'avia_framework' ) =>'slider_large',
							)
						),

						array(
							"name" 	=> __("Testimonial Grid Columns", 'avia_framework' ),
							"desc" 	=> __("How many columns do you want to display", 'avia_framework' ) ,
							"id" 	=> "columns",
							"required" 	=> array('style', 'equals', 'grid'),
							"type" 	=> "select",
							"std" 	=> "2",
							"subtype" => AviaHtmlHelper::number_array(1,4,1)
							),
							
						array(
							"name" 	=> __("Testimonial Grid Style", 'avia_framework' ),
							"desc" 	=> __("Set the styling for the testimonial grid", 'avia_framework' ) ,
							"id" 	=> "grid_style",
							"required" 	=> array('style', 'equals', 'grid'),
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array(	
												__('Default Grid', 'avia_framework' ) =>'',
												__('Minimal Grid', 'avia_framework' ) =>'av-minimal-grid-style',
												__('Boxed Grid', 'avia_framework' ) =>'av-minimal-grid-style av-boxed-grid-style',
							)
							),

						array(
						"name" 	=> __("Slideshow autorotation duration",'avia_framework' ),
						"desc" 	=> __("Slideshow will rotate every X seconds",'avia_framework' ),
						"id" 	=> "interval",
						"type" 	=> "select",
						"std" 	=> "5",
						"required" 	=> array('style','contains','slider'),
						"subtype" => array('3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','15'=>'15','20'=>'20','30'=>'30','40'=>'40','60'=>'60','100'=>'100')),
						
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
										"name" 	=> __("Font Colors", 'avia_framework' ),
										"desc" 	=> __("Either use the themes default colors or apply some custom ones", 'avia_framework' ),
										"id" 	=> "font_color",
										"type" 	=> "select",
										"std" 	=> "",
										"subtype" => array( __('Default', 'avia_framework' )=>'',
															__('Define Custom Colors', 'avia_framework' )=>'custom'),
								),
								
								array(	
									"name" 	=> __("Name Font Color", 'avia_framework' ),
									"desc" 	=> __("Select a custom font color. Leave empty to use the default", 'avia_framework' ),
									"id" 	=> "custom_title",
									"type" 	=> "colorpicker",
									"std" 	=> "",
									"container_class" => 'av_half av_half_first',
									"required" => array('font_color','equals','custom')
										),	
										
									array(	
											"name" 	=> __("Custom Content Font Color", 'avia_framework' ),
											"desc" 	=> __("Select a custom font color. Leave empty to use the default", 'avia_framework' ),
											"id" 	=> "custom_content",
											"type" 	=> "colorpicker",
											"std" 	=> "",
											"container_class" => 'av_half',
											"required" => array('font_color','equals','custom')
									
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
			 * Editor Sub Element - this function defines the visual appearance of an element that is displayed within a modal window and on click opens its own modal window
			 * Works in the same way as Editor Element
			 * @param array $params this array holds the default values for $content and $args.
			 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
			 */
			function editor_sub_element($params)
			{
				$template = $this->update_template("name", __("Testimonial by", 'avia_framework' ). ": {{name}}");

				$params['innerHtml']  = "";
				$params['innerHtml'] .= "<div class='avia_title_container' {$template}>".__("Testimonial by", 'avia_framework' ).": ".$params['args']['name']."</div>";

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
					
					'style'=> "grid",  
					'columns'=> "2", 
					"autoplay"=>true, 
					"interval"=>5,
					'font_color'=>'', 
					'custom_title'=>'', 
					'custom_content'=>'',
					'grid_style'	=> ''
				
				), $atts, $this->config['shortcode']);
				
				
				$custom_class = !empty($meta['custom_class']) ? $meta['custom_class'] : "";
				extract($atts);
				
				
				if($style != "grid") $grid_style = "";
				
				$this->title_styling 		= "";
				$this->content_styling 		= "";
				$this->content_class 		= "";
				$this->subtitle_class 		= "";
				
				if($font_color == "custom")
				{
					$this->title_styling 		.= !empty($custom_title) ? "color:{$custom_title}; " : "";
					$this->content_styling 		.= !empty($custom_content) ? "color:{$custom_content}; " : "";
					
					if($this->title_styling) 	
					{
						$this->title_styling = " style='{$this->title_styling}'" ;
						$this->subtitle_class = "av_opacity_variation";	
					}
					
					if($this->content_styling) 	
					{
						$this->content_class = "av_inherit_color";
						$this->content_styling = " style='{$this->content_styling}'" ;
					}
				}
				
				$output = "";

				switch($columns)
				{
					case 1: $columnClass = "av_one_full flex_column no_margin"; break;
					case 2: $columnClass = "av_one_half flex_column no_margin"; break;
					case 3: $columnClass = "av_one_third flex_column no_margin"; break;
					case 4: $columnClass = "av_one_fourth flex_column no_margin"; break;
				}

				$data = AviaHelper::create_data_string(array('autoplay'=>$autoplay, 'interval'=>$interval, 'animation' => 'fade', 'hoverpause' => true));
				$controls = false;
				
				if($style == "slider_large")
				{
					$style = "slider";
					$custom_class .= " av-large-testimonial-slider";
					$controls = true;
				}
				
				
				$output .= "<div {$data} class='avia-testimonial-wrapper avia-{$style}-testimonials avia-{$style}-{$columns}-testimonials avia_animate_when_almost_visible {$custom_class} {$grid_style} {$av_display_classes}'>";

				avia_sc_testimonial::$counter = 1;
				avia_sc_testimonial::$rows = 1;
				avia_sc_testimonial::$columnClass = $columnClass;
				avia_sc_testimonial::$columns = $columns;
				avia_sc_testimonial::$style = $style;
				avia_sc_testimonial::$grid_style = $grid_style;



				//if we got a slider we only need a single row wrpper
				if($style != "grid") avia_sc_testimonial::$columns = 100000;

				$output .= ShortcodeHelper::avia_remove_autop($content, true);

				//close unclosed wrapper containers
				if(avia_sc_testimonial::$counter != 1){
				$output .= "</section>";
				}
				
				if($controls)
				{
					$output .= $this->slide_navigation_arrows();
				}
				
				
				$output .= "</div>";

				return $output;
			}
			
			function slide_navigation_arrows()
	        {
	            $html  = "";
	            $html .= "<div class='avia-slideshow-arrows avia-slideshow-controls' {$this->content_styling}>";
				$html .= 	"<a href='#prev' class='prev-slide' ".av_icon_string('prev_big').">".__('Previous','avia_framework' )."</a>";
				$html .= 	"<a href='#next' class='next-slide' ".av_icon_string('next_big').">".__('Next','avia_framework' )."</a>";
	            $html .= "</div>";
	
	            return $html;
	        }
			

			function av_testimonial_single($atts, $content = "", $shortcodename = "")
			{
				extract(shortcode_atts(array('src'=> "",  'name'=> "",  'subtitle'=> "",  'link'=> "", 'linktext'=>"", 'custom_markup' =>'' ), $atts, 'av_testimonial_single'));

				$output = "";
				$avatar = "";
				$grid = avia_sc_testimonial::$style == 'grid' ? true :false;
				$grid_style = $grid == true? avia_sc_testimonial::$grid_style : "";
				$class = avia_sc_testimonial::$columnClass." avia-testimonial-row-".avia_sc_testimonial::$rows." ";
				//if(count($testimonials) <= $rows * $columns) $class.= " avia-testimonial-row-last ";
				if(avia_sc_testimonial::$counter == 1) $class .= "avia-first-testimonial";
				if(avia_sc_testimonial::$counter == avia_sc_testimonial::$columns) $class .= "avia-last-testimonial";
				if($link && !$linktext) $linktext = $link;
				if($link == 'http://') $link = "";
                $linktext = htmlentities($linktext);

				if(avia_sc_testimonial::$counter == 1)
                {
				    $output .= "<section class ='avia-testimonial-row'>";
				}


	//avatar size filter
	$avatar_size = apply_filters('avf_testimonials_avatar_size', 'square', $src, $class);

	//avatar
                $markup = avia_markup_helper(array('context' => 'single_image','echo'=>false, 'custom_markup'=>$custom_markup));
	if($src)	$avatar  = "<div class='avia-testimonial-image' $markup>".wp_get_attachment_image( $src , $avatar_size , false, array('alt'=>esc_attr(strip_tags($name))))."</div>";

	//meta
                $markup_text = avia_markup_helper(array('context' => 'entry','echo'=>false, 'custom_markup'=>$custom_markup));
                $markup_name = avia_markup_helper(array('context' => 'name','echo'=>false, 'custom_markup'=>$custom_markup));
                $markup_job = avia_markup_helper(array('context' => 'job','echo'=>false, 'custom_markup'=>$custom_markup));
                if(strstr($link, '@'))
                {
                    $markup_url = avia_markup_helper(array('context' => 'email','echo'=>false, 'custom_markup'=>$custom_markup));
                }
                else
                {
                    $markup_url = avia_markup_helper(array('context' => 'url','echo'=>false, 'custom_markup'=>$custom_markup));
                }

	//final output
				
				$markup = avia_markup_helper(array('context' => 'person','echo'=>false, 'custom_markup'=>$custom_markup));
				
				$output .= "<div class='avia-testimonial {$class}' $markup>";
				$output .= "<div class='avia-testimonial_inner'>";
				
	if($grid && $grid_style == "")   $output .= $avatar;
	
				$output .= 		"<div class='avia-testimonial-content {$this->content_class}'  {$this->content_styling} {$markup_text}>";
				$output .= 		ShortcodeHelper::avia_apply_autop(ShortcodeHelper::avia_remove_autop($content));
				$output .= 		"</div>";
				$output .= 			"<div class='avia-testimonial-meta'><div class='avia-testimonial-arrow-wrap'><div class='avia-arrow'></div></div>";
	if(!$grid || ($grid && $grid_style != ""))  $output .=  $avatar;
				$output .= 				"<div class='avia-testimonial-meta-mini'>";
	if($name)	$output .= 					"<strong  class='avia-testimonial-name'  {$this->title_styling} {$markup_name}>{$name}</strong>";
if($subtitle)	$output .= 					"<span  class='avia-testimonial-subtitle {$this->subtitle_class}' {$this->title_styling}  {$markup_job}>{$subtitle}</span>";
    if($link)	$output .= 					"<span class='hidden avia-testimonial-markup-link'  {$markup_url}>{$link}</span>";
	
	if($link && $subtitle)	$output .= 					" &ndash; ";
	if($link)	$output .= 					"<a class='aviablank avia-testimonial-link' href='{$link}' >{$linktext}</a>";
				$output .= 				"</div>";
				$output .= 			"</div>";
				$output .= "</div>";
				$output .= "</div>";



				if(avia_sc_testimonial::$counter == avia_sc_testimonial::$columns)
                {
				    $output .= "</section>";
				}

				avia_sc_testimonial::$counter++;
				if(avia_sc_testimonial::$counter > avia_sc_testimonial::$columns) { avia_sc_testimonial::$counter = 1; avia_sc_testimonial::$rows++; }


				return $output;
			}

	}
}






