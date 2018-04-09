scnShortcodeMeta = {
    attributes: [{
        label: "Title",
        id: "content",
        isRequired: true,
        help: "The link text."
    }, {
        label: "link",
        id: "url",
        help: "The Url for your link.",
        validatelink: true
    }, {
       label:"Style",
		id:"style",
		help:"Values: &lt;empty&gt;, info, alert, tick, download, note, help, error",
		controlType:"select-control", 
		selectValues:['', 'info', 'alert', 'tick', 'download', 'note', 'help', 'error'],
		defaultValue: '', 
		defaultText: 'none (Default)'
	}, {
        label: "Icon",
        id: "icon",
        help: "Optional. Url to a custom icon."
    }],
    defaultContent: "Download",
    shortcode: "ilink"
};