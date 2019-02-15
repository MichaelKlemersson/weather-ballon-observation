<?php

namespace WbApp\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use WbApp\WeatherDataFaker;

class WeatherBallonDataGeneratorCommand extends Command
{
    protected static $defaultName = 'app:generate-data';
    private $weatherDataFaker;
    private $filePath;

    public function __construct(string $filePath, WeatherDataFaker $faker)
    {
        parent::__construct();

        $this->filePath = $filePath;
        $this->weatherDataFaker = $faker;
    }

    protected function configure()
    {
        $this
            ->setDescription('Generates a new file')
            ->setHelp('Generates a file with random weather data, the file will be located on storage/files/')
            ->addArgument('lines', InputArgument::REQUIRED, 'How many lines of data should be generated');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->log("Creating file");

        $linesToGenerate = intval($input->getArgument('lines'));

        $file = fopen($this->filePath, 'w+');

        for ($i = 0; $i < $linesToGenerate; $i++) {
            $data = $this->weatherDataFaker->generate();

            $this->log("Writing data: {$data}");
            
            fwrite($file, $data . PHP_EOL);
        }

        fclose($file);

        $this->log("Finished");
    }

    private function log($message)
    {
        echo PHP_EOL . $message . PHP_EOL;
    }
}
