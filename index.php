<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use WbApp\Command\WeatherBallonDataGeneratorCommand;

$weatherFileData = 'storage/files/weather-ballon-data.txt';

$application = new Application();

$application->add(new WeatherBallonDataGeneratorCommand($weatherFileData));

$application->run();