# Weather Observations
This is a small project for a challenge test

## Requirements
- PHP 7.0+
- pdo_sqlite

## Testing
```bash
composer test
```

## Running

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