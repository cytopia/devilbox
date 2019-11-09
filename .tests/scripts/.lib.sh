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

	local red="\033[0;31m"
	local green="\033[0;32m"
	local yellow="\033[0;33m"
	local reset="\033[0m"

	if [ "${verbose}" -eq "1" ]; then
		>&2 printf "${yellow}%s \$${reset} %s\n" "$(whoami)" "${cmd}"
	fi

	for ((i=0; i<${retries}; i++)); do
		if [ -n "${workdir}" ]; then
			if bash -c "set -e && set -u && set -o pipefail && cd ${workdir} && ${cmd}"; then
				if [ "${verbose}" -eq "1" ]; then
					>&2 printf "${green}[%s: in %s rounds]${reset}\n" "OK" "$((i+1))"
				fi
				return 0
			fi
		else
			if bash -c "set -e && set -u && set -o pipefail && ${cmd}"; then
				if [ "${verbose}" -eq "1" ]; then
					>&2 printf "${green}[%s: in %s rounds]${reset}\n" "OK" "$((i+1))"
				fi
				return 0
			fi
		fi
		sleep 1
	done
	if [ "${verbose}" -eq "1" ]; then
		>&2 printf "${red}[%s: in %s rounds]${reset}\n" "FAIL" "${retries}"
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

	local red="\033[0;31m"
	local green="\033[0;32m"
	local yellow="\033[0;33m"
	local reset="\033[0m"

	if [ "${verbose}" -eq "1" ]; then
		>&2 printf "${yellow}%s \$${reset} %s\n" "$(whoami)" "${cmd}"
	fi

	for ((i=0; i<${retries}; i++)); do
		if [ -n "${workdir}" ]; then
			if ! bash -c "set -e && set -u && set -o pipefail && cd ${workdir} && ${cmd}"; then
				if [ "${verbose}" -eq "1" ]; then
					>&2 printf "${green}[%s: in %s rounds]${reset}\n" "OK" "$((i+1))"
				fi
				return 0
			fi
		else
			if ! bash -c "set -e && set -u && set -o pipefail && ${cmd}"; then
				if [ "${verbose}" -eq "1" ]; then
					>&2 printf "${green}[%s: in %s rounds]${reset}\n" "OK" "$((i+1))"
				fi
				return 0
			fi
		fi
		sleep 1
	done
	if [ "${verbose}" -eq "1" ]; then
		>&2 printf "${red}[%s: in %s rounds]${reset}\n" "FAIL" "${retries}"
	fi
	return 1
}
