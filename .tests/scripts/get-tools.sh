#!/usr/bin/env bash

# NOTE: Parsing curl to tac to circumnvent "failed writing body"
# https://stackoverflow.com/questions/16703647/why-curl-return-and-error-23-failed-writing-body

set -e
set -u
set -o pipefail

SCRIPT_PATH="$( cd "$(dirname "$0")" && pwd -P )"
DVLBOX_PATH="$( cd "${SCRIPT_PATH}/../.." && pwd -P )"
# shellcheck disable=SC1090
. "${SCRIPT_PATH}/.lib.sh"

RETRIES=10


# -------------------------------------------------------------------------------------------------
# FUNCTIONS
# -------------------------------------------------------------------------------------------------

PHP_TAG="$( grep 'devilbox/php' "${DVLBOX_PATH}/docker-compose.yml" | sed 's/^.*-work-//g' )"
PHP_TOOLS="$( run "curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/doc/available-tools.md'" "${RETRIES}" )";

echo "${PHP_TOOLS}" | grep -A 1000000 'TOOLS_WORK_START'
