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

$form_import  = '<div class="metabox-holder tg-import">';
	$form_import .= '<div class="postbox">';
		$form_import .= '<div class="tg-box-side">';
			$form_import .= '<h3>'. __( 'Importer', 'tg-text-domain' ) .'</h3>';
			$form_import .= '<i class="tg-info-box-icon dashicons dashicons-download"></i>';
		$form_import .= '</div>';
		$form_import .= '<div class="inside tg-box-inside">';
			$form_import .= '<h3>'. __( 'Import Grid/Skin', 'tg-text-domain' ) .'</h3>';
			$form_import .= '<p>'. __( 'Please select a .json file created with the current exporter feature.', 'tg-text-domain'  ) .'<br>'. __( 'Import grid/skin will add new grid(s) in overview list or skin(s) in skin builder panel.', 'tg-text-domain'  ) .'</p>';
			$form_import .= '<input type="file" id="tg-import-file" name="import_file"/>';
			$form_import .= '<br><br><div class="tg-button" data-action="tg_read_import_file" id="tg-import-read-file"><i class="tg-info-box-icon dashicons dashicons-search"></i>'. __( 'Read file content', 'tg-text-domain' ) .'</div>';
			$form_import .= ' <span><strong>'. __( 'or', 'tg-text-domain' ) .'&nbsp;&nbsp;</strong></span>';
			$form_import .= '<a class="tg-button" data-action="tg_read_import_file" id="tg-import-read-demo"><i class="dashicons dashicons-search"></i>'.__('Read Grid demo content', 'tg-text-domain').'</a>';
			$form_import .= '<div class="tg-import-loading"><div class="spinner"></div><strong class="tg-import-msg-success"></strong><strong class="tg-import-msg-error"></strong></div>';
			$form_import .= '<div class="tg-import-content"></div>';
		$form_import .= '</div>';
	$form_import .= '</div>';
$form_import .= '</div>';


echo $form_import;


