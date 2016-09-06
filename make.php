<?php

require 'vendor/autoload.php';

use Calendar\Generator;
use Calendar\Writers\ConsoleCalendarWriter;
use Carbon\Carbon;

$generator = new Generator(new ConsoleCalendarWriter());

$generator->generate(Carbon::today());
echo "\n\n";
$generator->generate(Carbon::today()->addMonth());