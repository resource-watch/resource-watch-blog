<?php if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed'); ?>

<div id="updraft-navtab-status-content" class="<?php if (1 != $tabflag) echo 'updraft-hidden'; ?>" style="<?php if (1 != $tabflag) echo 'display:none;'; ?>">

	<div id="updraft-insert-admin-warning"></div>

	<table class="form-table" style="float:left; clear:both;">
		<noscript>
		<tr>
			<th><?php _e('JavaScript warning', 'updraftplus');?>:</th>
			<td style="color:red"><?php _e('This admin interface uses JavaScript heavily. You either need to activate it within your browser, or to use a JavaScript-capable browser.', 'updraftplus');?></td>
		</tr>
		</noscript>

		<tr>
			<th></th>
			<td>

			<?php
				if ($backup_disabled) {
					$this->show_admin_warning(
						htmlspecialchars(__("The 'Backup Now' button is disabled as your backup directory is not writable (go to the 'Settings' tab and find the relevant option).", 'updraftplus')),
						'error'
					);
				}
			?>
			<button id="updraft-backupnow-button" type="button" <?php echo $backup_disabled; ?> class="updraft-bigbutton button-primary" <?php if ($backup_disabled) echo 'title="'.esc_attr(__('This button is disabled because your backup directory is not writable (see the settings).', 'updraftplus')).'" ';?> onclick="updraft_backup_dialog_open();"><?php _e('Backup Now', 'updraftplus');?></button>

			<button type="button" class="updraft-bigbutton button-primary" onclick="updraft_openrestorepanel();">
				<?php _e('Restore', 'updraftplus');?>
			</button>

			<button type="button" class="updraft-bigbutton button-primary" onclick="updraft_migrate_dialog_open();"><?php _e('Clone/Migrate', 'updraftplus');?></button>

			</td>
		</tr>

		<?php
			$last_backup_html = $this->last_backup_html();
			$current_time = get_date_from_gmt(gmdate('Y-m-d H:i:s'), 'D, F j, Y H:i');
		?>

		<script>var lastbackup_laststatus = '<?php echo esc_js($last_backup_html);?>';</script>

		<tr>
			<th><span title="<?php esc_attr_e("All the times shown in this section are using WordPress's configured time zone, which you can set in Settings -> General", 'updraftplus'); ?>"><?php _e('Next scheduled backups', 'updraftplus');?>:<br>
			<span style="font-weight:normal;"><em><?php _e('Now', 'updraftplus');?>: <?php echo $current_time; ?></span></span></em></th>
			<td>
				<table id="next-backup-table-inner" class="next-backup">
					<?php $updraftplus_admin->next_scheduled_backups_output(); ?>
				</table>
			</td>
		</tr>
		
		<tr>
			<th><?php _e('Last backup job run:', 'updraftplus');?></th>
			<td id="updraft_last_backup"><?php echo $last_backup_html; ?></td>
		</tr>
	</table>

	<br style="clear:both;" />

	<?php $updraftplus_admin->render_active_jobs_and_log_table(); ?>

	<div id="updraft-migrate-modal" title="<?php _e('Migrate Site', 'updraftplus'); ?>" style="display:none;">
		<?php
			if (class_exists('UpdraftPlus_Addons_Migrator')) {
				do_action('updraftplus_migrate_modal_output');
			} else {
				echo '<p id="updraft_migrate_modal_main">'.__('Do you want to migrate or clone/duplicate a site?', 'updraftplus').'</p><p>'.__('Then, try out our "Migrator" add-on which can perform a direct site-to-site migration. After using it once, you\'ll have saved the purchase price compared to the time needed to copy a site by hand.', 'updraftplus').'</p><p><a href="'.apply_filters('updraftplus_com_link', "https://updraftplus.com/landing/migrator/").'">'.__('Get it here.', 'updraftplus').'</a></p>';
			}
		?>
	</div>

	<div id="updraft-iframe-modal">
		<div id="updraft-iframe-modal-innards">
		</div>
	</div>

	<div id="updraft-authenticate-modal" style="display:none;" title="<?php esc_attr_e('Remote storage authentication', 'updraftplus');?>">
		<p><?php _e('You have selected a remote storage option which has an authorization step to complete:', 'updraftplus'); ?></p>
		<div id="updraft-authenticate-modal-innards">
		</div>
	</div>

	<div id="updraft-backupnow-modal" title="UpdraftPlus - <?php _e('Perform a one-time backup', 'updraftplus'); ?>">
<!--				<p>
			<?php _e("To proceed, press 'Backup Now'. Then, watch the 'Last Log Message' field for activity.", 'updraftplus');?>
		</p>-->

	<?php echo $updraftplus_admin->backupnow_modal_contents(); ?>
	
	</div>

	<?php if (is_multisite() && !file_exists(UPDRAFTPLUS_DIR.'/addons/multisite.php')) { ?>
		<h2>UpdraftPlus <?php _e('Multisite', 'updraftplus');?></h2>
		<table>
			<tr>
				<td>
					<p class="multisite-advert-width"><?php echo __('Do you need WordPress Multisite support?', 'updraftplus').' <a href="'.apply_filters('updraftplus_com_link', "https://updraftplus.com/shop/updraftplus-premium/").'">'. __('Please check out UpdraftPlus Premium, or the stand-alone Multisite add-on.', 'updraftplus');?></a>.</p>
				</td>
			</tr>
		</table>
	<?php } ?>
	
</div>
