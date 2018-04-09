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

$banner = '<div id="tg-banner-holder">';

	$banner .= '<div id="tg-banner" class="tg-banner-sticky">';
		$banner .= '<h2><span>The Grid</span>'.__( 'Global Settings', 'tg-text-domain').'</h2>';
		$banner .= '<div id="tg-buttons-holder">';
			$banner .= '<a class="tg-button" data-action="tg_save_settings" id="tg_settings_save"><i class="dashicons dashicons-yes"></i>'. __( 'Save Settings', 'tg-text-domain') .'</a>';
			$banner .= '<a class="tg-button reset" data-action="tg_reset_settings" id="tg_settings_reset"><i class="dashicons dashicons-update"></i>'. __( 'Reset', 'tg-text-domain') .'</a>';
		$banner .= '</div>';
	$banner .= '</div>';

$banner .= '</div>';

$banner .= $tg_admin_notices;

echo $banner;