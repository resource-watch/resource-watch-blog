jQuery(window).ready(function(){
		jQuery.fn.tzCheckbox = function(options){
		options = jQuery.extend({
			labels : ['ON','OFF']
		},options);
		
		return this.each(function(){
			var originalCheckBox = jQuery(this),
				labels = [];
			if(originalCheckBox.data('on')){
				labels[0] = originalCheckBox.data('on');
				labels[1] = originalCheckBox.data('off');
			}
			else labels = options.labels;
			var checkBox = jQuery('<span>');
				 checkBox.addClass(this.checked?' tzCheckBox checked':'tzCheckBox');
			     checkBox.prepend('<span class="tzCBContent">'+labels[this.checked?0:1]+ '</span><span class="tzCBPart"></span>');
			checkBox.insertAfter(originalCheckBox.hide());

			checkBox.click(function(){
				checkBox.toggleClass('checked');
				var isChecked = checkBox.hasClass('checked');
				originalCheckBox.attr('checked',isChecked);
				checkBox.find('.tzCBContent').html(labels[isChecked?0:1]);
			});

			originalCheckBox.bind('change',function(){
				checkBox.click();
			});
		});
	};
	
	jQuery('#state').tzCheckbox({labels:['On','Off']});
		var vColorPickerOptions = {
			defaultColor: false,
			change: function(event, ui){},
			clear: function() {},
			hide: true,
			palettes: true
		};
	
	jQuery('#body_bg_color, #font_color, #body_bg_blur_color, #controls_bg_color').wpColorPicker(vColorPickerOptions);
	
	if (jQuery('.select2_customize, .multiple-select-mt').length > 0) {
		jQuery('.select2_customize, .multiple-select-mt').select2({});
	}
	
	if (jQuery('#503_enabled').length > 0) {
		if (jQuery('#503_enabled').prop("checked")) {
			jQuery('#gg_analytics_id').prop('disabled', true);
		} else {
			jQuery('#gg_analytics_id').prop('disabled', false);
		}
	}
	
	jQuery('#503_enabled').on('change', function() {
		if (jQuery(this).prop("checked")) {
			jQuery('#gg_analytics_id').prop('disabled', true);
		} else {
			jQuery('#gg_analytics_id').prop('disabled', false);
		}
		
	});

    var t=null,t=jQuery.getJSON(maintenance.path+"includes/fonts/googlefonts.json");jQuery("#body_font_family").on("change",function(){var e=jQuery(this).val();n(e)});var n=function(e){jQuery("#body_font_subset").html(""),jQuery("#s2id_body_font_subset .select2-choice .select2-chosen").empty(),font=JSON.parse(t.responseText);for(var n in font)if(n==e)for(var o=0;o<font[n].variants.length;o++)0==o&&jQuery("#s2id_body_font_subset .select2-choice .select2-chosen").append(font[n].variants[o]),jQuery("#body_font_subset").append("<option>"+font[n].variants[o]+"</option>")}
});

