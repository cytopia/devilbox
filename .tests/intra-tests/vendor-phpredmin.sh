#!/usr/bin/env bash

set -e
set -u
set -o pipefail


DISABLED_VERSIONS=("5.2" "5.3" "5.4")

#
# NOTE: Parsing curl to tac to circumnvent "failed writing body"
# https://stackoverflow.com/questions/16703647/why-curl-return-and-error-23-failed-writing-body
#


###
### Ensure PHPRedmin works
###

URL="/vendor/phpredmin/public/index.php"

printf "[TEST] Fetch ${URL}"
# 1st Try
if [ "$(curl -sS localhost${URL} | tac | tac | grep -Ec "Strings|Hashes|Lists|Sets|Sorted Sets")" != "5" ]; then
	# 2nd Try
	sleep 1
	if [ "$(curl -sS localhost${URL} | tac | tac | grep -Ec "Strings|Hashes|Lists|Sets|Sorted Sets")" != "5" ]; then
		# 3rd Try
		sleep 1
		if [ "$(curl -sS localhost${URL} | tac | tac | grep -Ec "Strings|Hashes|Lists|Sets|Sorted Sets")" != "5" ]; then
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

if [[ ! ${DISABLED_VERSIONS[*]} =~ ${PHP_VERSION} ]]; then

	###
	### Ensure PHPRedmin works in subdirectory
	###
	URL="/vendor/phpredmin/public/index.php/welcome/info/0/0"

	printf "[TEST] Fetch ${URL}"
	# 1st Try
	if [ "$(curl -sS localhost${URL} | tac | tac | grep -Ec "(Version:)|(Mode:)|(Role:)|(OS:)|(Uptime:)")" != "5" ]; then
		# 2nd Try
		sleep 1
		if [ "$(curl -sS localhost${URL} | tac | tac | grep -Ec "(Version:)|(Mode:)|(Role:)|(OS:)|(Uptime:)")" != "5" ]; then
			# 3rd Try
			sleep 1
			if [ "$(curl -sS localhost${URL} | tac | tac | grep -Ec "(Version:)|(Mode:)|(Role:)|(OS:)|(Uptime:)")" != "5" ]; then
				printf "\r[FAIL] Fetch ${URL}\n"
				curl -sS localhost/${URL} | tac | tac | grep -Ec "(Version:)|(Mode:)|(Role:)|(OS:)|(Uptime:)" || true
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

fi
