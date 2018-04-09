<?php

if( ! defined( 'LS_ROOT_FILE' ) ) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$section = ! empty( $_GET['section'] ) ? $_GET['section'] : false;

switch( $section ) {

	case 'revisions':
		include LS_ROOT_PATH . '/views/revisions.php';
		break;

	default:
		include LS_ROOT_PATH . '/templates/tmpl-addons.php';
		break;
}