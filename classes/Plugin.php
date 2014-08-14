<?php
namespace jdpowered\EasyDebugInfo;

use jdpowered\EasyDebugInfo\Managers\ReporterManager;
use Carbon\Carbon;

class Plugin {

    /**
     * The tools page slug
     *
     * @since 1.0.0
     *
     * @type string
     */
    protected $toolsPageSlug;

	/**
	 * Create an instance of the plugin
	 *
	 * @since 1.0.0
	 *
	 * @return jdpowered\EasyDebugInfo\Plugin
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

        add_action('admin_post_easydebuginfo_download_report', array($this, 'downloadReport'));
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

    /**
     * Add a "Generate Report" plugin action link
     *
     * @action plugin_action_links_easy-debug-info
     * @since 1.0.0
     *
     * @param  array $actionLinks
     * @return array
     */
	public function addPluginActionLink($actionLinks)
    {
		$html = '<a href="tools.php?page=easydebuginfo" title="' . __('Generate a debug report', 'easydebuginfo') . '">' . __('Generate Report', 'easydebuginfo') . '</a>';
		array_unshift($actionLinks, $html);
		return $actionLinks;
	}





    /**************************************************************************\
    *                                TOOLS PAGE                                *
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

    /**
     * Render the Easy Debug Info tools page
     *
     * This will fetch the latest report, create a human readble diff for its
     * creation date and load the pages view.
     *
     * @since 1.0.0
     */
    public function renderToolsPage()
    {
        /*
            Fetch latest report and prepare it for rendering
         */
        $report = $this->getLatestReport();
        if(is_array($report))
            $report['created_at'] = Carbon::createFromTimestamp($report['created_at'])->diffForHumans();

        /*
            Prepare "Download Latest Report" link target
         */
        $downloadReportLink = add_query_arg(array(
            'action' => 'easydebuginfo_download_report',
            'nonce'  => wp_create_nonce('easydebuginfo_download_report'),
        ), admin_url('admin-post.php'));

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

    /**
     * Convert a report from an array to a string
     *
     * @since 1.0.0
     *
     * @param  array $report
     * @return string
     */
    protected function renderReport($report)
    {
        return implode("\n", $report);
    }

    /**
     * admin-post.php entrypoint for downloading a full report as text file
     *
     * @action admin_post_easydebuginfo_download_report
     * @since 1.0.0
     */
    public function downloadReport()
    {
        /*
            Security check
         */
        $nonce = (isset($_REQUEST['nonce'])) ? $_REQUEST['nonce'] : '';
        if( ! wp_verify_nonce($nonce, 'easydebuginfo_download_report'))
        {
            wp_die(__('Security check failed. Please try again!', 'easydebuginfo'));
        }

        /*
            Fetch the latest report,
            render it and
            create the filename for the download accordingly
         */
        $report = $this->getLatestReport();
        if(is_array($report))
        {
            $response = $this->renderReport($report['report']);
            $filename = 'Report ' . Carbon::createFromTimestamp($report['created_at'])->format('Y-m-d G-i-s T');
        }
        else
        {
            $response = __('No report generated, yet!', 'easydebuginfo');
            $filename = 'no-reports';
        }

        /*
            Send out some headers to prevent caching of the report
         */
        $this->sendNoCacheHeaders();

        /*
            Send the actual file download headers and content
         */
        header('Content-Disposition: attachment; filename=' . $filename . '.txt', true, 200);
        header('Content-Type: text/plain');
        header('Content-Length: '. strlen($response));
        header('Content-Transfer-Encoding: binary');
        echo $response;

        die;
    }




    
    /**************************************************************************\
    *                                   HTTP                                   *
    \**************************************************************************/

    /**
     * Sends out some headers to prevent provider and client side caching
     *
     * @since 1.0.0
     */
    protected function sendNoCacheHeaders()
    {
        header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        header('Cache-Control: max-age=0, no-cache, no-store');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }





    /**************************************************************************\
    *                                   AJAX                                   *
    \**************************************************************************/

	/**
	 * AJAX entrypoint for generating a full report
	 *
	 * @action wp_ajax_easydebuginfo_generate_report
	 * @since 1.0.0
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
