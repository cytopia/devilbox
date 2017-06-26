# Devilbox Documentation

[Overview](README.md) |
[Quickstart](Quickstart.md) |
[Install](Install.md) |
Update |
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

## Update

1. [TL;DR](#1-tldr)
2. [Git tag vs master branch](#2-git-tag-vs-master-branch)
    1. [Git tag](#21-git-tag)
    2. [Git master branch](#22-git-master-branch)
3. [Compare .env file](#3-compare-env-file)
4. [Pull new Docker container (Important!)](#4-pull-new-docker-container-important)
5. [Remove anonymous volumes](#5-remove-anonymous-volumes)

---

## 1. TL;DR

Shutdown, update and startup.

```shell
# Stop container, remove deprecated volumes and update git
$ docker-compose down
$ docker-compose rm
$ git fetch --all
$ git pull origin master

# Check for changes
$ vimdiff .env env-example

# Pull all available container
$ ./update-docker.sh
# Or just pull currently enabled versions in .env
$ docker-compose pull

# Ready and run
$ docker-compose up
```

Do not forget to read: [Pull new Docker container (Important!)](#4-pull-new-docker-container-important)


## 2. Git tag vs master branch

#### 2.1 Git tag

Git tags tie each Docker container to a stable Docker tag. This will look like this in the `docker-compose.yml`

```shell
$ grep '^[[:space:]]*image:' docker-compose.yml

    image: cytopia/bind:0.9
    image: cytopia/${PHP_SERVER:-php-fpm-7.0}:0.9
    image: cytopia/${HTTPD_SERVER:-nginx-stable}:0.9
    image: cytopia/${MYSQL_SERVER:-mariadb-10.1}:0.9
```

That means within your current git tag, you will not receive any Docker container updates, because the devilbox is bound to specific Docker tagged versions.

When a new devilbox tag is released, the `docker-compose.yml` file will have new Docker tags. There is no manuall action required. If you start up the devilbox for the first time, it will see that the container with those tags are not available locally and automatically start downloading them.

So the update procedure is as follows:

```shell
$ git fetch --all
$ git checkout "$(git describe --abbrev=0 --tags)"
```

**Note:** If you want to pre-download all available versions for later offline-usage, run the `update-docker.sh` script.

```shell
$ ./update-docker.sh
```

#### 2.2 Git master branch

The git master branch ties each Docker container to their `latest` tag. Latest tagged Docker container always reflect the latest changes and can be compared with a master branch of a git repository. This will look like this in the `docker-compose.yml`

```shell
$ grep '^[[:space:]]*image:' docker-compose.yml

    image: cytopia/bind:latest
    image: cytopia/${PHP_SERVER:-php-fpm-7.0}:latest
    image: cytopia/${HTTPD_SERVER:-nginx-stable}:latest
    image: cytopia/${MYSQL_SERVER:-mariadb-10.1}:latest
```

When you update the devilbox repository by `git pull origin master`, the Docker tags are still `latest` and you will continue using the current version of Docker container. You must also issue `docker-compose pull` in order to also update your Docker container.

So the update procedure is as follows:

```shell
$ git fetch --all
$ git pull origin master
$ docker-compose pull
```

**Note:** If you want to pre-download all available versions for later offline-usage, run the `update-docker.sh` script.

```shell
$ ./update-docker.sh
```


## 3. Compare .env file

New devilbox releases will most likeley receive new or improved functionality and features and therefore will have an altered `env-example` file. (This is an example configuration file which holds all current configuration options).
The effective configuration for docker-compose is stored in the `.env` file. However, the `.env` file is ignored by git, so that you can do changes without setting the git state dirty.

So when you update (master branch or tag) you will always have to compare your current `.env` settings with the new `env-example` file. If you are familiar with vim, just do the following:

```shell
$ vimdiff .env env-example
```

Make sure to transfer all new options from `env-example` to your current `.env` file.


## 4. Pull new Docker container (Important!)

As described above, for git master branch updates you will always have to pull new Docker container. **However, there is something very important to keep in mind:**

1. You have just updated the master branch and pulled new Docker container
2. You edit the `.env` file to switch to a different PHP version
3. You start up the devilbox. Is your new PHP Docker up to date?

No! You will have to `docker-compose pull` again. Why?

Lets have another look into `docker-compose.yml`:

```yml
image: cytopia/${PHP_SERVER:-php-fpm-7.0}:latest
image: cytopia/${HTTPD_SERVER:-nginx-stable}:latest
image: cytopia/${MYSQL_SERVER:-mariadb-10.1}:latest
```

As you can see, the Docker container names are variablized. If you updated `php-fpm-5.4:latest`, you still have to update `php-fpm-5.5:latest` (and all others) as they were not yet enabled/visible in `docker-compose.yml`.

So instead of pulling everything manually, use the bundled update script to do that for all available Docker container. That will also allow you to work offline, as every available docker image will be download in their latest version.

```shell
$ ./update-docker.sh
```


## 5. Remove anonymous volumes

The devilbox is not yet at a feature-ready stable release and volumes mounts might change from release to release until version 1.0 will be released. This can cause errors during startup. To solve those issues after updating, you should remove all anonymouse volumes with the following command:

```shell
$ docker-compose rm
```
