#!/usr/bin/env bash

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
COMPOSEPATH="${SCRIPTPATH}/.."
PHP_TAG="$( grep 'devilbox/php' "${COMPOSEPATH}/docker-compose.yml" | sed 's/^.*-work-//g' )"


###
### Get PHP core modules (5 rounds)
###

if ! PHP52_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '52-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP52_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '52-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP52_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '52-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP52_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '52-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP52_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '52-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.2"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP53_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '53-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP53_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '53-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP53_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '53-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP53_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '53-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP53_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '53-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.3"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP54_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '54-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP54_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '54-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP54_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '54-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP54_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '54-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP54_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '54-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.4"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP55_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '55-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP55_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '55-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP55_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '55-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP55_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '55-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP55_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '55-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.5"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP56_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '56-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP56_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '56-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP56_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '56-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP56_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '56-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP56_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '56-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.6"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP70_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '70-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP70_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '70-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP70_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '70-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP70_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '70-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP70_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '70-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.0"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP71_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '71-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP71_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '71-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP71_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '71-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP71_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '71-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP71_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '71-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.1"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP72_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '72-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP72_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '72-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP72_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '72-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP72_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '72-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP72_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '72-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.2"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP73_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '73-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP73_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '73-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP73_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '73-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP73_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '73-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP73_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '73-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.3"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP74_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '74-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP74_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '74-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP74_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '74-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP74_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '74-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP74_BASE="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '74-base' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.4"
					exit 1
				fi
			fi
		fi
	fi
fi

###
### Get PHP mods modules (5 rounds)
###

if ! PHP52_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '52-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP52_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '52-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP52_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '52-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP52_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '52-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP52_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '52-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.2"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP53_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '53-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP53_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '53-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP53_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '53-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP53_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '53-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP53_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '53-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.3"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP54_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '54-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP54_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '54-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP54_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '54-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP54_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '54-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP54_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '54-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.4"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP55_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '55-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP55_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '55-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP55_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '55-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP55_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '55-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP55_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '55-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.5"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP56_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '56-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP56_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '56-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP56_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '56-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP56_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '56-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP56_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '56-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 5.6"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP70_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '70-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP70_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '70-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP70_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '70-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP70_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '70-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP70_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '70-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.0"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP71_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '71-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP71_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '71-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP71_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '71-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP71_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '71-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP71_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '71-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.1"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP72_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '72-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP72_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '72-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP72_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '72-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP72_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '72-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP72_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '72-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.2"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP73_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '73-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP73_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '73-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP73_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '73-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP73_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '73-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP73_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '73-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.3"
					exit 1
				fi
			fi
		fi
	fi
fi
if ! PHP74_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '74-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
	sleep 5;
	if ! PHP74_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '74-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
		sleep 5;
		if ! PHP74_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '74-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
			sleep 5;
			if ! PHP74_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '74-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
				sleep 5;
				if ! PHP74_MODS="$( curl -sS  https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md | tac | tac | grep -E '74-mods' | sed -e 's/.*">//g' -e 's/<.*//g' )"; then
					>&2 echo "Failed to retrieve modules for PHP 7.4"
					exit 1
				fi
			fi
		fi
	fi
fi


###
### Todo: add ioncube
###
MODS="$( echo "${PHP52_MODS}, ${PHP53_MODS}, ${PHP54_MODS}, ${PHP55_MODS}, ${PHP56_MODS}, ${PHP70_MODS}, ${PHP71_MODS}, ${PHP72_MODS}, ${PHP73_MODS}, ${PHP74_MODS}" | sed 's/,/\n/g' | sed -e 's/^\s*//g' -e 's/\s*$//g' | sort -u )"


###
### Get disabled modules
###
DISABLED=",blackfire,ioncube,$( grep -E '^PHP_MODULES_DISABLE=' "${SCRIPTPATH}/../env-example" | sed 's/.*=//g' ),"
#echo $DISABLED
B="✔"  # Enabled base modules (cannot be disabled)
E="🗸"  # Enabled mods modules (can be disabled)
D="d"  # Disabled modules (can be enabled)
U=" "  # Unavailable

