<h1>The DevilBox</h1>

<br>

<h2>Clone DevilBox and create .env file</h2>

```
git clone https://github.com/rok666/devilbox.git
cd devilbox
cp env-example .env
```

<h2>Configure .env file</h2>

```
TLD_SUFFIX=dvl.to
PHP_SERVER=8.1
HTTPD_SERVER=apache-2.4
MYSQL_SERVER=mariadb-10.6
HOST_PATH_HTTPD_DATADIR=../www
MYSQL_ROOT_PASSWORD=root
HTTPD_DOCROOT_DIR=
```

<h2>Start containers Foreground</h2>

```
all containers          ->  docker-compose up
selected containers     ->  docker-compose up httpd php mysql
```

<h2>Start containers Background</h2>

```
all containers          ->  docker-compose up -d
selected containers     ->  docker-compose up -d httpd php mysql
```

<br>

---

<br>

<h1>Configure Xdebug</h1>

<br>

<h2>Retrive  IP</h2>

```
cd devilbox
./shell.sh
vim /etc/hosts

```

<p>retrive 'host.docker.internal' IP</p>

```
cd devilbox/cfg/php-ini-X.X
cp devilbox-php.in xdebug.ini
```



