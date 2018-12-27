#!/usr/bin/env bash

###
### Get PHP modules (5 rounds)
###

if ! PHP52="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '52-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP52="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '52-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP52="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '52-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP52="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '52-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP52="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '52-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.2"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP53="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '53-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP53="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '53-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP53="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '53-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP53="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '53-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP53="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '53-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.3"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP54="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '54-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP54="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '54-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP54="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '54-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP54="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '54-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP54="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '54-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.4"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP55="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '55-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP55="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '55-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP55="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '55-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP55="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '55-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP55="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '55-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.5"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP56="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '56-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP56="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '56-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP56="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '56-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP56="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '56-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP56="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '56-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.6"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP70="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '70-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP70="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '70-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP70="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '70-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP70="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '70-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP70="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '70-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.0"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP71="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '71-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP71="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '71-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP71="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '71-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP71="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '71-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP71="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '71-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.1"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP72="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '72-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP72="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '72-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP72="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '72-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP72="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '72-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP72="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '72-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.2"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP73="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '73-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP73="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '73-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP73="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '73-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP73="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '73-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP73="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '73-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.3"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP74="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '74-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP74="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '74-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP74="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '74-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP74="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '74-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP74="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/master/README.md | tac | tac | grep -E '74-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.4"
					exit 1
				fi
			fi
		fi
	fi
fi

ALL="$( echo "${PHP52}, ${PHP53}, ${PHP54}, ${PHP55}, ${PHP56}, ${PHP70}, ${PHP71}, ${PHP72}, ${PHP73}, ${PHP74}" | sed 's/,/\n/g' | sed -e 's/^\s*//g' -e 's/\s*$//g' | sort -u )"

Y="✓"

echo "| Modules        | PHP 5.2 | PHP 5.3 | PHP 5.4 | PHP 5.5 | PHP 5.6 | PHP 7.0 | PHP 7.1 | PHP 7.2 | PHP 7.3 | PHP 7.4 |"
echo "|----------------|---------|---------|---------|---------|---------|---------|---------|---------|---------|---------|"
echo "${ALL}" | while read line; do
	printf "| %-15s%s" "${line}" "|"

	if echo ",${PHP52}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		printf "    %s    |" "${Y}"
	else
		printf "         |"
	fi
	if echo ",${PHP53}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		printf "    %s    |" "${Y}"
	else
		printf "         |"
	fi
	if echo ",${PHP54}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		printf "    %s    |" "${Y}"
	else
		printf "         |"
	fi
	if echo ",${PHP55}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		printf "    %s    |" "${Y}"
	else
		printf "         |"
	fi
	if echo ",${PHP56}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		printf "    %s    |" "${Y}"
	else
		printf "         |"
	fi
	if echo ",${PHP70}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		printf "    %s    |" "${Y}"
	else
		printf "         |"
	fi
	if echo ",${PHP71}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		printf "    %s    |" "${Y}"
	else
		printf "         |"
	fi
	if echo ",${PHP72}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		printf "    %s    |" "${Y}"
	else
		printf "         |"
	fi
	if echo ",${PHP73}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		printf "    %s    |" "${Y}"
	else
		printf "         |"
	fi
	if echo ",${PHP74}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		printf "    %s    |" "${Y}"
	else
		printf "         |"
	fi

	printf "\n"
done
