<?php

namespace WbApp\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WbApp\Database\DbManager;

class InitDatabaseCommand extends Command
{
    protected static $defaultName = 'app:init-db';

    public function __construct(DbManager $dbManager)
    {
        parent::__construct();

        $this->dbManager = $dbManager;
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->log("Creating database...");

        $this->dbManager->query(
        <<<SQL
        CREATE TABLE IF NOT EXISTS weather_metrics(
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            `date` TEXT NOT NULL,
            observatory TEXT NOT NULL,
            `location` TEXT NOT NULL,
            temperature INTEGER NOT NULL DEFAULT 0,
            temperature_unit TEXT NOT NULL,
            distance REAL NOT NULL DEFAULT 0,
            distance_unit REAL NOT NULL DEFAULT 0
        );
        CREATE INDEX IF NOT EXISTS idx_filter ON weather_metrics(temperature_unit, distance_unit);
        CREATE INDEX IF NOT EXISTS idx_statistics ON weather_metrics(temperature, distance, observatory);
SQL
        );

        $this->log('Database has been created');
    }

    private function log($message)
    {
        echo PHP_EOL . $message . PHP_EOL;
    }
}
