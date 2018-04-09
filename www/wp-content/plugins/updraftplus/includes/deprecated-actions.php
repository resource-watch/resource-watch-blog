<?php

if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed');

/*
These actions are no longer called, except in the case of someone restoring an old version of UD onto a new backend and not refreshing the page. We can keep them around a bit longer to handle that limited case. This generally means that they were replaced by calls to commands in the standardised UpdraftPlus_Commands class.

These have been removed from admin.php as part of the process of removing them entirely.
*/

global $updraftplus, $updraftplus_admin;

if (isset($_POST['subaction']) && 'credentials_test' === $_POST['subaction']) {

	$updraftplus_admin->do_credentials_test($updraftplus->wp_unslash($_POST));
	
} elseif ('poplog' == $_REQUEST['subaction']) {

	echo json_encode($updraftplus_admin->fetch_log($_REQUEST['backup_nonce']));
	
} elseif ('sid_reset' == $_REQUEST['subaction']) {

	delete_site_option('updraftplus-addons_siteid');
	echo json_encode(array('newsid' => $updraftplus->siteid()));

} elseif ('countbackups' == $_REQUEST['subaction']) {

	$backup_history = UpdraftPlus_Backup_History::get_history();
	echo __('Existing Backups', 'updraftplus').' ('.count($backup_history).')';
	
} elseif ('historystatus' == $subaction) {

	$remotescan = !empty($_GET['remotescan']);
	$rescan = ($remotescan || !empty($_GET['rescan']));
	
	$history_status = $updraftplus_admin->get_history_status($rescan, $remotescan);
	echo @json_encode($history_status);

} elseif ('diskspaceused' == $subaction && isset($_GET['entity'])) {
	$entity = $_GET['entity'];
	// This can count either the size of the Updraft directory, or of the data to be backed up
	echo $updraftplus_admin->get_disk_space_used($entity);
} elseif ('callwpaction' == $subaction) {
	$updraftplus_admin->call_wp_action($updraftplus->wp_unslash($_REQUEST), true);
} elseif ('lastbackup' == $subaction) {
	echo $updraftplus_admin->last_backup_html();
}
