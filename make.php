<?php

require 'vendor/autoload.php';

use Carbon\Carbon;
use Kalendas\Writers\ConsoleCalendarWriter;
use Kalendas\Writers\OoxmlCalendarWriter;
use Kalendas\Writers\OoxmlWriterConfiguration;
use Kalendas\Generator;
use PhpOffice\PhpWord\PhpWord;

$generator = new Generator(new ConsoleCalendarWriter());
$generator->generate(Carbon::today());

$generator = new Generator(
    new OoxmlCalendarWriter(
        new PhpWord(),
        (new OoxmlWriterConfiguration())
    )
);

//$generator->generate(Carbon::createFromFormat('m-Y', '02-2021'));
//$generator->generate(Carbon::today()->addMonth());
$generator->generate(Carbon::today());