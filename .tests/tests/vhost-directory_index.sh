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
echo "# [vhost] DirectoryIndex"
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
TLD_SUFFIX="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "TLD_SUFFIX" )"
HTTPD_DOCROOT_DIR="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HTTPD_DOCROOT_DIR" )"
HTTPD_VERSION="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HTTPD_SERVER" )"


###
### The vhost name
###
VHOST=test-vhost-dir_index


###
### Create vhost directory
###
run "docker-compose exec --user devilbox -T php rm -rf /shared/httpd/${VHOST}" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"
run "docker-compose exec --user devilbox -T php mkdir -p /shared/httpd/${VHOST}/htdocs" "${RETRIES}" "${DVLBOX_PATH}"
run "sleep 4"


###
### Store the error
###
ERROR=0


###
### index.htm should be served by default
###
run "docker-compose exec --user devilbox -T php bash -c 'echo \"indexhtm\" > /shared/httpd/${VHOST}/${HTTPD_DOCROOT_DIR}/index.htm'" "${RETRIES}" "${DVLBOX_PATH}"
printf "[TEST] index.htm should be served by default"
# FIXME: Apache 2.2 currently only serves PHP files as Document Roots due to its FPM implementation
if [ "${HTTPD_VERSION}" != "apache-2.2" ]; then
	printf "[TEST] index.htm should be served by default"
	if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}' | tac | tac | grep -E '^indexhtm$' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}" "0"; then
		printf "\\r[FAIL] index.htm should be served by default\\n"
		run "docker-compose exec --user devilbox -T php curl -sS 'http://${VHOST}.${TLD_SUFFIX}' || true" "1" "${DVLBOX_PATH}"
		ERROR=1
	else
		printf "\\r[OK]   index.htm should be served by default\\n"
	fi
else
	printf "\\r[SKIP] index.htm should be served by default\\n"
fi


###
### index.html should be served by default
###
run "docker-compose exec --user devilbox -T php bash -c 'echo \"indexhtml\" > /shared/httpd/${VHOST}/${HTTPD_DOCROOT_DIR}/index.html'" "${RETRIES}" "${DVLBOX_PATH}"
printf "[TEST] index.html should be served by default"
# FIXME: Apache 2.2 currently only serves PHP files as Document Roots due to its FPM implementation
if [ "${HTTPD_VERSION}" != "apache-2.2" ]; then
	if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}' | tac | tac | grep -E '^indexhtml$' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}" "0"; then
		printf "\\r[FAIL] index.html should be served by default\\n"
		run "docker-compose exec --user devilbox -T php curl -sS 'http://${VHOST}.${TLD_SUFFIX}' || true" "1" "${DVLBOX_PATH}"
		ERROR=1
	else
		printf "\\r[OK]   index.html should be served by default\\n"
	fi
else
	printf "\\r[SKIP] index.html should be served by default\\n"
fi


###
### index.php should be served by default
###
run "docker-compose exec --user devilbox -T php bash -c 'echo \"<?php echo \\\"indexphp\\\";\" > /shared/httpd/${VHOST}/${HTTPD_DOCROOT_DIR}/index.php'" "${RETRIES}" "${DVLBOX_PATH}"
printf "[TEST] index.php should be served by default"
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}' | tac | tac | grep -E '^indexphp$' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}" "0"; then
	printf "\\r[FAIL] index.php should be served by default\\n"
    run "docker-compose exec --user devilbox -T php curl -sS 'http://${VHOST}.${TLD_SUFFIX}' || true" "1" "${DVLBOX_PATH}"
	ERROR=1
else
	printf "\\r[OK]   index.php should be served by default\\n"
fi



###
### index.htm is available via direct path
###
printf "[TEST] index.htm is available via direct path"
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}/index.htm' | tac | tac | grep -E '^indexhtm$' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}" "0"; then
	printf "\\r[FAIL] index.htm is available via direct path\\n"
    run "docker-compose exec --user devilbox -T php curl -sS 'http://${VHOST}.${TLD_SUFFIX}/index.htm' || true" "1" "${DVLBOX_PATH}"
	ERROR=1
else
	printf "\\r[OK]   index.htm is available via direct path\\n"
fi


###
### index.html is available via direct path
###
printf "[TEST] index.html is available via direct path"
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}/index.html' | tac | tac | grep -E '^indexhtml$' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}" "0"; then
	printf "\\r[FAIL] index.html is available via direct path\\n"
    run "docker-compose exec --user devilbox -T php curl -sS 'http://${VHOST}.${TLD_SUFFIX}/index.html' || true" "1" "${DVLBOX_PATH}"
	ERROR=1
else
	printf "\\r[OK]   index.html is available via direct path\\n"
fi


###
### index.php is available via direct path
###
printf "[TEST] index.php is available via direct path"
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}/index.php' | tac | tac | grep -E '^indexphp$' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}" "0"; then
	printf "\\r[FAIL] index.php is available via direct path\\n"
    run "docker-compose exec --user devilbox -T php curl -sS 'http://${VHOST}.${TLD_SUFFIX}/index.php' || true" "1" "${DVLBOX_PATH}"
	ERROR=1
else
	printf "\\r[OK]   index.php is available via direct path\\n"
fi


###
### Return error or success
###
exit "${ERROR}"
