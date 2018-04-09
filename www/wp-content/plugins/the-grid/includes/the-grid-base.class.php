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

class The_Grid_Base {
	
	/**
	* Check any Var if isset & assigned a default value
	* @since 1.0.0
	*/
	public function getVar($arr, $key, $default = ''){
		
		$val = (isset($arr[$key]) && !empty($arr[$key])) ? $arr[$key] : $default;
		return($val);
		
	}
	
	/**
	* Get all grid names
	* @since 1.0.0
	*/
	public static function get_all_grid_names() {
		
		$post_args = array(
			'post_type'      => 'the_grid',
			'post_status'    => 'any',
			'posts_per_page' => -1
		);
		
		$grids = get_posts($post_args); 
		
		if(!empty($grids)){
			foreach($grids as $grid){
				$grid_name[$grid->post_title] = $grid->ID;
			}
			return($grid_name);
		}
		
	}
	
	/**
	* Get grid list names
	* @since 1.0.0
	*/
	public static function get_grid_list_names() {
		
		$grid_list  = null;
		$grid_names = self::get_all_grid_names();
		
		if (isset($grid_names) && !empty($grid_names)) {
			foreach ($grid_names as $grid_name => $grid_ID) {
				$grid_list .= $grid_name.':'.$grid_ID.',';
			}
		}
		
		$grid_list = rtrim($grid_list, ',');
		return $grid_list;
		
	}
	
	/**
	* Get users/authors
	* @since 1.0.0
	*/
	public static function get_all_users() {
		
		$users = get_users(array(
			'orderby' => 'display_name',
			'order'   => 'DESC',
			'fields'  => array('ID', 'user_nicename'),
		));
		
		if ($users) {
			$array = array();
			foreach($users as $user){
				$array[$user->ID] = $user->user_nicename;
			}
			return $array;
		}
		
	}
	
	/**
	* Get All Post Types (builtin and custom)
	* @since 1.0.0
	*/
	public static function get_all_post_types() {
		
		$builtin_post_types = array(
			'post' => 'post',
			'page' => 'page',
			'attachment' => 'Media Library'
		);
		
		$custom_post_types = get_post_types(
			array('_builtin' => false)
		);
		
		unset($custom_post_types['the_grid']);
		if ( class_exists( 'WooCommerce' ) ) {
			unset($custom_post_types['shop_order']);	
		}
		
		$post_types = array_merge($builtin_post_types, $custom_post_types);
		foreach($post_types as $key => $type){
			$post_type_object = get_post_type_object($type);
			if(empty($post_type_object)){
				$post_types[$key] = $type;
				continue;
			}
			$post_types[$key] = $post_type_object->labels->name;
		}
		
		
		return($post_types);
		
	}
	
	/**
	* Get all taxonomy terms
	* @since 2.1.0
	* @modified 2.1.2
	*/
	public static function get_all_terms() {

		// store all terms (from all taxonomies and post types)
		$terms_list = array();
		// store each taxonomy terms list
		$post_terms = array();
		// get all post types
		$post_types = self::get_all_post_types();

		foreach ($post_types as $post_type => $name) {
			
			// get taxonomies from post type
			$taxonomies = get_object_taxonomies($post_type, 'objects');
			
			// if there are some taxonomies
			if ($taxonomies) {
				
				$taxonomies_slug = array();
				
				// for each taxonomy slug
				foreach ($taxonomies as $taxonomy => $settings) {
					
					// if this taxonomy was already proceeded
					if (isset($post_terms[$taxonomy])) {
						// store terms array from previous get_terms result
						$terms_list[$post_type]['taxonomies'][$taxonomy] = $post_terms[$taxonomy];
						
					} else {
						// start building post type taxonomy data
						$taxonomies_slug[] = $taxonomy;
						$terms_list[$post_type]['taxonomies'][$taxonomy] = array(
							'name'  => $taxonomy,
							'title' => isset($settings->label) ? $settings->label : $taxonomy
						);
					
					}
					
				}
				
				if ($taxonomies_slug) {
					
					// get all terms from current taxonomy
					$terms = get_terms($taxonomies_slug, array('hide_empty' => false, 'pad_counts' => false));
					
					if ($terms) {
					
						$terms_list[$post_type]['name']  = $name;
						$terms_list[$post_type]['count'] = count($taxonomies);
						
						foreach ($terms as $term) {
							
							if (!isset($terms_list[$post_type]['taxonomies'][$term->taxonomy]['terms'][$term->slug])) {
								
								$terms_list[$post_type]['taxonomies'][$term->taxonomy]['terms'][$term->slug] = array(
									'id'       => (int) $term->term_id,
									'slug'     => (string) $term->slug,
									'name'     => (string) $term->name,
									'title'    => (string) $term->name . ' ('.sprintf(_n( '%s post', '%s posts', ($term->count ? $term->count : 1), 'tg-text-domain' ), $term->count).')',
									'taxonomy' => (string) $term->taxonomy,
									'count'    => (int) $term->count,
									'parent'   => (int) $term->parent,
								);
								
							}
							
						}
						
						foreach ($taxonomies as $taxonomy => $settings) {
							
							// if some terms was added
							if (isset($terms_list[$post_type]['taxonomies'][$taxonomy]['terms'])) {
								// sort them hierarchically
								$terms_list[$post_type]['taxonomies'][$taxonomy]['terms'] = self::sort_terms_hierarchically($terms_list[$post_type]['taxonomies'][$taxonomy]['terms']);
							}
							
							// set main data for current taxonomy
							$count = isset($terms_list[$post_type]['taxonomies'][$taxonomy]['terms']) ? count($terms_list[$post_type]['taxonomies'][$taxonomy]['terms']) : 0;
							$terms = sprintf(_n( '%s term', '%s terms', ($count ? $count : 1), 'tg-text-domain' ), $count);
							$terms_list[$post_type]['taxonomies'][$taxonomy]['count'] = $count;
							$terms_list[$post_type]['taxonomies'][$taxonomy]['title'] = $terms_list[$post_type]['taxonomies'][$taxonomy]['title'].' ('.$terms.')';
							
							// store current taxonomy post (for later, prevent multiple calls to get_terms())
							$post_terms[$taxonomy] = $terms_list[$post_type]['taxonomies'][$taxonomy];
							
						}
					
					}
				
				}
				
			}
			
		}

		// prepare array to json (with escape)
		return htmlspecialchars(json_encode($terms_list), ENT_QUOTES, 'UTF-8');
	
	}
	
