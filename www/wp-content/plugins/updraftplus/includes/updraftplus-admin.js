/**
 * Send an action over AJAX. A wrapper around jQuery.ajax. In future, all consumers can be reviewed to simplify some of the options, where there is historical cruft.
 * N.B. updraft_iframe_modal() below uses the Ajax URL for the iframe's src attribute
 *
 * @param {string}   action   - the action to send
 * @param {*}        data     - data to send
 * @param {Function} callback - will be called with the results
 * @param {object}   options  -further options. Relevant properties include:
 * - [json_parse=true] - whether to JSON parse the results
 * - [alert_on_error=true] - whether to show an alert box if there was a problem (otherwise, suppress it)
 * - [action='updraft_ajax'] - what to send as the action parameter on the AJAX request (N.B. action parameter to this function goes as the 'subaction' parameter on the AJAX request)
 * - [nonce=updraft_credentialtest_nonce] - the nonce value to send.
 * - [nonce_key='nonce'] - the key value for the nonce field
 * - [timeout=null] - set a timeout after this number of seconds (or if null, none is set)
 * - [async=true] - control whether the request is asynchronous (almost always wanted) or blocking (would need to have a specific reason)
 * - [type='POST'] - GET or POST
 */
function updraft_send_command(action, data, callback, options) {
	
	default_options = {
		json_parse: true,
		alert_on_error: true,
		action: 'updraft_ajax',
		nonce: updraft_credentialtest_nonce,
		nonce_key: 'nonce',
		timeout: null,
		async: true,
		type: 'POST'
	}
	
	if ('undefined' === typeof options) options = {};

	for (var opt in default_options) {
		if (!options.hasOwnProperty(opt)) { options[opt] = default_options[opt]; }
	}
	
	var ajax_data = {
		action: options.action,
		subaction: action,
	};
	
	ajax_data[options.nonce_key] = options.nonce;
	
	// TODO: Once all calls are routed through here, change the listener in admin.php to always take the data from the 'data' attribute, instead of in the naked $_POST/$_GET
	if (typeof data == 'object') {
		for (var attrname in data) { ajax_data[attrname] = data[attrname]; }
	} else {
		ajax_data.action_data = data;
	}
	
	var ajax_opts = {
		type: options.type,
		url: ajaxurl,
		data: ajax_data,
		success: function(response, status) {
			if (options.json_parse) {
				try {
					var resp = ud_parse_json(response);
				} catch (e) {
					console.log(e);
					console.log(response);
					if (options.alert_on_error) { alert(updraftlion.unexpectedresponse+' '+response); }
					return;
				}
				if ('function' == typeof callback) callback(resp, status, response);
			} else {
				if ('function' == typeof callback) callback(response, status);
			}
		},
		error: function(response, status, error_code) {
			if ('function' == typeof options.error_callback) {
				options.error_callback(response, status, error_code);
			} else {
				console.log("updraft_send_command: error: "+status+" ("+error_code+")");
				console.log(response);
			}
		},
		dataType: 'text',
		async: options.async
	};
	
	if (null != options.timeout) { ajax_opts.timeout = options.timeout; }
	
	jQuery.ajax(ajax_opts);
	
}

/**
 * Opens the dialog box for confirmation of whether to delete a backup, plus options if relevant
 *
 * @param {string}  key        - The UNIX timestamp of the backup
 * @param {string}  nonce      - The backup job ID
 * @param {boolean} showremote - Whether or not to show the "also delete from remote storage?" checkbox
 */
function updraft_delete(key, nonce, showremote) {
	jQuery('#updraft_delete_timestamp').val(key);
	jQuery('#updraft_delete_nonce').val(nonce);
	if (showremote) {
		jQuery('#updraft-delete-remote-section, #updraft_delete_remote').removeAttr('disabled').show();
	} else {
		jQuery('#updraft-delete-remote-section, #updraft_delete_remote').hide().attr('disabled','disabled');
	}
	if (key.indexOf(',') > -1) {
		jQuery('#updraft_delete_question_singular').hide();
		jQuery('#updraft_delete_question_plural').show();
	} else {
		jQuery('#updraft_delete_question_plural').hide();
		jQuery('#updraft_delete_question_singular').show();
	}
	jQuery('#updraft-delete-modal').dialog('open');
}

function updraft_remote_storage_tab_activation(the_method){
	jQuery('.updraftplusmethod').hide();
	jQuery('.remote-tab').data('active', false);
	jQuery('.remote-tab').removeClass('nav-tab-active');
	jQuery('.updraftplusmethod.'+the_method).show();
	jQuery('.remote-tab-'+the_method).data('active', true);
	jQuery('.remote-tab-'+the_method).addClass('nav-tab-active');
}

/**
 * Check how many cron jobs are overdue, and display a message if it is several (as determined by the back-end)
 */
function updraft_check_overduecrons() {
	updraft_send_command('check_overdue_crons', null, function(response) {
		if (response && response.hasOwnProperty('m')) {
			jQuery('#updraft-insert-admin-warning').html(response.m);
		}
	}, { alert_on_error: false });
}

function updraft_remote_storage_tabs_setup() {
	
	var anychecked = 0;
	var set = jQuery('.updraft_servicecheckbox:checked');
	
	jQuery(set).each(function(ind, obj) {
		var ser = jQuery(obj).val();
		
		if (jQuery(obj).attr('id') != 'updraft_servicecheckbox_none') {
			anychecked++;
		}
		
		jQuery('.remote-tab-'+ser).show();
		if (ind == jQuery(set).length-1) {
			updraft_remote_storage_tab_activation(ser);
		}
	});

	if (anychecked > 0) {
		jQuery('.updraftplusmethod.none').hide();
	}
	
	// To allow labelauty remote storage buttons to be used with keyboard
	jQuery(document).keyup(function(event) {
		if (32 === event.keyCode || 13 === event.keyCode) {
			if (jQuery(document.activeElement).is("input.labelauty + label")) {
				var for_box = jQuery(document.activeElement).attr("for");
				if (for_box) {
					jQuery("#"+for_box).change();
				}
			}
		}
	});
	
	jQuery('.updraft_servicecheckbox').change(function() {
		var sclass = jQuery(this).attr('id');
		if ('updraft_servicecheckbox_' == sclass.substring(0,24)) {
			var serv = sclass.substring(24);
			if (null != serv && '' != serv) {
				if (jQuery(this).is(':checked')) {
					anychecked++;
					jQuery('.remote-tab-'+serv).fadeIn();
					updraft_remote_storage_tab_activation(serv);
				} else {
					anychecked--;
					jQuery('.remote-tab-'+serv).hide();
					// Check if this was the active tab, if yes, switch to another
					if (jQuery('.remote-tab-'+serv).data('active') == true) {
						updraft_remote_storage_tab_activation(jQuery('.remote-tab:visible').last().attr('name'));
					}
				}
			}
		}

		if (anychecked <= 0) {
			jQuery('.updraftplusmethod.none').fadeIn();
		} else {
			jQuery('.updraftplusmethod.none').hide();
		}
	});
	
	// Add stuff for free version
	jQuery('.updraft_servicecheckbox:not(.multi)').change(function() {
		var svalue = jQuery(this).attr('value');
		if (jQuery(this).is(':not(:checked)')) {
			jQuery('.updraftplusmethod.'+svalue).hide();
			jQuery('.updraftplusmethod.none').fadeIn();
		} else {
			jQuery('.updraft_servicecheckbox').not(this).prop('checked', false);
		}
	});
	
	var servicecheckbox = jQuery('.updraft_servicecheckbox');
	if (typeof servicecheckbox.labelauty === 'function') { servicecheckbox.labelauty(); }
	
}

/**
 * Carries out a remote storage test
 *
 * @param {string}   method          - The identifier for the remote storage
 * @param {callback} result_callback - A callback function to be called with the result
 * @param {string}   instance_id     - The particular instance (if any) of the remote storage to be tested (for methods supporting multiple instances)
 */
function updraft_remote_storage_test(method, result_callback, instance_id) {
	
	var $the_button;
	var settings_selector;
		
	if (instance_id) {
		$the_button = jQuery('#updraft-'+method+'-test-'+instance_id);
		settings_selector = '.updraftplusmethod.'+method+'-'+instance_id;
	} else {
		$the_button = jQuery('#updraft-'+method+'-test');
		settings_selector = '.updraftplusmethod.'+method;
	}
	
	var method_label = $the_button.data('method_label');
	
	$the_button.html(updraftlion.testing_settings.replace('%s', method_label));
	
	var data = {
		method: method
	};
	
	// Add the other items to the data object. The expert mode settings are for the generic SSL options.
	jQuery('#updraft-navtab-settings-content '+settings_selector+' input[data-updraft_settings_test], #updraft-navtab-settings-content .expertmode input[data-updraft_settings_test]').each(function(index, item) {
		var item_key = jQuery(item).data('updraft_settings_test');
		var input_type = jQuery(item).attr('type');
		if (!item_key) { return; }
		if (!input_type) {
			console.log("UpdraftPlus: settings test input item with no type found");
			console.log(item);
			// A default
			input_type = 'text';
		}
		var value = null;
		if ('checkbox' == input_type) {
			value = jQuery(item).is(':checked') ? 1 : 0;
		} else if ('text' == input_type || 'password' == input_type) {
			value = jQuery(item).val();
		} else {
			console.log("UpdraftPlus: settings test input item with unrecognised type ("+input_type+") found");
			console.log(item);
		}
		data[item_key] = value;
	});
	// Data from any text areas or select drop-downs
	jQuery('#updraft-navtab-settings-content '+settings_selector+' textarea[data-updraft_settings_test], #updraft-navtab-settings-content '+settings_selector+' select[data-updraft_settings_test]').each(function(index, item) {
		var item_key = jQuery(item).data('updraft_settings_test');
		data[item_key] = jQuery(item).val();
	});

	updraft_send_command('test_storage_settings', data, function(response, status) {
		$the_button.html(updraftlion.test_settings.replace('%s', method_label));
		if ('undefined' !== typeof result_callback && false != result_callback) {
			result_callback = result_callback.call(this, response, status, data);
		}
		if ('undefined' !== typeof result_callback && false === result_callback) {
			alert(updraftlion.settings_test_result.replace('%s', method_label)+' '+response.output);
			if (response.hasOwnProperty('data')) {
				console.log(response.data);
			}
		}
	});
}

function backupnow_whichfiles_checked(onlythesefileentities){
	jQuery('#backupnow_includefiles_moreoptions input[type="checkbox"]').each(function(index) {
		if (!jQuery(this).is(':checked')) { return; }
		var name = jQuery(this).attr('name');
		if (name.substring(0, 16) != 'updraft_include_') { return; }
		var entity = name.substring(16);
		if (onlythesefileentities != '') { onlythesefileentities += ','; }
		onlythesefileentities += entity;
	});
// console.log(onlythesefileentities);
	return onlythesefileentities;
}

/**
 * A method to get all the selected table values from the backup now modal
 *
 * @param {string} onlythesetableentities an empty string to append values to
 *
 * @return {string} a string that contains the values of all selected table entities and the database the belong to
 */
function backupnow_whichtables_checked(onlythesetableentities){
	var send_list = false;
	jQuery('#backupnow_database_moreoptions input[type="checkbox"]').each(function(index) {
		if (!jQuery(this).is(':checked')) { send_list = true; return; }
	});

	onlythesetableentities = jQuery("input[name^='updraft_include_tables_']").serializeArray();

	if (send_list) {
		return onlythesetableentities;
	} else {
		return true;
	}
}

function updraft_deleteallselected() {
	var howmany = 0;
	var remote_exists = 0;
	var key_all = '';
	var nonce_all = '';
	var remote_all = '';
	jQuery('#updraft-navtab-backups-content .updraft_existing_backups .updraft_existing_backups_row.backuprowselected').each(function(index) {
		howmany++;
		var nonce = jQuery(this).data('nonce');
		if (nonce_all) { nonce_all += ','; }
		nonce_all += nonce;
		var key = jQuery(this).data('key');
		if (key_all) { key_all += ','; }
		key_all += key;
		var has_remote = jQuery(this).find('.updraftplus-remove').data('hasremote');
		if (remote_all) { remote_all += ','; }
		remote_all += has_remote;
	});
	updraft_delete(key_all, nonce_all, remote_all);
}

function updraft_openrestorepanel(toggly) {
	// jQuery('.download-backups').slideDown(); updraft_historytimertoggle(1); jQuery('html,body').animate({scrollTop: jQuery('#updraft_lastlogcontainer').offset().top},'slow');
	updraft_console_focussed_tab = 2;
	updraft_historytimertoggle(toggly);
	jQuery('#updraft-navtab-status-content').hide();
	jQuery('#updraft-navtab-expert-content').hide();
	jQuery('#updraft-navtab-settings-content').hide();
	jQuery('#updraft-navtab-addons-content').hide();
	jQuery('#updraft-navtab-backups-content').show();
	jQuery('#updraft-navtab-backups').addClass('nav-tab-active');
	jQuery('#updraft-navtab-expert').removeClass('nav-tab-active');
	jQuery('#updraft-navtab-settings').removeClass('nav-tab-active');
	jQuery('#updraft-navtab-status').removeClass('nav-tab-active');
	jQuery('#updraft-navtab-addons').removeClass('nav-tab-active');
}

function updraft_delete_old_dirs() {
	return true;
}

function updraft_initiate_restore(whichset) {
	jQuery('#updraft-migrate-modal').dialog('close');
	jQuery('#updraft-navtab-backups-content .updraft_existing_backups button[data-backup_timestamp="'+whichset+'"]').click();
}

function updraft_restore_setoptions(entities) {
	var howmany = 0;
	jQuery('input[name="updraft_restore[]"]').each(function(x,y) {
		var entity = jQuery(y).val();
		var epat = entity+'=([0-9,]+)';
		var eregex = new RegExp(epat);
		var ematch = entities.match(eregex);
		if (ematch) {
			jQuery(y).removeAttr('disabled').data('howmany', ematch[1]).parent().show();
			howmany++;
			if ('db' == entity) { howmany += 4.5;}
			if (jQuery(y).is(':checked')) {
				// This element may or may not exist. The purpose of explicitly calling show() is that Firefox, when reloading (including via forwards/backwards navigation) will remember checkbox states, but not which DOM elements were showing/hidden - which can result in some being hidden when they should be shown, and the user not seeing the options that are/are not checked.
				jQuery('#updraft_restorer_'+entity+'options').show();
			}
		} else {
			jQuery(y).attr('disabled','disabled').parent().hide();
		}
	});
	var cryptmatch = entities.match(/dbcrypted=1/);
	if (cryptmatch) {
		jQuery('.updraft_restore_crypteddb').show();
	} else {
		jQuery('.updraft_restore_crypteddb').hide();
	}
	var dmatch = entities.match(/meta_foreign=([12])/);
	if (dmatch) {
		jQuery('#updraft_restore_meta_foreign').val(dmatch[1]);
	} else {
		jQuery('#updraft_restore_meta_foreign').val('0');
	}
	var height = 336+howmany*20;
	jQuery('#updraft-restore-modal').dialog("option", "height", height);
}

function updraft_backup_dialog_open() {
	jQuery('#backupnow_includefiles_moreoptions').hide();
	if (updraft_settings_form_changed) {
		if (window.confirm(updraftlion.unsavedsettingsbackup)) {
			jQuery('#backupnow_label').val('');
			jQuery('#updraft-backupnow-modal').dialog('open');
		}
	} else {
		jQuery('#backupnow_label').val('');
		jQuery('#updraft-backupnow-modal').dialog('open');
	}
}

var onlythesefileentities = backupnow_whichfiles_checked('');
if ('' == onlythesefileentities) {
	jQuery("#backupnow_includefiles_moreoptions").show();
} else {
	jQuery("#backupnow_includefiles_moreoptions").hide();
}

