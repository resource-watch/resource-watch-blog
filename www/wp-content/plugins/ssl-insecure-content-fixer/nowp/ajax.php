<?php

// compute the path to the plugin's root folder
$sslfix_plugin_root = dirname(dirname(__FILE__)) . '/';

require $sslfix_plugin_root . 'includes/nonces.php';

/**
* test for cookie, must have expected name and value
*/

$cookie_name  = ssl_insecure_content_fix_nonce_name($sslfix_plugin_root);
$cookie_value = ssl_insecure_content_fix_nonce_value();

if (!isset($_COOKIE[$cookie_name])) {
	sslfix_send_error('missing nonce.');
}

if ($_COOKIE[$cookie_name] !== $cookie_value) {
	sslfix_send_error('bad nonce value.');
}

/**
* run some AJAX functions outside of WordPress, so that we can see the raw environment
*/

if (isset($_GET['action'])) {
	switch ($_GET['action']) {

		case 'sslfix-get-recommended':
			sslfix_get_recommended();
			break;

		case 'sslfix-environment':
			sslfix_environment();
			break;

		default:
			sslfix_send_error('invalid action');
			break;

	}
}
else {
	sslfix_send_error('no action given');
}

/**
* test environment and recommend settings
*/
function sslfix_get_recommended() {
	$env = sslfix_get_environment();

	$response = array();

	switch ($env['detect']) {

		case 'HTTPS':
		case 'port':
			$response['recommended'] = 'normal';
			break;

		case 'HTTP_X_FORWARDED_PROTO':
			$response['recommended'] = 'HTTP_X_FORWARDED_PROTO';
			break;

		case 'HTTP_X_FORWARDED_SSL':
			$response['recommended'] = 'HTTP_X_FORWARDED_SSL';
			break;

		case 'HTTP_CLOUDFRONT_FORWARDED_PROTO':
			$response['recommended'] = 'HTTP_CLOUDFRONT_FORWARDED_PROTO';
			break;

		case 'HTTP_CF_VISITOR':
			$response['recommended'] = 'HTTP_CF_VISITOR';
			break;

		case 'HTTP_X_ARR_SSL':
			$response['recommended'] = 'HTTP_X_ARR_SSL';
			break;

		case 'HTTP_X_FORWARDED_SCHEME':
			$response['recommended'] = 'HTTP_X_FORWARDED_SCHEME';
			break;

		default:
			$response['recommended'] = 'detect_fail';
			break;

	}

	$response['recommended_element'] = 'proxy_fix_' . $response['recommended'];

	sslfix_send_json($response);
}

/**
* test environment to see what can be detected
*/
function sslfix_environment() {
	$response = sslfix_get_environment();

	// build a list of environment variables to omit, as keys
	// some are just unnecessary, some might expose sensitive information like script paths
	$env_blacklist = array_flip(array(
		'argc',
		'argv',
		'AUTH_TYPE',
		'CONTENT_LENGTH',
		'CONTENT_TYPE',
		'CONTEXT_DOCUMENT_ROOT',
		'CONTEXT_PREFIX',
		'DOCUMENT_ROOT',
		'DOCUMENT_ROOT_REAL',
		'DOCUMENT_URI',
		'FCGI_ROLE',
		'GATEWAY_INTERFACE',
		'HOME',
		'HTTP_ACCEPT',
		'HTTP_ACCEPT_CHARSET',
		'HTTP_ACCEPT_ENCODING',
		'HTTP_ACCEPT_LANGUAGE',
		'HTTP_CONNECTION',
		'HTTP_COOKIE',
		'HTTP_HOST',
		'HTTP_ORIGIN',
		'HTTP_REFERER',
		'HTTP_X_REQUESTED_WITH',
		'HTTP_USER_AGENT',
		'ORIG_PATH_INFO',
		'PATH',
		'PATH_INFO',
		'PATH_TRANSLATED',
		'PHP_AUTH_DIGEST',
		'PHP_AUTH_PW',
		'PHP_AUTH_USER',
		'PHP_SELF',
		'PP_CUSTOM_PHP_INI',
		'PP_CUSTOM_PHP_CGI_INDEX',
		'PWD',
		'QUERY_STRING',
		'REDIRECT_REMOTE_USER',
		'REDIRECT_STATUS',
		'REMOTE_ADDR',
		'REMOTE_PORT',
		'REMOTE_USER',
		'REQUEST_METHOD',
		'REQUEST_TIME',
		'REQUEST_TIME_FLOAT',
		'REQUEST_URI',
		'SCRIPT_FILENAME',
		'SCRIPT_NAME',
		'SCRIPT_URI',
		'SCRIPT_URL',
		'SERVER_ADDR',
		'SERVER_ADMIN',
		'SERVER_NAME',
		'SERVER_PORT',
		'SERVER_PROTOCOL',
		'SERVER_SIGNATURE',
		'SERVER_SOFTWARE',
		'UNIQUE_ID',
		'USER',
	));

	// build server environment to return, without blacklisted keys
	$env = array_diff_key($_SERVER, $env_blacklist);

	$response['env'] = print_r($env, 1);

	sslfix_send_json($response);
}

