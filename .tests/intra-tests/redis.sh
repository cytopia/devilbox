#!/usr/bin/env bash

set -e
set -u
set -o pipefail

#
# NOTE: Parsing curl to tac to circumnvent "failed writing body"
# https://stackoverflow.com/questions/16703647/why-curl-return-and-error-23-failed-writing-body
#


printf "[TEST] devilbox-version key in Redis"
# 1st Try
if ! curl -sS localhost/db_redis.php | tac | tac | grep -q 'devilbox-version'; then
	sleep 1
	if ! curl -sS localhost/db_redis.php | tac | tac | grep -q 'devilbox-version'; then
		sleep 1
		if ! curl -sS localhost/db_redis.php | tac | tac | grep -q 'devilbox-version'; then
			printf "\r[FAIL] devilbox-version key in Redis\n"
			curl -sS localhost/db_redis.php || true
			exit 1
		else
			printf "\r[OK]   devilbox-version key in Redis (3 rounds)\n"
		fi
	else
		printf "\r[OK]   devilbox-version key in Redis (2 rounds)\n"
	fi
else
	printf "\r[OK]   devilbox-version key in Redis (1 round)\n"
fi
