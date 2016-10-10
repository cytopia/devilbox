# Devilbox

The ultimate Docker-based LAMP/LEMP Stack for your development host.

## Overview

* All logs are available on your Host computer
* MySQL localhost socket is available in PHP container
* MySQL `127.0.0.1:3006` is available in PHP container
* Xdebug is included

## Run-time Matrix

You can choose any combination of the following docker images during run-time:

<sub>This is under active development. Only the linkable items work at the moment.</sub>

| Webserver | Database | PHP |
|-----------|----------|-----|
| [Apache 2.2](https://github.com/cytopia/docker-apache-2.2) | [MySQL 5.5](https://github.com/cytopia/docker-mysql-5.5) | [PHP 5.4](https://github.com/cytopia/docker-php-fpm-5.4) |
| [Apache 2.4](https://github.com/cytopia/docker-apache-2.4) | MySQL 5.6 | [PHP 5.5](https://github.com/cytopia/docker-php-fpm-5.5) |
| Nginx | MySQL 5.7  | [PHP 5.6](https://github.com/cytopia/docker-php-fpm-5.6) |
|       | MariaDB 5  | [PHP 7.0](https://github.com/cytopia/docker-php-fpm-7.0) |
|       | MariaDB 10 | [PHP 7.1](https://github.com/cytopia/docker-php-fpm-7.1) |


<!--
| Webserver | Database | PHP | KeyVal NoSQL | KeyDoc NoSQL | Column NoSQL |
|-----------|----------|-----|--------------|--------------|--------------|
| Apache 2.2 | [MySQL 5.5](https://github.com/cytopia/docker-mysql-5.5) | [PHP 5.5](https://github.com/cytopia/docker-php-fpm-5.5) | Redis | MongoDB | Cassandra |
| [Apache 2.4](https://github.com/cytopia/docker-apache-2.4) | MySQL 5.6 | [PHP 5.6](https://github.com/cytopia/docker-php-fpm-5.6) | Memcached | Couchbase | |
| Nginx | MySQL 5.7  | [PHP 7.0](https://github.com/cytopia/docker-php-fpm-7.0) | | | |
| lighttpd | MariaDB 5  | [PHP 7.1](https://github.com/cytopia/docker-php-fpm-7.1) | | | |
|       | MariaDB 10 | HHVM | | | |
|       | PostgreSQL | | | | |

<sub>Not all docker categories need to be started.</sub>
-->

## Start

1. Copy `env-example` to `.env`
2. Edit `.env`
3. `docker-compose up`


## Pre-configured Intranet

![Intranet Home](https://raw.githubusercontent.com/cytopia/devilbox/master/doc/img/01_intranet_home.png "Intranet Home")
![Intranet vHost](https://raw.githubusercontent.com/cytopia/devilbox/master/doc/img/02_intranet_vhosts.png "Intranet Home")
![Intranet DB](https://raw.githubusercontent.com/cytopia/devilbox/master/doc/img/03_intranet_databases.png "Intranet Home")
