<?php

/**
* generate nonce cookie name
* @param string $plugin_path
* @return string
*/
function ssl_insecure_content_fix_nonce_name($plugin_path) {
	// synthesise a temporary cookie using server name, file path, time, and system data
	// NB: only needs to be as complex/secure as the data that could be exposed, i.e. the contents of $_SERVER and script paths
	$tick = ceil(time() / 120);
	$cookie_name = 'sslfix_' . md5(sprintf('%s|%s|%s', $_SERVER['SERVER_NAME'], $plugin_path, $tick));

	return $cookie_name;
}


/**
* generate nonce value
* @return string
*/
function ssl_insecure_content_fix_nonce_value() {
	// some system data, difficult to guess unless server environment is already known
	$data = sprintf("%s\n%s\n%s\n%s", php_uname(), php_ini_loaded_file(), php_ini_scanned_files(), implode("\n", get_loaded_extensions()));

	return md5($data);
}


