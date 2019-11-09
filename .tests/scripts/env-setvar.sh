#!/usr/bin/env bash

set -e
set -u
set -o pipefail

SCRIPT_PATH="$( cd "$(dirname "$0")" && pwd -P )"
DVLBOX_PATH="$( cd "${SCRIPT_PATH}/../.." && pwd -P )"
# shellcheck disable=SC1090
. "${SCRIPT_PATH}/.lib.sh"


# -------------------------------------------------------------------------------------------------
# Pre-check
# -------------------------------------------------------------------------------------------------

if [ "${#}" -ne "2" ]; then
	>&2 echo "Error, requires two arguments: <DIRECTIVE> <VALUE>"
	exit 1
fi

# -------------------------------------------------------------------------------------------------
# FUNCTIONS
# -------------------------------------------------------------------------------------------------

env_set() {
	local env_path="${1}"
	local directive="${2}"
	local value="${3}"

	if [ ! -f "${env_path}" ]; then
		>&2 echo "Error, .env file does not exist in: ${env_path}"
		return 1
	fi
	if ! grep -Eq "^${directive}=" "${env_path}"; then
		>&2 echo "Error, directive does not exist in .env: ${directive}"
		return 1
	fi
	if [ "$( grep -Ec "^${directive}=" "${env_path}" )" -ne "1" ]; then
		>&2 echo "Error, directive exists multiple times in .env: ${directive}"
		return 1
	fi

	replace "^${directive}=.*\$" "${directive}=${value}" "${env_path}"
}


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT
# -------------------------------------------------------------------------------------------------

env_set "${DVLBOX_PATH}/.env" "${1}" "${2}"
