# Devilbox

| **Overview** | **Installing** | **Updating** | **Configuration** | **Usage** | **Technical** | **FAQ** |

---


### Dockerized LAMP/MEAN stack

<p class="center">The devilbox is a modern and highly customisable LAMP and MEAN stack replacement based purely on docker and docker-compose running on all major platforms. It supports an unlimited number of projects for which vhosts and DNS records are created automatically. Email catch-all and popular development tools will be at your service as well.</p>

<img src="img/devilbox-dash.png" alt="Devilbox" style="max-width:100%"/>


### Supported Host OS

<p class="center">Don't worry about switching computers. The devilbox will run on all major operating systems.</p>

<div class="center">
  <img class="battery" style="height:64px;" title="Linux support" alt="Linux support" src="https://raw.githubusercontent.com/cytopia/icons/master/64x64/linux.png" />
  <img class="battery" style="height:64px;" title="OSX support" alt="OSX support" src="https://raw.githubusercontent.com/cytopia/icons/master/64x64/osx.png" />
  <img class="battery" style="height:64px;" title="Windows support" alt="Windows support" src="https://raw.githubusercontent.com/cytopia/icons/master/64x64/windows.png" />
</div>


### Install, Configure and Start

<p class="center">Your whole development stack is up and running in a few simple steps.</p>

```shell
# Get the soures
$ git clone https://github.com/cytopia/devilbox
$ cd devilbox

# Create and customize the config file
$ cp env-example .env
$ vim .env

# Start your daemons
$ docker-compose up
```


### Run exactly what you need

<p class="center">Choose your required daemons and select a version. Any combination is possible.<br/>This will allow you, to always exactly simulate your production environment locally during development.</p>

