scnShortcodeMeta={
	attributes:[
		{
			label:"Sidebar Tabs",
			id:"content",
			controlType:"sidebar-tab-control"
		},
		
		{
		label:"Default Active",
		id:"active_tab_item",
		help:"Choose the number of the tab that should be active on default", 
		controlType:"select-control", 
		selectValues:['1', '2', '3', '4', '5', '6', '7', '8','9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20'],
		defaultValue: '1', 
		defaultText: '1'
    },
    {
		label:"Boxed Content",
		id:"boxed",
		help:"The tabbed content can be displayed with or without border", 
		controlType:"select-control", 
		selectValues:['No Border','Border Active'],
		defaultValue: 'No Border', 
		defaultText: 'No Border'
    }
		],
		disablePreview:true,
		customMakeShortcode: function(b){
			
			
			
			var a=b.data;
			var tabTitles = new Array();
			var tabIcons  = new Array();
			
			if(!a) return"";
			
			var r= b.active_tab_item;
			
			var g = ''; // The shortcode.
			
			for ( var i = 0; i < a.numTabs; i++ ) {
				
				var currentField = 'tle_' + ( i + 1 );
				
				tabIcons.push( a.icons.filter(':eq('+i+')').val() );
				
				if ( b[currentField] == '' ) {
				
					tabTitles.push( 'Tab ' + ( i + 1 ) );
				
				} else {
				
					var currentTitle = b[currentField];
					
					currentTitle = currentTitle.replace( /"/gi, "'" );
					
					tabTitles.push( currentTitle );
				
				} // End IF Statement
			
			} // End FOR Loop
			
			var boxed;
			
			if(b.boxed == "Border Active") 
			{
				boxed = " boxed='true' "
			}
			else
			{
				boxed = " boxed='false' "
			}
			
			g += '[sidebar_tab_container initial_open="'+r+'"'+boxed+']<br/><br/>';
			
			var icon = "";
			for ( var t in tabTitles ) {
				
				icon = "";
				if(tabIcons[t] != "") icon = "icon='"+tabIcons[t]+"' ";
				
				g += '[sidebar_tab '+icon+'title="' + tabTitles[t] + '"]' + tabTitles[t] + ' content goes here.[/sidebar_tab] <br/><br/>';
			
			} // End FOR Loop

			g += '[/sidebar_tab_container]';
			return g
		
		}
};