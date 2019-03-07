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
database: mysql
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

```bash
$ bin/console doctrine:database:create --env=dev
$ bin/console doctrine:schema:create --env=dev
$ bin/console doctrine:migrations:migrate --env=dev
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

## Production

A Production environment is available.

https://symfony.com/doc/current/deployment.html

## Licence

Human-Scoring is a MIT software see the licence in LICENCE.md