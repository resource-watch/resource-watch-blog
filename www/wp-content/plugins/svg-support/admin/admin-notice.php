<?php
/**
 * Display admin notice to users who upgraded from less than 2.3
 * Allow for dismissal by storing an option in the DB via AJAX
 * Remove option from DB when plugin is deactivated or uninstalled
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Admin notice markup
 */
function bodhi_svgs_admin_notice_upgrade() {

	echo '<div class="notice notice-warning is-dismissible svgs-upgrade-notice">';
		echo '<p>' . __( 'If you updated SVG Support from any version prior to 2.3 and you use the inline SVG features, please ', 'svg-support' ) . '<a href="' . get_admin_url( null, 'options-general.php?page=svg-support' ) . '">' . __( 'Enable Advanced Mode', 'svg-support' ) . '</a></p>';
	echo '</div>';

	update_option( 'bodhi_svgs_admin_notice_dismissed', 0 );

}

/**
 * Check if notice has been dismissed before
 */
if ( get_option( 'bodhi_svgs_admin_notice_dismissed' ) == 0 ) {
	add_action( 'admin_notices', 'bodhi_svgs_admin_notice_upgrade' );
}

/**
 * Enqueue JS for click detection
 */
function bodhi_svgs_admin_notice_enqueue() {
	wp_enqueue_script( 'svgs-admin-notice-update', BODHI_SVGS_PLUGIN_URL . '/js/min/svgs-admin-notice-update-min.js', array( 'jquery' ), '1.0', true  );
}
add_action( 'admin_enqueue_scripts', 'bodhi_svgs_admin_notice_enqueue' );

/**
 * Ajax to set option of dismissed
 */
function bodhi_svgs_dismiss_admin_notice() {
	update_option( 'bodhi_svgs_admin_notice_dismissed', 1 );
}
add_action( 'wp_ajax_bodhi_svgs_dismiss_admin_notice', 'bodhi_svgs_dismiss_admin_notice' );

/**
 * Remove notice dismissed option when plugin is deactivated or uninstalled
 */
function bodhi_svgs_deactivated() {
	delete_option( 'bodhi_svgs_admin_notice_dismissed' );
}
register_deactivation_hook( BODHI_SVGS_PLUGIN_PATH . '/svg-support.php', 'bodhi_svgs_deactivated' );
register_uninstall_hook( BODHI_SVGS_PLUGIN_PATH . '/svg-support.php', 'bodhi_svgs_deactivated' );
