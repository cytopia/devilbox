# Devilbox Documentation

[Overview](README.md) |
[Quickstart](Quickstart.md) |
[Install](Install.md) |
[Update](Update.md) |
[Configure](Configure.md) |
Run |
[Usage](Usage.md) |
[OS](OS.md) |
[Backups](Backups.md) |
[Examples](Examples.md) |
[Technical](Technical.md) |
[Hacking](Hacking.md) |
[FAQ](FAQ.md)

---

## Run

1. [Start the devilbox](#1-start-the-devilbox)
    1. [Foreground Start](#11-foreground-start)
    2. [Background Start](#12-background-start)
    3. [Selective Start](#13-selective-start)
2. [Stop the devilbox](#2-stop-the-devilbox)
    1. [Foreground Stop](#21-foreground-stop)
    2. [Background Stop](#22-background-stop)
3. [Attach/Detach during run-time](#3-attach-detach-during-run-time)
    1. [Attach during run-time](#31-attach-during-run-time)
    2. [Detach during run-time](#32-detach-during-run-time)
4. [Docker logs](#4-docker-logs)
    1. [All logs](#41-all-logs)
    1. [Specific logs](#42-specific-logs)
    1. [Tail logs](#43-tail-logs)


---

## 1. Start the devilbox

Starting and stopping containers is done via docker-compose. If you have never worked with it before, have a look at their documentation for an [overview](https://docs.docker.com/compose/reference/overview/), [up](https://docs.docker.com/compose/reference/up/) and [stop](https://docs.docker.com/compose/reference/stop/) commands.

By starting up the devilbox all attached containers will send their stdout and stderr to docker logs (foreground or background), you can increase/decrease the containers startup verbosity by configuring the `.env` file. See [Configure](Configure.md) for how to change that behavior.

#### 1.1 Foreground Start

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

**Start Note:** `httpd`, `php` and `bind` are base container that will **always** be started if specified or not. (Defined by `depends_on` in `docker-compose.yml`). So the above could also be achieved by simply specifying `mysql` only.

```shell
# Foreground
$ docker-compose up mysql

# Background
$ docker-compose up -d mysql
```

**Log Note:** When you do not specify httpd, php and bind in foreground start, their docker-logs will not be shown and you will have to explicitly use `docker-compose logs` to view their stdout/stderr output. Refer to the Log section below.

##### 1.3.2 Starting httpd, php, bind, pgsql and redis

```shell
# Foreground
$ docker-compose up httpd php bind pgsql redis

# Background
$ docker-compose up -d httpd php bind pgsql redis
```

**Start Note:** `httpd`, `php` and `bind` are base container that will **always** be started if specified or not. (Defined by `depends_on` in `docker-compose.yml`). So the above could also be achieved by simply specifying `pgsql` and `redis` only.

```shell
# Foreground
$ docker-compose up pgsql redis

# Background
$ docker-compose up -d pgsql redis
```
**Log Note:** When you do not specify httpd, php and bind in foreground start, their docker-logs will not be shown and you will have to explicitly use `docker-compose logs` to view their stdout/stderr output. Refer to the Log section below.


## 2. Stop the devilbox

#### 2.1 Foreground stop

If you started up docker compose in foreground mode (without `-d`), you can hit `ctrl+c` to gracefull stop or **twice** `ctrl+c` to kill the running containers.

**Note:** Automatically started containers that were not specified (such as `http` or `php`) will have to be stopped manually via `docker-compose down` afterwards.

#### 2.2 Background stop

If you started up docker compose in background mode (with `-d`), go back to the devilbox directory (where the `docker-compose.yml` file resides and type `docker-compose down` to gracefully stop or `docker-compose kill` to kill them immediately.

```shell
# Gracefully shutdown everything
$ docker-compose down

# Kill everything immediately
$ docker-compose kill
```

Best pracice would be to start the container in the background (with `-d`) and use `docker compose down` to gracefully stop all of them.


## 3. Attach/Detach during run-time

#### 3.1 Attach during run-time

You can also add/attach containers during runtime if you need them. You might have started httpd, php, bind and mysql and decided that you will also require redis. So go ahead and add redis to the running container stack.

```shell
# Foreground
$ docker-compose up redis

# Background
$ docker-compose up -d redis
```

It is recommended to always use background starts, this way you can intially start your desired stack and re-use the current terminal window to start or stop other services.

#### 3.2 Detach during run-time

You can also stop specific containers during runtime if they are not needed anymore. You might have started httpd, php, bind, mysql and redis and decided that redis was not needed. So go ahead and remove redis from the running container stack.

```shell
$ docker-compose stop redis
```


## 4. Docker Logs

Services started in background mode (`-d`) or those that were started as dependencies (`http` and `php`) will always only log to docker logs and not to stdout/stderr.

#### 4.1 All logs

In order to view logs of all started containers type:

```shell
$ docker-compose logs
```

#### 4.2 Specific logs

In order to view logs of a specific container, name it explicitly:

```shell
$ docker-compose logs redis
```

#### 4.3 Tail logs

There is also a version similar to `tail -f` to keep logs updated all the time.

```shell
$ docker-compose logs -f
```
