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
echo "# [vhost] Default Template"
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

if ! command -v diff >/dev/null 2>&1; then
	>&2 echo "Error 'diff' binary not found, but required."
	exit 1
fi


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT
# -------------------------------------------------------------------------------------------------

###
### Get required env values
###
HOST_PORT_HTTPD="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HOST_PORT_HTTPD" )"
HTTPD_TEMPLATE_DIR="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HTTPD_TEMPLATE_DIR" )"


###
### The vhost name
###
VHOST=test-vhost-vhostgen_def_tpl


###
### Create vhost directory
###
run "docker-compose exec --user devilbox -T php rm -rf /shared/httpd/${VHOST}" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"
run "docker-compose exec --user devilbox -T php mkdir -p /shared/httpd/${VHOST}/htdocs" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"


###
### Fetch current httpd.conf
###
TEMPLATE1="$( run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/vhost.d/${VHOST}.conf'" "${RETRIES}" )"


###
### Copy default configuration
###
run "docker-compose exec --user devilbox -T php mkdir -p /shared/httpd/${VHOST}/${HTTPD_TEMPLATE_DIR}" "${RETRIES}" "${DVLBOX_PATH}"
run "cp ${DVLBOX_PATH}/cfg/vhost-gen/apache22.yml-example-vhost ${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/apache22.yml" "${RETRIES}"
run "cp ${DVLBOX_PATH}/cfg/vhost-gen/apache24.yml-example-vhost ${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/apache24.yml" "${RETRIES}"
run "cp ${DVLBOX_PATH}/cfg/vhost-gen/nginx.yml-example-vhost ${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/nginx.yml" "${RETRIES}"


###
### Ensure webserver reloads configuration
###
run "docker-compose exec --user devilbox -T php mv /shared/httpd/${VHOST} /shared/httpd/${VHOST}.tmp" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"
run "docker-compose exec --user devilbox -T php mv /shared/httpd/${VHOST}.tmp /shared/httpd/${VHOST}" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"


###
### Fetch new httpd.conf
###
TEMPLATE2="$( run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/vhost.d/${VHOST}.conf'" "${RETRIES}" )"


###
### Diff templates
###
TMP_DIR="${SCRIPT_PATH}/../tmp"
run "mkdir -p '${TMP_DIR}'"
echo "${TEMPLATE1}" > "${TMP_DIR}/original.txt"
echo "${TEMPLATE2}" > "${TMP_DIR}/template.txt"

ERROR=0
if ! run "diff -y '${TMP_DIR}/original.txt' '${TMP_DIR}/template.txt'"; then
	ERROR=1
fi
if ! run "diff '${TMP_DIR}/original.txt' '${TMP_DIR}/template.txt'"; then
	ERROR=1
fi
exit "${ERROR}"
