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
        return __('Plugins', 'easydebuginfo');
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
            $this->addLabeledLine('Version', $info['version']);
            $this->addLabeledLine('Basefile', $plugin);
            $this->addLabeledBooleanLine('Activated', $this->isActivePlugin($plugin));
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

    /**
     * Check whether the plugin is activated.
     *
     * @param  string $plugin
     * @return bool
     */
    protected function isActivePlugin($plugin)
    {
        return is_plugin_active($plugin);
    }

}
