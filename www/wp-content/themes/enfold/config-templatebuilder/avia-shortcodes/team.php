<?php
/**
 * Team Member
 * 
 * Display a team members image with additional information
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_team' ) )
{
	class avia_sc_team extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'no';
				
				$this->config['name']		= __('Team Member', 'avia_framework' );
				$this->config['tab']		= __('Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-team.png";
				$this->config['order']		= 35;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_team_member';
				$this->config['shortcode_nested'] = array('av_team_icon');
				$this->config['tooltip'] 	= __('Display a team members image with additional information', 'avia_framework' );
				$this->config['preview'] 		= true;
			}


			function extra_assets()
			{
				if(is_admin())
				{
					$ver = AviaBuilder::VERSION;
					wp_enqueue_script('avia_tab_toggle_js' , AviaBuilder::$path['assetsURL'].'js/avia-tab-toggle.js' , array('avia_modal_js'), $ver, TRUE );
				}
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
									"name" 	=> __("Team Member Name", 'avia_framework' ),
									"desc" 	=> __("Name of the person", 'avia_framework' ) ,
									"id" 	=> "name",
									"std" 	=> "John Doe",
									"type" 	=> "input"),

						array(
									"name" 	=> __("Team Member Job title", 'avia_framework' ),
									"desc" 	=> __("Job title of the person.", 'avia_framework' ) ,
									"id" 	=> "job",
									"std" 	=> "",
									"type" 	=> "input"),

						array(
							"name" 	=> __("Team Member Image",'avia_framework' ),
							"desc" 	=> __("Either upload a new, or choose an existing image from your media library",'avia_framework' ),
							"id" 	=> "src",
							"type" 	=> "image",
							"title" => __("Insert Image",'avia_framework' ),
							"button" => __("Insert",'avia_framework' ),
							"std" 	=> ""),

						array(
							"name" 	=> __("Image size",'avia_framework' ),
							"desc" 	=> __("Select the size of the team member image", 'avia_framework' ),
							"id" 	=> "image_width",
							"type" 	=> "select",
							"std" 	=> "",
							"required" => array( 'src', 'not', '' ),
							"subtype" => array(
										__( 'Fit into container','avia_framework' ) => '',
										__( 'Use original size (or fit into container if too large)','avia_framework' ) => 'av-team-img-original',
									)
								),

						array(
							"name" 	=> __("Team Member Description",'avia_framework' ),
							"desc" 	=> __("Enter a few words that describe the person",'avia_framework' ),
							"id" 	=> "description",
							"type" 	=> "textarea",
							"std" 	=> ""),


						array(
							"name" => __("Add/Edit Social Service or Icon Links", 'avia_framework' ),
							"desc" => __("Below each Team Member you can add Icons that link to destinations like facebook page, twitter account etc.", 'avia_framework' ),
							"type" 			=> "modal_group",
							"id" 			=> "content",
							"modal_title" 	=> __("Edit Icon Link", 'avia_framework' ),
							"std"			=> array(

													),


							'subelements' 	=> array(

									array(
									"name" 	=> __("Hover Text", 'avia_framework' ),
									"desc" 	=> __("Text that appears if you place your mouse above the Icon", 'avia_framework' ) ,
									"id" 	=> "title",
									"std" 	=> "Tab Title",
									"type" 	=> "input"),

									 array(
									"name" 	=> __("Icon Link", 'avia_framework' ),
									"desc" 	=> __("Enter the URL of the Page you want to link to", 'avia_framework' ),
									"id" 	=> "link",
									"type" 	=> "input",
									"std" 	=> "http://"),

									array(
									"name" 	=> __("Open Link in new Window?", 'avia_framework' ),
									"desc" 	=> __("Select here if you want to open the linked page in a new window", 'avia_framework' ),
									"id" 	=> "link_target",
									"type" 	=> "select",
									"std" 	=> "",
									"subtype" => array(
										__('Open in same window',  'avia_framework' ) =>'',
										__('Open in new window',  'avia_framework' ) =>'_blank')),


								array(
										"name" 	=> __("Tab Icon",'avia_framework' ),
										"desc" 	=> __("Select an icon for your tab title below",'avia_framework' ),
										"id" 	=> "icon",
										"type" 	=> "iconfont",
										"std" 	=> "",
										),
									),
								),
								
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
									"name" 	=> __("Custom Title Font Color", 'avia_framework' ),
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
				$templateNAME  	= $this->update_template("name", "{{name}}");
				$templateIMG 	= $this->update_template("src", "<img src='{{src}}' alt=''/>");
				$templateJob 	= $this->update_template("job", "{{job}}");

				$params['innerHtml'] = "";

				if(empty($params['args']['src']))
				{
					$params['innerHtml'].= "<div class='avia_image_container' {$templateIMG}>";
					$params['innerHtml'].= "	<img src='".$this->config['icon']."' title='".$this->config['name']."' alt='' />";
					$params['innerHtml'].= "	<div class='avia-element-label'>".$this->config['name']."</div>";
					$params['innerHtml'].= "</div>";
				}
				else
				{
					$params['innerHtml'].= "<div class='avia_image_container' {$templateIMG}><img src='".$params['args']['src']."' alt='' /></div>";
				}

				$params['innerHtml'].= "<div class='avia-element-name' {$templateNAME} >".html_entity_decode($params['args']['name'])."</div>";
				$params['innerHtml'] .= "	<span class='avia_job_container_inner' {$templateJob} >".html_entity_decode($params['args']['job'])."</span>";

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
				$template  = $this->update_template("title", "{{title}}");

				extract(av_backend_icon($params)); // creates $font and $display_char if the icon was passed as param "icon" and the font as "font" 
				
				$params['innerHtml']  = "";
				$params['innerHtml'] .= "<div class='avia_title_container'>";
				$params['innerHtml'] .= "	<span ".$this->class_by_arguments('font' ,$font).">";
				$params['innerHtml'] .= "		<span data-update_with='icon_fakeArg' class='avia_tab_icon' >{$display_char}</span>";
				$params['innerHtml'] .= "	</span>";
				$params['innerHtml'] .= "	<span class='avia_title_container_inner' {$template} >".$params['args']['title']."</span>";
				$params['innerHtml'] .= "</div>";



				return $params;
			}

			/**
			 * Returns false by default.
			 * Override in a child class if you need to change this behaviour.
			 * 
			 * @since 4.2.1
			 * @param string $shortcode
			 * @return boolean
			 */
			public function is_nested_self_closing( $shortcode )
			{
				if( in_array( $shortcode, $this->config['shortcode_nested'] ) )
				{
					return true;
				}

				return false;
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
				
				$atts =  shortcode_atts(array(	'name' => '',
												'src' => '',
												'image_width' => '',
												'description' => '',
												'job' => '',
												'custom_markup' => '',
												'font_color'=>'', 
												'custom_title'=>'', 
												'custom_content'=>'',
			                                 
			                                 ), $atts, $this->config['shortcode']);
				extract($atts);

				$title_styling 		= "";
				$content_styling 	= "";
				$content_class		= "";
				$title_class		= "";
				
				if($font_color == "custom")
				{
					$title_styling 		.= !empty($custom_title) ? "color:{$custom_title}; " : "";
					$content_styling 	.= !empty($custom_content) ? "color:{$custom_content}; " : "";
					
					if($title_styling) 
					{
						$title_styling = " style='{$title_styling}'" ;
						$title_class = "av_opacity_variation";
					}
					if($content_styling) 
					{
						$content_styling = " style='{$content_styling}'" ;
						$content_class	 = "av_inherit_color";
					}
				}

				$socials = ShortcodeHelper::shortcode2array($content);

				$output  = "";
                $markup = avia_markup_helper(array('context' => 'person','echo'=>false, 'custom_markup'=>$custom_markup));

				$output .= "<section class='avia-team-member {$av_display_classes} ".$meta['el_class']."' $markup>";
				if($src)
				{
					$cls = 'avia_image avia_image_team';
					if( ! empty( $image_width) )
					{
						$cls .= ' ' . $image_width;
					}
					
					$output.= "<div class='team-img-container'>";
                    $markup = avia_markup_helper(array('context' => 'single_image','echo'=>false, 'custom_markup'=>$custom_markup));
					$output.= "<img class='{$cls}' src='".$src."' alt='".esc_attr($name)."' $markup />";


					if(!empty($socials))
					{
						$output .= "<div class='team-social'>";

							$output .= "<div class='team-social-inner'>";

							foreach($socials as $social)
							{
								//set defaults
								$social['attr'] =  shortcode_atts(array('link' => '',  'link_target' => '', 'icon' => '','font'=>'','title' => '' ), $social['attr'], 'av_social');

								//build link for each social item
								$tooltip = $social['attr']['title'] ? 'data-avia-tooltip="'.$social['attr']['title'].'"' : "";
								$target  = $social['attr']['link_target'] ? "target='_blank'" : "";

								//apply special class in case its a link to a known social media service
								$social_class = $this->get_social_class($social['attr']['link']);

                                if(strstr($social['attr']['link'], '@'))
                                {
                                    $markup = avia_markup_helper(array('context' => 'email','echo'=>false, 'custom_markup'=>$custom_markup));
                                }
                                else
                                {
                                    $markup = avia_markup_helper(array('context' => 'url','echo'=>false, 'custom_markup'=>$custom_markup));
                                }
								
								$display_char = av_icon($social['attr']['icon'], $social['attr']['font']);
								
                                $output .= "<span class='hidden av_member_url_markup {$social_class}' $markup>".$social['attr']['link']."</span>";

								$output.= "<a rel='v:url' {$tooltip} {$target} class='{$social_class} avia-team-icon ' href='".$social['attr']['link']."' {$display_char}>";
								$output.= "</a>";
							}

							$output .= "</div>";

						$output .= "</div>";
					}
					$output .= "</div>";

				}

				if($name)
				{
                    $markup = avia_markup_helper(array('context' => 'name','echo'=>false, 'custom_markup'=>$custom_markup));
					$output.= "<h3 class='team-member-name' {$title_styling} {$markup}>{$name}</h3>";
				}

				if($job)
				{
                    $markup = avia_markup_helper(array('context' => 'job','echo'=>false, 'custom_markup'=>$custom_markup));
					$output.= "<div class='team-member-job-title {$title_class}' {$title_styling} {$markup}>{$job}</div>";
				}

				if($description)
				{
                    $markup = avia_markup_helper(array('context' => 'description','echo'=>false, 'custom_markup'=>$custom_markup));
					$output.= "<div class='team-member-description {$content_class}' {$markup} {$content_styling}>".ShortcodeHelper::avia_apply_autop(ShortcodeHelper::avia_remove_autop($description) )."</div>";
				}

                $markup = avia_markup_helper(array('context' => 'affiliation','echo'=>false, 'custom_markup'=>$custom_markup));
				$output .= "<span class='hidden team-member-affiliation' {$markup}>".get_bloginfo('name')."</span>";
				$output .= "</section>";
				return $output;
			}

			function get_social_class($link)
			{
				$class = "";
				$services = array(
					'facebook',
					'youtube',
					'twitter',
					'pinterest',
					'tumblr',
					'flickr',
					'linkedin',
					'dribbble',
					'behance',
					'github',
					'soundcloud',
					'xing',
					'vimeo',
					'plus.google',
					'myspace',
					'forrst',
					'skype',
					'reddit'
				);

				foreach($services as $service)
				{
					if(strpos($link, $service) !== false) $class .= " ".str_replace('.','-',$service);
				}

				return $class;
			}




	}
}
