<?php
namespace jdpowered\EasyDebugInfo\Reporters;

abstract class BaseReporter {

    protected $lines = array();

    public function getVersion()
    {
        return JD_EASYDEBUGINFO_VERSION;
    }

    /**
     * Do investigations and return report
     *
     * @return string
     */
    abstract function report();

    protected function mergeLines($lines)
    {
        $this->lines = array_merge($this->lines, $lines);
    }

    protected function addLine($content)
    {
        $this->lines[] = $content;
    }

    protected function addLabeledLine($label, $content, $padding = 20)
    {
        $label = $label . ':';
        $this->lines[] = $this->pad($label, $padding, ' ') . $content;
    }

    protected function addLabeledBooleanLine($label, $boolean, $padding = 20)
    {
        $content = ($boolean) ? 'Yes' : 'No';
        $this->addLabeledLine($label, $content, $padding);
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
        $this->lines[] = $this->pad('', 100, $char);
    }

    protected function pad($input, $length, $char)
    {
        return str_pad($input, $length, $char, STR_PAD_RIGHT);
    }

}
