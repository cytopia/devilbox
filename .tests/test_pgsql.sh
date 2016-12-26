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
### TEST PGSQL
###
####################################################################################################
####################################################################################################

################################################################################
#
# PgSQL 1/5
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mariadb-10.2"
_pysql="9.2"
_php="php-fpm-7.0"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "PgSQL (1/5): ${_mysql}"
debilbox_test
devilbox_stop

################################################################################
#
# PgSQL 2/5
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mariadb-10.2"
_pysql="9.3"
_php="php-fpm-7.0"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "PgSQL (2/5): ${_mysql}"
debilbox_test
devilbox_stop

################################################################################
#
# PgSQL 3/5
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mariadb-10.2"
_pysql="9.4"
_php="php-fpm-7.0"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "PgSQL (3/5): ${_mysql}"
debilbox_test
devilbox_stop

################################################################################
#
# PgSQL 4/5
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mariadb-10.2"
_pysql="9.5"
_php="php-fpm-7.0"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "PgSQL (4/5): ${_mysql}"
debilbox_test
devilbox_stop

################################################################################
#
# PgSQL 5/5
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
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "PgSQL (5/5): ${_mysql}"
debilbox_test
devilbox_stop
