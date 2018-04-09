<?php
/*
Plugin Name: SSL Insecure Content Fixer
Plugin URI: https://ssl.webaware.net.au/
Description: Clean up WordPress website HTTPS insecure content
Version: 2.5.0
Author: WebAware
Author URI: https://shop.webaware.com.au/
Text Domain: ssl-insecure-content-fixer
*/

/*
copyright (c) 2012-2017 WebAware Pty Ltd (email : support@webaware.com.au)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


if (!defined('ABSPATH')) {
	exit;
}

define('SSLFIX_PLUGIN_FILE', __FILE__);
define('SSLFIX_PLUGIN_ROOT', dirname(__FILE__) . '/');
define('SSLFIX_PLUGIN_NAME', basename(dirname(__FILE__)) . '/' . basename(__FILE__));
define('SSLFIX_PLUGIN_VERSION', '2.5.0');
define('SSLFIX_PLUGIN_OPTIONS', 'ssl_insecure_content_fixer');

require SSLFIX_PLUGIN_ROOT . 'includes/class.SSLInsecureContentFixer.php';
SSLInsecureContentFixer::getInstance();


/**
* replace http: URL with https: URL
* @param string $url
* @return string
*/
function ssl_insecure_content_fix_url($url) {
	// only fix if source URL starts with http://
	if (stripos($url, 'http://') === 0) {
		$url = 'https' . substr($url, 4);
	}

	return $url;
}