<table>
  <thead>
    <tr>
      <th>Apache</th>
      <th>Nginx</th>
      <th>PHP</th>
      <th>MySQL</th>
      <th>MariaDB</th>
      <th>PgSQL</th>
      <th>Redis</th>
      <th>Memcached</th>
      <th>MongoDB</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><a target="_blank" title="Apache 2.2"       href="https://github.com/cytopia/docker-apache-2.2">2.2</a></td>
      <td><a target="_blank" title="Nginx stable"     href="https://github.com/cytopia/docker-nginx-stable">stable</a></td>
      <td><a target="_blank" title="PHP 5.4"          href="https://github.com/cytopia/docker-php-fpm-5.4">5.4</a></td>
      <td><a target="_blank" title="MySQL 5.5"        href="https://github.com/cytopia/docker-mysql-5.5">5.5</a></td>
      <td><a target="_blank" title="MariaDB 5.5"      href="https://github.com/cytopia/docker-mariadb-5.5">5.5</a></td>
      <td><a target="_blank" title="PgSQL 9.1"        href="https://github.com/docker-library/postgres">9.1</a></td>
      <td><a target="_blank" title="Redis 2.8"        href="https://github.com/docker-library/redis">2.8</a></td>
      <td><a target="_blank" title="Memcached 1.4.21" href="https://github.com/docker-library/memcached">1.4.21</a></td>
      <td><a target="_blank" title="MongoDB 2.8"      href="https://github.com/docker-library/mongo">2.8</a></td>
    </tr>
    <tr>
      <td><a target="_blank" title="Apache 2.4"       href="https://github.com/cytopia/docker-apache-2.4">2.4</a></td>
      <td><a target="_blank" title="Nginx mainline"   href="https://github.com/cytopia/docker-nginx-mainline">mainline</a></td>
      <td><a target="_blank" title="PHP 5.5"          href="https://github.com/cytopia/docker-php-fpm-5.5">5.5</a></td>
      <td><a target="_blank" title="MySQL 5.6"        href="https://github.com/cytopia/docker-mysql-5.6">5.6</a></td>
      <td><a target="_blank" title="MariaDB 10.0"     href="https://github.com/cytopia/docker-mariadb-10.0">10.0</a></td>
      <td><a target="_blank" title="PgSQL 9.2"        href="https://github.com/docker-library/postgres">9.2</a></td>
      <td><a target="_blank" title="Redis 3.0"        href="https://github.com/docker-library/redis">3.0</a></td>
      <td><a target="_blank" title="Memcached 1.4.22" href="https://github.com/docker-library/memcached">1.4.22</a></td>
      <td><a target="_blank" title="MongoDB 3.0"      href="https://github.com/docker-library/mongo">3.0</a></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><a target="_blank" title="PHP 5.6"          href="https://github.com/cytopia/docker-php-fpm-5.6">5.6</a></td>
      <td><a target="_blank" title="MySQL 5.7"        href="https://github.com/cytopia/docker-mysql-5.7">5.7</a></td>
      <td><a target="_blank" title="MariaDB 10.1"     href="https://github.com/cytopia/docker-mariadb-10.1">10.1</a></td>
      <td><a target="_blank" title="PgSQL 9.3"        href="https://github.com/docker-library/postgres">9.3</a></td>
      <td><a target="_blank" title="Redis 3.2"        href="https://github.com/docker-library/redis">3.2</a></td>
      <td><a target="_blank" title="Memcached 1.4.23" href="https://github.com/docker-library/memcached">1.4.23</a></td>
      <td><a target="_blank" title="MongoDB 3.2"      href="https://github.com/docker-library/mongo">3.2</a></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><a target="_blank" title="PHP 7.0"          href="https://github.com/cytopia/docker-php-fpm-7.0">7.0</a></td>
      <td><a target="_blank" title="MySQL 8.0"        href="https://github.com/cytopia/docker-mysql-8.0">8.0</a></td>
      <td><a target="_blank" title="MariaDB 10.2"     href="https://github.com/cytopia/docker-mariadb-10.2">10.2</a></td>
      <td><a target="_blank" title="PgSQL 9.4"        href="https://github.com/docker-library/postgres">9.4</a></td>
      <td></td>
      <td>...</td>
      <td><a target="_blank" title="MongoDB 3.4"      href="https://github.com/docker-library/mongo">3.4</a></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><a target="_blank" title="PHP 7.1"          href="https://github.com/cytopia/docker-php-fpm-7.1">7.1</a></td>
      <td></td>
      <td><a target="_blank" title="MariaDB 10.3"     href="https://github.com/cytopia/docker-mariadb-10.3">10.3</a></td>
      <td><a target="_blank" title="PgSQL 9.5"        href="https://github.com/docker-library/postgres">9.5</a></td>
      <td></td>
      <td><a target="_blank" title="Memcached 1.4.36" href="https://github.com/docker-library/memcached">1.4.36</a></td>
      <td><a target="_blank" title="MongoDB 3.5"      href="https://github.com/docker-library/mongo">3.5</a></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><a target="_blank" title="HHVM"             href="https://github.com/cytopia/docker-hhvm-latest">HHVM</a></td>
      <td></td>
      <td></td>
      <td><a target="_blank" title="PgSQL 9.6"        href="https://github.com/docker-library/postgres">9.6</a></td>
      <td></td>
      <td><a target="_blank" title="Memcached latest" href="https://github.com/docker-library/memcached">latest</a></td>
      <td></td>
    </tr>
  </tbody>
</table>


### Run only what you need

<p class="center">You are not forced to load the whole stack everytime. Only bring up what you really need.<br/>It is also possible to add or remove daemons while the stack is already running.</p>

```shell
# Load traditional lamp stack only
$ docker-compose up httpd php mysql

# Add redis to the running stack
$ docker-compose up redis

# Stop MySQL from the current stack
$ docker-compose stop mysql
```


### Introduction Videos

<p class="center">Head over to youtube for a quick introduction and see for yourself how easily new projects can be created.</p>

<div class="center">
  <a target="_blank" href="https://www.youtube.com/watch?v=reyZMyt2Zzo" alt="Devilbox introduction video" title="Devilbox introduction video"><img src="img/devilbox_01-setup-and-workflow.png" /></a>
  <a target="_blank" href="https://www.youtube.com/watch?v=e-U-C5WhxGY" alt="Devilbox Email catch-all introduction" title="Devilbox Email catch-all introduction"><img src="img/devilbox_02-email-catch-all.png" /></a>
</div>


### PHP Modules

