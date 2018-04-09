<?php
/* 
 * Allow plugins to change menus - if they remove the theme location we have to return the correct location so
 * we can add the burger menu.
 * 
 * @since 4.1.3
 */

add_filter( 'avf_append_burger_menu_location', 'avia_filter_append_burger_menu_location', 10, 4 );

if( ! function_exists( 'avia_filter_append_burger_menu_location' ) )
{
	/**
	 * 
	 * @since 4.1.3
	 * @param string $current_location
	 * @param string $original_location
	 * @param array $items
	 * @param string|stdClass $args
	 * @return string
	 */
	function avia_filter_append_burger_menu_location( $current_location, $original_location, $items, $args )
	{
		/*	Bugfix for for Zen Menu Logic plugin - removed theme location from menu array to exchange the menus		*/
		if( class_exists( 'ZenOfWPMenuLogic' ) || class_exists( 'Themify_Conditional_Menus' ) )
		{
			$current_location = $original_location;
		}
		
		return $current_location;
	}
}
