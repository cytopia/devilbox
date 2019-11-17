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
echo "# [curl] ${1:-}"
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
### Get vhost files
###
FILES="$( find "${TESTS}" -name '*.php' | sort )"
ERRORS=0


echo
echo "#--------------------------------------------------------------------------------"
echo "# [${1}] Test from host (http)"
echo "#--------------------------------------------------------------------------------"
echo

for file in ${FILES}; do
	name="$( basename "${file}" )"

	if ! run "curl -sS --fail --header 'Host: ${VHOST}.${TLD_SUFFIX}' 'http://localhost:${HOST_PORT_HTTPD}/${name}' | tac | tac | grep -E '^OK$' >/dev/null" "${RETRIES}"; then
		run "curl -sS --header 'Host: ${VHOST}.${TLD_SUFFIX}' 'http://localhost:${HOST_PORT_HTTPD}/${name}'"
		ERRORS="$(( ERRORS + 1))"
	fi
	if ! run_fail "curl -sS --fail --header 'Host: ${VHOST}.${TLD_SUFFIX}' 'http://localhost:${HOST_PORT_HTTPD}/${name}' 2>&1 | tac | tac | grep -Ei 'fatal|except|err|warn|notice' >/dev/null" "${RETRIES}"; then
		run "curl -sS --header 'Host: ${VHOST}.${TLD_SUFFIX}' 'http://localhost:${HOST_PORT_HTTPD}/${name}'"
		ERRORS="$(( ERRORS + 1))"
	fi
done


echo
echo "#--------------------------------------------------------------------------------"
echo "# [${1}] Test from container (http)"
echo "#--------------------------------------------------------------------------------"
echo

for file in ${FILES}; do
	name="$( basename "${file}" )"

	if ! run "docker-compose exec -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}:${HOST_PORT_HTTPD}/${name}' | tac | tac | grep -E '^OK$' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}"; then
		run "docker-compose exec -T php curl 'http://${VHOST}.${TLD_SUFFIX}:${HOST_PORT_HTTPD}/${name}'" "1" "${DVLBOX_PATH}"
		ERRORS="$(( ERRORS + 1))"
	fi
	if ! run_fail "docker-compose exec -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}:${HOST_PORT_HTTPD}/${name}' 2>&1 | tac | tac | grep -Ei 'fatal|except|err|war|notice' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}"; then
		run "docker-compose exec -T php curl 'http://${VHOST}.${TLD_SUFFIX}:${HOST_PORT_HTTPD}/${name}'" "1" "${DVLBOX_PATH}"
		ERRORS="$(( ERRORS + 1))"
	fi
done

exit "${ERRORS}"
