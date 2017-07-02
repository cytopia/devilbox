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
Technical |
[Hacking](Hacking.md) |
[FAQ](FAQ.md)

---

## Technical

1. [Networking](#1-networking)
2. [Ports and forwarding](#2-ports-and-forwarding)
    1. [PHP Container](#21-php-container)
    2. [Docker Host](#22-docker-host)
3. [Works the same on Host and PHP Container](#3-works-the-same-on-host-and-php-container)

---

## 1. Networking

It is best to use the hostnames and not to rely on the ip addresses as they might change. In most cases however you can use `127.0.0.1` for all connections. Read up to find out why.

> E.g.: When you want to setup a MySQL database connection use `mysql` or `127.0.0.1` as the hostname.

| Container                   | Container name  | Hostname  | IP Address     |
|-----------------------------|-----------------|-----------|----------------|
| DNS                         | bind            | bind      | 172.16.238.100 |
| PHP / HHVM                  | php             | php       | 172.16.238.10  |
| Apache / Nginx              | http            | http      | 172.16.238.11  |
| MySQL / MariaDB / PerconaDB | mysql           | mysql     | 172.16.238.12  |
| PostgreSQL                  | pgsql           | pgsql     | 172.16.238.13  |
| Redis                       | redis           | redis     | 172.16.238.14  |
| Memcached                   | memcd           | memcd     | 172.16.238.15  |
| MongoDB                     | mongo           | mongo     | 172.16.238.16  |


## 2. Ports and forwarding

#### 2.1 PHP Container

The `php` container is the center of all container. Everything happens in there.
This is also the reason it does some more magic than actually required.

**Remote ports and remote sockets are made available to the `php` container.**

The PHP container is using [socat](https://linux.die.net/man/1/socat) to

1. forward the remote mysql port `3306` (on the mysql container) to its own `127.0.0.1:3306`
2. forward the remote pgsql port `5432` (on the pgsql container) to its own `127.0.0.1:5432`
3. forward the remote redis port `6379` (on the redis container) to its own `127.0.0.1:6379`
4. forward the remote memcached port `11211` (on the memcd container) to its own `127.0.0.1:11211`
5. forward the remote mongodb port `27017` (on the mongo container) to its own `127.0.0.1:27017`

The following container can be reached from within the PHP container via the following methods:

| Container                   | Hostname  | IP Address     | IP Address | Port  |
|-----------------------------|-----------|----------------|------------|-------|
| DNS                         | bind      | 172.16.238.100 | -          |    53 |
| PHP / HHVM                  | php       | 172.16.238.10  | -          |  9000 |
| Apache / Nginx              | http      | 172.16.238.11  | -          |    80 |
| MySQL / MariaDB / PerconaDB | mysql     | 172.16.238.12  | 127.0.0.1  |  3306 |
| PostgreSQL                  | pgsql     | 172.16.238.13  | 127.0.0.1  |  5432 |
| Redis                       | redis     | 172.16.238.14  | 127.0.0.1  |  6379 |
| Memcached                   | memcd     | 172.16.238.15  | 127.0.0.1  | 11211 |
| MongoDB                     | mongo     | 172.16.238.16  | 127.0.0.1  | 27017 |

#### 2.2 Docker Host

The docker host (your computer) does exactly the same as the `php` container.

1. container mysql port `3306` is exposed to the host on port `127.0.0.1:3306`
2. container pgsql port `5432` is exposed to the host on port `127.0.0.1:5432`
3. container redis port `6379` is exposed to the host on port `127.0.0.1:6379`
3. container memcd port `11211` is exposed to the host on port `127.0.0.1:11211`
3. container mongo port `27017` is exposed to the host on port `127.0.0.1:27017`

The following container can be reached from the Docker host via the following methods:

| Container                   | IP Address | Port  |
|-----------------------------|------------|-------|
| DNS                         | 127.0.0.1  |  1053 |
| PHP / HHVM                  | -          |  9000 |
| Apache / Nginx              | 127.0.0.1  |    80 |
| MySQL / MariaDB / PerconaDB | 127.0.0.1  |  3306 |
| PostgreSQL                  | 127.0.0.1  |  5432 |
| Redis                       | 127.0.0.1  |  6379 |
| Memcached                   | 127.0.0.1  | 11211 |
| MongoDB                     | 127.0.0.1  | 27017 |


## 3. Works the same on Host and PHP Container

As you might have noticed, the ports and addresses will be exactly the same inside the PHP container and on the docker host (when using `127.0.0.1`) for most container. That way it is possible to write your php application like this:

```php
<?php
mysql_connect('127.0.0.1', 'user', 'pass');
```
