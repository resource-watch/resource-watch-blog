<?php
/**
 * COLUMNS
 * 
 * Shortcode which creates columns for better content separation
 */

 // Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }



if ( !class_exists( 'avia_sc_cell' ) )
{
	class avia_sc_cell extends aviaShortcodeTemplate{

			static $extraClass = "";
			static $attr = array();
			
			/**
			 * All available column width sizes
			 * 
			 * @since 4.2.1
			 * @var array 
			 */
			static private $size_array = array(
										'av_cell_one_full' => '1/1', 
										'av_cell_one_half' => '1/2', 
										'av_cell_one_third' => '1/3', 
										'av_cell_one_fourth' => '1/4', 
										'av_cell_one_fifth' => '1/5', 
										'av_cell_two_third' => '2/3', 
										'av_cell_three_fourth' => '3/4', 
										'av_cell_two_fifth' => '2/5', 
										'av_cell_three_fifth' => '3/5', 
										'av_cell_four_fifth' => '4/5'
									);
			
			/**
			 * Define the width for a cell
			 * 
			 * @since 4.2.1
			 * @var array 
			 */
			static $size_width = array(	
										'av_cell_one_full' 		=> 1.0, 
										'av_cell_one_half' 		=> 0.5, 
										'av_cell_one_third' 	=> 0.33, 
										'av_cell_one_fourth' 	=> 0.25, 
										'av_cell_one_fifth' 	=> 0.2, 
										'av_cell_two_third' 	=> 0.66, 
										'av_cell_three_fourth' 	=> 0.75, 
										'av_cell_two_fifth' 	=> 0.4, 
										'av_cell_three_fifth' 	=> 0.6, 
										'av_cell_four_fifth' 	=> 0.8
									);
			
			/**
			 * This constructor is implicity called by all derived classes
			 * To avoid duplicating code we put this in the constructor
			 * 
			 * @since 4.2.1
			 * @param AviaBuilder $builder
			 */
			public function __construct( $builder ) 
			{
				parent::__construct( $builder );
				
				$this->config['type']				=	'layout';		
				$this->config['self_closing']		=	'no';
				$this->config['contains_text']		=	'no';
				$this->config['contains_layout']	=	'yes';
				$this->config['contains_content']	=	'yes';
//				$this->config['first_in_row']		=	'first';
				
			}

			
			/**
			 * Returns the width of the cells. As this is the base class for all cells we only need to implement it here.
			 * 
			 * @since 4.2.1
			 * @return float
			 */
			public function get_element_width()
			{
				return isset( avia_sc_cell::$size_width[ $this->config['shortcode'] ] ) ? avia_sc_cell::$size_width[ $this->config['shortcode'] ] : 1.0;
			}

			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['invisible'] = true;
				$this->config['name']		= '1/1';
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-full.png";
				$this->config['tab']		= __('Layout Elements', 'avia_framework' );
				$this->config['order']		= 100;
				$this->config['target']		= "avia-section-drop";
				$this->config['shortcode'] 	= 'av_cell_one_full';
				$this->config['html_renderer'] 	= false;
				$this->config['tinyMCE'] 	= array('disable' => "true");
				$this->config['tooltip'] 	= __('Creates a single full width column', 'avia_framework' );
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
				
				extract($params);
				
				if(empty($data)) $data = array();
				
				$name 		= $this->config['shortcode'];
				$drag 		= $this->config['drag-level'];
				$drop 		= $this->config['drop-level'];
				
				$data['shortcodehandler'] 	= $this->config['shortcode'];
				$data['modal_title'] 		= __('Edit Cell','avia_framework' );
				$data['modal_ajax_hook'] 	= $this->config['shortcode'];
				$data['dragdrop-level']		= $this->config['drag-level'];
				$data['allowed-shortcodes'] = $this->config['shortcode'];
				
				if(!empty($this->config['modal_on_load']))
				{
					$data['modal_on_load'] 	= $this->config['modal_on_load'];
				}
	
				$dataString  = AviaHelper::create_data_string($data);
				
				$el_bg = !empty($args['background_color']) ? " style='background:".$args['background_color'].";'" : "";
				
				

				$output  = "<div class='avia_layout_column avia_layout_cell avia_pop_class avia-no-visual-updates ".$name." av_drag' {$dataString} data-width='{$name}'>";
				$output .= "<div class='avia_sorthandle'>";

				$output .= "<span class='avia-col-size'><span class='avia-element-bg-color' ".$el_bg."></span>".avia_sc_cell::$size_array[$name]."</span>";
				$output .= "<a class='avia-delete'  href='#delete' title='".__('Delete Cell','avia_framework' )."'>x</a>";
				$output .= "<a class='avia-clone'  href='#clone' title='".__('Clone Cell','avia_framework' )."' >".__('Clone Cell','avia_framework' )."</a>";
				
				if(!empty($this->config['popup_editor']))
    			{
    				$output .= "    <a class='avia-edit-element'  href='#edit-element' title='".__('Edit Cell','avia_framework' )."'>edit</a>";
    			}
				
				$output .= "</div><div class='avia_inner_shortcode avia_connect_sort av_drop ' data-dragdrop-level='{$drop}'><span class='av-fake-cellborder'></span>";
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
							"name" 	=> __("Vertical align", 'avia_framework' ),
							"desc" 	=> __("Choose the vertical alignment of your cells content.", 'avia_framework' ),
							"id" 	=> "vertical_align",
							"type" 	=> "select",
							"std" 	=> "top",
							"subtype" => array(
								__('Top',   'avia_framework' ) =>'top',
								__('Middle',  'avia_framework' ) =>'middle',
								__('Bottom',   'avia_framework' ) =>'bottom',
							)),	
					
					array(	
							"name" 	=> __("Cell Padding", 'avia_framework' ),
							"desc" 	=> __("Set the distance from the cell content to the border here. Both pixel and &percnt; based values are accepted. eg: 30px, 5&percnt;", 'avia_framework' ),
							"id" 	=> "padding",
							"type" 	=> "multi_input",
							"std" 	=> "30px",
							"sync" 	=> true,
							"multi" => array(	'top' 	=> __('Padding-Top','avia_framework'), 
												'right'	=> __('Padding-Right','avia_framework'), 
												'bottom'=> __('Padding-Bottom','avia_framework'),
												'left'	=> __('Padding-Left','avia_framework'), 
												)
						),
						
					
					
					array(	
							"name" 	=> __("Custom Background Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom background color for this cell here. Leave empty for default color", 'avia_framework' ),
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
						"type" 	=> "tab",
						"name"  => __("Screen Options" , 'avia_framework'),
						'nodescription' => true
					),
				
				array(
								"name" 	=> __("Element Visibility",'avia_framework' ),
								"desc" 	=> 
								__("Set the visibility for this element, based on the device screensize.", 'avia_framework' )."<br><small>".
								__("In order to prevent breaking the layout it is only possible to change the visibility settings for cells once they take up the full screen width, which means only on mobile devices", 'avia_framework' )."</small>",
								
								"type" 	=> "heading",
								"description_class" => "av-builder-note av-neutral",
					),
					
					
				array(	
						"name" 	=> __("Mobile display", 'avia_framework' ),
						"desc" 	=> __("Display settings for this element when viewed on smaller screens", 'avia_framework' ),
						"id" 	=> "mobile_display",
						"type" 	=> "select",
						"std" 	=> "",
						"subtype" => array(	
								__('Always display','avia_framework' ) =>'',
								//__('Hide on tablet and smaller devices',  'avia_framework' ) =>'av-hide-on-tablet',
								__('Hide on mobile devices',  'avia_framework' ) =>'av-hide-on-mobile',
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
					'vertical_align'		=> '',
					'padding'				=> '',
					'color'					=> '',
					'background_color'		=> '',
					'background_position' 	=> '',
					'background_repeat' 	=> '',
					'background_attachment' => '',
					'fetch_image'			=> '',
					'attachment_size'		=> '',
					'attachment'			=> '',
					'mobile_display'		=> ''
				
				), $atts, $this->config['shortcode']);
				
				$extraClass	 = "";
				$outer_style = "";
				$inner_style = "";
				
				if(!empty(avia_sc_cell::$attr['min_height']) && empty(avia_sc_cell::$attr['min_height_percent']))
				{
					$min = (int) avia_sc_cell::$attr['min_height'];
					$outer_style = "height:{$min}px; min-height:{$min}px;";
				}
				
				
				if(!empty($atts['attachment']))
				{
					$src = wp_get_attachment_image_src($atts['attachment'], $atts['attachment_size']);
					if(!empty($src[0])) $atts['fetch_image'] = $src[0];
				}
				
				if(!empty($atts['color']))
				{
					$extraClass .= " av_inherit_color";
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

				
				
				$explode_padding = explode(',',$atts['padding']);
				if(count($explode_padding) > 1)
				{
					$atts['padding'] = "";
					foreach($explode_padding as $value)
					{
						if(empty($value)) $value = "0";
						$atts['padding'] .= $value ." ";
					}
				}
				
				if($atts['padding'] == "0px" || $atts['padding'] == "0" || $atts['padding'] == "0%")
				{
					$extraClass .= " av-zero-padding";
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
				
				
				$shortcodename = str_replace('av_cell_', 'av_', $shortcodename);
				
				$avia_config['current_column'] = $shortcodename;
				
				if(!empty($outer_style)) $outer_style = "style='".$outer_style."'";
				if(!empty($inner_style)) $inner_style = "style='".$inner_style."'";
				
				$extraClass .= empty($atts['mobile_display']) ? "" : " ".$atts['mobile_display']." ";
				
				$output   = '<div class="flex_cell no_margin '.$shortcodename.' '.$meta['el_class'].' '.$extraClass.' '.avia_sc_cell::$extraClass.'" '.$outer_style.'>';
				$output  .= "<div class='flex_cell_inner' {$inner_style}>";
				//if the user uses the column shortcode without the layout builder make sure that paragraphs are applied to the text
				$content =  (empty($avia_config['conditionals']['is_builder_template'])) ? ShortcodeHelper::avia_apply_autop(ShortcodeHelper::avia_remove_autop($content)) : ShortcodeHelper::avia_remove_autop($content, true);
		
				$output .= $content.'</div>';
				$output .= '</div>';
				
				unset($avia_config['current_column']);

				return $output;
			}
			
	}
}









