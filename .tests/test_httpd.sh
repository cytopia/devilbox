#!/bin/sh -eu

if [ "${#}" != "1" ]; then
	echo "Error: Invalid number of arguments"
	exit 1
fi

if [ ! -d "${1}" ]; then
	echo "Error: Not a directory: ${1}"
	exit 1
fi

DEVILBOX_PATH="$( echo "${1}"| sed 's/\/*$//' )" # remove last slash(es): /

# Source files
. "${DEVILBOX_PATH}/.tests/.lib.sh" "${DEVILBOX_PATH}"
. "${DEVILBOX_PATH}/.tests/bootstrap.sh" "${DEVILBOX_PATH}"



####################################################################################################
####################################################################################################
###
### TEST HTTPD
###
####################################################################################################
####################################################################################################

################################################################################
#
# HTTPD 1/4
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
# HTTPD 2/4
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
# HTTPD 3/4
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
# HTTPD 4/4
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
