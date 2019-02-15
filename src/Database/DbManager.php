<?php

namespace WbApp\Database;

use PDO;

class DbManager
{
    protected $dbAdapter;

    private static $INSERT_QUERY = 'INSERT INTO weather_metrics VALUES :date, :location, :temperature, :observatory, :distance';

    public function __construct(PDO $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function __call(string $name, array $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func([$this, $name], ...$arguments);
        }

        return call_user_func([$this->dbAdapter, $name], ...$arguments);
    }

    public function insert(
        \DateTime $date,
        string $location,
        int $temperature,
        string $observatory,
        float $distance
    ): int
    {
        $statement = $this->dbAdapter->prepare(static::$INSERT_QUERY);
        $statement->execute([
            ':date' => $date,
            ':location' => $location,
            ':temperature' => $temperature,
            ':observatory' => $observatory,
            ':distance' => $distance,
        ]);
        $lastId = $this->dbAdapter->lastInsertId();

        $statement->closeCursor();

        return $lastId;
    }
}
