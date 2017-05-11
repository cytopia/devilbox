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


###
### Default enabled Docker Versions
###
get_enabled_versions() {
	 grep -E '^[A-Z]+_SERVER=' "${DEVILBOX_PATH}/.env" | sed 's/_SERVER=/\t/g'

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
#   S T A R T / S T O P / T E S T   T H E   D E V I L B O X
#
################################################################################

devilbox_start() {
	_new_httpd="$1"
	_new_mysql="$2"
	_new_pysql="$3"
	_new_php="$4"
	_new_head="$5"

	# Print Headline
	print_h1 "${_new_head}"

	# Adjust .env
	comment_all_dockers
	enable_docker_httpd "${_new_httpd}"
	enable_docker_mysql "${_new_mysql}"
	enable_docker_pgsql "${_new_pysql}"
	enable_docker_php "${_new_php}"

	# Run
	docker-compose up -d

	# Wait for http to return 200
	printf "wait "
	_max="90"
	# shellcheck disable=SC2034
	for i in $(seq 1 "${_max}"); do
		if [ "$(  curl --connect-timeout 1 --max-time 1 -s -o /dev/null -w '%{http_code}' http://localhost/index.php )" = "200" ]; then
			break;
		fi
		sleep 1
		printf "."
	done
	printf "\n"

	# Wait another 30 sec for databases to come up
	wait_for 30 1
	echo
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

devilbox_show() {
	###
	### 1. Show Info
	###
	print_h2 "Info"

	# Show wanted versions
	echo "[Wanted] .env settings"
	echo "------------------------------------------------------------"
	get_enabled_versions
	echo

	# Get actual versions
	echo "[Actual] http://localhost settings"
	echo "------------------------------------------------------------"
	curl -q http://localhost/index.php 2>/dev/null | \
        grep -E 'circles' | \
        grep -oE '<strong.*strong>.*\(.*\)' | \
        sed 's/<strong>//g' | \
        sed 's/<\/strong>.*(/\t/g' | \
        sed 's/)//g'
	echo
}


################################################################################
#
#   T E S T   T H E   D E V I L B O X
#
################################################################################

debilbox_test() {
	###
	### Variables
	###
	_ret=0 # Final exit code
	_oks=17 # Require this many [OK]'s on the page




	###
	### 2. Test docker-compose
	###
	print_h2 "docker-compose"

	echo "docker-compose ps"
	echo "------------------------------------------------------------"
	if _test_docker_compose >/dev/null 2>&1; then
		echo "[OK]: All running"
	else
		echo "[ERR]: Broken"
		_ret="$(( _ret + 1 ))"
	fi


	###
	### 3. Show Curl output
	###
	print_h2 "3. Test status via curl"

	echo "Count [OK]'s on curl-ed url"
	echo "------------------------------------------------------------"
	if ! _cnt="$( _test_curled_oks "${_oks}" )"; then
		_ret="$(( _ret + 1 ))"
		echo "[ERR]: ${_cnt} / ${_oks} (Not all 'dvlbox-ok' found)"
	else
		echo "[OK]: ${_cnt} / ${_oks} (All 'dvlbox-ok' found)"
	fi
	echo

	echo "Count [ERR]'s on curl-ed url"
	echo "------------------------------------------------------------"
	if ! _cnt="$( _test_curled_err )"; then
		_ret="$(( _ret + 1 ))"
		echo "[ERR]: ${_cnt} / 0 (Found some 'dvlbox-err')"
	else
		echo "[OK]: ${_cnt} / 0 (No 'dvlbox-err' found)"
	fi
	echo


	###
	### Final return
	###
	if [ "${_ret}" != "0" ]; then
		print_h2 "4. Error output"
		echo "Curl"
		echo "------------------------------------------------------------"
		curl -vv http://localhost/index.php || true
		echo

		echo "docker-compose ps"
		echo "------------------------------------------------------------"
		docker-compose ps
		echo

		echo "docker-compose logs"
		echo "------------------------------------------------------------"
		docker-compose logs
		echo

		echo "log files"
		echo "------------------------------------------------------------"
		ls -lap log/
		sudo find log -type f -exec sh -c 'echo "{}:\n-----------------"; cat "{}"; echo "\n\n"' \;

		return 1
	fi

	return 0
}


################################################################################
#
#   T E S T I N G   H E L P E R
#
################################################################################


###
### Test against stopped containers
###
_test_docker_compose() {

	_broken="$( docker-compose ps | grep -c 'Exit' || true )"
	_running="$( docker-compose ps | grep -c 'Up' || true )"
	_total="$( docker-compose ps -q | grep -c '' || true )"

	if [ "${_broken}" != "0" ]; then
		return 1
	fi

	if [ "${_running}" != "${_total}" ]; then
		return 1
	fi

	return 0
}


###
### Test [OK]'s found on website
###
_test_curled_oks() {
	_oks="${1}"
	_find_ok="dvlbox-ok"

	_count="$( curl -q http://localhost/index.php 2>/dev/null | grep -c "${_find_ok}" || true )"
	echo "${_count}"

	if [ "${_count}" != "${_oks}" ]; then
		return 1
	else
		return 0
	fi
}

###
### Test [ERR]'s found on website
###
_test_curled_err() {
	_find_err="dvlbox-err"

	_count="$( curl -q http://localhost/index.php 2>/dev/null | grep -c "${_find_err}" || true )"
	echo "${_count}"

	if [ "${_count}" != "0" ]; then
		return 1
	else
		return 0
	fi
}
