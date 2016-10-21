# ![Devilbox](https://raw.githubusercontent.com/cytopia/devilbox/master/.devilbox/www/htdocs/assets/img/devilbox_80.png) Devilbox

The devilbox is a `docker-compose` setup for your prefered LAMP/LEMP stack.

It is basically a pre-configured set of the below listed docker containers including a custom intranet which gives you an overview about your projects and
 keeps track if they have been setup correctly.

<sub>There is no need to setup Virtual Hosts for new projects, all provided webservers are pre-configured with mass-virtual hosts and are automatically linked to a PHP-FPM server of your prefered version.</sub>

## Easy usage

You are up and running in three simple steps:

```bash
# Copy the example configuration file
$ cp env-example .env

# Edit your configuration
$ vim .env

# Start the dockers
$ docker-compose up
```

## Run-time Matrix

Select your prefered setup.

No need to install and configure different versions locally. Simply choose your required LAMP/LEMP stack combination during startup and it is up and running instantly.

| Webserver | Database | PHP |
|-----------|----------|-----|
| [![Build Status](https://travis-ci.org/cytopia/docker-apache-2.2.svg?branch=master)](https://travis-ci.org/cytopia/docker-apache-2.2) [Apache 2.2](https://github.com/cytopia/docker-apache-2.2) | [MySQL 5.5](https://github.com/cytopia/docker-mysql-5.5) | [PHP 5.4](https://github.com/cytopia/docker-php-fpm-5.4) |
| [![Build Status](https://travis-ci.org/cytopia/docker-apache-2.4.svg?branch=master)](https://travis-ci.org/cytopia/docker-apache-2.4) [Apache 2.4](https://github.com/cytopia/docker-apache-2.4) | MySQL 5.6 | [PHP 5.5](https://github.com/cytopia/docker-php-fpm-5.5) |
| [![Build Status](https://travis-ci.org/cytopia/docker-nginx-stable.svg?branch=master)](https://travis-ci.org/cytopia/docker-nginx-stable) [Nginx stable](https://github.com/cytopia/docker-nginx-stable) | MySQL 5.7  | [PHP 5.6](https://github.com/cytopia/docker-php-fpm-5.6) |
| [![Build Status](https://travis-ci.org/cytopia/docker-nginx-mainline.svg?branch=master)](https://travis-ci.org/cytopia/docker-nginx-mainline) [Nginx mainline](https://github.com/cytopia/docker-nginx-mainline) | MariaDB 5  | [PHP 7.0](https://github.com/cytopia/docker-php-fpm-7.0) |
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
## Feature overview

* Dynamically Configured Mass Virtual Hosting
* Configuration overwrites (`my.cnf`, `nginx.conf`, `httpd.conf` or `php.ini`)
* Log files available on host computer
* MySQL socket (available on host computer and PHP container)
* MySQL connectivity (reachable from host computer and from PHP container via `127.0.0.1` and `localhost`)
* Xdebug



## Intranet overview

The devilbox comes with a pre-configured intranet on `http://localhost`

* Virtua lHost overview
* Database overview
* PHP Info
* MySQL Info
* PHPMyAdmin
* Opcache GUI


## Screenshots

**Homepage with host / docker information**

The homepage shows you the status of your current configured setup.

* which versions are used
* what directories are mounted
* what other settings have been set

![Intranet Home](https://raw.githubusercontent.com/cytopia/devilbox/master/doc/img/01_intranet_home.png "Intranet Home")


**Virtual Host overview**

This overview shows you all available virtual hosts and if they need additional configuration (on the host)

Virtual Hosts are considered valid if the following requirements are met (on the host system):

* `htdocs` folder/symlink exists in your project folder
* `/etc/hosts` has a valid DNS config for your host: `127.0.0.1  <project-folder>.loc`)

![Intranet vHost](https://raw.githubusercontent.com/cytopia/devilbox/master/doc/img/02_intranet_vhosts.png "Intranet Home")

**Database overview**

Shows you all the databases that are loaded

![Intranet DB](https://raw.githubusercontent.com/cytopia/devilbox/master/doc/img/03_intranet_databases.png "Intranet Home")
