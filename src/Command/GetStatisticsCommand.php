<?php

namespace WbApp\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use WbApp\Database\DbManager;

class GetStatisticsCommand extends Command
{
    protected static $defaultName = 'app:statistics';
    private $dbManager;

    public function __construct(DbManager $dbManager)
    {
        parent::__construct();

        $this->dbManager = $dbManager;
    }

    protected function configure()
    {
        $this->setDescription('Retrieve weather statistics from observatories');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $data = $this->dbManager->getStatistics();
        
        $table = new Table($output);
        $table
            ->setHeaders(
                [
                    'observatory',
                    'observations',
                    'min_temp',
                    'max_temp',
                    'mean_temp',
                    'temperature_unit',
                    'total_distance',
                    'distance_unit',
                ]
            )
            ->setRows($data);
        

        $table->render();
    }
}
