# Devilbox Documentation

[Overview](README.md) |
[Quickstart](Quickstart.md) |
[Install](Install.md) |
[Update](Update.md) |
[Configure](Configure.md) |
[Run](Run.md) |
[Usage](Usage.md) |
[OS](OS.md) |
[Backups](Backups.md) |
[Examples](Examples.md) |
[Technical](Technical.md) |
[Hacking](Hacking.md) |
FAQ

---

## FAQ

1. [General](#1-general)
2. [Configuration](#2-configuration)
3. [Usage](#3-usage)
4. [Troubleshooting](#4-troubleshooting)

---

## 1. General

**Are there any differences between Linux, Windows and OSX?**

Yes, have a look at **[OS](OS.md)** to read up about the differences.

**Why are mounted MySQL data directories separated by version?**

This is just a pre-caution. Imagine they would link to the same datadir. You start the devilbox with mysql 5.5, create a database and add some data. Now you decide to switch to mysql 5.7 and restart the devilbox. The newer mysql version will probably upgrade the data leaving it unable to start with older mysql versions.

**Why are mounted PostgreSQL data directories separated by version?**

See: *Why are mounted MySQL data directories separated by version?*

**Why are mounted MongoDB data directories separated by version?**

See: *Why are mounted MySQL data directories separated by version?*

**Why do the user/group permissions of log/ or cfg/ directories show 1000?**

Uid and Gid are set to 1000 by default. You can alter them to match the uid/gid of your current user. Open the `.env` file and change the sections `NEW_UID` and `NEW_GID`. When you start up the devilbox, the php-container will use these values for its user.

**Can I not just comment out the service in the `.env` file?**

No, don't do this. This will lead to unexpected behaviour (different versions will be loaded).
The `.env` file allows you to configure the devilbox, but not to start services selectively.

**Are there any required services that must/will always be started?**

Yes. `http` and `php` will automatically always be started (due to dependencies inside `docker-compose.yml`) if you specify them or not.

**What PHP Modules are available?**

The devilbox is a development stack, so it is made sure that a lot of PHP modules are available out of the box in order to work with many different frameworks.

> *apc, apcu, bcmath, bz2, calendar, Core, ctype, curl, date, dom, ereg, exif, fileinfo, filter, ftp, gd, gettext, gmp, hash, iconv, igbinary, imagick, imap, intl, json, ldap, libxml, magickwand, mbstring, mcrypt, memcache, memcached, mhash, mongodb, msgpack, mysql, mysqli, mysqlnd, openssl, pcntl, pcre, PDO, pdo_mysql, pdo_pgsql, pdo_sqlite, pgsql, phalcon, Phar, posix, pspell, readline, recode, redis, Reflection, session, shmop, SimpleXML, soap, sockets, SPL, sqlite3, standard, sysvmsg, sysvsem, sysvshm, tidy, tokenizer, uploadprogress, wddx, xdebug, xml, xmlreader, xmlrpc, xmlwriter, xsl, Zend OPcache, zip, zlib*

There will however be slight differences between the versions and especially with HHVM. To see the exact bundled modules for each version visit the corresponding docker repositories on Github:

[PHP 5.4](https://github.com/cytopia/docker-php-fpm-5.4) |
[PHP 5.5](https://github.com/cytopia/docker-php-fpm-5.5) |
[PHP 5.6](https://github.com/cytopia/docker-php-fpm-5.6) |
[PHP 7.0](https://github.com/cytopia/docker-php-fpm-7.0) |
[PHP 7.1](https://github.com/cytopia/docker-php-fpm-7.1) |
[PHP 7.2](https://github.com/cytopia/docker-php-fpm-7.2) |
[HHVM](https://github.com/cytopia/docker-hhvm-latest)


## 2. Configuration

**Can I change the MySQL root password?**

Yes, you can change the password of the MySQL root user. If you do so, you must also set the new password in your `.env` file. See **[Configure](Configure.md)** for how to change the values.

**Can I add other PHP Modules?**

Yes, if there are any PHP modules you require that are not yet available in the PHP Docker container, you can install it during run-time, or create your own container. See **[Hacking](Hacking.md)** for more information.

**Can I change php.ini?**

Yes, php.ini directives can be changes on a per PHP version base. Go to `./cfg/` inside devilbox git diretory. There you will find configuration directories for each php version. Just put a \*.ini file there and restart the devilbox.

**Can I change my.cnf?**

Yes, my.cnf directives can be changes on a per MySQL version base. Go to `./cfg/` inside devilbox git diretory. There you will find configuration directories for each MySQL version. Just put a \*.cnf file there and restart the devilbox.

**Can I switch HHVM between PHP 5.6 and PHP 7 mode?**

Yes, this can be done by adding a \*.ini file to `./cfg/hhvm-latest/` with the following content to disable PHP 7 mode:

```ini
hhvm.php7.all = 0
```
The default is to use PHP 7 mode.

**Can I change the project virtual host domain `.loc`?**

Yes, the `.env` variable `TLD_SUFFIX` can be adjusted with whatever domain or subdomain your require. A few examples to get you started:

| Project folder | TLD_SUFFIX | Project URL              |
|----------------|------------|--------------------------|
| project1       | loc        | http://project1.loc      |
| project1       | local      | http://project1.local    |
| project1       | dev        | http://project1.dev      |
| project1       | work.loc   | http://project1.work.loc |

**Can I just start PHP and MySQL instead of all container?**

Yes, every Docker container is optional. The devilbox allows for selective startup. See **[Run: selective start](Run.md#13-selective-start)** for more detail.

**Do I always have to edit `/etc/hosts` for new projects?**

You need a valid DNS entry for every project that points to the Httpd server. As those records don't exists by default, you will have to create them. However, the devilbox has a bundled DNS server that can automate this for you. The only thing you have to do for that to work is to add this DNS server's IP address to your `/etc/resolv.conf`. See **[Configure: AutoDNS](Configure.md#62-auto-dns)** for instructions.

## 3. Usage

**Does it work with CakePHP?**

Yes, see **[How to setup CakePHP](Examples.md#2-setup-cakephp)**.

**Does it work with Drupal?**

Yes, see **[How to setup Drupal](Examples.md#3-setup-drupal)**.

**Does it work with Laravel?**

Yes, see **[How to setup Laravel](Examples.md#4-setup-laravel)**.

**Does it work with PhalconPHP?**

Yes, see **[How to setup Phalcon](Examples.md#5-setup-phalcon)**.

**Does it work with Symfony?**

Yes, see **[How to setup Symfony](Examples.md#6-setup-symfony)**.

**Does it work with Wordpress?**

Yes, see **[How to setup Wordpress](Examples.md#7-setup-wordpress)**.

**Does it work with Yii?**

Yes, see **[How to setup Yii](Examples.md#8-setup-yii)**.

**Does it work with Zend Framework?**

Yes, see **[How to setup Zend](Examples.md#9-setup-zend)**.


## 4. Troubleshooting

**`Invalid bind mount spec` after changing the path of MySQL, PgSQL, Mongo or the Data dir.**

When you change any paths inside `.env` that affect Docker mountpoints, the container need to be *removed* and re-created during the next startup. *Removing* the container is sufficient as they will always be created during run if they don't exist.

In order to *remove* the container do the following:

```shell
$ docker-compose stop

# Remove the stopped container (IMPORTANT!)
# After the removal it will be re-created during next run
$ docker-compose rm -f
```
