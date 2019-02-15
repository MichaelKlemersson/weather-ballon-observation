<?php

namespace WbApp;

use DateTime;

class WeatherDataFaker
{
    const OBSERVATORIES = [
        'AU' => [
            'unit' => self::TEMPERATURE_UNIT_CELSIUS,
            'distance' => self::DISTANCE_TYPE_KILOMETERS
        ],
        'US' => [
            'unit' => self::TEMPERATURE_UNIT_FAHRENHEIT,
            'distance' => self::DISTANCE_TYPE_MILES
        ],
        'FR' => [
            'unit' => self::TEMPERATURE_UNIT_KELVIN,
            'distance' => self::DISTANCE_TYPE_METERS
        ],
        'BR' => [
            'unit' => self::TEMPERATURE_UNIT_CELSIUS,
            'distance' => self::DISTANCE_TYPE_METERS
        ],
        'JP' => [
            'unit' => self::TEMPERATURE_UNIT_FAHRENHEIT,
            'distance' => self::DISTANCE_TYPE_METERS
        ],
        'GER' => [
            'unit' => self::TEMPERATURE_UNIT_CELSIUS,
            'distance' => self::DISTANCE_TYPE_MILES
        ],
        'Others' => [
            'unit' => self::TEMPERATURE_UNIT_KELVIN,
            'distance' => self::DISTANCE_TYPE_KILOMETERS
        ],
    ];

    const TEMPERATURE_UNIT_CELSIUS = 'celsius';
    const TEMPERATURE_UNIT_FAHRENHEIT = 'fahrenheit';
    const TEMPERATURE_UNIT_KELVIN = 'kelvin';
    
    const DISTANCE_TYPE_KILOMETERS = 'km';
    const DISTANCE_TYPE_MILES = 'miles';
    const DISTANCE_TYPE_METERS = 'm';

    const DATE_FORMAT = 'Y-m-d\TH:i';
    const DATA_SEPARATOR = '|';

    public function generate(): string
    {
        $date = $this->getRandomDate()->format(self::DATE_FORMAT);
        $observatory = $this->getRandomObservatory();
        $location = implode(',', $this->getRandomLocation());
        $temperature = random_int(-2000, 2000);

        return $date . self::DATA_SEPARATOR
            . $location . self::DATA_SEPARATOR
            . $temperature . self::DATA_SEPARATOR
            . $observatory;
    }

    private function getRandomDate(): DateTime
    {
        $date = new DateTime();
        $year = random_int(1, 3);
        $month = random_int(1, 12);
        $day = random_int(1, $month !== 2 ? 30 : 28);

        $date->modify("-{$year} years");
        $date->modify("-{$month} months");
        $date->modify("-{$day} days");

        return $date;
    }

    private function getRandomLocation(): array
    {
        return [
            random_int(0, 20),
            random_int(0, 20),
        ];
    }

    private function getRandomObservatory(): string
    {
        $keys = array_keys(self::OBSERVATORIES);
        $randomIndex = random_int(0, count($keys) - 1);

        return $keys[$randomIndex];
    }
}
