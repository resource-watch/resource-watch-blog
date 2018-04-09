<?php
/**
 * Font Icon
 * 
 * Shortcode which displays an icon with optional hover effect
 */
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

if ( !class_exists( 'av_font_icon' ) )
{
    class av_font_icon extends aviaShortcodeTemplate
    {
        /**
         * Create the config array for the shortcode button
         */
        function shortcode_insert_button()
        {
			$this->config['self_closing']	=	'no';
			
            $this->config['name']       = __('Icon', 'avia_framework' );
			$this->config['tab']		= __('Content Elements', 'avia_framework' );
			$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-icon.png";
            $this->config['order']      = 90;
            $this->config['shortcode']  = 'av_font_icon';
            $this->config['tooltip'] 	    = __('Display an icon with optional hover effect', 'avia_framework' );
			$this->config['target']		= 'avia-target-insert';
            //$this->config['inline']   = true;
            $this->config['tinyMCE']    = array('tiny_always'=>true);
			$this->config['preview'] 	= 1;
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
                    "name"  => __("Font Icon",'avia_framework' ),
                    "desc"  => __("Select an Icon below",'avia_framework' ),
                    "id"    => "icon",
                    "type"  => "iconfont",
                    "std"   => ""),

				 array(
                    "name"  => __("Icon Style", 'avia_framework' ),
                    "desc"  => __("Here you can set the  style of the icon. Either display it inline as part of some text or let it stand alone with border and optional caption", 'avia_framework' ),
                    "id"    => "style",
                    "type" 	=> "select",
					"std" 	=> "",
					
					"subtype" => array(
						__('Default inline style',   'avia_framework' ) =>'',
						__('Standalone Icon with border and optional caption',  'avia_framework' ) =>'border',
					)),	

				
				 array(
                    "name"  => __("Icon Caption", 'avia_framework' ),
                    "desc"  => __("A small caption below the icon", 'avia_framework' ),
                    "id"    => "caption",
                    "type" 	=> "input",
					"std" 	=> "",
					"required" 	=> array('style', 'not', ''),

					),	
				
				

                array(
                    "name" 	=> __("Title Link?", 'avia_framework' ),
                    "desc" 	=> __("Where should your title link to?", 'avia_framework' ),
                    "id" 	=> "link",
                    "type" 	=> "linkpicker",
                    "fetchTMPL"	=> true,
                    "std"	=> "",
                    "subtype" => array(
                        __('No Link', 'avia_framework' ) =>'',
                        __('Set Manually', 'avia_framework' ) =>'manually',
                        __('Single Entry', 'avia_framework' ) =>'single',
                        __('Taxonomy Overview Page',  'avia_framework' )=>'taxonomy',
                    ),
                    "std" 	=> ""),

                array(
                    "name" 	=> __("Open in new window", 'avia_framework' ),
                    "desc" 	=> __("Do you want to open the link in a new window", 'avia_framework' ),
                    "id" 	=> "linktarget",
                    "required" 	=> array('link', 'not', ''),
                    "type" 	=> "select",
                    "std" 	=> "",
                    "subtype" => AviaHtmlHelper::linking_options()),   



                array(
                    "name"  => __("Icon Size", 'avia_framework' ),
                    "desc"  => __("Enter the font size in px, em or &percnt;", 'avia_framework' ),
                    "id"    => "size",
                    "type"  => "input",
                    "std"	=> "40px"
                    ),
                    
                array(	
					"name" 	=> __("Icon Position", 'avia_framework' ),
					"desc" 	=> __("Choose the alignment of your icon here", 'avia_framework' ),
					"id" 	=> "position",
					"type" 	=> "select",
					"std" 	=> "left",
					"subtype" => array(
						__('Align Left',   'avia_framework' ) =>'left',
						__('Align Center',  'avia_framework' ) =>'center',
						__('Align Right',   'avia_framework' ) =>'right',
					)),	
					
					
				array(
						"name" 	=> __("Optional Tooltip",'avia_framework' ),
						"desc" 	=> __("Add a tooltip for this Icon. The tooltip will appear on mouse over",'avia_framework' )
						."<br/><small>". __("Please note: Images within the tooltip are currently not supported",'avia_framework' )."</small>",
						"id" 	=> "content",
						"type" 	=> "textarea",
						"std" 	=> ""),
						
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
                    "name"  => __("Icon Color", 'avia_framework' ),
                    "desc"  => __("Here you can set the  color of the icon. Enter no value if you want to use the standard font color.", 'avia_framework' ),
                    "id"    => "color",
                    "rgba" 	=> true,
                    "type"  => "colorpicker"),	
                    
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
				extract(av_backend_icon($params)); // creates $font and $display_char if the icon was passed as param "icon" and the font as "font" 
				extract(shortcode_atts(array(
                'color'    => '',
                'size'     => '',
                'style'     => '',
                'caption'	=> '',
                'use_link' => 'no',
                'position' => 'left',
                'link' =>'',
                'linktarget' => 'no',
                'custom_class' => '',
            	), $params['args'], $this->config['shortcode']));
				
			
				$inner  = "<div class='avia_icon_element avia_textblock avia_textblock_style'>";
				$inner .= "		<div ".$this->class_by_arguments('position' ,$params['args']).">";
				$inner .= "		<div ".$this->class_by_arguments('style' ,$params['args']).">";
				$inner .= "			<span ".$this->class_by_arguments('font' ,$font).">";
				$inner .= "				<span data-update_with='icon_fakeArg' class='avia_icon_char'>".$display_char."</span>";
				$inner .= "			</span>";
				$inner .= "			<div class='avia_icon_content_wrap'>";
				$inner .= "				<h4  class='av_icon_caption' data-update_with='caption'>".html_entity_decode($caption)."</h4>";
				$inner .= "			</div>";
				$inner .= "		</div>";
				$inner .= "		</div>";
				$inner .= "</div>";

				$params['innerHtml'] = $inner;
				$params['class'] = "";

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
            //this is a fix that solves the false paragraph removal by wordpress if the dropcaps shortcode is used at the beginning of the content of single posts/pages
            global $post, $avia_add_p;

            $add_p = "";
            $custom_class = !empty($meta['custom_class']) ? $meta['custom_class'] : "";
            if(isset($post->post_content) && strpos($post->post_content, '[av_font_icon') === 0 && $avia_add_p == false && is_singular())
            {
                $add_p = "<p>";
                $avia_add_p = true;
            }
            
            extract(AviaHelper::av_mobile_sizes($atts)); //return $av_font_classes, $av_title_font_classes and $av_display_classes 

            extract(shortcode_atts(array(
                'icon'     => '',
                'font'     => '',
                'color'    => '',
                'size'     => '',
                'style'     => '',
                'caption'	=> '',
                'use_link' => 'no',
                'position' => 'left',
                'link' =>'',
                'linktarget' => 'no',
                'font' => ''
            ), $atts, $this->config['shortcode']));

            $char = av_icon($icon, $font);

            $color = !empty($color) ? "color:{$color}; border-color:{$color};" : '';
			
			if(empty($color)) $custom_class .= " av-no-color";
			
            if(!empty($size) && is_numeric($size)) $size .= 'px';
            $size_string = !empty($size) ? "font-size:{$size};line-height:{$size};" : '';
			
			if(!empty($style))
			{
				$size_string   .= "width:{$size};";
				if(!empty($caption)) $caption = "<span class='av_icon_caption av-special-font'>{$caption}</span>";
			}
			else
			{
				$caption = "";
			}
			
			
            $blank = (strpos($linktarget, '_blank') !== false || $linktarget == 'yes') ? ' target="_blank" ' : "";
            $blank .= strpos($linktarget, 'nofollow') !== false ? ' rel="nofollow" ' : "";
           
            $link = aviaHelper::get_url($link);
            
            $tags = !empty($link) ? array("a href='{$link}' {$blank} ",'a') : array('span','span');
            
            $tooltip = empty($content) ? '' : 'data-avia-icon-tooltip="'.htmlspecialchars(do_shortcode($content)).'"';
            
            $display_char = "<{$tags[0]} class='av-icon-char' style='{$size_string}' {$char} {$tooltip}></{$tags[1]}>";
            
            $output = '<span class="'.$shortcodename.' avia_animate_when_visible '.$av_display_classes.' av-icon-style-'.$style.' '.$custom_class.' avia-icon-pos-'.$position.' " style="'.$color.'">'.$display_char.$caption.'</span>';

			
			
            return $output;
        }

    }
}
