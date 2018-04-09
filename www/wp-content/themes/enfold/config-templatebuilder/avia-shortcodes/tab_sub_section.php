<?php
/**
 * Single Tab
 * 
 * Shortcode creates a single tab for the tab section element
 */

 // Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }



if ( !class_exists( 'avia_sc_tab_sub_section' ) )
{
	class avia_sc_tab_sub_section extends aviaShortcodeTemplate{

			static $extraClass = "";
			static $attr = array();
			
			
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['type']				=	'layout';
				$this->config['self_closing']		=	'no';
				$this->config['contains_text']		=	'no';
				$this->config['contains_layout']	=	'yes';
				$this->config['contains_content']	=	'yes';
				
				$this->config['invisible'] = true;
				$this->config['name']		= 'Single Tab';
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-full.png";
				$this->config['tab']		= __('Layout Elements', 'avia_framework' );
				$this->config['order']		= 100;
				$this->config['target']		= "avia-section-drop";
				$this->config['shortcode'] 	= 'av_tab_sub_section';
				$this->config['html_renderer'] 	= false;
				$this->config['tinyMCE'] 	= array('disable' => "true");
				$this->config['tooltip'] 	= __('Creates a single tab for the tab section element', 'avia_framework' );
				$this->config['drag-level'] = 2;
				$this->config['drop-level'] = 1;
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
				avia_sc_tab_section::$tab += 1;
				
				extract($params);
				
				if(empty($data)) $data = array();
				
				$name 		= $this->config['shortcode'];
				$drag 		= $this->config['drag-level'];
				$drop 		= $this->config['drop-level'];

				
				$data['shortcodehandler'] 	= $this->config['shortcode'];
				$data['modal_title'] 		= __('Edit Tab','avia_framework' );
				$data['modal_ajax_hook'] 	= $this->config['shortcode'];
				$data['dragdrop-level']		= $this->config['drag-level'];
				$data['allowed-shortcodes'] = $this->config['shortcode'];
				
				if(!empty($this->config['modal_on_load']))
				{
					$data['modal_on_load'] 	= $this->config['modal_on_load'];
				}
	
				$dataString  = AviaHelper::create_data_string($data);
				
				$el_bg = !empty($args['background_color']) ? " style='background:".$args['background_color'].";'" : "";
				$active_tab = avia_sc_tab_section::$tab == avia_sc_tab_section::$admin_active ? "av-admin-section-tab-content-active" : "";
				avia_sc_tab_section::$tab_titles[ avia_sc_tab_section::$tab ] = !empty($args['tab_title']) ? ": ".$args['tab_title'] : "";
				

				$output  = "<div  class='avia_layout_column avia_layout_tab {$active_tab} avia-no-visual-updates ".$name." av_drag' {$dataString} data-width='{$name}' data-av-tab-section-content='".avia_sc_tab_section::$tab."' >";
				$output .= "<div class='avia_sorthandle'>";

				//$output .= "<span class='avia-element-title'>".$this->config['name']."<span class='avia-element-title-id'>".$title_id."</span></span>";
				$output .= "<a class='avia-delete avia-tab-delete av-special-delete'  href='#delete' title='".__('Delete Tab','avia_framework' )."'>x</a>";
				$output .= "<a class='avia-clone avia-tab-clone av-special-clone'  href='#clone' title='".__('Clone Tab','avia_framework' )."' >".__('Clone Cell','avia_framework' )."</a>";
				
				if(!empty($this->config['popup_editor']))
    			{
    				$output .= "<a class='avia-edit-element'  href='#edit-element' title='".__('Edit Tab','avia_framework' )."'>edit</a>";
    			}
				
				$output .= "</div><div class='avia_inner_shortcode avia_connect_sort av_drop ' data-dragdrop-level='{$drop}'>";
				$output .= "<textarea data-name='text-shortcode' cols='20' rows='4'>".ShortcodeHelper::create_shortcode_by_array($name, $content, $args)."</textarea>";
				if($content)
				{
					$content = $this->builder->do_shortcode_backend($content);
				}
				$output .= $content;
				$output .= "</div>";
				$output .= "<div class='avia-layout-element-bg' ".$this->get_bg_string($args)."></div>";
				$output .= "</div>";


				return $output;
			}
			
			function get_bg_string($args)
			{
				$style = "";
			
				if(!empty($args['attachment']))
				{
					$image = false;
					$src = wp_get_attachment_image_src($args['attachment'], $args['attachment_size']);
					if(!empty($src[0])) $image = $src[0];
					
					
					if($image)
					{
						$bg 	= !empty($args['background_color']) ? 		$args['background_color'] : "transparent"; $bg = "transparent";
						$pos 	= !empty($args['background_position'])  ? 	$args['background_position'] : "center center";
						$repeat = !empty($args['background_repeat']) ?		$args['background_repeat'] : "no-repeat";
						$extra	= "";
						
						if($repeat == "stretch")
						{
							$repeat = "no-repeat";
							$extra = "background-size: cover;";
						}
						
						if($repeat == "contain")
						{
							$repeat = "no-repeat";
							$extra = "background-size: contain;";
						}
						
						
						
						$style = "style='background: $bg url($image) $repeat $pos; $extra'";
					}
					
				}
				
				return $style;
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
			    global  $avia_config;

				$this->elements = array(
					

					array(
							"type" 	=> "tab_container", 'nodescription' => true
						),
					
					 array(
						"type" 	=> "tab",
						"name"  => __("Settings" , 'avia_framework'),
						'nodescription' => true
					),
				
					array(	
							"name" 	=> __("Tab Title", 'avia_framework' ),
							"desc" 	=> __("Set a tab title", 'avia_framework' ),
							"id" 	=> "tab_title",
							"type" 	=> "input",
							"std" 	=> "",
						),
					
					array(	
							"name" 	=> __("Vertical align", 'avia_framework' ),
							"desc" 	=> __("Choose the vertical alignment of your tab content. (only applies if tabs are set to fixed height)", 'avia_framework' ),
							"id" 	=> "vertical_align",
							"type" 	=> "select",
							"std" 	=> "middle",
							"subtype" => array(
								__('Top',   'avia_framework' ) =>'top',
								__('Middle',  'avia_framework' ) =>'middle',
								__('Bottom',   'avia_framework' ) =>'bottom',
							)),	
							
					array(
                            "name" 	=> __("Tab Symbol", 'avia_framework' ),
                            "desc" 	=> __("Should an icon or image be displayed at the top of the tab title?", 'avia_framework' ),
                            "id" 	=> "icon_select",
                            "type" 	=> "select",
                            "std" 	=> "no",
                            "subtype" => array(
                                __('No icon or image',  'avia_framework' ) =>'no',
                                __('Display icon',  'avia_framework' ) =>'icon_top',
                                __('Display image',  'avia_framework' ) =>'image_top')),

                        array(
                            "name" 	=> __("Tab Icon",'avia_framework' ),
                            "desc" 	=> __("Select an icon for your tab title below",'avia_framework' ),
                            "id" 	=> "icon",
                            "type" 	=> "iconfont",
                            "std" 	=> "",
                            "required" => array('icon_select','equals','icon_top')
                        ),
                        
                        array(
									"name" 	=> __("Tab Image",'avia_framework' ),
									"desc" 	=> __("Either upload a new, or choose an existing image from your media library",'avia_framework' ),
									"id" 	=> "tab_image",
									"type" 	=> "image",
									"fetch" => "id",
									"secondary_img" => true,
									"force_id_fetch"=> true,
									"title" =>  __("Insert Image",'avia_framework' ),
									"button" => __("Insert",'avia_framework' ),
									"required" => array('icon_select','equals','image_top'),
									"std" 	=> ""),
					
						
						array(
                            "name" 	=> __("Tab Image Style", 'avia_framework' ),
                            "id" 	=> "tab_image_style",
                            "type" 	=> "select",
                            "std" 	=> "",
							"required" => array('icon_select','equals','image_top'),
                            "subtype" => array(
                                __('No special style',  'avia_framework' ) =>'',
                                __('Rounded Borders',  'avia_framework' ) =>'av-tab-image-rounded',
                                __('Circle',  'avia_framework' ) =>'av-tab-image-circle',
                               )),
						
						
					array(
							"type" 	=> "close_div",
							'nodescription' => true
						), 
				  
				  array(
						"type" 	=> "tab",
						"name"  => __("Colors" , 'avia_framework'),
						'nodescription' => true
					),
					
					array(	
							"name" 	=> __("Active Tab Font Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom font color for the active tab here. Leave empty for default color", 'avia_framework' ),
							"id" 	=> "color",
							"type" 	=> "colorpicker",
							"std" 	=> "",
						),
					
					
					array(	
							"name" 	=> __("Custom Background Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom background color for this section here. Leave empty for default color", 'avia_framework' ),
							"id" 	=> "background_color",
							"type" 	=> "colorpicker",
							"std" 	=> "",
						),
						
					array(
							"name" 	=> __("Custom Background Image",'avia_framework' ),
							"desc" 	=> __("Either upload a new, or choose an existing image from your media library. Leave empty if you don't want to use a background image ",'avia_framework' ),
							"id" 	=> "src",
							"type" 	=> "image",
							"title" => __("Insert Image",'avia_framework' ),
							"button" => __("Insert",'avia_framework' ),
							"std" 	=> ""),
					
					array(
						"name" 	=> __("Background Attachment",'avia_framework' ),
						"desc" 	=> __("Background can either scroll with the page or be fixed", 'avia_framework' ),
						"id" 	=> "background_attachment",
						"type" 	=> "select",
						"std" 	=> "scroll",
                        "required" => array('src','not',''),
						"subtype" => array(
							__('Scroll','avia_framework' )=>'scroll',
							__('Fixed','avia_framework' ) =>'fixed',
							)
						),
					
                    array(
						"name" 	=> __("Background Image Position",'avia_framework' ),
						"id" 	=> "background_position",
						"type" 	=> "select",
						"std" 	=> "top left",
                        "required" => array('src','not',''),
						"subtype" => array(   __('Top Left','avia_framework' )       =>'top left',
						                      __('Top Center','avia_framework' )     =>'top center',
						                      __('Top Right','avia_framework' )      =>'top right',
						                      __('Bottom Left','avia_framework' )    =>'bottom left',
						                      __('Bottom Center','avia_framework' )  =>'bottom center',
						                      __('Bottom Right','avia_framework' )   =>'bottom right',
						                      __('Center Left','avia_framework' )    =>'center left',
						                      __('Center Center','avia_framework' )  =>'center center',
						                      __('Center Right','avia_framework' )   =>'center right'
						                      )
				    ),

	               array(
						"name" 	=> __("Background Repeat",'avia_framework' ),
						"id" 	=> "background_repeat",
						"type" 	=> "select",
						"std" 	=> "no-repeat",
                        "required" => array('src','not',''),
						"subtype" => array(   __('No Repeat','avia_framework' )          =>'no-repeat',
						                      __('Repeat','avia_framework' )             =>'repeat',
						                      __('Tile Horizontally','avia_framework' )  =>'repeat-x',
						                      __('Tile Vertically','avia_framework' )    =>'repeat-y',
						                      __('Stretch to fit (stretches image to cover the element)','avia_framework' )     =>'stretch',
						                      __('Scale to fit (scales image so the whole image is always visible)','avia_framework' )     =>'contain'
						                      )
				  ),
				  
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
				global $avia_config;
				
				$atts = shortcode_atts(array(
					
					'tab_title'				=> '',
					'vertical_align'		=> '',
					'color'					=> '',
					'background_color'		=> '',
					'background_position' 	=> '',
					'background_repeat' 	=> '',
					'background_attachment' => '',
					'fetch_image'			=> '',
					'attachment_size'		=> '',
					'attachment'			=> '',
					'icon'					=> '',
					'font'					=> '',
					'icon_select'			=> 'no',
					'tab_image'				=> '',
					'tab_image_style'		=> '',
					
					
				
				), $atts, $this->config['shortcode']);
				
				if(avia_sc_tab_sub_section::$attr['content_height'] == "av-tab-content-auto"){
					$atts['vertical_align'] = "top";
				}
				
				avia_sc_tab_section::$tab += 1;
				avia_sc_tab_section::$tab_titles[ avia_sc_tab_section::$tab ] = !empty($atts['tab_title']) ? $atts['tab_title'] : "";
				avia_sc_tab_section::$tab_atts[ avia_sc_tab_section::$tab ] = $atts;
				
				
				$extraClass	 	= "";
				$outer_style 	= "";
				$data		 	= "";
				$display_char 	= av_icon($atts['icon'], $atts['font']);
				$icon			= "";
				$image 			= "";
				
				if($atts['icon_select'] == "icon_top")
	            {
	                avia_sc_tab_section::$tab_icons[ avia_sc_tab_section::$tab ] = "<span class='av-tab-section-icon' {$display_char}></span>";
	            }
	            
	            if($atts['icon_select'] == "image_top")
	            {
	            	if(!empty($atts['tab_image']))
	            	{
	            		$src = wp_get_attachment_image_src($atts['tab_image'], 'square');
	            		
	            		if(!empty($src[0])){
	            	    	avia_sc_tab_section::$tab_images[ avia_sc_tab_section::$tab ] = "<img class='av-tab-section-image' src='".$src[0]."' alt='' title='' />";
						}
					}
				}
				
				if(!empty($atts['attachment']))
				{
					$src = wp_get_attachment_image_src($atts['attachment'], $atts['attachment_size']);
					if(!empty($src[0])) $atts['fetch_image'] = $src[0];
				}
				
				if(!empty($atts['color']))
				{
					$data .= "data-av-tab-color='".$atts['color']."' ";
				}
				
				if(!empty($atts['background_color']))
				{
					$data .= "data-av-tab-bg-color='".$atts['background_color']."' ";
				}
				
				if($atts['background_repeat'] == "stretch")
				{
					$extraClass .= " avia-full-stretch";
					$atts['background_repeat'] = "no-repeat";
				}
				
				if($atts['background_repeat'] == "contain")
				{
					$extraClass .= " avia-full-contain";
					$atts['background_repeat'] = "no-repeat";
				}
				
				
				
				if(!empty($atts['fetch_image']))
				{
					$outer_style .= AviaHelper::style_string($atts, 'fetch_image', 'background-image');
					$outer_style .= AviaHelper::style_string($atts, 'background_position', 'background-position');
					$outer_style .= AviaHelper::style_string($atts, 'background_repeat', 'background-repeat');
					$outer_style .= AviaHelper::style_string($atts, 'background_attachment', 'background-attachment');
				}
				
				$outer_style .= AviaHelper::style_string($atts, 'vertical_align', 'vertical-align');
				$outer_style .= AviaHelper::style_string($atts, 'padding');
				$outer_style .= AviaHelper::style_string($atts, 'background_color', 'background-color');
				
				
				if(!empty($outer_style)) $outer_style = "style='".$outer_style."'";
								
				$avia_config['current_column'] = $shortcodename;
				
				if( ! isset( avia_sc_tab_sub_section::$attr['initial'] ) )
				{
					avia_sc_tab_sub_section::$attr['initial'] = 1;
				}
				else if( avia_sc_tab_sub_section::$attr['initial'] <= 0 )
				{
					avia_sc_tab_sub_section::$attr['initial'] = 1;
				}
				else if( avia_sc_tab_sub_section::$attr['initial'] > avia_sc_tab_section::$tab ) 
				{
					avia_sc_tab_sub_section::$attr['initial'] = avia_sc_tab_section::$tab;
				}
				
				$active_tab = avia_sc_tab_section::$tab == avia_sc_tab_sub_section::$attr['initial'] ? "av-active-tab-content __av_init_open" : "";
				
				$tab_link = AviaHelper::valid_href( $atts['tab_title'], '-', 'av-tab-section-' . avia_sc_tab_section::$count . '-' . avia_sc_tab_section::$tab );
				
				$output   = '<div data-av-tab-section-content="'.avia_sc_tab_section::$tab.'" class="av-layout-tab av-animation-delay-container '.$active_tab.' '.$meta['el_class'].' '.$extraClass.' '.avia_sc_tab_sub_section::$extraClass.'" '.$outer_style.' '.$data.' data-tab-section-id="'.$tab_link.'">';
				$output  .= "<div class='av-layout-tab-inner'>";
				$output  .= "<div class='container'>";
				//if the user uses the column shortcode without the layout builder make sure that paragraphs are applied to the text
				$content =  (empty($avia_config['conditionals']['is_builder_template'])) ? ShortcodeHelper::avia_apply_autop(ShortcodeHelper::avia_remove_autop($content)) : ShortcodeHelper::avia_remove_autop($content, true);
				$output .= $content.'</div>';
				$output .= '</div>';
				$output .= '</div>';

				unset($avia_config['current_column']);
					
				return $output;
			}
			
	}
}