#!/bin/sh

if hash docker-compose 2>/dev/null; then
	docker-compose exec --user devilbox php bash -c "cd soluzionefad; gulp default_shared -f gulpfile_shared.js"
else
	docker-compose exec --user devilbox php bash -c "cd soluzionefad; gulp default_shared -f gulpfile_shared.js"
fi
