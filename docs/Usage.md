# Devilbox Documentation

[Overview](README.d) |
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
3. [Creating new Projects](#3-creating-new-projects)
  1. [How does it work?](#3-1-)
  2. [Directory structure explained](#3-2-)
  3. [Creating new Projects](#3-3-)
    1. [From Docker Host](#3-3-1-)
    2. [From inside the PHP container](#3-3-2-)
    3. [Using symlinks](#3-3-3-)
  4. [Adding DNS records](#3-4-)
    1. [/etc/hosts](#3-4-1-)
    2. [Auto-DNS](#3-4-2-)
4. [Switching container versions](#5-switching-container-versions)
  1. [Httpd versions](#5-1-httpd-versions)
  2. [PHP versions](#5-2-php-versions)
  2. [SQL versions](#5-3-sql-versions)
  3. [NoSQL versions](#5-4-nosql-versions)
5. [Emails](#6-emails)
6. [Log files](#7-log-files)
7. [Intranet](#8-intranet)
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

## 3. Creating new Projects
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


#### 3.3 Creating new Projetcs

##### 3.3.1 From Docker host

The following will create a VirtualHost for `http://project1.local`.

```shell
# replace HOST_PATH_TO_HTTPD_DATADIR with the actual project base dir
$ cd HOST_PATH_TO_HTTPD_DATADIR
$ mkdir project1
$ mkdir project1/htdocs
```

<sub>If you want to know how to change the TLD_SUFFIX `local` to something else, refer to [Configure](Configure.md).</sub>

##### 3.3.2 From inside the PHP container

If you prefer to work directly inside the PHP Docker container, you can do the same. The following will create a VirtualHost for `http://project1.local`.

```shell
$ cd /shared/httpd
$ mkdir project1
$ mkdir project1/htdocs
```

<sub>If you want to know how to go into the PHP container, check the section above **2. Work inside the PHP container**.</sub>

##### 3.3.3 Using symlinks

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

#### 3.4 Adding DNS record

In order to actually visit the newly created project in your browser, there must be a DNS entry pointing to the webserver's listening IP address. This can either be done automatically by a DNS server or you can do it manually for each project by editing your `/etc/hosts` file every time you create a new project.

##### 3.4.1 /etc/hosts

If you have not setup Auto-DNS, you will need to create your own DNS records for every project. Let's assume your `TLD_SUFFIX` is set to `local`.

| Project folder | `/etc/hosts` entry            |
|----------------|-------------------------------|
| my-project1    | `127.0.0.1 my-project1.local` |
| drupal-test    | `127.0.0.1 drupal-test.local` |
| playground     | `127.0.0.1 playground.local`  |


##### 3.4.2 Auto-DNS

When using the devilbox built-in DNS server, there is nothing to do. DNS catch-all records for your `TLD_SUFFIX` exist and will always point to `127.0.0.1`. See [Configure](Configure.md) for how to setup Auto-DNS.


## 4. Switching container versions
#### 4.1 Httpd versions
#### 4.2 PHP versions
#### 4.3 SQL versions
#### 4.4 NoSQL versions

## 5. Emails

## 5. Log files

## 6. Intranet
#### 6.1 Overview
#### 6.2 vHosts
#### 6.3 Tools

