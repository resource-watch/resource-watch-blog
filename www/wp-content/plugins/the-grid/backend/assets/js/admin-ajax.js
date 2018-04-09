/*global jQuery:false*/
/*global ajaxurl:false*/
/*global TOMB_JS:false*/
/*global TG_element_color:false */
/*global TG_skin_elements */
/*global TG_field_value */
/*global TG_metaData*/
/*global TG_excludeItem*/
/*global tg_global_var*/
/*global tg_admin_global_var*/

(function($) {
				
	"use strict";
	
// ==================================================================
// Grid admin - Ajax Helper
// ==================================================================
	
	var xhr,
		msg_strings = tg_admin_global_var.box_messages,
		msg_icons   = tg_admin_global_var.box_icons;
	
	function Ajax_Helper(ajax_data) {
		
		// check if ajax request not proceeded and finish before running another one
		if (xhr && (xhr.readyState == 3 || xhr.readyState == 2 || xhr.readyState == 1)) {
			return false;
		}

		// prepare message icon
		var msg_state = msg_strings[ajax_data.func];
		
		// retrieve callbacks 
		var ajax_callbacks = ajax_data.callbacks;
		// delete callbacks to prevent fire function from $.ajax->data
		delete ajax_data.callbacks;
		
		// start ajax request
		xhr = $.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: ajax_data,
			beforeSend: function() {
				var msg_before = msg_icons.before+msg_state.before;
				if (ajax_callbacks.before) {
					ajax_callbacks.before(ajax_data, msg_before);
				}
			},
			error: function(response) {
				console.error(response);
				if (ajax_callbacks.error) {
					ajax_callbacks.error(ajax_data, response);
				}
			},
			success: function(response){
				// if errors occured in php script
				if (!response.success) {
					if (ajax_callbacks.error) {
						ajax_callbacks.error(ajax_data, response);
					}
					return false;
				} else {
					var msg_success = msg_icons.success+msg_state.success;
					if (ajax_callbacks.success) {
						ajax_callbacks.success(ajax_data, response, msg_success);
					}
				}
			}
		});
	}

// ==================================================================
// Helper to retrieve all metadata from Grid Settings
// ==================================================================
	
	window.TG_metaData = function(el){ 
	
		var meta_data = {},
			meta_val;
			
		el.each(function() {
			
			var $this = $(this),
				meta_ID = $this.find('[name]').attr('name');
				meta_ID = (meta_ID) ? meta_ID : '';
				meta_ID = meta_ID.replace('[]', '');
				
			if ($this.is('.tomb-type-radio')) {
				meta_val = $this.find('[name]:checked').val();
			} else if ($this.is('.tomb-type-image_select')) {
				meta_val = $this.find('input:checked').val();
			} else if ($this.is('.tomb-type-number')) {
				// force save 0 value instead of empty string
				meta_val = $this.find('input').val();
				meta_val = (meta_val == '0') ? '00' : meta_val;
			} else if ($this.is('.tomb-type-checkbox')) {
				meta_val = $this.find('input').is(':checked');
				meta_val = (meta_val) ? meta_val : null;
			} else if ($this.is('.tomb-type-checkbox_list')) {
				meta_val = [];
				$this.find('.tomb-checkbox-list:checked').each(function() {
    				meta_val.push($(this).val());
				});
			} else {
				meta_val = $this.find('[name]').val();
			}	
			if (meta_ID !== '') {
				meta_data[meta_ID] = meta_val;
			}
			
		});

		return meta_data;
		
	};

// ==================================================================
// Helper to get field value
// ==================================================================
	
	window.TG_field_value = function(el, prefix){ 
		
		var data = {};
		
		el.find('.tomb-row input, .tomb-row select, .tomb-row textarea').each(function() {
					
			var $this  = $(this), value,
				name   = $this.attr('name');
				
			if ($this.is(':radio')) {
				value = $this.closest('.tomb-row').find('[name]:checked').val();
			} else if ($this.is(':checkbox')) {
				value = $this.is(':checked');
				value = (value) ? true : '';
			} else {
				value = $this.val();
			}
				
			if (name) {
				data[name.replace(prefix,'')] = value;
			}
			
		});
		
		return data;
	
	};
	
// ==================================================================
// Helper to set element color (skin builder)
// ==================================================================
	
	window.TG_element_color = function(element, settings) {
		
		var tag,
			source        = (settings && settings.source) ? settings.source : [],
			html_tag      = (source && source.hasOwnProperty('html_tag')) ? source.html_tag : '',
			source_type   = (source && source.hasOwnProperty('source_type')) ? source.source_type : '',
			post_content  = (source && source.hasOwnProperty('post_content')) ? source.post_content : '',
			video_content = (source && source.hasOwnProperty('video_stream_content')) ? source.video_stream_content : '',
			woo_content   = (source && source.hasOwnProperty('woocommerce_content')) ? source.woocommerce_content : '';
			
		if (html_tag && source_type !== 'line_break') {
			
			tag = 'tg-'+html_tag.replace(/\d+/g, '')+'-tag';
		
		} else {
		
			if (source_type === 'post') {
				
				switch(post_content) {
					case 'get_the_title':
						tag = 'tg-h-tag';
						break;
					case 'get_the_excerpt':
						tag = 'tg-p-tag';
						break;
					case 'get_the_date':
						tag = 'tg-span-tag';
						break;
					case 'get_the_author':
						tag = 'tg-span-tag';
						break;
					case 'get_the_comments_number':
						tag = 'tg-span-tag';
						break;
					case 'get_the_likes_number':
						tag = 'tg-span-tag';
						break;
					case 'get_the_terms':
						tag = 'tg-span-tag';
						break;
					case 'get_item_meta':
						tag = 'tg-span-tag';
						break;
					default:
						tag = 'tg-p-tag';
				}
				
			} else if (source_type === 'video_stream') {
				
				switch(video_content) {
					case 'get_the_views_number':
						tag = 'tg-span-tag';
						break;
					case 'get_the_duration':
						tag = 'tg-div-tag';
						break;
					default:
						tag = 'tg-span-tag';
				}
				
			} else if (source_type === 'woocommerce') {
				
				switch(woo_content) {
					case 'get_product_price':
						tag = 'tg-div-tag';
						break;
					case 'get_product_full_price':
						tag = 'tg-div-tag';
						break;
					case 'get_product_regular_price':
						tag = 'tg-div-tag';
						break;
					case 'get_product_sale_price':
						tag = 'tg-div-tag';
						break;
					case 'get_product_add_to_cart_url':
						tag = 'tg-div-tag';
						break;
					default:
						tag = 'tg-span-tag';
				}
				
			} else if (source_type === 'media_button') {
				tag = 'tg-div-tag';
			} else if (source_type === 'social_link') {
				tag = 'tg-div-tag';
			} else if (source_type === 'icon') {
				tag = 'tg-div-tag';
			} else if (source_type === 'html') {
				tag = 'tg-div-tag';
			} else if (source_type === 'line_break') {
				tag = 'tg-line-break';
			}
		
		}
		
		$('.tg-element-draggable'+element+', .tg-element-custom'+element).removeClass('tg-h-tag tg-div-tag tg-p-tag tg-span-tag tg-line-break').addClass(tag);
	
	};

