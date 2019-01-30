#!/usr/bin/env bash

set -e
set -u
set -o pipefail


# There's currently an Issue with PHP 7.3, Xdebug and phpMyAdmin. It just segfaults,
# so I will remove it from the checks.
DISABLED_VERSIONS=("7.3")

#
# NOTE: Parsing curl to tac to circumnvent "failed writing body"
# https://stackoverflow.com/questions/16703647/why-curl-return-and-error-23-failed-writing-body
#


###
### Get current PHP version
###

printf "[TEST] Get PHP version"
# 1st Try
if ! PHP_VERSION="$( curl -sS localhost/index.php | tac | tac | grep -Eo 'PHP.*?\([.0-9]+' | grep -Eo '\([.0-9]+' | grep -Eo '[0-9]+\.[0-9]+' )"; then
	# 2nd Try
	sleep 1
	if ! PHP_VERSION="$( curl -sS localhost/index.php | tac | tac | grep -Eo 'PHP.*?\([.0-9]+' | grep -Eo '\([.0-9]+' | grep -Eo '[0-9]+\.[0-9]+' )"; then
		# 3rd Try
		sleep 1
		if ! PHP_VERSION="$( curl -sS localhost/index.php | tac | tac | grep -Eo 'PHP.*?\([.0-9]+' | grep -Eo '\([.0-9]+' | grep -Eo '[0-9]+\.[0-9]+' )"; then
			printf "\r[FAIL] Get PHP version\n"
			curl -sS localhost/index.php | tac | tac | grep -Eo 'PHP.*?\([.0-9]+' || true
			exit 1
		else
			printf "\r[OK]   Get PHP version (3 rounds): %s\n" "${PHP_VERSION}"
		fi
	else
		printf "\r[OK]   Get PHP version (2 rounds): %s\n" "${PHP_VERSION}"
	fi
else
	printf "\r[OK]   Get PHP version (1 round): %s\n" "${PHP_VERSION}"
fi


###
### Ensure only to check against desired versions
###

if [[ ${DISABLED_VERSIONS[*]} =~ ${PHP_VERSION} ]]; then
	printf "[SKIP] Skipping all checks for PHP ${PHP_VERSION}\n"
	exit 0
fi


###
### Retrieve URL for current PHP version.
### Older PHP versions are presented a link with a different version due to compatibility.
###

printf "[TEST] Retrieve phpMyAdmin URL"
# 1st Try
if ! URL="$( curl -sS localhost/index.php | grep -Eo "/vendor/phpmyadmin-[.0-9]+/index\.php" )"; then
	# 2nd Try
	sleep 1
	if ! URL="$( curl -sS localhost/index.php | grep -Eo "/vendor/phpmyadmin-[.0-9]+/index\.php" )"; then
		# 3rd Try
		sleep 1
		if ! URL="$( curl -sS localhost/index.php | grep -Eo "/vendor/phpmyadmin-[.0-9]+/index\.php" )"; then
			printf "\r[FAILED] Retrieve phpMyAdmin URL\n"
			curl -sS localhost/index.php | grep -Eo "/vendor/phpmyadmin-[.0-9]+/index\.php" || true
			exit 1
		else
			printf "\r[OK]   Retrieve phpMyAdmin URL (3 rounds): ${URL}\n"
		fi
	else
		printf "\r[OK]   Retrieve phpMyAdmin URL (2 rounds): ${URL}\n"
	fi
else
	printf "\r[OK]   Retrieve phpMyAdmin URL (1 round): ${URL}\n"
fi


