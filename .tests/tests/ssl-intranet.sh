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
echo "# [SSL] Intranet"
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
HOST_PORT_HTTPD_SSL="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HOST_PORT_HTTPD_SSL" )"


###
### Store the error
###
ERROR=0


###
### Intranet / from host
###
printf "[TEST] https Intranet / from host"
if ! run "curl -sS --fail --cacert ${DVLBOX_PATH}/ca/devilbox-ca.crt 'https://localhost:${HOST_PORT_HTTPD_SSL}' >/dev/null" "${RETRIES}" "" "0"; then
	printf "\\r[FAIL] https Intranet / from host\\n"
	run "curl -v --cacert ${DVLBOX_PATH}/ca/devilbox-ca.crt 'https://localhost:${HOST_PORT_HTTPD_SSL}' || true" "1"
	ERROR=1
else
	printf "\\r[OK]   https Intranet / from host\\n"
fi


###
### Intranet / from container
###
printf "[TEST] https Intranet / from container"
if ! run "docker-compose exec -T php curl -sS --fail 'https://httpd' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}" "0"; then
	printf "\\r[FAIL] https Intranet / from container\\n"
	run "docker-compose exec -T php curl -v 'https://httpd' || true" "1" "${DVLBOX_PATH}"
	ERROR=1
else
	printf "\\r[OK]   https Intranet / from container\\n"
fi


###
### Intranet /credits.php from host
###
printf "[TEST] https Intranet /credits.php from host"
if ! run "curl -sS --fail --cacert ${DVLBOX_PATH}/ca/devilbox-ca.crt 'https://localhost:${HOST_PORT_HTTPD_SSL}/credits.php' | tac | tac | grep -E 'https:\\/\\/github\\.com\\/cytopia' >/dev/null" "${RETRIES}" "" "0"; then
	printf "\\r[FAIL] https Intranet /credits.php from host\\n"
	run "curl -v --cacert ${DVLBOX_PATH}/ca/devilbox-ca.crt 'https://localhost:${HOST_PORT_HTTPD_SSL}/credits.php' || true" "1"
	ERROR=1
else
	printf "\\r[OK]   https Intranet /credits.php from host\\n"
fi


###
### Intranet /credits.php from container
###
printf "[TEST] https Intranet /credits.php from container"
if ! run "docker-compose exec -T php curl -sS --fail 'https://httpd/credits.php' | tac | tac | grep -E 'https:\\/\\/github\\.com\\/cytopia' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}" "0"; then
	printf "\\r[FAIL] https Intranet /credits.php from container\\n"
	run "docker-compose exec -T php curl -v 'https://httpd/credits.php' || true" "1" "${DVLBOX_PATH}"
	ERROR=1
else
	printf "\\r[OK]   https Intranet /credits.php from container\\n"
fi


###
### Return error or success
###
exit "${ERROR}"