function updraft_migrate_dialog_open() {
	jQuery('#updraft_migrate_modal_alt').hide();
	updraft_migrate_modal_default_buttons = {};
	updraft_migrate_modal_default_buttons[updraftlion.close] = function() {
 jQuery(this).dialog("close"); };
	jQuery("#updraft-migrate-modal").dialog("option", "buttons", updraft_migrate_modal_default_buttons);
	jQuery('#updraft-migrate-modal').dialog('open');
	jQuery('#updraft_migrate_modal_main').show();
}

var updraft_restore_stage = 1;
var lastlog_lastmessage = "";
var lastlog_lastdata = "";
var lastlog_jobs = "";
// var lastlog_sdata = { action: 'updraft_ajax', subaction: 'lastlog' };
var updraft_activejobs_nextupdate = (new Date).getTime() + 1000;
// Bits: main tab displayed (1); restore dialog open (uses downloader) (2); tab not visible (4)
var updraft_page_is_visible = 1;
var updraft_console_focussed_tab = 1;

var updraft_settings_form_changed = false;
window.onbeforeunload = function(e) {
	if (updraft_settings_form_changed) return updraftlion.unsavedsettings;
}

/**
 * N.B. This function works on both the UD settings page and elsewhere
 *
 * @param {boolean} firstload Check if this is first load
 */
function updraft_check_page_visibility(firstload) {
	if ('hidden' == document["visibilityState"]) {
		updraft_page_is_visible = 0;
	} else {
		updraft_page_is_visible = 1;
		if (1 !== firstload) { updraft_activejobs_update(true); }
	};
}

// See http://caniuse.com/#feat=pagevisibility for compatibility (we don't bother with prefixes)
if (typeof document.hidden !== "undefined") {
	document.addEventListener('visibilitychange', function() {
updraft_check_page_visibility(0);}, false);
}

updraft_check_page_visibility(1);

var updraft_poplog_log_nonce;
var updraft_poplog_log_pointer = 0;
var updraft_poplog_lastscroll = -1;
var updraft_last_forced_jobid = -1;
var updraft_last_forced_resumption = -1;
var updraft_last_forced_when = -1;

var updraft_backupnow_nonce = '';
var updraft_activejobslist_backupnownonce_only = 0;
var updraft_inpage_hasbegun = 0;

/**
 * Run a backup with show modal with progress.
 *
 * @param {Function} success_callback   callback function after backup
 * @param {String}   onlythisfileentity csv list of file entities to be backed up
 * @param {Array}    extradata          any extra data to be added
 * @param {Integer}  backupnow_nodb      Indicate whether the database should be backed up. Valid values: 0, 1
 * @param {Integer}  backupnow_nofiles  Indicate whether any files should be backed up. Valid values: 0, 1
 * @param {Integer}  backupnow_nocloud  Indicate whether the backup should be uploaded to cloud storage. Valid values: 0, 1
 * @param {String}   label              An optional label to be added to a backup
 */
function updraft_backupnow_inpage_go(success_callback, onlythisfileentity, extradata, backupnow_nodb, backupnow_nofiles, backupnow_nocloud, label) {
	
	backupnow_nodb = ('undefined' === typeof backupnow_nodb) ? 0 : backupnow_nodb;
	backupnow_nofiles = ('undefined' === typeof backupnow_nofiles) ? 0 : backupnow_nofiles;
	backupnow_nocloud = ('undefined' === typeof backupnow_nocloud) ? 0 : backupnow_nocloud;
	label = ('undefined' === typeof label) ? updraftlion.automaticbackupbeforeupdate : label;
	
	// N.B. This function should never be called on the UpdraftPlus settings page - it is assumed we are elsewhere. So, it is safe to fake the console-focussing parameter.
	updraft_console_focussed_tab = 1;
	updraft_inpage_success_callback = success_callback;
	var updraft_inpage_modal_buttons = {};
	var inpage_modal_exists = jQuery('#updraft-backupnow-inpage-modal').length;
	if (inpage_modal_exists) {
		jQuery('#updraft-backupnow-inpage-modal').dialog('option', 'buttons', updraft_inpage_modal_buttons);
	}
	jQuery('#updraft_inpage_prebackup').hide();
	if (inpage_modal_exists) {
		jQuery('#updraft-backupnow-inpage-modal').dialog('open');
	}
	jQuery('#updraft_inpage_backup').show();
	updraft_activejobslist_backupnownonce_only = 1;
	updraft_inpage_hasbegun = 0;
	updraft_backupnow_go(backupnow_nodb, backupnow_nofiles, backupnow_nocloud, onlythisfileentity, extradata, label, '');
}

function updraft_activejobs_update(force) {
	var timenow = (new Date).getTime();
	if (false == force && timenow < updraft_activejobs_nextupdate) { return; }
	updraft_activejobs_nextupdate = timenow + 5500;
	var downloaders = '';
	jQuery('.ud_downloadstatus .updraftplus_downloader, #ud_downloadstatus2 .updraftplus_downloader').each(function(x,y) {
		var dat = jQuery(y).data('downloaderfor');
		if (typeof dat == 'object') {
			if (downloaders != '') { downloaders = downloaders + ':'; }
			downloaders = downloaders + dat.base + ',' + dat.nonce + ',' + dat.what + ',' + dat.index;
		}
	});
	
	var gdata = {
		downloaders: downloaders
	}
	
	try {
		if (jQuery("#updraft-poplog").dialog("isOpen")) {
			gdata.log_fetch = 1;
			gdata.log_nonce = updraft_poplog_log_nonce;
			gdata.log_pointer = updraft_poplog_log_pointer
		}
	} catch (err) {
		console.log(err);
	}

	if (updraft_activejobslist_backupnownonce_only && typeof updraft_backupnow_nonce !== 'undefined' && updraft_backupnow_nonce != '') {
		gdata.thisjobonly = updraft_backupnow_nonce;
	}
	
	updraft_send_command('activejobs_list', gdata, function(response) {
		
		try {
			resp = ud_parse_json(response);
		
			// if (repeat) { setTimeout(function(){updraft_activejobs_update(true);}, nexttimer);}
			if (resp.hasOwnProperty('l')) {
				if (resp.l) {
					jQuery('#updraft_lastlogmessagerow').show();
					jQuery('#updraft_lastlogcontainer').html(resp.l);
				} else {
					jQuery('#updraft_lastlogmessagerow').hide();
					jQuery('#updraft_lastlogcontainer').html('('+updraftlion.nothing_yet_logged+')');
				}
			}
			
			var lastactivity = -1;
			
			jQuery('#updraft_activejobs').html(resp.j);
			jQuery('#updraft_activejobs .updraft_jobtimings').each(function(ind, element) {
				var $el = jQuery(element);
				// lastactivity, nextresumption, nextresumptionafter
				if ($el.data('lastactivity') && $el.data('jobid')) {
					var jobid = $el.data('jobid');
					var new_lastactivity = $el.data('lastactivity');
					if (lastactivity == -1 || new_lastactivity < lastactivity) { lastactivity = new_lastactivity; }
					var nextresumptionafter = $el.data('nextresumptionafter');
					var nextresumption = $el.data('nextresumption');
	// console.log("Job ID: "+jobid+", Next resumption: "+nextresumption+", Next resumption after: "+nextresumptionafter+", Last activity: "+new_lastactivity);
					// Milliseconds
					timenow = (new Date).getTime();
					if (new_lastactivity > 50 && nextresumption >0 && nextresumptionafter < -30 && timenow > updraft_last_forced_when+100000 && (updraft_last_forced_jobid != jobid || nextresumption != updraft_last_forced_resumption)) {
						updraft_last_forced_resumption = nextresumption;
						updraft_last_forced_jobid = jobid;
						updraft_last_forced_when = timenow;
						console.log('UpdraftPlus: force resumption: job_id='+jobid+', resumption='+nextresumption);
						updraft_send_command('forcescheduledresumption', {
							resumption: nextresumption,
							job_id: jobid
						}, function(response) {
							console.log(response);
						}, { json_parse: false, alert_on_error: false });
					}
				}
			});
			
			timenow = (new Date).getTime();
			updraft_activejobs_nextupdate = timenow + 180000;
			// More rapid updates needed if a) we are on the main console, or b) a downloader is open (which can only happen on the restore console)
			if (updraft_page_is_visible == 1 && (1 == updraft_console_focussed_tab || (2 == updraft_console_focussed_tab && downloaders != ''))) {
				if (lastactivity > -1) {
					if (lastactivity < 5) {
						updraft_activejobs_nextupdate = timenow + 1750;
					} else {
						updraft_activejobs_nextupdate = timenow + 5000;
					}
				} else if (lastlog_lastdata == response) {
					updraft_activejobs_nextupdate = timenow + 7500;
				} else {
					updraft_activejobs_nextupdate = timenow + 1750;
				}
			}

			lastlog_lastdata = response;
			
			if (resp.j != null && resp.j != '') {
				jQuery('#updraft_activejobsrow').show();

				if (gdata.hasOwnProperty('thisjobonly') && !updraft_inpage_hasbegun && jQuery('#updraft-jobid-'+gdata.thisjobonly).length) {
					updraft_inpage_hasbegun = 1;
					console.log('UpdraftPlus: the start of the requested backup job has been detected');
				} else if (!updraft_inpage_hasbegun && updraft_activejobslist_backupnownonce_only && jQuery('.updraft_jobtimings.isautobackup').length) {
					autobackup_nonce = jQuery('.updraft_jobtimings.isautobackup').first().data('jobid');
					if (autobackup_nonce) {
						updraft_inpage_hasbegun = 1;
						updraft_backupnow_nonce = autobackup_nonce;
						gdata.thisjobonly = autobackup_nonce;
						console.log('UpdraftPlus: the start of the requested backup job has been detected; id: '+autobackup_nonce);
					}
				} else if (updraft_inpage_hasbegun == 1 && jQuery('#updraft-jobid-'+gdata.thisjobonly+'.updraft_finished').length) {
					// This block used to be a straightforward 'if'... switching to 'else if' ensures that it cannot fire on the same run. (If the backup hasn't started, it may be detected as finished before to it began, on an overloaded server if there's a race).
					// Don't reset to 0 - this will cause the 'began' event to be detected again
					updraft_inpage_hasbegun = 2;
	// var updraft_inpage_modal_buttons = {};
	// updraft_inpage_modal_buttons[updraftlion.close] = function() {
	// jQuery(this).dialog("close");
	// };
	// jQuery('#updraft-backupnow-inpage-modal').dialog('option', 'buttons', updraft_inpage_modal_buttons);
					console.log('UpdraftPlus: the end of the requested backup job has been detected');
					if (typeof updraft_inpage_success_callback !== 'undefined' && updraft_inpage_success_callback != '') {
						// Move on to next page
						updraft_inpage_success_callback.call(false);
					} else {
						jQuery('#updraft-backupnow-inpage-modal').dialog('close');
					}
				}
				if ('' == lastlog_jobs) {
					setTimeout(function() {
jQuery('#updraft_backup_started').slideUp();}, 3500);
				}
			} else {
				if (!jQuery('#updraft_activejobsrow').is(':hidden')) {
					// Backup has now apparently finished - hide the row. If using this for detecting a finished job, be aware that it may never have shown in the first place - so you'll need more than this.
					if (typeof lastbackup_laststatus != 'undefined') { updraft_showlastbackup(); }
					jQuery('#updraft_activejobsrow').hide();
				}
			}
			lastlog_jobs = resp.j;
			
			// Download status
			if (resp.ds != null && resp.ds != '') {
				jQuery(resp.ds).each(function(x, dstatus) {
					if (dstatus.base != '') {
						updraft_downloader_status_update(dstatus.base, dstatus.timestamp, dstatus.what, dstatus.findex, dstatus, response);
					}
				});
			}

			if (resp.u != null && resp.u != '' && jQuery("#updraft-poplog").dialog("isOpen")) {
				var log_append_array = resp.u;
				if (log_append_array.nonce == updraft_poplog_log_nonce) {
					updraft_poplog_log_pointer = log_append_array.pointer;
					if (log_append_array.log != null && log_append_array.log != '') {
						var oldscroll = jQuery('#updraft-poplog').scrollTop();
						jQuery('#updraft-poplog-content').append(log_append_array.log);
						if (updraft_poplog_lastscroll == oldscroll || updraft_poplog_lastscroll == -1) {
							jQuery('#updraft-poplog').scrollTop(jQuery('#updraft-poplog-content').prop("scrollHeight"));
							updraft_poplog_lastscroll = jQuery('#updraft-poplog').scrollTop();
						}
					}
				}
			}
		} catch (err) {
			console.log(updraftlion.unexpectedresponse+' '+response);
			console.log(err);
		}
	}, { json_parse: false, type: 'GET' });
}

/**
 * Opens a dialog window showing the requested (or latest) log file, plus an option to download it
 *
 * @param {string} backup_nonce - the nonce of the log to display, or empty for the latest one
 */
function updraft_popuplog(backup_nonce) {
		
		var loading_message = updraftlion.loading_log_file;
		
		if (backup_nonce) { loading_message += ' (log.'+backup_nonce+'.txt)'; }
	
		jQuery('#updraft-poplog').dialog("option", "title", loading_message);
		jQuery('#updraft-poplog-content').html('<em>'+loading_message+' ...</em> ');
		jQuery('#updraft-poplog').dialog("open");
		
		updraft_send_command('get_log', backup_nonce, function(resp) {

			updraft_poplog_log_pointer = resp.pointer;
			updraft_poplog_log_nonce = resp.nonce;
			
			var download_url = '?page=updraftplus&action=downloadlog&force_download=1&updraftplus_backup_nonce='+resp.nonce;
			
			jQuery('#updraft-poplog-content').html(resp.log);
			
			var log_popup_buttons = {};
			log_popup_buttons[updraftlion.downloadlogfile] = function() {
 window.location.href = download_url; };
			log_popup_buttons[updraftlion.close] = function() {
 jQuery(this).dialog("close"); };
			
			// Set the dialog buttons: Download log, Close log
			jQuery('#updraft-poplog').dialog("option", "buttons", log_popup_buttons);
			jQuery('#updraft-poplog').dialog("option", "title", 'log.'+resp.nonce+'.txt');
			
			updraft_poplog_lastscroll = -1;
			
		}, { type: 'GET', timeout: 60000, error_callback: function(response, status, error_code) {
			var msg = (status == error_code) ? error_code : error_code+" ("+status+")";
			jQuery('#updraft-poplog-content').append(msg);
			console.log(response);
		}});
}

function updraft_showlastbackup() {
	
	updraft_send_command('get_fragment', 'last_backup_html', function(resp) {
		
		response = resp.output;
		
		if (lastbackup_laststatus == response) {
			setTimeout(function() {
 updraft_showlastbackup(); }, 7000);
		} else {
			jQuery('#updraft_last_backup').html(response);
		}
		lastbackup_laststatus = response;
		
	}, { type: 'GET' });
	
}

var updraft_historytimer = 0;
var calculated_diskspace = 0;
var updraft_historytimer_notbefore = 0;
var updraft_history_lastchecksum = false;

function updraft_historytimertoggle(forceon) {
	if (!updraft_historytimer || forceon == 1) {
		updraft_updatehistory(0, 0);
		updraft_historytimer = setInterval(function() {
updraft_updatehistory(0, 0);}, 30000);
		if (!calculated_diskspace) {
			updraftplus_diskspace();
			calculated_diskspace = 1;
		}
	} else {
		clearTimeout(updraft_historytimer);
		updraft_historytimer = 0;
	}
}

