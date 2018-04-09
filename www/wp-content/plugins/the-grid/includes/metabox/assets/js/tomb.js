/**
 * Themeone MetaBox
 * http://theme-one.com/
 *
 * Copyright (c) 2015 Themeone
 * All right reserved
 *
 */
 
// ======================================================
// Init all fields and events only once (work with ajax)
// ======================================================

/*global jQuery:false*/
/*global wp:false*/

jQuery(document).ready(function(){
    TOMB_JS.init();
	// Run ui Slider Plugin button +/- event
	TOMB_RangeSlider.event();
	// Load init Select plugin
	TOMB_Select.event();
	// Run image Select radio fields
	TOMB_ImageSelect.event();
	// Run tab open/close event
	TOMB_MetaboxTab.event();
	// Run required fields event
	TOMB_RequiredField.event();
});

// ======================================================
// Init all fields functionnalities (works with ajax)
// ======================================================

var TOMB_JS = {
	init: function() {
		// Run Single Image/Media Editor event
		TOMB_MediaControl.init();
		// Run Gallery Editor
		TOMB_GalleryControl.init();
		// Run ui Slider Plugin
		TOMB_RangeSlider.init();
		// Run tab open/close
		TOMB_MetaboxTab.init();
		// Load colorpicker if field exists
		TOMB_ColorPicker.init();
		// Load init Select plugin
		TOMB_Select.init();
		// Load textarea count plugin
		TOMB_TextArea.init();
		// Run codemirror
		TOMB_Code.init();
	}
};

// ======================================================
// Dropdown/select plugin
// ======================================================

var select_field    = '.tomb-select, .tomb-multiselect',
	select_holder   = '.tomb-select-holder',
	select_open     = 'tomb-select-open',
	select_dropdown = '.tomb-select-dropdown',
	select_plholder = '.tomb-select-placeholder',
	select_search_in= '.tomb-select-search-field',
	select_noresult = '.tomb-select-search-no-result',
	select_value    = '.tomb-select-value',
	select_remove   = '.tomb-multiselect-remove',
	select_clear    = '.tomb-select-clear';

