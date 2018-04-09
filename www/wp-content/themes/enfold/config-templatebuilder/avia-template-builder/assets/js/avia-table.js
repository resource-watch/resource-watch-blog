
(function($)
{
	"use strict";
	$.AviaModal.register_callback = $.AviaModal.register_callback || {};
	
	$.AviaModal.register_callback.modal_load_tablebuilder = function(passed_scope)
	{
		var scope	= passed_scope || this.modal,
			$scope	= $(scope),
			$table  = $(scope).find('.avia-table'),
			open	= false,
			
			methods = {
			
				init: function()
				{
					methods.attachEvents();
					methods.on_load();
				},
				
				attachEvents: function()
				{
					scope.on('change', '.avia-table-data-container', methods.update_textfield);
					scope.on('click' , '.avia-table-cell:not(.avia-delete-row .avia-table-cell, .avia-table-col-style .avia-table-cell, .avia-button-row .avia-table-cell)', methods.show_editor);
					scope.on('click' , '.avia-button-row .avia-table-cell', methods.add_button);
					scope.on('click' , '.avia-attach-table-row', methods.add_row);
					scope.on('click' , '.avia-attach-table-col', methods.add_col);
					scope.on('click' , '.avia-delete-row .avia-table-cell', methods.remove_col);
					scope.on('click' , '.avia-attach-delete-table-row', methods.remove_row);
					scope.on('click' , '.avia_sorthandle .avia-delete', methods.remove_element);
					scope.on('click' , '.av-table-pos-button', methods.change_order)
					scope.on('change', 'select[name=column_style]', methods.change_col_class);
					scope.on('change', 'select[name=row_style]', methods.change_row_class);
					scope.on('click' ,  methods.close_editor);
					scope.on('keyup' , 	methods.cell_tab);
					scope.find('.avia-delete-row .avia-table-cell, .avia-table-builder-add-buttons a').disableSelection();	
				},
				
				on_load: function()
				{
					$table.find('textarea').attr('name','content');
				},
				
				cell_tab: function(e)
				{
					if(e.keyCode == 9 && open)
					{
						var elements	= $table.find('.avia-table-cell:not(.avia-table-cell-delete, .avia-delete-row .avia-table-cell, .avia-template-row .avia-table-cell, .avia-table-cell-style, .avia-table-col-style .avia-table-cell)'),
							index		= elements.index(open),
							direction	= e.shiftKey ? -1 : 1,
							next		= elements.filter(':eq('+(index + direction)+')');
						
							if(!next.length)
							{
								next = direction == 1 ? elements.filter(':eq(0)') : elements.filter(':last');
							}
							
							e.preventDefault();
							e.stopPropagation();
							next.trigger('click');
					}
				},
				
				show_editor: function(e)
				{
					e.stopPropagation();
					if(open != this) methods.close_editor();
					
					this.className += " avia-show-editor ";
					$(this).parents('.avia-table-cell:eq(0)').add(this).find('.avia-table-data-container').focus();
					
					open = this;
				},
				
				close_editor: function()
				{
					$scope.find('.avia-show-editor').removeClass('avia-show-editor').find('.avia-table-data-container').trigger('change');
				},
				
				update_textfield: function()
				{
					var value = this.value, field = $(this).prev('.avia-table-content').html(avia_nl2br(value));
				},
				
				add_row: function()
				{
					var template = $scope.find('.avia-template-row'),
						clone	 = template.clone().removeClass('avia-template-row').insertBefore(template);
				},
				
				add_col: function()
				{
					var columns	 = $scope.find('.avia-template-row .avia-table-cell');
			
					if(columns.length <= 21)
					{
						var	template = $scope.find('.avia-template-row .avia-table-cell:eq(1)'),
							insert	 = $scope.find('.avia-table-row .avia-table-cell-delete'),
							clone	 = template.clone().attr('class','avia-table-cell').insertBefore(insert),
							dropdown = $scope.find('.avia-table-cell:eq(1)').html(),
							insert2  = $scope.find('.avia-table-col-style .avia-table-cell:not(.avia-table-cell-delete):last');
							
							insert2.html(dropdown).find('select').val('').trigger('change');
					}
						
					
				},
				remove_row: function()
				{
					var elements = $table.find('.avia-table-row'),
						row = $(this).parents('.avia-table-row:eq(0)');
					
					if(elements.length > 4)
					{
						row.remove();
					}
					else
					{
						row.find('.avia-table-data-container').val('').trigger('change');
					}
				},
				remove_col: function(e)
				{
					var elements = $(this).parents('.avia-table-row:eq(0)').find('.avia-table-cell'),
						index = elements.index(this);
					
					if(elements.length > 3)
					{
						$table.find('.avia-table-row .avia-table-cell:nth-child('+(index + 1)+')').remove();
					}
				},
				
				change_row_class: function()
				{
					var select		= $(this),
						current		= select.parents('.avia-table-cell:eq(0)'),
						elements 	= select.parents('.avia-table-row:eq(0)').find('.avia-table-cell'),
						index 		= elements.index(current),
						row			= select.parents('.avia-table-row:eq(0)'),
						classNames 	= select.find('option').map(function(){return this.value}).get().join(" "),
						newClass 	= select.val();
						
						row.removeClass(classNames).addClass(newClass);
						
						if(newClass == 'avia-button-row')
						{
							row.find('textarea').val("").trigger('change');
						}
						else
						{
							/* old version: removes the content of the table when class is changed. doesnt need vars: current, elements, index
							var template = $scope.find('.avia-template-row .avia-table-cell:eq(1)');
							row.find('.avia-table-cell:not(.avia-table-cell-style.avia-table-cell, .avia-table-cell-delete.avia-table-cell)').html(template.html());
							*/
							
							$table.find('.avia-table-row .avia-table-cell:not(.avia-delete-row .avia-table-cell):nth-child('+(index + 1)+')').removeClass(classNames).addClass(newClass);
						}
				},
				
				change_col_class: function()
				{
					var select 		= $(this),
						current		= select.parents('.avia-table-cell:eq(0)'),
						elements 	= select.parents('.avia-table-row:eq(0)').find('.avia-table-cell'),
						index 		= elements.index(current),
						classNames 	= select.find('option').map(function(){return this.value}).get().join(" "),
						newClass 	= select.val();
						
						$table.find('.avia-table-row .avia-table-cell:not(.avia-delete-row .avia-table-cell):nth-child('+(index + 1)+')').removeClass(classNames).addClass(newClass);
						
				},
				
				add_button: function()
				{
					var template = $("#avia-tmpl-avia_sc_button").html(),
						current		= $(this);
						
						if(!current.is('.avia-table-cell')) current = current.parents('.avia-table-cell:eq(0)');
						
						if(current.find('.avia-edit-element, select').length == 0)
						{
							current.html(template);
							current.find('textarea').attr('name','content').trigger('click');
						}
				
				},
				
				remove_element: function(e)
				{
					var select 		= $(this),
						template 	= $scope.find('.avia-template-row .avia-table-cell:eq(1)'),
						current		= select.parents('.avia-table-cell:eq(0)').html(template.html());
						e.stopImmediatePropagation();
				},
				
				change_order: function(e)
				{
					var button 		= $(this),
						direction	= button.data('direction');
						
						
					if(direction == 'up' || direction == 'down')
					{
						methods.change_row_order(button, direction);
					}
					else
					{
						methods.change_col_order(button, direction);
					}
					return false;
				},
				
				change_row_order: function(button, direction)
				{
					var row 	= button.parents('.avia-table-row').eq(0),
						moveTo  = direction === "up" ? row.prev() : row.next();
					
					//if its the first or last visible row dont move it
					if(moveTo.is('.avia-template-row') || moveTo.is('.avia-attach-table-col-style')) return;
					
					if(direction === "up")
					{
						row.insertBefore(moveTo);
					}
					else
					{
						row.insertAfter(moveTo);
					}
					
				},
				
				change_col_order: function(button, direction)
				{
					var current		= button.parents('.avia-table-cell:eq(0)'),
						elements 	= button.parents('.avia-table-row:eq(0)').find('.avia-table-cell'),
						index 		= elements.index(current),
						move_els	= $table.find('.avia-table-row .avia-table-cell:nth-child('+(index + 1)+')');
					
					if((index === 1 && direction === "left") || (index === elements.length - 2 && direction === "right")) return;
					
					move_els.each(function()
					{
						var col = $(this);
						
						if(direction === "left")
						{
							col.insertBefore(col.prev());
						}
						else
						{
							col.insertAfter(col.next());
						}
						
					});
				},
				
				
			};
			
			
			methods.init();
	}
	
	
	//table shortcode constructor
	$.AviaModal.register_callback.before_table_save = function(values, save_param)
	{
		if(values.column_style.length == "") values.column_style = [""];
		if(typeof values.column_style === 'string') values.column_style = [values.column_style];
		
		var columns = values.column_style.length,
			rows	= values.row_style.length - 3,
			output	= "",
			index 	= 0;
			
			output += "[av_table";
			
			for(var y in values)
			{
				if(y.indexOf('aviaTB') != -1)
				{
					output += " " + y.replace(/aviaTB/g,"") + "='" + values[y] + "'";
				}
			}
			
			output += "]\n"
			
			for(var i = 0; i < rows; i++)
			{
				output += "[av_row row_style='"+values.row_style[i+1]+"']";
				for(var j = 0; j < columns; j++)
				{
					output += "[av_cell col_style='"+values.column_style[j]+"']";
					output += values.content[index];
					output += "[/av_cell]"
					index++;
				}
				output += "[/av_row]\n"
			}
			
			output += "[/av_table]\n\n";
			
		return output;
	}

	
	
		
})(jQuery);	 