function updraft_updatehistory(rescan, remotescan) {
	
	var unixtime = Math.round(new Date().getTime() / 1000);
	
	if (1 == rescan || 1 == remotescan) {
		updraft_historytimer_notbefore = unixtime + 30;
	} else {
		if (unixtime < updraft_historytimer_notbefore) {
			console.log("Update history skipped: "+unixtime.toString()+" < "+updraft_historytimer_notbefore.toString());
			return;
		}
	}
	
	if (rescan == 1) {
		if (remotescan == 1) {
			updraft_history_lastchecksum = false;
			jQuery('#updraft-navtab-backups-content .updraft_existing_backups').html('<p style="text-align:center;"><em>'+updraftlion.rescanningremote+'</em></p>');
		} else {
			updraft_history_lastchecksum = false;
			jQuery('#updraft-navtab-backups-content .updraft_existing_backups').html('<p style="text-align:center;"><em>'+updraftlion.rescanning+'</em></p>');
		}
	}
	
	var what_op = remotescan ? 'remotescan' : (rescan ? 'rescan' : false);
	
	updraft_send_command('rescan', what_op, function(resp) {
		if (resp.hasOwnProperty('logs_exist') && resp.logs_exist) {
			// Show the "most recently modified log" link, in case it was previously hidden (if there were no logs until now)
			jQuery('#updraft_lastlogmessagerow .updraft-log-link').show();
		}
		
		if (resp.hasOwnProperty('migrate_modal') && resp.migrate_modal) {
			jQuery('#updraft_migrate_modal_main').replaceWith(resp.migrate_modal);
		}
		
		if (resp.n != null) { jQuery('#updraft-navtab-backups').html(resp.n); }
		if (resp.t != null) {
			if (resp.cksum != null) {
				if (resp.cksum == updraft_history_lastchecksum) {
					// Avoid unnecessarily refreshing the HTML if the data is the same. This helps avoid resetting the DOM (annoying when debugging), and keeps user row selections.
					return;
				}
				updraft_history_lastchecksum = resp.cksum;
			}
			jQuery('#updraft-navtab-backups-content .updraft_existing_backups').html(resp.t);
			if (resp.data) {
				console.log(resp.data);
			}
		}
	});
}

var updraft_interval_week_val = false;
var updraft_interval_month_val = false;

function updraft_intervals_monthly_or_not(selector_id, now_showing) {
	var selector = '#updraft-navtab-settings-content #'+selector_id;
	var current_length = jQuery(selector+' option').length;
	var is_monthly = ('monthly' == now_showing) ? true : false;
	var existing_is_monthly = false;
	if (current_length > 10) { existing_is_monthly = true; }
	if (!is_monthly && !existing_is_monthly) {
		return;
	}
	if (is_monthly && existing_is_monthly) {
		if ('monthly' == now_showing) {
			// existing_is_monthly does not mean the same as now_showing=='monthly'. existing_is_monthly refers to the drop-down, not whether the drop-down is being displayed. We may need to add these words back.
			jQuery('.updraft_monthly_extra_words_'+selector_id).remove();
			jQuery(selector).before('<span class="updraft_monthly_extra_words_'+selector_id+'">'+updraftlion.day+' </span>').after('<span class="updraft_monthly_extra_words_'+selector_id+'"> '+updraftlion.inthemonth+' </span>');
		}
		return;
	}
	jQuery('.updraft_monthly_extra_words_'+selector_id).remove();
	if (is_monthly) {
		// Save the old value
		updraft_interval_week_val = jQuery(selector+' option:selected').val();
		jQuery(selector).html(updraftlion.mdayselector).before('<span class="updraft_monthly_extra_words_'+selector_id+'">'+updraftlion.day+' </span>').after('<span class="updraft_monthly_extra_words_'+selector_id+'"> '+updraftlion.inthemonth+' </span>');
		var select_mday = (updraft_interval_month_val === false) ? 1 : updraft_interval_month_val;
		// Convert from day of the month (ordinal) to option index (starts at 0)
		select_mday = select_mday - 1;
		jQuery(selector+" option:eq("+select_mday+")").prop('selected', true);
	} else {
		// Save the old value
		updraft_interval_month_val = jQuery(selector+' option:selected').val();
		jQuery(selector).html(updraftlion.dayselector);
		var select_day = (updraft_interval_week_val === false) ? 1 : updraft_interval_week_val;
		jQuery(selector+" option:eq("+select_day+")").prop('selected', true);
	}
}

function updraft_check_same_times() {
	var dbmanual = 0;
	var file_interval = jQuery('#updraft-navtab-settings-content .updraft_interval').val();
	if (file_interval == 'manual') {
// jQuery('#updraft_files_timings').css('opacity', '0.25');
		jQuery('#updraft-navtab-settings-content .updraft_files_timings').hide();
	} else {
// jQuery('#updraft_files_timings').css('opacity', 1);
		jQuery('#updraft-navtab-settings-content .updraft_files_timings').show();
	}
	
	if ('weekly' == file_interval || 'fortnightly' == file_interval || 'monthly' == file_interval) {
		updraft_intervals_monthly_or_not('updraft_startday_files', file_interval);
		jQuery('#updraft-navtab-settings-content #updraft_startday_files').show();
	} else {
		jQuery('.updraft_monthly_extra_words_updraft_startday_files').remove();
		jQuery('#updraft-navtab-settings-content #updraft_startday_files').hide();
	}
	
	var db_interval = jQuery('#updraft-navtab-settings-content .updraft_interval_database').val();
	if (db_interval == 'manual') {
		dbmanual = 1;
// jQuery('#updraft_db_timings').css('opacity', '0.25');
		jQuery('#updraft-navtab-settings-content .updraft_db_timings').hide();
	}
	
	if ('weekly' == db_interval || 'fortnightly' == db_interval || 'monthly' == db_interval) {
		updraft_intervals_monthly_or_not('updraft_startday_db', db_interval);
		jQuery('#updraft-navtab-settings-content #updraft_startday_db').show();
	} else {
		jQuery('.updraft_monthly_extra_words_updraft_startday_db').remove();
		jQuery('#updraft-navtab-settings-content #updraft_startday_db').hide();
	}
	
	if (db_interval == file_interval) {
// jQuery('#updraft_db_timings').css('opacity','0.25');
		jQuery('#updraft-navtab-settings-content .updraft_db_timings').hide();
// jQuery('#updraft_same_schedules_message').show();
		if (0 == dbmanual) {
			jQuery('#updraft-navtab-settings-content .updraft_same_schedules_message').show();
		} else {
			jQuery('#updraft-navtab-settings-content .updraft_same_schedules_message').hide();
		}
	} else {
		jQuery('#updraft-navtab-settings-content .updraft_same_schedules_message').hide();
		if (0 == dbmanual) {
// jQuery('#updraft_db_timings').css('opacity', '1');
			jQuery('#updraft-navtab-settings-content .updraft_db_timings').show();
		}
	}
}

// Visit the site in the background every 3.5 minutes - ensures that backups can progress if you've got the UD settings page open
if ('undefined' !== typeof updraft_siteurl) {
	setInterval(function() {
jQuery.get(updraft_siteurl+'/wp-cron.php');}, 210000);
}
	
function updraft_activejobs_delete(jobid) {
	updraft_send_command('activejobs_delete', jobid, function(resp) {
		if (resp.ok == 'Y') {
			jQuery('#updraft-jobid-'+jobid).html(resp.m).fadeOut('slow').remove();
		} else if ('N' == resp.ok) {
			alert(resp.m);
		} else {
			alert(updraftlion.unexpectedresponse);
			console.log(resp);
		}
	});
}

function updraftplus_diskspace_entity(key) {
	jQuery('#updraft_diskspaceused_'+key).html('<em>'+updraftlion.calculating+'</em>');
	updraft_send_command('get_fragment', { fragment: 'disk_usage', data: key }, function(response) {
		jQuery('#updraft_diskspaceused_'+key).html(response.output);
	}, { type: 'GET' });
}

/**
 * Open a modal with content fetched from an iframe
 *
 * @param {String} getwhat - the subaction parameter to pass to UD's AJAX handler
 * @param {String} title   - the title for the modal
 */
function updraft_iframe_modal(getwhat, title) {
	var width = 780;
	var height = 500;
	jQuery('#updraft-iframe-modal-innards').html('<iframe width="100%" height="430px" src="'+ajaxurl+'?action=updraft_ajax&subaction='+getwhat+'&nonce='+updraft_credentialtest_nonce+'"></iframe>');
	jQuery('#updraft-iframe-modal').dialog('option', 'title', title).dialog('option', 'width', width).dialog('option', 'height', height).dialog('open');
}

function updraft_html_modal(showwhat, title, width, height) {
	jQuery('#updraft-iframe-modal-innards').html(showwhat);
	var updraft_html_modal_buttons = {};
	if (width < 450) {
		updraft_html_modal_buttons[updraftlion.close] = function() {
 jQuery(this).dialog("close"); };
	}
	jQuery('#updraft-iframe-modal').dialog('option', 'title', title).dialog('option', 'width', width).dialog('option', 'height', height).dialog('option', 'buttons', updraft_html_modal_buttons).dialog('open');
}

function updraftplus_diskspace() {
	jQuery('#updraft-navtab-backups-content .updraft_diskspaceused').html('<em>'+updraftlion.calculating+'</em>');
	updraft_send_command('get_fragment', { fragment: 'disk_usage', data: 'updraft' }, function(response) {
		jQuery('#updraft-navtab-backups-content .updraft_diskspaceused').html(response.output);
	}, { type: 'GET' });
}
var lastlog_lastmessage = "";
function updraftplus_deletefromserver(timestamp, type, findex) {
	if (!findex) findex=0;
	var pdata = {
		stage: 'delete',
		timestamp: timestamp,
		type: type,
		findex: findex
	};
	updraft_send_command('updraft_download_backup', pdata, null, { action: 'updraft_download_backup', nonce: updraft_download_nonce, nonce_key: '_wpnonce' });
}

function updraftplus_downloadstage2(timestamp, type, findex) {
	location.href =ajaxurl+'?_wpnonce='+updraft_download_nonce+'&timestamp='+timestamp+'&type='+type+'&stage=2&findex='+findex+'&action=updraft_download_backup';
}

function updraftplus_show_contents(timestamp, type, findex) {
	var modal_content = '<div id="updraft_zip_files_container" class="hidden-in-updraftcentral" style="clear:left;"><div id="updraft_zip_info_container"><p><span id="updraft_zip_path_text">' + updraftlion.zip_file_contents_info + '</span> - <span id="updraft_zip_size_text"></span></p>'+updraftlion.browse_download_link+'</div><div id="updraft_zip_files_jstree_container"><input type="search" id="zip_files_jstree_search" name="zip_files_jstree_search" placeholder="' + updraftlion.search + '"><div id="updraft_zip_files_jstree"></div></div></div>';

	updraft_html_modal(modal_content, updraftlion.zip_file_contents, 780, 500);

	zip_files_jstree('zipbrowser', timestamp, type, findex);
}

/**
 * Creates the jstree and makes a call to the backend to dynamically get the tree nodes
 *
 * @param {string} entity     Entity for the jstree
 * @param {integer} timestamp Timestamp of the jstree
 * @param {string} type       Type of file to display in the JS tree
 * @param {array} findex      Index of Zip
 */
function zip_files_jstree(entity, timestamp, type, findex) {

	jQuery('#updraft_zip_files_jstree').jstree({
		"core": {
			"multiple": false,
			"data": function (nodeid, callback) {
				updraft_send_command('get_jstree_directory_nodes', {entity:entity, node:nodeid, timestamp:timestamp, type:type, findex:findex}, function(response) {
					if (response.hasOwnProperty('error')) {
						alert(response.error);
					} else {
						callback.call(this, response.nodes);
					}
				});
			},
			"error": function(error) {
				alert(error);
				console.log(error);
			},
		},
		"search": {
			"show_only_matches": true
		},
		"plugins": ["search", "sort"],
	});

	// Update modal title once tree loads
	jQuery('#updraft_zip_files_jstree').on('ready.jstree', function(e, data) {
		jQuery('#updraft-iframe-modal').dialog('option', 'title', updraftlion.zip_file_contents + ': ' + data.instance.get_node('#').children[0])
	});

	// Search function for jstree, this will hide nodes that don't match the search
	var timeout = false;
	jQuery('#zip_files_jstree_search').keyup(function () {
		if (timeout) { clearTimeout(timeout); }
		timeout = setTimeout(function () {
			var value = jQuery('#zip_files_jstree_search').val();
			jQuery('#updraft_zip_files_jstree').jstree(true).search(value);
		}, 250);
	});

	// Detect change on the tree and update the input that has been marked as editing
	jQuery('#updraft_zip_files_jstree').on("changed.jstree", function (e, data) {
		jQuery('#updraft_zip_path_text').text(data.node.li_attr.path);
		
		if (data.node.li_attr.size) {
			jQuery('#updraft_zip_size_text').text(data.node.li_attr.size);
			jQuery('#updraft_zip_download_item').show();
		} else {
			jQuery('#updraft_zip_size_text').text('');
			jQuery('#updraft_zip_download_item').hide();
		}
	});

	jQuery('#updraft_zip_download_item').click(function(event) {
		
		event.preventDefault();
		
		var path = jQuery('#updraft_zip_path_text').text();

		updraft_send_command('get_zipfile_download', {path:path, timestamp:timestamp, type:type, findex:findex}, function(response) {
			if (response.hasOwnProperty('error')) {
				alert(response.error);
			} else if (response.hasOwnProperty('path')) {
				location.href =ajaxurl+'?_wpnonce='+updraft_download_nonce+'&timestamp='+timestamp+'&type='+type+'&stage=2&findex='+findex+'&filepath='+response.path+'&action=updraft_download_backup';
			} else {
				alert(updraftlion.download_timeout);
			}
		});
	});
}

function updraft_downloader(base, backup_timestamp, what, whicharea, set_contents, prettydate, async) {
	
	if (typeof set_contents !== "string") set_contents = set_contents.toString();

	var set_contents = set_contents.split(',');

	for (var i=0; i<set_contents.length; i++) {
		// Create somewhere for the status to be found
		var stid = base+backup_timestamp+'_'+what+'_'+set_contents[i];
		var stid_selector = '.'+stid;
		var show_index = parseInt(set_contents[i]); show_index++;
		var itext = (0 == set_contents[i]) ? '' : ' ('+show_index+')';
		if (!jQuery(stid_selector).length) {
			var prdate = (prettydate) ? prettydate : backup_timestamp;
			jQuery(whicharea).append('<div style="clear:left; border: 1px solid; padding: 8px; margin-top: 4px; max-width:840px;" class="'+stid+' updraftplus_downloader"><button onclick="jQuery(this).parent().fadeOut().remove();" type="button" style="float:right; margin-bottom: 8px;">X</button><strong>'+updraftlion.download+' '+what+itext+' ('+prdate+')</strong>:<div class="raw">'+updraftlion.begunlooking+'</div><div class="file '+stid+'_st"><div class="dlfileprogress" style="width: 0;"></div></div></div>');
			jQuery(stid_selector).data('downloaderfor', { base: base, nonce: backup_timestamp, what: what, index: set_contents[i] });
			setTimeout(function() {
updraft_activejobs_update(true);}, 1500);
		}
		jQuery(stid_selector).data('lasttimebegan', (new Date).getTime());
		
		// Now send the actual request to kick it all off
		async = async ? true : false;
		
		// Old-style, from when it was a form
		 // var data = jQuery('#updraft-navtab-backups-content .uddownloadform_'+what+'_'+backup_timestamp+'_'+set_contents[i]).serialize();
		
		var nonce = jQuery('#updraft-navtab-backups-content .uddownloadform_'+what+'_'+backup_timestamp+'_'+set_contents[i]).data('wp_nonce').toString()
		
		var data = {
			type: what,
			timestamp: backup_timestamp,
			findex: set_contents[i]
		};
		
		var options = {
			action: 'updraft_download_backup',
			nonce_key: '_wpnonce',
			nonce: nonce,
			timeout: 10000,
			async: async
		}
		
		updraft_send_command('updraft_download_backup', data, function(response) {}, options);
		
	}
	// We don't want the form to submit as that replaces the document
	return false;
}

/**
 * Parse JSON string, including automatically detecting unwanted extra input and skipping it
 *
 * @param {string} json_mix_str - JSON string which need to parse and convert to object
 *
 * @throws SyntaxError|String (including passing on what JSON.parse may throw) if a parsing error occurs.
 *
 * @returns Mixed parsed JSON object. Will only return if parsing is successful (otherwise, will throw)
 */
