/*globals ajaxurl, wpml_sticky_links_ajxloaderimg, data */

var wpml_sticky_links_ajax_loader_img = data.wpml_sticky_links_ajxloaderimg;

jQuery(document).ready(function ($) {
	var sticky_links = sticky_links || {};

	sticky_links.save_options = function (event) {

		if (typeof(event.preventDefault) !== 'undefined' ) {
			event.preventDefault();
		} else {
			event.returnValue = false;
		}

		var submit_button = $(this).attr('disabled', 'disabled').after(wpml_sticky_links_ajax_loader_img);
		var form = $(this).closest('form');
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: "action=wpml_sticky_links_save_options&" + form.serialize(),
			success: function () {
				submit_button.removeAttr('disabled').next().fadeOut();
			}
		});
		return false;

	};

	jQuery('#icl_save_sl_options').find('#save').on('click', sticky_links.save_options);

});