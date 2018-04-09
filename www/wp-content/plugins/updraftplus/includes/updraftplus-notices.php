<?php

if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed');

if (!class_exists('Updraft_Notices')) require_once(UPDRAFTPLUS_DIR.'/includes/updraft-notices.php');

class UpdraftPlus_Notices extends Updraft_Notices {

	protected static $_instance = null;

	private $initialized = false;

	protected $notices_content = array();
	
	protected $self_affiliate_id = 212;

	public static function instance() {
		if (empty(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	protected function populate_notices_content() {
		
		$parent_notice_content = parent::populate_notices_content();

		$child_notice_content = array(
			1 => array(
				'prefix' => __('UpdraftPlus Premium:', 'updraftplus'),
				'title' => __('Support', 'updraftplus'),
				'text' => __('Enjoy professional, fast, and friendly help whenever you need it with Premium.', 'updraftplus'),
				'image' => 'notices/support.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $this->dashboard_top_or_report,
			),
			2 => array(
				'prefix' => __('UpdraftPlus Premium:', 'updraftplus'),
				'title' => __('UpdraftVault storage', 'updraftplus'),
				'text' => __('The ultimately secure and convenient place to store your backups.', 'updraftplus'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => 'https://updraftplus.com/landing/vault',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $this->dashboard_top_or_report,
			),
			3 => array(
				'prefix' => __('UpdraftPlus Premium:', 'updraftplus'),
				'title' => __('enhanced remote storage options', 'updraftplus'),
				'text' => __('Enhanced storage options for Dropbox, Google Drive and S3. Plus many more options.', 'updraftplus'),
				'image' => 'addons-images/morestorage.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $this->dashboard_top_or_report,
			),
			4 => array(
				'prefix' => __('UpdraftPlus Premium:', 'updraftplus'),
				'title' => __('advanced options', 'updraftplus'),
				'text' => __('Secure multisite installation, advanced reporting and much more.', 'updraftplus'),
				'image' => 'addons-images/reporting.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $this->dashboard_top_or_report,
			),
			5 => array(
				'prefix' => __('UpdraftPlus Premium:', 'updraftplus'),
				'title' => __('secure your backups', 'updraftplus'),
				'text' => __('Add SFTP to send your data securely, lock settings and encrypt your database backups for extra security.', 'updraftplus'),
				'image' => 'addons-images/lockadmin.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $this->dashboard_top_or_report,
			),
			6 => array(
				'prefix' => __('UpdraftPlus Premium:', 'updraftplus'),
				'title' => __('easily migrate or clone your site in minutes', 'updraftplus'),
				'text' => __('Copy your site to another domain directly. Includes find-and-replace tool for database references.', 'updraftplus'),
				'image' => 'addons-images/migrator.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $this->anywhere,
			),
			7 => array(
				'prefix' => '',
				'title' => __('Introducing UpdraftCentral', 'updraftplus'),
				'text' => __('UpdraftCentral is a highly efficient way to manage, update and backup multiple websites from one place.', 'updraftplus'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => 'https://updraftcentral.com',
				'button_meta' => 'updraftcentral',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $this->dashboard_top_or_report,
			),
			8 => array(
				'prefix' => '',
				'title' => __('Like UpdraftPlus and can spare one minute?', 'updraftplus'),
				'text' => __('Please help UpdraftPlus by giving a positive review at wordpress.org.', 'updraftplus'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => 'https://wordpress.org/support/plugin/updraftplus/reviews/?rate=5#new-post',
				'button_meta' => 'review',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $this->anywhere,
			),
			9 => array(
				'prefix' => '',
				'title' => __('Do you use UpdraftPlus on multiple sites?', 'updraftplus'),
				'text' => __('Control all your WordPress installations from one place using UpdraftCentral remote site management!', 'updraftplus'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => 'https://updraftcentral.com',
				'button_meta' => 'updraftcentral',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $this->anywhere,
			),
			'translation_needed' => array(
				'prefix' => '',
				'title' => 'Can you translate? Want to improve UpdraftPlus for speakers of your language?',
				'text' => $this->url_start(true, 'updraftplus.com/translate/')."Please go here for instructions - it is easy.".$this->url_end(true, 'updraftplus.com/translate/'),
				'text_plain' => $this->url_start(false, 'updraftplus.com/translate/')."Please go here for instructions - it is easy.".$this->url_end(false, 'updraftplus.com/translate/'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => false,
				'dismiss_time' => false,
				'supported_positions' => $this->anywhere,
				'validity_function' => 'translation_needed',
			),
			'social_media' => array(
				'prefix' => '',
				'title' => __('UpdraftPlus is on social media - check us out!', 'updraftplus'),
				'text' => $this->url_start(true, 'twitter.com/updraftplus', true).__('Twitter', 'updraftplus').$this->url_end(true, 'twitter.com/updraftplus', true).' - '.$this->url_start(true, 'facebook.com/updraftplus', true).__('Facebook', 'updraftplus').$this->url_end(true, 'facebook.com/updraftplus', true).' - '.$this->url_start(true, 'plus.google.com/u/0/b/112313994681166369508/112313994681166369508/about', true).__('Google+', 'updraftplus').$this->url_end(true, 'plus.google.com/u/0/b/112313994681166369508/112313994681166369508/about', true).' - '.$this->url_start(true, 'www.linkedin.com/company/updraftplus', true).__('LinkedIn', 'updraftplus').$this->url_end(true, 'www.linkedin.com/company/updraftplus', true),
				'text_plain' => $this->url_start(false, 'twitter.com/updraftplus', true).__('Twitter', 'updraftplus').$this->url_end(false, 'twitter.com/updraftplus', true).' - '.$this->url_start(false, 'facebook.com/updraftplus', true).__('Facebook', 'updraftplus').$this->url_end(false, 'facebook.com/updraftplus', true).' - '.$this->url_start(false, 'plus.google.com/u/0/b/112313994681166369508/112313994681166369508/about', true).__('Google+', 'updraftplus').$this->url_end(false, 'plus.google.com/u/0/b/112313994681166369508/112313994681166369508/about', true).' - '.$this->url_start(false, 'www.linkedin.com/company/updraftplus', true).__('LinkedIn', 'updraftplus').$this->url_end(false, 'www.linkedin.com/company/updraftplus', true),
				'image' => 'notices/updraft_logo.png',
				'dismiss_time' => false,
				'supported_positions' => $this->anywhere,
			),
			'newsletter' => array(
				'prefix' => '',
				'title' => __('UpdraftPlus Newsletter', 'updraftplus'),
				'text' => __("Follow this link to sign up for the UpdraftPlus newsletter.", 'updraftplus'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => 'https://updraftplus.com/newsletter-signup',
				'button_meta' => 'signup',
				'supported_positions' => $this->anywhere,
				'dismiss_time' => false
			),
			'subscribe_blog' => array(
				'prefix' => '',
				'title' => __('UpdraftPlus Blog - get up-to-date news and offers', 'updraftplus'),
				'text' => $this->url_start(true, 'updraftplus.com/news/').__("Blog link", 'updraftplus').$this->url_end(true, 'updraftplus.com/news/').' - '.$this->url_start(true, 'feeds.feedburner.com/UpdraftPlus').__("RSS link", 'updraftplus').$this->url_end(true, 'feeds.feedburner.com/UpdraftPlus'),
				'text_plain' => $this->url_start(false, 'updraftplus.com/news/').__("Blog link", 'updraftplus').$this->url_end(false, 'updraftplus.com/news/').' - '.$this->url_start(false, 'feeds.feedburner.com/UpdraftPlus').__("RSS link", 'updraftplus').$this->url_end(false, 'feeds.feedburner.com/UpdraftPlus'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => false,
				'supported_positions' => $this->anywhere,
				'dismiss_time' => false
			),
			'check_out_updraftplus_com' => array(
				'prefix' => '',
				'title' => __('UpdraftPlus Blog - get up-to-date news and offers', 'updraftplus'),
				'text' => $this->url_start(true, 'updraftplus.com/news/').__("Blog link", 'updraftplus').$this->url_end(true, 'updraftplus.com/news/').' - '.$this->url_start(true, 'feeds.feedburner.com/UpdraftPlus').__("RSS link", 'updraftplus').$this->url_end(true, 'feeds.feedburner.com/UpdraftPlus'),
				'text_plain' => $this->url_start(false, 'updraftplus.com/news/').__("Blog link", 'updraftplus').$this->url_end(false, 'updraftplus.com/news/').' - '.$this->url_start(false, 'feeds.feedburner.com/UpdraftPlus').__("RSS link", 'updraftplus').$this->url_end(false, 'feeds.feedburner.com/UpdraftPlus'),
				'image' => 'notices/updraft_logo.png',
				'button_link' => false,
				'supported_positions' => $this->dashboard_bottom_or_report,
				'dismiss_time' => false
			),
			'autobackup' => array(
				'prefix' => '',
				'title' => __('Be safe with an automatic backup', 'updraftplus'),
				'text' => __('UpdraftPlus Premium can automatically backup your plugins/themes/database before you update, without you needing to remember.', 'updraftplus'),
				'image' => 'addons-images/autobackup.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismissautobackup',
				'supported_positions' => $this->autobackup_bottom_or_report,
			),
			'wp-optimize' => array(
				'prefix' => '',
				'title' => 'WP-Optimize',
				'text' => __("After you've backed up your database, we recommend you install our WP-Optimize plugin to streamline it for better website performance.", "updraftplus"),
				'image' => 'notices/wp_optimize_logo.png',
				'button_link' => 'https://wordpress.org/plugins/wp-optimize/',
				'button_meta' => 'wp-optimize',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $this->anywhere,
				'validity_function' => 'wp_optimize_installed',
			),
			'keyy' => array(
				'prefix' => '',
				'title' => 'Keyy',
				'text' => __("Instant and secure logon with a wave of your phone.", "updraftplus") . ' ' . $this->url_start(true, 'getkeyy.com') . __("No more forgotten passwords. Find out more about our revolutionary new WordPress plugin", 'updraftplus') . $this->url_end(true, 'getkeyy.com'),
				'image' => 'notices/keyy_logo.png',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $this->anywhere,
				'validity_function' => 'keyy_installed',
			),
			'metaslider' => array(
				'prefix' => '',
				'title' => "MetaSlider: The world's #1 slider plugin from the makers of UpdraftPlus",
				'text' => __("With Metaslider, you can easily add style and flare with beautifully-designed sliders.", "updraftplus") . ' ' . $this->url_start(true, 'metaslider.com'),
				'image' => 'notices/metaslider_logo.png',
				'dismiss_time' => 'dismiss_notice',
				'supported_positions' => $this->anywhere,
				'validity_function' => 'metaslider_installed',
			),
			
			// The sale adverts content starts here
			'blackfriday' => array(
				'prefix' => '',
				'title' => __('Black Friday - 20% off UpdraftPlus Premium until November 30th', 'updraftplus'),
				'text' => __('To benefit, use this discount code:', 'updraftplus').' ',
				'image' => 'notices/black_friday.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_season',
				'discount_code' => 'blackfridaysale2017',
				'valid_from' => '2017-11-20 00:00:00',
				'valid_to' => '2017-11-30 23:59:59',
				'supported_positions' => $this->dashboard_top_or_report,
			),
			'christmas' => array(
				'prefix' => '',
				'title' => __('Christmas sale - 20% off UpdraftPlus Premium until December 25th', 'updraftplus'),
				'text' => __('To benefit, use this discount code:', 'updraftplus').' ',
				'image' => 'notices/christmas.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_season',
				'discount_code' => 'christmassale2017',
				'valid_from' => '2017-12-01 00:00:00',
				'valid_to' => '2017-12-25 23:59:59',
				'supported_positions' => $this->dashboard_top_or_report,
			),
			'newyear' => array(
				'prefix' => '',
				'title' => __('Happy New Year - 20% off UpdraftPlus Premium until January 1st', 'updraftplus'),
				'text' => __('To benefit, use this discount code:', 'updraftplus').' ',
				'image' => 'notices/new_year.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_season',
				'discount_code' => 'newyearsale2018',
				'valid_from' => '2017-12-26 00:00:00',
				'valid_to' => '2018-01-14 23:59:59',
				'supported_positions' => $this->dashboard_top_or_report,
			),
			'spring' => array(
				'prefix' => '',
				'title' => __('Spring sale - 20% off UpdraftPlus Premium until April 31st', 'updraftplus'),
				'text' => __('To benefit, use this discount code:', 'updraftplus').' ',
				'image' => 'notices/spring.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_season',
				'discount_code' => 'springsale2018',
				'valid_from' => '2018-04-01 00:00:00',
				'valid_to' => '2018-04-30 23:59:59',
				'supported_positions' => $this->dashboard_top_or_report,
			),
			'summer' => array(
				'prefix' => '',
				'title' => __('Summer sale - 20% off UpdraftPlus Premium until July 31st', 'updraftplus'),
				'text' => __('To benefit, use this discount code:', 'updraftplus').' ',
				'image' => 'notices/summer.png',
				'button_link' => 'https://updraftplus.com/landing/updraftplus-premium',
				'button_meta' => 'updraftplus',
				'dismiss_time' => 'dismiss_season',
				'discount_code' => 'summersale2018',
				'valid_from' => '2018-07-01 00:00:00',
				'valid_to' => '2018-07-31 23:59:59',
				'supported_positions' => $this->dashboard_top_or_report,
			)
		);

		return array_merge($parent_notice_content, $child_notice_content);
	}
	
	/**
	 * Call this method to setup the notices
	 */
	public function notices_init() {
		if ($this->initialized) return;
		$this->initialized = true;
		// parent::notices_init();
		$this->notices_content = (defined('UPDRAFTPLUS_NOADS_B') && UPDRAFTPLUS_NOADS_B) ? array() : $this->populate_notices_content();
		global $updraftplus;
		$enqueue_version = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? $updraftplus->version.'.'.time() : $updraftplus->version;
		$min_or_not = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

		wp_enqueue_style('updraftplus-notices-css',  UPDRAFTPLUS_URL.'/css/updraftplus-notices'.$min_or_not.'.css', array(), $enqueue_version);
	}

	protected function translation_needed($plugin_base_dir = null, $product_name = null) {
		return parent::translation_needed(UPDRAFTPLUS_DIR, 'updraftplus');
	}
	
	protected function wp_optimize_installed($plugin_base_dir = null, $product_name = null) {
		$wp_optimize_file = false;
		if (!function_exists('get_plugins')) include_once(ABSPATH.'wp-admin/includes/plugin.php');
		$plugins = get_plugins();

		foreach ($plugins as $key => $value) {
			if ('wp-optimize' == $value['TextDomain']) {
				return false;
			}
		}
		return true;
	}

	protected function keyy_installed($plugin_base_dir = null, $product_name = null) {
		$wp_optimize_file = false;
		if (!function_exists('get_plugins')) include_once(ABSPATH.'wp-admin/includes/plugin.php');
		$plugins = get_plugins();

		foreach ($plugins as $key => $value) {
			if ('keyy' == $value['TextDomain']) {
				return false;
			}
		}
		return true;
	}

	protected function metaslider_installed($plugin_base_dir = null, $product_name = null) {
		if (!function_exists('get_plugins')) include_once(ABSPATH.'wp-admin/includes/plugin.php');
		$plugins = get_plugins();

		foreach ($plugins as $key => $value) {
			if ('ml-slider' == $value['TextDomain']) {
				return false;
			}
		}
		return true;
	}

	protected function clef_2fa_installed($plugin_base_dir = null, $product_name = null) {

		if (!function_exists('get_plugins')) include_once(ABSPATH.'wp-admin/includes/plugin.php');

		$plugins = get_plugins();
		$clef_found = false;

		foreach ($plugins as $key => $value) {
			if ('wpclef' == $value['TextDomain']) {
				$clef_found = true;
			} elseif ('two-factor-authentication' == $value['TextDomain'] || 'two-factor-authentication-premium' == $value['TextDomain']) {
				return false;
			}
		}

		return $clef_found;
		
	}
	
	protected function url_start($html_allowed = false, $url, $https = false, $website_home = 'updraftplus.com') {
		return parent::url_start($html_allowed, $url, $https, $website_home);
	}

	protected function skip_seasonal_notices($notice_data) {
		global $updraftplus;

		$time_now = defined('UPDRAFTPLUS_NOTICES_FORCE_TIME') ? UPDRAFTPLUS_NOTICES_FORCE_TIME : time();
		// Do not show seasonal notices to people with an updraftplus.com version and no-addons yet
		if (!file_exists(UPDRAFTPLUS_DIR.'/udaddons') || $updraftplus->have_addons) {
			$valid_from = strtotime($notice_data['valid_from']);
			$valid_to = strtotime($notice_data['valid_to']);
			$dismiss = $this->check_notice_dismissed($notice_data['dismiss_time']);
			if (($time_now >= $valid_from && $time_now <= $valid_to) && !$dismiss) {
				// return true so that we return this notice to be displayed
				return true;
			}
		}
		
		return false;
	}
	
	protected function check_notice_dismissed($dismiss_time) {

		$time_now = defined('UPDRAFTPLUS_NOTICES_FORCE_TIME') ? UPDRAFTPLUS_NOTICES_FORCE_TIME : time();
	
		$notice_dismiss = ($time_now < UpdraftPlus_Options::get_updraft_option('dismissed_general_notices_until', 0));
		$seasonal_dismiss = ($time_now < UpdraftPlus_Options::get_updraft_option('dismissed_season_notices_until', 0));
		$autobackup_dismiss = ($time_now < UpdraftPlus_Options::get_updraft_option('updraftplus_dismissedautobackup', 0));

		$dismiss = false;

		if ('dismiss_notice' == $dismiss_time) $dismiss = $notice_dismiss;
		if ('dismiss_season' == $dismiss_time) $dismiss = $seasonal_dismiss;
		if ('dismissautobackup' == $dismiss_time) $dismiss = $autobackup_dismiss;

		return $dismiss;
	}

	protected function render_specified_notice($advert_information, $return_instead_of_echo = false, $position = 'top') {
	
		if ('bottom' == $position) {
			$template_file = 'bottom-notice.php';
		} elseif ('report' == $position) {
			$template_file = 'report.php';
		} elseif ('report-plain' == $position) {
			$template_file = 'report-plain.php';
		} else {
			$template_file = 'horizontal-notice.php';
		}
		
		/*
			Check to see if the updraftplus_com_link filter is being used, if it's not then add our tracking to the link.
		*/
	
		if (!has_filter('updraftplus_com_link') && isset($advert_information['button_link']) && false !== strpos($advert_information['button_link'], '//updraftplus.com')) {
			$advert_information['button_link'] = trailingslashit($advert_information['button_link']).'?afref='.$this->self_affiliate_id;
		}

		include_once(UPDRAFTPLUS_DIR.'/admin.php');
		global $updraftplus_admin;
		return $updraftplus_admin->include_template('wp-admin/notices/'.$template_file, $return_instead_of_echo, $advert_information);
	}
}

$GLOBALS['updraftplus_notices'] = UpdraftPlus_Notices::instance();