var TOMB_Select = {
	
	init: function() {
		
		jQuery(select_field).each(function() {
			var el = jQuery(this).closest(select_holder);
			if (!el.hasClass('tomb-select-init')) {
				el.addClass('tomb-select-init');
				TOMB_Select.select(jQuery(this));
			}
		});
		
	},
	event: function(el) {
		
		jQuery(window).on('resize', function() {
			TOMB_Select.close();
		});
		
		jQuery(document).on('change', select_field, function() {
			TOMB_Select.select(jQuery(this));	
		}).on('click', select_holder, function(e) {
			if (!jQuery(e.target).is(select_clear) && !jQuery(e.target).is(select_remove)) {
				TOMB_Select.open(jQuery(this));	
			}
		}).on('mousedown', function(e) {
			TOMB_Select.close(jQuery(e.target));
		}).on('click', select_dropdown+' li', function(e) {
			el = jQuery(this);
			if (el.data('disabled') != 'disabled' && !jQuery(e.target).is(select_noresult)) {
				if (el.closest(select_dropdown).data('multiple') && el.data('selected')) {
					el.data('option').prop('selected', false).trigger('change');	
				} else {
					el.data('option').prop('selected', true).trigger('change');
				}
			}
			TOMB_Select.close();
		}).on('click', select_clear, function() {
			TOMB_Select.clear(jQuery(this));
		}).on('keyup search', select_search_in, function() {
			TOMB_Select.search(jQuery(this));
		}).on('click', select_remove, function() {
			jQuery(this).data('option').prop('selected', false).trigger('change');
			TOMB_Select.close();
		});		
		
	},
	open: function(el) {
		
		jQuery(select_search_in).val('');
		jQuery(select_dropdown).remove();
		jQuery(select_holder).removeClass(select_open);
		
		var i = 0,
			opt  = [],
			data = [];
		el.find('option').each(function() {
			var il = jQuery(this);
			if (il.get(0).attributes.length) {
				opt[i]  = '<li data-option="'+il+'" value="'+il.val()+'" data-disabled="'+il.attr('disabled')+'" data-selected="'+il.prop('selected')+'">'+il.text()+'</li>';
				data[i] = il;
				i++;
			}
		});	
		opt = jQuery(opt.join(''));
		opt.each(function(i) {
			opt.eq(i).data('option', data[i]);
		});
		
		var position = TOMB_Select.position(el.find('.tomb-select-fake'), opt),
			multiple = (el.data('multiple')) ? true : false,
			posclass = (position.top == 'auto') ? 'bottom' : 'top';
		
		var $dropdown = jQuery(
			'<div class="tomb-select-dropdown" data-multiple="'+multiple+'">'+
				'<span class="tomb-select-search">'+
					'<input class="tomb-select-search-field" type="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox">'+
				'</span>'+
				'<ul>'+
					'<li class="tomb-select-search-no-result">'+jQuery(select_holder).data('noresult')+'</li>'+
				'</ul>'+
			'</div>'
		).css({
			'width'  : position.width,
			'top'    : position.top,
			'bottom' : position.bottom,
			'left'   : position.left
		}).addClass(posclass);
		
		$dropdown.find('ul').prepend(opt);
		jQuery('body').append($dropdown);
		el.addClass(select_open);
		$dropdown.find('ul').scrollTop(el.data('scrollTop'));
		jQuery(select_search_in).select();
		TOMB_Select.empty();
		var scroller = el.closest(':TOMB_hasScroll(y)');
		el.closest(':TOMB_hasScroll(y)').on('mousewheel DOMMouseScroll', TOMB_Select.noscroll);
		$dropdown.find('ul').scroll(function() {TOMB_Select.onscroll(jQuery(this), el);});

	},
	close: function(el) {
		
		if (!el || (!el.closest(select_holder).length && !el.is(select_holder) && !el.is(select_clear) && !el.closest(select_dropdown).length)) {
			jQuery('.'+select_open).closest(':TOMB_hasScroll(y)').off('mousewheel DOMMouseScroll', TOMB_Select.noscroll);
			jQuery(select_holder).removeClass(select_open);
			jQuery(select_dropdown).remove();
		}
		
	},
	noscroll: function(e) {
		e.preventDefault();
	},
	onscroll: function(dd, el) {
		el.data('scrollTop', dd.scrollTop());
	},
	position: function(el, nb) {
		
		var offset = el.offset(),
			height = (nb.length*28+38 < 262) ? nb.length*28+38 : 262,
			position = [];
			
		position.top    = (offset.top+height < jQuery(window).height()+jQuery(window).scrollTop()) ? offset.top : 'auto';
		position.bottom = (position.top == 'auto') ? jQuery(window).height() - offset.top - el.outerHeight(true) : 'auto';
		position.left   = offset.left;
		position.width  = el.outerWidth();
		return position;
		
	},
	search: function(el) {
		
		var search = el.val();
		jQuery(select_dropdown).find('li').each(function() {
			var $this = jQuery(this);
			if ($this.text().toLowerCase().indexOf(search) !== -1) {
				$this.show();
			} else {
				$this.hide();
			}
		});
		TOMB_Select.empty();
		
	},
	clear: function(el) {
		
		var il = el.closest(select_holder);
		il.find('select').val('').trigger('change');
		TOMB_Select.select(el);
		TOMB_Select.close();
		
	},
	empty: function() {
		
		if (jQuery(select_dropdown).find('li:not('+select_noresult+'):visible').length === 0 || jQuery(select_dropdown).find('li').length === 1) {
			jQuery(select_noresult).show();
		} else {
			jQuery(select_noresult).hide();
		}
		
	},
	select: function(el) {
		
		el.closest(select_holder).find('.tomb-value-holder').html('');
		if (jQuery.isArray(el.val())) {
			var data = [],
				selected = [];
			jQuery.each(el.val(), function(i,v){
				var il = el.find('option[value="'+v+'"]');
				selected[i] = '<span><span class="tomb-multiselect-remove">x</span>'+il.text()+'</span>';
				data[i] = il;
			});
			selected = jQuery(selected.join(''));
			selected.each(function(i) {
				selected.find('.tomb-multiselect-remove').eq(i).data('option', data[i]);
			});
			el.closest(select_holder).find('.tomb-value-holder').html('').append(selected);
		} else {
			selected = el.find('option:selected').text();
			el.closest(select_holder).find(select_value).text(selected);
			if (selected) {
				el.closest(select_holder).find(select_plholder).hide();
				el.closest(select_holder).find(select_clear).show();
			} else {
				el.closest(select_holder).find(select_plholder).show();
				el.closest(select_holder).find(select_clear).hide();
			}
		}
		
	}
};

