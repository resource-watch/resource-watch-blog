<?php
class avia_wp_import extends WP_Import
{
	var $preStringOption; 
	var $results;
	var $getOptions;
	var $saveOptions;
	var $termNames;
	
	function saveOptions($option_file, $import_only = false)
	{	
		if($option_file) @include_once($option_file);
		
		switch($import_only)
		{
			case 'options': $dynamic_pages = $dynamic_elements = false; break;
			case 'dynamic_pages': $options = $dynamic_elements = false; break;
			case 'dynamic_elements': $options = $dynamic_pages = false; break;
		}
		
		
		
		if(!isset($options) && !isset($dynamic_pages) && !isset($dynamic_elements)  ) { return false; }
		
		$options = unserialize(base64_decode($options));
		$dynamic_pages = unserialize(base64_decode($dynamic_pages));
		$dynamic_elements = unserialize(base64_decode($dynamic_elements));
		
		global $avia;
		
		if(is_array($options))
		{
			foreach($avia->option_pages as $page)
			{
				$database_option[$page['parent']] = $this->extract_default_values($options[$page['parent']], $page, $avia->subpages);
			}
		}
		
		if(!empty($database_option))
		{
			update_option($avia->option_prefix, $database_option);
		}
		
		if(!empty($dynamic_pages))
		{
			update_option($avia->option_prefix.'_dynamic_pages', $dynamic_pages);
		}
		
		if(!empty($dynamic_elements))
		{
			update_option($avia->option_prefix.'_dynamic_elements', $dynamic_elements);
		}
		
		if(!empty($fonts))
		{
			$this->import_iconfont( $fonts );
		}
		
		if(!empty($layerslider))
		{
			$this->import_layerslides( $layerslider );
		}
		
		
		if(!empty($widget_settings))
		{
			$widget_settings = unserialize(base64_decode($widget_settings));
			if(!empty($widget_settings))
			{
				foreach($widget_settings as $key => $setting)
				{
					update_option( $key, $setting );
				}
			}
		}
		
		
		
	}
	
	public function import_layerslides( $layerslider )
	{
		@ini_set('max_execution_time', 300);
		
		$slider 		= urlencode( $layerslider );
		$remoteURL 		= 'https://kriesi.at/themes/wp-content/uploads/avia-sample-layerslides/'.$slider.".zip";
	
		$uploads 		= wp_upload_dir();
		$downloadPath 	= $uploads['basedir'].'/lsimport.zip';
	
		// Download package
		$request = wp_remote_post($remoteURL, array(
			'method' => 'POST',
			'timeout' => 300,
			'body' => array()
		));

		$zip = wp_remote_retrieve_body($request);
		
		if( ! $zip ) {
			die("LayerSlider couldn't download your selected slider. Please check LayerSlider -> System Status for potential issues. The WP Remote functions may be unavailable or your web hosting provider has to allow external connections to our domain."
			);
		}
	
		// Save package
		if( ! file_put_contents($downloadPath, $zip) ) 
		{
			die("LayerSlider couldn't save the downloaded slider on your server. Please check LayerSlider -> System Status for potential issues. The most common reason for this issue is the lack of write permission on the /wp-content/uploads/ directory.");
			
		}
	
		// Load importUtil & import the slider
		include LS_ROOT_PATH.'/classes/class.ls.importutil.php';
		$import = new LS_ImportUtil( $downloadPath );
	
		// Remove package
		unlink( $downloadPath );
	}
	
	
	
	
	public function import_iconfont( $new_fonts )
	{
		@ini_set('max_execution_time', 300);
		
		//update iconfont option 
		$key 			= 'avia_builder_fonts';
		$fonts_old 		= get_option( $key );
		
		if(empty($fonts_old)) $fonts_old = array();

		$new_fonts 		= unserialize(base64_decode($new_fonts));
		$merged_fonts 	= array_merge( $new_fonts , $fonts_old );
		$files_to_copy  = array("config.json", "FONTNAME.svg", "FONTNAME.ttf", "FONTNAME.eot", "FONTNAME.woff");	
		update_option($key, $merged_fonts);
		
		
		
		$http 			= new WP_Http();
		$font_uploader 	= new avia_font_manager();
		$paths			= $font_uploader->paths;
		
		//if a temp dir already exists remove it and create a new one
		if(!is_dir($paths['tempdir']))
		{
			$fontdir = avia_backend_create_folder($paths['tempdir'], false);
			if(!$fontdir) echo('Wasn\'t able to create the folder for font files');
		}
		
		//download iconfont files into uploadsfolder
		foreach ($new_fonts as $font_name => $font)
		{
			if(empty($fonts_old[$font_name]))
			{
				//folder name
				$new_font_folder = trailingslashit($paths['tempdir']);
				
				//if a sub dir already exists remove it and create a new one
				if(is_dir($new_font_folder)) $font_uploader->delete_folder( $new_font_folder );
				
				$subpdir = avia_backend_create_folder($new_font_folder, false);
				if(!$subpdir)
				{ 
					echo('Wasn\'t able to create sub-folder for font files');
				}
				
				
				//iterate over files on remote server and create the same ones on this server
				foreach ($files_to_copy as $file_to_copy)
				{
					$file_to_copy 	= str_replace("FONTNAME", $font_name, $file_to_copy);
					$origin_url 	= $font['origin_folder'].trailingslashit($font['folder']).$file_to_copy;
					$new_path		= trailingslashit($new_font_folder).$file_to_copy;
					$headers 		= $http->request( $origin_url, array('stream'=>true, 'filename'=>$new_path) );
				}
				
				
				//create a config file
				$font_uploader->font_name = $font_name;
				$font_uploader->create_config();
				
			}
		}
	}
	
	
	
	
	
