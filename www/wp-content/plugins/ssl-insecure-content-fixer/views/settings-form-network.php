<?php
// settings form for single site / blog

if (!defined('ABSPATH')) {
	exit;
}
?>

<div class="wrap">

	<h1><?php
		/* translators: heading for multisite network admin settings */
		esc_html_e('SSL Insecure Content Fixer multisite network settings', 'ssl-insecure-content-fixer');
	?></h1>

	<p><?php esc_html_e('These settings affect all sites on this network that have not been set individually.', 'ssl-insecure-content-fixer'); ?></p>

	<?php settings_errors(SSLFIX_PLUGIN_OPTIONS); ?>

	<form action="<?php echo esc_url(network_admin_url('settings.php?page=ssl-insecure-content-fixer')); ?>" method="POST">
		<?php wp_nonce_field('settings', 'sslfix_nonce'); ?>

		<table class="form-table">

			<?php require SSLFIX_PLUGIN_ROOT . 'views/settings-fields-common.php'; ?>

		</table>

		<?php submit_button(); ?>
	</form>

</div>
