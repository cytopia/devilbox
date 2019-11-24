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

if [ "${#}" -ne "1" ]; then
	>&2 echo "Error, requires one argument: <DIRECTIVE>"
	exit 1
fi

if ! command -v awk >/dev/null 2>&1; then
	>&2 echo "Error 'awk' binary not found, but required."
	exit 1
fi


# -------------------------------------------------------------------------------------------------
# FUNCTIONS
# -------------------------------------------------------------------------------------------------

env_get() {
	local env_path="${1}"
	local directive="${2}"

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

	grep -E "^${directive}=.*\$" "${env_path}" \
		| awk -F'=' '{for (i=2; i<NF; i++) printf $i "="; print $NF}'
}



# -------------------------------------------------------------------------------------------------
# ENTRYPOINT
# -------------------------------------------------------------------------------------------------

env_get "${DVLBOX_PATH}/.env" "${1}"