<div class="center">
  <p>The devilbox is a development stack, so it is made sure that a lot of PHP modules are available out of the box in order to work with many different frameworks. There will however be slight differences between the versions and especially with HHVM. To see the exact bundled modules for each version visit the corresponding docker repositories on Github:</p>

  <img class="battery" style="height:64px;" title="PHP" alt="PHP" src="img/logos/php.png" />
  <img class="battery" style="height:64px;" title="HHVM" alt="HHVM" src="img/logos/hhvm.png" />

  <p>
  <strong><a target="_blank" title="PHP 5.4" href="https://github.com/cytopia/docker-php-fpm-5.4">PHP 5.4</a></strong> |
  <strong><a target="_blank" title="PHP 5.5" href="https://github.com/cytopia/docker-php-fpm-5.5">PHP 5.5</a></strong> |
  <strong><a target="_blank" title="PHP 5.6" href="https://github.com/cytopia/docker-php-fpm-5.5">PHP 5.6</a></strong> |
  <strong><a target="_blank" title="PHP 7.0" href="https://github.com/cytopia/docker-php-fpm-5.5">PHP 7.0</a></strong> |
  <strong><a target="_blank" title="PHP 7.1" href="https://github.com/cytopia/docker-php-fpm-5.5">PHP 7.1</a></strong> |
  <strong><a target="_blank" title="HHVM" href="https://github.com/cytopia/docker-hhvm-latest">HHVM</a></strong>
  </p>
<p>apc, apcu, bcmath, bz2, calendar, Core, ctype, curl, date, dom, ereg, exif, fileinfo, filter, ftp, gd, gettext, gmp, hash, iconv, igbinary, imagick, imap, intl, json, ldap, libxml, magickwand, mbstring, mcrypt, memcache, memcached, mhash, mongodb, msgpack, mysql, mysqli, mysqlnd, openssl, pcntl, pcre, PDO, pdo_mysql, pdo_pgsql, pdo_sqlite, pgsql, phalcon, Phar, posix, pspell, readline, recode, redis, Reflection, session, shmop, SimpleXML, soap, sockets, SPL, sqlite3, standard, sysvmsg, sysvsem, sysvshm, tidy, tokenizer, uploadprogress, wddx, xdebug, xml, xmlreader, xmlrpc, xmlwriter, xsl, Zend OPcache, zip, zlib</p>
</div>


### Email catch-all

<div class="center">
  <img class="battery" style="height:64px;" title="Email catch-all" alt="Email catch-all" src="img/logos/email.png"/>
  <p>The built-in postfix mailserver is configured to automatically intercept all outgoing emails. This is an important measurement during development to make sure not to accidentally send out real emails. Instead you will be able to see all sent emails in the included intranet mail view. See Intranet section below.</p>
</div>


### Auto-DNS

<div class="center">
  <img class="battery" style="height:64px;" title="Auto-DNS" alt="Auto-DNS" src="img/logos/dns.png" />
  <p>Creating a new project just requires you to create a new folder on the filesystem. As you probalby don't want to bother with editing your /etc/hosts file everytime, the built-in DNS server will automatically provide the correct DNS records for every project.</p>
</div>


### Batteries included

<p class="center">No need to download external tools. Everything is bundled, up-to-date and available inside the containers.</p>

<div class="center">
  <a target="_blank" title="phpMyAdmin" href="https://www.phpmyadmin.net"><img class="battery" style="height:64px;" src="img/logos/phpmyadmin.png" alt="Devilbox"/></a>
  <a target="_blank" title="Adminer" href="https://www.adminer.org"><img class="battery" style="height:64px;" src="img/logos/adminer.png" alt="Devilbox"/></a>
  <a target="_blank" title="OpCache GUI" href="https://github.com/amnuts/opcache-gui"><img class="battery" style="height:64px;" src="img/logos/opcachegui.png" alt="Devilbox"/></a> 
  <br/> 
  <a target="_blank" title="Composer" href="https://getcomposer.org"><img class="battery" style="height:64px;" src="img/logos/composer.png" alt="Devilbox"/></a>
  <a target="_blank" title="Drush" href="https://www.drupal.org/project/drush"><img class="battery" style="height:64px;" src="img/logos/drush.png" alt="Devilbox"/></a>
  <a target="_blank" title="Drupal Console" href="https://drupalconsole.com"><img class="battery" style="height:64px;" src="img/logos/drupal-console.png" alt="Devilbox"/></a>
  <a target="_blank" title="NodeJS" href="https://nodejs.org"><img class="battery" style="height:64px;" src="img/logos/nodejs.png" alt="Devilbox"/></a>
  <a target="_blank" title="WP-CLI" href="https://wp-cli.org"><img class="battery" style="height:64px;" src="img/logos/wp-cli.png" alt="Devilbox"/></a>
  <a target="_blank" title="NPM" href="https://www.npmjs.com"><img class="battery" style="height:64px;" src="img/logos/npm.png" alt="Devilbox"/></a>
  <a target="_blank" title="Git" href="https://git-scm.com"><img class="battery" style="height:64px;" src="img/logos/git.png" alt="Devilbox"/></a>
