#!/bin/sh

if hash docker-compose 2>/dev/null; then
	docker-compose exec --user devilbox php bash -c "cd soluzionefad; gulp default_core -f gulpfile_admin.js"
else
	docker-compose exec --user devilbox php bash -c "cd soluzionefad; gulp default_core -f gulpfile_admin.js"
fi
