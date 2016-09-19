<?php

namespace Calendar;

use Carbon\Carbon;

class Generator
{
    private $writer;

    public function __construct(CalendarWriter $writer)
    {
        $this->writer = $writer;
    }

    /**
     * @param Carbon $month
     */
    public function generate(Carbon $month)
    {
        $this->generateTitle($month);
        $this->generateCalendar($month);
    }

    /**
     * @param Carbon $month
     */
    private function generateTitle(Carbon $month)
    {
        $this->writer->writeTitle($month->format('F Y'));
    }

    /**
     * @param Carbon $month
     */
    private function generateCalendar(Carbon $month)
    {
        $this->writer->writeCalendar(
            $this->generateWeeks($month)
        );
    }

    /**
     * @param Carbon $month
     * @return array
     */
    private function generateWeeks(Carbon $month)
    {
        $weeks[] = $this->generateWeekHeader();
        $weeksInMonth = $this->weeksInMonth($month);

        for ($week=0; $week<$weeksInMonth; $week++) {
            $firstDayOfWeek = $this->firstDayOfWeek($month, $week);

            $weeks[] = $this->generateWeek($firstDayOfWeek, $month);
        }

        return $weeks;
    }

    /**
     * @return array
     */
    private function generateWeekHeader()
    {
        $week = [];
        for ($day=0; $day<7; $day++) {
            $week[] = $this->writer->formatDayLabel($day);
        }

        return $week;
    }

    /**
     * @param Carbon $firstDay
     * @param Carbon $month
     *
     * @return array
     */
    private function generateWeek(Carbon $firstDay, Carbon $month)
    {
        $week = [];

        for ($i=0; $i<Carbon::DAYS_PER_WEEK; $i++) {
            $week[] = $this->formatDay($firstDay, $month);

            $firstDay->addDay();
        }

        return $week;
    }

    /**
     * @param Carbon $day
     * @param Carbon $month
     *
     * @return string
     */
    private function formatDay(Carbon $day, Carbon $month)
    {
        return $this->isInSameMonth($day, $month->month, $month->year)
            ? $this->writer->formatMonthDay($day->day)
            : $this->writer->formatOtherMonthDay($day->day);
    }

    /**
     * @param Carbon $weekDay
     * @param int $month
     * @param int $year
     *
     * @return bool
     */
    private function isInSameMonth(Carbon $weekDay, $month, $year)
    {
        return ($month === $weekDay->month && $year === $weekDay->year);
    }

    /**
     * @param Carbon $month
     *
     * @return int
     */
    private function weeksInMonth(Carbon $month)
    {
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        return ($end->weekOfYear - $start->weekOfYear) + 1;
    }

    /**
     * @param Carbon $month
     * @param int $week
     *
     * @return Carbon
     */
    private function firstDayOfWeek(Carbon $month, $week)
    {
        return $month->copy()->firstOfMonth()->startOfWeek()->addWeek($week);
    }
}