// ==================================================================
// Helper to get url parameter
// ===================================================================

	function TG_getUrlParameter(sParam) {
		
		var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;
	
		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');
	
			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : sParameterName[1];
			}
		}
		
	}
	
// ==================================================================
// Sticker banner/header
// ===================================================================
		
	var $tg_banner_holder = $('#tg-banner-holder'),
		$tg_banner = $('#tg-banner'),
		tg_fixed   = 'tg-fixed',
		admin_barH = $('#wpadminbar').height(),
		position;
	
	if ($tg_banner.hasClass('tg-banner-sticky')) {
		
		$(document).ready(function(e) {
			$tg_banner.width($tg_banner_holder.width()); 
		});
		
		$(document).on('click', '.tomb-menu-options .tomb-tab', function() {
			setTimeout(function() {
				$tg_banner.width($tg_banner_holder.width());
			}, 100);	
		});
		
		$(window).on('resize', function() {
			$tg_banner.width($tg_banner_holder.width()); 
		});
				
		$(document).scroll(function() {
			position = $tg_banner_holder.offset().top-admin_barH;
			if (position <= $(document).scrollTop()) {
				$tg_banner.addClass(tg_fixed);	
			} else {
				$tg_banner.removeClass(tg_fixed);
			}
		});
	
	}

// ==================================================================
// Close info Box Helper
// ===================================================================
	
	var info_box = '#tg-info-box',
		info_msg = '.tg-info-box-msg',
		box_load = 'tg-box-loading';
		
	$(document).on('click',info_box ,function(e) {
		if ($(info_box).is(':visible') && ($(e.target).is('.tg-info-inner') || $(e.target).is('.tg-close-infox-box'))) {
			$(info_box).removeClass(box_load);
			setTimeout(function() {
				$(info_box).removeClass(box_load);
			}, 300);
		}
	});

// ==================================================================
// GRID Overview page - sort/Page/Clone/Delete
// ==================================================================
	
	var over_holder   = '#tg-grid-list-holder',
		over_list     = '#tg-grid-list-wrap',
		over_sorters  = over_holder+' .tg-sort-table span',
		over_favorite = over_holder+' .tg-grid-list-favorite i',
		over_page_nb  = over_holder+' .page-numbers:not(.current)',
		over_per_page = over_holder+' .tg-list-number',
		over_clone    = over_holder+' .tg-clone',
		over_delete   = over_holder+' .tg-delete',
		over_event    = over_sorters+','+over_favorite+','+over_page_nb+','+over_clone+','+over_delete;
	
	// grid list overview event
	$(document).on('click', over_event, function(e) {
		e.preventDefault();
		var result;
		if ($(this).hasClass('tg-delete')) {
			result = confirm(tg_admin_global_var.box_messages.tg_delete.message);
			if (!result) {
				return false;
			}
		} else if ($(this).hasClass('tg-clone')) {
			result = confirm(tg_admin_global_var.box_messages.tg_clone.message);
			if (!result) {
				return false;
			}
		}
		Ajax_Helper(overview_data($(this)));
	});
	
	// grid list dropdown post per page
	$(document).on('change', over_per_page, function(e) {
		e.preventDefault();
		Ajax_Helper(overview_data($(this)));
	});
	
	function overview_data(el) {
		
		return {
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : el.data('action'),
			orderby   : el.closest('th').data('orderby'),
			order     : el.data('order'),
			number    : el.val(),
			page_nb   : el.data('page-nb'),
			post_ID   : el.data('grid-id'),
			meta_data : el.data('favorite'),
			callbacks : {
				before  : show_message_load,
				success : replace_grid_list,
				error   : show_message_error
			}
		};
		
	}
	
	// show box message
	function show_message_load(ajax_data, msg) {
		$(info_msg).html(msg);
		$(info_box).addClass(box_load);
	}
	
	// show error message
	function show_message_error(ajax_data, response) {
		$(info_msg).html(tg_admin_global_var.box_icons.error+response.message);
		setTimeout(function() {
			$(info_box).removeClass(box_load);
		}, 1500);
	}

	// hide box message
	function replace_grid_list(ajax_data, response, msg) {
		$(info_msg).html(msg);
		setTimeout(function() {
			$(info_box).removeClass(box_load);
			setTimeout(function() {
				var grid_list = $(response.content).html();
				$(over_list).html(grid_list);
				if (ajax_data.func === 'tg_clone') {
					$(over_holder+' .wp-list-table tbody tr').first().addClass('cloned');
				}
			}, 300);
		}, 800);
	}

// ==================================================================
// GRID Settings page - Save
// ==================================================================

	$(document).on('click', '#tg_post_save', function(e) {
		
		e.preventDefault();

		var ajax_data = {
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : $(this).data('action'),
			post_ID   : $('#post_ID').attr('value'),
			meta_data : JSON.stringify(TG_metaData($('#the_grid_metabox .tomb-row'))),
			callbacks : {
				before  : show_message_load,
				success : show_save_success,
				error   : show_save_error
			}
		};

		Ajax_Helper(ajax_data);
		
	});
	
	// show error message
	function show_save_error(ajax_data, response) {
		$(info_msg).html(response.message);
	}
	
	// show error message
	function show_save_success(ajax_data, response, msg) {
		
		$(info_msg).html(msg);
		
		setTimeout(function() {
			//change url (with pushstate if new post) to preserve current post on eventual refresh
			if (response.content) {
				// assign new post ID wordpress attribute
				$('#post_ID').attr('value',response.content);
				$('#the_grid_id').val('grid-'+response.content);
				var href = window.location.href,
					lastIndex = href.substr(href.lastIndexOf('/') + 1);
					href = href.replace(lastIndex, 'admin.php?page=the_grid_settings&id='+response.content);					
				if (history.pushState) {
					history.pushState(null, null, href);
				} else {
					window.location.href = href;
				}
				
			}
			$(info_box).removeClass(box_load);
		}, 800);
	}

// ==================================================================
// GRID Settings page - Delete
// ==================================================================

	$(document).on('click', '#tg_post_delete', function(e) {
		
		e.preventDefault();
		
		var result = confirm(tg_admin_global_var.box_messages.tg_delete.message);
		if (!result) {
			return false;
		}

		var ajax_data = {
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : $(this).data('action'),
			post_ID   : $('#post_ID').attr('value'),
			callbacks : {
				before  : show_message_load,
				success : redirect_to_list,
				error   : show_message_error
			}
		};
		Ajax_Helper(ajax_data);
		
	});
	
	function redirect_to_list(ajax_data, response, msg) {
		$(info_msg).html(msg);
		$('#tg_post_close')[0].click();
	}

