<?php if(!defined('ABSPATH')) die('Direct access is not allowed.');


/*
Plugin Name:   Easy Debug Info
Plugin URI:
Description:   Print debug info easily
Version:       1.0.0
Author:        Jonas Döbertin
Author URI:
*/


/*
	Define some constants, including the current plugin version, it's basename,
	the full path and the url to the plugin files.
 */
define('JD_EASYDEBUGINFO_BASENAME',  plugin_basename(__FILE__));
define('JD_EASYDEBUGINFO_MAINFILE',  __FILE__);
define('JD_EASYDEBUGINFO_PATH',     plugin_dir_path(__FILE__));
define('JD_EASYDEBUGINFO_URL',      plugins_url('', __FILE__));
define('JD_EASYDEBUGINFO_VERSION',  '1.0.0');


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