// ======================================================
// Textarea counter
// ======================================================

var TOMB_TextArea = {
	init: function() {
		jQuery('.tomb-textarea').each(function() {
			jQuery(this).textareaCount({'originalStyle': 'originalDisplayInfo'});
		});
	}
};

// ======================================================
// Code line numbered
// ======================================================

var TOMB_codeTxt = [];
var TOMB_Code = {
	init: function() {
		jQuery('.tomb-code-holder').To_linenumbers();
	}
};

// ======================================================
// ColorPicker (Wordpress)
// ======================================================

var TOMB_ColorPicker = {
	
	init: function() {
		var $colorPicker = jQuery('.tomb-colorpicker');
		
		if ($colorPicker.length > 0 ) {

			if (typeof jQuery().wpColorPicker !== 'undefined') {
			
				$colorPicker.wpColorPicker();
			
				$colorPicker.each(function() {
	
					if (jQuery(this).data('alpha') !== 1) return;
	
					var $control = jQuery(this),
						value    = $control.val().replace(/\s+/g, ''),
						$alpha   = jQuery(
							'<div class="tomb-alpha-container">'+
							'<div class="slider-alpha"></div>'+
							'<div class="transparency"></div>'+
							'</div>'
						),
						new_alpha_val,
						color_picker,
						alpha_val,
						$slider,
						iris;
					
					if ($control.parents('.wp-picker-container').find('.tomb-alpha-container').length === 0) {
						$alpha.appendTo($control.parents('.wp-picker-container'));
					}
					$slider = $control.parents('.wp-picker-container:first').find('.slider-alpha');
	
					if (value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)) {
						alpha_val = parseFloat(value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)[1]) * 100;
						alpha_val = parseInt(alpha_val);
					} else {
						alpha_val = 100;
					}
	
					
					$slider.slider({
						slide: function(event, ui) {
							getAlphaVal(ui.value);
						},
						value: alpha_val,
						range: "max",
						step: 1,
						min: 1,
						max: 100
					});
					
					var get_val = $control.val();
	
					$control.wpColorPicker({
						color: get_val,
						clear: function() {
							$slider.slider({value: 100});
							$control.val('');	
						},
						change: function(event, ui) {
							var $transparency = $control.parents('.wp-picker-container:first').find('.transparency');
							$transparency.css('backgroundColor', ui.color.toString('no-alpha'));
						},
					});
					
					function getAlphaVal(value) {
						iris = $control.data('a8cIris');
						new_alpha_val = parseFloat(value);
						color_picker = $control.data('wpWpColorPicker');
						iris._color._alpha = new_alpha_val / 100.0;
						$control.val(iris._color.toString());
						jQuery($control).wpColorPicker('color', $control.val());
					}
				
				});
			
			}
		}
	}
	
};

// ======================================================
// MetaBox tab
// ======================================================

var TOMB_MetaboxTab = {
	init: function() {
		jQuery('.tomb-tab.selected').each(function() {
			TOMB_MetaboxTab.check(jQuery(this));
		});
	},
	check: function(el) {
		var selected_tab = el.data('target');
		el.closest('ul').find('li').removeClass('selected');
		el.addClass('selected');
		el.closest('ul').nextAll('.tomb-tab-content').removeClass('tomb-tab-show').hide();
        el.closest('ul').nextAll('.tomb-tab-content.'+selected_tab+'').addClass('tomb-tab-show').show();
	},
	event: function() {
		jQuery(document).on('click','.tomb-tab', function() {
			TOMB_MetaboxTab.check(jQuery(this));
		});
	}
};

