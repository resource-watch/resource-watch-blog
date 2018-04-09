<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

delete_option('maintenance_options');
delete_option('maintenance_db_version');

?>