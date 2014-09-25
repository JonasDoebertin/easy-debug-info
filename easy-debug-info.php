<?php if(!defined('ABSPATH')) die('Direct access is not allowed.');


/*
	Plugin Name:   Easy Debug Info
	Plugin URI:    http://wordpress.org/plugins/easy-debug-info/
	Description:   Making collecting extensive and extendable debug info finally easy
	Version:       1.2.0
	Author:        Jonas Döbertin
	Author URI:    http://jd-powered.net
 */


/*
	Easy Debug Info
	Copyright (C) 2014 Jonas Döbertin <hallo@jonasdoebertin.net>

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


/*
	Define some constants, including the current plugin version, it's basename,
	the full path and the url to the plugin files.
 */
define('JD_EASYDEBUGINFO_BASENAME',  plugin_basename(__FILE__));
define('JD_EASYDEBUGINFO_MAINFILE',  __FILE__);
define('JD_EASYDEBUGINFO_PATH',     plugin_dir_path(__FILE__));
define('JD_EASYDEBUGINFO_URL',      plugins_url('', __FILE__));
define('JD_EASYDEBUGINFO_VERSION',  '1.2.0');


/*
	Check for a compatible version of PHP
 */
if(version_compare(PHP_VERSION, '5.3.0', '<'))
{
	/*
		If the PHP version is too old, we'll import our legacy code. This will
		add a notice to the plugins.php page (stating that the plugin requires
		PHP 5.3.0 or newer) and register a stylesheet for this notification.
		THE MAIN PLUGIN WILL NOT BE LOADED
	 */
	require JD_EASYDEBUGINFO_PATH . 'legacy.php';
}
else
{
	/*
		We do have a version of PHP that matches our criteria. To avoid any
		syntax errors thrown by PHP < 5.3.0 when using namespaces, we'll load
		our bootstrap file that will handle loading the plugin core.
	 */
	require JD_EASYDEBUGINFO_PATH . 'bootstrap.php';
}
