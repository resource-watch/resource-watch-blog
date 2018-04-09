<?php

//activate the update script
add_action('admin_init', array(new avia_update_helper(), 'update_version'));

/*all the functions that keep compatibility between theme versions: */

/*
 *
 * update for version 2.6: 
 * we need to map the single string that defines which header we are using to the multiple   
 * new options and save them, so the user does not need to manually update the header
 *
 * also the post specific layout option that shows/hides the title bar is saved with a new name and value set so it can easily overwrite the global option
 */
 
add_action('ava_trigger_updates', 'avia_map_header_setting',10,2);

function avia_map_header_setting($prev_version, $new_version)
{	
	//if the previous theme version is equal or bigger to 2.6 we don't need to update
	if(version_compare($prev_version, 2.6, ">=")) return; 
	
	//set global options
	global $avia;
	$theme_options = $avia->options['avia'];
	
	//if one of those settings is not available the user has never saved the theme options. No need to change anything
	if(empty($theme_options) || !isset($theme_options['header_setting'])) return;


	//set defaults
	$theme_options['header_layout'] 		= "logo_left menu_right";
	$theme_options['header_size'] 			= "slim";
	$theme_options['header_sticky'] 		= "header_sticky";
	$theme_options['header_shrinking'] 		= "header_shrinking";
	$theme_options['header_social'] 		= "";
	$theme_options['header_secondary_menu'] = "";
	$theme_options['header_phone_active']	= "";
	
	if(!empty($theme_options['phone'])) $theme_options['header_phone_active'] = "phone_active_right extra_header_active";
	
	//overwrite defaults based on the selection
	switch($theme_options['header_setting'])
	{
		case 'nonfixed_header': 
		
			$theme_options['header_sticky'] 	= "";
			$theme_options['header_shrinking'] 	= "";
			$theme_options['header_social'] 	= "";	
				
		break;
		case 'fixed_header social_header': 
		
			$theme_options['header_size'] 			= "large";
			$theme_options['header_social'] 		= "icon_active_left extra_header_active";
			$theme_options['header_secondary_menu'] = "secondary_right extra_header_active";
		
		break;
		case 'nonfixed_header social_header': 
		
			$theme_options['header_size'] 			= "large";
			$theme_options['header_sticky'] 		= "";
			$theme_options['header_shrinking'] 		= "";
			$theme_options['header_social'] 		= "icon_active_left extra_header_active";
			$theme_options['header_secondary_menu'] = "secondary_right extra_header_active";
		
		
		break;
		case 'nonfixed_header social_header bottom_nav_header': 
		
			$theme_options['header_layout'] 		= "logo_left bottom_nav_header";
			$theme_options['header_sticky'] 		= "";
			$theme_options['header_shrinking'] 		= "";
			$theme_options['header_social'] 		= "icon_active_main";
			$theme_options['header_secondary_menu'] = "secondary_right extra_header_active";
			
		break;
	}
	
	//replace existing options with the new options
	$avia->options['avia'] = $theme_options;
	update_option($avia->option_prefix, $avia->options);
	
	
	//update post specific options
    $getPosts = new WP_Query(
    	array(
	        'post_type'     => array( 'post', 'page', 'portfolio', 'product' ),
	        'post_status'   => 'publish',
	        'posts_per_page'=>-1,
	        'meta_query' => array(
	            array(
	                'key' => 'header'
	            )
	        )
	    ));
	    
	if(!empty($getPosts->posts))
	{
		foreach($getPosts->posts as $post)
		{
			$header_setting = get_post_meta( $post->ID, 'header', true );
			switch($header_setting)
			{
				case "yes": update_post_meta($post->ID, 'header_title_bar', ''); ; break;
				case "no":  update_post_meta($post->ID, 'header_title_bar', 'hidden_title_bar'); ; break;
			}
		}
	}
	
	
}


/*
 *
 * update for version 3.0: updates responsive option and splits it into multiple fields for more flexibility
 *
 */
 
 add_action('ava_trigger_updates', 'avia_update_grid_system',11,2);

