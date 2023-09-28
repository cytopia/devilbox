

[Install Devilbox](#install-devilbox) - [Configure HTTPS](#configure-https) - [Configure xDebug](#configure-xdebug) - [Multiple PHP Versions](#multiple-php)

<h1></h1>

<br>

<h1 id="install-devilbox">Install DevilBox</h1>

<br>

<h2>Clone DevilBox and create .env file</h2>

```bash
git clone https://github.com/rok666/devilbox.git
cd devilbox
cp env-example .env
```

<h2>Configure .env file</h2>

```ini
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

<h1></h1>

<br>

<h1 id="configure-https">Configure HTTPS</h1>

<br>

<h2>Configure .env file</h2>

The option are: both, redir, ssl, plain

```ini
HTTPD_VHOST_SSL_TYPE=both
```

<h2>Store Certificate authority</h2>

When the Devilbox starts up for the first time, it will generate a Certificate Authority  and will store its public and private key in `devilbox/ca` folder.

<h2>Windows</h2>

| Function | Command |
| --- | --- |
| ADD | certutil -addstore -f "ROOT" <new-root-certificate.crt> |
| REMOVE | bash certutil -delstore "ROOT" <serial-number-hex> |

<br>

<h2>Mac OS</h2>

In Mac OS you can also use the Keychain Access app.

| Function | Command |
| --- | --- |
| ADD | sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain <new-root-certificate.crt> |
| REMOVE | sudo security delete-certificate -c "\<name of existing certificate\>" |

<br>

<h1></h1>

<br>

<h1 id="configure-xdebug">Configure Xdebug</h1>

<br>

<h2>Retrive vsCode IP</h2>

Chose one of these three options

```bash
IP Address extension for vsCode
```

```bash
ifconfig
```

```bash
ip addr show eth0 | grep -oP '(?<=inet\s)\d+(\.\d+){3}'
```

create xdebug.ini

```bash
cd devilbox/cfg/php-ini-X.X
cp devilbox-php.in xdebug.ini
```

```ini
xdebug.mode               = profile,debug
xdebug.start_with_request = yes
xdebug.remote_handler     = dbgp
xdebug.client_port        = 9003
xdebug.client_host        = 192.168.65.254
xdebug.idekey             = vsc
xdebug.log                = /var/log/php/xdebug.log
xdebug.output_dir         = /var/log/php
```

Install `PHP Debug` extension for vsCode and configure launch.json

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/shared/httpd/project_name": "${workspaceFolder}/www/project_name"
            }
        }
    ]
}
```

<br>

<h1></h1>

<br>

<h1 id="multiple-php">Multiple PHP Versions</h1>

<br>

<h2>Setup, stop and run</h2>

```bash
cd devilbox
cp compose/docker-compose.override.yml-php-multi.yml docker-compose.override.yml
docker-compose up php httpd bind php80
```

file: /shared/httpd/<project>/.devilbox/backend.cfg

```bash
conf:phpfpm:tcp:php80:9000
```

