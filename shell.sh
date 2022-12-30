#!/bin/sh
if hash docker-compose 2>/dev/null; then
	docker-compose exec --user devilbox ${1:-php} bash -l
else
	docker compose exec --user devilbox ${1:-php} bash -l
fi
