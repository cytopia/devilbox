#!/bin/sh
DVL_PATH="${1:-/home/witek/Projects/devilbox}"
cd "${DVL_PATH}"
sudo docker-compose exec --user devilbox php bash -l
