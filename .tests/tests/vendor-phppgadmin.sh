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
echo "# [Vendor] phpPgAdmin"
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
PGSQL_ROOT_USER="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "PGSQL_ROOT_USER" )"
PGSQL_ROOT_PASSWORD="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "PGSQL_ROOT_PASSWORD" )"
DEVILBOX_VENDOR_PHPPGADMIN_AUTOLOGIN="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "DEVILBOX_VENDOR_PHPPGADMIN_AUTOLOGIN" )"


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT: Version
# -------------------------------------------------------------------------------------------------

# Retrieve URL for current PHP version.
# Older PHP versions are presented a link with a different version due to compatibility.
printf "[TEST] Retrieve phpPgAdmin URL"
if ! URL="$( run "\
	curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/index.php' \
	| tac \
	| tac \
	| grep -Eo '/vendor/phppgadmin-[.0-9]+/'" \
	"${RETRIES}" "" "0" )"; then
	print_fail "1" "1" "Retrieve phpPgAdmin URL"
	run "curl -sS 'http://localhost:${HOST_PORT_HTTPD}/index.php' || true"
	exit 1
else
	print_succ "1" "1" "Retrieve phpPgAdmin URL: ${URL}"
fi


###
### Ensure given phpPgAdmin version works
###
printf "[TEST] Fetch %sintro.php" "${URL}"
# 1st Try
if ! curl -sS --fail "http://localhost:${HOST_PORT_HTTPD}${URL}intro.php" | tac | tac | grep -Eiq "welcome to phpPgAdmin"; then
	# 2nd Try
	sleep 1
	if ! curl -sS --fail "http://localhost:${HOST_PORT_HTTPD}${URL}intro.php" | tac | tac | grep -Eiq "welcome to phpPgAdmin"; then
		# 3rd Try
		sleep 1
		if ! curl -sS --fail "http://localhost:${HOST_PORT_HTTPD}${URL}intro.php" | tac | tac | grep -Eiq "welcome to phpPgAdmin"; then
			print_fail "1" "1" "Fetch ${URL}intro.php"
			curl -sS "http://localhost:${HOST_PORT_HTTPD}${URL}intro.php" || true
			curl -sS -I "http://localhost:${HOST_PORT_HTTPD}${URL}intro.php" || true
			exit 1
		else
			print_succ "1" "1" "Fetch ${URL}intro.php (3 rounds)"
		fi
	else
		print_succ "1" "1" "Fetch ${URL}intro.php (2 rounds)"
	fi
else
	print_succ "1" "1" "Fetch ${URL}intro.php (1 round)"
fi


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT: Configuration file
# -------------------------------------------------------------------------------------------------

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
DVLBOXPATH="${SCRIPTPATH}/../../"
CONFIGPATH="${DVLBOXPATH}/.devilbox/www/htdocs${URL%index\.php}conf/config.inc.php"
LIBPATH="${DVLBOXPATH}/.devilbox/www/htdocs${URL%index\.php}libraries/lib.inc.php"

printf "[TEST] config.inc.php exists"
if [ ! -f "${CONFIGPATH}" ]; then
	print_fail "1" "1" "config.inc.php exists: no"
	exit 1
else
	print_succ "1" "1" "config.inc.php exists: yes"
fi

# $conf['servers'][0]['host'] = 'pgsql';
printf "[TEST] config.inc.php check: \$conf['servers'][0]['host'] = 'pgsql';"
if ! grep -E "^[[:space:]]*\\\$conf\\['servers'\\]\\[0\\]\\['host'\\][[:space:]]*=[[:space:]]*'pgsql';" "${CONFIGPATH}" >/dev/null; then
	print_fail "1" "1" "config.inc.php check: \$conf['servers'][0]['host'] = 'pgsql';"
	if ! grep 'servers' "${CONFIGPATH}"; then
		cat "${CONFIGPATH}"
	fi
	exit 1
else
	print_succ "1" "1" "config.inc.php check: \$conf['servers'][0]['host'] = 'pgsql';"
fi

# $conf['extra_login_security'] = false;
printf "[TEST] config.inc.php check: \$conf['extra_login_security'] = false;"
if ! grep -E "^[[:space:]]*\\\$conf\\['extra_login_security'\\][[:space:]]*=[[:space:]]*false;" "${CONFIGPATH}" >/dev/null; then
	print_fail "1" "1" "config.inc.php check: \$conf['extra_login_security'] = false;"
	if ! grep 'extra_login_security' "${CONFIGPATH}"; then
		cat "${CONFIGPATH}"
	fi
	exit 1
else
	print_succ "1" "1" "config.inc.php check: \$conf['extra_login_security'] = false;"
fi

# error_reporting(E_ERROR | E_WARNING | E_PARSE);
printf "[TEST] lib.inc.php check: error_reporting(E_ERROR | E_WARNING | E_PARSE);"
if ! grep -E "^[[:space:]]*error_reporting\\(E_ERROR[[:space:]]*\\|[[:space:]]*E_WARNING[[:space:]]*\\|[[:space:]]*E_PARSE\\);" "${LIBPATH}" >/dev/null; then
	print_fail "1" "1" "lib.inc.php check: error_reporting(E_ERROR | E_WARNING | E_PARSE);"
	if ! grep 'error_reporting' "${LIBPATH}"; then
		cat "${LIBPATH}"
	fi
	exit 1
