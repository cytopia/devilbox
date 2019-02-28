#!/bin/sh -eu
#
# This script will pull all Docker images that are currently
# bound to your devilbox git state.
#
# When updating the devilbox via git, do run this script once
# in order to download all images locally.
#

WHICH="all"
if [ "${#}" -eq "1" ]; then
	if [ "${1}" = "bind" ]; then
		WHICH="bind"
	elif [ "${1}" = "php" ]; then
		WHICH="php"
	elif [ "${1}" = "httpd" ]; then
		WHICH="httpd"
	elif [ "${1}" = "mysql" ]; then
		WHICH="mysql"
	elif [ "${1}" = "rest" ]; then
		WHICH="rest"
	else
		echo "Error: Unknown option"
		echo "Supported: php, httpd, mysql, rest"
		exit 1
	fi
fi


###
### Path of devilbox repository
###
CWD="$(cd -P -- "$(dirname -- "$0")" && pwd -P)"


###
### BIND
###
if [ "${WHICH}" = "all" ] || [ "${WHICH}" = "bind" ]; then
	TAG="$( grep '^[[:space:]]*image:[[:space:]]*cytopia/bind' "${CWD}/docker-compose.yml" | sed 's/^.*://g' )"
	docker pull cytopia/bind:${TAG}
fi


###
### PHP
###
if [ "${WHICH}" = "all" ] || [ "${WHICH}" = "php" ]; then
	SUFFIX="$( grep -E '^\s+image:\s+devilbox/php-fpm' "${CWD}/docker-compose.yml" | sed 's/.*}//g' )"
	IMAGES="$( grep -Eo '^#*PHP_SERVER=[.0-9]+' "${CWD}/env-example" | sed 's/.*=//g' )"
	echo "${IMAGES}" | while read version ; do
		docker pull devilbox/php-fpm:${version}${SUFFIX}
	done
fi


###
### HTTPD
###
if [ "${WHICH}" = "all" ] || [ "${WHICH}" = "httpd" ]; then
	SUFFIX="$( grep -E '^\s+image:\s+devilbox/\${HTTPD_SERVER' "${CWD}/docker-compose.yml" | sed 's/.*://g' )"
	IMAGES="$( grep -Eo '^#*HTTPD_SERVER=[-a-z]+[.0-9]*' "${CWD}/env-example" | sed 's/.*=//g' )"
	echo "${IMAGES}" | while read version ; do
		docker pull devilbox/${version}:${SUFFIX}
	done
fi


###
### MYSQL
###
if [ "${WHICH}" = "all" ] || [ "${WHICH}" = "mysql" ]; then
	IMAGES="$( grep -Eo '^#*MYSQL_SERVER=[-a-z]+[.0-9]*' "${CWD}/env-example" | sed 's/.*=//g' )"
	echo "${IMAGES}" | while read version ; do
		docker pull devilbox/mysql:${version}
	done
fi


###
### Rest of the fucking owl
###
### For all other non-base service, only download the currently enabled one
###
if [ "${WHICH}" = "all" ] || [ "${WHICH}" = "rest" ]; then
	if [ ! -f "${CWD}/.env" ]; then
		cp "${CWD}/env-example" "${CWD}/.env"
	fi
	docker-compose --project-directory "${CWD}" --file "${CWD}/docker-compose.yml" pull
fi
