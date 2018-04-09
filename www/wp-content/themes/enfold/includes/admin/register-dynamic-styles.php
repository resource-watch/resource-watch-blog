<?php

function avia_prepare_dynamic_styles($options = false)
{
	global $avia_config;

	if(!$options) $options 		= avia_get_option();
	$color_set 	= $styles		= array();
	$post_id 					= avia_get_the_ID();
	$options 					= apply_filters('avia_pre_prepare_colors', $options);

	if($options === "") { $options = array(); }
	
	//boxed or stretched layout
	$avia_config['box_class'] = empty($options['color-body_style']) ? "stretched" : $options['color-body_style'];
	
	//transparency color for header menu
	$avia_config['backend_colors']['menu_transparent'] = empty($options['header_replacement_menu']) ? "" : $options['header_replacement_menu']; 

	//custom color for burger menu
	$avia_config['backend_colors']['burger_color'] = empty($options['burger_color']) ? "" : $options['burger_color']; 
	
	//custom width for burger menu flyout
	$avia_config['backend_colors']['burger_flyout_width'] = empty($options['burger_flyout_width']) ? "" : $options['burger_flyout_width']; 
	
	//iterate over the options array to get the color and bg image options and save them to new array
	foreach ($options as $key => $option)
	{
		if(strpos($key, 'colorset-') === 0)
		{
			$newkey = explode('-', $key);

			//add the option to the new array
			$color_set[$newkey[1]][$newkey[2]] = $option;
		}

		if(strpos($key, 'color-') === 0)
		{
			$newkey = explode('-', $key);

			//add the option to the new array
			$styles[$newkey[1]] = $option;
		}
	}

	//make sure that main color is added later than alternate color so we can nest main color elements within alternate color elements and the styling is applied.
	$color_set = array_reverse($color_set);

	######################################################################
	# optimize the styles array and set teh background image and sizing
	######################################################################


		/* only needed if we got a boxed layout option */
		if(empty($styles['body_img'])) $styles['body_img'] = "";
		if(empty($styles['body_repeat'])) $styles['body_repeat'] = "no-repeat";
		if(empty($styles['body_attach'])) $styles['body_attach'] = "fixed";
		if(empty($styles['body_pos'])) $styles['body_pos'] = "top left";
		if(empty($styles['default_font_size'])) $styles['default_font_size'] = "";



		if($styles['body_img'] == 'custom')
		{
			$styles['body_img'] = $styles['body_customimage'];
			unset($styles['body_customimage']);
		}

		if($styles['body_repeat']  == 'fullscreen')
		{
			$styles['body_img'] = trim($styles['body_img']);
			if(!empty($styles['body_img'])) 
			{
				$avia_config['fullscreen_image'] = str_replace('{{AVIA_BASE_URL}}', AVIA_BASE_URL, $styles['body_img']);
			}
			unset($styles['body_img']);
			$styles['body_background'] = "";
		}
		else
		{
			$styles['body_img'] = trim($styles['body_img']);
			$url = empty($styles['body_img']) ? "" : "url(".$styles['body_img'].")";

			$bg = empty($styles['body_color']) ? 'transparent' : $styles['body_color'];
			$styles['body_background'] = "$bg  $url ".$styles['body_pos']."  ".$styles['body_repeat']." ".$styles['body_attach'];
		}
		/*
		*/



	######################################################################
	# optimize the array to make it smaller
	######################################################################

	foreach($color_set as $key => $set)
	{
		if($color_set[$key]['bg'] == '') 		$color_set[$key]['bg'] = 'transparent';
		if($color_set[$key]['bg2'] == '') 		$color_set[$key]['bg2'] = 'transparent';
		if($color_set[$key]['primary'] == '') 	$color_set[$key]['primary'] = 'transparent';
		if($color_set[$key]['secondary'] == '') $color_set[$key]['secondary'] = 'transparent';
		if($color_set[$key]['color'] == '') 	$color_set[$key]['color'] = 'transparent';
		if($color_set[$key]['border'] == '') 	$color_set[$key]['border'] = 'transparent';
		
		
		if($color_set[$key]['img'] == 'custom')
		{
			$color_set[$key]['img'] = $color_set[$key]['customimage'];
			unset($color_set[$key]['customimage']);
		}

		if($color_set[$key]['img'] == '')
		{
			unset($color_set[$key]['img'], $color_set[$key]['pos'], $color_set[$key]['repeat'], $color_set[$key]['attach']);
		}
		else
		{
			$bg = empty($color_set[$key]['bg']) ? 'transparent' : $color_set[$key]['bg'];
			
			$repeat = $color_set[$key]['repeat'] == "fullscreen" ? "no-repeat" : $color_set[$key]['repeat'];
			
			$color_set[$key]['img'] = trim($color_set[$key]['img']);
			$url = empty($color_set[$key]['img']) ? "" : "url(".$color_set[$key]['img'].")";

			$color_set[$key]['background_image'] = "$bg $url ".$color_set[$key]['pos']."  ".$repeat." ".$color_set[$key]['attach'];
		}

		if(isset($color_set[$key]['customimage'])) unset($color_set[$key]['customimage']);

		if(empty($color_set[$key]['heading']))
		{
			//checks if we have a dark or light background and then creates a stronger version of the main font color for headings
			$shade = avia_backend_calc_preceived_brightness($color_set[$key]['bg'], 100) ? 'lighter' : 'darker';
			$color_set[$key]['heading'] = avia_backend_calculate_similar_color($color_set[$key]['color'], $shade, 4);
		}
		
		if(empty($color_set[$key]['meta']))
		{
			// creates a new color from the background color and the heading color (results in a lighter color)
			$color_set[$key]['meta'] 	= avia_backend_merge_colors($color_set[$key]['heading'], $color_set[$key]['bg']);
		}
	}


	$avia_config['backend_colors']['color_set'] = $color_set;
	$avia_config['backend_colors']['style'] = $styles;

	require( AVIA_BASE.'css/dynamic-css.php');

}





