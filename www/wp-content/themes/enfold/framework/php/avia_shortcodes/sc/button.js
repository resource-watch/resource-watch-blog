scnShortcodeMeta = {
    attributes: [{
        label: "Title",
        id: "content",
        help: "The button title.",
        isRequired: true
    }, {
        label: "link",
        id: "link",
        help: "Optional link (e.g. http://google.com).",
        validatelink: true
    }, {
		label:"Size",
		id:"size",
		help:"Values: &lt;empty&gt; for normal size, small, large, xl.", 
		controlType:"select-control", 
		selectValues:['small', '', 'large', 'xl'],
		defaultValue: '', 
		defaultText: 'medium (Default)'
    }, {
		label:"Style",
		id:"style",
		help:"Values: &lt;empty&gt;, info, alert, tick, download, note, help, error",
		controlType:"select-control", 
		selectValues:['', 'info', 'alert', 'tick', 'download', 'note', 'help', 'error'],
		defaultValue: '', 
		defaultText: 'none (Default)'
	}, {
        label: "Background Color",
        id: "color",
        help: "Values: &lt;empty&gt; for default or a color (e.g. red or #000000)."
    }, {
        label: "Border",
        id: "border",
        help: "&lt;empty&gt; for default or the border color (e.g. red or #000000)."
    }, {
		label:"Dark Text?",
		id:"text",
		help:'Leave empty for light text color or use "dark" (for light background color buttons).', 
		controlType:"select-control", 
		selectValues:['light', 'grey', 'dark'],
		defaultValue: 'grey', 
		defaultText: 'grey'
	},
	{
		label:"CSS Class",
		id:"class",
		help:"Optional CSS class."
	}
	,
	{
		label:"Open in a new window",
		id:"window",
		help:"Optionally open this link in a new window.", 
		controlType:"select-control", 
		selectValues:['', 'yes'],
		defaultValue: '', 
		defaultText: 'no (Default)'
	}],
    defaultContent: "Click me!",
    shortcode: "button"
};