// ======================================================
// Requiered field to hide show dependencies
// ======================================================

var TOMB_requiredFields,
	TOMB_RequiredField = {
	
	event: function() {
		
		TOMB_RequiredField.fetch();
		TOMB_RequiredField.check();

		jQuery(document).on('change', '.tomb-check-field', function() {
			TOMB_RequiredField.check();
		});
		
	},
	fetch: function() {
		
		TOMB_requiredFields = {};

		jQuery.each(jQuery('[data-tomb-required]'), function(index) {
			
			var $this     = jQuery(this),
				condition = $this.data('tomb-required').split(';');

			for (var i = 0; i < condition.length; i++) {

				var field  = condition[i].split(','),
					$input = jQuery('[name="'+field[0]+'"]');
					
				$input = !$input.length ? jQuery('[name="'+field[0]+'[]"]') : $input;
				
				TOMB_requiredFields[index] = (!TOMB_requiredFields[index]) ? [] : TOMB_requiredFields[index];
				
				TOMB_requiredFields[index].push({
					'field'    : $this, 
					'input'    : $input, 
					'operator' : field[1],
					'value'    : field[2]
				})

				$input.addClass('tomb-check-field');
	
			}
			
		});
		
	},
	check: function() {

		jQuery.each(TOMB_requiredFields, function(i) {
			
			var nb_condition_tot  = TOMB_requiredFields[i].length,
				nb_condition_true = 0;

			for (var c = 0; c < nb_condition_tot; c++) {
				
				var value,
					condition = TOMB_requiredFields[i][c],
					$input    = condition['input'];
				
				// get the type of field
				var type = $input.attr('class');
					type = (type) ? type.split(' ')[0] : type;
				
				// depending of the field type fetch right value
				switch (type) {
					case 'tomb-radio':
						value = $input.closest('div').find('input:checked').val();
						break;
					case 'tomb-image-select':
						value = $input.closest('div').find('input:checked').val();
						break;
					case 'tomb-checkbox':
						value = String($input.prop('checked'));
						break;
					case 'tomb-checkbox-list':
						value = $input.closest('div').find('input:checked').val();
						break;
					default:
						value = $input.val();
						break;
				}
				
				// check if required match with current operator
				var match_condition = TOMB_RequiredField.operator(
					condition['operator'],
					value,
					condition['value']
				)
				
				// increment condition if match condition
				nb_condition_true = (match_condition) ? nb_condition_true+1 : nb_condition_true;
				
				// if conditions match then show field
				if (nb_condition_true === nb_condition_tot) {
					condition['field'].show();
				} else {
					condition['field'].hide();
				}

			}
		
		});
		
	},
	operator: function(operator, x, y) {
			
		switch (operator) {
			case 'contains':
				return (x && y && x.toString().indexOf(y.toString()) >= 0 );
				break;
			case '==':
				return (x == y);
				break;
			case '===':
				return (x === y);
				break;
			case '!=':
				return (x != y);
				break;
			case '!==':
				return (x !== y);
				break;
			case '>':
				return (x > y);
				break;
			case '>=':
				return (x >= y);
				break;
			case '<':
				return (x < y);
				break;
			case '<=':
				return (x <= y);
				break;
			default:
				return (x == y);
				break;
		}
		
		return false;
			
	}
	
};

// ======================================================
// Image select
// ======================================================

var TOMB_ImageSelect = {
	event: function() {
		jQuery(document).on('click', '.tomb-image-holder', function(){
			var $this = jQuery(this);
			$this.closest('.tomb-field').find('.tomb-image-holder, input, img').removeAttr('data-checked checked');
			$this.attr('data-checked',1);
			$this.find('img').attr('data-checked',1);
			$this.find('input').attr('checked',1);
			$this.find('input').trigger('change');
		});
	}
};

// ======================================================
// Range Slider
// ======================================================

var slider_range = '.tomb-slider-range',
	slider_input = '.tomb-slider-input',
	slider_field = '.tomb-field',
	slider_plus  = '.tomb-slider-plus',
	slider_less  = '.tomb-slider-less',
	slider       = '.tomb-slider',
	int;

