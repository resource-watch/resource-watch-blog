<?php

if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed');

if (!class_exists('UpdraftPlus_Addon_LockAdmin') || (defined('UPDRAFTPLUS_NOADMINLOCK') && UPDRAFTPLUS_NOADMINLOCK)) { ?>
	<div class="advanced_tools lock_admin">
		<p class="updraftplus-lock-advert">
			<h3><?php _e('Lock access to the UpdraftPlus settings page', 'updraftplus'); ?></h3>
			
			<?php
			
				if (defined('UPDRAFTPLUS_NOADMINLOCK') && UPDRAFTPLUS_NOADMINLOCK) {
				
					_e('This functionality has been disabled by the site administrator.', 'updraftplus');
					
				} else {
			
					?><a href="<?php apply_filters("updraftplus_com_link", "https://updraftplus.com/shop/updraftplus-premium/");?>">
						<em><?php _e('For the ability to lock access to UpdraftPlus settings with a password, upgrade to UpdraftPlus Premium.', 'updraftplus'); ?></em>
					</a><?php
			
				}
			?>
		</p>
	</div>
<?php }