function ud_parse_json(json_mix_str) {
	// Here taking first and last char in variable, because these are used more than once in this function
	var first_char = json_mix_str.charAt(0);
	var last_char = json_mix_str.charAt(json_mix_str.length - 1);
	
	// Just try it - i.e. the 'default' case where things work (which can include extra whitespace/line-feeds, and simple strings, etc.).
	try {
		var result = JSON.parse(json_mix_str);
		return result;
	} catch (e) {
		console.log("UpdraftPlus: Exception when trying to parse JSON (1) - will attempt to fix/re-parse");
		console.log(json_mix_str);
	}

	var json_start_pos = json_mix_str.indexOf('{');
	var json_last_pos = json_mix_str.lastIndexOf('}');
	
	// Case where some php notice may be added after or before json string
	if (json_start_pos > -1 && json_last_pos > -1) {
		var json_str = json_mix_str.slice(json_start_pos, json_last_pos + 1);
		try {
			var parsed = JSON.parse(json_str);
			console.log("UpdraftPlus: JSON re-parse successful");
			return parsed;
		} catch (e) {
			 console.log("UpdraftPlus: Exception when trying to parse JSON (2)");
			 // Throw it again, so that our function works just like JSON.parse() in its behaviour.
			 throw e;
		}
	}

	throw "UpdraftPlus: could not parse the JSON";
	
}

// Catch HTTP errors if the download status check returns them
jQuery(document).ajaxError(function(event, jqxhr, settings, exception) {
	if (exception == null || exception == '') return;
	if (jqxhr.responseText == null || jqxhr.responseText == '') return;
	console.log("Error caught by UpdraftPlus ajaxError handler (follows) for "+settings.url);
	console.log(exception);
	if (settings.url.search(ajaxurl) == 0) {
		// TODO subaction=downloadstatus is no longer used. This should be adjusted to the current set-up.
		if (settings.url.search('subaction=downloadstatus') >= 0) {
			var timestamp = settings.url.match(/timestamp=\d+/);
			var type = settings.url.match(/type=[a-z]+/);
			var findex = settings.url.match(/findex=\d+/);
			var base = settings.url.match(/base=[a-z_]+/);
			findex = (findex instanceof Array) ? parseInt(findex[0].substr(7)) : 0;
			type = (type instanceof Array) ? type[0].substr(5) : '';
			base = (base instanceof Array) ? base[0].substr(5) : '';
			timestamp = (timestamp instanceof Array) ? parseInt(timestamp[0].substr(10)) : 0;
			if ('' != base && '' != type && timestamp >0) {
				var stid = base+timestamp+'_'+type+'_'+findex;
				jQuery('.'+stid+' .raw').html('<strong>'+updraftlion.error+'</strong> '+updraftlion.servererrorcode);
			}
		} else if (settings.url.search('subaction=restore_alldownloaded') >= 0) {
			// var timestamp = settings.url.match(/timestamp=\d+/);
			jQuery('#updraft-restore-modal-stage2a').append('<br><strong>'+updraftlion.error+'</strong> '+updraftlion.servererrorcode+': '+exception);
		}
	}
});

function updraft_restorer_checkstage2(doalert) {
	// How many left?
	var stilldownloading = jQuery('#ud_downloadstatus2 .file').length;
	if (stilldownloading > 0) {
		if (doalert) { alert(updraftlion.stilldownloading); }
		return;
	}
	// Allow pressing 'Restore' to proceed
	jQuery('#updraft-restore-modal-stage2a').html(updraftlion.processing);
	updraft_send_command('restore_alldownloaded', {
		timestamp: jQuery('#updraft_restore_timestamp').val(),
		restoreopts: jQuery('#updraft_restore_form').serialize()
	}, function(data) {
		var info = null;
		jQuery('#updraft_restorer_restore_options').val('');
		try {
			var resp = ud_parse_json(data);
			if (null == resp) {
				jQuery('#updraft-restore-modal-stage2a').html(updraftlion.emptyresponse);
				return;
			}
			var report = resp.m;
			if (resp.w != '') {
				report = report + "<p><strong>" + updraftlion.warnings +'</strong><br>' + resp.w + "</p>";
			}
			if (resp.e != '') {
				report = report + "<p><strong>" + updraftlion.errors+'</strong><br>' + resp.e + "</p>";
			} else {
				updraft_restore_stage = 3;
			}
			if (resp.hasOwnProperty('i')) {
				// Store the information passed back from the backup scan
				try {
					info = ud_parse_json(resp.i);
// if (info.hasOwnProperty('multisite') && info.multisite && info.hasOwnProperty('same_url') && info.same_url) {
					if (info.hasOwnProperty('addui')) {
						console.log("Further UI options are being displayed");
						var addui = info.addui;
						report += '<div id="updraft_restoreoptions_ui" style="clear:left; padding-top:10px;">'+addui+'</div>';
						if (typeof JSON == 'object' && typeof JSON.stringify == 'function') {
							// If possible, remove from the stored info, to prevent passing back potentially large amounts of unwanted data
							delete info.addui;
							resp.i = JSON.stringify(info);
						}
					}
				} catch (err) {
					console.log(err);
					console.log(resp);
				}
				jQuery('#updraft_restorer_backup_info').val(resp.i);
			} else {
				jQuery('#updraft_restorer_backup_info').val();
			}
			jQuery('#updraft-restore-modal-stage2a').html(report);
			if (jQuery('#updraft-restore-modal-stage2a .updraft_select2').length > 0) {
				jQuery('#updraft-restore-modal-stage2a .updraft_select2').select2();
			}
		} catch (err) {
			console.log(data);
			console.log(err);
			jQuery('#updraft-restore-modal-stage2a').text(updraftlion.jsonnotunderstood+' '+updraftlion.errordata+": "+data).html();
		}
	}, { json_parse: false });
}


function updraft_downloader_status(base, nonce, what, findex) {
	// Short-circuit. See previous versions for the old code.
	return;
}

function updraft_downloader_status_update(base, backup_timestamp, what, findex, resp, response) {
	var stid = base+backup_timestamp+'_'+what+'_'+findex;
	var stid_selector = '.'+stid;
	var cancel_repeat = 0;
	if (resp.e != null) {
		jQuery(stid_selector+' .raw').html('<strong>'+updraftlion.error+'</strong> '+resp.e);
		console.log(resp);
	} else if (resp.p != null) {
		jQuery(stid_selector+'_st .dlfileprogress').width(resp.p+'%');
		// jQuery(stid_selector+'_st .dlsofar').html(Math.round(resp.s/1024));
		// jQuery(stid_selector+'_st .dlsize').html(Math.round(resp.t/1024));
		
		// Is a restart appropriate?
		// resp.a, if set, indicates that a) the download is incomplete and b) the value is the number of seconds since the file was last modified...
		if (resp.a != null && resp.a > 0) {
			var timenow = (new Date).getTime();
			var lasttimebegan = jQuery(stid_selector).data('lasttimebegan');
			// Remember that this is in milliseconds
			var sincelastrestart = timenow - lasttimebegan;
			if (resp.a > 90 && sincelastrestart > 60000) {
				console.log(backup_timestamp+" "+what+" "+findex+": restarting download: file_age="+resp.a+", sincelastrestart_ms="+sincelastrestart);
				jQuery(stid_selector).data('lasttimebegan', (new Date).getTime());
				
				var $original_button = jQuery('#updraft-navtab-backups-content .uddownloadform_'+what+'_'+backup_timestamp+'_'+findex);
				
				var data = {
					type: what,
					timestamp: backup_timestamp,
					findex: findex
				};
				
				var options = {
					action: 'updraft_download_backup',
					nonce_key: '_wpnonce',
					nonce: $original_button.data('wp_nonce').toString(),
					timeout: 10000
				};
				
				updraft_send_command('updraft_download_backup', data, function(response) {}, options);
				
				jQuery(stid_selector).data('lasttimebegan', (new Date).getTime());
			}
		}

		if (resp.m != null) {
			if (resp.p >= 100 && 'udrestoredlstatus_' == base) {
				jQuery(stid_selector+' .raw').html(resp.m);
				jQuery(stid_selector).fadeOut('slow', function() {
 jQuery(this).remove(); updraft_restorer_checkstage2(0);});
			} else if (resp.p < 100 || base != 'uddlstatus_') {
				jQuery(stid_selector+' .raw').html(resp.m);
			} else {
				var file_ready_actions = updraftlion.fileready+' '+ updraftlion.actions+': \
				<button type="button" onclick="updraftplus_downloadstage2(\''+backup_timestamp+'\', \''+what+'\', \''+findex+'\')\">'+updraftlion.downloadtocomputer+'</button> \
				<button id="uddownloaddelete_'+backup_timestamp+'_'+what+'" type="button" onclick="updraftplus_deletefromserver(\''+backup_timestamp+'\', \''+what+'\', \''+findex+'\')\">'+updraftlion.deletefromserver+'</button>';

				if (resp.hasOwnProperty('can_show_contents') && resp.can_show_contents) {
					file_ready_actions += ' <button type="button" onclick="updraftplus_show_contents(\''+backup_timestamp+'\', \''+what+'\', \''+findex+'\')\">'+updraftlion.browse_contents+'</button>';
				}
				jQuery(stid_selector+' .raw').html(file_ready_actions);
			}
		}
// dlstatus_lastlog = response;
	} else if (resp.m != null) {
			jQuery(stid_selector+' .raw').html(resp.m);
	} else {
		jQuery(stid_selector+' .raw').html(updraftlion.jsonnotunderstood+' ('+response+')');
		cancel_repeat = 1;
	}
	return cancel_repeat;
}

/**
 * Function that sets up a ajax call to start a backup
 *
 * @param {Integer} backupnow_nodb         Indicate whether the database should be backed up: valid values are 0, 1
 * @param {Integer} backupnow_nofiles      Indicate whether any files should be backed up: valid values are 0, 1
 * @param {Integer} backupnow_nocloud      Indicate whether the backup should be uploaded to cloud storage: valid values are 0, 1
 * @param {String}  onlythesefileentities  A csv list of file entities to be backed up
 * @param {String}  onlythesetableentities A csv list of table entities to be backed up
 * @param {Array}   extradata              any extra data to be added
 * @param {String}  label                  A optional label to be added to a backup
 */
function updraft_backupnow_go(backupnow_nodb, backupnow_nofiles, backupnow_nocloud, onlythesefileentities, extradata, label, onlythesetableentities) {

	jQuery('#updraft_backup_started').html('<em>'+updraftlion.requeststart+'</em>').slideDown('');
	setTimeout(function() {
jQuery('#updraft_backup_started').fadeOut('slow');}, 75000);

	var params = {
		backupnow_nodb: backupnow_nodb,
		backupnow_nofiles: backupnow_nofiles,
		backupnow_nocloud: backupnow_nocloud,
		backupnow_label: label,
		extradata: extradata
	};
	
	if ('' != onlythesefileentities) {
		params.onlythisfileentity = onlythesefileentities;
	}

	if ('' != onlythesetableentities) {
		params.onlythesetableentities = onlythesetableentities;
	}
	
	updraft_send_command('backupnow', params, function(resp) {
		jQuery('#updraft_backup_started').html(resp.m);
		if (resp.hasOwnProperty('nonce')) {
			// Can't return it from this context
			updraft_backupnow_nonce = resp.nonce;
			console.log("UpdraftPlus: ID of started job: "+updraft_backupnow_nonce);
		}
		setTimeout(function() {
			updraft_activejobs_update(true);}, 500);
	});
}

