#!/usr/bin/env bash

set -e
set -u
set -o pipefail


printf "[TEST] devilbox-version key in Redis"
# 1st Try
if ! curl -sS localhost/db_redis.php | grep -q 'devilbox-version'; then
	sleep 1
	if ! curl -sS localhost/db_redis.php | grep -q 'devilbox-version'; then
		sleep 1
		if ! curl -sS localhost/db_redis.php | grep -q 'devilbox-version'; then
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
