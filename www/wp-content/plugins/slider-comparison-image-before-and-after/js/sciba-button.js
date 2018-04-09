jQuery(function($) {
	tinymce.PluginManager.add('sciba_mce_button', function( editor, url ) {
		editor.addButton('sciba_mce_button', {
			text: '',
			title: l10nSciba.title,
			icon: 'sciba',
			onclick: function() {
				editor.windowManager.open( {
				title: l10nSciba.title,
					body: [
						{
							type: 'textbox',
							name: 'urlImgLeft',
							label: l10nSciba.urlImgLeft,
							id: 'urlImgLeft',
							value: ''
						},
						{
							type: 'button',
							name: 'urlImgLeftSelectImage',
							text: l10nSciba.selectImage,
							onclick: function() {
								window.mb = window.mb || {};

								window.mb.frame = wp.media({
									frame: 'post',
									state: 'insert',
									library : {
										type : 'image'
									},
									multiple: false
								});

								window.mb.frame.on('insert', function() {
									var json = window.mb.frame.state().get('selection').first().toJSON();

									if (0 > $.trim(json.url.length)) {
										return;
									}

									$('#urlImgLeft').val(json.url);
								});

								window.mb.frame.open();
							}
						},
						{
							type: 'textbox',
							name: 'urlImgRight',
							label: l10nSciba.urlImgRight,
							id: 'urlImgRight',
							value: ''
						},
						{
							type: 'button',
							name: 'urlImgRightSelectImage',
							text: l10nSciba.selectImage,
							onclick: function() {
								window.mb = window.mb || {};

								window.mb.frame = wp.media({
									frame: 'post',
									state: 'insert',
									library : {
										type : 'image'
									},
									multiple: false
								});

								window.mb.frame.on('insert', function() {
									var json = window.mb.frame.state().get('selection').first().toJSON();

									if (0 > $.trim(json.url.length)) {
										return;
									}

									$('#urlImgRight').val(json.url);
								});

								window.mb.frame.open();
							}
						},
						{
							type: 'textbox',
							name: 'labelTextLeft',
							label: l10nSciba.labelTextLeft,
						},
						{
							type: 'textbox',
							name: 'labelTextRight',
							label: l10nSciba.labelTextRight,
						},						
						{
							type: 'listbox',
							name: 'modeListbox',
							label: l10nSciba.modeListbox,
							'values': [
								{text: l10nSciba.modeHorizontal, value: 'horizontal'},
								{text: l10nSciba.modeVertical, value: 'vertical'}
							]
						},
						{
							type: 'textbox',
							name: 'width',
							label: l10nSciba.width,
							desc: 'sdadaasds'
						},	
					],
					onsubmit: function( e ) {
						editor.insertContent( '[sciba leftsrc="' + e.data.urlImgLeft + '" leftlabel="' + e.data.labelTextLeft + '" rightsrc="' + e.data.urlImgRight + '" rightlabel="' + e.data.labelTextRight + '" mode="' + e.data.modeListbox + '" width="' + e.data.width + '"]');
					}
				});
			}
		});
	});
});