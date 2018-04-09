<?php

if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed.');

require_once(UPDRAFTPLUS_DIR.'/methods/s3.php');

/**
 * Converted to multi-options (Feb 2017-) and previous options conversion removed: Yes
 */
class UpdraftPlus_BackupModule_dreamobjects extends UpdraftPlus_BackupModule_s3 {

	// When new endpoint introduced in future, Please add it here and also add it as hard coded option for endpoint dropdown in self::get_partial_configuration_template_for_endpoint()
	private $dreamobjects_endpoints = array('objects-us-west-1.dream.io');

	protected $use_v4 = false;

	protected function set_region($obj, $region = '', $bucket_name = '') {
		$config = $this->get_config();
		$endpoint = ('' != $region && 'n/a' != $region) ? $region : $config['endpoint'];
		global $updraftplus;
		if ($updraftplus->backup_time) $updraftplus->log("Set endpoint: $endpoint");
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
		);
	}

	/**
	 * Retrieve specific options for this remote storage module
	 *
	 * @return Array - an array of options
	 */
	protected function get_config() {
		$opts = $this->get_options();
		$opts['whoweare'] = 'DreamObjects';
		$opts['whoweare_long'] = 'DreamObjects';
		$opts['key'] = 'dreamobjects';
		if (empty($opts['endpoint'])) $opts['endpoint'] = $this->dreamobjects_endpoints[0];
		return $opts;
	}

	/**
	 * Get the pre configuration template
	 *
	 * @return String - the template
	 */
	public function get_pre_configuration_template() {
		$this->get_pre_configuration_template_engine('dreamobjects', 'DreamObjects', 'DreamObjects', 'DreamObjects', 'https://panel.dreamhost.com/index.cgi?tree=storage.dreamhostobjects', '<a href="https://dreamhost.com/cloud/dreamobjects/"><img alt="DreamObjects" src="'.UPDRAFTPLUS_URL.'/images/dreamobjects_logo-horiz-2013.png"></a>');
	}

	/**
	 * Get the configuration template
	 *
	 * @return String - the template, ready for substitutions to be carried out
	 */
	public function get_configuration_template() {
		return $this->get_configuration_template_engine('dreamobjects', 'DreamObjects', 'DreamObjects', 'DreamObjects', 'https://panel.dreamhost.com/index.cgi?tree=storage.dreamhostobjects', '<a href="https://dreamhost.com/cloud/dreamobjects/"><img alt="DreamObjects" src="'.UPDRAFTPLUS_URL.'/images/dreamobjects_logo-horiz-2013.png"></a>');
	}
	
	/**
	 * Get handlebar partial template string for endpoint of s3 compatible remote storage method. Other child class can extend it.
	 *
	 * @return string the partial template string
	 */
	protected function get_partial_configuration_template_for_endpoint() {
		// When new endpoint introduced in future, Please add it  as hard coded option for below  endpoint dropdown and also add as array value in private $dreamobjects_endpoints variable
		return '<tr class="'.$this->get_css_classes().'">
					<th>'.sprintf(__('%s end-point', 'updraftplus'), 'DreamObjects').'</th>
					<td>
						<select data-updraft_settings_test="endpoint" '.$this->output_settings_field_name_and_id('endpoint', true).' style="width: 360px">							
							{{#each dreamobjects_endpoints}}
								<option value="{{this}}" {{#ifeq ../endpoint this}}selected="selected"{{/ifeq}}>{{this}}</option>
							{{/each}}				
						</select>
					</td>
				</tr>';
	}
	
	/**
	 * Modifies handerbar template options
	 *
	 * @param array $opts
	 * @return array - Modified handerbar template options
	 */
	public function transform_options_for_template($opts) {
		$opts['endpoint'] = !empty($opts['endpoint']) ? $opts['endpoint'] : '';
		$opts['dreamobjects_endpoints'] = $this->dreamobjects_endpoints;
		return $opts;
	}

	public function credentials_test($posted_settings) {
		$this->credentials_test_engine($this->get_config(), $posted_settings);
	}
}
