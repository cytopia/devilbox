# Devilbox Documentation

[Home](https://github.com/cytopia/devilbox) |
[Overview](README.md) |
Configuration |
[Usage](Usage.md) |
[Updating](Updating.md) |
[Info](Info.md) |
[PHP Projects](PHP_Projects.md) |
[Emails](Emails.md) |
[Logs](Logs.md) |
[Intranet](Intranet.md) |
[FAQ](FAQ.md)

----

## Configuration


### 1. The `.env` file

All docker-compose configuration is done inside the `.env` file which simply defines key-value variables parsed to `docker-compose.yml`.

First thing to do after cloning this repository is creating this file from the `env-example`.

```shell
$ cp env-example .env
```

The file itself is well commented and should explain itself. In case anything is unclear leave an issue at GitHub.

> [What is the `.env` file?](https://docs.docker.com/compose/env-file/)


There are a few sections you should be aware off to better understand how it all works.

#### 1.1 Selecting service versions

Each service has a `<SERVICE_NAME>_SERVER` variable with a corresponding version. All existing versions are present in the file and commented out.

It is important to leave at least one version uncomment, otherwise the start-up will lead to errors. Use the following variable to control which version will be loaded:

* **`PHP_SERVER`**
* **`HTTPD_SERVER`**
* **`MYSQL_SERVER`**
* **`PGSQL_SERVER`**
* **`REDIS_SERVER`**
* **`MEMCD_SERVER`**

#### 1.2 Data directories

There are a few pre-configured data directories to make storage persistent across container restarts:

* *`HOST_PATH_HTTPD_DATADIR`*
* *`HOST_PATH_MYSQL_DATADIR`*
* *`HOST_PATH_PGSQL_DATADIR`*

The values by default point to relative directories inside the devilbox repository. You can however also point them to different locations (relative or absolute)

MySQL and PgSQL data directories will also append their version as a subfolder to the data directories in order to prevent database file corruptions due to different versions and possible incompatabilities between them.

If you have a MySQL database on your host computer with the same version that your docker container is using, you can also interchangeably use the data dir with you host MySQL version and vice-versa.

### 2. The `cfg/` directory

Inside the devilbox root directory you will find a foder called `cfg/`. This will contain subdirectories in the form of `<SERVICE>-<VERSION>`.
Those folders will be mounted into the appropriate location into the respective docker container in order to overwrite service configuration.

Currently only MySQL/MariaDB and PHP/HHVM overrides are supported.

The folder structure looks like this:
```
cfg/
  hhvm-latest/
  mariadb-10.0/
  mariadb-10.1/
  mariadb-10.2/
  mariadb-10.3/
  mariadb-5.5/
  mysql-5.5/
  mysql-5.6/
  mysql-5.7/
  mysql-8.0/
  php-fpm-5.4/
  php-fpm-5.5/
  php-fpm-5.6/
  php-fpm-7.0/
  php-fpm-7.1/
```

Each of the folders will contain an example file in the following format:
```
devilbox-custom.<ext>-example
```

Only files which have the correct file extensions will be read, all others such as `*.<ext>-example` will be ignored.

#### 2.1 Adding PHP options




---

### Hints

**A. Can I not just comment out the service in the `.env` file?**

No, don't do this. This will lead to unexpected behaviour (different versions will be loaded).
The `.env` file allows you to configure the devilbox, but not to start services selectively. At least one version per service must be defined.

**B. I don't want to start all container. How would I do this?**

Head over to the **[Usage](Usage.md)** section to get an in-depth explanation about how to start services selectively.
