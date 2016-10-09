# Devilbox

The ultimate Docker LAMP/LEMP Stack for local development.

## Specials

* All logs are available on your Host computer
* MySQL localhost socket is available in PHP container
* MySQL `127.0.0.1:3006` is available in PHP container
* Xdebug is included

## Run-time Matrix

You can choose any combination of the following docker images during run-time:

| Webserver | Database | PHP |
|-----------|----------|-----|
| Apache 2.2 | [MySQL 5.5](https://github.com/cytopia/docker-mysql-5.5) | [PHP 5.5](https://github.com/cytopia/docker-php-fpm-5.5) |
| [Apache 2.4](https://github.com/cytopia/docker-apache-2.4) | MySQL 5.6 | [PHP 5.6](https://github.com/cytopia/docker-php-fpm-5.6) |
| Nginx | MySQL 5.7  | [PHP 7.0](https://github.com/cytopia/docker-php-fpm-7.0) |
|       | MariaDB 5  | [PHP 7.1](https://github.com/cytopia/docker-php-fpm-7.1) |
|       | MariaDB 10 | |


## Start

1. Copy `env-example` to `.env`
2. Edit `.env`
3. `docker-compose up`
