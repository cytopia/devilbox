#!/bin/sh

if hash docker-compose 2>/dev/null; then
	docker-compose exec --user devilbox php bash -l 
else
	docker compose exec --user devilbox php bash -l
fi
