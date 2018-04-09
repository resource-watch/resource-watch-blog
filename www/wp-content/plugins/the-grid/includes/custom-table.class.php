<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

class The_Grid_Custom_Table {
	
	/**
	* Main tables name const
	* @since 1.6.0
	*/
	const TABLE_GRID     = 'tg_item_table';
	const TABLE_SKINS    = 'tg_item_skins';
	const TABLE_ELEMENTS = 'tg_item_elements';

	/**
	* Create Database Tables (multisite condition)
	* @since 1.6.0
	*/
	public static function create_tables($networkwide = false, $force = false){
		
		global $wpdb;
		
		// if multisite, create table for each site
		if (function_exists('is_multisite') && is_multisite() && $networkwide) {
			
			// store current blog ID
			$current_blog_ID = $wpdb->blogid;
			
			// get all site blog ID
			$blog_IDs = $wpdb->get_col("SELECT blog_id FROM ".$wpdb->blogs);
			
			// create table for each blog ID
			foreach($blog_IDs as $blog_ID){
				switch_to_blog($blog_ID);
				self::_create_tables($force);
			}
			
			// go back to current blog ID
			switch_to_blog($current_blog_ID);
		
		// if no multisite
		} else{  
		
			self::_create_tables($force);
			
		}
		
	}
	
	/**
	* Create Tables
	* @since 1.6.0
	*/
	public static function _create_tables($force){
		
		global $wpdb;
		
		//Create/Update Grids Database
		$table_version = get_option('tg_grid_db_version', '0');
		
		if (version_compare($table_version, '1', '<') || $force) {
			
			// add upgrade class
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			
			// get database character collate
			$charset_collate = $wpdb->get_charset_collate();

			// custom skins SQL
			$skins_sql = 'CREATE TABLE IF NOT EXISTS '.$wpdb->prefix . self::TABLE_SKINS.' (
				id mediumint(6) NOT NULL AUTO_INCREMENT,
				name VARCHAR(191) NOT NULL,
				slug VARCHAR(191) NOT NULL,	
				date datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,
				modified_date datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,
				params MEDIUMTEXT NOT NULL,
				settings MEDIUMTEXT  NOT NULL,
				elements MEDIUMTEXT  NOT NULL,
				styles MEDIUMTEXT  NOT NULL,
				UNIQUE KEY id (id),
				UNIQUE (name),
				UNIQUE (slug)		
			) '. $charset_collate .';';
			// create skins table
			dbDelta($skins_sql);
			
			// custom element SQL
			$elements_sql = 'CREATE TABLE IF NOT EXISTS '.$wpdb->prefix . self::TABLE_ELEMENTS.' (
				id mediumint(6) NOT NULL AUTO_INCREMENT,
				name VARCHAR(191) NOT NULL,
				slug VARCHAR(191) NOT NULL,
				date datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,
				modified_date datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,
				settings MEDIUMTEXT NOT NULL,
				UNIQUE KEY id (id),
				UNIQUE (name),
				UNIQUE (slug)
			) '. $charset_collate .';';
			// create elements table
			dbDelta($elements_sql);
			