// ==================================================================
// GRID Global Settings - Add Meta Data functionnality
// ==================================================================	

	var metakey_holder = '.tg-meta-key-holder',
		metakey_name   = '.tg-meta-name',
		metakey_key    = '.tg-meta-key-name',
		metakey_data   = '.the_grid_custom_meta_data',
		add_metakey    = '#tg_settings_add_metadata',
		remove_metakey = '#tg_settings_remove_metadata';
	
	$(add_metakey).on('click', function() {
		var target = $(this).closest('.tomb-row'),
			cloned = $(metakey_holder).eq(0).clone();
		cloned.find('input').val('');
		cloned.find(remove_metakey).show();
		cloned.insertBefore(target);
	});
	
	$(document).on('click', remove_metakey, function() {
		$(this).closest(metakey_holder).remove();
		update_custom_meta_data();
	});
	
	$(document).on('change', metakey_name+','+metakey_key, function() {
		update_custom_meta_data();
	});
	
	function update_custom_meta_data() {
		var tg_meta_data = [];
		$(metakey_holder).each(function(i) {
			var $this = $(this),
				name  = $this.find(metakey_name).val(),
				value = $this.find(metakey_key).val();
				
			if (name) {
				tg_meta_data[i] = {};
				tg_meta_data[i].name = name;
				tg_meta_data[i].key  = value;
			}
		});
		$(metakey_data).attr('value',JSON.stringify(tg_meta_data));
	}

// ==================================================================
// GRID Global Settings - Save/reset settings
// ==================================================================

	$(document).on('click','#tg_settings_save, #tg_settings_reset', function() {
		
		if ($(this).is('#tg_settings_reset')) {
			var result = confirm(tg_admin_global_var.box_messages.tg_reset_settings.message);
			if (!result) {
				return false;
			}
		}
		
		var func = $(this).data('action'),
			reset = (func === 'tg_reset_settings') ? true : null,
			setting_data = get_settings_data(reset);

		Ajax_Helper({
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : func,
			reset     : reset,
			setting_data : JSON.stringify(setting_data),
			callbacks : {
				before  : show_message_load,
				success : show_save_settings_success,
				error   : show_message_error
			}
		});

	});
	
	function get_settings_data(reset) { 
	
		var setting_data = {},
			setting_val;
			
		$('.tomb-row').each(function() {
			var $setting     = $(this).find('[name]');
			if ($setting.length) {
				var setting_name = $setting.attr('name');
				if (reset) {
					setting_val  = $setting.data('default');
				} else {
					setting_val  = $setting.val();
					if ($setting.is('.tomb-checkbox')) {
						setting_val = $setting.is(':checked');
						setting_val = (setting_val) ? setting_val : null;
					}
				}
				setting_data[setting_name] = setting_val;
			}
        });

		return setting_data;
		
	}
	
	function show_save_settings_success(ajax_data, response, msg) {
		$(info_msg).html(msg);
		setTimeout(function() {
			$(info_box).removeClass(box_load);
			if (response.content) {
				$('.metabox-holder.tg-settings').html($(response.content).html());
				TOMB_JS.init();
			}
		}, 800);
	}

	
// ==================================================================
// GRID Global Settings - Clear cache
// ==================================================================

	var clear_spinner = '#tg_clear_cache_msg .spinner',
		clear_text    = '#tg_clear_cache_msg strong';
	
	$(document).on('click','#tg_clear_cache', function() {
		
		Ajax_Helper({
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : $(this).data('action'),
			callbacks : {
				before  : function(ajax_data, msg) {
					$(clear_spinner).css('visibility','visible').show();
					$(clear_text).html(msg_strings[ajax_data.func].before);
				},
				success : function(ajax_data, response, msg) {
					$(clear_spinner).css('visibility','hidden').hide();
					$(clear_text).html(msg_strings[ajax_data.func].success);
				},
				error   : function(ajax_data, response) {
					$(clear_spinner).css('visibility','hidden').hide();
					$(clear_text).html(response.error);
				}
			}
		});

	});

// ==================================================================
// GRID Export
// ==================================================================

	$(document).on('click','#tg_export_items', function() {
		
		$('.tg-export-msg').html('');
		
		var item_IDs = {};
		$('.tg-export .tg-list-item-holder li').each(function(){ 
			var $this = $(this);
			if ($this.hasClass('selected')) {
				item_IDs[$this.data('type')] = (item_IDs[$this.data('type')]) ? item_IDs[$this.data('type')] : {};
				item_IDs[$this.data('type')][$this.data('name')] = $this.data('id'); 
			}
		});

		if (!$.isEmptyObject(item_IDs)) {
			$(info_msg).html(msg_icons.before+msg_strings.tg_export_items.before);
			$(info_box).addClass(box_load);
			$('[name="tg_export_items"]').val(JSON.stringify(item_IDs));
			$('[name="tg_export_items"]').trigger('click');
			setTimeout(function() {
				$(info_msg).html(msg_icons.success+msg_strings.tg_export_items.success);
				setTimeout(function() {
					$(info_box).removeClass(box_load);
				}, 800);
			}, 800);
		} else {
			$('.tg-export-msg').html(msg_strings.tg_export_items.empty);
			setTimeout(function() {
				$('.tg-export-msg').html('');
			}, 1500);
		}

	});

// ======================================================
// Export grid list selection
// ======================================================
		
	$(document).on('click', '.tg-list-item-wrapper[data-multi-select="1"] .tg-list-item-holder li', function() {
		$(this).toggleClass('selected');
	});
		
	$(document).on('keyup','.tg-list-item-search', function() {
		var val = $(this).val();
		tg_search_grid(val);
	});
	
	$(document).on('click', '.tg-list-item-add-all', function() {
		$(this).prevAll('.tg-list-item-wrapper').find('.tg-list-item-holder li').addClass('selected');
	});
	
	$(document).on('click', '.tg-list-item-clear', function() {
		$(this).prevAll('.tg-list-item-wrapper').find('.tg-list-item-holder li').removeClass('selected');
	});
		
	function tg_search_grid(val) {
		$('.tg-list-item-holder li').each(function(index, element) {
			var $this = $(this);
			var grid = $this.text();
			if (grid.toLowerCase().indexOf(val) >= 0) {
               	$this.show();
			} else {
				$this.hide();
			}
		});
	}