if ( !class_exists( 'avia_sc_cell_one_half' ) )
{
	class avia_sc_cell_one_half extends avia_sc_cell{

			function shortcode_insert_button()
			{
				$this->config['invisible'] = true;
				$this->config['name']		= '1/2';
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-half.png";
				$this->config['tab']		= __('Layout Elements', 'avia_framework' );
				$this->config['order']		= 90;
				$this->config['target']		= "avia-section-drop";
				$this->config['shortcode'] 	= 'av_cell_one_half';
				$this->config['html_renderer'] 	= false;
				$this->config['tinyMCE'] 	= array('disable' => "true");
				$this->config['tooltip'] 	= __('Creates a single column with 50&percnt; width', 'avia_framework' );
				$this->config['drag-level'] = 2;
				$this->config['drop-level'] = 1;
		}
	}
}


if ( !class_exists( 'avia_sc_cell_one_third' ) )
{
	class avia_sc_cell_one_third extends avia_sc_cell{

			function shortcode_insert_button()
			{
				$this->config['invisible'] = true;
				$this->config['name']		= '1/3';
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-third.png";
				$this->config['tab']		= __('Layout Elements', 'avia_framework' );
				$this->config['order']		= 80;
				$this->config['target']		= "avia-section-drop";
				$this->config['shortcode'] 	= 'av_cell_one_third';
				$this->config['html_renderer'] 	= false;
				$this->config['tooltip'] 	= __('Creates a single column with 33&percnt; width', 'avia_framework' );
				$this->config['drag-level'] = 2;
				$this->config['drop-level'] = 1;
				$this->config['tinyMCE'] 	= array('disable' => "true");
			}
	}
}

