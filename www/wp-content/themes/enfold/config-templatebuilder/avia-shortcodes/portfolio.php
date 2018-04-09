<?php
/**
 * Portfolio Grid
 * 
 * Creates a grid of portfolio excerpts
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_portfolio' ) )
{
	class avia_sc_portfolio extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'yes';
				
				$this->config['name']		= __('Portfolio Grid', 'avia_framework' );
				$this->config['tab']		= __('Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-portfolio.png";
				$this->config['order']		= 38;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_portfolio';
				$this->config['tooltip'] 	= __('Creates a grid of portfolio excerpts', 'avia_framework' );
			}

			function extra_assets()
			{
				if(!is_admin() && !current_theme_supports('avia_no_session_support') && !session_id()) session_start();
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

					
					array(	"name" 		=> __("Which categories should be used for the portfolio?", 'avia_framework' ),
							"desc" 		=> __("You can select multiple categories here. The Page will then show posts from only those categories.", 'avia_framework' ),
				            "id" 		=> "categories",
				            "type" 		=> "select",
	        				"multiple"	=> 6,
	        				"taxonomy" 	=> "portfolio_entries",
				            "subtype" 	=> "cat"),
				    /*
						array(
							"name" 	=> __("Style?", 'avia_framework' ),
							"desc" 	=> __("Choose the style of the entries here", 'avia_framework' ),
							"id" 	=> "style",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array( __('Default Style', 'avia_framework' ) => '',
												__('Circle Image Stlye',  'avia_framework' )=>'grid-circle')),
					*/

					array(
							"name" 	=> __("Columns", 'avia_framework' ),
							"desc" 	=> __("How many columns should be displayed?", 'avia_framework' ),
							"id" 	=> "columns",
							"type" 	=> "select",
							"std" 	=> "4",
							"subtype" => array(	__('1 Column',  'avia_framework' )=>'1',
												__('2 Columns', 'avia_framework' )=>'2',
												__('3 Columns', 'avia_framework' )=>'3',
												__('4 Columns', 'avia_framework' )=>'4',
												__('5 Columns', 'avia_framework' )=>'5',
												__('6 Columns', 'avia_framework' )=>'6',
												)),

                    array(
                        "name" 	=> __("1 Column layout", 'avia_framework' ),
                        "desc" 	=> __("Choose the 1 column layout", 'avia_framework' ),
                        "id" 	=> "one_column_template",
                        "type" 	=> "select",
                        "required" 	=> array('columns','equals','1'),
                        "std" 	=> "special",
                        "subtype" => array(
                            __('Use special 1 column layout (side by side)',  'avia_framework' ) =>'special',
                            __('Use default portfolio layout',  'avia_framework' ) =>'default')),

					array(
							"name" 	=> __("Post Number", 'avia_framework' ),
							"desc" 	=> __("How many items should be displayed per page?", 'avia_framework' ),
							"id" 	=> "items",
							"type" 	=> "select",
							"std" 	=> "16",
							"subtype" => AviaHtmlHelper::number_array(1,100,1, array('All'=>'-1'))),

					array(
							"name" 	=> __("Excerpt", 'avia_framework' ),
							"desc" 	=> __("Display Excerpt and Title below the preview image?", 'avia_framework' ),
							"id" 	=> "contents",
							"type" 	=> "select",
							"std" 	=> "yes",
							"subtype" => array(
								__('Title and Excerpt',  'avia_framework' ) =>'excerpt',
								__('Only Title',  'avia_framework' ) =>'title',
								__('Only excerpt',  'avia_framework' ) =>'only_excerpt',
								__('No Title and no excerpt',  'avia_framework' ) =>'no')),

					array(
                        "name" 	=> __("Portfolio Grid Image Size", 'avia_framework' ),
                        "desc" 	=> __("Set the image size of the Portfolio Grid images", 'avia_framework' ),
                        "id" 	=> "preview_mode",
                        "type" 	=> "select",
                        "std" 	=> "auto",
                        "subtype" => array(__('Set the Portfolio Grid image size automatically based on column or layout width','avia_framework' ) =>'auto',__('Choose the Portfolio Grid image size manually (select thumbnail size)','avia_framework' ) =>'custom')),

                    array(
                        "name" 	=> __("Select custom image size", 'avia_framework' ),
                        "desc" 	=> __("Choose image size for Portfolio Grid Images", 'avia_framework' ) . "<br/><small>" . __("(Note: Images will be scaled to fit for the amount of columns chosen above)", 'avia_framework' )."</small>",
                        "id" 	=> "image_size",
                        "type" 	=> "select",
                        "required" 	=> array('preview_mode','equals','custom'),
                        "std" 	=> "portfolio",
                        "subtype" =>  AviaHelper::get_registered_image_sizes(array('logo','thumbnail','widget'))
                    ),

					array(
							"name" 	=> __("Link Handling", 'avia_framework' ),
							"desc" 	=> __("When clicking on a portfolio item you can choose to open the link to the single entry, open a preview (aka AJAX Portfolio) or show a bigger version of the image in a lightbox overlay", 'avia_framework' ),
							"id" 	=> "linking",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array(
								__('Open the entry on a new page',  'avia_framework' ) =>'',
								__('Open a preview of the entry (known as AJAX Portfolio)',  'avia_framework' ) =>'ajax',
								__('Display the big image in a lightbox',  'avia_framework' ) =>'lightbox')),

					array(
							"name" 	=> __("Sortable?", 'avia_framework' ),
							"desc" 	=> __("Should the sorting options based on categories be displayed?", 'avia_framework' ),
							"id" 	=> "sort",
							"type" 	=> "select",
							"std" 	=> "yes",
							"subtype" => array(
							__('Yes, display sort options',  'avia_framework' ) => 'yes',
							__('Yes, display sort options and currently active categories',  'avia_framework' ) => 'yes-tax',
							__('No, do not display sort options',  'avia_framework' )  => 'no')),

					array(
							"name" 	=> __("Pagination", 'avia_framework' ),
							"desc" 	=> __("Should a pagination be displayed?", 'avia_framework' ),
							"id" 	=> "paginate",
							"type" 	=> "select",
							"std" 	=> "yes",
							"subtype" => array(
								__('yes',  'avia_framework' ) =>'yes',
								__('no',  'avia_framework' ) =>'no')),
								
					array(
            			    "name" => __("Order by",'avia_framework' ),
							"desc" 	=> __("You can order the result by various attributes like creation date, title, author etc", 'avia_framework' ),
            			    "id"   => "query_orderby",
            			    "type" 	=> "select",
            			    "std" 	=> "date",
            			    "subtype" => array(
            			        __('Date',  'avia_framework' ) =>'date',
            			        __('Title',  'avia_framework' ) =>'title',
            			        __('Random',  'avia_framework' ) =>'rand',
            			        __('Author',  'avia_framework' ) =>'author',
            			        __('Name (Post Slug)',  'avia_framework' ) =>'name',
            			        __('Last modified',  'avia_framework' ) =>'modified',
            			        __('Comment Count',  'avia_framework' ) =>'comment_count',
            			        __('Page Order',  'avia_framework' ) =>'menu_order')
            			),
            			
            			array(
              			  "name" => __("Display order",'avia_framework' ),
						  "desc" 	=> __("Display the results either in ascending or descending order", 'avia_framework' ),
              			  "id"   => "query_order",
              			  "type" 	=> "select",
              			  "std" 	=> "DESC",
              			  "subtype" => array(
              			      __('Ascending Order',  'avia_framework' ) =>'ASC',
              			      __('Descending Order',  'avia_framework' ) =>'DESC')
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
								"name" 	=> __("Element Columns",'avia_framework' ),
								"desc" 	=> 
								__("Set the column count for this element, based on the device screensize.", 'avia_framework' )
								,
								"type" 	=> "heading",
								"description_class" => "av-builder-note av-neutral",
								),
							
							
								array(	"name" 	=> __("Column count for medium sized screens", 'avia_framework' ),
						            "id" 	=> "av-medium-columns",
						            "type" 	=> "select",
						            "subtype" => AviaHtmlHelper::number_array(1,4,1, array( __("Default", 'avia_framework' )=>'')),
						            "std" => ""),
						            
						            array(	"name" 	=> __("Column count for small screens", 'avia_framework' ),
						            "id" 	=> "av-small-columns",
						            "type" 	=> "select",
						            "subtype" => AviaHtmlHelper::number_array(1,4,1, array( __("Default", 'avia_framework' )=>'')),
						            "std" => ""),
						            
									array(	"name" 	=> __("Column count for very small screens", 'avia_framework' ),
						            "id" 	=> "av-mini-columns",
						            "type" 	=> "select",
						            "subtype" => AviaHtmlHelper::number_array(1,4,1, array( __("Default", 'avia_framework' )=>'')),
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
				
				
			        if(current_theme_supports('avia_template_builder_custom_post_type_grid'))
			        {
			            $this->elements[2] = array(
			                    "name" 	=> __("Which Entries?", 'avia_framework' ),
			                    "desc" 	=> __("Select which entries should be displayed by selecting a taxonomy", 'avia_framework' ),
			                    "id" 	=> "link",
			                    "fetchTMPL"	=> true,
			                    "type" 	=> "linkpicker",
			                    "subtype"  => array( __('Display Entries from:',  'avia_framework' )=>'taxonomy'),
			                    "multiple"	=> 6,
			                    "std" 	=> "category");


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
									
							array_splice($this->elements, 2, 0, array($element));
							
						}
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
				
				$atts['class'] = !empty($meta['custom_class']) ? $meta['custom_class'] : "";
				
				if(current_theme_supports('avia_template_builder_custom_post_type_grid'))
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
				
					if(empty($atts['post_type']) || !current_theme_supports('add_avia_builder_post_type_option'))
					{
						$atts['post_type'] = get_post_types();
					}

					if(is_string($atts['post_type'])) $atts['post_type'] = explode(',', $atts['post_type']);
				}
				
				$atts['fullscreen'] = ShortcodeHelper::is_top_level();

				$grid = new avia_post_grid($atts);
				$grid->query_entries();
				$portfolio_html = $grid->html();
			
				if(!ShortcodeHelper::is_top_level()) 
				return $portfolio_html;
				
				
				$params['class'] = "main_color avia-no-border-styling avia-fullwidth-portfolio {$av_display_classes} ".$meta['el_class'];
				$params['open_structure'] = false;
				$params['id'] = !empty($atts['id']) ? AviaHelper::save_string($atts['id'],'-') : "";
				$params['custom_markup'] = $meta['custom_markup'];
				
				//we dont need a closing structure if the element is the first one or if a previous fullwidth element was displayed before
				if(isset($meta['index']) && $meta['index'] == 0) $params['close'] = false;
				if(!empty($meta['siblings']['prev']['tag']) && in_array($meta['siblings']['prev']['tag'], AviaBuilder::$full_el_no_section )) $params['close'] = false;
					
				$output  =  avia_new_section($params);
				$output .= $portfolio_html;
				$output .= avia_section_after_element_content( $meta , 'after_portfolio' );
				
				return $output;
			}
		}
}