// ==================================================================
// Import grid(s)
// ==================================================================

	var import_data = null,
		import_content = '.tg-import-content',
		import_spinner = '.tg-import-loading .spinner',
		import_file_uploaded      = '#tg-import-file[type="file"]',
		import_message_success    = '.tg-import-msg-success',
		import_item_message_error = '.tg-import-item-msg-error',
		import_message_error      = '.tg-import-msg-error';
	
	$(document).on('click','#tg_import_items, #tg-import-demo', function() {
		
		var item_names = [];
		var y = 0;
		$('.tg-import .tg-list-item-holder li').each(function(i, selected){ 
			if ($(this).hasClass('selected')) {
				item_names[y] = $(selected).data('name'); 
				y++;
			}
		});

		if (item_names.length !== 0 || $(this).data('grid-demo')) {

			Ajax_Helper({
				nonce      : tg_admin_global_var.nonce,
				action     : 'backend_grid_ajax',
				func       : $(this).data('action'),
				item_data  : import_data,
				item_names : item_names,
				grid_demo  : $(this).data('grid-demo'),
				callbacks : {
					before  : show_message_load,
					success : function(ajax_data, response, msg) {
						$(info_msg).html(msg);
						setTimeout(function() {
							$(info_box).removeClass(box_load);
							if (response.content) {
								var grid_list = $(response.content).html();
								$(over_list).html(grid_list);
							}
						}, 800);
					},
					error   : show_message_error
				}
			});
		
		} else {
			
			$(import_item_message_error).html(msg_strings.tg_import_items.empty);
			setTimeout(function() {
				$(import_item_message_error).html('');
			}, 1500);
		
		}

	});
	
	$(document).on('click','#tg-import-read-demo', function() {
		
		clear_import_msg();
			
		var func = $(this).data('action'),
			ajax_data = {
			nonce  : tg_admin_global_var.nonce,
			action : 'backend_grid_ajax',
			func   : func,
			demo   : true
		};
		
		get_file_content(ajax_data, 'application/x-www-form-urlencoded; charset=UTF-8', true, func);
		
	});

	$(document).on('click','#tg-import-read-file', function(event) {
		
		clear_import_msg();
		
		var ajax_data = new FormData(),
			func = $(this).data('action'),
			file = $(document).find(import_file_uploaded),
			individual_file = file[0].files[0];
			
		if (!individual_file) {
			$(import_message_error).html(msg_strings[func].empty);
			return false;
		}

		ajax_data.append('file', individual_file);
		ajax_data.append('action', 'backend_grid_ajax');
		ajax_data.append('nonce', tg_admin_global_var.nonce); 
		ajax_data.append('func', func);

		get_file_content(ajax_data, false, false, func);
		
	});
	
	function get_file_content(ajax_data, contentType, processData, func) {

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			dataType: 'json',
			data: ajax_data,
			contentType: contentType,
			processData: processData,
			beforeSend: function(response) {
				$(import_message_success).html(msg_strings[func].before);
				$(import_spinner).show();
			},
			error: function(response) {
				$(import_message_error).html(response.message);
				$(import_spinner).hide();
			},
			success: function(response) {
				$(import_spinner).hide();
				if (!response.success) {
					$(import_message_success).html('');
					$(import_message_error).html(response.message);		
				} else {
					$(import_content).html(response.content);
					$(import_message_success).html('');
					import_data = response.message;
				}
			}
		});
		
	}
	
	function clear_import_msg() {
		import_data = null;
		$(import_content)
			.add(import_message_error)
			.add(import_message_success).html('');
	}
	
// ==================================================================
// GRID Settings - Retrieve skins preview
// ==================================================================

	var current_post,
		current_post_skin,
		$source_type = $('.the_grid_source_type.tomb-type-image_select'),
		$grid_skins = $('#tg-grid-skins'),
		$grid_skins_loading = $('#tg-grid-skins-loading'),
		dft_post_value = $('.the_grid_style input:checked').val(),
		dft_post_style = (dft_post_value === 'justified') ? 'grid' : dft_post_value,
		dft_maso_skin  = (typeof tg_admin_global_var.default_skin !== 'undefined') ? tg_admin_global_var.default_skin.masonry : null,
		dft_grid_skin  = (typeof tg_admin_global_var.default_skin !== 'undefined') ? tg_admin_global_var.default_skin.grid : null,
		dft_skin_name  = (dft_post_style === 'grid') ?  dft_grid_skin : dft_maso_skin,
		skin_data_arr  = $.parseJSON($('.tomb-grid-skins').val());
		skin_data_arr  = (skin_data_arr && !$.isEmptyObject(skin_data_arr)) ? skin_data_arr : {};

	$('.tomb-tab-content.Skins').find('#section_skins_start').addClass('has-grid-skin');
		
	if ($('#tg-grid-skins').length > 0) {
		
		Ajax_Helper({
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : 'tg_skin_selector',
			post_ID   : $('#post_ID').attr('value'),
			callbacks : {
				before  : function(ajax_data, response, msg) {
					$grid_skins_loading.html(msg_strings.tg_skin_selector.before);
				},
				success : function(ajax_data, response, msg) {
					
					if (!response.success) {
						$grid_skins_loading.removeClass('loading-anim').html(response.message);
						return false;
					}
					
					$grid_skins.html(response.content).find('.tg-grid-holder').The_Grid();
					$grid_skins.find('[data-filter=".selected"] .tg-filter-count').html(1);
					var interval = setInterval(function(){ 
						if ($grid_skins.find('.tg-grid-loaded').length === 2) {
							$(window).trigger(tg_debounce_resize);
							clearInterval(interval);
						}
					}, 50);
					
					// set selected skins
					grid_skins_preview();
					update_selected_skin();	
									
				},
				error : function(ajax_data, response) {
					$grid_skins_loading.removeClass('loading-anim').html(response.message);
				}
			}
		});
		
		var grid_skins_preview = function() {
			
			$('.tomb-post-type-skin').on('change', function() {
				update_selected_skin();
				$('#tg-grid-skins .tg-grid-wrapper:not(.skin-hidden) .tg-filter-active').click();
			});
			
			$('#the_grid_post_type').on('change', function() {
				update_post_skin($(this));
			});
			
			$('.the_grid_style input').on('change', function() {
				skin_data_arr = {};
				$('.the_grid_social_skin').val('');
				$('#tg-grid-'+dft_post_style+'-skin').addClass('skin-hidden');
				dft_post_style = ($(this).val() === 'justified') ? 'grid' : $(this).val();
				dft_skin_name  = (dft_post_style === 'grid') ?  dft_grid_skin : dft_maso_skin;
				$('#tg-grid-'+dft_post_style+'-skin').removeClass('skin-hidden');
				update_selected_skin();
			});
			
			$source_type.on('change', function() {
				update_selected_skin();
			});
	
		};
		
		$(document).on('click','#tg-grid-skins .tg-item, #tg-grid-skins .tg-item *', function(e) {
			
			var eclass = $(e.target).attr('class');
			
			if (eclass && (eclass.indexOf('tg-edit-skin') > 0 || eclass.indexOf('dashicons-admin-tools') >= 0)) {
				return;
			} else {
				e.preventDefault();
				if ($source_type.find('input[type="radio"]:checked').val() == 'post_type') {
					// retieve current post for skin selection
					get_current_post_skin();
					skin_data_arr[current_post] = $(this).closest('.tg-item').find('.tg-item-skin-name').data('slug');
				} else {
					current_post_skin = $(this).closest('.tg-item').find('.tg-item-skin-name').data('slug');
					$('.the_grid_social_skin').val(current_post_skin);
				}
				// update skin options array
				update_selected_skin();
			}
			
		});
		
		var update_post_skin = function(el) {

			var $this = el,
				skin_options = null,
				post_type = ($this.val()) ? $this.val() : ['post'];
				
			for (var i = 0; i < post_type.length; i++) {
				var slug = post_type[i];
				var name = $('#the_grid_post_type [value="'+slug+'"]').text();
				skin_options += '<option value="'+slug+'">'+name+'</option>';
			}
			
			$('select.tomb-post-type-skin option').remove();
			$('select.tomb-post-type-skin').append(skin_options).trigger('change');	
			skin_data_arr = {};
			update_selected_skin();

		};
		
		var update_post_skin_arr = function() {
			$('input.tomb-grid-skins').val(JSON.stringify(skin_data_arr));
		};
		
		var get_current_post_skin = function() {
			current_post = $('.tomb-post-type-skin').val();
		};
		
		var update_selected_skin = function() {
			
			if ($source_type.find('input[type="radio"]:checked').val() == 'post_type') {
			
				// retieve current post for skin selection
				get_current_post_skin();
				
				// skin data option is empty then define default values
				if (!Object.keys(skin_data_arr).length) {
					$('select.tomb-post-type-skin option').each(function() {
						current_post_skin = skin_data_arr[$(this).val()] = dft_skin_name;
					});
				} else {
					current_post_skin = skin_data_arr[current_post];
					if (!current_post_skin) {
						current_post_skin = skin_data_arr[current_post] = dft_skin_name;
					}
				}
				
				// update skin options array
				update_post_skin_arr();
			
			} else {
				current_post_skin = $('.the_grid_social_skin').val();
				current_post_skin = (!current_post_skin) ? dft_skin_name : current_post_skin;
			}

			// add class to current selected skin
			$grid_skins.find('.tg-item').removeClass('selected');
			$grid_skins.find('.tg-item-skin-name[data-slug="'+current_post_skin+'"]').closest('.tg-item').addClass('selected');

		};

	}

