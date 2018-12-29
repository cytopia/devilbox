#!/usr/bin/env bash

set -e
set -u

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
COMPOSEPATH="${SCRIPTPATH}/../../../"
cd "${COMPOSEPATH}"

NODE_PATH="/shared/httpd/my-rhost/js"
NODE_FILE="index.js"

if ! docker-compose exec php test -d "${NODE_PATH}"; then
	>&2 echo "[Warning], skipping startup, directory does not exist in container: ${NODE_PATH}"
	exit 1
fi

if ! docker-compose exec php test -f "${NODE_PATH}/${NODE_FILE}"; then
	>&2 echo "[Warning], skipping startup, file does not exist in container: ${NODE_PATH}/${NODE_FILE}"
	exit 1
fi


# Check if at least one pm2 process is already running
if docker-compose exec --user devilbox php bash -c "cd /shared/httpd/my-rhost/js; pm2 show 0 >/dev/null"; then
	>&2 echo "[Warning], pm2 process already running"
else
	echo "su -c \"cd ${NODE_PATH}; pm2 start ${NODE_FILE}\" -l devilbox"
	docker-compose exec --user devilbox php bash -c "cd /shared/httpd/my-rhost/js; pm2 start ${NODE_FILE}"
fi
