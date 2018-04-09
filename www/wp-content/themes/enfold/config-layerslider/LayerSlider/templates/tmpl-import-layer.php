<?php if(!defined('LS_ROOT_FILE')) { header('HTTP/1.0 403 Forbidden'); exit; } ?>
<script type="text/html" id="tmpl-import-layer">
	<div id="tmpl-import-layer-modal-window" class="ls-import-slider-contents">
		<header>
			<h1><?php _e('Import Layer', 'LayerSlider') ?></h1>
			<b class="dashicons dashicons-no"></b>
		</header>
		<div class="km-ui-modal-scrollable">
			<div class="columns clearfix">
				<div class="third third-1">
					<h3><?php _e('Select slider', 'LayerSlider') ?></h3>
				</div>
				<div class="third third-2">
					<h3><?php _e('Choose a Slide', 'LayerSlider') ?></h3>
				</div>
				<div class="third third-3">
					<h3><?php _e('Click to import', 'LayerSlider') ?></h3>
				</div>
			</div>
			<div class="columns clearfix">
				<div class="third third-1 ls-import-layer-sliders">
					<?php _e('Loading ...', 'LayerSlider') ?>
				</div>
				<div class="third third-2 ls-import-layer-slides">
					<?php _e('Select a slider first.', 'LayerSlider') ?>
				</div>
				<div class="third third-3 ls-import-layer-layers">
					<?php _e('Select a slide first.', 'LayerSlider') ?>
				</div>
			</div>
		</div>
	</div>
</script>