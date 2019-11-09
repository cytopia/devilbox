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

if ! command -v docker-compose >/dev/null 2>&1; then
	>&2 echo "Error 'docker-compose' binary not found, but required."
	exit 1
fi


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT
# -------------------------------------------------------------------------------------------------

###
### Clean Devilbox artifacts
###

# Remove emails
run "docker-compose exec php truncate -s0 /var/mail/devilbox || true" "1" "${DVLBOX_PATH}"

# Remove PHP logs
run "docker-compose exec php sh -c \"find /var/log -name 'php-fpm*' -print0 | xargs -n1 -0 rm -f\" || true" "1" "${DVLBOX_PATH}"

# Remove HTTP logs
run "docker-compose exec httpd sh -c \"find /var/log/ -name '*.log' -print0 | xargs -n1 -0 rm -f\" || true" "1" "${DVLBOX_PATH}"


###
### Stop and remove container
###

run "docker-compose stop"  "1" "${DVLBOX_PATH}"
run "docker-compose kill"  "1" "${DVLBOX_PATH}"
run "docker-compose rm -f" "1" "${DVLBOX_PATH}"
