#!/bin/sh
#
# This script will pull all Docker images that are currently
# bound to your devilbox git state.
#
# When updating the devilbox via git, do run this script once
# in order to download all images locally.
#

###
### Path of devilbox repository
###
CWD="$(cd -P -- "$(dirname -- "$0")" && pwd -P)"

###
### DNS
###
TAG="$( grep '^[[:space:]]*image:[[:space:]]*cytopia/bind' "${CWD}/docker-compose.yml" | sed 's/^.*://g' )"
docker pull cytopia/bind:${TAG}

###
### PHP
###
TAG="$( grep '^[[:space:]]*image:.*\${PHP_SERVER' "${CWD}/docker-compose.yml" | sed 's/^.*://g' )"
docker pull devilbox/php-fpm:5.3-work
docker pull devilbox/php-fpm:5.4-work
docker pull devilbox/php-fpm:5.5-work
docker pull devilbox/php-fpm:5.6-work
docker pull devilbox/php-fpm:7.0-work
docker pull devilbox/php-fpm:7.1-work
docker pull devilbox/php-fpm:7.2-work
docker pull devilbox/php-fpm:7.3-work
#docker pull cytopia/hhvm-latest:${TAG}

###
### HTTPD
###
TAG="$( grep '^[[:space:]]*image:.*\${HTTPD_SERVER' "${CWD}/docker-compose.yml" | sed 's/^.*://g' )"
docker pull devilbox/nginx-stable:${TAG}
docker pull devilbox/nginx-mainline:${TAG}
docker pull devilbox/apache-2.2:${TAG}
docker pull devilbox/apache-2.4:${TAG}

###
### MYSQL
###
TAG="$( grep '^[[:space:]]*image:.*\${MYSQL_SERVER' "${CWD}/docker-compose.yml" | sed 's/^.*://g' )"
docker pull cytopia/mysql-5.5:${TAG}
docker pull cytopia/mysql-5.6:${TAG}
docker pull cytopia/mysql-5.7:${TAG}
docker pull cytopia/mysql-8.0:${TAG}
docker pull cytopia/mariadb-5.5:${TAG}
docker pull cytopia/mariadb-10.0:${TAG}
docker pull cytopia/mariadb-10.1:${TAG}
docker pull cytopia/mariadb-10.2:${TAG}
docker pull cytopia/mariadb-10.3:${TAG}

###
### PGSQL
###
docker pull postgres:9.1
docker pull postgres:9.2
docker pull postgres:9.3
docker pull postgres:9.4
docker pull postgres:9.5
docker pull postgres:9.6
docker pull postgres:10.0
docker pull postgres:10.1
docker pull postgres:10.2
docker pull postgres:10.3

###
### REDIS
###
docker pull redis:2.8
docker pull redis:3.0
docker pull redis:3.2
docker pull redis:4.0

###
### MEMCACHED
###
docker pull memcached:1.4.21
docker pull memcached:1.4.22
docker pull memcached:1.4.23
docker pull memcached:1.4.24
docker pull memcached:1.4.25
docker pull memcached:1.4.26
docker pull memcached:1.4.27
docker pull memcached:1.4.28
docker pull memcached:1.4.29
docker pull memcached:1.4.30
docker pull memcached:1.4.31
docker pull memcached:1.4.32
docker pull memcached:1.4.33
docker pull memcached:1.4.34
docker pull memcached:1.4.35
docker pull memcached:1.4.36
docker pull memcached:1.4.37
docker pull memcached:1.4.38
docker pull memcached:1.4.39
docker pull memcached:1.5.0
docker pull memcached:1.5.1
docker pull memcached:1.5.2
docker pull memcached:1.5.3
docker pull memcached:1.5.4
docker pull memcached:1.5.5
docker pull memcached:latest

###
### MONGODB
###
docker pull mongo:2.8
docker pull mongo:3.0
docker pull mongo:3.2
docker pull mongo:3.4
docker pull mongo:3.5
docker pull mongo:3.6
docker pull mongo:3.7

