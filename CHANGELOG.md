# Changelog

Make sure to have a look at [UPDATING](https://github.com/cytopia/devilbox/blob/master/UPDATING.md) to see any required steps for updating
major versions.



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
