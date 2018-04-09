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

$preview  = '<div id="tg-grid-preview">';
	$preview .= '<div id="tg-grid-preview-overlay"></div>';
	
	$preview .= '<div id="tg-grid-preview-header">';
		$preview .= '<div id="tg-grid-preview-viewport">';
			$preview .= '<div class="tg-grid-preview-mobile"><div class="tg-grid-preview-img"></div><div class="tg-grid-preview-tooltip">'.__( 'Mobile', 'tg-text-domain').'</div></div>';
			$preview .= '<div class="tg-grid-preview-tablet-small"><div class="tg-grid-preview-img"></div><div class="tg-grid-preview-tooltip">'.__( 'Tablet Small', 'tg-text-domain').'</div></div>';
			$preview .= '<div class="tg-grid-preview-tablet"><div class="tg-grid-preview-img"></div><div class="tg-grid-preview-tooltip">'.__( 'Tablet', 'tg-text-domain').'</div></div>';
			$preview .= '<div class="tg-grid-preview-desktop-small"><div class="tg-grid-preview-img"></div><div class="tg-grid-preview-tooltip">'.__( 'Desktop Small', 'tg-text-domain').'</div></div>';
			$preview .= '<div class="tg-grid-preview-desktop-medium"><div class="tg-grid-preview-img"></div><div class="tg-grid-preview-tooltip">'.__( 'Desktop Medium', 'tg-text-domain').'</div></div>';
			$preview .= '<div class="tg-grid-preview-desktop-large tg-viewport-active"><div class="tg-grid-preview-img"></div><div class="tg-grid-preview-tooltip">'.__( 'Desktop Large', 'tg-text-domain').'</div></div>';	
		$preview .= '</div>';
		$preview .= '<div id="tg-grid-preview-refresh"><i class="dashicons dashicons-update"></i></div>';
		$preview .= '<div id="tg-grid-preview-close"><i class="dashicons dashicons-no-alt"></i></div>';
	$preview .= '</div>';
	
	$preview .= '<div id="tg-grid-preview-wrapper">';
		$preview .= '<div id="tg-grid-preview-loading">'.__('Fetching grid data, please wait...', 'tg-text-domain').'</div>';
		$preview .= '<div id="tg-grid-preview-inner" ></div>';
		$preview .= '<div id="tg-grid-preview-settings">';
			$preview .= '<div id="tg-grid-preview-settings-footer">';
				$preview .= '<span class="tg-grid-preview-settings-wait">'.__('Please Wait', 'tg-text-domain').'</span><div class="spinner"></div>';
				$preview .= '<div class="tg-button" id="tg-grid-preview-settings-save">';
					$preview .= '<i class="dashicons dashicons-yes"></i>';
					$preview .= __( 'Save Changes', 'tg-text-domain' );
				$preview .= '</div>';
			$preview .= '</div>';
		$preview .= '</div>';
	$preview .= '</div>';
	
$preview .= '</div>';

echo $preview;