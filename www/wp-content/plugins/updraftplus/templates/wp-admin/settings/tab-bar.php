<?php if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed'); ?>

<h2 class="nav-tab-wrapper">

<a class="nav-tab <?php if (1 == $tabflag) echo 'nav-tab-active'; ?>" id="updraft-navtab-status" href="#updraft-navtab-status-content" ><?php _e('Current Status', 'updraftplus');?>             </span></a>
<a class="nav-tab <?php if (2 == $tabflag) echo 'nav-tab-active'; ?>" id="updraft-navtab-backups" href="#updraft-navtab-backups-contents" ><?php echo __('Existing Backups', 'updraftplus').' ('.count($backup_history).')';?>           </span></a>
<a class="nav-tab <?php if (3 == $tabflag) echo 'nav-tab-active'; ?>" id="updraft-navtab-settings" href="#updraft-navtab-settings-content"><?php _e('Settings', 'updraftplus');?>                </span></a>
<a class="nav-tab<?php if (4 == $tabflag) echo ' nav-tab-active'; ?>" id="updraft-navtab-expert" href="#updraft-navtab-expert-content"><?php _e('Advanced Tools', 'updraftplus');?>              </span></a>
<a class="nav-tab<?php if (5 == $tabflag) echo ' nav-tab-active'; ?>" id="updraft-navtab-addons" href="#updraft-navtab-addons-content"><?php _e('Premium / Extensions', 'updraftplus');?>                </span></a>
 
</h2>
