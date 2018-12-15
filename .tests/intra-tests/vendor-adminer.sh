#!/usr/bin/env bash

set -e
set -u
set -o pipefail


#
# NOTE: Parsing curl to tac to circumnvent "failed writing body"
# https://stackoverflow.com/questions/16703647/why-curl-return-and-error-23-failed-writing-body
#


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
if ! curl -sS "localhost${URL}?server=127.0.0.1&username=root" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
	# 2nd Try
	sleep 1
	if ! curl -sS "localhost${URL}?server=127.0.0.1&username=root" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
		# 3rd Try
		sleep 1
		if ! curl -sS "localhost${URL}?server=127.0.0.1&username=root" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
			printf "\r[FAIL] Adminer MySQL login\n"
			curl -sS "localhost${URL}?server=127.0.0.1&username=root" || true
			curl -sSI "localhost${URL}?server=127.0.0.1&username=root" || true
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
if ! curl -sS "localhost${URL}?pgsql=127.0.0.1&username=postgres" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
	# 2nd Try
	sleep 1
	if ! curl -sS "localhost${URL}?pgsql=127.0.0.1&username=postgres" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
		# 3rd Try
		sleep 1
		if ! curl -sS "localhost${URL}?pgsql=127.0.0.1&username=postgres" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
			printf "\r[FAIL] Adminer PgSQL login\n"
			curl -sS "localhost${URL}?pgsql=127.0.0.1&username=postgres" || true
			curl -sSI "localhost${URL}?pgsql=127.0.0.1&username=postgres" || true
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
### Test Adminer MongoDB login
###

printf "[TEST] Adminer Mongo login"
# 1st Try
if ! curl -sS "localhost${URL}?mongo=127.0.0.1&username=" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
	# 2nd Try
	sleep 1
	if ! curl -sS "localhost${URL}?mongo=127.0.0.1&username=" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
		# 3rd Try
		sleep 1
		if ! curl -sS "localhost${URL}?mongo=127.0.0.1&username=" | tac | tac | grep -Eiq "Database.+Collation.+Tables"; then
			printf "\r[FAIL] Adminer Mongo login\n"
			curl -sS "localhost${URL}?mongo=127.0.0.1&username=" || true
			curl -sSI "localhost${URL}?mongo=127.0.0.1&username=" || true
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
