#!/bin/sh -eu


DEVILBOX_PATH="$( echo "${1}"| sed 's/\/*$//' )" # remove last slash(es): /



################################################################################
#
#  H E L P E R
#
################################################################################

run() {
	_cmd="${1}"
	_debug="0"

	_red="\033[0;31m"
	_green="\033[0;32m"
	_reset="\033[0m"
	_user="$(whoami)"


	# If 2nd argument is set and enabled, allow debug command
	if [ "${#}" = "2" ]; then
		if [ "${2}" = "1" ]; then
			_debug="1"
		fi
	fi


	if [ "${_debug}" = "1" ]; then
		printf "${_red}%s \$ ${_green}${_cmd}${_reset}\n" "${_user}"
	fi
	sh -c "LANG=C LC_ALL=C ${_cmd}"
}

runsu() {
	_cmd="${1}"
	_debug="0"

	_red="\033[0;31m"
	_green="\033[0;32m"
	_reset="\033[0m"
	_user="$(whoami)"

	# If 2nd argument is set and enabled, allow debug command
	if [ "${#}" = "2" ]; then
		if [ "${2}" = "1" ]; then
			_debug="1"
		fi
	fi


	if [ "${_debug}" = "1" ]; then
		printf "${_red}%s \$ ${_green}sudo ${_cmd}${_reset}\n" "${_user}"
	fi

	sudo "LANG=C LC_ALL=C ${_cmd}"
}


wait_for() {
	_time="${1}"
	_debug="0"


	# Sleep with debug output
	if [ "${#}" = "2" ]; then
		if [ "${2}" = "1" ]; then
			printf "wait "
			for i in $(seq 1 "${_time}"); do
				sleep 1
				printf "."
			done
			printf "\n"
			return 0
		fi
	fi


	# Sleep silently
	sleep "${_time}"
}


################################################################################
#
#  G E T   D E F A U L T S
#
################################################################################

###
### Default enabled Docker Versions
###
get_default_version_httpd() {
	_default="$( grep -E '^HTTPD_SERVER=' "${DEVILBOX_PATH}/env-example" | sed 's/^.*=//g' )"
	echo "${_default}"
}
get_default_version_mysql() {
	_default="$( grep -E '^MYSQL_SERVER=' "${DEVILBOX_PATH}/env-example" | sed 's/^.*=//g' )"
	echo "${_default}"
}
get_default_version_postgres() {
	_default="$( grep -E '^POSTGRES_SERVER=' "${DEVILBOX_PATH}/env-example" | sed 's/^.*=//g' )"
	echo "${_default}"
}
get_default_version_php() {
	_default="$( grep -E '^PHP_SERVER=' "${DEVILBOX_PATH}/env-example" | sed 's/^.*=//g' )"
	echo "${_default}"
}

###
### Default enabled Host Ports
###
get_default_port_httpd() {
	_default="$( grep -E '^HOST_PORT_HTTPD=' "${DEVILBOX_PATH}/env-example" | sed 's/^.*=//g' )"
	echo "${_default}"
}
get_default_port_mysql() {
	_default="$( grep -E '^HOST_PORT_MYSQL=' "${DEVILBOX_PATH}/env-example" | sed 's/^.*=//g' )"
	echo "${_default}"
}
get_default_port_postgres() {
	_default="$( grep -E '^HOST_PORT_POSTGRES=' "${DEVILBOX_PATH}/env-example" | sed 's/^.*=//g' )"
	echo "${_default}"
}