jQuery(document).ready(function($) {
	// Advanced settings new menu button listeners
	$('.expertmode .advanced_settings_container .advanced_tools_button').click(function() {
		advanced_tool_hide($(this).attr("id"));
	});
	
	function advanced_tool_hide(show_tool) {
		
		$('.expertmode .advanced_settings_container .advanced_tools:not(".'+show_tool+'")').hide();
		$('.expertmode .advanced_settings_container .advanced_tools.'+show_tool).fadeIn('slow');
		
		$('.expertmode .advanced_settings_container .advanced_tools_button:not(#'+show_tool+')').removeClass('active');
		$('.expertmode .advanced_settings_container .advanced_tools_button#'+show_tool).addClass('active');
		
	}
	// https://github.com/select2/select2/issues/1246#issuecomment-71710835
	if (jQuery.ui && jQuery.ui.dialog && jQuery.ui.dialog.prototype._allowInteraction) {
		var ui_dialog_interaction = jQuery.ui.dialog.prototype._allowInteraction;
		jQuery.ui.dialog.prototype._allowInteraction = function(e) {
			if (jQuery(e.target).closest('.select2-dropdown').length) return true;
					   return ui_dialog_interaction.apply(this, arguments);
		};
	}

	$('#updraftcentral_keys').on('click', 'a.updraftcentral_keys_show', function(e) {
		e.preventDefault();
		$(this).remove();
		$('#updraftcentral_keys_table').slideDown();
	});
	
	$('#updraftcentral_keycreate_altmethod_moreinfo_get').click(function(e) {
		e.preventDefault();
		$(this).remove();
		$('#updraftcentral_keycreate_altmethod_moreinfo').slideDown();
	});
	
	// Update WebDAV URL as user edits
	$('#updraft-navtab-settings-content #remote-storage-holder').on('change keyup paste', '.updraft_webdav_settings', function() {
		var updraft_webdav_settings = [];
		$('.updraft_webdav_settings').each(function(index, item) {
			
			var id = $(item).attr('id');
			
			if (id && 'updraft_webdav_' == id.substring(0, 15)) {
				var which_one = id.substring(15);
				id_split = which_one.split('_');
				which_one = id_split[0];
				var instance_id = id_split[1];
				if ('undefined' == typeof updraft_webdav_settings[instance_id]) updraft_webdav_settings[instance_id] = [];
				updraft_webdav_settings[instance_id][which_one] = this.value;
			}
		});

		var updraft_webdav_url = "";
		var host = "@";
		var slash = "/";
		var colon = ":";
		var colon_port = ":";

		for (var instance_id in updraft_webdav_settings) {
			
			if (updraft_webdav_settings[instance_id]['host'].indexOf("@") >= 0 || "" === updraft_webdav_settings[instance_id]['host']) {
				host = "";
			}
			if (updraft_webdav_settings[instance_id]['host'].indexOf("/") >= 0) {
				$('#updraft_webdav_host_error').show();
			} else {
				$('#updraft_webdav_host_error').hide();
			}
			
			if (0 == updraft_webdav_settings[instance_id]['path'].indexOf("/") || "" === updraft_webdav_settings[instance_id]['path']) {
				slash = "";
			}
			
			if ("" === updraft_webdav_settings[instance_id]['user'] || "" === updraft_webdav_settings[instance_id]['pass']) {
				colon = "";
			}
			
			if ("" === updraft_webdav_settings[instance_id]['host'] || "" === updraft_webdav_settings[instance_id]['port']) {
				colon_port = "";
			}
			
			updraft_webdav_url = updraft_webdav_settings[instance_id]['webdav'] + updraft_webdav_settings[instance_id]['user'] + colon + updraft_webdav_settings[instance_id]['pass'] + host +encodeURIComponent(updraft_webdav_settings[instance_id]['host']) + colon_port + updraft_webdav_settings[instance_id]['port'] + slash + updraft_webdav_settings[instance_id]['path'];
			
			$('#updraft_webdav_url_' + instance_id).val(updraft_webdav_url);
		}
	});
	
	$('#updraft-navtab-backups-content').on('click', '.updraft_existing_backups .updraft_existing_backups_row', function(e) {
		if (!e.ctrlKey && !e.metaKey) return;
		$(this).toggleClass('backuprowselected');
		if ($('#updraft-navtab-backups-content .updraft_existing_backups .updraft_existing_backups_row.backuprowselected').length >0) {
			$('#ud_massactions').show();
		} else {
			$('#ud_massactions').hide();
		}
	});

	$('#updraft-navtab-settings-content #remote-storage-holder').on('click', '.updraftplusmethod a.updraft_add_instance', function(e) {
		e.preventDefault();

		updraft_settings_form_changed = true;
		
		var method = $(this).data('method');
		add_new_instance(method);
	});

	$('#updraft-navtab-settings-content #remote-storage-holder').on('click', '.updraftplusmethod a.updraft_delete_instance', function(e) {
		e.preventDefault();

		updraft_settings_form_changed = true;

		var method = $(this).data('method');
		var instance_id = $(this).data('instance_id');

		if (1 === $('.' + method + '_updraft_remote_storage_border').length) {
			add_new_instance(method);
		}

		$('.' + method + '-' + instance_id).hide('slow', function() {
			$(this).remove();
		});
	});

	$('#updraft-navtab-settings-content #remote-storage-holder').on('click', '.updraftplusmethod .updraft_edit_label_instance', function(e) {
		$(this).find('span').hide();
		$(this).attr('contentEditable', true).focus();
	});
	
	$('#updraft-navtab-settings-content #remote-storage-holder').on('keyup', '.updraftplusmethod .updraft_edit_label_instance', function(e) {
		var method = jQuery(this).data('method');
		var instance_id = jQuery(this).data('instance_id');
		var content = jQuery(this).text();

		$('#updraft_' + method + '_instance_label_' + instance_id).val(content);
	});

	$('#updraft-navtab-settings-content #remote-storage-holder').on('blur', '.updraftplusmethod .updraft_edit_label_instance', function(e) {
		$(this).attr('contentEditable', false);
		$(this).find('span').show();
	});

	$('#updraft-navtab-settings-content #remote-storage-holder').on('keypress', '.updraftplusmethod .updraft_edit_label_instance', function(e) {
		if (13 === e.which) {
			$(this).attr('contentEditable', false);
			$(this).find('span').show();
			$(this).blur();
		}
	});

	/**
	 * This method will get the default options and compile a template with them
	 *
	 * @param {string} method - the remote storage name
	 * @param {boolean} first_instance - indicates if this is the first instance of this type
	 */
	function add_new_instance(method) {
		var template = Handlebars.compile(updraftlion.remote_storage_templates[method]);
		var context = updraftlion.remote_storage_options[method]['default'];
		context['instance_id'] = 's-' + generate_instance_id(32);
		context['instance_enabled'] = 1;
		var html = template(context);
		jQuery(html).hide().insertAfter('.' + method + '_add_instance_container:first').show('slow');
	}

	/**
	 * This method will return a random instance id string
	 *
	 * @param {integer} length - the length of the string to be generated
	 *
	 * @return string         - the instance id
	 */
	function generate_instance_id(length) {
		var uuid = '';
		var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		for (var i = 0; i < length; i++) {
			uuid += characters.charAt(Math.floor(Math.random() * characters.length));
		}

		return uuid;
	}

	jQuery('#updraft-navtab-settings-content #remote-storage-holder').on("change", "input[class='updraft_instance_toggle']", function () {
		updraft_settings_form_changed = true;
		if (jQuery(this).is(':checked')) {
			jQuery(this).siblings('label').html(updraftlion.instance_enabled);
		} else {
			jQuery(this).siblings('label').html(updraftlion.instance_disabled);
		}
	});
	
	jQuery('#updraft-navtab-settings-content #remote-storage-holder').on('click', '.updraftplusmethod button.updraft-test-button', function() {

		var method = jQuery(this).data('method');
		var instance_id = jQuery(this).data('instance_id');
		updraft_remote_storage_test(method, function(response, status, data) {
			if ('sftp' != method) { return false; }
			
			if (data.hasOwnProperty('scp') && data.scp) {
				alert(updraftlion.settings_test_result.replace('%s', 'SCP')+' '+response.output);
			} else {
				alert(updraftlion.settings_test_result.replace('%s', 'SFTP')+' '+response.output);
			}
			
			if (response.hasOwnProperty('data')) {
				console.log(response.data);
			}
			
			return true;
			
		}, instance_id);
	});
	
	$('#updraft-navtab-settings-content select.updraft_interval, #updraft-navtab-settings-content select.updraft_interval_database').change(function() {
		updraft_check_same_times();
	});
	
	$('#backupnow_includefiles_showmoreoptions').click(function(e) {
		e.preventDefault();
		$('#backupnow_includefiles_moreoptions').toggle();
	});

	$('#backupnow_database_showmoreoptions').click(function(e) {
		e.preventDefault();
		$('#backupnow_database_moreoptions').toggle();
	});
	
	$('#updraft-navtab-backups-content a.updraft_diskspaceused_update').click(function(e) {
		e.preventDefault();
		updraftplus_diskspace();
	});
	
	$('#updraft-navtab-backups-content a.updraft_uploader_toggle').click(function(e) {
		e.preventDefault();
		$('#updraft-plupload-modal').slideToggle();
	});
	
	$('#updraft-navtab-backups-content a.updraft_rescan_local').click(function(e) {
		e.preventDefault();
		updraft_updatehistory(1, 0);
	});
	
	$('#updraft-navtab-backups-content a.updraft_rescan_remote').click(function(e) {
		e.preventDefault();
		updraft_updatehistory(1, 1);
	});

	function updraftcentral_keys_setupform(on_page_load) {
		var is_other = jQuery('#updraftcentral_mothership_other').is(':checked') ? true : false;
		if (is_other) {
			jQuery('#updraftcentral_keycreate_mothership').prop('disabled', false);
			if (on_page_load) {
				jQuery('#updraftcentral_keycreate_mothership_firewalled_container').show();
			} else {
				jQuery('.updraftcentral_wizard_self_hosted_stage2').show();
				jQuery('#updraftcentral_keycreate_mothership_firewalled_container').slideDown();
				jQuery('#updraftcentral_keycreate_mothership').focus();
			}
		} else {
			jQuery('#updraftcentral_keycreate_mothership').prop('disabled', true);
			if (!on_page_load) {
				jQuery('.updraftcentral_wizard_self_hosted_stage2').hide();
				updraftcentral_stage2_go();
			}
		}
	}
	
	function updraftcentral_stage2_go() {
		// Reset the error message before we continue
		jQuery('#updraftcentral_wizard_stage1_error').text('');

		var host = '';

		if (jQuery('#updraftcentral_mothership_updraftpluscom').is(':checked')) {
			jQuery('.updraftcentral_keycreate_description').hide();
			host = 'updraftplus.com';
		} else if (jQuery('#updraftcentral_mothership_other').is(':checked')) {
			jQuery('.updraftcentral_keycreate_description').show();
			var mothership = jQuery('#updraftcentral_keycreate_mothership').val();
			if ('' == mothership) {
				jQuery('#updraftcentral_wizard_stage1_error').text(updraftlion.updraftcentral_wizard_empty_url);
				return;
			}
			try {
				var url = new URL(mothership);
				host = url.hostname;
			} catch (e) {
				// Try and grab the host name a different way if it failed because of no URL object (e.g. IE 11).
				if ('undefined' === typeof URL) {
					host = jQuery('<a>').prop('href', mothership).prop('hostname');
				}
				if (!host || 'undefined' !== typeof URL) {
					jQuery('#updraftcentral_wizard_stage1_error').text(updraftlion.updraftcentral_wizard_invalid_url);
					return;
				}
			}
		}

		jQuery('#updraftcentral_keycreate_description').val(host);

		jQuery('.updraftcentral_wizard_stage1').hide();
		jQuery('.updraftcentral_wizard_stage2').show();
	}

	jQuery('#updraftcentral_keys').on('click', 'input[type="radio"]', function() {
		updraftcentral_keys_setupform(false);
	});
	// Initial setup (for browsers, e.g. Firefox, that remember form selection state but not DOM state, which can leave an inconsistent state)
	updraftcentral_keys_setupform(true);
	
	jQuery('#updraftcentral_keys').on('click', '#updraftcentral_view_log', function(e) {
		e.preventDefault();
		jQuery('#updraftcentral_view_log_container').block({ message: '<div style="margin: 8px; font-size:150%;"><img src="'+updraftlion.ud_url+'/images/udlogo-rotating.gif" height="80" width="80" style="padding-bottom:10px;"><br>'+updraftlion.fetching+'</div>'});
		try {
			updraft_send_command('updraftcentral_get_log', null, function(response) {
				jQuery('#updraftcentral_view_log_container').unblock();
				if (response.hasOwnProperty('log_contents')) {
					jQuery('#updraftcentral_view_log_contents').html('<div style="border:1px solid;padding: 2px;max-height: 400px; overflow-y:scroll;">'+response.log_contents+'</div>');
				} else {
					console.response(resp);
				}
			});
		} catch (err) {
			jQuery('#updraft_central_key').html();
			console.log(err);
		}
	});
	
	// UpdraftCentral
	jQuery('#updraftcentral_keys').on('click', '#updraftcentral_wizard_go', function(e) {
		jQuery('#updraftcentral_wizard_go').hide();
		jQuery('.updraftcentral_wizard_success').remove();
		jQuery('.create_key_container').show();
	});
	
	jQuery('#updraftcentral_keys').on('click', '#updraftcentral_stage1_go', function(e) {
		e.preventDefault();
		jQuery('.updraftcentral_wizard_stage2').hide();
		jQuery('.updraftcentral_wizard_stage1').show();
	});

	jQuery('#updraftcentral_keys').on('click', '#updraftcentral_stage2_go', function(e) {
		e.preventDefault();

		updraftcentral_stage2_go();
	});
	
	jQuery('#updraftcentral_keys').on('click', '#updraftcentral_keycreate_go', function(e) {
		e.preventDefault();
		
		var is_other = jQuery('#updraftcentral_mothership_other').is(':checked') ? true : false;
		
		var key_description = jQuery('#updraftcentral_keycreate_description').val();
		var key_size = jQuery('#updraftcentral_keycreate_keysize').val();

		var where_send = '__updraftpluscom';
		
		data = {
			key_description: key_description,
			key_size: key_size,
		};
		
		if (is_other) {
			where_send = jQuery('#updraftcentral_keycreate_mothership').val();
			if (where_send.substring(0, 4) != 'http') {
				alert(updraftlion.enter_mothership_url);
				return;
			}
		}
		
		data.mothership_firewalled = jQuery('#updraftcentral_keycreate_mothership_firewalled').is(':checked') ? 1 : 0;
		data.where_send = where_send;

		jQuery('.create_key_container').hide();
		jQuery('.updraftcentral_wizard_stage1').show();
		jQuery('.updraftcentral_wizard_stage2').hide();
		
		jQuery('#updraftcentral_keys').block({ message: '<div style="margin: 8px; font-size:150%;"><img src="'+updraftlion.ud_url+'/images/udlogo-rotating.gif" height="80" width="80" style="padding-bottom:10px;"><br>'+updraftlion.creating_please_allow+'</div>'});

		try {
			updraft_send_command('updraftcentral_create_key', data, function(response) {
				jQuery('#updraftcentral_keys').unblock();
				try {
					resp = ud_parse_json(response);
					if (resp.hasOwnProperty('error')) {
						alert(resp.error);
						console.log(resp);
						return;
					}
					alert(resp.r);

					if (resp.hasOwnProperty('bundle') && resp.hasOwnProperty('keys_guide')) {
						jQuery('#updraftcentral_keys_content').html(resp.keys_guide);
						jQuery('#updraftcentral_keys_content').append('<div class="updraftcentral_wizard_success">'+resp.r+'<br><textarea onclick="this.select();" style="width:620px; height:165px; word-wrap:break-word; border: 1px solid #aaa; border-radius: 3px; padding:4px;">'+resp.bundle+'</textarea></div>');
					} else {
						console.log(resp);
					}

					if (resp.hasOwnProperty('keys_table')) {
						jQuery('#updraftcentral_keys_content').append(resp.keys_table);
					}
					
					jQuery('#updraftcentral_wizard_go').show();

				} catch (err) {
					alert(updraftlion.unexpectedresponse+' '+response);
					console.log(err);
				}
			}, { json_parse: false });
		} catch (err) {
			jQuery('#updraft_central_key').html();
			console.log(err);
		}
	});
	
	jQuery('#updraftcentral_keys').on('click', '.updraftcentral_key_delete', function(e) {
		e.preventDefault();
		var key_id = jQuery(this).data('key_id');
		if ('undefined' == typeof key_id) {
			console.log("UpdraftPlus: .updraftcentral_key_delete clicked, but no key ID found");
			return;
		}

		jQuery('#updraftcentral_keys').block({ message: '<div style="margin: 8px; font-size:150%;"><img src="'+updraftlion.ud_url+'/images/udlogo-rotating.gif" height="80" width="80" style="padding-bottom:10px;"><br>'+updraftlion.deleting+'</div>'});
		
		updraft_send_command('updraftcentral_delete_key', { key_id: key_id }, function(response) {
			jQuery('#updraftcentral_keys').unblock();
			if (response.hasOwnProperty('keys_table')) {
				jQuery('#updraftcentral_keys_content').html(response.keys_table);
			}
		});
	});
	
	jQuery('#updraft_reset_sid').click(function(e) {
		e.preventDefault();
		updraft_send_command('reset_site_id', null, function(response) {
			jQuery('#updraft_show_sid').html(response);
		}, { json_parse: false });
	});
	
	jQuery("#updraft-navtab-settings-content form input:not('.udignorechange'), #updraft-navtab-settings-content form select").change(function(e) {
		updraft_settings_form_changed = true;
	});
	jQuery("#updraft-navtab-settings-content form input[type='submit']").click(function (e) {
		updraft_settings_form_changed = false;
	});
	
	var bigbutton_width = 180;
	jQuery('.updraft-bigbutton').each(function(x,y) {
		var bwid = jQuery(y).width();
		if (bwid > bigbutton_width) bigbutton_width = bwid;
	});
	if (bigbutton_width > 180) jQuery('.updraft-bigbutton').width(bigbutton_width);

	// setTimeout(function(){updraft_showlastlog(true);}, 1200);
	setInterval(function() {
updraft_activejobs_update(false);}, 1250);

	// Prevent profusion of notices
	setTimeout(function() {
jQuery('#setting-error-settings_updated').slideUp();}, 5000);
	
	jQuery('#updraft_restore_db').change(function() {
		if (jQuery('#updraft_restore_db').is(':checked')) {
			jQuery('#updraft_restorer_dboptions').slideDown();
		} else {
			jQuery('#updraft_restorer_dboptions').slideUp();
		}
	});

	updraft_check_same_times();

	var updraft_message_modal_buttons = {};
	updraft_message_modal_buttons[updraftlion.close] = function() {
 jQuery(this).dialog("close"); };
	jQuery("#updraft-message-modal").dialog({
		autoOpen: false, height: 350, width: 520, modal: true,
		buttons: updraft_message_modal_buttons
	});
	
	var updraft_delete_modal_buttons = {};
	updraft_delete_modal_buttons[updraftlion.deletebutton] = function() {
 updraft_remove_backup_sets(0, 0, 0, 0); };


	function updraft_remove_backup_sets(deleted_counter, backup_local, backup_remote, backup_sets) {
		jQuery('#updraft-delete-waitwarning').slideDown();
		var deleted_files_counter = deleted_counter;
		var local_deleted = backup_local;
		var remote_deleted = backup_remote;
		var sets_deleted = backup_sets;
		var timestamps = jQuery('#updraft_delete_timestamp').val().split(',');
		
		var form_data = jQuery('#updraft_delete_form').serializeArray();
		var data = {};
		
		$.each(form_data, function() {
			if (undefined !== data[this.name]) {
				if (!data[this.name].push) {
					data[this.name] = [data[this.name]];
				}
				data[this.name].push(this.value || '');
			} else {
				data[this.name] = this.value || '';
			}
		});
		
		data.remote_delete_limit = updraftlion.remote_delete_limit;
		
		delete data.action;
		delete data.subaction;
		delete data.nonce;
		
		updraft_send_command('deleteset', data, function(resp) {
			if (!resp.hasOwnProperty('result') || resp.result == null) { return; }
			if (resp.result == 'error') {
				alert(updraftlion.error+' '+resp.message);
			} else if (resp.result == 'continue') {
				deleted_files_counter = deleted_files_counter + resp.backup_local + resp.backup_remote;
				local_deleted = local_deleted + resp.backup_local;
				remote_deleted = remote_deleted + resp.backup_remote;
				sets_deleted = sets_deleted + resp.backup_sets;
				jQuery('#updraft-deleted-files-total').text(deleted_files_counter + ' ' + updraftlion.remote_files_deleted);
				updraft_remove_backup_sets(deleted_files_counter, local_deleted, remote_deleted, sets_deleted);
			} else if (resp.result == 'success') {
				jQuery('#updraft-deleted-files-total').text('');
				jQuery('#updraft-delete-waitwarning').slideUp();
				if (resp.hasOwnProperty('count_backups')) {
					jQuery('#updraft-navtab-backups').html(updraftlion.existing_backups+' ('+resp.count_backups+')');
				}
				for (var i = 0; i < timestamps.length; i++) {
					var timestamp = timestamps[i];
					jQuery('#updraft-navtab-backups-content .updraft_existing_backups_row_'+timestamp).slideUp().remove();
				}
				if (jQuery('#updraft-navtab-backups-content .updraft_existing_backups .updraft_existing_backups_row.backuprowselected').length < 1) {
					jQuery('#ud_massactions').hide();
				}
				updraft_history_lastchecksum = false;
				jQuery("#updraft-delete-modal").dialog('close');

				local_deleted = local_deleted + resp.backup_local;
				remote_deleted = remote_deleted + resp.backup_remote;
				sets_deleted = sets_deleted + resp.backup_sets;

				alert(resp.set_message + " " + sets_deleted + "\n" + resp.local_message + " " + local_deleted + "\n" + resp.remote_message + " " + remote_deleted);
			}
		});
	};

	updraft_delete_modal_buttons[updraftlion.cancel] = function() {
 jQuery(this).dialog("close"); };
	jQuery("#updraft-delete-modal").dialog({
		autoOpen: false, height: 322, width: 430, modal: true,
		buttons: updraft_delete_modal_buttons
	});

	var updraft_restore_modal_buttons = {};
	updraft_restore_modal_buttons[updraftlion.restore] = function() {
		var anyselected = 0;
		var whichselected = [];
		// Make a list of what files we want
		var already_added_wpcore = 0;
		var meta_foreign = jQuery('#updraft_restore_meta_foreign').val();
		jQuery('input[name="updraft_restore[]"]').each(function(x, y) {
			if (jQuery(y).is(':checked') && !jQuery(y).is(':disabled')) {
				anyselected = 1;
				var howmany = jQuery(y).data('howmany');
				var type = jQuery(y).val();
				if (1 == meta_foreign || (2 == meta_foreign && 'db' != type)) {
					if ('wpcore' != type) {
						howmany = jQuery('#updraft_restore_form #updraft_restore_wpcore').data('howmany');
					}
					type = 'wpcore';
				}
				if ('wpcore' != type || already_added_wpcore == 0) {
					var restobj = [ type, howmany ];
					whichselected.push(restobj);
					// alert(jQuery(y).val());
					if ('wpcore' == type) { already_added_wpcore = 1; }
				}
			}
		});
		if (1 == anyselected) {
			// Work out what to download
			if (1 == updraft_restore_stage) {
				// meta_foreign == 1 : All-in-one format: the only thing to download, always, is wpcore
// if ('1' == meta_foreign) {
// whichselected = [];
// whichselected.push([ 'wpcore', 0 ]);
// } else if ('2' == meta_foreign) {
// jQuery(whichselected).each(function(x,y) {
// restobj = whichselected[x];
// });
// whichselected = [];
// whichselected.push([ 'wpcore', 0 ]);
// }
				jQuery('#updraft-restore-modal-stage1').slideUp('slow');
				jQuery('#updraft-restore-modal-stage2').show();
				updraft_restore_stage = 2;
				var pretty_date = jQuery('.updraft_restore_date').first().text();
				// Create the downloader active widgets

				// See if we some are already known to be downloaded - in which case, skip creating the download widget. (That saves on HTTP round-trips, as each widget creates a new POST request. Of course, this is at the expense of one extra one here).
				var which_to_download = whichselected;
				var backup_timestamp = jQuery('#updraft_restore_timestamp').val();

				try {
					updraft_send_command('whichdownloadsneeded', {
						downloads: whichselected,
						timestamp: backup_timestamp
					}, function(response) {
						
						if (response.hasOwnProperty('downloads')) {
							console.log('UpdraftPlus: items which still require downloading follow');
							which_to_download = response.downloads;
							console.log(which_to_download);
						}

						// Kick off any downloads, if needed
						if (which_to_download.length == 0) {
							updraft_restorer_checkstage2(0);
						} else {
							for (var i=0; i<which_to_download.length; i++) {
								updraft_downloader('udrestoredlstatus_', backup_timestamp, which_to_download[i][0], '#ud_downloadstatus2', which_to_download[i][1], pretty_date, false);
							}
						}
						
					}, { alert_on_error: false });
				} catch (err) {
					console.log("UpdraftPlus: error (follows) when looking for items needing downloading");
					console.log(err);
					alert(updraftlion.jsonnotunderstood);
				}

				// Make sure all are downloaded
			} else if (2 == updraft_restore_stage) {
				updraft_restorer_checkstage2(1);
			} else if (3 == updraft_restore_stage) {
				var continue_restore = 1;
				jQuery('#updraft_restoreoptions_ui input.required').each(function(index) {
					if (continue_restore == 0) return;
					var sitename = jQuery(this).val();
					if (sitename == '') {
						alert(updraftlion.pleasefillinrequired);
						continue_restore = 0;
					} else if (jQuery(this).attr('pattern') != '') {
						var pattern = jQuery(this).attr('pattern');
						var re = new RegExp(pattern, "g");
						if (!re.test(sitename)) {
							alert(jQuery(this).data('invalidpattern'));
							continue_restore = 0;
						}
					}
				});
				if (!continue_restore) return;
				var restore_options = jQuery('#updraft_restoreoptions_ui select, #updraft_restoreoptions_ui input').serialize();
				console.log("Restore options: "+restore_options);
				jQuery('#updraft_restorer_restore_options').val(restore_options);
				// This must be done last, as it wipes out the section with #updraft_restoreoptions_ui
				jQuery('#updraft-restore-modal-stage2a').html(updraftlion.restore_proceeding);
				jQuery('#updraft_restore_form').submit();
				// In progress; prevent the button being pressed again
				updraft_restore_stage = 4;
			}
		} else {
			alert(updraftlion.youdidnotselectany);
		}
	};
	
	updraft_restore_modal_buttons[updraftlion.cancel] = function() {
 jQuery(this).dialog("close"); };

	jQuery("#updraft-restore-modal").dialog({
		autoOpen: false, height: 505, width: 590, modal: true,
		buttons: updraft_restore_modal_buttons
	});

	jQuery("#updraft-iframe-modal").dialog({
		autoOpen: false, height: 500, width: 780, modal: true
	});

	jQuery("#updraft-backupnow-inpage-modal").dialog({
		autoOpen: false, height: 345, width: 580, modal: true
	});
	
	var backupnow_modal_buttons = {};
	backupnow_modal_buttons[updraftlion.backupnow] = function() {
		
		var backupnow_nodb = jQuery('#backupnow_includedb').is(':checked') ? 0 : 1;
		var backupnow_nofiles = jQuery('#backupnow_includefiles').is(':checked') ? 0 : 1;
		var backupnow_nocloud = jQuery('#backupnow_includecloud').is(':checked') ? 0 : 1;
		
		var onlythesetableentities = backupnow_whichtables_checked('');

		if ('' == onlythesetableentities && 0 == backupnow_nodb) {
			alert(updraftlion.notableschosen);
			jQuery('#backupnow_includefiles_moreoptions').show();
			return;
		}

		if (typeof onlythesetableentities === 'boolean') {
			onlythesetableentities = null;
		}

		var onlythesefileentities = backupnow_whichfiles_checked('');

		if ('' == onlythesefileentities && 0 == backupnow_nofiles) {
			alert(updraftlion.nofileschosen);
			jQuery('#backupnow_includefiles_moreoptions').show();
			return;
		}
		
		if (backupnow_nodb && backupnow_nofiles) {
			alert(updraftlion.excludedeverything);
			return;
		}
		
		jQuery(this).dialog("close");

		setTimeout(function() {
			jQuery('#updraft_lastlogmessagerow').fadeOut('slow', function() {
				jQuery(this).fadeIn('slow');
			});
		}, 1700);
		
		updraft_backupnow_go(backupnow_nodb, backupnow_nofiles, backupnow_nocloud, onlythesefileentities, '', jQuery('#backupnow_label').val(), onlythesetableentities);
	};
	backupnow_modal_buttons[updraftlion.cancel] = function() {
 jQuery(this).dialog("close"); };
	
	jQuery("#updraft-backupnow-modal").dialog({
		autoOpen: false, height: 472, width: 610, modal: true,
		buttons: backupnow_modal_buttons
	});

	jQuery("#updraft-migrate-modal").dialog({
		autoOpen: false, height: updraftlion.migratemodalheight, width: updraftlion.migratemodalwidth, modal: true,
	});

	jQuery("#updraft-poplog").dialog({
		autoOpen: false, height: 600, width: '75%', modal: true,
	});
	
	jQuery('#updraft-navtab-settings-content .enableexpertmode').click(function() {
		jQuery('#updraft-navtab-settings-content .expertmode').fadeIn();
		jQuery('#updraft-navtab-settings-content .enableexpertmode').off('click');
		return false;
	});
	
	jQuery('#updraft-navtab-settings-content .backupdirrow').on('click', 'a.updraft_backup_dir_reset', function() {
		jQuery('#updraft_dir').val('updraft'); return false;
	});

	function setup_file_entity_exclude_field(field, instant) {
		if (jQuery('#updraft-navtab-settings-content #updraft_include_'+field).is(':checked')) {
			if (instant) {
				jQuery('#updraft-navtab-settings-content #updraft_include_'+field+'_exclude').show();
			} else {
				jQuery('#updraft-navtab-settings-content #updraft_include_'+field+'_exclude').slideDown();
			}
		} else {
			if (instant) {
				jQuery('#updraft-navtab-settings-content #updraft_include_'+field+'_exclude').hide();
			} else {
				jQuery('#updraft-navtab-settings-content #updraft_include_'+field+'_exclude').slideUp();
			}
		}
	}
	
	jQuery('#updraft-navtab-settings-content .updraft_include_entity').click(function() {
		var has_exclude_field = jQuery(this).data('toggle_exclude_field');
		if (has_exclude_field) {
			setup_file_entity_exclude_field(has_exclude_field, false);
		}
	});
	
	// TODO: This is suspected to be obsolete. Confirm + remove.
	jQuery('#updraft-navtab-settings-content .updraft-service').change(function() {
		var active_class = jQuery(this).val();
		jQuery('#updraft-navtab-settings-content .updraftplusmethod').hide();
		jQuery('#updraft-navtab-settings-content .'+active_class).show();
	});

	jQuery('#updraft-navtab-settings-content a.updraft_show_decryption_widget').click(function(e) {
		e.preventDefault();
		jQuery('#updraftplus_db_decrypt').val(jQuery('#updraft_encryptionphrase').val());
		jQuery('#updraft-manualdecrypt-modal').slideToggle();
	});
	
	jQuery('#updraftplus-phpinfo').click(function(e) {
		e.preventDefault();
		updraft_iframe_modal('phpinfo', updraftlion.phpinfo);
	});

	jQuery('#updraftplus-rawbackuphistory').click(function(e) {
		e.preventDefault();
		updraft_iframe_modal('rawbackuphistory', updraftlion.raw);
	});

	// + Added addons navtab
	jQuery('#updraft-navtab-status').click(function(e) {
		e.preventDefault();
		jQuery(this).addClass('nav-tab-active');
		jQuery('#updraft-navtab-expert-content').hide();
		jQuery('#updraft-navtab-settings-content').hide();
		jQuery('#updraft-navtab-backups-content').hide();
		jQuery('#updraft-navtab-addons-content').hide();
		jQuery('#updraft-navtab-status-content').show();
		jQuery('#updraft-navtab-expert').removeClass('nav-tab-active');
		jQuery('#updraft-navtab-backups').removeClass('nav-tab-active');
		jQuery('#updraft-navtab-settings').removeClass('nav-tab-active');
		jQuery('#updraft-navtab-addons').removeClass('nav-tab-active');
		updraft_page_is_visible = 1;
		updraft_console_focussed_tab = 1;
		// Refresh the console, as its next update might be far away
		updraft_activejobs_update(true);
	});
	jQuery('#updraft-navtab-expert').click(function(e) {
		e.preventDefault();
		jQuery(this).addClass('nav-tab-active');
		jQuery('#updraft-navtab-settings-content').hide();
		jQuery('#updraft-navtab-status-content').hide();
		jQuery('#updraft-navtab-backups-content').hide();
		jQuery('#updraft-navtab-addons-content').hide();
		jQuery('#updraft-navtab-expert-content').show();
		jQuery('#updraft-navtab-status').removeClass('nav-tab-active');
		jQuery('#updraft-navtab-backups').removeClass('nav-tab-active');
		jQuery('#updraft-navtab-settings').removeClass('nav-tab-active');
		jQuery('#updraft-navtab-addons').removeClass('nav-tab-active');
		updraft_page_is_visible = 1;
		updraft_console_focussed_tab = 4;
	});
	jQuery('#updraft-navtab-settings, #updraft-navtab-settings2, #updraft_backupnow_gotosettings').click(function(e) {
		e.preventDefault();
		// These next two should only do anything if the relevant selector was clicked
		jQuery(this).parents('.updraftmessage').remove();
		jQuery('#updraft-backupnow-modal').dialog('close');
		jQuery('#updraft-navtab-status-content').hide();
		jQuery('#updraft-navtab-backups-content').hide();
		jQuery('#updraft-navtab-expert-content').hide();
		jQuery('#updraft-navtab-addons-content').hide();
		jQuery('#updraft-navtab-settings-content').show();
		jQuery('#updraft-navtab-settings').addClass('nav-tab-active');
		jQuery('#updraft-navtab-expert').removeClass('nav-tab-active');
		jQuery('#updraft-navtab-backups').removeClass('nav-tab-active');
		jQuery('#updraft-navtab-status').removeClass('nav-tab-active');
		jQuery('#updraft-navtab-addons').removeClass('nav-tab-active');
		updraft_page_is_visible = 1;
		updraft_console_focussed_tab = 3;
	});
	jQuery('#updraft-navtab-addons').click(function(e) {
		e.preventDefault();
		jQuery(this).addClass('b#nav-tab-active');
		jQuery('#updraft-navtab-status-content').hide();
		jQuery('#updraft-navtab-backups-content').hide();
		jQuery('#updraft-navtab-expert-content').hide();
		jQuery('#updraft-navtab-settings-content').hide();
		jQuery('#updraft-navtab-addons-content').show();
		jQuery('#updraft-navtab-addons').addClass('nav-tab-active');
		jQuery('#updraft-navtab-expert').removeClass('nav-tab-active');
		jQuery('#updraft-navtab-backups').removeClass('nav-tab-active');
		jQuery('#updraft-navtab-status').removeClass('nav-tab-active');
		jQuery('#updraft-navtab-settings').removeClass('nav-tab-active');
		updraft_page_is_visible = 1;
		updraft_console_focussed_tab = 5;
	});
	jQuery('#updraft-navtab-backups').click(function(e) {
		e.preventDefault();
		updraft_openrestorepanel(1);
	});
	
	updraft_send_command('ping', null, function(data, response) {
		if ('success' == response && data != 'pong' && data.indexOf('pong')>=0) {
			jQuery('#updraft-navtab-backups-content .ud-whitespace-warning').show();
			console.log("UpdraftPlus: Extra output warning: response (which should be just (string)'pong') follows.");
			console.log(data);
		}
	}, { json_parse: false, type: 'GET' });

	// Section: Plupload
	try {
		if (typeof updraft_plupload_config !== 'undefined') {
			plupload_init();
		}
	} catch (err) {
		console.log(err);
	}
	
	function plupload_init() {
	
		// create the uploader and pass the config from above
		var uploader = new plupload.Uploader(updraft_plupload_config);

		// checks if browser supports drag and drop upload, makes some css adjustments if necessary
		uploader.bind('Init', function(up) {
			var uploaddiv = jQuery('#plupload-upload-ui');
			
			if (up.features.dragdrop) {
				uploaddiv.addClass('drag-drop');
				jQuery('#drag-drop-area')
				.bind('dragover.wp-uploader', function() {
 uploaddiv.addClass('drag-over'); })
				.bind('dragleave.wp-uploader, drop.wp-uploader', function() {
 uploaddiv.removeClass('drag-over'); });
				
			} else {
				uploaddiv.removeClass('drag-drop');
				jQuery('#drag-drop-area').unbind('.wp-uploader');
			}
		});
					
		uploader.init();

		// a file was added in the queue
		uploader.bind('FilesAdded', function(up, files) {
		// var hundredmb = 100 * 1024 * 1024, max = parseInt(up.settings.max_file_size, 10);
		
		plupload.each(files, function(file) {
				// @codingStandardsIgnoreLine
				if (! /^backup_([\-0-9]{15})_.*_([0-9a-f]{12})-[\-a-z]+([0-9]+?)?(\.(zip|gz|gz\.crypt))?$/i.test(file.name) && ! /^log\.([0-9a-f]{12})\.txt$/.test(file.name)) {
					var accepted_file = false;
					for (var i = 0; i<updraft_accept_archivename.length; i++) {
						if (updraft_accept_archivename[i].test(file.name)) {
							var accepted_file = true;
						}
					}
					if (!accepted_file) {
						if (/\.(zip|tar|tar\.gz|tar\.bz2)$/i.test(file.name) || /\.sql(\.gz)?$/i.test(file.name)) {
							jQuery('#updraft-message-modal-innards').html('<p><strong>'+file.name+"</strong></p> "+updraftlion.notarchive2);
							jQuery('#updraft-message-modal').dialog('open');
						} else {
							alert(file.name+": "+updraftlion.notarchive);
						}
						uploader.removeFile(file);
						return;
					}
					}
			
				// a file was added, you may want to update your DOM here...
				jQuery('#filelist').append(
					'<div class="file" id="' + file.id + '"><b>' +
					file.name + '</b> (<span>' + plupload.formatSize(0) + '</span>/' + plupload.formatSize(file.size) + ') ' +
				'<div class="fileprogress"></div></div>');
		});
			
			up.refresh();
			up.start();
		});
			
		uploader.bind('UploadProgress', function(up, file) {
			jQuery('#' + file.id + " .fileprogress").width(file.percent + "%");
			jQuery('#' + file.id + " span").html(plupload.formatSize(parseInt(file.size * file.percent / 100)));

			if (file.size == file.loaded) {
				jQuery('#' + file.id).html('<div class="file" id="' + file.id + '"><b>' +
				file.name + '</b> (<span>' + plupload.formatSize(parseInt(file.size * file.percent / 100)) + '</span>/' + plupload.formatSize(file.size) + ') - ' + updraftlion.complete +
				'</div>'); // Removed <div class="fileprogress"></div> (just before closing </div>) to make clearer it's complete.
				jQuery('#' + file.id + " .fileprogress").width(file.percent + "%");
			}
		});

		uploader.bind('Error', function(up, error) {

			console.log(error);
			
			var err_makesure;
			if (error.code == "-200") {
				err_makesure = '\n'+updraftlion.makesure2;
			} else {
				err_makesure = updraftlion.makesure;
			}
			
			var msg = updraftlion.uploaderr+' (code '+error.code+') : '+error.message;
			
			if (error.hasOwnProperty('status') && error.status) {
				msg += ' ('+updraftlion.http_code+' '+error.status+')';
			}
			
			if (error.hasOwnProperty('response')) {
				console.log('UpdraftPlus: plupload error: '+error.response);
				if (error.response.length < 100) msg += ' '+updraftlion.error+' '+error.response+'\n';
			}
			
			msg += ' '+err_makesure;
			
			alert(msg);
		});

		// a file was uploaded
		uploader.bind('FileUploaded', function(up, file, response) {
			
			if (response.status == '200') {
				// this is your ajax response, update the DOM with it or something...
				try {
					resp = ud_parse_json(response.response);
					if (resp.e) {
						alert(updraftlion.uploaderror+" "+resp.e);
					} else if (resp.dm) {
						alert(resp.dm);
						updraft_updatehistory(1, 0);
					} else if (resp.m) {
						updraft_updatehistory(1, 0);
					} else {
						alert('Unknown server response: '+response.response);
					}
					
				} catch (err) {
					console.log(response);
					alert(updraftlion.jsonnotunderstood);
				}

			} else {
				alert('Unknown server response status: '+response.code);
				console.log(response);
			}

		});
	}
	
	// Functions in the debugging console
	jQuery('#updraftplus_httpget_go').click(function(e) {
		e.preventDefault();
		updraftplus_httpget_go(0);
	});

	jQuery('#updraftplus_httpget_gocurl').click(function(e) {
		e.preventDefault();
		updraftplus_httpget_go(1);
	});
	
	jQuery('#updraftplus_callwpaction_go').click(function(e) {
		e.preventDefault();
		params = { wpaction: jQuery('#updraftplus_callwpaction').val() };
		updraft_send_command('call_wordpress_action', params, function(response) {
			if (response.e) {
				alert(response.e);
			} else if (response.s) {
				// Silence
			} else if (response.r) {
				jQuery('#updraftplus_callwpaction_results').html(response.r);
			} else {
				console.log(response);
				alert(updraftlion.jsonnotunderstood);
			}
		});
	});
	
	function updraftplus_httpget_go(curl) {
		params = { uri: jQuery('#updraftplus_httpget_uri').val() };
		params.curl = curl;
		updraft_send_command('httpget', params, function(resp) {
			if (resp.e) { alert(resp.e); }
			if (resp.r) {
				jQuery('#updraftplus_httpget_results').html('<pre>'+resp.r+'</pre>');
			} else {
				console.log(resp);
			}
		}, { type: 'GET' });
	}
	
	jQuery('#updraft_activejobs_table').on('click', '.updraft_jobinfo_delete', function(e) {
		e.preventDefault();
		var job_id = jQuery(this).data('jobid');
		if (job_id) {
			updraft_activejobs_delete(job_id);
		} else {
			console.log("UpdraftPlus: A stop job link was clicked, but the Job ID could not be found");
		}
	});
	
	jQuery('#updraft_activejobs_table, #updraft-navtab-backups-content .updraft_existing_backups, #updraft-backupnow-inpage-modal').on('click', '.updraft-log-link', function(e) {
		e.preventDefault();
		var job_id = jQuery(this).data('jobid');
		if (job_id) {
			updraft_popuplog(job_id);
		} else {
			console.log("UpdraftPlus: A log link was clicked, but the Job ID could not be found");
		}
	});
	
	function updraft_restore_setup(entities, key, show_data) {
		updraft_restore_setoptions(entities);
		jQuery('#updraft_restore_timestamp').val(key);
		jQuery('.updraft_restore_date').html(show_data);
		
		updraft_restore_stage = 1;
		
		jQuery('#updraft-migrate-modal').dialog('close');
		jQuery('#updraft-restore-modal').dialog('open');
		jQuery('#updraft-restore-modal-stage1').show();
		jQuery('#updraft-restore-modal-stage2').hide();
		jQuery('#updraft-restore-modal-stage2a').html('');
		
		updraft_activejobs_update(true);
	}
	
	jQuery('#updraft-navtab-backups-content .updraft_existing_backups').on('click', 'button.choose-components-button', function(e) {
		var entities = jQuery(this).data('entities');
		var backup_timestamp = jQuery(this).data('backup_timestamp');
		var show_data = jQuery(this).data('showdata');
		updraft_restore_setup(entities, backup_timestamp, show_data);
	});
	
	/**
	 * Get the value of a named URL parameter - https://stackoverflow.com/questions/4548487/jquery-read-query-string
	 *
	 * @param {string} name - URL parameter to return the value of
	 *
	 * @returns {string}
	 */
	function get_parameter_by_name(name) {
		name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
		var regex_s = "[\\?&]"+name+"=([^&#]*)";
		var regex = new RegExp(regex_s);
		var results = regex.exec(window.location.href);
		if (results == null) {
			return '';
		} else {
			return decodeURIComponent(results[1].replace(/\+/g, ' '));
		}
	}
	
	if (get_parameter_by_name('udaction') == 'initiate_restore') {
		var entities = get_parameter_by_name('entities');
		var backup_timestamp = get_parameter_by_name('backup_timestamp');
		var show_data = get_parameter_by_name('showdata');
		updraft_restore_setup(entities, backup_timestamp, show_data);
	}
	
	jQuery('#updraft-navtab-backups-content .updraft_existing_backups').on('click', '.updraft-delete-link', function(e) {
		e.preventDefault();
		var hasremote = jQuery(this).data('hasremote');
		var nonce = jQuery(this).data('nonce').toString();
		var key = jQuery(this).data('key').toString();
		if (nonce) {
			updraft_delete(key, nonce, hasremote);
		} else {
			console.log("UpdraftPlus: A delete link was clicked, but the Job ID could not be found");
		}
	});
	
	jQuery('#updraft-navtab-backups-content .updraft_existing_backups').on('click', 'button.updraft_download_button', function(e) {
		e.preventDefault();
		var base = 'uddlstatus_';
		var backup_timestamp = jQuery(this).data('backup_timestamp');
		var what = jQuery(this).data('what');
		var whicharea = '.ud_downloadstatus';
		var set_contents = jQuery(this).data('set_contents');
		var prettydate = jQuery(this).data('prettydate');
		var async = true;
		updraft_downloader(base, backup_timestamp, what, whicharea, set_contents, prettydate, async);
	});
	
	jQuery('#updraft-navtab-backups-content .updraft_existing_backups').on('dblclick', '.updraft_existingbackup_date', function(e) {
		e.preventDefault();
		var data = jQuery(this).data('rawbackup');
		if (data != null && data != '') {
			updraft_html_modal(data, updraftlion.raw, 780, 500);
		}
	});

});

