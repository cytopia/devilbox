#!/usr/bin/env bash

set -e
set -u
set -o pipefail


DISABLED_VERSIONS_MONGO=("")

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
### Retrieve URL for current Adminer version.
###

printf "[TEST] Retrieve Adminer URL"
# 1st Try
if ! URL="$( curl -sS localhost/index.php | tac | tac | grep -Eo "/vendor/adminer-[.0-9]+-en\.php" )"; then
	# 2nd Try
	sleep 1
	if ! URL="$( curl -sS localhost/index.php | tac | tac | grep -Eo "/vendor/adminer-[.0-9]+-en\.php" )"; then
		# 3rd Try
		sleep 1
		if ! URL="$( curl -sS localhost/index.php | tac | tac | grep -Eo "/vendor/adminer-[.0-9]+-en\.php" )"; then
			printf "\r[FAILED] Retrieve Adminer URL\n"
			curl -sS localhost/index.php | tac | tac | grep -Eo "/vendor/adminer-[.0-9]+-en\.php" || true
			exit 1
		else
			printf "\r[OK]   Retrieve Adminer URL (3 rounds): ${URL}\n"
		fi
	else
		printf "\r[OK]   Retrieve Adminer URL (2 rounds): ${URL}\n"
	fi
else
	printf "\r[OK]   Retrieve Adminer URL (1 round): ${URL}\n"
fi


###
### Ensure given Adminer version works
###

printf "[TEST] Fetch ${URL}"
# 1st Try
if ! curl -sS localhost${URL} | tac | tac | grep -Eiq "Login.+Adminer"; then
	# 2nd Try
	sleep 1
	if ! curl -sS localhost${URL} | tac | tac | grep -Eiq "Login.+Adminer"; then
		# 3rd Try
		sleep 1
		if ! curl -sS localhost${URL} | tac | tac | grep -Eiq "Login.+Adminer"; then
			printf "\r[FAIL] Fetch ${URL}\n"
			curl -sS localhost${URL} || true
			curl -sSI localhost${URL} || true
			exit 1
		else
			printf "\r[OK]   Fetch ${URL} (3 rounds)\n"
		fi
	else
		printf "\r[OK]   Fetch ${URL} (2 rounds)\n"
	fi
else
	printf "\r[OK]   Fetch ${URL} (1 round)\n"
fi


###
### Test Adminer MySQL login
###

printf "[TEST] Adminer MySQL login"
# 1st Try
if ! curl -sS "localhost${URL}?server=mysql&username=root" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
	# 2nd Try
	sleep 1
	if ! curl -sS "localhost${URL}?server=mysql&username=root" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
		# 3rd Try
		sleep 1
		if ! curl -sS "localhost${URL}?server=mysql&username=root" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
			printf "\r[FAIL] Adminer MySQL login\n"
			curl -sS "localhost${URL}?server=mysql&username=root" || true
			curl -sSI "localhost${URL}?server=mysql&username=root" || true
			exit 1
		else
			printf "\r[OK]   Adminer MySQL login (3 rounds)\n"
		fi
	else
		printf "\r[OK]   Adminer MySQL login (2 rounds)\n"
	fi
else
	printf "\r[OK]   Adminer MySQL login (1 round)\n"
fi


###
### Test Adminer PostgreSQL login
###

printf "[TEST] Adminer PgSQL login"
# 1st Try
if ! curl -sS "localhost${URL}?pgsql=pgsql&username=postgres" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
	# 2nd Try
	sleep 1
	if ! curl -sS "localhost${URL}?pgsql=pgsql&username=postgres" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
		# 3rd Try
		sleep 1
		if ! curl -sS "localhost${URL}?pgsql=pgsql&username=postgres" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
			printf "\r[FAIL] Adminer PgSQL login\n"
			curl -sS "localhost${URL}?pgsql=pgsql&username=postgres" || true
			curl -sSI "localhost${URL}?pgsql=pgsql&username=postgres" || true
			exit 1
		else
			printf "\r[OK]   Adminer PgSQL login (3 rounds)\n"
		fi
	else
		printf "\r[OK]   Adminer PgSQL login (2 rounds)\n"
	fi
else
	printf "\r[OK]   Adminer PgSQL login (1 round)\n"
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
# 1st Try
if ! curl -sS "localhost${URL}?mongo=mongo&username=" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
	# 2nd Try
	sleep 1
	if ! curl -sS "localhost${URL}?mongo=mongo&username=" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
		# 3rd Try
		sleep 1
		if ! curl -sS "localhost${URL}?mongo=mongo&username=" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
			printf "\r[FAIL] Adminer Mongo login\n"
			curl -sS "localhost${URL}?mongo=mongo&username=" || true
			curl -sSI "localhost${URL}?mongo=mongo&username=" || true
			exit 1
		else
			printf "\r[OK]   Adminer Mongo login (3 rounds)\n"
		fi
	else
		printf "\r[OK]   Adminer Mongo login (2 rounds)\n"
	fi
else
	printf "\r[OK]   Adminer Mongo login (1 round)\n"
fi
