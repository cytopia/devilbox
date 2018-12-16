#!/usr/bin/env bash

set -e
set -u
set -o pipefail


#
# NOTE: Parsing curl to tac to circumnvent "failed writing body"
# https://stackoverflow.com/questions/16703647/why-curl-return-and-error-23-failed-writing-body
#


###
### Xdebug available
###

printf "[TEST] Xdebug available"
# 1st Try
if ! curl -sS localhost/info_php.php | tac | tac | grep -q 'xdebug\.remote_enable'; then
	# 2nd Try
	sleep 1
	if ! curl -sS localhost/info_php.php | tac | tac | grep -q 'xdebug\.remote_enable'; then
		# 3rd Try
		sleep 1
		if ! curl -sS localhost/info_php.php | tac | tac | grep -q 'xdebug\.remote_enable'; then
			printf "\r[FAIL] Xdebug available\n"
			curl -sS localhost/info_php.php | tac | tac | grep 'xdebug' || true
			exit 1
		else
			printf "\r[OK]   Xdebug available (3 rounds)\n"
		fi
	else
		printf "\r[OK]   Xdebug available (2 rounds)\n"
	fi
else
	printf "\r[OK]   Xdebug available (1 round)\n"
fi


###
### Xdebug default disabled
###

printf "[TEST] Xdebug default disabled"
# 1st Try
if ! curl -sS localhost/info_php.php | tac | tac | grep 'xdebug\.remote_enable' | grep -Eq 'Off.+Off'; then
	# 2nd Try
	sleep 1
	if ! curl -sS localhost/info_php.php | tac | tac | grep 'xdebug\.remote_enable' | grep -Eq 'Off.+Off'; then
		# 3rd Try
		sleep 1
		if ! curl -sS localhost/info_php.php | tac | tac | grep 'xdebug\.remote_enable' | grep -Eq 'Off.+Off'; then
			printf "\r[FAIL] Xdebug default disabled\n"
			curl -sS localhost/info_php.php | tac | tac | grep 'xdebug' || true
			exit 1
		else
			printf "\r[OK]   Xdebug default disabled (3 rounds)\n"
		fi
	else
		printf "\r[OK]   Xdebug default disabled (2 rounds)\n"
	fi
else
	printf "\r[OK]   Xdebug default disabled (1 round)\n"
fi