// UpdraftPlus Vault
jQuery(document).ready(function($) {
	
	var settings_css_prefix = '#updraft-navtab-settings-content ';
	
	$(settings_css_prefix+'#remote-storage-holder').on('click', '.updraftvault_backtostart', function(e) {
		e.preventDefault();
		$(settings_css_prefix+'#updraftvault_settings_showoptions').slideUp();
		$(settings_css_prefix+'#updraftvault_settings_connect').slideUp();
		$(settings_css_prefix+'#updraftvault_settings_connected').slideUp();
		$(settings_css_prefix+'#updraftvault_settings_default').slideDown();
	});
	
	// Prevent default event when pressing return in the form
	$(settings_css_prefix).on('keypress','#updraftvault_settings_connect input', function(e) {
		if (13 == e.which) {
			$(settings_css_prefix+'#updraftvault_connect_go').click();
			return false;
		}
	});
	
	$(settings_css_prefix+'#remote-storage-holder').on('click', '#updraftvault_recountquota', function(e) {
		e.preventDefault();
		$(settings_css_prefix+'#updraftvault_recountquota').html(updraftlion.counting);
		try {
			updraft_send_command('vault_recountquota', { instance_id: $('#updraftvault_settings_connect').data('instance_id') }, function(response) {
				$(settings_css_prefix+'#updraftvault_recountquota').html(updraftlion.updatequotacount);
				if (response.hasOwnProperty('html')) {
					$(settings_css_prefix+'#updraftvault_settings_connected').html(response.html);
					if (response.hasOwnProperty('connected')) {
						if (response.connected) {
							$(settings_css_prefix+'#updraftvault_settings_default').hide();
							$(settings_css_prefix+'#updraftvault_settings_connected').show();
						} else {
							$(settings_css_prefix+'#updraftvault_settings_connected').hide();
							$(settings_css_prefix+'#updraftvault_settings_default').show();
						}
					}
				}
			});
		} catch (err) {
			$(settings_css_prefix+'#updraftvault_recountquota').html(updraftlion.updatequotacount);
			console.log(err);
		}
	});
	
	$(settings_css_prefix+'#remote-storage-holder').on('click', '#updraftvault_disconnect', function(e) {
		e.preventDefault();
		$(settings_css_prefix+'#updraftvault_disconnect').html(updraftlion.disconnecting);
		try {
			updraft_send_command('vault_disconnect', { immediate_echo: true, instance_id: $('#updraftvault_settings_connect').data('instance_id') }, function(response) {
				$(settings_css_prefix+'#updraftvault_disconnect').html(updraftlion.disconnect);
				if (response.hasOwnProperty('html')) {
					$(settings_css_prefix+'#updraftvault_settings_connected').html(response.html).slideUp();
					$(settings_css_prefix+'#updraftvault_settings_default').slideDown();
				}
			});
		} catch (err) {
			$(settings_css_prefix+'#updraftvault_disconnect').html(updraftlion.disconnect);
			console.log(err);
		}
	});
	
	$(settings_css_prefix+'#remote-storage-holder').on('click', '#updraftvault_connect', function(e) {
		e.preventDefault();
		$(settings_css_prefix+'#updraftvault_settings_default').slideUp();
		$(settings_css_prefix+'#updraftvault_settings_connect').slideDown();
	});
	
	$(settings_css_prefix+'#remote-storage-holder').on('click', '#updraftvault_showoptions', function(e) {
		e.preventDefault();
		$(settings_css_prefix+'#updraftvault_settings_default').slideUp();
		$(settings_css_prefix+'#updraftvault_settings_showoptions').slideDown();
	});
	
	$(settings_css_prefix+'#remote-storage-holder').on('click', '#updraftvault_connect_go', function(e) {
		$(settings_css_prefix+'#updraftvault_connect_go').html(updraftlion.connecting);
		updraft_send_command('vault_connect', {
			email: $('#updraftvault_email').val(),
			pass: $('#updraftvault_pass').val(),
			instance_id: $('#updraftvault_settings_connect').data('instance_id'),
		}, function(resp, status, response) {
			$(settings_css_prefix+'#updraftvault_connect_go').html(updraftlion.connect);
			if (resp.hasOwnProperty('e')) {
				updraft_html_modal('<h4 style="margin-top:0px; padding-top:0px;">'+updraftlion.errornocolon+'</h4><p>'+resp.e+'</p>', updraftlion.disconnect, 400, 250);
				if (resp.hasOwnProperty('code') && resp.code == 'no_quota') {
					$(settings_css_prefix+'#updraftvault_settings_connect').slideUp();
					$(settings_css_prefix+'#updraftvault_settings_default').slideDown();
				}
			} else if (resp.hasOwnProperty('connected') && resp.connected && resp.hasOwnProperty('html')) {
				$(settings_css_prefix+'#updraftvault_settings_connect').slideUp();
				$(settings_css_prefix+'#updraftvault_settings_connected').html(resp.html).slideDown();
			} else {
				console.log(resp);
				alert(updraftlion.unexpectedresponse+' '+response);
			}
		});
		return false;
	});
});