	/**
	* Get all taxonomy terms
	* @since 2.1.0
	*/
	public static function sort_terms_hierarchically($array, $id = 0, $level = 0) {

		$orderedArray = array();
	
		foreach($array as $k=>$arr) {
			
			if($arr['parent'] == $id) {
				
				$arr['title'] = str_repeat('&#8212; ', $level) . ' ' . $arr['title'];
				$orderedArray[] = $arr;
				$children = self::sort_terms_hierarchically($array, $arr['id'], $level + 1);
				
				foreach($children as $child) {
					$orderedArray[] = $child;
				}
				
			}
			
		}
	
		return $orderedArray;

	}
	
	/**
	* Retrieve all metadata
	* @since 1.0.0
	*/
	public static function get_all_meta_field() {
		
		$post_types = self::get_all_post_types();
		
		foreach( $post_types as $post_type => $value ) {
			
			if(post_type_exists($post_type)) { 
			
				$query_args = array(
					'post_type'   => $post_type,
					'numberposts' => 1,
					'post_status' => 'any'
				);
				
				$items = get_posts($query_args);
				
				if ($items) {
					
					foreach($items as $item) {
						$custom_field_keys = get_post_custom_keys($item->ID);
						if ($custom_field_keys) {
							foreach ($custom_field_keys as $key => $value) {
								$post_key[$post_type.':'.$value] = $value;
							}
						}
					}
					
				}
			}
			
		}
		
	}
	
	/**
	* Get all page title and id
	* @since 1.0.0
	*/
	public static function get_all_page_id() {
		
		$pages = get_pages();
		$pages_data = array();
		
		if (isset($pages) && !empty($pages)) {
			foreach ($pages as $page) {
				$pages_data[$page->ID] = $page->post_title;	
			} 
		}
		
		return $pages_data;
		
	}
	
	/**
	* Get post type in category
	* @since 1.0.0
	*/
	public static function get_post_ids_by_cat($post_type,$tax_query,$post_cats_child, $cat, $taxonomy='category') {
		
		return get_posts(array(
			'post_type'     => $post_type, 
			'numberposts'   => -1,
			'tax_query'     => $tax_query,
			'fields'        => 'ids',
		));
		
	}
	
	/**
	* List all available image sizes
	* @since 1.0.0
	*/
	public static function get_image_size() {
		
		$new_sizes = array();
		$added_sizes = get_intermediate_image_sizes();
		
		foreach($added_sizes as $key => $value) {
			$new_sizes[$value] = ucfirst(str_replace('_', ' ', $value));
		}
		
		$std_sizes = array(
			'full'      => __('Original Size', 'tg-text-domain'),
			'thumbnail' => __('Thumbnail', 'tg-text-domain'),
			'medium'    => __('Medium', 'tg-text-domain'),
			'large'     => __('Large', 'tg-text-domain')
		);
		
		$new_sizes = array_merge($std_sizes,$new_sizes);
		
		return $new_sizes;
	}
	
	/**
	* Sorting array data grid
	* @since 1.0.0
	*/
	public static function grid_sorting() {
		
		$sorting = array();
		$sorting['std-disabled'] = __( 'Standard', 'tg-text-domain'  );
		$sorting['none']         = __( 'None', 'tg-text-domain'  );
		$sorting['id']           = __( 'ID', 'tg-text-domain'  );
		$sorting['date']         = __( 'Date', 'tg-text-domain'  );
		$sorting['title']        = __( 'Title', 'tg-text-domain'  );
		$sorting['excerpt']      = __( 'Excerpt', 'tg-text-domain'  );
		$sorting['author']       = __( 'Author', 'tg-text-domain'  );
		$sorting['comment']      = __( 'Number of comment', 'tg-text-domain'  );
		$sorting['popular_post'] = __( 'Popular post', 'tg-text-domain'  );
		
		if ( class_exists( 'WooCommerce' ) ) {
			
			$sorting['woo_disabled']      = 'Woocommerce';
			$sorting['woo_SKU']           = __( 'SKU', 'tg-text-domain'  );
			$sorting['woo_regular_price'] = __( 'Price', 'tg-text-domain'  );
			$sorting['woo_sale_price']    = __( 'Sale Price', 'tg-text-domain'  );
			$sorting['woo_total_sales']   = __( 'Number of sales', 'tg-text-domain'  );
			$sorting['woo_featured']      = __( 'Featured Products', 'tg-text-domain'  );
			$sorting['woo_stock']         = __( 'Stock Quantity', 'tg-text-domain'  );
			
		}
		// add custom meta key to sorting
		$meta_data = get_option('the_grid_custom_meta_data', '');
		
		if (isset($meta_data) && !empty($meta_data) && json_decode($meta_data) != null) {
			
			$meta_data = json_decode($meta_data, true);
			$sorting['meta_disabled'] = __( 'Custom meta data', 'tg-text-domain' );
			
			foreach($meta_data as $meta) {
				$sorting[$meta['key']] = $meta['name'];
			}
			
		}
		
		return $sorting;
		
	}
	
