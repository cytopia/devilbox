#!/bin/sh

docker-compose exec --user devilbox ${1:-php} bash -l