###
### Default enabled Host Mounts
###
get_default_mount_httpd() {
	_default="$( grep -E '^HOST_PATH_TO_WWW_DOCROOTS=' "${DEVILBOX_PATH}/env-example" | sed 's/^.*=//g' )"
	_prefix="$( echo "${_default}" | cut -c-1 )"

	# Relative path?
	if [ "${_prefix}" = "." ]; then
		_default="$( echo "${_default}" | sed 's/^\.//g' )" # Remove leading dot: .
		_default="$( echo "${_default}" | sed 's/^\///' )" # Remove leading slash: /
		echo "${DEVILBOX_PATH}/${_default}"
	else
		echo "${_default}"
	fi
}
get_default_mount_mysql() {
	_default="$( grep -E '^HOST_PATH_TO_MYSQL_DATADIR=' "${DEVILBOX_PATH}/env-example" | sed 's/^.*=//g' )"
	_prefix="$( echo "${_default}" | cut -c-1 )"

	# Relative path?
	if [ "${_prefix}" = "." ]; then
		_default="$( echo "${_default}" | sed 's/^\.//g' )" # Remove leading dot: .
		_default="$( echo "${_default}" | sed 's/^\///' )" # Remove leading slash: /
		echo "${DEVILBOX_PATH}/${_default}"
	else
		echo "${_default}"
	fi
}
get_default_mount_postgres() {
	_default="$( grep -E '^HOST_PATH_TO_POSTGRES_DATADIR=' "${DEVILBOX_PATH}/env-example" | sed 's/^.*=//g' )"
	_prefix="$( echo "${_default}" | cut -c-1 )"

	# Relative path?
	if [ "${_prefix}" = "." ]; then
		_default="$( echo "${_default}" | sed 's/^\.//g' )" # Remove leading dot: .
		_default="$( echo "${_default}" | sed 's/^\///' )" # Remove leading slash: /
		echo "${DEVILBOX_PATH}/${_default}"
	else
		echo "${_default}"
	fi
}


################################################################################
#
#  G E T   E N A B L E D
#
################################################################################

###
### Default enabled Docker Versions
###
get_enabled_version_httpd() {
	_default="$( grep -E '^HTTPD_SERVER=' "${DEVILBOX_PATH}/.env" | sed 's/^.*=//g' )"
	echo "${_default}"
}
get_enabled_version_mysql() {
	_default="$( grep -E '^MYSQL_SERVER=' "${DEVILBOX_PATH}/.env" | sed 's/^.*=//g' )"
	echo "${_default}"
}
get_enabled_version_postgres() {
	_default="$( grep -E '^POSTGRES_SERVER=' "${DEVILBOX_PATH}/.env" | sed 's/^.*=//g' )"
	echo "${_default}"
}
get_enabled_version_php() {
	_default="$( grep -E '^PHP_SERVER=' "${DEVILBOX_PATH}/.env" | sed 's/^.*=//g' )"
	echo "${_default}"
}



################################################################################
#
#  G E T   A L L  D O C K E R   V E R S I O N S
#
################################################################################

###
### All Docker Versions
###
get_all_docker_httpd() {
	_all="$( grep -E '^#?HTTPD_SERVER=' "${DEVILBOX_PATH}/env-example" | sed 's/.*=//g' )"
	echo "${_all}"
}
get_all_docker_mysql() {
	_all="$( grep -E '^#?MYSQL_SERVER=' "${DEVILBOX_PATH}/env-example" | sed 's/.*=//g' )"
	echo "${_all}"
}
get_all_docker_postgres() {
	_all="$( grep -E '^#?POSTGRES_SERVER=' "${DEVILBOX_PATH}/env-example" | sed 's/.*=//g' )"
	echo "${_all}"
}
get_all_docker_php() {
	_all="$( grep -E '^#?PHP_SERVER=' "${DEVILBOX_PATH}/env-example" | sed 's/.*=//g' )"
	echo "${_all}"
}


################################################################################
#
#  S E T   /  R E S E T   F U N C T I O N S
#
################################################################################

###
### Recreate .env file from env-example
###
reset_env_file() {

	# Re-create .env file
	if [ -f "${DEVILBOX_PATH}/.env" ]; then
		rm -f "${DEVILBOX_PATH}/.env"
	fi
	cp "${DEVILBOX_PATH}/env-example" "${DEVILBOX_PATH}/.env"
}

###
### Comment out all docker versions
###
comment_all_dockers() {

	# Comment out all enabled docker versions
	run "sed -i'' \"s/^HTTPD_SERVER=/#HTTPD_SERVER=/g\" \"${DEVILBOX_PATH}/.env\""
	run "sed -i'' \"s/^MYSQL_SERVER=/#MYSQL_SERVER=/g\" \"${DEVILBOX_PATH}/.env\""
	run "sed -i'' \"s/^POSTGRES_SERVER=/#POSTGRES_SERVER=/g\" \"${DEVILBOX_PATH}/.env\""
	run "sed -i'' \"s/^PHP_SERVER=/#PHP_SERVER=/g\" \"${DEVILBOX_PATH}/.env\""
}

