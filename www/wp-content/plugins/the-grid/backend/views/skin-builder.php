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

$animation_name  = new The_Grid_Element_Animation();

$like = '<span class="no-ajaxy to-post-like liked">';
	$like .= '<span class="to-heart-icon">';
		$like .= '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 64 64">';
			$like .= '<g transform="translate(0, 0)">';
				$like .= '<path stroke-width="6" stroke-linecap="square" stroke-miterlimit="10" d="M1,21c0,20,31,38,31,38s31-18,31-38 c0-8.285-6-16-15-16c-8.285,0-16,5.715-16,14c0-8.285-7.715-14-16-14C7,5,1,12.715,1,21z"></path>';
			$like .= '</g>';
		$like .= '</svg>';
	$like .= '</span>';
		$like .= '<span class="to-like-count">';
			$like .= '12';
		$like .= '</span>';
$like .= '</span>';

$excerpt = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec non quam vitae ligula viverra tempus ut et leo. Etiam interdum a sapien nec vulputate. Suspendisse eu velit maximus, facilisis orci et, ullamcorper odio. Aliquam erat volutpat. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras a erat ac velit dapibus pharetra at ut massa. Fusce nec lobortis augue. Phasellus molestie sagittis iaculis. Nulla at metus eros. Vivamus ultrices ligula leo, ut tristique odio auctor id. Sed suscipit enim nec nisi commodo bibendum. Curabitur convallis felis in bibendum elementum. Curabitur nisl massa, mattis in dictum vel, auctor quis odio. Sed nibh neque, tristique quis sem nec, mollis pretium leo. Proin quis enim pulvinar, dignissim turpis vitae, semper metus. Aenean rhoncus lectus id quam cursus tincidunt. Integer dictum sit amet orci pretium fringilla. Fusce lacinia vel dolor at tristique. Sed vehicula interdum risus venenatis molestie. Nullam';

$elements_content = array(
	'skin_was_modified'           => __( 'Some modification were made to the skin without saving it. Are you sure you want to leave?', 'tg-text-domain' ),
	'click_to_edit_string'        => __( 'Click to Edit', 'tg-text-domain' ),
	'important_string'            => __( 'add important rule', 'tg-text-domain' ),
	'get_the_title'               => __( 'The Post Title', 'tg-text-domain' ),
	'get_the_excerpt'             => $excerpt,
	'get_the_date'                => get_option('date_format'),
	'get_the_terms'               => __( 'Term', 'tg-text-domain' ),
	'get_the_author'              => __( 'Author Name', 'tg-text-domain' ),
	'get_the_author_avatar'       => '<div class="tg-item-author-avatar"></div>',
	'get_the_comments_number'     => __( '1 comment', 'tg-text-domain' ),
	'get_the_likes_number'        => $like,
	'get_media_button'            => __( 'Lightbox', 'tg-text-domain' ),
	'get_link_button'             => __( 'Link', 'tg-text-domain' ),
	'get_the_meta_data'           => '',
	'get_product_price'           => '<span class="amount">$179</span>',
	'get_product_full_price'      => '<del><span class="amount">$179</span></del> <ins><span class="amount">$99</span></ins>',
	'get_product_regular_price'   => '<span class="amount">$179</span>',
	'get_product_sale_price'      => '<span class="amount">$99</span>',
	'get_product_on_sale'         => __( 'Sale!', 'tg-text-domain' ),
	'get_product_cart_button'     => '<div class="add_to_cart_button">'.__( 'Add to Cart', 'tg-text-domain' ).'</div>',
	'get_product_add_to_cart_url' => __( 'Add to Cart', 'tg-text-domain' ),
	'get_product_text_rating'     => __( '4.5 out of 5', 'tg-text-domain' ),
	'get_the_views_number'        => '12.5K',
	'views_number_suffix'         => __( 'views', 'tg-text-domain' ),
	'get_the_duration'            => '06:25',
	'line_break'                  => '<span class="tg-line-break-inner"><span>'.__( 'line break', 'tg-text-domain' ).'</span></span>'
);

// icon Fonts array
require_once(TG_PLUGIN_PATH . '/includes/icon-fonts.php');

$icon_list = null;
foreach ($tg_icons as $icon) {
	$icon_list .= '<i class="'.$icon.'"></i>';
}

// set prefix for metabox fields
$prefix = TG_PREFIX;

function tg_css_multiple_input($prefix,$type,$values,$units,$std = '', $min = '', $max = '', $step = 1) {
	$i = 0;
	
	$html = null;

	if ($type == 'positions' && $prefix == 'element_idle_' ) {
		
		$html .= '<div class="tg-move-from-holder">';
		
		$html .= '<div class="tomb-select-holder" data-noresult="'. __( 'No results found', 'tg-text-domain' ) .'" data-clear="" style="width:145px;vertical-align:top;margin: 1px;">';
			$html .= '<div class="tomb-select-fake">';
				$html .= '<span class="tomb-select-value">px</span>';
				$html .= '<span class="tomb-select-arrow"><i></i></span>';
			$html .= '</div>';
			$html .= '<select class="tomb-select" name="'.$prefix.$type.'-from" data-clear="">';
				$html .= '<option value="t/l">'.__( 'From top/left', 'tg-text-domain' ).'</option>';
				$html .= '<option value="t/r">'.__( 'From top/right', 'tg-text-domain' ).'</option>';
				$html .= '<option value="b/l">'.__( 'From bottom/left', 'tg-text-domain' ).'</option>';
				$html .= '<option value="b/r">'.__( 'From bottom/right', 'tg-text-domain' ).'</option>';
			$html .= '</select>';
		$html .= '</div>';
		
		$html .= '</div>';
	
	}
	
	$html .= '<div class="tg-number-fields">';
		foreach($values as $value) {
			if (count($values) > 1) {
				$html .= '<span class="tg-filter-tooltip-holder">';
					$tooltip = str_replace(array('-','margin','padding','border','radius','shadow','inset','text','box'), array(' ','','','','','','',''),$value);
					$min   = (strrpos($value, 'shadow-size') !== false) ? 0 : $min;
					$min   = (strrpos($value, 'shadow-blur') !== false) ? 0 : $min;
					$def   = (isset($std[$i])) ? $std[$i] : '';
					$html .= '<input type="number" class="tomb-text number mini" name="'.$prefix.$value.'" value="'.$def.'" step="'.$step.'" min="'.$min.'" max="'.$max.'" > ';
					$html .= '<span class="tg-filter-tooltip">'.ucfirst($tooltip).'</span>';
				$html .= '</span>';
				$i++;
			} else {
				$html .= '<input type="number" class="tomb-text number mini" name="'.$prefix.$value.'" value="'.$std.'" step="'.$step.'" min="'.$min.'" max="'.$max.'" > ';
			}
			
		}
		$html .= tg_css_unit($prefix,$type, $units);
	$html .= '</div>';
	return $html;
}

function tg_css_animation_type($prefix) {
	

	$html = '<div class="tomb-select-holder" data-noresult="'. __( 'No results found', 'tg-text-domain' ) .'" data-clear="" style="width:180px;">';
		$html .= '<div class="tomb-select-fake">';
			$html .= '<span class="tomb-select-value"></span>';
			$html .= '<span class="tomb-select-arrow"><i></i></span>';
		$html .= '</div>';
		
		$html .= '<select class="tomb-select" name="'.$prefix.'_animation_type" data-clear="">';
			$html .= '<option value="show">'.__( 'Animate & show', 'tg-text-domain' ).'</option>';
			$html .= '<option value="hide">'.__( 'Animate & hide', 'tg-text-domain' ).'</option>';
			$html .= '<option value="animate">'.__( 'Animate Only', 'tg-text-domain' ).'</option>';
		$html .= '</select>';
	$html .= '</div>';
	
	$html .= '<div class="tomb-select-holder" data-noresult="'. __( 'No results found', 'tg-text-domain' ) .'" data-clear="" style="width:75px;">';
		$html .= '<div class="tomb-select-fake">';
			$html .= '<span class="tomb-select-value"></span>';
			$html .= '<span class="tomb-select-arrow"><i></i></span>';
		$html .= '</div>';
		$html .= '<select class="tomb-select" name="'.$prefix.'_animation_position" data-clear="">';
			$html .= '<option value="from">'.__( 'From', 'tg-text-domain' ).'</option>';
			$html .= '<option value="to">'.__( 'To', 'tg-text-domain' ).'</option>';
		$html .= '</select>';
		$html .= '<span class="tg-filter-tooltip-holder">';
			$html .= '<span class="tg-filter-tooltip">'. sprintf(__( 'Transformed %s position', 'tg-text-domain' ), '<br>') .'</span>';
		$html .= '</span>';
	$html .= '</div>';
	
	return $html;

}

function tg_css_transform($prefix, $animation_name) {
	
	$transforms = array(
		__( 'translate', 'tg-text-domain' )   => array('x','y','z'),
		__( 'rotate', 'tg-text-domain' )      => array('x','y','z'),
		__( 'scale', 'tg-text-domain' )       => array('x','y','z'),
		__( 'skew', 'tg-text-domain' )        => array('x','y'),
		__( 'origin', 'tg-text-domain' )      => array('x','y','z'),
		__( 'perspective', 'tg-text-domain' ) => array('')
	);

	$def_step = '1';
	$def_min  = '-5000';
	$max  = '5000';

	$html = '<div class="tomb-select-holder" data-noresult="'. __( 'No results found', 'tg-text-domain' ) .'" data-clear="1" style="width:180px;">';
		$html .= '<div class="tomb-select-fake">';
			$html .= '<span class="tomb-select-value"></span>';
			$html .= '<span class="tomb-select-arrow"><i></i></span>';
			$html .= '<span class="tomb-select-placeholder">'. __( 'Select an Animation', 'tg-text-domain' ) .'</span>';
			$html .= '<span class="tomb-select-clear" style="display: block;">×</span>';
		$html .= '</div>';
		$html .= '<select class="tomb-select" name="'.$prefix.'_animation_name" data-clear="">';
			$animations = $animation_name->get_animation_arr();
			$html .= '<option></option>';
			foreach($animations as $value => $name) {
				$html .= '<option value="'.$value.'">'.$name.'</option>';
			}
		$html .= '</select>';
	$html .= '</div>';
	
	$html .= '<div class="tg-toogle-transform">'. __( 'Show Transform', 'tg-text-domain' ) .'</div>';
	
	$html .= '<div class="tg-transform-fields">';
	foreach($transforms as $transform => $type) {
		$html .= '<label class="tomb-label">'.ucfirst($transform).'</label>';
		$html .= '<div class="tg-number-fields">';
			foreach($type as $value) {
				$html .= ($value) ? '<span class="tg-filter-tooltip-holder">' : null;
					$tooltip = $value;
					$min   = (strrpos($transform, 'scale') !== false) ? 0 : $def_min;
					$step  = (strrpos($transform, 'scale') !== false) ? 0.01 : $def_step;
					$html .= '<input type="number" class="tomb-text number mini" name="'.$prefix.'_'.$transform.$value.'" value="" step="'.$step.'" min="'.$min.'" max="'.$max.'" > ';
					$html .= ($value) ? '<span class="tg-filter-tooltip">'.ucfirst($tooltip).'</span>' : null;
				$html .= ($value) ? '</span>' : null;
			}
			$html .= ($transform == 'translate') ? tg_css_unit($prefix.'_', $transform, array('px','%')) : null;
			$html .= ($transform == 'rotate')      ? '<span class="tg-transform-unit">'.__( 'deg', 'tg-text-domain' ).'</span>' : null;
			$html .= ($transform == 'scale')       ? '<span class="tg-transform-unit">X</span>' : null;
			$html .= ($transform == 'skew')        ? '<span class="tg-transform-unit">'.__( 'deg', 'tg-text-domain' ).'</span>' : null;
			$html .= ($transform == 'origin')      ? '<span class="tg-transform-unit">%</span>' : null;
			$html .= ($transform == 'perspective') ? '<span class="tg-transform-unit">px</span>' : null;
		$html .= '</div>';
	}
	$html .= '</div>';
		
	return $html;

}

function tg_css_unit($prefix,$type, $units) {
	
	$html = '<div class="tomb-select-holder" data-noresult="'. __( 'No results found', 'tg-text-domain' ) .'" data-clear="" style="width:65px;vertical-align:top;margin: 1px;">';
		$html .= '<div class="tomb-select-fake">';
			$html .= '<span class="tomb-select-value">px</span>';
			$html .= '<span class="tomb-select-arrow"><i></i></span>';
		$html .= '</div>';
		$html .= '<select class="tomb-select tg-css-unit" name="'.$prefix.$type.'-unit" data-clear="">';
			foreach($units as $unit) {
				$html .= '<option value="'.$unit.'">'.$unit.'</option>';
			}
		$html .= '</select>';
	$html .= '</div>';
	
	return $html;
}

