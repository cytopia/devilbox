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
echo "# [Intranet] vhost"
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
TLD_SUFFIX="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "TLD_SUFFIX" )"
HTTPD_DOCROOT_DIR="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HTTPD_DOCROOT_DIR" )"
#HTTPD_TEMPLATE_DIR="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HTTPD_TEMPLATE_DIR" )"


###
### The vhost name
###
VHOST=test-intranet-vhost


###
### Create vhost directory
###
run "docker-compose exec --user devilbox -T php rm -rf /shared/httpd/${VHOST}" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"
run "docker-compose exec --user devilbox -T php mkdir -p /shared/httpd/${VHOST}" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"


###
### Vhost is available
###
printf "[TEST] vhost appears on intranet"
if ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/vhosts.php' | tac | tac | grep '${VHOST}' >/dev/null" "${RETRIES}" "" "0"; then
	printf "\\r[FAIL] vhost appears on intranet\\n"
	run "curl 'http://localhost:${HOST_PORT_HTTPD}/vhosts.php' || true"
	exit 1
else
	printf "\\r[OK]   vhost appears on intranet\\n"
fi


###
### Vhost shows htdocs/ dir error
###
printf "[TEST] vhost shows htdocs dir error if dir is missing"
if ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/_ajax_callback.php?vhost=${VHOST}' | tac | tac | grep 'error' | grep '${VHOST}' >/dev/null" "${RETRIES}" "" "0"; then
	printf "\\r[FAIL] vhost shows htdocs dir error if dir is missing\\n"
	run "curl  'http://localhost:${HOST_PORT_HTTPD}/_ajax_callback.php?vhost=${VHOST}' || true"
	exit 1
else
	printf "\\r[OK]   vhost shows htdocs dir error if dir is missing\\n"
fi


###
### Vhost shows success with htdocs/ dir created
###
run "docker-compose exec --user devilbox -T php mkdir -p /shared/httpd/${VHOST}/${HTTPD_DOCROOT_DIR}" "${RETRIES}" "${DVLBOX_PATH}"
printf "[TEST] vhost shows no htdocs dir error if dir is present"
if run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/_ajax_callback.php?vhost=${VHOST}' | tac | tac | grep -E 'error|${VHOST}' >/dev/null" "1" "" "0"; then
	printf "\\r[FAIL] vhost shows no htdocs dir error if dir is present\\n"
	run "curl 'http://localhost:${HOST_PORT_HTTPD}/_ajax_callback.php?vhost=${VHOST}' || true"
	exit 1
else
	printf "\\r[OK]   vhost shows no htdocs dir error if dir is present\\n"
fi


###
### Vhost shows success for DNS
###
printf "[TEST] vhost shows DNS record success"
if ! run "curl -sS --fail --header 'host: ${VHOST}.${TLD_SUFFIX}' 'http://localhost:${HOST_PORT_HTTPD}/devilbox-api/status.json' | tac | tac | grep 'success' >/dev/null" "${RETRIES}" "" "0"; then
	printf "\\r[FAIL] vhost shows DNS record success\\n"
	run "curl --header 'host: ${VHOST}.${TLD_SUFFIX}' 'http://localhost:${HOST_PORT_HTTPD}/_ajax_callback.php?vhost=${VHOST}' || true"
	exit 1
else
	printf "\\r[OK]   vhost shows DNS record success\\n"
fi


###
### Vhost config link is available
###
#printf "[TEST] vhost.d config link is available"
#if ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/vhosts.php' | tac | tac | grep 'vhost.d/${VHOST}.conf' >/dev/null" "${RETRIES}" "" "0"; then
#	printf "\\r[FAIL] vhost.d config link is available\\n"
#	run "curl 'http://localhost:${HOST_PORT_HTTPD}/vhosts.php' || true"
#	exit 1
#else
#	printf "\\r[OK]   vhost.d config link is available\\n"
#fi


