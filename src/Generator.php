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

    public function generate(Carbon $month)
    {
        $this->generateTitle($month);
        $this->generateWeekHeader();

        $weeksInMonth = $this->weeksInMonth($month);

        for ($week=0; $week<$weeksInMonth; $week++) {
            $firstDayOfWeek = $this->firstDayOfWeek($month, $week);

            $this->generateWeek($firstDayOfWeek, $month);
        }
    }

    /**
     * @param Carbon $firstDay
     * @param Carbon $month
     */
    private function generateWeek(Carbon $firstDay, Carbon $month)
    {
        $week = [];

        for ($i=0; $i<Carbon::DAYS_PER_WEEK; $i++) {
            $week[] = $this->formatDay($firstDay, $month);

            $firstDay->addDay();
        }

        $this->writer->writeWeek($week);
    }

    /**
     * @param Carbon $day
     * @param Carbon $month
     *
     * @return mixed
     */
    private function formatDay(Carbon $day, Carbon $month)
    {
        return $this->isInSameMonth($day, $month->month, $month->year)
            ? $this->writer->formatMonthDay($day->day)
            : $this->writer->formatOtherMonthDay($day->day);
    }

    /**
     * @param Carbon $weekDay
     * @param $month
     * @param $year
     * @return bool
     */
    private function isInSameMonth(Carbon $weekDay, $month, $year)
    {
        return ($month === $weekDay->month && $year === $weekDay->year);
    }

    private function weeksInMonth(Carbon $month)
    {
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        return ($end->weekOfYear - $start->weekOfYear) + 1;
    }

    private function firstDayOfWeek(Carbon $month, $week)
    {
        return $month->copy()->firstOfMonth()->startOfWeek()->addWeek($week);
    }

    private function generateWeekHeader()
    {
        $days = ['L','M','X','J','V','S','D'];

        foreach ($days as $day) {
            $header[] = $this->writer->formatDayLabel($day);
        }

        $this->writer->writeWeek($header);
    }

    private function generateTitle($month)
    {
        $this->writer->writeTitle($month->format('F Y'));
    }
}