#!/usr/bin/env bash


RETURN=0

for url in $(find _includes/ -name \*.rst -exec grep -Eo 'http(s)?://[-?:,._a/#-Z0-9]+' {} \; | sort -u ); do

	header="$( curl -I "${url}" 2>/dev/null )"
	line="$( echo "${header}" | grep -E '^HTTP/(1|2)' )"
	stat="$( echo "${line}" | awk '{print $2}' )"

	if [ "${stat}" != "200" ]; then
		printf "\e[0;31m[ERR]\e[0m %s %s\n" "${url}" "${line}"
		RETURN=1
	else
		printf "\e[0;32m[OK]\e[0m  %s\n" "${url}"
	fi
done

exit ${RETURN}
