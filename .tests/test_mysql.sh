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
### TEST MYSQL
###
####################################################################################################
####################################################################################################

################################################################################
#
# MySQL 1/7
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mysql-5.5"
_pysql="9.6"
_php="php-fpm-7.0"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "MySQL (1/7): ${_mysql}"
debilbox_test
devilbox_stop

################################################################################
#
# MySQL 2/7
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mysql-5.6"
_pysql="9.6"
_php="php-fpm-7.0"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "MySQL (2/7): ${_mysql}"
debilbox_test
devilbox_stop

################################################################################
#
# MySQL 3/7
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mysql-5.7"
_pysql="9.6"
_php="php-fpm-7.0"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "MySQL (3/7): ${_mysql}"
debilbox_test
devilbox_stop

################################################################################
#
# MySQL 4/7
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mariadb-5.5"
_pysql="9.6"
_php="php-fpm-7.0"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "MySQL (4/7): ${_mysql}"
debilbox_test
devilbox_stop

################################################################################
#
# MySQL 5/7
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mariadb-10.0"
_pysql="9.6"
_php="php-fpm-7.0"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "MySQL (5/7): ${_mysql}"
debilbox_test
devilbox_stop

################################################################################
#
# MySQL 6/7
#
################################################################################

###
### Docker versions to use
###
_httpd="apache-2.4"
_mysql="mariadb-10.1"
_pysql="9.6"
_php="php-fpm-7.0"

###
### Go
###
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "MySQL (6/7): ${_mysql}"
debilbox_test
devilbox_stop

################################################################################
#
# MySQL 7/7
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
devilbox_start "${_httpd}" "${_mysql}" "${_pysql}" "${_php}" "MySQL (7/7): ${_mysql}"
debilbox_test
devilbox_stop
