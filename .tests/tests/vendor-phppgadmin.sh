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
DISABLED_VERSIONS=("8.0")


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


###
### Retrieve URL for current PHP version.
### Older PHP versions are presented a link with a different version due to compatibility.
###
printf "[TEST] Retrieve phpPgAdmin URL"
if ! URL="$( run "\
	curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/index.php' \
	| tac \
	| tac \
	| grep -Eo '/vendor/phppgadmin-[.0-9]+/'" \
	"${RETRIES}" "" "0" )"; then
	printf "\\r[FAILED] Retrieve phpPgAdmin URL\\n"
	run "curl -sS 'http://localhost:${HOST_PORT_HTTPD}/index.php' || true"
	exit 1
else
	printf "\\r[OK]   Retrieve phpPgAdmin URL: %s\\n" "${URL}"
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
			printf "\\r[FAIL] Fetch %sintro.php\\n" "${URL}"
			curl -sS "http://localhost:${HOST_PORT_HTTPD}${URL}intro.php" || true
			curl -sS -I "http://localhost:${HOST_PORT_HTTPD}${URL}intro.php" || true
			exit 1
		else
			printf "\\r[OK]   Fetch %sintro.php (3 rounds)\\n" "${URL}"
		fi
	else
		printf "\\r[OK]   Fetch %sintro.php (2 rounds)\\n" "${URL}"
	fi
else
	printf "\\r[OK]   Fetch %sintro.php (1 round)\\n" "${URL}"
fi


