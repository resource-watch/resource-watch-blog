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

class The_Grid_Register_Item_Skin {

    private $skins = array();

    function __construct() {
		
        $this->skins = apply_filters( 'tg_register_item_skin', $this->skins );
		$register = array();
		
		if (is_array($this->skins)) {
			
			foreach($this->skins as $skin => $data) {
				$name = (is_array($data) && !empty($data)) ? $skin : $data;
				$data = (is_array($data) && !empty($data)) ? $data : '';		
				$register[$name] = array(
					'name'   => (isset($data['name'])   && !empty($data['name'])) ? $data['name'] : $name,
					'filter' => (isset($data['filter']) && !empty($data['filter'])) ? $data['filter'] : 'Standard',
					'col'    => (isset($data['col'])    && !empty($data['col'])) ? $data['col'] : 1,
					'row'    => (isset($data['row'])    && !empty($data['row'])) ? $data['row'] : 1,
				);
			}
			
			$this->skins = $register;
			
		} else {
			
			$this->skins = array();
			
		}

    }

	// get skin array
	function get_registered_skins() {
		
		return $this->skins;
		
    }

}

add_filter('tg_register_item_skin', function($skins){
	
	$skins = array(
		// grid skins
		'alofi',
		'apia',
		'bogota' => array(
			'filter' => 'Instagram'
		),
		'brasilia',
		'camberra',
		'caracas',
		'dacca',
		'honiara',
		'lisboa',
		'lome',
		'malabo',
		'male',
		'maputo' => array(
			'filter' => 'Instagram'
		),
		'oslo',
		'podgorica' => array(
			'filter' => 'Youtube/Vimeo'
		),
		'pracia',
		'roma',
		'sofia',
		'suva' => array(
			'filter' => 'Woocommerce'
		),
		// masonry skins
		'doha',
		'kampala',
		'lima',
		'lusaka',
		'maren',
		'panama',
		'praia',
		'quito',
		'riga',
		'sanaa' => array(
			'filter' => 'Woocommerce'
		),
		'victoria' => array(
			'filter' => 'Youtube/Vimeo'
		),
		'vaduz' => array(
			'filter' => 'Youtube/Vimeo'
		),
	);
	
	return $skins;
	
});
 
class The_Grid_Item_Skin {

    private $skins = array();

    function __construct() {
		
        $this->skins = apply_filters( 'tg_add_item_skin', $this->skins );
		
    }

	// get skin array
	function get_skin_names() {
		
		return $this->skins;
		
    }

}

add_filter('tg_add_item_skin', function($skins){
				
	// get upload dir for custom skins
	$wp_upload_dir = wp_upload_dir();
	$wp_theme_dir  = get_stylesheet_directory();
	
	// register all paths where skins can be stored
	$paths = array(
		// dir path for native skins
		TG_PLUGIN_PATH . 'includes/item-skins/grid/',
		TG_PLUGIN_PATH . 'includes/item-skins/masonry/',
		// dir path for theme skins
		$wp_theme_dir . '/the-grid/grid/',
		$wp_theme_dir . '/the-grid/masonry/',
	);
	
	// get all registered skins
	$register_skins_base = new The_Grid_Register_Item_Skin();
	$register_skins = $register_skins_base->get_registered_skins();
	
	// get all skin sluf (folder name)
	foreach ($paths as $path) {

		// get all folders from current path
		$folders = (is_dir($path)) ? array_diff(scandir($path), array('.', '..')) : array();
		
		// for each folder register skin
		foreach ($folders as $slug) {
			
			// if the skin was registered or custom skin from wp_upload_dir
			if (array_key_exists($slug, $register_skins) || strpos($path, $wp_upload_dir['basedir']) !== false) {
			
				// construct php & css files paths
				$php  = $path.$slug.'/'.$slug.'.php';
				$css  = $path.$slug.'/'.$slug.'.css';
				$skins[$slug] = array(
					'type'   => basename($path),
					'slug'   => $slug,
					'filter' => (isset($register_skins[$slug]['filter'])) ? $register_skins[$slug]['filter'] : 'Standard',
					'name'   => (isset($register_skins[$slug]['name']))   ? $register_skins[$slug]['name'] : $slug,
					'col'    => (isset($register_skins[$slug]['col']))    ? $register_skins[$slug]['col']  : 1,
					'row'    => (isset($register_skins[$slug]['row']))    ? $register_skins[$slug]['row']  : 1,
					'php'    => $php,
					'css'    => $css
				);
			
			}
			
		}
		
	}
	
	if (The_Grid_Base::get_purchase_code()) {
	
		// fetch custom skins
		$custom_skins = The_Grid_Custom_Table::get_skin_params();
	
		// reassigned custom skin name and params
		foreach ($custom_skins as $custom_skin) {
			$params = (isset($custom_skin['params'])) ? json_decode($custom_skin['params'], true) : null;
			$params['id'] = (isset($custom_skin['id']) && is_array($params)) ? $custom_skin['id'] : null;
			if ($params && isset($params['slug'])) {
				$skins[$params['slug']] = $params;
			}
		}
	
	}

	return $skins;

});
