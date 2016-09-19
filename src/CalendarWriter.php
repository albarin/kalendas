<?php

namespace Calendar;

interface CalendarWriter
{
    /**
     * @param string $title
     */
    public function writeTitle($title);

    /**
     * @param array $weeks
     */
    public function writeCalendar(array $weeks);

    /**
     * @param int $day
     *
     * @return string
     */
    public function formatDayLabel($day);

    /**
     * @param int $day
     *
     * @return string
     */
    public function formatMonthDay($day);

    /**
     * @param int $day
     *
     * @return string
     */
    public function formatOtherMonthDay($day);
}