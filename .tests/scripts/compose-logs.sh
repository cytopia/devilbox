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

if ! command -v docker-compose >/dev/null 2>&1; then
	>&2 echo "Error 'docker-compose' binary not found, but required."
	exit 1
fi


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT
# -------------------------------------------------------------------------------------------------

echo
echo "# --------------------------------------------------------------------------------"
echo "# docker-compose logs"
echo "# --------------------------------------------------------------------------------"
run "docker-compose logs || true" "1" "${DVLBOX_PATH}"

echo
echo "# --------------------------------------------------------------------------------"
echo "# docker-compose ps"
echo "# --------------------------------------------------------------------------------"
run "docker-compose ps || true" "1" "${DVLBOX_PATH}"


echo
echo "# --------------------------------------------------------------------------------"
echo "# Devilbox logs"
echo "# --------------------------------------------------------------------------------"

# shellcheck disable=SC2044
for file in $( find "${DVLBOX_PATH}/log" -type f ); do
	dirname="$( basename "$( dirname "${file}" )" )"
	filename="$( basename "${file}" )"
	echo
	echo "# ------------------------------------------------------------"
	echo "# [${dirname}] ${filename}"
	echo "# ------------------------------------------------------------"
	cat "${file}"
	echo
done


echo
echo "# --------------------------------------------------------------------------------"
echo "# mounts httpd"
echo "# --------------------------------------------------------------------------------"
run "docker-compose exec -T httpd ls -lap /shared/httpd/ || true" "1" "${DVLBOX_PATH}"


echo
echo "# --------------------------------------------------------------------------------"
echo "# mounts php"
echo "# --------------------------------------------------------------------------------"
run "docker-compose exec -T php ls -lap /shared/httpd/ || true" "1" "${DVLBOX_PATH}"
