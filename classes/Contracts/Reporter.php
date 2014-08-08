<?php
namespace jdpowered\EasyDebugInfo\Contracts;

interface Reporter {

    /**
     * Return the name of the reporter
     *
     * @return string
     */
    public function getName();

    /**
     * Return the description of the reporter
     *
     * @return string
     */
    public function getDescription();

    /**
     * Return the version of the reporter
     *
     * @return bool
     */
    public function getVersion();

    /**
     * Do investigations and return report
     *
     * @return array
     */
    public function report();

}
