<?php if(!defined('ABSPATH')) die('Direct access is not allowed.');


/*
    Abort if not called during uninstallation process
*/
if( ! defined('WP_UNINSTALL_PLUGIN'))
{
	exit;
}


/* Delete our options */
delete_option('easydebuginfo_latest_report');