</div>


### Supported Frameworks and CMS

<p class="center">There is nothing special about the devilbox, so any framework or CMS that will work with normal LAMP/MEAN stacks will work here as well. However in order to make double sure, a few popular applications have been explicitly tested.</p>

<div class="center">
  <a target="_blank" title="CakePHP" href="https://cakephp.org" ><img alt="CakePHP" class="battery" style="height:64px;" src="img/logos/cake.png" /></a>
  <a target="_blank" title="Drupal" href="https://www.drupal.org/" ><img alt="Drupal" class="battery" style="height:64px;" src="img/logos/drupal.png" /></a>
  <a target="_blank" title="PhalconPHP" href="https://phalconphp.com" ><img alt="PhalconPHP" class="battery" style="height:64px;" src="img/logos/phalcon.png" /></a>
  <a target="_blank" title="Wordpress" href="https://wordpress.org" ><img alt="Wordpress" class="battery" style="height:64px;" src="img/logos/wordpress.png" /></a>
  <a target="_blank" title="Yii" href="http://www.yiiframework.com" ><img alt="Yii" class="battery" style="height:64px;" src="img/logos/yii.png" /></a>
</div>


### Devilbox Intranet

<p class="center">Once the devilbox is up and running, you can visit the bundled intranet on <a target="_blank" href="http://localhost">http://localhost</a>.<br/>The intranet is not just a simple dash, it provides many useful tools:</p>

<div class="center">
  Container Health | DNS Status | Available vHosts | Emails | Databases | Effective Configuration<br/>
  <img alt="" title="" src="img/02_intranet_vhosts.png "/>
  <img alt="" title="" src="img/04_intranet_emails.png "/>
</div>


### Security

<p class="center">Be aware that the docker service is running with root privileges on your system (like any other webserver for example). The devilbox is using a mix of official docker images and custom images. All integrated containers are available on <a target="_blank" href="https://github.com/cytopia/devilbox#run-time-matrix">Github</a> and can be reviewed at any time.</p>


### Up-to-dateness

<p class="center">Docker containers are pushed to <a target="_blank" href="https://hub.docker.com/r/cytopia">Docker Hub</a> frequently.<br/>It should be enough for you to pull updated images on a regeular basis.</p>

```shell
$ docker-compose pull
```

<p class="center">However, if a new minor version (PHP for example) has just been released and you want to use it right away, you can simply *git clone* the docker repository and rebuild the container. Each container repository contains a shell script for easy building.</p>

```shell
# Download PHP 7.1 repository
$ git clone https://github.com/cytopia/docker-php-fpm-7.1

# Rebuild the container in order to get the latest minor/patch version
$ cd docker-php-fpm-7.1
$ ./build/docker-rebuild.sh
```


### Integration Tests

<div class="center">
  <a target="_blank" href="https://travis-ci.org/cytopia/devilbox"><img src="https://travis-ci.org/cytopia/devilbox.svg?branch=master" /></a>
</div>

<p class="center">In order to make sure everything always runs stable and as expected, the devilbox makes heavy use of integration tests. You can head over to <a target="_blank" href="https://travis-ci.org/cytopia/devilbox">Travis-CI</a> and have a look at stable and nightly builds.</p>


### Contribute

<div class="center">
  <p>Contributers are welcome in any way.</p>
  
  <p>First of all, if you like the project, please <a href="https://github.com/cytopia/devilbox">do star it</a>. Starring is an important measurement to see the number of active users and better allows me to organize my time and effort I can put into this project.</p>
  
  <p>You can also get actively involved. <a href="https://github.com/cytopia/devilbox">Do clone the project</a> and start improving whatever you think is useful. There is quite a lot todo and planned. If you like to contribute, view <a href="https://github.com/cytopia/devilbox/blob/master/CONTRIBUTING.md">CONTRIBUTING.md</a> and <a href="https://github.com/cytopia/devilbox/issues/23">ROADMAP</a>.</p>
  
  <p>Major contributors will be credited within the intranet and on the github page.</p>
</div>


### License

<div class="center">
  <p>MIT License</p>
  <p>Copyright (c) 2016 cytopia</p>
</div>


