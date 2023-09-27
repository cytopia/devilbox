#!/bin/sh

WSL_IP=$(ip addr show eth0 | grep -oP '(?<=inet\s)\d+(\.\d+){3}')
DIR='./cfg/php-ini-8.1/xdebug.ini'

echo '[PHP]' > $DIR
echo 'xdebug.mode = debug' >> $DIR
echo 'xdebug.start_with_request = yes' >> $DIR
echo 'xdebug.remote_handler = dbgp' >> $DIR
echo 'xdebug.client_port = 9003' >> $DIR
echo 'xdebug.idekey = vsc' >> $DIR
echo "xdebug.client_host = ${WSL_IP}" >> $DIR

docker-compose up -d httpd mysql php

docker-compose exec --user devilbox php bash -l


