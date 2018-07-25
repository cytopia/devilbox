#!/usr/bin/env bash

setval() {
	printf -v "$1" "%s" "$(cat)";
	declare -p "$1";
}

RETURN=0

for url in $(find _includes/ -name \*.rst -exec grep -Eo 'http(s)?://[-?:,._a/#-Z0-9]+' {} \; | sort -u ); do

	# Try to curl multiple times in case host is currently not reachable
	max=60; i=0; fail=0
	eval "$( curl -SsI "${url}" 2> >(setval errval) > >(setval header); <<<"$?" setval retval;  )"
	while [ "${retval}" != "0" ] ; do
	#while ! header="$( curl -I "${url}" 2>/dev/null )"; do
		i=$(( i + 1 ))
		sleep 2
		if [ "${i}" -gt "${max}" ]; then
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

		if [ "${stat}" != "200" ]; then
			printf "\e[0;31m[ERR]\e[0m  %s %s\n" "${url}" "${line}"
			RETURN=1
		else
			printf "\e[0;32m[OK]\e[0m   %s\n" "${url}"
		fi
	fi
done

exit ${RETURN}
