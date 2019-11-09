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
DISABLED_VERSIONS_MONGO=("")


echo
echo "# --------------------------------------------------------------------------------------------------"
echo "# [Vendor] Adminer"
echo "# --------------------------------------------------------------------------------------------------"
echo


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT
# -------------------------------------------------------------------------------------------------

###
### Get required env values
###
HOST_PORT_HTTPD="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HOST_PORT_HTTPD" )"


###
### Get current PHP version
###
printf "[TEST] Retrieve PHP version"
if ! PHP_VERSION="$( run "\
	curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/index.php' \
	| tac \
	| tac \
	| grep -Eo 'PHP.*?\\([.0-9]+' \
	| grep -Eo '\\([.0-9]+' \
	| grep -Eo '[0-9]+\\.[0-9]+'" \
	"${RETRIES}" "" "0" )"; then
	printf "\\r[FAILED] Retrieve PHP version\\n"
	run "curl -v 'http://localhost:${HOST_PORT_HTTPD}/index.php' || true"
	exit 1
else
	printf "\\r[OK]   Retrieve PHP version: %s\\n" "${PHP_VERSION}"
fi


###
### Retrieve URL for current Adminer version.
###
printf "[TEST] Retrieve Adminer URL"
if ! URL="$( run "\
	curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/index.php' \
	| tac \
	| tac \
	| grep -Eo '/vendor/adminer-[.0-9]+-en\\.php'" \
	"${RETRIES}" "" "0" )"; then
	printf "\\r[FAILED] Retrieve Adminer URL\\n"
	run "curl -v 'http://localhost:${HOST_PORT_HTTPD}/index.php' || true"
	exit 1
else
	printf "\\r[OK]   Retrieve Adminer URL: %s\\n" "${URL}"
fi


###
### Ensure given Adminer version works
###
printf "[TEST] Fetch %s" "${URL}"
if ! run "\
	curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}${URL}' \
	| tac \
	| tac \
	| grep -Ei 'Login.+Adminer' >/dev/null" \
	"${RETRIES}" "" "0"; then
	printf "\\r[FAILED] Fetch %s\\n" "${URL}"
	run "curl -v 'http://localhost:${HOST_PORT_HTTPD}${URL}' || true"
	exit 1
else
	printf "\\r[OK]   Fetch %s\\n" "${URL}"
fi


###
### Test Adminer MySQL login
###
# TODO: password
printf "[TEST] Adminer MySQL login"
if ! run "\
	curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}${URL}?server=mysql&username=root' \
	| tac \
	| tac \
	| grep -Ei 'Database.+Collation.+Tables' >/dev/null" \
	"${RETRIES}" "" "0"; then
	printf "\\r[FAIL] Adminer MySQL login\\n"
	run "curl -v 'http://localhost:${HOST_PORT_HTTPD}${URL}?server=mysql&username=root' || true"
	run "curl -vI 'http://localhost:${HOST_PORT_HTTPD}${URL}?server=mysql&username=root' || true"
	exit 1
else
	printf "\\r[OK]   Adminer MySQL login\\n"
fi


###
### Test Adminer PostgreSQL login
###
printf "[TEST] Adminer PgSQL login"
if ! run "\
	curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}${URL}?pgsql=pgsql&username=postgres' \
	| tac \
	| tac \
	| grep -Ei 'Database.+Collation.+Tables' >/dev/null" \
	"${RETRIES}" "" "0"; then
	printf "\\r[FAIL] Adminer PgSQL login\\n"
	run "curl -v 'http://localhost:${HOST_PORT_HTTPD}${URL}?pgsql=pgsql&username=postgres' || true"
	run "curl -vI 'http://localhost:${HOST_PORT_HTTPD}${URL}?pgsql=pgsql&username=postgres' || true"
	exit 1
else
	printf "\\r[OK]   Adminer PgSQL login\\n"
fi


###
### Ensure only to check against desired versions
###
if [[ ${DISABLED_VERSIONS_MONGO[*]} =~ ${PHP_VERSION} ]]; then
	echo "Skipping Adminer Mongo login test for PHP ${PHP_VERSION}"
	exit 0
fi


###
### Test Adminer MongoDB login
###
printf "[TEST] Adminer Mongo login"
if ! run "\
	curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}${URL}?mongo=mongo&username=' \
	| tac \
	| tac \
	| grep -Ei 'Database.+Collation.+Tables' >/dev/null" \
	"${RETRIES}" "" "0"; then
	printf "\\r[FAIL] Adminer Mongo login\\n"
	run "curl -v 'http://localhost:${HOST_PORT_HTTPD}${URL}?mongo=mongo&username=' || true"
	run "curl -vI 'http://localhost:${HOST_PORT_HTTPD}${URL}?mongo=mongo&username=' || true"
	exit 1
else
	printf "\\r[OK]   Adminer Mongo login\\n"
fi
