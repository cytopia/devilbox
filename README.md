# ![Devilbox](https://raw.githubusercontent.com/cytopia/devilbox/master/.devilbox/www/htdocs/assets/img/devilbox_80.png) The devilbox

**General Note:**

Always check out the latest git tag. The master branch is experimental and likely to not work.

**Note for OSX:**

There is currently a huge annoyance with Docker on OSX resulting in very slow file access, because of directory mounts. Read about the ticket here [Docker Forums #8076](https://forums.docker.com/t/file-access-in-mounted-volumes-extremely-slow-cpu-bound/8076).

**Latest feature:** `MySQL 8.0`

----

[Usage](https://github.com/cytopia/devilbox#usage) |
[Documentation](https://github.com/cytopia/devilbox#documentation) |
[Run-time Matrix](https://github.com/cytopia/devilbox#run-time-matrix) |
[Features](https://github.com/cytopia/devilbox#feature-overview) |
[Intranet](https://github.com/cytopia/devilbox#intranet-overview) |
[Screenshots](https://github.com/cytopia/devilbox#screenshots) |
[License](https://github.com/cytopia/devilbox/blob/master/LICENSE.md) |
[Contributing](https://github.com/cytopia/devilbox#contributing) |
[Todo](https://github.com/cytopia/devilbox/blob/master/CONTRIBUTING.md)

[![Build Status](https://travis-ci.org/cytopia/devilbox.svg?branch=master)](https://travis-ci.org/cytopia/devilbox) ![Tag](https://img.shields.io/github/tag/cytopia/devilbox.svg) [![type](https://img.shields.io/badge/type-Docker-orange.svg)](https://www.docker.com/) [![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://opensource.org/licenses/MIT)

The devilbox is a modern and highly customizable alternative for [XAMPP](https://www.apachefriends.org). It is based on `docker-compose` with presets for all kinds of versions for webservers, database servers and PHP.

Configuration is not necessary, as everything is pre-setup with mass virtual hosting.

**Supported operating systems**

![Linux](https://raw.githubusercontent.com/cytopia/icons/master/64x64/linux.png) ![Windows](https://raw.githubusercontent.com/cytopia/icons/master/64x64/windows.png) ![OSX](https://raw.githubusercontent.com/cytopia/icons/master/64x64/osx.png)

<sub>It might run on FreeBSD, but I don't know the status of docker-compose there.</sub>



---

## Usage

You are up and running in three simple steps:

```bash
# (optional) check out latest stable release
$ git checkout $(git describe --abbrev=0 --tags)

# Copy the example configuration file
$ cp env-example .env

# Edit your configuration
$ vim .env

# Start the containers (base-stack)
$ docker-compose up

# Or instead of the above base-stack, you can also additionally load the
# optional stack.
# Use this command instead:
$ docker-compose -f docker-compose.optional.yml up
```

## Updates

In case you update this repository locally on the master branch (e.g.: `git pull origin master`), make sure to repull all Docker containers as they very likely have also been up updated.
Otherwise you might run into problems.

[What is the `.env` file?](https://docs.docker.com/compose/env-file/)

## Documentation

**Video Tutorials**

[![Devilbox setup and workflow](https://raw.githubusercontent.com/cytopia/devilbox/master/doc/img/devilbox_01-setup-and-workflow.png "devilbox - setup and workflow")](https://www.youtube.com/watch?v=reyZMyt2Zzo) 
[![Devilbox email catch-all](https://raw.githubusercontent.com/cytopia/devilbox/master/doc/img/devilbox_02-email-catch-all.png "devilbox - email catch-all")](https://www.youtube.com/watch?v=e-U-C5WhxGY)

**Documentation**

For setup, usage and examples see detailed **[Documentation](https://github.com/cytopia/devilbox/blob/master/doc/README.md)**.

## Run-time Matrix

Select your prefered setup.

No need to install and configure different versions locally. Simply choose your required LAMP/LEMP stack combination during startup and it is up and running instantly.

**Note:** Some Docker container combinations might not work well. See the overall build-matrix for possible problems: \[ [![Build Status](https://travis-ci.org/cytopia/devilbox.svg?branch=master)](https://travis-ci.org/cytopia/devilbox) \]

**Base stack**

If you only want to use the base stack, use `docker-compose.yml` (default):
```shell
$ docker-compose up
```

| Webserver | MySQL | PostgreSQL | PHP |
|-----------|-------|------------|-----|
| [![Build Status](https://travis-ci.org/cytopia/docker-apache-2.2.svg?branch=master)](https://travis-ci.org/cytopia/docker-apache-2.2) [Apache 2.2](https://github.com/cytopia/docker-apache-2.2) | [![Build Status](https://travis-ci.org/cytopia/docker-mysql-5.5.svg?branch=master)](https://travis-ci.org/cytopia/docker-mysql-5.5) [MySQL 5.5](https://github.com/cytopia/docker-mysql-5.5) | [![Build Status](https://travis-ci.org/docker-library/postgres.svg?branch=master)](https://travis-ci.org/docker-library/postgres/branches) [PgSQL 9.2](https://hub.docker.com/_/postgres/) | [![Build Status](https://travis-ci.org/cytopia/docker-php-fpm-5.4.svg?branch=master)](https://travis-ci.org/cytopia/docker-php-fpm-5.4) [PHP 5.4](https://github.com/cytopia/docker-php-fpm-5.4) |
| [![Build Status](https://travis-ci.org/cytopia/docker-apache-2.4.svg?branch=master)](https://travis-ci.org/cytopia/docker-apache-2.4) [Apache 2.4](https://github.com/cytopia/docker-apache-2.4) | [![Build Status](https://travis-ci.org/cytopia/docker-mysql-5.6.svg?branch=master)](https://travis-ci.org/cytopia/docker-mysql-5.6) [MySQL 5.6](https://github.com/cytopia/docker-mysql-5.6) | [![Build Status](https://travis-ci.org/docker-library/postgres.svg?branch=master)](https://travis-ci.org/docker-library/postgres/branches) [PgSQL 9.3](https://hub.docker.com/_/postgres/) | [![Build Status](https://travis-ci.org/cytopia/docker-php-fpm-5.5.svg?branch=master)](https://travis-ci.org/cytopia/docker-php-fpm-5.5) [PHP 5.5](https://github.com/cytopia/docker-php-fpm-5.5) |
| [![Build Status](https://travis-ci.org/cytopia/docker-nginx-stable.svg?branch=master)](https://travis-ci.org/cytopia/docker-nginx-stable) [Nginx stable](https://github.com/cytopia/docker-nginx-stable) | [![Build Status](https://travis-ci.org/cytopia/docker-mysql-5.7.svg?branch=master)](https://travis-ci.org/cytopia/docker-mysql-5.7) [MySQL 5.7](https://github.com/cytopia/docker-mysql-5.7) | [![Build Status](https://travis-ci.org/docker-library/postgres.svg?branch=master)](https://travis-ci.org/docker-library/postgres/branches) [PgSQL 9.4](https://hub.docker.com/_/postgres/) | [![Build Status](https://travis-ci.org/cytopia/docker-php-fpm-5.6.svg?branch=master)](https://travis-ci.org/cytopia/docker-php-fpm-5.6) [PHP 5.6](https://github.com/cytopia/docker-php-fpm-5.6) |
| [![Build Status](https://travis-ci.org/cytopia/docker-nginx-mainline.svg?branch=master)](https://travis-ci.org/cytopia/docker-nginx-mainline) [Nginx mainline](https://github.com/cytopia/docker-nginx-mainline) | [![Build Status](https://travis-ci.org/cytopia/docker-mysql-8.0.svg?branch=master)](https://travis-ci.org/cytopia/docker-mysql-8.0) [MySQL 8.0](https://github.com/cytopia/docker-mysql-8.0)  | [![Build Status](https://travis-ci.org/docker-library/postgres.svg?branch=master)](https://travis-ci.org/docker-library/postgres/branches) [PgSQL 9.5](https://hub.docker.com/_/postgres/) | [![Build Status](https://travis-ci.org/cytopia/docker-php-fpm-7.0.svg?branch=master)](https://travis-ci.org/cytopia/docker-php-fpm-7.0) [PHP 7.0](https://github.com/cytopia/docker-php-fpm-7.0) |
|       | [![Build Status](https://travis-ci.org/cytopia/docker-mariadb-5.5.svg?branch=master)](https://travis-ci.org/cytopia/docker-mariadb-5.5) [MariaDB 5.5](https://github.com/cytopia/docker-mariadb-5.5) | [![Build Status](https://travis-ci.org/docker-library/postgres.svg?branch=master)](https://travis-ci.org/docker-library/postgres/branches) [PgSQL 9.6](https://hub.docker.com/_/postgres/) | [![Build Status](https://travis-ci.org/cytopia/docker-php-fpm-7.1.svg?branch=master)](https://travis-ci.org/cytopia/docker-php-fpm-7.1) [PHP 7.1](https://github.com/cytopia/docker-php-fpm-7.1) |
|       | [![Build Status](https://travis-ci.org/cytopia/docker-mariadb-10.0.svg?branch=master)](https://travis-ci.org/cytopia/docker-mariadb-10.0) [MariaDB 10.0](https://github.com/cytopia/docker-mariadb-10.0) | | [![Build Status](https://travis-ci.org/cytopia/docker-hhvm-latest.svg?branch=master)](https://travis-ci.org/cytopia/docker-hhvm-latest) [HHVM latest](https://github.com/cytopia/docker-hhvm-latest)
|       | [![Build Status](https://travis-ci.org/cytopia/docker-mariadb-10.1.svg?branch=master)](https://travis-ci.org/cytopia/docker-mariadb-10.1) [MariaDB 10.1](https://github.com/cytopia/docker-mariadb-10.1) | |
|       | [![Build Status](https://travis-ci.org/cytopia/docker-mariadb-10.2.svg?branch=master)](https://travis-ci.org/cytopia/docker-mariadb-10.2) [MariaDB 10.2](https://github.com/cytopia/docker-mariadb-10.2) | |
|       | [![Build Status](https://travis-ci.org/cytopia/docker-mariadb-10.3.svg?branch=master)](https://travis-ci.org/cytopia/docker-mariadb-10.3) [MariaDB 10.3](https://github.com/cytopia/docker-mariadb-10.3) | |


**Optional NoSQL stack**

In order to also use the Docker containers below, use the `docker-compose.optional.yml` instead:
```shell
$ docker-compose -f docker-compose.optional.yml up
```

| Cassandra | CouchDB | Memcached | MongoDB | Redis |
|-----------|---------|-----------|---------|-------|
| Cassandra 2.1 | CouchDB 1.6 | Memcached latest | MongoDB 2.6 | [![Travis CI](https://img.shields.io/travis/docker-library/redis/master.svg)](https://travis-ci.org/docker-library/redis/branches) [Redis 2.8](https://github.com/docker-library/redis) |
| Cassandra 2.2 | CouchDB 2.0 |                  | MongoDB 3.0 | [![Travis CI](https://img.shields.io/travis/docker-library/redis/master.svg)](https://travis-ci.org/docker-library/redis/branches) [Redis 3.0](https://github.com/docker-library/redis) |
| Cassandra 3.0 |             |                  | MongoDB 3.2 | [![Travis CI](https://img.shields.io/travis/docker-library/redis/master.svg)](https://travis-ci.org/docker-library/redis/branches) [Redis 3.2](https://github.com/docker-library/redis) |
|               |             |                  | MongoDB 3.4 | |

<sub>**Note:** Entries without links or without build-status are not yet available, but are coming soon. See [ROADMAP](https://github.com/cytopia/devilbox/issues/23) for tasks and upcoming features.</sub>

<!--
**Optional search stack**

| Apache Solr | Elasticsearch |
|-------------|---------------|
| todo        |               |
-->
<!--
**Optional cgi stack**

| Go   | Perl | Python | Ruby |
|------|------|--------|------|
| todo | todo | todo   | todo |
-->

## Feature overview

* Dynamically Configured **Mass Virtual Hosting**
* **Email** catch-all (Intercept and view all sent emails)
* Configuration **overwrites** (`my.cnf`, `nginx.conf`, `httpd.conf` or `php.ini`)
* **Log files** available on host computer
* MySQL socket (available on host computer and PHP container)
* MySQL connectivity (reachable from host computer and from PHP container via `127.0.0.1` and `localhost`)
* **Xdebug**


<!--
## Documentation

* Configuration
* Xdebug
-->

## Intranet overview

The devilbox comes with a pre-configured intranet on `http://localhost`

* Virtual Host overview
* MySQL Database overview
* PostgreSQL Database overview
* Email overview
* PHP Info
* MySQL Info
* PostgreSQL Info
* phpMyAdmin
* Adminer
* Opcache GUI



## Screenshots

**Homepage with host / Docker information**

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


**Email overview**

Shows you all the emails that have been sent. No email will actually be sent outside, but they are all catched by one account and presented here.

![Intranet Email](https://raw.githubusercontent.com/cytopia/devilbox/master/doc/img/04_intranet_emails.png "Intranet Home")


## Contributing

There is quite a lot todo and planned. If you like to contribute, view [CONTRIBUTING.md](https://github.com/cytopia/devilbox/blob/master/CONTRIBUTING.md) and [ROADMAP](https://github.com/cytopia/devilbox/issues/23).

Contributors will be credited within the intranet and on the GitHub page.

