scnShortcodeMeta={
	attributes:[
		{
			label:"Tabs",
			id:"content",
			controlType:"tab-control"
		},
		
		{
		label:"Default Active",
		id:"active_tab_item",
		help:"Choose the number of the tab that should be active on default", 
		controlType:"select-control", 
		selectValues:['1', '2', '3', '4', '5', '6'],
		defaultValue: '1', 
		defaultText: '1'
    }
		],
		disablePreview:true,
		customMakeShortcode: function(b){
				
			var a=b.data;
			var tabTitles = new Array();
			
			if(!a)return"";
			
			var c=a.content;
			var r= b.active_tab_item;
			
			var g = ''; // The shortcode.
			
			for ( var i = 0; i < a.numTabs; i++ ) {
			
				var currentField = 'tle_' + ( i + 1 );

				if ( b[currentField] == '' ) {
				
					tabTitles.push( 'Tab ' + ( i + 1 ) );
				
				} else {
				
					var currentTitle = b[currentField];
					
					currentTitle = currentTitle.replace( /"/gi, "'" );
					
					tabTitles.push( currentTitle );
				
				} // End IF Statement
			
			} // End FOR Loop
			
			g += '[tab_container initial_open="'+r+'"]<br/><br/>';
			
			for ( var t in tabTitles ) {
			
				g += '[tab title="' + tabTitles[t] + '"]' + tabTitles[t] + ' content goes here.[/tab] <br/><br/>';
			
			} // End FOR Loop

			g += '[/tab_container]';

			return g
		
		}
};