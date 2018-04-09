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

class The_Grid_WPML {
	
	/**
	* Check if WPML exist
	* @since 1.0.0
	*/
	public static function WPML_exists() {
		
		if (function_exists('icl_get_languages') && defined('ICL_LANGUAGE_CODE')) {
			return true;
		} else {
			return false;
		}
		
	}
	
	/**
	* WPML current lang
	* @since 1.0.0
	*/
	public static function WPML_current_lang() {
		
		if(self::WPML_exists()) {
			global $sitepress;
			return (isset($sitepress) && !empty($sitepress) && function_exists('icl_object_id')) ? $sitepress->get_current_language() : null;
		}
		
	}
	
	/**
	* WPML default lang
	* @since 1.0.0
	*/
	public static function WPML_default_lang() {
		
		if(self::WPML_exists()) {
			global $sitepress;
			return (isset($sitepress) && !empty($sitepress) && function_exists('icl_object_id')) ? $sitepress->get_default_language() : null;
		}
		
	}
	
	/**
	* WPML list all flags langs
	* @since 1.0.0
	*/
	public static function WPML_flags() {
		
		$WPML_flags = null;
		
		if(self::WPML_exists()) {
			
			$WPML_current_lang = self::WPML_current_lang();
			$WPML_default_lang = self::WPML_default_lang();
			$WPML_languages  = icl_get_languages('skip_missing=0');
			
			if (1 < count($WPML_languages)) {
				
				$WPML_flags .= '<div id="tg-grids-flags">';
					$WPML_flags .= '<span>'.__('Languages', 'tg-text-domain').' :</span>';
					$WPML_flags .= '<div class="tg-grids-flag"><a href="'.admin_url('admin.php?page=the_grid&lang=all').'">'.__('All', 'tg-text-domain').'</a></div> - ';
					
					foreach ($WPML_languages as $l) {
						
						$WPML_active_flag   = ($l['language_code'] == $WPML_current_lang  ) ? 'tg-active-flag' : '';
						$WPML_language_url  = admin_url('admin.php?page=the_grid&lang='.$l['language_code']);
						$WPML_language_flag = $l['country_flag_url'];
						$WPML_language_code = $l['language_code'];
						$WPML_flags .= '<div class="tg-grids-flag">'; 
							$WPML_flags .= '<a class="'.$WPML_active_flag.'" href="'.$WPML_language_url.'">';
								$WPML_flags .= '<img src="'.$WPML_language_flag.'" alt="'.$WPML_language_code.'"/>';
							$WPML_flags .= '</a>';
						$WPML_flags .= '</div>';
						
					}
					
				$WPML_flags .= '</div>';
				
			}
			
		}
		
		return $WPML_flags;
		
	}
	
	/**
	* WPML query url
	* @since 1.0.0
	*/
	public static function WPML_query_lang() {
		
		$WPML_query_lang = null;
		
		if(self::WPML_exists()) {
			
			$WPML_current_lang = self::WPML_current_lang();
			$WPML_default_lang = self::WPML_default_lang();
			$WPML_current_lang = ($WPML_current_lang == 'all') ? '' : $WPML_current_lang;
			$WPML_query_lang = (!empty($WPML_current_lang)) ? $WPML_current_lang : $WPML_default_lang;
			$WPML_query_lang = '&lang='.$WPML_query_lang;
			
		}
		
		return $WPML_query_lang;
		
	}
	
	/**
	* WPML query post url
	* @since 1.0.0
	*/
	public static function WPML_post_query_lang($grid_ID) {
		
		$WPML_query_lang   = null;
		$WPML_current_lang = get_post_meta($grid_ID, 'the_grid_language', true);
		
		if(self::WPML_exists()) {
			
			$WPML_default_lang = self::WPML_default_lang();
			$WPML_current_lang = ($WPML_current_lang == 'all') ? '' : $WPML_current_lang;
			$WPML_query_lang = (!empty($WPML_current_lang)) ? $WPML_current_lang : $WPML_default_lang;
			$WPML_query_lang = '&lang='.$WPML_query_lang;
			
		}
		
		return $WPML_query_lang;
		
	}
	
