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


echo
echo "# --------------------------------------------------------------------------------------------------"
echo "# [modules] fetch external tests"
echo "# --------------------------------------------------------------------------------------------------"
echo

# -------------------------------------------------------------------------------------------------
# Pre-check
# -------------------------------------------------------------------------------------------------

if [ "${#}" -ne "1" ]; then
	>&2 echo "Error, requires one argument: <TEST_DIR>"
	exit 1
fi


# -------------------------------------------------------------------------------------------------
# Download Test directory from PHP-FPM via SVN
# -------------------------------------------------------------------------------------------------

VHOST="${1}"

# SVN allows to download a specific directory from GitHub so it is used instead of git cmd.
# The following ensures to download the module test directory

# Where to download from
TEST_REPO="https://github.com/devilbox/docker-php-fpm"
TEST_PATH="tests/mods/modules"

# Get current PHP_FPM git tag or branch
PHP_FPM_GIT_SLUG="$( \
	grep -E '^[[:space:]]+image:[[:space:]]+devilbox/php-fpm:' "${DVLBOX_PATH}/docker-compose.yml" \
	| perl -p -e 's/.*(base|mods|prod|work|)-//g'
)"

# Distinguish between tag or branch and build SVN path
if [[ ${PHP_FPM_GIT_SLUG} =~ ^[.0-9]+$ ]]; then
	SVN_PATH="${TEST_REPO}/tags/${PHP_FPM_GIT_SLUG}/${TEST_PATH}"
else
	SVN_PATH="${TEST_REPO}/branches/${PHP_FPM_GIT_SLUG}/${TEST_PATH}"
fi

# Cleanup and fetch data
run "docker-compose exec -T --user devilbox php rm -rf /shared/httpd/${VHOST} || true" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec -T --user devilbox php mkdir -p /shared/httpd/${VHOST}" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec -T --user devilbox php svn checkout ${SVN_PATH} /shared/httpd/${VHOST}/htdocs" "${RETRIES}" "${DVLBOX_PATH}"
