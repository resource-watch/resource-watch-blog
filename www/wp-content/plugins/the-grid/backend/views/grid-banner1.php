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

global $tg_admin_notices;

$banner  = '<div id="tg-banner-holder">';

	$banner .= '<div id="tg-banner" class="tg-banner-sticky">';
		$banner .= '<h2><span>The Grid</span>'.__( 'Overview', 'tg-text-domain').'</h2>';
	$banner .= '</div>';

$banner .= '</div>';

$banner .= $tg_admin_notices;

echo $banner;