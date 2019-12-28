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
echo "# [Vendor] phpMyAdmin"
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
MYSQL_ROOT_PASSWORD="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "MYSQL_ROOT_PASSWORD" )"
DEVILBOX_VENDOR_PHPMYADMIN_AUTOLOGIN="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "DEVILBOX_VENDOR_PHPMYADMIN_AUTOLOGIN" )"


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT: Version
# -------------------------------------------------------------------------------------------------

# Retrieve URL for current PHP version.
# Older PHP versions are presented a link with a different version due to compatibility.
printf "[TEST] Retrieve phpMyAdmin URL"
if ! URL="$( run "\
	curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/index.php' \
	| tac \
	| tac \
	| grep -Eo '/vendor/phpmyadmin-[.0-9]+/index\\.php'" \
	"${RETRIES}" "" "0" )"; then
	printf "\\r[FAILED] Retrieve phpMyAdmin URL\\n"
	run "curl -sS 'http://localhost:${HOST_PORT_HTTPD}/index.php' || true"
	exit 1
else
	printf "\\r[OK]   Retrieve phpMyAdmin URL: %s\\n" "${URL}"
fi


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT: Configuration file
# -------------------------------------------------------------------------------------------------

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
DVLBOXPATH="${SCRIPTPATH}/../../"
CONFIGPATH="${DVLBOXPATH}/.devilbox/www/htdocs${URL%index\.php}config.inc.php"

printf "[TEST] config.inc.php exists"
if [ ! -f "${CONFIGPATH}" ]; then
	printf "\\r[FAIL] config.inc.php exists: no\\n"
	exit 1
else
	printf "\\r[OK]   config.inc.php exists: yes\\n"
fi


# error_reporting(0);
printf "[TEST] config.inc.php check: error_reporting(0);"
if ! grep -E "^error_reporting\\(0\\);" "${CONFIGPATH}" >/dev/null; then
	printf "\\r[FAIL] config.inc.php check: error_reporting(0);\\n"
	if ! grep 'error_reporting' "${CONFIGPATH}"; then
		cat "${CONFIGPATH}"
	fi
	exit 1
else
	printf "\\r[OK]   config.inc.php check: error_reporting(0);\\n"
fi

# $cfg['TempDir'] = '/tmp';
printf "[TEST] config.inc.php check: \$cfg['TempDir'] = '/tmp';"
if ! grep -E "^\\\$cfg\\['TempDir'\\][[:space:]]*=[[:space:]]*'/tmp';" "${CONFIGPATH}" >/dev/null; then
	printf "\\r[FAIL] config.inc.php check: \$cfg['TempDir'] = '/tmp';\\n"
	if ! grep 'TempDir' "${CONFIGPATH}"; then
		cat "${CONFIGPATH}"
	fi
	exit 1
else
	printf "\\r[OK]   config.inc.php check: \$cfg['TempDir'] = '/tmp';\\n"
fi

# $cfg['CheckConfigurationPermissions'] = false;
printf "[TEST] config.inc.php check: \$cfg['CheckConfigurationPermissions'] = false;"
if ! grep -E "^\\\$cfg\\['CheckConfigurationPermissions'\\][[:space:]]*=[[:space:]]*false;" "${CONFIGPATH}" >/dev/null; then
	printf "\\r[FAIL] config.inc.php check: \$cfg['CheckConfigurationPermissions'] = false;\\n"
	if ! grep 'CheckConfigurationPermissions' "${CONFIGPATH}"; then
		cat "${CONFIGPATH}"
	fi
	exit 1
else
	printf "\\r[OK]   config.inc.php check: \$cfg['CheckConfigurationPermissions'] = false;\\n"
fi

# $cfg['blowfish_secret'] = '...'
printf "[TEST] config.inc.php check: \$cfg['blowfish_secret'] = '...';"
if ! grep -E "^\\\$cfg\\['blowfish_secret'\\][[:space:]]*=[[:space:]]*'.{32,}';" "${CONFIGPATH}" >/dev/null; then
	printf "\\r[FAIL] config.inc.php check: \$cfg['blowfish_secret'] = '...';\\n"
	if ! grep 'blowfish_secret' "${CONFIGPATH}"; then
		cat "${CONFIGPATH}"
	fi
	exit 1
