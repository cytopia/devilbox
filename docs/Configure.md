# Devilbox Documentation

[Overview](README.md) |
[Quickstart](Quickstart.md) |
[Install](Install.md) |
[Update](Update.md) |
Configure |
[Run](Run.md) |
[Usage](Usage.md) |
[Backups](Backups.md) |
[Examples](Examples.md) |
[Technical](Technical.md) |
[Hacking](Hacking.md) |
[FAQ](FAQ.md)

---

## Configure

1. [Overview]()
2. [Devilbox general settings](#1-devilbox-general-settings)
  1. [Verbosity]()
  2. [Devilbox base path]()
  3. [Host computer listening address]()
3. [Project settings]()
  1. [Project domain]()
  2. [Project path]()
4. [Container settings]()
  1. [General]()
    1. [Timezone]()
  2. [PHP / HHVM]()
    1. [User id]()
	2. [Group id]()
	3. [Xdebug]()
	4. [php.ini]()
	5. [HHVM]()
  3. [Webserver]()
    1. [Host port]()
  4. [MySQL]()
    1. [Root password]()
	2. [General Log]()
	3. [Host port]()
    4. [Data path]()
    5. [my.cnf]()
  5. [PostgreSQL]()
    1. [Root user]()
	2. [Root password]()
	3. [Host port]()
    4. [Data path]()
  6. [Redis]()
    1. [Host port]()
  7. [Memcached]()
    1. [Host port]()
  8. [MongoDB]()
    1. [Host port]()
    2. [Data path]()
  9. [Bind]()
    1. [Upstream resolver]()
    2. [Host port]()
5. [Intranet settings]()
  1. [DNS check timeout]()
6. [Host computer]()
  1. [DNS]()
  2. [/etc/hosts/]()

---

## 1. Overview
## 2. Devilbox general settings
#### 2.1 Verbosity

| `.env` file variable name | Default | Note |
|---------------------------|---------|------|
| DEBUG_COMPOSE_ENTRYPOINT  | `0`     | Set it to `1` or `0` |

If set to `1`, it will show all executed commands during docker startup.

#### 2.2 Devilbox base path

| `.env` file variable name | Default | Note |
|---------------------------|---------|------|
| DEVILBOX_PATH             | `.`     | Relative or absolute path allowed |

This is the base path that will be prepended to all mount paths specified in `.env`.
You will usually not need to change this value..

#### 2.3 Host computer listening address

| `.env` file variable name | Default | Note |
|---------------------------|---------|------|
| LOCAL_LISTEN_ADDRESS      | `127.0.0.1:`   | Address for every service to listen on the Docker host.<br/>Pay attention to the **`:`** after the IP address |

This determines the Host address your webserver and all other daemons should listen on.

In case it is not `127.0.0.1` (because you are using a VirtualBox Docker setup) change it to the IP address of the VirtualBox host. Otherwise you will not need to change this value.

1. When you remove it completely, it will listen on all interfaces.
2. When you use specific address, you must add a **`:`** at the end.


## 3. Project settings

#### 3.1 Project domain

| `.env` file variable name | Default | Note |
|---------------------------|---------|------|
| TLD_SUFFIX                | `loc`   | Domain suffix for all your project. Can also be a subdomain such `work.loc` |

Each project will be served by `http://<project-folder>.<TLD_SUFFIX>`. If you want to change the default `loc` domain suffix to something else such as local, adjust this variable. Here are a few examples:

| Project Folder | TLD_SUFFIX | URL |
|----------------|------------|-----|
| my-cake        | loc        | http://my-cake.loc      |
| my-cake        | local      | http://my-cake.local    |
| my-cake        | dev        | http://my-cake.dev      |
| my-cake        | work.loc   | http://my-cake.work.loc |
| test           | foo        | http://test.foo         |
| project1       | bar        | http://project1.bar     |

The above examples should make it clear enough.

#### 3.2 Project path

| `.env` file variable name | Default | Note |
|---------------------------|---------|------|
| HOST_PATH_HTTPD_DATADIR   | `./data/www`   | Can be absolute or relative path. A relative path starts inside the devilbox git directory. |

This is the file system path on your host computer which will hold the Project Folders.

## 4. Container settings
#### 4.1 General
##### 4.1.1 Timezone


#### 4.2 PHP / HHVM
##### 4.2.1 User id
##### 4.2.2 Group id
##### 4.2.3 Xdebug
##### 4.2.4 php.ini
##### 4.2.5 HHVM
#### 4.3 Webserver
##### 4.3.1 Host port
#### 4.4 MySQL
##### 4.4.1 Root password
##### 4.4.2 General Log
##### 4.4.3 Host port
##### 4.4.4 Data path
##### 4.4.5 my.cnf
#### 4.5 PostgreSQL
##### 4.5.1 Root user
##### 4.5.2 Root password
##### 4.5.3 Host port
##### 4.5.4 Data path
#### 4.6 Redis
##### 4.6.1 Host port
#### 4.7 Memcached
##### 4.7.1 Host port
#### 4.8 MongoDB
##### 4.8.1 Host port
##### 4.8.2 Data path
#### 4.9 Bind
##### 4.9.1 Upstream resolver
##### 4.9.2 Host port
## 5. Intranet settings
#### 5.1 DNS check timeout
## 6. Host computer
#### 6.1 DNS
#### 6.2 /etc/hosts/



