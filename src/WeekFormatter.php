<?php

namespace Kalendas;

use Carbon\Carbon;

class WeekFormatter
{
    public static function oneLetter()
    {
        return self::formatLength(1);
    }

    public static function short()
    {
        return self::formatLength(3);
    }

    public static function full()
    {
        return self::formatLength();
    }

    private static function formatLength($length = null)
    {
        $days = [];
        $startOfWeek = Carbon::now()->startOfWeek();

        for ($i=0; $i<Carbon::DAYS_PER_WEEK; $i++) {
            $day = $startOfWeek->format('l');

            $days[] = is_null($length)
                ? $day
                : substr($day, 0, $length);

            $startOfWeek->addDay();
        }

        return $days;
    }
}