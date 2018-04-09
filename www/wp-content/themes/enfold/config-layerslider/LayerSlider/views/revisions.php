<?php

if(!defined('LS_ROOT_FILE')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

// Show the welcome screen when the slider ID is missing or the plugin is not yet activated
if( empty( $_GET['id'] ) ||  ! get_option('layerslider-authorized-site', false) ) {

	include LS_ROOT_PATH . '/templates/tmpl-revisions-welcome.php';

} else {

	if( ! $revisions = LS_Revisions::snapshots( (int)$_GET['id'] ) ) {
		$notification = sprintf(__('There are no revisions available for the selected slider yet. Revisions will be added over time when you make new changes to your sliders. Check %sRevisions Preferences%s and make sure that Revisions is enabled.', 'LayerSlider'), '<a href="#" class="ls-revisions-options">', '</a>');
		include LS_ROOT_PATH . '/templates/tmpl-revisions-welcome.php';
	} else {
		include LS_ROOT_PATH . '/templates/tmpl-revisions-history.php';
	}

}