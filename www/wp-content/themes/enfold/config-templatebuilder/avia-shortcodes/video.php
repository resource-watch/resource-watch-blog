<?php
/**
 * Video
 * 
 * Shortcode which display a video
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_video' ) ) 
{
	class avia_sc_video extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'yes';
				
				$this->config['name']			= __('Video', 'avia_framework' );
				$this->config['tab']			= __('Media Elements', 'avia_framework' );
				$this->config['icon']			= AviaBuilder::$path['imagesURL']."sc-video.png";
				$this->config['order']			= 90;
				$this->config['target']			= 'avia-target-insert';
				$this->config['shortcode'] 		= 'av_video';
				$this->config['modal_data']     = array('modal_class' => 'mediumscreen');
				$this->config['tooltip']        = __('Display a video', 'avia_framework' );
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
							"name" 	=> __("Choose Video",'avia_framework' ),
							"desc" 	=> __("Either upload a new video, choose an existing video from your media library or link to a video by URL",'avia_framework' )."<br/><br/>".
										__("A list of all supported Video Services can be found on",'avia_framework' ).
										" <a target='_blank' href='http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F'>WordPress.org</a><br/><br/>".
										__("Working examples, in case you want to use an external service:",'avia_framework' ). "<br/>".
										"<strong>http://vimeo.com/1084537</strong><br/>".
										"<strong>http://www.youtube.com/watch?v=G0k3kHtyoqc</strong><br/><br/>".
										"<strong>".__("Attention when using self hosted HTML 5 Videos",'avia_framework' ). ":</strong><br/>".
										__("Different Browsers support different file types (mp4, ogv, webm). If you embed a example.mp4 video the video player will automatically check if a example.ogv and example.webm video is available and display those versions in case its possible and necessary",'avia_framework' )."<br/>",
							
							"id" 	=> "src",
							"type" 	=> "video",
							"title" => __("Insert Video",'avia_framework' ),
							"button" => __("Insert",'avia_framework' ),
							"std" 	=> ""),
					array(	
							"name" 	=> __("Video Format", 'avia_framework' ),
							"desc" 	=> __("Choose if you want to display a modern 16:9 or classic 4:3 Video, or use a custom ratio", 'avia_framework' ),
							"id" 	=> "format",
							"type" 	=> "select",
							"std" 	=> "16:9",
							"subtype" => array( 
												__('16:9',  'avia_framework' ) =>'16-9',
												__('4:3', 'avia_framework' ) =>'4-3',
												__('Custom Ratio', 'avia_framework' ) =>'custom',
												)		
							),
							
					array(	
							"name" 	=> __("Video width", 'avia_framework' ),
							"desc" 	=> __("Enter a value for the width", 'avia_framework' ),
							"id" 	=> "width",
							"type" 	=> "input",
							"std" 	=> "16",
							"required" => array('format','equals','custom')
						),	
						
					array(	
							"name" 	=> __("Video height", 'avia_framework' ),
							"desc" 	=> __("Enter a value for the height", 'avia_framework' ),
							"id" 	=> "height",
							"type" 	=> "input",
							"std" 	=> "9",
							"required" => array('format','equals','custom')
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

                    if(current_theme_supports('avia_template_builder_custom_html5_video_urls'))
                    {
                        for ($i = 2; $i > 0; $i--)
                        {
                            $element = $this->elements[2];
                            $element['id'] = 'src_'.$i;
                            $element['name'] =  __("Choose Another Video (HTML5 Only)",'avia_framework');
                            $element['desc'] = __("Either upload a new video, choose an existing video from your media library or link to a video by URL.
                                                   If you want to make sure that all browser can display your video upload a mp4, an ogv and a webm version of your video.",'avia_framework' );

                            array_splice($this->elements, 3, 0, array($element));
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
				$template = $this->update_template("src", "URL: {{src}}");
				
				$params['content'] = NULL;
				$params['innerHtml'] = "<img src='".$this->config['icon']."' title='".$this->config['name']."' />";
				$params['innerHtml'].= "<div class='avia-element-label'>".$this->config['name']."</div>";
				$params['innerHtml'].= "<div class='avia-element-url' {$template}> URL: ".$params['args']['src']."</div>";
				$params['class'] = "avia-video-element";

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
				
				extract(shortcode_atts(array('src' => '', 'src_1' => '', 'src_2' => '', 'autoplay' => '', 'format' => '', 'height'=>'9', 'width'=>'16'), $atts, $this->config['shortcode']));
				$custom_class = !empty($meta['custom_class']) ? $meta['custom_class'] : '';
				$style = '';
				$html  = '';

                if(current_theme_supports('avia_template_builder_custom_html5_video_urls'))
                {
                    $sources = array();
                    if(!empty($src)) $sources['src'] = array('url' => $src, 'extension' => substr($src, strrpos($src, '.') + 1));
                    if(!empty($src_1)) $sources['src_1'] = array('url' => $src_1, 'extension' => substr($src_1, strrpos($src_1, '.') + 1));
                    if(!empty($src_2)) $sources['src_2'] = array('url' => $src_2, 'extension' => substr($src_2, strrpos($src_2, '.') + 1));

                    $html5 = false;

                    if(!empty($sources))
                    {
                        foreach($sources as $source)
                        {
                            if(in_array($source['extension'], array('ogv','webm','mp4'))) //check for html 5 video
                            {
                                $html5 = true;
                            }
                            else
                            {
                                $video = $source['url'];
                                $html5 = false;
                                break;
                            }
                        }
                    }

                    if($html5 && !empty($sources)) //check for html 5 video
                    {
                        $video = '';
                        foreach($sources as $source)
                        {
                            $video .= $source['extension'].'="'.$source['url'].'" ';
                        }

                        $output = do_shortcode('[video '.$video.']');
                        $html = "avia-video-html5";
                    }
                    else if(!empty($video))
                    {
                        global $wp_embed;
                        $output = $wp_embed->run_shortcode("[embed]".trim($src)."[/embed]");
                    }
                }
                else
                {
                    $file_extension = substr($src, strrpos($src, '.') + 1);

                    if(in_array($file_extension, array('ogv','webm','mp4'))) //check for html 5 video
                    {
                        $output = avia_html5_video_embed($src);
                        $html = "avia-video-html5";
                    }
                    else
                    {
                        global $wp_embed;
                        $output = $wp_embed->run_shortcode("[embed]".trim($src)."[/embed]");
                    }
                }
				
				if($format == 'custom')
				{
					$height = intval($height);
					$width  = intval($width);
					$ratio  = (100 / $width) * $height;
					$style = "style='padding-bottom:{$ratio}%;'";
				}
				
				if(!empty($output))
				{
                    $markup = avia_markup_helper(array('context' => 'video','echo'=>false, 'custom_markup'=>$meta['custom_markup']));
					$output = "<div {$style} class='avia-video avia-video-{$format} {$html} {$custom_class} {$av_display_classes}' {$markup}>{$output}</div>";
				}
				
				
				return $output;
			}
			
			
	}
}
