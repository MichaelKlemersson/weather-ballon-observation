<?php

namespace WbApp\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use WbApp\Database\DbManager;
use WbApp\Math;
use WbApp\WeatherDataFaker;

class GetObservationsCommand extends Command
{
    protected static $defaultName = 'app:observations';
    private $dbManager;

    public function __construct(DbManager $dbManager)
    {
        parent::__construct();

        $this->dbManager = $dbManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Retrieve weather statistics from observatories')
            ->addArgument('temperature_unit', InputArgument::REQUIRED, 'Temperature unit[celsius, fahrenheit, kelvin]')
            ->addArgument('point', InputArgument::REQUIRED, 'Point in cartesian plan. e.g. 0,0');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $temperatureUnit = $input->getArgument('temperature_unit');
        $point = $input->getArgument('point');

        $this->validateArguments($temperatureUnit, $point);

        $distance = Math::getDistanceBetweenTwoPoints([0, 0], explode(',', $point));

        $data = $this->dbManager->getObservations($temperatureUnit, $distance);
        
        $table = new Table($output);
        $table
            ->setHeaders(['observatory', 'temperature', 'temperature_unit', 'distance', 'distance_unit'])
            ->setRows($data);
        

        $table->render();
    }

    private function validateArguments(string $temperatureUnit, string $point)
    {
        $units = [
            WeatherDataFaker::TEMPERATURE_UNIT_CELSIUS,
            WeatherDataFaker::TEMPERATURE_UNIT_KELVIN,
            WeatherDataFaker::TEMPERATURE_UNIT_FAHRENHEIT,
        ];

        $point = explode(',', preg_replace(['/\s+/'], '', $point));

        $isValidPoint = count($point) === 2;

        if (!in_array($temperatureUnit, $units) || !$isValidPoint) {
            throw new \InvalidArgumentException(
                'Unit should be [celsius, kelvin, fahrenheit] and/or point should follow the notation 0,0'
            );
        }
    }
}
