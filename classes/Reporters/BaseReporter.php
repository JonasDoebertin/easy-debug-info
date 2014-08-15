<?php
namespace jdpowered\EasyDebugInfo\Reporters;

use jdpowered\EasyDebugInfo\Tools\ConsoleTable;

abstract class BaseReporter {

    /**
     * The individual lines of the report
     *
     * @since 1.0.0
     *
     * @type array
     */
    protected $lines = array();

    /**
     * Return the version of the reporter
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function getVersion()
    {
        return JD_EASYDEBUGINFO_VERSION;
    }

    /**
     * Return the name of the plugin providing this report
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getProvider()
    {
        return __('Easy Debug Info', 'easydebuginfo');
    }

    /**
     * Return whether the reporter is provided by the core or by an extension
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function isCoreReporter()
    {
        return true;
    }

    /**
     * Do investigations and return report
     *
     * @since 1.0.0
     *
     * @return string
     */
    abstract function report();

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

    protected function prepareTable($headers, $alignments)
    {
        $table = new ConsoleTable(
            \CONSOLE_TABLE_ALIGN_LEFT,
            \CONSOLE_TABLE_BORDER_ASCII,
            2,
            null,
            false
        );

        $table->setBorderVisibility(array(
            'top'    => false,
            'right'  => false,
            'bottom' => false,
            'left'   => false,
            'inner'  => true,
        ));

        $table->setHeaders($headers);

        for($i = 0; $i < count($alignments); $i++)
        {
            $table->setAlign($i, $alignments[$i]);
        }

        return $table;
    }

}
