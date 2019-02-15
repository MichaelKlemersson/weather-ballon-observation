<?php

namespace Test\WbApp\Unit;

use PHPUnit\Framework\TestCase;
use SplFileObject;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use WbApp\Command\WeatherBallonDataGeneratorCommand;

class GenerateDataCommandTest extends TestCase
{
    protected $testFile = __DIR__ . '/../../storage/files/weather-test-data.txt';

    public function testCanGenerateWeatherData(): void
    {
        // prepare
        $numberOfLines = 1;
        $command = new WeatherBallonDataGeneratorCommand($this->testFile);
        $input = new ArrayInput(['lines' => $numberOfLines]);
        $output = new NullOutput();

        // test
        $command->execute($input, $output);
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

        var_dump($path);

        $file = new SplFileObject($this->testFile, 'r');
        $file->seek($limitOfLines);

        return $file->key() + 1;
    }

    public function tearDown(): void
    {
        if (file_exists($this->testFile)) {
            unlink($this->testFile);
        }
    }
}
