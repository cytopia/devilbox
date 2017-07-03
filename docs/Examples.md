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
Examples |
[Technical](Technical.md) |
[Hacking](Hacking.md) |
[FAQ](FAQ.md)

---

## Examples

1. [Introduction](#1-introduction)
2. [Setup CakePHP](#2-setup-cakephp)
3. [Setup Drupal](#3-setup-drupal)
4. [Setup Laravel](#4-setup-laravel)
5. [Setup Phalcon](#5-setup-phalcon)
6. [Setup Symfony](#6-setup-symfony)
7. [Setup Wordpress](#7-setup-wordpress)
8. [Setup Yii](#8-setup-yii)
9. [Setup Zend](#9-setup-zend)

---

## 1. Introduction

The devilbox provides popular tools for setting up and managing major frameworks or content management systems. The following bundled tools are available:

| Binary     | Tool name         | Framework/CMS      |
|------------|-------------------|--------------------|
| `composer` | [composer](https://getcomposer.org)      | CakePHPi, Symfony, Yii, Zend and others |
| `drush`    | [drush](http://www.drush.org/)           | Drupal             |
| `drupal`   | [drupal-consol](https://drupalconsole.com) | Drupal           |
| `git`      | [git](https://git-scm.com) | Everything available on github and other git servers |
| `laravel`  | [laravel installer](https://github.com/laravel/installer) | Laravel |
| `phalcon`  | [phalcon devtools](https://github.com/phalcon/phalcon-devtools) | Phalcon         |
| `symfony`  | [symfony installer](https://github.com/symfony/symfony-installer) | Symfony       |
| `wp`       | [wp-cli](https://wp-cli.org/)            | Wordpress          |


## 2. Setup CakePHP

> **[Official CakePHP Documentation](https://book.cakephp.org/3.0/en/installation.html)**

The following configuration will be used:

| Project name | VirtualHost directory | Database   | TLD_SUFFIX | Url |
|--------------|-----------------------|------------|------------|-----|
| my-cake      | /shared/httpd/my-cake | my_cake    | loc        | http://my-cake.loc |

It will be ready in eight simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install CakePHP via `composer`
4. Symlink webroot directory
5. Add MySQL database
6. Configure datbase connection
7. Setup DNS record
8. Visit http://my-cake.loc in your browser

```shell
# 1. Enter the PHP container
host> ./bash.sh

# 2. Create a new VirtualHost directory
devilbox@php-7.0.20 in /shared/httpd $ mkdir my-cake

# 3. Install CakePHP via composer
devilbox@php-7.0.20 in /shared/httpd $ cd my-cake
devilbox@php-7.0.20 in /shared/httpd $ composer create-project --prefer-dist cakephp/app cakephp

# 4. Symlink webroot directory
devilbox@php-7.0.20 in /shared/httpd $ ln -s cakephp/webroot/ htdocs

# 5. Add MySQL datbase
devilbox@php-7.0.20 in /shared/httpd $ mysql -u root -h 127.0.0.1 -p -e 'CREATE DATABASE my_cake;'

# 6. Configure datbase connection
devilbox@php-7.0.20 in /shared/httpd $ vi cakephp/config/app.php
```
```php
<?php
  'Datasources' => [
        'default' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => '127.0.0.1',
            /**
             * CakePHP will use the default DB port based on the driver selected
             * MySQL on MAMP uses port 8889, MAMP users will want to uncomment
             * the following line and set the port accordingly
             */
            //'port' => 'non_standard_port_number',
            'username' => 'root',
            'password' => 'secret',
            'database' => 'my_cake',
            'encoding' => 'utf8',
            'timezone' => 'UTC',
            'flags' => [],
            'cacheMetadata' => true,
```
**7. DNS record**

If you do not have auto-DNS configured, you will need to add the following line to your Host computer's `/etc/hosts`:
```shell
127.0.0.1 my-cake.loc
```

**8. Open your browser**

All set now, you can visit http://my-cake.loc in your browser.


## 3. Setup Drupal

> **[Official Drupal Documentation](https://www.drupal.org/docs/7/install)**

The following configuration will be used:

| Project name | VirtualHost directory | Database   | TLD_SUFFIX | Url |
|--------------|-----------------------|------------|------------|-----|
| my-drupal    | /shared/httpd/my-drupal | my_drupal| loc        | http://my-drupal.loc |

It will be ready in six simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install Drupal via `drush`
4. Symlink Drupal directory
5. Setup DNS record
6. Visit http://my-drupal.loc in your browser and follow instructions

```shell
# 1. Enter the PHP container
host> ./bash.sh

# 2. Create a new VirtualHost directory
devilbox@php-7.0.20 in /shared/httpd $ mkdir my-drupal

# 3. Install Drupal via drush
devilbox@php-7.0.20 in /shared/httpd $ cd my-drupal
devilbox@php-7.0.20 in /shared/httpd $ drush dl drupal

# 4. Symlink Drupal directory
devilbox@php-7.0.20 in /shared/httpd $ ln -s drupal-8.3.3 htdocs
```

**5. DNS record**

If you do not have auto-DNS configured, you will need to add the following line to your Host computer's `/etc/hosts`:
```shell
127.0.0.1 my-drupal.loc
```

**6. Open your browser**

Open your browser at http://my-drupal.loc and follow the Drupal installation steps.

**Note:** For MySQL host choose `127.0.0.1`.


## 4. Setup Laravel

> **[Official Laravel Documentation](https://laravel.com/docs/5.4/installation)**

The following configuration will be used:

| Project name | VirtualHost directory | Database   | TLD_SUFFIX | Url |
|--------------|-----------------------|------------|------------|-----|
| my-laravel   | /shared/httpd/my-laravel | -       | loc        | http://my-laravel.loc |

It will be ready in six simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install Laravel via `laravel`
4. Symlink public directory
5. Setup DNS record
6. Visit http://my-laravel.loc in your browser

```shell
# 1. Enter the PHP container
host> ./bash.sh

# 2. Create a new VirtualHost directory
devilbox@php-7.0.20 in /shared/httpd $ mkdir my-laravel

# 3. Install Laravel via laravel
devilbox@php-7.0.20 in /shared/httpd $ cd my-laravel
devilbox@php-7.0.20 in /shared/httpd $ laravel new laravel-project

# 4. Symlink public directory
devilbox@php-7.0.20 in /shared/httpd $ ln -s laravel-project/public htdocs
```

**5. DNS record**

If you do not have auto-DNS configured, you will need to add the following line to your Host computer's `/etc/hosts`:
```shell
127.0.0.1 my-laravel.loc
```

**6. Open your browser**

Open your browser at http://my-laravel.loc


## 5. Setup Phalcon

> **[Official Phalcon Documentation](https://docs.phalconphp.com/en/3.2/devtools-usage)**

The following configuration will be used:

| Project name | VirtualHost directory | Database   | TLD_SUFFIX | Url |
|--------------|-----------------------|------------|------------|-----|
| my-phalcon   | /shared/httpd/my-phalcon | -       | loc        | http://my-phalcon.loc |

It will be ready in six simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install Phalcon via `phalcon`
4. Symlink public directory
5. Setup DNS record
6. Visit http://my-phalcon.loc in your browser

```shell
# 1. Enter the PHP container
host> ./bash.sh

# 2. Create a new VirtualHost directory
devilbox@php-7.0.20 in /shared/httpd $ mkdir my-phalcon

# 3. Install Phalcon via phalcon
devilbox@php-7.0.20 in /shared/httpd $ cd my-phalcon
devilbox@php-7.0.20 in /shared/httpd $ phalcon project phalconphp

# 4. Symlink public directory
devilbox@php-7.0.20 in /shared/httpd $ ln -s phalconphp/public htdocs
```

**5. DNS record**

If you do not have auto-DNS configured, you will need to add the following line to your Host computer's `/etc/hosts`:
```shell
127.0.0.1 my-phalcon.loc
```

**6. Open your browser**

Open your browser at http://my-phalcon.loc


## 6. Setup Symfony

> **[Official Symfony Documentation](https://symfony.com/doc/current/setup.html)**

The following configuration will be used:

| Project name | VirtualHost directory | Database   | TLD_SUFFIX | Url |
|--------------|-----------------------|------------|------------|-----|
| my-symfony   | /shared/httpd/my-symfony | -       | loc        | http://my-symfony.loc |

It will be ready in six simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install Symfony via `symfony`
4. Symlink web directory
5. Enable Symfony prod (app.php)
6. Setup DNS record
7. Visit http://my-symfony.loc in your browser

```shell
# 1. Enter the PHP container
host> ./bash.sh

# 2. Create a new VirtualHost directory
devilbox@php-7.0.20 in /shared/httpd $ mkdir my-symfony

# 3. Install Symfony via symfony
devilbox@php-7.0.20 in /shared/httpd $ cd my-symfony
devilbox@php-7.0.20 in /shared/httpd $ symfony new symfony

# 4. Symlink web directory
devilbox@php-7.0.20 in /shared/httpd $ ln -s symfony/web htdocs

# 5. Enable Symfony production (app.php)
devilbox@php-7.0.20 in /shared/httpd $ cd symfony/web
devilbox@php-7.0.20 in /shared/httpd $ ln -s app.php index.php
```

**6. DNS record**

If you do not have auto-DNS configured, you will need to add the following line to your Host computer's `/etc/hosts`:
```shell
127.0.0.1 my-symfony.loc
```

**7. Open your browser**

Open your browser at http://my-symfony.loc


## 7. Setup Wordpress

> **[Official Wordpress Documentation](https://codex.wordpress.org/Installing_WordPress)**

The following configuration will be used:

| Project name | VirtualHost directory | Database   | TLD_SUFFIX | Url |
|--------------|-----------------------|------------|------------|-----|
| my-wp        | /shared/httpd/my-wp   | my_wp      | loc        | http://my-wp.loc |

It will be ready in six simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Download Wordpress via `git`
4. Symlink wordpress git directory
5. Setup DNS record
6. Visit http://my-wp.loc in your browser

```shell
# 1. Enter the PHP container
host> ./bash.sh

# 2. Create a new VirtualHost directory
devilbox@php-7.0.20 in /shared/httpd $ mkdir my-wp

# 3. Download Wordpress via git
devilbox@php-7.0.20 in /shared/httpd $ cd my-wp
devilbox@php-7.0.20 in /shared/httpd $ git clone https://github.com/WordPress/WordPress wordpress.git

# 4. Symlink wordpress git directory
devilbox@php-7.0.20 in /shared/httpd $ ln -s wordpress.git htdocs
```

**5. DNS record**

If you do not have auto-DNS configured, you will need to add the following line to your Host computer's `/etc/hosts`:
```shell
127.0.0.1 my-wp.loc
```

**6. Open your browser**

Open your browser at http://my-wp.loc


## 8. Setup Yii

> **[Official Yii Documentation](http://www.yiiframework.com/doc-2.0/guide-start-installation.html)**

The following configuration will be used:

| Project name | VirtualHost directory | Database   | TLD_SUFFIX | Url |
|--------------|-----------------------|------------|------------|-----|
| my-yii       | /shared/httpd/my-yii  | -          | loc        | http://my-yii.loc |

It will be ready in six simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install Yii2 via `composer`
4. Symlink web directory
5. Setup DNS record
6. Visit http://my-yii.loc in your browser

```shell
# 1. Enter the PHP container
host> ./bash.sh

# 2. Create a new VirtualHost directory
devilbox@php-7.0.20 in /shared/httpd $ mkdir my-yii

# 3. Install Yii2 via composer
devilbox@php-7.0.20 in /shared/httpd $ cd my-yii
devilbox@php-7.0.20 in /shared/httpd $ composer create-project --prefer-dist --stability=dev yiisoft/yii2-app-basic yii2-dev

# 4. Symlink web directory
devilbox@php-7.0.20 in /shared/httpd $ ln -s yii2-dev/web htdocs
```

**5. DNS record**

If you do not have auto-DNS configured, you will need to add the following line to your Host computer's `/etc/hosts`:
```shell
127.0.0.1 my-yii.loc
```

**6. Open your browser**

Open your browser at http://my-yii.loc


## 9. Setup Zend

> **[Official Zend Documentation](https://docs.zendframework.com/tutorials/getting-started/skeleton-application/)**

The following configuration will be used:

| Project name | VirtualHost directory | Database   | TLD_SUFFIX | Url |
|--------------|-----------------------|------------|------------|-----|
| my-zend      | /shared/httpd/my-zend | -          | loc        | http://my-zend.loc |

It will be ready in six simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install Zendframework via `composer`
4. Symlink public directory
5. Setup DNS record
6. Visit http://my-zend.loc in your browser

```shell
# 1. Enter the PHP container
host> ./bash.sh

# 2. Create a new VirtualHost directory
devilbox@php-7.0.20 in /shared/httpd $ mkdir my-zend

# 3. Install Zendframework via composer
devilbox@php-7.0.20 in /shared/httpd $ cd my-zend
devilbox@php-7.0.20 in /shared/httpd $ composer create-project --prefer-dist zendframework/skeleton-application zend

# 4. Symlink public directory
devilbox@php-7.0.20 in /shared/httpd $ ln -s zend/public htdocs
```

**5. DNS record**

If you do not have auto-DNS configured, you will need to add the following line to your Host computer's `/etc/hosts`:
```shell
127.0.0.1 my-zend.loc
```

**6. Open your browser**

Open your browser at http://my-zend.loc
