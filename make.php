<?php

require_once 'bootstrap.php';
require 'vendor/autoload.php';

use Calendar\Generator;
use Calendar\Writers\ConsoleCalendarWriter;
use Calendar\Writers\OoxmlCalendarWriter;
use Calendar\Writers\OoxmlWriterConfiguration;
use Carbon\Carbon;
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