// ==================================================================
// GRID Settings - Grid preview
// ==================================================================

	var $grid_preview          = $('#tg-grid-preview'),
		$grid_preview_holder   = $('#tg-grid-preview-inner'),
		$grid_preview_loader   = $('#tg-grid-preview-loading'),
		$grid_preview_viewport = $('#tg-grid-preview-viewport'),
		$grid_preview_settings = $('#tg-grid-preview-settings'),
		$grid_preview_tooltip  = $('.tg-filter-tooltip'),
		$grid_preloader_styles = $('.tg-grid-preloader-styles'),
		tg_ww = 0, tg_colw,
		tg_debounce_resize = (typeof tg_global_var !== 'undefined' && tg_global_var.debounce) ? 'debouncedresize' : 'resize';
	
	$(document).on('click','#tg_post_preview, #tg-grid-preview-refresh', function() {
		
		// check if ajax request not proceeded and finish before running another one
		if (xhr && (xhr.readyState == 3 || xhr.readyState == 2 || xhr.readyState == 1)) {
			return false;
		}
		
		$('body').css('overflow','hidden');
		$grid_preview_tooltip.remove();
		
		$.TG_media_destroy($grid_preview_holder);
		$grid_preview_loader.show();
		$grid_preview_holder.html('');

		Ajax_Helper({
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : 'tg_grid_preview',
			post_ID   : $('#post_ID').attr('value'),
			meta_data : TG_metaData($('#the_grid_metabox .tomb-row')),
			callbacks : {
				before  : function(ajax_data, response, msg) {
					$grid_preview.addClass('tg-show-preview');
					$grid_preview_loader.html(msg_strings.tg_grid_preview.before);
				},
				success : function(ajax_data, response, msg) {
					if (response.content) {
						$grid_preview_loader.html('');
						$grid_preview_holder.append(response.content);
						defined_screen_widths();
						grid_preview_width();
						TG_excludeItem();
						$grid_preloader_styles.removeAttr('scoped');
						$grid_preview_holder.find('.tg-grid-holder').The_Grid();
						$.TG_media_init();
					} else {
						$grid_preview_holder.html(response.content);
					}
				},
				error : function(ajax_data, response) {
					$grid_preview_loader.html(response.message);
				}
			}
		});
		
	});
	
	function defined_screen_widths() {
		tg_colw = $grid_preview_holder.find('.tg-grid-holder').data('cols');
		if (tg_colw) {
			tg_colw.sort(function(a, b){return b[0]-a[0];});
			$('.tg-grid-preview-mobile').data('val',tg_colw[5][0]);
			$('.tg-grid-preview-tablet-small').data('val',tg_colw[4][0]);
			$('.tg-grid-preview-tablet').data('val',tg_colw[3][0]);
			$('.tg-grid-preview-desktop-small').data('val',tg_colw[2][0]);
			$('.tg-grid-preview-desktop-medium').data('val',tg_colw[1][0]);
			$('.tg-grid-preview-desktop-large').data('val','100%');
		}
	}
	
	function grid_preview_width() {	
		var ww = $(window).width(),
			width,
			view;

		var current_w = $grid_preview_viewport.find('.tg-viewport-active').data('val');
			current_w = (current_w == '100%') ? ww : current_w;

		if (tg_ww !== ww && tg_colw) {
			tg_colw[0][0] = tg_ww = ww;
			$.each(tg_colw, function(i, col) {
				if (col[0] >= ww) {
					view  = 6-1-i;
					width = col[0];	
				} else {
					return false;
				}
			});
			if (current_w >= ww) {
				width = (width > tg_colw[1][0] && width > tg_ww) ? tg_ww : width;
				$grid_preview_viewport.find('div').removeClass('tg-viewport-active');
				$grid_preview_viewport.children().eq(view).show().addClass('tg-viewport-active');
				$grid_preview_holder.width(width);
			}
			$grid_preview_viewport.find('div').show();
			view = (!view) ? 0 : view;
			if (view+1 < 6) {
				for (var i = view+1; i <= 6; i++) {
					$grid_preview_viewport.children().eq(i).hide();
				}
			}
		}
	}
	
	$(window).resize(function() {
		grid_preview_width();
	});
	
	$grid_preview_viewport.find('div').click(function() {
		if ($grid_preview_holder.find('.tg-grid-loaded').length > 0) {
			var $this = $(this),
				size = $this.data('val');
			$grid_preview_viewport.find('div').removeClass('tg-viewport-active');
			$this.addClass('tg-viewport-active');
			$grid_preview_holder.width(size);
			$(window).trigger(tg_debounce_resize);
		}
	});
	
	
	$(document).on('click','#tg-grid-preview-close', function() {
		$grid_preview.removeClass('tg-show-preview');
		$('body').css('overflow','visible');
		setTimeout(function() {
			$grid_preview_settings.removeClass('loaded');
			$grid_preview_holder.html('');
			$grid_preview_tooltip.remove();
		}, 400);
	});
	
	$(document).on('click','[data-target="Skins"].tomb-tab', function() {
		setTimeout(function() {
			$(window).trigger('resize');
		}, 100);
	});
	
	$(document).on('click','#tg-grid-preview-inner .tg-item *', function(e) {
		e.preventDefault();
	});

// ==================================================================
// GRID Settings - Exclude items functionnality
// ==================================================================

	window.TG_excludeItem = function(url, gkey){ 
	
		var not_in  = $('#the_grid_post_not_in').val(),
			not_arr = not_in.split(', ');
			
		$('#tg-grid-preview .tg-item-exclude').each(function() {

			var $this = $(this),
				ID    = $this.data('id').toString();
				
			if($.inArray(ID, not_arr) > -1){
				$this.prevAll('.tg-item-hidden-overlay').addClass('tg-item-hidden');
				$this.addClass('tg-item-excluded');
			}
			
		});
		
	};
	
	$(document).on('click', '.tg-item-exclude', function() {
		
		var $this = $(this),
			ID = $this.data('id').toString(),
			not_in = $('#the_grid_post_not_in').val(),
			not_arr = not_in.split(', ');
		
		if($.inArray(ID, not_arr) == -1){
			$this.prevAll('.tg-item-hidden-overlay').addClass('tg-item-hidden');
			$this.addClass('tg-item-excluded');
			var separator = (not_in === '') ? '' : ', ';
			$('#the_grid_post_not_in').val(not_in+separator+ID);
			
		} else {
			$this.prevAll('.tg-item-hidden-overlay').removeClass('tg-item-hidden');
			$this.removeClass('tg-item-excluded');
			not_arr = jQuery.grep(not_arr, function(value) {
				return value != ID;
			});
			not_arr = not_arr.join(', ');
			$('#the_grid_post_not_in').val(not_arr);
		}
	});