####
#### Ensure given phpMyAdmin version works
####
#
#printf "[TEST] Fetch ${URL}"
## 1st Try
#if ! curl -sS localhost${URL} | tac | tac | grep -Eiq "welcome to.+phpMyAdmin"; then
#	# 2nd Try
#	sleep 1
#	if ! curl -sS localhost${URL} | tac | tac | grep -Eiq "welcome to.+phpMyAdmin"; then
#		# 3rd Try
#		sleep 1
#		if ! curl -sS localhost${URL} | tac | tac | grep -Eiq "welcome to.+phpMyAdmin"; then
#			printf "\r[FAIL] Fetch ${URL}\n"
#			curl -sS localhost/${URL} || true
#			curl -sSI localhost/${URL} || true
#			exit 1
#		else
#			printf "\r[OK]   Fetch ${URL} (3 rounds)\n"
#		fi
#	else
#		printf "\r[OK]   Fetch ${URL} (2 rounds)\n"
#	fi
#else
#	printf "\r[OK]   Fetch ${URL} (1 round)\n"
#fi
#
#
####
#### Login
####
#
#TOKEN=
#printf "[TEST] Retrieve phpMyAdmin login page"
#while true; do
#	# Try again until it succeeds
#	if ! CONTENT="$( curl -sS -c cookie.txt localhost${URL} )"; then
#		rm -f cookie.txt
#		printf "e"
#		continue;
#	fi
#	# Extract the token
#	if ! TOKEN="$( echo "${CONTENT}" \
#		| grep -Eo "name=\"token\" value=\".+\"" \
#		| head -1 \
#		| grep -Eo "value=\".+\"" \
#		| sed -e 's/^value="//g' -e 's/"$//g' )"; then
#		rm -f cookie.txt
#		printf "w"
#		continue;
#	fi
#	# Ensure Token does not contain '!'
#	if echo "${TOKEN}" | grep -q "!"; then
#		rm -f cookie.txt
#		printf "!"
#		continue;
#	fi
#	# Ensure Token does not contain '&'
#	if echo "${TOKEN}" | grep -q "&"; then
#		rm -f cookie.txt
#		printf "&"
#		continue;
#	fi
#	# Ensure Token does not contain '?'
#	if echo "${TOKEN}" | grep -q "?"; then
#		rm -f cookie.txt
#		printf "?"
#		continue;
#	fi
#	# Ensure Token does not contain '"'
#	if echo "${TOKEN}" | grep -q "\""; then
#		rm -f cookie.txt
#		printf "\""
#		continue;
#	fi
#	# Ensure Token does not contain '
#	if echo "${TOKEN}" | grep -q "'"; then
#		rm -f cookie.txt
#		printf "'"
#		continue;
#	fi
#	# Ensure Token does not contain '@'
#	if echo "${TOKEN}" | grep -q "@"; then
#		rm -f cookie.txt
#		printf "@"
#		continue;
#	fi
#	# Ensure Token does not contain '\'
#	if echo "${TOKEN}" | grep -q "\\\\"; then
#		rm -f cookie.txt
#		printf "\\"
#		continue;
#	fi
#	# Ensure Token does not contain '='
#	if echo "${TOKEN}" | grep -q "="; then
#		rm -f cookie.txt
#		printf "="
#		continue;
#	fi
#	# Ensure Token does not contain '`'
#	if echo "${TOKEN}" | grep -q "\`"; then
#		rm -f cookie.txt
#		printf "\`"
#		continue;
#	fi
#	# Ensure Token does not contain '+'
#	if echo "${TOKEN}" | grep -q "+"; then
#		rm -f cookie.txt
#		printf "+"
#		continue;
#	fi
#	# Ensure Token does not contain ';'
#	if echo "${TOKEN}" | grep -q ";"; then
#		rm -f cookie.txt
#		printf ";"
#		continue;
#	fi
#	# Ensure Token does not contain '%'
#	if echo "${TOKEN}" | grep -q "%"; then
#		rm -f cookie.txt
#		printf "%%"
#		continue;
#	fi
#	# Ensure Token does not contain ']'
#	if echo "${TOKEN}" | grep -q "\]"; then
#		rm -f cookie.txt
#		printf "\]"
#		continue;
#	fi
#	# Ensure Token does not contain '['
#	if echo "${TOKEN}" | grep -q "\["; then
#		rm -f cookie.txt
#		printf "\["
#		continue;
#	fi
#	# Ensure Token does not contain '$'
#	if echo "${TOKEN}" | grep -q '\$'; then
#		rm -f cookie.txt
#		printf '\$'
#		continue;
#	fi
#
#	# All set
#	break
#done
#printf "\r[OK]   Retrieve phpMyAdmin login page\r\n"
#
#
#printf "[TEST] Exract phpMyAdmin login token"
#if ! TOKEN="$( echo "${CONTENT}" \
#	| grep -Eo "name=\"token\" value=\".+\"" \
#	| head -1 \
#	| grep -Eo "value=\".+\"" \
#	| sed -e 's/^value="//g' -e 's/"$//g' )"; then
#	printf "\r[FAIL] Exract phpMyAdmin login token\n"
#	rm -f cookie.txt || true
#	echo "${CONTENT}"
#	exit 1
#fi
#printf "\r[OK]   Exract phpMyAdmin login token: \"%s\"\n" "${TOKEN}"
#
#printf "[TEST] Extract phpMyAdmin login session"
#if ! SESSION="$( echo "${CONTENT}" \
#	| grep -Eo "name=\"set_session\" value=\"[A-Fa-f0-9]+\"" \
#	| grep -Eo "value=\"[A-Fa-f0-9]+\"" \
#	| sed -e 's/^value="//g' -e 's/"$//g' )"; then
#	printf "\r[OK]   Extract phpMyAdmin login session (not available)\n"
#	SESSION=""
#else
#	printf "\r[OK]   Extract phpMyAdmin login session: \"%s\"\n" "${SESSION}"
#fi
#
#
#printf "[TEST] Submit phpMyAdmin POST login"
## 1st Try
#if ! curl -sS -c cookie.txt -b cookie.txt -d "pma_username=root&pma_password=&server=1&target=index.php&token=${TOKEN}&set_session=${SESSION}" localhost${URL}; then
#	# 2nd Try
#	sleep 1
#	if ! curl -sS -c cookie.txt -b cookie.txt -d "pma_username=root&pma_password=&server=1&target=index.php&token=${TOKEN}&set_session=${SESSION}" localhost${URL}; then
#		# 3rd Try
#		sleep 1
#		if ! curl -sS -c cookie.txt -b cookie.txt -d "pma_username=root&pma_password=&server=1&target=index.php&token=${TOKEN}&set_session=${SESSION}" localhost${URL}; then
#			printf "\r[FAIL] Submit phpMyAdmin POST login\n"
#			curl -sS -c cookie.txt -b cookie.txt localhost/${URL} || true
#			curl -sSI -c cookie.txt -b cookie.txt localhost/${URL} || true
#			rm -f cookie.txt || true
#			exit 1
#		else
#			printf "\r[OK]   Submit phpMyAdmin POST login (3 rounds)\n"
#		fi
#	else
#		printf "\r[OK]   Submit phpMyAdmin POST login (2 rounds)\n"
#	fi
#else
#	printf "\r[OK]   Submit phpMyAdmin POST login (1 round)\n"
#fi

