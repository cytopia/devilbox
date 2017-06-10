# Devilbox Documentation

**[Overview](README.md)** |
**Installing** |
**Updating** |
**Configuration** |
**[Run](Run.md)** |
**Usage** |
**[Examples](Examples.md)** |
**[Technical](Technical.md)** |
**[Hacking](Hacking.md)** |
**[FAQ](FAQ.md)**

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
  1. [Creating projects on the docker host](#)
  2. [Creating projects from inside the PHP container](#)
4. [Backups](#)
  1. [Backup MySQL database](#)
  2. [Backup PgSQL database](#)
5. [DNS](#)
  1. [/etc/hosts](#)
  2. [Auto-DNS](#)
6. [Intranet](#)
7. [Emails](#)
8. [Log files](#)

---

### 1. Start and Stop

Starting and stopping containers is done via docker-compose. If you have never worked with it before, have a look at their documentation for an [overview](https://docs.docker.com/compose/reference/overview/), [up](https://docs.docker.com/compose/reference/up/) and [stop](https://docs.docker.com/compose/reference/stop/) commands.

#### 1.1 Normal Start

The normal start will bring up **all** container defined in *docker-compose.yml* and will stay in forground making it possible to stop them via Ctrl+c.

```shell
$ docker-compose up
```

#### 1.2 Background Start

Instead of having the docker-compose run stay in foreground, you can also send it to the background by adding `-d` as an argument. The following will bring up **all** container and send docker-compose to background.

```shell
$ docker-compose up -d
```

#### 1.3 Selective Start

There is no need to always bring up **all** container, if you just need a few at the moment. In order to do so, simply specify the container by name that you actually need.

##### 1.3.1 Starting httpd, php, bind and mysql

```shell
# Foreground
$ docker-compose up httpd php bind mysql

# Background
$ docker-compose up -d httpd php bind mysql
```

**Note:** `httpd`, `php` and `bind` are base container that will **always** be started if specified or not. (Defined by `depends_on` in `docker-compose.yml`). So the above could also be achieved by simply specifying `mysql` only.

```shell
# Foreground
$ docker-compose up mysql

# Background
$ docker-compose up -d mysql
```

##### 1.3.2 Starting httpd, php, bind, pgsql and redis

```shell
# Foreground
$ docker-compose up httpd php bind pgsql redis

# Background
$ docker-compose up -d httpd php bind pgsql redis
```

**Note:** `httpd`, `php` and `bind` are base container that will **always** be started if specified or not. (Defined by `depends_on` in `docker-compose.yml`). So the above could also be achieved by simply specifying `pgsql` and `redis` only.

```shell
# Foreground
$ docker-compose up pgsql redis

# Background
$ docker-compose up -d pgsql redis
```

#### 1.4 Normal Stop

1. If you started up docker compose in foreground mode (without `-d`), you can hit `ctrl+c` to gracefull stop or **twice** `ctrl+c` to kill the running containers.<br/>**Note:** Automatically started containers that were not specified (such as `http` or `php`) will have to be stopped manually via `docker-compose down` afterwards.
2. If you started up docker compose in background mode (with `-d`), go back to the devilbox directory (where the `docker-compose.yml` file resides and type `docker-compose down` to gracefully stop or `docker-compose kill` to kill them immediately.

Best pracice would be to start the container in the background (with `-d`) and use `docker compose down` to gracefully stop all of them.

#### 1.5 Selective stop

You can also stop specific containers during runtime if they are not needed anymore. You might have started httpd, php, bind, mysql and redis and decided that redis was not needed. So go ahead and remove redis from the running container stack.

```shell
$ docker-compose stop redis
```

#### 1.6 Attach/Detach during run-time



### 2. Creating Projects

This section is about how to start, stop, view and enter (all or a selection of some) containers. If you want to know how to choose the container type version (e.g. which mysql version or which php version) refer to the **[Configuration](Configuration.md)** section.

**Convention:** The terms *container* and *service* are used interchangeably.

**Assumption:** All `docker-compose` commands must be executed within the devilbox root directory, where the `docker-compose.yml` file resides.

``

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

