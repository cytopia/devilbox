#!/bin/sh

if hash docker-compose 2>/dev/null; then
	docker-compose exec --user devilbox php bash -c "cd soluzionefad; gulp default_landing -f gulpfile_landing.js"
else
	docker-compose exec --user devilbox php bash -c "cd soluzionefad; gulp default_landing -f gulpfile_landing.js"
fi