printf "[TEST] Evaluate successful phpMyAdmin login"
# 1st Try
if [ "$( curl -sS -c cookie.txt -b cookie.txt localhost${URL} | tac | tac  | grep -Ec "(Databases<.+SQL<.+Status<.+Users<.+Export<)|(\"User accounts\")" )" != "1" ]; then
	# 2nd Try
	sleep 1
	if [ "$( curl -sS -c cookie.txt -b cookie.txt localhost${URL} | tac | tac | grep -Ec "(Databases<.+SQL<.+Status<.+Users<.+Export<|(\"User accounts\")" )" != "1" ]; then
		# 3rd Try
		sleep 1
		if [ "$( curl -sS -c cookie.txt -b cookie.txt localhost${URL} | tac | tac | grep -Ec "(Databases<.+SQL<.+Status<.+Users<.+Export<|(\"User accounts\")" )" != "1" ]; then
			printf "\r[FAIL] Evaluate successful phpMyAdmin login\n"
			curl -sS -c cookie.txt -b cookie.txt localhost/${URL} || true
			curl -sSI -c cookie.txt -b cookie.txt localhost/${URL} || true
			rm -f cookie.txt || true
			exit 1
		else
			printf "\r[OK]   Evaluate successful phpMyAdmin login (3 rounds)\n"
		fi
	else
		printf "\r[OK]   Evaluate successful phpMyAdmin login (2 rounds)\n"
	fi
else
	printf "\r[OK]   Evaluate successful phpMyAdmin login (1 round)\n"
fi
rm -f cookie.txt || true


###
### Configuration File
###
SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
DVLBOXPATH="${SCRIPTPATH}/../../"
CONFIGPATH="${DVLBOXPATH}/.devilbox/www/htdocs${URL%index\.php}config.inc.php"

printf "[TEST] config.inc.php exists"
if [ ! -f "${CONFIGPATH}" ]; then
	printf "\r[FAIL] config.inc.php exists: no\n"
	exit 1
else
	printf "\r[OK]   config.inc.php exists: yes\n"
fi


# error_reporting(-1);
printf "[TEST] config.inc.php check: error_reporting(-1);"
if ! grep -q "^error_reporting(-1);" "${CONFIGPATH}"; then
	printf "\r[FAIL] config.inc.php check: error_reporting(-1);\n"
	cat "${CONFIGPATH}"
	exit 1
else
	printf "\r[OK]   config.inc.php check: error_reporting(-1);\n"
fi

# $cfg['TempDir'] = '/tmp';
printf "[TEST] config.inc.php check: \$cfg['TempDir'] = '/tmp';"
if ! grep -q "^\$cfg\['TempDir'\]\s*=\s*'/tmp';" "${CONFIGPATH}"; then
	printf "\r[FAIL] config.inc.php check: \$cfg['TempDir'] = '/tmp';\n"
	cat "${CONFIGPATH}"
	exit 1
else
	printf "\r[OK]   config.inc.php check: \$cfg['TempDir'] = '/tmp';\n"
fi

# $cfg['CheckConfigurationPermissions'] = false;
printf "[TEST] config.inc.php check: \$cfg['CheckConfigurationPermissions'] = false;"
if ! grep -q "^\$cfg\['CheckConfigurationPermissions'\]\s*=\s*false;" "${CONFIGPATH}"; then
	printf "\r[FAIL] config.inc.php check: \$cfg['CheckConfigurationPermissions'] = false;\n"
	cat "${CONFIGPATH}"
	exit 1
else
	printf "\r[OK]   config.inc.php check: \$cfg['CheckConfigurationPermissions'] = false;\n"
fi

# $cfg['blowfish_secret'] = '...'
printf "[TEST] config.inc.php check: \$cfg['blowfish_secret'] = '...';"
if ! grep -qE '^\$cfg\['"'"'blowfish_secret'"'"'\]\s*=\s*'"'"'.{32,}'"'"';' "${CONFIGPATH}"; then
	printf "\r[FAIL] config.inc.php check: \$cfg['blowfish_secret'] = '...';\n"
	cat "${CONFIGPATH}"
	exit 1
else
	printf "\r[OK]   config.inc.php check: \$cfg['blowfish_secret'] = '...';\n"
fi

# $cfg['SendErrorReports'] = 'never';
printf "[TEST] config.inc.php check: \$cfg['SendErrorReports'] = 'never';"
if ! grep -q "^\$cfg\['SendErrorReports'\]\s*=\s*'never';" "${CONFIGPATH}"; then
	printf "\r[FAIL] config.inc.php check: \$cfg['SendErrorReports'] = 'never';\n"
	cat "${CONFIGPATH}"
	exit 1
else
	printf "\r[OK]   config.inc.php check: \$cfg['SendErrorReports'] = 'never';\n"
fi

# $cfg['Servers'][$i]['host'] = 'mysql';
printf "[TEST] config.inc.php check: \$cfg['Servers'][\$i]['host'] = 'mysql';"
if ! grep -q "^\$cfg\['Servers'\]\[\$i\]\['host'\]\s*=\s*'mysql';" "${CONFIGPATH}"; then
	printf "\r[FAIL] config.inc.php check: \$cfg['Servers'][\$i]['host'] = 'mysql';\n"
	cat "${CONFIGPATH}"
	exit 1
else
	printf "\r[OK]   config.inc.php check: \$cfg['Servers'][\$i]['host'] = 'mysql';\n"
fi

# $cfg['Servers'][$i]['connect_type'] = 'tcp';
printf "[TEST] config.inc.php check: \$cfg['Servers'][\$i]['connect_type'] = 'tcp';"
if ! grep -q "^\$cfg\['Servers'\]\[\$i\]\['connect_type'\]\s*=\s*'tcp';" "${CONFIGPATH}"; then
	printf "\r[FAIL] config.inc.php check: \$cfg['Servers'][\$i]['connect_type'] = 'tcp';\n"
	cat "${CONFIGPATH}"
	exit 1
else
	printf "\r[OK]   config.inc.php check: \$cfg['Servers'][\$i]['connect_type'] = 'tcp';\n"
fi

# $cfg['Servers'][$i]['compress'] = false;
printf "[TEST] config.inc.php check: \$cfg['Servers'][\$i]['compress'] = false;"
if ! grep -q "^\$cfg\['Servers'\]\[\$i\]\['compress'\]\s*=\s*false;" "${CONFIGPATH}"; then
	printf "\r[FAIL] config.inc.php check: \$cfg['Servers'][\$i]['compress'] = false;\n"
	cat "${CONFIGPATH}"
	exit 1
else
	printf "\r[OK]   config.inc.php check: \$cfg['Servers'][\$i]['compress'] = false;\n"
fi

# $cfg['Servers'][$i]['AllowNoPassword'] = true;
printf "[TEST] config.inc.php check: \$cfg['Servers'][\$i]['compress'] = false;"
if ! grep -q "^\$cfg\['Servers'\]\[\$i\]\['AllowNoPassword'\]\s*=\s*true;" "${CONFIGPATH}"; then
	printf "\r[FAIL] config.inc.php check: \$cfg['Servers'][\$i]['AllowNoPassword'] = true;\n"
	cat "${CONFIGPATH}"
	exit 1
else
	printf "\r[OK]   config.inc.php check: \$cfg['Servers'][\$i]['AllowNoPassword'] = true;\n"
fi
