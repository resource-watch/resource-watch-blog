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

$info_box = '<div id="tg-info-box">';
	$info_box .= '<div class="tg-info-overlay"></div>';
	$info_box .= '<div class="tg-info-inner">';
		$info_box .= '<div class="tg-info-box-msg"></div>';
	$info_box .= '</div>';
$info_box .= '</div>';

echo $info_box;
