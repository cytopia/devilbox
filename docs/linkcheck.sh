#!/usr/bin/env bash

# Bestrict
set -e
set -u
set -o pipefail


############################################################
# Overwritable global variables
############################################################


###
### In what path to look for files
###
SEARCH_PATH="."


###
### Comma separated list of file extensions to scan for urls
###
EXTENSIONS=""


###
### Regex to exclude URLs from being tested
###
URL_REGEX_EXCLUDE="^http(s)?:\/\/(127\.0\.0\.1)|(localhost)|(.+\.loc).*$"


###
### Timeout in seconds to see if a site is alive
###
TIMEOUT=10


###
### How many times to probe one URL to see if it is alive
###
RETRIES=3


###
### Comma separated list of acceptable http status codes
### to define that the URL is alive
###
STATUS_CODES=200



############################################################
# Functions
############################################################

###
### Usage
###
print_usage() {
	echo "Usage: linkcheck [-e -i -t -r -c] [<path>]"
	echo "       linkcheck --version"
	echo "       linkcheck --help"
	echo
	echo
	echo "Options:"
	echo
	echo "-e        Limit search to those file extensions."
	echo "          Defaults to limiting on non-binary files."
	echo "          Accepts comma separated string of extensions:"
	echo "            -e txt"
	echo "            -e txt,rst"
	echo "            -e sh,py.c,h"
	echo
	echo "-i        Ignore all URLs matching the specified regex."
	echo '          Defaults to: ^http(s)?:\/\/(127\.0\.0\.1)|(localhost)|(.+\.loc).*$'
	echo "          Accepts a single regex string:"
	echo "            -i '^http(?):\/\/my-comapny.com.*$'"
	echo
	echo "-t        Specify curl timeout in seconds, after which probing stops for one url."
	echo "          Defaults to 10 seconds."
	echo "          Accepts a positive integer:"
	echo "            -t 5"
	echo "            -t 10"
	echo
	echo "-r        Specify how many time to retry probing a single URL, before giving up."
	echo "          Defaults to 3 times."
	echo "          Accepts a positive integer:"
	echo "            -r 5"
	echo "            -r 10"
	echo
	echo "-c        Specify HTTP status codes that are valid for success."
	echo "          Any code not specified in here will produce an error for the given URL."
	echo "          Defaults to '200'."
	echo "          Accepts comma separated string of http status codes:"
	echo "            -c '200'"
	echo "            -c '200,301'"
	echo "            -c '200,301,302'"
	echo
	echo
	echo "--version Show version and exit."
	echo "--help    Show this help screen."
	echo
	echo
	echo "Optional arguments:"
	echo
	echo "<path>    Specify what directory to scan files for URLs."
	echo "          Defaults to current directory."
	echo
	echo
}


###
### Version
###
print_version() {
	echo "linkcheck v0.1 by cytopia"
	echo "https://github.com/cytopia/linkcheck"
}


###
### Set value (used to store stdout and stderr in two different variables)
###
setval() {
	printf -v "$1" "%s" "$(cat)";
	declare -p "$1";
}


###
### Gather URLs from files
###
gather_urls() {
	local path="${1}"
	local extensions="${2}"
	local reg_exclude="${3}"

	local url_regex="http(s)?:\/\/[-=?:,._/#0-9a-zA-Z]+"
	local find_ext=
	local find_cmd=

	if [ -n "${extensions}" ]; then
		find_ext="\( -iname \*.${extensions//,/ -o -iname \\*.} \)"
	fi

	find_cmd="find ${path} ${find_ext} -type f -exec grep --binary-files=without-match -Eo '${url_regex}' '{}' \;"
	>&2 echo "\$ ${find_cmd}"

	# Loop through uniqued URLs
	for url in $(eval "${find_cmd}" 2>/dev/null | sort -u); do
		# Ignore any 'Binary file...' results
		if echo "${url}" | grep -Eq '^htt'; then
			# Remove any trailing: [,.]
			url="$( echo "${url}" | sed 's/[,.]$//g')"

			# Ignore URLs excluded by regex
			if ! echo "${url}" | grep -qE "${reg_exclude}"; then
				echo "${url}"
			fi
		fi
	done
}


