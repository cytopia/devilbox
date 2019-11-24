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
echo "# [modules] curl ${1:-}"
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

if ! command -v sort >/dev/null 2>&1; then
	>&2 echo "Error 'sort' binary not found, but required."
	exit 1
fi

if [ "${#}" -ne "1" ]; then
	>&2 echo "Error, requires one argument: <TEST_DIR>"
	exit 1
fi

VHOST="${1}"
TESTS="${SCRIPT_PATH}/../www/${VHOST}/htdocs"
if [ ! -d "${TESTS}" ]; then
	>&2 echo "Error, test dir does not exist: ${TESTS}"
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


###
### Store the error
###
ERROR=0


###
### Get vhost files
###
FILES="$( cd "${TESTS}" && find . -name '*.php' | sort )"

echo
echo "#--------------------------------------------------------------------------------"
echo "# [${1}] Test from host (http)"
echo "#--------------------------------------------------------------------------------"
echo

for file in ${FILES}; do
	name="${file#./}"

	if ! run "curl -sS --fail --header 'Host: ${VHOST}.${TLD_SUFFIX}' -o /dev/null -I -w '%{http_code}' 'http://localhost:${HOST_PORT_HTTPD}/${name}' | tac | tac | grep -E '200'" "${RETRIES}"; then
		run "curl -sS --header 'Host: ${VHOST}.${TLD_SUFFIX}' -o /dev/null -I -w '%{http_code}' 'http://localhost:${HOST_PORT_HTTPD}/${name}' || true"
		ERROR=1
	fi
	if ! run "curl -sS --fail --header 'Host: ${VHOST}.${TLD_SUFFIX}' 'http://localhost:${HOST_PORT_HTTPD}/${name}' | tac | tac | grep -E '^(OK|SKIP)$'" "${RETRIES}"; then
		run "curl -sS --header 'Host: ${VHOST}.${TLD_SUFFIX}' 'http://localhost:${HOST_PORT_HTTPD}/${name}'"
		ERROR=1
	fi
	if ! run_fail "curl -sS --fail --header 'Host: ${VHOST}.${TLD_SUFFIX}' 'http://localhost:${HOST_PORT_HTTPD}/${name}' 2>&1 | tac | tac | grep -Ei 'fatal|except|err|warn|notice' >/dev/null" "${RETRIES}"; then
		run "curl -sS --header 'Host: ${VHOST}.${TLD_SUFFIX}' 'http://localhost:${HOST_PORT_HTTPD}/${name}'"
		ERROR=1
	fi
done


echo
echo "#--------------------------------------------------------------------------------"
echo "# [${1}] Test from container (http)"
echo "#--------------------------------------------------------------------------------"
echo

for file in ${FILES}; do
	name="${file#./}"

	if ! run "docker-compose exec -T php curl -sS --fail -o /dev/null -I -w '%{http_code}' 'http://${VHOST}.${TLD_SUFFIX}/${name}' | tac | tac | grep -E '200'" "${RETRIES}" "${DVLBOX_PATH}"; then
		run "docker-compose exec -T php curl -sS -o /dev/null -I -w '%{http_code}' 'http://${VHOST}.${TLD_SUFFIX}/${name}'" "1" "${DVLBOX_PATH}"
		ERROR=1
	fi
	if ! run "docker-compose exec -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}/${name}' | tac | tac | grep -E '^(OK|SKIP)$'" "${RETRIES}" "${DVLBOX_PATH}"; then
		run "docker-compose exec -T php curl -sS 'http://${VHOST}.${TLD_SUFFIX}/${name}'" "1" "${DVLBOX_PATH}"
		ERROR=1
	fi
	if ! run_fail "docker-compose exec -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}/${name}' 2>&1 | tac | tac | grep -Ei 'fatal|except|err|war|notice' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}"; then
		run "docker-compose exec -T php curl -sS 'http://${VHOST}.${TLD_SUFFIX}/${name}'" "1" "${DVLBOX_PATH}"
		ERROR=1
	fi
done


###
### Return error or success
###
exit "${ERROR}"
