<?php
/**
 * Post Slider
 *
 * Display a Slideshow of Post Entries
 * Element is in Beta and by default disabled. Todo: test with layerslider elements. currently throws error bc layerslider is only included if layerslider element is detected which is not the case with the post/page element
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_postslider' ))
{
	class avia_sc_postslider extends aviaShortcodeTemplate
	{

		/**
		 * Create the config array for the shortcode button
		 */
		function shortcode_insert_button()
		{
			$this->config['self_closing']	=	'yes';
			
			$this->config['name']		= __('Post Slider', 'avia_framework' );
			$this->config['tab']		= __('Content Elements', 'avia_framework' );
			$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-postslider.png";
			$this->config['order']		= 30;
			$this->config['target']		= 'avia-target-insert';
			$this->config['shortcode'] 	= 'av_postslider';
			$this->config['tooltip'] 	= __('Display a Slideshow of Post Entries', 'avia_framework' );
			$this->config['drag-level'] = 3;
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
						"name" 	=> __("Columns", 'avia_framework' ),
						"desc" 	=> __("How many columns should be displayed?", 'avia_framework' ),
						"id" 	=> "columns",
						"type" 	=> "select",
						"std" 	=> "3",
						"subtype" => array(	__('1 Columns', 'avia_framework' )=>'1',
											__('2 Columns', 'avia_framework' )=>'2',
											__('3 Columns', 'avia_framework' )=>'3',
											__('4 Columns', 'avia_framework' )=>'4',
											__('5 Columns', 'avia_framework' )=>'5',
											)),
				array(
						"name" 	=> __("Entry Number", 'avia_framework' ),
						"desc" 	=> __("How many items should be displayed?", 'avia_framework' ),
						"id" 	=> "items",
						"type" 	=> "select",
						"std" 	=> "9",
						"subtype" => AviaHtmlHelper::number_array(1,100,1, array('All'=>'-1'))),

                array(
                    "name" 	=> __("Offset Number", 'avia_framework' ),
                    "desc" 	=> __("The offset determines where the query begins pulling posts. Useful if you want to remove a certain number of posts because you already query them with another post slider element.", 'avia_framework' ),
                    "id" 	=> "offset",
                    "type" 	=> "select",
                    "std" 	=> "0",
                    "subtype" => AviaHtmlHelper::number_array(1,100,1, array(__('Deactivate offset','avia_framework')=>'0', __('Do not allow duplicate posts on the entire page (set offset automatically)', 'avia_framework' ) =>'no_duplicates'))),

				array(
						"name" 	=> __("Title and Excerpt",'avia_framework' ),
						"desc" 	=> __("Choose if you want to only display the post title or title and excerpt",'avia_framework' ),
						"id" 	=> "contents",
						"type" 	=> "select",
						"std" 	=> "excerpt",
						"subtype" => array(
							__('Title and Excerpt',  'avia_framework' ) =>'excerpt',
							__('Title and Excerpt + Read More Link',  'avia_framework' ) =>'excerpt_read_more',
							__('Only Title',  'avia_framework' ) =>'title',
							__('Only Title + Read More Link',  'avia_framework' ) =>'title_read_more',
							__('Only excerpt',  'avia_framework' ) =>'only_excerpt',
							__('Only excerpt + Read More Link',  'avia_framework' ) =>'only_excerpt_read_more',
							__('No Title and no excerpt',  'avia_framework' ) =>'no')),

				array(
							"name" 	=> __("Preview Image Size", 'avia_framework' ),
							"desc" 	=> __("Set the image size of the preview images", 'avia_framework' ),
							"id" 	=> "preview_mode",
							"type" 	=> "select",
							"std" 	=> "auto",
							"subtype" => array(__('Set the preview image size automatically based on column width','avia_framework' ) =>'auto',__('Choose the preview image size manually (select thumbnail size)','avia_framework' ) =>'custom')),

				array(
							"name" 	=> __("Select custom preview image size", 'avia_framework' ),
							"desc" 	=> __("Choose image size for Preview Image", 'avia_framework' ),
							"id" 	=> "image_size",
							"type" 	=> "select",
							"required" 	=> array('preview_mode','equals','custom'),
							"std" 	=> "portfolio",
							"subtype" =>  AviaHelper::get_registered_image_sizes(array('logo'))
							),
				
				/*
array(
							"name" 	=> __("Post Slider Transition", 'avia_framework' ),
							"desc" 	=> __("Choose the transition for your Post Slider.", 'avia_framework' ),
							"id" 	=> "animation",
							"type" 	=> "select",
							"std" 	=> "fade",
							"subtype" => array(__('Slide','avia_framework' ) =>'slide',__('Fade','avia_framework' ) =>'fade'),
							),
*/
				
				
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
			$screen_sizes = AviaHelper::av_mobile_sizes($atts);
			
			if(isset($atts['link']))
			{
				$atts['link'] = explode(',', $atts['link'], 2 );
				$atts['taxonomy'] = $atts['link'][0];

				if(isset($atts['link'][1]))
				{
					$atts['categories'] = $atts['link'][1];
				}
			}

			$atts['class'] = $meta['el_class'];
			$atts = array_merge($atts, $screen_sizes);
			
			$slider = new avia_post_slider($atts);
			$slider->query_entries();
			return $slider->html();
		}

	}
}