	/**
	* WPML modified meta query
	* @since 1.0.0
	*/
	public static function WPML_meta_query() {
		
		$WPML_meta_query = null;
		
		if(self::WPML_exists()) {
			
			$WPML_current_lang = self::WPML_current_lang();
			$WPML_default_lang = self::WPML_default_lang();
			$WPML_current_lang = ($WPML_current_lang == 'all') ? '' : $WPML_current_lang;
			$WPML_not_exist = array(
				'key' => 'the_grid_language',
				'value' => '',
				'compare' => 'NOT EXISTS'
			);
			$WPML_not_exist = (($WPML_current_lang == $WPML_default_lang) || empty($WPML_current_lang)) ? $WPML_not_exist : null;
			$WPML_meta_query = array (
				'relation' => 'OR',
				array(
					'key' => 'the_grid_language',
					'value' => $WPML_current_lang,
					'compare' => 'LIKE'
				),
				$WPML_not_exist
			);
			
		}
		
		return $WPML_meta_query;
		
	}
	
	/**
	* WPML flag image and alt attribute
	* @since 1.0.0
	*/
	public static function WPML_flag_data($grid_ID) {
		
		$WPML_flag_data = null;
		
		if(self::WPML_exists()) {
			
			$WPML_default_lang = self::WPML_default_lang();
			$WPML_current_lang = get_post_meta($grid_ID, 'the_grid_language', true);
			$WPML_current_lang = (isset($WPML_current_lang) && !empty($WPML_current_lang)) ? $WPML_current_lang : $WPML_default_lang;
			$WPML_languages = icl_get_languages('skip_missing=0');	
				
			if (1 < count($WPML_languages)) {
				
				foreach ($WPML_languages as $l) {
					
					if ($l['language_code'] == $WPML_current_lang) {
						$WPML_flag_url   = $l['country_flag_url'];
						$WPML_query_lang = '&lang='.$WPML_current_lang;
						break;
					}
					
				}
				
				if (isset($WPML_flag_url)) {
					
					$WPML_flag_data['url'] = $WPML_flag_url;
					$WPML_flag_data['alt'] = $WPML_current_lang;
					
				}
				
			}	
			
		}
		
		return $WPML_flag_data;
		
	}
	
	/**
	* WPML language switcher
	* @since 1.0.0
	*/
	public static function WPML_language_switcher() {
		
		$WPML_languages    = null;
		$WPML_current_lang = null;
		
		$lang_switcher = array(
			'id'   => 'the_grid',
			'name' => '',	
			'desc' => '',
			'sub_desc' => '',
			'type' => 'custom',
			'options' => $WPML_languages,
			'tab' => 'General'
		);
		
		if(self::WPML_exists()) {
			
			$WPML_current_lang = self::WPML_current_lang();
			$WPML_default_lang = self::WPML_default_lang();
			$WPML_default_lang_query = '&lang='.$WPML_current_lang;
			$WPML_languages = array();
			$WPML_langs = icl_get_languages('skip_missing=0');	
				
			if (1 < count($WPML_langs)) {
				
				foreach ($WPML_langs as $l) {
					$WPML_languages[$l['language_code']]['value'] = $l['language_code'];
					$WPML_languages[$l['language_code']]['image'] = $l['country_flag_url'];
					$WPML_languages[$l['language_code']]['label'] = $l['language_code'];
				}
				
			}
			
			$lang_switcher = array(
				'id'   => 'the_grid_language',
				'name' => __( 'Grid Language', 'tg-text-domain'  ),	
				'desc' => __( 'Select the current grid language for WPML', 'tg-text-domain'  ),
				'sub_desc' => '<strong>'.__( '* If no language are selected then the current language will be set', 'tg-text-domain' ).'</strong>',
				'type' => 'image_select',
				'placeholder' => __( 'Select a language', 'tg-text-domain' ),
				'width' => 120,
				'options' => $WPML_languages,
				'std' => $WPML_current_lang,
				'tab' => 'General'
			);
			
		}
		
		return $lang_switcher;
		
	}
	
}