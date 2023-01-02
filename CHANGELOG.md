# Changelog

Make sure to have a look at [UPDATING.md](https://github.com/cytopia/devilbox/blob/master/UPDATING.md) to see any required steps for updating major or minor versions.


## Unreleased


## Release v3.0.0-beta-0.3 (2022-01-02)

This release provides the `dvl.to` domain to be used with `TLD_SUFFIX` (set to default), which eliminates the need to set any entries in `/etc/hosts`, as all of its subdomain will point to `127.0.0.1` via official DNS. Domain has been acquired thanks to awesome sponsors!

### Fixed
- Intranet: mail.php fixed deprecation warnings [#798](https://github.com/cytopia/devilbox/issues/798)
- Added `host.docker.internal` to extra_hosts to be able to connect to the host system [#919](https://github.com/cytopia/devilbox/issues/919)

### Changed
- Use `dvl.to` as default `TLD_SUFFIX` (it always poits to `127.0.0.1` removing the need to create `/etc/hosts` entries)

### Added
- Intranet: vhost overview shows listening ports
- Intranet: vhost overview now has modals to show httpd and vhost-gen configs
- Docs: Show available tools per version in README.md
- Added `xhprof` PHP extension


## Release v3.0.0-beta-0.2 (2022-12-27)

The Backend configuration now supports websockets as well:

file: `/shared/httpd/<project>/.devilbox/backend.cfg`
```bash
# PHP-FPM backend
conf:phpfpm:tcp:php80:9000

# HTTP Reverse Proxy backend
conf:rproxy:http:172.16.238.10:3000

# HTTPS Reverse Proxy backend
conf:rproxy:https:172.16.238.10:3000

# Websocket Reverse Proxy backend
conf:rproxy:ws:172.16.238.10:3000

# SSL Websocket Reverse Proxy backend
conf:rproxy:wss:172.16.238.10:3000
```

Once you're done with `backend.cfg` changes, head over to the Intranet C&C page (http://localhost/cnc.php) and Reload `watcherd`.


### Fixed
- Intranet: vhost overview: allow HTTP 426 to succeed in vhost page (websocket projects)
- Intranet: vhost overview: Reverse Proxy or Websocket backends do not require a `htdocs/` dir for healthcheck
- Fixed reverse proxy template generation for Apache 2.2 and Apache 2.4 [vhost-gen #51](https://github.com/devilbox/vhost-gen/pull/51)
- Fixed Nginx hash bucket size length to allow long hostnames

### Added
- Reverse Proxy automation for websocket projects (`ws://<host>:<port>` or `wss:<host>:<port>`) (Does not work with Apache 2.2)
- Added tool `wscat` to be able to test websocket connections
- Intranet: show `wscat` version
- Intranet: vhost overview now also shows websocket projects

### Changed
- Do not mount any startup/autostart script directories for multi-php compose as they do not contain tools
- Updated vhost-gen templates in `cfg/vhost-gen` (replace your project templates with new ones)


## Release v3.0.0-beta-0.1 (2022-12-24) 🎅🎄🎁

This is a beta release, using a completely rewritten set of HTTPD server, which allow easy reverse Proxy integration and different PHP versions per project:

* https://github.com/devilbox/docker-nginx-stable/pull/55
* https://github.com/devilbox/docker-nginx-mainline/pull/57
* https://github.com/devilbox/docker-apache-2.2/pull/53
* https://github.com/devilbox/docker-apache-2.4/pull/54

Once it has been tested by the community, and potential errors have been addressed, a new major version will be released.

**IMPORTANT:** This release required you to copy `env-example` over onto `.env` due to some changes in variables.

### TL;DR

1. **Multiple PHP Versions**<br/>
    Here is an example to run one project with a specific PHP version<br/>
    ```bash
    # Enable all PHP versions
    cp compose/docker-compose.override.yml-php-multi.yml docker-compose.override.yml
    # Start default set and php80
    docker-compose up php httpd bind php80
    ```
    file: `/shared/httpd/<project>/.devilbox/backend.cfg`
    ```
    conf:phpfpm:tcp:php80:9000
    ```
2. **Automated Reverse Proxy setup**<br/>
    Here is an example to proxy one project to a backend service (e.g. NodeJS or Python application, which runs in the PHP container on port 3000)<br/>
    file: `/shared/httpd/<project>/.devilbox/backend.cfg`
    ```
    conf:rproxy:http:127.0.0.1:3000
    ```
#### PHP hostnames and IP addresses

| PHP Version | Hostname | IP address     |
|-------------|----------|----------------|
| 5.4         | php54    | 172.16.238.201 |
| 5.5         | php55    | 172.16.238.202 |
| 5.6         | php56    | 172.16.238.203 |
| 7.0         | php70    | 172.16.238.204 |
| 7.1         | php71    | 172.16.238.205 |
| 7.2         | php72    | 172.16.238.206 |
| 7.3         | php73    | 172.16.238.207 |
| 7.4         | php74    | 172.16.238.208 |
| 8.0         | php80    | 172.16.238.209 |
| 8.1         | php81    | 172.16.238.210 |
| 8.2         | php82    | 172.16.238.211 |

### Fixed
- Fixed Protocol substitution bug in Reverse Proxy generation for Apache 2.2 and Apache 2.4 [vhost-gen #49](https://github.com/devilbox/vhost-gen/pull/49) [vhost-gen #50](https://github.com/devilbox/vhost-gen/pull/50)
- Fixed missing module `mod_proxy_html` in Apache 2.4 as per requirement from `vhost-gen` for Reverse Proxy setup
- Fixed encoding issue with Apache 2.4 Reverse Proxy by enabling `mod_xml2enc` module (Required by `mod_proxy_html`)
- Allow to run different PHP versions per project. fixes [#146](https://github.com/cytopia/devilbox/issues/146)

### Added
- New HTTPD server capable of auto reverse proxy creation (and different PHP versions per project)
- Intranet: Added Command & Control center to view watcherd logs and retrigger config in case of vhost changes
- Intranet: vhost page now also shows the configured Backend
- Environment variable `DEVILBOX_HTTPD_MGMT_PASS`
- Environment variable `DEVILBOX_HTTPD_MGMT_USER`
- New Docker Compose Override file `docker-compose.override.yml-php-multi.yml` (allows to run multiple PHP versions).
- Update Bind to latest version

### Changed
- Disabled `psr` extension by default [php-psr #78](https://github.com/jbboehr/php-psr/issues/78#issuecomment-722290110)
- Disabled `phalcon` extension by default
- Environment variable `DEBUG_COMPOSE_ENTRYPOINT` renamed to `DEBUG_ENTRYPOINT`
- Environment variable `HTTPD_TIMEOUT_TO_PHP_FPM` renamed to `HTTPD_BACKEND_TIMEOUT`


## Release v2.4.0 (2022-12-18)

This release might be a bit bumpy due to a massive amount of changes in upstream projects. If you encounter issues, please do raise tickets.

### General

#### New PHP-FPM images
This release uses a new set of PHP-FPM images. They have been heavily rewritten and modularized in order to make PHP extension and PHP tool generation more easy. See the following release notes for details:

> 499 changed files with 29,281 additions and 13,977 deletions.

* https://github.com/devilbox/docker-php-fpm/releases/tag/0.145
* https://github.com/devilbox/docker-php-fpm/releases/tag/0.146
* https://github.com/devilbox/docker-php-fpm/releases/tag/0.147

#### How to add modules/tools?
* **[How to build PHP modules](https://github.com/devilbox/docker-php-fpm/blob/master/php_modules/README.md)**
* **[How to install tools in PHP images](https://github.com/devilbox/docker-php-fpm/blob/master/php_tools/README.md)**

#### Available Tools
You can now also find a detailed overview about what tools are installed in what PHP version image. See here: https://github.com/devilbox/docker-php-fpm/blob/master/doc/available-tools.md

#### Gitter -> Discord
Additionally I am moving away from Gitter to **Discord**. See reason and announcement here: https://devilbox.discourse.group/t/migrating-from-gitter-to-discord/716/2

**🎮 Discord:** https://discord.gg/2wP3V6kBj4

### Fixed
- Intranet: Fixed PostgreSQL database overview
- Fixed PATH for all pre-installed composer and node tools

### Changed
- Updated PHP versions (https://github.com/cytopia/devilbox/issues/940)
- Updated MySQL versions
- Intranet: Improved installed tools overview (index.php)
- Intranet: Delayed message loading (https://github.com/cytopia/devilbox/pull/904)

### Added
- Added tool `mhsendmail` for arm64 images
- Added tool `wkhtmltopdf` for arm64 images (https://github.com/cytopia/devilbox/issues/936)
- Added tool `taskfile` (https://github.com/cytopia/devilbox/issues/934)

### Removed
- Removed tool `drush` (detail: https://github.com/cytopia/devilbox/issues/930#issuecomment-1344764908)


## Release v2.3.0 (2022-12-04)

### Fixed
- Fixed correct permission for `/opt/nvm` in PHP container [#499](https://github.com/cytopia/devilbox/issues/499), [#PHP-FPM 0.141](https://github.com/devilbox/docker-php-fpm/releases/tag/0.141)
- Fixed Debian Jessie repository trust beyond EOL [#PHP-FPM 0.140](https://github.com/devilbox/docker-php-fpm/releases/tag/0.140)
- Fixed phpPgAdmin to work with PostgreSQL 15

### Added
- Added env var to Bind to specify overall memory consumption via `MAX_CACHE_SIZE` [#BIND 0.30](https://github.com/cytopia/docker-bind/releases/tag/0.30)
- Added PHP extension: `lz4` [#PHP-FPM 0.144](https://github.com/devilbox/docker-php-fpm/releases/tag/0.144)
- Added PHP extension: `lzf` [#PHP-FPM 0.144](https://github.com/devilbox/docker-php-fpm/releases/tag/0.144)
- Added PHP extension: `zstd` [#PHP-FPM 0.144](https://github.com/devilbox/docker-php-fpm/releases/tag/0.144)
- Added serializer to Redis extension: `lz4`, `lzf` and` zstd` [#PHP-FPM 0.144](https://github.com/devilbox/docker-php-fpm/releases/tag/0.144)
- Added MariaDB 10.9 and 10.11 [#MYSQL 0.19](https://github.com/devilbox/docker-mysql/pull/24)
- Added PGSQL 15
- Added Redis 7.0

### Changed
- Switched to `phalcon` 5.x extension for PHP 8.0 and PHP 8.1 [#913](https://github.com/cytopia/devilbox/issues/913), [#PHP-FPM 0.143](https://github.com/devilbox/docker-php-fpm/releases/tag/0.143)
- Updated to latest minor versions of Apache 2.2, Apache 2.4, Nginx stable and Nginx mainline
- Updated to latest minor versions of PHP [#917](https://github.com/cytopia/devilbox/issues/917)
- Updated to latest minor versions of MySQL, MariaDB and Percona DB
- Updated PHP extensions to lastest versions [#899](https://github.com/cytopia/devilbox/issues/899)

### Removed
- Removed Phalcon DevTools for PHP 7.4 due to build error [#PHP-FPM 0.142](https://github.com/devilbox/docker-php-fpm/releases/tag/0.142)


## Release v2.2.0 (2022-04-14)

This release adds PHP-FPM community images via `docker-compose.override.yml`, which easily allows you
to build upon existing PHP images and customize them for your usecase/workflow.

#### Added
- Added PHP-FPM Community images: https://github.com/devilbox/docker-php-fpm-community/


## Release v2.1.1 (2022-04-07)

#### Changed
- Used tagged PHP images (auto-updating)instead early release branch one.


## Release v2.1.0 (2022-04-05)

This is now a 100% `arm64` compatible release.

#### Fixed
- Fixed imklog: cannot open kernel log (/proc/kmsg): Operation not permitted.
- Fixed missing `arm64` support: [#855](https://github.com/cytopia/devilbox/issues/855)

#### Added
- Added PHP images with `arm64` support for PHP: https://github.com/devilbox/docker-php-fpm/releases/tag/0.138
- Added `vips` to PHP 8.0
- Added `vips` to PHP 8.1
- Added `swoole` to PHP 8.1

#### Removed
- Removed homebrew due to arm64 issues
- Removed Ansible due to arm64 issues


## Release v2.0.0 (2022-03-28)

The goal of this release is to reduce the overall size of Docker images and bring in latest versions.

**Important:** This release introduces backwards incompatible changes due to only keeping major versions of PostreSQL and therefore removing old volumes. Additionally the PostgreSQL volume names have changed. In order to guarantee a smooth transition, backup your PostgreSQL databases in the previous version before switching and then re-importing them in this version.

#### Added
- Added CakePHP integration tests for PHP 8+
- Added `.env` variable `HTTPD_FLAVOUR` to decide between `Debian` or `Alpine` for HTTP server

#### Changed
- Changed default PostgreSQL server from `12.4` to `14-alpine` (breaking change)
- Changed default Redis server from `6.0` to `6.2-alpine`
- Changed default Memcached server from `1.6` to `1.6-alpine`
- Changed default MongoDB server from `4.4` to `5.0`
- Changed default HTTPD server flavour from `Debian` to `Alpine`
- Use tiny Alpine version of Bind container

#### Removed
- Removed CI for MongoDB `2.8` and MongoDB `3.0` due to segfault: https://github.com/docker-library/mongo/issues/251


## Release v1.11.0 (2022-03-22)

#### Fixed
- Fixed pidof issue on QUEMU by replacing it with pgrep #854
- Fixed array definition for PHP < 5.4
- Fixed bind caching issue  [#37](https://github.com/cytopia/docker-bind/pull/37)
- Fixed Adminer 4.8.1 CSS issues [#867](https://github.com/cytopia/devilbox/issues/867)

#### Added
- Allow to globally enable/disable HTTP/2 [#844](https://github.com/cytopia/devilbox/issues/844)
- Added New `.env` variable: `HTTPD_HTTP2_ENABLE`

#### Changed
- Make MariaDB 10.6 the default
- Make PHP 8.1 the default
- Updated Apache 2.2
- Updated Apache 2.4
- Updated Nginx stable
- Updated Nginx mainline
- Updated PHP-FPM images [#230](https://github.com/devilbox/docker-php-fpm/pull/230)
- Updated PHP-FPM images [#231](https://github.com/devilbox/docker-php-fpm/pull/231)
- Updated phpMyAdmin to 5.1.3


## Release v1.10.5 (2022-03-16)

#### Added
- Added MariaDB 10.8

#### Changed
- Updated Bind [#36](https://github.com/cytopia/docker-bind/pull/36)
- Updated MySQL


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
