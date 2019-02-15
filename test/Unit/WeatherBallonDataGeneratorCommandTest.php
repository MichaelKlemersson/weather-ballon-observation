<?php

namespace Test\WbApp\Unit;

use SplFileObject;
use Symfony\Component\Console\Tester\CommandTester;
use WbApp\Command\WeatherBallonDataGeneratorCommand;
use WbApp\WeatherDataFaker;

class WeatherBallonDataGeneratorCommandTest extends BaseTestCase
{
    private $testFile = __DIR__ . '/../../storage/files/weather-test-data.txt';

    public function testCanGenerateWeatherData(): void
    {
        // prepare
        $numberOfLines = 1;
        $classUnderTest = new WeatherBallonDataGeneratorCommand($this->testFile, new WeatherDataFaker());
        $this->application->add($classUnderTest);

        $command = $this->application->find('app:generate-data');
        $commandTester = new CommandTester($command);

        // test
        $commandTester->execute(['lines' => $numberOfLines]);
        $lineCounter = $this->getNumberOfLines($numberOfLines + 1);

        // assert
        $this->assertEquals($numberOfLines, $lineCounter);
        $this->assertTrue(file_exists($this->testFile));
    }

    private function getNumberOfLines(int $limitOfLines): int
    {
        if (!file_exists($this->testFile)) {
            return 0;
        }

        $path = realpath($this->testFile);

        $file = new SplFileObject($this->testFile, 'r');
        $file->seek($limitOfLines);

        return $file->key();
    }

    public function setUp(): void
    {
        parent::setUp();

        if (file_exists($this->testFile)) {
            unlink($this->testFile);
        }
    }

    public function tearDown(): void
    {
        parent::setUp();
        
        if (file_exists($this->testFile)) {
            unlink($this->testFile);
        }
    }
}
