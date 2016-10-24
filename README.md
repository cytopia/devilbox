# ![Devilbox](https://raw.githubusercontent.com/cytopia/devilbox/master/.devilbox/www/htdocs/assets/img/devilbox_80.png) The devilbox

**Note for OSX:** 

There is currently a huge annoyance with docker on OSX resulting in very slow file access, because of directory mounts. Read about the ticket here [Docker Forums #8076](https://forums.docker.com/t/file-access-in-mounted-volumes-extremely-slow-cpu-bound/8076).

----

[Usage](https://github.com/cytopia/devilbox#usage) |
[Run-time Matrix](https://github.com/cytopia/devilbox#run-time-matrix) |
[Features](https://github.com/cytopia/devilbox#feature-overview) |
[Intranet](https://github.com/cytopia/devilbox#intranet-overview) |
[Contributing](https://github.com/cytopia/devilbox#contributing) |
[Screenshots](https://github.com/cytopia/devilbox#screenshots) |
[License](https://github.com/cytopia/devilbox/blob/master/LICENSE.md) |
[Todo](https://github.com/cytopia/devilbox/blob/master/CONTRIBUTING.md)

[![Build Status](https://travis-ci.org/cytopia/devilbox.svg?branch=master)](https://travis-ci.org/cytopia/devilbox) [![Technology](https://img.shields.io/badge/technology-Docker-orange.svg)](https://www.docker.com/) [![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://opensource.org/licenses/MIT)

The devilbox is a modern and highly customizable alternative for [XAMPP](https://www.apachefriends.org). It is based on `docker-compose` with presets for all kinds of versions for webservers, database servers and php.

Configuration is not necessary, as everything is pre-setup with mass virtual hosting.

**Supported operating systems**

![Linux](https://raw.githubusercontent.com/cytopia/icons/master/64x64/linux.png) ![Windows](https://raw.githubusercontent.com/cytopia/icons/master/64x64/windows.png) ![OSX](https://raw.githubusercontent.com/cytopia/icons/master/64x64/osx.png)

<sub>It might run on FreeBSD, but I don't know the status of docker-compose there.</sub>



---

## Usage

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
| [![Build Status](https://travis-ci.org/cytopia/docker-apache-2.2.svg?branch=master)](https://travis-ci.org/cytopia/docker-apache-2.2) [Apache 2.2](https://github.com/cytopia/docker-apache-2.2) | [![Build Status](https://travis-ci.org/cytopia/docker-mysql-5.5.svg?branch=master)](https://travis-ci.org/cytopia/docker-mysql-5.5) [MySQL 5.5](https://github.com/cytopia/docker-mysql-5.5) | [![Build Status](https://travis-ci.org/cytopia/docker-php-fpm-5.4.svg?branch=master)](https://travis-ci.org/cytopia/docker-php-fpm-5.4) [PHP 5.4](https://github.com/cytopia/docker-php-fpm-5.4) |
| [![Build Status](https://travis-ci.org/cytopia/docker-apache-2.4.svg?branch=master)](https://travis-ci.org/cytopia/docker-apache-2.4) [Apache 2.4](https://github.com/cytopia/docker-apache-2.4) | [![Build Status](https://travis-ci.org/cytopia/docker-mysql-5.6.svg?branch=master)](https://travis-ci.org/cytopia/docker-mysql-5.6) [MySQL 5.6](https://github.com/cytopia/docker-mysql-5.6) | [![Build Status](https://travis-ci.org/cytopia/docker-php-fpm-5.5.svg?branch=master)](https://travis-ci.org/cytopia/docker-php-fpm-5.5) [PHP 5.5](https://github.com/cytopia/docker-php-fpm-5.5) |
| [![Build Status](https://travis-ci.org/cytopia/docker-nginx-stable.svg?branch=master)](https://travis-ci.org/cytopia/docker-nginx-stable) [Nginx stable](https://github.com/cytopia/docker-nginx-stable) | [![Build Status](https://travis-ci.org/cytopia/docker-mysql-5.7.svg?branch=master)](https://travis-ci.org/cytopia/docker-mysql-5.7) [MySQL 5.7](https://github.com/cytopia/docker-mysql-5.7)  | [![Build Status](https://travis-ci.org/cytopia/docker-php-fpm-5.6.svg?branch=master)](https://travis-ci.org/cytopia/docker-php-fpm-5.6) [PHP 5.6](https://github.com/cytopia/docker-php-fpm-5.6) |
| [![Build Status](https://travis-ci.org/cytopia/docker-nginx-mainline.svg?branch=master)](https://travis-ci.org/cytopia/docker-nginx-mainline) [Nginx mainline](https://github.com/cytopia/docker-nginx-mainline) | MariaDB 5  | [![Build Status](https://travis-ci.org/cytopia/docker-php-fpm-7.0.svg?branch=master)](https://travis-ci.org/cytopia/docker-php-fpm-7.0) [PHP 7.0](https://github.com/cytopia/docker-php-fpm-7.0) |
|       | MariaDB 10 | [![Build Status](https://travis-ci.org/cytopia/docker-php-fpm-7.1.svg?branch=master)](https://travis-ci.org/cytopia/docker-php-fpm-7.1) [PHP 7.1](https://github.com/cytopia/docker-php-fpm-7.1) |


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


## Contributing

There is quite a lot todo and planned. If you like to contribute, view [CONTRIBUTING.md](https://github.com/cytopia/devilbox/blob/master/CONTRIBUTING.md).

Contributors will be credited within the intranet and on the github page.


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
