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
DISABLED_VERSIONS=("8.0")


echo
echo "# --------------------------------------------------------------------------------------------------"
echo "# [Config] Xdebug"
echo "# --------------------------------------------------------------------------------------------------"
echo


# -------------------------------------------------------------------------------------------------
# Pre-check
# -------------------------------------------------------------------------------------------------

PHP_SERVER="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "PHP_SERVER" )"
if [[ ${DISABLED_VERSIONS[*]} =~ ${PHP_SERVER} ]]; then
	printf "[SKIP] Skipping all checks for PHP %s\\n" "${PHP_SERVER}"
	exit 0
fi


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT
# -------------------------------------------------------------------------------------------------


###
### Get required env values
###
HOST_PORT_HTTPD="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HOST_PORT_HTTPD" )"

###
### Xdebug available
###
printf "[TEST] Xdebug available"
if  ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' | tac | tac | grep -q 'xdebug.remote_enable'" "${RETRIES}" "" "0"; then
	printf "\\r[FAIL] Xdebug available\\n"
	run "curl -sS 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' || true"
	exit 1
else
	printf "\\r[OK]   Xdebug available\\n"
fi


###
### Xdebug default disabled
###
printf "[TEST] Xdebug default disabled"
if  ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' | tac | tac | grep 'xdebug.remote_enable' | grep -Eq 'Off.+Off'" "${RETRIES}" "" "0"; then
	printf "\\r[FAIL] Xdebug default disabled\\n"
	run "curl -sS 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' | grep 'xdebug.remote_enable' || true"
	exit 1
else
	printf "\\r[OK]   Xdebug default disabled\\n"
fi


###
### Xdebug autostart disabled
###
printf "[TEST] Xdebug autostart disabled"
if  ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' | tac | tac | grep 'xdebug.remote_autostart' | grep -Eq 'Off.+Off'" "${RETRIES}" "" "0"; then
	printf "\\r[FAIL] Xdebug autostart disabled\\n"
	run "curl 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' | grep 'xdebug.remote_autostart' || true"
	exit 1
else
	printf "\\r[OK]   Xdebug autostart disabled\\n"
fi