else
	print_succ "1" "1" "lib.inc.php check: error_reporting(E_ERROR | E_WARNING | E_PARSE);"
fi


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT: Login Check
# -------------------------------------------------------------------------------------------------

###
### Autologin disabled
###
if [ "${DEVILBOX_VENDOR_PHPPGADMIN_AUTOLOGIN}" != "1" ]; then

	###
	### Login (get token URL)
	###
	printf "[TEST] Retrieve phpPgAdmin token page"
	if ! TOKEN_URL="$( run "curl -sS --fail -c cookie.txt 'http://localhost:${HOST_PORT_HTTPD}${URL}servers.php' | tac | tac | grep -Eo '\"redirect\\.php\\?subject=server.+\"' " "${RETRIES}" "" "0" )"; then
		print_fail "1" "1" "Retrieve phpPgAdmin login page"
		run "curl -sS 'http://localhost:${HOST_PORT_HTTPD}${URL}servers.php' || true"
		run "curl -sSI 'http://localhost:${HOST_PORT_HTTPD}${URL}servers.php' || true"
		rm -f cookie.txt
		exit 1
	else
		TOKEN_URL="${TOKEN_URL//\"/}"
		TOKEN_URL="${TOKEN_URL//&amp;/&}"
		print_succ "1" "1" "Retrieve phpPgAdmin token page: ${TOKEN_URL}"
	fi

	###
	### Login (get Login Token)
	###
	printf "[TEST] Retrieve phpPgAdmin login token"
	# 1st Try
	if ! TOKEN="$( run "curl -sS --fail -c cookie.txt -b cookie.txt 'http://localhost:${HOST_PORT_HTTPD}${URL}${TOKEN_URL}' | tac | tac | grep -Eo 'loginPassword_[a-zA-Z0-9]+'" "${RETRIES}" "" "0" )"; then
		print_fail "1" "1" "Retrieve phpPgAdmin login token"
		run "curl -sS 'http://localhost:${TOKEN_URL_URL}' || true"
		run "curl -sSI 'http://localhost:${TOKEN_URL_URL}' || true"
		rm -f cookie.txt
		exit 1
	else
		TOKEN="$( echo "${TOKEN}" | head -1 )"
		print_succ "1" "1" "Retrieve phpPgAdmin login token: ${TOKEN}"
	fi

	###
	### Login
	###
	printf "[TEST] Submit phpPgAdmin POST login"
	# 1st Try
	if ! run "curl -sS --fail -c cookie.txt -b cookie.txt \
			--data 'subject=server' \
			--data 'server=pgsql%3A5432%3Aallow' \
			--data 'loginServer=pgsql%3A5432%3Aallow' \
			--data 'loginUsername=${PGSQL_ROOT_USER}' \
			--data '${TOKEN}=${PGSQL_ROOT_PASSWORD}' \
			--data 'loginSubmit=Login' \
		'http://localhost:${HOST_PORT_HTTPD}${URL}redirect.php' 2>/dev/null \
		| grep -q 'Create database'" "${RETRIES}" "" "0"; then
		print_fail "1" "1" "Submit phpPgAdmin POST login"
		run "curl -sS -c cookie.txt -b cookie.txt \
			--data 'subject=server' \
			--data 'server=pgsql%3A5432%3Aallow' \
			--data 'loginServer=pgsql%3A5432%3Aallow' \
			--data 'loginUsername=${PGSQL_ROOT_USER}' \
			--data '${TOKEN}=${PGSQL_ROOT_PASSWORD}' \
			--data 'loginSubmit=Login' \
		'http://localhost:${HOST_PORT_HTTPD}${URL}redirect.php 2>/dev/null'"
		run "curl -sS -I -c cookie.txt -b cookie.txt \
			--data 'subject=server' \
			--data 'server=pgsql%3A5432%3Aallow' \
			--data 'loginServer=pgsql%3A5432%3Aallow' \
			--data 'loginUsername=${PGSQL_ROOT_USER}' \
			--data '${TOKEN}=${PGSQL_ROOT_PASSWORD}' \
			--data 'loginSubmit=Login' \
		'http://localhost:${HOST_PORT_HTTPD}${URL}redirect.php 2>/dev/null'"
		rm -f cookie.txt || true
		exit 1
	else
		print_succ "1" "1" "Submit phpPgAdmin POST login"
	fi
fi


###
### Evaluate successful phpPgAdmin login
###
printf "[TEST] Evaluate successful phpPgAdmin login"
if [ "$( run "curl -sS --fail \
	-c cookie.txt \
	-b cookie.txt \
	'http://localhost:${HOST_PORT_HTTPD}${URL}redirect.php?subject=server&server=pgsql%3A5432%3Aallow&' \
	| tac \
	| tac \
	| grep -Ec 'data\">(Database|Owner|Collation|Tablespace)'" "${RETRIES}" "" "0" )" != "4" ]; then
	print_fail "1" "1" "Evaluate successful phpPgAdmin login"
	run "curl -sS 'http://localhost:${HOST_PORT_HTTPD}${URL}redirect.php?subject=server&server=pgsql%3A5432%3Aallow&' || true"
	run "curl -sS -I 'http://localhost:${HOST_PORT_HTTPD}${URL}redirect.php?subject=server&server=pgsql%3A5432%3Aallow&' || true"
	rm -f cookie.txt || true
	exit 1
else
	print_succ "1" "1" "Evaluate successful phpPgAdmin login"
fi
rm -f cookie.txt || true
