#!/bin/sh
DVL_PATH="${1:-/home/witek/Projects/devilbox}"
cd "${DVL_PATH}"
sudo docker-compose up -d php httpd mysql