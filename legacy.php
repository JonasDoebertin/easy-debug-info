<?php if(!defined('ABSPATH')) die('Direct access is not allowed.');


/*
    The PHP version is too old. We will add a notice to the plugins.php page
    (stating that Easy Debug Info requires PHP 5.3.0 or newer) and register a
    stylesheet for this notification.
 */


/*
    We want to show the message only on the "Installed Plugins" page
 */
if( ! isset($pagenow) or ($pagenow == 'plugins.php'))
{


    /*
        Register notice and the related stylesheet
     */
    add_action('admin_notices', 'jd_easydebuginfo_legacy_add_admin_notice');
    add_action('admin_enqueue_scripts', 'jd_easydebuginfo_legacy_register_admin_notice_style');

    /*
        Register function to programmatically deactive ourself
        after showing the notice.
     */
    add_action('admin_init', 'jd_easydebuginfo_legacy_self_deactivation');

    /**
     * Load legacy notice view
     *
     * @return void
     */
    function jd_easydebuginfo_legacy_add_admin_notice()
    {
        include JD_EASYDEBUGINFO_PATH . 'views/notices/legacy.php';
    }

    /**
     * Enqueue legacy notice specific stylesheet
     *
     * @return void
     */
    function jd_easydebuginfo_legacy_register_admin_notice_style()
    {
        wp_enqueue_style('jd-easydebuginfo-notices', JD_EASYDEBUGINFO_URL . '/assets/css/notices.css', array(), JD_EASYDEBUGINFO_VERSION, 'all');
    }

    /**
     * Deactive ourselfs
     *
     * @return void
     */
    function jd_easydebuginfo_legacy_self_deactivation()
    {
        deactivate_plugins(JD_EASYDEBUGINFO_BASENAME);
    }
}
