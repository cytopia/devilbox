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
echo "# [vhost] Customize"
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
TLD_SUFFIX="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "TLD_SUFFIX" )"
HTTPD_DOCROOT_DIR="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HTTPD_DOCROOT_DIR" )"
HTTPD_TEMPLATE_DIR="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HTTPD_TEMPLATE_DIR" )"


###
### The vhost name
###
VHOST=test-vhost-vhostgen_customize


###
### Create vhost directory
###
run "docker-compose exec --user devilbox -T php rm -rf /shared/httpd/${VHOST}" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"
run "docker-compose exec --user devilbox -T php mkdir -p /shared/httpd/${VHOST}/htdocs" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"


###
### Add index.htm, index.html and index.php
###
run "docker-compose exec --user devilbox -T php bash -c 'echo \"indexhtm\" > /shared/httpd/${VHOST}/${HTTPD_DOCROOT_DIR}/index.htm'" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php bash -c 'echo \"indexhtml\" > /shared/httpd/${VHOST}/${HTTPD_DOCROOT_DIR}/index.html'" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php bash -c 'echo \"<?php echo \\\"indexphp\\\";\" > /shared/httpd/${VHOST}/${HTTPD_DOCROOT_DIR}/index.php'" "${RETRIES}" "${DVLBOX_PATH}"


###
### Copy default configuration
###
run "docker-compose exec --user devilbox -T php mkdir -p /shared/httpd/${VHOST}/${HTTPD_TEMPLATE_DIR}" "${RETRIES}" "${DVLBOX_PATH}"
run "cp ${DVLBOX_PATH}/cfg/vhost-gen/apache22.yml-example-vhost ${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/apache22.yml" "${RETRIES}"
run "cp ${DVLBOX_PATH}/cfg/vhost-gen/apache24.yml-example-vhost ${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/apache24.yml" "${RETRIES}"
run "cp ${DVLBOX_PATH}/cfg/vhost-gen/nginx.yml-example-vhost ${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/nginx.yml" "${RETRIES}"


###
### Customize DirectoryIndex to use 'index.html' only
###
replace "__INDEX__" "index.html" "${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/apache22.yml"
replace "__INDEX__" "index.html" "${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/apache24.yml"
replace "__INDEX__" "index.html" "${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/nginx.yml"


###
### Ensure webserver reloads configuration
###
run "docker-compose exec --user devilbox -T php mv /shared/httpd/${VHOST} /shared/httpd/${VHOST}.tmp" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"
run "docker-compose exec --user devilbox -T php mv /shared/httpd/${VHOST}.tmp /shared/httpd/${VHOST}" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"


###
### Ensure index.html will be served by default (as configured)
###
printf "[TEST] index.html should be served"
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}' | tac | tac | grep -E '^indexhtml$' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}" "0"; then
	printf "\\r[FAIL] index.html should be served\\n"
    run "docker-compose exec --user devilbox -T php curl -sS 'http://${VHOST}.${TLD_SUFFIX}' || true" "1" "${DVLBOX_PATH}"
	exit 1
else
	printf "\\r[OK]   index.html should be served\\n"
fi
