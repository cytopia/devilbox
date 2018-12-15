#!/usr/bin/env bash

set -e
set -u
set -o pipefail


#
# NOTE: Parsing curl to tac to circumnvent "failed writing body"
# https://stackoverflow.com/questions/16703647/why-curl-return-and-error-23-failed-writing-body
#


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


###
### Ensure given phpMyAdmin version works
###

printf "[TEST] Fetch ${URL}"
# 1st Try
if ! curl -sS localhost${URL} | tac | tac | grep -Eiq "welcome to.+phpMyAdmin"; then
	# 2nd Try
	sleep 1
	if ! curl -sS localhost${URL} | tac | tac | grep -Eiq "welcome to.+phpMyAdmin"; then
		# 3rd Try
		sleep 1
		if ! curl -sS localhost${URL} | tac | tac | grep -Eiq "welcome to.+phpMyAdmin"; then
			printf "\r[FAIL] Fetch ${URL}\n"
			curl -sS localhost/${URL} || true
			curl -sSI localhost/${URL} || true
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
