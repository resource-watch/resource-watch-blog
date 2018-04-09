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
		$banner .= '<h2><span>The Grid</span>'. __( 'Grid Settings', 'tg-text-domain') .'</h2>';
		$banner .= '<div id="tg-buttons-holder">';
			$banner .= '<a class="tg-button" data-action="tg_save" id="tg_post_save"><i class="dashicons dashicons-yes"></i>'. __( 'Save', 'tg-text-domain') .'</a>';
			$banner .= '<a class="tg-button" id="tg_post_preview"><i class="dashicons dashicons-welcome-view-site"></i>'. __( 'Preview', 'tg-text-domain') .'</a>';
			$banner .= '<a class="tg-button" data-action="tg_delete" id="tg_post_delete"><i class="dashicons dashicons-trash"></i>'. __( 'Delete', 'tg-text-domain') .'</a>';
			$banner .= '<a class="tg-button" id="tg_post_close" href="'.admin_url( 'admin.php?page=the_grid').'"><i class="dashicons dashicons-no-alt"></i>'. __( 'Close', 'tg-text-domain') .'</a>';
		$banner .= '</div>';
	$banner .= '</div>';

$banner .= '</div>';

$banner .= $tg_admin_notices;

echo $banner;