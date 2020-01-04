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
echo "# [Vendor] Opcache Control Panel"
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
### Get required env values
###
HOST_PORT_HTTPD="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HOST_PORT_HTTPD" )"


###
### Ensure Opcache Control Panel works
###
URL="/vendor/ocp.php"
printf "[TEST] Fetch %s" "${URL}"
if [ "$( run "\
	curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}${URL}' \
	| tac \
	| tac \
	| grep -Ec 'Used Memory'" \
	"${RETRIES}" "" "0" )" != "1" ]; then
	printf "\\r[FAIL] Fetch %s\\n" "${URL}"
	run "curl -sS 'http://localhost:${HOST_PORT_HTTPD}${URL}' || true"
	run "curl -sS -I 'http://localhost:${HOST_PORT_HTTPD}${URL}' || true"
	exit 1
else
	printf "\\r[OK]   Fetch %s\\n" "${URL}"
fi


URL="/vendor/ocp.php?FILES=1&GROUP=2&SORT=3"
printf "[TEST] Fetch %s" "${URL}"
if [ "$( run "\
	curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}${URL}' \
	| tac \
	| tac \
	| grep -Ec 'files cached'" \
	"${RETRIES}" "" "0" )" != "1" ]; then
	printf "\\r[FAIL] Fetch %s\\n" "${URL}"
	run "curl -sS 'http://localhost:${HOST_PORT_HTTPD}${URL}' || true"
	run "curl -sS -I 'http://localhost:${HOST_PORT_HTTPD}${URL}' || true"
	exit 1
else
	printf "\\r[OK]   Fetch %s\\n" "${URL}"
fi
