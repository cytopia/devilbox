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

###
### Default values for container
###
DEF_PHP="php-fpm-7.0"
DEF_HTTPD="nginx-stable"
DEF_MYSQL="mariadb-10.0"
DEF_PGSQL="9.6"



################################################################################
#
# Test
#
################################################################################

# Print Headline
print_h1 "Configuration: ${DVL_SRV1}-${DVL_VER1} vs ${DVL_SRV2}-${DVL_VER2}"


###
### Configure
###
devilbox_configure "${DVL_SRV1}" "${DVL_VER1}" "${DVL_SRV2}" "${DVL_VER2}" "${DEF_PHP}" "${DEF_HTTPD}" "${DEF_MYSQL}" "${DEF_PGSQL}"


###
### Download and run
###
devilbox_pull
devilbox_start
devilbox_show


###
### Test
###
print_h1 "Testing"

print_h2 "docker-compose"
if devilbox_test_compose; then
	echo "[OK]: all container running"
else
	devilbox_print_errors "http://localhost/index.php"
	exit 1
fi

print_h2 "Testing 'dvlbox-ok: index.php"
if devilbox_test_url "http://localhost/index.php" "dvlbox-ok" "20"; then
	echo "[OK]: All 'dvlbox-ok' found"
else
	echo "[ERR]: Not all 'dvlbox-ok' found"
	devilbox_print_errors "http://localhost/index.php"
	exit 1
fi

print_h2 "Testing 'dvlbox-err: index.php"
if devilbox_test_url "http://localhost/index.php" "dvlbox-err" "0"; then
	echo "[OK]: No 'dvlbox-err' found"
else
	echo "[ERR]: 'dvlbox-err' found"
	devilbox_print_errors "http://localhost/index.php"
	exit 1
fi


###
### Stop
###
devilbox_stop
