<?php

namespace Test\Unit;

use Prophecy\Argument;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;
use WbApp\Database\DbManager;
use WbApp\Command\ImportCommand;
use WbApp\WeatherDataFaker;

class ImportCommandTest extends TestCase
{
    private $testFile = __DIR__ . '/../../storage/files/weather-test-data.txt';

    public function testImportDataToDataBase(): void
    {
        // prepare
        $dataGenerator = new WeatherDataFaker();
        touch($this->testFile);
        file_put_contents($this->testFile, $dataGenerator->generate() . PHP_EOL);
        $application = new Application();

        // mocks
        $dbManagerMock = $this->getMockBuilder(DbManager::class)->disableOriginalConstructor()->getMock();

        // assert
        $dbManagerMock->expects($this->once())->method('beginTransaction');
        $dbManagerMock->expects($this->once())->method('commit');

        $classUnderTest = new ImportCommand($dbManagerMock);
        $application->add($classUnderTest);

        $command = $application->find('app:import-data');
        $commandTester = new CommandTester($command);

        // test
        $commandTester->execute(['file' => $this->testFile]);
    }

    public function setUp(): void
    {
        parent::setUp();

        @unlink($this->testFile);

        touch($this->testFile);
    }

    public function tearDown(): void
    {
        parent::setUp();
        
        @unlink($this->testFile);
    }
}