	/**
	 *  Extracts the default values from the option_page_data array in case no database savings were done yet
	 *  The functions calls itself recursive with a subset of elements if groups are encountered within that array
	 */
	public function extract_default_values($elements, $page, $subpages)
	{
	
		$values = array();
		foreach($elements as $element)
		{
				if($element['type'] == 'group')
				{	
					$iterations =  count($element['std']);
					
					for($i = 0; $i<$iterations; $i++)
					{
						$values[$element['id']][$i] = $this->extract_default_values($element['std'][$i], $page, $subpages);
					}
				}
				else if(isset($element['id']))
				{
					if(!isset($element['std'])) $element['std'] = "";
					
					if($element['type'] == 'select' && !is_array($element['subtype']))
					{	
						if(!isset($element['taxonomy'])) $element['taxonomy'] = 'category';
						$values[$element['id']] = $this->getSelectValues($element['subtype'], $element['std'], $element['taxonomy']);
					}
					else
					{
						$values[$element['id']] = $element['std'];
					}
				}
			
		}
		
		return $values;
	}
	
	function getSelectValues($type, $name, $taxonomy)
	{
		switch ($type)
		{
			case 'page':
			case 'post':	
				$the_post = get_page_by_title( $name, 'OBJECT', $type );
				if(isset($the_post->ID)) return $the_post->ID;
			break;
			
			case 'cat':	
			
				if(!empty($name))
				{
					$return = array();
					
					foreach($name as $cat_name)
					{	
						if($cat_name)
						{	
							if(!$taxonomy) $taxonomy = 'category';
							$the_category = get_term_by('name', $cat_name, $taxonomy);
						
							if($the_category) $return[] = $the_category->term_id;
						}
					}
				
				if(!empty($return))
				{
					if(!isset($return[1]))
					{
						 $return = $return[0];
					}
					else
					{
						$return = implode(',',$return);
					}
				}
				return $return;
			}
			
		break;
		}
	}
	
	/*rename existung menus so that newly added menu items are not appended*/
	function rename_existing_menus()
	{
		$menus  = wp_get_nav_menus();
		
		if(!empty($menus))
		{	
			//wp_delete_nav_menu($menu->slug);
			
			foreach($menus as $menu)
			{
				$updated = false;
				$i = 0;
				
				while(!is_numeric($updated)) //try to update the menu name. if it exists increment the number and thereby change the name
				{
					$i++;
					$args['menu-name'] 		= __("Previously used menu",'avia_framework')." ".$i;
					$args['description'] 	= $menu->description;
					$args['parent'] 		= $menu->parent;
					
					$updated = wp_update_nav_menu_object($menu->term_id, $args); //return a number on success or wp_error object if menu name exists
					
					//fallback, prevents infinite loop if something weird happens
					if($i > 100) $updated = 1;
				}
			}
		}
	}
	