else
	printf "\\r[OK]   config.inc.php check: \$cfg['blowfish_secret'] = '...';\\n"
fi

# $cfg['SendErrorReports'] = 'never';
printf "[TEST] config.inc.php check: \$cfg['SendErrorReports'] = 'never';"
if ! grep "^\\\$cfg\\['SendErrorReports'\\][[:space:]]*=[[:space:]]*'never';" "${CONFIGPATH}" >/dev/null; then
	printf "\\r[FAIL] config.inc.php check: \$cfg['SendErrorReports'] = 'never';\\n"
	if ! grep 'SendErrorReports' "${CONFIGPATH}"; then
		cat "${CONFIGPATH}"
	fi
	exit 1
else
	printf "\\r[OK]   config.inc.php check: \$cfg['SendErrorReports'] = 'never';\\n"
fi

# $cfg['Servers'][$i]['host'] = 'mysql';
printf "[TEST] config.inc.php check: \$cfg['Servers'][\$i]['host'] = 'mysql';"
if ! grep "^\\\$cfg\\['Servers'\\]\\[\$i\\]\\['host'\\][[:space:]]*=[[:space:]]*'mysql';" "${CONFIGPATH}" >/dev/null; then
	printf "\\r[FAIL] config.inc.php check: \$cfg['Servers'][\$i]['host'] = 'mysql';\\n"
	if ! grep 'Servers' "${CONFIGPATH}" | grep 'host'; then
		cat "${CONFIGPATH}"
	fi
	exit 1
else
	printf "\\r[OK]   config.inc.php check: \$cfg['Servers'][\$i]['host'] = 'mysql';\\n"
fi

# $cfg['Servers'][$i]['connect_type'] = 'tcp';
printf "[TEST] config.inc.php check: \$cfg['Servers'][\$i]['connect_type'] = 'tcp';"
if ! grep "^\\\$cfg\\['Servers'\\]\\[\$i\\]\\['connect_type'\\][[:space:]]*=[[:space:]]*'tcp';" "${CONFIGPATH}" >/dev/null; then
	printf "\\r[FAIL] config.inc.php check: \$cfg['Servers'][\$i]['connect_type'] = 'tcp';\\n"
	if ! grep 'Servers' "${CONFIGPATH}" | grep 'connect_type'; then
		cat "${CONFIGPATH}"
	fi
	exit 1
else
	printf "\\r[OK]   config.inc.php check: \$cfg['Servers'][\$i]['connect_type'] = 'tcp';\\n"
fi

# $cfg['Servers'][$i]['compress'] = false;
printf "[TEST] config.inc.php check: \$cfg['Servers'][\$i]['compress'] = false;"
if ! grep "^\\\$cfg\\['Servers'\\]\\[\$i\\]\\['compress'\\][[:space:]]*=[[:space:]]*false;" "${CONFIGPATH}" >/dev/null; then
	printf "\\r[FAIL] config.inc.php check: \$cfg['Servers'][\$i]['compress'] = false;\\n"
	if ! grep 'Servers' "${CONFIGPATH}" | grep 'compress'; then
		cat "${CONFIGPATH}"
	fi
	exit 1
else
	printf "\\r[OK]   config.inc.php check: \$cfg['Servers'][\$i]['compress'] = false;\\n"
fi

# $cfg['Servers'][$i]['AllowNoPassword'] = true;
printf "[TEST] config.inc.php check: \$cfg['Servers'][\$i]['AllowNoPassword'] = false;"
if ! grep "^\\\$cfg\\['Servers'\\]\\[\$i\\]\\['AllowNoPassword'\\][[:space:]]*=[[:space:]]*true;" "${CONFIGPATH}" >/dev/null; then
	printf "\\r[FAIL] config.inc.php check: \$cfg['Servers'][\$i]['AllowNoPassword'] = true;\\n"
	if ! grep 'Servers' "${CONFIGPATH}" | grep 'AllowNoPassword'; then
		cat "${CONFIGPATH}"
	fi
	exit 1
