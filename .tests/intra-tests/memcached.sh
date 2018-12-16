#!/usr/bin/env bash

set -e
set -u
set -o pipefail


printf "[TEST] devilbox-version key in Memcached"
# 1st Try
if ! curl -sS localhost/db_memcd.php | grep -q 'devilbox-version'; then
	sleep 1
	if ! curl -sS localhost/db_memcd.php | grep -q 'devilbox-version'; then
		sleep 1
		if ! curl -sS localhost/db_memcd.php | grep -q 'devilbox-version'; then
			printf "\r[FAIL] devilbox-version key in Memcached\n"
			curl -sS localhost/db_memcd.php || true
			exit 1
		else
			printf "\r[OK]   devilbox-version key in Memcached (3 rounds)\n"
		fi
	else
		printf "\r[OK]   devilbox-version key in Memcached (2 rounds)\n"
	fi
else
	printf "\r[OK]   devilbox-version key in Memcached (1 round)\n"
fi