	/**
	* Compress css function
	* @since 1.0.0
	*/
	public static function compress_css($styles) {
		
		$styles = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styles);
    	$styles = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $styles);
		$styles = str_replace(' {', '{', $styles);
		$styles = str_replace('{ ', '{', $styles);
    	$styles = str_replace(' }', '}', $styles);
		$styles = str_replace( '} ', '}', $styles);
		$styles = str_replace( ';}', '}', $styles);
		$styles = str_replace( ', ', ',', $styles);
		$styles = str_replace('; ', ';', $styles);
		$styles = str_replace(': ', ':', $styles);
		
		return $styles;
		
	}
	
	/**
	* Delete specific transient name
	* @since 1.0.0
	*/
	public static function delete_transient($grid_name) {
		
		global $wpdb;
		
		// transient SQL
		$sql = "SELECT `option_name` AS `name`, `option_value` AS `value`
				FROM  $wpdb->options
				WHERE `option_name` LIKE '%_transient_timeout_%'
				ORDER BY `option_name`";
				
		$results = $wpdb->get_results($sql);
		$transients = array();
		
		// loop through each transient option
		foreach ($results as $result) {
			// if transient option name matched then delete it (only if can expire)
			if (strpos($result->name, $grid_name)) {
				$name = str_replace('_transient_timeout_','',$result->name);
				delete_transient($name);
			}
		}	
		
	}
	
	/**
	* Lighter color function
	* @since 1.0.0
	*/
	public static function HEXLighter($col,$ratio) {
		
		$col = Array(hexdec(substr($col, 1, 2)), hexdec(substr($col, 3, 2)), hexdec(substr($col, 5, 2)));
		
		$lighter = Array(
			255-(255-$col[0])/$ratio,
			255-(255-$col[1])/$ratio,
			255-(255-$col[2])/$ratio
		);
		
		return "#".sprintf("%02X%02X%02X", $lighter[0], $lighter[1], $lighter[2]);
		
	}
	
	/**
	* Darker color function
	* @since 1.0.0
	*/
	public static function HEXDarker($col,$ratio) {
		
		$col = Array(hexdec(substr($col, 1, 2)), hexdec(substr($col, 3, 2)), hexdec(substr($col, 5, 2)));
		
		$darker = Array(
			$col[0]/$ratio,
			$col[1]/$ratio,
			$col[2]/$ratio
		);
		
		return '#'.sprintf('%02X%02X%02X', $darker[0], $darker[1], $darker[2]);
		
	}
	
	/**
	* HEX to RGB function
	* @since 1.0.0
	*/
	public static function HEX2RGB($hex,$alpha=1) {
		
		$hex = str_replace("#", "", $hex);
		
		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		
		$rgb['red']   = $r;
		$rgb['green'] = $g;
		$rgb['blue']  = $b;
		
		if ($alpha < 1) {
			$rgb = 'rgba('.$r.','.$g.','.$b.','.$alpha.')';
		}
		
		return $rgb;
	}
	
	/**
	* RGB to HEX function
	* @since 1.0.0
	*/
	public static function RGB2HEX($rgb) {
		
	   $hex  = str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
	   $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
	   $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);
	   return $hex;
	   
	}
	
	/**
	* Brightness Color function
	* @since 1.0.0
	*/
	public static function brightness($hex) {

		$rgb = self::HEX2RGB($hex);
		
		$r = ((float)$rgb['red']) / 255.0;
		$g = ((float)$rgb['green']) / 255.0;
		$b = ((float)$rgb['blue']) / 255.0;

		$maxC = max($r, $g, $b);
		$minC = min($r, $g, $b);

		$l = ($maxC + $minC) / 2.0;
		$l = (int)round(255.0 * $l);
		
		if($l > 200) {
			$brightness = 'bright';
		} else {
			$brightness = 'dark';
		}
		
		return $brightness;

	}
	
	/**
	* Search in array strpos function
	* @since 1.0.0
	*/
	public static function strpos_array($haystack, $needles, $offset = 0) {
		
		if (is_array($needles)) {
			
			foreach ($needles as $needle) {
				$pos = self::strpos_array($haystack, $needle);
				if ($pos !== false) {
					return true;
				}
			}
			
			return false;
			
		} else {
			
			return strpos($haystack, $needles, $offset);
			
		}
		
	}	
	
	/**
	* Shorten long numbers (K/M/B) 
	* @since 1.0.0
	* @modified 1.4.5
	*/
	public function shorten_number_format($n, $precision = 1) {

		if ($n < 1000) {
			$shorten  = '';
			$n_format = $n;
		} else if ($n >= 1000 && $n <= 999999) {
			$shorten  = 'k';
			$n_format = $n / 1000;
		} else if ($n <= 1000000000) {
			$shorten  = 'M';
			$n_format = $n / 1000000;
		} else {
			$shorten  = 'B';
			$n_format = $n / 1000000000;
		}

		$whole = floor($n_format);
		$float = (int) $n_format - (int) $whole > 0 ? str_replace('0.', '', $n_format - $whole) : '';
		$float = isset($float[0]) && $float[0] > 0 ? '.'.$float[0] : '';
		$n_format = (int) $n_format.$float.$shorten;
		
		
    	return $n_format;

	}
	
	/**
	* Set to bytes 
	* @since 1.5.0
	*/
	public function setting_to_bytes($setting) {
		
		$short = array(
			'k' => 0x400,
			'm' => 0x100000,
			'g' => 0x40000000
		);
	   
		$setting = (string) $setting;
		
		if (!($len = strlen($setting))) {
			return null;
		}
		
		$last     = strtolower($setting[$len - 1]);
		$numeric  = 0 + (int) $setting;
		$numeric *= isset($short[$last]) ? $short[$last] : 1;
		
		return $numeric;
		
	}
	
	/**
	* Shorthand css properties (margin, padding, border-width, etc...)
	* @since 1.6.0
	*/
	function shorthand($value){
		
        $values = explode(' ',$value);
		
        switch(count($values)) {
            case 4:
            	if ($values[0] == $values[1] && $values[0] == $values[2] && $values[0] == $values[3]) {
                	return $values[0];
				} else if ($values[1] == $values[3] && $values[0] == $values[2]) {
					return $values[0].' '.$values[1];
				} else if ($values[1] == $values[3]) {
					return $values[0].' '.$values[1].' '.$values[2];
				}
				break;
			case 3:
				if ($values[0] == $values[1] && $values[0] == $values[2]) {
					return $values[0];
				} else if ($values[0] == $values[2]) {
					return $values[0].' '.$values[1];
				}
            	break;
			case 2:
				if($values[0] == $values[1]) {
					return $values[0];
				}
            	break;
        }

        return $value;
		
    }
	
	/**
	* Encode into base58
	* @since 2.0.5
	*/
	public static function base58_encode($num){
		
		$alphabet = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
		$base_count = strlen($alphabet);
		$encoded = '';
	
		while ($num >= $base_count) {
			$div = $num / $base_count;
			$mod = ($num - ($base_count * intval($div)));
			$encoded = $alphabet[$mod] . $encoded;
			$num = intval($div);
		}

		if ($num) {
			$encoded = $alphabet[$num] . $encoded;
		}

		return $encoded;

	}
	
	/**
	* Get css direction values (top, right, bottom, left)
	* @since 1.7.0
	*/
	function get_css_directions($val1, $val2, $val3, $val4, $unit, $imp, $arr, $nb = 4, $shorthand = true){
		
		if (isset($arr) && $arr) {
		
			$imp  = isset($arr[$imp]) && $arr[$imp] ? ' !important' : null;
			$unit = isset($arr[$unit]) && !empty($arr[$unit]) ? $arr[$unit] : 'px';
			$val1 = isset($arr[$val1]) ? $arr[$val1] : null;
			$val2 = isset($arr[$val2]) ? $arr[$val2] : null;
			$val3 = isset($arr[$val3]) ? $arr[$val3] : null;
			$val4 = isset($arr[$val4]) ? $arr[$val4] : null;
			
			// is there is at least one numeric value
			if (is_numeric($val1) || is_numeric($val2) ||is_numeric($val3) || is_numeric($val4)) {
				
				$val1 = $val1 ? $val1.$unit : 0;
				$val2 = $val2 ? $val2.$unit : 0;
				$val3 = $val3 ? $val3.$unit : 0;
				$val4 = $val4 ? $val4.$unit : 0;
				
				if (!$shorthand) {
					return ($nb == 4) ? (string) $val1.' '.$val2.' '.$val3.' '.$val4.$imp : (string) $val1.' '.$val2.' '.$val3.$imp;
				} else {
					return ($nb == 4) ? (string) $this->shorthand($val1.' '.$val2.' '.$val3.' '.$val4).$imp : (string) $this->shorthand($val1.' '.$val2.' '.$val3).$imp;
				}
				
			}
		
		}
   
    }
	
	/**
	* Var export to prettify array
	* @since 1.0.0
	*/
	public static function var_export_min($var, $return = false) {
		
		if (is_array($var)) {
			
			$toImplode = array();
			
			foreach ($var as $key => $value) {
				$toImplode[] = var_export($key, true).' => '.self::var_export_min($value, true);
			}
			
			$code = 'array('.implode(', ', $toImplode).')';
			
			if ($return) {
				return $code;
			} else {
				echo $code;
			}
			
		} else {
			
			return str_ireplace('NULL', "''", var_export($var, $return));
			
		}
		
	}
	
	/**
	* Parse and check for css error
	* @since 1.0.0
	*/
	public static function parse_css($css_str = '') {
		
		if (!empty($css_str)) {
			
			$css      = array();
			$aCSSItem = array();
			$cssstr   = $css_str;
					
			// Strip all line endings and both single and multiline comments
			$css_str   = preg_replace('/\/\*.+?\*\//s', '', $css_str);
			$css_class = explode('}', $css_str);
			
			while (list($key, $val) = each($css_class)) {
				
				$aCSSObj = explode('{', $val);
				$cSel = strtolower(trim($aCSSObj[0]));
				
				if($cSel){
					
					$cssprops[] = $cSel;
					$a = explode(';', $aCSSObj[1]);
					
					while (list($key, $val0) = each($a)){
						
						if(trim($val0)){
								  
							$aCSSSub = explode(':', $val0);
							$cAtt = strtolower(trim($aCSSSub[0]));
							
							if(isset($aCSSSub[1])){
								$aCSSItem[$cAtt] = trim($aCSSSub[1]);
							} 
							
						}
						
					}
					
					if (isset($css[$cSel]) && $css[$cSel]){
						$aCSSItem = array_merge($css[$cSel], $aCSSItem);
					}
										
					$css[$cSel] = $aCSSItem;
					$aCSSItem = array();
					
				}
				
				if(strstr($cSel, ',')){
					
					$aTags = explode(',', $cSel);
					foreach($aTags as $key0 => $value0){
						$css[$value0] = $css[$cSel];
					}
					unset($css[$cSel]);
					
				}	
							
			} 
			
			$cssstr = null;	
				
			foreach ($css as $key0 => $value0) {
				
				$trimmed = trim($key0);
				
				if (isset($css[$key0]) && !empty($css[$key0])) {
					
					$cssstr .= $trimmed.' {'. "\n";
					foreach ($css[$key0] as $key1 => $value1) {
						$cssstr .= "\t". $key1 .': '. $value1 .";\n";
					}
					$cssstr .= "}\n";
					
				}
				
			}
			
			return $cssstr;
		
		}
		
	}
	
	/**
	* Shorten content while preserving HTML tag
	* @since: 2.0.8
	*/
	public function truncate_html($text, $length = 100, $suffix = '', $exact = false) {
		
		// if the plain text is shorter than the maximum length, return the whole text
		if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}
			
		// splits all html-tags to scanable lines
		preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
		$total_length = strlen($suffix);
		$open_tags = array();
		$truncate = '';
			
		foreach ($lines as $line_matchings) {
				
			// if there is any html-tag in this line, handle it and add it (uncounted) to the output
			if (!empty($line_matchings[1])) {
				
				// if it's an "empty element" with or without xhtml-conform closing slash
				if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
					// do nothing
					// if tag is a closing tag
				} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
					// delete tag from $open_tags list
					$pos = array_search($tag_matchings[1], $open_tags);
					if ($pos !== false) {
						unset($open_tags[$pos]);
					}
				// if tag is an opening tag
				} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
					// add tag to the beginning of $open_tags list
					array_unshift($open_tags, strtolower($tag_matchings[1]));
				}
				
				// add html-tag to $truncate'd text
				$truncate .= $line_matchings[1];
				
			}
		
			// calculate the length of the plain text part of the line; handle entities as one character
			$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
			
			if ($total_length+$content_length > $length) {
				
				// the number of characters which are left
				$left = $length - $total_length;
				$entities_length = 0;
				
				// search for html entities
				if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
					// calculate the real length of all entities in the legal range
					foreach ($entities[0] as $entity) {
						if ($entity[1]+1-$entities_length <= $left) {
							$left--;
							$entities_length += strlen($entity[0]);
						} else {
							// no more characters left
							break;
						}
					}
				}
				
				$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
				// maximum lenght is reached, so get off the loop
				break;
				
			} else {
				$truncate .= $line_matchings[2];
				$total_length += $content_length;
			}
			
			// if the maximum length is reached, get off the loop
			if($total_length>= $length) {
				break;
			}
		}
		
		// if the words shouldn't be cut in the middle...
		if (!$exact) {
			
			// ...search the last occurance of a space...
			$spacepos = strrpos($truncate, ' ');
			if (isset($spacepos)) {
				// ...and cut the text in this position
				$truncate = substr($truncate, 0, $spacepos);
			}
			
		}
		
		// add the defined ending to the text
		$truncate .= $suffix;
		
		// close all unclosed html-tags
		foreach ($open_tags as $tag) {
			$truncate .= '</' . $tag . '>';
		}
		
		return $truncate;
			
	}
	
	/**
	* Get Google Fonts url
	* @since 1.7.0
	*/
	public static function get_google_fonts($fonts) {
		
		$font_url = null;
		
		if (isset($fonts['google_font'])) {
			
			$first = true;
			$fonts['google_font'] = $fonts['google_font'];

			foreach ($fonts['google_font'] as $type => $font) {
				
				// make unique array
				$font['variants'] = isset($font['variants']) && is_array($font['variants']) ? array_map('unserialize', array_unique(array_map('serialize', $font['variants']))) : null;
				$font['subsets']  = isset($font['subsets']) && is_array($font['subsets']) ? array_map('unserialize', array_unique(array_map('serialize', $font['subsets']))) : null;
				
				// create google font url from subsets and variants
				$variants  = (isset($font['variants']) && !empty($font['variants'])) ? ':'.implode(',', $font['variants']) : null;
				$subsets   = (isset($font['subsets']) && !empty($font['subsets']) && count($fonts['google_font']) == 1 && implode('', $font['subsets']) != 'latin') ? '&subset='.implode(',', $font['subsets']) : null;
				$font_url .= ($first) ? $type.$variants.$subsets : '|'.$type.$variants.$subsets;
				
				$first = false;
				
			}
			
			$font_url = '//fonts.googleapis.com/css?family='.urlencode($font_url);
			
		}
		
		return $font_url;
	
	}
	
	/**
	* Get Default Grid Skins
	* @since 1.0.0
	*/
	public static function default_skin($style) {
		
		$default_skin = ($style == 'grid') ? 'brasilia' : 'kampala';
		$item_base = new The_Grid_Item_Skin();
		$get_skins = $item_base->get_skin_names();
		
		if (!array_key_exists($default_skin,$get_skins)){
			$default_skin = null;
			foreach($get_skins as $skin => $data) {
				if ($data['type'] == $style) {
				 	$default_skin = $data['slug'];
					break;
				}
			}
		}
		
		return $default_skin;
		
	}
	
	/**
	* Detect IE browsers
	* @since 1.0.0
	*/
	public static function is_ie() {
		
		if(isset($_SERVER) && !empty($_SERVER) && isset($_SERVER['HTTP_USER_AGENT'])) {
			if ((strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false) || preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT'])) {
				return 'is-ie';
			}
		}
	
	}
	
	/**
	* Request remote data
	* @since 1.0.0
	*/
	public static function request_data($url) {

		$response = null;

		if(!empty($url)) {
			
			// First, we try to use wp_remote_get
			$response = wp_remote_get($url);
			if(is_wp_error($response)) {
		
				// If wp_remote_get failed try file_get_contents
				$response = file_get_contents($url);
				if(false == $response) {
					$response = null;
				}
		
			}
		
			// If response is an array, it's coming from wp_remote_get,
			if(is_array($response)) {
				$response = $response['body'];
			}
		}
	
		return $response;
	
	}
	
	/**
	* Build the grid list for shortcode and export form
	* @since 1.0.7
	*/
	public function get_grid_shortcode_list($value = ''){

		$output = null;
		$list   = $this->get_grid_list();
		
		if ($list) {
			
			$output .= '<label class="tg-grid-list-label">'.__("Select a grid from the list", 'tg-text-domain').'</label>';
			$output .= '<div class="tg-list-item-wrapper" data-multi-select="">';
				$output .= '<div class="tg-list-item-search-holder">';
					$output .= '<input type="text" class="tg-list-item-search" placeholder="'.__("Type to Search...", 'tg-text-domain').'" />';
					$output .= '<i class="tg-list-item-search-icon dashicons dashicons-search"></i>';
				$output .= '</div>';
				$output .= '<ul class="tg-list-item-holder">';
				$output .= $list;
				$output .= '</ul>';
				$output .= '<input name="name" type="hidden" class="tg-grid-shortcode-value wpb_vc_param_value wpb-input wpb-text" value="'.$value.'"/>';
			$output .= '</div>';
			
		} else {
			
			$output .= '<p>'. __( 'Currently, you don&#39;t have any grid.', 'tg-text-domain'  );
			$output .= '<br>'. __( 'You need to add a grid in order to export it.', 'tg-text-domain'  );
			$output .= '<br>'. __( 'You can create a new grid', 'tg-text-domain'  );
			$output .= ' <a href="'.admin_url( 'post-new.php?post_type=the_grid').'">'. __( 'here.', 'tg-text-domain'  ) .'</a></p>';
			
		}
		
		return $output;
			
	}
	
	/**
	* Build grid list
	* @since 1.0.7
	*/
	public function get_grid_list(){
		
		$current_page = esc_html(get_admin_page_title());
		
		$WPML = new The_Grid_WPML();
		$WPML_meta_query = ($current_page != 'Import/Export') ? $WPML->WPML_meta_query() : null;
		
		$post_args = array(
			'post_type'      => 'the_grid',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'orderby'        => 'modified',
			'meta_query' => array(
				'relation' => 'AND',
				$WPML_meta_query
			),
			'suppress_filters' => true,
			'no_found_rows' => true,
			'cache_results' => false
		);
		
		$grids = get_posts($post_args);
		
		$grid_list = null;
		foreach($grids as $grid){
			
			$grid_title = $grid->post_title;
			$grid_id    = $grid->ID;
			$WPML_flag_data = $WPML->WPML_flag_data($grid_id);
			$WPML_flag_data = (!empty($WPML_flag_data)) ? '<img src="'.esc_url($WPML_flag_data['url']).'">' : '';
			
			$grid_list .= '<li class="tg-list-item" data-type="grid" data-name="'.esc_attr($grid_title).'" data-id="'.esc_attr($grid_id).'">';
				$grid_list .= (!empty($WPML_flag_data)) ? '<span>'.$WPML_flag_data.'</span>' : null;
				$grid_list .= '<span><b>'.esc_attr($grid_title).'</b></span>';
			$grid_list .= '</li>';

		}
		
		return $grid_list;
		
	}
	
	/**
	* Build custom skin list
	* @since 1.6.0
	*/
	public function get_skin_list(){
		
		if ($this->get_purchase_code()) {
		
			// fetch custom skins
			$custom_skins = (array) The_Grid_Custom_Table::get_skin_params();
			
			$skin_list = null;
			foreach ($custom_skins as $custom_skin) {
				$params = json_decode($custom_skin['params'], true);
				$skin_list .= '<li class="tg-list-item" data-type="skin" data-name="'.esc_attr($params['name']).'" data-id="'.esc_attr($custom_skin['id']).'">';
					$skin_list .= '<span><b>'.esc_attr($params['name']).'</b></span>';
				$skin_list .= '</li>';
			}
			
			return $skin_list;
		
		}
	
	}
	
	/**
	* Build custom element list
	* @since 1.6.0
	*/
	public function get_element_list(){
		
		if ($this->get_purchase_code()) {
		
			// fetch custom skins
			$custom_elements = (array) The_Grid_Custom_Table::get_elements();
			
			$elem_list = null;
			foreach ($custom_elements as $custom_element) {
				$elem_list .= '<li class="tg-list-item" data-type="elem" data-name="'.esc_attr($custom_element['name']).'" data-id="'.esc_attr($custom_element['id']).'">';
					$elem_list .= '<span><b>'.esc_attr($custom_element['name']).'</b></span>';
				$elem_list .= '</li>';
			}
			
			return $elem_list;
		
		}
	
	}
	
	
	/**
	* Build native/custom element
	* @since 1.6.0
	*/
	public function get_item_element($elements = array(), $is_custom = false, $ajax = false){
		
		if ($elements) {
			
			$generator = new The_Grid_Skin_Generator();
			
			$tabs = array();
			$element_data     = array();
			$element_settings = array();
			$element_styles   = null;
			$element_markup   = null;
			
			$tabs_attr = array(
				'get_the_title'           => '1-title-excerpt',
				'get_the_excerpt'         => '1-title-excerpt',
				'get_the_date'            => '2-date',
				'get_the_terms'           => '3-terms-list',
				'get_the_author'          => '4-author-avatar',
				'get_the_author_avatar'   => '4-author-avatar',
				'get_the_comments_number' => '5-nb-like-comment',
				'get_the_likes_number'    => '5-nb-like-comment',
				'get_the_meta_data'       => '6-metadata',
				'woocommerce'             => '7-woocommerce',
				'video_stream'            => '8-video_stream',				
				'media_button'            => '9-media_button',
				'social_link'             => '10-social_link',
				'icon'                    => '11-icon',
				'html'                    => '12-html',
				'line_break'              => '13-line_break',
				'other'                   => '14-other'
			);
			
			$tabs_data = array(
				'1-title-excerpt'    => array(
					'name' => __( 'Title/Excerpt (post data)', 'tg-text-domain' ),
					'icon' => 'dashicons dashicons-editor-alignleft'
				),
				'2-date'             => array(
					'name' => __( 'Date (post data)', 'tg-text-domain' ),
					'icon' => 'dashicons dashicons-calendar-alt'
				),
				'3-terms-list'       => array(
					'name' => __( 'Terms list (post data)', 'tg-text-domain' ),
					'icon' => 'dashicons dashicons-tag'
				),
				'4-author-avatar'    => array(
					'name' => __( 'Author/Avatar (post data)', 'tg-text-domain' ),
					'icon' => 'dashicons dashicons-admin-users'
				),
				'5-nb-like-comment'  => array(
					'name' => __( 'Nb of Likes/Comments (post data)', 'tg-text-domain' ),
					'icon' => 'dashicons dashicons-admin-comments'
				),
				'6-metadata'         => array(
					'name' => __( 'Metadata (post data)', 'tg-text-domain' ),
					'icon' => 'dashicons dashicons-category'
				),
				'7-woocommerce'      => array(
					'name' => __( 'Woocommerce', 'tg-text-domain' ),
					'icon' => 'dashicons dashicons-cart'
				),
				'8-video_stream'      => array(
					'name' => __( 'Youtube/Vimeo', 'tg-text-domain' ),
					'icon' => 'dashicons dashicons-video-alt3'
				),
				'9-media_button'     => array(
					'name' => __( 'Lightbox/Play button', 'tg-text-domain' ),
					'icon' => 'dashicons dashicons-format-video'
				),
				'10-social_link'     => array(
					'name' => __( 'Social Link', 'tg-text-domain' ),
					'icon' => 'dashicons dashicons-share'
				),
				'11-icon'             => array(
					'name' => __( 'Icon', 'tg-text-domain' ),
					'icon' => 'dashicons dashicons-art'
				),
				'12-html'             => array(
					'name' => __( 'HTML', 'tg-text-domain' ),
					'icon' => 'dashicons dashicons-editor-code'
				),
				'13-line_break'       => array(
					'name' => __( 'Line Break', 'tg-text-domain' ),
					'icon' => 'dashicons dashicons-editor-insertmore'
				),
				'14-other'       => array(
					'name' => __( 'Other', 'tg-text-domain' ),
					'icon' => 'dashicons dashicons-info'
				)
			);
			
			foreach ($elements as $element => $data) {
				
				$json = json_decode($data['settings'], true);
				
				// remove some styles to prevent issue in element list
				$json['styles']['is_hover'] = false;
				$json['styles']['idle_state']['top']    = '';
				$json['styles']['idle_state']['bottom'] = '';
				$json['styles']['idle_state']['left']   = '';
				$json['styles']['idle_state']['right']  = '';
				$json['styles']['idle_state']['margin-top']    = '';
				$json['styles']['idle_state']['margin-bottom'] = '';
				$json['styles']['idle_state']['margin-left']   = '';
				$json['styles']['idle_state']['margin-right']  = '';
				
				$json['styles']['idle_state']['color'] = ($json['styles']['idle_state']['color-important']) ? $json['styles']['idle_state']['color'] : null;
				
				// prepare data
				$overlay      = null;
				$important    = $json['styles']['idle_state']['color-important'];
				$background   = $json['styles']['idle_state']['background-color'];
				$color        = $json['styles']['idle_state']['color'];
				$class        = ($json['source']['source_type'] == 'line_break') ? ' tg-line-break' : null;
				$color_scheme = (isset($json['color-scheme'])) ? $json['color-scheme'] : null;
				$title        = 'title="'.__( 'Click to Edit', 'tg-text-domain' ).'"';
				
				$type = ($json['source']['source_type'] == 'post') ? $json['source']['post_content'] : $json['source']['source_type'];

				// remove animation
				unset($json['animation']);
				
				if ((empty($background) || $background == $color) && $important || $color_scheme == 'tg-light') {
					$brightness = $this->brightness($color);
					$overlay = ($brightness == 'bright' || $color_scheme == 'tg-light') ? '<div class="tg-element-overlay" style="background:rgba(0,0,0,0.3)"></div>' : null;
				}
				
				$process_styles   = ($json['source']['source_type'] == 'line_break') ? null : $generator->process_css('tg-element-custom[data-slug="'.$data['slug'].'"]', $json);
				$element_styles  .= ($process_styles) ? '<style class="tg-element-styles" data-slug="'.$data['slug'].'" type="text/css">'.$process_styles.'</style>' : $element_styles;
				$element_content  = stripslashes($json['content']);
				$element_settings[$data['slug']] = $data['settings'];//wp_json_encode(stripslashes_deep(json_decode($data['settings'], true)))
				
				$element_holder = '<div class="tg-element-holder '.$color_scheme.'">';
				
					$element_holder .= $overlay;
					$element_holder .= '<div class="tg-element-custom'.esc_attr($class).'" '.$title.' data-slug="'.esc_attr($data['slug']).'">'.$element_content.'</div>';
					$element_holder .= '<div class="tg-custom-element-overlay">';
						$element_holder .= '<div class="tg-add-element">'.__( 'Add To Skin', 'tg-text-domain' ).'</div>';
					$element_holder .= '</div>';
					$element_holder .= '<div class="tg-custom-element-name">'.esc_html($data['name']).'</div>';
					
					if ($is_custom) {
						$element_holder .= '<div class="tg-button tg-custom-element-delete" data-action="tg_delete_element" data-id="'.esc_attr($data['id']).'">';
							$element_holder .= '<i class="dashicons dashicons-trash"></i>';
						$element_holder .= '</div>';
					}	
					
				$element_holder .= '</div>';
				
				$type = (isset($tabs_attr[$type])) ? $tabs_attr[$type] : '14-other';
				$tabs[$type][] = $element_holder;
				
				$generator->reset_css();

			}

			$element_tabs = null;
			$element_content = null;
			
			uksort($tabs, 'strnatcmp');
			
			foreach ($tabs as $type => $elements) {
				$element_tabs    .= '<li class="tomb-tab" data-target="tg-tab-elements-'.$type.'">';
					$element_tabs .= '<i class="tomb-icon '.$tabs_data[$type]['icon'].'"></i>';
					$element_tabs .= $tabs_data[$type]['name'];
				$element_tabs    .= '</li>';
				$element_content .= '<div class="tomb-tab-content tg-tab-elements-'.$type.'">';
					foreach ($elements as $element) {
						$element_content .= $element;
					}
				$element_content .= '</div>';
			}
			
			$element_markup = '<div class="tg-component-style-properties">';
				$element_markup .= '<div class="tg-component-back">';
					$element_markup .= '<i class="tomb-icon dashicons dashicons-arrow-left-alt2"></i>';
					$element_markup .= '<span></span>';
				$element_markup .= '</div>';
				$element_markup .= '<div>';
					$element_markup .= '<ul class="tomb-tabs-holder tomb-tabs-elements">';
						$element_markup .= $element_tabs;
					$element_markup .= '</ul>';
					$element_markup .= $element_content;
				$element_markup .= '</div>';
			$element_markup .= '</div>';

			$element_data = array(
				'markup'   => $element_markup,
				'styles'   => $element_styles,
				'settings' => $element_settings
			);

			return $element_data;
		
		}
	
	}
	
	/**
	* Get The Grid purchase code
	* @since 2.0.0
	* @modified 2.0.5
	*/
	public static function get_purchase_code(){
		
		$unfilter    = apply_filters('tg_grid_un13306812', false);
		$plugin_info = get_option('the_grid_plugin_info', '');
		
		if (isset($plugin_info['purchase_code']) || $unfilter) {
			return true;
		} else {
			return;
		}

	}

}

