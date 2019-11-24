#!/usr/bin/env bash

set -e
set -u
set -o pipefail

SCRIPT_PATH="$( cd "$(dirname "$0")" && pwd -P )"
DVLBOX_PATH="$( cd "${SCRIPT_PATH}/../.." && pwd -P )"
# shellcheck disable=SC1090
. "${SCRIPT_PATH}/.lib.sh"


# -------------------------------------------------------------------------------------------------
# Pre-check
# -------------------------------------------------------------------------------------------------

if ! command -v curl >/dev/null 2>&1; then
	>&2 echo "Error 'curl' binary not found, but required."
	exit 1
fi

if ! command -v docker-compose >/dev/null 2>&1; then
	>&2 echo "Error 'docker-compose' binary not found, but required."
	exit 1
fi


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT
# -------------------------------------------------------------------------------------------------

###
### Get required env values
###
HOST_PORT_HTTPD="$( "${SCRIPT_PATH}/env-getvar.sh" "HOST_PORT_HTTPD" )"
MYSQL_ROOT_PASSWORD="$( "${SCRIPT_PATH}/env-getvar.sh" "MYSQL_ROOT_PASSWORD" )"


###
### Startup
###
run "docker-compose up -d" "1" "${DVLBOX_PATH}"


###
### Test for HTTP 200
###
printf "Waiting for HTTP 200 "
i=0
until curl -sS -I --fail -o /dev/null -w "%{http_code}" "http://localhost:${HOST_PORT_HTTPD}" 2>/dev/null | grep '200' >/dev/null; do
	printf "."

	i=$(( i + 1 ))
	if [ "${i}" -eq "60" ]; then
		printf "[FAIL]\\n\\n"
		echo "---- curl From host ----"
		curl -sS -v "http://localhost:${HOST_PORT_HTTPD}" || true
		curl -sS -I "http://localhost:${HOST_PORT_HTTPD}" || true
		curl -sS -I -o /dev/null -w "%{http_code}" "http://localhost:${HOST_PORT_HTTPD}" || true
		echo
		echo "---- curl From PHP container ----"
		run "docker-compose exec -T --user devilbox php curl -sS -v 'http://localhost/'" "1" "${DVLBOX_PATH}" || true
		run "docker-compose exec -T --user devilbox php curl -sS -I 'http://localhost/'" "1" "${DVLBOX_PATH}" || true
		run "docker-compose exec -T --user devilbox php curl -sS -I -o /dev/null -w '%{http_code}' 'http://localhost/'" "1" "${DVLBOX_PATH}" || true
		exit 1
	fi

	sleep 1
done
printf "[OK]\\n"


###
### Test Intranet start page
###
printf "Waiting for Intranet to be ready "
i=0
until curl -sS --fail "http://localhost:${HOST_PORT_HTTPD}" 2>/dev/null | grep 'dvlbox-ok' >/dev/null; do
	printf "."

	i=$(( i + 1 ))
	if [ "${i}" -eq "60" ]; then
		printf "[FAIL]\\n"
		curl -sS -v "http://localhost:${HOST_PORT_HTTPD}"
		curl -sS -I "http://localhost:${HOST_PORT_HTTPD}"
		exit 1
	fi

	sleep 1
done
printf "[OK]\\n"


###
### Wait for DB's to initialize
###
SEC=10
printf "Waiting %s sec for DB's to init " "${SEC}"
for i in $(seq 1 ${SEC}); do
	printf "."
	sleep 1
done
printf "[OK]\\n"


###
### Test MongoDB connection
###
printf "Waiting for MongoDB connection to be ready "
i=0
until cd "${DVLBOX_PATH}" && docker-compose exec -T php mongofiles --host=mongo list >/dev/null 2>&1; do
	printf "."

	i=$(( i + 1 ))
	if [ "${i}" -eq "60" ]; then
		printf "[FAIL]\\n"
		cd "${DVLBOX_PATH}" && docker-compose exec -T php mongofiles --host=mongo list
		exit 1
	fi

	sleep 1
done
printf "[OK]\\n"


###
### Test MySQL connection
###
printf "Waiting for MySQL connection to be ready ";
i=0
until cd "${DVLBOX_PATH}" && docker-compose exec -T php mysql --user=root --password="${MYSQL_ROOT_PASSWORD}" --host=mysql -e 'show databases;' 2>&1 | grep mysql >/dev/null; do
	printf "."

	i=$(( i + 1 ))
	if [ "${i}" -eq "60" ]; then
		printf "[FAIL]\\n"
		cd "${DVLBOX_PATH}" && docker-compose exec -T php mysql --user=root --password="${MYSQL_ROOT_PASSWORD}" --host=mysql -e 'show databases;'
		exit 1
	fi

	sleep 1
done
printf "[OK]\\n"


###
### Test PgSQL connection
###
printf "Waiting for PostgreSQL connection to be ready ";
i=0
until cd "${DVLBOX_PATH}" && docker-compose exec -T php pg_isready --host=pgsql >/dev/null 2>&1; do
	printf "."

	i=$(( i + 1 ))
	if [ "${i}" -eq "60" ]; then
		printf "[FAIL]\\n"
		cd "${DVLBOX_PATH}" && docker-compose exec -T php pg_isready --host=pgsql
		exit 1
	fi

	sleep 1
done
printf "[OK]\\n"
