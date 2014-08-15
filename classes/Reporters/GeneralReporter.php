<?php
namespace jdpowered\EasyDebugInfo\Reporters;

use jdpowered\EasyDebugInfo\Contracts\Reporter;

class GeneralReporter extends BaseReporter implements Reporter {

    /**
     * Return the name of the reporter
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getName()
    {
        return __('General Information', 'easydebuginfo');
    }

    /**
     * Return the description of the reporter
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getDescription()
    {
        return __('Some basic information about the WordPress setup', 'easydebuginfo');
    }

    /**
     * Do investigations and return report
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function report()
    {
        $this->generalReport();
        $this->constantsReport();

        return $this->lines;
    }

    /**
     * Investigate WordPress setup and generate report
     *
     * @since 1.0.0
     */
    protected function generalReport()
    {
        /*
            General WordPress Setup
         */
        $this->addHeadingLine('WordPress');
        $this->addLabeledLine('Version', $this->getInfo('version'));
        $this->addLabeledLine('HTML Type', $this->getInfo('html_type'));
        $this->addLabeledLine('Charset', $this->getInfo('charset'));
        $this->addLabeledLine('Language', $this->getInfo('language'));
        $this->addLabeledBooleanLine('RTL', $this->isRtl());
        $this->addLabeledLine('WordPress URL', $this->getSiteUrl());
        $this->addLabeledLine('Site URL', $this->getHomeUrl());
        $this->addLabeledLine('Base Directory', ABSPATH);

        /*
            Active Theme
         */
        $this->addHeadingLine('Active Theme');
        $this->addLabeledLine('Theme', $this->getParentTheme());
        $this->addLabeledLine('Theme Directory', $this->getParentThemeDirectoryUrl());

        if($this->isChildThemeInUse())
        {
            $this->addLabeledLine('Child Theme', $this->getChildTheme());
            $this->addLabeledLine('Child Theme Directory', $this->getChildThemeDirectoryUrl());
        }
    }

    /**
     * Investigate WordPress core constants
     *
     * @since 1.1.0
     */
    protected function constantsReport()
    {
        $this->addHeadingLine('Constants');

        /*
            Add all relevant constants with their values
         */
        foreach($this->getWordPressConstants() as $constant)
        {
            $this->addLabeledConstantLine($constant, 30);
        }
    }

    /**
     * Get various infos from the `get_bloginfo()` function
     *
     * @since 1.0.0
     *
     * @param  string $key
     * @return string
     */
    protected function getInfo($key)
    {
        return get_bloginfo($key, 'raw');
    }

    /**
     * Get the site url
     *
     * This is where the WordPress core files reside
     *
     * @since 1.0.0
     *
     * @return string
     */
    protected function getSiteUrl()
    {
        return esc_url(site_url());
    }

    /**
     * Get the sites home url
     *
     * This is the url the users sees when visiting the site
     *
     * @since 1.0.0
     *
     * @return string
     */
    protected function getHomeUrl()
    {
        return esc_url(home_url());
    }

    /**
     * Get the slug of the parent theme
     *
     * @since 1.0.0
     *
     * @return string
     */
    protected function getParentTheme()
    {
        return get_template();
    }

    /**
     * Get the slug of the child theme
     *
     * @since 1.0.0
     *
     * @return string
     */
    protected function getChildTheme()
    {
        return get_stylesheet();
    }

    /**
     * Checks whether the site uses a child theme or not
     *
     * @since 1.0.0
     *
     * @return bool
     */
    protected function isChildThemeInUse()
    {
        return is_child_theme();
    }

    /**
     * Get the child themes directory url
     *
     * @since 1.0.0
     *
     * @return string
     */
    protected function getChildThemeDirectoryUrl()
    {
        return esc_url(get_stylesheet_directory_uri());
    }

    /**
     * Get the parent themes directory url
     *
     * @since 1.0.0
     *
     * @return string
     */
    protected function getParentThemeDirectoryUrl()
    {
        return esc_url(get_template_directory_uri());
    }

    /**
     * Get the theme directory url
     *
     * @since 1.0.0
     *
     * @return string
     */
    protected function getThemeRootUrl()
    {
        return esc_url(get_theme_root_uri());
    }

    /**
     * Checks if WordPress is running in RTL (right to left script) mode
     *
     * @since 1.0.0
     *
     * @return bool
     */
    protected function isRtl()
    {
        return is_rtl();
    }

    /**
     * Return a list of all WordPress core constants
     *
     * @since 1.1.0
     *
     * @return array
     */
    protected function getWordpressConstants()
    {
        return array(
            'ABSPATH',
            'WP_SITEURL',
            'WP_HOME',
            'WP_CONTENT_DIR',
            'WP_CONTENT_URL',
            'WP_PLUGIN_DIR',
            'WP_PLUGIN_URL',
            'PLUGINDIR',
            'UPLOADS',
            'AUTOSAVE_INTERVAL',
            'WP_POST_REVISIONS',
            'COOKIE_DOMAIN',
            'WP_ALLOW_MULTISITE',
            'NOBLOGREDIRECT',
            'WP_DEBUG',
            'WP_DEBUG_LOG',
            'WP_DEBUG_DISPLAY',
            'SCRIPT_DEBUG',
            'SAVEQUERIES',
            'CONCATENATE_SCRIPTS',
            'WP_DEBUG_DISPLAY',
            'WP_MEMORY_LIMIT',
            'WP_MAX_MEMORY_LIMIT',
            'WP_CACHE',
            'CUSTOM_USER_TABLE',
            'CUSTOM_USER_META_TABLE',
            'WPLANG',
            'WP_LANG_DIR',
            'FS_CHMOD_DIR',
            'FS_CHMOD_FILE',
            'FS_METHOD',
            'FTP_BASE',
            'FTP_CONTENT_DIR',
            'FTP_PLUGIN_DIR',
            'FTP_PUBKEY',
            'FTP_PRIKEY',
            'FTP_HOST',
            'FTP_SSL',
            'ALTERNATE_WP_CRON',
            'DISABLE_WP_CRON',
            'WP_CRON_LOCK_TIMEOUT',
            'COOKIEPATH',
            'SITECOOKIEPATH',
            'ADMIN_COOKIE_PATH',
            'PLUGINS_COOKIE_PATH',
            'TEMPLATEPATH',
            'STYLESHEETPATH',
            'EMPTY_TRASH_DAYS',
            'WP_ALLOW_REPAIR',
            'DO_NOT_UPGRADE_GLOBAL_TABLES',
            'DISALLOW_FILE_EDIT',
            'DISALLOW_FILE_MODS',
            'FORCE_SSL_LOGIN',
            'FORCE_SSL_ADMIN',
            'WP_HTTP_BLOCK_EXTERNAL',
            'WP_ACCESSIBLE_HOSTS',
            'AUTOMATIC_UPDATER_DISABLED',
            'WP_AUTO_UPDATE_CORE',
        );
    }

}
