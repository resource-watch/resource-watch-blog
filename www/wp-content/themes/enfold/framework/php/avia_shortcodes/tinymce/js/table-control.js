function ScnTableMaker(table_marker, max_val, f) {

	
	var this_stored		= this,
		thickbox		= jQuery('#TB_window:eq(0)').addClass('table_creator_thickbox flexible_thickbox'),
		main_table		= jQuery(thickbox).find('#scn-options-table'),
		new_table		= jQuery("<table class='scn-custom-table scn-label-table'></table>"),
		send_button		= thickbox.find('#scn-btn-insert'),
		options_table	= false, options_table_hr = false, options_column = false, options_column_hr = false,
		generated_table = false, 
		dropdowns,
		selectsToBuild	= {
		
			1: {
				id: 'scn-table-columns',
				options: 8,
				std: 3,
				label:'Table Columns'
			},
			
			2: {
				id: 'scn-table-rows',
				options: 50,
				std: 3,
				label:'Table Rows'
			}
			
			/*
			3: {
				id: 'scn-table-style',
				options: {'th-row':'Special Style for first Row', 'th-column':'Special Style for first Column', 'th-column th-row': 'Special Style for both: first Column and first Row'},
				std: 'th-column th-row',
				label:'Table Style'
			},
			*/
		},
		
		tableOptions = {
		
			row: {
				id: 'scn-row-otions',
				options: {'default':'Default Row', 'pricing-row': 'Pricing Row', 'button-row':'Button Row'},
				std: 'default'
			},
			
			column: {
				id: 'scn-column-otions',
				options: {'default':'Default Column', 'highlighted': 'Highlight Column'},
				std: 'default'
			}
		};
		
		
		
		
		
		
		 
	
	this.init = function () {
        	
        	this.setTickbox();
        	this.addStyles();
        	this.buildSelectControls(selectsToBuild);
        	
        	this.recalc_table();
        	this.bindStuff();
    };
    
    
    
    this.addStyles = function()
    {
    	var style	= jQuery('<link rel="stylesheet" href="'+avia_framework_globals.installedAt+'css/shortcodes.css" type="text/css" media="screen"/>').prependTo(thickbox),
    		dynamic	= jQuery('<style type="text/css">').prependTo(thickbox);
    	
    	dynamic.html(avia_framework_globals.backend_style);
    	send_button.parent('div').css({clear:"both"});
    };
    
    
    
    
     this.setTickbox = function()
    {
    	thickbox.css({marginLeft:(thickbox.width()/2) * -1});
    };
    
    
    
    
    this.recalc_table = function()
    {
    	if(!dropdowns) {dropdowns = new_table.find('select');}
    
    	var rows	= dropdowns.filter('#scn-table-rows').val(),
    		columns	= dropdowns.filter('#scn-table-columns').val(),
    		style	= dropdowns.filter('#scn-table-style').val();
    	
    	this.buildTable(rows, columns, style);
    	
    	//trigger change event on the option table dropdowns so new rows get the styles as well
    	options_table.find('select').filter(function(){ if(jQuery(this).val() !== 'default') { return true;} }).trigger('change');
    };
    
    
    
    
    this.buildTable = function(rows, columns, style)
    {	
    	var avia_wrapper,
    		columns_ot,
    		columns_gt,
    		rows_gt,
    		rows_oc,
    		last_table,
    		first_run = false;
    	
    	if(!options_table)
    	{ 	
    		options_table = jQuery("<table class='scn-custom-table scn-options-table'></table>").insertAfter(new_table);
    		options_table_hr = jQuery("<tr></tr>").appendTo(options_table);
    		first_run = true;
    	}
    	
    	if(!options_column)
    	{
    		options_column = jQuery("<table class='scn-custom-table scn-options-column'></table>").insertAfter(options_table);
    	}
    	
    	if(!generated_table)
    	{ 
    		avia_wrapper = jQuery("<div>").addClass('avia_table').insertAfter(options_column);
    		generated_table = jQuery("<table class='scn-generated-table "+style+"'></table>").data({rows:0, columns:0, style:style}).appendTo(avia_wrapper);
    		avia_wrapper.wrap('<div id="top" />')
    		avia_wrapper.avia_instant_editor({
    		
    			elements: 'td',
    			input: {avia_text:'textarea'},
    			output:'',
    			start:'click',
    			special_buttons:{
					avia_table_tick: {
							label: '&#x2713;',
							code: '[table_icon tick]'
					},
					avia_table_plus: {
							label: '+',
							code: '[table_icon plus]'
					},
					avia_table_minus: {
							label: '-',
							code: '[table_icon minus]'
					},
					avia_table_button: {
							label: 'button',
							code: '[button link="http://kriesi.at"]Label[/button]'
					}
    			}
    		
    		});
    	}
    	
    	last_table = generated_table.data();
    	
    	
    	if(last_table.rows <= parseInt(rows,10))
    	{	
    		//build left floating option column
    		rows_oc = this.build_rows((rows - last_table.rows), this.build_columns(1, this.buildSelect(tableOptions.row.options, tableOptions.row.std)));
    		jQuery(rows_oc).appendTo(options_column);
    	
    		//build the real table
    		rows_gt = this.build_rows((rows - last_table.rows), this.build_columns((columns) ));
    		jQuery(rows_gt).appendTo(generated_table);
    	}
    	else
    	{
    		generated_table.find('tr').slice(rows,last_table.rows).remove();
    		options_column.find('tr').slice(rows,last_table.rows).remove();
    	}
    	
    	
    	
    	if(last_table.columns <= parseInt(columns,10))
    	{
    		columns_ot = this.build_columns((columns - last_table.columns), this.buildSelect(tableOptions.column.options, tableOptions.column.std));
    		jQuery(columns_ot).appendTo(options_table_hr);
    		
    		if(last_table.columns !== 0)
    		{
    			columns_gt = this.build_columns((columns - last_table.columns));
    			jQuery(columns_gt).appendTo(generated_table.find('tr'));
    		}
    	}
    	else
    	{
    		options_table_hr.find('td').slice(columns,last_table.columns).remove();
    		generated_table.find('tr').each(function()
    		{
    			jQuery(this).find('td').slice(columns,last_table.columns).remove();
    		});
    		
    	}
    	
    	
    	if(first_run)
    	{
    		options_column.find('select:eq(0)').append('<option value="description_row">Description Row</option>');
    		options_table.find('select:eq(0)').append('<option value="description_column">Description Column</option>');
    	}
    	
    	
    	//update data
    	last_table.columns = columns;
    	last_table.rows = rows;
    	
    };
    
    
    
    
    
    this.build_columns = function(columns, data)
    {
    	var i, output = "", interim_data;
    	
    	if(typeof data === 'object')
    	{
    		interim_data = jQuery('<div></div>');
    		data.appendTo(interim_data);
    		data = interim_data.html();
    	}
    	
    	if(typeof data === 'undefined')
    	{
    		data = 'Edit';
    	}
    	
    	for(i = 1; i <= columns; i++)
    	{
    		output += "<td>"+data+"</td>";
    	}
    	
    	return output;
    };
    
    
    
    
    
    
    this.build_rows = function(rows, data)
    {
    	var i, output = "";
    	for(i = 1; i <= rows; i++)
    	{
    		output += "<tr>"+data+"</tr>";
    	}
    	
    	return output;
    };
    
    
  
  
  
    this.buildSelectControls = function (selectsToBuild) 
    {
    	var newselect, newoptions, select_key, option_key, i, generate_options, current, activeClass, table_row;
    	for(select_key in selectsToBuild)
    	{
    		newselect			= "";
    		current				= selectsToBuild[select_key].std;
    		generate_options	= selectsToBuild[select_key].options;

			newselect = this.buildSelect(generate_options, current);
			
    		table_row = "<tr><th><label>"+selectsToBuild[select_key].label+"</label></th><td><select id='"+selectsToBuild[select_key].id+"'>"+newselect.html()+"</select></td></tr>";
    		jQuery(table_row).appendTo(new_table);
    	} 
  		
  		new_table.insertAfter(main_table);
    };
    
    
    
    
    
    
    this.buildSelect = function(options, active)
    {
    	var newselect	= jQuery("<select></select>"),
    		activeClass	= "",
    		newoptions	= "";
    	
    	if(typeof options === 'number')
		{
			for(i = 1; i <= options; i++)
			{
				if(active === i){activeClass = "selected = 'selected' ";}
				newoptions += "<option "+activeClass+" value='"+i+"'>"+i+"</option>";
				activeClass = "";
			}
		}
		else
		{
			for(i in options)
			{
				if(active === i){activeClass = "selected = 'selected' ";}
				newoptions += "<option "+activeClass+" value='"+i+"'>"+options[i]+"</option>";
				activeClass = "";
			}
		}
    
    	jQuery(newoptions).appendTo(newselect);
    	return newselect;
    };
    
    
    
    
    
    this.regenerate_class = function(select_item, row, col, elements)
	{
		var  select_item = jQuery(select_item), option_vals = select_item.find('option'), value = select_item.val();
	
		if(!elements)
		{
			if(row !== false)
			{
				elements = generated_table.find('tr:eq('+row+')');
			}
			else
			{
				elements = generated_table.find('td:nth-child('+(col + 1)+')');
			}
		}

		elements.each(function(){
		
			var current_el = jQuery(this);
		
			option_vals.each(function()
			{
				current_el.removeClass(this.value);
			});
			
			if('default' !== value)
			{
				current_el.addClass(value);
			}
			
		});
	};
	
	
	
	
	
    this.bindStuff = function()
    {
    	this_stored = this;
    	jQuery(window).resize( this.setTickbox );
    	dropdowns = new_table.find('select').on('change', function(){ this_stored.recalc_table(); });
    	
    	options_table.on('change','select', function()
    	{
    		var index	= options_table.find('select').index(this);
    		this_stored.regenerate_class(this, false, index);
    		
    		if(index === 0)
    		{
    			this_stored.regenerate_class(this, false, false, generated_table);
    		}
    	});
    	
    	options_column.on('change','select', function()
    	{
    		var index	= options_column.find('select').index(this);
    		this_stored.regenerate_class(this, index, false);
    		
    		if(index === 0)
    		{
    			this_stored.regenerate_class(this, false, false, generated_table);
    		}
    	});
    	
    	
    	send_button.hover(function(){jQuery(window).trigger('click');});
    	
    };

	
	
	this.init()
	
}