###
### Vhost vhost.d config is available
###
printf "[TEST] vhost.d config is available"
if ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/vhost.d/${VHOST}.conf' | tac | tac | grep '${VHOST}-access.log' >/dev/null" "${RETRIES}" "" "0"; then
	printf "\\r[FAIL] vhost.d config is available\\n"
	run "curl 'http://localhost:${HOST_PORT_HTTPD}/vhost.d/${VHOST}.conf' || true"
	exit 1
else
	printf "\\r[OK]   vhost.d config is available\\n"
fi


###
### vhost-gen config link should appear
###
#run "docker-compose exec --user devilbox -T php mkdir -p /shared/httpd/${VHOST}/${HTTPD_TEMPLATE_DIR}" "${RETRIES}" "${DVLBOX_PATH}"
#run "cp ${DVLBOX_PATH}/cfg/vhost-gen/apache22.yml-example-vhost ${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/apache22.yml" "${RETRIES}"
#run "cp ${DVLBOX_PATH}/cfg/vhost-gen/apache24.yml-example-vhost ${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/apache24.yml" "${RETRIES}"
#run "cp ${DVLBOX_PATH}/cfg/vhost-gen/nginx.yml-example-vhost ${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/nginx.yml" "${RETRIES}"
#
#printf "[TEST] vhost-gen config link is available"
#if ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/vhosts.php' | tac | tac | grep 'info_vhostgen.php?name=${VHOST}' >/dev/null" "${RETRIES}" "" "0"; then
#	printf "\\r[FAIL] vhost-gen config link is available\\n"
#	run "curl 'http://localhost:${HOST_PORT_HTTPD}/vhosts.php' || true"
#	exit 1
#else
#	printf "\\r[OK]   vhost-gen config link is available\\n"
#fi


###
### vhost-gen config should be available
###
#printf "[TEST] vhost-gen config is available"
#if ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/info_vhostgen.php?name=${VHOST}' | tac | tac | grep '__VHOST_NAME__' >/dev/null" "${RETRIES}" "" "0"; then
#	printf "\\r[FAIL] vhost-gen config is available\\n"
#	run "curl 'http://localhost:${HOST_PORT_HTTPD}/info_vhostgen.php?name=${VHOST}' || true"
#	exit 1
#else
#	printf "\\r[OK]   vhost-gen config is available\\n"
#fi


###
### vhost-gen config changes are shown in intranet
###
#replace "__INDEX__" "__MY_GREP_VALUE__" "${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/apache22.yml"
#replace "__INDEX__" "__MY_GREP_VALUE__" "${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/apache24.yml"
#replace "__INDEX__" "__MY_GREP_VALUE__" "${SCRIPT_PATH}/../www/${VHOST}/${HTTPD_TEMPLATE_DIR}/nginx.yml"
#
#printf "[TEST] vhost-gen config changes are shown"
#if ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/info_vhostgen.php?name=${VHOST}' | tac | tac | grep '__MY_GREP_VALUE__' >/dev/null" "${RETRIES}" "" "0"; then
#	printf "\\r[FAIL] vhost-gen config changes are shown\\n"
#	run "curl 'http://localhost:${HOST_PORT_HTTPD}/info_vhostgen.php?name=${VHOST}' || true"
#	exit 1
#else
#	printf "\\r[OK]   vhost-gen config changes are shown\\n"
#fi


###
### Vhost disappears after removing its dir
###
#run "docker-compose exec --user devilbox -T php rm -rf /shared/httpd/${VHOST}" "${RETRIES}" "${DVLBOX_PATH}"
#run "sleep 4"
#
#printf "[TEST] vhost disappears after removing its dir"
#if ! run "test \"\$(curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/vhosts.php' | tac | tac | grep -c '${VHOST}')\" = \"0\"" "${RETRIES}" "" "0"; then
#	printf "\\r[FAIL] vhost disappears after removing its dir\\n"
#	run "curl 'http://localhost:${HOST_PORT_HTTPD}/vhosts.php' | tac | tac | grep '${VHOST}' || true"
#	exit 1
#else
#	printf "\\r[OK]   vhost disappears after removing its dir\\n"
#fi
