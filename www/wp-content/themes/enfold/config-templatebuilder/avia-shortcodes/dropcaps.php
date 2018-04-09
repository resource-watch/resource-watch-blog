<?php
/**
 * DROPCAPS
 * 
 * Shortcode which creates dropcaps
 */
 
 // Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }



if ( !class_exists( 'av_dropcap1' ) ) 
{
	class av_dropcap1 extends aviaShortcodeTemplate{
			
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'no';
				
				$this->config['name']		= 'Dropcap 1 (Big Letter)';
				$this->config['order']		= 100;
				$this->config['shortcode'] 	= 'av_dropcap1';
				$this->config['inline'] 	= true;
				$this->config['html_renderer'] 	= false;
				$this->config['tinyMCE'] 	= array('tiny_only'=>true, 'instantInsert' => "[av_dropcap1]H[/av_dropcap1]ello");
				
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

				$atts =  shortcode_atts(array('color' => '','custom_bg' => '#444444'), $atts, $this->config['shortcode']);
				
				$add_p = "";
				$custom_class = !empty($meta['custom_class']) ? $meta['custom_class'] : "";
				if(isset($post->post_content) && strpos($post->post_content, '[dropcap') === 0 && $avia_add_p == false && is_singular())
				{
					$add_p = "<p>";
					$avia_add_p = true;
				}

				if(!empty($atts['color']))
				{
					$color = ($atts['color'] == 'custom' && !empty($atts['custom_bg'])) ? $atts['custom_bg'] : $atts['color'];
				}

				$style = !empty($color) ? 'style="background-color:'.$color.'"' : '';
				
				//this is the actual shortcode
				$output  = $add_p.'<span class="'.$shortcodename." ".$custom_class.'" '.$style.'>';
				$output .= $content;
				$output .= '</span>';	
				
			
				return $output;
			}
	}
}


if ( !class_exists( 'av_dropcap2' ) ) 
{
	class av_dropcap2 extends av_dropcap1{
			
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'no';
				
				$this->config['name']		= 'Dropcap 2 (Colored Background)';
				$this->config['order']		= 90;
				$this->config['shortcode'] 	= 'av_dropcap2';
				$this->config['html_renderer'] 	= false;
				$this->config['inline'] 	= true;
				$this->config['tinyMCE'] 	= array('tiny_only'=>true, 'templateInsert'=>'[av_dropcap2 color="{{color}}" custom_bg="{{custom_bg}}"]H[/av_dropcap2]ello');
				//$this->config['modal_data'] = array('modal_class' => 'smallscreen');
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
							"name" 	=> __("Dropcap Styling", 'avia_framework' ),
							"desc" 	=> __("Here you can set the background color of your Dropcap", 'avia_framework' ),
							"id" 	=> "color",
							"type" 	=> "select",
							"std" 	=> "default",
							"subtype" => array(	__('Theme Color', 'avia_framework' ) =>'default',
												__('Custom Color', 'avia_framework') => 'custom',
												__('Blue', 'avia_framework' )=>'blue',
												__('Red',  'avia_framework' )=>'red',
												__('Green', 'avia_framework' )=>'green',
												__('Orange', 'avia_framework' )=>'orange',
												__('Aqua', 'avia_framework' )=>'aqua',
												__('Teal', 'avia_framework' )=>'teal',
												__('Purple', 'avia_framework' )=>'purple',
												__('Pink', 'avia_framework' )=>'pink',
												__('Silver', 'avia_framework' )=>'silver',
												__('Grey', 'avia_framework' )=>'grey',
												__('Black', 'avia_framework' )=>'black',
												)),

					array(	
							"name" 	=> __("Custom Background Color", 'avia_framework' ),
							"desc" 	=> __("Select a custom background color for your dropcap here", 'avia_framework' ),
							"id" 	=> "custom_bg",
							"type" 	=> "colorpicker",
							"std" 	=> "#444444",
							"required" => array('color','equals','custom')
						),

				);

			}
	}
}



