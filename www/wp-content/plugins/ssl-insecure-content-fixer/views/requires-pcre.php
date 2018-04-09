
<div class="error">
	<p><?php printf(__('SSL Insecure Content Fixer requires <a target="_blank" rel="noopener" href="%1$s">PCRE</a> version %2$s or higher; your website has PCRE version %3$s', 'ssl-insecure-content-fixer'),
		'http://php.net/manual/en/book.pcre.php', esc_html($pcre_min), esc_html(PCRE_VERSION)); ?></p>
</div>
