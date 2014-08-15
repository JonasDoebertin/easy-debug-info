<?php
namespace jdpowered\EasyDebugInfo\Tools;

/**
 * Extend the original PEAR Console_Table class by an option
 * to return the table as array of lines instead of a string.
 *
 * @since 1.1.0
 * @see \Console_Table
 */
class ConsoleTable extends \Console_Table {

    /**
     * Returns the generated table as array of lines
     *
     * @since 1.1.0
     *
     * @return array
     */
    public function getTableLines()
    {
        /*
            Let parent class generate the table
         */
        $table = $this->getTable();

        /*
            Return array of lines
         */
        $lines = explode(PHP_EOL, $table);

        /*
            Remove last line if it's empty
         */
        $lastLine = end($lines);
        if(empty($lastLine))
        {
            array_pop($lines);
        }
        reset($lines);

        return $lines;
    }

}
