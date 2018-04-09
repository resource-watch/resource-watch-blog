<?php

abstract class WPML_Singleton {

	private static $the_instance;

	private function __construct() {}

	private function __clone() {}

	public static function get_instance() {
		if ( ! self::$the_instance ) {
			self::$the_instance = new static();
		}
		return self::$the_instance;
	}

	public static function set_instance( $instance ) {
		self::$the_instance = $instance;
	}

}