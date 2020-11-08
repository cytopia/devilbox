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
echo "# [Vendor] Adminer: MySQL"
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
### Retrieve URL for current Adminer version.
###
printf "[TEST] Retrieve Adminer URL"
if ! URL="$( run "\
	curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/index.php' \
	| tac \
	| tac \
	| grep -Eo '/vendor/adminer-[.0-9]+-en(-php8)?\\.php'" \
	"${RETRIES}" "" "0" )"; then
	printf "\\r[FAILED] Retrieve Adminer URL\\n"
	run "curl -sS 'http://localhost:${HOST_PORT_HTTPD}/index.php' || true"
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
	run "curl -sS 'http://localhost:${HOST_PORT_HTTPD}${URL}' || true"
	exit 1
else
	printf "\\r[OK]   Fetch %s\\n" "${URL}"
fi


###
### Test Adminer MySQL login
###
MYSQL_ROOT_PASSWORD="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "MYSQL_ROOT_PASSWORD" )"

printf "[TEST] Adminer MySQL login"
if ! run "\
	curl -sS --fail -c cookie.txt -b cookie.txt \
		-L \
		--data 'auth[driver]=server' \
		--data 'auth[server]=mysql' \
		--data 'auth[username]=root' \
		--data 'auth[password]=${MYSQL_ROOT_PASSWORD}' \
		--data 'auth[db]=' \
		'http://localhost:${HOST_PORT_HTTPD}${URL}?server=mysql&username=root' \
	| tac \
	| tac \
	| grep -Ei 'Database.+Collation.+Tables' >/dev/null" \
	"${RETRIES}" "" "0"; then
	printf "\\r[FAIL] Adminer MySQL login\\n"
	run "curl -sS -c cookie.txt -b cookie.txt \
		-L \
		--data 'auth[driver]=server' \
		--data 'auth[server]=mysql' \
		--data 'auth[username]=root' \
		--data 'auth[password]=${MYSQL_ROOT_PASSWORD}' \
		--data 'auth[db]=' \
		'http://localhost:${HOST_PORT_HTTPD}${URL}?server=mysql&username=root || true'" "1"
	run "curl -sS -I -c cookie.txt -b cookie.txt \
		-L \
		--data 'auth[driver]=server' \
		--data 'auth[server]=mysql' \
		--data 'auth[username]=root' \
		--data 'auth[password]=${MYSQL_ROOT_PASSWORD}' \
		--data 'auth[db]=' \
		'http://localhost:${HOST_PORT_HTTPD}${URL}?server=mysql&username=root || true'" "1"
	rm -f cookie.txt
	exit 1
else
	printf "\\r[OK]   Adminer MySQL login\\n"
fi
rm -f cookie.txt
