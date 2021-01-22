#!/usr/bin/env bash

set -e
set -u
set -o pipefail

# -------------------------------------------------------------------------------------------------
# Pre-check
# -------------------------------------------------------------------------------------------------

if ! command -v perl >/dev/null 2>&1; then
	>&2 echo "Error 'perl' binary not found, but required."
	exit 1
fi


# -------------------------------------------------------------------------------------------------
# Functions
# -------------------------------------------------------------------------------------------------

###
### Get PHP Version
###
get_php_version() {
	local root_path="${1}"

	local retries=10
	local host_port_httpd
	local env_version
	local cli_version
	local web_version

	if ! host_port_httpd="$( "${root_path}/.tests/scripts/env-getvar.sh" "HOST_PORT_HTTPD" )"; then
		>&2 echo "Error, failed to retrieve HOST_PORT_HTTPD port from .env"
		return 1
	fi

	# Check .env file
	>&2 printf "Fetching PHP version from .env:     "
	if ! env_version="$( "${root_path}/.tests/scripts/env-getvar.sh" "PHP_SERVER" )"; then
		>&2 printf "FAILED\\n"
		>&2 echo "Error, failed to retrieve valid PHP version from .env"
		return 1
	fi
	>&2 printf "%s\\n" "${env_version}"

	# Check php -v
	>&2 printf "Fetching PHP version from php -v:   "
	if ! cli_version="$( run "docker-compose exec -T php php -v \
		| head -1 \
		| grep -Eo 'PHP[[:space:]]+[0-9]+\\.[0-9]+' \
		| grep -Eo '[0-9]+\\.[0-9]+'" \
		"${retries}" "${root_path}" "0" )"; then
		>&2 printf "FAILED\\n"
		>&2 echo "Error, failed to retrieve valid PHP version from php container"
		return 1
	fi
	>&2 printf "%s\\n" "${cli_version}"

	# Check intranet
	>&2 printf "Fetching PHP version from intranet: "
	if ! web_version="$( run "\
		curl -sS --fail 'http://localhost:${host_port_httpd}/index.php' \
		| tac \
		| tac \
		| grep -Eo 'PHP.*?\\([.0-9]+' \
		| grep -Eo '\\([.0-9]+' \
		| grep -Eo '[0-9]+\\.[0-9]+'" \
		"${retries}" "" "0" )"; then
		>&2 printf "FAILED\\n"
		>&2 echo "Error, failed to retrieve valid PHP version from intranet"
		return 1
	fi
	>&2 printf "%s\\n" "${web_version}"

	# Check if versions are non-empty
	if [ -z "${env_version}" ]; then
		>&2 echo "Error, no PHP version found in .env"
		return 1
	fi
	if [ -z "${cli_version}" ]; then
		>&2 echo "Error, no PHP version found via php -v"
		return 1
	fi
	if [ -z "${web_version}" ]; then
		>&2 echo "Error, no PHP version found from intranet"
		return 1
	fi

	# Check if versions match
	if [ "${env_version}" != "${cli_version}" ]; then
		>&2 printf "Error, PHP .env version (%s) does not match php -v version (%s)\\n" "${env_version}" "${cli_version}"
		return 1
	fi
	if [ "${env_version}" != "${web_version}" ]; then
		>&2 printf "Error, PHP .env version (%s) does not match intranet version (%s)\\n" "${env_version}" "${web_version}"
		return 1
	fi

	# Return PHP version
	echo "${env_version}"
}


###
### X-platform In-file replace
###
replace() {
	local from="${1}"
	local to="${2}"
	local file="${3}"
	local sep="|"
	if [ "${#}" = "4" ]; then
		sep="${4}"
	fi

	run "perl -pi -e 's${sep}${from}${sep}${to}${sep}g' ${file}"
}