// ==================================================================
// GRID Settings - item settings popup
// ==================================================================

	var $item_settings = $('#tg-grid-preview-settings');    
	
	// retrieve item settings metadata popup
	$(document).on('click','.tg-item-settings', function() {
		
		var $this  = $(this);	
		var action = $this.data('action');

		$.ajax({
			type:'GET',
			url: action,
			beforeSend: function(){
				$this.addClass('loading');
				$item_settings.removeClass('loaded');
			},
			success: function(data){
				$this.removeClass('loading');
				data = $(data).find('#the_grid_item_formats').html();
				$('#tg-grid-preview-settings-save').data('id',$this.data('id'));
				$item_settings.find('>*').not('#tg-grid-preview-settings-footer').remove();
				$item_settings.prepend(data);
				$item_settings.find('.hndle').append('<div id="tg-grid-preview-settings-close" class="dashicons dashicons-no-alt"></div>');
				$item_settings.draggable();
				$item_settings.addClass('loaded');
				TOMB_JS.init();
			}
		});
	});
	
	// Close item sttings metadata popup
	$(document).on('click','#tg-grid-preview-settings-close', function() {
		$item_settings.removeClass('loaded');
	});
	
// ==================================================================
// GRID Settings - item settings popup save
// ==================================================================

	$(document).on('click','#tg-grid-preview-settings-save', function() {
		
		var $this = $(this),
			tg_save_timeout,
			$item_sttings_popup = $this.closest('#tg-grid-preview-settings').find('.tomb-row');

		Ajax_Helper({
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : 'tg_save_item_settings',
			post_ID   : $this.data('id'),
			meta_data : JSON.stringify(TG_metaData($item_sttings_popup)),
			callbacks : {
				before  : function(ajax_data, response, msg) {
					$item_settings.find('.tg-grid-preview-settings-wait').html(msg_strings.tg_save_item_settings.before);
					$item_settings.find('.spinner').show();
					$item_settings.addClass('saving');
				},
				success : function(ajax_data, response, msg) {
					clearTimeout(tg_save_timeout);
					$item_settings.find('.spinner').hide();
					$item_settings.find('.tg-grid-preview-settings-wait').html('');
				},
				error : function(ajax_data, response) {
					$item_settings.find('.spinner').hide();
					$item_settings.find('.tg-grid-preview-settings-wait').html(msg_strings.tg_save_item_settings.error);
					tg_save_timeout = setTimeout(function() {
						$item_settings.removeClass('saving');
					}, 2500);
				}
			}
		});
		
	});

// ==================================================================
// Envato Authorization - Access Token
// ==================================================================
	
	$(document).on('click', '.tg-button-register', function() {
		$(this).closest('.tg-container').addClass('tg-container-anim');	
	});
	
	$(document).on('click','#tg-grid-save-envato-api-token', function() {
		
		var $this = $(this);
		
		Ajax_Helper({
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : 'tg_save_envato_api_token',
			token     : $('[name="the_grid_envato_api_token"]').val(),
			callbacks : {
				before  : function(ajax_data, msg) {
					$this.nextAll('.spinner').css('visibility', 'visible').show();
					$this.nextAll('strong').html(msg_strings[ajax_data.func].before);
				},
				success : function(ajax_data, response, msg) {
					$this.nextAll('.spinner').css('visibility', 'hidden').hide();
					$this.nextAll('strong').html(msg_strings[ajax_data.func].success);
					if ($('.tg-custom-skins-overview').length) {
						location.reload();
					} else {
						setTimeout(function() {
							$('.tg-row').html($(response.content).html());
						}, 1500);
					}
				},
				error : function(ajax_data, response) {
					$this.nextAll('.spinner').css('visibility', 'hidden').hide();
					$this.nextAll('strong').html(response.message);
					setTimeout(function() {
						$('.tg-row').html($(response.content).html());
					}, 2000);
				}
			}
		});
		
	});
	
// ==================================================================
// The Grid - Check plugin update
// ==================================================================
	
	
	$(document).on('click','#tg-check-update', function() {

		var $this = $(this);
		
		Ajax_Helper({
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : 'tg_check_for_update',
			callbacks : {
				before  : function(ajax_data, msg) {
					$this.nextAll('.spinner').css('visibility', 'visible').show();
					$this.nextAll('strong').html(msg_strings[ajax_data.func].before);
				},
				success : function(ajax_data, response, msg) {
					$this.nextAll('.spinner').css('visibility', 'hidden').hide();
					$this.nextAll('strong').html(msg_strings[ajax_data.func].success);
					setTimeout(function() {
						$this.nextAll('strong').html('');
						$('.tg-row').html($(response.content).html());
					}, 1500);
				},
				error : function(ajax_data, response) {
					$this.nextAll('.spinner').css('visibility', 'hidden').hide();
					$this.nextAll('strong').html(response.message);
					setTimeout(function() {
						$this.nextAll('strong').html('');
					}, 2000);
				}
			}
		});
		
	});
	
// ==================================================================
// The Grid - Update plugin
// ==================================================================
	
	$(document).on('click','.update-now.tg-button-live-update', function(e) {
		
		var $this = $(this);
		$this.nextAll('.spinner').css('visibility', 'visible').show();
		$this.nextAll('strong').html(msg_strings.tg_update_plugin.before);
		
	});
	
// ==================================================================
// The Grid - Custom Skins Overview
// ==================================================================
	
	// skins overview style buttons
	$(document).on('click', '.tg-skins-style-button', function() {
		
		var $this = $(this),
			style = $this.data('style');
			
		$('.tg-skins-style-button').removeClass('tg-selected');
		$this.addClass('tg-selected');
		$('.tg-grid-wrapper').addClass('skin-hidden');
		$('#tg-grid-'+style+'-skin').removeClass('skin-hidden');
		
	});
	
	/*** prevent click on item link (custom skin preview) ***/
	$(document).on('click','.tg-custom-skins-overview .tg-item-inner, .tg-custom-skins-overview .tg-item-inner *', function(e) {
		e.preventDefault();
	});

// ==================================================================
// The Grid - Import demo skins
// ==================================================================
	
	// import demo skins
	$(document).on('click', '#tg-import-skin-demo', function() {

		Ajax_Helper({
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : 'tg_import_demo_skins',
			callbacks : {
				before  : show_message_load,
				success : function(ajax_data, response, msg) {
					$(info_msg).html(msg);
					setTimeout(function() {
						$(info_box).removeClass(box_load);
						if (response.content) {
							var skin_list = $(response.content);
							$('.tg-custom-skins-overview').replaceWith(skin_list);
							$('.tg-grid-holder').The_Grid();
						}
					}, 800);
				},
				error   : show_message_error
			}
		});
		
	});

