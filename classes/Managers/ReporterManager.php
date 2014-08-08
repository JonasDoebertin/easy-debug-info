<?php
namespace jdpowered\EasyDebugInfo\Managers;

use jdpowered\EasyDebugInfo\Contracts\Reporter;

class ReporterManager {

    /**
     * Registered reporters
     *
     * @type array
     */
    protected $reporters = array();

    /**
     * The individual lines of the report
     *
     * @type array
     */
    protected $lines = array();

    /**
     * Register common reporters
     *
     * @return jdpowered\EasyDebugInfo\Managers\ReporterManager
     */
    public function __construct()
    {

        $this->registerReporter('jdpowered\EasyDebugInfo\Reporters\PluginsReporter');

    }

    /**************************************************************************\
    *                                  REPORT                                  *
    \**************************************************************************/

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

    protected function addReport(Reporter $reporter)
    {
        $this->addDividerLine('bold');
        $this->addBlankLine();
        $this->addHeadingLineWithVersion($reporter->getName(), $reporter->getVersion());
        $this->addBlankLine();
        $this->addDividerLine('normal');
        $this->addBlankLine();
        $this->mergeLines($reporter->report());
        $this->addBlankLine(2);
    }

    protected function addIntroduction()
    {
        $this->addLine('EASY DEBUG INFO REPORT');
        $this->addBlankLine();
        $this->addLabeledLine('Version', JD_EASYDEBUGINFO_VERSION, 4);
        $this->addLabeledLine('Created at', date('r'));
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

    protected function mergeLines($lines)
    {
        $this->lines = array_merge($this->lines, $lines);
    }

    protected function addLine($content)
    {
        $this->lines[] = $content;
    }

    protected function addLabeledLine($label, $content, $padding = 1)
    {
        $this->lines[] = $label . ':' . $this->pad(' ', $padding) . $content;
    }

    protected function addHeadingLine($heading)
    {
        $this->lines[] = "[{$heading}]";
    }

    protected function addHeadingLineWithVersion($heading, $version)
    {
        $this->lines[] = '[' . mb_strtoupper($heading) . '] v' . $version;
    }

    protected function addBlankLine($count = 1)
    {
        for($i = 0; $i < $count; $i++)
        {
            $this->lines[] = '';
        }
    }

    protected function addDividerLine($weight = 'normal')
    {
        $char = ($weight == 'bold') ? '=' : '-';
        $this->lines[] = $this->pad($char, 80);
    }

    protected function pad($char, $length)
    {
        return str_pad('', $length, $char, STR_PAD_RIGHT);
    }

}