###
### Run command
###
run() {
	local cmd="${1}"
	local retries=1
	local workdir=
	local verbose=1

	# retry?
	if [ "${#}" -gt "1" ]; then
		retries="${2}"
	fi
	# change directory?
	if [ "${#}" -gt "2" ]; then
		workdir="${3}"
	fi

	# be verbose?
	if [ "${#}" -gt "3" ]; then
		verbose="${4}"
	fi

	local red="\\033[0;31m"
	local green="\\033[0;32m"
	local yellow="\\033[0;33m"
	local reset="\\033[0m"

	# Set command
	if [ -n "${workdir}" ]; then
		cmd="set -e && set -u && set -o pipefail && cd ${workdir} && ${cmd}"
	else
		cmd="set -e && set -u && set -o pipefail && ${cmd}"
	fi
	# Print command?
	if [ "${verbose}" -eq "1" ]; then
		>&2 printf "${yellow}%s \$${reset} %s\\n" "$(whoami)" "${cmd}"
	fi

	for ((i=0; i<retries; i++)); do
		if eval "${cmd}"; then
			if [ "${verbose}" -eq "1" ]; then
				>&2 printf "${green}[%s: in %s rounds]${reset}\\n" "OK" "$((i+1))"
			fi
			return 0
		fi
		sleep 1
	done
	if [ "${verbose}" -eq "1" ]; then
		>&2 printf "${red}[%s: in %s rounds]${reset}\\n" "FAIL" "${retries}"
	fi
	return 1
}


###
### Run fail command (succeeds on error and fails on success)
###
run_fail() {
	local cmd="${1}"
	local workdir=
	local retries=1
	local verbose=1

	# retry?
	if [ "${#}" -gt "1" ]; then
		retries="${2}"
	fi
	# change directory?
	if [ "${#}" -gt "2" ]; then
		workdir="${3}"
	fi

	# be verbose?
	if [ "${#}" -gt "3" ]; then
		verbose="${4}"
	fi

	local red="\\033[0;31m"
	local green="\\033[0;32m"
	local yellow="\\033[0;33m"
	local reset="\\033[0m"

	# Set command
	if [ -n "${workdir}" ]; then
		cmd="set -e && set -u && set -o pipefail && cd ${workdir} && ${cmd}"
	else
		cmd="set -e && set -u && set -o pipefail && ${cmd}"
	fi
	# Print command?
	if [ "${verbose}" -eq "1" ]; then
		>&2 printf "${yellow}%s \$${reset} %s\\n" "$(whoami)" "${cmd}"
	fi

	for ((i=0; i<retries; i++)); do
		if ! eval "${cmd}"; then
			if [ "${verbose}" -eq "1" ]; then
				>&2 printf "${green}[%s: in %s rounds]${reset}\\n" "OK" "$((i+1))"
			fi
			return 0
		fi
		sleep 1
	done
	if [ "${verbose}" -eq "1" ]; then
		>&2 printf "${red}[%s: in %s rounds]${reset}\\n" "FAIL" "${retries}"
	fi
	return 1
}


###
### Create and validate vhost directory
###
create_vhost_dir() {
	local vhost="${1}"

	echo "Ensure vhost '${vhost}' is created"

	# Clean vhost dir
	cd "${DVLBOX_PATH}"
	while docker-compose exec --user devilbox -T php curl -sS --fail "http://php/vhosts.php" | grep ">${vhost}<" >/dev/null; do
		echo "Deleting vhost: ${vhost}"
		run "docker-compose exec --user devilbox -T php bash -c 'rm -rf /shared/httpd/${vhost} && sleep 5;'" "1" "${DVLBOX_PATH}"
	done

	# Create vhost dir
	cd "${DVLBOX_PATH}"
	while ! docker-compose exec --user devilbox -T php curl -sS --fail "http://php/vhosts.php" | grep ">${vhost}<" >/dev/null; do
		echo "Recreating vhost: ${vhost}"
		run "docker-compose exec --user devilbox -T php bash -c 'rm -rf   /shared/httpd/${vhost} && sleep 5;'" "1" "${DVLBOX_PATH}"
		run "docker-compose exec --user devilbox -T php bash -c 'mkdir -p /shared/httpd/${vhost} && sleep 5;'" "1" "${DVLBOX_PATH}"
	done
	echo "Vhost is present: ${vhost}"
}
