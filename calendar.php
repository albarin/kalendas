<?php
require_once 'bootstrap.php';
require 'vendor/autoload.php';

use Carbon\Carbon;

$phpWord = new \PhpOffice\PhpWord\PhpWord();

$monthDay = Carbon::now();

$section = $phpWord->addSection(['orientation' => 'landscape']);
$section->addText(
    "{$monthDay->format('F')} {$monthDay->format('Y')}",
    ['size' => 16, 'bold' => true]
);


$startOfWeek = $monthDay->copy()->firstOfMonth()->startOfWeek();
$totalWeeks = $monthDay->copy()->endOfMonth()->weekOfMonth;

$table = $section->addTable([
    'width' => 100 * 50,
    'unit' => 'pct',
    'alignment' => 'center',
    'borderSize' => 10,
]);

addCalendarHeader($table);

for ($week=0; $week<$totalWeeks; $week++) {
    addWeekRow($table, $startOfWeek->copy()->addWeeks($week), $monthDay);
}

function addWeekRow($table, Carbon $startOfWeek, Carbon $month)
{
    $table->addRow();

    for ($c=0; $c<Carbon::DAYS_PER_WEEK; $c++) {
        $day = $startOfWeek->copy()->addDays($c);
        $mark = ($day->month === $month->month && $day->year === $month->year) ? '' : '*';
        $table->addCell(1000)->addText("$day->day $mark");
    }
}

writeDoc($phpWord);

function addCalendarHeader($table) {
    $cellStyle = ['bgColor' => 'a6a6a6'];
    $fontCellStyle = ['size' => 11, 'bold' => true, 'color' => 'FFFFFF'];
    $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    $table->addRow();
    for ($c=0; $c<Carbon::DAYS_PER_WEEK; $c++) {
        $table->addCell(1300, $cellStyle)->addText("{$weekDays[$c]}", $fontCellStyle, ['alignment' => 'center']);
    }
}

function writeDoc($phpWord)
{
    // Saving the document as OOXML file...
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save('helloWorld.docx');

    // Saving the document as ODF file...
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
    $objWriter->save('helloWorld.odt');
}