/**
* test environment to see what can be detected
*/
function sslfix_get_environment() {
	$env = array(
		'ssl' => false,
	);

	if (isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] === '1')) {
		$env['detect'] = 'HTTPS';
		$env['ssl'] = true;
	}
	elseif (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] === '443')) {
		$env['detect'] = 'port';
		$env['ssl'] = true;
	}
	elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
		$env['detect'] = 'HTTP_X_FORWARDED_PROTO';
		$env['ssl'] = true;
	}
	elseif (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && (strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) === 'on' || $_SERVER['HTTP_X_FORWARDED_SSL'] === '1')) {
		$env['detect'] = 'HTTP_X_FORWARDED_SSL';
		$env['ssl'] = true;
	}
	elseif (isset($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO']) === 'https') {
		$env['detect'] = 'HTTP_CLOUDFRONT_FORWARDED_PROTO';
		$env['ssl'] = true;
	}
	elseif (!empty($_SERVER['HTTP_X_ARR_SSL'])) {
		$env['detect'] = 'HTTP_X_ARR_SSL';
		$env['ssl'] = true;
	}
	elseif (isset($_SERVER['HTTP_X_FORWARDED_SCHEME']) && strtolower($_SERVER['HTTP_X_FORWARDED_SCHEME']) === 'https') {
		$env['detect'] = 'HTTP_X_FORWARDED_SCHEME';
		$env['ssl'] = true;
	}
	elseif (isset($_SERVER['HTTP_CF_VISITOR']) && strpos($_SERVER['HTTP_CF_VISITOR'], 'https') !== false) {
		$env['detect'] = 'HTTP_CF_VISITOR';
		$env['ssl'] = true;
	}
	else {
		$env['detect'] = 'fail';
		$env['ssl'] = false;
	}

	return $env;
}

/**
* send JSON response and terminate
* @param array $response
*/
function sslfix_send_json($response) {
	@header('Content-Type: application/json; charset=UTF-8');
	@header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
	@header('Cache-Control: no-cache, must-revalidate, max-age=0');
	@header('Pragma: no-cache');

	// add CORS headers so that browsers permit response
	@header('Access-Control-Allow-Credentials: true');
	if (isset($_SERVER['HTTP_ORIGIN'])) {
		@header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
	}

	echo json_encode($response);
	exit;
}

/**
* terminate with error
* @param string $msg
*/
function sslfix_send_error($msg) {
	@header('HTTP/1.0 403 Forbidden');

	// add CORS headers so that browsers permit response
	@header('Access-Control-Allow-Credentials: true');
	if (isset($_SERVER['HTTP_ORIGIN'])) {
		@header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
	}

	echo $msg;
	exit;
}