if ( !class_exists( 'avia_sc_cell_two_third' ) )
{
	class avia_sc_cell_two_third extends avia_sc_cell{

			function shortcode_insert_button()
			{
				$this->config['invisible'] = true;
				$this->config['name']		= '2/3';
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-two_third.png";
				$this->config['tab']		= __('Layout Elements', 'avia_framework' );
				$this->config['order']		= 70;
				$this->config['target']		= "avia-section-drop";
				$this->config['shortcode'] 	= 'av_cell_two_third';
				$this->config['html_renderer'] 	= false;
				$this->config['tooltip'] 	= __('Creates a single column with 67&percnt; width', 'avia_framework' );
				$this->config['drag-level'] = 2;
				$this->config['drop-level'] = 1;
				$this->config['tinyMCE'] 	= array('disable' => "true");
			}
	}
}

if ( !class_exists( 'avia_sc_cell_one_fourth' ) )
{
	class avia_sc_cell_one_fourth extends avia_sc_cell{

			function shortcode_insert_button()
			{
				$this->config['invisible'] = true;
				$this->config['name']		= '1/4';
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-fourth.png";
				$this->config['tab']		= __('Layout Elements', 'avia_framework' );
				$this->config['order']		= 60;
				$this->config['target']		= "avia-section-drop";
				$this->config['shortcode'] 	= 'av_cell_one_fourth';
				$this->config['tooltip'] 	= __('Creates a single column with 25&percnt; width', 'avia_framework' );
				$this->config['html_renderer'] 	= false;
				$this->config['drag-level'] = 2;
				$this->config['drop-level'] = 1;
				$this->config['tinyMCE'] 	= array('disable' => "true");
			}
	}
}

