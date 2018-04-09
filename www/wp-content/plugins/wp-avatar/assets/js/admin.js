(function ($) {
	"use strict";

	$(document).ready(function () {
		wpa_admin.init();
	});

	var wpa_admin = window.wpa_admin = {

		init: function () {
			this.choose_avatar();
			this.choose_default_avatar();
			this.upload_avatar();
			this.remove_avatar();
		},

		upload_avatar: function () {
			$('#your-profile').attr('enctype', 'multipart/form-data');

			// Disables upload buttons until files are selected.
			(function () {
				var button, input, wpa = $('#wpa_wrapper');

				if (!wpa.length) {
					return;
				}

				button = wpa.find('input[type="submit"]');
				input = wpa.find('input[type="file"]');

				function toggleUploadButton() {
					button.prop('disabled', '' === input.map(function () {
							return $(this).val();
						}).get().join(''));
				}

				toggleUploadButton();

				input.on('change', toggleUploadButton);
			})();
		},

		choose_avatar: function () {

			var file_frame;

			$('#wpa_btn_choose_image').on('click', function (event) {
				event.preventDefault();

				// If the media frame already exists, reopen it.
				if (file_frame) {
					file_frame.open();
					return;
				}

				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					multiple: false  // Set to true to allow multiple files to be selected
				});

				// When an image is selected, run a callback.
				file_frame.on('select', function () {
					// We set multiple to false so only get one image from the uploader
					var attachment = file_frame.state().get('selection').first().toJSON();
					$('#wpa_preview').attr('src', attachment.sizes.thumbnail.url);
					$('#wpa_avatar_id').attr('value', attachment.id);
					$('#wpa_wrapper').addClass('has-custom-avatar');
				});

				// Finally, open the modal
				file_frame.open();
			});
		},

		choose_default_avatar: function () {

			var file_frame;

			$('#wpa_btn_choose_default_avatar').on('click', function (event) {
				event.preventDefault();

				// If the media frame already exists, reopen it.
				if (file_frame) {
					file_frame.open();
					return;
				}

				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					multiple: false  // Set to true to allow multiple files to be selected
				});

				// When an image is selected, run a callback.
				file_frame.on('select', function () {
					// We set multiple to false so only get one image from the uploader
					var attachment = file_frame.state().get('selection').first().toJSON();
					var avatar_full = attachment.url;
					var avatar_thumbnail = avatar_full;
					if (typeof attachment.sizes.thumbnail != 'undefined') {
						avatar_thumbnail = attachment.sizes.thumbnail.url;
					}
					$('#wpa_btn_choose_default_avatar').parent().find('img').attr('src', avatar_thumbnail);
					$('#wpa_btn_choose_default_avatar').parent().find('input[name="avatar_default"]').attr('value', avatar_full);
					$('input[name="wp_avatar[default_avatar_url]"]').attr('value', avatar_full);
				});

				// Finally, open the modal
				file_frame.open();
			});
		},


		remove_avatar: function () {
			$('#wpa_remove').on('click', function () {
				var ga = $('#wpa_preview').attr('data-ga');
				$('#wpa_preview').attr('src', ga);
				$('#wpa_avatar_id').attr('value', '');
				$('#wpa_wrapper').removeClass('has-custom-avatar');
			});
		},

	};

})(jQuery);