###
### Probe URLs for availability
###
probe_urls() {
	local urls="${1}"
	local timeout="${2}"
	local retries="${3}"
	local status_codes="${4}"
	local ret_code=0

	status_codes="${status_codes//,/|}"          # comma to |
	status_codes="${status_codes//[[:space:]]/}" # remove whitespace

	for url in ${urls}; do

		# Try to curl multiple times in case host is currently not reachable
		i=0; fail=0
		eval "$( curl -SsI --connect-timeout "${timeout}" "${url}" 2> >(setval errval) > >(setval header); <<<"$?" setval retval;  )"
		while [ "${retval}" != "0" ] ; do
			i=$(( i + 1 ))
			sleep 2
			if [ "${i}" -ge "${retries}" ]; then
				fail=1
				break;
			fi
		done

		# Curl request failed
		if [ "${fail}" = "1" ]; then
			printf "\e[0;31m[FAIL]\e[0m %s %s\n" "${url}" "${errval}"

		# Curl request succeeded
		else
			line="$( echo "${header}" | grep -E '^HTTP/(1|2)' )"
			stat="$( echo "${line}" | awk '{print $2}' )"

			#if [ "${stat}" != "200" ]; then
			if ! echo "${stat}" | grep -qE "${status_codes}"; then
				printf "\e[0;31m[ERR]\e[0m  %s %s\n" "${url}" "${line}"
				ret_code=1
			else
				printf "\e[0;32m[OK]\e[0m   %s %s\n" "${url}" "${line}"
			fi
		fi
	done
	return ${ret_code}
}


############################################################
# Entrypoint: arguments
############################################################
#-e -i -t -r -c
while [ $# -gt 0  ]; do
	case "${1}" in

		# ----------------------------------------
		-e)
			shift
			if [ "${#}" -gt "0" ]; then
				EXTENSIONS="${1}"
			else
				>&2 echo "Error, -e requires an argument."
				exit 1
			fi
			;;

		# ----------------------------------------
		-i)
			shift
			if [ "${#}" -gt "0" ]; then
				URL_REGEX_EXCLUDE="${1}"
			else
				>&2 echo "Error, -i requires an argument."
				exit 1
			fi
			;;

		# ----------------------------------------
		-t)
			shift
			if [ "${#}" -gt "0" ]; then
				TIMEOUT="${1}"
			else
				>&2 echo "Error, -t requires an argument."
				exit 1
			fi
			;;

		# ----------------------------------------
		-r)
			shift
			if [ "${#}" -gt "0" ]; then
				RETRIES="${1}"
			else
				>&2 echo "Error, -r requires an argument."
				exit 1
			fi
			;;
		# ----------------------------------------
		-c)
			shift
			if [ "${#}" -gt "0" ]; then
				STATUS_CODES="${1}"
			else
				>&2 echo "Error, -c requires an argument."
				exit 1
			fi
			;;

		# ----------------------------------------
		--help)
			print_usage
			exit 0
			;;

		# ----------------------------------------
		--version)
			print_version
			exit 0
			;;

		# ----------------------------------------
		*)
			# If it is the last argument, its the path
			if [ "${#}" = "1" ]; then
				SEARCH_PATH="${1}"
			else
				echo "Invalid argument: ${1}"
				echo "Type 'linkcheck --help' for available options."
				exit 1
			fi
			;;
	esac
	shift
done



MY_URLS="$( gather_urls "${SEARCH_PATH}" "${EXTENSIONS}" "${URL_REGEX_EXCLUDE}" )"

probe_urls "${MY_URLS}" "${TIMEOUT}" "${RETRIES}" "${STATUS_CODES}"
