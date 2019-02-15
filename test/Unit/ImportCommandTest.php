<?php

namespace Test\WbApp\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Input\ArrayInput;
use WbApp\Database\DbManager;
use WbApp\Command\ImportCommand;

class ImportCommandTest extends TestCase
{
    private $testFile = __DIR__ . '/../../storage/database/weather-test-db.sqlite';

    public function testImportDataToDataBase(): void
    {
        // prepare
        $file = __DIR__ . '/../../weather-test-data.txt';
        $dbManagerMock = $this->prophesize(DbManager::class);
        
        $dbManagerMock->insert(
            Argument::type(\DateTime::class),
            Argument::type('string'),
            Argument::type('int'),
            Argument::type('string'),
            Argument::type('float')
        )->willReturn(1);

        $classUnderTest = new ImportCommand($dbManagerMock->reveal());
        $input = new ArrayInput(['file' => $file]);
        $output = new NullOutput();

        // test
        $classUnderTest->execute($input, $output);

        // assert
        $dbManagerMock->insert(
            Argument::type(\DateTime::class),
            Argument::type('string'),
            Argument::type('int'),
            Argument::type('string'),
            Argument::type('float')
        )->shouldHaveBeenCalled();
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
