# Changelog

Make sure to have a look at [UPDATING](https://github.com/cytopia/devilbox/blob/master/UPDATING.md) to see any required steps for updating
major versions.


## Unreleased


## Release v1.7.2 (2020-09-17)

#### Added
- [#166](https://github.com/devilbox/docker-php-fpm/issues/166) Added `locale-gen` binary
- [#716](https://github.com/cytopia/devilbox/issues/716) Adding `vips` PHP module
- [#721](https://github.com/cytopia/devilbox/issues/721) Adding `xlswriter` PHP module
- [#724](https://github.com/cytopia/devilbox/issues/724) Adding `xdebug` PHP module for PHP 8.0
- Added `COMPOSER_MEMORY_LIMIT=-1` env variable to PHP


## Release v1.7.1 (2020-08-09)

#### Added
- [#700](https://github.com/cytopia/devilbox/issues/700) Re-added imap for PHP 7.4
- [#611](https://github.com/cytopia/devilbox/issues/611) Adding `certbot` binary to PHP
- [#713](https://github.com/cytopia/devilbox/issues/713) Added `gsfonts`
- [#713](https://github.com/cytopia/devilbox/issues/713) Added `mupdf` and `mupdf-tools`
- [#714](https://github.com/cytopia/devilbox/issues/714) Added PDF support for imagick

#### Changed
- Updated Nginx (stable and mainline)
- Updated MySQL/MariaDB/PerconaDB images


## Release v1.7.0 (2020-03-24)

#### Added
- Python Flask


## Bugfix Release v1.6.3 (2020-03-23)

#### Fixed
- Fixed PHP FPM images
- Fixed cert-gen for HAProxy
- Various spelling errors in documentation
- PostgreSQL startup without a password
#### Added
- [#686](https://github.com/cytopia/devilbox/pull/686) Added documentation for ExpressEngine
- New .env var: `PGSQL_HOST_AUTH_METHOD`


## Bugfix Release v1.6.2 (2020-02-06)

#### Added
- [#670](https://github.com/cytopia/devilbox/issues/670) Add `phalcon` binary to PHP 7.3 and 7.4
- [#664](https://github.com/cytopia/devilbox/issues/664) Add PHP `yaml` extension


## Bugfix Release v1.6.1 (2020-01-05)

#### Fixed
- [#662](https://github.com/cytopia/devilbox/issues/662) Update to latest Symfony CLI


## Release v1.6.0 (2020-01-04)

#### Changed
- [#642](https://github.com/cytopia/devilbox/issues/642) Make email catch-all configurable
- [#265](https://github.com/cytopia/devilbox/issues/265) Make SSL vhost settings configurable

#### Added
- [#615](https://github.com/cytopia/devilbox/issues/615) Add phpmd
- [#378](https://github.com/cytopia/devilbox/issues/378) Allow to mount local `.ssh/` directory into PHP container (read-only)


## Release v1.5.0 (2020-01-03)

#### Added
- [#654](https://github.com/cytopia/devilbox/issues/654) Added Opcache Control Panel
- Integration tests for MySQL Docker image


## Release v1.4.0 (2020-01-02)

#### Fixed
- [#618](https://github.com/cytopia/devilbox/issues/618) Update Compose version to 2.3
- [#614](https://github.com/cytopia/devilbox/issues/614) Update to latest mhsendmail binary with `-o` flag
- [#265](https://github.com/cytopia/devilbox/issues/265) Fix http to https redirect for projects

#### Changed
- [#642](https://github.com/cytopia/devilbox/issues/642) Be able to disable email catch-all and still run postfix


## Release v1.3.0 (2019-12-29)

#### Fixed
- [#626](https://github.com/cytopia/devilbox/issues/626) Cannot create MongoDB database with Adminer

#### Added
- latest PHP-FPM images
- PHP module: solr
- PHP module: ssh2
- phpMyAdmin to 5.0.0

#### Changed
- Use official PHP 7.4 Docker image as base
- Updated Adminer to 4.7.5
```
sed -i'' 's/^<?php$/<?php if(!function_exists("get_magic_quotes_runtime")){function get_magic_quotes_runtime(){return false;}}if(!function_exists("get_magic_quotes_gpc")){function get_magic_quotes_gpc(){return false;}}/g' adminer-4.7.5-en.php
sed -i'' 's/while(list(\$y,\$X)=each(\$qg))/foreach ($qg as $y => $x)/g' adminer-4.7.5-en.php
sed -i'' 's/error_reporting(6135)/error_reporting(0)/g' adminer-4.7.5-en.php
```
- Updated phpMyAdmin to 4.9.3


## Release v1.2.0 (2019-12-01)

#### Fixed
- [#622](https://github.com/cytopia/devilbox/issues/622) Certificate Generation Settings
- [#640](https://github.com/cytopia/devilbox/issues/640) Mac OS Catalina invalidates virtual host certificates
- [#592](https://github.com/cytopia/devilbox/issues/592) sqlsrv connection problem

#### Added
- Added latest PHP-FPM images
- Added latest MySQL images
- Added latest Apache/Nginx images

#### Changed
- Made PHP 7.3 the default version


## Release v1.1.0 (2019-11-24)

#### Fixed
- [#644](https://github.com/cytopia/devilbox/issues/644) Fix GD jpeg support missing in PHP 7.4
- [#619](https://github.com/cytopia/devilbox/issues/619) Fix PHP 7.x  WebP support with imagick

#### Added
- Make Ngrok region configurable via `NGROK_REGION` env variable
- [#641](https://github.com/cytopia/devilbox/issues/641) Added phpPgAdmin v7 for PHP >=7
- [#594](https://github.com/cytopia/devilbox/issues/594) Tool: ghostscript
- Extensive GitHub Action CI checks
- Added new PostgreSQL images
- Added new MongoDB images

#### Changed
- Updated PHP Docker images (PHP version, modules and tools)


## Bugfix Release v1.0.2 (2019-05-21)

#### Fixed
- Fixed various typos in documentation
- Fix CI tests: They still expected a mounted mail directory instead of a Docker volume
- Fix mods for PHP 8.0
- Make npm binaries available in $PATH
- Remove orphaned mentions of HOST_PATH_MYSQL_DATADIR
- Allow symlinks in autostart scripts

#### Added
- Documentation: Setup ProcessWire
- Tool: Angular CLI
- Tool: Laravel Lumen
- Tool: [prestissimo](https://github.com/hirak/prestissimo)
- Tool: [yq](https://github.com/mikefarah/yq)
- Module: OAuth

#### Removed
- Enchant module for PHP 7.4 and PHP 8.0 (build breaks)


## Bugfix Release v1.0.1 (2019-03-24)

This is a bugfix release and everybody is encouraged to upgrade to this tag as soon as possible.
No explicit actions to be taken for updating.

#### Fixed
- [#373](https://github.com/cytopia/devilbox/issues/373) Read custom MySQL configuration
- [#540](https://github.com/cytopia/devilbox/issues/540) Fix '&' password substitution in mysqldump-secure
- [#209](https://github.com/cytopia/devilbox/issues/209) Documentation for Xdebug on Docker for Windows

#### Changed
- Updated MongoDB cli tools in PHP image
- Updated PostgreSQL cli tools in PHP image
- MySQL images are now bound to a specific Docker tag and are built nightly
- [#506](https://github.com/cytopia/devilbox/issues/506) Documentation improvements for connecting to databases

#### Added
- [#536](https://github.com/cytopia/devilbox/issues/536) Added @vue/cli and @vue/cli-service-global



## Release v1.0.0 (2019-03-19)

This is the first major stable release of the Devilbox.

#### Changed
- Everything from v1.0.0-alpha1 has been backported
- Everything from v0.15.0 has been backported



## Pre-Release v1.0.0-alpha1 (2019-03-09)

#### Changed
- Use Docker volumes instead of directory mounts for stateful data (MySQL, PgSQL and MongoDB)
    - This fixes various mount issues on Windows: #175 #382
    - This improves general performance
- Use Official MySQL, MariaDB and Percona Docker container



## Release v0.15.0 (2019-03-09)

This will be the last v0.x release.

#### Fixed
- break on errors in wrong vhost-gen overwrite
- XSS vulnerability in email display
- Various fixes in Documentation
- vhost-gen fixes

#### Changed
- Use semantic versioning
    - This allows for faster releases
    - This allows for better visibility of breaking changes (note that breaking changes might still occur before release v1.0.0)
- Autologin for phpMyAdmin
- Autologin for phpPgAdmin
- Intranet to show vhost and vhost-gen overwrite config per vhost
- Allow to specify Redis startup arguments (e.g.: password)
- Fixed hostnames for all Docker container
- PHP-FPM workers changed from `dynamic` to `ondemand`
- Allow Apache to server underscore domains
- Changed Nginx `client_max_body_size` to `0` to be in sync with Apache
- Document failing start behaviour of MySQL container

#### Added
- [CHANGELOG](https://github.com/cytopia/devilbox/blob/master/CHANGELOG.md) by the standard of: https://keepachangelog.com
- [UPDATING](https://github.com/cytopia/devilbox/blob/master/UPDATING.md) provides information how to update between major versions
- HTTP/2 support
- Reverse Proxy support
- Autostart scripts
- Allow to enable/disable PHP modules
- Allow to set Nginx worker_processes via .env
- Allow to set Nginx worker_connections via .env
- Intranet vendors
    - PHPRedMin
    - PHPMemcachedAdmin
- Mount options for volumes
- Docker Compose images:
    - PHP 5.2
    - PHP 5.3
    - PHP 7.4
    - PHP 8.0
    - Alpine images where possible
- Docker Compose overwrite images:
    - Blackfire
    - ELK (Elastic Search, Logstash and Kibana)
    - MailHog
    - Ngrok
    - RabbitMQ
    - Solr
    - Varnish
    - HAProxy
- New binaries
    - `blackfire`
    - `dep` (Deployer)
    - `drush6`
    - `drush7`
    - `drush8`
    - `php-cs-fixer`
    - `rsync`
    - `unzip`
    - `wkhtmltopdf`
    - `zip`
    - `zsh`
- New PHP modules
    - `blackfire`
    - `ffi`
    - `ioncube`
    - `oci8`
    - `phalcon`
    - `pdo_oci`
    - `pdo_sqlsrv`
    - `rdkafka`
    - `sqlsrv`
- Framework documentation:
    - Codeignitor
    - Contao CMS
    - Craft CMS
    - NodeJS Reverse proxy
    - Photon CMS
    - Presta Shop
    - Shopware CMS
    - Sphinx documentation Reverse proxy
    - Typo3
- GitHub Issue templates
- Discourse forum link: https://devilbox.discourse.group
