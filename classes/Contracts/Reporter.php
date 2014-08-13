<?php
namespace jdpowered\EasyDebugInfo\Contracts;

interface Reporter {

    /**
     * Return the name of the reporter
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getName();

    /**
     * Return the description of the reporter
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getDescription();

    /**
     * Return the version of the reporter
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function getVersion();

    /**
     * Return the name of the plugin providing this report
     *
     * @since 1.0.0
     *
     * @return string
     */
    public function getProvider();

    /**
     * Return whether the reporter is provided by the core or by an extension
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function isCoreReporter();

    /**
     * Do investigations and return report
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function report();

}