echo "| Modules        | PHP 5.2 | PHP 5.3 | PHP 5.4 | PHP 5.5 | PHP 5.6 | PHP 7.0 | PHP 7.1 | PHP 7.2 | PHP 7.3 | PHP 7.4 |"
echo "|----------------|---------|---------|---------|---------|---------|---------|---------|---------|---------|---------|"
echo "${MODS}" | while read line; do
	printf "| %-15s%s" "${line}" "|"

	# ---------- PHP 5.2 ----------#
	if echo ",${PHP52_MODS}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		if echo "${DISABLED}" | grep -Eq ",${line},"; then
			printf "    %s    |" "${D}"      # Currently disabled
		else
			if echo ",${PHP52_BASE}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
				printf "    %s    |" "${B}"  # Enabled, but cannot be disabled
			else
				printf "    %s    |" "${E}"  # Enabled, can be disabled
			fi
		fi
	else
		printf "    %s    |" "${U}"          # Not available
	fi

	# ---------- PHP 5.3 ----------#
	if echo ",${PHP53_MODS}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		if echo "${DISABLED}" | grep -Eq ",${line},"; then
			printf "    %s    |" "${D}"      # Currently disabled
		else
			if echo ",${PHP53_BASE}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
				printf "    %s    |" "${B}"  # Enabled, but cannot be disabled
			else
				printf "    %s    |" "${E}"  # Enabled, can be disabled
			fi
		fi
	else
		printf "    %s    |" "${U}"          # Not available
	fi

	# ---------- PHP 5.4 ----------#
	if echo ",${PHP54_MODS}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		if echo "${DISABLED}" | grep -Eq ",${line},"; then
			printf "    %s    |" "${D}"      # Currently disabled
		else
			if echo ",${PHP54_BASE}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
				printf "    %s    |" "${B}"  # Enabled, but cannot be disabled
			else
				printf "    %s    |" "${E}"  # Enabled, can be disabled
			fi
		fi
	else
		printf "    %s    |" "${U}"          # Not available
	fi

	# ---------- PHP 5.5 ----------#
	if echo ",${PHP55_MODS}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		if echo "${DISABLED}" | grep -Eq ",${line},"; then
			printf "    %s    |" "${D}"      # Currently disabled
		else
			if echo ",${PHP55_BASE}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
				printf "    %s    |" "${B}"  # Enabled, but cannot be disabled
			else
				printf "    %s    |" "${E}"  # Enabled, can be disabled
			fi
		fi
	else
		printf "    %s    |" "${U}"          # Not available
	fi

	# ---------- PHP 5.6 ----------#
	if echo ",${PHP56_MODS}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		if echo "${DISABLED}" | grep -Eq ",${line},"; then
			printf "    %s    |" "${D}"      # Currently disabled
		else
			if echo ",${PHP56_BASE}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
				printf "    %s    |" "${B}"  # Enabled, but cannot be disabled
			else
				printf "    %s    |" "${E}"  # Enabled, can be disabled
			fi
		fi
	else
		printf "    %s    |" "${U}"          # Not available
	fi

	# ---------- PHP 7.0 ----------#
	if echo ",${PHP70_MODS}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		if echo "${DISABLED}" | grep -Eq ",${line},"; then
			printf "    %s    |" "${D}"      # Currently disabled
		else
			if echo ",${PHP70_BASE}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
				printf "    %s    |" "${B}"  # Enabled, but cannot be disabled
			else
				printf "    %s    |" "${E}"  # Enabled, can be disabled
			fi
		fi
	else
		printf "    %s    |" "${U}"          # Not available
	fi

	# ---------- PHP 7.1 ----------#
	if echo ",${PHP71_MODS}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		if echo "${DISABLED}" | grep -Eq ",${line},"; then
			printf "    %s    |" "${D}"      # Currently disabled
		else
			if echo ",${PHP71_BASE}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
				printf "    %s    |" "${B}"  # Enabled, but cannot be disabled
			else
				printf "    %s    |" "${E}"  # Enabled, can be disabled
			fi
		fi
	else
		printf "    %s    |" "${U}"          # Not available
	fi

	# ---------- PHP 7.2 ----------#
	if echo ",${PHP72_MODS}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		if echo "${DISABLED}" | grep -Eq ",${line},"; then
			printf "    %s    |" "${D}"      # Currently disabled
		else
			if echo ",${PHP72_BASE}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
				printf "    %s    |" "${B}"  # Enabled, but cannot be disabled
			else
				printf "    %s    |" "${E}"  # Enabled, can be disabled
			fi
		fi
	else
		printf "    %s    |" "${U}"          # Not available
	fi

	# ---------- PHP 7.3 ----------#
	if echo ",${PHP73_MODS}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		if echo "${DISABLED}" | grep -Eq ",${line},"; then
			printf "    %s    |" "${D}"      # Currently disabled
		else
			if echo ",${PHP73_BASE}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
				printf "    %s    |" "${B}"  # Enabled, but cannot be disabled
			else
				printf "    %s    |" "${E}"  # Enabled, can be disabled
			fi
		fi
	else
		printf "    %s    |" "${U}"          # Not available
	fi

	# ---------- PHP 7.4 ----------#
	if echo ",${PHP74_MODS}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		if echo "${DISABLED}" | grep -Eq ",${line},"; then
			printf "    %s    |" "${D}"      # Currently disabled
		else
			if echo ",${PHP74_BASE}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
				printf "    %s    |" "${B}"  # Enabled, but cannot be disabled
			else
				printf "    %s    |" "${E}"  # Enabled, can be disabled
			fi
		fi
	else
		printf "    %s    |" "${U}"          # Not available
	fi

	printf "\n"
done
