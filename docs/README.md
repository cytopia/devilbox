# Devilbox Documentation

**Overview** |
**Installing** |
**Updating** |
**Configuration** |
**[Run](Run.md)** |
**[Usage](Usage.md)** |
**[Examples](Examples.md)** |
**[Technical](Technical.md)** |
**[Hacking](Hacking.md)** |
**[FAQ](FAQ.md)**

---

## Overview

1. [Main idea](#1-main-idea)
2. [Supported Host OS](#2-supported-host-os)
3. [Requirements](#3-requirements)
4. [Docker documentation](#4-docker-documentation)
5. [Devilbox documentation](#5-devilbox-documentation)
6. [Video Tutorials](#6-video-tutorials)
7. [Supported Frameworks and CMS](#7-supported-frameworks-and-cms)

---

### 1. Main idea

The devilbox allows you to have an unlimitted number of projects ready without having to install any external software and without having to configure any virtual hosts.

The only thing you will have to do is to create a new folder on the filesystem and your virtual host is ready to be served with your custom domain.

The default project catch-all domain is `*.dev` (can be changed). Let's see an example:

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

### 2. Supported Host OS

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


### 3. Requirements

* **Internet connection** - only required during initial setup for cloning the devilbox repository and pulling the required docker container. Afterwards you can always work offline.
* [Docker Engine 1.12.0+](https://docs.docker.com/compose/compose-file/compose-versioning/#version-21)
* [Docker Compose 1.6.0+](https://docs.docker.com/compose/compose-file/compose-versioning/#version-2)
* On Windows use [Docker for Windows][d4w] (not tested on [Docker Toolbox][dtb])
* On OSX use [Docker for Mac][d4m] (not tested on [Docker Toolbox][dtb])


### 4. Docker documentation

If you have never worked with docker/docker-compose before, you should check up on their documentation to get you started: [docker docs](https://docs.docker.com/).


### 5. Devilbox documentation

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


### 6. Video Tutorials

Have a look at youtube to see some the features in action.

[![Devilbox setup and workflow](img/devilbox_01-setup-and-workflow.png "devilbox - setup and workflow")](https://www.youtube.com/watch?v=reyZMyt2Zzo) 
[![Devilbox email catch-all](img/devilbox_02-email-catch-all.png "devilbox - email catch-all")](https://www.youtube.com/watch?v=e-U-C5WhxGY)


### 7. Supported Frameworks and CMS

As far as tested there are no limitations and you can use any Framework or CMS just as you would on your live environment. Below are a few examples of extensively tested Frameworks and CMS:

![CakePHP](img/logos/cake.png)
![Drupal](img/logos/drupal.png)
![Phalcon](img/logos/phalcon.png)
![Wordpress](img/logos/wordpress.png)
![Yii](img/logos/yii.png)
