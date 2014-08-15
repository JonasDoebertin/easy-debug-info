<?php
namespace jdpowered\EasyDebugInfo\Reporters;

use jdpowered\EasyDebugInfo\Contracts\Reporter;
use Rych\ByteSize\ByteSize;

class EnvironmentReporter extends BaseReporter implements Reporter {

    /**
     * Return the name of the reporter
     *
     * @since 1.1.0
     *
     * @return string
     */
    public function getName()
    {
        return 'Server Environment';
    }

    /**
     * Return the description of the reporter
     *
     * @since 1.1.0
     *
     * @return string
     */
    public function getDescription()
    {
        return __('Everything about the server software', 'easydebuginfo');
    }

    /**
     * Do investigations and return report
     *
     * @since 1.1.0
     *
     * @return array
     */
    public function report()
    {
        $this->serverReport();
        $this->phpReport();
        $this->mySqlReport();
        return $this->lines;
    }

    /**
     * Show some server software metrics
     *
     * @since 1.1.0
     */
    protected function serverReport()
    {
        $this->addHeadingLine('Server');
        $this->addLabeledLine('Software', $_SERVER['SERVER_SOFTWARE']);
        $this->addLabeledLine('Protocol', $_SERVER['SERVER_PROTOCOL']);
        $this->addLabeledLine('Interface', $_SERVER['GATEWAY_INTERFACE']);
        $this->addLabeledLine('Document Root', $_SERVER['DOCUMENT_ROOT']);
    }

    /**
     * Show some PHP metrics
     *
     * @since 1.1.0
     */
    protected function phpReport()
    {
        $this->addHeadingLine('PHP');
        $this->addLabeledLine('Version', phpversion());
        $this->addLabeledLine('System', PHP_OS);
        $this->addLabeledLine('System', php_uname());
        $this->addLabeledLine('Interface', PHP_SAPI);
        $this->addLabeledLine('Include Path', get_include_path());
        $this->addBlankLine();

        foreach($this->getPhpExtensions() as $extension)
        {
            $this->addLabeledLine('Extension', $extension);
        }
    }

    /**
     * Generate MySQL report
     *
     * @since 1.1.0
     */
    protected function mySqlReport()
    {
        $this->addHeadingLine('MySQL');
        $this->addLabeledLine('Version', $this->getMySqlVersion());
        $this->addBlankLine();
        $this->addLine('More database statistics can be found within the "Database" section of this report.');
    }

    /**
     * Generate a list of all loaded PHP extensions
     *
     * @since 1.1.0
     *
     * @return array
     */
    protected function getPhpExtensions()
    {
        $extensions = get_loaded_extensions();
        natcasesort($extensions);

        foreach($extensions as &$extension)
        {
            $extension = $extension . ' ' . phpversion($extension);
        }

        return $extensions;
    }

    /**
     * Shortcut for retrieving the MySQL server version
     *
     * @since 1.1.0
     * @global \wpdb $wpdb
     *
     * @return string
     */
    protected function getMySqlVersion()
    {
        global $wpdb;
        return $wpdb->db_version();
    }

}