var TOMB_RangeSlider = {	
	init: function() {
		jQuery(slider_range).each(function() {
        	var $this  = jQuery(this);
			if (!$this.is('.ui-slider')) {
				var $input = $this.closest(slider_field).find(slider);
					$this.slider({ 
						range: 'min',
						min  : $this.data('min'),
						max  : $this.data('max'),
						step : ($this.data('step')) ? $this.data('step') : 1,
						value: $this.data('value'),
						slide: function(e,ui) {
							TOMB_RangeSlider.update($this,ui);
						},
						change: function(e,ui) {
							TOMB_RangeSlider.update($this,ui);
							$this.siblings('input').trigger("change");
						}
				});
				$input.attr('size', String($this.attr('data-max')).length+String($this.attr('data-sign')).length-1);
			}
		});
	},
	event: function() {
		jQuery(document).on('mousedown',slider_plus+','+slider_less,function() {
			var $this = jQuery(this);
			int = setInterval(function() {
				TOMB_RangeSlider.change($this);
			}, 100);
		}).on('mouseup mouseleave',function() {
			clearInterval(int);  
		});
	},
	update: function(el,ui) {
		var sign = el.data('sign');
		el.closest(slider_field).find(slider).val(ui.value+sign);
		el.closest(slider_field).find(slider_input).val(ui.value);
	},
	change: function(el) {
		var $this = el.closest(slider_field).find(slider_range),
			step  = ($this.data('step')) ? $this.data('step') : 1,
			val   = el.closest(slider_field).find('.tomb-slider-input').val(),
			min   = $this.slider("option", "min"),
			max   = $this.slider("option", "max");

		if (el.is(slider_less)) {
			step = -step;
		}
		val = parseFloat(val)+step;
		if (val <= max && val >= min) {
			$this.slider("option", "value", val);
		}
	}

};

// ======================================================
// Single image/media uploader
// ======================================================

var TOMB_MediaControl = {
    // Initializes a new media manager or returns an existing frame.
    init: function() {
        // Handle media manager
		jQuery('.tomb-open-media:not(is-init)').each(function() {
			
			var $this = jQuery(this);
			
			$this.add($this.next('.tomb-image-remove')).addClass('is-init');
			
			var frame,
				tomb_image_id     = ($this.is('.tomb-image-id')) ? true : false,
				tomb_image_holder = $this.prevAll('.tomb-img-field'),
				tomb_image_remove = $this.nextAll('.tomb-image-remove'),
				tomb_image_input  = $this.prevAll('input'),
				tomb_frame_title  = $this.data('frame-title'),
				tomb_frame_button = $this.data('frame-button'),
				tomb_frame_type   = ($this.data('media-type')) ? $this.data('media-type') : 'image';
			
			if (frame) { return frame; }
			
			frame = wp.media({
				id: 'tomb-media-popup',
				title: tomb_frame_title,
				frame: 'select',
				library: { 
					type: tomb_frame_type
				},
				button: {
					text: tomb_frame_button
				},
				multiple: false
			});

			$this.on('click', function(e) {
				e.preventDefault();
				frame.open();
			});
			
			frame.on( 'select', function() {
				var media = frame.state().get('selection').first().toJSON();
				var id    = media.id;
				var thumb = media.url;		 
				var value = (tomb_image_id === true) ? id : thumb;
				jQuery(tomb_image_input).val(value);
				jQuery(tomb_image_holder).attr('style','background-image: url('+thumb+')').addClass('show');
				jQuery(tomb_image_remove).addClass('show');
				jQuery(tomb_image_input).trigger('change');
			});
			
			$this.next('.tomb-image-remove:not(is-init)').on('click', function(){
				var $formfield = jQuery(this);
				$formfield.removeClass('show');
				$formfield.prevAll('input').val('');
				$formfield.prevAll('.tomb-img-field').attr('style','');
				$formfield.prevAll('.tomb-img-field').removeClass('show');
				$formfield.prevAll('input').trigger("change");
			});
			
		});
		
    }
};