// Next: the encrypted database pluploader
jQuery(document).ready(function($) {
	
	try {
		if (typeof updraft_plupload_config2 !== 'undefined') {
			plupload_init();
		}
	} catch (err) {
		console.log(err);
	}
		
	function plupload_init() {
		// create the uploader and pass the config from above
		var uploader = new plupload.Uploader(updraft_plupload_config2);
		
		// checks if browser supports drag and drop upload, makes some css adjustments if necessary
		uploader.bind('Init', function(up) {
			var uploaddiv = jQuery('#plupload-upload-ui2');

			if (up.features.dragdrop) {
				uploaddiv.addClass('drag-drop');
				jQuery('#drag-drop-area2')
				.bind('dragover.wp-uploader', function() {
 uploaddiv.addClass('drag-over'); })
				.bind('dragleave.wp-uploader, drop.wp-uploader', function() {
 uploaddiv.removeClass('drag-over'); });
			} else {
				uploaddiv.removeClass('drag-drop');
				jQuery('#drag-drop-area2').unbind('.wp-uploader');
			}
		});
		
		uploader.init();
		
		// a file was added in the queue
		uploader.bind('FilesAdded', function(up, files) {
			// var hundredmb = 100 * 1024 * 1024, max = parseInt(up.settings.max_file_size, 10);
			
			plupload.each(files, function(file) {
				// @codingStandardsIgnoreLine
				if (!/^backup_([\-0-9]{15})_.*_([0-9a-f]{12})-db([0-9]+)?\.(gz\.crypt)$/i.test(file.name)) {
					alert(file.name+': '+updraftlion.notdba);
					uploader.removeFile(file);
					return;
				}
				
				// a file was added, you may want to update your DOM here...
				jQuery('#filelist2').append(
					'<div class="file" id="' + file.id + '"><b>' +
					file.name + '</b> (<span>' + plupload.formatSize(0) + '</span>/' + plupload.formatSize(file.size) + ') ' +
				'<div class="fileprogress"></div></div>');
			});
		
			up.refresh();
			up.start();
		});
		
		uploader.bind('UploadProgress', function(up, file) {
			jQuery('#' + file.id + " .fileprogress").width(file.percent + "%");
			jQuery('#' + file.id + " span").html(plupload.formatSize(parseInt(file.size * file.percent / 100)));
		});
		
		uploader.bind('Error', function(up, error) {
			if ('-200' == error.code) {
				err_makesure = '\n'+updraftlion.makesure2;
			} else {
				err_makesure = updraftlion.makesure;
			}
			alert(updraftlion.uploaderr+' (code '+error.code+") : "+error.message+" "+err_makesure);
		});
		
		// a file was uploaded
		uploader.bind('FileUploaded', function(up, file, response) {
			
			if (response.status == '200') {
				// this is your ajax response, update the DOM with it or something...
				if (response.response.substring(0,6) == 'ERROR:') {
					alert(updraftlion.uploaderror+" "+response.response.substring(6));
				} else if (response.response.substring(0,3) == 'OK:') {
					bkey = response.response.substring(3);
					jQuery('#' + file.id + " .fileprogress").hide();
					jQuery('#' + file.id).append(updraftlion.uploaded+' <a href="?page=updraftplus&action=downloadfile&updraftplus_file='+bkey+'&decrypt_key='+encodeURIComponent(jQuery('#updraftplus_db_decrypt').val())+'">'+updraftlion.followlink+'</a> '+updraftlion.thiskey+' '+jQuery('#updraftplus_db_decrypt').val().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;"));
				} else {
					alert(updraftlion.unknownresp+' '+response.response);
				}
			} else {
				alert(updraftlion.ukrespstatus+' '+response.code);
			}
			
		});
	}

	jQuery('#updraft-hidethis').remove();
	
	/*
		* A Handlebarsjs helper function that is used to compare
		* two values if they are equal. Please refer to the example below.
		* Assuming "comment_status" contains the value of "spam".
		*
		* @param {mixed} a The first value to compare
		* @param {mixed} b The second value to compare
		*
		* @example
		* // returns "<span>I am spam!</span>", otherwise "<span>I am not a spam!</span>"
		* {{#ifeq "spam" comment_status}}
		*      <span>I am spam!</span>
		* {{else}}
		*      <span>I am not a spam!</span>
		* {{/ifeq}}
		*
		* @return {string}
	*/

	Handlebars.registerHelper('ifeq', function (a, b, opts) {
		if ('string' !== typeof a && 'undefined' !== typeof a && null !== a) a = a.toString();
		if ('string' !== typeof b && 'undefined' !== typeof b && null !== b) b = b.toString();
		if (a === b) {
			return opts.fn(this);
		} else {
			return opts.inverse(this);
		}
	});
	// Add remote methods html using handlebarjs
	if ($('#remote-storage-holder').length) {
		var html = '';
		for (var method in updraftlion.remote_storage_templates) {
			if ('undefined' != typeof updraftlion.remote_storage_options[method]) {
				var template = Handlebars.compile(updraftlion.remote_storage_templates[method]);
				var first_instance = true;
				for (var instance_id in updraftlion.remote_storage_options[method]) {
					if ('default' === instance_id) continue;
					var context = updraftlion.remote_storage_options[method][instance_id];
					context['first_instance'] = first_instance;
					if ('undefined' == typeof context['instance_enabled']) {
						context['instance_enabled'] = 1;
					}
					html += template(context);
					first_instance = false;
				}
			} else {
				html += updraftlion.remote_storage_templates[method];
			}
		}
		$('#remote-storage-holder').append(html).ready(function () {
			$('.updraftplusmethod').not('.none').hide();
			updraft_remote_storage_tabs_setup();
		});
	}

});

// Save/Export/Import settings via AJAX
jQuery(document).ready(function($) {
	// Pre-load the image so that it doesn't jerk when first used
	var my_image = new Image();
	my_image.src = updraftlion.ud_url+'/images/udlogo-rotating.gif';

	// When inclusion options for file entities in the settings tab, reflect that in the "Backup Now" dialog, to prevent unexpected surprises
	$('#updraft-navtab-settings-content input.updraft_include_entity').change(function(e) {
		var event_target = $(this).attr('id');
		var checked = $(this).is(':checked');
		var backup_target = '#backupnow_files_'+event_target;
		$(backup_target).prop('checked', checked);
	});

	$('#updraftplus-settings-save').click(function(e) {
		e.preventDefault();
		$.blockUI({ message: '<div style="margin: 8px; font-size:150%;"><img src="'+updraftlion.ud_url+'/images/udlogo-rotating.gif" height="80" width="80" style="padding-bottom:10px;"><br>'+updraftlion.saving+'</div>'});
		
		var form_data = gather_updraft_settings('string');
		// POST the settings back to the AJAX handler
		updraft_send_command('savesettings', {
			settings: form_data,
			updraftplus_version: updraftlion.updraftplus_version
		}, function(response) {
			// Add page updates etc based on response
			updraft_handle_page_updates(response);
			
			$('#updraft-wrap .fade').delay(6000).fadeOut(2000);

			$('html, body').animate({
				scrollTop: $("#updraft-wrap").offset().top
			}, 1000, function() {
			  check_cloud_authentication()
			});

			$.unblockUI();
		}, { action: 'updraft_savesettings', nonce: updraftplus_settings_nonce, json_parse: false });
	});

	$('#updraftplus-settings-export').click(function() {
		if (updraft_settings_form_changed) {
			alert(updraftlion.unsaved_settings_export);
		}
		export_settings();
	});

	$('#updraftplus-settings-import').click(function() {
		$.blockUI({ message: '<div style="margin: 8px; font-size:150%;"><img src="'+updraftlion.ud_url+'/images/udlogo-rotating.gif" height="80" width="80" style="padding-bottom:10px;"><br>'+updraftlion.importing+'</div>'});
		var updraft_import_file_input = document.getElementById('import_settings');
		if (updraft_import_file_input.files.length == 0) {
			alert(updraftlion.import_select_file);
			$.unblockUI();
			return;
		}
		var updraft_import_file_file = updraft_import_file_input.files[0];
		var updraft_import_file_reader = new FileReader();
		updraft_import_file_reader.onload = function() {
			import_settings(this.result);
		};
		updraft_import_file_reader.readAsText(updraft_import_file_file);
	});
	
	function export_settings() {
		var form_data = gather_updraft_settings('object');
		
		var date_now = new Date();
		
		form_data = JSON.stringify({
			// Indicate the last time the format changed - i.e. do not update this unless there is a format change
			version: '1.12.40',
			epoch_date: date_now.getTime(),
			local_date: date_now.toLocaleString(),
			network_site_url: updraftlion.network_site_url,
			data: form_data
		});
		
		// Attach this data to an anchor on page
		var link = document.body.appendChild(document.createElement('a'));
		link.setAttribute('download', updraftlion.export_settings_file_name);
		link.setAttribute('style', "display:none;");
		link.setAttribute('href', 'data:text/json' + ';charset=UTF-8,' + encodeURIComponent(form_data));
		link.click();
	}
	
	function import_settings(updraft_file_result) {
		var data = decodeURIComponent(updraft_file_result);
		var parsed;
		try {
			parsed = ud_parse_json(data);
		} catch (e) {
			$.unblockUI();
			jQuery('#import_settings').val('');
			console.log(data);
			console.log(e);
			alert(updraftlion.import_invalid_json_file);
			return;
		}
		if (window.confirm(updraftlion.importing_data_from + ' ' + data['network_site_url'] + "\n" + updraftlion.exported_on + ' ' + data['local_date'] + "\n" + updraftlion.continue_import)) {
			// GET the settings back to the AJAX handler
			var stringified = JSON.stringify(parsed['data']);
			updraft_send_command('importsettings', {
				settings: stringified,
				updraftplus_version: updraftlion.updraftplus_version,
			}, function(response) {
				var resp = updraft_handle_page_updates(response);
				if (!resp.hasOwnProperty('saved') || resp.saved) {
					// Prevent the user being told they have unsaved settings
					updraft_settings_form_changed = false;
					// Add page updates etc based on response
					location.replace(updraftlion.updraft_settings_url);
				} else {
					$.unblockUI();
					if (resp.hasOwnProperty('error_message') && resp.error_message) {
						alert(resp.error_message);
					}
				}
			}, { action: 'updraft_importsettings', nonce: updraftplus_settings_nonce, json_parse: false });
		} else {
			$.unblockUI();
		}
	}
	
	/**
	 * Retrieve the current settings from the DOM
	 *
	 * @param {string} output_format - the output format; valid values are 'string' or 'object'
	 *
	 * @returns String|Object
	 */
	function gather_updraft_settings(output_format) {

		var form_data = '';
		var output_format = ('undefined' === typeof output_format) ? 'string' : output_format;
		
		if ('object' == output_format) {
			// Excluding the unnecessary 'action' input avoids triggering a very mis-conceived mod_security rule seen on one user's site
			form_data = $("#updraft-navtab-settings-content form input[name!='action'][name!='option_page'][name!='_wpnonce'][name!='_wp_http_referer'], #updraft-navtab-settings-content form textarea, #updraft-navtab-settings-content form select, #updraft-navtab-settings-content form input[type=checkbox]").serializeJSON({checkboxUncheckedValue: '0', useIntKeysAsArrayIndex: true});
		} else {
			// Excluding the unnecessary 'action' input avoids triggering a very mis-conceived mod_security rule seen on one user's site
			form_data = $("#updraft-navtab-settings-content form input[name!='action'], #updraft-navtab-settings-content form textarea, #updraft-navtab-settings-content form select").serialize();
			
			// include unchecked checkboxes. user filter to only include unchecked boxes.
			$.each($('#updraft-navtab-settings-content form input[type=checkbox]')
				.filter(function(idx) {
				return $(this).prop('checked') == false
				}),
				function(idx, el) {
					// attach matched element names to the form_data with chosen value.
					var empty_val = '0';
					form_data += '&' + $(el).attr('name') + '=' + empty_val;
				}
			);
		}

		return form_data;
	}
	
	/**
	 * Method to parse the response from the backend and update the page with the returned content or display error messages if failed
	 *
	 * @param {string} response - the JSON-encoded response containing information to update the settings page with
	 *
	 * @return {object} - the decoded response (empty if decoding was not successful)
	 */
	function updraft_handle_page_updates(response) {
					
		try {
			var resp = ud_parse_json(response);
			
			var messages = resp.messages;
			// var debug = resp.changed.updraft_debug_mode;
			
			// If backup dir is not writable, change the text, and grey out the 'Backup Now' button
			var backup_dir_writable = resp.backup_dir.writable;
			var backup_dir_message = resp.backup_dir.message;
			var backup_button_title = resp.backup_dir.button_title;
		} catch (e) {
			console.log(e);
			console.log(response);
			alert(updraftlion.jsonnotunderstood);
			$.unblockUI();
			return {};
		}
		
		if (resp.hasOwnProperty('changed')) {
			console.log("UpdraftPlus: savesettings: some values were changed after being filtered");
			console.log(resp.changed);
			for (prop in resp.changed) {
				if ('object' === typeof resp.changed[prop]) {
					for (innerprop in resp.changed[prop]) {
						if (!$("[name='"+innerprop+"']").is(':checkbox')) {
							$("[name='"+prop+"["+innerprop+"]']").val(resp.changed[prop][innerprop]);
						}
					}
				} else {
					if (!$("[name='"+prop+"']").is(':checkbox')) {
						$("[name='"+prop+"']").val(resp.changed[prop]);
					}
				}
			}
		}
		
		$('#updraft_writable_mess').html(backup_dir_message);
		
		if (false == backup_dir_writable) {
			$('#updraft-backupnow-button').attr('disabled', 'disabled');
			$('#updraft-backupnow-button').attr('title', backup_button_title);
			$('.backupdirrow').css('display', 'table-row');
		} else {
			$('#updraft-backupnow-button').removeAttr('disabled');
			$('#updraft-backupnow-button').removeAttr('title');
			// $('.backupdirrow').hide();
		}

		if (resp.hasOwnProperty('updraft_include_more_path')) {
			$('#backupnow_includefiles_moreoptions').html(resp.updraft_include_more_path);
		}

		if (resp.hasOwnProperty('backup_now_message')) { $('#backupnow_remote_container').html(resp.backup_now_message); }
		
		// Move from 2 to 1
		$('.updraftmessage').remove();
		
		$('#updraft_backup_started').before(resp.messages);
	
		$('#next-backup-table-inner').html(resp.scheduled);
		
		return resp;
		
	}

	/**
	 * This function has the workings for checking if any cloud storage needs authentication
	 * If so, these are amended to the HTML and the popup is shown to the users.
	 */
	function check_cloud_authentication(){
		var show_auth_modal = false;
		jQuery('#updraft-authenticate-modal-innards').html('');
		
		jQuery("div[class*=updraft_authenticate_] a.updraft_authlink").each(function () {
			jQuery("#updraft-authenticate-modal-innards").append('<p><a href="'+jQuery(this).attr('href')+'">'+jQuery(this).html()+'</a></p>');
			show_auth_modal = true;
		  });
			

		if (show_auth_modal) {
			var updraft_authenticate_modal_buttons = {};
			updraft_authenticate_modal_buttons[updraftlion.cancel] = function() {
 jQuery(this).dialog("close"); };

			jQuery('#updraft-authenticate-modal').dialog({autoOpen: true,
				modal: true,
				resizable: false,
				draggable: false,
				buttons: updraft_authenticate_modal_buttons,
				width:'auto'}).dialog('open');
		}
	}


});

// For When character set and collate both are unsupported at restoration time and if user change anyone substitution dropdown from both, Other substitution select box value should be change respectively.
jQuery(document).ready(function($) {
	jQuery('#updraft-restore-modal').on('change', '#updraft_restorer_charset', function(e) {
		if ($('#updraft_restorer_charset').length && $('#updraft_restorer_collate').length && $('#collate_change_on_charset_selection_data').length) {
			var updraft_restorer_charset = $('#updraft_restorer_charset').val();
			// For only show collate which are related to charset
			$('#updraft_restorer_collate option').show();
			$('#updraft_restorer_collate option[data-charset!='+updraft_restorer_charset+']').hide();
			updraft_send_command('collate_change_on_charset_selection', {
				collate_change_on_charset_selection_data: $('#collate_change_on_charset_selection_data').val(),
				updraft_restorer_charset: updraft_restorer_charset,
				updraft_restorer_collate: $('#updraft_restorer_collate').val(),
			}, function(response) {
				if (response.hasOwnProperty('is_action_required') && 1 == response.is_action_required && response.hasOwnProperty('similar_type_collate')) {
					$('#updraft_restorer_collate').val(response.similar_type_collate);
				}
			});
		}
	});
});
