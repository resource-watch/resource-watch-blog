<?php if(!defined('LS_ROOT_FILE')) { header('HTTP/1.0 403 Forbidden'); exit; } ?>
<script type="text/html" id="tmpl-post-chooser">
	<div id="ls-post-chooser-modal-window">
		<header>
			<h1><?php _e('Select the Post, Page or Attachment you want to use', 'LayerSlider') ?></h1>
			<b class="dashicons dashicons-no"></b>
		</header>
		<div class="km-ui-modal-scrollable">
			<form method="post">
				<?php wp_nonce_field( 'ls_get_search_posts' ) ?>
				<input type="hidden" name="action" value="ls_get_search_posts">
				<div class="search-holder">
					<input type="search" name="s" placeholder="<?php _e('Type here to search ...', 'LayerSlider') ?>">
				</div>
				<select name="post_type">
					<option value="page"><?php _e('Pages', 'LayerSlider') ?></option>
					<option value="post"><?php _e('Posts', 'LayerSlider') ?></option>
					<option value="attachment"><?php _e('Attachments', 'LayerSlider') ?></option>
				</select>
			</form>

			<div class="results ls-post-previews">
				<ul>

				</ul>
			</div>

		</div>
	</div>
</script>