// ======================================================
// Gallery image uploader
// ======================================================

var TOMB_GalleryControl = {
    // Initializes a new media manager or returns an existing frame.	
    init: function() {
        // Handle media manager
		jQuery('.tomb-open-gallery:not(.is-init)').each(function() {
			
			var $this = jQuery(this);

			var frame,
				previous_selection = [],
				tomb_gallery_list  = $this.closest('.tomb-gallery-container').find('.tomb-gallery-holder'),
				tomb_gallery_remove= $this.closest('.tomb-gallery-container').find('.tomb-delete-gallery:not(is-init)'),
				tomb_gallery_ids   = $this.prevAll('input'),
				tomb_window_title  = $this.data('title'),
				tomb_window_button = $this.data('button');
				
			jQuery('.tomb-gallery-holder:not(is-init)').sortable({
				placeholder: 'tomb-gallery-item-highlight',
				update: function() {
					var $this = jQuery(this);
					TOMB_GalleryControl.update($this);
				},
			});
			
			jQuery(document).on('click', '.tomb-gallery-item-remove', function(){
				var $this = jQuery(this).closest('.tomb-gallery-holder');
				jQuery(this).closest('li').remove();
				TOMB_GalleryControl.update($this);
			});
			
			jQuery('.tomb-gallery-holder').addClass('is-init');
			jQuery('.tomb-gallery-item').addClass('is-init');
			$this.add(jQuery(tomb_gallery_remove)).addClass('is-init');
			
			if (frame) {
				return frame;
			}
			
			jQuery(tomb_gallery_remove).on('click', function(e) {
				e.preventDefault();
				var tomb_delete_message = jQuery(this).data('del');
				var tomb_confirm_delete = confirm(tomb_delete_message);
				if (tomb_confirm_delete === true) {
					var $this  = jQuery(this).closest('.tomb-row');
					$this.find('.tomb-gallery-holder').empty();
					$this.find('input').val('');
				}
			});
			
			frame = wp.media({
				frame    : 'select',
				id       : 'tomb-media-popup',
				title    : tomb_window_title,
				library  : { type: 'image' },
				button   : { text: tomb_window_button },
				multiple : 'toggle',
			});

			frame.on('select', function() {

				var ids = [], imageHTML = '',
					selection = frame.state().get("selection").toJSON();
					
				selection.forEach(function(attachment) {
					ids.push(attachment.id);
					if (attachment.sizes) {
						img = (attachment.sizes.thumbnail !== undefined) ? attachment.sizes.thumbnail.url : attachment.sizes.full.url;
					} else {
						img = decodeURIComponent(previous_selection[attachment.id]);
					}
					imageHTML += '<li data-id="'+attachment.id+'">';
					imageHTML += '<div class="tomb-gallery-item-remove">x</div>';
					imageHTML += '<div class="tomb-gallery-item-image" style="background-image:url('+img+')"></div>';
					imageHTML += '</li>';
				});
				
				ids = (ids) ? ids.join(',') : '';
				jQuery(tomb_gallery_list).html(imageHTML); 
				jQuery(tomb_gallery_ids).val(ids);
				
			});
				
			frame.on('open', function() {
				
				var ids = (tomb_gallery_ids.val()) ? tomb_gallery_ids.val() : null,
					selection = frame.state().get('selection'),
					prev_img;
				
				if (ids) {
					idsArray = ids.split(',');
					idsArray.forEach(function(id) {
						attachment = wp.media.attachment(id);
						attachment.fetch();
						selection.add(attachment ? [attachment]  : []);
						prev_img = jQuery(tomb_gallery_list).find('[data-id="'+id+'"]').find('.tomb-gallery-item-image').css('background-image');
						prev_img = prev_img.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
						previous_selection[id] = encodeURIComponent(prev_img);
					});
				}
				
			});
	
			$this.on('click', function(e) {	
				frame.open();
			});
			
		});
		
    },
	
	update: function(el) {
		// update gallery ids on drag and remove
		var ids = [];
		el.find('li').each(function() {
			ids.push(jQuery(this).data('id'));
		});
		ids = (ids) ? ids.join(',') : '';
		el.closest('.tomb-row').find('input').val(ids);
	}
		
};

