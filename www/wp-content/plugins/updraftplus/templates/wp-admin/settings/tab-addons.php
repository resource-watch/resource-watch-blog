<?php

if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed');

$tick = UPDRAFTPLUS_URL.'/images/updraft_tick.png';
$cross = UPDRAFTPLUS_URL.'/images/updraft_cross.png';
$freev = UPDRAFTPLUS_URL.'/images/updraft_freev.png';
$premv = UPDRAFTPLUS_URL.'/images/updraft_premv.png';

?>
<div>
	<h2 id="premium-upgrade-header">UpdraftPlus Premium - <a target="_blank" href="<?php echo apply_filters('updraftplus_com_link', "https://updraftplus.com/shop/updraftplus-premium/");?>"><?php _e('get it here', 'updraftplus');?></a></h2>
	<p>
		<span class="premium-upgrade-prompt"><?php _e('You are currently using the free version of UpdraftPlus.', 'updraftplus');?> <a target="_blank" href="<?php echo apply_filters('updraftplus_com_link', "https://updraftplus.com/support/installing-updraftplus-premium-your-add-on/");?>"> <?php echo __('If you have purchased from UpdraftPlus.Com, then follow this link to the installation instructions (particularly step 1).', 'updraftplus');?></a></span>
		<ul class="updraft_premium_description_list">
			<li><a target="_blank" href="<?php echo apply_filters('updraftplus_com_link', "https://updraftplus.com/updraftplus-full-feature-list/");?>"><?php _e('Full feature list', 'updraftplus');?></a></li>
			<li><a target="_blank" href="<?php echo apply_filters('updraftplus_com_link', "https://updraftplus.com/faq-category/general-and-pre-sales-questions/");?>"><?php _e('Pre-sales FAQs', 'updraftplus');?></a></li>
			<li><a target="_blank" href="<?php echo apply_filters('updraftplus_com_link', "https://updraftplus.com/ask-a-pre-sales-question/");?>"><?php _e('Ask a pre-sales question', 'updraftplus');?></a></li>
			<li><a target="_blank" href="<?php echo apply_filters('updraftplus_com_link', "https://updraftplus.com/shop/updraftplus-premium/");?>"><?php _e('Buy it now', 'updraftplus');?></a></li>
			<li><a target="_blank" href="<?php echo apply_filters('updraftplus_com_link', "https://updraftplus.com/support/");?>"><?php _e('Support', 'updraftplus');?></a></li>
			<li><a target="_blank" href="https://updraftcentral.com/">UpdraftCentral</a></li>
			<li><a target="_blank" href="https://wordpress.org/plugins/wp-optimize/">WP-Optimize</a></li>
			<li class="last"><a target="_blank" href="https://wordpress.org/plugins/keyy/">Keyy</a></li>
		</ul>
	</p>