function avia_update_grid_system($prev_version, $new_version)
{	
	//if the previous theme version is equal or bigger to 3.0 we don't need to update
	if(version_compare($prev_version, 3.0, ">=")) return; 
	
	//set global options
	global $avia;
	$theme_options = $avia->options['avia'];
	
	//if one of those settings is not available the user has never saved the theme options. No need to change anything
	if(empty($theme_options) ) return;
 	if(empty($theme_options['responsive_layout'])) $theme_options['responsive_layout'] = "responsive responsive_large";
 	
 	$responsive = "enabled";
 	$size		= "1130px";
 	
 	switch($theme_options['responsive_layout'])
 	{
 		case "responsive" : $responsive = "enabled"; break;
 		case "responsive responsive_large" : $responsive = "enabled"; $size = "1310px"; break;
 		case "static_layout" : $responsive = "disabled";  break;
 	}
 	
 	$theme_options['responsive_active'] = $responsive;
 	$theme_options['responsive_size']   = $size;
 	
 	//replace existing options with the new options
	$avia->options['avia'] = $theme_options;
	update_option($avia->option_prefix, $avia->options);
 }
 
 
 
 /*
 *
 * update for version 3.1: updates the main menu seperator setting in case a user had a bottom nav main menu
 * also adds the values for meta and heading to the theme options array so they can be set manually
 *
 */
 
add_action('ava_trigger_updates', 'avia_update_seperator_main',12,2);

function avia_update_seperator_main($prev_version, $new_version)
{	
	//if the previous theme version is equal or bigger to 3.1 we don't need to update
	if(version_compare($prev_version, 3.1, ">=")) return; 
	
	//set global options
	global $avia, $avia_config;
	$theme_options = $avia->options['avia'];

	
	//if one of those settings is not available the user has never saved the theme options. No need to change anything
	if(empty( $theme_options )) return;
	if(strpos($theme_options['header_layout'],'bottom_nav_header') !== false) 
	{
		$theme_options['header_menu_border'] = "seperator_big_border";
	}
	
	//removes the old calculated meta and heading colors and changes it to a custom color that can be set by the user
	$colorsets = $avia_config['color_sets'];
	if(!empty($colorsets))
	{
		foreach($colorsets as $set_key => $set_value)
		{
			if(isset($avia_config['backend_colors']['color_set'][$set_key]))
			{
				if(isset($avia_config['backend_colors']['color_set'][$set_key]['meta']))
				{
					$theme_options["colorset-$set_key-meta"] = $avia_config['backend_colors']['color_set'][$set_key]['meta'];
				}
				
				if(isset($avia_config['backend_colors']['color_set'][$set_key]['heading']))
				{
					$new_heading = $avia_config['backend_colors']['color_set'][$set_key]['heading'];
					
					if('footer_color' == $set_key)
					{
						$new_heading = $avia_config['backend_colors']['color_set'][$set_key]['meta'];
					}
					
					$theme_options["colorset-$set_key-heading"] = $new_heading;
				}
		
			}
		}
	}

 	
 	//replace existing options with the new options
	$avia->options['avia'] = $theme_options;
	update_option($avia->option_prefix, $avia->options);
 }
 
 
 
 /*
 *
 * update for version 3.1.4: update the widget locations to avoid error notice in 4.2 and to prevent the sorting bugs
 *
 */
 
add_action('ava_trigger_updates', 'avia_update_widget',13,2);

function avia_update_widget($prev_version, $new_version)
{	
	//if the previous theme version is equal or bigger to 3.1 we don't need to update
	if(version_compare($prev_version, '3.1.4', ">=")) return; 
	
	$map = array('av_everywhere', 'av_blog', 'av_pages');
	
	if(class_exists( 'woocommerce' ))
	{
		$map[] = 'av_shop_overview';
		$map[] = 'av_shop_single';
	}
	
	for ($i = 1; $i <= avia_get_option('footer_columns','5'); $i++)
	{
		$map[] = 'av_footer_'.$i;
	}
	
	if(class_exists( 'bbPress' ))
	{
		$map[] = 'av_forum';
	}
	
	$dynamic = get_option('avia_sidebars');
	if(is_array($dynamic) && !empty($dynamic))
	{
		foreach($dynamic as $key => $value)
		{
			$map[] = avia_backend_safe_string($value,'-'); 
		}
	}
	
	$current_sidebars = get_option('sidebars_widgets');
	
	if(!empty($current_sidebars) && isset($current_sidebars['sidebar-1']))
	{
		$new_sidebars = array('wp_inactive_widgets' => $current_sidebars['wp_inactive_widgets']);
		
		foreach($map as $key => $sidebar)
		{	
			if( isset( $current_sidebars['sidebar-'. ($key + 1)] ) )
			{
				$new_sidebars[ $sidebar ] = $current_sidebars['sidebar-'. ($key + 1)];
			}
		}
		
		update_option('sidebars_widgets', $new_sidebars);
	}
	

}
 
 
 
 
 
 /*
 *
 * update for version 4.0: updates the old layerslider datastructure to the new one so we can use the latest version of the slider
 *
 */
 
