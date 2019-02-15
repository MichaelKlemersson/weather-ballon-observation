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

# Generating dummy data
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
# Entering into the container
docker container exec -it weather-app bash

# Initing the database
php index.php app:init-db

# Generating dummy data
php index.php app:generate-data

# Importing data
php index.php app:import-data storage/database/weather.sqlite

# Retrieving statistics
php index.php app:statistics

# Retrieving observations
php index.php app:observations
```