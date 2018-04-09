<?php

if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed.');

require_once(UPDRAFTPLUS_DIR.'/methods/s3.php');

/**
 * Converted to multi-options (Feb 2017-) and previous options conversion removed: Yes
 */
class UpdraftPlus_BackupModule_s3generic extends UpdraftPlus_BackupModule_s3 {

	protected $use_v4 = false;

	protected function set_region($obj, $region = '', $bucket_name = '') {
		$config = $this->get_config();
		$endpoint = ('' != $region && 'n/a' != $region) ? $region : $config['endpoint'];
		$log_message = "Set endpoint: $endpoint";
		if (is_string($endpoint) && preg_match('/^(.*):(\d+)$/', $endpoint, $matches)) {
			$endpoint = $matches[1];
			$port = $matches[2];
			$log_message .= ", port=$port";
			$obj->setPort($port);
		}
		global $updraftplus;
		if ($updraftplus->backup_time) $updraftplus->log($log_message);
		$obj->setEndpoint($endpoint);
	}

	/**
	 * This method overrides the parent method and lists the supported features of this remote storage option.
	 *
	 * @return Array - an array of supported features (any features not mentioned are asuumed to not be supported)
	 */
	public function get_supported_features() {
		// This options format is handled via only accessing options via $this->get_options()
		return array('multi_options', 'config_templates', 'multi_storage');
	}

	/**
	 * Retrieve default options for this remote storage module.
	 *
	 * @return Array - an array of options
	 */
	public function get_default_options() {
		return array(
			'accesskey' => '',
			'secretkey' => '',
			'path' => '',
			'endpoint' => '',
		);
	}

	/**
	 * Retrieve specific options for this remote storage module
	 *
	 * @return Array - an array of options
	 */
	protected function get_config() {
		$opts = $this->get_options();
		$opts['whoweare'] = 'S3';
		$opts['whoweare_long'] = __('S3 (Compatible)', 'updraftplus');
		$opts['key'] = 's3generic';
		return $opts;
	}

	/**
	 * Get the pre configuration template
	 *
	 * @return String - the template
	 */
	public function get_pre_configuration_template() {
		$this->get_pre_configuration_template_engine('s3generic', 'S3', __('S3 (Compatible)', 'updraftplus'), 'S3', '', '');
	}

	/**
	 * Get the configuration template
	 *
	 * @return String - the template, ready for substitutions to be carried out
	 */
	public function get_configuration_template() {
		// 5th parameter = control panel URL
		// 6th = image HTML
		return $this->get_configuration_template_engine('s3generic', 'S3', __('S3 (Compatible)', 'updraftplus'), 'S3', '', '');
	}
	
	/**
	 * Modifies handerbar template options
	 * The function require because It should override parent class's UpdraftPlus_BackupModule_s3::transform_options_for_template() functionality with no operation.
	 *
	 * @param array $opts
	 * @return array - Modified handerbar template options
	 */
	public function transform_options_for_template($opts) {
		return $opts;
	}
	
	/**
	 * Get handlebar partial template string for endpoint of s3 compatible remote storage method. Other child class can extend it.
	 *
	 * @return string the partial template string
	 */
	protected function get_partial_configuration_template_for_endpoint() {
		return '<tr class="'.$this->get_css_classes().'">
					<th>'.sprintf(__('%s end-point', 'updraftplus'), 'S3').'</th>
					<td>
						<input data-updraft_settings_test="endpoint" type="text" style="width: 360px" '.$this->output_settings_field_name_and_id('endpoint', true).' value="{{endpoint}}" />
				</tr>';
	}

	public function credentials_test($posted_settings) {
		$this->credentials_test_engine($this->get_config(), $posted_settings);
	}
}