add_action('ava_trigger_updates', 'avia_update_layerslider_data_structure',14,2);

function avia_update_layerslider_data_structure($prev_version, $new_version)
{	
	//if the previous theme version is equal or bigger to 3.0 we don't need to update
	if(version_compare($prev_version, 4.0, ">=")) return; 
	
	// Get WPDB Object
    global $wpdb;
 
    // Table name
    $table_name = $wpdb->prefix . "layerslider";
 
    // Get sliders
    $sliders = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY date_c ASC LIMIT 300" );
 	
 	if(empty($sliders)) return;
 	
 	$easy_mapping = array(
	 	"easingin",
	 	"easingout",
	 	"delayin",
	 	"delayout",
	 	"durationin",
	 	"durationout",
	 	"showuntil",
	 	"rotateout",
	 	"rotatein",
 	);
 	
	foreach($sliders as $key => $item) 
    {
	    // we presume that no update is necessary. only after we check each slide data and the subslides we know if the transition property is available. 
	    // if it is not we need to update
	    $update_necessary 	= false;
	    $id 				= $item->id;
	    $layers 			= false;
	    $test_update_all 	= false; // testing - update everything
	    $uploaded_imgs		= array();
	    
	    if(isset($item->data))
	    {
		    $data = json_decode($item->data);
		    if(is_object($data) && !empty($data->layers)) $layers = $data->layers;
	    }
	    
		if($layers)
		{
			foreach($layers as $layer)
			{
				//update the subslides
				if(!empty($layer->sublayers))
				{
					foreach ($layer->sublayers as $sublayer )
					{
						if(empty($sublayer->transition) || $test_update_all )
						{
							$update_necessary = true;
							$sublayer->transition = array();
							
							//first map the easy conditions to the new slider. those are 1:1 translateable because the got the same key
							foreach($easy_mapping as $key)
							{
								if(!empty($sublayer->$key)){
									$sublayer->transition[$key] = $sublayer->$key;
								}
							}
							
							//now map the complicated stuff
							
							//slideIndirection
							if(!empty($sublayer->slidedirection))
							{
								if(in_array($sublayer->slidedirection, array('left','right','auto')))
								{
									$sublayer->transition['offsetxin'] = $sublayer->slidedirection;
									$sublayer->transition['offsetyin'] = "0";
								}
								else
								{
									$sublayer->transition['offsetyin'] = $sublayer->slidedirection;
									$sublayer->transition['offsetxin'] = "0";
								}
							}
							
							//slideOutdirection
							if(!empty($sublayer->slideoutdirection))
							{
								if(in_array($sublayer->slideoutdirection, array('left','right','auto')))
								{
									$sublayer->transition['offsetxout'] = $sublayer->slideoutdirection;
									$sublayer->transition['offsetyout'] = "0";
								}
								else
								{
									$sublayer->transition['offsetyout'] = $sublayer->slideoutdirection;
									$sublayer->transition['offsetxout'] = "0";
								}
							}
							
							$sublayer->transition = json_encode($sublayer->transition);
						}
						
						//update image links for old demo sliders in case they are still used by some users
						//eg old: http://wpoffice/layerslider-test-2/wp-content/themes/enfold/config-layerslider/LayerSlider/avia-samples/slide1_Layer_2.png
						//to new: http://www.kriesi.at/themes/wp-content/uploads/avia-sample-layerslides/slide1_Layer_2.png
						
						if(!empty($sublayer->image) && strpos($sublayer->image, "/config-layerslider/LayerSlider/") !== false)
						{
							update_option('enfold_layerslider_compat_update', 1);
							
							/*
							set_time_limit ( 0 );
							
							$image_name = basename($sublayer->image);
							
							if(isset($uploaded_imgs[$image_name]))
							{
								$sublayer->image = $new_url;
								$update_necessary = true;
							}
							else
							{
								$full_url 	= "http://www.kriesi.at/themes/wp-content/uploads/avia-sample-layerslides/" . $image_name;
								
								$new_url = media_sideload_image( $full_url , false, NULL, 'src');
								
								if(!is_object($new_url) && !empty($new_url))
								{
									$uploaded_imgs[$image_name] = $new_url;
									$sublayer->image = $new_url;
									$update_necessary = true;
								}
							}
							*/
						}
						
					}
				}
				
				
				//update images for the main slides

				if(isset($layer->properties))
				{
					if(isset($layer->properties->background) && strpos($layer->properties->background, "/config-layerslider/LayerSlider/") !== false)
					{
						update_option('enfold_layerslider_compat_update', 1);
						
						/*
						set_time_limit ( 0 );
						$image_name = basename($layer->properties->background);
						
						if(isset($uploaded_imgs[$image_name]))
						{
							$sublayer->image = $new_url;
							$update_necessary = true;
						}
						else
						{
							$full_url 	= "http://www.kriesi.at/themes/wp-content/uploads/avia-sample-layerslides/" . $image_name;
							$new_url = media_sideload_image( $full_url , false, NULL, 'src');
							
							if(!is_object($new_url) && !empty($new_url))
							{
								$uploaded_imgs[$image_name] = $new_url;
								$layer->properties->background = $new_url;
								$update_necessary = true;
							}
						}
						*/
						
						
					}
				}
			}
		}
	
		if($update_necessary)
		{
			$wpdb->update($table_name, array(
				'data' => json_encode($data),
			),
			array('id' => $id),
			array('%s')
			);
		}
    }
 }
 
 
 
  /*
 *
 * update for version 4.1: update the main menu icon and move the scale and color from advanced editor to a normal option
 *
 */
 
