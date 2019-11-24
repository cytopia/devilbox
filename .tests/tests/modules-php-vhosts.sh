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
echo "# [modules] php ${1:-}"
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
### Store the error
###
ERROR=0


###
### Get vhost files
###
FILES="$( cd "${TESTS}" && find . -name '*.php' | sort )"

for file in ${FILES}; do
	name="${file#./}"
	if ! run "docker-compose exec -T --user devilbox php php /shared/httpd/${VHOST}/htdocs/${name} | grep -E '^(OK|SKIP)$'" "${RETRIES}" "${DVLBOX_PATH}"; then
		run "docker-compose exec -T --user devilbox php php /shared/httpd/${VHOST}/htdocs/${name} || true" "1" "${DVLBOX_PATH}"
		ERROR=1
	fi
	if ! run_fail "docker-compose exec -T --user devilbox php php /shared/httpd/${VHOST}/htdocs/${name} 2>&1 | grep -Ei 'fatal|except|err|warn|notice' > /dev/null" "${RETRIES}" "${DVLBOX_PATH}"; then
		run "docker-compose exec -T --user devilbox php php /shared/httpd/${VHOST}/htdocs/${name} || true" "1" "${DVLBOX_PATH}"
		ERROR=1
	fi
done


###
### Return error or success
###
exit "${ERROR}"
