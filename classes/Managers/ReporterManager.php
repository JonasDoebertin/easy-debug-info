<?php
namespace jdpowered\EasyDebugInfo\Managers;

use jdpowered\EasyDebugInfo\Contracts\Reporter;

class ReporterManager {

    /**
     * Registered reporters
     *
     * @since 1.0.0
     *
     * @type array
     */
    protected $reporters = array();

    /**
     * The individual lines of the report
     *
     * @since 1.0.0
     *
     * @type array
     */
    protected $lines = array();

    /**
     * Register common reporters
     *
     * @since 1.0.0
     *
     * @return jdpowered\EasyDebugInfo\Managers\ReporterManager
     */
    public function __construct()
    {
        $this->registerReporter('jdpowered\EasyDebugInfo\Reporters\GeneralReporter');
        $this->registerReporter('jdpowered\EasyDebugInfo\Reporters\StatisticsReporter');
        $this->registerReporter('jdpowered\EasyDebugInfo\Reporters\ThemesReporter');
        $this->registerReporter('jdpowered\EasyDebugInfo\Reporters\PluginsReporter');
    }

    /**************************************************************************\
    *                                  REPORT                                  *
    \**************************************************************************/

    /**
     * Compose a full report
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function composeReport()
    {
        $this->addIntroduction();

        foreach($this->reporters as $reporterClass)
        {
            $reporter = new $reporterClass;
            $this->addReport($reporter);
        }

        return $this->lines;
    }

    /**
     * Print a report section heading and merge the report from the reporter
     *
     * @since 1.0.0
     *
     * @param jdpowered\EasyDebugInfo\Contracts\Reporter $reporter
     */
    protected function addReport(Reporter $reporter)
    {
        $this->addDividerLine('bold');
        $this->addBlankLine();
        $this->addLine(mb_strtoupper($reporter->getName()));
        $this->addLine('Provided by ' . $reporter->getProvider());
        $this->addBlankLine();
        $this->addLine($reporter->getDescription());

        if( ! $reporter->isCoreReporter())
        {
            $this->addBlankLine();
            $this->addLine('Version ' . $reporter->getVersion());
        }

        $this->addBlankLine();
        $this->addDividerLine('normal');
        $this->addBlankLine();
        $this->mergeLines($reporter->report());
        $this->addBlankLine(3);
    }

    /**
     * Add a general introduction for the report
     *
     * @since 1.0.0
     */
    protected function addIntroduction()
    {
        $this->addLine('EASY DEBUG INFO REPORT');
        $this->addBlankLine();
        $this->addLabeledLine('Plugin Version', JD_EASYDEBUGINFO_VERSION);
        $this->addLabeledLine('Report Creation', date('r'));
        $this->addBlankLine(3);
    }

    /**************************************************************************\
    *                           REPORTER MANAGEMENT                            *
    \**************************************************************************/

    /**
     * Register a reporter with the Reportermanager.
     *
     * The registered class has to implement the Reporter interface
     *
     * @param  string $classname
     * @return bool
     */
    public function registerReporter($classname)
    {
        if(class_exists($classname) and $this->implementsReporterInterface($classname))
        {
            $this->reporters[] = $classname;
            return true;
        }

        return false;
    }

    /**
     * Check if a class implements the reporter interface
     *
     * @param  string $classname
     * @return bool
     */
    protected function implementsReporterInterface($classname)
    {
        $interfaces = class_implements($classname);
        return in_array('jdpowered\EasyDebugInfo\Contracts\Reporter', $interfaces);
    }

    /**************************************************************************\
    *                               LINE HELPERS                               *
    *                                                                          *
    *                   Copied and pasted from BaseReporter                    *
    *                    We want modern PHP for WordPress!                     *
    \**************************************************************************/

    /**
     * Append an array of lines to own lines
     *
     * @since 1.0.0
     *
     * @param array $lines
     */
    protected function mergeLines($lines)
    {
        $this->lines = array_merge($this->lines, $lines);
    }

    /**
     * Add a string as a new line
     *
     * @since 1.0.0
     *
     * @param string $content
     */
    protected function addLine($content)
    {
        $this->lines[] = $content;
    }

    /**
     * Add a string as a new line and include a label
     *
     * The labels width may be padded to a specific amount
     *
     * @since 1.0.0
     *
     * @param string $label
     * @param string $content
     * @param int    $padding = 20
     */
    protected function addLabeledLine($label, $content, $padding = 20)
    {
        $label = $label . ':';
        $this->lines[] = $this->pad($label, $padding, ' ') . $content;
    }

    /**
     * Add a "yes/no" new line and include a label
     *
     * The labels width may be padded to a specific amount
     *
     * @since 1.0.0
     * @see addLabeledLine()
     *
     * @param string $label
     * @param bool   $boolean
     * @param int    $padding = 20
     */
    protected function addLabeledBooleanLine($label, $boolean, $padding = 20)
    {
        $content = ($boolean) ? 'Yes' : 'No';
        $this->addLabeledLine($label, $content, $padding);
    }

    /**
     * Add a heading line
     *
     * @since 1.0.0
     *
     * @param string $heading
     */
    protected function addHeadingLine($heading)
    {
        $this->lines[] = "[{$heading}]";
    }

    /**
     * Add a heading line with an included version number
     *
     * @since 1.0.0
     *
     * @param string $heading
     * @param string $version
     */
    protected function addHeadingLineWithVersion($heading, $version)
    {
        $this->lines[] = '[' . mb_strtoupper($heading) . '] v' . $version;
    }

    /**
     * Add a number of blank lines
     *
     * @since 1.0.0
     *
     * @param int $count = 1
     */
    protected function addBlankLine($count = 1)
    {
        for($i = 0; $i < $count; $i++)
        {
            $this->lines[] = '';
        }
    }

    /**
     * Add a divider line
     *
     * @since 1.0.0
     *
     * @param string $weight = 'normal'
     */
    protected function addDividerLine($weight = 'normal')
    {
        $char = ($weight == 'bold') ? '=' : '-';
        $this->lines[] = $this->pad('', 100, $char);
    }

    /**
     * Pad a string to a given length
     *
     * @since 1.0.0
     * @see str_pad()
     *
     * @param  string $input
     * @param  int    $length
     * @param  string $char
     *
     * @return string
     */
    protected function pad($input, $length, $char)
    {
        return str_pad($input, $length, $char, STR_PAD_RIGHT);
    }

}
