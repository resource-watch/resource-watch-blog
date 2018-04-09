<?php
add_action('init', 'portfolio_register');

function portfolio_register()
{
	global $avia_config;
 
	$labels = array(
		'name' => _x('Portfolio Items', 'post type general name','avia_framework'),
		'singular_name' => _x('Portfolio Entry', 'post type singular name','avia_framework'),
		'add_new' => _x('Add New', 'portfolio','avia_framework'),
		'add_new_item' => __('Add New Portfolio Entry','avia_framework'),
		'edit_item' => __('Edit Portfolio Entry','avia_framework'),
		'new_item' => __('New Portfolio Entry','avia_framework'),
		'view_item' => __('View Portfolio Entry','avia_framework'),
		'search_items' => __('Search Portfolio Entries','avia_framework'),
		'not_found' =>  __('No Portfolio Entries found','avia_framework'),
		'not_found_in_trash' => __('No Portfolio Entries found in Trash','avia_framework'),
		'parent_item_colon' => ''
	);
 
    $permalinks = get_option('avia_permalink_settings');
    if(!$permalinks) $permalinks = array();    

    $permalinks['portfolio_permalink_base'] = empty($permalinks['portfolio_permalink_base']) ? __('portfolio-item', 'avia_framework') : $permalinks['portfolio_permalink_base'];
    $permalinks['portfolio_entries_taxonomy_base'] = empty($permalinks['portfolio_entries_taxonomy_base']) ? __('portfolio_entries', 'avia_framework') : $permalinks['portfolio_entries_taxonomy_base'];
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array('slug'=>_x($permalinks['portfolio_permalink_base'],'URL slug','avia_framework'), 'with_front'=>true),
		'query_var' => true,
		'show_in_nav_menus'=> true,
		'taxonomies' => array('post_tag'),
		'supports' => array('title','thumbnail','excerpt','editor','comments'),
		'menu_icon' => 'dashicons-images-alt2'
	);
	
	
	$args = apply_filters('avf_portfolio_cpt_args', $args);
	$avia_config['custom_post']['portfolio']['args'] = $args;
 
	register_post_type( 'portfolio' , $args );


	$tax_args = array(	
		"hierarchical" => true,
		"label" => "Portfolio Categories",
		"singular_label" => "Portfolio Category",
		"rewrite" => array('slug'=>_x($permalinks['portfolio_entries_taxonomy_base'],'URL slug','avia_framework'), 'with_front'=>true),
		"query_var" => true
	);
 
 	$avia_config['custom_taxonomy']['portfolio']['portfolio_entries']['args'] = $tax_args;

	register_taxonomy("portfolio_entries", array("portfolio"), $tax_args);

	//deactivate the avia_flush_rewrites() function - not required because we rely on the default wordpress permalink settings
	remove_action('wp_loaded', 'avia_flush_rewrites');
}



#portfolio_columns, register_post_type then append _columns
add_filter("manage_edit-portfolio_columns", "prod_edit_columns");
add_filter("manage_edit-post_columns", "post_edit_columns");
add_action("manage_posts_custom_column",  "prod_custom_columns");


function post_edit_columns($columns)
{
	$newcolumns = array(
		"cb" => "<input type=\"checkbox\" />",
		"thumb column-comments" => "Image",
	);

	$columns= array_merge($newcolumns, $columns);

	return $columns;
}

function prod_edit_columns($columns)
{
	$newcolumns = array(
		"cb" => "<input type=\"checkbox\" />",
		"thumb column-comments" => "Image",
		"title" => "Title",
		"portfolio_entries" => "Categories"
	);

	$columns= array_merge($newcolumns, $columns);

	return $columns;
}

function prod_custom_columns($column)
{
	global $post;
	switch ($column)
	{
		case "thumb column-comments":
		if (has_post_thumbnail($post->ID)){
				echo get_the_post_thumbnail($post->ID, 'widget');
			}
		break;

		case "description":
		#the_excerpt();
		break;
		case "price":
		#$custom = get_post_custom();
		#echo $custom["price"][0];
		break;
		case "portfolio_entries":
		echo get_the_term_list($post->ID, 'portfolio_entries', '', ', ','');
		break;
	}
}

 
 
/**
 * avia portfolio permalink setting.
 *
 * @access public
 * @return void
 */
