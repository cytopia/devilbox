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

	eval "sudo LANG=C LC_ALL=C ${_cmd}"
}

print_h1() {
	_headline="${1}"

	_blue="\033[0;34m"
	_reset="\033[0m"
	printf "${_blue}%s${_reset}\n" "################################################################################"
	printf "${_blue}%s${_reset}\n" "#"
	printf "${_blue}%s %s${_reset}\n" "#" "${_headline}"
	printf "${_blue}%s${_reset}\n" "#"
	printf "${_blue}%s${_reset}\n" "################################################################################"
}

print_h2() {
	_headline="${1}"

	_blue="\033[0;34m"
	_reset="\033[0m"
	printf "${_blue}%s${_reset}\n" "############################################################"
	printf "${_blue}%s %s${_reset}\n" "#" "${_headline}"
	printf "${_blue}%s${_reset}\n" "############################################################"
}

wait_for() {
	_time="${1}"
	_debug="0"


	# Sleep with debug output
	if [ "${#}" = "2" ]; then
		if [ "${2}" = "1" ]; then
			printf "wait "
			# shellcheck disable=SC2034
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
### Get newline separated default data mount directories (from .env file)
###
get_data_mounts() {
	_mounts="$( grep -E '^.*_DATADIR=' "${DEVILBOX_PATH}/env-example" | sed 's/^.*=//g' )"
	_data=""

	IFS='
	'
	for _mount in ${_mounts}; do
		_prefix="$( echo "${_mount}" | cut -c-1 )"

		# Relative path?
		if [ "${_prefix}" = "." ]; then
			_mount="$( echo "${_mount}" | sed 's/^\.//g' )" # Remove leading dot: .
			_mount="$( echo "${_mount}" | sed 's/^\///' )" # Remove leading slash: /
			_mount="${DEVILBOX_PATH}/${_mount}"
		fi

		# newline Append
		if [ "${_data}" = "" ]; then
			_data="${_mount}"
		else
			_data="${_data}\n${_mount}"
		fi
	done

	echo "${_data}"
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
	run "sed -i'' \"s/^PGSQL_SERVER=/#PGSQL_SERVER=/g\" \"${DEVILBOX_PATH}/.env\""
	run "sed -i'' \"s/^PHP_SERVER=/#PHP_SERVER=/g\" \"${DEVILBOX_PATH}/.env\""
}

###
### Enable debug mode
###
set_debug_enable() {
	run "sed -i'' \"s/^DEBUG_COMPOSE_ENTRYPOINT=.*/DEBUG_COMPOSE_ENTRYPOINT=1/\" \"${DEVILBOX_PATH}/.env\""
}

###
### Alter ports
###
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
	run "sed -i'' \"s/^HOST_PORT_PGSQL=.*/HOST_PORT_PGSQL=${_port}/\" \"${DEVILBOX_PATH}/.env\""
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
enable_docker_pgsql() {
	_docker_version="${1}"
	run "sed -i'' \"s/#PGSQL_SERVER=${_docker_version}/PGSQL_SERVER=${_docker_version}/g\" \"${DEVILBOX_PATH}/.env\""
}
enable_docker_php() {
	_docker_version="${1}"
	run "sed -i'' \"s/#PHP_SERVER=${_docker_version}/PHP_SERVER=${_docker_version}/g\" \"${DEVILBOX_PATH}/.env\""
}



################################################################################
#
#   S T A R T / S T O P   T H E   D E V I L B O X
#
################################################################################

devilbox_configure() {
	_srv1="${1}"
	_ver1="${2}"
	_srv2="${3}"
	_ver2="${4}"

	# Default values for remaining servers
	_def_php="${5}"
	_def_httpd="${6}"
	_def_mysql="${7}"
	_def_pgsql="${8}"

	# Specific enabled servers
	_set_php=""
	_set_httpd=""
	_set_mysql=""
	_set_pgsql=""

	# Enable Type 1
	if [ "${_srv1}" = "HTTPD" ]; then
		_set_httpd="${_ver1}"
	elif [ "${_srv1}" = "MYSQL" ]; then
		_set_mysql="${_ver1}"
	elif [ "${_srv1}" = "PGSQL" ]; then
		_set_pgsql="${_ver1}"
	elif [ "${_srv1}" = "PHP" ]; then
		_set_php="${_ver1}"
	else
		echo "Invalid server: ${_srv1}"
		exit 1
	fi

	# Enable Type 2
	if [ "${_srv2}" = "HTTPD" ]; then
		_set_httpd="${_ver2}"
	elif [ "${_srv2}" = "MYSQL" ]; then
		_set_mysql="${_ver2}"
	elif [ "${_srv2}" = "PGSQL" ]; then
		_set_pgsql="${_ver2}"
	elif [ "${_srv2}" = "PHP" ]; then
		_set_php="${_ver2}"
	else
		echo "Invalid server: ${_srv2}"
		exit 1
	fi

	# Enable remaining onces
	if [ "${_set_php}" = "" ]; then
		_set_php="${_def_php}"
	fi
	if [ "${_set_httpd}" = "" ]; then
		_set_httpd="${_def_httpd}"
	fi
	if [ "${_set_mysql}" = "" ]; then
		_set_mysql="${_def_mysql}"
	fi
	if [ "${_set_pgsql}" = "" ]; then
		_set_pgsql="${_def_pgsql}"
	fi

	# Adjust .env
	comment_all_dockers

	# Set versions in .env file
	enable_docker_php "${_set_php}"
	enable_docker_httpd "${_set_httpd}"
	enable_docker_mysql "${_set_mysql}"
	enable_docker_pgsql "${_set_pgsql}"
}


devilbox_configured_settings() {
	 grep -E '^[A-Z]+_SERVER=' "${DEVILBOX_PATH}/.env" | sed 's/_SERVER=/\t/g'
}


devilbox_pull() {
	# Make sure to pull until success
	ret=1
	while [ "${ret}" != "0" ]; do
		if ! docker-compose pull; then
			ret=1
		else
			ret=0
		fi
	done
}


devilbox_start() {
	# Make sure everything is stopped and defaulted
	sudo service docker restart || true
	sleep 5
	devilbox_stop

	# Make sure to start until success
	ret=1
	while [ "${ret}" != "0" ]; do
		if ! docker-compose up -d; then
			ret=1
			# Stop it and try again
			devilbox_stop
		else
			ret=0
		fi
	done

	# Wait for http to return 200
	printf "wait "
	_max="90"
	# shellcheck disable=SC2034
	for i in $(seq 1 "${_max}"); do
		if [ "$(  curl --connect-timeout 1 --max-time 1 -s -o /dev/null -w '%{http_code}' http://127.0.0.1/index.php )" = "200" ]; then
			break;
		fi
		sleep 1
		printf "."
	done
	printf "\n"

	# Wait another 60 sec for databases to come up
	wait_for 60 1
	echo

}


devilbox_print_actual_settings() {
	VERSIONS="$( curl -q http://127.0.0.1/index.php 2>/dev/null | \
        grep -E 'circles' | \
        grep -oE '<strong.*strong>.*\(.*\)' | \
        sed 's/<strong>//g' | \
        sed 's/<\/strong>.*(/\t/g' | \
        sed 's/)//g' )"

		IFS='
'
		for v in ${VERSIONS}; do
			service="$( echo "${v}" | awk '{print $1}' )"
			version="$( echo "${v}" | awk '{print $2}' )"
			printf "%-15s%s\n" "${service}" "${version}"
		done
}


devilbox_stop() {
	# Stop existing dockers
	cd "${DEVILBOX_PATH}" || exit 1
	docker-compose down > /dev/null 2>&1 || true
	docker-compose stop > /dev/null 2>&1 || true
	docker-compose kill > /dev/null 2>&1 || true
	docker-compose rm -f || true

	# Delete existing data dirs
	_data_dirs="$( get_data_mounts )"
	IFS='
	'
	for d in ${_data_dirs}; do
		runsu "rm -rf ${d}" "1"
	done
}


devilbox_print_errors() {
	_url="${1}"

	print_h2 "[ERROR] Curl"
	curl -vv "${_url}" || true
	echo

	print_h2 "[ERROR] docker-compose ps"
	docker-compose ps
	echo

	print_h2 "[ERROR] docker-compose logs"
	docker-compose logs
	echo

	print_h2 "[ERROR] log files"
	ls -lap log/
	sudo find log -type f -exec sh -c 'echo "{}:\n-----------------"; cat "{}"; echo "\n\n"' \;
}

################################################################################
#
#   T E S T   T H E   D E V I L B O X
#
################################################################################

devilbox_test_compose() {

	_broken="$( docker-compose ps | grep -c 'Exit' || true )"
	_running="$( docker-compose ps | grep -c 'Up' || true )"
	_total="$( docker-compose ps -q | grep -c '' || true )"

	if [ "${_broken}" != "0" ]; then
		echo "[ERR]: Broken: ${_broken} broken container"
		return 1
	fi

	if [ "${_running}" != "${_total}" ]; then
		echo "[ERR]: Broken: ${_running} / ${_total} container running"
		return 1
	fi

	echo "[OK]: All running"
	return 0
}


devilbox_test_url() {
	# Variables
	_url="${1}"
	_pattern="${2}"
	_number="${3}"

	_count="$( curl -q --retry 100 --retry-max-time 0 "${_url}" 2>/dev/null | grep -c "${_pattern}" || true )"

	if [ "${_count}" != "${_number}" ]; then
		echo "[ERR]: Found ${_count}/${_number} of '${_pattern}'"
		return 1
	fi

	echo "[OK]: Found ${_count}/${_number} of '${_pattern}'"
	return 0
}
