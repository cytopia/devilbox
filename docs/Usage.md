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

1. [Work inside the PHP container](#)
  1. [As devilbox user](#)
  2. [As root user](#)
  3. [Available tools](#)
  4. [Available URLs](#)
2. [Creating Projects](#)
  1. [Creating projects on the docker host](#)
  2. [Creating projects from inside the PHP container](#)
3. [Backups](#)
  1. [Backup MySQL database](#)
  2. [Backup PgSQL database](#)
4. [DNS](#)
  1. [/etc/hosts](#)
  2. [Auto-DNS](#)
5. [Intranet](#)
6. [Emails](#)
7. [Log files](#)

---

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

