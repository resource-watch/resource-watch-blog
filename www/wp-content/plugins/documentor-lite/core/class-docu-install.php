<?php
/**
 * Installation related functions and actions
 *
 * @author   Tejaswini Deshpande
 * @category Admin
 * @package  Documentor/Classes
 * @version  1.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * DocuLite_Install Class.
 */
class DocuLite_Install {
	/**
	 * Install/Update Documentor.
	 */
	public static function install() {
		global $wpdb;
		$installed_ver = get_option( "documentorlite_db_version" );
		if( $installed_ver != DOCUMENTORLITE_VER ) {
			self::process_options();
			self::process_tables();
			
			// Trigger action
			do_action( 'documentor_lite_installed' );
		}//end of if db version change
	}
	
	private static function process_options() {
		update_option( "documentorlite_db_version", DOCUMENTORLITE_VER );
		//global setting
		$global_settings = documentor_lite_global_settings();
		$global_settings_curr = get_option('documentor_global_options');
		if( !$global_settings_curr ) {
			$global_settings_curr = array();
		}
		foreach($global_settings as $key=>$value) {
			if(!isset($global_settings_curr[$key])) {
				$global_settings_curr[$key] = $value;
			}
		}
		update_option('documentor_global_options',$global_settings_curr);
	}
	
	private static function process_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		//Create or update tables
		dbDelta( self::get_schema() );
		
		//Insert the only Guide
		$doc_table=$wpdb->prefix.DOCUMENTORLITE_TABLE;
		$sqlsel = "SELECT COUNT(*) FROM $doc_table;";
		$count = $wpdb->get_var( $sqlsel );
		if( $count <= 0 ) {
			$wpdb->insert( 
							$doc_table, 
							array(
								'doc_id' => 1,
								'post_id' => 0							
							), 
							array( 
								'%d',
								'%d'
							)
						);
		}
	}

	/**
	 * Get Table schema.
	 * @return string
	 */
	private static function get_schema() {
		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
		
		$tables = "		
CREATE TABLE {$wpdb->prefix}".DOCUMENTORLITE_TABLE." (
	doc_id int(5) NOT NULL auto_increment,
	post_id bigint(20) NOT NULL,
	PRIMARY KEY  doc_id (doc_id)
) $collate;
CREATE TABLE {$wpdb->prefix}".DOCUMENTORLITE_SECTIONS." (
	sec_id int(5) NOT NULL auto_increment,
	doc_id int(5) NOT NULL,
	post_id bigint(20) NOT NULL,
	type int(2) NOT NULL,
	upvote int(5) NOT NULL default '0',
	downvote int(5) NOT NULL default '0',
	PRIMARY KEY  sec_id (sec_id)
) $collate;
CREATE TABLE {$wpdb->prefix}".DOCUMENTORLITE_FEEDBACK." (
	id bigint(20) NOT NULL auto_increment,
	doc_id int(5) NOT NULL,							
	sec_id int(5) NOT NULL,
	ip varchar(45) NOT NULL,
	vote varchar(10) NOT NULL,
	date TIMESTAMP NOT NULL,
	PRIMARY KEY  id (id)
) $collate;
	";
		return $tables;
	}
}