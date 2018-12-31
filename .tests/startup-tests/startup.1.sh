#!/usr/bin/env bash

set -e
set -u
set -o pipefail

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
COMPOSEPATH="${SCRIPTPATH}/../../"
CONTAINER="$( cd "${COMPOSEPATH}" && docker-compose ps -q php )"

#FILES="$()"
cd "${COMPOSEPATH}" && docker-compose exec -T php bash -c "find /startup.1.d/*.sh-example -type f -print0 2>/dev/null" \
	| xargs -0 -n 1 echo \
	| while read f; do

	echo "# ----------------------------------------------------------------------------------------"
	echo "# [TEST] ${f}"
	echo "# ----------------------------------------------------------------------------------------"
	if ! docker exec -t ${CONTAINER} bash "${f}" "ACCEPT_EULA=1"; then
		sleep 5
		if ! docker exec -t ${CONTAINER} bash "${f}" "ACCEPT_EULA=1"; then
			sleep 5
			if ! docker exec -t ${CONTAINER} bash "${f}" "ACCEPT_EULA=1"; then
				sleep 5
				if ! docker exec -t ${CONTAINER} bash "${f}" "ACCEPT_EULA=1"; then
					sleep 5
					if ! docker exec -t ${CONTAINER} bash "${f}" "ACCEPT_EULA=1"; then
						echo "[FAIl] ${f}"
						exit 1
					fi
				fi
			fi
		fi
	fi
	echo "[OK]   ${f}"
	echo
	echo
done