if ( !class_exists( 'avia_sc_cell_three_fourth' ) )
{
	class avia_sc_cell_three_fourth extends avia_sc_cell{

			function shortcode_insert_button()
			{
				$this->config['invisible'] = true;
				$this->config['name']		= '3/4';
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-three_fourth.png";
				$this->config['tab']		= __('Layout Elements', 'avia_framework' );
				$this->config['order']		= 50;
				$this->config['target']		= "avia-section-drop";
				$this->config['shortcode'] 	= 'av_cell_three_fourth';
				$this->config['tooltip'] 	= __('Creates a single column with 75&percnt; width', 'avia_framework' );
				$this->config['html_renderer'] 	= false;
				$this->config['drag-level'] = 2;
				$this->config['drop-level'] = 1;
				$this->config['tinyMCE'] 	= array('disable' => "true");
			}
	}
}

if ( !class_exists( 'avia_sc_cell_one_fifth' ) )
{
	class avia_sc_cell_one_fifth extends avia_sc_cell{

			function shortcode_insert_button()
			{
				$this->config['invisible'] = true;
				$this->config['name']		= '1/5';
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-fifth.png";
				$this->config['tab']		= __('Layout Elements', 'avia_framework' );
				$this->config['order']		= 40;
				$this->config['target']		= "avia-section-drop";
				$this->config['shortcode'] 	= 'av_cell_one_fifth';
				$this->config['html_renderer'] 	= false;
				$this->config['tooltip'] 	= __('Creates a single column with 20&percnt; width', 'avia_framework' );
				$this->config['drag-level'] = 2;
				$this->config['drop-level'] = 1;
				$this->config['tinyMCE'] 	= array('disable' => "true");
			}
	}
}

if ( !class_exists( 'avia_sc_cell_two_fifth' ) )
{
	class avia_sc_cell_two_fifth extends avia_sc_cell{

			function shortcode_insert_button()
			{
				$this->config['invisible'] = true;
				$this->config['name']		= '2/5';
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-two_fifth.png";
				$this->config['tab']		= __('Layout Elements', 'avia_framework' );
				$this->config['order']		= 39;
				$this->config['target']		= "avia-section-drop";
				$this->config['shortcode'] 	= 'av_cell_two_fifth';
				$this->config['html_renderer'] 	= false;
				$this->config['tooltip'] 	= __('Creates a single column with 40&percnt; width', 'avia_framework' );
				$this->config['drag-level'] = 2;
				$this->config['drop-level'] = 1;
				$this->config['tinyMCE'] 	= array('disable' => "true");
			}
	}
}

if ( !class_exists( 'avia_sc_cell_three_fifth' ) )
{
	class avia_sc_cell_three_fifth extends avia_sc_cell{

			function shortcode_insert_button()
			{
				$this->config['invisible'] = true;
				$this->config['name']		= '3/5';
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-three_fifth.png";
				$this->config['tab']		= __('Layout Elements', 'avia_framework' );
				$this->config['order']		= 38;
				$this->config['target']		= "avia-section-drop";
				$this->config['shortcode'] 	= 'av_cell_three_fifth';
				$this->config['html_renderer'] 	= false;
				$this->config['tooltip'] 	= __('Creates a single column with 60&percnt; width', 'avia_framework' );
				$this->config['drag-level'] = 2;
				$this->config['drop-level'] = 1;
				$this->config['tinyMCE'] 	= array('disable' => "true");
			}
	}
}

if ( !class_exists( 'avia_sc_cell_four_fifth' ) )
{
	class avia_sc_cell_four_fifth extends avia_sc_cell{

			function shortcode_insert_button()
			{
				$this->config['invisible'] = true;
				$this->config['name']		= '4/5';
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-four_fifth.png";
				$this->config['tab']		= __('Layout Elements', 'avia_framework' );
				$this->config['order']		= 37;
				$this->config['target']		= "avia-section-drop";
				$this->config['shortcode'] 	= 'av_cell_four_fifth';
				$this->config['html_renderer'] 	= false;
				$this->config['tooltip'] 	= __('Creates a single column with 80&percnt; width', 'avia_framework' );
				$this->config['drag-level'] = 2;
				$this->config['drop-level'] = 1;
				$this->config['tinyMCE'] 	= array('disable' => "true");
			}
	}
}