			// store The Grid custom db version (if future changes are needed)
			update_option('tg_grid_db_version', '1');

		}
		
	}
	
	/**
	* Check if custom tables exist
	* @since 1.6.0
	*/
	public static function custom_tables_exist(){
		
		global $wpdb;
		
		$table_skin_name = $wpdb->prefix . self::TABLE_SKINS;
		$table_element_name = $wpdb->prefix . self::TABLE_ELEMENTS;
		
		// if custom tables don't exist create them
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_skin_name'") != $table_skin_name || $wpdb->get_var("SHOW TABLES LIKE '$table_element_name'") != $table_element_name)  {
			self::create_tables(false, true);
		}
		
	}
	
	/**
	* Save/Update item skin in table
	* @since 1.6.0
	*/
	public static function save_item_skin($skin_data = array(), $ID = 0) {
		
		self::custom_tables_exist();
		
		// if skin data exist
		if (is_array($skin_data) && !empty($skin_data)) { 
		
			global $wpdb;
		
			$table_name = $wpdb->prefix . self::TABLE_SKINS;
			
			// check if name exists in another skin
			$check_name = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE name = %s AND id != %s ", array($skin_data['name'], $ID)), ARRAY_A);
			
			// get native skin name
			$native_skins = apply_filters('tg_register_item_skin', '');
		
			// check if exists in DB, if no, create
			if(!empty($check_name) || isset($native_skins[$skin_data['slug']]) || in_array($skin_data['slug'], (array) $native_skins)){

				$error_msg = __('This skin name already exists. Please use a different name:', 'tg-text-domain' );
				throw new Exception($error_msg);
						
			}

			// Update if valid skin id
			if (isset($ID) && intval($ID) > 0){
				
				// check if skin exist
				$skin_exist = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %s ", $ID), ARRAY_A);
				
				if ($skin_exist) {
					
					// update skin data (wpdb prepare & escape the data)
					$wpdb->update( 
						$table_name, 
						array(  
							'modified_date' => current_time('mysql'), 
							'name'          => $skin_data['name'],
							'slug'          => $skin_data['slug'],
							'params'        => $skin_data['params'], 
							'settings'      => $skin_data['settings'],
							'elements'      => $skin_data['elements'],
							'styles'        => $skin_data['styles']
						),
						array(
							'id' => $ID
						)
					);
					
				} else {
					
					$error_msg = __('This skin was not found in the database.', 'tg-text-domain' );
					throw new Exception($error_msg);
				
				}
			
			// create skin
			} else {
				
				// insert a new skin (wpdb prepare & escape the data)
				$wpdb->insert(
					$table_name,
					array( 
						'date'          => current_time('mysql'), 
						'modified_date' => current_time('mysql'), 
						'name'          => $skin_data['name'],
						'slug'          => $skin_data['slug'],
						'params'        => $skin_data['params'], 
						'settings'      => $skin_data['settings'],
						'elements'      => $skin_data['elements'],
						'styles'        => $skin_data['styles']
					)
				);
				
				// retrieve new skin id
				$ID = $wpdb->insert_id;
			
			}

			// if an error occured while inserting new skin
			if ($wpdb->last_error) {
				
				$error_msg  = __('Sorry, an unknown issue occured:', 'tg-text-domain' );
				$error_msg .= '<br>';
				$error_msg .= $wpdb->last_error;
				throw new Exception($error_msg);
			
			}
			
			return $ID;
		
		// if no data skin
		} else {
			
			$error_msg = __('No data was found for this skin.', 'tg-text-domain' );
			throw new Exception($error_msg);
		
		}
	
	}
	
	
	/**
	* Save/Update item element in table
	* @since 1.6.0
	*/
	public static function save_item_element($element_data = array(), $ID = 0) {
		
		self::custom_tables_exist();
		
		// if element data exist
		if (is_array($element_data) && !empty($element_data)) { 
		
			global $wpdb;
		
			$table_name = $wpdb->prefix . self::TABLE_ELEMENTS;
			
			// Update if valid skin id
			if (isset($ID) && intval($ID) > 0){
			
				// check if skin exist
				$element_exist = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %s ", $ID), ARRAY_A);
				
				if ($element_exist) {
					
					// update skin data (wpdb prepare & escape the data)
					$wpdb->update( 
						$table_name, 
						array(  
							'modified_date' => current_time('mysql'), 
							'name'          => $element_data['name'],
							'slug'          => $element_data['slug'],
							'settings'      => $element_data['settings'],
						),
						array(
							'id' => $ID
						)
					);
				
				} else {
					
					$error_msg = __('This element was not found in the database.', 'tg-text-domain' );
					throw new Exception($error_msg);
				
				}
					
			} else {
					
				// insert a new skin (wpdb prepare & escape the data)
				$wpdb->insert(
					$table_name,
					array( 
						'date'          => current_time('mysql'), 
						'modified_date' => current_time('mysql'), 
						'name'          => $element_data['name'],
						'slug'          => $element_data['slug'],
						'settings'      => $element_data['settings'],
					)
				);
				
				// retrieve new skin id
				$ID = $wpdb->insert_id;
				
			}
			
			// if an error occured while inserting new skin
			if ($wpdb->last_error) {
				
				$error_msg  = __('Sorry, an unknown issue occured:', 'tg-text-domain' );
				$error_msg .= '<br>';
				$error_msg .= $wpdb->last_error;
				throw new Exception($error_msg);
			
			}
			
			// return element ID
			return $ID;
		
		// if no data skin
		} else {
			
			$error_msg = __('No data was found for this element.', 'tg-text-domain' );
			throw new Exception($error_msg);
		
		}
	
	}

	/**
	* Delete item skin from DB
	* @since 1.6.0
	*/
	public static function delete_item_skin($ID = 0){
		
		// check if valid skin id
		if ($ID && intval($ID) != 0) {
		
			global $wpdb;
			
			$table_name = $wpdb->prefix . self::TABLE_SKINS;
			
			$response = $wpdb->delete($table_name, array('id' =>$ID));

			if (!$response) {
				
				$error_msg = __('Item Skin could not be deleted', 'tg-text-domain' );
				throw new Exception($error_msg);
				
			}
		
		} else {
			
			$error_msg = __('Invalid Skin ID', 'tg-text-domain' );
			throw new Exception($error_msg);
			
		}

	}
	
	/**
	* Delete item element from DB
	* @since 1.6.0
	*/
	public static function delete_item_element($ID){
		
		// check if valid skin id
		if ($ID && intval($ID) != 0) {
		
			global $wpdb;
			
			$table_name = $wpdb->prefix . self::TABLE_ELEMENTS;
			
			$response = $wpdb->delete($table_name, array('id' =>$ID));

			if (!$response) {
				
				$error_msg = __('Element could not be deleted', 'tg-text-domain' );
				throw new Exception($error_msg);
				
			}
		
		} else {
			
			$error_msg = __('Invalid Element ID', 'tg-text-domain' );
			throw new Exception($error_msg);
			
		}

	}
	
	/**
	* Clone item Skin from DB
	* @since 1.6.0
	*/
	public static function clone_item_skin($ID){
		
		// check if valid skin id
		if ($ID && intval($ID) != 0) {
		
			global $wpdb;
			
			$table_name = $wpdb->prefix . self::TABLE_SKINS;
			
			$skin_settings = self::get_skin_settings($ID);
			if (empty($skin_settings)) {
				$error_msg = __('Skin data are missing', 'tg-text-domain' );
				throw new Exception($error_msg);
			}
			
			$skin_settings = self::generate_unique_skin($skin_settings);
			
			try {
				
				$generator_class = new The_Grid_Skin_Generator();
				$skin_settings   = $generator_class->generate_skin($skin_settings);
				$response = self::save_item_skin($skin_settings);
					
			} catch (Exception $e) {
						
				// show error message if throw
				$error_msg = $e->getMessage();
				throw new Exception($error_msg);
						
			}

			return $response;
		
		} else {
			
			$error_msg = __('Invalid Skin ID.', 'tg-text-domain' );
			throw new Exception($error_msg);
			
		}

	}

	/**
	* Import item skins to DB
	* @since 1.6.0
	*/
	public static function import_item_skin($skin_settings){
		
		// check if valid skin settings
		if ($skin_settings) {
			
			// check if table exist before check unique slug
			self::custom_tables_exist();
			
			$skin_settings = self::generate_unique_skin($skin_settings);
			
			try {
				
				$generator_class = new The_Grid_Skin_Generator();
				$skin_settings   = $generator_class->generate_skin($skin_settings);
				$response = self::save_item_skin($skin_settings);
					
			} catch (Exception $e) {
						
				// show error message if throw
				$error_msg = $e->getMessage();
				throw new Exception($error_msg);
						
			}

			return $response;
		
		} else {
			
			$error_msg = __('Skin data are missing', 'tg-text-domain' );
			throw new Exception($error_msg);
			
		}

	}
	
	/**
	* Update skin name/class name/global css
	* @since 1.7.0
	*/
	public static function generate_unique_skin($skin_settings){
		
		// decode settings and change name
		$skin_settings = json_decode($skin_settings, true);
			
		// fetch old name
		$old_name   = $skin_settings['item']['layout']['skin_name'];
		
		// fetch global css
		$global_css = $skin_settings['item']['global_css'];
		
		// generate old slug
		$old_slug   = sanitize_title($old_name);
		$old_slug   = sanitize_html_class($old_slug);
		
		// generate new slug
		$new_name   = self::unique_name($old_name, self::TABLE_SKINS);
		$new_slug   = sanitize_title($new_name);
		$new_slug   = sanitize_html_class($new_slug);
		
		// change old slug and change all css class for old slug
		$skin_settings['item']['layout']['skin_name'] = $new_name;
		$skin_settings['item']['global_css'] = str_replace($old_slug, $new_slug, $global_css);
			
		// encode settings with new slug and modified global css
		$skin_settings = json_encode($skin_settings);
		
		return $skin_settings;
	
	}
	
	/**
	* Import item element to DB
	* @since 1.6.0
	*/
	public static function import_item_element($element_name, $element_settings){
		
		// check if valid element settings & name
		if ($element_settings && $element_name) {
			
			// check if table exist before check unique slug
			self::custom_tables_exist();

			$element_name = self::unique_name($element_name, self::TABLE_ELEMENTS);	
			$element_slug = sanitize_title($element_name);
			$element_slug = sanitize_html_class($element_slug);
			
			try {
				
				$response = self::save_item_element(array(
					'slug'     => $element_slug,
					'name'     => $element_name,
					'settings' => $element_settings
				));
					
			} catch (Exception $e) {
						
				// show error message if throw
				$error_msg = $e->getMessage();
				throw new Exception($error_msg);
						
			}

			return $response;
		
		} else {
			
			$error_msg = __('Element data are missing.', 'tg-text-domain' );
			throw new Exception($error_msg);
			
		}

	}

	/**
	* Get item skin params from DB
	* @since 1.6.0
	*/
	public static function get_skin_params(){

		global $wpdb;
		
		$table_name = $wpdb->prefix . self::TABLE_SKINS;
		
		$skin = $wpdb->get_results("SELECT params, id FROM $table_name ORDER BY slug ASC", ARRAY_A);

		return $skin;

	}
	
	/**
	* Get item skin settings from DB
	* @since 1.6.0
	*/
	public static function get_skin_settings($ID){
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . self::TABLE_SKINS;

		$skin_settings = $wpdb->get_row($wpdb->prepare("SELECT settings FROM $table_name WHERE id = %d", $ID), ARRAY_A);

		return $skin_settings['settings'];

	}
	
	/**
	* Get item skin elements from DB
	* @since 1.6.0
	*/
	public static function get_skin_elements($slug){
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . self::TABLE_SKINS;

		$skin_elements = $wpdb->get_row($wpdb->prepare("SELECT elements FROM $table_name WHERE slug = %s ", $slug), ARRAY_A);

		return $skin_elements['elements'];

	}
	
	/**
	* Get item skin styles from DB
	* @since 1.6.0
	*/
	public static function get_skin_styles($slug){
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . self::TABLE_SKINS;

		$skin_styles = $wpdb->get_row($wpdb->prepare("SELECT styles FROM $table_name WHERE slug = %s ", $slug), ARRAY_A);

		return $skin_styles['styles'];

	}
	
	/**
	* Get item element settings from DB
	* @since 1.6.0
	*/
	public static function get_elements(){
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . self::TABLE_ELEMENTS;
		
		$elements = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date DESC", ARRAY_A);

		return $elements;

	}
	
	/**
	* Get item element settings from DB
	* @since 1.6.0
	*/
	public static function get_element_settings($ID){
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . self::TABLE_ELEMENTS;

		$element_settings = $wpdb->get_row($wpdb->prepare("SELECT settings FROM $table_name WHERE id = %d", $ID), ARRAY_A);

		return $element_settings['settings'];

	}
	
	/**
	* Generate unique name
	* @since 1.6.0
	*/
	public static function unique_name($name, $table_name){
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . $table_name;
	
		$query = $wpdb->prepare("SELECT name FROM $table_name WHERE name = %s", htmlspecialchars($name));

		if ($wpdb->get_var($query)) {
			$num = 2;
			do {
				$alt_name = $name . ' ' . $num;
				$num++;
				$name_check = $wpdb->get_var($wpdb->prepare("SELECT name FROM $table_name WHERE name = %s", htmlspecialchars($alt_name)));
			} while ($name_check);
			$name = $alt_name;
		}
		
		// check if skin slug exists in registered skins
		if ($table_name == $wpdb->prefix . 'tg_item_skins') {
			
			// get native skin name
			$native_skins = apply_filters('tg_register_item_skin', '');

			// get slug
			$slug = sanitize_title($name);
			$slug = sanitize_html_class($slug);
			
			if ((isset($native_skins[$slug]) || in_array($slug, (array) $native_skins))) {
				$num = (isset($num)) ? $num : 2;
				do {
					$alt_name = $name . ' ' . $num;
					$num++;
					$slug = sanitize_title($alt_name);
					$slug = sanitize_html_class($slug);
					$name_check = isset($native_skins[$slug]);
				} while ($name_check);
				$name = $alt_name;
			}
		
		}
		
		return $name;
		
	}

}