	function set_menus()
	{
		global $avia_config;
		//get all registered menu locations
		$locations   = get_theme_mod('nav_menu_locations');

		//get all created menus
		$avia_menus  = wp_get_nav_menus();
		
		
		if(!empty($avia_menus) && !empty($avia_config['nav_menus']))
		{
			$avia_navs = array();
			foreach($avia_config['nav_menus'] as $key => $nav_menu)
			{
				if(isset($nav_menu['html']))
				{
					$avia_navs[$key] = $nav_menu['html'];
				}
				else
				{
					$avia_navs[$key] = $nav_menu;
				}
			}
		
			foreach($avia_menus as $avia_menu)
			{
				//check if we got a menu that corresponds to the Menu name array ($avia_config['nav_menus']) we have set in functions.php
				// a partial match like "Main Menu" "Main", or "Secondary" is enough
				if( is_object($avia_menu) )
				{
					foreach($avia_navs as $key => $value)
					{
						$value = strtolower($value);
						$name = strtolower($avia_menu->name);
				
						if(strpos($value, $name) !== false)
						{
							$locations[$key] = $avia_menu->term_id;
						}
					}
				}
			}
		}
		
		
		
		//update the theme
		set_theme_mod( 'nav_menu_locations', $locations);
	}
	
	
	function process_menu_item( $item ) {

		// skip draft, orphaned menu items
		if ( 'draft' == $item['status'] )
			return;

		$menu_slug = false;
		if ( isset($item['terms']) ) {
			// loop through terms, assume first nav_menu term is correct menu
			foreach ( $item['terms'] as $term ) {
				if ( 'nav_menu' == $term['domain'] ) {
					$menu_slug = $term['slug'];
					break;
				}
			}
		}

		// no nav_menu term associated with this menu item
		if ( ! $menu_slug ) {
			_e( 'Menu item skipped due to missing menu slug', 'wordpress-importer' );
			echo '<br />';
			return;
		}

		$menu_id = term_exists( $menu_slug, 'nav_menu' );
		if ( ! $menu_id ) {
			printf( __( 'Menu item skipped due to invalid menu slug: %s', 'wordpress-importer' ), esc_html( $menu_slug ) );
			echo '<br />';
			return;
		} else {
			$menu_id = is_array( $menu_id ) ? $menu_id['term_id'] : $menu_id;
		}

		foreach ( $item['postmeta'] as $meta )
			${$meta['key']} = $meta['value']; //kriesi mod: php 7 fix - added braces

		if ( 'taxonomy' == $_menu_item_type && isset( $this->processed_terms[intval($_menu_item_object_id)] ) ) {
			$_menu_item_object_id = $this->processed_terms[intval($_menu_item_object_id)];
		} else if ( 'post_type' == $_menu_item_type && isset( $this->processed_posts[intval($_menu_item_object_id)] ) ) {
			$_menu_item_object_id = $this->processed_posts[intval($_menu_item_object_id)];
		} else if ( 'custom' != $_menu_item_type ) {
			// associated object is missing or not imported yet, we'll retry later
			$this->missing_menu_items[] = $item;
			return;
		}

		if ( isset( $this->processed_menu_items[intval($_menu_item_menu_item_parent)] ) ) {
			$_menu_item_menu_item_parent = $this->processed_menu_items[intval($_menu_item_menu_item_parent)];
		} else if ( $_menu_item_menu_item_parent ) {
			$this->menu_item_orphans[intval($item['post_id'])] = (int) $_menu_item_menu_item_parent;
			$_menu_item_menu_item_parent = 0;
		}

		// wp_update_nav_menu_item expects CSS classes as a space separated string
		$_menu_item_classes = maybe_unserialize( $_menu_item_classes );
		if ( is_array( $_menu_item_classes ) )
			$_menu_item_classes = implode( ' ', $_menu_item_classes );

		$args = array(
			'menu-item-object-id' => $_menu_item_object_id,
			'menu-item-object' => $_menu_item_object,
			'menu-item-parent-id' => $_menu_item_menu_item_parent,
			'menu-item-position' => intval( $item['menu_order'] ),
			'menu-item-type' => $_menu_item_type,
			'menu-item-title' => $item['post_title'],
			'menu-item-url' => $_menu_item_url,
			'menu-item-description' => $item['post_content'],
			'menu-item-attr-title' => $item['post_excerpt'],
			'menu-item-target' => $_menu_item_target,
			'menu-item-classes' => $_menu_item_classes,
			'menu-item-xfn' => $_menu_item_xfn,
			'menu-item-status' => $item['status']
		);
		
		
		

		$id = wp_update_nav_menu_item( $menu_id, 0, $args );
		if ( $id && ! is_wp_error( $id ) )
			$this->processed_menu_items[intval($item['post_id'])] = (int) $id;
		
		/*kriesi mod: necessary to add custom post meta to the import*/
		if ( $id && ! is_wp_error( $id ) )
		{
			foreach($item['postmeta'] as $itemkey => $meta)
			{
				$key = str_replace('_', '-', ltrim($meta['key'], "_"));
				
				/*do a check: only add keys that do not exist - parent menu item is a special case that must be checked as well*/
				if( !array_key_exists($key, $args) && $key != "menu-item-menu-item-parent")
				{
					if(!empty($meta['value']))
					{
						update_post_meta($id, $meta['key'], $meta['value']);
					}
				}
			}
		}
		/*end mod*/
		
		
	}
	
	
	
}




