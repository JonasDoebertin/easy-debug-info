<?php
namespace jdpowered\EasyDebugInfo;

use jdpowered\EasyDebugInfo\Managers\ReporterManager;
use Carbon\Carbon;

class Plugin {

    /**
     * The tools page slug
     *
     * @type string
     */
    protected $toolsPageSlug;

	/**
	 * Create an instance of the plugin
	 */
	public function __construct()
    {
		/* Register and load textdomain */
		load_plugin_textdomain('easydebuginfo', null, dirname(JD_EASYDEBUGINFO_BASENAME) . '/languages/');

		/* Add custom "Generate Report" link to plugin actions */
		add_action('plugin_action_links_' . JD_EASYDEBUGINFO_BASENAME, array($this, 'addPluginActionLink'));

		/* Register Options Page */
		add_action('admin_menu', array($this, 'registerToolsPage'));

		/* Register styles for options page */
		add_action('admin_enqueue_scripts', array($this, 'registerToolsPageScripts'));

		/* Register custom actions for ajax */
		add_action('wp_ajax_easydebuginfo_generate_report', array($this, 'ajaxGenerateReport'));
	}





    /**************************************************************************\
    *                             PLUGIN INTERNALS                             *
    \**************************************************************************/

	/**
	 * Register options and cron schedules
	 *
	 * Will be run through register_activation_hook()
	 */
	public static function activatePlugin()
	{
		/* Add option */
		add_option('easydebuginfo_latest_report', false);
	}

	/**
	 * Deregister options and cron schedules
	 *
	 * Will be run through register_deactivation_hook
	 */
	public static function deactivatePlugin()
    {
		/* Delete option */
		delete_option('easydebuginfo_latest_report');
	}

	public function addPluginActionLink($actionLinks)
    {
		$html = '<a href="#" title="' . __('Generate a debug report', 'easydebuginfo') . '">' . __('Generate Report', 'easydebuginfo') . '</a>';
		array_unshift($actionLinks, $html);
		return $actionLinks;
	}





    /**************************************************************************\
    *                               OPTIONS PAGE                               *
    \**************************************************************************/

    /**
     * Add the options page
     *
     * Will be run in "admin_menu" action
     */
    public function registerToolsPage()
    {
        $this->toolsPageSlug = add_management_page(
            __('Easy Debug Info', 'easydebuginfo'),
            __('Easy Debug Info', 'easydebuginfo'),
            'manage_options',
            'easydebuginfo',
            array($this, 'renderToolsPage')
        );
    }

    public function renderToolsPage()
    {
        $report = $this->getLatestReport();

        if(is_array($report))
            $report['created_at'] = Carbon::createFromTimestamp($report['created_at'])->diffForHumans();

        include JD_EASYDEBUGINFO_PATH . '/views/tools-page/tools-page.php';
    }

	/**
	 * Register & enqueue styles for the options page
	 *
	 * Will be run in "admin_enqueue_scripts" action
	 */
	public function registerToolsPageScripts($hook)
    {
        /*
            Register styles & scripts on our own page only
         */
        if($hook == $this->toolsPageSlug)
        {
            /*
                Register stylesheet
             */
            wp_register_style('easydebuginfo', JD_EASYDEBUGINFO_URL . '/assets/css/easydebuginfo.css', array(), JD_EASYDEBUGINFO_VERSION, 'screen');
            wp_enqueue_style('easydebuginfo');

            /*
                Register scripts
             */
            wp_register_script('easydebuginfo', JD_EASYDEBUGINFO_URL . '/assets/js/easydebuginfo.js', array('jquery'), JD_EASYDEBUGINFO_VERSION, true);
            wp_localize_script('easydebuginfo', 'EasyDebugInfo', array(
				'action' => array(
					'generate' => 'easydebuginfo_generate_report',
				),
				'nonce'  => array(
					'generate' => wp_create_nonce('easydebuginfo_generate_report'),
				),
			));
            wp_enqueue_script('easydebuginfo');
        }
	}





    /**************************************************************************\
    *                            MOST RECENT REPORT                            *
    \**************************************************************************/

    /**
     * Save a report to the database for later use
     *
     * @param array $report
     */
    protected function setLatestReport($report)
    {
        update_option('easydebuginfo_latest_report', array(
            'created_at' => time(),
            'report'     => $report,
        ));
    }

    /**
     * Get the latest generated report from the database
     *
     * @return array
     */
    protected function getLatestReport()
    {
        $report = get_option('easydebuginfo_latest_report', false);

        if( ! is_array($report))
            $report = false;

        return $report;
    }

    protected function renderReport($report)
    {
        return implode("\n", $report);
    }





    /**************************************************************************\
    *                                   AJAX                                   *
    \**************************************************************************/

	/**
	 *
	 *
	 * Will be run through an AJAX call in "wp_ajax_easydebuginfo_generate_report" action
	 */
	public function ajaxGenerateReport()
    {
		/*
		    Security check
        */
		if( ! check_ajax_referer('easydebuginfo_generate_report', 'nonce', false))
        {
			die;
		}

        /*
            Compose the report
         */
		$manager = new ReporterManager();
        $report = $manager->composeReport();

        /*
            Save as most recent report
         */
        $this->setLatestReport($report);

        /*
            Return report
         */
        echo $this->renderReport($report);
        die;
	}

}
