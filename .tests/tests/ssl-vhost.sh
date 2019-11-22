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
echo "# [SSL] vhost"
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
HTTPD_DOCROOT_DIR="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HTTPD_DOCROOT_DIR" )"
HOST_PORT_HTTPD_SSL="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HOST_PORT_HTTPD_SSL" )"
TLD_SUFFIX="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "TLD_SUFFIX" )"


###
### The vhost name
###
VHOST=test-ssl-vhost


###
### Store the error
###
ERROR=0


###
### Create vhost directory
###
run "docker-compose exec --user devilbox -T php rm -rf /shared/httpd/${VHOST}" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"
run "docker-compose exec --user devilbox -T php mkdir -p /shared/httpd/${VHOST}/htdocs" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php bash -c 'echo \"<?php echo \\\"indexphp\\\";\" > /shared/httpd/${VHOST}/${HTTPD_DOCROOT_DIR}/index.php'" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"


###
### Vhost / from host
###
printf "[TEST] https vhost / from host"
if ! run "curl -sS --fail --resolve ${VHOST}.${TLD_SUFFIX}:${HOST_PORT_HTTPD_SSL}:127.0.0.1 --cacert ${DVLBOX_PATH}/ca/devilbox-ca.crt 'https://${VHOST}.${TLD_SUFFIX}:${HOST_PORT_HTTPD_SSL}' >/dev/null" "${RETRIES}" "" "0"; then
	printf "\\r[FAIL] https vhost / from host\\n"
	run "curl -v --resolve ${VHOST}.${TLD_SUFFIX}:${HOST_PORT_HTTPD_SSL}:127.0.0.1 --cacert ${DVLBOX_PATH}/ca/devilbox-ca.crt 'https://${VHOST}.${TLD_SUFFIX}:${HOST_PORT_HTTPD_SSL}' || true" "1"
	ERROR=1
else
	printf "\\r[OK]   https vhost / from host\\n"
fi


###
### Vhost / from container
###
printf "[TEST] https vhost / from container"
if ! run "docker-compose exec -T php curl -sS --fail 'https://${VHOST}.${TLD_SUFFIX}' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}" "0"; then
	printf "\\r[FAIL] https vhost / from container\\n"
	run "docker-compose exec -T php curl -v 'https://${VHOST}.${TLD_SUFFIX}' || true" "1" "${DVLBOX_PATH}" "0"
	ERROR=1
else
	printf "\\r[OK]   https vhost / from container\\n"
fi


###
### Return error or success
###
exit "${ERROR}"
