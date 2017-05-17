#!/bin/sh -eu

################################################################################
#
# Arguments
#
################################################################################

###
### Validate arguments
###
if [ "${#}" != "5" ]; then
	echo "Error: Invalid number of arguments"
	exit 1
fi

if [ ! -d "${1}" ]; then
	echo "Error: Not a directory: ${1}"
	exit 1
fi

###
### Get arguments
###
DVL_PATH="$( echo "${1}"| sed 's/\/*$//' )" # remove last slash(es): /
DVL_SRV1="${2}" # Server 1
DVL_VER1="${3}" # Version 1
DVL_SRV2="${4}" # Server 2
DVL_VER2="${5}" # Version 2



################################################################################
#
# Bootstrap
#
################################################################################

###
### Source library
###
. "${DVL_PATH}/.tests/.lib.sh" "${DVL_PATH}"

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
set_host_port_mysql "3306"
set_host_port_pgsql "5432"



################################################################################
#
# Test
#
################################################################################

devilbox_start "${DVL_SRV1}" "${DVL_VER1}" "${DVL_SRV2}" "${DVL_VER2}"
devilbox_show
devilbox_test
devilbox_stop
