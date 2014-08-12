<?php
namespace jdpowered\EasyDebugInfo\Reporters;

use jdpowered\EasyDebugInfo\Contracts\Reporter;

class GeneralReporter extends BaseReporter implements Reporter {

    public function __construct()
    {

    }

    /**
     * Return the name of the reporter
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
     * @return string
     */
    public function getDescription()
    {
        return __('Some basic information about the WordPress setup', 'easydebuginfo');
    }

    /**
     * Do investigations and return report
     *
     * @return array
     */
    public function report()
    {
        $this->generalReport();

        return $this->lines;
    }

    /**
     * Investigate all available plugins
     *
     * Add a comprehensive list of all available plugins
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

        /*
            Active Theme
         */
        $this->addBlankLine();
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
     * Get various infos from the `get_bloginfo()` function
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
     * @return string
     */
    protected function getHomeUrl()
    {
        return esc_url(home_url());
    }

    /**
     * Get the slug of the parent theme
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
     * @return string
     */
    protected function getChildTheme()
    {
        return get_stylesheet();
    }

    /**
     * Checks whether the site uses a child theme or not
     *
     * @return bool
     */
    protected function isChildThemeInUse()
    {
        return is_child_theme();
    }

    protected function getChildThemeDirectoryUrl()
    {
        return esc_url(get_stylesheet_directory_uri());
    }

    protected function getParentThemeDirectoryUrl()
    {
        return esc_url(get_template_directory_uri());
    }

    protected function getThemeRootUrl()
    {
        return esc_url(get_theme_root_uri());
    }

    /**
     * Checks if WordPress is running in RTL (right to left script) mode
     *
     * @return bool
     */
    protected function isRtl()
    {
        return is_rtl();
    }

}
