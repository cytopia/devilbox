# Devilbox Documentation

**Overview** |
**Installing** |
**Updating** |
**Configuration** |
**[Usage](Usage.md)** |
**[Examples](Examples.md)** |
**Technical** |
**[Hacking](Hacking.md)** |
**[FAQ](FAQ.md)**

---

## Overview

1. [Supported Host OS](#1-supported-host-os)
2. [Requirements](#2-requirements)
3. [Docker documentation](#3-docker-documentation)
4. [Devilbox documentation](#4-devilbox-documentation)
5. [Video Tutorials](#5-video-tutorials)

---

### 1. Supported Host OS

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


### 2. Requirements

* **Internet connection** - only required during initial setup for cloning the devilbox repository and pulling the required docker container. Afterwards you can always work offline.
* [Docker Engine 1.12.0+](https://docs.docker.com/compose/compose-file/compose-versioning/#version-21)
* [Docker Compose 1.6.0+](https://docs.docker.com/compose/compose-file/compose-versioning/#version-2)
* On Windows use [Docker for Windows][d4w] (not tested on [Docker Toolbox][dtb])
* On OSX use [Docker for Mac][d4m] (not tested on [Docker Toolbox][dtb])


### 3. Docker documentation

If you have never worked with docker/docker-compose before, you should check up on their documentation to get you started: [docker docs](https://docs.docker.com/).


### 4. Devilbox documentation

| Topic                   | Description |
|-------------------------|-------------|
| **Installing**          | How to install docker, docker-compose and the devilbox |
| **Updating**            | Update best practise |
| **Configuration**       | How to configure the devilbox, switch versions (PHP, MySQL, PgSQL, ...)  and how to set custom options (php.ini, my.cnf, httpd.conf, ...) |
| **[Usage](Usage.md)**   | How to create projects, Email and DNS usage, tools (`composer`, `npm`, `node`, `drush`, ...), entering the container, Log files, Xdebug, Backups, Intranet, ...|
| **[Examples](Examples.md)** | Some project examples for popular CMS/Frameworks. How to setup Wordpress, Drupal, Yii, ... |
| **Technical**            | Technical background information |
| **[FAQ](FAQ.md)**        | Questions and Troubleshooting |


### 5. Video Tutorials

Have a look at youtube to see all the features in action.

[![Devilbox setup and workflow](img/devilbox_01-setup-and-workflow.png "devilbox - setup and workflow")](https://www.youtube.com/watch?v=reyZMyt2Zzo) 
[![Devilbox email catch-all](img/devilbox_02-email-catch-all.png "devilbox - email catch-all")](https://www.youtube.com/watch?v=e-U-C5WhxGY)



