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
DISABLED_VERSIONS=()


echo
echo "# --------------------------------------------------------------------------------------------------"
echo "# [rproxy] Javascript"
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
TLD_SUFFIX="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "TLD_SUFFIX" )"
HTTPD_TEMPLATE_DIR="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HTTPD_TEMPLATE_DIR" )"


###
### Bundled vhost-gen rproxy files have this as their default port
###
VHOSTGEN_TPL_DEFAULT_PORT=8000


###
### Application specific settings
###
RPROXY_NAME="rproxy"
RPROXY_PORT=8000
OUTPUT="OK-RPROXY-JAVASCRIPT"


###
### Create vhost-gen config directory
###
run "docker-compose exec --user devilbox -T php rm -rf /shared/httpd/${RPROXY_NAME}/${HTTPD_TEMPLATE_DIR}" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php mkdir -p /shared/httpd/${RPROXY_NAME}/${HTTPD_TEMPLATE_DIR}" "${RETRIES}" "${DVLBOX_PATH}"


###
### Apply default vhost-gen reverse proxy configurations
###
run "cp ${DVLBOX_PATH}/cfg/vhost-gen/apache22.yml-example-rproxy ${SCRIPT_PATH}/../www/${RPROXY_NAME}/${HTTPD_TEMPLATE_DIR}/apache22.yml" "${RETRIES}"
run "cp ${DVLBOX_PATH}/cfg/vhost-gen/apache24.yml-example-rproxy ${SCRIPT_PATH}/../www/${RPROXY_NAME}/${HTTPD_TEMPLATE_DIR}/apache24.yml" "${RETRIES}"
run "cp ${DVLBOX_PATH}/cfg/vhost-gen/nginx.yml-example-rproxy    ${SCRIPT_PATH}/../www/${RPROXY_NAME}/${HTTPD_TEMPLATE_DIR}/nginx.yml" "${RETRIES}"


###
### Apply custom configuration to reverse proxy files
###
replace ":${VHOSTGEN_TPL_DEFAULT_PORT}" ":${RPROXY_PORT}" "${SCRIPT_PATH}/../www/${RPROXY_NAME}/${HTTPD_TEMPLATE_DIR}/apache22.yml"
replace ":${VHOSTGEN_TPL_DEFAULT_PORT}" ":${RPROXY_PORT}" "${SCRIPT_PATH}/../www/${RPROXY_NAME}/${HTTPD_TEMPLATE_DIR}/apache24.yml"
replace ":${VHOSTGEN_TPL_DEFAULT_PORT}" ":${RPROXY_PORT}" "${SCRIPT_PATH}/../www/${RPROXY_NAME}/${HTTPD_TEMPLATE_DIR}/nginx.yml"


###
### Ensure webserver reloads configuration
###
run "docker-compose exec --user devilbox -T php mv /shared/httpd/${RPROXY_NAME} /shared/httpd/${RPROXY_NAME}.tmp" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"
run "docker-compose exec --user devilbox -T php mv /shared/httpd/${RPROXY_NAME}.tmp /shared/httpd/${RPROXY_NAME}" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"


###
### Start rproxy application
###
run "docker-compose exec --user devilbox -T php bash -c 'cd /shared/httpd/${RPROXY_NAME}/js && pm2 start index.js'" "${RETRIES}" "${DVLBOX_PATH}"


###
### Test rhost
###
printf "[TEST] rproxy javascript"
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${RPROXY_NAME}.${TLD_SUFFIX}:${HOST_PORT_HTTPD}' | tac | tac | grep -E '^${OUTPUT}$' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}" "0"; then
	printf "\\r[FAIL] rproxy javascript\\n"
	run "docker-compose exec --user devilbox -T php curl -v 'http://${RPROXY_NAME}.${TLD_SUFFIX}:${HOST_PORT_HTTPD}' || true' || true" "1" "${DVLBOX_PATH}"
	exit 1
else
	printf "\\r[OK]   rproxy javascript\\n"
fi
