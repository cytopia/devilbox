# Documentation

[Home](https://github.com/cytopia/devilbox) |
[Overview](README.md) |
[Configuration](Configuration.md) |
[Usage](Usage.md) |
[Updating](Updating.md) |
Info |
[PHP Projects](PHP_Projects.md) |
[Emails](Emails.md) |
[Logs](Logs.md) |
[Intranet](Intranet.md) |
[FAQ](FAQ.md)

----

## Info


### Available containers

It is best to use the hostnames and not to rely on the ip addresses as they might change. In most cases however you can even use `127.0.0.1` or `localhost` (See background section below).

> E.g.: When you want to setup a MySQL database connection use `mysql` or `127.0.0.1` as the hostname.

| Container       | Container name  | Hostname  | IP Address     |
|-----------------|-----------------|-----------|----------------|
| DNS             | bind            | bind      | 172.16.238.100 |
| PHP / HHVM      | php             | php       | 172.16.238.10  |
| Apache / Nginx  | http            | http      | 172.16.238.11  |
| MySQL / MariaDB | mysql           | mysql     | 172.16.238.12  |
| PostgreSQL      | pgsql           | pgsql     | 172.16.238.13  |
| Redis           | redis           | redis     | 172.16.238.14  |
| Memcahed        | memcd           | memcd     | 172.16.238.15  |

### Background

#### Background - PHP Container

The `php` container is the center of all container. Everything happens in there.
This is also the reason it does some more magic than actually required.

**Remote ports and remote sockets are made available to the `php` container.**

The PHP container is using [socat](https://linux.die.net/man/1/socat) to
1. forward the remote mysql port `3306` (on the mysql container) to its own `127.0.0.1:3306`
2. forward the remote pgsql port `5432` (on the mysql container) to its own `127.0.0.1:5432`
3. forward the remote redis port `6379` (on the mysql container) to its own `127.0.0.1:6379`
3. forward the remote mecahced port `11211` (on the memcd container) to its own `127.0.0.1:11211`


#### Background - Docker Host

The docker host (your computer) does exactly the same as the `php` container.
1. container mysql port `3306` is exposed to the host on port `127.0.0.1:3306`
2. container pgsql port `5432` is exposed to the host on port `127.0.0.1:5432`
3. container redis port `6379` is exposed to the host on port `127.0.0.1:6379`
3. container memcd port `11211` is exposed to the host on port `127.0.0.1:11211`

Also the database sockets from the container are mounted into the host.

#### Background - Benefit of the above

With the PHP container and the docker host (your computer) behaving the same it is possible to write your php applications like this:
```php
<?php
mysql_connect('127.0.0.1', 'user', 'pass');
```

This setup can then either be served by the docker or by your host computer (if you shutdown docker and start your local lamp stack)
