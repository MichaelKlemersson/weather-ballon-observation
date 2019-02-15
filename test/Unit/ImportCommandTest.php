<?php

namespace Test\WbApp\Unit;

use Prophecy\Argument;
use Symfony\Component\Console\Tester\CommandTester;
use WbApp\Database\DbManager;
use WbApp\Command\ImportCommand;
use WbApp\WeatherDataFaker;

class ImportCommandTest extends BaseTestCase
{
    private $testFile = __DIR__ . '/../../storage/files/weather-test-data.txt';

    public function testImportDataToDataBase(): void
    {
        // prepare
        $dataGenerator = new WeatherDataFaker();
        touch($this->testFile);
        file_put_contents($this->testFile, $dataGenerator->generate() . PHP_EOL);

        // mocks
        $dbManagerMock = $this->getMockBuilder(DbManager::class)->disableOriginalConstructor()->getMock();

        // assert
        $dbManagerMock->expects($this->once())->method('beginTransaction');
        $dbManagerMock->expects($this->once())->method('commit');

        $classUnderTest = new ImportCommand($dbManagerMock);
        $this->application->add($classUnderTest);

        $command = $this->application->find('app:import-data');
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
