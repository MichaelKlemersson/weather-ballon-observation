<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use WbApp\Command\WeatherBallonDataGeneratorCommand;
use WbApp\Command\ImportCommand;
use WbApp\Command\InitDatabaseCommand;
use WbApp\Command\GetStatisticsCommand;
use WbApp\Command\GetObservationsCommand;
use WbApp\Database\DbManager;
use WbApp\WeatherDataFaker;

$weatherFileData = 'storage/files/weather-ballon-data.txt';
$weatherDbLocation = __DIR__ . DIRECTORY_SEPARATOR . 'storage/database/weather.sqlite';

$dbAdapter = new PDO("sqlite:{$weatherDbLocation}");
$dbAdapter->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbManager = new DbManager($dbAdapter);

$dataGenerator = new WeatherDataFaker();

$application = new Application();

$application->add(new WeatherBallonDataGeneratorCommand($weatherFileData, $dataGenerator));
$application->add(new ImportCommand($dbManager));
$application->add(new InitDatabaseCommand($dbManager));
$application->add(new GetStatisticsCommand($dbManager));
$application->add(new GetObservationsCommand($dbManager));

$application->run();