if(!function_exists('avia_permalink_settings_init'))
{
	function avia_permalink_settings_init()
	{
		global $avia_config;
		
		if(!empty($avia_config['custom_post']))
		{
			foreach($avia_config['custom_post'] as $cpt => $cpt_args)
			{
			    // Add a section to the permalinks page
			    add_settings_section('avia-permalink', $cpt_args['args']['labels']['singular_name'] . ' ' . __('Settings', 'avia_framework'), 'avia_permalink_settings', 'permalink');
			}
		}
	}
	add_action('admin_init', 'avia_permalink_settings_init');
}
 
 
if(!function_exists('avia_permalink_settings'))
{
	function avia_permalink_settings()
	{
		global $avia_config;

		if(!empty($avia_config['custom_post']))
		{
			foreach($avia_config['custom_post'] as $cpt => $cpt_args)
			{			
				echo wpautop(__('These settings change the permalinks used for the', 'avia_framework') . ' ' . strtolower($cpt_args['args']['labels']['name']) . __('.', 'avia_framework'));

				$permalinks = get_option('avia_permalink_settings');
				if(!$permalinks) $permalinks = array();

				$permalinks[$cpt.'_permalink_base'] = empty($permalinks[$cpt.'_permalink_base']) ? $cpt_args['args']['rewrite']['slug'] : $permalinks[$cpt.'_permalink_base'];

				if(!empty($avia_config['custom_taxonomy'][$cpt]))
				{
					foreach($avia_config['custom_taxonomy'][$cpt] as $tax => $tax_args)
					{
						$permalinks[$tax.'_taxonomy_base'] = empty($permalinks[$tax.'_taxonomy_base']) ? $tax_args['args']['rewrite']['slug'] : $permalinks[$tax.'_taxonomy_base'];
					}
				}
				?>

				<table class="form-table">
				    <tbody>
				    <tr>
				        <th><?php echo $cpt_args['args']['labels']['name'] . ' ' . __('Base', 'avia_framework'); ?></th>
				        <td>
				        	<?php $option_id = $cpt.'_permalink_base'; ?>
				            <input name="<?php echo $option_id; ?>" id="<?php echo $option_id; ?>" type="text" value="<?php echo esc_attr($permalinks[$option_id]); ?>" class="regular-text code"> <span class="description"><?php _e( 'Enter a custom base to use. A base must be set or WordPress will use default instead.', 'avia_framework' ); ?></span>
				        </td>
				    </tr>

			<?php
				if(!empty($avia_config['custom_taxonomy'][$cpt]))
				{
					foreach($avia_config['custom_taxonomy'][$cpt] as $tax => $tax_args)
					{
				?>
					    <tr>
					        <th><?php echo $tax_args['args']['label']  . ' ' . __('Base', 'avia_framework'); ?></th>
					        <td>
					        	<?php $option_id = $tax.'_taxonomy_base'; ?>
					            <input name="<?php echo $option_id; ?>" id="<?php echo $option_id; ?>" type="text" value="<?php echo esc_attr($permalinks[$option_id]); ?>" class="regular-text code"> <span class="description"><?php _e( 'Enter a custom base to use. A base must be set or WordPress will use default instead.', 'avia_framework' ); ?></span>
					        </td>
					    </tr>
				<?php
					}
				}
			?>
				    </tbody>
				</table>
				
				<?php
			}
		}
	}
}


if(!function_exists('avia_permalink_settings_save'))
{ 
	function avia_permalink_settings_save()
	{
		if (defined('DOING_AJAX') && DOING_AJAX) return;
		
		global $avia_config;
		$permalinks = get_option('avia_permalink_settings');
		if(!$permalinks) $permalinks = array();

		if(!empty($avia_config['custom_post']))
		{
			foreach($avia_config['custom_post'] as $cpt => $cpt_args)
			{
				$option_id = $cpt.'_permalink_base';

			    // We need to save the options ourselves; settings api does not trigger save for the permalinks page
			    if(isset($_POST[$option_id]))
			    {
		        	$permalinks[$option_id] = untrailingslashit(esc_html($_POST[$option_id]));
			    }

				if(!empty($avia_config['custom_taxonomy'][$cpt]))
				{
				    foreach($avia_config['custom_taxonomy'][$cpt] as $tax => $tax_args)
					{
						$option_id = $tax.'_taxonomy_base';

						if(isset($_POST[$option_id]))
			    		{
							$permalinks[$option_id] = untrailingslashit(esc_html($_POST[$option_id]));
						}
					}
				}
			}

			update_option('avia_permalink_settings', $permalinks);
		}
	}
	add_action('admin_init', 'avia_permalink_settings_save');
}
