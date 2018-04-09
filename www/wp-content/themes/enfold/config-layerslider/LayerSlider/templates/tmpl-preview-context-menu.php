<?php if(!defined('LS_ROOT_FILE')) {  header('HTTP/1.0 403 Forbidden'); exit; } ?>
<script type="text/html" id="tmpl-ls-preview-context-menu">
	<div class="ls-preview-context-menu">
		<ul>
			<li class="group">
				<i class="dashicons dashicons-plus"></i>
				<?php _e('Add Layer', 'LayerSlider') ?>
				<div>
					<ul class="ls-context-add-layer">
						<li data-type="img">
							<i class="dashicons dashicons-format-image"></i>
							<?php _e('Image', 'LayerSlider') ?>
						</li>
						<li data-type="icon">
							<i class="dashicons dashicons-flag"></i>
							<?php _e('Icon', 'LayerSlider') ?>
						</li>
						<li data-type="text">
							<i class="dashicons dashicons-text"></i>
							<?php _e('Text', 'LayerSlider') ?>
						</li>
						<li data-type="button">
							<i class="dashicons dashicons-marker"></i>
							<?php _e('Button', 'LayerSlider') ?>
						</li>
						<li data-type="media">
							<i class="dashicons dashicons-video-alt3"></i>
							<?php _e('Video / Audio', 'LayerSlider') ?>
						</li>
						<li data-type="html">
							<i class="dashicons dashicons-editor-code"></i>
							<?php _e('HTML', 'LayerSlider') ?>
						</li>
						<li data-type="post">
							<i class="dashicons dashicons-admin-post"></i>
							<?php _e('Dynamic Layer', 'LayerSlider') ?>
						</li>
						<li data-type="import">
							<i class="dashicons dashicons-upload"></i>
							<?php _e('Import Layer', 'LayerSlider') ?>
						</li>
					</ul>
				</div>
			</li>
			<li class="group ls-context-overlapping-layers">
				<i class="dashicons dashicons-menu"></i>
				<?php _e('Overlapping Layers', 'LayerSlider') ?>
				<div>
					<ul></ul>
				</div>
			</li>
			<li class="group ls-context-menu-align">
				<i class="dashicons dashicons-align-right"></i>
				<?php _e('Align Layer', 'LayerSlider') ?>
				<div>
					<ul>
						<li data-move="left" class="ls-align-left">
							<i class="dashicons dashicons-align-left"></i>
							<?php _e('Left Edge', 'LayerSlider') ?>
						</li>
						<li data-move="center" class="ls-align-center">
							<i class="dashicons dashicons-align-center"></i>
							<?php _e('Horizontal Center', 'LayerSlider') ?>
						</li>
						<li data-move="right" class="ls-align-right">
							<i class="dashicons dashicons-align-right"></i>
							<?php _e('Right Edge', 'LayerSlider') ?>
						</li>
						<li class="divider"></li>
						<li data-move="top" class="ls-align-top">
							<i class="dashicons dashicons-align-left"></i>
							<?php _e('Top Edge', 'LayerSlider') ?>
						</li>
						<li data-move="middle" class="ls-align-middle">
							<i class="dashicons dashicons-align-center"></i>
							<?php _e('Vertical Center', 'LayerSlider') ?>
						</li>
						<li data-move="bottom" class="ls-align-bottom">
							<i class="dashicons dashicons-align-right"></i>
							<?php _e('Bottom Edge', 'LayerSlider') ?>
						</li>
						<li class="divider"></li>
						<li data-move="middle center" class="ls-align-center-center">
							<i class="dashicons dashicons-align-center"></i>
							<?php _e('Center Center', 'LayerSlider') ?>
						</li>
					</ul>
				</div>
			</li>
			<li class="ls-context-menu-duplicate">
				<i class="dashicons dashicons-admin-page"></i>
				<?php _e('Duplicate Layer', 'LayerSlider') ?>
			</li>
			<li class="ls-context-menu-remove">
				<i class="dashicons dashicons-trash"></i>
				<?php _e('Remove Layer', 'LayerSlider') ?>
			</li>
			<li class="divider"></li>
			<li class="ls-context-menu-copy-layer">
				<i class="dashicons dashicons-clipboard"></i>
				<?php _e('Copy Layer') ?>
			</li>
			<li class="ls-context-menu-paste-layer">
				<i class="dashicons dashicons-admin-page"></i>
				<?php _e('Paste Layer') ?>
			</li>
			<li class="divider"></li>
			<li class="ls-context-menu-hide">
				<i class="dashicons dashicons-visibility"></i>
				<?php _e('Toggle Layer Visibility', 'LayerSlider') ?>
			</li>
			<li class="ls-context-menu-lock">
				<i class="dashicons dashicons-lock"></i>
				<?php _e('Toggle Layer Locking', 'LayerSlider') ?>
			</li>
			<li class="divider"></li>
			<li class="ls-context-menu-copy-styles">
				<i class="dashicons dashicons-clipboard"></i>
				<?php _e('Copy Layer Styles') ?>
			</li>
			<li class="ls-context-menu-paste-styles">
				<i class="dashicons dashicons-admin-page"></i>
				<?php _e('Paste Layer Styles') ?>
			</li>
		</ul>
	</div>
</script>
