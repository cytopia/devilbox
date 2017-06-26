# Devilbox Documentation

[Overview](README.md) |
[Quickstart](Quickstart.md) |
Install |
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

## Install

1. [Install Docker](#1-install-docker)
    1. [Linux](#11-linux)
    2. [Windows](#12-windows)
    3. [OSX](#13-osx)
2. [Install Devilbox](#2-install-devilbox)
    1. [Latest git tag](#21-latest-git-tag)
    2. [Current master branch](#22-current-master-branch)

---

## 1. Install Docker

Only requirement for the devilbox is to have docker and docker-compose installed, everything else is bundled and provided within the Docker container.

#### 1.1 Linux

Refer to the official [Docker for Linux documentation](https://docs.docker.com/engine/installation/#supported-platforms) for how to install Docker on your distribution.

#### 1.2 Windows

Refer to the official [Docker for Windows documentation](https://docs.docker.com/docker-for-windows/install/) for how to install Docker.

**Note:** You should install the [Native Windows Docker](https://docs.docker.com/docker-for-windows/install/) and not the [Docker Toolbox](https://docs.docker.com/toolbox/overview/).

#### 1.3 OSX

Refer to the official [Docker for Mac documentation](https://docs.docker.com/docker-for-mac/install/) for how to install Docker.

**Note:** You should install the [Native Mac Docker](https://docs.docker.com/docker-for-mac/install/) and not the [Docker Toolbox](https://docs.docker.com/toolbox/overview/).


## 2. Install Devilbox

Just clone the devilbox repository and copy the configuration file.

```shell
$ git clone https://github.com/cytopia/devilbox
$ cd devilbox
$ cp env-example .env
```

You are all set now and can continue with [configuring the devilbox](Configure.md).

#### 2.1 Latest git tag

If you always want a stable development environment, you should stay on the latest git tag. However devilbox git tags are tied to specific Docker container tags. That means you will only get new Docker versions once you switch to the next devilbox git tag.

To check out the latest git tag, issue the following command:

```shell
$ git checkout "$(git describe --abbrev=0 --tags)"
```

When updating git tags, you do not need to explicitly pull new Docker images, as this will be done automatically once starting up the devilbox. The only thing you will have to do is to compare your current configuration file `.env` with possible new options in `env-example`. Refer to [Update](Update.md) For more information about how to update the devilbox properly.

#### 2.2 Current master branch

The devilbox master branch will always reflect the latest changes. However, no commits are pushed directly to master and everything merged into this branch has to go through various tests to make sure the master branch stays as stable as it can get.
The master branch ties each Docker container to their `latest` Docker tags. That means you can regularily `docker-compose pull` in order to obtain updated Docker container.

**Note:** Always do `git pull origin master` and `docker-compose pull` together. Do not just do one of them.

For the installation routine, there is nothing else to do here. After cloning you are automatically on the master branch. There are however a few things to pay attention to when updating the master branch. Refer to [Update](Update.md) For more information about how to update the devilbox properly.
