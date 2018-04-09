<?php

if (!defined('UPDRAFTPLUS_DIR')) die('No access.');

/*
	- A container for all the remote commands implemented. Commands map exactly onto method names (and hence this class should not implement anything else, beyond the constructor, and private methods)
	- Return format is either to return data (boolean, string, array), or an WP_Error object
	
	Commands are not allowed to begin with an underscore. So, any private methods can be prefixed with an underscore.
	
	TODO: Many of these just verify input, and then call back into a relevant method in UpdraftPlus_Admin. Once all commands have been ported over to go via this class, those methods in UpdraftPlus_Admin can generally be folded into the relevant method in here, and removed from UpdraftPlus_Admin. (Since this class is intended to become the official way of performing actions). As a bonus, we then won't need so much _load_ud(_admin) boilerplate.
	
*/

if (class_exists('UpdraftPlus_Commands')) return;

class UpdraftPlus_Commands {

	private $_uc_helper;

	/**
	 * Constructor
	 *
	 * @param string $uc_helper The 'helper' needs to provide the method _updraftplus_background_operation_started
	 */
	public function __construct($uc_helper) {
		$this->_uc_helper = $uc_helper;
	}

	/**
	 * Get the Advanced Tools HTMl and return to Central
	 *
	 * @param  string $options Options for advanced settings
	 * @return string
	 */
	public function get_advanced_settings($options) {
		// load global updraftplus and admin
		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');
		if (false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');

		$html = $updraftplus_admin->settings_advanced_tools(true, array('options' => $options));
		
		return $html;
	}

	public function get_download_status($items) {
		// load global updraftplus and admin
		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');
		
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');
	
		if (!is_array($items)) $items = array();

		return $updraftplus_admin->get_download_statuses($items);
	
	}
	
	public function downloader($downloader_params) {

		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');
		
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');
	
		$findex = $downloader_params['findex'];
		$type = $downloader_params['type'];
		$timestamp = $downloader_params['timestamp'];
		// Valid stages: 2='spool the data'|'delete'='delete local copy'|anything else='make sure it is present'
		$stage = empty($downloader_params['stage']) ? false : $downloader_params['stage'];
	
		// This may, or may not, return, depending upon whether the files are already downloaded
		// The response is usually an array with key 'result', and values deleted|downloaded|needs_download|download_failed
		$response = $updraftplus_admin->do_updraft_download_backup($findex, $type, $timestamp, $stage, array($this->_uc_helper, '_updraftplus_background_operation_started'));
	
		if (is_array($response)) {
			$response['request'] = $downloader_params;
		}
	
		return $response;
	}
	
	public function delete_downloaded($set_info) {
		$set_info['stage'] = 'delete';
		return $this->downloader($set_info);
	}
	
	public function backup_progress($params) {
	
		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');
		
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');
		
		$request = array(
			'thisjobonly' => $params['job_id']
		);
		
		$activejobs_list = $updraftplus_admin->get_activejobs_list($request);
		
		return $activejobs_list;
	
	}
	
	public function backupnow($params) {
		
		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');
		
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');

		$updraftplus_admin->request_backupnow($params, array($this->_uc_helper, '_updraftplus_background_operation_started'));
		
		// Control returns when the backup finished; but, the browser connection should have been closed before
		die;
	}
	
	private function _load_ud() {
		global $updraftplus;
		return is_a($updraftplus, 'UpdraftPlus') ? $updraftplus : false;
	}
	
	private function _load_ud_admin() {
		if (!defined('UPDRAFTPLUS_DIR') || !is_file(UPDRAFTPLUS_DIR.'/admin.php')) return false;
		include_once(UPDRAFTPLUS_DIR.'/admin.php');
		global $updraftplus_admin;
		return $updraftplus_admin;
	}
	
	public function get_log($job_id = '') {

		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');
	
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');

		if ('' != $job_id && !preg_match("/^[0-9a-f]{12}$/", $job_id)) return new WP_Error('updraftplus_permission_invalid_jobid');
		
		return $updraftplus_admin->fetch_log($job_id);
	
	}
	
	public function activejobs_delete($job_id) {
	
		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');

		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');

		return $updraftplus_admin->activejobs_delete((string) $job_id);

	}
	
	public function deleteset($what) {
	
		if (false === ($updraftplus_admin = $this->_load_ud_admin()) || false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');

		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');
	
		$results = $updraftplus_admin->delete_set($what);
	
		$get_history_opts = isset($what['get_history_opts']) ? $what['get_history_opts'] : array();
	
		$backup_history = UpdraftPlus_Backup_History::get_history();
	
		$results['history'] = $updraftplus_admin->settings_downloading_and_restoring($backup_history, true, $get_history_opts);
		
		$results['count_backups'] = count($backup_history);
	
		return $results;
	
	}
	
	/**
	 * Slightly misnamed - this doesn't always rescan, but it does always return the history status (possibly after a rescan)
	 *
	 * @param  string $what speific string to scan
	 * @return array retuns an array of history statuses
	 */
	public function rescan($what) {

		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');
		
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');
		
		$remotescan = ('remotescan' == $what);
		$rescan = ($remotescan || 'rescan' == $what);
		
		$history_status = $updraftplus_admin->get_history_status($rescan, $remotescan);

		return $history_status;
		
	}
	
	public function get_settings($options) {
		global $updraftplus;
		if (false === ($updraftplus_admin = $this->_load_ud_admin()) || false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');
		
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');
		
		ob_start();
		$updraftplus_admin->settings_formcontents($options);
		$output = ob_get_contents();
		ob_end_clean();
		
		$remote_storage_options_and_templates = $updraftplus->get_remote_storage_options_and_templates();
		return array(
			'settings' => $output,
			'remote_storage_options' => $remote_storage_options_and_templates['options'],
			'remote_storage_templates' => $remote_storage_options_and_templates['templates'],
			'meta' => apply_filters('updraftplus_get_settings_meta', array()),
			'updraftplus_version' => $updraftplus->version,
		);
		
	}
	
	public function test_storage_settings($test_data) {
	
		if (false === ($updraftplus_admin = $this->_load_ud_admin()) || false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');
		
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');
	
		ob_start();
		$updraftplus_admin->do_credentials_test($test_data);
		$output = ob_get_contents();
		ob_end_clean();
	
		return array(
			'output' => $output,
		);
	
	}
	
	public function extradb_testconnection($info) {
	
		if (false === ($updraftplus_admin = $this->_load_ud_admin()) || false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');
		
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');
	
		$results = apply_filters('updraft_extradb_testconnection_go', array(), $info);
	
		return $results;
	
	}
	
	/**
	 * This method will make a call to the methods responsible for recounting the quota in the UpdraftVault account
	 *
	 * @param  array $params - an array of parameters such as a instance_id
	 * @return string - the result of the call
	 */
	public function vault_recountquota($params = array()) {
		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');

		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');
		
		$instance_id = empty($params['instance_id']) ? '' : $params['instance_id'];

		$vault = $updraftplus_admin->get_updraftvault($instance_id);

		return $vault->ajax_vault_recountquota(false);
	}
	
	/**
	 * This method will make a call to the methods responsible for creating a connection to UpdraftVault
	 *
	 * @param  array $credentials - an array of parameters such as the user credentials and instance_id
	 * @return string - the result of the call
	 */
	public function vault_connect($credentials) {
	
		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');
		
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');

		$instance_id = empty($credentials['instance_id']) ? '' : $credentials['instance_id'];

		return $updraftplus_admin->get_updraftvault($instance_id)->ajax_vault_connect(false, $credentials);
	
	}
	
	/**
	 * This method will make a call to the methods responsible for removing a connection to UpdraftVault
	 *
	 * @param array $params - an array of parameters such as a instance_id
	 * @return string - the result of the call
	 */
	public function vault_disconnect($params = array()) {
	
		if (false === ($updraftplus_admin = $this->_load_ud_admin()) || false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');
		
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');

		$echo_results = empty($params['immediate_echo']) ? false : true;

		$instance_id = empty($params['instance_id']) ? '' : $params['instance_id'];
		
		$results = (array) $updraftplus_admin->get_updraftvault($instance_id)->ajax_vault_disconnect($echo_results);

		return $results;
	
	}
	
	/**
	 * A handler method to call the UpdraftPlus admin save settings method. It will check if the settings passed to it are in the format of a string if so it converts it to an array otherwise just pass the array
	 *
	 * @param  String/Array $settings Settings to be saved to UpdraftPlus either in the form of a string ready to be converted to an array or already an array ready to be passed to the save settings function in UpdraftPlus.
	 * @return Array An Array response to be sent back
	 */
	public function save_settings($settings) {
	
		if (false === ($updraftplus_admin = $this->_load_ud_admin()) || false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');
		
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');

		if (!empty($settings)) {

			if (is_string($settings)) {
				parse_str($settings, $settings_as_array);
			} elseif (is_array($settings)) {
				$settings_as_array = $settings;
			} else {
				return new WP_Error('invalid_settings');
			}
		}
		
		$results = $updraftplus_admin->save_settings($settings_as_array);

		return $results;
	
	}
	
	public function s3_newuser($data) {
		if (false === ($updraftplus_admin = $this->_load_ud_admin()) || false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');
		
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');
		$results = apply_filters('updraft_s3_newuser_go', array(), $data);
		
		return $results;
	}
	
	public function cloudfiles_newuser($data) {
	
		global $updraftplus_addon_cloudfilesenhanced;
		if (!is_a($updraftplus_addon_cloudfilesenhanced, 'UpdraftPlus_Addon_CloudFilesEnhanced')) {
			$data = array('e' => 1, 'm' => sprintf(__('%s add-on not found', 'updraftplus'), 'Rackspace Cloud Files'));
		} else {
			$data = $updraftplus_addon_cloudfilesenhanced->create_api_user($data);
		}
		
		if (0 === $data["e"]) {
			return $data;
		} else {
			return new WP_Error('error', '', $data);
		}
	}
	
	public function get_fragment($fragment) {
	
		if (false === ($updraftplus_admin = $this->_load_ud_admin()) || false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');
		
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');

		if (is_array($fragment)) {
			$data = $fragment['data'];
			$fragment = $fragment['fragment'];
		}
		
		$error = false;
		
		switch ($fragment) {
		
			case 'last_backup_html':
			$output = $updraftplus_admin->last_backup_html();
				break;
		
			case 's3_new_api_user_form':
			ob_start();
			do_action('updraft_s3_print_new_api_user_form', false);
			$output = ob_get_contents();
			ob_end_clean();
				break;
				
			case 'cloudfiles_new_api_user_form':
			global $updraftplus_addon_cloudfilesenhanced;
			if (!is_a($updraftplus_addon_cloudfilesenhanced, 'UpdraftPlus_Addon_CloudFilesEnhanced')) {
					$error = true;
					$output = 'cloudfiles_addon_not_found';
			} else {
				$output = array(
					'accounts' => $updraftplus_addon_cloudfilesenhanced->account_options(),
					'regions' => $updraftplus_addon_cloudfilesenhanced->region_options(),
				);
			}
				break;
				
			case 'backupnow_modal_contents':
			$updraft_dir = $updraftplus->backups_dir_location();
			if (!$updraftplus->really_is_writable($updraft_dir)) {
					$output = array('error' => true, 'html' => __("The 'Backup Now' button is disabled as your backup directory is not writable (go to the 'Settings' tab and find the relevant option).", 'updraftplus'));
			} else {
								$output = array('html' => $updraftplus_admin->backupnow_modal_contents());
			}
				break;
			
			case 'panel_download_and_restore':
			$backup_history = UpdraftPlus_Backup_History::get_history();
			if (empty($backup_history)) {
				UpdraftPlus_Backup_History::rebuild_backup_history();
				$backup_history = UpdraftPlus_Backup_History::get_history();
			}
				
			$output = $updraftplus_admin->settings_downloading_and_restoring($backup_history, true, $data);
				break;
			
			case 'disk_usage':
			$output = $updraftplus_admin->get_disk_space_used($data);
				break;
			default:
			// We just return a code - translation is done on the other side
			$output = 'ud_get_fragment_could_not_return';
			$error = true;
				break;
		}
		
		if (!$error) {
			return array(
				'output' => $output,
			);
		} else {
			return new WP_Error('get_fragment_error', '', $output);
		}
		
	}
	
	/**
	 * This gets the http_get function from admin to grab information on a url
	 *
	 * @param  string $uri URL to be used
	 * @return array returns response from specific URL
	 */
	public function http_get($uri) {
		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');

		if (empty($uri)) {
			return new WP_Error('error', '', 'no_uri');
		}
		
		$response = $updraftplus_admin->http_get($uri, false);
		$response_decode = json_decode($response);

		if (isset($response_decode->e)) {
		  return new WP_Error('error', '', htmlspecialchars($response_decode->e));
		}

		return array('status' => $response_decode->code, 'response' => $response_decode->html_response);
	}

	/**
	 * This gets the http_get function from admin to grab cURL information on a url
	 *
	 * @param  string $uri URL to be used
	 * @return array
	 */
	public function http_get_curl($uri) {
		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');

		if (empty($uri)) {
			return new WP_Error('error', '', 'no_uri');
		}
		
		if (!function_exists('curl_exec')) {
			return new WP_Error('error', '', 'no_curl');
		}
		
		$response_encode = $updraftplus_admin->http_get($uri, true);
		$response_decode = json_decode($response_encode);

		$response = 'Curl Info: ' . $response_decode->verb
					.'Response: ' . $response_decode->response;

		if (false === $response_decode->response) {
			return new WP_Error('error', '', array(
				'error' => htmlspecialchars($response_decode->e),
				"status" => $response_decode->status,
				"log" => htmlspecialchars($response_decode->verb)
			));
		}
		
		return array(
			'response'=> htmlspecialchars(substr($response, 0, 2048)),
			'status'=> $response_decode->status,
			'log'=> htmlspecialchars($response_decode->verb)
		);
	}

	/**
	 * Display raw backup and file list
	 *
	 * @return string
	 */
	public function show_raw_backup_and_file_list() {
		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');

		/*
			Need to remove the pre tags as the modal assumes a <pre> is for a new box.
			This cause issues specifically with fetch log events. Do this by passing true
			to the method show_raw_backups
		 */
		
		$response = $updraftplus_admin->show_raw_backups(true);

		return $response['html'];
	}

	public function reset_site_id() {
		if (false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');
		delete_site_option('updraftplus-addons_siteid');
		return $updraftplus->siteid();
	}

	public function search_replace($query) {

		if (!class_exists('UpdraftPlus_Addons_Migrator')) {
			return new WP_Error('error', '', 'no_class_found');
		}
		
		global $updraftplus_addons_migrator;
		
		if (!is_a($updraftplus_addons_migrator, 'UpdraftPlus_Addons_Migrator')) {
			return new WP_Error('error', 'no_object_found');
		}

		$_POST = $query;
		
		ob_start();

		do_action('updraftplus_adminaction_searchreplace', $query);
		
		$response = array('log' => ob_get_clean());
		
		return $response;
	}

	public function change_lock_settings($data) {
		global $updraftplus_addon_lockadmin;
		
		if (!class_exists('UpdraftPlus_Addon_LockAdmin')) {
			return new WP_Error('error', '', 'no_class_found');
		}
		
		if (!is_a($updraftplus_addon_lockadmin, "UpdraftPlus_Addon_LockAdmin")) {
			return new WP_Error('error', '', 'no_object_found');
		}

		$session_length = empty($data["session_length"]) ? '' : $data["session_length"];
		$password 		= empty($data["password"]) ? '' : $data["password"];
		$old_password 	= empty($data["old_password"]) ? '' : $data["old_password"];
		$support_url 	= $data["support_url"];
		
		$user = wp_get_current_user();
		if (0 == $user->ID) {
			return new WP_Error('no_user_found');
		}
		
		$options = $updraftplus_addon_lockadmin->return_opts();

		if ($old_password == $options['password']) {
			
			$options['password'] = (string) $password;
			$options['support_url'] = (string) $support_url;
			$options['session_length'] = (int) $session_length;
			UpdraftPlus_Options::update_updraft_option('updraft_adminlocking', $options);
						
			return "lock_changed";
		} else {
			return new WP_Error('error', '', 'wrong_old_password');
		}
	}

	public function delete_key($key_id) {
		global $updraftplus_updraftcentral_main;

		if (!is_a($updraftplus_updraftcentral_main, 'UpdraftPlus_UpdraftCentral_Main')) {
			return new WP_Error('error', '', 'UpdraftPlus_UpdraftCentral_Main object not found');
		}
		
		$response = $updraftplus_updraftcentral_main->delete_key($key_id);
		return $response;
		
	}
	
	public function create_key($data) {
		global $updraftplus_updraftcentral_main;

		if (!is_a($updraftplus_updraftcentral_main, 'UpdraftPlus_UpdraftCentral_Main')) {
			return new WP_Error('error', '', 'UpdraftPlus_UpdraftCentral_Main object not found');
		}
		
		$response = call_user_func(array($updraftplus_updraftcentral_main, 'create_key'), $data);
		
		return $response;
	}
	
	public function fetch_log($data) {
		global $updraftplus_updraftcentral_main;

		if (!is_a($updraftplus_updraftcentral_main, 'UpdraftPlus_UpdraftCentral_Main')) {
			return new WP_Error('error', '', 'UpdraftPlus_UpdraftCentral_Main object not found');
		}
		
		$response = call_user_func(array($updraftplus_updraftcentral_main, 'get_log'), $data);
		return $response;
	}

	/**
	 * A handler method to call the UpdraftPlus admin auth_remote_method
	 *
	 * @param Array - $data It consists of below key elements:
	 *                $remote_method - Remote storage service
	 *                $instance_id - Remote storage instance id
	 * @return Array An Array response to be sent back
	 */
	public function auth_remote_method($data) {
		if (false === ($updraftplus_admin = $this->_load_ud_admin()) || false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');
		$response = $updraftplus_admin->auth_remote_method($data);
		return $response;
	}

	/**
	 * A handler method to call the UpdraftPlus admin deauth_remote_method
	 *
	 * @param Array - $data It consists of below key elements:
	 *                $remote_method - Remote storage service
	 *                $instance_id - Remote storage instance id
	 * @return Array An Array response to be sent back
	 */
	public function deauth_remote_method($data) {
		if (false === ($updraftplus_admin = $this->_load_ud_admin()) || false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');
		$response = $updraftplus_admin->deauth_remote_method($data);
		return $response;
	}
	
	/**
	 * A handler method to call the UpdraftPlus admin wipe settings method
	 *
	 * @return Array An Array response to be sent back
	 */
	public function wipe_settings() {
		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');
		
		if (!UpdraftPlus_Options::user_can_manage()) return new WP_Error('updraftplus_permission_denied');

		// pass false to this method so that it does not remove the UpdraftCentral key
		$response = $updraftplus_admin->updraft_wipe_settings(false);

		return $response;
	}

	/**
	 * Retrieves backup information (next scheduled backups, last backup jobs and last log message)
	 * for UpdraftCentral consumption
	 *
	 * @return Array An array containing the results of the backup information retrieval
	 */
	public function get_backup_info() {
		try {
			
			// load global updraftplus admin
			if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');

			ob_start();
			$updraftplus_admin->next_scheduled_backups_output();
			$next_scheduled_backups = ob_get_clean();

			$response = array(
				'next_scheduled_backups' => $next_scheduled_backups,
				'last_backup_job' => $updraftplus_admin->last_backup_html(),
				'last_log_message' => UpdraftPlus_Options::get_updraft_lastmessage()
			);

			$updraft_last_backup = UpdraftPlus_Options::get_updraft_option('updraft_last_backup', false);
			$backup_history = UpdraftPlus_Backup_History::get_history();
			
			if (false !== $updraft_last_backup && !empty($backup_history)) {
				$backup_nonce = $updraft_last_backup['backup_nonce'];

				$response['backup_nonce'] = $backup_nonce;
				$response['log'] = $this->get_log($backup_nonce);
			}

		} catch (Exception $e) {
			$response = array('error' => true, 'message' => $e->getMessage());
		}

		return $response;
	}
}
