#!/usr/bin/env bash

set -e
set -u
set -o pipefail


DISABLED_VERSIONS=("7.4")


#
# NOTE: Parsing curl to tac to circumnvent "failed writing body"
# https://stackoverflow.com/questions/16703647/why-curl-return-and-error-23-failed-writing-body
#

###
### Fetch PHP version
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
### https://stackoverflow.com/a/15394738
###

if [[ ! ${DISABLED_VERSIONS[*]} =~ ${PHP_VERSION} ]]; then

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

	###
	### Xdebug autostart disabled
	###
	printf "[TEST] Xdebug autostart disabled"
	# 1st Try
	if ! curl -sS localhost/info_php.php | tac | tac | grep 'xdebug\.remote_autostart' | grep -Eq 'Off.+Off'; then
		# 2nd Try
		sleep 1
		if ! curl -sS localhost/info_php.php | tac | tac | grep 'xdebug\.remote_autostart' | grep -Eq 'Off.+Off'; then
			# 3rd Try
			sleep 1
			if ! curl -sS localhost/info_php.php | tac | tac | grep 'xdebug\.remote_autostart' | grep -Eq 'Off.+Off'; then
				printf "\r[FAIL] Xdebug autostart disabled\n"
				curl -sS localhost/info_php.php | tac | tac | grep 'xdebug' || true
				exit 1
			else
				printf "\r[OK]   Xdebug autostart disabled (3 rounds)\n"
			fi
		else
			printf "\r[OK]   Xdebug autostart disabled (2 rounds)\n"
		fi
	else
		printf "\r[OK]   Xdebug autostart disabled (1 round)\n"
	fi
fi
