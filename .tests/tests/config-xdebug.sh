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
echo "# [Config] Xdebug"
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
if  ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' | tac | tac | grep 'xdebug.remote_enable' >/dev/null" "${RETRIES}" "" "0"; then
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
if [ "${PHP_VERSION}" = "5.2" ] || [ "${PHP_VERSION}" = "5.3" ] || [ "${PHP_VERSION}" = "5.4" ] || [ "${PHP_VERSION}" = "5.5" ] || [ "${PHP_VERSION}" = "5.6" ] || [ "${PHP_VERSION}" = "7.0" ] || [ "${PHP_VERSION}" = "7.1" ]; then
	if  ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' | tac | tac | grep 'xdebug.remote_enable' | grep -E 'Off.+Off' >/dev/null" "${RETRIES}" "" "0"; then
		printf "\\r[FAIL] Xdebug default disabled\\n"
		run "curl -sS 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' | grep 'xdebug.remote_enable' || true"
		exit 1
	else
		printf "\\r[OK]   Xdebug default disabled\\n"
	fi
else
	if  ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' | tac | tac | grep 'xdebug.mode' | grep -E '(develop.+develop)|(no value.+no value)' >/dev/null" "${RETRIES}" "" "0"; then
		printf "\\r[FAIL] Xdebug default disabled\\n"
		run "curl -sS 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' | grep 'xdebug.mode' || true"
		exit 1
	else
		printf "\\r[OK]   Xdebug default disabled\\n"
	fi
fi

###
### Xdebug autostart disabled
###
printf "[TEST] Xdebug autostart disabled"
if [ "${PHP_VERSION}" = "5.2" ] || [ "${PHP_VERSION}" = "5.3" ] || [ "${PHP_VERSION}" = "5.4" ] || [ "${PHP_VERSION}" = "5.5" ] || [ "${PHP_VERSION}" = "5.6" ] || [ "${PHP_VERSION}" = "7.0" ] || [ "${PHP_VERSION}" = "7.1" ]; then
	if  ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' | tac | tac | grep 'xdebug.remote_autostart' | grep -E 'Off.+Off' >/dev/null" "${RETRIES}" "" "0"; then
		printf "\\r[FAIL] Xdebug autostart disabled\\n"
		run "curl 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' | grep 'xdebug.remote_autostart' || true"
		exit 1
	else
		printf "\\r[OK]   Xdebug autostart disabled\\n"
	fi
else
	if  ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' | tac | tac | grep 'xdebug.start_with_request' | grep -E 'default.+default' >/dev/null" "${RETRIES}" "" "0"; then
		printf "\\r[FAIL] Xdebug autostart disabled\\n"
		run "curl 'http://localhost:${HOST_PORT_HTTPD}/info_php.php' | grep 'xdebug.start_with_request' || true"
		exit 1
	else
		printf "\\r[OK]   Xdebug autostart disabled\\n"
	fi
fi