</div>
<div>
	<table class="updraft_feat_table">
		<tbody>
		<tr>
			<td></td>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/ud-logo.png';?>" alt="UpdraftPlus" width="80" height="80">
				<?php _e('UpdraftPlus', 'updraftplus'); ?> <br> <?php _e('Free', 'updraftplus');?>
			</td>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/ud-logo.png';?>" alt="<?php esc_attr_e('UpdraftPlus Premium', 'updraftplus');?>" width="80" height="80">
				<?php _e('UpdraftPlus', 'updraftplus'); ?> <br> <?php _e('Premium', 'updraftplus');?>
			</td>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/ud-logo.png';?>" alt="<?php esc_attr_e('UpdraftPlus Gold', 'updraftplus');?>" width="80" height="80">
				<?php _e('UpdraftPlus', 'updraftplus'); ?> <br> <?php _e('Gold', 'updraftplus');?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<p><?php _e('Installed', 'updraftplus');?></p>
			</td>
			<td>
				<p><a href="<?php esc_attr_e(apply_filters('updraftplus_com_link', 'https://updraftplus.com/landing/updraftplus-premium'));?>"><?php _e('Upgrade now', 'updraftplus');?></a></p>
			</td>
			<td>
				<p><a href="<?php esc_attr_e(apply_filters('updraftplus_com_link', 'https://updraftplus.com/landing/updraftplus-premium'));?>"><?php _e('Upgrade now', 'updraftplus');?></a></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/addons-images/morestorage.png';?>" alt="<?php esc_attr_e('Remote storage', 'updraftplus');?>" width="80" height="80" class="udp-premium-image">
				<h4><?php _e('Backup to remote storage locations', 'updraftplus');?></h4>
				<p><?php _e('To avoid server-wide risks, always backup to remote cloud storage. UpdraftPlus free includes Dropbox, Google Drive, Amazon S3, Rackspace and more.', 'updraftplus');?></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/addons-images/morestorage.png';?>" alt="<?php esc_attr_e('Additional storage', 'updraftplus');?>" width="80" height="80" class="udp-premium-image">
				<h4><?php _e('Additional and enhanced remote storage locations', 'updraftplus');?></h4>
				<p><?php _e('Get enhanced versions of the free remote storage options and even more remote storage options like OneDrive, SFTP, Azure, WebDAV and more with UpdraftPlus Premium.', 'updraftplus');?></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/addons-images/migrator.png';?>" alt="<?php esc_attr_e('Migrator', 'updraftplus');?>" width="80" height="80" class="udp-premium-image">
				<h4><?php _e('Cloning and migration', 'updraftplus');?></h4>
				<p><?php _e('UpdraftPlus Migrator clones your WordPress site and moves it to a new domain directly and simply.', 'updraftplus');?></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/notices/support.png';?>" alt="<?php esc_attr_e('Support', 'updraftplus');?>" width="80" height="80" class="udp-premium-image">
				<h4><?php _e('Fast, personal support', 'updraftplus');?></h4>
				<p><?php _e('Provides expert help and support from the developers whenever you need it.', 'updraftplus');?></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/automaticbackup.png';?>" alt="<?php esc_attr_e('Pre-update backups', 'updraftplus');?>" width="80" height="80" class="udp-premium-image">
				<h4><?php _e('Pre-update backups', 'updraftplus');?></h4>
				<p><?php _e('Automatically backs up your website before any updates to plugins, themes and WordPress core.', 'updraftplus');?></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/addons-images/morefiles.png';?>" alt="<?php esc_attr_e('Backup non-WordPress files and databases', 'updraftplus');?>" width="80" height="80" class="udp-premium-image">
				<h4><?php _e('Backup non-WordPress files and databases', 'updraftplus');?></h4>
				<p><?php _e('Backup WordPress core and non-WP files and databases.', 'updraftplus');?></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></span></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/addons-images/multisite.png';?>" alt="<?php esc_attr_e('Network and multisite', 'updraftplus');?>" width="80" height="80" class="udp-premium-image">
				<h4><?php _e('Network / multisite', 'updraftplus');?></h4>
				<p><?php _e('Backup WordPress multisites (i.e, networks), securely.', 'updraftplus');?></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/addons-images/fixtime.png';?>" alt="<?php esc_attr_e('Backup time and scheduling', 'updraftplus');?>" width="80" height="80" class="udp-premium-image">
				<h4><?php _e('Backup time and scheduling', 'updraftplus');?></h4>
				<p><?php _e('Set exact times to create or delete backups.', 'updraftplus');?></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/addons-images/moredatabase.png';?>" alt="<?php esc_attr_e('More database options', 'updraftplus');?>" width="80" height="80" class="udp-premium-image">
				<h4><?php _e('More database options', 'updraftplus');?></h4>
				<p><?php _e('Encrypt your sensitive databases (e.g. customer information or passwords); Backup external databases too.', 'updraftplus');?></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/addons-images/reporting.png';?>" alt="<?php esc_attr_e('Reporting', 'updraftplus');?>" width="80" height="80" class="udp-premium-image">
				<h4><?php _e('Reporting', 'updraftplus');?></h4>
				<p><?php _e('Sophisticated reporting and emailing capabilities.', 'updraftplus');?></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/addons-images/noadverts.png';?>" alt="<?php esc_attr_e('No ads', 'updraftplus');?>" width="80" height="80" class="udp-premium-image">
				<h4><?php _e('No ads', 'updraftplus');?></h4>
				<p><?php _e('Tidy things up for clients and remove all adverts for our other products.', 'updraftplus');?></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/addons-images/importer.png';?>" alt="<?php esc_attr_e('Importer', 'updraftplus');?>" width="80" height="80" class="udp-premium-image">
				<h4><?php _e('Importer', 'updraftplus');?></h4>
				<p><?php _e('Some backup plugins can’t restore a backup, so Premium allows you to restore backups from other plugins.', 'updraftplus');?></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/addons-images/lockadmin.png';?>" alt="<?php esc_attr_e('Lock settings', 'updraftplus');?>" width="80" height="80" class="udp-premium-image">
				<h4><?php _e('Lock settings', 'updraftplus');?></h4>
				<p><?php _e('Lock access to UpdraftPlus via a password so you choose which admin users can access backups.', 'updraftplus');?></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
		</tr>
		<tr>
			<td>
				<p><?php _e('Other products bundled with UpdraftPlus Premium or Gold', 'updraftplus');?></p>
			</td>
			<td>
				<p><?php _e('UpdraftPlus Free', 'updraftplus');?></p>
			</td>
			<td>
				<p><?php _e('UpdraftPlus Premium', 'updraftplus');?></p>
			</td>
			<td>
				<p><?php _e('UpdraftPlus Gold', 'updraftplus');?></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/updraft_vault_logo.png';?>" alt="<?php esc_attr_e('UpdraftVault', 'updraftplus');?>" width="100" height="100" class="udp-premium-image">
				<h4><?php _e('UpdraftVault storage', 'updraftplus');?></h4>
				<p>
					<?php _e('UpdraftPlus has its own embedded storage option, providing a zero-hassle way to download, store and manage all your backups from one place.', 'updraftplus');?>
					<a href="<?php esc_attr_e(apply_filters('updraftplus_com_link', 'https://updraftplus.com/landing/updraftvault'));?>"><?php _e('Find out more', 'updraftplus');?></a>
				</p>
				
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></span></p>
			</td>
			<td>
				<p><span class="updraft-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>">1 GB</span></p>
			</td>
			<td>
				<p><span class="updraft-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>">50 GB</span></p>
			</td>
		</tr>
		<tr>
			<td>
				<img src="<?php echo UPDRAFTPLUS_URL.'/images/updraft_central_logo.png';?>" alt="<?php esc_attr_e('UpdraftCentral', 'updraftplus');?>" width="100" height="100" class="udp-premium-image">
				<h4><?php _e('UpdraftCentral Cloud or Premium', 'updraftplus');?></h4>
				<p>
					<?php echo __('UpdraftCentral is a highly efficient way to manage, update and backup multiple websites from one place.', 'updraftplus').' '.__('Everyone can use the free version; but UpdraftGold bundles an enhanced paid version.', 'updraftplus');?>
					<a href="<?php esc_attr_e(apply_filters('updraftplus_com_link', 'https://updraftplus.com/landing/updraftcentral'));?>"><?php _e('Find out more', 'updraftplus');?></a>	
				</p>
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-no-alt" aria-label="<?php esc_attr_e('No', 'updraftplus');?>"></span></p>
			</td>
			<td>
				<p><span class="dashicons dashicons-yes" aria-label="<?php esc_attr_e('Yes', 'updraftplus');?>"></span></p>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<p><?php _e('Installed', 'updraftplus');?></p>
			</td>
			<td>
				<p><a href="<?php esc_attr_e(apply_filters('updraftplus_com_link', 'https://updraftplus.com/landing/updraftplus-premium'));?>"><?php _e('Upgrade now', 'updraftplus');?></a></p>
			</td>
			<td>
				<p><a href="<?php esc_attr_e(apply_filters('updraftplus_com_link', 'https://updraftplus.com/landing/updraftplus-premium'));?>"><?php _e('Upgrade now', 'updraftplus');?></a></p>
			</td>
		</tr>
		</tbody>
	</table> 
</div>


