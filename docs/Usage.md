# Devilbox Documentation

[Overview](README.md) |
[Quickstart](Quickstart.md) |
[Install](Install.md) |
[Update](Update.md) |
[Configure](Configure.md) |
[Run](Run.md) |
Usage |
[Backups](Backups.md) |
[Examples](Examples.md) |
[Technical](Technical.md) |
[Hacking](Hacking.md) |
[FAQ](FAQ.md)

---

## Usage

1. [Work on the Docker host](#1-work-on-the-docker-host)
2. [Work inside the PHP container](#2-work-inside-the-php-container)
  1. [As devilbox user](#2-1-as-devilbox-user)
  2. [As root user](#2-2-as-root-user)
  3. [Available tools](#2-3-available-tools)
  4. [Available URLs](#2-4-available-urls)
3. [Managing Projects explained](#3-managing-projects-explained)
  1. [How does it work?](#3-1-how-does-it-work-)
  2. [Directory structure explained](#3-2-directory-structure-explained)
4. [Creating new Projects](#4-creating-new-projetcs)
  1. [From Docker Host](#4-1-from-docker-host)
  2. [From inside the PHP container](#4-2-from-inside-the-php-container)
  3. [Using symlinks](#4-3-using-symlinks)
  4. [Adding DNS records](#4-4-adding-dns-record)
    1. [/etc/hosts](#4-4-1-etc-hosts)
    2. [Auto-DNS](#4-4-2-auto-dns)
5. [Switching container versions](#5-switching-container-versions)
  1. [Httpd versions](#5-1-httpd-versions)
  2. [PHP versions](#5-2-php-versions)
  2. [SQL versions](#5-3-sql-versions)
  3. [NoSQL versions](#5-4-nosql-versions)
6. [Emails](#6-emails)
7. [Log files](#7-log-files)
8. [Intranet](#8-intranet)
  1. [Overview](#8-1-overview)
  2. [vHosts](#8-2-vhosts)
  3. [Tools](#8-3-tools)

---

## 1. Work on the Docker host

If you do not need to run any command line tools manually (composer, node, etc), it is sufficient to stay on the host. All you need is a browser and an editor/IDE.

1. Open your browser at http://localhost
2. Open your project inside your editor
3. Start coding


**Note:** If you want to do some command-line PHP tasks and you have PHP installed on your host, make sure it is the same version as your currently started PHP Docker container. If not, just enter the PHP Docker and do the tasks there.

You could however also invoke the Docker's PHP executeable (or any other binary) from your host via:

```shell
# Call a generic command
$ docker-compose exec --user devilbox php <command>
       |           |         |         |        | 
      use        execute    use     container  the
 docker-compose    cmd    built-in    name     actual
                  on the    user       to      command
                  docker   devilbox   exec     to
                 container            command  execute
```

Here is an example to list the PHP inside the container from the Docker host.
```shell
# Print PHP version
$ docker-compose exec --user devilbox php php -v
```

## 2. Work inside the PHP container

If you need to run some command line tasks manually such as `drush`, `composer` or anything similar which is not available on your host computer, you can do that inside the PHP container, which comes with lots of useful pre-install tools.

The situation inside the container is not different from on the Docker host. All services and port bindings are available there as well on `127.0.0.1`. Read up on [Technical](Technical.md) to find out more about the syncronization of both.

**FYI:** You will always find your projects inside the PHP container at `/shared/httpd/`.

#### 2.1 As devilbox user

In the devilbox git directory you will find a bash script called `bash.sh`. Just execute this script and it will take you directly into the PHP docker container at the project root directory.

```shell
host> ./bash.sh
devilbox@php-7.0.19 in /shared/httpd $
```

As you can see, the PS1 prompt will also show you the current configured PHP version.

#### 2.2 As root user

In case you need to perform some tasks that are only possible with root rights (such as installing or updating software), you can also use the `root_bash.sh` script.

```shell
host> ./root_bash.sh
root@php-7.0.19 in /shared/httpd $
```

**Note:** Performing installations and updates are only temporary for the current session. Any change will be lost at the next start/restart of the devilbox. If you permanently require additional software refer to [Hacking](Hacking.md).

#### 2.3 Available tools

For your convenience a few selected tools have been pre-installed in their current version that you can use for your daily development tasks. Some of them are:

*composer, drush, drupal-console, git, node, npm*

The complete list of tools including their version can be found at the PHP docker containers git repository Readme:

[PHP 5.4](https://github.com/cytopia/docker-php-fpm-5.4) |
[PHP 5.5](https://github.com/cytopia/docker-php-fpm-5.5) |
[PHP 5.6](https://github.com/cytopia/docker-php-fpm-5.6) |
[PHP 7.0](https://github.com/cytopia/docker-php-fpm-7.0) |
[PHP 7.1](https://github.com/cytopia/docker-php-fpm-7.1) |
[HHVM](https://github.com/cytopia/docker-hhvm-latest)

If you permanently require additional software refer to [Hacking](Hacking.md).

If you think additional tools should always be bundled, [create an issue](https://github.com/cytopia/devilbox/issues).

#### 2.4 Available URLs

Your projects will be available by the same URL as they are available from your docker host computer. There is no need to edit the PHP container's `/etc/hosts` file, as it is automatically provide via the DNS container `bind`.

For example, by doing `curl http://project1.dev` from either your host computer or from inside the PHP container will return the same URL.

## 3. Managing Projects explained
[![Devilbox setup and workflow](img/devilbox_01-setup-and-workflow.png "devilbox - setup and workflow")](https://www.youtube.com/watch?v=reyZMyt2Zzo) 

#### 3.1 How does it work?

Creating new projects is really simple and just involves a few steps.

1. Create a new **project folder** for your VirtualHost
2. Create a subfolder named **htdocs/** for the DocumentRoot
2. Create a **DNS record** pointing to your VirtualHost (via `/etc/hosts`)

The **project folder** will be the name of your VirtualHost. The **htdocs/** folder holds all files that will be server by the VirtualHost (called DocumentRoot). The **DNS record** will be the domain name that points to the webserver's IP address (127.0.0.1).

#### 3.2 Directory structure explained

Your project folder is determined by the value of `HOST_PATH_TO_HTTPD_DATADIR` which can be set in `.env`. The default is `./data/www`.

| Location     | Project directory |
|--------------|-------------------|
| Host system  | `HOST_PATH_TO_HTTPD_DATADIR` (default: `./data/www`) |
| PHP Docker   | `/shared/httpd` |
| HTTPD Docker | `/shared/httpd` |


**What directory structure is required to serve a new project?**

1. Each folder inside your project directory is an independent VirtualHost.
2. Each VirtualHost folder requires the `htdocs/` folder which is the DocumentRoot.

In order to make the following examples easier let's work with some assumed default values. The first one represents the project base directory and the second one is for the project domains.

1. HOST_PATH_TO_HTTPD_DATADIR=**./data/www**
2. TLD_SUFFIX=**local**

| VirtualHost directory | DocumentRoot directory      | URL                    |
|-----------------------|-----------------------------|------------------------|
| <code>./data/www/<b>project1</b></code> | <code>./data/www/project1/<b>htdocs</b></code> | `http://project1.local`  |
| <code>./data/www/<b>project2</b></code> | <code>./data/www/project2/<b>htdocs</b></code>  | `http://project2.local`  |
| <code>./data/www/<b>wordpress</b></code>| <code>./data/www/wordpress/<b>htdocs</b></code> | `http://wordpress.local` |

The VirtualHost directory make a new VirtualHost available under the specified URL. However the actual files that will be served are always expected to be in a subfolder called `htdocs/`. By having an additional sub-directory for the Document root you are able to store non-www files inside the project folder and even **symlink** you www dir to htdocs.


## 4. Creating new Projetcs

This is a general overview about creating projects. If you want to see some real examples how to setup **Wordpress**, **Drupal**, **CakePHP**, **Yii**, **Symfony** and others, visit the [Example Section](Examples.md).

#### 4.1 From Docker host

The following will create a VirtualHost for `http://project1.local`.

```shell
# replace HOST_PATH_TO_HTTPD_DATADIR with the actual project base dir
$ cd HOST_PATH_TO_HTTPD_DATADIR
$ mkdir project1
$ mkdir project1/htdocs
```

<sub>If you want to know how to change the TLD_SUFFIX `local` to something else, refer to [Configure](Configure.md).</sub>

#### 4.2 From inside the PHP container

If you prefer to work directly inside the PHP Docker container, you can do the same. The following will create a VirtualHost for `http://project1.local`.

```shell
$ cd /shared/httpd
$ mkdir project1
$ mkdir project1/htdocs
```

<sub>If you want to know how to go into the PHP container, check the section above **2. Work inside the PHP container**.</sub>

#### 4.3 Using symlinks

Instead of creating a **htdocs/** folder explicitly, you can also make a symlink by the same name. This is required as some frameworks have nested www folders.

Keep the actual versioned wordpress name and symlink it to htdocs.
```shell
$ ls -l
drwxrwxr-x 2 cytopia 4096 Jun 14 08:29 wordpress-4.8
lrwxrwxrwx 1 cytopia   11 Jun 14 08:29 htdocs -> wordpress-4.8/
```

CakePHP serves its files from a nested folder, a symlink is required here.
```shell
$ ls -l
drwxrwxr-x 2 cytopia 4096 Jun 14 08:29 cakephp
lrwxrwxrwx 1 cytopia   11 Jun 14 08:29 htdocs -> cakephp/app/webroot/
```

#### 4.4 Adding DNS record

In order to actually visit the newly created project in your browser, there must be a DNS entry pointing to the webserver's listening IP address. This can either be done automatically by a DNS server or you can do it manually for each project by editing your `/etc/hosts` file every time you create a new project.

##### 4.4.1 /etc/hosts

If you have not setup Auto-DNS, you will need to create your own DNS records for every project. Let's assume your `TLD_SUFFIX` is set to `local`.

| Project folder | `/etc/hosts` entry            |
|----------------|-------------------------------|
| my-project1    | `127.0.0.1 my-project1.local` |
| drupal-test    | `127.0.0.1 drupal-test.local` |
| playground     | `127.0.0.1 playground.local`  |


##### 4.4.2 Auto-DNS

When using the devilbox built-in DNS server, there is nothing to do. DNS catch-all records for your `TLD_SUFFIX` exist and will always point to `127.0.0.1`. See [Configure](Configure.md) for how to setup Auto-DNS.


## 5. Switching container versions

Being able to combine all kinds of different container version is one of the main goals of the devilbox. Changing the versions is kept simple and consistent for all container.

1. Open the `.env` file in your favorite editor
2. Find the `*_SERVER=` block for the container to change the version
3. Comment all lines you do not want to activate
4. Uncomment the one line you want to use.
5. Restart the devilbox for the changes to take effect

<sub>Be aware that if multiple lines are uncommented, the last one takes effect.</sub>

#### 5.1 Httpd versions

1. Open the `.env` file in your favorite editor
2. Find the `HTTPD_SERVER=` block

You can choose between Apache and Nginx in different version. All of them are configured to work the same, there is nothing to worry about when changing them.


#### 5.2 PHP versions

1. Open the `.env` file in your favorite editor
2. Find the `PHP_SERVER=` block

You can choose between different PHP versions and HHVM.

**Important:** Keep in mind that if you have a custom php.ini config at `./cfg/php-*/`, it is only effective for one version. Custom php configurations are separted per version.

#### 5.3 SQL versions

1. Open the `.env` file in your favorite editor
2. Find the `MYSQL_SERVER=` or `PGSQL_SERVER=`  block

**Important:** Each version has a different data directory. This is a security precautions. Imagine you startup MySQL 5.5 for the first time. New databases will be created. Now you startup MySQL 8. All existing databases would be upgraded to work flawlessly with MySQL 8, however this is not downwards compatible. So by startup up MySQL 5.5 again, it would say the database is corrupt.

#### 5.4 NoSQL versions

1. Open the `.env` file in your favorite editor
2. Find the `MONGO_SERVER=`, 'MEMCD_SERVER=`  or `REDIS_SERVER=`  block

There is nothing to pay attention to here.

## 6. Emails

All your projects can send emails to whatever recipient. You do not have to worry that they will actually being sent. Each PHP container runs a local postfix mailserver that intercepts all outgoing mails and puts them all in the local devilbox user mail account.

In order to view sent emails open up the devilbox intranet http://localhost/mail.php. There you can also test email sending and verify that they really stay locally.

## 7. Log files

Log files are available on the Host system and separated per service version. See `./log/` (inside devilbox git directory) for all log files.

## 8. Intranet

The devilbox bundled intranet is not required for project management or creation, however it offers a few useful tools.

#### 8.1 Overview

The overview page presents you the current state of the running stack and any errors it might have encountered.

http://localhost

#### 8.2 vHosts

The vHost page shows you all available projects and any configuation errors that need to be resolved. Errors could be: missing `htdocs/` folder and incorrect DNS settings. So make sure to first visit this page if any of your vHost does not work.

http://localhost/vhosts.php

#### 8.3 Tools

The intranet also offers a few common as well as self-made tools. These include:

* phpMyAdmin
* Adminer
* Mail viewer
* OpCacheGUI
* SQL/NoSQL database viewer
* Info pages (showing detailed configurations for the attached container)

If you are interested in doing database backups, either use phpMyAdmin or Adminer. You can however also use the PHP container itself. Read more about this on [Backups](Backups.md)
