#!/bin/sh -eu

if [ "${#}" != "1" ]; then
	echo "Error: Invalid number of arguments"
	exit 1
fi

if [ ! -d "${1}" ]; then
	echo "Error: Not a directory: ${1}"
	exit 1
fi

###
### Get devilbox path and source libs file
###
DEVILBOX_PATH="$( echo "${1}"| sed 's/\/*$//' )" # remove last slash(es): /
. "${DEVILBOX_PATH}/.tests/.lib.sh" "${DEVILBOX_PATH}"

################################################################################
#
#  B O O T S T R A P
#
################################################################################

###
### Reset .env file
###
reset_env_file

###
### Alter host ports
###
set_host_port_httpd "80"
set_host_port_mysql "33060"
set_host_port_pgsql "54320"



################################################################################
#
#  T E S T   (HTTPD 01)
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.2"
_mysql="mariadb-10.2"
_pysql="9.6"
_php="php-fpm-7.0"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "HTTPD (1/4): ${_httpd}"
debilbox_test
devilbox_stop



################################################################################
#
#  T E S T   (HTTPD 02)
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mariadb-10.2"
_pysql="9.6"
_php="php-fpm-7.0"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "HTTPD (2/4): ${_httpd}"
debilbox_test
devilbox_stop



################################################################################
#
#  T E S T   (HTTPD 03)
#
################################################################################

###
### Docker versions to use
###
_httpd="nginx-stable"
_mysql="mariadb-10.2"
_pysql="9.6"
_php="php-fpm-7.0"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "HTTPD (3/4): ${_httpd}"
debilbox_test
devilbox_stop



################################################################################
#
#  T E S T   (HTTPD 04)
#
################################################################################

###
### Docker versions to use
###
_httpd="nginx-mainline"
_mysql="mariadb-10.2"
_pysql="9.6"
_php="php-fpm-7.0"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "HTTPD (4/4): ${_httpd}"
debilbox_test
devilbox_stop