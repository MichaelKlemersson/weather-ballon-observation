<?php

namespace WbApp\Command;

use SplFileObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use WbApp\WeatherDataFaker;
use WbApp\Database\DbManager;
use WbApp\Math;
use WbApp\FileDataParser;

class ImportCommand extends Command
{
    protected static $defaultName = 'app:import-data';
    private $dbManager;
    private $filePath;
    private $batchSize = 1024;

    public function __construct(DbManager $dbManager)
    {
        parent::__construct();

        $this->dbManager = $dbManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import file contents')
            ->addArgument('file', InputArgument::REQUIRED, 'path/to/file that will be imported');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $file = new SplFileObject($input->getFirstArgument());
        $file->setFlags(SplFileObject::READ_AHEAD);

        while (!$file->eof()) {
            $data = $file->current();

            list($date, $location, $temperature, $observatory) = FileDataParser::parse($data);

            $distance = Math::getDistanceBetweenTwoPoints([0, 0], explode(',', $location));

            $this->log("Inserting data: {$data}");

            $this->dbManager->insert($date, $location, $temperature, $observatory, $distance);

            $file->next();
        }
        
        unset($file);
    }

    private function log($message)
    {
        echo PHP_EOL . $message . PHP_EOL;
    }
}
