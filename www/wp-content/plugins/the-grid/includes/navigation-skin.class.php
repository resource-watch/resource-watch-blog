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
 
class The_Grid_Navigation_Skin {

    private $navigations = array();

    function __construct() {
        $this->navigations = apply_filters('tg_add_navigation_skin', $this->navigations);
    }

    function __call($name,$grid_data) {		
        if(isset($this->navigations[$name])) {
			return $this->navigations[$name]();
		}
    }
	
	// get skin names array
	function get_navigation_name() {
		$navigation_skin_arr = array();
        $navigation_skins = $this->navigations;
		foreach ($navigation_skins as $navigation_skin => $param) {
			$navigation_name = $this->$navigation_skin();
			$navigation_skin_arr[$navigation_skin] = $navigation_name['name'];
		}
		return $navigation_skin_arr;
    }
}

add_filter('tg_add_navigation_skin', function($navigations) {
	
	$navigations['tg-txt'] = function() {
		
		global $tg_nav_colors;
		
		$navigation['name'] = 'Text';
		
		$css =  $tg_nav_colors['css_ID'].' .tg-nav-color:not(.dots):not(.tg-dropdown-value):not(.tg-dropdown-title):hover,
				'.$tg_nav_colors['css_ID'].' .tg-nav-color:hover .tg-nav-color,
				'.$tg_nav_colors['css_ID'].' .tg-page-number.tg-page-current,
				'.$tg_nav_colors['css_ID'].' .tg-filter.tg-filter-active span {
					color: '.$tg_nav_colors['accent_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-filter:before,
				'.$tg_nav_colors['css_ID'].' .tg-filter.tg-filter-active:before {
					color: '.$tg_nav_colors['text_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-dropdown-holder,
				'.$tg_nav_colors['css_ID'].' .tg-search-inner,
				'.$tg_nav_colors['css_ID'].' .tg-sorter-order {
					border: 1px solid '.$tg_nav_colors['border_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-search-clear,
				'.$tg_nav_colors['css_ID'].' .tg-search-clear:hover {
					border: none;
					border-left: 1px solid '.$tg_nav_colors['border_color'].';
				}
				.tg-txt .tg-nav-font,
				.tg-txt input[type=text].tg-search {
					font-size: 14px;
					font-weight: 600;
				}
				.tg-txt .tg-search::-webkit-input-placeholder {
					font-size: 14px;
				}
				.tg-txt .tg-search::-moz-placeholder {
					font-size: 14px;
				}
				.tg-txt .tg-search:-ms-input-placeholder {
					font-size: 14px;
				}
				.tg-txt .tg-icon-left-arrow:before {
					content: "\e604";
					font-size: 32px;
					font-weight: 100;
				}
				.tg-txt .tg-icon-right-arrow:before {
					content: "\e602";
					font-size: 32px;
					font-weight: 100;
				}
				.tg-txt .tg-icon-dropdown-open:before,
				.tg-txt .tg-icon-sorter-down:before {
					content: "\e60a";
				}
				.tg-txt .tg-icon-sorter-up:before {
					content: "\e609";
				}
				.tg-txt .tg-search-clear:before {
					content: "\e611";
					font-weight: 300;
				}
				.tg-txt .tg-search-icon:before {
					content: "\e62e";
					font-size: 16px;
					font-weight: 600;
				}';
		
		$navigation['css'] = $css;
		
		return $navigation;
	};
	
	$navigations['tg-txt-slash'] = function() {
		
		global $tg_nav_colors;
		
		$navigation['name'] = 'Text Slash';
		
		$css =  $tg_nav_colors['css_ID'].' .tg-nav-color:not(.dots):not(.tg-dropdown-value):not(.tg-dropdown-title):hover,
				'.$tg_nav_colors['css_ID'].' .tg-nav-color:hover .tg-nav-color,
				'.$tg_nav_colors['css_ID'].' .tg-page-number.tg-page-current,
				'.$tg_nav_colors['css_ID'].' .tg-filter.tg-filter-active span {
					color: '.$tg_nav_colors['accent_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-filter:before,
				'.$tg_nav_colors['css_ID'].' .tg-filter.tg-filter-active:before {
					color: '.$tg_nav_colors['text_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-dropdown-holder,
				'.$tg_nav_colors['css_ID'].' .tg-search-inner,
				'.$tg_nav_colors['css_ID'].' .tg-sorter-order {
					border: 1px solid '.$tg_nav_colors['border_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-search-clear,
				'.$tg_nav_colors['css_ID'].' .tg-search-clear:hover {
					border: none;
					border-left: 1px solid '.$tg_nav_colors['border_color'].';
				}
				.tg-txt-slash .tg-nav-font,
				.tg-txt-slash input[type=text].tg-search {
					font-size: 14px;
					font-weight: 600;
				}
				.tg-txt-slash .tg-search::-webkit-input-placeholder {
					font-size: 14px;
				}
				.tg-txt-slash .tg-search::-moz-placeholder {
					font-size: 14px;
				}
				.tg-txt-slash .tg-search:-ms-input-placeholder {
					font-size: 14px;
				}
				.tg-txt-slash .tg-filter:before {
					content: "/";
					position: absolute;
					display: block;
					left: -5px;
					font-weight: normal;
					opacity: 0.7;
				}
				.tg-txt-slash .tg-filter:first-child:before {
					content: "";
				}
				.tg-txt-slash .tg-icon-left-arrow:before {
					content: "\e604";
					font-size: 32px;
					font-weight: 100;
				}
				.tg-txt-slash .tg-icon-right-arrow:before {
					content: "\e602";
					font-size: 32px;
					font-weight: 100;
				}
				.tg-txt-slash .tg-icon-dropdown-open:before,
				.tg-txt-slash .tg-icon-sorter-down:before {
					content: "\e60a";
				}
				.tg-txt-slash .tg-icon-sorter-up:before {
					content: "\e609";
				}
				.tg-txt-slash .tg-search-clear:before {
					content: "\e611";
					font-weight: 300;
				}
				.tg-txt-slash .tg-search-icon:before {
					content: "\e62e";
					font-size: 16px;
					font-weight: 600;
				}';
		
		$navigation['css'] = $css;
		
		return $navigation;
	};
	
	$navigations['tg-nav-sqr-thin'] = function() {
		
		global $tg_nav_colors;
		
		$navigation['name'] = 'Square Thin';
		
		$css =  $tg_nav_colors['css_ID'].' .tg-nav-border:hover,
				'.$tg_nav_colors['css_ID'].' .tg-page-number.tg-page-current,
				'.$tg_nav_colors['css_ID'].' .tg-filter.tg-filter-active:not(.tg-dropdown-item) {
  					border-color: '.$tg_nav_colors['accent_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-nav-border,
				'.$tg_nav_colors['css_ID'].' .tg-dropdown-holder:hover,
				'.$tg_nav_colors['css_ID'].' .tg-search-inner:hover,
				'.$tg_nav_colors['css_ID'].' .tg-sorter-order:hover,
				'.$tg_nav_colors['css_ID'].' .tg-disabled:hover i {
					border: 1px solid '.$tg_nav_colors['border_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-search-clear,
				'.$tg_nav_colors['css_ID'].' .tg-search-clear:hover {
					border: none;
					border-left: 1px solid '.$tg_nav_colors['border_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-nav-color:not(.dots):not(.tg-dropdown-value):not(.tg-dropdown-title):hover,
				'.$tg_nav_colors['css_ID'].' .tg-nav-color:hover .tg-nav-color,
				'.$tg_nav_colors['css_ID'].' .tg-page-number.tg-page-current,
				'.$tg_nav_colors['css_ID'].' .tg-filter.tg-filter-active span:not(.tg-filter-count) {
					color: '.$tg_nav_colors['accent_color'].';
				}
				.tg-nav-sqr-thin .tg-page-number.dots {
					border: none !important;
				}	
				.tg-nav-sqr-thin .tg-grid-area-left i,
				.tg-nav-sqr-thin .tg-grid-area-right i {
					line-height: 38px;
				}	
				.tg-nav-sqr-thin .tg-page-number.dots,
				.tg-nav-sqr-thin .tg-slider-bullets {
					height: 32px;
				}
				.tg-nav-sqr-thin .tg-search-icon,
				.tg-nav-sqr-thin .tg-sorter-order i {
					font-weight: 100;
				}
				.tg-nav-sqr-thin .tg-page-number.dots,
				.tg-nav-sqr-thin .tg-search-inner,
				.tg-nav-sqr-thin .tg-search-clear,
				.tg-nav-sqr-thin .tg-sorter-order,
				.tg-nav-sqr-thin .tg-left-arrow,
				.tg-nav-sqr-thin .tg-right-arrow {
					border: none;
				}
				.tg-nav-sqr-thin .tg-dropdown-list {
					margin-top: 1px;
				}
				';
		
		$navigation['css'] = $css;
		
		return $navigation;
	};
	
	$navigations['tg-nav-sqr-thick'] = function() {
		
		global $tg_nav_colors;
		
		$navigation['name'] = 'Square Thick';
		
		
		$css =  $tg_nav_colors['css_ID'].' .tg-nav-border:hover,
				'.$tg_nav_colors['css_ID'].' .tg-page-number.tg-page-current,
				'.$tg_nav_colors['css_ID'].' .tg-filter.tg-filter-active:not(.tg-dropdown-item) {
  					border-color: '.$tg_nav_colors['accent_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-nav-border,
				'.$tg_nav_colors['css_ID'].' .tg-dropdown-holder:hover,
				'.$tg_nav_colors['css_ID'].' .tg-search-inner:hover,
				'.$tg_nav_colors['css_ID'].' .tg-sorter-order:hover,
				'.$tg_nav_colors['css_ID'].' .tg-disabled:hover i {
					border: 2px solid '.$tg_nav_colors['border_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-search-clear,
				'.$tg_nav_colors['css_ID'].' .tg-search-clear:hover {
					border: none;
					border-left: 2px solid '.$tg_nav_colors['border_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-nav-color:not(.dots):not(.tg-dropdown-value):not(.tg-dropdown-title):hover,
				'.$tg_nav_colors['css_ID'].' .tg-nav-color:hover .tg-nav-color,
				'.$tg_nav_colors['css_ID'].' .tg-page-number.tg-page-current,
				'.$tg_nav_colors['css_ID'].' .tg-filter.tg-filter-active span:not(.tg-filter-count) {
					color: '.$tg_nav_colors['accent_color'].';
				}
				.tg-nav-sqr-thick .tg-page-number.dots {
					border: none !important;
				}
				.tg-nav-sqr-thick .tg-grid-area-left i,
				.tg-nav-sqr-thick .tg-grid-area-left i:before,
				.tg-nav-sqr-thick .tg-grid-area-right i,
				.tg-nav-sqr-thick .tg-grid-area-right i:before {
					line-height: 38px;
				}
				.tg-nav-sqr-thick input[type=text].tg-search {
					height: 36px;
				}
				.tg-nav-sqr-thick .tg-nav-font,
				.tg-nav-sqr-thick input[type=text].tg-search {
					font-size: 13px;
					font-weight: 600;
					line-height: 36px;
				}
				.tg-nav-sqr-thick .tg-search::-webkit-input-placeholder {
					font-size: 13px;
					font-weight: 600;
					line-height: 36px;
				}
				.tg-nav-sqr-thick .tg-search::-moz-placeholder {
					font-size: 13px;
					font-weight: 600;
					line-height: 36px;
				}
				.tg-nav-sqr-thick .tg-search:-ms-input-placeholder {
					font-size: 13px;
					font-weight: 600;
					line-height: 36px;
				}
				.tg-nav-sqr-thick .tg-page-number.dots,
				.tg-nav-sqr-thick .tg-slider-bullets {
					height: 40px;
				}
				.tg-nav-sqr-thick .tg-search-icon,
				.tg-nav-sqr-thick .tg-search-clear,
				.tg-nav-sqr-thick .tg-sorter-order,
				.tg-nav-sqr-thick .tg-page-number,
				.tg-nav-sqr-thick .tg-left-arrow i,
				.tg-nav-sqr-thick .tg-right-arrow i {
					min-width: 40px;
				}
				.tg-nav-sqr-thick .tg-search-icon,
				.tg-nav-sqr-thick .tg-sorter-order i {
					font-weight: 100;
				}
				.tg-nav-sqr-thick .tg-page-number.dots,
				.tg-nav-sqr-thick .tg-search-inner,
				.tg-nav-sqr-thick .tg-search-clear,
				.tg-nav-sqr-thick .tg-sorter-order,
				.tg-nav-sqr-thick .tg-left-arrow,
				.tg-nav-sqr-thick .tg-right-arrow {
					border: none;
				}
				.tg-nav-sqr-thick .tg-dropdown-list {
					margin-top: 2px;
				}
				';
		
		$navigation['css'] = $css;
		
		return $navigation;
	};

	$navigations['tg-nav-bg'] = function() {
		
		global $tg_nav_colors;

		$navigation['name'] = 'Background';
		
		$css =  $tg_nav_colors['css_ID'].' .tg-nav-color:not(.dots):not(.tg-dropdown-value):not(.tg-dropdown-title):hover,
				'.$tg_nav_colors['css_ID'].' .tg-nav-color:hover .tg-nav-color,
				'.$tg_nav_colors['css_ID'].' .tg-filter-active span,
				'.$tg_nav_colors['css_ID'].' .tg-page-number.tg-page-current {
					color: '.$tg_nav_colors['accent_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-filter:not(.tg-dropdown-item),
				'.$tg_nav_colors['css_ID'].' .tg-search-holder,
				'.$tg_nav_colors['css_ID'].' .tg-dropdown-holder,
				'.$tg_nav_colors['css_ID'].' .tg-sorter-order,
				'.$tg_nav_colors['css_ID'].' .tg-left-arrow,
				'.$tg_nav_colors['css_ID'].' .tg-right-arrow,
				'.$tg_nav_colors['css_ID'].' .tg-search-holder,
				'.$tg_nav_colors['css_ID'].' .tg-page-number:not(.dots),
				'.$tg_nav_colors['css_ID'].' .tg-pagination-prev,
				'.$tg_nav_colors['css_ID'].' .tg-pagination-next,
				'.$tg_nav_colors['css_ID'].' .tg-ajax-button {
					background: '.$tg_nav_colors['background'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-filter:not(.tg-dropdown-item):hover,
				'.$tg_nav_colors['css_ID'].' .tg-filter.tg-filter-active,
				'.$tg_nav_colors['css_ID'].' .tg-sorter-order:hover,
				'.$tg_nav_colors['css_ID'].' .tg-left-arrow:not(.tg-disabled):hover,
				'.$tg_nav_colors['css_ID'].' .tg-right-arrow:not(.tg-disabled):hover,
				'.$tg_nav_colors['css_ID'].' .tg-page-number.tg-page-current,
				'.$tg_nav_colors['css_ID'].' .tg-page-number:not(.dots):hover,
				'.$tg_nav_colors['css_ID'].' .tg-pagination-prev:hover,
				'.$tg_nav_colors['css_ID'].' .tg-pagination-next:hover,
				'.$tg_nav_colors['css_ID'].' .tg-ajax-button:hover {
					background: '.$tg_nav_colors['accent_background'].';
				}
				.tg-nav-bg input[type=text].tg-search {
					height: 34px;
				}
				.tg-nav-bg .tg-nav-font,
				.tg-nav-bg input[type=text].tg-search {
					font-size: 13px;
					line-height: 34px;
				}
				.tg-nav-bg .tg-search::-webkit-input-placeholder {
					font-size: 13px;
					line-height: 34px;
				}
				.tg-nav-bg .tg-search::-moz-placeholder {
					font-size: 13px;
					line-height: 34px;
				}
				.tg-nav-bg .tg-search:-ms-input-placeholder {
					font-size: 13px;
					line-height: 34px;
				}
				.tg-nav-bg .tg-page-number.dots,
				.tg-nav-bg .tg-slider-bullets {
					height: 34px;
				}
				.tg-nav-bg .tg-search-icon,
				.tg-nav-bg .tg-search-clear,
				.tg-nav-bg .tg-sorter-order,
				.tg-nav-bg .tg-page-number,
				.tg-nav-bg .tg-left-arrow i,
				.tg-nav-bg .tg-right-arrow i {
					min-width: 34px;
				}
				.tg-nav-bg .tg-dropdown-item {
					font-weight: normal;
				}
				.tg-nav-bg .tg-dropdown-item {
					text-transform: none;
				}';
		
		$navigation['css'] = $css;
		
		return $navigation;
	};
	
	$navigations['tg-nav-bg-round'] = function() {
		
		global $tg_nav_colors;

		$navigation['name'] = 'Background Rounded';
		
		$css =  $tg_nav_colors['css_ID'].' .tg-nav-color:not(.dots):hover,
				'.$tg_nav_colors['css_ID'].' .tg-nav-color:hover .tg-nav-color,
				'.$tg_nav_colors['css_ID'].' .tg-filter-active span,
				'.$tg_nav_colors['css_ID'].' .tg-page-number.tg-page-current {
					color: '.$tg_nav_colors['accent_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-filter:not(.tg-dropdown-item),
				'.$tg_nav_colors['css_ID'].' .tg-search-holder,
				'.$tg_nav_colors['css_ID'].' .tg-dropdown-holder,
				'.$tg_nav_colors['css_ID'].' .tg-sorter-order,
				'.$tg_nav_colors['css_ID'].' .tg-left-arrow,
				'.$tg_nav_colors['css_ID'].' .tg-right-arrow,
				'.$tg_nav_colors['css_ID'].' .tg-search-holder,
				'.$tg_nav_colors['css_ID'].' .tg-page-number:not(.dots),
				'.$tg_nav_colors['css_ID'].' .tg-pagination-prev,
				'.$tg_nav_colors['css_ID'].' .tg-pagination-next,
				'.$tg_nav_colors['css_ID'].' .tg-ajax-button {
					background: '.$tg_nav_colors['background'].';
					border-radius: 3px;
				}
				'.$tg_nav_colors['css_ID'].' .tg-filter:not(.tg-dropdown-item):hover,
				'.$tg_nav_colors['css_ID'].' .tg-filter.tg-filter-active,
				'.$tg_nav_colors['css_ID'].' .tg-sorter-order:hover,
				'.$tg_nav_colors['css_ID'].' .tg-left-arrow:not(.tg-disabled):hover,
				'.$tg_nav_colors['css_ID'].' .tg-right-arrow:not(.tg-disabled):hover,
				'.$tg_nav_colors['css_ID'].' .tg-page-number.tg-page-current,
				'.$tg_nav_colors['css_ID'].' .tg-page-number:not(.dots):hover,
				'.$tg_nav_colors['css_ID'].' .tg-pagination-prev:hover,
				'.$tg_nav_colors['css_ID'].' .tg-pagination-next:hover,
				'.$tg_nav_colors['css_ID'].' .tg-ajax-button:hover {
					background: '.$tg_nav_colors['accent_background'].';
				}
				.tg-nav-bg-round input[type=text].tg-search {
					height: 34px;
				}
				.tg-nav-bg-round .tg-nav-font,
				.tg-nav-bg-round input[type=text].tg-search {
					font-size: 13px;
					line-height: 34px;
				}
				.tg-nav-bg-round .tg-search::-webkit-input-placeholder {
					font-size: 13px;
					line-height: 34px;
				}
				.tg-nav-bg-round .tg-search::-moz-placeholder {
					font-size: 13px;
					line-height: 34px;
				}
				.tg-nav-bg-round .tg-search:-ms-input-placeholder {
					font-size: 13px;
					line-height: 34px;
				}
				.tg-nav-bg-round .tg-page-number.dots,
				.tg-nav-bg-round .tg-slider-bullets {
					height: 34px;
				}
				.tg-nav-bg-round .tg-search-icon,
				.tg-nav-bg-round .tg-search-clear,
				.tg-nav-bg-round .tg-sorter-order,
				.tg-nav-bg-round .tg-page-number,
				.tg-nav-bg-round .tg-left-arrow i,
				.tg-nav-bg-round .tg-right-arrow i {
					min-width: 34px;
				}
				.tg-nav-bg-round .tg-dropdown-item {
					font-weight: normal;
				}
				.tg-nav-bg-round .tg-dropdown-item {
					text-transform: none;
				}';
		
		$navigation['css'] = $css;
		
		return $navigation;
	};
	
	$navigations['tg-nav-under'] = function() {
		
		global $tg_nav_colors;

		$navigation['name'] = 'Underline';
		
		$css =  $tg_nav_colors['css_ID'].' .tg-nav-color:not(.dots):not(.tg-dropdown-value):not(.tg-dropdown-title):not(.tg-filter-name):hover,
				'.$tg_nav_colors['css_ID'].' .tg-page-number.tg-page-current,
				'.$tg_nav_colors['css_ID'].' .tg-filter-active span:not(.tg-filter-count) {
					color: '.$tg_nav_colors['accent_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-filter:not(.tg-dropdown-item):after {
  					border-bottom: 3px solid '.$tg_nav_colors['accent_color'].';
				}
				'.$tg_nav_colors['css_ID'].' .tg-search-holder:before,
				'.$tg_nav_colors['css_ID'].' .tg-dropdown-holder:before,
				'.$tg_nav_colors['css_ID'].' .tg-filter:not(.tg-dropdown-item):before {
					border-bottom: 1px solid '.$tg_nav_colors['border_color'].';
				}
				.tg-nav-under .tg-nav-font,
				.tg-nav-under input[type=text].tg-search {
					font-size: 13px;
					font-weight: 600;
					text-transform: uppercase;
				}
				.tg-nav-under .tg-search::-webkit-input-placeholder {
					font-size: 13px;
					font-weight: 600;
					text-transform: uppercase;
				}
				.tg-nav-under .tg-search::-moz-placeholder {
					font-size: 13px;
					font-weight: 600;
					text-transform: uppercase;
				}
				.tg-nav-under .tg-search:-ms-input-placeholder {
					font-size: 13px;
					font-weight: 600;
					text-transform: uppercase;
				}
				.tg-nav-under .tg-dropdown-item {
					font-weight: normal;
				}
				.tg-nav-under .tg-dropdown-item {
					text-transform: none;
				}
				.tg-nav-under a.tg-page-number {
					border: none;
				}
				.tg-nav-under .tg-filter {
					margin: 0 0 5px 0;
				}
				.tg-nav-under .tg-filter-name {
					padding-left: 20px;
					padding-right: 20px;
				}
				.tg-nav-under .tg-filter {
					padding-top: 14px;
				}
				.tg-nav-under .tg-filter-name {
					padding-bottom: 14px;
				}
				.tg-nav-under .tg-sorter-order,
				.tg-nav-under .tg-search-holder,
				.tg-nav-under .tg-dropdown-holder,
				.tg-nav-under .tg-ajax-button,
				.tg-nav-under .tg-pagination-holder,
				.tg-nav-under .tg-slider-bullets-holder,
				.tg-nav-under .tg-left-arrow,
				.tg-nav-under .tg-right-arrow {
					padding-top: 14px;
					padding-bottom: 14px;
				}
				.tg-nav-under .tg-search-holder:before,
				.tg-nav-under .tg-dropdown-holder:before,
				.tg-nav-under .tg-filter:not(.tg-dropdown-item):before {
					content: "";
					position: absolute;
					display: block;
					top: 0;
					bottom: 0;
					left: 0;
					right: 0;
					opacity: 0.6;
				}
				.tg-nav-under .tg-filter:not(.tg-dropdown-item):after {
					content: "";
					position: absolute;
					display: block;
					top: 0;
					bottom: 0;
					left: 0;
					right: 0;
					margin: 0 auto;
					opacity: 0;
					-webkit-transform: scale(0,1);
					-moz-transform: scale(0,1);
					-ms-transform: scale(0,1);
					-o-transform: scale(0,1);
					transform: scale(0,1);
					-webkit-transition: -webkit-transform 0.2s linear, opacity 0.2s linear;
					-moz-transition: -moz-transform 0.2s linear, opacity 0.2s linear;
					-ms-transition: -ms-transform 0.2s linear, opacity 0.2s linear;
					-o-transition: -o-transform 0.2s linear, opacity 0.2s linear;
					transition: transform 0.2s linear, opacity 0.2s linear;
				}
				.tg-nav-under .tg-filter.tg-filter-active:after,
				.tg-nav-under .tg-filter:hover:after {
					opacity: 1;
					-webkit-transform: scale(1,1);
					-moz-transform: scale(1,1);
					-ms-transform: scale(1,1);
					-o-transform: scale(1,1);
					transform: scale(1,1);
				}
				.tg-nav-under .tg-left-arrow:before,
				.tg-nav-under .tg-right-arrow:before,
				.tg-nav-under .tg-icon-dropdown-open:before,
				.tg-nav-under .tg-icon-sorter-down:before,
				.tg-nav-under .tg-icon-sorter-up:before {
					font-weight: bolder;
					font-size: 17px;
					position: relative;
				}			
				.tg-nav-under .tg-icon-left-arrow:before {
					content: "\e604";
					font-size: 32px;
					font-weight: 100;
				}
				.tg-nav-under .tg-icon-right-arrow:before {
					content: "\e602";
					font-size: 32px;
					font-weight: 100;
				}
				.tg-nav-under .tg-icon-dropdown-open:before,
				.tg-nav-under .tg-icon-sorter-down:before {
					content: "\e60a";
				}
				.tg-nav-under .tg-icon-sorter-up:before {
					content: "\e609";
				}
				.tg-nav-under .tg-search-clear:before {
					content: "\e611";
					font-weight: 300;
				}
				.tg-nav-under .tg-search-icon:before {
					content: "\e62e";
					font-size: 16px;
					font-weight: 600;
				}
				.tg-nav-under .tg-page-number {
					font-size: 14px;
				}
				.tg-nav-under .tg-grid-area-left .tg-left-arrow,
				.tg-nav-under .tg-grid-area-right .tg-right-arrow {
  					width: 40px;
					height: 40px;
					line-height: 40px;
					padding: 0;
				}
				.tg-nav-under .tg-grid-area-left .tg-icon-left-arrow:before,
				.tg-nav-under .tg-grid-area-right .tg-icon-right-arrow:before {
  					top: 0;
					line-height: 40px;
				}';
		
		$navigation['css'] = $css;
		
		return $navigation;
	};
	
	return $navigations;
	
});