#!/usr/bin/env bash

set -e
set -u
set -o pipefail


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