###
### Eenable desired docker version
###
enable_docker_httpd() {
	_docker_version="${1}"
	run "sed -i'' \"s/#HTTPD_SERVER=${_docker_version}/HTTPD_SERVER=${_docker_version}/g\" \"${DEVILBOX_PATH}/.env\""
}
enable_docker_mysql() {
	_docker_version="${1}"
	run "sed -i'' \"s/#MYSQL_SERVER=${_docker_version}/MYSQL_SERVER=${_docker_version}/g\" \"${DEVILBOX_PATH}/.env\""
}
enable_docker_postgres() {
	_docker_version="${1}"
	run "sed -i'' \"s/#POSTGRES_SERVER=${_docker_version}/POSTGRES_SERVER=${_docker_version}/g\" \"${DEVILBOX_PATH}/.env\""
}
enable_docker_php() {
	_docker_version="${1}"
	run "sed -i'' \"s/#PHP_SERVER=${_docker_version}/PHP_SERVER=${_docker_version}/g\" \"${DEVILBOX_PATH}/.env\""
}


set_host_port_httpd() {
	_port="${1}"
	run "sed -i'' \"s/^HOST_PORT_HTTPD=.*/HOST_PORT_HTTPD=${_port}/\" \"${DEVILBOX_PATH}/.env\""
}
set_host_port_mysql() {
	_port="${1}"
	run "sed -i'' \"s/^HOST_PORT_MYSQL=.*/HOST_PORT_MYSQL=${_port}/\" \"${DEVILBOX_PATH}/.env\""
}
set_host_port_pgsql() {
	_port="${1}"
	run "sed -i'' \"s/^HOST_PORT_POSTGRES=.*/HOST_PORT_POSTGRES=${_port}/\" \"${DEVILBOX_PATH}/.env\""
}


set_debug_enable() {
	run "sed -i'' \"s/^DEBUG_COMPOSE_ENTRYPOINT=.*/DEBUG_COMPOSE_ENTRYPOINT=1/\" \"${DEVILBOX_PATH}/.env\""
}


################################################################################
#
#   S T A R T / S T O P   T H E   D E V I L B O X
#
################################################################################

devilbox_start() {
	_new_httpd="$1"
	_new_mysql="$2"
	_new_pysql="$3"
	_new_php="$4"
	_new_head="$5"


	# Print Headline
	_blue="\033[0;34m"
	_reset="\033[0m"
	printf "${_blue}%s${_reset}\n" "################################################################################"
	printf "${_blue}%s${_reset}\n" "#"
	printf "${_blue}%s %s${_reset}\n" "#" "${_new_head}"
	printf "${_blue}%s${_reset}\n" "#"
	printf "${_blue}%s${_reset}\n" "################################################################################"

	# Adjust .env
	comment_all_dockers
	enable_docker_httpd "${_new_httpd}"
	enable_docker_mysql "${_new_mysql}"
	enable_docker_postgres "${_new_pysql}"
	enable_docker_php "${_new_php}"

	# Run
	docker-compose up -d

	# Wait for it to come up
	wait_for 90 1

	# Show log/info
	docker-compose logs
	docker-compose ps
}
devilbox_stop() {
	# Stop existing dockers
	cd "${DEVILBOX_PATH}" || exit 1
	docker-compose down > /dev/null 2>&1 || true
	docker-compose stop > /dev/null 2>&1 || true
	docker-compose kill > /dev/null 2>&1 || true
	docker-compose rm -f || true

	# Delete existing data dirs
	sudo rm -rf "$( get_default_mount_httpd )"
	sudo rm -rf "$( get_default_mount_mysql )"
	sudo rm -rf "$( get_default_mount_postgres )"
}


################################################################################
#
#   T E S T   T H E   D E V I L B O X
#
################################################################################


debilbox_test() {
	echo ".env settings"
	echo "------------------------------------------------------------"
	echo "HTTPD: $(get_enabled_version_httpd)"
	echo "PHP:   $(get_enabled_version_php)"
	echo "MySQL: $(get_enabled_version_mysql)"
	echo "PgSQL: $(get_enabled_version_postgres)"
	echo

	echo "http://localhost settings"
	echo "------------------------------------------------------------"
	curl -q localhost 2>/dev/null | grep -E '<h3>.*</h3>' | sed 's/.*<h3>//g' | sed 's/<\/h3>//g'
	echo


	count="$( curl -q localhost 2>/dev/null | grep -c OK )"
	echo "${count}"

	# Break on OK's less or more than 4
	if [ "${count}" != "3" ]; then
		curl localhost
		return 1
	fi
}

