#!/usr/bin/env bash

set -e
set -u
set -o pipefail

SCRIPT_PATH="$( cd "$(dirname "$0")" && pwd -P )"
DVLBOX_PATH="$( cd "${SCRIPT_PATH}/../.." && pwd -P )"
# shellcheck disable=SC1090
. "${SCRIPT_PATH}/.lib.sh"


# -------------------------------------------------------------------------------------------------
# Pre-check
# -------------------------------------------------------------------------------------------------

if ! command -v docker >/dev/null 2>&1; then
	>&2 echo "Error 'docker' binary not found, but required."
	exit 1
fi


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT
# -------------------------------------------------------------------------------------------------

###
### Clean Docker artifacts
###

DIR_NAME="$( basename "${DVLBOX_PATH}" )"

# Remove networks
if docker network ls | grep "${DIR_NAME}" >/dev/null; then
	run "docker network rm \"\$(docker network ls | grep '${DIR_NAME}' | awk '{print \$1}')\""
fi

# Remove volumes
if docker volume ls | grep "${DIR_NAME}" >/dev/null; then
	run "docker volume ls | grep '${DIR_NAME}' | awk '{print \$2}' | xargs docker volume rm"
fi