add_action('ava_trigger_updates', 'avia_update_menu_icon_advanced',15,2);

function avia_update_menu_icon_advanced($prev_version, $new_version)
{	
	//if the previous theme version is equal or bigger to 4.1 we don't need to update
	if(version_compare($prev_version, '4.1', ">=")) return; 
	
	//fetch advanced data
	global $avia;
	$theme_options = $avia->options['avia'];
	$advanced = avia_get_option('advanced_styling');
	
	
	if(isset($theme_options['menu_display']) && $theme_options['menu_display'] == 'burger_menu')
	{
		$theme_options['overlay_style'] = 'av-overlay-full';
		$theme_options['submenu_clone'] = 'av-submenu-noclone';
		$theme_options['submenu_visibility'] = 'av-submenu-hidden av-submenu-display-hover';
	}
	else
	{
		$theme_options['overlay_style'] = 'av-overlay-side av-overlay-side-classic';
		
		if(isset($theme_options['header_mobile_behavior']) && $theme_options['header_mobile_behavior'] != "")
		{
			$theme_options['submenu_visibility'] = 'av-submenu-hidden av-submenu-display-click';
		}
		else
		{
			$theme_options['submenu_visibility'] = '';
		}
		
		$theme_options['submenu_clone'] = 'av-submenu-noclone';
		
	}

	
	
	if(!empty($advanced))
	{
		foreach($advanced as $rule)
		{
			if(isset($rule) && $rule['id'] == 'main_menu_icon_style')
			{
				if(!empty($rule['color'])) $theme_options['burger_color'] = $rule['color'];
				if(!empty($rule['size']))  $theme_options['burger_size'] = 'av-small-burger-icon';

				break;
			}
		}
	}
	
	
	//replace existing options with the new options
	$avia->options['avia'] = $theme_options;
	update_option($avia->option_prefix, $avia->options);
	
}

 
 
 
 
 
 

