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
[Examples](Examples.md) |
[Technical](Technical.md) |
Hacking |
[FAQ](FAQ.md)

---

## Hacking

1. [Rebuilding bundled Docker container](#1-rebuilding-bundled-docker-container)
    1. [Why rebuild yourself?](#11-why-rebuild-yourself)
    2. [How to rebuild yourself?](#12-how-to-rebuild-yourself)
    3. [How to use the rebuild container?](#13-how-to-use-the-rebuild-container)
2. [Customizing the bundled Docker container](#2-customizing-the-bundled-docker-container)
3. [Adding your own Docker container](#3-adding-your-own-docker-container)
    1. [What information will you need?](#31-what-information-will-you-need)
    2. [How to add your service?](#32-how-to-add-your-service)
    3. [How to start your service?](#33-how-to-start-your-service)

---

## 1. Rebuilding bundled Docker container

The devilbox Docker container are rebuild frequently and pushed to [dockerhub](https://hub.docker.com/r/cytopia/). However there might be cases in which you want to rebuild right away in order to use a new minor version of a tool inside the container.

#### 1.1 Why rebuild yourself?

Imagine for example you are using the [PHP 7.1](https://github.com/cytopia/docker-php-fpm-7.1) container which currently is available with the version 7.1.6. You found that PHP 7.1.7 has just been released, but the container wasn't yet updated to that version. If you can't wait and need that version now, you can just update the container to that version yourself.

#### 1.2 How to rebuild yourself?

Rebuilding yourself has been made pretty easy. The devilbox docker container repositories come with two scripts for automatic rebuilding. `build/docker-build.sh` and `build/docker-rebuild.sh`.

1. *Clone* or *fork and clone* the desired docker repository to your computer
2. Run `build/docker-build.sh` (cached build) or `build/docker-rebuild.sh` (uncached build)

Either of the two above scripts will rebuild the desired docker container with the latest available versions. The rebuild docker container will then be available locally by the tag `latest`

#### 1.3 How to use the rebuild container?

##### 1.3.1 Tagged devilbox repository

If your devilbox git repository is checked out on a `git tag`, then also the docker container are bound to specific docker tags. It will look like this in `docker-compose.yml`:

```yml
  php:
     image: cytopia/${PHP_SERVER:-php-fpm-7.0}:0.10
```

Your newly rebuild `latest` docker container will not yet be available for the next run. You still need to change the `docker-compose.yml` and set the container to `latest`:

```yml
  php:
     image: cytopia/${PHP_SERVER:-php-fpm-7.0}:latest
```

##### 1.3.2 devilbox repository on master branch

If your devilbox git repository is checkout out on the `master` branch, then all docker container are always bound to the `latest` docker tag inside `docker-compose.yml` and you do not need to change anything. Just rebuilding the container is enough to be picked up for the next start.


## 2. Customizing the bundled Docker container

Customizing a Docker container is almost as simple as rebuilding it.

1. Fork the desired docker repository
2. Clone your forked docker repository locally
3. Edit `Dockerfile` with your customization
4. Run `build/docker-build.sh` (cached build) or `build/docker-rebuild.sh` (uncached build)
5. Read the rebuild section above to apply necessary steps


## 3. Adding your own Docker container

You can add your custom docker container including its configuration to `docker-compose.yml`.

#### 3.1 What information will you need?

1. A name that you can use to refer to in the docker-compose command
2. The Docker image name
3. The Docker image version or `latest`
4. An unused IP address from the devilbox network

#### 3.2 How to add your service?

##### 3.2.1 General example

1. Open `docker-compose.yml`
2. Paste the following snippet with your replaced values just below the `services:` line (with one level of indentation)

```yml
...
services:
  # Your custom Docker container here:
  <name>:
    image: <image-name>:<image-version>
    networks:
      app_net:
        ipv4_address: <unused-ip-address>
    # For ease of use always automatically start these:
    depends_on:
      - bind
      - php
      - httpd
  # End of custom Docker container
...
```

##### 3.2.2 Specific example

Lets make a real example for adding [Cockroach DB](https://hub.docker.com/r/cockroachdb/cockroach/)

1. Name: `cockroach`
2. Image: `cockroachdb/cockroach`
3. Version: `latest`
4. IP: `172.16.238.200`

Now add the information to `docker-compose.yml` just below the `services:` line:

```yml
...
services:
  # Your custom Docker container here:
  cockroach:
    image: cockroachdb/cockroach:latest
    networks:
      app_net:
        ipv4_address: 172.16.238.200
    # For ease of use always automatically start these:
    depends_on:
      - bind
      - php
      - httpd
  # End of custom Docker container
...
```


#### 3.3 How to start your service?

```shell
# The following will bring up your service including all the
# dependent services (bind, php and httpd)
$ docker-compose up <name>
```
