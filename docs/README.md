# Devilbox Documentation

Overview |
[Quickstart](Quickstart.md) |
[Install](Install.md) |
[Update](Update.md) |
[Configure](Configure.md) |
[Run](Run.md) |
[Usage](Usage.md) |
[Backups](Backups.md) |
[Examples](Examples.md) |
[Technical](Technical.md) |
[Hacking](Hacking.md) |
[FAQ](FAQ.md)

---

## Overview

1. [Main idea](#1-main-idea)
2. [Features](#2-features)
3. [Supported Host OS](#3-supported-host-os)
4. [Requirements](#4-requirements)
5. [Docker documentation](#5-docker-documentation)
6. [Devilbox documentation](#6-devilbox-documentation)
7. [Video Tutorials](#7-video-tutorials)
8. [Available PHP Modules](#8-available-php-modules)
9. [Supported Frameworks and CMS](#9-supported-frameworks-and-cms)

---

## 1. Main idea

The devilbox allows you to have an unlimitted number of projects ready without having to install any external software and without having to configure any virtual hosts. As well as providing a very flexible development stack that you can run offline. (Internet is only required to initially pull docker container).

The only thing you will have to do is to create a new folder on the filesystem and your virtual host is ready to be served with your custom domain.

The default project catch-all domain is `*.dev` (see [Configure](Configure.md) for how to change it). Let's view an example:

```shell
# Inside your main project folder
$ ls -l
drwxr-xr-x 3 cytopia 4096 Jun 10 13:10 my-drupal
drwxr-xr-x 3 cytopia 4096 Jun 10 13:10 my-wordpress
drwxr-xr-x 3 cytopia 4096 Jun 10 13:10 project1
drwxr-xr-x 3 cytopia 4096 Jun 10 13:10 project2
drwxr-xr-x 3 cytopia 4096 Jun 10 13:10 yii-test
```

By having the above folders, the devilbox will automatically be able to serve the following vhosts:

* http://my-drupal.dev
* http://my-wordpress.dev
* http://project1.dev
* http://project2.dev
* http://yii-test.dev

New folders can be created, deleted and removed during run-time and corresponding virtual hosts will be available instantly without having to restart anything.

## 2. Features

| Feature | Description |
|---------|-------------|
| **Internet** | |
| No always-on | Internet connection is only required during initial setup or update (to pull containers), afterwards you can always work offline. |
| **Projects** | |
| Unlimitted Projects | Add as many Projects as you need. |
| Auto VirtualHosts | New VirtualHosts are added instantly without a restart or reload. Just create a new directory and you are ready to go. |
| Auto DNS | Use the built-in DNS server to stop worrying about `/etc/hosts` setup per project. |
| Email catch-all | All outgoing emails are intercepted and stored locally. Use the intranet to view any sent email. |
| Custom VirtualHost domains | Whatever project domain you desire: `*.dev`, `*.loc`, `*.local` or even subdomains like `*.sub.example` - you can adjust it to your needs. |
| **Run** | |
| Selective start | Run only the Docker container you actually need. |
| Version choice  | Use your development stack with whatever version combination needed. |
| Stack choice  | Attach SQL or NoSQL container and use Nginx or Apache to simulate your live env. |
| Log files | Log files are available for each chosen version. |
| **Configuration** | |
| HHVM | You can choose between PHP 5.6 and PHP 7 mode for HHVM |
| php.ini | You an overwrite PHP settings for each PHP version. |
| my.cnf | You an overwrite MySQL settings for each MySQL version. |
| **Intranet** | |
| phpMyAdmin | Manage your MySQL databases here. |
| Adminer | Manage your SQL and NoSQL databases here. |
| OpCacheGUI | Visualize the state of opcache usage. |
| EmailGUI | See all sent emails at a glance |
| **Docker Tools**|
| Work inside container | You can completely work inside the PHP container and use all bundled tools in order to keep your host system clean. |
| **Hacking** |
| Add custom container | You can add any other Docker container to `docker-compose.yml` and start using them in your development stack. |


## 3. Supported Host OS

The devilbox runs on all major operating systems. Below you can quickly check the recommended docker versions and current issues per OS.

|             | ![linux][lin-logo]   | ![windows][win-logo]      | ![osx][osx-logo]     |
|-------------|----------------------|---------------------------|----------------------|
| **Docker Version** | normal               | [Docker for Windows][d4w] | [Docker dor Mac][d4m]|
| **Current Issues**  | [here][lin-issues]   | [here][win-issues]        | [here][osx-issues]   |

[win-logo]: https://raw.githubusercontent.com/cytopia/icons/master/64x64/windows.png
[lin-logo]: https://raw.githubusercontent.com/cytopia/icons/master/64x64/linux.png
[osx-logo]: https://raw.githubusercontent.com/cytopia/icons/master/64x64/osx.png
[d4w]: https://docs.docker.com/docker-for-windows/install/
[d4m]: https://docs.docker.com/docker-for-mac/install/
[dtb]: https://docs.docker.com/toolbox/overview/
[win-issues]: https://github.com/cytopia/devilbox/issues?utf8=%E2%9C%93&q=is%3Aissue%20is%3Aopen%20label%3A%22host%3Awindows%22
[lin-issues]: https://github.com/cytopia/devilbox/issues?utf8=%E2%9C%93&q=is%3Aissue%20is%3Aopen%20label%3A%22host%3Alinux%22
[osx-issues]: https://github.com/cytopia/devilbox/issues?utf8=%E2%9C%93&q=is%3Aissue%20is%3Aopen%20label%3A%22host%3Aosx%22


## 4. Requirements

* **Internet connection** - only required during initial setup for cloning the devilbox repository and pulling the required docker container. Afterwards you can always work offline.
* [Docker Engine 1.12.0+](https://docs.docker.com/compose/compose-file/compose-versioning/#version-21)
* [Docker Compose 1.6.0+](https://docs.docker.com/compose/compose-file/compose-versioning/#version-2)
* On Windows use [Docker for Windows][d4w] (not tested on [Docker Toolbox][dtb])
* On OSX use [Docker for Mac][d4m] (not tested on [Docker Toolbox][dtb])


## 5. Docker documentation

If you have never worked with docker/docker-compose before, you should check up on their documentation to get you started: [docker docs](https://docs.docker.com/).


## 6. Devilbox documentation

| Topic                   | Description |
|-------------------------|-------------|
| **Installing**          | How to install docker, docker-compose and the devilbox |
| **Updating**            | Update best practise |
| **Configuration**       | How to configure the devilbox, switch versions (PHP, MySQL, PgSQL, ...)  and how to set custom options (php.ini, my.cnf, httpd.conf, ...) |
| **[Run](Run.md)**       | How to operate the devilbox, start and stop all or only required Docker container. |
| **[Usage](Usage.md)**   | How to create projects, Email and DNS usage, tools (`composer`, `npm`, `node`, `drush`, ...), entering the container, Log files, Xdebug, Backups, Intranet, ...|
| **[Examples](Examples.md)** | Some project examples for popular CMS/Frameworks. How to setup Wordpress, Drupal, Yii, ... |
| **Technical**            | Technical background information |
| **[Hacking](Hacking.md)**| How to extend the devilbox with your own docker container |
| **[FAQ](FAQ.md)**        | Questions and Troubleshooting |


## 7. Video Tutorials

Have a look at youtube to see some the features in action.

[![Devilbox setup and workflow](img/devilbox_01-setup-and-workflow.png "devilbox - setup and workflow")](https://www.youtube.com/watch?v=reyZMyt2Zzo) 
[![Devilbox email catch-all](img/devilbox_02-email-catch-all.png "devilbox - email catch-all")](https://www.youtube.com/watch?v=e-U-C5WhxGY)


## 8. Available PHP Modules

The devilbox is a development stack, so it is made sure that a lot of PHP modules are available out of the box in order to work with many different frameworks.

*apc, apcu, bcmath, bz2, calendar, Core, ctype, curl, date, dom, ereg, exif, fileinfo, filter, ftp, gd, gettext, gmp, hash, iconv, igbinary, imagick, imap, intl, json, ldap, libxml, magickwand, mbstring, mcrypt, memcache, memcached, mhash, mongodb, msgpack, mysql, mysqli, mysqlnd, openssl, pcntl, pcre, PDO, pdo_mysql, pdo_pgsql, pdo_sqlite, pgsql, phalcon, Phar, posix, pspell, readline, recode, redis, Reflection, session, shmop, SimpleXML, soap, sockets, SPL, sqlite3, standard, sysvmsg, sysvsem, sysvshm, tidy, tokenizer, uploadprogress, wddx, xdebug, xml, xmlreader, xmlrpc, xmlwriter, xsl, Zend OPcache, zip, zlib*

There will however be slight differences between the versions and especially with HHVM. To see the exact bundled modules for each version visit the corresponding docker repositories on Github:

[PHP 5.4](https://github.com/cytopia/docker-php-fpm-5.4) |
[PHP 5.5](https://github.com/cytopia/docker-php-fpm-5.5) |
[PHP 5.6](https://github.com/cytopia/docker-php-fpm-5.6) |
[PHP 7.0](https://github.com/cytopia/docker-php-fpm-7.0) |
[PHP 7.1](https://github.com/cytopia/docker-php-fpm-7.1) |
[HHVM](https://github.com/cytopia/docker-hhvm-latest)

## 9. Supported Frameworks and CMS

As far as tested there are no limitations and you can use any Framework or CMS just as you would on your live environment. Below are a few examples of extensively tested Frameworks and CMS:

![CakePHP](img/logos/cake.png)
![Drupal](img/logos/drupal.png)
![Phalcon](img/logos/phalcon.png)
![Wordpress](img/logos/wordpress.png)
![Yii](img/logos/yii.png)
