<?php
namespace jdpowered\EasyDebugInfo\Reporters;

use jdpowered\EasyDebugInfo\Contracts\Reporter;
use Carbon\Carbon;

class CronReporter extends BaseReporter implements Reporter {

    /**
     * Return the name of the reporter
     *
     * @since 1.2.0
     *
     * @return string
     */
    public function getName()
    {
        return 'Scheduled Events';
    }

    /**
     * Return the description of the reporter
     *
     * @since 1.2.0
     *
     * @return string
     */
    public function getDescription()
    {
        return __('A list of scheduled events.', 'easydebuginfo');
    }

    /**
     * Do investigations and return report
     *
     * @since 1.2.0
     *
     * @return array
     */
    public function report()
    {
        $this->intervalsReport();
        $this->eventsReport();
        return $this->lines;
    }

    /**
     * Generate intervals report
     *
     * @since 1.2.0
     */
    protected function intervalsReport()
    {
        $this->addHeadingLine('Registered Intervals');

        /*
            Prepare the table by adding headers
            and setting the border options
         */
        $table = $this->prepareTable(
            array(
                'Identifier',
                'Interval (s)',
                'Interval (readable)',
                'Description',
            ),
            array(
                -1, // left
                1,  // right
                1,  // right
            )
        );

        /*
            Populate table
         */
        foreach($this->getScheduleIntervals() as $slug => $info)
        {
            $table->addRow(array(
                $slug,
                $info['interval'],
                $this->intervalForHumans($info['interval']),
                $info['display'],
            ));
        }

        /*
            Merge generated table into report lines
         */
        $this->mergeLines($table->getTableLines());
    }

    /**
     * Generate events report
     *
     * @since 1.2.0
     */
    protected function eventsReport()
    {
        $this->addHeadingLine('Scheduled Events');

        /*
            Prepare the table by adding headers
            and setting the border options
         */
        $table = $this->prepareTable(
            array(
                'Execution (absolute)',
                'Execution (relative)',
                'Hook',
                'Interval',
                'Interval Identifier',
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
        foreach($this->getScheduledEvents() as $event)
        {
            $table->addRow(array(
                date('Y-m-d H:i:s', $event['timestamp']),
                Carbon::createFromTimestamp($event['timestamp'])->diffForHumans(),
                $event['hook'],
                $this->intervalForHumans($event['interval']),
                $event['schedule'],
            ));
        }

        /*
            Merge generated table into report lines
         */
        $this->mergeLines($table->getTableLines());

    }

    /**
     * Retrieve supported and filtered Cron recurrences
     *
     * @since 1.2.0
     * @see http://codex.wordpress.org/Function_Reference/wp_get_schedules
     *
     * @return array
     */
    protected function getScheduleIntervals()
    {
        return wp_get_schedules();
    }

    /**
     * Get all scheduled events
     *
     * @since 1.2.0
     * @see \_get_cron_array()
     *
     * @return bool|array
     */
    protected function getScheduledEvents()
    {
        /*
            Handle missing internal function
         */
        if( ! function_exists('_get_cron_array'))
            return false;

        /*
            Attention: Usage of internal function that may change in a future
            version. Check back often and ensure this works.
         */
        $data = _get_cron_array();

        /*
            Transform into an easier to handle representation
         */
        $events = array();
        foreach($data as $timestamp => $rawEvents) // loop through execution timestamps
        {
            foreach($rawEvents as $hook => $instances) // loop through all hook per timestamp
            {
                foreach($instances as $key => $instance) // loop through all instances per hook
                {
                    $events[] = array(
                        'timestamp' => $timestamp,
                        'hook'      => $hook,
                        'interval'  => $instance['interval'],
                        'schedule'  => $instance['schedule'],
                    );
                }
            }
        }

        return $events;
    }

    /**
     * Generate a human readable representation of an interval
     *
     * @since 1.2.0
     *
     * @param  int $seconds
     * @return string
     */
    protected function intervalForHumans($seconds)
    {
        /*
            Set up units
         */
        $units = array(
            'week'   => 7 * 24 * 3600,
            'day'    =>     24 * 3600,
            'hour'   =>          3600,
            'minute' =>            60,
            'second' =>             1,
        );

        /*
            Handle "0" values
         */
        if($seconds == 0)
            return '0 seconds';

        /*
            Calculate interval string
         */
        $interval = '';
        foreach($units as $unit => $divisor)
        {
            if($value = floor($seconds / $divisor))
            {
                $interval .= $value . ' ' . $unit;
                $interval .= (abs($value) > 1 ? 's' : '') . ', ';
                $seconds -= $value * $divisor;
            }
        }

        /*
            Remove trailing comma and return
         */
        return substr($interval, 0, -2);
    }

}
