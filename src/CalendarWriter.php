<?php

namespace Calendar;

interface CalendarWriter
{
    /**
     * @param string $title
     */
    public function writeTitle($title);

    /**
     * @param array $week
     */
    public function writeWeek($week);

    /**
     * @param int $day
     */
    public function formatDayLabel($day);

    /**
     * @param int $day
     */
    public function formatMonthDay($day);

    /**
     * @param int $day
     */
    public function formatOtherMonthDay($day);
}