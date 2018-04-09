<?php
/**
 * Helper for slideshows
 * 
 */	
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_slideshow' ) )
{
	class avia_slideshow
	{
		static  $slider = 0; 				//slider count for the current page
		protected $config;	 				//base config set on initialization
		protected $slides;	 				//attachment posts for the current slider
		protected $slide_count = 0;			//number of slides
		protected $id_array = array();
		function __construct($config)
		{

			$this->config = array_merge(array(
				'size'			=> 'featured',
				'lightbox_size'	=> 'large',
				'animation'		=> 'slide',
				'ids'    	 	=> '',
				'video_counter' => 0,
				'autoplay'		=> 'false',
				'bg_slider'		=> 'false',
				'slide_height'	=> '',
				'handle'		=> '',
				'interval'		=> 5,
				'class'			=> "",
				'css_id'		=> "",
				'scroll_down'	=> "",
				'control_layout'=> '',
				'content'		=> array(),
				'custom_markup' => '',
				'perma_caption'	=> '',
				'autoplay_stopper'=>'',
				'image_attachment'=>'',
				'min_height'	  =>'0px'
				), $config);

			$this->config = apply_filters('avf_slideshow_config', $this->config);

			//check how large the slider is and change the classname accordingly
			global $_wp_additional_image_sizes;
			$width = 1500;

			if(isset($_wp_additional_image_sizes[$this->config['size']]['width']))
			{
				$width  = $_wp_additional_image_sizes[$this->config['size']]['width'];
				$height = $_wp_additional_image_sizes[$this->config['size']]['height'];
				
				$this->config['default-height'] = (100/$width) * $height;
				
			}
			else if($size = get_option( $this->config['size'].'_size_w' ))
			{
				$width = $size;
			}

			if($width < 600)
			{
				$this->config['class'] .= " avia-small-width-slider";
			}

			if($width < 305)
			{
				$this->config['class'] .= " avia-super-small-width-slider";
			}

			//if we got subslides overwrite the id array
			if(!empty($config['content']))
			{
				$this->extract_subslides($config['content']);
			}
			
			if("aviaTBautoplay_stopper" == $this->config['autoplay_stopper'])
			{
				$this->config['autoplay_stopper'] = true;
			}
			else
			{
				$this->config['autoplay_stopper'] = false;
			}

			$this->set_slides($this->config['ids']);
		}

		public function set_slides($ids)
		{
			if(empty($ids) && empty($this->config['video_counter'])) return;

			$this->slides = get_posts(array(
				'include' => $ids,
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => 'ASC',
				'orderby' => 'post__in')
				);



			//resort slides so the id of each slide matches the post id
			$new_slides = array();
			foreach($this->slides as $slide)
			{
				$new_slides[$slide->ID] = $slide;
			}

			$slideshow_data = array();
			$slideshow_data['slides'] = $new_slides;
			$slideshow_data['id_array'] = explode(',',$this->config['ids']);
			$slideshow_data['slide_count'] = count(array_filter($slideshow_data['id_array'])) + $this->config['video_counter'];
			
			$slideshow_data = apply_filters('avf_avia_builder_slideshow_filter', $slideshow_data);
			
			$this->slides = $slideshow_data['slides'];
			$this->id_array = $slideshow_data['id_array'];
			$this->slide_count = $slideshow_data['slide_count'];
		}

		public function set_size($size)
		{
			$this->config['size'] = $size;
		}

		public function set_extra_class($class)
		{
			$this->config['class'] .= " ".$class;
		}



		public function html()
		{
			$html 		= "";
			$counter 	= 0;
			$style   	= "";
			$extraClass = "";
			avia_slideshow::$slider++;
			if($this->slide_count == 0) return $html;
			
			if(!empty($this->config['scroll_down']))
			{	
				$html .= "<a href='#next-section' title='' class='scroll-down-link ".$this->config['control_layout']."' ". av_icon_string( 'scrolldown' ). "></a>";
				$extraClass .= "av-slider-scroll-down-active";
			}
			
			if(!empty($this->config['control_layout'])) $extraClass .= " ".$this->config['control_layout'];
			
			$style = "";
			$data = AviaHelper::create_data_string($this->config);
			$slide_html = empty($this->subslides) ? $this->default_slide() : $this->advanced_slide();
			
			if(!empty($this->config['default-height']))
			{
				$style = "style='padding-bottom: ".$this->config['default-height']."%;'";
				$extraClass .= " av-default-height-applied";
			}
			
			
            $markup = avia_markup_helper(array('context' => 'image','echo'=>false, 'custom_markup'=>$this->config['custom_markup']));

			

			$html .= "<div {$data} class='avia-slideshow avia-slideshow-".avia_slideshow::$slider." {$extraClass} avia-slideshow-".$this->config['size']." ".$this->config['handle']." ".$this->config['class']." avia-".$this->config['animation']."-slider ' $markup>";
			
			
			$html .= "<ul class='avia-slideshow-inner' {$style}>";

			
			$html .= $slide_html;
			$html .= "</ul>";

			if($this->slide_count > 1)
			{
				$html .= $this->slide_navigation_arrows();
				$html .= $this->slide_navigation_dots();
			}
			
			
			if(!empty($this->config['caption_override'])) $html .= $this->config['caption_override'];
			

			$html .= "</div>";
		
			
			return $html;
		}

		//function that renders the usual slides. use when we didnt use sub-shorcodes to define the images but ids
		protected function default_slide()
		{
			$html = "";
			$counter = 0;

            $markup_url = avia_markup_helper(array('context' => 'image_url','echo'=>false, 'custom_markup'=>$this->config['custom_markup']));

			foreach($this->id_array as $id)
			{
				if(isset($this->slides[$id]))
				{
					$slide = $this->slides[$id];

					$counter ++;
					$img 	 = wp_get_attachment_image_src($slide->ID, $this->config['size']);
					$link	 = wp_get_attachment_image_src($slide->ID, $this->config['lightbox_size']);
					$caption = trim($slide->post_excerpt) ? '<div class="avia-caption capt-bottom capt-left"><div class="avia-inner-caption">'.wptexturize($slide->post_excerpt)."</div></div>": "";

                    $imgalt = get_post_meta($slide->ID, '_wp_attachment_image_alt', true);
                    $imgalt = !empty($imgalt) ? esc_attr($imgalt) : '';
                    $imgtitle = trim($slide->post_title) ? esc_attr($slide->post_title) : "";
                  	if($imgtitle == "-") $imgtitle = "";
                    $imgdescription = trim($slide->post_content) ? esc_attr($slide->post_content) : "";
					

					$tags = apply_filters('avf_slideshow_link_tags', array("a href='".$link[0]."' title='".$imgdescription."'",'a')); // can be filtered and for example be replaced by array('div','div')
					
					$html .= "<li class='slide-{$counter} slide-id-".$slide->ID."'>";
					$html .= "<".$tags[0]." >{$caption}<img src='".$img[0]."' width='".$img[1]."' height='".$img[2]."' title='".$imgtitle."' alt='".$imgalt."' $markup_url  /></ ".$tags[1]." >";
					$html .= "</li>";
				}
				else
				{
					$this->slide_count --;
				}
			}

			return $html;
		}

		//function that renders the slides. use when we did use sub-shorcodes to define the images
		protected function advanced_slide()
		{
			$html = "";
			$counter = 0;
			$this->ie8_fallback = "";

			foreach($this->id_array as $key => $id)
			{
				$meta = array_merge( array( 'content'		=> $this->subslides[$key]['content'],
											'title'			=>'',
											'link_apply'	=>'',
											//direct link from image
											'link'			=>'',
											'link_target'	=>'',
											//button link 1
											'button_label'	=>'',
											'button_color'	=>'light',
											'link1'			=>'',
											'link_target1'	=>'',											
											//button link 2
											'button_label2'	=>'',
											'button_color2'	=>'light',
											'link2'			=>'',
											'link_target2'	=>'',
											
											'position'		=>'center center',
											'caption_pos'	=>'capt-bottom capt-left',
											'video_cover'	=>'',
											'video_controls'=>'',
											'video_mute'	=>'',
											'video_loop'	=>'',
											'video_format'	=>'',
											'video_autoplay'=>'',
											'video_ratio'	=>'16:9',
											'video_mobile_disabled'=>'',
											'video_mobile'	=>'mobile-fallback-image',
											'mobile_image'	=> '',
											'slide_type'	=>'',
											'custom_markup' => '',
											'custom_title_size' => '',
											'custom_content_size' => '',
											'font_color'	=>'',
											'custom_title' 	=> '',
											'custom_content' => '',
											'overlay_enable' => '',
			    							'overlay_opacity' => '',
			    							'overlay_color' => '',
			    							'overlay_pattern' => '',
			    							'overlay_custom_pattern' => '',


										), $this->subslides[$key]['attr']);
				
				//return $av_font_classes, $av_title_font_classes and $av_display_classes 
				extract(AviaHelper::av_mobile_sizes($this->subslides[$key]['attr'])); 
				extract($meta);
				
				if(isset($this->slides[$id]) || $slide_type == 'video')
				{
					$img			= array('');
					$slide			= "";
					$attachment_id	= isset($this->slides[$id]) ? $id : false;
					$link			= AviaHelper::get_url($link, $attachment_id); 
					$extra_class 	= "";
					$linkdescription= "";
					$linkalt 		= "";
					$this->service  = false;
					$slider_data	= "";
					$stretch_height	= false;
					$final_ratio	= "";
					$viewport		= 16/9;

            		$markup_url = avia_markup_helper(array('context' => 'image_url','echo'=>false, 'id'=>$attachment_id, 'custom_markup'=>$custom_markup));
					
					if($slide_type == 'video')
					{
						$this->service    = avia_slideshow_video_helper::which_video_service($video);
						$video 			  = avia_slideshow_video_helper::set_video_slide($video, $this->service, $meta); 
						$video_class	  = !empty( $video_controls ) ? " av-hide-video-controls" : "";
						$video_class	 .= !empty( $video_mute ) ? " av-mute-video" : "";
						$video_class	 .= !empty( $video_loop ) ? " av-loop-video" : "";
						$video_class	 .= !empty( $video_mobile ) ? " av-".$video_mobile : "";
						
						$extra_class 	.= " av-video-slide ".$video_cover." av-video-service-".$this->service." ".$video_class;
						$slider_data 	.= " data-controls='{$video_controls}' data-mute='{$video_mute}' data-loop='{$video_loop}' data-disable-autoplay='{$video_autoplay}' ";	
						
						if($mobile_image){
							$fallback_img = wp_get_attachment_image_src($mobile_image, $this->config['size']);
							$slider_data .= " data-mobile-img='".$fallback_img[0]."'";
						}
						
						//if we dont use a fullscreen slider pass the video ratio to the slider
						if($this->config['bg_slider'] != "true")
						{
							global $avia_config;
							//if we use the small slideshow only allow the "full" $video_format
							if($this->config['handle'] == 'av_slideshow') $video_format = "full";
							
							
							//calculate the viewport ratio
							if(!empty($avia_config['imgSize'][$this->config['size']]))
							{
								$viewport = $avia_config['imgSize'][$this->config['size']]['width'] / $avia_config['imgSize'][$this->config['size']]['height'];
							}
							
							
							//calculate the ratio when passed as a string (eg: 16:9, 4:3). fallback is 16:9
							$video_ratio = explode(':',trim($video_ratio));
							if(empty($video_ratio[0])) $video_ratio[0] = 16;
							if(empty($video_ratio[1])) $video_ratio[1] = 9;
							$final_ratio = ((int) $video_ratio[0] / (int) $video_ratio[1]);							
							
							switch($video_format)
							{
								case "": 
									$final_ratio = $viewport; 
								break;
								case "stretch": 
									$final_ratio 	 = $viewport; 
									$stretch_height  = ceil( $viewport / ($video_ratio[0]/$video_ratio[1]) * 100 );
									$stretch_pos 	 = (($stretch_height - 100) / 2) * -1;
									$slider_data 	.= " data-video-height='{$stretch_height}'";
									$slider_data 	.= " data-video-toppos='{$stretch_pos}'";
									$extra_class 	.= " av-video-stretch";
								break;
								case "full": 
									// do nothing and apply the entered ratio
								break;
							}
							
							$slider_data .= " data-video-ratio='{$final_ratio}'";	
						}
						
					}
					else //img slide
					{
						$slide 			 = $this->slides[$id];
						$linktitle 		 = trim($slide->post_title) ? esc_attr($slide->post_title) : "";
						if($linktitle == "-") $linktitle = "";
                    	$linkdescription = (trim($slide->post_content) && empty($link)) ? "title='".esc_attr($slide->post_content)."'" : "";
                    	$linkalt 		 = get_post_meta($slide->ID, '_wp_attachment_image_alt', true);
                    	$linkalt 		 = !empty($linkalt) ? esc_attr($linkalt) : '';
						$img   			 = wp_get_attachment_image_src($slide->ID, $this->config['size']);
						$video			 = "";
					}
					
					if($this->slide_count === 1) $extra_class .= " av-single-slide";
					
					$blank = (strpos($link_target, '_blank') !== false || $link_target == 'yes') ? ' target="_blank" ' : "";
					$blank .= strpos($link_target, 'nofollow') !== false ? ' rel="nofollow" ' : "";
					$tags 			= (!empty($link) && $link_apply == 'image') ? array("a href='{$link}'{$blank}",'a') : array('div','div');
					$caption  		= "";
					$button_html 	= "";
					$counter ++;
					$button_count = "";
					if(strpos($link_apply, 'button-two') !== false){$button_count = "avia-multi-slideshow-button";}
					
					
					//if we got a CTA button apply the link to the button istead of the slide
					if(strpos($link_apply, 'button') !== false)
					{
						$button_html .= $this->slideshow_cta_button($link1, $link_target1, $button_color, $button_label, $button_count);
						$tags = array('div','div');
					}
					
					if(strpos($link_apply, 'button-two') !== false)
					{
						$button_count .= " avia-slideshow-button-2";
						$button_html .= $this->slideshow_cta_button($link2, $link_target2, $button_color2, $button_label2, $button_count);
					}
					
					
					//custom caption styles
					
					$title_styling 		 = !empty($custom_title_size) ? "font-size:{$custom_title_size}px; " : "";
					$content_styling 	 = !empty($custom_content_size) ? "font-size:{$custom_content_size}px; " : "";
					$content_class		 = "";
					
					if($font_color == "custom")
					{
						$title_styling 		.= !empty($custom_title) ? "color:{$custom_title}; " : "";
						$content_styling 	.= !empty($custom_content) ? "color:{$custom_content}; " : "";
					}
					
					if($title_styling) $title_styling = " style='{$title_styling}'" ;
					if($content_styling) 
					{
						$content_styling = " style='{$content_styling}'" ;
						$content_class	 = "av_inherit_color";
					}
					
					
					
					
					//check if we got a caption
                    $markup_description = avia_markup_helper(array('context' => 'description','echo'=>false, 'id'=>$attachment_id, 'custom_markup'=>$custom_markup));
                    $markup_name = avia_markup_helper(array('context' => 'name','echo'=>false, 'id'=>$attachment_id, 'custom_markup'=>$custom_markup));
					if(trim($title) != "")   $title 	= "<h2 {$title_styling} class='avia-caption-title {$av_title_font_classes}' $markup_name>".trim(apply_filters('avf_slideshow_title', $title))."</h2>";
					
					if(is_array($content)) $content = implode(' ',$content); //temp fix for trim() expects string warning until I can actually reproduce the problem
					if(trim($content) != "") $content 	= "<div class='avia-caption-content {$av_font_classes} {$content_class}' {$markup_description} {$content_styling}>".ShortcodeHelper::avia_apply_autop(ShortcodeHelper::avia_remove_autop(trim($content)))."</div>";

					if(trim($title.$content.$button_html) != "")
					{
						if(trim($title) != "" && trim($button_html) != "" && trim($content) == "") $content = "<br/>";

						if($this->config['handle'] == 'av_slideshow_full' || $this->config['handle'] == 'av_fullscreen')
						{
							$caption .= '<div class = "caption_fullwidth av-slideshow-caption '.$caption_pos.'">';
							$caption .= 	'<div class = "container caption_container">';
							$caption .= 			'<div class = "slideshow_caption">';
							$caption .= 				'<div class = "slideshow_inner_caption">';
							$caption .= 					'<div class = "slideshow_align_caption">';
							$caption .=						$title;
							$caption .=						$content;
							$caption .=						$button_html;
							$caption .= 					'</div>';
							$caption .= 				'</div>';
							$caption .= 			'</div>';
							$caption .= 	'</div>';
							$caption .= '</div>';
						}
						else
						{
							$caption = '<div class="avia-caption av-slideshow-caption"><div class="avia-inner-caption">'.$title.$content."</div></div>";
						}
					}

					if(!empty($this->config['perma_caption']) && empty($this->config['caption_override']))
					{
						$this->config['caption_override'] = $caption;
					}
                   	
                   	if(!empty($this->config['caption_override'])) $caption = "";
                    
					
					if(!empty($img[0]))
					{
						$slider_data .= $this->config['bg_slider'] == "true" ? "style='background-position:{$position};' data-img-url='".$img[0]."'" : "";
						
						if($slider_data )
						{
							if(empty($this->ie8_fallback))
							{
						    	$this->ie8_fallback .= "<!--[if lte IE 8]>";
								$this->ie8_fallback .= "<style type='text/css'>";
							}
							$this->ie8_fallback .= "\n #{$this->config['css_id']} .slide-{$counter}{";
							$this->ie8_fallback .= "\n -ms-filter: \"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='{$img[0]}', sizingMethod='scale')\"; ";
						    $this->ie8_fallback .= "\n filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='{$img[0]}', sizingMethod='scale'); ";
							$this->ie8_fallback .= "\n } \n";
						}
					}
					
					
					
					
					// $img[0] = 'https://kriesi.at/themes/enfold-photography/files/2014/08/darkened_girl.jpg';


					$html .= "<li {$slider_data} class='{$extra_class} slide-{$counter} ' >";
					$html .= "<".$tags[0]." data-rel='slideshow-".avia_slideshow::$slider."' class='avia-slide-wrap' {$linkdescription} >{$caption}";
					if($this->config['bg_slider'] != "true" && empty($video))
					{
						$img_style = "";
						if(!empty($this->config['min_height']) && $this->config['min_height'] != "0px")
						{
							$percent = 100 / (100/$img[2] * (int) $this->config['min_height'] );
							$this->config['min_width'] = ceil(($img[1] / $percent)) . "px";
							
							$img_style .= AviaHelper::style_string($this->config, 'min_height', 'min-height');
							$img_style .= AviaHelper::style_string($this->config, 'min_width', 'min-width');
							$img_style  = AviaHelper::style_string($img_style);
						}
				
						
						$html .= "<img src='".$img[0]."' width='".$img[1]."' height='".$img[2]."' title='".$linktitle."' alt='".$linkalt."' $markup_url $img_style />";
					}
					$html .= $video;
					$html .= $this->create_overlay($meta);
					$html .= "</".$tags[1].">";
					$html .= "</li>";
					
					if( $counter === 1 )
					{
						if(!empty($img[1]) && !empty($img[2]))
						{
							$this->config['default-height'] = (100/$img[1]) * $img[2];
						}
					}
					
			}
			else
			{
				$this->slide_count --;
			}
		}

			if(!empty($this->ie8_fallback))
			{
				$this->ie8_fallback .= "</style> <![endif]-->";
				add_action('wp_footer', array($this, 'add_ie8_fallback_to_footer'));
			}

			return $html;
		}

		public function add_ie8_fallback_to_footer()
		{
			// echo $this->ie8_fallback;
		}
		
		protected function slideshow_cta_button($link, $link_target, $button_color, $button_label, $button_count)
		{
			$button_html = "";
			$blank = (strpos($link_target, '_blank') !== false || $link_target == 'yes') ? ' target="_blank" ' : "";
			$blank .= strpos($link_target, 'nofollow') !== false ? ' rel="nofollow" ' : "";
			
			$link = AviaHelper::get_url($link); 
			
			$button_html .= "<a href='{$link}' {$blank} class='avia-slideshow-button avia-button avia-color-{$button_color} {$button_count}' data-duration='800' data-easing='easeInOutQuad'>";
			$button_html .= $button_label;
			$button_html .= "</a>";
			return $button_html;
		}


		protected function slide_navigation_arrows()
		{
			global $avia_config;
		
			$html  = "";
			$html .= "<div class='avia-slideshow-arrows avia-slideshow-controls'>";
			$html .= 	"<a href='#prev' class='prev-slide' ".av_icon_string('prev_big').">".__('Previous','avia_framework' )."</a>";
			$html .= 	"<a href='#next' class='next-slide' ".av_icon_string('next_big').">".__('Next','avia_framework' )."</a>";
			$html .= "</div>";

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

		protected function extract_subslides($slide_array)
		{
			$this->config['ids']= array();
			$this->subslides 	= array();
		
			foreach($slide_array as $key => $slide)
			{
				$this->subslides[$key] = $slide;
				$this->config['ids'][] = $slide['attr']['id'];
			
				if( empty($slide['attr']['id']) && !empty($slide['attr']['video']) && $slide['attr']['slide_type'] === 'video')
				{
					$this->config['video_counter'] ++ ;
				}
			}

			$this->config['ids'] = implode(',',$this->config['ids'] );
			
			unset($this->config['content']);
		}
		
		protected function create_overlay($meta)
		{
			extract($meta);
			
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
		

		
		
	}
}






if ( !class_exists( 'avia_slideshow_video_helper' ) )
{
	class avia_slideshow_video_helper
	{
		static function set_video_slide($video_url, $service = false, $meta = false)
		{
			$video = "";
			if(empty($service)) $service = self::which_video_service($video_url);
			
			$uid 		= 'player_'.get_the_ID().'_'.mt_rand().'_'.mt_rand();
			$controls 	= empty($meta['video_controls']) ? 1 : 0;
			$loop 		= empty($meta['video_loop']) ? 0 : 1;
			
			switch( $service )
			{
				case "html5": $video = "<div class='av-click-overlay'></div>".avia_html5_video_embed($video_url); break;
				case "iframe":$video = $video_url; break;
				case "youtube":
					
					$explode_at = strpos($video_url, 'youtu.be/') !== false ? "/" : "v=";
					$video_url	= explode($explode_at, trim($video_url));
					$video_url	= end($video_url);
					$video_id	= $video_url;
					
					//if parameters are appended make sure to create the correct video id
					if (strpos($video_url,'?') !== false || strpos($video_url,'?') !== false) 
					{
					    preg_match('!(.+)[&?]!',$video_url, $video_id);
						$video_id = isset($video_id[1]) ? $video_id[1] : $video_id[0];
					}
					
					$video_data = apply_filters( 'avf_youtube_video_data', array(
							'autoplay' 		=> 0,
							'videoid'		=> $video_id,
							'hd'			=> 1,
							'rel'			=> 0,
							'wmode'			=> 'opaque',
							'playlist'		=> $uid,
							'loop'			=> 0,
							'version'		=> 3,
							'autohide'		=> 1,
							'color'			=> 'white',
							'controls'		=> $controls,
							'showinfo'		=> 0,
							'iv_load_policy'=> 3
						));
						
					$data = AviaHelper::create_data_string($video_data);
				
					$video 	= "<div class='av-click-overlay'></div><div class='mejs-mediaelement'><div height='1600' width='900' class='av_youtube_frame' id='{$uid}' {$data}></div></div>";
					
				break;
				case "vimeo":
			
					$color		= ltrim( avia_get_option('colorset-main_color-primary'), '#');				
					$autopause  = empty($meta['video_section_bg']) ? 1 : 0; //pause if another vimeo video plays?
					$video_url	= explode('/', trim($video_url));
					$video_url	= end($video_url);
					$video_url 	= esc_url(add_query_arg(
						array(
							'portrait' 	=> 0,
							'byline'	=> 0,
							'title'		=> 0,
							'badge'		=> 0,
							'loop'		=> $loop,
							'autopause'	=> $autopause,
							'api'		=> 1,
							'rel'		=> 0,
							'player_id'	=> $uid,
							'color'		=> $color
						),
					'//player.vimeo.com/video/'.$video_url 
					));
					
					$video_url = apply_filters( 'avf_vimeo_video_url' , $video_url);
					$video 	= "<div class='av-click-overlay'></div><div class='mejs-mediaelement'><iframe src='{$video_url}' height='1600' width='900'  frameborder='' class='av_vimeo_frame' id='{$uid}'></iframe></div>";
					
				break;
			}
			
			
			
			return $video;
			
		}
		
		//get the video service based on the url string fo the video
		static function which_video_service($video_url)
		{
			$service = "";
			
			if(avia_backend_is_file($video_url, 'html5video'))
			{
				$service = "html5";
			}
			else if(strpos($video_url,'<iframe') !== false)
			{
				$service = "iframe";
			}
			else
			{
				if(strpos($video_url, 'youtube.com/watch') !== false || strpos($video_url, 'youtu.be/') !== false)
				{
					$service = "youtube";
				}
				else if(strpos($video_url, 'vimeo.com') !== false)
				{
					$service = "vimeo";
				}
			}
			
			return $service;
		}
	}
}



