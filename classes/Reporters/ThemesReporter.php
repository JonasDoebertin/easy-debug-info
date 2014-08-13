<?php
namespace jdpowered\EasyDebugInfo\Reporters;

use jdpowered\EasyDebugInfo\Contracts\Reporter;

class ThemesReporter extends BaseReporter implements Reporter {

    /**
     * Will hold \WP_Theme objects for all available themes
     *
     * @since 1.0.0
     *
     * @type array
     */
    protected $themes = array();

    /**
     * Create new instance of the reporter
     *
     * @since 1.0.0
     *
     * @return jdpowered\EasyDebugInfo\Reporters\ThemesReporter
     */
    public function __construct()
    {
        /*
            Load list of available themes to use
            throughout the report generation
         */
        $this->themes = $this->getThemes();
    }

    /**
     * Return the name of the reporter
     *
     * @return string
     */
    public function getName()
    {
        return __('Themes', 'easydebuginfo');
    }

    /**
     * Return the description of the reporter
     *
     * @return string
     */
    public function getDescription()
    {
        return __('A detailed list of all available themes', 'easydebuginfo');
    }

    /**
     * Do investigations and return report
     *
     * @return array
     */
    public function report()
    {
        $this->metaReport();
        $this->themesListReport();

        return $this->lines;
    }

    protected function metaReport()
    {
        $this->addLabeledLine('Root Directory', $this->getThemeRootDirectory());
        $this->addLabeledLine('Root URL', $this->getThemeRootDirectoryUrl());
        $this->addBlankLine();
        $this->addDividerLine();
    }

    /**
     * Investigate all available themes
     *
     * Add a comprehensive list of all available themes
     */
    protected function themesListReport()
    {
        foreach($this->themes as $theme)
        {
            $this->addBlankLine();
            $this->addHeadingLine($theme->Name);
            $this->addLabeledLine('Version', $theme->get('Version'));
            $this->addLabeledLine('Author', $theme->get('Author'));
            $this->addLabeledLine('Website', $theme->get('ThemeURI'));
            $this->addLabeledLine('Textdomain', $theme->get('TextDomain'));

            $this->addLabeledLine('Theme Slug', $theme->get_stylesheet());
            $this->addLabeledLine('Theme Directory', $theme->get_stylesheet_directory());
            $this->addLabeledLine('Theme URL', $theme->get_stylesheet_directory_uri());

            $this->addLabeledBooleanLine('Child Theme', $this->isChildTheme($theme));

            if($this->isChildTheme($theme))
            {
                $this->addLabeledLine('Parent Theme', $theme->get('Template'));
            }
        }
    }

    protected function getThemeRootDirectory()
    {
        return get_theme_root();
    }

    protected function getThemeRootDirectoryUrl()
    {
        return get_theme_root_uri();
    }

    /**
     * Get all available plugins from WordPress
     *
     * Also makes sure, all indicies are lowercase
     *
     * @return array
     */
    protected function getThemes()
    {
        $themes = wp_get_themes();

        foreach($themes as &$theme)
        {
            $theme->Author = wp_strip_all_tags($theme->Author);
        }

        return $themes;
    }

    /**
     * Check whether the plugin is activated.
     *
     * @param  string $plugin
     * @return bool
     */
    protected function isChildTheme($theme)
    {
        $template = $theme->get('Template');
        return ! empty($template);
    }

}
