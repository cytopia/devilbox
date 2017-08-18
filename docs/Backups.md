# Devilbox Documentation

[Overview](README.md) |
[Quickstart](Quickstart.md) |
[Install](Install.md) |
[Update](Update.md) |
[Configure](Configure.md) |
[Run](Run.md) |
[Usage](Usage.md) |
[OS](OS.md) |
Backups |
[Examples](Examples.md) |
[Technical](Technical.md) |
[Hacking](Hacking.md) |
[FAQ](FAQ.md)

---

## Backups

1. [Info](#1-info)
2. [MySQL](#2-mysql)
    1. [MySQL Database Backup](#21-mysql-database-backup)
        1. [mysqldump-secure](#211-mysqldump-secure)
        2. [mysqldump](#212-mysqldump)
        3. [phpMyAdmin](#213-phpmyadmin)
        4. [Adminer](#214-adminer)
    2. [MySQL Database Restore](#22-mysql-database-restore)
        1. [mysql](#221-mysql)
        2. [phpMyAdmin](#222-phpmyadmin)
        3. [Adminer](#223-adminer)
3. [PostgreSQL](#3-postgresql)
    1. [PostgreSQL Database Backup](#31-postgresql-database-backup)
        1. [pg_dump](#311-pg_dump)
        2. [Adminer](#312-adminer)
    2. [PostgreSQL Database Restore](#32-postgresql-database-restore)
        1. [psql](#321-psql)
        2. [Adminer](#322-adminer)
4. [MongoDB](#4-mongodb)
    1. [MongoDB Database Backup](#41-mongodb-database-backup)
        1. [mongodump](#411-mongodump)
    2. [MongoDB Database Restore](#42-mongodb-database-restore)
        1. [mongorestore](#421-mongorestore)

---

## 1. Info

Backup and restore will be necessary when you are going to change MySQL, PostgreSQL or MongoDB versions. Each version has its own data directory and different versions do not pick up the databases from another version.

**Example**

```
./data/mysql/mysql-5.5
./data/mysql/mysql-5.7
./data/mysql/mariadb-10.1

./data/pgsql/9.5
./data/pgsql/9.6

./data/mongo/3.2
./data/mongo/3.4
```

This is necessary as later MySQL, PostgreSQL and MongoDB versions will upgrade the databases making it unusable for older versions.

So before you change to a new database version you will have to make a backup and restore the backup in the new version.

If you use the devilbox bundled tools, you will find all backups in the main directory under `./backups/`.


## 2. MySQL

#### 2.1 MySQL Database Backup

There are many different options to backup your MySQL database including some for the command line and some for using the Web interface. The recommended and fastest method is to use mysqldump-secure, as it will also add info files (`*.info`) to each database recording checksums, dump date, dump options and from which version the backup come from.

##### 2.1.1 mysqldump-secure

**[mysqldump-secure](https://mysqldump-secure.org)** is bundled, setup and ready to use in every PHP/HHVM container. You can run it without any arguments and it will dump each available database as a separated compressed file. Backups will be located in `./backups/mysql/` on the Docker host or in `/shared/backups/mysql/` inside the Docker container.

```shell
# Enter the Container
host> ./shell.sh

# Start the backup
devilbox@php-7.1.6 in /shared/httpd $ mysqldump-secure
[INFO]  (OPT): Logging enabled
[INFO]  (OPT): MySQL SSL connection disabled
[INFO]  (OPT): Compression enabled
[INFO]  (OPT): Encryption disabled
[INFO]  (OPT): Deletion disabled
[INFO]  (OPT): Nagios log disabled
[INFO]  (OPT): Info files enabled
[INFO]  (SQL): 1/3 Skipping: information_schema (DB is ignored)
[INFO]  (SQL): 2/3 Dumping:  mysql (0.66 MB)  1 sec (0.13 MB)
[INFO]  (SQL): 3/3 Skipping: performance_schema (DB is ignored)
[OK]    Finished successfully

# List backups inside the container
devilbox@php-7.1.6 in /shared/httpd $ ls -l /shared/backups/mysql/
-rw-r--r-- 1 devilbox 136751 Jun 17 13:31 2017-06-17_13-31__mysql.sql.gz
-rw-r--r-- 1 devilbox   2269 Jun 17 13:31 2017-06-17_13-31__mysql.sql.gz.info

# Quit the docker container
devilbox@php-7.1.6 in /shared/httpd $ exit

# List backups on your hostsystem (from devilbox root directory)
host> ls -l backups/mysql/
-rw-r--r-- 1 cytopia 136751 Jun 17 13:31 2017-06-17_13-31__mysql.sql.gz
-rw-r--r-- 1 cytopia   2269 Jun 17 13:31 2017-06-17_13-31__mysql.sql.gz.info
```

The `*.info` file will hold many useful information in case you need to debug any problems occured during backups.

```shell
$ cat ./backups/mysql/2017-06-17_13-31__mysql.sql.gz.info
```
```ini
; mysqldump-secure backup record
; Do not alter this file!
; Creation of this file can be turned off via config file.

; ============================================================
; = Local system information
; ============================================================
[mysqldump-secure]
version    = /usr/local/bin/mysqldump-secure (0.16.3)
vdate      = 2016-08-18
config     = /etc/mysqldump-secure.conf

[system]
uname      = Linux 4.4.0-79-generic
hostname   =
user       = devilbox
group      = devilbox

[tools]
mysqldump  = /usr/bin/mysqldump (10.14 Distrib 5.5.52-MariaDB) [for Linux (x86_64)]
mysql      = /usr/bin/mysql (15.1 Distrib 5.5.52-MariaDB) [for Linux (x86_64) using readline 5.1]
compressor = /usr/bin/gzip (gzip 1.5)
encryptor  = Not used

; ============================================================
; = Database / File information
; ============================================================
[database]
db_name    = mysql
db_size    = 687326 Bytes (0.66 MB)
tbl_cnt    = 30

[file]
file_path  = /shared/backups/mysql
file_name  = 2017-06-17_13-31__mysql.sql.gz
file_size  = 136751 Bytes (0.13 MB)
file_chmod = 0644
file_owner = devilbox
file_group = devilbox
file_mtime = 1497699116 (2017-06-17 13:31:56 CEST [+0200])
file_md5   = 8d1a6c38f81c691bc4b490e7024a4f72
file_sha   = 11fb85282ea866dfc69d29dc02a0418bebfea30e7e566c3c588a50987aceac2f

; ============================================================
; = Dump procedure information
; ============================================================
[mysqldump]
encrypted  = 0
compressed = 1
arguments  = --opt --default-character-set=utf8 --events --triggers --routines --hex-blob --complete-insert --extended-insert --compress --lock-tables  --skip-quick
duration   = 1 sec

[compression]
compressor = gzip
arguments  = -9 --stdout

[encryption]
encryptor  =
algorithm  =
pubkey     =

; ============================================================
; = Server information
; ============================================================
[connection]
protocol   = mysql via TCP/IP
secured    = No SSL
arguments  = --defaults-file=/etc/mysqldump-secure.cnf

[server]
hostname   = 001b3750b549
port       = 3306
replica    = master
version    = MariaDB 10.1.23-MariaDB MariaDB Server
```



You can alternatively also execute it directly from your host computer. For that to work, you must be inside the devilbox root directory.

```shell
$ docker-compose exec --user devilbox php mysqldump-secure
```

To find out more about the configuration and options of mysqldump-secure, visit its project page under: [https://mysqldump-secure.org](https://mysqldump-secure.org).

##### 2.1.2 mysqldump

**[mysqldump](https://dev.mysql.com/doc/refman/5.7/en/mysqldump.html)** is bundled with each PHP/HHVM container and ready to use. To backup a database named `my_db_name` follow the below listed example:

```shell
# Enter the Container
host> ./shell.sh

# Start the backup
devilbox@php-7.1.6 in /shared/httpd $ mysqldump -h mysql -u root -p my_db_name > /shared/backups/mysql/my_db_name.sql
```

To find out more about the configuration and options of mysqldump, visit its project page under: [https://dev.mysql.com/doc/refman/5.7/en/mysqldump.html](https://dev.mysql.com/doc/refman/5.7/en/mysqldump.html).

##### 2.1.3 phpMyAdmin

If you do not like to use the command line for backups, you can use **[phpMyAdmin](https://www.phpmyadmin.net)**. It comes bundled with the devilbox intranet. View [Usage](Usage.md) to read more about the devilbox intranet.

To find out more about the usage of phpMyAdmin, visit its project page under: [https://www.phpmyadmin.net](https://www.phpmyadmin.net).

##### 2.1.4 Adminer

If you do not like to use the command line for backups, you can use **[Adminer](https://www.adminer.org)**. It comes bundled with the devilbox intranet. View [Usage](Usage.md) to read more about the devilbox intranet.

To find out more about the usage of Adminer, visit its project page under: [https://www.adminer.org](https://www.adminer.org).

#### 2.2 MySQL Database Restore

##### 2.2.1 mysql

In order to restore or import mysql databases on the command line, you need to use `mysql`. Here are a few examples for different file types:

###### 2.2.1.1 `*.sql` files

```shell
# Enter the Container
host> ./shell.sh

# Start the import
devilbox@php-7.1.6 in /shared/httpd $ mysql -h mysql -u root -p my_db_name < /shared/backups/mysql/my_db_name.sql
```

###### 2.2.1.2 `*.sql.gz` files

```shell
# Enter the Container
host> ./shell.sh

# Start the import
devilbox@php-7.1.6 in /shared/httpd $ zcat /shared/backups/mysql/my_db_name.sql.gz | mysql -h mysql -u root -p my_db_name
```

###### 2.2.1.3 `*.sql.tar.gz` files

```shell
# Enter the Container
host> ./shell.sh

# Start the import
devilbox@php-7.1.6 in /shared/httpd $ tar xzOf /shared/backups/mysql/my_db_name.sql.tar.gz | mysql -h mysql -u root -p my_db_name
```

##### 2.2.2 phpMyAdmin

**[phpMyAdmin](https://www.phpmyadmin.net)** supports importing of many different formats out-of-the-box. Simply select the compressed or uncompressed file and press `Go` in the Web interface.

##### 2.2.3 Adminer

**[Adminer](https://www.adminer.org)** supports importing of plain (`*.sql`) or gzipped compressed (`*.sql.gz`) files out-of-the-box. Simply select the compressed or uncompressed file and press `Execute` in the Web interface.


## 3. PostgreSQL

#### 3.1 PostgreSQL Database Backup

##### 3.1.1 pg_dump

**[pg_dump](https://www.postgresql.org/docs/current/static/backup-dump.html)** is bundled with each PHP/HHVM container and ready to use. To backup a database named `my_db_name` follow the below listed example:

```shell
# Enter the Container
host> ./shell.sh

# Start the import
devilbox@php-7.1.6 in /shared/httpd $ pg_dump -h pgsql -U postgres -W my_db_name > /shared/backups/pgsql/my_db_name.sql
```

To find out more about the configuration and options of pg_dump, visit its project page under: [https://www.postgresql.org/docs/current/static/backup-dump.html](https://www.postgresql.org/docs/current/static/backup-dump.html#BACKUP-DUMP).

##### 3.1.2 Adminer

If you do not like to use the command line for backups, you can use **[Adminer](https://www.adminer.org)**. It comes bundled with the devilbox intranet. View [Usage](Usage.md) to read more about the devilbox intranet.

To find out more about the usage of Adminer, visit its project page under: [https://www.adminer.org](https://www.adminer.org).

#### 3.2 PostgreSQL Database Restore

##### 3.2.1 psql

In order to restore or import PostgreSQL databases on the command line, you need to use **[psql](https://www.postgresql.org/docs/current/static/backup-dump.html#BACKUP-DUMP-RESTORE)**. Here are a few examples for different file types:

###### 3.2.1.1 `*.sql` files

```shell
# Enter the Container
host> ./shell.sh

# Start the import
devilbox@php-7.1.6 in /shared/httpd $ psql -h pgsql -U postgres -W my_db_name < /shared/backups/pgsql/my_db_name.sql
```

###### 3.2.1.2 `*.sql.gz` files

```shell
# Enter the Container
host> ./shell.sh

# Start the import
devilbox@php-7.1.6 in /shared/httpd $ zcat /shared/backups/pgsql/my_db_name.sql.gz | psql -h pgsql -U postgres -W my_db_name
```

###### 3.2.1.3 `*.sql.tar.gz` files

```shell
# Enter the Container
host> ./shell.sh

# Start the import
devilbox@php-7.1.6 in /shared/httpd $ tar xzOf /shared/backups/pgsql/my_db_name.sql.tar.gz | psql -h pgsql -U postgres -W my_db_name
```

##### 3.2.2 Adminer

**[Adminer](https://www.adminer.org)** supports importing of plain (`*.sql`) or gzipped compressed (`*.sql.gz`) files out-of-the-box. Simply select the compressed or uncompressed file and press `Execute` in the Web interface.


## 4. MongoDB

#### 4.1 MongoDB Database Backup

##### 4.1.1 mongodump

**[mongodump](https://docs.mongodb.com/manual/reference/program/mongodump/)** is bundled with each PHP/HHVM container and ready to use. To backup all MongoDB databases follow the below listed example:

```shell
# Enter the Container
host> ./shell.sh

# Start the dump into /shared/backups/mongo
devilbox@php-7.1.6 in /shared/httpd $ mongodump --out /shared/backups/mongo
```

To find out more about the configuration and options of mongodump, visit its project page under: [https://docs.mongodb.com/manual/reference/program/mongodump/](https://docs.mongodb.com/manual/reference/program/mongodump/).

#### 4.2 MongoDB Database Restore

##### 4.2.1 mongorestore

**[mongorestore](https://docs.mongodb.com/manual/reference/program/mongorestore/)** is bundled with each PHP/HHVM container and ready to use. To restore all MongoDB databases follow the below listed example:

```shell
# Enter the Container
host> ./shell.sh

# Start the restore/import from /shared/backups/mongo
devilbox@php-7.1.6 in /shared/httpd $ mongorestore /shared/backups/mongo
```

To find out more about the configuration and options of mongorestore, visit its project page under: [https://docs.mongodb.com/manual/reference/program/mongorestore/](https://docs.mongodb.com/manual/reference/program/mongorestore/).
