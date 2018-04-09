<div id="updraft-dashnotice" class="updated">
	<div style="float:right;"><a href="#" onclick="jQuery('#updraft-dashnotice').slideUp(); jQuery.post(ajaxurl, {action: 'updraft_ajax', subaction: 'dismissdashnotice', nonce: '<?php echo wp_create_nonce('updraftplus-credentialtest-nonce');?>' });"><?php printf(__('Dismiss (for %s months)', 'updraftplus'), 12); ?></a></div>

	<h3><?php _e('Thank you for installing UpdraftPlus!', 'updraftplus');?></h3>
	
	<a href="<?php echo apply_filters('updraftplus_com_link', 'https://updraftplus.com/');?>"><img style="border: 0px; float: right; height: 150px; width: 150px; margin: 20px 15px 15px 35px;" alt="UpdraftPlus" src="<?php echo UPDRAFTPLUS_URL.'/images/ud-logo-150.png'; ?>"></a>

	<?php
		echo '<p>'.__('Super-charge and secure your WordPress site with our other top plugins:', 'updraftplus').'</p>';
	?>
	<p>
		<?php echo '<strong><a href="'.apply_filters('updraftplus_com_link', "https://updraftplus.com/shop/updraftplus-premium/").'">'.__('UpdraftPlus Premium', 'updraftplus').'</a>: </strong>'.__('For personal support, the ability to copy sites, more storage destinations, encrypted backups for security, multiple backup destinations, better reporting, no adverts and plenty more, take a look at the premium version of UpdraftPlus - the world’s most popular backup plugin.', 'updraftplus');
		echo ' <a href="'.apply_filters('updraftplus_com_link', "https://updraftplus.com/comparison-updraftplus-free-updraftplus-premium/").'">'.__('Compare with the free version', 'updraftplus').'</a> / <a href="'.apply_filters('updraftplus_com_link', "https://updraftplus.com/shop/updraftplus-premium/").'">'.__('Go to the shop.', 'updraftplus').'</a>';
	?>
	</p>
	<p>
		<?php echo '<strong><a href="'.apply_filters('updraftplus_com_link', "https://updraftplus.com/updraftcentral/").'">'.__('UpdraftCentral', 'updraftplus').'</a> </strong>'.__('is a highly efficient way to manage, update and backup multiple websites from one place.', 'updraftplus'); ?>
	</p>
	<p>
		<?php echo '<strong><a href="https://www.metaslider.com">MetaSlider</a>: </strong>'.__('Add style and flare easily with beautifully-designed sliders on the #1 WP slider plugin', 'updraftplus'); ?>
	</p>
	<p>
		<?php echo '<strong><a href="https://getwpo.com">WP-Optimize</a>: </strong>'.__('Auto-clean your WordPress database so that it runs at maximum efficiency. 800,000 users!', 'updraftplus'); ?>
	</p>
	<p>
		<?php echo '<strong><a href="https://getkeyy.com/">Keyy: </a></strong>'.__('Simple and secure login with a wave of your phone.', 'updraftplus'); ?>
	</p>
	<p>
		<?php echo '<strong><a href="'.apply_filters('updraftplus_com_link', "https://updraftplus.com/newsletter-signup").'">'.__('Free Newsletter', 'updraftplus').'</a>: </strong>'.__('UpdraftPlus news, high-quality training materials for WordPress developers and site-owners, and general WordPress news. You can de-subscribe at any time.', 'updraftplus'); ?>
	</p>
	<p>
		<?php echo '<strong>'.__('More quality plugins', 'updraftplus').' :</strong>';?>
		<a href="https://www.simbahosting.co.uk/s3/shop/"><?php echo __('Premium WooCommerce plugins', 'updraftplus').'</a> | <a href="https://wordpress.org/plugins/two-factor-authentication/">'.__('Free two-factor security plugin', 'updraftplus');?></a>
	</p>
	<div style="float:right;"><a href="#" onclick="jQuery('#updraft-dashnotice').slideUp(); jQuery.post(ajaxurl, {action: 'updraft_ajax', subaction: 'dismissdashnotice', nonce: '<?php echo wp_create_nonce('updraftplus-credentialtest-nonce');?>' });"><?php printf(__('Dismiss (for %s months)', 'updraftplus'), 12); ?></a></div>
	<p>&nbsp;</p>
</div>