if ( !class_exists( 'avia_post_grid' ) )
{
	class avia_post_grid
	{
		static  $grid = 0;
		static  $preview_template = array();
		protected $atts;
		protected $entries;

		function __construct($atts = array())
		{
			$this->screen_options = AviaHelper::av_mobile_sizes($atts);
			
			$this->atts = shortcode_atts(array(	'style'		=> '',
										 		'linking' 	=> '',
										 		'columns' 	=> '4',
		                                 		'items' 	=> '16',
		                                 		'contents' 	=> 'title',
		                                 		'sort' 		=> 'yes',
		                                 		'paginate' 	=> 'yes',
		                                 		'categories'=> '',
		                                 		'preview_mode' => 'auto',
                                                'image_size' => 'portfolio',
		                                 		'post_type'	=> 'portfolio',
		                                 		'taxonomy'  => 'portfolio_entries',
		                                 		'one_column_template' => 'special',
		                                 		'set_breadcrumb' => true, //no shortcode option for this, modifies the breadcrumb nav, must be false on taxonomy overview
		                                 		'class'		=> "",
		                                 		'custom_markup'	=> '',
		                                 		'fullscreen'	=> false,
		                                 		'query_orderby' => 'date',
		                                 		'query_order' => 'DESC',
		                                 		), $atts, 'av_portfolio');



		    if($this->atts['linking'] == 'ajax')
				add_action('wp_footer' , array($this, 'print_preview_templates'));
		}

		//generates the html of the post grid
		public function html()
		{
			if(empty($this->entries) || empty($this->entries->posts)) return;

			avia_post_grid::$grid ++;
			extract($this->atts);
			extract($this->screen_options); //return $av_font_classes, $av_title_font_classes and $av_display_classes 
			
			$container_id 		= avia_post_grid::$grid;
			$extraClass 		= 'first';
			$grid 				= 'one_fourth';
			if($preview_mode == 'auto') $image_size = 'portfolio';
			$post_loop_count 	= 1;
			$loop_counter		= 1;
			$output				= "";
			$style_class		= empty($style) ? 'no_margin' : $style;
			$total				= $this->entries->post_count % 2 ? "odd" : "even";

			if($set_breadcrumb && is_page())
			{
				$_SESSION["avia_{$post_type}"] = get_the_ID();
			}

			switch($columns)
			{
				case "1": $grid = 'av_fullwidth';  if($preview_mode == 'auto') $image_size = 'featured'; break;
				case "2": $grid = 'av_one_half';   break;
				case "3": $grid = 'av_one_third';  break;
				case "4": $grid = 'av_one_fourth'; if($preview_mode == 'auto') $image_size = 'portfolio_small'; break;
				case "5": $grid = 'av_one_fifth';  if($preview_mode == 'auto') $image_size = 'portfolio_small'; break;
				case "6": $grid = 'av_one_sixth';  if($preview_mode == 'auto') $image_size = 'portfolio_small'; break;
			}
			
			if($fullscreen && $preview_mode =='auto' && $image_size == "portfolio_small") $image_size = 'portfolio';

			$output .= $sort != "no" ? $this->sort_buttons($this->entries->posts, $this->atts) : "";

			if($linking == "ajax")
			{
				global $avia_config;
				
				$container_class = $fullscreen ? "container" : "";
				
				$output .= "<div class='portfolio_preview_container {$container_class}' data-portfolio-id='{$container_id}'>
								<div class='ajax_controlls iconfont'>
									<a href='#prev' class='ajax_previous' 	".av_icon_string('prev')."></a>
									<a href='#next' class='ajax_next'		".av_icon_string('next')."></a>
									<a class='avia_close' href='#close'		".av_icon_string('close')."></a>
								</div>
								<div class='portfolio-details-inner'></div>
							</div>";
			}
			$output .= "<div class='{$class} grid-sort-container isotope {$av_display_classes} {$av_column_classes} {$style_class}-container with-{$contents}-container grid-total-{$total} grid-col-{$columns} grid-links-{$linking}' data-portfolio-id='{$container_id}'>";

			foreach ($this->entries->posts as $entry)
			{
				$the_id 	= $entry->ID;
				$parity		= $post_loop_count % 2 ? 'odd' : 'even';
				$last       = $this->entries->post_count == $post_loop_count ? " post-entry-last " : "";
				$post_class = "post-entry post-entry-{$the_id} grid-entry-overview grid-loop-{$post_loop_count} grid-parity-{$parity} {$last}";
				$sort_class = $this->sort_cat_string($the_id, $this->atts);

				switch($linking)
				{
					case "lightbox":  $link = aviaHelper::get_url('lightbox', get_post_thumbnail_id($the_id));	break;
					default: 		  $link = get_permalink($the_id); break;
				}

				$title_link  = get_permalink($the_id);
				$custom_link = get_post_meta( $the_id ,'_portfolio_custom_link', true) != "" ? get_post_meta( $the_id ,'_portfolio_custom_link_url', true) : false;

				if($custom_link)
				{
					$title_link = "";
					$link = $custom_link;
				}

				$excerpt 	= '';
				$title 		= '';

				switch($contents)
				{
					case "excerpt": $excerpt = $entry->post_excerpt; $title = $entry->post_title; break;
					case "title": $excerpt = ''; $title = $entry->post_title;  break;
					case "only_excerpt": $excerpt = $entry->post_excerpt; $title = ''; break;
					case "no": $excerpt = ''; $title = ''; break;
				}

				$custom_overlay = apply_filters('avf_portfolio_custom_overlay', "", $entry);
				$link_markup 	= apply_filters('avf_portfolio_custom_image_container', array("a href='{$link}' title='".esc_attr(strip_tags($title))."' ",'a'), $entry);

				$title 			= apply_filters('avf_portfolio_title', $title, $entry);
				$title_link    	= apply_filters('avf_portfolio_title_link', $title_link, $entry);
				$image_attrs    = apply_filters('avf_portfolio_image_attrs', array(), $entry);
				
				
				
                if($columns == "1" && $one_column_template == 'special')
                {
                    $extraClass .= ' special_av_fullwidth ';

                    $output .= "<div data-ajax-id='{$the_id}' class=' grid-entry flex_column isotope-item all_sort {$style_class} {$post_class} {$sort_class} {$grid} {$extraClass}'>";
                    $output .= "<article class='main_color inner-entry' ".avia_markup_helper(array('context' => 'entry','echo'=>false, 'id'=>$the_id, 'custom_markup'=>$custom_markup)).">";
                    $output .= apply_filters('avf_portfolio_extra', "", $entry);

                    $output .= "<div class='av_table_col first portfolio-entry grid-content'>";

                    if(!empty($title))
                    {
                        $markup = avia_markup_helper(array('context' => 'entry_title','echo'=>false, 'id'=>$the_id, 'custom_markup'=>$custom_markup));
                        $output .= '<header class="entry-content-header">';
                        $output .= "<h2 class='portfolio-grid-title entry-title' $markup>";
                        
                        if(!empty($title_link))
                        {
                        	$output .= "<a href='{$title_link}'>".$title."</a>";
                        }
                        else
                        {
                        	$output .= "".$title."";
                        }
                        $output .= '</h2></header>';
                    }

                    if(!empty($excerpt))
                    {
                        $markup = avia_markup_helper(array('context' => 'entry_content','echo'=>false, 'id'=>$the_id, 'custom_markup'=>$custom_markup));

                        $output .= "<div class='entry-content-wrapper'>";
                        $output .= "<div class='grid-entry-excerpt entry-content' $markup>".$excerpt."</div>";
                        $output .= "</div>";
                    }
                    $output .= '<div class="avia-arrow"></div>';
                    $output .= "</div>";

                    $image = get_the_post_thumbnail( $the_id, $image_size, $image_attrs );
                    if(!empty($image))
                    {
                        $output .= "<div class='av_table_col portfolio-grid-image'>";
                        $output .= "<".$link_markup[0]." data-rel='grid-".avia_post_grid::$grid."' class='grid-image avia-hover-fx'>".$custom_overlay.$image."</".$link_markup[1].">";
                        $output .= "</div>";
                    }
                    $output .= '<footer class="entry-footer"></footer>';
                    $output .= "</article>";
                    $output .= "</div>";
                }
                else
                {
                    $extraClass .= ' default_av_fullwidth ';

                    $output .= "<div data-ajax-id='{$the_id}' class=' grid-entry flex_column isotope-item all_sort {$style_class} {$post_class} {$sort_class} {$grid} {$extraClass}'>";
                    $output .= "<article class='main_color inner-entry' ".avia_markup_helper(array('context' => 'entry','echo'=>false, 'id'=>$the_id, 'custom_markup'=>$custom_markup)).">";
                    $output .= apply_filters('avf_portfolio_extra', "", $entry);
                    $output .= "<".$link_markup[0]." data-rel='grid-".avia_post_grid::$grid."' class='grid-image avia-hover-fx'>".$custom_overlay.get_the_post_thumbnail( $the_id, $image_size, $image_attrs )."</".$link_markup[1].">";
                    $output .= !empty($title) || !empty($excerpt) ? "<div class='grid-content'><div class='avia-arrow'></div>" : '';

                    if(!empty($title))
                    {
                        $markup = avia_markup_helper(array('context' => 'entry_title','echo'=>false, 'id'=>$the_id, 'custom_markup'=>$custom_markup));
                        $output .= '<header class="entry-content-header">';
                        $output .= "<h3 class='grid-entry-title entry-title' $markup>";
                        
                        if(!empty($title_link))
                        {
                        	$output .= "<a href='{$title_link}' title='".esc_attr(strip_tags($title))."'>".$title."</a>";
                        }
                        else
                        {
                        	$output .= "".$title."";
                        }
                        
                        $output .= '</h3></header>';
                    }
                    $output .= !empty($excerpt) ? "<div class='grid-entry-excerpt entry-content' ".avia_markup_helper(array('context'=>'entry_content','echo'=>false, 'id'=>$the_id, 'custom_markup'=>$custom_markup)).">".$excerpt."</div>" : '';
                    $output .= !empty($title) || !empty($excerpt) ? "</div>" : '';
                    $output .= '<footer class="entry-footer"></footer>';
                    $output .= "</article>";
                    $output .= "</div>";
                }


				$loop_counter ++;
				$post_loop_count ++;
				$extraClass = "";

				if($loop_counter > $columns)
				{
					$loop_counter = 1;
					$extraClass = 'first';
				}
			}

			$output .= "</div>";

			//append pagination
			if($paginate == "yes" && $avia_pagination = avia_pagination($this->entries->max_num_pages, 'nav')) $output .= "<div class='pagination-wrap pagination-{$post_type}'>{$avia_pagination}</div>";

			return $output;
		}

		//generates the html for the sort buttons
		protected function sort_buttons($entries, $params)
		{
			//get all categories that are actually listed on the page
			$categories = get_categories(array(
				'taxonomy'	=> $params['taxonomy'],
				'hide_empty'=> 0
			));

			$current_page_cats 	= array();
			$cat_count 			= array();
			$display_cats 		= is_array($params['categories']) ? $params['categories'] : array_filter(explode(',',$params['categories']));

			foreach ($entries as $entry)
			{
				if($current_item_cats = get_the_terms( $entry->ID, $params['taxonomy'] ))
				{
					if(!empty($current_item_cats))
					{
						foreach($current_item_cats as $current_item_cat)
						{
							if(empty($display_cats) || in_array($current_item_cat->term_id, $display_cats))
							{
								$current_page_cats[$current_item_cat->term_id] = $current_item_cat->term_id;

								if(!isset($cat_count[$current_item_cat->term_id] ))
								{
									$cat_count[$current_item_cat->term_id] = 0;
								}

								$cat_count[$current_item_cat->term_id] ++;
							}
						}
					}
				}
			}
			
			extract($this->screen_options); //return $av_font_classes, $av_title_font_classes and $av_display_classes 

			$output = "<div class='sort_width_container {$av_display_classes} av-sort-".$this->atts['sort']."' data-portfolio-id='".avia_post_grid::$grid."' ><div id='js_sort_items' >";
			$hide 	= count($current_page_cats) <= 1 ? "hidden" : "";


			$first_item_name = apply_filters('avf_portfolio_sort_first_label', __('All','avia_framework' ), $params);
			$first_item_html = '<span class="inner_sort_button"><span>'.$first_item_name.'</span><small class="av-cat-count"> '.count($entries).' </small></span>';
			$output .= apply_filters('avf_portfolio_sort_heading', "", $params);
			
			
			if(strpos($this->atts['sort'], 'tax') !== false) $output .= "<div class='av-current-sort-title'>{$first_item_html}</div>";
			$output .= "<div class='sort_by_cat {$hide} '>";
			$output .= '<a href="#" data-filter="all_sort" class="all_sort_button active_sort">'.$first_item_html.'</a>';


			foreach($categories as $category)
			{
				if(in_array($category->term_id, $current_page_cats))
				{
					//fix for cyrillic, etc. characters - isotope does not support the % char
					$category->category_nicename = str_replace('%', '', $category->category_nicename);

					$output .= 	"<span class='text-sep ".$category->category_nicename."_sort_sep'>/</span>";
					$output .= 		'<a href="#" data-filter="'.$category->category_nicename.'_sort" class="'.$category->category_nicename.'_sort_button" ><span class="inner_sort_button">';
					$output .= 			"<span>".esc_html(trim($category->cat_name))."</span>";
					$output .= 			"<small class='av-cat-count'> ".$cat_count[$category->term_id]." </small></span>";
					$output .= 		"</a>";
				}
			}

			$output .= "</div></div></div>";

			return $output;
		}


		//get the categories for each post and create a string that serves as classes so the javascript can sort by those classes
		protected function sort_cat_string($the_id, $params)
		{
			$sort_classes = "";
			$item_categories = get_the_terms( $the_id, $params['taxonomy']);

			if(is_object($item_categories) || is_array($item_categories))
			{
				foreach ($item_categories as $cat)
				{
					//fix for cyrillic, etc. characters - isotope does not support the % char
					$cat->slug = str_replace('%', '', $cat->slug);
					
					$sort_classes .= $cat->slug.'_sort ';
				}
			}

			return $sort_classes;
		}

		protected function build_preview_template( $entry )
		{
			if(isset(avia_post_grid::$preview_template[$entry->ID])) return;
			avia_post_grid::$preview_template[$entry->ID] = true;

			$id 					= $entry->ID;
			$output 				= "";
			$defaults 				= array( 'ids' => get_post_thumbnail_id( $id ), 'text' => apply_filters( 'get_the_excerpt', $entry->post_excerpt) , "method" => 'gallery' , "auto" => "", "columns" => 5);
			$params['ids'] 			= get_post_meta( $id ,'_preview_ids', true);
			$params['text']		  	= get_post_meta( $id ,'_preview_text', true);
			$params['method']	  	= get_post_meta( $id ,'_preview_display', true);
			$params['interval']		= get_post_meta( $id ,'_preview_autorotation', true);
			$params['columns']      = get_post_meta( $id ,'_preview_columns', true);
			$params['preview_size'] = apply_filters('avf_ajax_preview_image_size',"gallery");
			$params['autoplay']		= is_numeric($params['interval']) ? "true" : "false";

			$link = get_post_meta( $id ,'_portfolio_custom_link', true) != "" ? get_post_meta( $id ,'_portfolio_custom_link_url', true) : get_permalink($id);


			//merge default and params array. remove empty params with array_filter
			$params = array_merge($defaults, array_filter($params));
			
			$params = apply_filters('avf_portfolio_preview_template_params', $params, $entry);

			//set the content
			$content = str_replace(']]>', ']]&gt;', apply_filters('the_content', $params['text'] )); unset($params['text']);

			//set images
			$string = "";

			//set first class if preview images are deactivated
			$nogalleryclass = '';
			$params['ajax_request'] = true;
			switch($params['method'])
			{
				case 'gallery':

					$params['style'] =  "big_thumb";
					$params['thumb_size'] =  "square";
					foreach($params as $key => $param) $string .= $key."='".$param."' ";
					$images = do_shortcode("[av_gallery {$string}]");
				break;

				case 'slideshow':
					$params['size'] = $params['preview_size'];
					foreach($params as $key => $param) $string .= $key."='".$param."' ";
					$images = do_shortcode("[av_slideshow {$string}]");
				break;

				case 'list':
					$images = $this->post_images($params['ids']);
				break;

				case 'no':
					$images = false;
					$nogalleryclass = ' no_portfolio_preview_gallery ';
				break;
			}

			$output .= "<div class='ajax_slide ajax_slide_{$id}' data-slide-id='{$id}' >";

				$output .= "<article class='inner_slide $nogalleryclass' ".avia_markup_helper(array('context' => 'entry','echo'=>false, 'id'=>$id, 'custom_markup'=>$this->atts['custom_markup'])).">";

				if(!empty($images))
				{
					$output .= "<div class='av_table_col first portfolio-preview-image'>";
					$output .= $images;
					$output .= "</div>";
				}

				if(!empty($nogalleryclass)) $nogalleryclass .= ' first ';

					$output .= "<div class='av_table_col $nogalleryclass portfolio-entry portfolio-preview-content'>";

                        $markup = avia_markup_helper(array('context' => 'entry_title','echo'=>false, 'id'=>$id, 'custom_markup'=>$this->atts['custom_markup']));
                        $output .= '<header class="entry-content-header">';
						$output .= "<h2 class='portfolio-preview-title entry-title' $markup><a href='{$link}'>".$entry->post_title."</a></h2>";
                        $output .= '</header>';

						$output .= "<div class='entry-content-wrapper entry-content' ".avia_markup_helper(array('context' => 'entry_content','echo'=>false, 'id'=>$id, 'custom_markup'=>$this->atts['custom_markup'])).">";
						$output .= $content;
						$output .= "</div>";
						$output .= "<span class='avia-arrow'></span>";
					$output .= "</div>";

                $output .= '<footer class="entry-footer"></footer>';
				$output .= "</article>";

			$output .= "</div>";

		return "<script type='text/html' id='avia-tmpl-portfolio-preview-{$id}'>\n{$output}\n</script>\n\n";

		}

		protected function post_images($ids)
		{
			if(empty($ids)) return;

			$attachments = get_posts(array(
				'include' => $ids,
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => 'ASC',
				'orderby' => 'post__in')
				);

			$output = "";

			foreach($attachments as $attachment)
			{
				$img	 = wp_get_attachment_image_src($attachment->ID, 'large');

                $alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
                $alt = !empty($alt) ? esc_attr($alt) : '';
                $title = trim($attachment->post_title) ? esc_attr($attachment->post_title) : "";
                $description = trim($attachment->post_content) ? esc_attr($attachment->post_content) : "";

				$output .= " <a href='".$img[0]."' class='portolio-preview-list-image' title='".$description."' ><img src='".$img[0]."' title='".$title."' alt='".$alt."' /></a>";
			}

			return $output;
		}




		public function print_preview_templates()
		{
			foreach ($this->entries->posts as $entry)
			{
				echo $this->build_preview_template( $entry );
			}
		}



		//fetch new entries
		public function query_entries($params = array())
		{
			
		
			$query = array();
			if(empty($params)) $params = $this->atts;

			if(!empty($params['categories']))
			{
				//get the portfolio categories
				$terms 	= explode(',', $params['categories']);
			}

			$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
			if(!$page || $params['paginate'] == 'no') $page = 1;

			//if we find categories perform complex query, otherwise simple one
			if(isset($terms[0]) && !empty($terms[0]) && !is_null($terms[0]) && $terms[0] != "null")
			{
				$query = array(	'orderby' 	=> $params['query_orderby'],
								'order' 	=> $params['query_order'],
								'paged' 	=> $page,
								'posts_per_page' => $params['items'],
								'post_type' => $params['post_type'],
								'tax_query' => array( 	array( 	'taxonomy' 	=> $params['taxonomy'],
																'field' 	=> 'id',
																'terms' 	=> $terms,
																'operator' 	=> 'IN')));
			}
			else
			{
				$query = array(	'orderby' 	=> $params['query_orderby'],
								'order' 	=> $params['query_order'],
								'paged'		=> $page, 
								'posts_per_page' => $params['items'], 
								'post_type' => $params['post_type']);
			}

			$query = apply_filters('avia_post_grid_query', $query, $params);

			$this->entries = new WP_Query( $query );

		}


		//function that allows to set the query to an existing post query. usually only needed on pages that already did a query for the entries, like taxonomy archive pages.
		//Shortcode uses the query_entries function above
		public function use_global_query()
		{
			global $wp_query;
			$this->entries = $wp_query;
		}



	}
}


/*
Example: how to order posts randomly on page load. put this into functions.php

add_filter('avia_post_grid_query','avia_order_by_random');
function avia_order_by_random($query)
{
	$query['orderby'] = 'rand';
	return $query;
}
*/
