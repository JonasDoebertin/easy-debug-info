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

}
