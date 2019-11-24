#!/usr/bin/env bash

# NOTE: Parsing curl to tac to circumnvent "failed writing body"
# https://stackoverflow.com/questions/16703647/why-curl-return-and-error-23-failed-writing-body

set -e
set -u
set -o pipefail

SCRIPT_PATH="$( cd "$(dirname "$0")" && pwd -P )"
DVLBOX_PATH="$( cd "${SCRIPT_PATH}/../.." && pwd -P )"
# shellcheck disable=SC1090
. "${SCRIPT_PATH}/../scripts/.lib.sh"

RETRIES=10
DISABLED_VERSIONS=("")


echo
echo "# --------------------------------------------------------------------------------------------------"
echo "# [Intranet] Homepage"
echo "# --------------------------------------------------------------------------------------------------"
echo


# -------------------------------------------------------------------------------------------------
# Pre-check
# -------------------------------------------------------------------------------------------------

PHP_VERSION="$( get_php_version "${DVLBOX_PATH}" )"
if [[ ${DISABLED_VERSIONS[*]} =~ ${PHP_VERSION} ]]; then
	printf "[SKIP] Skipping all checks for PHP %s\\n" "${PHP_VERSION}"
	exit 0
fi

if ! command -v curl >/dev/null 2>&1; then
	>&2 echo "Error 'curl' binary not found, but required."
	exit 1
fi

if ! command -v tac >/dev/null 2>&1; then
	>&2 echo "Error 'tac' binary not found, but required."
	exit 1
fi


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT
# -------------------------------------------------------------------------------------------------

###
### Get required env values
###
HOST_PORT_HTTPD="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HOST_PORT_HTTPD" )"


###
### dvlbox-ok
###
NUM_OK="20"
printf "[TEST] dvlbox-ok"
if  ! run "test \"\$(curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/index.php' | tac | tac | grep -c 'dvlbox-ok' || true)\" = \"${NUM_OK}\"" "${RETRIES}" "" "0"; then
	printf "\\r[FAIL] dvlbox-ok\\n"
	run "curl 'http://localhost:${HOST_PORT_HTTPD}/index.php' | tac | tac | grep -c 'dvlbox-ok' || true"
	exit 1
else
	printf "\\r[OK]   dvlbox-ok\\n"
fi


###
### dvlbox-err
###
printf "[TEST] dvlbox-err"
if  ! run "test \"\$(curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/index.php' | tac | tac | grep -c 'dvlbox-err' || true)\" = \"0\"" "${RETRIES}" "" "0"; then
	printf "\\r[FAIL] dvlbox-err\\n"
	run "curl 'http://localhost:${HOST_PORT_HTTPD}/index.php' | tac | tac | grep -c 'dvlbox-err' || true"
	exit 1
else
	printf "\\r[OK]   dvlbox-err\\n"
fi