// ======================================================
// jQuery Textarea Characters Counter
// ======================================================

(function($){  
	$.fn.textareaCount = function(options, fn) {   
		var defaults = {  
			maxCharacterSize: -1,  
			originalStyle: 'originalTextareaInfo',
			warningStyle: 'warningTextareaInfo',  
			warningNumber: 20,
			displayFormat: '#input characters | #words words'
		};  
		options = $.extend(defaults, options);
		
		var container = $(this);
		
		$("<div class='charleft'>&nbsp;</div>").insertAfter(container);
		
		//create charleft css
		var charLeftCss = {
			'width' : container.width()
		};
		
		var charLeftInfo = getNextCharLeftInformation(container);
		charLeftInfo.addClass(options.originalStyle);
		charLeftInfo.css(charLeftCss);
		
		var numInput = 0;
		var maxCharacters = options.maxCharacterSize;
		var numLeft = 0;
		var numWords = 0;
				
		container.bind('keyup', function(){limitTextAreaByCharacterCount();})
				 .bind('mouseover', function(){setTimeout(function(){limitTextAreaByCharacterCount();}, 10);})
				 .bind('paste', function(){setTimeout(function(){limitTextAreaByCharacterCount();}, 10);});
		
		
		function limitTextAreaByCharacterCount(){
			charLeftInfo.html(countByCharacters());
			//function call back
			if(typeof fn != 'undefined'){
				fn.call(this, getInfo());
			}
			return true;
		}
		
		function countByCharacters(){
			var content = container.val();
			var contentLength = content.length;
			var newlineCount;
			
			//Start Cut
			if(options.maxCharacterSize > 0){
				//If copied content is already more than maxCharacterSize, chop it to maxCharacterSize.
				if(contentLength >= options.maxCharacterSize) {
					content = content.substring(0, options.maxCharacterSize); 				
				}
				
				newlineCount = getNewlineCount(content);
				
				// newlineCount new line character. For windows, it occupies 2 characters
				var systemmaxCharacterSize = options.maxCharacterSize - newlineCount;
				if (!isWin()){
					 systemmaxCharacterSize = options.maxCharacterSize;
				}
				if(contentLength > systemmaxCharacterSize){
					//avoid scroll bar moving
					var originalScrollTopPosition = this.scrollTop;
					container.val(content.substring(0, systemmaxCharacterSize));
					this.scrollTop = originalScrollTopPosition;
				}
				charLeftInfo.removeClass(options.warningStyle);
				if(systemmaxCharacterSize - contentLength <= options.warningNumber){
					charLeftInfo.addClass(options.warningStyle);
				}
				
				numInput = container.val().length + newlineCount;
				if(!isWin()){
					numInput = container.val().length;
				}
			
				numWords = countWord(getCleanedWordString(container.val()));
				
				numLeft = maxCharacters - numInput;
			} else {
				//normal count, no cut
				newlineCount = getNewlineCount(content);
				numInput = container.val().length + newlineCount;
				if(!isWin()){
					numInput = container.val().length;
				}
				numWords = countWord(getCleanedWordString(container.val()));
			}
			
			return formatDisplayInfo();
		}
		
		function formatDisplayInfo(){
			var format = options.displayFormat;
			format = format.replace('#input', numInput);
			format = format.replace('#words', numWords);
			//When maxCharacters <= 0, #max, #left cannot be substituted.
			if(maxCharacters > 0){
				format = format.replace('#max', maxCharacters);
				format = format.replace('#left', numLeft);
			}
			return format;
		}
		
		function getInfo(){
			var info = {
				input: numInput,
				max: maxCharacters,
				left: numLeft,
				words: numWords
			};
			return info;
		}
		
		function getNextCharLeftInformation(container){
				return container.next('.charleft');
		}
		
		function isWin(){
			var strOS = navigator.appVersion;
			if (strOS.toLowerCase().indexOf('win') != -1){
				return true;
			}
			return false;
		}
		
		function getNewlineCount(content){
			var newlineCount = 0;
			for(var i=0; i<content.length;i++){
				if(content.charAt(i) == '\n'){
					newlineCount++;
				}
			}
			return newlineCount;
		}
		
		function getCleanedWordString(content){
			var fullStr = content + " ";
			var initial_whitespace_rExp = /^[^A-Za-z0-9]+/gi;
			var left_trimmedStr = fullStr.replace(initial_whitespace_rExp, "");
			var non_alphanumerics_rExp = /[^A-Za-z0-9]+/gi;
			var cleanedStr = left_trimmedStr.replace(non_alphanumerics_rExp, " ");
			var splitString = cleanedStr.split(" ");
			return splitString;
		}
		
		function countWord(cleanedWordString){
			var word_count = cleanedWordString.length-1;
			return word_count;
		}
	};  
})(jQuery); 

