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
