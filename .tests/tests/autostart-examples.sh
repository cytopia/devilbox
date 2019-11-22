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
echo "# [Autostart]"
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


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT
# -------------------------------------------------------------------------------------------------

###
### Get autostart files
###
FILES="$( run "docker-compose exec -T --user devilbox php bash -c 'find /startup.1.d/ -name \"*.sh-example\"'" "${RETRIES}" "${DVLBOX_PATH}" "0" )"

echo
echo "#--------------------------------------------------------------------------------"
echo "# [Autostart] Test PHP specific startup scripts: /startup.1.d/"
echo "#--------------------------------------------------------------------------------"
echo

for file in ${FILES}; do
	run "docker-compose exec -T php bash ${file} 'ACCEPT_EULA=1'" "${RETRIES}" "${DVLBOX_PATH}"
done


###
### Get autostart files
###
FILES="$( run "docker-compose exec -T --user devilbox php bash -c 'find /startup.2.d/ -name \"*.sh-example\"'" "${RETRIES}" "${DVLBOX_PATH}" "0" )"

echo
echo "#--------------------------------------------------------------------------------"
echo "# [Autostart] Test global startup scripts: /startup.2.d/"
echo "#--------------------------------------------------------------------------------"
echo

for file in ${FILES}; do
	run "docker-compose exec -T php bash ${file}" "${RETRIES}" "${DVLBOX_PATH}"
done
