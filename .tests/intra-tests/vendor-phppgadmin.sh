#!/usr/bin/env bash

set -e
set -u
set -o pipefail


# There's currently an Issue with PHP 7.3, Xdebug and phpMyAdmin. It just segfaults,
# so I will remove it from the checks.
DISABLED_VERSIONS=("")

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

printf "[TEST] Retrieve phpPgAdmin URL"
# 1st Try
if ! URL="$( curl -sS localhost/index.php | grep -Eo "/vendor/phppgadmin-[.0-9]+/" )"; then
	# 2nd Try
	sleep 1
	if ! URL="$( curl -sS localhost/index.php | grep -Eo "/vendor/phppgadmin-[.0-9]+/" )"; then
		# 3rd Try
		sleep 1
		if ! URL="$( curl -sS localhost/index.php | grep -Eo "/vendor/phppgadmin-[.0-9]+/" )"; then
			printf "\r[FAILED] Retrieve phpMyAdmin URL\n"
			curl -sS localhost/index.php | grep -Eo "/vendor/phppgadmin-[.0-9]+/" || true
			exit 1
		else
			printf "\r[OK]   Retrieve phpPgAdmin URL (3 rounds): ${URL}\n"
		fi
	else
		printf "\r[OK]   Retrieve phpPgAdmin URL (2 rounds): ${URL}\n"
	fi
else
	printf "\r[OK]   Retrieve phpPgAdmin URL (1 round): ${URL}\n"
fi


###
### Ensure given phpPgAdmin version works
###

printf "[TEST] Fetch ${URL}intro.php"
# 1st Try
if ! curl -sS localhost${URL}intro.php | tac | tac | grep -Eiq "welcome to phpPgAdmin"; then
	# 2nd Try
	sleep 1
	if ! curl -sS localhost${URL}intro.php | tac | tac | grep -Eiq "welcome to phpPgAdmin"; then
		# 3rd Try
		sleep 1
		if ! curl -sS localhost${URL}intro.php | tac | tac | grep -Eiq "welcome to phpPgAdmin"; then
			printf "\r[FAIL] Fetch ${URL}intro.php\n"
			curl -sS localhost/${URL}intro.php || true
			curl -sSI localhost/${URL}intro.php || true
			exit 1
		else
			printf "\r[OK]   Fetch ${URL}intro.php (3 rounds)\n"
		fi
	else
		printf "\r[OK]   Fetch ${URL}intro.php (2 rounds)\n"
	fi
else
	printf "\r[OK]   Fetch ${URL}intro.php (1 round)\n"
fi


###
### Evaluate successful phpPgAdmin login
###

printf "[TEST] Evaluate successful phpPgAdmin login"
# 1st Try
if [ "$(curl -sS "localhost${URL}redirect.php?subject=server&server=pgsql%3A5432%3Aallow&" | tac | tac | grep -Ec 'data">(Database|Owner|Collation|Tablespace)')" != "4" ]; then
	# 2nd Try
	sleep 1
	if [ "$(curl -sS "localhost${URL}redirect.php?subject=server&server=pgsql%3A5432%3Aallow&" | tac | tac | grep -Ec 'data">(Database|Owner|Collation|Tablespace)')" != "4" ]; then
		# 3rd Try
		sleep 1
		if [ "$(curl -sS "localhost${URL}redirect.php?subject=server&server=pgsql%3A5432%3Aallow&" | tac | tac | grep -Ec 'data">(Database|Owner|Collation|Tablespace)')" != "4" ]; then
			printf "\r[FAIL] Evaluate successful phpPgAdmin login\n"
			curl -sS "localhost/${URL}redirect.php?subject=server&server=pgsql%3A5432%3Aallow&" || true
			curl -sSI "localhost/${URL}redirect.php?subject=server&server=pgsql%3A5432%3Aallow&" || true
			exit 1
		else
			printf "\r[OK]   Evaluate successful phpPgAdmin login (3 rounds)\n"
		fi
	else
		printf "\r[OK]   Evaluate successful phpPgAdmin login (2 rounds)\n"
	fi
else
	printf "\r[OK]   Evaluate successful phpPgAdmin login (1 round)\n"
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
#			printf "\r[FAIL] Retrieve phpPgAdmin login page\n"
#			curl -sS localhost/${URL}servers.php || true
#			curl -sSI localhost/${URL}servers.php || true
#			rm -f cookie.txt
#			exit 1
#		else
#			TOKEN_URL="$( echo "${TOKEN_URL}" | sed 's/"//g' )"
#			TOKEN_URL="$( echo "${TOKEN_URL}" | sed 's/&amp;/\&/g' )"
#			printf "\r[OK]   Retrieve phpPgAdmin token page (3 rounds): ${TOKEN_URL}\n"
#		fi
#	else
#		TOKEN_URL="$( echo "${TOKEN_URL}" | sed 's/"//g' )"
#		TOKEN_URL="$( echo "${TOKEN_URL}" | sed 's/&amp;/\&/g' )"
#		printf "\r[OK]   Retrieve phpPgAdmin login token (2 rounds): ${TOKEN_URL}\n"
#	fi
#else
#	TOKEN_URL="$( echo "${TOKEN_URL}" | sed 's/"//g' )"
#	TOKEN_URL="$( echo "${TOKEN_URL}" | sed 's/&amp;/\&/g' )"
#	printf "\r[OK]   Retrieve phpPgAdmin token page (1 round): ${TOKEN_URL}\n"
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
#			printf "\r[FAIL] Retrieve phpPgAdmin login token\n"
#			curl -sS "${TOKEN_URL_URL}" || true
#			curl -sSI "${TOKEN_URL_URL}" || true
#			rm -f cookie.txt
#			exit 1
#		else
#			TOKEN="$( echo "${TOKEN}" | head -1 )"
#			printf "\r[OK]   Retrieve phpPgAdmin login token (3 rounds): ${TOKEN}\n"
#		fi
#	else
#		TOKEN="$( echo "${TOKEN}" | head -1 )"
#		printf "\r[OK]   Retrieve phpPgAdmin login token (2 rounds): ${TOKEN}\n"
#	fi
#else
#	TOKEN="$( echo "${TOKEN}" | head -1 )"
#	printf "\r[OK]   Retrieve phpPgAdmin login token (1 round): ${TOKEN}\n"
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
#			printf "\r[FAIL] Submit phpPgAdmin POST login\n"
#			curl -sS -c cookie.txt -b cookie.txt \
#				-d "subject=server&server=pgsql%3A5432%3Aallow&loginServer=pgsql%3A5432%3Aallow&loginUsername=postgres&${TOKEN}=&loginSubmit=Login" \
#				localhost${URL}redirect.php || true
#			curl -sSI -c cookie.txt -b cookie.txt \
#				-d "subject=server&server=pgsql%3A5432%3Aallow&loginServer=pgsql%3A5432%3Aallow&loginUsername=postgres&${TOKEN}=&loginSubmit=Login" \
#				localhost${URL}redirect.php || true
#			rm -f cookie.txt || true
#			exit 1
#		else
#			printf "\r[OK]   Submit phpPgAdmin POST login (3 rounds)\n"
#		fi
#	else
#		printf "\r[OK]   Submit phpPgAdmin POST login (2 rounds)\n"
#	fi
#else
#	printf "\r[OK]   Submit phpPgAdmin POST login (1 round)\n"
#fi
#
#rm -f cookie.txt || true
