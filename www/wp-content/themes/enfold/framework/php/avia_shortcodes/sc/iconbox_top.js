scnShortcodeMeta={
	attributes:[
		{
			label:"Title",
			id:"title",
			help: 'The title of your icon box.',
			isRequired:true
		},
		{
			label:"Content",
			id:"content",
			help: 'The content of your info box.',
			isRequired:true
		},
		{
			label:"Icon",
			id:"icon",
			help:"Select one of the icons. You can add icons to this list by simply uploading new ones to your themes image folder (images/icons/iconbox). They will be displayed here automatically ;)", 
			controlType:"select-control", 
			selectValues:avia_framework_globals['iconbox_icons'],
			defaultValue: '', 
			defaultText: 'Please choose a Icon'
		}
		],
		defaultContent:"Don't box me in.",
		shortcode:"iconbox_top"
};
