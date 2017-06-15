# Devilbox Documentation

[Overview](README.md) |
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
FAQ

---

## FAQ


**How do I backup my MySQL database?**

1. Use phpMyAdmin
2. Use Adminer
3. use `mysqldump` from your host computer
4. use `mysqldump` from within the php container

**Why are mounted MySQL data directories separated by version?**

This is just a pre-caution. Imagine they would link to the same datadir. You start the devilbox with mysql 5.5, create a database and add some data. Now you decide to switch to mysql 5.7 and restart the devilbox. The newer mysql version will probably upgrade the data leaving it unable to start with older mysql versions.

**Why are mounted PostgreSQL data directories separated by version?**

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

*apc, apcu, bcmath, bz2, calendar, Core, ctype, curl, date, dom, ereg, exif, fileinfo, filter, ftp, gd, gettext, gmp, hash, iconv, igbinary, imagick, imap, intl, json, ldap, libxml, magickwand, mbstring, mcrypt, memcache, memcached, mhash, mongodb, msgpack, mysql, mysqli, mysqlnd, openssl, pcntl, pcre, PDO, pdo_mysql, pdo_pgsql, pdo_sqlite, pgsql, phalcon, Phar, posix, pspell, readline, recode, redis, Reflection, session, shmop, SimpleXML, soap, sockets, SPL, sqlite3, standard, sysvmsg, sysvsem, sysvshm, tidy, tokenizer, uploadprogress, wddx, xdebug, xml, xmlreader, xmlrpc, xmlwriter, xsl, Zend OPcache, zip, zlib*

There will however be slight differences between the versions and especially with HHVM. To see the exact bundled modules for each version visit the corresponding docker repositories on Github:

[PHP 5.4](https://github.com/cytopia/docker-php-fpm-5.4) |
[PHP 5.5](https://github.com/cytopia/docker-php-fpm-5.5) |
[PHP 5.6](https://github.com/cytopia/docker-php-fpm-5.6) |
[PHP 7.0](https://github.com/cytopia/docker-php-fpm-7.0) |
[PHP 7.1](https://github.com/cytopia/docker-php-fpm-7.1) |
[HHVM](https://github.com/cytopia/docker-hhvm-latest)


**Can I add other PHP Modules?**

Yes, if there are any PHP modules you require that are not yet available in the PHP Docker container, you can install it during run-time, or create your own container. See [Hacking](Hacking.md) for more informatino.

**Can I change php.ini?**

Yes, php.ini directives can be changes on a per PHP version base. Go to `./cfg/` inside devilbox git diretory. There you will find configuration directories for each php version. Just put a \*.ini file there and restart the devilbox.

**Can I change my.cnf?**

Yes, my.cnf directives can be changes on a per MySQL version base. Go to `./cfg/` inside devilbox git diretory. There you will find configuration directories for each MySQL version. Just put a \*.cnf file there and restart the devilbox.

**Can I switch HHVM between PHP 5.6 and PHP 7 mode?**

Yes, this can be done by adding a \*.ini file to `./cfg/hhvm-latest/` with the following content: `hhvm.php7.all = 0` to disable PHP 7. The default is to use PHP 7 mode.
