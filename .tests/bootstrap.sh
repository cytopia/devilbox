#!/bin/sh -eu

if [ "${#}" != "1" ]; then
	exit 1
fi

if [ ! -d "${1}" ]; then
	exit 1
fi

DEVILBOX_PATH="${1}"

# Source files
. "${DEVILBOX_PATH}/.tests/.lib.sh" "${DEVILBOX_PATH}"


###
### Reset .env file
###
reset_env_file

###
### Enable debug mode
###
set_debug_enable

###
### Alter host ports
###
set_host_port_httpd "80"
set_host_port_mysql "33060"
set_host_port_pgsql "54320"
