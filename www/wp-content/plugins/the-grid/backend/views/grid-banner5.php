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

$banner .= '<form method="post" style="display:none">';
$banner .= '<input type="submit" name="tg_export_skin"/>';
$banner .= '</form>';

	$banner .= '<div id="tg-banner" class="tg-banner-sticky">';
		$banner .= '<h2><span>The Grid</span>'. __( 'Skin Builder', 'tg-text-domain') .'</h2>';
		$banner .= '<div id="tg-buttons-holder">';
			$banner .= '<a class="tg-button" id="tg_download_skin"><i class="tg-info-box-icon dashicons dashicons-upload"></i>'. __( 'Download Skin (Developer)', 'tg-text-domain') .'</a>';
			$banner .= '<a class="tg-button" data-action="tg_save_skin" id="tg_skin_save"><i class="dashicons dashicons-yes"></i>'. __( 'Save', 'tg-text-domain') .'</a>';
			$banner .= '<a class="tg-button" id="tg_post_close" href="'.admin_url( 'admin.php?page=the_grid_skins_overview').'"><i class="dashicons dashicons-no-alt"></i>'. __( 'Close', 'tg-text-domain') .'</a>';		
		$banner .= '</div>';
	$banner .= '</div>';

$banner .= '</div>';

$banner .= $tg_admin_notices;

echo $banner;