####
#### Login (get token URL)
####
## 1st Try
#printf "[TEST] Retrieve phpPgAdmin token page"
#if ! TOKEN_URL="$( curl -sS -c cookie.txt localhost${URL}servers.php | tac | tac | grep -Eo "\"redirect\.php\?subject=server.+\"" )"; then
#	# 2nd Try
#	sleep 1
#	if ! TOKEN_URL="$( curl -sS -c cookie.txt localhost${URL}servers.php | tac | tac | grep -Eo "\"redirect\.php\?subject=server.+\"" )"; then
#		# 3rd Try
#		sleep 1
#		if ! TOKEN_URL="$( curl -sS -c cookie.txt localhost${URL}servers.php | tac | tac | grep -Eo "\"redirect\.php\?subject=server.+\"" )"; then
#			printf "\\r[FAIL] Retrieve phpPgAdmin login page\\n"
#			curl -sS localhost/${URL}servers.php || true
#			curl -sSI localhost/${URL}servers.php || true
#			rm -f cookie.txt
#			exit 1
#		else
#			TOKEN_URL="$( echo "${TOKEN_URL}" | sed 's/"//g' )"
#			TOKEN_URL="$( echo "${TOKEN_URL}" | sed 's/&amp;/\&/g' )"
#			printf "\\r[OK]   Retrieve phpPgAdmin token page (3 rounds): ${TOKEN_URL}\\n"
#		fi
#	else
#		TOKEN_URL="$( echo "${TOKEN_URL}" | sed 's/"//g' )"
#		TOKEN_URL="$( echo "${TOKEN_URL}" | sed 's/&amp;/\&/g' )"
#		printf "\\r[OK]   Retrieve phpPgAdmin login token (2 rounds): ${TOKEN_URL}\\n"
#	fi
#else
#	TOKEN_URL="$( echo "${TOKEN_URL}" | sed 's/"//g' )"
#	TOKEN_URL="$( echo "${TOKEN_URL}" | sed 's/&amp;/\&/g' )"
#	printf "\\r[OK]   Retrieve phpPgAdmin token page (1 round): ${TOKEN_URL}\\n"
#fi
#
#
####
#### Login (get Login Token)
####
#TOKEN=
#printf "[TEST] Retrieve phpPgAdmin login token"
## 1st Try
#if ! TOKEN="$( curl -sS -c cookie.txt -b cookie.txt "localhost${URL}${TOKEN_URL}" | tac | tac | grep -Eo "loginPassword_[a-zA-Z0-9]+" )"; then
#	# 2nd Try
#	sleep 1
#	if ! TOKEN="$( curl -sS -c cookie.txt -b cookie.txt "localhost${URL}${TOKEN_URL}" | tac | tac | grep -Eo "loginPassword_[a-zA-Z0-9]+" )"; then
#		# 3rd Try
#		sleep 1
#		if ! TOKEN="$( curl -sS -c cookie.txt -b cookie.txt "localhost${URL}${TOKEN_URL}" | tac | tac | grep -Eo "loginPassword_[a-zA-Z0-9]+" )"; then
#			printf "\\r[FAIL] Retrieve phpPgAdmin login token\\n"
#			curl -sS "${TOKEN_URL_URL}" || true
#			curl -sSI "${TOKEN_URL_URL}" || true
#			rm -f cookie.txt
#			exit 1
#		else
#			TOKEN="$( echo "${TOKEN}" | head -1 )"
#			printf "\\r[OK]   Retrieve phpPgAdmin login token (3 rounds): ${TOKEN}\\n"
#		fi
#	else
#		TOKEN="$( echo "${TOKEN}" | head -1 )"
#		printf "\\r[OK]   Retrieve phpPgAdmin login token (2 rounds): ${TOKEN}\\n"
#	fi
#else
#	TOKEN="$( echo "${TOKEN}" | head -1 )"
#	printf "\\r[OK]   Retrieve phpPgAdmin login token (1 round): ${TOKEN}\\n"
#fi
#
#
####
#### Login
####
#
#printf "[TEST] Submit phpPgAdmin POST login"
## 1st Try
#if ! curl -sS -c cookie.txt -b cookie.txt \
#	-d "subject=server&server=pgsql%3A5432%3Aallow&loginServer=pgsql%3A5432%3Aallow&loginUsername=postgres&${TOKEN}=&loginSubmit=Login" \
#	localhost${URL}redirect.php 2>/dev/null | grep -q "Create database"; then
#	# 2nd Try
#	sleep 1
#	if ! curl -sS -c cookie.txt -b cookie.txt \
#		-d "subject=server&server=pgsql%3A5432%3Aallow&loginServer=pgsql%3A5432%3Aallow&loginUsername=postgres&${TOKEN}=&loginSubmit=Login" \
#		localhost${URL}redirect.php 2>/dev/null | grep -q "Create database"; then
#		# 3rd Try
#		sleep 1
#		if ! curl -sS -c cookie.txt -b cookie.txt \
#			-d "subject=server&server=pgsql%3A5432%3Aallow&loginServer=pgsql%3A5432%3Aallow&loginUsername=postgres&${TOKEN}=&loginSubmit=Login" \
#			localhost${URL}redirect.php 2>/dev/null | grep -q "Create database"; then
#			printf "\\r[FAIL] Submit phpPgAdmin POST login\\n"
#			curl -sS -c cookie.txt -b cookie.txt \
#				-d "subject=server&server=pgsql%3A5432%3Aallow&loginServer=pgsql%3A5432%3Aallow&loginUsername=postgres&${TOKEN}=&loginSubmit=Login" \
#				localhost${URL}redirect.php || true
#			curl -sSI -c cookie.txt -b cookie.txt \
#				-d "subject=server&server=pgsql%3A5432%3Aallow&loginServer=pgsql%3A5432%3Aallow&loginUsername=postgres&${TOKEN}=&loginSubmit=Login" \
#				localhost${URL}redirect.php || true
#			rm -f cookie.txt || true
#			exit 1
#		else
#			printf "\\r[OK]   Submit phpPgAdmin POST login (3 rounds)\\n"
#		fi
#	else
#		printf "\\r[OK]   Submit phpPgAdmin POST login (2 rounds)\\n"
#	fi
#else
#	printf "\\r[OK]   Submit phpPgAdmin POST login (1 round)\\n"
#fi
#
#rm -f cookie.txt || true


###
### Configuration File
###
SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
DVLBOXPATH="${SCRIPTPATH}/../../"
CONFIGPATH="${DVLBOXPATH}/.devilbox/www/htdocs${URL%index\.php}conf/config.inc.php"
LIBPATH="${DVLBOXPATH}/.devilbox/www/htdocs${URL%index\.php}libraries/lib.inc.php"

printf "[TEST] config.inc.php exists"
if [ ! -f "${CONFIGPATH}" ]; then
	printf "\\r[FAIL] config.inc.php exists: no\\n"
	exit 1
else
	printf "\\r[OK]   config.inc.php exists: yes\\n"
