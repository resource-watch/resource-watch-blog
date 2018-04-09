<?php
/**
 * @package   The_Grid
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2015 Themeone
 */

// Exit if accessed directly
if (!defined('ABSPATH')) { 
	exit;
}

// Append custom script
if (!empty($tg_grid_data['custom_js'])) {
	echo '<script type="text/javascript">(function($) {"use strict";'.$tg_grid_data['custom_js'].'})(jQuery)</script>';
}
