# Changelog

Make sure to have a look at [UPDATING](Updating.md) to see any required steps for updating
major versions.

## [unreleasd]

#### Changed
- Split Bind container into internal DNS and autoDNS: #248
    - This fixes various issues with Docker Toolbox and DNS resolution: #119


## v1.0.0-alpha1 [unreleased]

#### Changed
- Use Docker volumes instead of directory mounts for stateful data (MySQL, PgSQL and MongoDB)
    - This fixes various mount issues on Windows: #175 #382
    - This improves general performance
- Use Official MySQL, MariaDB and Percona Docker container


## v0.15.0 [unreleased]

#### Added
- [CHANGELOG](CHANGELOG.md) by the standard of: https://keepachangelog.com
- [UPDATING](UPDATING.md) provides information how to update between major versions
- HTTP/2 support
- Reverse Proxy support
- Autostart scripts
- Allow to enable/disable PHP modules
- Intranet vendors
    - PHPRedMin
- Mount options for volumes
- Docker Compose images:
    - PHP 5.2
    - PHP 5.3
    - PHP 7.4
    - Alpine images where possible
- Docker Compose overwrite images:
    - Blackfire
    - MailHog
    - RabbitMQ
    - Solr
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
    - Shopware CMS
    - Presta Shop
    - Photon CMS
    - Codeignitor
    - Typo3
    - Craft CMS
    - NodeJS Reverse proxy
    - Sphinx documentation Reverse proxy
- GitHub Issue templates
- Discourse forum link: https://devilbox.discourse.group

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

#### Fixed
- break on errors in wrong vhost-gen overwrite
- XSS vulnerability in email display
- Various fixes in Documentation
- vhost-gen fixes
