#!/usr/bin/env bash

set -e
set -u
set -o pipefail


#
# NOTE: Parsing curl to tac to circumnvent "failed writing body"
# https://stackoverflow.com/questions/16703647/why-curl-return-and-error-23-failed-writing-body
#


###
### Retrieve URL for current PHP version.
### Older PHP versions are presented a link with a different version due to compatibility.
###

printf "[TEST] Retrieve phpMyAdmin URL"
# 1st Try
if ! URL="$( curl -sS localhost/index.php | grep -Eo "/vendor/phpmyadmin-[.0-9]+/index\.php" )"; then
	# 2nd Try
	sleep 1
	if ! URL="$( curl -sS localhost/index.php | grep -Eo "/vendor/phpmyadmin-[.0-9]+/index\.php" )"; then
		# 3rd Try
		sleep 1
		if ! URL="$( curl -sS localhost/index.php | grep -Eo "/vendor/phpmyadmin-[.0-9]+/index\.php" )"; then
			printf "\r[FAILED] Retrieve phpMyAdmin URL\n"
			curl -sS localhost/index.php | grep -Eo "/vendor/phpmyadmin-[.0-9]+/index\.php" || true
			exit 1
		else
			printf "\r[OK]   Retrieve phpMyAdmin URL (3 rounds): ${URL}\n"
		fi
	else
		printf "\r[OK]   Retrieve phpMyAdmin URL (2 rounds): ${URL}\n"
	fi
else
	printf "\r[OK]   Retrieve phpMyAdmin URL (1 round): ${URL}\n"
fi


###
### Ensure given phpMyAdmin version works
###

printf "[TEST] Fetch ${URL}"
# 1st Try
if ! curl -sS localhost${URL} | tac | tac | grep -Eiq "welcome to.+phpMyAdmin"; then
	# 2nd Try
	sleep 1
	if ! curl -sS localhost${URL} | tac | tac | grep -Eiq "welcome to.+phpMyAdmin"; then
		# 3rd Try
		sleep 1
		if ! curl -sS localhost${URL} | tac | tac | grep -Eiq "welcome to.+phpMyAdmin"; then
			printf "\r[FAIL] Fetch ${URL}\n"
			curl -sS localhost/${URL} || true
			curl -sSI localhost/${URL} || true
			exit 1
		else
			printf "\r[OK]   Fetch ${URL} (3 rounds)\n"
		fi
	else
		printf "\r[OK]   Fetch ${URL} (2 rounds)\n"
	fi
else
	printf "\r[OK]   Fetch ${URL} (1 round)\n"
fi


###
### Login
###

TOKEN=
printf "[TEST] Retrieve phpMyAdmin login page"
while true; do
	# Try again until it succeeds
	if ! CONTENT="$( curl -sS -c cookie.txt localhost${URL} )"; then
		rm -f cookie.txt
		printf "e"
		continue;
	fi
	# Extract the token
	if ! TOKEN="$( echo "${CONTENT}" \
		| grep -Eo "name=\"token\" value=\".+\"" \
		| head -1 \
		| grep -Eo "value=\".+\"" \
		| sed -e 's/^value="//g' -e 's/"$//g' )"; then
		rm -f cookie.txt
		printf "w"
		continue;
	fi
	# Ensure Token does not contain '!'
	if echo "${TOKEN}" | grep -q "!"; then
		rm -f cookie.txt
		printf "!"
		continue;
	fi
	# Ensure Token does not contain '&'
	if echo "${TOKEN}" | grep -q "&"; then
		rm -f cookie.txt
		printf "&"
		continue;
	fi
	# Ensure Token does not contain '?'
	if echo "${TOKEN}" | grep -q "?"; then
		rm -f cookie.txt
		printf "?"
		continue;
	fi
	# Ensure Token does not contain '"'
	if echo "${TOKEN}" | grep -q "\""; then
		rm -f cookie.txt
		printf "\""
		continue;
	fi
	# Ensure Token does not contain '
	if echo "${TOKEN}" | grep -q "'"; then
		rm -f cookie.txt
		printf "'"
		continue;
	fi
	# Ensure Token does not contain '@'
	if echo "${TOKEN}" | grep -q "@"; then
		rm -f cookie.txt
		printf "@"
		continue;
	fi
	# Ensure Token does not contain '\'
	if echo "${TOKEN}" | grep -q "\\\\"; then
		rm -f cookie.txt
		printf "\\"
		continue;
	fi
	# Ensure Token does not contain '='
	if echo "${TOKEN}" | grep -q "="; then
		rm -f cookie.txt
		printf "="
		continue;
	fi
	# Ensure Token does not contain '`'
	if echo "${TOKEN}" | grep -q "\`"; then
		rm -f cookie.txt
		printf "\`"
		continue;
	fi
	# Ensure Token does not contain '+'
	if echo "${TOKEN}" | grep -q "+"; then
		rm -f cookie.txt
		printf "+"
		continue;
	fi
	# Ensure Token does not contain ';'
	if echo "${TOKEN}" | grep -q ";"; then
		rm -f cookie.txt
		printf ";"
		continue;
	fi
	# Ensure Token does not contain '%'
	if echo "${TOKEN}" | grep -q "%"; then
		rm -f cookie.txt
		printf "%%"
		continue;
	fi
	# Ensure Token does not contain ']'
	if echo "${TOKEN}" | grep -q "\]"; then
		rm -f cookie.txt
		printf "\]"
		continue;
	fi
	# Ensure Token does not contain '['
	if echo "${TOKEN}" | grep -q "\["; then
		rm -f cookie.txt
		printf "\["
		continue;
	fi
	# Ensure Token does not contain '$'
	if echo "${TOKEN}" | grep -q '\$'; then
		rm -f cookie.txt
		printf '\$'
		continue;
	fi

	# All set
	break
