<?php

namespace Calendar\Writers;

use Calendar\CalendarWriter;

class ConsoleCalendarWriter implements CalendarWriter
{
    /**
     * {@inheritdoc}
     */
    public function writeTitle($title)
    {
        echo str_pad($title, 27-count($title), '-', STR_PAD_BOTH) . "\n";
    }

    /**
     * {@inheritdoc}
     */
    public function writeWeek($week)
    {
        echo implode('  ', $week) . "\n";
    }

    /**
     * {@inheritdoc}
     */
    public function formatMonthDay($day)
    {
        return str_pad($day, 2, ' ', STR_PAD_LEFT);
    }

    /**
     * {@inheritdoc}
     */
    public function formatOtherMonthDay($day)
    {
        return str_pad('*', 2, ' ', STR_PAD_LEFT);
    }

    /**
     * {@inheritdoc}
     */
    public function formatDayLabel($day)
    {
        return str_pad($day, 2, ' ', STR_PAD_LEFT);
    }
}