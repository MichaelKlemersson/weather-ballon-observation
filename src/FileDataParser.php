<?php

namespace WbApp;

use DateTime;
use WbApp\WeatherDataFaker;

class FileDataParser
{
    public static function parse(string $data): array
    {
        $splittedData = explode(WeatherDataFaker::DATA_SEPARATOR, $data);
        $date = new DateTime($splittedData[0]);
        $location = $splittedData[1];
        $temperature = (int) $splittedData[2];
        $observatory = $splittedData[3];

        return [$date, $location, $temperature, $observatory];
    }
}
