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
### TEST PHP
###
####################################################################################################
####################################################################################################

################################################################################
#
# PHP 1/5
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mariadb-10.2"
_pysql="9.6"
_php="php-fpm-5.4"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "PHP (1/5): ${_php}"
debilbox_test
devilbox_stop

################################################################################
#
# PHP 2/5
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mariadb-10.2"
_pysql="9.6"
_php="php-fpm-5.5"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "PHP (2/5): ${_php}"
debilbox_test
devilbox_stop

################################################################################
#
# PHP 3/5
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mariadb-10.2"
_pysql="9.6"
_php="php-fpm-5.6"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "PHP (3/5): ${_php}"
debilbox_test
devilbox_stop

################################################################################
#
# PHP 4/5
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
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "PHP (4/5): ${_php}"
debilbox_test
devilbox_stop

################################################################################
#
# PHP 5/5
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mariadb-10.2"
_pysql="9.6"
_php="php-fpm-7.1"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "PHP (5/5): ${_php}"
debilbox_test
devilbox_stop
