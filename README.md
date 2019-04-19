# Human-Scoring

## Presentation

Human-Scoring is a symfony 4 software which provides to a scorer the possibility to establish a score for open-ended or constructed response items, e.g. items that contain an extended text interaction or certain PCIs.

#### Authentication

The scorer is authenticated as ROLE_USER.
the password is encoded in bcrypt.

## Installation

set the environment:

```
Server version: Apache/2.4.29 (Ubuntu)\
PHP version: 7.2
database: mysql, postgresql.
```

install the git repository:

```bash 
$ git clone https://github.com/sirpa44/human_scoring.git
```

install the dependency:

```bash 
$ composer install
```

set the database:

by default mysql config  :

.env config file

```bash
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
```

postgresql config:

config/doctrine.yaml

```yaml
doctrine:
dbal:
    driver: 'pdo_pgsql'
    charset: utf8
```

.env

```bash
DATABASE_URL=pgsql://db_user:db_password@127.0.0.1:5432/db_name
```

once the database is configured:

```bash
$ bin/console doctrine:database:create --env=dev
$ bin/console doctrine:schema:create --env=dev
```

## Unittest

set the database:

```bash
$ bin/console doctrine:database:create --env=test
$ bin/console doctrine:schema:create --env=test
```

Before running the tests please make sure you have PHP SQLite extension installed

```bash
$ ./bin/phpunit
```

## Import Scorer Command

Import Scorer Command put scorers in the database from a CSV file

### CSV File 

example:
```csv
username,password
scorer1,password1
scorer2,password2
``` 

### Command

Command Dry Run :
```bash
$ bin/console app:import-scorer <CsvFilePath>
```

### Options
 
Force :
```bash
--force
```
Import Scorer Command is a DryRun by default.
force option causes data ingestion to be applied into storage.

Overwrite :
```bash
--overwrite
```
Import Scorer Command doesn't change scorer data for a Scorer that already exist in database by default.
overwrite Scorer in database.

## Production

A Production environment is available.

https://symfony.com/doc/current/deployment.html

## Licence

Human-Scoring is a MIT software see the licence in LICENCE.md