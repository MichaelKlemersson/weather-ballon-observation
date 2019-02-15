<?php

namespace WbApp\Database;

use PDO;

class DbManager
{
    protected $dbAdapter;

    private static $INSERT_QUERY = 'INSERT INTO weather_metrics(`date`, `location`, `temperature`, `temperature_unit`, `observatory`, `distance`, `distance_unit`) VALUES'
        . ' (:date, :location, :temperature, :temperature_unit, :observatory, :distance, :distance_unit)';

    private static $SELECT_STATISTICS = <<<SQL_STATISTICS
    SELECT
        wm.observatory,
        COUNT(wm.id) as observations,
        MIN(wm.temperature) AS min_temp,
        MAX(wm.temperature) AS max_temp,
        AVG(wm.temperature) AS mean_temp,
        wm.temperature_unit,
        SUM(wm.distance) AS total_distance,
        wm.distance_unit
    FROM weather_metrics wm
    GROUP BY wm.observatory;
SQL_STATISTICS;

    private static $SELECT_OBSERVATIONS = <<<SQL_FILTER
    SELECT
        wm.observatory,
        wm.temperature,
        wm.temperature_unit,
        wm.distance,
        wm.distance_unit
    FROM weather_metrics wm
    WHERE wm.temperature_unit = :unit AND wm.distance = :distance
    ORDER BY wm.id DESC;
SQL_FILTER;

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
        string $date,
        string $location,
        array $temperature,
        string $observatory,
        array $distance
    ): int
    {
        $statement = $this->dbAdapter->prepare(static::$INSERT_QUERY);
        $statement->execute([
            ':date' => $date,
            ':location' => $location,
            ':temperature' => $temperature[0],
            ':temperature_unit' => $temperature[1],
            ':observatory' => $observatory,
            ':distance' => $distance[0],
            ':distance_unit' => $distance[1],
        ]);
        $lastId = $this->dbAdapter->lastInsertId();

        $statement->closeCursor();

        return $lastId;
    }

    public function getStatistics(): array
    {
        $statement = $this->dbAdapter->prepare(static::$SELECT_STATISTICS);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $result;
    }

    public function getObservations(string $temperatureUnit, float $distance): array
    {
        $statement = $this->dbAdapter->prepare(static::$SELECT_OBSERVATIONS);
        $statement->execute([
            ':unit' => $temperatureUnit,
            ':distance' => $distance,
        ]);

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $result;
    }

    public function beginTransaction(): void
    {
        $this->dbAdapter->beginTransaction();
    }

    public function commit(): void
    {
        $this->dbAdapter->commit();
    }
}
