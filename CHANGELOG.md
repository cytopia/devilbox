# Changelog

Make sure to have a look at [UPDATING.md](https://github.com/cytopia/devilbox/blob/master/UPDATING.md) to see any required steps for updating major or minor versions.


## Unreleased


## Release v1.10.4 (2022-02-15)

#### Fixed
- Fixed SSL-Cache Mutex on M1 CPU [#862](https://github.com/cytopia/devilbox/issues/862)

#### Changed
- Changed Intranet mail tester to using POST instead of GET for larger body size
- Made vhost error message more verbose
- Updated Nginx Stable [#36](https://github.com/devilbox/docker-nginx-stable/pull/36)
- Updated Nginx Mainline [#39](https://github.com/devilbox/docker-nginx-mainline/pull/39)
- Updated Apache 2.2 [#33](https://github.com/devilbox/docker-apache-2.2/pull/33)
- Updated Apache 2.4 [#35](https://github.com/devilbox/docker-apache-2.4/pull/35)


## Release v1.10.3 (2022-02-04)

#### Added
- Added PHP 8.2

#### Changed
- Updated PHP-FPM images [#225](https://github.com/devilbox/docker-php-fpm/pull/225)
- Updated PHP-FPM images [#226](https://github.com/devilbox/docker-php-fpm/pull/226)


## Release v1.10.2 (2022-02-02)

#### Fixed
- Fixed `nvm` PATH priority [#846](https://github.com/cytopia/devilbox/issues/846)

#### Added
- Added extension `sqlsrv` to php 8.1
- Added extension `pdo_sqlsrv` to php 8.1

#### Changed
- Changed postfix hostname to `localhost` instead of GitHub runners long name
- Updated PHP-FPM images [#224](https://github.com/devilbox/docker-php-fpm/pull/224)


## Release v1.10.1 (2022-01-30)

#### Fixed
- Fixed evaluation of `MASS_VHOST_SSL_GEN` env var [#830](https://github.com/cytopia/devilbox/issues/830)

#### Added
- Added feature to delete emails from within control center [#754](https://github.com/cytopia/devilbox/issues/754)

#### Changed
- Updated Nginx Stable [#35](https://github.com/devilbox/docker-nginx-stable/pull/35)
- Updated Nginx Mainline [#38](https://github.com/devilbox/docker-nginx-mainline/pull/38)
- Updated Apache 2.2 [#32](https://github.com/devilbox/docker-apache-2.2/pull/32)
- Updated Apache 2.4 [#34](https://github.com/devilbox/docker-apache-2.4/pull/34)


## Release v1.10.0 (2022-01-28)

#### Fixed
- Fixed mail.php to correctly show UTF chars in Body [#850](https://github.com/cytopia/devilbox/issues/850)
- Fixed desc in env-example [#807](https://github.com/cytopia/devilbox/issues/807)

#### Added
- Added binary `sqlite3` to all PHP images [#856](https://github.com/cytopia/devilbox/issues/856)
- Added binary `laravel` to PHP 8.0 and PHP 8.1 [#823](https://github.com/cytopia/devilbox/issues/823)
- Added AVIF support in GD for PHP 8.1 [#834](https://github.com/cytopia/devilbox/issues/834)
- Added extension `amqp` to PHP 8.0 and PHP 8.1 [#826](https://github.com/cytopia/devilbox/issues/826)
- Added extension `uploadprogress` to PHP 8.0 and PHP 8.1 [#158](https://github.com/devilbox/docker-php-fpm/pull/158)
- Added extension `imagick` to PHP 8.0 and PHP 8.1
- Added extension `rdkafka` to PHP 8.0 and PHP 8.1
- Added extension `xlswriter` to PHP 8.1
- Added extension `pdo_dblib` to PHP 8.1
- Added extension `uuid` to all PHP versions (except 5.2)
- Added MySQL image: MariaDB 10.6
- Added MySQL image: MariaDB 10.7

#### Changed
- Updated `php-cs-fixer` to latest version [#219](https://github.com/devilbox/docker-php-fpm/pull/219)
- Updated Nginx Stable [#33](https://github.com/devilbox/docker-nginx-stable/pull/33)
- Updated Nginx Stable [#34](https://github.com/devilbox/docker-nginx-stable/pull/34)
- Updated Nginx Mainline [#36](https://github.com/devilbox/docker-nginx-mainline/pull/36)
- Updated Nginx Mainline [#37](https://github.com/devilbox/docker-nginx-mainline/pull/37)
- Updated Apache 2.2 [#30](https://github.com/devilbox/docker-apache-2.2/pull/30)
- Updated Apache 2.2 [#31](https://github.com/devilbox/docker-apache-2.2/pull/31)
- Updated Apache 2.4 [#32](https://github.com/devilbox/docker-apache-2.4/pull/32)
- Updated Apache 2.4 [#33](https://github.com/devilbox/docker-apache-2.4/pull/33)


## Release v1.9.3 (2022-01-24)

#### Fixed
- Updated PHP Docker Images: [#221](https://github.com/devilbox/docker-php-fpm/pull/221)
- Updated PHP Docker Images: [#222](https://github.com/devilbox/docker-php-fpm/pull/222)
- Update MySQL Docker Images: [#10](https://github.com/devilbox/docker-mysql/pull/10)
- Fixed documentation build issues
- Fixed intranet PHP code to work with legacy versions


## Release v1.9.2 (2021-06-04)

#### Added
- Added Homebrew for all PHP images
- Added `pdo_sqlsrv` PHP extension for 7.4 and 8.0
- Xdebug 3.0 documentation


## Release v1.9.1 (2021-05-19)

#### Added
- Added PHP Xdebug info page for intranet

#### Changed
- [#769](https://github.com/cytopia/devilbox/issues/769) Adjusted Xdebug 3.0 defaults
- Update PHP images to 0.125
- MySQL database use binlog by default
- Updated Adminer to 4.8.1

#### Fixed
- [#783](https://github.com/cytopia/devilbox/pull/783) Kibana 6.6 and above uses ELASTICSEARCH_HOSTS
- [#801](https://github.com/cytopia/devilbox/issues/801) Intranet not available when some php modules disabled or not compiled


## Release v1.9.0 (2020-12-12)

#### Fixed
- [#761](https://github.com/cytopia/devilbox/issues/761) Fixed missing Varnish config env var
- [#10](https://github.com/devilbox/watcherd/issues/10) watcherd performance issues
- Fixed `mdl` rubygen for PHP images
- Fixed `drupal` (Drupal Console Launcher) for PHP images

#### Added
- Added `ioncube` extension to PHP 7.4
- Added `sqlsrv` extension to PHP 7.4
- Added `apcu` extension to PHP 8.0
- Added `blackfire` extension to PHP 8.0
- Added `igbinary` extension to PHP 8.0
- Added `imap` extension to PHP 8.0
- Added `mcrypt` extension to PHP 8.0
- Added `memcache` extension to PHP 8.0
- Added `msgpack` extension to PHP 8.0
- Added `oauth` extension to PHP 8.0
- Added `psr` extension to PHP 8.0
- Added `solr` extension to PHP 8.0
- Added `xlswriter` extension to PHP 8.0
- Added `yaml` extension to PHP 8.0
- Added `apcu` extension to PHP 8.1
- Added `igbinary` extension to PHP 8.1
- Added `imap` extension to PHP 8.1
- Added `mcrypt` extension to PHP 8.1
- Added `memcache` extension to PHP 8.1
- Added `msgpack` extension to PHP 8.1
- Added `oauth` extension to PHP 8.1
- Added `psr` extension to PHP 8.1
- Added `solr` extension to PHP 8.1
- Added `xlswriter` extension to PHP 8.1
- Added `yaml` extension to PHP 8.1
- Added checks for TLD_SUFFIX in check-config.sh

#### Changed
- [#763](https://github.com/cytopia/devilbox/issues/764) `redis` extension compiles with `msgpack` and `igbinary` as available serializers
- Updated xdebug to latest version
- Updated `watcherd` to latest version
- Updated `vhost-gen` to latest version


## Release v1.8.3 (2020-11-22)

#### Fixed
- [#753](https://github.com/cytopia/devilbox/issues/753) Fixed symlink handling in watcherd
- [#751](https://github.com/cytopia/devilbox/issues/751) Fixed duplicate output in check-config.sh

#### Added
- [#755](https://github.com/cytopia/devilbox/issues/755) Added ~/.composer/vendor/bin to $PATH
- [#692](https://github.com/cytopia/devilbox/issues/692) Added custom supervisor configs
- Added project and customization checks in check-config.sh
- Intranet: show custom PHP configuration files
- Intranet: show custom Httpd configuration files


## Release v1.8.2 (2020-11-14)

#### Fixed
- [#643](https://github.com/cytopia/devilbox/issues/643) Wrong entrypoint in mysql images
- [#703](https://github.com/cytopia/devilbox/issues/703) Don't fail on uid/gid change
- [#749](https://github.com/cytopia/devilbox/issues/749) Fix to disable PHP modules without `*.so` ext
- Fixed `check-config.sh` to properly expand `~` character in path

#### Added
- [#707](https://github.com/cytopia/devilbox/issues/707) New `.env` variable: `HOST_PATH_BACKUPDIR`

#### Changed
- [#547](https://github.com/cytopia/devilbox/issues/547) Added link to official Contao Devilbox Documentation


## Release v1.8.1 (2020-11-12)

#### Fixed
- Silence PHP warnings in phpmemcached and opcache GUIs
- [#746](https://github.com/cytopia/devilbox/issues/746) Fix xdebug config for PHP 8.0 and 8.1

#### Added
- Added `check-config.sh` script to check against correct Devilbox configuration


## Release v1.8.0 (2020-11-08)

#### Fixed
- [#739](https://github.com/cytopia/devilbox/issues/739) Disabled gd-jis: https://bugs.php.net/bug.php?id=73582
- [#740](https://github.com/cytopia/devilbox/issues/740) Use latest PHP 8.0 image

#### Added
- [#715](https://github.com/cytopia/devilbox/issues/715) PHP module mongodb is re-added to PHP 8.0
- Added **PHP 8.1**: https://github.com/devilbox/docker-php-fpm-8.1
- Added Postgres images: 11.7, 11.8, 11.9, 12.2, 12.3, 12.4, 13.0
- Added Redis images: 6.0
- Added Memcache images: 1.6
- Added MongoDB images: 4.4
- Added MySQL images: MariaDB 10.5

#### Changed
- [#736](https://github.com/cytopia/devilbox/issues/736) Composer is updated to v2 (`/usr/local/bin/composer`)
- [#728](https://github.com/cytopia/devilbox/issues/728) Updated phpPgAdmin from 7.12 to 7.13
- Updated phpMyAdmin from 5.0.0 to 5.0.4
- Updated phpMyAdmin from 4.9.3 to 4.9.7
- Updated Adminer from 4.7.5 to 4.7.7
- Composer is available as v1 and v2 (`/usr/local/bin/composer-1` and `/usr/local/bin/composer-2`)
- New default PHP version: 7.4
- New default MySQL version: MariaDB 10.5
- New default Postgres version: 12.4
- New default Redis version: 6.0
- New default Memcached version 1.6
- New default MongoDB version: 4.4


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
