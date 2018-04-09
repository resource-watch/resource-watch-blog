scnShortcodeMeta={
	attributes:[
		{
			label:"Title",
			id:"title",
			help: 'Enter the title that should be displayed above the widget'
		},
		{
			label:"How many entries",
			id:"count",
			help:"How many entries do you want to display?", 
			controlType:"select-control", 
			selectValues:['1', '2', '3', '4', '5', '6', '7', '8','9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '30', '40', '50', '100'],
			defaultValue: '3', 
			defaultText: '3'
		},
		{
			label:"Entries from which categories do you want to show?",
			id:"cat",
			help: 'Enter a comma separated string with category ids here. Leave empty if you want to display posts from all categories. Example: 1,5,12'
		},
		{
			label:"Display post title only or title &amp; excerpt?",
			id:"excerpt",
			controlType:"select-control", 
			selectValues:['show title only','display title and excerpt'],
			defaultValue: 'show title only', 
			defaultText: 'show title only'
		},

		],
		disablePreview: true,
		defaultContent:"Recent Portfolio Entries",
		shortcode:"widget",
		customMakeShortcode: function (a) {
        
        var f = 'widget widget_name="avia_portfoliobox" widget_class_name="newsbox" ';
        
        for (var d in a) 
        {
            var g = a[d];
            if (g) {f += " " + d + '="' + g + '"'};
        }
        
       
        return "[" + f + "]";
    }};
