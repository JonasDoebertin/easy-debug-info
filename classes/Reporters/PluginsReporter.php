<?php
namespace jdpowered\EasyDebugInfo\Reporters;

use jdpowered\EasyDebugInfo\Contracts\Reporter;

class PluginsReporter extends BaseReporter implements Reporter {

    public function __construct()
    {

        /*
            Load wp-admin/includes/plugins.php if needed
         */
        if( ! function_exists('get_plugins') or ! function_exists('get_dropins'))
        {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        /*
            Clears the Plugins cache used by get_plugins()
         */
        if(function_exists('wp_clean_plugins_cache'))
        {
            wp_clean_plugins_cache();
        }


    }

    /**
     * Return the name of the reporter
     *
     * @return string
     */
    public function getName()
    {
        return __('Plugins Report', 'easydebuginfo');
    }

    /**
     * Return the description of the reporter
     *
     * @return string
     */
    public function getDescription()
    {
        return __('A detailed list of all available plugins, their version and statuses', 'easydebuginfo');
    }

    /**
     * Do investigations and return report
     *
     * @return array
     */
    public function report()
    {
        $this->pluginsReport();
        //$dropins = get_dropins();

        return $this->lines;
    }

    /**
     * Investigate all available plugins
     *
     * Add a comprehensive list of all available plugins
     */
    protected function pluginsReport()
    {
        foreach($this->getPlugins() as $plugin => $info)
        {
            $this->addHeadingLine($info['name']);
            $this->addLabeledLine('Version', $info['version'], 4);
            $this->addLabeledLine('Basefile', $plugin, 3);
            $this->addLabeledLine('Plugin URI', $info['pluginuri']);
            $this->addBlankLine();
        }
    }

    /**
     * Get all available plugins from WordPress
     *
     * Also makes sure, all indicies are lowercase
     *
     * @return array
     */
    protected function getPlugins()
    {
        $plugins = get_plugins();

        foreach($plugins as &$plugin)
        {
            $plugin = array_change_key_case($plugin);
        }

        return $plugins;
    }

}