fi

# $conf['servers'][0]['host'] = 'pgsql';
printf "[TEST] config.inc.php check: \$conf['servers'][0]['host'] = 'pgsql';"
if ! grep -E "^[[:space:]]*\\\$conf\\['servers'\\]\\[0\\]\\['host'\\][[:space:]]*=[[:space:]]*'pgsql';" "${CONFIGPATH}" >/dev/null; then
	printf "\\r[FAIL] config.inc.php check: \$conf['servers'][0]['host'] = 'pgsql';\\n"
	if ! grep 'servers' "${CONFIGPATH}"; then
		cat "${CONFIGPATH}"
	fi
	exit 1
else
	printf "\\r[OK]   config.inc.php check: \$conf['servers'][0]['host'] = 'pgsql';\\n"
fi

# $conf['extra_login_security'] = false;
printf "[TEST] config.inc.php check: \$conf['extra_login_security'] = false;"
if ! grep -E "^[[:space:]]*\\\$conf\\['extra_login_security'\\][[:space:]]*=[[:space:]]*false;" "${CONFIGPATH}" >/dev/null; then
	printf "\\r[FAIL] config.inc.php check: \$conf['extra_login_security'] = false;\\n"
	if ! grep 'extra_login_security' "${CONFIGPATH}"; then
		cat "${CONFIGPATH}"
	fi
	exit 1
else
	printf "\\r[OK]   config.inc.php check: \$conf['extra_login_security'] = false;\\n"
fi

# error_reporting(E_ERROR | E_WARNING | E_PARSE);
printf "[TEST] lib.inc.php check: error_reporting(E_ERROR | E_WARNING | E_PARSE);"
if ! grep -E "^[[:space:]]*error_reporting\\(E_ERROR[[:space:]]*\\|[[:space:]]*E_WARNING[[:space:]]*\\|[[:space:]]*E_PARSE\\);" "${LIBPATH}" >/dev/null; then
	printf "\\r[FAIL] lib.inc.php check: error_reporting(E_ERROR | E_WARNING | E_PARSE);\\n"
	if ! grep 'error_reporting' "${LIBPATH}"; then
		cat "${LIBPATH}"
	fi
	exit 1
else
	printf "\\r[OK]   lib.inc.php check: error_reporting(E_ERROR | E_WARNING | E_PARSE);\\n"
fi


###
### Evaluate successful phpPgAdmin login
###
printf "[TEST] Evaluate successful phpPgAdmin login"
# 1st Try
if [ "$(curl -sS --fail "http://localhost:${HOST_PORT_HTTPD}${URL}redirect.php?subject=server&server=pgsql%3A5432%3Aallow&" | tac | tac | grep -Ec 'data">(Database|Owner|Collation|Tablespace)')" != "4" ]; then
	# 2nd Try
	sleep 1
	if [ "$(curl -sS --fail "http://localhost:${HOST_PORT_HTTPD}${URL}redirect.php?subject=server&server=pgsql%3A5432%3Aallow&" | tac | tac | grep -Ec 'data">(Database|Owner|Collation|Tablespace)')" != "4" ]; then
		# 3rd Try
		sleep 1
		if [ "$(curl -sS --fail "http://localhost:${HOST_PORT_HTTPD}${URL}redirect.php?subject=server&server=pgsql%3A5432%3Aallow&" | tac | tac | grep -Ec 'data">(Database|Owner|Collation|Tablespace)')" != "4" ]; then
			printf "\\r[FAIL] Evaluate successful phpPgAdmin login\\n"
			curl -sS "http://localhost:${HOST_PORT_HTTPD}${URL}redirect.php?subject=server&server=pgsql%3A5432%3Aallow&" || true
			curl -sS -I "http://localhost:${HOST_PORT_HTTPD}${URL}redirect.php?subject=server&server=pgsql%3A5432%3Aallow&" || true
			exit 1
		else
			printf "\\r[OK]   Evaluate successful phpPgAdmin login (3 rounds)\\n"
		fi
	else
		printf "\\r[OK]   Evaluate successful phpPgAdmin login (2 rounds)\\n"
	fi
else
	printf "\\r[OK]   Evaluate successful phpPgAdmin login (1 round)\\n"
fi
