/*
* adds undo and redo functionality to the Avia Builder
*/

(function($)
{
	"use strict";
	$.AviaElementBehavior = $.AviaElementBehavior || {};
	
	
	$.AviaElementBehavior.history = function (options) {
        
        var defaults = {
        	
        	steps: 40,						// maximum number of steps that are saved
        	monitor: "",					// selector for element to watch
        	editor: "",						// id of editor that holds the html code
        	buttons: "",					// selector for elements that gets the buttons attached
        	event: "avia-storage-update"	// create snapshot when this event is fired on the monitor element	
        }
        
        //no web storage? stop here :)
		if(typeof(Storage) === "undefined") return false;
        
        this.options	= $.extend({}, defaults, options);
        this.setUp();
	
	}
	
	$.AviaElementBehavior.history.prototype = {
	
		setUp: function()
		{
			this.canvas	 = $(this.options.monitor);
			this.wrapper = this.canvas.parent();
        	this.buttons = $(this.options.buttons);
			this.editor	 = $(this.options.editor);
			
			//create a unqiue array key for this post
			this.key	 = this.create_array_key();
			this.storage = this.get() || [];
			this.max 	 = this.storage.length-1;
			this.index	 = this.get(this.key +'index'); 
			if(typeof this.index == 'undefined' || this.index == null) { this.index = this.storage.length-1; }
			
			//attach buttons to html container
			this.undoBtn = $('<a href="#undo" class="avia-undo-button " title="'+avia_history_L10n.undo_label+'"><-</a>').appendTo(this.buttons);
			this.redoBtn = $('<a href="#redo" class="avia-redo-button " title="'+avia_history_L10n.redo_label+'">-></a>').appendTo(this.buttons);
			
			this.clear(); // <- clear storage for testing purpose
			this.bindEvents();
		},
		
		//creates the array key for this posts history
		create_array_key: function()
		{
			var key = "avia" + avia_globals.themename + avia_globals.themeversion + avia_globals.post_id + avia_globals.builderversion;
				key = key.replace(/[^a-zA-Z0-9]/g,"").toLowerCase();
				return key;
		},
		
		bindEvents: function()
		{
			var obj = this;
		
			this.canvas.on('avia-storage-update', function(){ obj.do_snapshot(); });
			this.wrapper.on('click', '.avia-undo-button', function(){ obj.undo(); });
			this.wrapper.on('click', '.avia-redo-button', function(){ obj.redo(); });
			
		},
		
		set: function(passed_key, passed_value)
		{
			var key 	= passed_key || this.key,
				value 	= passed_value || JSON.stringify(this.storage);
				
                try 
                {
                    sessionStorage.setItem(key, value);
                } 
                catch(e) 
                {
                    avia_log('Storage Limit reached. Your Browser does not offer enough session storage to save more steps for the undo/redo history.')
                    avia_log(e)
                    this.clear();
                    this.redoBtn.addClass('avia-inactive-step');
                    this.undoBtn.addClass('avia-inactive-step');
                }	
			
		},
		
		get: function(passed_key)
		{
			var key = passed_key || this.key;
			return JSON.parse(sessionStorage.getItem(key));
		},
		
		clear: function()
		{
			sessionStorage.removeItem(this.key);
			sessionStorage.removeItem(this.key +'index');
			this.storage = [];
			this.index = null;
		},
		
		redo: function()
		{
			
			if(this.index + 1 <= this.max)
			{
				this.index++
				this.update_canvas(this.storage[this.index]);
			}
			return false;
		},
		
		undo: function()
		{
			
			if(this.index - 1 >= 0)
			{
				this.index--
				this.update_canvas(this.storage[this.index]);
			}
			
			return false;
		},
		
		update_canvas: function(values)
		{
			if(typeof this.tinyMCE == 'undefined')
			{
				this.tinyMCE = typeof window.tinyMCE == 'undefined' ? false : window.tinyMCE.get(this.options.editor.replace('#',''));
			}
			
			if(this.tinyMCE)
			{
				this.tinyMCE.setContent(window.switchEditors.wpautop(values[0]), {format:'html'});
			}
			
			this.editor.val(values[0]);
			this.canvas.html(values[1]);
			sessionStorage.setItem(this.key +'index', this.index);
			
			if(this.index + 1 > this.max)
			{
				this.redoBtn.addClass('avia-inactive-step');
			}
			else
			{
				this.redoBtn.removeClass('avia-inactive-step');
			}
			
			if(this.index <= 0)
			{
				this.undoBtn.addClass('avia-inactive-step');
			}
			else
			{
				this.undoBtn.removeClass('avia-inactive-step');
			}
			
			this.canvas.trigger('avia-history-update');
		},
		
		do_snapshot: function()
		{
			//update all textareas html with the actual value, otherwise jquerys html() fetches the values that were present on page load
			this.canvas.find('textarea').each(function(){
			
				this.innerHTML = this.value;
			
			});
			
			//set storage, index
			this.storage = this.storage || this.get() || [];
			this.index	 = this.index  || this.get(this.key +'index'); 
			if(typeof this.index == 'undefined' || this.index == null) { this.index = this.storage.length-1; }
			
			var snapshot 	= [this.editor.val(), this.canvas.html().replace(/avia_pop_class/g,'')],
				lastStorage	= this.storage[this.index]
		
			//create a new snapshot if none exists or if the last stored snapshot doesnt match the current state
			if(typeof lastStorage === 'undefined' || (lastStorage[0] !== snapshot[0]) )
			{
				this.index ++;
			
				//remove all steps after the current one
				this.storage = this.storage.slice(0, this.index);
				
				//add the latest step to the array
				this.storage.push(snapshot);
				
				//if we got more steps than defined in our options remove the first step
				if(this.options.steps < this.storage.length) this.storage.shift();
				
				//set the browser storage object
				this.set();
			}
			
			this.max = this.storage.length-1;
			
				//set redo and undo button if storage is on the last index
			if(this.storage.length == 1 || this.index == 0)
			{
				this.undoBtn.addClass('avia-inactive-step');
			}
			else
			{
				this.undoBtn.removeClass('avia-inactive-step');
			}
			
			if(this.storage.length-1 == this.index)
			{
				this.redoBtn.addClass('avia-inactive-step');
			}
			else
			{
				this.redoBtn.removeClass('avia-inactive-step');
			}

		}
	};

	
})(jQuery);	 

