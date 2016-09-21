<?php

namespace Kalendas\Writers;

use Kalendas\CalendarWriter;
use Kalendas\WeekFormatter;

class ConsoleCalendarWriter implements CalendarWriter
{
    /**
     * @var string
     */
    private $calendar;

    /**
     * @var array
     */
    private $weeksDays;

    public function __construct()
    {
        $this->calendar = '';
        $this->weeksDays = WeekFormatter::oneLetter();
    }

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
    public function writeCalendar(array $weeks)
    {
        foreach ($weeks as $week) {
            $this->calendar .= implode('  ', $week) . "\n";
        }

        echo $this->calendar;
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
        return str_pad($this->weeksDays[$day], 2, ' ', STR_PAD_LEFT);
    }
}