/**
* Get template part slug/name
* @since 1.2.0
*/
function tg_get_template_part($slug, $name = null, $load = true, $param = null) {
	
	// Execute code for this part
	do_action('get_template_part_' . $slug, $slug, $name, $param);
	 
	// Setup possible parts
	$templates = array();
	if (isset($name)) {
		$templates[] = $slug . '-' . $name . '.php';
	}
	$templates[] = $slug . '.php';
	 
	// Allow template parts to be filtered
	$templates = apply_filters('tg_get_template_part', $templates, $slug, $name, $param);
	// Return the part that is found
	tg_locate_template($templates, $load, false, $param);
	
}
	
/**
* load template part
* @since 1.2.0
*/	
function tg_locate_template($template_names, $load = true, $require_once = true, $param = null) {
	
	// No file found yet
	$located = false;
	 
	// Try to find a template file
	foreach ((array)$template_names as $template_name) {
	 
		// Continue if template is empty
		if (empty($template_name)) {
			continue;
		}
	 
		// Trim off any slashes from the template name
		$template_name = ltrim($template_name, '/');
	 
		// Check child theme first
		if (file_exists(trailingslashit(get_stylesheet_directory()) . 'the-grid/templates/' . $template_name)) {
			$located = trailingslashit(get_stylesheet_directory()) . 'the-grid/templates/' . $template_name;
			break;
		// Check parent theme next
		} else if (file_exists(trailingslashit( get_template_directory()) . 'the-grid/templates/' . $template_name)) {
			$located = trailingslashit(get_template_directory()) . 'the-grid/templates/' . $template_name;
			break;
		// Check theme compatibility last
		} else if (file_exists(trailingslashit(TG_PLUGIN_PATH) . 'includes/templates/' . $template_name)) {
			$located = trailingslashit(TG_PLUGIN_PATH) . 'includes/templates/' . $template_name;
			break;
		}
	}

	if ((true == $load) && ! empty($located)) {
		
		if ($param) {
			$tg_grid_data = $param;
		}
		
		$tg_grid_data = $param;
		require $located;

	}

}