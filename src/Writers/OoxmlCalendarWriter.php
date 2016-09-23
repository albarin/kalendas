<?php

namespace Kalendas\Writers;

use Kalendas\CalendarWriter;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class OoxmlCalendarWriter implements CalendarWriter
{
    const SATURDAY = 5;
    const SUNDAY = 6;

    /**
     * @var PhpWord
     */
    private $writer;

    /**
     * @var Section
     */
    private $section;

    /**
     * @var Table
     */
    private $table;

    /**
     * @var OoxmlWriterConfiguration
     */
    private $config;

    /**
     * @var string
     */
    private $title;

    public function __construct(PhpWord $writer, OoxmlWriterConfiguration $config)
    {
        $this->writer = $writer;
        $this->config = $config;

        $this->section = $this->writer->addSection(['orientation' => 'landscape']);
    }

    /**
     * {@inheritdoc}
     */
    public function writeTitle($title)
    {
        $this->title = $title;
        $this->section->addText(
            "$title\n",
            $this->config->titleStyles(),
            ['align' => 'center']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function writeCalendar(array $weeks)
    {
        $this->setUpTable();

        $this->writeCalendarHeader(array_shift($weeks));
        $this->writeWeeks($weeks);

        $this->saveFile();
    }

    /**
     * @param array $week
     */
    private function writeCalendarHeader($week)
    {
        $this->writeWeek(
            $week,
            $this->config->headerHeight(),
            $this->config->headerStyles(),
            $this->config->headerStyles(),
            $this->config->headerStyles()
        );
    }

    /**
     * @param array $weeks
     */
    private function writeWeeks(array $weeks)
    {
        $numberOfWeeks = count($weeks);

        foreach ($weeks as $week) {
            $this->writeWeek(
                $week,
                $this->config->weekHeight($numberOfWeeks),
                $this->config->weekCellStyle(),
                $this->config->weekendCellStyle(),
                $this->config->weekDayStyle()
            );
        }
    }

    /**
     * @param array $week
     * @param int $height
     * @param array $weekdayStyle
     * @param array $weekendStyle
     * @param array $textStyle
     */
    private function writeWeek(array $week, $height, array $weekdayStyle, array $weekendStyle, array $textStyle)
    {
        $this->table->addRow($height);

        foreach ($week as $dayNumber => $day) {
            $cellStyle = $this->cellStyle($dayNumber, $weekdayStyle, $weekendStyle);

            $this->table
                ->addCell(1000, $cellStyle)
                ->addText($day, $textStyle, $textStyle);
        }
    }

    /**s
     * @param int $dayNumber
     * @param array $weekdayStyle
     * @param array $weekendStyle
     *
     * @return array
     */
    private function cellStyle($dayNumber, array $weekdayStyle, array $weekendStyle)
    {
        return in_array($dayNumber, [self::SATURDAY, self::SUNDAY])
            ? $weekendStyle
            : $weekdayStyle;
    }

    /**
     * {@inheritdoc}
     */
    public function formatDayLabel($day)
    {
        return $this->config->dayFormat()[$day];
    }

    /**
     * {@inheritdoc}
     */
    public function formatMonthDay($day)
    {
        return $day;
    }

    /**
     * {@inheritdoc}
     */
    public function formatOtherMonthDay($day)
    {
        return '';
    }

    private function setUpTable()
    {
        $this->table = $this->section->addTable([
            'borderSize' => 1,
            'width' => 100 * 50,
            'unit' => 'pct',
            'cellMarginTop' => 100,
            'cellMarginBottom' => 100
        ]);
    }

    private function saveFile()
    {
        $objWriter = IOFactory::createWriter($this->writer, 'Word2007');
        $objWriter->save($this->filename());
    }

    /**
     * @return string
     */
    public function filename()
    {
        $month = str_replace(' ', '-', $this->title);

        if ($this->config->isCustom()) {
            return "{$month}-custom.docx";
        }

        return "{$month}.docx";
    }
}