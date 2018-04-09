<?php

if (!defined('UPDRAFTPLUS_DIR')) die('No access.');

/**
 * Handles UpdraftCentral Plugin Commands which basically handles
 * the installation and activation of a plugin
 */
class UpdraftCentral_Plugin_Commands extends UpdraftCentral_Commands {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->_admin_include('plugin.php', 'file.php', 'template.php', 'class-wp-upgrader.php', 'plugin-install.php');
	}

	/**
	 * Checks whether the plugin is currently installed and activated.
	 *
	 * @param array $query Parameter array containing the name of the plugin to check
	 * @return array Contains the result of the current process
	 */
	public function is_plugin_installed($query) {

		if (!isset($query['plugin']))
			return $this->_response(array('error' => true, 'message' => 'plugin_name_required', 'values' => array()));


		$result = $this->get_plugin_info($query['plugin']);
		return $this->_response($result);
	}

	/**
	 * Activates the plugin
	 *
	 * @param array $query Parameter array containing the name of the plugin to activate
	 * @return array Contains the result of the current process
	 */
	public function activate_plugin($query) {

		if (!isset($query['plugin']))
			return $this->_response(array('error' => true, 'message' => 'plugin_name_required', 'values' => array()));

		if (!current_user_can('activate_plugins'))
			return $this->_response(array('error' => true, 'message' => 'plugin_insufficient_permission', 'values' => array()));


		$info = $this->get_plugin_info($query['plugin']);
		if ($info['installed']) {
			$activate = activate_plugin($info['plugin_path']);

			if (is_wp_error($activate)) {
				$result = array('error' => true, 'message' => 'generic_response_error', 'values' => array($activate->get_error_message()));
			} else {
				$result = array('activated' => true);
			}
		} else {
			$result = array('error' => true, 'message' => 'plugin_not_installed', 'values' => array($query['plugin']));
		}

		return $this->_response($result);
	}

	/**
	 * Download, install and activates the plugin
	 *
	 * @param array $query Parameter array containing the filesystem credentials entered by the user along with the plugin name and slug
	 * @return array Contains the result of the current process
	 */
	public function install_activate_plugin($query) {

		if (!isset($query['plugin']))
			return $this->_response(array('error' => true, 'message' => 'plugin_name_required', 'values' => array()));

		if (!isset($query['slug']))
			return $this->_response(array('error' => true, 'message' => 'plugin_slug_required', 'values' => array()));

		if (!current_user_can('install_plugins') || !current_user_can('activate_plugins'))
			return $this->_response(array('error' => true, 'message' => 'plugin_insufficient_permission', 'values' => array()));


		if (!empty($query) && isset($query['filesystem_credentials'])) {
			parse_str($query['filesystem_credentials'], $filesystem_credentials);
			if (is_array($filesystem_credentials)) {
				foreach ($filesystem_credentials as $key => $value) {
					// Put them into $_POST, which is where request_filesystem_credentials() checks for them.
					$_POST[$key] = $value;
				}
			}
		}

		$api = plugins_api('plugin_information', array(
			'slug' => $query['slug'],
			'fields' => array(
				'short_description' => false,
				'sections' => false,
				'requires' => false,
				'rating' => false,
				'ratings' => false,
				'downloaded' => false,
				'last_updated' => false,
				'added' => false,
				'tags' => false,
				'compatibility' => false,
				'homepage' => false,
				'donate_link' => false,
			)
		));

		if (is_wp_error($api)) {
			$result = array('error' => true, 'message' => 'generic_response_error', 'values' => array($api->get_error_message()));
		} else {
			$info = $this->get_plugin_info($query['plugin']);
			$installed = $info['installed'];

			if (!$installed) {
				// WP < 3.7
				if (!class_exists('Automatic_Upgrader_Skin')) include_once(UPDRAFTPLUS_DIR.'/central/classes/class-automatic-upgrader-skin.php');

				$skin = new Automatic_Upgrader_Skin();
				$upgrader = new Plugin_Upgrader($skin);

				$download_link = $api->download_link;
				$installed = $upgrader->install($download_link);
			}

			if (!$installed) {
				$result = array('error' => true, 'message' => 'plugin_install_failed', 'values' => array($query['plugin']));
			} else {
				// Here, we're pulling the information one more time to verify the installation and to
				// extract the plugin_path that will be used to activate the plugin in case it did not
				// get activated after the installation.
				$info = $this->get_plugin_info($query['plugin']);

				if (!$info['active']) {
					$activate = activate_plugin($info['plugin_path']);

					if (is_wp_error($activate)) {
						$result = array('error' => true, 'message' => 'generic_response_error', 'values' => array($activate->get_error_message()));
					} else {
						$result = array('installed' => true);
					}
				} else {
					$result = array('installed' => true);
				}
			}
		}

		return $this->_response($result);
	}

	/**
	 * Gets the plugin information along with its active and install status
	 *
	 * @internal
	 * @param array $plugin The name of the plugin to pull the information from
	 * @return array Contains the plugin information
	 */
	private function get_plugin_info($plugin) {

		$info = array(
			'active' => false,
			'installed' => false
		);
		
		// Gets all plugins available.
		$get_plugins = get_plugins();

		// Loops around each plugin available.
		foreach ($get_plugins as $key => $value) {
			// If the plugin name matches that of the specified name, it will gather details.
			if ($value['Name'] === $plugin) {
				$info['installed'] = true;
				$info['active'] = is_plugin_active($key);
				$info['plugin_path'] = $key;
				$info['data'] = $value;
				break;
			}
		}

		return $info;
	}
}
