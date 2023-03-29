@echo off

set php=php

if not "%~1"=="" (
    set php=%1
)

docker-compose exec --user devilbox %php% /bin/sh -c "cd /shared/httpd; exec bash -l"
