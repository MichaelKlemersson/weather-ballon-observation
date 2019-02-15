<?php

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use WbApp\WeatherDataFaker;

class WeatherDataFakerTest extends TestCase
{
    public function testGenerateFakeData()
    {
        // prepare
        $expectedSize = 4;
        $classUnderTest = new WeatherDataFaker();

        // test
        $result = $classUnderTest->generate();

        $data = explode(WeatherDataFaker::DATA_SEPARATOR, $result);
        $dateTime = new \DateTime($data[0]);
        $location = explode(',', $data[1]);
        $temperature = $data[2];
        $observatory = $data[3];

        // assert
        $this->assertCount($expectedSize, $data);
        $this->assertCount(2, $location);
        $this->assertTrue(intval($location[0]) >= 0);
        $this->assertTrue(intval($location[1]) >= 0);
        $this->assertIsNumeric($temperature);
        $this->assertArrayHasKey($observatory, WeatherDataFaker::OBSERVATORIES);
    }
}
