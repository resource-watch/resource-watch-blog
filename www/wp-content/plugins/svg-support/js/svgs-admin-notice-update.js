jQuery(document).ready(function ($) {

	function svgsDismissNotice(){

		jQuery.ajax({
			url: ajaxurl,
			data: {
				action: 'bodhi_svgs_dismiss_admin_notice'
			}
		});

	}

	$('.svgs-upgrade-notice .notice-dismiss').click(svgsDismissNotice);

});