if ( !class_exists( 'avia_post_slider' ) )
{
	class avia_post_slider
	{	
		static  $slide = 0;
		protected $atts;
		protected $entries;

		function __construct($atts = array())
		{
			$this->atts = shortcode_atts(array(	'type'		=> 'slider', // can also be used as grid
												'style'		=> '', //no_margin
										 		'columns' 	=> '4',
		                                 		'items' 	=> '16',
		                                 		'taxonomy'  => 'category',
												'wc_prod_visible'	=>	'',
												'prod_order_by'		=>	'',
												'prod_order'		=>	'',
		                                 		'post_type'=> get_post_types(),
		                                 		'contents' 	=> 'excerpt',
		                                 		'preview_mode' => 'auto',
												'image_size' => 'portfolio',
		                                 		'autoplay'  => 'no',
												'animation' => 'fade',
												'paginate'	=> 'no',
                                                'use_main_query_pagination' => 'no',
												'interval'  => 5,
												'class'		=> '',
		                                 		'categories'=> array(),
		                                 		'custom_query'=> array(),
                                                'offset' => 0,
                                                'custom_markup' => '',
                                                'av_display_classes' => ''
		                                 		), $atts, 'av_postslider');
		                                 		
		                    
		}

		public function html()
		{
			global $avia_config;

			$output = "";

			if(empty($this->entries) || empty($this->entries->posts)) return $output;

			avia_post_slider::$slide ++;
			extract($this->atts);

			if($preview_mode == 'auto') $image_size = 'portfolio';
			$extraClass 		= 'first';
			$grid 				= 'one_third';
			$post_loop_count 	= 1;
			$loop_counter		= 1;
			$autoplay 			= $autoplay == "no" ? false : true;
			$total				= $columns % 2 ? "odd" : "even";
			$blogstyle 			= function_exists('avia_get_option') ? avia_get_option('blog_global_style','') : "";
			$excerpt_length 	= 60;
			
			
			if($blogstyle !== "")
			{
				$excerpt_length = 240;
			}
			
			switch($columns)
			{
				case "1": $grid = 'av_fullwidth';  if($preview_mode == 'auto') $image_size = 'large'; break;
				case "2": $grid = 'av_one_half';   break;
				case "3": $grid = 'av_one_third';  break;
				case "4": $grid = 'av_one_fourth'; if($preview_mode == 'auto') $image_size = 'portfolio_small'; break;
				case "5": $grid = 'av_one_fifth';  if($preview_mode == 'auto') $image_size = 'portfolio_small'; break;
			}


			$data = AviaHelper::create_data_string(array('autoplay'=>$autoplay, 'interval'=>$interval, 'animation' => $animation, 'show_slide_delay'=>90));

			$thumb_fallback = "";
            $markup = avia_markup_helper(array('context' => 'blog','echo'=>false, 'custom_markup'=>$custom_markup));
			$output .= "<div {$data} class='avia-content-slider avia-content-{$type}-active avia-content-slider".avia_post_slider::$slide." avia-content-slider-{$total} {$class} {$av_display_classes}' $markup>";
			$output .= 		"<div class='avia-content-slider-inner'>";

				foreach ($this->entries->posts as $entry)
				{
					$the_id 	= $entry->ID;
					$parity		= $loop_counter % 2 ? 'odd' : 'even';
					$last       = $this->entries->post_count == $post_loop_count ? " post-entry-last " : "";
					$post_class = "post-entry post-entry-{$the_id} slide-entry-overview slide-loop-{$post_loop_count} slide-parity-{$parity} {$last}";
					$link		= get_post_meta( $the_id ,'_portfolio_custom_link', true ) != "" ? get_post_meta( $the_id ,'_portfolio_custom_link_url', true ) : get_permalink( $the_id );
					$excerpt	= "";
					$title  	= '';
					$show_meta  = !is_post_type_hierarchical($entry->post_type);
					$commentCount = get_comments_number($the_id);
					$thumbnail  = get_the_post_thumbnail( $the_id, $image_size );
					$format 	= get_post_format( $the_id );
					if(empty($format)) $format = "standard";

					if($thumbnail)
					{
						$thumb_fallback = $thumbnail;
						$thumb_class	= "real-thumbnail";
					}
					else
					{
						$thumbnail = "<span class=' fallback-post-type-icon' ".av_icon_string($format)."></span><span class='slider-fallback-image'>{{thumbnail}}</span>";
						$thumb_class	= "fake-thumbnail";
					}


					$permalink = '<div class="read-more-link"><a href="'.get_permalink($the_id).'" class="more-link">'.__('Read more','avia_framework').'<span class="more-link-arrow"></span></a></div>';
					$prepare_excerpt = !empty($entry->post_excerpt) ? $entry->post_excerpt : avia_backend_truncate($entry->post_content, apply_filters( 'avf_postgrid_excerpt_length' , $excerpt_length) , apply_filters( 'avf_postgrid_excerpt_delimiter' , " "), "…", true, '');

		                  	if($format == 'link')
		                   	{
			                        $current_post = array();
			                        $current_post['content'] = $entry->post_content;
			                        $current_post['title'] =  $entry->post_title;
			                        
			                        if(function_exists('avia_link_content_filter'))
			                        {
			                            $current_post = avia_link_content_filter($current_post);
			                        }
			
			                        $link = $current_post['url'];
		                    	}
		                    
                    
					switch($contents)
					{
						case "excerpt":
								$excerpt = $prepare_excerpt;
								$title = $entry->post_title;
								break;
						case "excerpt_read_more":
								$excerpt = $prepare_excerpt;
								$excerpt .= $permalink;
								$title = $entry->post_title;
								break;
						case "title":
								$excerpt = '';
								$title = $entry->post_title;
								break;
						case "title_read_more":
								$excerpt = $permalink;
								$title = $entry->post_title;
								break;
						case "only_excerpt":
								$excerpt = $prepare_excerpt;
								$title = '';
								break;
						case "only_excerpt_read_more":
								$excerpt = $prepare_excerpt;
								$excerpt .= $permalink;
								$title = '';
								break;
						case "no":
								$excerpt = '';
								$title = '';
								break;
					}
					
					$title = apply_filters( 'avf_postslider_title', $title, $entry );
					
					if($loop_counter == 1) $output .= "<div class='slide-entry-wrap'>";
					
					$post_format = get_post_format($the_id) ? get_post_format($the_id) : 'standard';
					
                    $markup = avia_markup_helper(array('context' => 'entry','echo'=>false, 'id'=>$the_id, 'custom_markup'=>$custom_markup));
					$output .= "<article class='slide-entry flex_column {$style} {$post_class} {$grid} {$extraClass} {$thumb_class}' $markup>";
					$output .= $thumbnail ? "<a href='{$link}' data-rel='slide-".avia_post_slider::$slide."' class='slide-image' title=''>{$thumbnail}</a>" : "";
					
					if($post_format == "audio")
					{	
						$current_post = array();
			            $current_post['content'] = $entry->post_content;
			            $current_post['title'] =  $entry->post_title;
						
						$current_post = apply_filters( 'post-format-'.$post_format, $current_post );
						
						if(!empty( $current_post['before_content'] )) $output .= '<div class="big-preview single-big audio-preview">'.$current_post['before_content'].'</div>';
					}
					
					$output .= "<div class='slide-content'>";

                    $markup = avia_markup_helper(array('context' => 'entry_title','echo'=>false, 'id'=>$the_id, 'custom_markup'=>$custom_markup));
                    $output .= '<header class="entry-content-header">';
                    $meta_out = "";
                    
                    if (!empty($title))
                    {
	                    if($show_meta)
	                    {
		                    $taxonomies  = get_object_taxonomies(get_post_type($the_id));
			                $cats = '';
			                $excluded_taxonomies = array_merge( get_taxonomies( array( 'public' => false ) ), array('post_tag','post_format') );
							$excluded_taxonomies = apply_filters('avf_exclude_taxonomies', $excluded_taxonomies, get_post_type($the_id), $the_id);
			
			                if(!empty($taxonomies))
			                {
			                    foreach($taxonomies as $taxonomy)
			                    {
			                        if(!in_array($taxonomy, $excluded_taxonomies))
			                        {
			                            $cats .= get_the_term_list($the_id, $taxonomy, '', ', ','').' ';
			                        }
			                    }
			                }
			                
			                if(!empty($cats))
		                    {
		                        $meta_out .= '<span class="blog-categories minor-meta">';
		                        $meta_out .= $cats;
		                        $meta_out .= '</span>';
		                    }
	                    }
						
						/**
						 * Allow to change default output of categories - by default supressed for setting Default(Business) blog style
						 * 
						 * @since 4.0.6
						 * @param string $blogstyle						'' | 'elegant-blog' | 'elegant-blog modern-blog'
						 * @param avia_post_slider $this
						 * @return string								'show_elegant' | 'show_business' | 'use_theme_default' | 'no_show_cats' 
						 */
						$show_cats = apply_filters( 'avf_postslider_show_catergories', 'use_theme_default', $blogstyle, $this );
	                    
						switch( $show_cats )
						{
							case 'no_show_cats':
								$new_blogstyle = '';
								break;
							case 'show_elegant':
								$new_blogstyle = 'elegant-blog';
								break;
							case 'show_business':
								$new_blogstyle = 'elegant-blog modern-blog';
								break;
							case 'use_theme_default':
							default:
								$new_blogstyle = $blogstyle;
								break;
						}
						
							//	elegant style
	                    if( ( strpos( $new_blogstyle, 'modern-blog' ) === false ) && ( $new_blogstyle != "" ) )
						{
							$output .= $meta_out;
						}
						
                    	$output .=  "<h3 class='slide-entry-title entry-title' $markup><a href='{$link}' title='".esc_attr(strip_tags($title))."'>".$title."</a></h3>";
                    	
							//	modern business style
                    	if( ( strpos( $new_blogstyle, 'modern-blog' ) !== false ) && ( $new_blogstyle != "" ) ) 
						{
							$output .= $meta_out;
						}
						
                    	$output .= '<span class="av-vertical-delimiter"></span>';
                    }
                    
                    $output .= '</header>';

                    if($show_meta && !empty($excerpt))
					{
						$meta  = "<div class='slide-meta'>";
						if ( $commentCount != "0" || comments_open($the_id) && $entry->post_type != 'portfolio')
						{
							$link_add = $commentCount === "0" ? "#respond" : "#comments";
							$text_add = $commentCount === "1" ? __('Comment', 'avia_framework' ) : __('Comments', 'avia_framework' );

							$meta .= "<div class='slide-meta-comments'><a href='{$link}{$link_add}'>{$commentCount} {$text_add}</a></div><div class='slide-meta-del'>/</div>";
						}
                        $markup = avia_markup_helper(array('context' => 'entry_time','echo'=>false, 'id'=>$the_id, 'custom_markup'=>$custom_markup));
						$meta .= "<time class='slide-meta-time updated' $markup>" .get_the_time(get_option('date_format'), $the_id)."</time>";
						$meta .= "</div>";
						
						if( strpos($blogstyle, 'elegant-blog') === false )
						{
							$output .= $meta;
							$meta = "";
						}
					}
                    $markup = avia_markup_helper(array('context' => 'entry_content','echo'=>false, 'id'=>$the_id, 'custom_markup'=>$custom_markup));
					$excerpt = apply_filters( 'avf_post_slider_entry_excerpt', $excerpt, $prepare_excerpt, $permalink, $entry );
					$output .= !empty($excerpt) ? "<div class='slide-entry-excerpt entry-content' $markup>".$excerpt."</div>" : "";

                    $output .= "</div>";
                    $output .= '<footer class="entry-footer">';
                    if( !empty($meta) ) $output .= $meta;
                    $output .= '</footer>';
                    
                    $output .= av_blog_entry_markup_helper( $the_id );
                    
					$output .= "</article>";

					$loop_counter ++;
					$post_loop_count ++;
					$extraClass = "";

					if($loop_counter > $columns)
					{
						$loop_counter = 1;
						$extraClass = 'first';
					}

					if($loop_counter == 1 || !empty($last))
					{
						$output .="</div>";
					}
				}

			$output .= 		"</div>";

			if($post_loop_count -1 > $columns && $type == 'slider')
			{
				$output .= $this->slide_navigation_arrows();
			}
			
			global $wp_query;
            if($use_main_query_pagination == 'yes' && $paginate == "yes")
            {
                $avia_pagination = avia_pagination($wp_query->max_num_pages, 'nav');
            }
            else if($paginate == "yes")
            {
                $avia_pagination = avia_pagination($this->entries, 'nav');
            }

            if(!empty($avia_pagination)) $output .= "<div class='pagination-wrap pagination-slider'>{$avia_pagination}</div>";


            $output .= "</div>";

			$output = str_replace('{{thumbnail}}', $thumb_fallback, $output);

			wp_reset_query();
			return $output;
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
                    $params['offset'] = false;
                    $no_duplicates = true;
                }
                
                
				//wordpress 4.4 offset fix
				if( $params['offset'] == 0 )
				{
					$params['offset'] = false;
				}
				else
				{	
					//if the offset is set the paged param is ignored. therefore we need to factor in the page number
					$params['offset'] = $params['offset'] + ( ($page -1 ) * $params['items']);
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


			$query = apply_filters('avia_post_slide_query', $query, $params);

			@$this->entries = new WP_Query( $query ); //@ is used to prevent errors caused by wpml

		    // store the queried post ids in
            if( $this->entries->have_posts() )
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
