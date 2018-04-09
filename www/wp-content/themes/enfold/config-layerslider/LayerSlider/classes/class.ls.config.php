<?php

class LS_Config {

	public static $config = array();

	private function __construct() {}


	public static function init() {

		self::$config = array(
			'theme_bundle' => false,
			'autoupdate' => true,
			'notices' => true,
			'purchase_url' => get_option('ls-p-url', 'https://codecanyon.net/cart/add_items?ref=kreatura&amp;item_ids=1362246')
		);
	}


	public static function has( $feature ) {
		return isset( self::$config[ $feature ] );
	}


	public static function get( $feature ) {

		if( isset( self::$config[ $feature ] ) ) {
			return self::$config[ $feature ];
		}

		return null;
	}


	public static function set( $keys, $value = null ) {

		if( is_string( $keys ) ) {
			$keys = array( "$keys" => $value );
		}

		if( is_array( $keys ) ) {
			foreach( $keys as $key => $val ) {
				self::$config[ $key ] = $val;
			}
		}
	}


	public static function setAsTheme() {

		self::set( array(
			'theme_bundle' 	=> true,
			'autoupdate' 	=> false,
			'notices' 		=> false
		) );
	}


	public static function checkCompatibility() {

		if( isset( $GLOBALS['lsAutoUpdateBox'] ) && $GLOBALS['lsAutoUpdateBox'] === false ) {
			self::set('autoupdate', false);
		}
	}
}