function css_position($prefix, $std = 'relative') {
	return array(
		'id'   => $prefix.'position',
		'name' => __( 'Position', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 160,
		'options' => array(
			'relative' => __( 'Relative', 'tg-text-domain' ),
			'absolute' => __( 'Absolute', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Position', 'tg-text-domain' )
	);
}

function css_display($prefix, $std = 'inline-block') {
	return array(
		'id'   => $prefix.'display',
		'name' => __( 'Display', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 160,
		'options' => array(
			'block' => __( 'Block', 'tg-text-domain' ),
			'inline-block' => __( 'Inline-block', 'tg-text-domain' ),
			'inline' => __( 'Inline (rare case)', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Position', 'tg-text-domain' ),
		'required' => array(
			array($prefix.'position', '==', 'relative')
		)
	);
}

function css_overflow($prefix, $std = '') {
	return array(
		'id'   => $prefix.'overflow',
		'name' => __( 'Overflow', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 160,
		'placeholder' => __( 'Select a value', 'tg-text-domain' ),
		'clear' => true,
		'options' => array(
			'visible' => __( 'Visible', 'tg-text-domain' ),
			'hidden' => __( 'Hidden', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Position', 'tg-text-domain' )
	);
}

function css_float($prefix, $std = '') {
	return array(
		'id'   => $prefix.'float',
		'name' => __( 'Float', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 160,
		'placeholder' => __( 'Select a value', 'tg-text-domain' ),
		'clear' => true,
		'options' => array(
			'none'  => __( 'None', 'tg-text-domain' ),
			'left'  => __( 'Left', 'tg-text-domain' ),
			'right' => __( 'right', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Position', 'tg-text-domain' ),
		'required' => array(
			array($prefix.'position', '==', 'relative'),
			array($prefix.'display', '==', 'inline-block')
		)
	);
}

function css_clear($prefix, $std = '') {
	return array(
		'id'   => $prefix.'clear',
		'name' => __( 'Clear', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 160,
		'placeholder' => __( 'Select a value', 'tg-text-domain' ),
		'clear' => true,
		'options' => array(
			'none'  => __( 'None', 'tg-text-domain' ),
			'left'  => __( 'Left', 'tg-text-domain' ),
			'right' => __( 'right', 'tg-text-domain' ),
			'both'  => __( 'both', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Position', 'tg-text-domain' ),
		'required' => array(
			array($prefix.'position', '==', 'relative')
		)
	);
}

function css_zindex($prefix, $std = '') {
	return array(
		'id'   => $prefix.'z-index',
		'name' => __( 'Z Index', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'number',
		'min' => 0,
		'max' => 999,
		'std' => '',
		'options' => '',
		'tab' => __( 'Position', 'tg-text-domain' )
	);
}

function css_positions($prefix, $std = '') {
	return array(
		'id'   => $prefix.'positions',
		'name' => __( 'Positions', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'positions',array('top','right','bottom','left'),array('px','%'), $std, '', ''),
		'tab' => __( 'Position', 'tg-text-domain' ),
		'required' => array(
			array($prefix.'position', '==', 'absolute')
		),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-editor-expand"></i>'
	);
}

function css_positions2($prefix, $std = '') {
	return array(
		'id'   => $prefix.'positions',
		'name' => __( 'Positions', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'positions',array('top','right','bottom','left'),array('px','%'), $std, '', ''),
		'tab' => __( 'Position', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-editor-expand"></i>'
	);
}

function css_width($prefix, $std = '') {
	return array(
		'id'   => $prefix.'width',
		'name' => __( 'Width', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'width',array('width'),array('px','%'), $std, 0, ''),
		'tab' => __( 'Position', 'tg-text-domain' ),
		'required' => array(
			array($prefix.'display', '!==', 'inline')
		)
	);
}

function css_height($prefix, $std = '') {
	return array(
		'id'   => $prefix.'height',
		'name' => __( 'Height', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'height',array('height'),array('px','%'), $std, 0, ''),
		'tab' => __( 'Position', 'tg-text-domain' ),
		'required' => array(
			array($prefix.'display', '!==', 'inline')
		)
	);
}

function css_margin($prefix, $std = '') {
	return array(
		'id'   => $prefix.'margin',
		'name' => __( 'Margin', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'margin',array('margin-top','margin-right','margin-bottom','margin-left'),array('px','%'), $std),
		'tab' => __( 'Position', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-editor-expand"></i>'
	);
}

function css_padding($prefix, $std = '') {
	return array(
		'id'   => $prefix.'padding',
		'name' => __( 'Paddings', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'padding',array('padding-top','padding-right','padding-bottom','padding-left'),array('px','%'), $std, 0, ''),
		'tab' => __( 'Position', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-editor-expand"></i>'
	);
}
function css_font_size($prefix, $std = 13) {
	return array(
		'id'   => $prefix.'font-size',
		'name' => __( 'Font Size', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'font-size',array('font-size'),array('px','em'), $std, 0, 120),
		'tab' => __( 'Font', 'tg-text-domain' )
	);
}

function css_line_height($prefix, $std = 16) {
	return array(
		'id'   => $prefix.'line-height',
		'name' => __( 'Line Height', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'line-height',array('line-height'),array('px','em'), $std, 0, 150),
		'tab' => __( 'Font', 'tg-text-domain' )
	);
}

function css_color($prefix, $std = '#444444') {
	return array(
		'id' => $prefix . 'color',
		'name' => __('Font Color', 'tg-text-domain'),
		'desc' => '',
		'sub_desc' => '',
		'type' => 'color',
		'rgba' => true,
		'std' => '#444444',
		'tab' => __( 'Font', 'tg-text-domain' )
	);
}

function css_font_weight($prefix, $std = 400) {
	return array(
		'id'   => $prefix.'font-weight',
		'name' => __( 'Font Weight', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 120,
		'options' => array(
			'100' => __( '100', 'tg-text-domain' ),
			'200' => __( '200', 'tg-text-domain' ),
			'300' => __( '300', 'tg-text-domain' ),
			'400' => __( '400', 'tg-text-domain' ),
			'500' => __( '500', 'tg-text-domain' ),
			'600' => __( '600', 'tg-text-domain' ),
			'700' => __( '700', 'tg-text-domain' ),
			'800' => __( '800', 'tg-text-domain' ),
			'900' => __( '900', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Font', 'tg-text-domain' )
	);
}

function css_font_subset($prefix, $std = '') {
	return array(
		'id'   => $prefix.'font-subset',
		'name' => __( 'Font Subset', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 120,
		'options' => array(
			'serif' => __( 'Serif', 'tg-text-domain' ),
			'sans-serif' => __( 'Sans-Serif', 'tg-text-domain' ),
			'monospace' => __( 'Monospace', 'tg-text-domain' ), 
		),
		'std' => $std,
		'tab' => __( 'Font', 'tg-text-domain' )
	);
}

function css_font_style($prefix, $std = '') {
	return array(
		'id'   => $prefix.'font-style',
		'name' => __( 'Font Style', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 160,
		'placeholder' => __( 'Select a value', 'tg-text-domain' ),
		'clear' => true,
		'options' => array(
			'normal'  => __( 'Normal', 'tg-text-domain' ),
			'inherit' => __( 'Inherit', 'tg-text-domain' ),
			'initial' => __( 'initial', 'tg-text-domain' ),
			'italic'  => __( 'italic', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Font', 'tg-text-domain' )
	);
}



function css_font_family($prefix, $std = '') {
	
	// Google Font array
	include TG_PLUGIN_PATH . '/includes/google-fonts.php';
	
	$fonts = array("google-font-disabled" => "Google Fonts");
	
	if (isset($googlefonts) && is_array($googlefonts)) {
		foreach($googlefonts as $font => $params) {
			$fonts[$font] = $font;
		}
	} else {
		$googlefonts = array();
	}
	
	$native_fonts = array(
		"serif-disabled" => "Serif Fonts",
		"Georgia, serif" => "Georgia, serif",
		"'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "Palatino Linotype",
		"'Times New Roman', Times, serif" => "Times New Roman",
		
		"sans-serif-disabled" => "Sans-Serif Fonts",
		"Arial, Helvetica, sans-serif" => "Arial, Helvetica",
		"'Arial Black', Gadget, sans-serif" => "Arial Black",
		"'Comic Sans MS', cursive, sans-serif" => "Comic Sans MS",
		"Impact, Charcoal, sans-serif" => "Impact",
		"'Lucida Sans Unicode', 'Lucida Grande', sans-serif" => "Lucida Sans Unicode",
		'Tahoma, Geneva, sans-serif' => 'Tahoma',
		"'Trebuchet MS', Helvetica, sans-serif" => "Trebuchet MS",
		"Verdana, Geneva, sans-serif" => "Verdana",
		
		"monospace-disabled" => "Monospace Fonts",
		"'Courier New', Courier, monospace" => "Courier New",
		"'Lucida Console', Monaco, monospace" => "Lucida Console",
	);
	
	$fonts = array_merge($native_fonts, $fonts);
	
	echo '<script type="text/javascript">var tg_skin_fonts = '.json_encode($googlefonts).';</script>';
	
	return array(
		'id'   => $prefix.'font-family',
		'name' => __( 'Font Family', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 160,
		'placeholder' => __( 'Select a value', 'tg-text-domain' ),
		'clear' => true,
		'options' => $fonts,
		'std' => $std,
		'tab' => __( 'Font', 'tg-text-domain' )
	);
	
}

function css_letter_spacing($prefix, $std = '') {
	return array(
		'id'   => $prefix.'letter-spacing',
		'name' => __( 'Letter Spacing', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'letter-spacing',array('letter-spacing'),array('px','em'), $std, -50, 50, 0.1),
		'std' => $std,
		'tab' => __( 'Font', 'tg-text-domain' )
	);
}

function css_word_spacing($prefix, $std = '') {
	return array(
		'id'   => $prefix.'word-spacing',
		'name' => __( 'Word Spacing', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'word-spacing',array('word-spacing'),array('px','em'), $std, -50, 50, 0.1),
		'std' => $std,
		'tab' => __( 'Font', 'tg-text-domain' )
	);
}

function css_text_decoration($prefix, $std = '') {
	return array(
		'id'   => $prefix.'text-decoration',
		'name' => __( 'Text Decoration', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 160,
		'placeholder' => __( 'Select a value', 'tg-text-domain' ),
		'clear' => true,
		'options' => array(
			'none'         => __( 'None', 'tg-text-domain' ),
			'underline'    => __( 'Underline', 'tg-text-domain' ),
			'overline'     => __( 'Overline', 'tg-text-domain' ),
			'line-through' => __( 'Line Through', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Font', 'tg-text-domain' )
	);
}

function css_text_transform($prefix, $std = '') {
	return array(
		'id'   => $prefix.'text-transform',
		'name' => __( 'Text Transform', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 160,
		'placeholder' => __( 'Select a value', 'tg-text-domain' ),
		'clear' => true,
		'options' => array(
			'none'       => __( 'None', 'tg-text-domain' ),
			'capitalize' => __( 'Capitalize', 'tg-text-domain' ),
			'uppercase'  => __( 'Uppercase', 'tg-text-domain' ),
			'lowercase'  => __( 'Lowercase', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Font', 'tg-text-domain' )
	);
}

function css_text_align($prefix, $std = '') {
	return array(
		'id'   => $prefix.'text-align',
		'name' => __( 'Text Alignment', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 160,
		'placeholder' => __( 'Select a value', 'tg-text-domain' ),
		'clear' => true,
		'options' => array(
			'none'    => __( 'None', 'tg-text-domain' ),
			'center'  => __( 'Center', 'tg-text-domain' ),
			'left'    => __( 'Left', 'tg-text-domain' ),
			'right'   => __( 'Right', 'tg-text-domain' ),
			'justify' => __( 'Justify', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Font', 'tg-text-domain' )
	);
}

function css_content_align($prefix, $std = '') {
	return array(
		'id'   => $prefix.'text-align',
		'name' => __( 'Content Align', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 160,
		'placeholder' => __( 'Select a value', 'tg-text-domain' ),
		'clear' => true,
		'options' => array(
			'center'  => __( 'Center', 'tg-text-domain' ),
			'left'    => __( 'Left', 'tg-text-domain' ),
			'right'   => __( 'Right', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Position', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-editor-expand"></i>'
	);
}

function css_text_shadow($prefix, $std = '') {
	return array(
		'id'   => $prefix.'text-shadow',
		'name' => __( 'Text Shadow', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'text-shadow',array('text-shadow-horizontal','text-shadow-vertical','text-shadow-blur'),array('px','em'), $std),
		'tab' => __( 'Font', 'tg-text-domain' )
	);
}

function css_text_shadow_color($prefix, $std = 'rgba(0,0,0,0.8)') {
	return array(
		'id' => $prefix . 'text-shadow-color',
		'name' => __('Text Shadow Color', 'tg-text-domain'),
		'desc' => '',
		'sub_desc' => '',
		'type' => 'color',
		'rgba' => true,
		'std' => $std,
		'tab' => __( 'Font', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-editor-paragraph"></i>'
	);
}

function css_border($prefix, $std = '') {
	return array(
		'id'   => $prefix.'border',
		'name' => __( 'Border Width', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'border',array('border-top','border-right','border-bottom','border-left'),array('px','em'), $std, 0, ''),
		'tab' => __( 'Border', 'tg-text-domain' )
	);
}

function css_border_radius($prefix, $std = '') {
	return array(
		'id'   => $prefix.'border-radius',
		'name' => __( 'Border Radius', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'border-radius',array('border-top-left-radius','border-top-right-radius','border-bottom-right-radius','border-bottom-left-radius'),array('px','em', '%'), $std, 0, ''),
		'tab' => __( 'Border', 'tg-text-domain' )
	);
}

function css_border_style($prefix, $std = '') {
	return array(
		'id'   => $prefix.'border-style',
		'name' => __( 'Border Style', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 160,
		'placeholder' => __( 'Select a value', 'tg-text-domain' ),
		'clear' => true,
		'options' => array(
			'none'   => __( 'None', 'tg-text-domain' ),
			'solid'  => __( 'Solid', 'tg-text-domain' ),
			'dotted' => __( 'Dotted', 'tg-text-domain' ),
			'dashed' => __( 'Dashed', 'tg-text-domain' ),
			'double' => __( 'Double', 'tg-text-domain' ),
			'groove' => __( 'Groove', 'tg-text-domain' ),
			'ridge'  => __( 'Ridge', 'tg-text-domain' ),
			'inset'  => __( 'inset', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Border', 'tg-text-domain' )
	);
}

function css_border_color($prefix, $std = '') {
	return array(
		'id' => $prefix . 'border-color',
		'name' => __('Border Color', 'tg-text-domain'),
		'desc' => '',
		'sub_desc' => '',
		'type' => 'color',
		'rgba' => true,
		'std' => $std,
		'tab' => __( 'Border', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-align-center"></i>'
	);
}

function css_shadow($prefix, $std = '') {
	return array(
		'id'   => $prefix.'box-shadow',
		'name' => __( 'Shadow', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'box-shadow',array('box-shadow-horizontal','box-shadow-vertical','box-shadow-blur','box-shadow-size'),array('px','rem'), $std),
		'tab' => __( 'Shadow', 'tg-text-domain' )
	);
}

function css_shadow_color($prefix, $std = '') {
	return array(
		'id' => $prefix . 'box-shadow-color',
		'name' => __('Shadow Color', 'tg-text-domain'),
		'desc' => '',
		'sub_desc' => '',
		'type' => 'color',
		'rgba' => true,
		'std' => $std,
		'tab' => __( 'Shadow', 'tg-text-domain' )
	);
}

function css_shadow_inset($prefix, $std = '') {
	return array(
		'id'   => $prefix.'box-shadow-inset',
		'name' => __( 'Inset Shadow', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'box-shadow-inset',array('box-shadow-inset-horizontal','box-shadow-inset-vertical','box-shadow-inset-blur','box-shadow-inset-size'),array('px','rem'), $std),
		'tab' => __( 'Shadow', 'tg-text-domain' )
	);
}

function css_shadow_inset_color($prefix, $std = '') {
	return array(
		'id' => $prefix . 'box-shadow-inset-color',
		'name' => __('Inset Color', 'tg-text-domain'),
		'desc' => '',
		'sub_desc' => '',
		'type' => 'color',
		'rgba' => true,
		'std' => $std,
		'tab' => __( 'Shadow', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-admin-page"></i>'
	);
}

function css_background_color($prefix, $std = '') {
	return array(
		'id' => $prefix . 'background-color',
		'name' => __('Background Color', 'tg-text-domain'),
		'desc' => '',
		'sub_desc' => '',
		'type' => 'color',
		'rgba' => true,
		'std' => $std,
		'tab' => __( 'Background', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-format-image"></i>'
	);
}

function css_background_image($prefix, $std = '') {
	return array(
		'id' => $prefix . 'background-image',
		'name' => __('Background Image', 'tg-text-domain'),
		'desc' => '',
		'sub_desc' => '',
		'type' => 'image',
		'frame_title' => __( 'Select a background image', 'tg-text-domain' ),
		'frame_button' => __( 'Add background image', 'tg-text-domain' ),
		'button_upload' => __( 'Add image', 'tg-text-domain' ),
		'button_remove' => __( 'Remove', 'tg-text-domain' ),
		'std' => $std,
		'tab' => __( 'Background', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-format-image"></i>'
	);
}

function css_background_size($prefix, $std = '') {
	return array(
		'id'   => $prefix.'background-size',
		'name' => __( 'Background Size', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 121,
		'placeholder' => __( 'Select size', 'tg-text-domain' ),
		'clear' => true,
		'options' => array(
			'initial' => __( 'Initial', 'tg-text-domain' ),
			'contain' => __( 'Contain', 'tg-text-domain' ),
			'cover'   => __( 'Cover', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Background', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-align-center"></i>'
	);
}

function css_background_repeat($prefix, $std = '') {
	return array(
		'id'   => $prefix.'background-repeat',
		'name' => __( 'Background Repeat', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 121,
		'placeholder' => __( 'Select repeat', 'tg-text-domain' ),
		'clear' => true,
		'options' => array(
			'no-repeat' => __( 'No Repeat', 'tg-text-domain' ),
			'repeat'    => __( 'Repeat', 'tg-text-domain' ),
			'repeat-x'  => __( 'Repeat X', 'tg-text-domain' ),
			'repeat-Y'  => __( 'Repeat Y', 'tg-text-domain' ),
			'round'     => __( 'Round', 'tg-text-domain' ),
			'space'     => __( 'Space', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Background', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-align-center"></i>'
	);
}

function css_background_position_x($prefix, $std = '') {
	return array(
		'id'   => $prefix.'background-position-x',
		'name' => __( 'Background Position X', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  $std,
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'background-position-x',array('background-position-x'),array('px','%'), $std, '', ''),
		'tab' => __( 'Background', 'tg-text-domain' )
	);
}

function css_background_position_y($prefix, $std = '') {
	return array(
		'id'   => $prefix.'background-position-y',
		'name' => __( 'Background Position Y', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'custom',
		'options' => tg_css_multiple_input($prefix,'background-position-y',array('background-position-y'),array('px','%'), $std, '', ''),
		'tab' => __( 'Background', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-format-image"></i>'
	);
}

function css_visibility($prefix, $std = '') {
	return array(
		'id'   => $prefix.'visibility',
		'name' => __( 'Visibility', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'select',
		'width' => 137,
		'placeholder' => __( 'Select a value', 'tg-text-domain' ),
		'clear' => true,
		'options' => array(
			'visible'  => __( 'Visible', 'tg-text-domain' ),
			'hidden'   => __( 'Hidden', 'tg-text-domain' )
		),
		'std' => $std,
		'tab' => __( 'Visibility', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-visibility"></i>'
	);
}

function css_opacity($prefix, $std = 1) {
	return array(
		'id'   => $prefix.'opacity',
		'name' => __( 'Opacity', 'tg-text-domain' ),
		'desc' => '',
		'sub_desc' =>  '',
		'type' => 'slider',
		'label' => '',
		'min' => 0,
		'max' => 1,
		'step' => 0.01,
		'sign' => '',
		'std' => $std,
		'tab' => __( 'Visibility', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-visibility"></i>'
	);
}

function css_visibility_desc($prefix) {
	return array(
		'id'   => $prefix.'visibility_desc',
		'name' => '',
		'options' => '<br><p>'.__( 'Visibility and opacity values will not be applied directly on the element otherwise you will not be able to see it or select it anymore.', 'tg-text-domain' ).'</p>',
		'sub_desc' =>  '',
		'type' => 'custom',
		'tab' => __( 'Visibility', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-visibility"></i>'
	);
}

function css_custom($prefix, $std = '') {
	return array(
		'id'   => $prefix.'custom-rules',
		'name' => __( 'Custom rules', 'tg-text-domain' ),
		'options' => '',
		'sub_desc' =>  '',
		'type' => 'textarea',
		'cols' => 80,
		'rows' => 14,
		'std'  => $std,
		'tab' => __( 'Custom', 'tg-text-domain' )
	);
}

function css_custom_desc($prefix) {
	return array(
		'id'   => $prefix.'custom_desc',
		'name' => '',
		'options' => __( 'In this section, you can add your custom css rules if you need to extend the basis rules available in other panels.', 'tg-text-domain' ).'<br><strong>* '.__( 'e.g.: margin-left: auto; margin-right: auto; width: 150px;', 'tg-text-domain' ).'</strong>',
		'sub_desc' =>  '',
		'type' => 'custom',
		'tab' => __( 'Custom', 'tg-text-domain' ),
		'tab_icon' => '<i class="tomb-icon dashicons dashicons-admin-appearance"></i>'
	);
}

	
// build item styles idle state
$idle_pre = 'element_idle_';
$element_idle = array(
		'id'    => 'element_styles_idle',
		'title' => '',
		'icon' => '',
		'color' => '#f1f1f1',
		'background' => '#e74c3c',
		'pages' => array('the_grid'),
		'type' => 'page2',
		'fields' => array(
			css_position($idle_pre),
			css_display($idle_pre),
			css_overflow($idle_pre),
			css_float($idle_pre),
			css_clear($idle_pre),
			css_zindex($idle_pre),
			css_positions($idle_pre),
			css_width($idle_pre),
			css_height($idle_pre),
			css_margin($idle_pre),
			css_padding($idle_pre),
			css_visibility($idle_pre),
			css_opacity($idle_pre),
			css_visibility_desc($idle_pre),
			css_font_size($idle_pre),
			css_line_height($idle_pre),
			css_color($idle_pre),
			css_font_weight($idle_pre),
			css_font_subset($idle_pre),
			css_font_family($idle_pre),
			css_font_style($idle_pre),
			css_text_decoration($idle_pre),
			css_text_transform($idle_pre),
			css_text_align($idle_pre),
			css_letter_spacing($idle_pre),
			css_word_spacing($idle_pre),
			css_text_shadow($idle_pre),
			css_text_shadow_color($idle_pre),
			css_border($idle_pre),
			css_border_radius($idle_pre),
			css_border_style($idle_pre),
			css_border_color($idle_pre),
			css_shadow($idle_pre),
			css_shadow_color($idle_pre),
			css_shadow_inset($idle_pre),
			css_shadow_inset_color($idle_pre),
			css_background_color($idle_pre),
			css_background_image($idle_pre),
			css_background_size($idle_pre),
			css_background_repeat($idle_pre),
			css_background_position_x($idle_pre),
			css_background_position_y($idle_pre),
			css_custom($idle_pre),
			css_custom_desc($idle_pre)
	),
);

// build item styles hover state
$hover_pre = 'element_hover_';
$element_hover = array(
		'id'    => 'element_styles_hover',
		'title' => '',
		'icon' => '',
		'color' => '#f1f1f1',
		'background' => '#e74c3c',
		'pages' => array('the_grid'),
		'type' => 'page2',
		'fields' => array(	
			css_visibility($hover_pre),
			css_opacity($hover_pre),
			css_visibility_desc($hover_pre),
			css_font_size($hover_pre),
			css_line_height($hover_pre),
			css_color($hover_pre),
			css_font_style($hover_pre),
			css_text_transform($hover_pre),
			css_text_align($hover_pre),
			css_letter_spacing($hover_pre),
			css_word_spacing($hover_pre),
			css_text_decoration($hover_pre),
			css_text_shadow($hover_pre),
			css_text_shadow_color($hover_pre),
			css_border($hover_pre),
			css_border_radius($hover_pre),
			css_border_style($hover_pre),
			css_border_color($hover_pre),
			css_shadow($hover_pre),
			css_shadow_color($hover_pre),
			css_shadow_inset($hover_pre),
			css_shadow_inset_color($hover_pre),
			css_background_color($hover_pre),
			css_background_image($hover_pre),
			css_background_size($hover_pre),
			css_background_repeat($hover_pre),
			css_background_position_x($hover_pre),
			css_background_position_y($hover_pre),
			css_custom($hover_pre),
			css_custom_desc($hover_pre)	
	),
);

// build element source
$element_source = array(
	'id'    => 'element_source',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(	
		array(
			'id'   => 'class_name',
			'name' => __( 'Class Name', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'text',
			'size' => 160,
			'options' => '',
			'std' => ''
		),
		array(
			'id'   => 'html_tag',
			'name' => __( 'HTML Tag', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type'  => 'select',
			'width' => 160,
			'placeholder' => 'DIV',
			'options'  => array(
				''     => __( 'Auto', 'tg-text-domain' ),
				'div'  => 'DIV',
				'span' => 'SPAN',
				'p'    => 'P',
				'h2'   => 'H2',
				'h3'   => 'H3',
				'h4'   => 'H4',
				'h5'   => 'H5',
				'h6'   => 'H6'
			),
			'std'   => ''
			/*'required' => array(
				array('type', '==', '')
			)*/
		),
		array(
			'id'   => 'source_type',
			'name' => __( 'Source', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'select',
			'width' => 160,
			'options' => array(
				'post' => __( 'Post Data', 'tg-text-domain' ),
				'woocommerce' => __( 'Woocommerce', 'tg-text-domain' ),
				'video_stream' => __( 'Youtube/Vimeo', 'tg-text-domain' ),
				'media_button' => __( 'Lightbox/Play button', 'tg-text-domain' ),
				'social_link'  => __( 'Social Link', 'tg-text-domain' ),
				'icon' => __( 'Icon', 'tg-text-domain' ),
				'html' => __( 'Text/HTML', 'tg-text-domain' ),
				'line_break' => __( 'Line Break', 'tg-text-domain' )
			),
			'std' => 'post'
		),
		array(
			'id'   => 'social_link_type',
			'name' => __( 'Social Type', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'select',
			'width' => 160,
			'options' => array(
				'facebook'    => __( 'Facebook', 'tg-text-domain' ),
				'twitter'     => __( 'Twitter', 'tg-text-domain' ),
				'google-plus' => __( 'Google+', 'tg-text-domain' ),
				'pinterest'   => __( 'Pinterest', 'tg-text-domain' ),
				'linkedin'    => __( 'Linkedin', 'tg-text-domain' ),
				'tumblr'      => __( 'Tumblr', 'tg-text-domain' ),
				'digg'        => __( 'Digg', 'tg-text-domain' ),
				'reddit'      => __( 'Reddit', 'tg-text-domain' )
			),
			'std' => 'facebook',
			'required' => array(
				array('source_type', '==', 'social_link')
			)
		),
		array(
			'id'   => 'post_content',
			'name' => __( 'Content', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'select',
			'width' => 160,
			'options' => array(
				'get_the_title'           => __( 'Title', 'tg-text-domain' ),
				'get_the_excerpt'         => __( 'Excerpt', 'tg-text-domain' ),
				'get_the_date'            => __( 'Date', 'tg-text-domain' ),
				'get_the_terms'           => __( 'Terms list', 'tg-text-domain' ),
				'get_the_author'          => __( 'Author Name', 'tg-text-domain' ),
				'get_the_author_avatar'   => __( 'Author Avatar', 'tg-text-domain' ),
				'get_the_comments_number' => __( 'Nb of comment', 'tg-text-domain' ),
				'get_the_likes_number'    => __( 'Nb of like', 'tg-text-domain' ),
				'get_the_meta_data'       => __( 'Metadata', 'tg-text-domain' ),
			),
			'std' => 'get_the_title',
			'required' => array(
				array('source_type', '==', 'post')
			)
		),
		array(
			'id'   => 'video_stream_content',
			'name' => __( 'Content', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'select',
			'width' => 160,
			'options' => array(
				'get_the_views_number' => __( 'View Number', 'tg-text-domain' ),
				'get_the_duration'     => __( 'Video Duration', 'tg-text-domain' )
			),
			'std' => 'get_the_views_number',
			'required' => array(
				array('source_type', '==', 'video_stream')
			)
		),
		array(
			'id'   => 'woocommerce_content',
			'name' => __( 'Content', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'select',
			'width' => 160,
			'options' => array(
				'get_product_price'           => __( 'Price', 'tg-text-domain' ),
				'get_product_full_price'      => __( 'Full Price', 'tg-text-domain' ),
				'get_product_regular_price'   => __( 'Regular Price', 'tg-text-domain' ),
				'get_product_sale_price'      => __( 'Sale Price', 'tg-text-domain' ),
				'get_product_rating'          => __( 'Star Rating', 'tg-text-domain' ),
				'get_product_text_rating'     => __( 'Text Rating', 'tg-text-domain' ),
				'get_product_on_sale'         => __( 'On Sale', 'tg-text-domain' ),
				'get_product_cart_button'     => __( 'Cart Button', 'tg-text-domain' ),
				'get_product_add_to_cart_url' => __( 'Add to Cart URL', 'tg-text-domain' )	
			),
			'std' => 'title',
			'required' => array(
				array('source_type', '==', 'woocommerce')
			)
		),
		array(
			'id'   => 'html_content',
			'name' => __( 'HTML Content', 'tg-text-domain' ),
			'options' => '',
			'sub_desc' =>  '',
			'type' => 'textarea',
			'cols' => 80,
			'rows' => 12,
			'required' => array(
				array('source_type', '==', 'html')
			)
		),
		// excerpt args
		array(
			'id'   => 'excerpt_length',
			'name' => __( 'Excerpt Length', 'tg-text-domain' ),
			'options' => '',
			'sub_desc' =>  '',
			'type' => 'number',
			'step' => 1,
			'min'  => -1,
			'max'  => 999,
			'sign' => '',
			'std'  => 240,
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_excerpt')
			)
		),
		array(
			'id'   => 'excerpt_suffix',
			'name' => __( 'Excerpt Suffix', 'tg-text-domain' ),
			'options' => '',
			'sub_desc' =>  '',
			'type' => 'text',
			'size' => 49,
			'std'  => '...',
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_excerpt')
			)
		),
		array(
			'id'   => 'excerpt_desc',
			'name' => '',
			'options' => '',
			'sub_desc' =>  '<br><strong>*</strong> '.__( 'TIP: -1 full excerpt and 0 (240 characters)', 'tg-text-domain' ) .'<br>'.__( 'The excerpt length will also depend of your theme. It can be limited to a certain number of characters.', 'tg-text-domain' ),
			'type' => 'custom',
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_excerpt')
			)
		),
		// date args
		array(
			'id'   => 'date_format',
			'name' => __( 'Date Format', 'tg-text-domain' ),
			'options' => '',
			'sub_desc' =>  __( 'Keep this field empty if you want to use the date format set in your Wordpress settings', 'tg-text-domain' ).'<br><strong>('.
			__( 'e.g.: F j, Y', 'tg-text-domain' ).' - <a href="http://php.net/manual/en/function.date.php">'.__( 'PHP date format', 'tg-text-domain' ).'</a></strong>)'.
			'<br><strong>('.__( "TIP: 'ago' displays human readable format such as '1 hour ago'", 'tg-text-domain' ).'</strong>)',
			'type' => 'textarea',
			'cols' => 80,
			'rows' => 4,
			'std'  => '',
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_date')
			)
		),
		// author args
		array(
			'id'   => 'author_prefix',
			'name' => __( 'Author Prefix', 'tg-text-domain' ),
			'options' => '',
			'sub_desc' =>  '',
			'type' => 'text',
			'size' => 60,
			'std'  => 'by',
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_author')
			)
		),
		// terms args
		array(
			'id'   => 'taxonomy',
			'name' => __( 'Taxonomy', 'tg-text-domain' ),
			'sub_desc' =>  __( 'Leave this field empty if you want to display all taxonomies terms. (e.g: category, post_tag)', 'tg-text-domain' ),
			'options' => '',
			'type' => 'text',
			'size' => 160,
			'std'  => '',
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_terms')
			)
		),
		array(
			'id'   => 'terms_link',
			'name' => __( 'Terms link', 'tg-text-domain' ),
			'options' => '',
			'sub_desc' =>  __( 'You need to deactivate terms link in order to use action.', 'tg-text-domain' ),
			'type' => 'checkbox',
			'std'  => 1,
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_terms')
			)
		),
		array(
			'id'   => 'terms_color',
			'name' => __( 'Terms Color', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type'  => 'select',
			'width' => 160,
			'options' => array(
				''           => __( 'None', 'tg-text-domain' ),
				'color'      => __( 'Text Color', 'tg-text-domain' ),
				'background' => __( 'Background Color', 'tg-text-domain' ),
			),
			'std'   => '',
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_terms')
			)
		),
		array(
			'id'   => 'terms_separator',
			'name' => __( 'Terms separator', 'tg-text-domain' ),
			'options' => '',
			'sub_desc' =>  '',
			'type' => 'text',
			'size' => 60,
			'std'  => ',',
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_terms')
			)
		),
		array(
			'id'   => 'terms_padding',
			'name' => __( 'Terms padding', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'custom',
			'options' => tg_css_multiple_input('terms_','padding',array('padding-top','padding-right','padding-bottom','padding-left'),array('px','em','%'), '', 0, ''),
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_terms')
			)
		),
		// metadate arg
		array(
			'id'   => 'metadata_key',
			'name' => __( 'Metadata Key', 'tg-text-domain' ),
			'options' => '',
			'sub_desc' =>  '',
			'type' => 'text',
			'size' => 159,
			'std'  => '',
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_meta_data')
			)
		),
		// comment icon
		array(
			'id'   => 'comment_icon',
			'name' => __( 'Comment Icon', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'custom',
			'options' => '<div class="tg-icon-field"><div class="tg-icon-holder"><i></i><input name="comment_icon" type="hidden"></div></div>',
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_comments_number')
			)
		),
		array(
			'id' => 'comment_icon_color',
			'name' => __('Icon Color', 'tg-text-domain'),
			'desc' => '',
			'sub_desc' => '',
			'type' => 'color',
			'rgba' => true,
			'std' => '',
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_comments_number'),
				array('comment_icon', '!=' , '')
			)
		),
		array(
			'id'   => 'comment_icon_font-size',
			'name' => __( 'Icon Size', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'custom',
			'options' => tg_css_multiple_input('comment_icon_','font-size',array('font-size'),array('px','em'), 16, 0, 120),
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_comments_number'),
				array('comment_icon', '!=', '')
			)
		),
		array(
			'id'   => 'comment_icon_float',
			'name' => __( 'Icon Float', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'select',
			'width' => 121,
			'placeholder' => __( 'Left', 'tg-text-domain' ),
			'options' => array(
				'left'  => __( 'Left', 'tg-text-domain' ),
				'right' => __( 'right', 'tg-text-domain' )
			),
			'std' => 'left',
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_comments_number'),
				array('comment_icon', '!=', '')
			)
		),
		array(
			'id'   => 'comment_icon_positions',
			'name' => __( 'Icon Positions', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'custom',
			'options' => tg_css_multiple_input('comment_icon_','margin',array('margin-top','margin-right','margin-bottom','margin-left'),array('px','em','%'), '', '', ''),
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_comments_number'),
				array('comment_icon', '!=', '')
			)
		),
		// like heart icon
		array(
			'id' => 'like_icon_color',
			'name' => __('Icon Color', 'tg-text-domain'),
			'desc' => '',
			'sub_desc' => '',
			'type' => 'color',
			'rgba' => true,
			'std' => '',
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_likes_number')
			)
		),
		array(
			'id'   => 'like_icon_font-size',
			'name' => __( 'Icon Size', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'custom',
			'options' => tg_css_multiple_input('like_icon_','font-size',array('font-size'),array('px','em'), 16, 0, 120),
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_likes_number')
			)
		),
		array(
			'id'   => 'like_icon_float',
			'name' => __( 'Icon Float', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'select',
			'width' => 121,
			'placeholder' => __( 'Left', 'tg-text-domain' ),
			'options' => array(
				'left'  => __( 'Left', 'tg-text-domain' ),
				'right' => __( 'right', 'tg-text-domain' )
			),
			'std' => 'left',
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_likes_number')
			)
		),
		array(
			'id'   => 'like_icon_positions',
			'name' => __( 'Icon Positions', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'custom',
			'options' => tg_css_multiple_input('like_icon_','margin',array('margin-top','margin-right','margin-bottom','margin-left'),array('px','em','%'), '', '', ''),
			'required' => array(
				array('source_type', '==', 'post'),
				array('post_content', '==', 'get_the_likes_number')
			)
		),
		
		// lightbox content
		array(
			'id'   => 'lightbox_content_type',
			'name' => __( 'Content type', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'select',
			'width' => 160,
			'placeholder' => __( 'icon', 'tg-text-domain' ),
			'options' => array(
				'icon' => __( 'Icon', 'tg-text-domain' ),
				'text' => __( 'Text', 'tg-text-domain' )
			),
			'std' => 'left',
			'required' => array(
				array('source_type', '==', 'media_button')
			)
		),
		array(
			'id'   => 'lightbox_image_icon',
			'name' => __( 'Image Icon', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'custom',
			'options' => '<div class="tg-icon-field"><div class="tg-icon-holder"><i></i><input name="lightbox_image_icon" type="hidden"></div></div>',
			'required' => array(
				array('source_type', '==', 'media_button'),
				array('lightbox_content_type', '!=', 'text')
			)
		),
		array(
			'id'   => 'lightbox_image_text',
			'name' => __( 'Image Text', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'text',
			'size' => 158,
			'options' => __( 'Open Image', 'tg-text-domain'  ),
			'required' => array(
				array('source_type', '==', 'media_button'),
				array('lightbox_content_type', '==', 'text')
			)
		),
		
		array(
			'id'   => 'lightbox_audio_icon',
			'name' => __( 'Audio Icon', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'custom',
			'options' => '<div class="tg-icon-field"><div class="tg-icon-holder"><i></i><input name="lightbox_audio_icon" type="hidden"></div></div>',
			'required' => array(
				array('source_type', '==', 'media_button'),
				array('lightbox_content_type', '!=', 'text')
			)
		),
		array(
			'id'   => 'lightbox_audio_text',
			'name' => __( 'Audio Text', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'text',
			'size' => 158,
			'options' => __( 'Play Song', 'tg-text-domain'  ),
			'required' => array(
				array('source_type', '==', 'media_button'),
				array('lightbox_content_type', '==', 'text')
			)
		),
		array(
			'id'   => 'lightbox_video_icon',
			'name' => __( 'Video Icon', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'custom',
			'options' => '<div class="tg-icon-field"><div class="tg-icon-holder"><i></i><input name="lightbox_video_icon" type="hidden"></div></div>',
			'required' => array(
				array('source_type', '==', 'media_button'),
				array('lightbox_content_type', '!=', 'text')
			)
		),
		array(
			'id'   => 'lightbox_video_text',
			'name' => __( 'Video Text', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'text',
			'size' => 158,
			'options' => __( 'Play Video', 'tg-text-domain'  ),
			'required' => array(
				array('source_type', '==', 'media_button'),
				array('lightbox_content_type', '==', 'text')
			)
		),	
		array(
			'id'   => 'lightbox_pause_icon',
			'name' => __( 'Pause Icon', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'custom',
			'options' => '<div class="tg-icon-field"><div class="tg-icon-holder"><i></i><input name="lightbox_pause_icon" type="hidden"></div></div>',
			'required' => array(
				array('source_type', '==', 'media_button'),
				array('lightbox_content_type', '!=', 'text')
			)
		),
		array(
			'id'   => 'lightbox_pause_text',
			'name' => __( 'Pause Text', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'text',
			'size' => 158,
			'options' => __( 'Pause', 'tg-text-domain'  ),
			'required' => array(
				array('source_type', '==', 'media_button'),
				array('lightbox_content_type', '==', 'text')
			)
		),
		array(
			'id'   => 'element_icon',
			'name' => __( 'Element Icon', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'custom',
			'options' => '<div class="tg-icon-field"><div class="tg-icon-holder"><i></i><input name="element_icon" type="hidden"></div></div>',
			'required' => array(
				array('source_type', '==', 'icon')
			)
		),
		// woocommerce star rating
		array(
			'id' => 'woo_star_color_empty',
			'name' => __('Stars Color Empty', 'tg-text-domain'),
			'desc' => '',
			'sub_desc' => '',
			'type' => 'color',
			'rgba' => true,
			'std' => '',
			'required' => array(
				array('source_type', '==', 'woocommerce'),
				array('woocommerce_content', '==', 'get_product_rating')
			)
		),
		array(
			'id' => 'woo_star_color_fill',
			'name' => __('Stars Color Fill', 'tg-text-domain'),
			'desc' => '',
			'sub_desc' => '',
			'type' => 'color',
			'rgba' => true,
			'std' => '',
			'required' => array(
				array('source_type', '==', 'woocommerce'),
				array('woocommerce_content', '==', 'get_product_rating')
			)
		),
		array(
			'id'   => 'woo_star_font-size',
			'name' => __( 'Stars Size', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'custom',
			'options' => tg_css_multiple_input('woo_star_','font-size',array('font-size'),array('px','em'), 16, 0, 120),
			'required' => array(
				array('source_type', '==', 'woocommerce'),
				array('woocommerce_content', '==', 'get_product_rating')
			)
		),
		array(
			'id'   => 'add_to_cart_url_text',
			'name' => __( 'URL Text', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'text',
			'size' => 158,
			'options' => __( 'Add to Cart', 'tg-text-domain'  ),
			'required' => array(
				array('source_type', '==', 'woocommerce'),
				array('woocommerce_content', '==', 'get_product_add_to_cart_url'),
			)
		),
		// woocomerce cart icons
		array(
			'id'   => 'woo_cart_icon',
			'name' => __( 'Cart Icon', 'tg-text-domain' ),
			'options' => '',
			'sub_desc' =>  '',
			'type' => 'checkbox',
			'std'  => '',
			'required' => array(
				array('source_type', '==', 'woocommerce'),
				array('woocommerce_content', '==', 'get_product_cart_button')
			)
		),
		array(
			'id'   => 'woo_cart_icon_simple',
			'name' => __( 'Simple Icon', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'custom',
			'options' => '<div class="tg-icon-field"><div class="tg-icon-holder"><i></i><input name="woo_cart_icon_simple" type="hidden"></div></div>',
			'required' => array(
				array('source_type', '==', 'woocommerce'),
				array('woocommerce_content', '==', 'get_product_cart_button'),
				array('woo_cart_icon', '==', 'true')
			)
		),
		array(
			'id'   => 'woo_cart_icon_variable',
			'name' => __( 'Variable Icon', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'custom',
			'options' => '<div class="tg-icon-field"><div class="tg-icon-holder"><i></i><input name="woo_cart_icon_variable" type="hidden"></div></div>',
			'required' => array(
				array('source_type', '==', 'woocommerce'),
				array('woocommerce_content', '==', 'get_product_cart_button'),
				array('woo_cart_icon', '==', 'true')
			)
		),
		array(
			'id'   => 'view_number_suffix',
			'name' => __( 'Suffix (view)', 'tg-text-domain' ),
			'options' => '',
			'sub_desc' =>  '',
			'type' => 'checkbox',
			'std'  => 0,
			'required' => array(
				array('source_type', '==', 'video_stream'),
				array('video_stream_content', '==', 'get_the_views_number')
			)
		),
	),
);

// build element action
$element_action = array(
	'id'    => 'element_action',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(	
		array(
			'id'   => 'type',
			'name' => __( 'Action', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'select',
			'width' => 160,
			'options' => array(
				''         => __( 'None', 'tg-text-domain' ),
				'link'     => __( 'Link to', 'tg-text-domain' ),
				'lightbox' => __( 'Lightbox/Play', 'tg-text-domain' )
			),
			'std' => ''
		),
		array(
			'id'   => 'link_target',
			'name' => __( 'Link Target', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'select',
			'width' => 160,
			'options' => array(
				'_self'   => __( '_self', 'tg-text-domain' ),
				'_blank'  => __( '_blank', 'tg-text-domain' )
			),
			'std' => '_self',
			'required' => array(
				array('type', '==', 'link')
			)
		),
		array(
			'id'   => 'link_url',
			'name' => __( 'Link to', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'select',
			'width' => 160,
			'options' => array(
				'post_url'          => __( 'Post Page', 'tg-text-domain' ),
				'author_posts_url'  => __( 'Author Posts Page', 'tg-text-domain' ),
				'custom_url'        => __( 'Custom url', 'tg-text-domain' ),
				'meta_data_url'     => __( 'Meta Data Key', 'tg-text-domain' )
			),
			'std' => 'post_url',
			'required' => array(
				array('type', '==', 'link')
			)
		),
		array(
			'id'   => 'custom_url',
			'name' => __( 'Custom url', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'text',
			'size' => 160,
			'options' => '',
			'std' => '',
			'required' => array(
				array('type', '==', 'link'),
				array('link_url', '==', 'custom_url')
			)
		),
		array(
			'id'   => 'meta_data_url',
			'name' => __( 'Meta Data Key', 'tg-text-domain' ),
			'desc' => '',
			'sub_desc' =>  '',
			'type' => 'text',
			'size' => 160,
			'options' => '',
			'std' => '',
			'required' => array(
				array('type', '==', 'link'),
				array('link_url', '==', 'meta_data_url')
			)
		)
	)
);

$item_layout = array(
	'id'    => 'item_layout',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		array(
			'id'   => 'skin_filter',
			'name' => __( 'Skin Category', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'text',
			'size' => 139,
			'std'  => '',
		),
		array(
			'id'   => 'skin_style',
			'name' => __( 'Skin style', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'select',
			'width' => 140,
			'options' => array(
				'masonry' => __( 'Masonry', 'tg-text-domain' ),
				'grid'    => __( 'Grid/Justified', 'tg-text-domain' )
			),
			'std' => 'masonry'
		),
		array(
			'id'   => 'content_position',
			'name' => __( 'Content position', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'select',
			'width' => 140,
			'options' => array(
				'none'   => __( 'None', 'tg-text-domain' ),
				'bottom' => __( 'Bottom', 'tg-text-domain' ),
				'top'    => __( 'Top', 'tg-text-domain' ),
				'both'   => __( 'Top & Bottom', 'tg-text-domain' )
			),
			'std' => 'bottom',
			'required' => array(
				array('skin_style', '==', 'masonry')
			)
		),
		array(
			'id'   => 'media_content',
			'name' => __( 'Media Content', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'checkbox',
			'std' => 1,
			'required' => array(
				array('skin_style', '==', 'masonry'),
				array('content_position', '!=', 'none')
			)
		),
		array(
			'id'   => 'overlay_type',
			'name' => __( 'Overlay Type', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'select',
			'width' => 140,
			'options' => array(
				''        => __( 'None', 'tg-text-domain' ),
				'full'    => __( 'Full size', 'tg-text-domain' ),
				'content' => __( 'Content Based', 'tg-text-domain' )
			),
			'std' => 'full'
		),
		array(
			'id'   => 'item_ratio',
			'name' => __( 'Aspect Ratio', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'custom',
			'options' => '<input type="number" style="width: 48px" class="tomb-text number mini" name="item_x_ratio" id="item_x_ratio" value="4" step="1" min="1" max="9999"> : <input type="number" style="width: 48px" class="tomb-text number mini" name="item_y_ratio" id="item_y_ratio" value="3" step="1" min="1" max="9999"> '.__( '(X:Y)', 'tg-text-domain'  ),
			'required' => array(
				array('skin_style', '==', 'grid')
			)
		),
		array(
			'id'   => 'skin_col',
			'name' => __( 'Column Number', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'slider',
			'std'  => 1,
			'step' => 1,
			'min'  => 1,
			'max'  => 3
		),
		array(
			'id'   => 'skin_row',
			'name' => __( 'Row Number', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'slider',
			'std'  => 1,
			'step' => 1,
			'min'  => 1,
			'max'  => 3,
			'required' => array(
				array('skin_style', '==', 'grid')
			)
		)
	)
);

$global_css = array(
	'id'    => 'global_custom_css',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		array(
			'id'   => 'global_css',
			'name' => __( 'Enter your custom css here:', 'tg-text-domain' ),
			'options' => '',
			'sub_desc' =>  '',
			'type' => 'textarea',
			'cols' => 80,
			'rows' => 14,
			'tab' => __( 'Custom', 'tg-text-domain' )
		),
		array(
			'id'   => 'global_css_desc',
			'name' => '',
			'options' => __( 'In this section, you can add any custom css.', 'tg-text-domain' ).'<br><ins>'.__( 'Skin slug', 'tg-text-domain' ).'</ins> : <strong><span class="tg-skin-slug"></span></strong><br><br><strong>* '.__( 'e.g:', 'tg-text-domain' ) .' .<span class="tg-skin-slug"></span> .tg-element-1 {<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;color: red;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;font-size: 14px;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}</strong>',
			'sub_desc' =>  '',
			'type' => 'custom',
			'tab' => __( 'Custom', 'tg-text-domain' ),
			'tab_icon' => '<i class="tomb-icon dashicons dashicons-admin-appearance"></i>'
		)
	)
);

$item_pre = 'item_idle_';
$item_idle = array(
	'id'    => 'item_idle',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		css_overflow($item_pre),
		css_padding($item_pre),
		css_border($item_pre),
		css_border_radius($item_pre),
		css_border_style($item_pre),
		css_border_color($item_pre),
		css_shadow($item_pre),
		css_shadow_color($item_pre),
		css_shadow_inset($item_pre),
		css_shadow_inset_color($item_pre),
		css_background_color($item_pre),
		css_background_image($item_pre),
		css_background_size($item_pre),
		css_background_repeat($item_pre),
		css_background_position_x($item_pre),
		css_background_position_y($item_pre),
		css_custom($item_pre),
		css_custom_desc($item_pre)	
	)
);

$item_pre = 'item_hover_';
$item_hover = array(
	'id'    => 'item_hover',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		css_border($item_pre),
		css_border_radius($item_pre),
		css_border_style($item_pre),
		css_border_color($item_pre),
		css_shadow($item_pre),
		css_shadow_color($item_pre),
		css_shadow_inset($item_pre),
		css_shadow_inset_color($item_pre),
		css_background_color($item_pre),
		css_background_image($item_pre),
		css_background_size($item_pre),
		css_background_repeat($item_pre),
		css_background_position_x($item_pre),
		css_background_position_y($item_pre),
		css_custom($item_pre),
		css_custom_desc($item_pre)	
	)
);

$media_holder_pre = 'media_holder_idle_';
$media_holder_idle = array(
	'id'    => 'media_holder_idle',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		css_overflow($media_holder_pre),
		css_margin($media_holder_pre),
		css_border($media_holder_pre),
		css_border_radius($media_holder_pre),
		css_border_style($media_holder_pre),
		css_border_color($media_holder_pre),
		css_shadow($media_holder_pre),
		css_shadow_color($media_holder_pre),
		css_shadow_inset($media_holder_pre),
		css_shadow_inset_color($media_holder_pre),
		css_custom($media_holder_pre),
		css_custom_desc($media_holder_pre)	
	)
);

$media_holder_pre = 'media_holder_hover_';
$media_holder_hover = array(
	'id'    => 'media_holder_hover',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		css_border($media_holder_pre),
		css_border_radius($media_holder_pre),
		css_border_style($media_holder_pre),
		css_border_color($media_holder_pre),
		css_shadow($media_holder_pre),
		css_shadow_color($media_holder_pre),
		css_shadow_inset($media_holder_pre),
		css_shadow_inset_color($media_holder_pre),
		css_custom($media_holder_pre),
		css_custom_desc($media_holder_pre)	
	)
);

$overlay_pre  = 'overlay_idle_';
$overlay_idle = array(
	'id'    => 'overlay_idle',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		css_positions2($overlay_pre),
		css_border($overlay_pre),
		css_border_radius($overlay_pre),
		css_border_style($overlay_pre),
		css_border_color($overlay_pre),
		css_shadow($overlay_pre),
		css_shadow_color($overlay_pre),
		css_shadow_inset($overlay_pre),
		css_shadow_inset_color($overlay_pre),
		css_background_color($overlay_pre),
		css_background_image($overlay_pre),
		css_background_size($overlay_pre),
		css_background_repeat($overlay_pre),
		css_background_position_x($overlay_pre),
		css_background_position_y($overlay_pre),
		css_custom($overlay_pre),
		css_custom_desc($overlay_pre)	
	)
);
$overlay_pre = 'overlay_hover_';
$overlay_hover = array(
	'id'    => 'overlay_hover',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		css_border($overlay_pre),
		css_border_radius($overlay_pre),
		css_border_style($overlay_pre),
		css_border_color($overlay_pre),
		css_shadow($overlay_pre),
		css_shadow_color($overlay_pre),
		css_shadow_inset($overlay_pre),
		css_shadow_inset_color($overlay_pre),
		css_background_color($overlay_pre),
		css_background_image($overlay_pre),
		css_background_size($overlay_pre),
		css_background_repeat($overlay_pre),
		css_background_position_x($overlay_pre),
		css_background_position_y($overlay_pre),
		css_custom($overlay_pre),
		css_custom_desc($overlay_pre)	
	)
);

$media_content_pre = 'media_content_idle_';
$media_content_idle = array(
	'id'    => 'media_content_idle',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		css_overflow($media_content_pre),
		css_margin($media_content_pre),
		css_content_align($media_content_pre),
		css_border($media_content_pre),
		css_border_radius($media_content_pre),
		css_border_style($media_content_pre),
		css_border_color($media_content_pre),
		css_shadow($media_content_pre),
		css_shadow_color($media_content_pre),
		css_shadow_inset($media_content_pre),
		css_shadow_inset_color($media_content_pre),
		css_background_color($media_content_pre),
		css_background_image($media_content_pre),
		css_background_size($media_content_pre),
		css_background_repeat($media_content_pre),
		css_background_position_x($media_content_pre),
		css_background_position_y($media_content_pre),
		css_custom($media_content_pre),
		css_custom_desc($media_content_pre)
	)
);
$media_content_pre = 'media_content_hover_';
$media_content_hover = array(
	'id'    => 'media_content_hover',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		css_border($media_content_pre),
		css_border_radius($media_content_pre),
		css_border_style($media_content_pre),
		css_border_color($media_content_pre),
		css_shadow($media_content_pre),
		css_shadow_color($media_content_pre),
		css_shadow_inset($media_content_pre),
		css_shadow_inset_color($media_content_pre),
		css_background_color($media_content_pre),
		css_background_image($media_content_pre),
		css_background_size($media_content_pre),
		css_background_repeat($media_content_pre),
		css_background_position_x($media_content_pre),
		css_background_position_y($media_content_pre),
		css_custom($media_content_pre),
		css_custom_desc($media_content_pre)
	)
);

$top_content_pre = 'top_content_idle_';
$top_content_idle = array(
	'id'    => 'top_content_idle',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		css_overflow($top_content_pre),
		css_margin($top_content_pre),
		css_padding($top_content_pre),
		css_content_align($top_content_pre),
		css_border($top_content_pre),
		css_border_radius($top_content_pre),
		css_border_style($top_content_pre),
		css_border_color($top_content_pre),
		css_shadow($top_content_pre),
		css_shadow_color($top_content_pre),
		css_shadow_inset($top_content_pre),
		css_shadow_inset_color($top_content_pre),
		css_background_color($top_content_pre),
		css_background_image($top_content_pre),
		css_background_size($top_content_pre),
		css_background_repeat($top_content_pre),
		css_background_position_x($top_content_pre),
		css_background_position_y($top_content_pre),
		css_custom($top_content_pre),
		css_custom_desc($top_content_pre)
	)
);
$top_content_pre = 'top_content_hover_';
$top_content_hover = array(
	'id'    => 'top_content_hover',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		css_border($top_content_pre),
		css_border_radius($top_content_pre),
		css_border_style($top_content_pre),
		css_border_color($top_content_pre),
		css_shadow($top_content_pre),
		css_shadow_color($top_content_pre),
		css_shadow_inset($top_content_pre),
		css_shadow_inset_color($top_content_pre),
		css_background_color($top_content_pre),
		css_background_image($top_content_pre),
		css_background_size($top_content_pre),
		css_background_repeat($top_content_pre),
		css_background_position_x($top_content_pre),
		css_background_position_y($top_content_pre),
		css_custom($top_content_pre),
		css_custom_desc($top_content_pre)
	)
);

$bottom_content_pre = 'bottom_content_idle_';
$bottom_content_idle = array(
	'id'    => 'bottom_content_idle',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		css_overflow($bottom_content_pre),
		css_margin($bottom_content_pre),
		css_padding($bottom_content_pre),
		css_content_align($bottom_content_pre),
		css_border($bottom_content_pre),
		css_border_radius($bottom_content_pre),
		css_border_style($bottom_content_pre),
		css_border_color($bottom_content_pre),
		css_shadow($bottom_content_pre),
		css_shadow_color($bottom_content_pre),
		css_shadow_inset($bottom_content_pre),
		css_shadow_inset_color($bottom_content_pre),
		css_background_color($bottom_content_pre),
		css_background_image($bottom_content_pre),
		css_background_size($bottom_content_pre),
		css_background_repeat($bottom_content_pre),
		css_background_position_x($bottom_content_pre),
		css_background_position_y($bottom_content_pre),
		css_custom($bottom_content_pre),
		css_custom_desc($bottom_content_pre)
	)
);
$bottom_content_pre = 'bottom_content_hover_';
$bottom_content_hover = array(
	'id'    => 'bottom_content_hover',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		css_border($bottom_content_pre),
		css_border_radius($bottom_content_pre),
		css_border_style($bottom_content_pre),
		css_border_color($bottom_content_pre),
		css_shadow($bottom_content_pre),
		css_shadow_color($bottom_content_pre),
		css_shadow_inset($bottom_content_pre),
		css_shadow_inset_color($bottom_content_pre),
		css_background_color($bottom_content_pre),
		css_background_image($bottom_content_pre),
		css_background_size($bottom_content_pre),
		css_background_repeat($bottom_content_pre),
		css_background_position_x($bottom_content_pre),
		css_background_position_y($bottom_content_pre),
		css_custom($bottom_content_pre),
		css_custom_desc($bottom_content_pre)
	)
);

function animation_setting($prefix, $animation_name) {
	
	if ($prefix != 'element') {
		$animation_from = array(
			'item'  => __( 'Full item', 'tg-text-domain' ),
			'media' => __( 'Media', 'tg-text-domain' )
		);
	} else {
		$animation_from = array(
			'item'   => __( 'Full item', 'tg-text-domain' ),
			'parent' => __( 'Parent Container', 'tg-text-domain' ),
		);
	}
	
	$item_animation = array(
		'id'    => $prefix.'_animation',
		'title' => '',
		'icon' => '',
		'color' => '#f1f1f1',
		'background' => '#e74c3c',
		'pages' => array('the_grid'),
		'type' => 'page2',
		'fields' => array(
			array(
				'id'   => $prefix.'_animation_state',
				'name' => __( 'Animation Type', 'tg-text-domain'  ),
				'sub_desc' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => tg_css_animation_type($prefix),
				'std' => ''
			),
			array(
				'id'   => $prefix.'_animation_from',
				'name' => __( 'Animation From', 'tg-text-domain'  ),
				'sub_desc' => '',
				'desc' => '',
				'type' => 'select',
				'width' => 180,
				'options' => $animation_from,
				'std' => 'show'
			),
			array(
				'id'   => $prefix.'_animation_name',
				'name' => __( 'Animation Style', 'tg-text-domain'  ),
				'sub_desc' => '',
				'desc' => '',
				'type' => 'custom',
				'options' => tg_css_transform($prefix, $animation_name),
				'std' => ''
			),
			array(
				'id'   => $prefix.'_animation_easing',
				'name' => __( 'Animation Easing', 'tg-text-domain'  ),
				'sub_desc' => '',
				'desc' => '',
				'type' => 'select',
				'width' => 180,
				'options' => array(
					'ease'           => 'Ease',
					'linear'         => 'Linear',
					'ease-in'        => 'EaseIn',
					'ease-out'       => 'EaseOut',
					'ease-in-out'    => 'EaseInOut',
					'easeInCubic'    => 'easeInCubic',
					'easeOutCubic'   => 'easeOutCubic',
					'easeInOutCubic' => 'easeInOutCubic',
					'easeInCirc'     => 'easeInCirc',
					'easeOutCirc'    => 'easeOutCirc',
					'easeInOutCirc'  => 'easeInOutCirc',
					'easeInExpo'     => 'easeInExpo',
					'easeOutExpo'    => 'easeOutExpo',
					'easeInOutExpo'  => 'easeInOutExpo',
					'easeInQuad'     => 'easeInQuad',
					'easeOutQuad'    => 'easeOutQuad',
					'easeInOutQuad'  => 'easeInOutQuad',
					'easeInQuart'    => 'easeInQuart',
					'easeOutQuart'   => 'easeOutQuart',
					'easeInOutQuart' => 'easeInOutQuart',
					'easeInQuint'    => 'easeInQuint',
					'easeOutQuint'   => 'easeOutQuint',
					'easeInOutQuint' => 'easeInOutQuint',
					'easeInSine'     => 'easeInSine',
					'easeOutSine'    => 'easeOutSine',
					'easeInOutSine'  => 'easeInOutSine',
					'easeInBack'     => 'easeInBack',
					'easeOutBack'    => 'easeOutBack',
					'easeInOutBack'  => 'easeInOutBack',
					'custom-easing'  => __( 'Custom Easing', 'tg-text-domain' ),
				),
				'std' => 'ease'
			),
			array(
				'id'   => $prefix.'_animation_custom_easing',
				'name' => __( 'Custom Easing (Cubic Bezier)', 'tg-text-domain' ),
				'options' => '',
				'sub_desc' =>  '',
				'type' => 'text',
				'std' => 'cubic-bezier(.39,1.89,.55,1.45)',
				'required' => array(
					array($prefix.'_animation_easing', '==', 'custom-easing')
				)
			),
			array(
				'id'   => $prefix.'_animation_duration',
				'name' => __( 'Animation Timing', 'tg-text-domain' ),
				'desc' => '',
				'sub_desc' =>  '',
				'type' => 'slider',
				'label' => __( 'Duration', 'tg-text-domain' ),
				'min' => 0,
				'max' => 2500,
				'step' => 10,
				'sign' => 'ms',
				'std' => 700
			),
			array(
				'id'   => $prefix.'_animation_delay',
				'name' => '',
				'desc' => '',
				'sub_desc' =>  '',
				'type' => 'slider',
				'label' => __( 'Delay', 'tg-text-domain' ),
				'min' => 0,
				'max' => 2500,
				'step' => 10,
				'sign' => 'ms',
				'std' => 0
			)
		)
	);
	
	new TOMB_Metabox($item_animation);
	
}

// build layers depth
$prefix = 'z-index';
$layer_depths = array(
	'id'    => 'z-index',
	'title' => '',
	'icon' => '',
	'color' => '#f1f1f1',
	'background' => '#e74c3c',
	'pages' => array('the_grid'),
	'type' => 'page2',
	'fields' => array(
		array(
			'id'   => 'top_content_idle_'.$prefix,
			'name' => __( 'Top Content', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'select',
			'width' => 78,
			'options' => array(
				''        => __( 'None', 'tg-text-domain' ),
				'1' => 1,
				'2' => 2,
				'3' => 3
			),
			'std' => ''
		),
		array(
			'id'   => 'media_holder_idle_'.$prefix,
			'name' => __( 'Media Holder', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'select',
			'width' => 78,
			'options' => array(
				''        => __( 'None', 'tg-text-domain' ),
				'1' => 1,
				'2' => 2,
				'3' => 3
			),
			'std' => ''
		),
		array(
			'id'   => 'bottom_content_idle_'.$prefix,
			'name' => __( 'Bottom Content', 'tg-text-domain'  ),
			'sub_desc' => '',
			'desc' => '',
			'type' => 'select',
			'width' => 78,
			'options' => array(
				''        => __( 'None', 'tg-text-domain' ),
				'1' => 1,
				'2' => 2,
				'3' => 3
			),
			'std' => ''
		),
		array(
			'id'   => $prefix.'_description',
			'name' => '',
			'desc' => '',
			'sub_desc' => sprintf(__( '%sN.B.%s: In 3D view mode you will see the layer depths. The depths are only applied in preview mode in order to prevent any overflow issue.', 'tg-text-domain' ), '<strong>','</strong>') .'<br>'. __( 'Without any depth value (or all equal), elements stack in the order that they appear in the skin (the lowest one down at the same hierarchy level appears on top).', 'tg-text-domain' ) ,
			'type' => 'custom',
			'options' => '',
			'std' => ''
		),
	)
);

// build layers depth
$layers = array('top_content', 'media', 'bottom_content');
foreach ($layers as $prefix) {
	$layer_action[$prefix] = array(
		'id'    => $prefix.'_layer_action',
		'title' => '',
		'icon' => '',
		'color' => '#f1f1f1',
		'background' => '#e74c3c',
		'pages' => array('the_grid'),
		'type' => 'page2',
		'fields' => array(	
			array(
				'id'   => $prefix.'_type',
				'name' => __( 'Action', 'tg-text-domain' ),
				'desc' => '',
				'sub_desc' =>  '',
				'type' => 'select',
				'width' => 160,
				'options' => array(
					''         => __( 'None', 'tg-text-domain' ),
					'link'     => __( 'Link to', 'tg-text-domain' ),
					'lightbox' => __( 'Lightbox/Play', 'tg-text-domain' )
				),
				'std' => ''
			),
			array(
				'id'   => $prefix.'_position',
				'name' => __( 'Position', 'tg-text-domain' ),
				'desc' => '',
				'sub_desc' =>  '',
				'type' => 'select',
				'width' => 160,
				'options' => array(
					'under' => __( 'Under Content', 'tg-text-domain' ),
					'above' => __( 'Above Content', 'tg-text-domain' ),
				),
				'std' => 'under'
			),
			array(
				'id'   => $prefix.'_link_target',
				'name' => __( 'Link Target', 'tg-text-domain' ),
				'desc' => '',
				'sub_desc' =>  '',
				'type' => 'select',
				'width' => 160,
				'options' => array(
					'_self'   => __( '_self', 'tg-text-domain' ),
					'_blank'  => __( '_blank', 'tg-text-domain' )
				),
				'std' => '_self',
				'required' => array(
					array($prefix.'_type', '==', 'link')
				)
			),
			array(
				'id'   => $prefix.'_link_url',
				'name' => __( 'Link to', 'tg-text-domain' ),
				'desc' => '',
				'sub_desc' =>  '',
				'type' => 'select',
				'width' => 160,
				'options' => array(
					'post_url'          => __( 'Post Page', 'tg-text-domain' ),
					'author_posts_url'  => __( 'Author Posts Page', 'tg-text-domain' ),
					'custom_url'        => __( 'Custom url', 'tg-text-domain' ),
					'meta_data_url'     => __( 'Meta Data Key', 'tg-text-domain' )
				),
				'std' => 'post_url',
				'required' => array(
					array($prefix.'_type', '==', 'link')
				)
			),
			array(
				'id'   => $prefix.'_custom_url',
				'name' => __( 'Custom url', 'tg-text-domain' ),
				'desc' => '',
				'sub_desc' =>  '',
				'type' => 'text',
				'size' => 160,
				'options' => '',
				'std' => '',
				'required' => array(
					array($prefix.'_type', '==', 'link'),
					array($prefix.'_link_url', '==', 'custom_url')
				)
			),
			array(
				'id'   => $prefix.'_meta_data_url',
				'name' => __( 'Meta Data Key', 'tg-text-domain' ),
				'desc' => '',
				'sub_desc' =>  '',
				'type' => 'text',
				'size' => 160,
				'options' => '',
				'std' => '',
				'required' => array(
					array($prefix.'_type', '==', 'link'),
					array($prefix.'_link_url', '==', 'meta_data_url')
				)
			)
		)
	);
}

/*************************************
*  Overlay settings
*************************************/

$item_panels = array(
	'item' => array(
		'element'     => 'tg-item-inner',
		'idle_state'  => $item_idle,
		'hover_state' => $item_hover,
	),
	'media_holder' => array(
		'element'     => 'tg-item-media-holder',
		'idle_state'  => $media_holder_idle,
		'hover_state' => $media_holder_hover,
	),
	'overlay' => array(
		'element'     => 'tg-item-overlay',
		'idle_state'  => $overlay_idle,
		'hover_state' => $overlay_hover
	),
	'media_content' => array(
		'element'     => 'tg-item-media-content',
		'idle_state'  => $media_content_idle,
		'hover_state' => $media_content_hover
	),
	'top_content' => array(
		'element'     => 'tg-item-content-holder[data-position=&quot;top&quot;]',
		'idle_state'  => $top_content_idle,
		'hover_state' => $top_content_hover
	),
	'bottom_content' => array(
		'element'     => 'tg-item-content-holder[data-position=&quot;bottom&quot;]',
		'idle_state'  => $bottom_content_idle,
		'hover_state' => $bottom_content_hover
	)
);

/*************************************
*  Generate each item styles panel
*************************************/

foreach ($item_panels as $type => $panel) {
	
	$panels[$type] = '<div class="tg-component-panel">';
			
		$panels[$type] .= '<div class="tomb-tab-content tg-component-styles tomb-tab-show" data-settings="styles">';
						
			$panels[$type] .= '<ul class="tomb-tabs-holder tg-component-tabs">';
				$panels[$type] .= '<li class="tomb-tab tg-component-tab selected" data-target="idle_state"><i class="tomb-icon"></i>'.__( 'Idle Sate', 'tg-text-domain' ).'</li>';
				$panels[$type] .= '<li class="tomb-tab tg-component-tab" data-target="hover_state"><i class="tomb-icon"></i>'.__( 'Hover Sate', 'tg-text-domain' ).'</li>';
			$panels[$type] .= '</ul>';
							
			$panels[$type] .= '<div class="tomb-tab-content tg-component-style-properties idle_state tomb-tab-show" data-settings="idle_state" data-element="'.$panel['element'].'" data-prefix="'.$type.'_idle_">';
			
			
			$panels[$type] .= '<div class="tg-component-back">';
				$panels[$type] .= '<i class="tomb-icon dashicons dashicons-arrow-left-alt2"></i><span>'.__( 'Styles', 'tg-text-domain' ).'</span> / <span></span> / <span></span>';
			$panels[$type] .= '</div>';
			
				ob_start();
				new TOMB_Metabox($panel['idle_state']);
				$panels[$type] .= ob_get_contents();
				ob_clean();
			$panels[$type] .= '</div>';
					
			$panels[$type] .= '<div class="tomb-tab-content tg-component-style-properties hover_state" data-settings="hover_state" data-element="'.$panel['element'].'" data-prefix="'.$type.'_hover_">';
			
				$panels[$type] .= '<div class="tg-style-on-hover">';
					$panels[$type] .= '<label class="tomb-label">'.__( 'Apply styles on mouseover', 'tg-text-domain' ).'</label>';
					$panels[$type] .= '<div class="tomb-switch">';
						$panels[$type] .= '<input type="checkbox" class="tomb-checkbox" name="is_hover">';
						$panels[$type] .= '<label for="is_hover"></label>';
					$panels[$type] .= '</div>';
				$panels[$type] .= '</div>';
				
				$panels[$type] .= '<div class="tg-component-back">';
					$panels[$type] .= '<i class="tomb-icon dashicons dashicons-arrow-left-alt2"></i><span>'.__( 'Styles', 'tg-text-domain' ).'</span> / <span></span> / <span></span>';
				$panels[$type] .= '</div>';

				ob_start();
				new TOMB_Metabox($panel['hover_state']);
				$panels[$type] .= ob_get_contents();
				ob_clean();
			$panels[$type] .= '</div>';
							
		$panels[$type] .= '</div>';
				
	$panels[$type] .= '</div>';	

}

/*************************************
*  Handle skin download error
*************************************/

global $tg_download_skin_error;

if (!empty($tg_download_skin_error)) {
	echo $tg_download_skin_error;
}

/*************************************
*  Item settings
*************************************/

$skin_name = __( 'My Skin', 'tg-text-domain' );

if (isset($_GET['id'])) {

	$settings = The_Grid_Custom_Table::get_skin_settings($_GET['id']);
	
	if ($settings) {
		$skin_name = json_decode($settings, true);
		$skin_name = $skin_name['item']['layout']['skin_name'];
		echo '<script type="text/javascript">var tg_skin_settings = '.wp_json_encode($settings).';</script>';
	}

}

echo '<div class="tg-panels-holder">';

echo '<div class="tg-panel-item" data-element-animations=\''.wp_json_encode($animation_name->get_animation_name()).'\'>';
	
	echo '<div class="skin_name tomb-field tomb-type-text tomb-row">';
		echo '<label class="tomb-label">'.__( 'Skin Name', 'tg-text-domain' ).'</label>';
		echo '<input type="text" class="tomb-text" name="skin_name" id="skin_name" value=\''.esc_textarea($skin_name).'\'>';
	echo '</div>';
	
	echo '<div class="tg-layout-settings">'.__( 'Layout Settings', 'tg-text-domain' ).'</div>';
	
	
	echo '<div class="tg-container-content">';
	
		echo '<ul class="tomb-tabs-holder tomb-tabs-item-settings">';
			echo '<li class="tomb-tab" data-target="tg-tab-item-layout"><i class="tomb-icon dashicons dashicons-editor-kitchensink"></i>'.__( 'Item Layout', 'tg-text-domain' ).'</li>';
			echo '<li class="tomb-tab" data-target="tg-tab-item-color-scheme"><i class="tomb-icon dashicons dashicons-art"></i>'.__( 'Color Scheme', 'tg-text-domain' ).'</li>';
			echo '<li class="tomb-tab" data-target="tg-tab-item-styles" data-highlight="tg-item-inner"><i class="tomb-icon dashicons dashicons-align-center"></i>'.__( 'Item Styles', 'tg-text-domain' ).'</li>';
			echo '<li class="tomb-tab" data-target="tg-tab-top-content-styles" data-highlight="tg-item-content-holder[data-position=\'top\']"><i class="tomb-icon dashicons dashicons-menu"></i>'.__( 'Top Content Styles', 'tg-text-domain' );
			echo '<li class="tomb-tab" data-target="tg-tab-media-holder-styles" data-highlight="tg-item-media-holder, .tg-item-media-image"><i class="tomb-icon dashicons dashicons-format-image"></i>'.__( 'Media Holder Styles', 'tg-text-domain' ).'</li>';
			echo '<li class="tomb-tab" data-target="tg-tab-overlay-styles" data-highlight="tg-item-overlay"><i class="tomb-icon dashicons dashicons-admin-page"></i>'.__( 'Overlay Styles', 'tg-text-domain' ).'</li>';
			echo '<li class="tomb-tab" data-target="tg-tab-media-content-styles" data-highlight="tg-item-media-content"><i class="tomb-icon dashicons dashicons-menu"></i>'.__( 'Media Content Holder Styles', 'tg-text-domain' ).'</li>';
			echo '<li class="tomb-tab" data-target="tg-tab-bottom-content-styles" data-highlight="tg-item-content-holder[data-position=\'bottom\']"><i class="tomb-icon dashicons dashicons-menu"></i>'.__( 'Bottom Content Styles', 'tg-text-domain' );
			echo '<li class="tomb-tab" data-target="tg-tab-global-css"><i class="tomb-icon dashicons dashicons-admin-appearance"></i>'.__( 'Global Custom CSS', 'tg-text-domain' );
			echo '<li class="tomb-tab" data-target="tg-tab-media-animations"><i class="tomb-icon dashicons dashicons-format-video"></i>'.__( 'Media Animations', 'tg-text-domain' );
			echo '<li class="tomb-tab" data-target="tg-tab-layer-depths"><i class="tomb-icon dashicons dashicons-images-alt2"></i>'.__( 'Layer Depths', 'tg-text-domain' );
			echo '<li class="tomb-tab" data-target="tg-tab-layer-actions"><i class="tomb-icon dashicons dashicons-admin-links"></i>'.__( 'Layer Actions', 'tg-text-domain' );
		echo '</ul>';
		
		echo '<div class="tg-component-back">';
			echo '<i class="tomb-icon dashicons dashicons-arrow-left-alt2"></i><span></span>';
		echo '</div>';
		
		echo '<div class="tomb-tab-content tg-tab-item-layout tomb-tab-show" data-settings="layout">';
			new TOMB_Metabox($item_layout);
		echo '</div>';
		
		echo '<div class="tomb-tab-content tg-tab-item-color-scheme">';
			echo '<div class="tomb-metabox">';
				echo '<p class="tg-important-rule-desc">'.__( 'The Grid uses a color scheme system to automatically apply colors for the overlay background, content background and text colors.', 'tg-text-domain' );
				echo '<br><br>';
				echo __( 'The skin builder will preserve this color scheme system which can be setup for each grid and in the global settings.', 'tg-text-domain' );
				echo '<br><br>';
				echo __( 'However, if you want to override a color inside a skin, you can add an important rule thanks to the exclamation point icon (!).', 'tg-text-domain' );
				echo '</p>';
				echo '<img class="tg-important-rule-img" src="'. TG_PLUGIN_URL.'backend/assets/images/important-rule.jpg"/>';
				echo '<p class="tg-important-rule-desc"><strong>'.__( 'The styles displayed in the Skin Builder will be the same as the one displayed in the grid.', 'tg-text-domain' ).'</strong></p>';
			echo '</div>';
		echo '</div>';
		
		echo '<div class="tomb-tab-content tg-tab-item-styles" data-element="tg-item-inner" data-settings="tg-item-inner" data-style="true">';
			echo $panels['item'];
		echo '</div>';
		
		echo '<div class="tomb-tab-content tg-tab-media-holder-styles" data-element="tg-item-media-holder" data-settings="tg-item-media-holder" data-style="true">';
			echo $panels['media_holder'];
		echo '</div>';
		
		echo '<div class="tomb-tab-content tg-tab-overlay-styles" data-element="tg-item-overlay" data-settings="tg-item-overlay" data-style="true">';
			echo $panels['overlay'];
		echo '</div>';
		
		$overlays = array(
			'tg-item-media-inner',
			'tg-item-overlay[data-position=&quot;top&quot;]',
			'tg-item-overlay[data-position=&quot;center&quot;]',
			'tg-item-overlay[data-position=&quot;bottom&quot;]',
			'tg-top-holder',
			'tg-center-inner',
			'tg-bottom-holder',
		);
		
		foreach ($overlays as $overlay) {
			
			echo '<div class="tomb-tab-content tg-tab-item-media-styles" data-element=\''.$overlay.'\' data-settings=\''.$overlay.'\' data-style="true">';
				echo '<div class="tomb-tab-content tg-component-style-properties" data-settings="styles">';
					echo '<div class="tomb-tab-content tg-component-style-properties idle_state" data-settings="idle_state" data-element=\''.$overlay.'\' data-prefix="overlay_"></div>';
					echo '<div class="tomb-tab-content tg-component-style-properties hover_state" data-settings="hover_state" data-element=\''.$overlay.'\' data-prefix="overlay_"></div>';
				echo '</div>';
			echo '</div>';
		}		
		
		echo '<div class="tomb-tab-content tg-tab-top-content-styles" data-element=\'tg-item-content-holder[data-position=&quot;top&quot;]\' data-settings=\'tg-item-content-holder[data-position=&quot;top&quot;]\' data-style="true">';
			echo $panels['top_content'];
		echo '</div>';
		
		echo '<div class="tomb-tab-content tg-tab-bottom-content-styles" data-element=\'tg-item-content-holder[data-position=&quot;bottom&quot;]\' data-settings=\'tg-item-content-holder[data-position=&quot;bottom&quot;]\' data-style="true">';
			echo $panels['bottom_content'];
		echo '</div>';
		
		echo '<div class="tomb-tab-content tg-tab-media-content-styles" data-element="tg-item-media-content" data-settings="tg-item-media-content" data-style="true">';
			echo $panels['media_content'];
		echo '</div>';

		echo '<div class="tomb-tab-content tg-tab-global-css" data-settings="global_css">';
			new TOMB_Metabox($global_css);
		echo '</div>';
		
		echo '<div class="tomb-tab-content tg-tab-media-animations" data-element="tg-item-animation" data-settings="animations">';
		
			echo '<div class="tg-component-panel">';
				echo '<div class="tomb-tab-content tg-component-animation" data-settings="animation">';
					echo '<ul class="tomb-tabs-holder tg-component-tabs">';
						echo '<li class="tomb-tab tg-component-tab selected" data-target="tg-tab-media-inner-animation" data-highlight="tg-item-media-inner">'.__( 'Media', 'tg-text-domain' ).'</li>';
						echo '<li class="tomb-tab tg-component-tab" data-target="tg-tab-overlay-animation" data-highlight="tg-item-overlay">'.__( 'Overlay', 'tg-text-domain' ).'</li>';
						echo '<li class="tomb-tab tg-component-tab" data-target="tg-tab-media-content-animation" data-highlight="tg-item-media-content">'.__( 'Media Content', 'tg-text-domain' ).'</li>';
					echo '</ul>';
					
					echo '<div class="tomb-tab-content tg-tab-media-inner-animation" data-settings="tg-item-media-inner" data-prefix="media_inner_">';
						animation_setting('media_inner', $animation_name);
					echo '</div>';
					
					echo '<div class="tomb-tab-content tg-tab-overlay-animation">';
						echo '<ul class="tomb-tabs-holder tg-component-tabs tg-overlay-positions">';
							echo '<li class="tomb-tab tg-component-tab selected" data-target="tg-tab-overlay-top" data-highlight=\'tg-item-overlay[data-position=&quot;top&quot;]\'>'.__( 'Top', 'tg-text-domain' ).'</li>';
							echo '<li class="tomb-tab tg-component-tab" data-target="tg-tab-overlay-center" data-highlight=\'tg-item-overlay[data-position=&quot;center&quot;]\'>'.__( 'Center', 'tg-text-domain' ).'</li>';
							echo '<li class="tomb-tab tg-component-tab" data-target="tg-tab-overlay-bottom" data-highlight=\'tg-item-overlay[data-position=&quot;bottom&quot;]\'>'.__( 'Bottom', 'tg-text-domain' ).'</li>';
						echo '</ul>';
									
						echo '<div class="tomb-tab-content tg-tab-overlay-top" data-settings=\'tg-item-overlay[data-position=&quot;top&quot;]\' data-prefix="overlay_top_">';		
							animation_setting('overlay_top', $animation_name);
						echo '</div>';	
						echo '<div class="tomb-tab-content tg-tab-overlay-center" data-settings=\'tg-item-overlay[data-position=&quot;center&quot;]\' data-prefix="overlay_center_">';
							animation_setting('overlay_center', $animation_name);
						echo '</div>';	
						echo '<div class="tomb-tab-content tg-tab-overlay-bottom" data-settings=\'tg-item-overlay[data-position=&quot;bottom&quot;]\' data-prefix="overlay_bottom_">';
							animation_setting('overlay_bottom', $animation_name);
						echo '</div>';	
							
					echo '</div>';

					echo '<div class="tomb-tab-content tg-tab-media-content-animation">';
					
						echo '<ul class="tomb-tabs-holder tg-component-tabs tg-media-content-positions">';
							echo '<li class="tomb-tab tg-component-tab selected" data-target="tg-tab-full-holder" data-highlight="tg-item-media-content">'.__( 'All', 'tg-text-domain' ).'</li>';
							echo '<li class="tomb-tab tg-component-tab" data-target="tg-tab-top-holder" data-highlight="tg-top-holder">'.__( 'Top', 'tg-text-domain' ).'</li>';
							echo '<li class="tomb-tab tg-component-tab" data-target="tg-tab-center-holder" data-highlight="tg-center-holder">'.__( 'Center', 'tg-text-domain' ).'</li>';
							echo '<li class="tomb-tab tg-component-tab" data-target="tg-tab-bottom-holder" data-highlight="tg-bottom-holder">'.__( 'Bottom', 'tg-text-domain' ).'</li>';
						echo '</ul>';
						
						echo '<div class="tomb-tab-content tg-tab-full-holder" data-settings="tg-item-media-content" data-prefix="media_content_">';
							animation_setting('media_content', $animation_name);
						echo '</div>';		
						echo '<div class="tomb-tab-content tg-tab-top-holder" data-settings="tg-top-holder" data-prefix="top_holder_">';		
							animation_setting('top_holder', $animation_name);
						echo '</div>';	
						echo '<div class="tomb-tab-content tg-tab-center-holder" data-settings="tg-center-inner" data-prefix="center_holder_">';
							animation_setting('center_holder', $animation_name);
						echo '</div>';	
						echo '<div class="tomb-tab-content tg-tab-bottom-holder" data-settings="tg-bottom-holder" data-prefix="bottom_holder_">';
							animation_setting('bottom_holder', $animation_name);
						echo '</div>';	
							
					echo '</div>';
					
					
										
				echo '</div>';	
			echo '</div>';

		echo '</div>';
		
		echo '<div class="tomb-tab-content tg-tab-layer-depths" data-settings="z-index" data-prefix="z-index">';
			new TOMB_Metabox($layer_depths);
		echo '</div>';
		
		echo '<div class="tomb-tab-content tg-tab-layer-actions" data-settings="action" data-prefix="action">';
		
			echo '<div class="tg-component-panel">';
			
				echo '<div class="tomb-tab-content tg-component-actions" data-settings="action" data-prefix="action">';
				
					echo '<ul class="tomb-tabs-holder tg-component-tabs">';
						echo '<li class="tomb-tab tg-component-tab selected" data-target="tg-top-content-action" data-highlight=\'tg-item-content-holder[data-position=&quot;top&quot;]\'>'.__( 'Top content', 'tg-text-domain' ).'</li>';
						echo '<li class="tomb-tab tg-component-tab" data-target="tg-media-content-action" data-highlight="tg-item-media-holder">'.__( 'Media Content', 'tg-text-domain' ).'</li>';
						echo '<li class="tomb-tab tg-component-tab" data-target="tg-bottom-content-action" data-highlight=\'tg-item-content-holder[data-position=&quot;bottom&quot;]\'>'.__( 'Bottom Content', 'tg-text-domain' ).'</li>';
					echo '</ul>';

					echo '<div class="tomb-tab-content tg-top-content-action" data-settings=\'tg-item-content-holder[data-position=&quot;top&quot;]\' data-prefix="top_content_">';	
						new TOMB_Metabox($layer_action['top_content']);
					echo '</div>';
					
					echo '<div class="tomb-tab-content tg-media-content-action" data-settings="tg-item-media-holder" data-prefix="media_">';	
						new TOMB_Metabox($layer_action['media']);
					echo '</div>';
					
					echo '<div class="tomb-tab-content tg-bottom-content-action" data-settings=\'tg-item-content-holder[data-position=&quot;bottom&quot;]\' data-prefix="bottom_content_">';	
						new TOMB_Metabox($layer_action['bottom_content']);
					echo '</div>';
				
				echo '</div>';
			
			echo '</div>';
			
		echo '</div>';
	
	echo '</div>';
	
echo '</div>';
	
/*************************************
*  Skin Builder (drag & drop builder)
*************************************/

echo '<div class="tg-panel-skin">';

	echo '<div class="tg-container-header">';
		echo '<div class="tg-container-title">'.__( 'Item Layout', 'tg-text-domain' ).'</div>';
		echo '<div class="tg-button" id="tg-item-preview"><i class="dashicons dashicons-visibility"></i>'.__( 'Preview', 'tg-text-domain' ).'</div>';
		echo '<div class="tg-button" id="tg-3d-view"><i class="dashicons dashicons-images-alt2"></i>'.__( '3D View', 'tg-text-domain' ).'</div>';
	echo '</div>';
	
	$zone_name = __( 'DROP ZONE', 'tg-text-domain' );
	
	echo '<div class="tg-skin-build-inner">';
		
		echo '<div id="tg-ruler-holder">';
			echo '<div id="tg-horizontalRuler-overlay" class="tg-ruler"></div>';
			echo '<div id="tg-verticalRuler-overlay" class="tg-ruler"></div>';
			echo '<div id="tg-rightRuler-overlay" class="tg-ruler"></div>';
			echo '<div id="tg-horizontalRuler" class="tg-ruler"><div id="tg-hMarker" class="tg-marker"><span></span></div></div>';
			echo '<div id="tg-verticalRuler" class="tg-ruler"><div id="tg-vMarker" class="tg-marker"><span></span></div></div>';
			echo '<div id="tg-ruler-corner-top-left"></div>';
			echo '<div id="tg-ruler-corner-top-right"></div>';
			echo '<div id="tg-ruler-corner-bottom-left"></div>';
			echo '<div id="tg-ruler-grid"></div>';
			echo '<div id="tg-ruler-grid-toolbar">';
			
				echo '<div class="tg-ruler-grid-tool">';
					echo '<label>'.__( 'Helper Grid', 'tg-text-domain' ).'</label>';
					echo '<div class="tomb-select-holder" data-noresult="'. __( 'No results found', 'tg-text-domain' ) .'" data-clear="" style="width:75px;">';
						echo '<div class="tomb-select-fake">';
							echo '<span class="tomb-select-value">'. __( 'None', 'tg-text-domain' ) .'</span>';
							echo '<span class="tomb-select-arrow"><i></i></span>';
						echo '</div>';
						echo '<select class="tomb-select tg-css-unit" name="tg-ruler-grid-size">';
							echo '<option value="">'. __( 'None', 'tg-text-domain' ) .'</option>';
							echo '<option value="tg-grid-100" data-grid="20">100</option>';
							echo '<option value="tg-grid-50" data-grid="10">50</option>';
							echo '<option value="tg-grid-25" data-grid="5">25</option>';
							echo '<option value="tg-grid-10" data-grid="5">10</option>';
							echo '<option value="tg-grid-5" data-grid="5">5</option>';
						echo '</select>';
					echo '</div>';
				echo '</div>';
				
				echo '<div class="tg-ruler-grid-tool">';
					echo '<input type="checkbox" class="tomb-checkbox-list" name="tg-ruler-grid-snap">';
					echo '<label>'.__( 'Snap to Grid', 'tg-text-domain' ).'</label>';
				echo '</div>';
				
			echo '</div>';
		echo '</div>';
		
		echo '<div id="tg-toolbar">';
			echo '<div id="tg-add-element" class="tg-button"><i class="dashicons dashicons-archive"></i>'.__( 'Add Element', 'tg-text-domain' ).'</div>';
			echo '<div class="tg-element-class">';
				echo '<div class="tomb-select-holder" data-noresult="'.__( 'No element found', 'tg-text-domain' ).'" data-clear="true" style="width:260px">';
					echo '<div class="tomb-select-fake">';
						echo '<span class="tomb-select-value">'.__( 'Class Name', 'tg-text-domain' ).'</span>';
						echo '<span class="tomb-select-arrow"><i></i></span>';
					echo '</div>';
					echo '<select class="tomb-select" name="tg-element-class" data-clear="">';
						echo '<option value="" selected="selected">'.__( 'No Element Selected', 'tg-text-domain' ).'</option>';
					echo '</select>';
				echo '</div>';
			echo '</div>';
			
		echo '</div>';
		
	
		echo '<div class="tg-item">';
			echo '<div class="tg-item-inner" data-width="400">';
				echo '<div class="tg-item-content-holder tg-dark tg-area-droppable" data-position="top" data-name="'.__( 'TOP CONTENT', 'tg-text-domain' ).'" data-item-area="top-content-holder">';
				echo '</div>';
				echo '<div class="tg-item-media-holder tg-light" data-name="'.__( 'Media', 'tg-text-domain' ).'">';
					echo '<div class="tg-item-media-inner">';
						echo '<div class="tg-item-media-image"></div>';
					echo '</div>';
					echo '<div class="tg-item-media-content tg-area-droppable" data-item-area="media-holder" data-name="'.__( 'Media Content', 'tg-text-domain' ).'">';
						echo '<div class="tg-item-overlay" data-name="'.__( 'Overlay', 'tg-text-domain' ).'"></div>';
						echo '<div class="tg-item-overlay-content tg-area-droppable tg-media-top tg-top-holder" data-item-area="media-holder-top" data-position="top" data-name="'.$zone_name.'"></div>';
						echo '<div class="tg-media-center tg-center-holder" data-position="center">';
							echo '<div class="tg-item-overlay-content tg-area-droppable tg-media-center tg-center-inner" data-item-area="media-holder-center" data-name="'.$zone_name.'"></div>';
						echo '</div>';
						
						echo '<div class="tg-item-overlay-content tg-area-droppable tg-media-bottom tg-bottom-holder" data-item-area="media-holder-bottom" data-position="bottom" data-name="'.$zone_name.'"></div>';
					echo '</div>';
				echo '</div>';
				echo '<div class="tg-item-content-holder tg-dark tg-area-droppable" data-position="bottom" data-name="'.__( 'BOTTOM CONTENT', 'tg-text-domain' ).'" data-item-area="bottom-content-holder">';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
	echo '<div class="tg-skin-elements-css"></div>';
	
	echo '<div class="tg-loading-editor">';
		echo '<span>'. __( 'Loading Skin Builder...', 'tg-text-domain' ) .'</span>';
	echo '</div>';

	
echo '</div>';

echo '</div>';

/*************************************
*  Elements
*************************************/

echo '<div class="tg-panel-elements">';

	echo '<div class="tg-container-header">';
		echo '<div class="tg-container-title">'.__( 'Available Elements', 'tg-text-domain' ).'</div>';
		echo '<i class="tg-container-close dashicons dashicons-no-alt"></i>';
	echo '</div>';
	
	echo '<div class="tg-elements-inner tg-component-panel tg-dark">';
	
		echo '<ul class="tomb-tabs-holder tg-component-tabs">';
			echo '<li class="tomb-tab tg-component-tab selected" data-target="tg-native-elements">'.__( 'Default Elements', 'tg-text-domain' ).'</li>';
			echo '<li class="tomb-tab tg-component-tab" data-target="tg-custom-elements">'.__( 'Custom Elements', 'tg-text-domain' ).'</li>';
		echo '</ul>';

		echo '<div class="tomb-tab-content tg-native-elements tomb-tab-show"></div>';
		
		echo '<div class="tomb-tab-content tg-custom-elements"></div>';
		
		echo '<div class="tg-element-styles-holder"></div>';

	echo '</div>';
	
echo '</div>';

/*************************************
*  Element settings
*************************************/

echo '<div class="tg-panel-element" data-elements-content=\''.json_encode($elements_content, true).'\'>';

	echo '<div class="tg-container-header">';
		echo '<div class="tg-container-title">'.__( 'Settings', 'tg-text-domain' ).'<span></span></div>';
		echo '<i class="tg-container-close dashicons dashicons-no-alt"></i>';
	echo '</div>';
	
	echo '<div class="tg-component-panel">';
	
		echo '<ul class="tomb-tabs-holder tg-component-tabs">';
			echo '<li class="tomb-tab tg-component-tab selected" data-target="tg-component-sources">'.__( 'Content', 'tg-text-domain' ).'</li>';
			echo '<li class="tomb-tab tg-component-tab" data-target="tg-component-action">'.__( 'Action', 'tg-text-domain' ).'</li>';
			echo '<li class="tomb-tab tg-component-tab" data-target="tg-component-styles">'.__( 'Styles', 'tg-text-domain' ).'</li>';
			echo '<li class="tomb-tab tg-component-tab" data-target="tg-component-animation">'.__( 'Animation', 'tg-text-domain' ).'</li>';
		echo '</ul>';
		
		echo '<div class="tomb-tab-content tg-component-sources" data-settings="source" data-prefix="">';
			new TOMB_Metabox($element_source);
		echo '</div>';
		
		echo '<div class="tomb-tab-content tg-component-action" data-settings="action" data-prefix="">';
			new TOMB_Metabox($element_action);
		echo '</div>';
		
		
		echo '<div class="tomb-tab-content tg-component-styles tg-element-styles" data-settings="styles" data-prefix="">';
				
			echo '<ul class="tomb-tabs-holder tg-component-tabs">';
				echo '<li class="tomb-tab tg-component-tab selected" data-target="idle_state"><i class="tomb-icon"></i>'.__( 'Idle Sate', 'tg-text-domain' ).'</li>';
				echo '<li class="tomb-tab tg-component-tab" data-target="hover_state"><i class="tomb-icon"></i>'.__( 'Hover Sate', 'tg-text-domain' ).'</li>';
			echo '</ul>';
					
			echo '<div class="tomb-tab-content tg-component-style-properties idle_state" data-settings="idle_state" data-prefix="element_idle_">';
			
				echo '<div class="tg-component-back">';
					echo '<i class="tomb-icon dashicons dashicons-arrow-left-alt2"></i><span>'.__( 'Styles', 'tg-text-domain' ).'</span> / <span></span> / <span></span>';
				echo '</div>';
			
				new TOMB_Metabox($element_idle);
			echo '</div>';
			
			echo '<div class="tomb-tab-content tg-component-style-properties hover_state" data-settings="hover_state" data-prefix="element_hover_">';
				echo '<div class="tg-style-on-hover">';
					echo '<label class="tomb-label">'.__( 'Apply styles on mouseover', 'tg-text-domain' ).'</label>';
					echo '<div class="tomb-switch">';
						echo '<input type="checkbox" class="tomb-checkbox" name="is_hover">';
						echo '<label for="is_hover"></label>';
					echo '</div>';
				echo '</div>';
				
				echo '<div class="tg-component-back">';
					echo '<i class="tomb-icon dashicons dashicons-arrow-left-alt2"></i><span>'.__( 'Styles', 'tg-text-domain' ).'</span> / <span></span> / <span></span>';
				echo '</div>';
				
				new TOMB_Metabox($element_hover);
			echo '</div>';
					
		echo '</div>';
		
		echo '<div class="tomb-tab-content tg-component-animation tg-element-animation idle_state" data-settings="animation" data-prefix="element_">';
				animation_setting('element', $animation_name);
		echo '</div>';
		
		echo '<div class="tg-component-footer">';
			echo '<div class="tg-button" id="tg-element-save" data-action="tg_save_element"><i class="dashicons dashicons-format-aside"></i>';
				echo '<span class="tg-filter-tooltip-holder">';	
					echo '<span class="tg-filter-tooltip">'.__( 'Save as Template', 'tg-text-domain' ).'</span>';
				echo '</span>';
			echo '</div>';
			echo '<div class="tg-button" id="tg-element-clone"><i class="dashicons dashicons-format-gallery"></i>';
				echo '<span class="tg-filter-tooltip-holder">';	
					echo '<span class="tg-filter-tooltip">'.__( 'Clone Element', 'tg-text-domain' ).'</span>';
				echo '</span>';
			echo '</div>';
			echo '<div class="tg-button" id="tg-element-remove"><i class="dashicons dashicons-trash"></i>';
				echo '<span class="tg-filter-tooltip-holder">';	
					echo '<span class="tg-filter-tooltip">'.__( 'Remove Element', 'tg-text-domain' ).'</span>';
				echo '</span>';
			echo '</div>';
			echo '<div class="tg-button tg-element-move" data-move="next"><i class="dashicons dashicons-arrow-down-alt2"></i>';
				echo '<span class="tg-filter-tooltip-holder">';	
					echo '<span class="tg-filter-tooltip">'.__( 'Move Element Down', 'tg-text-domain' ).'</span>';
				echo '</span>';
			echo '</div>';
			echo '<div class="tg-button tg-element-move" data-move="prev"><i class="dashicons dashicons-arrow-up-alt2"></i>';
				echo '<span class="tg-filter-tooltip-holder">';	
					echo '<span class="tg-filter-tooltip">'.__( 'Move Element Up', 'tg-text-domain' ).'</span>';
				echo '</span>';
			echo '</div>';
		echo '</div>';
		
	echo '</div>';	
		
echo '</div>';

$schemes    = array('dark','light');
$title_tags = array('', '.tg-div-tag:not(.tg-line-break)', '.tg-h-tag:not(.tg-line-break)', 'i', '.tg-media-button');
$para_tags  = array('.tg-p-tag:not(.tg-line-break)', 'p');
$span_tags  = array('.tg-span-tag:not(.tg-line-break)', 'span', '.no-liked .to-heart-icon path', '.empty-heart .to-heart-icon path');
		
$tags = array(
	'title' => $title_tags,
	'text'  => $para_tags,
	'span'  => $span_tags
);

$color_options = array(
	'light' => array(
		'title' => get_option('the_grid_light_title'),
		'text'  => get_option('the_grid_light_text'),
		'span'  => get_option('the_grid_light_span')
	),
	'dark' => array(
		'title' => get_option('the_grid_dark_title'),
		'text'  => get_option('the_grid_dark_text'),
		'span'  => get_option('the_grid_dark_span')
	)
);

$default = array(
	'dark_title'  => '#444444',
	'dark_text'   => '#777777',
	'dark_span'   => '#999999',
	'light_title' => '#ffffff',
	'light_text'  => '#f5f5f5',
	'light_span'  => '#f6f6f6',
);

$colors = null;
$base = new The_Grid_Base();
foreach ($schemes as $scheme) {
	foreach ($tags as $tag => $classes) {
		$classes   = implode(',.tg-item .tg-'.$scheme.' ', $classes);
		$def_color = $default[$scheme.'_'.$tag];
		$color_scheme  = $base->getVar($color_options,$scheme, array());
		$color_value   = $base->getVar($color_scheme,$tag,$def_color);
		$colors .= ($color_value) ? '.tg-panel-elements .tg-'.$scheme.' '.$classes.'{color:'.$color_value.';fill:'.$color_value.';stroke:'.$color_value.';border-color:'.$color_value.'}' : '';
		$colors .= ($color_value) ? '.tg-item .tg-'.$scheme.' '.$classes.'{color:'.$color_value.';fill:'.$color_value.';stroke:'.$color_value.';border-color:'.$color_value.'}' : '';
		$colors .= ($color_value) ? '.tg-item-preview .tg-item .tg-'.$scheme.' '.$classes.'{color:'.$color_value.';fill:'.$color_value.';stroke:'.$color_value.';border-color:'.$color_value.'}' : '';
	}
}

echo '<style type="text/css">'.$colors.'</style>';

echo '<div class="tg-icons-popup">';
	echo '<div class="tg-icons-popup-triangle"></div>';
	echo '<div class="tg-icons-search-holder">';
		echo '<input type="search" class="tg-icons-search" autocomplete="off" placeholder="'.__( 'Type to search icons...', 'tg-text-domain' ).'">';
	echo '</div>';
	echo '<div class="tg-icons-list-holder">';	
		echo '<div class="tg-icons-list">'.$icon_list.'</div>';
	echo '</div>';
echo '</div>';