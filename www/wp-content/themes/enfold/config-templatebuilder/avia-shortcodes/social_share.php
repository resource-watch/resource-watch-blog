<?php
/**
 * Social Share Buttons
 * 
 * Shortcode creates one or more social share buttons
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_social_share' ) )
{
	class avia_sc_social_share extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{

				$this->config['self_closing']	=	'yes';
				
				$this->config['name']			= __('Social Share Buttons', 'avia_framework' );
				$this->config['tab']			= __('Content Elements', 'avia_framework' );
				$this->config['icon']			= AviaBuilder::$path['imagesURL']."sc-social.png";
				$this->config['order']			= 7;
				$this->config['target']			= 'avia-target-insert';
				$this->config['shortcode'] 		= 'av_social_share';
				$this->config['tooltip'] 	    = __('Creates one or more social share buttons ', 'avia_framework' );
				$this->config['preview'] 		= true;
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
		                    "name"  => __("Small title", 'avia_framework' ),
		                    "desc"  => __("A small title above the buttons.", 'avia_framework' ),
		                    "id"    => "title",
		                    "type" 	=> "input",
							"std" 	=> __("Share this entry",'avia_framework')
					),
					
					array(
							"name" 	=> __("Style", 'avia_framework' ),
							"desc" 	=> __("How to display the social sharing bar?", 'avia_framework' ),
							"id" 	=> "style",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array( __('Default with border', 'avia_framework' )=>'',
												__('Minimal', 'avia_framework' )=>'minimal'),
					),
					
					
					array(
							"name" 	=> __("Social Buttons", 'avia_framework' ),
							"desc" 	=> __("Which Social Buttons do you want to display? Defaults are set in ", 'avia_framework' ).
							"<a href='".admin_url('admin.php?page=avia#goto_blog')."'>".__('Blog Layout','avia_framework').
							"</a>",
							"id" 	=> "buttons",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array( __('Use Defaults that are also used for your blog', 'avia_framework' )=>'',
												__('Use a custom set', 'avia_framework' )=>'custom'),
					),
										
					
					array(	
							"name" 	=> __("Facebook link", 'avia_framework'),
							"desc" 	=> __("Check to display", 'avia_framework'),
							"id" 	=> "share_facebook",
							"std" 	=> "",
							"container_class" => 'av_third av_third_first',
							"required" => array("buttons",'equals','custom'),
							"type" 	=> "checkbox"),
					
					array(	
							"name" 	=> __("Twitter link", 'avia_framework'),
							"desc" 	=> __("Check to display", 'avia_framework'),
							"id" 	=> "share_twitter",
							"std" 	=> "",
							"container_class" => 'av_third ',
							"required" => array("buttons",'equals','custom'),
							"type" 	=> "checkbox"),
							
					array(	
							"name" 	=> __("Pinterest link", 'avia_framework'),
							"desc" 	=> __("Check to display", 'avia_framework'),
							"id" 	=> "share_pinterest",
							"std" 	=> "",
							"container_class" => 'av_third ',
							"required" => array("buttons",'equals','custom'),
							"type" 	=> "checkbox"),
							
					array(	
							"name" 	=> __("Google Plus link", 'avia_framework'),
							"desc" 	=> __("Check to display", 'avia_framework'),
							"id" 	=> "share_gplus",
							"std" 	=> "",
							"container_class" => 'av_third av_third_first',
							"required" => array("buttons",'equals','custom'),
							"type" 	=> "checkbox"),
					
					array(	
							"name" 	=> __("Reddit link", 'avia_framework'),
							"desc" 	=> __("Check to display", 'avia_framework'),
							"id" 	=> "share_reddit",
							"std" 	=> "",
							"container_class" => 'av_third ',
							"required" => array("buttons",'equals','custom'),
							"type" 	=> "checkbox"),
							
					array(	
							"name" 	=> __("Linkedin link", 'avia_framework'),
							"desc" 	=> __("Check to display", 'avia_framework'),
							"id" 	=> "share_linkedin",
							"std" 	=> "",
							"container_class" => 'av_third ',
							"required" => array("buttons",'equals','custom'),
							"type" 	=> "checkbox"),
							
					array(	
							"name" 	=> __("Tumblr link", 'avia_framework'),
							"desc" 	=> __("Check to display", 'avia_framework'),
							"id" 	=> "share_tumblr",
							"std" 	=> "",
							"container_class" => 'av_third av_third_first',
							"required" => array("buttons",'equals','custom'),
							"type" 	=> "checkbox"),
					
					array(	
							"name" 	=> __("VK link", 'avia_framework'),
							"desc" 	=> __("Check to display", 'avia_framework'),
							"id" 	=> "share_vk",
							"std" 	=> "",
							"container_class" => 'av_third ',
							"required" => array("buttons",'equals','custom'),
							"type" 	=> "checkbox"),
							
					array(	
							"name" 	=> __("Email link", 'avia_framework'),
							"desc" 	=> __("Check to display", 'avia_framework'),
							"id" 	=> "share_mail",
							"std" 	=> "",
							"container_class" => 'av_third ',
							"required" => array("buttons",'equals','custom'),
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
				
				'buttons' => "",
				'share_facebook' => '',
				'share_twitter' => '',
				'share_vk' => '',
				'share_tumblr' => '',
				'share_linkedin' => '',
				'share_pinterest' => '',
				'share_mail' => '',
				'share_gplus' => '',
				'share_reddit' => '',
				'title' => '',
				'style' => ''
				
				), $atts, $this->config['shortcode']);
				
				extract($atts);
				$custom_class 	= !empty($meta['custom_class']) ? $meta['custom_class'] : "";
				$custom_class  .= $meta['el_class'];
				if($style == 'minimal') $custom_class .= " av-social-sharing-box-minimal";
				
                $output 		= '';
                $args			= array();
				$options 		= false;
				$echo 			= false;
				
				if($buttons == "custom")
				{
					foreach($atts as &$att)
					{
						if(empty($att)) $att = "disabled";
					}
					
					$options = $atts;
				}
				
				
                $output .= "<div class='av-social-sharing-box {$custom_class} {$av_display_classes}'>";
                $output .= avia_social_share_links($args, $options, $title, $echo);
                $output .= "</div>";

                return $output;
			}

	}
}