else
	printf "\\r[OK]   config.inc.php check: \$cfg['Servers'][\$i]['AllowNoPassword'] = true;\\n"
fi


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT: Login Check
# -------------------------------------------------------------------------------------------------

###
### Autologin disabled
###
if [ "${DEVILBOX_VENDOR_PHPMYADMIN_AUTOLOGIN}" != "1" ]; then

	###
	### Ensure given phpMyAdmin version works
	###
	printf "[TEST] Fetch %s" "${URL}"
	if ! run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}${URL}' | tac | tac | grep -Ei 'welcome to.+phpMyAdmin' >/dev/null" "${RETRIES}" "" "0"; then
		printf "\\r[FAIL] Fetch %s\\n" "${URL}"
		run "curl -sS 'http://localhost:${HOST_PORT_HTTPD}${URL}' || true"
		run "curl -sSI 'http://localhost:${HOST_PORT_HTTPD}${URL}' || true"
		exit 1
	else
		printf "\\r[OK]   Fetch %s\\n" "${URL}"
	fi


	###
	### Login
	###
	TOKEN=
	printf "[TEST] Retrieve phpMyAdmin login page"
	while true; do
		# Try again until it succeeds
		if ! CONTENT="$( curl -sS --fail -c cookie.txt "http://localhost:${HOST_PORT_HTTPD}${URL}" )"; then
			rm -f cookie.txt
			printf "e"
			continue;
		fi
		# Extract the token
		if ! TOKEN="$( echo "${CONTENT}" \
			| grep -Eo "name=\"token\" value=\".+\"" \
			| head -1 \
			| grep -Eo "value=\".+\"" \
			| sed -e 's/^value="//g' -e 's/"$//g' )"; then
			rm -f cookie.txt
			printf "w"
			continue;
		fi
		# Ensure Token does not contain '!'
		if echo "${TOKEN}" | grep -q "!"; then
			rm -f cookie.txt
			printf "!"
			continue;
		fi
		# Ensure Token does not contain '&'
		if echo "${TOKEN}" | grep -q "&"; then
			rm -f cookie.txt
			printf "&"
			continue;
		fi
		# Ensure Token does not contain '?'
		if echo "${TOKEN}" | grep -q "?"; then
			rm -f cookie.txt
			printf "?"
			continue;
		fi
		# Ensure Token does not contain '"'
		if echo "${TOKEN}" | grep -q "\""; then
			rm -f cookie.txt
			printf "\""
			continue;
		fi
		# Ensure Token does not contain '
		if echo "${TOKEN}" | grep -q "'"; then
			rm -f cookie.txt
			printf "'"
			continue;
		fi
		# Ensure Token does not contain '@'
		if echo "${TOKEN}" | grep -q "@"; then
			rm -f cookie.txt
			printf "@"
			continue;
		fi
		# Ensure Token does not contain '\'
		if echo "${TOKEN}" | grep -q "\\\\"; then
			rm -f cookie.txt
			printf "\\"
			continue;
		fi
		# Ensure Token does not contain '='
		if echo "${TOKEN}" | grep -q "="; then
			rm -f cookie.txt
			printf "="
			continue;
		fi
		# Ensure Token does not contain '`'
		if echo "${TOKEN}" | grep -q "\`"; then
			rm -f cookie.txt
			printf "\`"
			continue;
		fi
		# Ensure Token does not contain '+'
		if echo "${TOKEN}" | grep -q "+"; then
			rm -f cookie.txt
			printf "+"
			continue;
		fi
		# Ensure Token does not contain ';'
		if echo "${TOKEN}" | grep -q ";"; then
			rm -f cookie.txt
			printf ";"
			continue;
		fi
		# Ensure Token does not contain '%'
		if echo "${TOKEN}" | grep -q "%"; then
			rm -f cookie.txt
			printf "%%"
			continue;
		fi
		# Ensure Token does not contain ']'
		if echo "${TOKEN}" | grep -q "\\]"; then
			rm -f cookie.txt
			printf "\\]"
			continue;
		fi
		# Ensure Token does not contain '['
		if echo "${TOKEN}" | grep -q "\\["; then
			rm -f cookie.txt
			printf "\\["
			continue;
		fi
		# Ensure Token does not contain '$'
		if echo "${TOKEN}" | grep -q '\$'; then
			rm -f cookie.txt
			printf '\$'
			continue;
		fi

		# All set
		break
	done
	printf "\\r[OK]   Retrieve phpMyAdmin login page\\r\\n"


	printf "[TEST] Exract phpMyAdmin login token"
	if ! TOKEN="$( echo "${CONTENT}" \
		| grep -Eo "name=\"token\" value=\".+\"" \
		| head -1 \
		| grep -Eo "value=\".+\"" \
		| sed -e 's/^value="//g' -e 's/"$//g' )"; then
		printf "\\r[FAIL] Exract phpMyAdmin login token\\n"
		rm -f cookie.txt || true
		echo "${CONTENT}"
		exit 1
	fi
	printf "\\r[OK]   Exract phpMyAdmin login token: \"%s\"\\n" "${TOKEN}"

	printf "[TEST] Extract phpMyAdmin login session"
	if ! SESSION="$( echo "${CONTENT}" \
		| grep -Eo "name=\"set_session\" value=\"[A-Fa-f0-9]+\"" \
		| grep -Eo "value=\"[A-Fa-f0-9]+\"" \
		| sed -e 's/^value="//g' -e 's/"$//g' )"; then
		printf "\\r[OK]   Extract phpMyAdmin login session (not available)\\n"
		SESSION=""
	else
		printf "\\r[OK]   Extract phpMyAdmin login session: \"%s\"\\n" "${SESSION}"
	fi


	printf "[TEST] Submit phpMyAdmin POST login"
	if ! run "curl -sS --fail -c cookie.txt -b cookie.txt -d 'pma_username=root&pma_password=${MYSQL_ROOT_PASSWORD}&server=1&target=index.php&token=${TOKEN}&set_session=${SESSION}' 'http://localhost:${HOST_PORT_HTTPD}${URL}'" "${RETRIES}" "" "0"; then
		printf "\\r[FAIL] Submit phpMyAdmin POST login\\n"
		run "curl -sS -c cookie.txt -b cookie.txt 'http://localhost:${HOST_PORT_HTTPD}${URL}' || true"
		run "curl -sSI -c cookie.txt -b cookie.txt 'http://localhost:${HOST_PORT_HTTPD}${URL}' || true"
		rm -f cookie.txt || true
		exit 1
	else
		printf "\\r[OK]   Submit phpMyAdmin POST login\\n"
	fi
fi


###
### Check if login works
###
printf "[TEST] Evaluate successful phpMyAdmin login"
if [ "$( run "\
	curl -sS --fail -c cookie.txt -b cookie.txt 'http://localhost:${HOST_PORT_HTTPD}${URL}' \
	| tac \
	| tac \
	| grep -Ec '(Databases<.+SQL<.+Status<.+Users<.+Export<)|(\"User accounts\")'" \
	"${RETRIES}" "" "0" )" != "1" ]; then
	printf "\\r[FAIL] Evaluate successful phpMyAdmin login\\n"
	run "curl -sS -c cookie.txt -b cookie.txt 'http://localhost:${HOST_PORT_HTTPD}${URL}' || true"
	run "curl -sS -I -c cookie.txt -b cookie.txt 'http://localhost:${HOST_PORT_HTTPD}${URL}' || true"
	rm -f cookie.txt || true
	exit 1
else
	printf "\\r[OK]   Evaluate successful phpMyAdmin login\\n"
fi
rm -f cookie.txt || true
