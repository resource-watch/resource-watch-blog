<?php if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed'); ?>

<div class="wrap" id="updraft-wrap">

	<h1><?php echo $updraftplus->plugin_title; ?></h1>

	<a href="<?php echo apply_filters('updraftplus_com_link', "https://updraftplus.com/");?>">UpdraftPlus.Com</a> | 
	<?php
		if (!defined('UPDRAFTPLUS_NOADS_B')) {
			?>
			<a href="<?php echo apply_filters('updraftplus_com_link', 'https://updraftplus.com/shop/updraftplus-premium/');?>"><?php _e("Premium", 'updraftplus'); ?></a> |
		<?php
		}
	?>
	<a href="<?php echo apply_filters('updraftplus_com_link', "https://updraftplus.com/news/");?>"><?php _e('News', 'updraftplus');?></a>  | 
	<a href="https://twitter.com/updraftplus"><?php _e('Twitter', 'updraftplus');?></a> | 
	<a href="<?php echo apply_filters('updraftplus_com_link', "https://updraftplus.com/support/");?>"><?php _e("Support", 'updraftplus');?></a> | 
	<?php
		if (!is_file(UPDRAFTPLUS_DIR.'/udaddons/updraftplus-addons.php')) {
		?>
			<a href="<?php echo apply_filters('updraftplus_com_link', "https://updraftplus.com/newsletter-signup");?>"><?php _e("Newsletter sign-up", 'updraftplus'); ?></a> |
		<?php
		}
?>
	<a href="https://david.dw-perspective.org.uk"><?php _e("Lead developer's homepage", 'updraftplus');?></a> | 
	<a href="<?php echo apply_filters('updraftplus_com_link', "https://updraftplus.com/support/frequently-asked-questions/");?>"><?php _e('FAQs', 'updraftplus'); ?></a> | <a href="https://www.simbahosting.co.uk/s3/shop/"><?php _e('More plugins', 'updraftplus');?></a> - <?php _e('Version', 'updraftplus');?>: <?php echo $updraftplus->version; ?>

	<br>
