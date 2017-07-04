# Devilbox Documentation

[Overview](README.md) |
Quickstart |
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
[FAQ](FAQ.md)

---

## Quickstart

1. [Installation](#1-installation)
2. [Update](#2-update)
    1. [Tagged release](#21-tagged-release)
    2. [Master branch](#22-master-branch)
3. [Configuration](#3-configuration)
    1. [.env](#31-env)
    2. [Services](#32-services)
4. [Run](#4-run)
    1. [Run all](#41-run-all)
    2. [Run selection](#42-run-selection)
5. [Project setup](#5-project-setup)
    1. [General setup](#51-general-setup)
    2. [Specific Frameworks](#52-specific-frameworks)
6. [Enter the PHP Docker container](#6-enter-the-php-docker-container)

---

## 1. Installation

Installing the devilbox is as easy as this:

```shell
$ git clone https://github.com/cytopia/devilbox
$ cd devilbox/
$ cp env-example .env
```

To find out in more detail for different operating systems have a look at **[Install](Install.md)**.

## 2. Update

You will have the choice to stay on stable git tags or on the latest master branch. Both options have slightly different update procedures. View the quick instructions below and for more information have a look at **[Update](Update.md)**

#### 2.1 Tagged release

```shell
$ docker-compose stop
$ docker-compose rm
$ git fetch --all
$ git checkout "$(git describe --abbrev=0 --tags)"
```

#### 2.2 Master branch

```shell
$ docker-compose stop
$ docker-compose rm
$ git fetch --all
$ git pull origin master
$ ./update-docker.sh
```


## 3. Configuration

The devilbox will work out-of-the box after the above installation routine has been done. However there are lots of options to configure. Read up on it on **[Configure](Configure.md)**. A brief overview is shown below.

#### 3.1 .env

Edit all general settings inside the .env file (file paths, what version to run, debug, timezeon, etc). The `.env` file is well documented and self-explanatory.

```shell
$ vim .env
```

**Important:** When changing any path variables, you will have to stop all container, delete them so that they can be re-created during the next startup.

```shell
$ docker-compose stop

# Remove the stopped container (IMPORTANT!)
# After the removal it will be re-created during next run
$ docker-compose rm -rf
```

#### 3.2 Services

Additionally to configure the devilbox in general, you can also configure each service separately by adding/altering service specific configuration files.

**Example:** Configure PHP 5.6
```shell
$ cd cfg/
$ echo "max_execution_time = 180" > php-fpm-5.6/config.ini
```

**Example:** Configure MySQL 5.5
```shell
$ cd cfg/
$ echo "[mysqld]\nslow_query_log = 1" > mysql-5.5/config.cnf
```


## 4. Run

Starting up the devilbox is done via docker-compose commands. You will have the choice to start-up everything or just a selection of the services you need. To get more more information about this view **[Run](Run.md)**.

#### 4.1 Run all

```shell
$ docker-compose up -d
```

#### 4.2 Run selection

```shell
$ docker-compose up -d httpd php mysql redis
```


## 5. Project setup

The heart of the devilbox is the easy configuration of an unlimitted numbder of projects. Most stuff configures itself automatically in the background, but a few things are still left up to you. The following will give you a kick-start for setting up a few projects. To find out in more detail view **[Usage](Usage.md)**.

#### 5.1 General setup

**Assumption:**

1. HOST_PATH_TO_HTTPD_DATADIR=**./data/www**
2. TLD_SUFFIX=**loc**
3. Three Projects: project1, project2 and wordpress

**Folder setup on your Host system:**

| VirtualHost directory | DocumentRoot directory      | URL                    |
|-----------------------|-----------------------------|------------------------|
| <code>./data/www/<b>project1</b></code> | <code>./data/www/project1/<b>htdocs</b></code> | `http://project1.loc`  |
| <code>./data/www/<b>project2</b></code> | <code>./data/www/project2/<b>htdocs</b></code>  | `http://project2.loc`  |
| <code>./data/www/<b>wordpress</b></code>| <code>./data/www/wordpress/<b>htdocs</b></code> | `http://wordpress.loc` |

Each VirtualHost will serve files from the **htdocs/** folder.

**DNS setup on your Host system:**

| Project folder | `/etc/hosts` entry         |
|----------------|----------------------------|
| project1       | `127.0.0.1 project1.loc` |
| project2       | `127.0.0.1 project2.loc` |
| wordpress      | `127.0.0.1 wordpress.loc`|

Some frameworks have a nested www directory and require you to use a symlink instead of explicitly setting a **htdocs/** folder. See the CakePHP folder setup below:

```shell
$ ls -l
drwxrwxr-x 2 cytopia 4096 Jun 14 08:29 cakephp
lrwxrwxrwx 1 cytopia   11 Jun 14 08:29 htdocs -> cakephp/app/webroot/
```

#### 5.2 Specific Frameworks

One example of the above mentioned nested directory structure is CakePHP. Its actual www dats is serveed from:

```shell
<project>/cake/app/webroot
```

instead of
```shell
<project>/htdocs
```

You can easily achieve this by symlinking this folder to `htdocs`:

```shell
$ ls -l <project>/
drwxrwxr-x 2 cytopia 4096 Jun 14 08:29 cakephp
lrwxrwxrwx 1 cytopia   11 Jun 14 08:29 htdocs -> cakephp/app/webroot/
```

To quickly find setup instructions for your framework of choice head over to **[Examples](Examples.md)**:

> 1. [Introduction](Examples.md#1-introduction)
> 2. [Setup CakePHP](Examples.md#2-setup-cakephp)
> 3. [Setup Drupal](Examples.md#3-setup-drupal)
> 4. [Setup Laravel](Examples.md#4-setup-laravel)
> 5. [Setup Phalcon](Examples.md#5-setup-phalcon)
> 6. [Setup Symfony](Examples.md#6-setup-symfony)
> 7. [Setup Wordpress](Examples.md#7-setup-wordpress)
> 8. [Setup Yii](Examples.md#8-setup-yii)
> 9. [Setup Zend](Examples.md#9-setup-zend)


## 6. Enter the PHP Docker container

The PHP Docker container is your workhorse which has many tools pre-installed and you can do every task inside instead of doing it on the docker host. Entering the container is done via a shipped script:

```shell
host> ./bash.sh
devilbox@php-7.0.19 in /shared/httpd $
```

See **[Usage](Usage.md)** for a detailed explanation.
