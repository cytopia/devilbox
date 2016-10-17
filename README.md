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
| [Nginx stable](https://github.com/cytopia/docker-nginx-stable) | MySQL 5.7  | [PHP 5.6](https://github.com/cytopia/docker-php-fpm-5.6) |
| Nginx mainline | MariaDB 5  | [PHP 7.0](https://github.com/cytopia/docker-php-fpm-7.0) |
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

1. Homepage with host / docker information
2. Virtual Host overview
3. Database overview
4. PHPinfo() page

### 1. Homepage with host / docker information

The homepage shows you the status of your current configured setup.

* which versions are used
* what directories are mounted
* what other settings have been set

![Intranet Home](https://raw.githubusercontent.com/cytopia/devilbox/master/doc/img/01_intranet_home.png "Intranet Home")


### 2. Virtual Host overview

This overview shows you all available virtual hosts and if they need additional configuration (on the host)

Virtual Hosts are considered valid if the following requirements are met (on the host system):

* `htdocs` folder/symlink exists in your project folder
* `/etc/hosts` has a valid DNS config for your host: `127.0.0.1  <project-folder>.loc`)

![Intranet vHost](https://raw.githubusercontent.com/cytopia/devilbox/master/doc/img/02_intranet_vhosts.png "Intranet Home")

### 3. Database overview

Shows you all the databases that are loaded

![Intranet DB](https://raw.githubusercontent.com/cytopia/devilbox/master/doc/img/03_intranet_databases.png "Intranet Home")
