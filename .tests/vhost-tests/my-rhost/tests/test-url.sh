#!/usr/bin/env bash

set -e
set -u

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
COMPOSEPATH="${SCRIPTPATH}/../../../../"
cd "${COMPOSEPATH}"


printf "[TEST] http://my-rhost.loc"
# 1st round
if ! docker-compose exec --user devilbox php curl http://my-rhost.loc | grep "Hello World"; then
	sleep 1;
	# 2nd round
	if ! docker-compose exec --user devilbox php curl http://my-rhost.loc | grep "Hello World"; then
		sleep 1;
		# 3rd round
		if ! docker-compose exec --user devilbox php curl http://my-rhost.loc | grep "Hello World"; then
			printf "\r[FAIL] http://my-rhost.loc\n"
			cd "${COMPOSEPATH}" && docker-compose exec --user devilbox php curl http://my-rhost.loc
			exit 1
		else
			printf "\r[OK]   http://my-rhost.loc (3 rounds)\n"
		fi
	else
		printf "\r[OK]   http://my-rhost.loc (2 rounds)\n"
	fi
else
	printf "\r[OK]   http://my-rhost.loc (1 round)\n"
fi


printf "[TEST] https://my-rhost.loc"
# 1st round
if ! docker-compose exec --user devilbox php curl https://my-rhost.loc | grep "Hello World"; then
	sleep 1;
	# 2nd round
	if ! docker-compose exec --user devilbox php curl https://my-rhost.loc | grep "Hello World"; then
		sleep 1;
		# 3rd round
		if ! docker-compose exec --user devilbox php curl https://my-rhost.loc | grep "Hello World"; then
			printf "\r[FAIL] https://my-rhost.loc\n"
			cd "${COMPOSEPATH}" && docker-compose exec --user devilbox php curl https://my-rhost.loc
			exit 1
		else
			printf "\r[OK]   https://my-rhost.loc (3 rounds)\n"
		fi
	else
		printf "\r[OK]   https://my-rhost.loc (2 rounds)\n"
	fi
else
	printf "\r[OK]   https://my-rhost.loc (1 round)\n"
fi
