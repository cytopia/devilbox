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

<h1>Configure HTTPS</h1>

<br>

<h2>Configure .env file</h2>

The option are: both, redir, ssl, plain

```
HTTPD_VHOST_SSL_TYPE=both
```

<h2>Store Certificate authority</h2>

When the Devilbox starts up for the first time, it will generate a Certificate Authority  and will store its public and private key in `devilbox/ca` folder.

<h2>Windows</h2>

| Function | Command |
| --- | --- |
| ADD | certutil -addstore -f "ROOT" <new-root-certificate.crt> |
| REMOVE | certutil -delstore "ROOT" <serial-number-hex> |

<h2>Mac OS</h2>

| Function | Command |
| --- | --- |
| ADD | sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain <new-root-certificate.crt> |
| REMOVE | sudo security delete-certificate -c "\<name of existing certificate\>" |

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

Search somethings like this:

```
192.168.65.254    host.docker.internal
```

create xdebug.ini

```
cd devilbox/cfg/php-ini-X.X
cp devilbox-php.in xdebug.ini
```

```
xdebug.mode               = debug
xdebug.start_with_request = yes
xdebug.remote_handler     = dbgp
xdebug.client_port        = 9003
xdebug.client_host        = 192.168.65.254
xdebug.idekey             = vsc
xdebug.remote_log         = /var/log/php/xdebug.log
```

Install `PHP Debug` extension for vsCode and configure launch.json

```
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003
        }
    ]
}
```




