<?php
namespace jdpowered\EasyDebugInfo\Reporters;

use jdpowered\EasyDebugInfo\Contracts\Reporter;
use Rych\ByteSize\ByteSize;

class DatabaseReporter extends BaseReporter implements Reporter {

    /**
     * @type string
     * @since 1.1.0
     */
    const QUERY_TABLE_STATUS = 'SHOW TABLE STATUS;';

    /**
     * @type string
     * @since 1.1.0
     */
    const QUERY_VARIABLES = 'SHOW VARIABLES;';

    /**
     * @type string
     * @since 1.1.0
     */
    const QUERY_COUNT_ROWS = 'SELECT Count(*) FROM %s;';

    /**
     * This will hold various query results that will be prefetched from
     * within the constructor
     *
     * @type array
     * @since 1.1.0
     */
    protected $prefetched = array();

    /**
     * Create a new instance of this reporter
     *
     * Also prefetch some database queries. This way, they only have to be
     * exucuted once and the results are available right from the start.
     *
     * @since 1.1.0
     *
     * @return jdpowered\EasyDebugInfo\Reporters\DatabaseReporter
     */
    public function __construct()
    {
        /*
            Prefetch table status information
         */
        $this->prefetched['tableStatus'] = $this->fetchTableStatus();

        /*
            Prefetch internal variables
         */
        $this->prefetched['variables']   = $this->fetchVariables();

        /*
            Prefetch precise row numbers for each table
         */
        $this->prefetched['rowNumbers'] = array();
        foreach($this->prefetched['tableStatus'] as $table)
        {
            $this->prefetched['rowNumbers'][$table->Name] = $this->fetchRowNumber($table->Name);
        }
    }

    /**
     * Return the name of the reporter
     *
     * @since 1.1.0
     *
     * @return string
     */
    public function getName()
    {
        return 'Database';
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
        return __('Everything about the database', 'easydebuginfo');
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
        $this->generalReport();
        $this->tablesReport();
        return $this->lines;
    }

    /**
     * Show some general database metrics
     *
     * @since 1.1.0
     */
    protected function generalReport()
    {
        $this->addLabeledLine('MySQL Version', $this->getMySqlVersion());
        $this->addLabeledLine('Hostname', $this->getMySqlHostname());
        $this->addLabeledLine('Total Size', $this->getDatabaseSize());
    }

    /**
     * Show key metrics for each table
     *
     * @since 1.1.0
     */
    protected function tablesReport()
    {
        $this->addBlankLine();

        /*
            Prepare the table by adding headers
            and setting the border options
         */
        $table = $this->prepareTable(
            array(
                'Table Name',
                'Rows',
                'Avg. Row Size',
                'Index Size',
                'Total Size',
            ),
            array(
                -1, // left
                1,  // right
                1,  // right
                1,  // right
                1,  // right
            )
        );

        /*
            Populate table
         */
        foreach($this->prefetched['tableStatus'] as $databaseTable)
        {
            $table->addRow(array(
                $databaseTable->Name,
                $this->getRowCount($databaseTable),
                $this->getAvgRowSize($databaseTable),
                $this->getIndexSize($databaseTable),
                $this->getTableSize($databaseTable),
            ));
        }

        /*
            Merge generated table into report lines
         */
        $this->mergeLines($table->getTableLines());

    }

    /**
     * Execute "SHOW TABLE STATUS" and return result
     *
     * @global \wpdb $wpdb
     * @since 1.1.0
     *
     * @return array
     */
    protected function fetchTableStatus()
    {
        global $wpdb;

        /*
            Execute "SHOW TABLE STATUS" query
         */
        return $wpdb->get_results(self::QUERY_TABLE_STATUS, OBJECT);
    }

    /**
     * Execute "SHOW VARIABLES" and return result
     *
     * @global \wpdb $wpdb
     * @since 1.1.0
     *
     * @return array
     */
    protected function fetchVariables()
    {
        global $wpdb;

        /*
            Execute "SHOW VARIABLES" query
         */
        return $wpdb->get_results(self::QUERY_VARIABLES, OBJECT);
    }

    /**
     * Execute "SELECT Count(*) FROM $table" and return result
     *
     * @global \wpdb $wpdb
     * @since 1.1.0
     *
     * @param  string $table
     * @return int
     */
    protected function fetchRowNumber($table)
    {
        global $wpdb;

        $number = $wpdb->get_var(sprintf(self::QUERY_COUNT_ROWS, $table));
        return $number;
    }

    /**
     * Return a value from the prefetched MySQL variables
     *
     * @since 1.1.0
     *
     * @param  string $name
     * @return mixed
     */
    protected function findInVariables($name)
    {
        /*
            Try to find the variable and return its value
         */
        foreach($this->prefetched['variables'] as $variable)
        {
            if($variable->Variable_name == $name)
                return $variable->Value;
        }

        /*
            Handle missing variables
         */
        return false;
    }

    /**
     * Shortcut to find the MySQL server version in MySQL variables
     *
     * @since 1.1.0
     *
     * @return string
     */
    protected function getMySqlVersion()
    {
        return $this->findInVariables('version');
    }

    /**
     * Shortcut to find the MySQL server hostname in MySQL variables
     *
     * @since 1.1.0
     *
     * @return string
     */
    protected function getMySqlHostname()
    {
        return $this->findInVariables('hostname');
    }

    /**
     * Calculate and format the total database size
     *
     * Uses the prefetch "SHOW TABLE STATUS" results
     *
     * @since 1.1.0
     *
     * @return string
     */
    protected function getDatabaseSize()
    {
        $size = 0;
        foreach($this->prefetched['tableStatus'] as $table)
        {
            $size += $table->Data_length + $table->Index_length;
        }
        return ByteSize::formatBinary($size);
    }

    /**
     * Return the number of rows a table has.
     *
     * @param  string $table
     * @return int
     */
    protected function getRowCount($table)
    {
        return (isset($this->prefetched['rowNumbers'][$table->Name])) ? $this->prefetched['rowNumbers'][$table->Name] : 0;
    }

    /**
     * Calculate and format the avarage row size for a table
     *
     * @since 1.1.0
     *
     * @param  string $table
     * @return string
     */
    protected function getAvgRowSize($table)
    {
        $rows = $this->getRowCount($table);
        return ($rows == 0) ? ByteSize::formatBinary(0) : ByteSize::formatBinary($table->Data_length / $rows);
    }

    /**
     * Return the formatted index size for a table
     *
     * @since 1.1.0
     *
     * @param  string $table
     * @return string
     */
    protected function getIndexSize($table)
    {
        return ByteSize::formatBinary($table->Index_length);
    }

    /**
     * Calculate and format the total size of a table
     *
     * @since 1.1.0
     *
     * @param  string $table
     * @return string
     */
    protected function getTableSize($table)
    {
        return ByteSize::formatBinary($table->Data_length + $table->Index_length);
    }

}
