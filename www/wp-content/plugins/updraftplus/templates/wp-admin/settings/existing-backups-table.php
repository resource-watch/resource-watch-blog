<?php

if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed');

$accept = apply_filters('updraftplus_accept_archivename', array());
if (!is_array($accept)) $accept = array();
$image_folder = UPDRAFTPLUS_DIR.'/images/icons/';
$image_folder_url = UPDRAFTPLUS_URL.'/images/icons/';

?>
<table class="existing-backups-table">
	<thead>
		<tr style="margin-bottom: 4px;">
			<th class="backup-date"><?php _e('Backup date', 'updraftplus');?></th>
			<th class="backup-data"><?php _e('Backup data (click to download)', 'updraftplus');?></th>
			<th class="updraft_backup_actions"><?php _e('Actions', 'updraftplus');?></th>
		</tr>
		<tr style="height:2px; padding:1px; margin:0px;">
			<td colspan="4" style="margin:0; padding:0"><div style="height: 2px; background-color:#888888;">&nbsp;</div></td>
		</tr>
	</thead>
	<tbody>
		<?php

		// Reverse date sort - i.e. most recent first
		krsort($backup_history);

		foreach ($backup_history as $key => $backup) {

			$remote_sent = (!empty($backup['service']) && ((is_array($backup['service']) && in_array('remotesend', $backup['service'])) || 'remotesend' === $backup['service'])) ? true : false;

			// https://core.trac.wordpress.org/ticket/25331 explains why the following line is wrong
			// $pretty_date = date_i18n('Y-m-d G:i',$key);
			// Convert to blog time zone
			// $pretty_date = get_date_from_gmt(gmdate('Y-m-d H:i:s', (int)$key), 'Y-m-d G:i');
			$pretty_date = get_date_from_gmt(gmdate('Y-m-d H:i:s', (int) $key), 'M d, Y G:i');

			$esc_pretty_date = esc_attr($pretty_date);
			$entities = '';

			$nonce = $backup['nonce'];
			$rawbackup = $updraftplus_admin->raw_backup_info($backup_history, $key, $nonce);

			$jobdata = $updraftplus->jobdata_getarray($nonce);

			$delete_button = $updraftplus_admin->delete_button($key, $nonce, $backup);

			$date_label = $updraftplus_admin->date_label($pretty_date, $key, $backup, $jobdata, $nonce);

			$log_button = $updraftplus_admin->log_button($backup);

			// Remote backups with no log result in useless empty rows. However, not showing anything messes up the "Existing Backups (14)" display, until we tweak that code to count differently
			// if ($remote_sent && !$log_button) continue;

			?>
			<tr class="updraft_existing_backups_row updraft_existing_backups_row_<?php echo $key;?>" data-key="<?php echo $key;?>" data-nonce="<?php echo $nonce;?>">

				<td class="updraft_existingbackup_date " data-rawbackup="<?php echo $rawbackup;?>">
					<div class="backup_date_label">
						<?php echo $date_label;?>
						<?php
							if (!isset($backup['service'])) $backup['service'] = array();
							if (!is_array($backup['service'])) $backup['service'] = array($backup['service']);
							foreach ($backup['service'] as $service) {
								if ('none' === $service || '' === $service || (is_array($service) && (empty($service) || array('none') === $service || array('') === $service))) {
									// Do nothing
								} else {
									$image_url = file_exists($image_folder.$service.'.png') ? $image_folder_url.$service.'.png' : $image_folder_url.'folder.png';

									$remote_storage = ('remotesend' === $service) ? __('remote site', 'updraftplus') : $updraftplus->backup_methods[$service];
									?>
									<img class="stored_icon" src="<?php echo esc_attr($image_url);?>" title="<?php echo esc_attr(sprintf(__('Stored at: %s', 'updraftplus'), $remote_storage));?>">
									<?php
								}
							}
						?>
					</div>
				</td>
				
				<td><?php

				if ($remote_sent) {

					_e('Backup sent to remote site - not available for download.', 'updraftplus');
					if (!empty($backup['remotesend_url'])) echo '<br>'.__('Site', 'updraftplus').': '.htmlspecialchars($backup['remotesend_url']);

				} else {

					if (empty($backup['meta_foreign']) || !empty($accept[$backup['meta_foreign']]['separatedb'])) {

						if (isset($backup['db'])) {
							$entities .= '/db=0/';

							// Set a flag according to whether or not $backup['db'] ends in .crypt, then pick this up in the display of the decrypt field.
							$db = is_array($backup['db']) ? $backup['db'][0] : $backup['db'];
							if ($updraftplus->is_db_encrypted($db)) $entities .= '/dbcrypted=1/';

							echo $updraftplus_admin->download_db_button('db', $key, $esc_pretty_date, $backup, $accept);
						}

						// External databases
						foreach ($backup as $bkey => $binfo) {
							if ('db' == $bkey || 'db' != substr($bkey, 0, 2) || '-size' == substr($bkey, -5, 5)) continue;
							echo $updraftplus_admin->download_db_button($bkey, $key, $esc_pretty_date, $backup);
						}

					} else {
						// Foreign without separate db
						$entities = '/db=0/meta_foreign=1/';
					}

					if (!empty($backup['meta_foreign']) && !empty($accept[$backup['meta_foreign']]) && !empty($accept[$backup['meta_foreign']]['separatedb'])) {
						$entities .= '/meta_foreign=2/';
					}

					echo $updraftplus_admin->download_buttons($backup, $key, $accept, $entities, $esc_pretty_date);

				}

				?>
				</td>
				<td class="before-restore-button">
					<?php
					echo $updraftplus_admin->restore_button($backup, $key, $pretty_date, $entities);
					echo $delete_button;
					if (empty($backup['meta_foreign'])) echo $log_button;
					?>
				</td>
			</tr>

			<tr style="height:2px; padding:1px; margin:0px;">
				<td colspan="4" style="margin:0; padding:0">
					<div style="height: 2px; background-color:#aaaaaa;">&nbsp;</div>
				</td>
			</tr>

		<?php } ?>	

	</tbody>
</table>
