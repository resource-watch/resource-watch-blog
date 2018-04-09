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
 
class The_Grid_Element_Animation {
	
	private $animations = array();
	
	function __construct() {
		$this->animations = apply_filters('tg_add_element_animation', $this->animations);
	}
		
	// get custom animation array
	function get_animation_name() {
		return $this->animations;
	}
	
	// get custom animation array
	function get_animation_arr() {
		$anim_arr = array();
		$animation = $this->animations;
		foreach($animation as $slug=>$name) {
			$anim_arr[$slug] = $name['name'];
		}
		return $anim_arr;
	}
}

new The_Grid_Element_Animation();

add_filter('tg_add_element_animation', 'tg_built_in_element_animation');
function tg_built_in_element_animation($animation) {
	
	$animation['custom'] = array(
		'name'    => __('Custom', 'tg-text-domain'),
		'transform' => array(
			'translatex' => null,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> null
		)
	);
	
	$animation['fade_in'] = array(
		'name'    => __('Fade', 'tg-text-domain'),
		'transform' => array(
			'translatex' => null,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> null
		)
	);
	
	$animation['zoom_in'] = array(
		'name' => __('Zoom in', 'tg-text-domain'),
		'transform' => array(
			'translatex' => null,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => 0.5,
			'scaley'     => 0.5,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> 500
		)
	);
	
	$animation['zoom_out'] = array(
		'name' => __('Zoom Out', 'tg-text-domain'),
		'transform' => array(
			'translatex' => null,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => 1.5,
			'scaley'     => 1.5,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> 500
		)
	);
	
	$animation['top'] = array(
		'name' => __('Top', 'tg-text-domain'),
		'transform' => array(
			'translatex' => null,
			'translatey' => -100,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> null
		)
	);
	
	$animation['bottom'] = array(
		'name' => __('Bottom', 'tg-text-domain'),
		'transform' => array(
			'translatex' => null,
			'translatey' => 100,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> null
		)
	);
	
	$animation['left'] = array(
		'name' => __('Left', 'tg-text-domain'),
		'transform' => array(
			'translatex' => -100,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> null
		)
	);
	
	$animation['right'] = array(
		'name' => __('Right', 'tg-text-domain'),
		'transform' => array(
			'translatex' => 100,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> null
		)
	);
	
	$animation['top_left'] = array(
		'name' => __('Top/Left', 'tg-text-domain'),
		'transform' => array(
			'translatex' => -100,
			'translatey' => -100,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> null
		)
	);
	
	$animation['top_right'] = array(
		'name' => __('Top/Right', 'tg-text-domain'),
		'transform' => array(
			'translatex' => 100,
			'translatey' => -100,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> null
		)
	);
	
	$animation['bottom_left'] = array(
		'name' => __('Bottom/Left', 'tg-text-domain'),
		'transform' => array(
			'translatex' => -100,
			'translatey' => 100,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> null
		)
	);
	
	$animation['Bottom_right'] = array(
		'name' => __('Bottom/Right', 'tg-text-domain'),
		'transform' => array(
			'translatex' => 100,
			'translatey' => 100,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> null
		)
	);

	$animation['flip_x'] = array(
		'name' => __('Flip X', 'tg-text-domain'),
		'transform' => array(
			'translatex' => null,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => 90,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> 1600
		)
	);
	
	$animation['flip_x_top'] = array(
		'name' => __('Flip X from top', 'tg-text-domain'),
		'transform' => array(
			'translatex' => null,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => 60,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => 50,
			'originy'    => 0,
			'originz'    => null,
			'perspective'=> 1600
		)
	);
	
	$animation['flip_x_bottom'] = array(
		'name' => __('Flip X from bottom', 'tg-text-domain'),
		'transform' => array(
			'translatex' => null,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => -60,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => 50,
			'originy'    => 100,
			'originz'    => null,
			'perspective'=> 1600
		)
	);

	
	$animation['flip_y'] = array(
		'name' => __('Flip Y', 'tg-text-domain'),
		'transform' => array(
			'translatex' => null,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => 90,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> 1600
		)
	);
	
	$animation['flip_y_left'] = array(
		'name' => __('Flip Y from left', 'tg-text-domain'),
		'transform' => array(
			'translatex' => null,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => -60,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => 0,
			'originy'    => 50,
			'originz'    => null,
			'perspective'=> 1600
		)
	);
	
	$animation['flip_y_right'] = array(
		'name' => __('Flip Y from right', 'tg-text-domain'),
		'transform' => array(
			'translatex' => null,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => 60,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => null,
			'skewy'      => null,
			'originx'    => 100,
			'originy'    => 50,
			'originz'    => null,
			'perspective'=> 1600
		)
	);
	
	$animation['roll_left'] = array(
		'name' => __('Roll left', 'tg-text-domain'),
		'transform' => array(
			'translatex' => -100,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => 100,
			'rotatey'    => -100,
			'rotatez'    => 100,
			'scalex'     => 0.8,
			'scaley'     => 0.8,
			'scalez'     => null,
			'skewx'      => 20,
			'skewy'      => -20,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> null
		)
	);
	
	$animation['roll_right'] = array(
		'name' => __('Roll right', 'tg-text-domain'),
		'transform' => array(
			'translatex' => 100,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => 100,
			'rotatey'    => 100,
			'rotatez'    => 100,
			'scalex'     => 0.8,
			'scaley'     => 0.8,
			'scalez'     => null,
			'skewx'      => 20,
			'skewy'      => 20,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> null
		)
	);
	
	$animation['skew_left'] = array(
		'name' => __('Skew left', 'tg-text-domain'),
		'transform' => array(
			'translatex' => -100,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => 20,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> null
		)
	);
	
	$animation['skew_right'] = array(
		'name' => __('Skew right', 'tg-text-domain'),
		'transform' => array(
			'translatex' => 100,
			'translatey' => null,
			'translatez' => null,
			'rotatex'    => null,
			'rotatey'    => null,
			'rotatez'    => null,
			'scalex'     => null,
			'scaley'     => null,
			'scalez'     => null,
			'skewx'      => -20,
			'skewy'      => null,
			'originx'    => null,
			'originy'    => null,
			'originz'    => null,
			'perspective'=> null
		)
	);
		
	return $animation;

}