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
  1. [Tagged release](#2-1-tagged-release)
  2. [Master branch](#2-2-master-branch)
3. [Configuration](#3-configuration)
  1. [.env](#3-1-env)
  2. [Services](#3-2-services)
4. [Run](#4-run)
  1. [Run all](#4-1-run-all)
  2. [Run selection](#4-2-run-selection)
5. [Project setup](#5-project-setup)
  1. [General setup](#5-1-general-setup)
  2. [Specific Frameworks](#5-2-specific-frameworks)
6. [Enter the PHP Docker container](#6-enter-the-php-docker-container)

---

## 1. Installation

```shell
$ git clone https://github.com/cytopia/devilbox
$ cd devilbox/
$ cp env-example .env
```

## 2. Update

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

#### 3.1 .env

Edit all general settings inside the .env file (file paths, what version to run, debug, timezeon, etc)
```shell
$ vim .env
```

#### 3.2 Services

Configure PHP 5.6
```shell
$ cd cfg/
$ echo "max_execution_time = 180" > php-fpm-5.6/config.ini
```

Configure MySQL 5.5
```shell
$ cd cfg/
$ echo "[mysqld]\nslow_query_log = 1" > mysql-5.5/config.cnf
```


## 4. Run

#### 4.1 Run all

```shell
$ docker-compose up -d
```

#### 4.2 Run selection

```shell
$ docker-compose up -d httpd php mysql redis
```


## 5. Project setup

#### 5.1 General setup

**Assumption:**

1. HOST_PATH_TO_HTTPD_DATADIR=**./data/www**
2. TLD_SUFFIX=**local**
3. Three Projects: project1, project2 and wordpress

**Folder setup on your Host system:**

| VirtualHost directory | DocumentRoot directory      | URL                    |
|-----------------------|-----------------------------|------------------------|
| <code>./data/www/<b>project1</b></code> | <code>./data/www/project1/<b>htdocs</b></code> | `http://project1.local`  |
| <code>./data/www/<b>project2</b></code> | <code>./data/www/project2/<b>htdocs</b></code>  | `http://project2.local`  |
| <code>./data/www/<b>wordpress</b></code>| <code>./data/www/wordpress/<b>htdocs</b></code> | `http://wordpress.local` |

Each VirtualHost will serve files from the **htdocs/** folder.

**DNS setup on your Host system:**

| Project folder | `/etc/hosts` entry         |
|----------------|----------------------------|
| project1       | `127.0.0.1 project1.local` |
| project2       | `127.0.0.1 project2.local` |
| wordpress      | `127.0.0.1 wordpress.local`|

Some frameworks have a nested www directory and require you to use a symlink instead of explicitly setting a **htdocs/** folder. See the CakePHP folder setup below:

```shell
$ ls -l
drwxrwxr-x 2 cytopia 4096 Jun 14 08:29 cakephp
lrwxrwxrwx 1 cytopia   11 Jun 14 08:29 htdocs -> cakephp/app/webroot/
```

#### 5.2 Specific Frameworks

Some frameworks use a deep nested directory to serve their actual www data such as:

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

See [Examples](Examples.md) for more info about how to setup different frameworks.


## 6. Enter the PHP Docker container

The PHP Docker container is your workhorse which has many tools pre-installed and you can do every task inside instead of doing it on the docker host. Entering the container is done via a shipped script:

```shell
host> ./bash.sh
devilbox@php-7.0.19 in /shared/httpd $
```

See [Usage](Usage.md) for a detailed explanation.
