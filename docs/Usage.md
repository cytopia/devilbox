# Devilbox Documentation

|
**Overview** |
**Installing** |
**Updating** |
**Configuration** |
Usage |
[**Examples**](Examples.md) |
**Technical** |
[**FAQ**](FAQ.md) |

---

## Usage

1. [Start and Stop](#1-start-and-stop)
  1. [Normal Start](#1-1-normal-start)
  2. [Background Start](#)
  3. [Selective Start](#)
  4. [Normal Stop](#)
  5. [Selective Stop](#)
  6. [Attach/Detach during run-time](#)
2. [Work inside the PHP container](#)
  1. [As devilbox user](#)
  2. [As root user](#)
  3. [Available tools](#)
  4. [Available URLs](#)
3. [Creating Projects](#)
  1. Creating projects on the docker host
  2. Creating projects from within the PHP container
4. [DNS](#)
  1. [/etc/hosts](#)
  2. [Auto-DNS](#)
5. [Intranet](#)
6. [Emails](#)
7. [Log files](#)

---

### 1. Start and Stop

Starting and stopping containers is done via docker-compose. If you have never worked with it before, have a look at their documentation for an [overview](https://docs.docker.com/compose/reference/overview/), [up](https://docs.docker.com/compose/reference/up/) and [stop](https://docs.docker.com/compose/reference/stop/)

#### 1.1 Normal Start

The normal start will bring up all container defined in *docker-compose.yml* and will stay in forground making it possible to stop them via Ctrl+c.

```shell
$ docker-compose up
```

#### 1.2 Background Start
#### 1.3 Selective Start
#### 1.4 Normal Stop
#### 1.5  Selective stop
#### 1.6  Attach/Detach during run-time
### 2. Creating Projects

This section is about how to start, stop, view and enter (all or a selection of some) containers. If you want to know how to choose the container type version (e.g. which mysql version or which php version) refer to the **[Configuration](Configuration.md)** section.

**Convention:** The terms *container* and *service* are used interchangeably.

**Assumption:** All `docker-compose` commands must be executed within the devilbox root directory, where the `docker-compose.yml` file resides.

### 1. Start and Stop

#### 1.1 Start all container

```shell
$ docker-compose up
```

This will bring up **all containers** defined in `docker-compose.yml`.

However, you will probably not need all of the defined services and especially on slow machines you only want to start what you really need. This is possible as well.

#### 1.2 Start selected container

In order to only start the services you actually need, you must specify them with the docker-compose command.

**Note:** The `http` and `php` container will automatically be started and must not be explicitly specified. (If not specified, their log output will also not go to stderr/stdout, but instead to docker logs)

```shell
# Only start HTTP and PHP
$ docker-compose up http php

# Start HTTP, PHP and Redis
$ docker-compose up redis

# Start HTTP, PHP and MySQL
$ docker-compose up mysql

# Start HTTP, PHP, PostgreSQL and Memcache
$ docker-compose up pgsql memcache
```
I think you get the idea.

#### 1.3 Start in background

You can also run the docker compose command in the background and close your terminal after startup. To do so simply add the `-d` flag:

```shell
$ docker-compose up -d
```
Or in case of selectively starting
```shell
$ docker-compose up -d mysql
```

#### 1.4 Stop container

1. If you started up docker compose in foreground mode (without `-d`), you can hit `ctrl+c` to gracefull stop or **twice** `ctrl+c` to kill the running containers.<br/>**Note:** Automatically started containers that were not specified (such as `http` or `php`) will have to be stopped manually via `docker-compose down` afterwards.
2. If you started up docker compose in background mode (with `-d`), go back to the devilbox directory (where the `docker-compose.yml` file resides and type `docker-compose down` to gracefully stop or `docker-compose kill` to kill them immediately.

Best pracice would be to start the container in the background (with `-d`) and use `docker compose down` to gracefully stop all of them.

### 2. Container Info

#### 2.1 List running container

Inside the devilbox directory enter the following command to get a list of the started/running container:
```shell
$ docker-compose ps
```

#### 2.2 Show container stdout/stderr output

Services started in background mode (`-d`) or those that were started as dependencies (`http` and `php`) will always only log to docker logs and not to stdout/stderr. In order to view their output use:
```shell
$ docker-compose logs
```

### 3. Enter

#### 3.1 Enter the php container

The `php` container (which might also have hhvm installed, depending on your version choice) is the container you can use to enter if you want to execute commands with the specified php version.

> **Note:** If you also have php installed locally on your host machine (and it is the php version of your choice), there is no need to enter the php container, just execute all the required commands on your project dir.

To enter the php container, type the following in the devilbox root directory:
```shel
$ ./bash.sh
```
You can alternatively also enter as root:
```
$ ./root_bash.sh
```

#### 3.2 Find your project files

The `php` container mounts your project files (the path of `HOST_PATH_TO_WWW_DOCROOTS` as specified in the `.env` file) to `/shared/httpd`.

So enter the container as described above and once inside the `php` container cd into `/shared/httpd`.

----

### Hints

**A. How do I know the name of the container I can start?**

Refer to the **[Info](Info.md)** section or look it up in the `docker-compose.yml` file.

**B. Can I not just comment out the service in the `.env` file?**

No, don't do this. This will lead to unexpected behaviour (different versions will be loaded).
The `.env` file allows you to configure the devilbox, but not to start services selectively.

**C. Are there any required services that must/will always be started?**

Yes. `http` and `php` will automatically always be started (due to dependencies inside `docker-compose.yml`) if you specify them or not.
