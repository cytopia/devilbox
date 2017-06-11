# Devilbox Documentation

**[Overview](README.md)** |
**[Install](Install.md)** |
**[Update](Update.md)** |
**[Configure](Configure.md)** |
**[Run](Run.md)** |
**Usage** |
**[Backups](Backups.md)** |
**[Examples](Examples.md)** |
**[Technical](Technical.md)** |
**[Hacking](Hacking.md)** |
**[FAQ](FAQ.md)**

---

## Usage

1. [Work on the Docker host](#1-work-on-the-docker-host)
2. [Work inside the PHP container](#2-work-inside-the-php-container)
  1. [As devilbox user](#2-1-as-devilbox-user)
  2. [As root user](#2-2-as-root-user)
  3. [Available tools](#2-3-available-tools)
  4. [Available URLs](#2-4-available-urls)
3. [Creating Projects](#3-creating-projects)
  1. [Creating projects on the docker host](#3-1-creating-projects-on-the-docker-host)
  2. [Creating projects from inside the PHP container](#3-2-creating-projects-from-inside-the-php-container)
4. [Project DNS](#4-project-dns)
  1. [/etc/hosts](#4-1-etc-hosts)
  2. [Auto-DNS](#4-2-auto-dns)
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

### 1. Work on the Docker host

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

# Print PHP version
$ docker-compose exec --user devilbox php php -v
```

### 2. Work inside the PHP container

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

### 3. Creating Projects
#### 3.1 Creating projects on the docker host
#### 3.2 Creating projects from inside the PHP container

### 4. Project DNS
#### 4.1 /etc/hosts
#### 4.2 Auto-DNS

### 5. Switching container versions
#### 5.1 Httpd versions
#### 5.2 PHP versions
#### 5.3 SQL versions
#### 5.4 NoSQL versions

### 6. Emails

### 7. Log files

### 8. Intranet
#### 8.1 Overview
#### 8.2 vHosts
#### 8.3 Tools

