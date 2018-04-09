<?php
/**
 * Blog Posts
 * 
 * Displays Posts from your Blog
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_blog' ) )
{
	class avia_sc_blog extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'yes';
				
				$this->config['name']		= __('Blog Posts', 'avia_framework' );
				$this->config['tab']		= __('Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-blog.png";
				$this->config['order']		= 40;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_blog';
				$this->config['tooltip'] 	= __('Displays Posts from your Blog', 'avia_framework' );
				$this->config['preview'] 	= false;
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
					
                    array(	"name" 		=> __("Do you want to display blog posts?", 'avia_framework' ),
                        "desc" 		=> __("Do you want to display blog posts or entries from a custom taxonomy?", 'avia_framework' ),
                        "id" 		=> "blog_type",
                        "type" 		=> "select",
                        "std" 	=> "posts",
                        "subtype" => array( __('Display blog posts', 'avia_framework') =>'posts',
                                            __('Display entries from a custom taxonomy', 'avia_framework') =>'taxonomy')),
											
											

					array(	"name" 		=> __("Which categories should be used for the blog?", 'avia_framework' ),
							"desc" 		=> __("You can select multiple categories here. The Page will then show posts from only those categories.", 'avia_framework' ),
				            "id" 		=> "categories",
				            "type" 		=> "select",
	        				"multiple"	=> 6,
                            "required" 	=> array('blog_type', 'equals', 'posts'),
				            "subtype" 	=> "cat"),

                    array(
                        "name" 	=> __("Which Entries?", 'avia_framework' ),
                        "desc" 	=> __("Select which entries should be displayed by selecting a taxonomy", 'avia_framework' ),
                        "id" 	=> "link",
                        "fetchTMPL"	=> true,
                        "type" 	=> "linkpicker",
                        "subtype"  => array( __('Display Entries from:',  'avia_framework' )=>'taxonomy'),
                        "multiple"	=> 6,
                        "required" 	=> array('blog_type', 'equals', 'taxonomy'),
                        "std" 	=> "category"
                    ),

					array(
							"name" 	=> __("Blog Style", 'avia_framework' ),
							"desc" 	=> __("Choose the default blog layout here.", 'avia_framework' ),
							"id" 	=> "blog_style",
							"type" 	=> "select",
							"std" 	=> "single-big",
							"no_first"=>true,
							"subtype" => array( __('Multi Author Blog (displays Gravatar of the article author beside the entry and feature images above)', 'avia_framework') =>'multi-big',
												__('Single Author, small preview Pic (no author picture is displayed, feature image is small)', 'avia_framework') =>'single-small',
												__('Single Author, big preview Pic (no author picture is displayed, feature image is big)', 'avia_framework') =>'single-big',
												__('Grid Layout', 'avia_framework') =>'blog-grid',
												/* 'no sidebar'=>'fullsize' */
										)),
										
					array(
							"name" 	=> __("Blog Grid Columns", 'avia_framework' ),
							"desc" 	=> __("How many columns do you want to display?", 'avia_framework' ),
							"id" 	=> "columns",
							"type" 	=> "select",
							"std" 	=> "3",
							"required" 	=> array('blog_style', 'equals', 'blog-grid'),
							"subtype" => AviaHtmlHelper::number_array(1,5,1)),

					array(
							"name" 	=> __("Define Blog Grid layout", 'avia_framework' ),
							"desc" 	=> __("Do you want to display a read more link?", 'avia_framework' ),
							"id" 	=> "contents",
							"type" 	=> "select",
							"std" 	=> "excerpt",
							"required" 	=> array('blog_style', 'equals', 'blog-grid'),
							"subtype" =>   array(
                                    __('Title and Excerpt',  'avia_framework' ) =>'excerpt',
                                    __('Title and Excerpt + Read More Link',  'avia_framework' ) =>'excerpt_read_more',
                                    __('Only Title',  'avia_framework' ) =>'title',
                                    __('Only Title + Read More Link',  'avia_framework' ) =>'title_read_more',
                                    __('Only excerpt',  'avia_framework' ) =>'only_excerpt',
                                    __('Only excerpt + Read More Link',  'avia_framework' ) =>'only_excerpt_read_more',
                                    __('No Title and no excerpt',  'avia_framework' ) =>'no')
                            ),


					array(
							"name" 	=> __("Blog Content length", 'avia_framework' ),
							"desc" 	=> __("Should the full entry be displayed or just a small excerpt?", 'avia_framework' ),
							"id" 	=> "content_length",
							"type" 	=> "select",
							"std" 	=> "content",
							"required" 	=> array('blog_style', 'not', 'blog-grid'),
							"subtype" => array(
								__('Full Content',  'avia_framework' ) =>'content',
								__('Excerpt',  'avia_framework' ) =>'excerpt',
                                __('Excerpt With Read More Link',  'avia_framework' ) =>'excerpt_read_more')),

					array(
							"name" 	=> __("Preview Image Size", 'avia_framework' ),
							"desc" 	=> __("Set the image size of the preview images", 'avia_framework' ),
							"id" 	=> "preview_mode",
							"type" 	=> "select",
							"std" 	=> "auto",
							"subtype" => array(__('Set the preview image size automatically based on column or layout width','avia_framework' ) =>'auto',__('Choose the preview image size manually (select thumbnail size)','avia_framework' ) =>'custom')),

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
							"name" 	=> __("Post Number", 'avia_framework' ),
							"desc" 	=> __("How many items should be displayed per page?", 'avia_framework' ),
							"id" 	=> "items",
							"type" 	=> "select",
							"std" 	=> "3",
							"subtype" => AviaHtmlHelper::number_array(1,100,1, array('All'=>'-1'))),

                    array(
                        "name" 	=> __("Offset Number", 'avia_framework' ),
                        "desc" 	=> __("The offset determines where the query begins pulling posts. Useful if you want to remove a certain number of posts because you already query them with another blog or magazine element.", 'avia_framework' ),
                        "id" 	=> "offset",
                        "type" 	=> "select",
                        "std" 	=> "0",
                        "subtype" => AviaHtmlHelper::number_array(1,100,1, array(__('Deactivate offset','avia_framework')=>'0', __('Do not allow duplicate posts on the entire page (set offset automatically)', 'avia_framework' ) =>'no_duplicates'))),


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
							"name" 	=> __("Conditional display", 'avia_framework' ),
							"desc" 	=> __("When should the element be displayed?", 'avia_framework' ),
							"id" 	=> "conditional",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array(
								__('Always display the element',  'avia_framework' ) =>'',
								__('Remove element if the user navigated away from page 1 to page 2,3,4 etc ',  'avia_framework' ) =>'is_subpage')),
					
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
                        "required" 	=> array('blog_type', 'equals', 'taxonomy'),
                        "multiple"	=> 6,
                        "std" 	=> "",
                        "subtype" => AviaHtmlHelper::get_registered_post_type_array()
                    );

                    array_splice($this->elements, 4, 0, array($element));
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
				global $avia_config, $more;
				
				$screen_sizes = AviaHelper::av_mobile_sizes($atts);
				extract($screen_sizes); //return $av_font_classes, $av_title_font_classes and $av_display_classes 
				
				if(empty($atts['categories'])) $atts['categories'] = "";
                if(isset($atts['link']) && isset($atts['blog_type']) && $atts['blog_type'] == 'taxonomy')
                {
                    $atts['link'] = explode(',', $atts['link'], 2 );
                    $atts['taxonomy'] = $atts['link'][0];

                    if(!empty($atts['link'][1]))
                    {
                        $atts['categories'] = $atts['link'][1];
                    }
                    else if(!empty($atts['taxonomy']))
                    {
                        $taxonomy_terms_obj = get_terms($atts['taxonomy']);
                        foreach ($taxonomy_terms_obj as $taxonomy_term)
                        {
                            $atts['categories'] .= $taxonomy_term->term_id . ',';
                        }
                    }
                }

				$atts = shortcode_atts(array('blog_style'	=> '',
											 'columns' 		=> 3,
                                             'blog_type'    => 'posts',
			                                 'items' 		=> '16',
			                                 'paginate' 	=> 'yes',
			                                 'categories' 	=> '',
			                                 'preview_mode' => 'auto',
											 'image_size' => 'portfolio',
			                                 'taxonomy'		=> 'category',
			                                 'post_type'=> get_post_types(),
                                             'contents'     => 'excerpt',
			                                 'content_length' => 'content',
                                             'offset' => '0',
                                             'conditional' => ''
			                                 ), $atts, $this->config['shortcode']);
				
				$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
				if(!$page) $page = 1;
				
				/**
				 * Skip blog queries, if element will not be displayed
				 */
				if( $atts['conditional'] == 'is_subpage' && $page != 1 ) 
				{
					return '';
				}
				
				if($atts['blog_style'] == "blog-grid")
				{
					$atts['class'] = $meta['el_class'];
					$atts['type']  = 'grid';
					$atts = array_merge($atts, $screen_sizes);
					//using the post slider with inactive js will result in displaying a nice post grid
					$slider = new avia_post_slider($atts);
					$slider->query_entries();
			
					return $slider->html();
				}

				$this->query_entries($atts);

				$avia_config['blog_style'] = $atts['blog_style'];
				$avia_config['preview_mode'] = $atts['preview_mode'];
				$avia_config['image_size'] = $atts['image_size'];
				$avia_config['blog_content'] = $atts['content_length'];
				$avia_config['remove_pagination'] = $atts['paginate'] === "yes" ? false :true;
				
				/**
				 * Force supress of pagination if element will be hidden on foillowing pages
				 */
				if( $atts['conditional'] == 'is_subpage' && $page == 1 )
				{
					$avia_config['remove_pagination'] = true;
				}

				$more = 0;
				ob_start(); //start buffering the output instead of echoing it
				get_template_part( 'includes/loop', 'index' );
				$output = ob_get_clean();
				wp_reset_query();
				avia_set_layout_array();

				if($output)
				{
					$extraclass = function_exists('avia_blog_class_string') ? avia_blog_class_string() : "";
                    $markup = avia_markup_helper(array('context' => 'blog','echo'=>false, 'custom_markup'=>$meta['custom_markup']));
					$output = "<div class='av-alb-blogposts template-blog {$extraclass} {$av_display_classes}' {$markup}>{$output}</div>";
				}

				return $output;
			}


			function query_entries($params)
			{
				global $avia_config;
				$query = array();

				if(!empty($params['categories']) && is_string($params['categories']))
				{
					//get the categories
					$terms 	= explode(',', $params['categories']);
				}

				$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
				if(!$page || $params['paginate'] == 'no') $page = 1;

                if($params['offset'] == 'no_duplicates')
                {
                    $params['offset'] = 0;
                    $no_duplicates = true;
                }

                if(empty($params['blog_type']) || $params['blog_type'] == 'posts') $params['post_type'] = 'post';
                if(empty($params['post_type'])) $params['post_type'] = get_post_types();
                if(is_string($params['post_type'])) $params['post_type'] = explode(',', $params['post_type']);
				
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
		
				//if we find categories perform complex query, otherwise simple one
				if(isset($terms[0]) && !empty($terms[0]) && !is_null($terms[0]) && $terms[0] != "null" && !empty($params['taxonomy']))
				{
					$query = array(	'paged' 	=> $page,
									'posts_per_page' => $params['items'],
                                    'offset' => $params['offset'],
                                    'post__not_in' => (!empty($no_duplicates)) ? $avia_config['posts_on_current_page'] : array(),
                                    'post_type' => $params['post_type'],
									'tax_query' => array( 	array( 	'taxonomy' 	=> $params['taxonomy'],
																	'field' 	=> 'id',
																	'terms' 	=> $terms,
																	'operator' 	=> 'IN'))
																	);
				}
                else
				{
					$query = array(	'paged'=> $page,
                                    'posts_per_page' => $params['items'],
                                    'offset' => $params['offset'],
                                    'post__not_in' => (!empty($no_duplicates)) ? $avia_config['posts_on_current_page'] : array(),
                                    'post_type' => $params['post_type']);
				}

				$query = apply_filters('avia_blog_post_query', $query, $params);

				$results = query_posts($query);

                // store the queried post ids in
                if( have_posts() )
                {
                    while( have_posts() )
                    {
                        the_post();
                        $avia_config['posts_on_current_page'][] = get_the_ID();
                    }
                }
			}




	}
}