// ==================================================================
// The Grid - Get custom skin settings
// ==================================================================

	function TG_fetch_skin_data() {
		
		var json = {};
		
		json.item = {};
		json.elements = {};
		
		// store elements settings for each item area
		$('.tg-skin-build-inner [data-item-area]').each(function() {
			
			var area = $(this).data('item-area');
			json.elements[area] = {};
			
			if ($(this).is(':visible')) {
				$(this).find('> .tg-element-draggable').each(function() {
					
					var $this = $(this);
					json.elements[area][$this.data('name')] = $this.data('settings');
					
					/*** store element content ***/
					var $content = $(this).clone();
					$content.find('.tg-element-helper, .ui-resizable-handle, .tg-element-icon').remove();
					$content = $content.html();
					json.elements[area][$this.data('name')].content = $content;

				});
			}
			
        });

		// store item layout settings
		$('.tg-panel-item > div > [data-settings]').each(function() {
			
			var prefix  = $(this).data('prefix');
			var element = $(this).data('settings');

			if ($(this).data('style') && $.inArray(element, ['animations', 'action', 'z-index']) === -1) {

				if (!json.item.containers) {
					json.item.containers = {};
				}
				json.item.containers[element] = {};
				
				$(this).find('[data-settings="styles"]').each(function() {
					
					var settings = $(this).data('settings');
					json.item.containers[element][settings] = {};
					
					$(this).find('[data-settings]').each(function() {
						
						prefix = $(this).data('prefix');
						json.item.containers[element][settings][$(this).data('settings')] = TG_field_value($(this), prefix);
						
						// add z-index (layer depth)
						if ($(this).data('settings') === 'idle_state') {
							var z_index = $('[name="'+prefix+'z-index"]').val();
							json.item.containers[element][settings][$(this).data('settings')]['z-index'] = (z_index) ? z_index : '';
						}
						
					});
					
					// add hover setting
					json.item.containers[element][settings].is_hover = $(this).find('[name="is_hover"]').is(':checked');
					
					// add animation settings
					var $animation = $('.tg-panel-item [data-settings="animation"] [data-settings=\''+element+'\']');
					json.item.containers[element].animation = TG_field_value($animation, $animation.data('prefix'));
					
					// add action settings
					var $action = $('.tg-panel-item [data-settings="action"] [data-settings=\''+element+'\']');
					json.item.containers[element].action = TG_field_value($action, $action.data('prefix'));
					
				});
				
			} else if ($.inArray(element, ['animations', 'action', 'z-index']) === -1){
				
				if (element != 'global_css'){
					json.item[$(this).data('settings')] = TG_field_value($(this), prefix);
				} else {
					json.item[$(this).data('settings')] = $(this).find('textarea').val();
				}
				
			}
            
        });

		// store skin name
		json.item.layout.skin_name = $('[name="skin_name"]').val();

		return json;
		
	}
	
// ==================================================================
// The Grid - Save skin
// ==================================================================

	$(document).on('click', '#tg_skin_save', function() {
		
		var $this   = $(this);
		
		var json = TG_fetch_skin_data();

		Ajax_Helper({
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : $this.data('action'),
			id        : TG_getUrlParameter('id'),
			settings  : JSON.stringify(json),
			callbacks : {
				before  : show_message_load,
				success : function(ajax_data, response, msg) {
					
					var href = window.location.href,
					lastIndex = href.substr(href.lastIndexOf('/') + 1);
					href = href.replace(lastIndex, 'admin.php?page=the_grid_skin_builder&id='+response.message);					
					if (history.pushState) {
						history.pushState(null, null, href);
					} else {
						window.location.href = href;
					}
					
					$(info_msg).html(msg);
					setTimeout(function() {
						$(info_box).removeClass(box_load);
					}, 800);
					
				},
				error   : show_message_error
			}
		});
		
	});
	
// ==================================================================
// The Grid - Download skin
// ==================================================================

	$(document).on('click', '#tg_download_skin', function() {
		
		var json = TG_fetch_skin_data();
		$('[name="tg_export_skin"]').val(JSON.stringify(json));
		$('[name="tg_export_skin"]').trigger('click');
		
		$(info_msg).html(msg_icons.before+msg_strings.tg_download_skin.before);
		$(info_box).addClass(box_load);
		setTimeout(function() {
			$(info_msg).html(msg_icons.success+msg_strings.tg_download_skin.success);
			setTimeout(function() {
				$(info_box).removeClass(box_load);
			}, 800);
		}, 800);
		
	});

// ==================================================================
// The Grid - Delete/Clone skin
// ==================================================================
	
	$(document).on('click', '.tg-delete-skin, .tg-clone-skin', function() {

		var $this = $(this),
			$tab  = $('.tg-skins-style-button.tg-selected').data('style'),
			result  = confirm(tg_admin_global_var.box_messages[$this.data('action')].message);
		
		if (!result) {
			return false;
		}
		
		Ajax_Helper({
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : $this.data('action'),
			id        : $this.data('id'),
			callbacks : {
				before  : show_message_load,
				success : function(ajax_data, response, msg) {
					$(info_msg).html(msg);
					setTimeout(function() {
						$(info_box).removeClass(box_load);
						if (response.content) {
							var skin_list = $(response.content);
							skin_list.find('.tg-skins-style-button').removeClass('tg-selected');
							$('.tg-custom-skins-overview').replaceWith(skin_list);
							$('.tg-grid-holder').The_Grid();
							$('.tg-skins-style-button[data-style="'+$tab+'"]').trigger('click');
						}
					}, 800);
				},
				error   : show_message_error
			}
		});
		
	});

// ==================================================================
// The Grid - Get skin elements
// ==================================================================
	
	// declare global var for skin element
	window.TG_skin_elements = {};
	
	var $elements_holder = $('.tg-elements-inner');
	
	if ($elements_holder.length) {
		
		var $elements_msg = $('<div class="tg-panel-elements-msg"></div>');
		
		$.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: {
				nonce  : tg_admin_global_var.nonce,
				action : 'backend_grid_ajax',
				func   : 'tg_get_elements'
			},
			beforeSend: function() {
				
				$elements_msg.html(msg_strings.tg_get_elements.before);
				$elements_holder.find('.tg-native-elements, .tg-custom-elements').html($elements_msg);
				
			},
			error: function(response) {
				
				$elements_msg.html(msg_strings.tg_get_elements.error);
				$elements_holder.find('.tg-native-elements, .tg-custom-elements').html($elements_msg);
				
			},
			success: function(response){
				
				$elements_holder.find('.tg-native-elements, .tg-custom-elements').html('');
				
				// append elements
				if (response.content) {
					$elements_holder.find('.tg-element-styles-holder').append(response.content.styles);
					$elements_holder.find('.tg-native-elements').append(response.content.native_elements);
					$elements_holder.find('.tg-custom-elements').append(response.content.custom_elements);
				}
				
				// store elements settings for later
				window.TG_skin_elements = (response.content.settings) ? response.content.settings : {};

				// set elements colors
				if (typeof TG_skin_elements === 'object') {
					
					for (var slug in TG_skin_elements) {

						if (TG_skin_elements.hasOwnProperty(slug)) {
							
							var settings = JSON.parse(TG_skin_elements[slug]);
							TG_element_color('[data-slug="'+slug+'"]', settings);
							
						}
						
					}
					
				}
				
			}
		});
	
	}
	
