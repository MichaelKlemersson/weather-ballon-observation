[![Build Status](https://travis-ci.com/MichaelKlemersson/weather-ballon-observation.svg?branch=master)](https://travis-ci.com/MichaelKlemersson/weather-ballon-observation)

# Weather Observations
This is a small project for a challenge test

## Requirements
- PHP 7.0+
- pdo_sqlite
- composer

## Testing
```bash
composer test
```

## Running

> Make sure that you have all the dependencies installed
    composer install

### CLI

```bash
# Initing the database
php index.php app:init-db

# Generating dummy data, this data will be stored in storage/files/ folder
php index.php app:generate-data

# Importing data
php index.php app:import-data storage/database/weather.sqlite

# Retrieving statistics
php index.php app:statistics

# Retrieving observations
php index.php app:observations
```

### Docker
```bash
# Start the container
docker-compose up -d

# Entering inside the container
docker container exec -it weather-app bash

# Initing the database
php index.php app:init-db

# Generating dummy data, this data will be stored in storage/files/ folder
php index.php app:generate-data

# Importing data
php index.php app:import-data storage/database/weather.sqlite

# Retrieving statistics
php index.php app:statistics

# Retrieving observations
php index.php app:observations
```

## Road map
- [] Add tests for not reliable data