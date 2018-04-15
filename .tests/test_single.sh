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
DEF_PHP="7.0"
DEF_HTTPD="nginx-stable"
DEF_MYSQL="mariadb-10.0"
DEF_PGSQL="9.6"



################################################################################
#
# Test
#
################################################################################

###
### Docker Host settings
###
print_h1 "Docker Host settings"

print_h2 "Listening services"
run "netstat -tulpn"

print_h2 "Docker version"
run "docker --version"
run "docker-compose --version"



###
### Configure
###
print_h1 "Configuration: ${DVL_SRV1}-${DVL_VER1} vs ${DVL_SRV2}-${DVL_VER2}"

print_h2 "Enabled settings in .env"
devilbox_configure "${DVL_SRV1}" "${DVL_VER1}" "${DVL_SRV2}" "${DVL_VER2}" "${DEF_PHP}" "${DEF_HTTPD}" "${DEF_MYSQL}" "${DEF_PGSQL}"
devilbox_configured_settings



###
### Download and run
###
print_h1 "Startup Devilbox"

print_h2 "Download"
devilbox_pull

print_h2 "Run"
devilbox_start

print_h2 "Actual settings from index.php"
devilbox_print_actual_settings



###
### Test
###
print_h1 "Testing"

print_h2 "docker-compose"
if ! devilbox_test_compose; then
	devilbox_print_errors "http://127.0.0.1/index.php"
	exit 1
fi

print_h2 "Testing 'dvlbox-ok': index.php"
if ! devilbox_test_url "http://127.0.0.1/index.php" "dvlbox-ok" "20"; then
	devilbox_print_errors "http://127.0.0.1/index.php"
	exit 1
fi

print_h2 "Testing 'dvlbox-err': index.php"
if ! devilbox_test_url "http://127.0.0.1/index.php" "dvlbox-err" "0"; then
	devilbox_print_errors "http://127.0.0.1/index.php"
	exit 1
fi



###
### Stop
###
print_h1 "Shutdown and exit"
devilbox_stop