// ==================================================================
// The Grid - Save skin element
// ==================================================================

	var tg_element_name;
	var tg_overwrite_element = '';
	
	$(document).on('click', '#tg-element-save', function() {

		var $this = $(this);
		
		// if overwrite mode name input box
		if (!tg_overwrite_element) {
			tg_element_name = window.prompt(tg_admin_global_var.box_messages[$this.data('action')].message, 'My element');
		}
		
		if (tg_element_name === null) {
        	return;
    	}
		
		var $element = $('.tg-element-draggable.tg-element-selected');
		
		// get element settings
		var tg_element_settings = $element.data('settings');
		
		/*** get element content/color scheme ***/
		var $content = $element.clone();
		$content.find('.tg-element-helper, .ui-resizable-handle, .tg-element-icon').remove();
		$content = $content.html();
		tg_element_settings.content = $content;
		tg_element_settings['color-scheme'] = ($element.closest('.tg-light').length) ? 'tg-light' : 'tg-dark';	

		// Generate native element json array
		//console.log(JSON.stringify(tg_element_settings));

		Ajax_Helper({
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : $this.data('action'),
			overwrite : tg_overwrite_element,
			element_name     : tg_element_name,
			element_settings : tg_element_settings,
			callbacks : {
				before  : show_message_load,
				success : function(ajax_data, response, msg) {

					// if element exists
					if (response.content === 'exists') {
						
						$(info_box).removeClass(box_load);
						tg_overwrite_element  = confirm(tg_admin_global_var.box_messages[$this.data('action')].message2);
						
						if (tg_overwrite_element) {
							$('#tg-element-save').trigger('click');
						} else {
							tg_overwrite_element = '';
						}
						
						return false;
						
					}
					
					
					var $element = $(response.content.markup);
					if (tg_overwrite_element) {

						var $old_element = $('.tg-element-custom[data-slug="'+response.content.slug+'"]');
						// replace existing element if overwrite
						if ($old_element.closest('.tomb-tab-content').hasClass($element.find('.tomb-tab').data('target'))) {

							$old_element.closest('.tg-element-holder').replaceWith($element.find('.tg-element-holder'));
						
						// if existing element have different content from new, then check tab and remove if empty
						} else {
							
							tg_overwrite_element = false;
							var $tab_content = $old_element.closest('.tomb-tab-content');
							
							if ($tab_content.find('.tg-element-holder').length === 1) {
		
								var classes = $tab_content.attr('class').split(/\s+/),
									$tab_li = $('.tg-custom-elements').find('[data-target="'+classes[1]+'"]');
								
								// move tab back remove tab holder and tab li
								$tab_content.closest('.tg-move-tab').removeClass('tg-move-tab');
								$tab_li.remove();
								$tab_content.remove();
								
							}
							
							// remove old element
							$old_element.closest('.tg-element-holder').remove();
							
						}
						
					}
					
					if (!tg_overwrite_element) {
						
						// append new element with styles
						var tab     = $element.find('.tomb-tab').data('target'),
							$holder = $('.tg-elements-inner .tg-custom-elements').find('.'+tab);

						if ($holder.length) {
							$holder.prepend($element.find('.tg-element-holder'));
						} else {
							
							var $element_list = $('.tg-elements-inner .tg-custom-elements ul');
							
							if (!$element_list.length) {
								
								$('.tg-elements-inner .tg-custom-elements').append($element);

							} else {
							
								$element_list.append($element.find('li'));
								$element.find('.tomb-tab-content').insertAfter($('.tg-elements-inner .tg-custom-elements ul'));
								
								// sort li
								var listitems = $element_list.children('li').get();
								listitems.sort(function(a, b) {
								   return $(a).data('target').match(/\d+/) - $(b).data('target').match(/\d+/);
								});
								$.each(listitems, function(idx, itm) { $element_list.append(itm); });
							
							}
							
						}
						
					}
					
					// append element styles
					$('.tg-element-styles-holder').append(response.content.styles);
					// set element color
					TG_element_color('[data-slug="'+response.content.slug+'"]', tg_element_settings);
					// add element to global skin elements
					TG_skin_elements[response.content.slug] = JSON.stringify(tg_element_settings);

					// add settings to element
					$('.tg-no-custom-element').hide();
					
					tg_overwrite_element = '';
					$(info_msg).html(msg);
					
					setTimeout(function() {
						$(info_box).removeClass(box_load);
					}, 800);
					
				},
				error   : show_message_error
			}
		});
		
	});
	
// ==================================================================
// The Grid - Delete skin element
// ==================================================================

	$(document).on('click', '.tg-custom-element-delete', function() {

		var $this = $(this);
		
		var result  = confirm(tg_admin_global_var.box_messages[$this.data('action')].message);
		if (!result) {
			return false;
		}

		Ajax_Helper({
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : $this.data('action'),
			id        : $this.data('id'),
			callbacks : {
				before  : show_message_load,
				success : function(ajax_data, response, msg) {
					
					$(info_msg).html(msg);
					
					// remove tab content and li if no more element in tab content
					var $tab_content = $this.closest('.tomb-tab-content');
					if ($tab_content.find('.tg-element-holder').length === 1) {

						var classes = $tab_content.attr('class').split(/\s+/),
							$tab_li = $('.tg-custom-elements').find('[data-target="'+classes[1]+'"]');
						
						// move tab back remove tab holder and tab li
						$tab_content.closest('.tg-move-tab').removeClass('tg-move-tab');
						$tab_li.remove();
						$tab_content.remove();
						
					}
					
					// remove element
					$this.closest('.tg-element-holder').remove();
					
					// remove element style to prevent conflict
					$('.tg-element-styles-holder .tg-element-styles[data-slug="'+$this.prevAll('[data-slug]').data('slug')+'"]').remove();
					
					// if no custom element show no element msg
					if (!$('.tg-custom-elements .tg-element-holder').length) {
						$('.tg-no-custom-element').show();
					}

					// delete element from global skin element array
					var slug = $this.prevAll('[data-slug]').data('slug');
					delete TG_skin_elements[slug];

					setTimeout(function() {
						$(info_box).removeClass(box_load);
					}, 800);
					
				},
				error   : show_message_error
			}
		});
		
	});
	
// ==================================================================
// The Grid - Get Flickr Photoset list (name and ID)
// ==================================================================

	$(document).on('click', '#tg_flickr_get_photosets_list', function() {

		var $this = $(this);

		Ajax_Helper({
			nonce     : tg_admin_global_var.nonce,
			action    : 'backend_grid_ajax',
			func      : $this.data('action'),
			user_url  : $('[name="the_grid_flickr_user_url"]').val(),
			callbacks : {
				before  : show_message_load,
				success : function(ajax_data, response, msg) {
					$this.next('div').remove();
					$(response.content).insertAfter($this);
					setTimeout(function() {
						$(info_box).removeClass(box_load);
					}, 800);
					
				},
				error   : show_message_error
			}
		});
		
	});
	
	// set flickr photoset ID from photosets list fetched with  ajax
	$(document).on('change', '.tg_flickr_get_photosets_list select', function() {
		$('[name="the_grid_flickr_photoset_id"]').val($(this).val());
	});


})(jQuery);