done
printf "\r[OK]   Retrieve phpMyAdmin login page\r\n"


printf "[TEST] Exract phpMyAdmin login token"
if ! TOKEN="$( echo "${CONTENT}" \
	| grep -Eo "name=\"token\" value=\".+\"" \
	| head -1 \
	| grep -Eo "value=\".+\"" \
	| sed -e 's/^value="//g' -e 's/"$//g' )"; then
	printf "\r[FAIL] Exract phpMyAdmin login token\n"
	rm -f cookie.txt || true
	echo "${CONTENT}"
	exit 1
fi
printf "\r[OK]   Exract phpMyAdmin login token: \"%s\"\n" "${TOKEN}"

printf "[TEST] Extract phpMyAdmin login session"
if ! SESSION="$( echo "${CONTENT}" \
	| grep -Eo "name=\"set_session\" value=\"[A-Fa-f0-9]+\"" \
	| grep -Eo "value=\"[A-Fa-f0-9]+\"" \
	| sed -e 's/^value="//g' -e 's/"$//g' )"; then
	printf "\r[OK]   Extract phpMyAdmin login session (not available)\n"
	SESSION=""
else
	printf "\r[OK]   Extract phpMyAdmin login session: \"%s\"\n" "${SESSION}"
fi


printf "[TEST] Submit phpMyAdmin POST login"
# 1st Try
if ! curl -sS -c cookie.txt -b cookie.txt -d "pma_username=root&pma_password=&server=1&target=index.php&token=${TOKEN}&set_session=${SESSION}" localhost${URL}; then
	# 2nd Try
	sleep 1
	if ! curl -sS -c cookie.txt -b cookie.txt -d "pma_username=root&pma_password=&server=1&target=index.php&token=${TOKEN}&set_session=${SESSION}" localhost${URL}; then
		# 3rd Try
		sleep 1
		if ! curl -sS -c cookie.txt -b cookie.txt -d "pma_username=root&pma_password=&server=1&target=index.php&token=${TOKEN}&set_session=${SESSION}" localhost${URL}; then
			printf "\r[FAIL] Submit phpMyAdmin POST login\n"
			curl -sS -c cookie.txt -b cookie.txt localhost/${URL} || true
			curl -sSI -c cookie.txt -b cookie.txt localhost/${URL} || true
			rm -f cookie.txt || true
			exit 1
		else
			printf "\r[OK]   Submit phpMyAdmin POST login (3 rounds)\n"
		fi
	else
		printf "\r[OK]   Submit phpMyAdmin POST login (2 rounds)\n"
	fi
else
	printf "\r[OK]   Submit phpMyAdmin POST login (1 round)\n"
fi


printf "[TEST] Evaluate successful phpMyAdmin login"
# 1st Try
if [ "$( curl -sS -c cookie.txt -b cookie.txt localhost${URL} | tac | tac  | grep -Ec "(Databases<.+SQL<.+Status<.+Users<.+Export<)|(\"User accounts\")" )" != "1" ]; then
	# 2nd Try
	sleep 1
	if [ "$( curl -sS -c cookie.txt -b cookie.txt localhost${URL} | tac | tac | grep -Ec "(Databases<.+SQL<.+Status<.+Users<.+Export<|(\"User accounts\")" )" != "1" ]; then
		# 3rd Try
		sleep 1
		if [ "$( curl -sS -c cookie.txt -b cookie.txt localhost${URL} | tac | tac | grep -Ec "(Databases<.+SQL<.+Status<.+Users<.+Export<|(\"User accounts\")" )" != "1" ]; then
			printf "\r[FAIL] Evaluate successful phpMyAdmin login\n"
			curl -sS -c cookie.txt -b cookie.txt localhost/${URL} || true
			curl -sSI -c cookie.txt -b cookie.txt localhost/${URL} || true
			rm -f cookie.txt || true
			exit 1
		else
			printf "\r[OK]   Evaluate successful phpMyAdmin login (3 rounds)\n"
		fi
	else
		printf "\r[OK]   Evaluate successful phpMyAdmin login (2 rounds)\n"
	fi
else
	printf "\r[OK]   Evaluate successful phpMyAdmin login (1 round)\n"
fi
rm -f cookie.txt || true
