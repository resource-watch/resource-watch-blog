
<div class="error">
	<p><?php _e('SSL Insecure Content Fixer requires these missing PHP extensions. Please contact your website host to have these extensions installed.', 'ssl-insecure-content-fixer'); ?></p>
	<ul style="padding-left: 2em">
		<?php foreach ($missing as $ext): ?>
		<li style="list-style-type:disc"><?php echo esc_html($ext); ?></li>
		<?php endforeach; ?>
	</ul>
</div>
