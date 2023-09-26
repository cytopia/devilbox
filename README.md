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
TLD_SUFFIX=brainin
PHP_SERVER=7.4
HTTPD_SERVER=apache-2.4
MYSQL_SERVER=mysql-5.7
HOST_PATH_HTTPD_DATADIR=../www
MYSQL_ROOT_PASSWORD=root
```

<h2>Start containers Foreground</h2>

```
**all containers**          ->  docker-compose up
**selected containers**     ->  docker-compose up httpd php mysql
```

<h2>Start containers Background</h2>

```
**all containers**          ->  docker-compose up -d
**selected containers**     ->  docker-compose up -d httpd php mysql
```