// ======================================================
// Remove alpha canal color
// ======================================================

jQuery(document).ready(function() {

	if (typeof Color !== 'undefined') {
		
		Color.prototype.toString = function(remove_alpha) {
			if (remove_alpha == 'no-alpha') {
				return this.toCSS('rgba', '1').replace(/\s+/g, '');
			}
			if (this._alpha < 1) {
				return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
			}
			var hex = parseInt(this._color, 10).toString(16);
			if (this.error) return '';
			if (hex.length < 6) {
				for (var i = 6 - hex.length - 1; i >= 0; i--) {
					hex = '0' + hex;
				}
			}
			return '#' + hex;
		};
	
	}

});

// ======================================================
// Detect div scrollbar
// ======================================================

// http://codereview.stackexchange.com/q/13338
// Was designed to be used with the Sizzle selector engine.
function TOMB_hasScroll(el, index, match) {

    var $el = jQuery(el),
        sX = $el.css('overflow-x'),
        sY = $el.css('overflow-y'),
        hidden = 'hidden',
        visible = 'visible',
        scroll = 'scroll',
        axis = match[3];
		
	if ($el.is('body')) {
		return false;
	}

    if (!axis) {
        if (sX === sY && (sY === hidden || sY === visible)) {
            return false;
        }
        if (sX === scroll || sY === scroll) { 
			return true;
		}
    } else if (axis === 'x') {
        if (sX === hidden || sX === visible) {
			return false;
		}
        if (sX === scroll) {
			return true;
		}
    } else if (axis === 'y') {
        if (sY === hidden || sY === visible) {
			return false;
		}
        if (sY === scroll) {
			return true;
		}
    }

    return $el.innerHeight() < el.scrollHeight || $el.innerWidth() < el.scrollWidth;
	
}

jQuery.expr[':'].TOMB_hasScroll = TOMB_hasScroll;

jQuery.fn.TOMB_hasScroll = function(axis) {
	
	var el = this[0];
	if (!el) { return false; }
	return TOMB_hasScroll(el, 0, [0, 0, 0, axis]);
	
};

// ======================================================
// Textarea line number
// ======================================================

(function($){
	
	$.fn.To_linenumbers = function(in_opts){
				
		return this.each(function(){
			
			var el = $(this);
			
			if (!el.hasClass('tomb-code-init')) {
				
				el.addClass('tomb-code-init');
				
				var code  = el.find('.tomb-code'),
					lnbox = el.find('.tomb-code-line-numbers');

				code.bind('blur focus change keyup keydown',function(){
					
					var lines = '\n'+$(this).val(),
						line_number_output='';
						lines = lines.match(/[^\n]*\n[^\n]*/gi);

					// Loop through and process each line
					for (var i = 0, l = lines.length; i < l; i++) {
						line_number_output += (i!=0) ? '\n'+(i+1) : i+1;
					}
					
					// Give the text area out modified content.
					lnbox.val(line_number_output).scrollTop($(this).scrollTop());
					
				})
				
				// Lock scrolling together, for mouse-wheel scrolling 
				.scroll(function(){
					lnbox.scrollTop($(this).scrollTop());
				})
				
				// Fire it off once to get things started
				.trigger('keyup');
			
			}
			
		});
	};
	
})(jQuery);