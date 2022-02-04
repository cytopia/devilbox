#!/usr/bin/env bash

# NOTE: Parsing curl to tac to circumnvent "failed writing body"
# https://stackoverflow.com/questions/16703647/why-curl-return-and-error-23-failed-writing-body

set -e
set -u
set -o pipefail

SCRIPT_PATH="$( cd "$(dirname "$0")" && pwd -P )"
DVLBOX_PATH="$( cd "${SCRIPT_PATH}/../.." && pwd -P )"
# shellcheck disable=SC1090
. "${SCRIPT_PATH}/.lib.sh"

RETRIES=10


# -------------------------------------------------------------------------------------------------
# FUNCTIONS
# -------------------------------------------------------------------------------------------------

PHP_TAG="$( grep 'devilbox/php' "${DVLBOX_PATH}/docker-compose.yml" | sed 's/^.*-work-//g' )"


###
### Get PHP core modules (5 rounds)
###
if ! PHP52_BASE="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '52-base' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 5.2"
	exit 1
fi

if ! PHP53_BASE="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '53-base' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 5.3"
	exit 1
fi

if ! PHP54_BASE="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '54-base' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 5.4"
	exit 1
fi

if ! PHP55_BASE="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '55-base' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 5.5"
	exit 1
fi

if ! PHP56_BASE="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '56-base' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 5.6"
	exit 1
fi

if ! PHP70_BASE="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '70-base' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 7.0"
	exit 1
fi

if ! PHP71_BASE="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '71-base' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 7.1"
	exit 1
fi

if ! PHP72_BASE="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '72-base' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 7.2"
	exit 1
fi

if ! PHP73_BASE="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '73-base' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 7.3"
	exit 1
fi

if ! PHP74_BASE="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '74-base' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 7.4"
	exit 1
fi

if ! PHP80_BASE="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '80-base' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 8.0"
	exit 1
fi

if ! PHP81_BASE="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '81-base' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 8.1"
	exit 1
fi

if ! PHP82_BASE="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '82-base' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 8.2"
	exit 1
fi

###
### Get PHP mods modules (5 rounds)
###

if ! PHP52_MODS="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '52-mods' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 5.2"
	exit 1
fi

if ! PHP53_MODS="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '53-mods' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 5.3"
	exit 1
fi

if ! PHP54_MODS="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '54-mods' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 5.4"
	exit 1
fi

if ! PHP55_MODS="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '55-mods' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 5.5"
	exit 1
fi

if ! PHP56_MODS="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '56-mods' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 5.6"
	exit 1
fi

if ! PHP70_MODS="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '70-mods' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 7.0"
	exit 1
fi

if ! PHP71_MODS="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '71-mods' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 7.1"
	exit 1
fi

if ! PHP72_MODS="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '72-mods' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 7.2"
	exit 1
fi

if ! PHP73_MODS="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '73-mods' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 7.3"
	exit 1
fi

if ! PHP74_MODS="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '74-mods' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 7.4"
	exit 1
fi

if ! PHP80_MODS="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '80-mods' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 8.0"
	exit 1
fi

if ! PHP81_MODS="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '81-mods' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 8.1"
	exit 1
fi

if ! PHP82_MODS="$( run "\
	curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/README.md' \
	| tac \
	| tac \
	| grep -E '82-mods' \
	| sed \
		-e 's/.*\">//g' \
		-e 's/<.*//g'" "${RETRIES}" )"; then
	>&2 echo "Failed to retrieve modules for PHP 8.2"
	exit 1
fi

###
### Todo: add ioncube
###
MODS="$( echo "${PHP52_MODS}, ${PHP53_MODS}, ${PHP54_MODS}, ${PHP55_MODS}, ${PHP56_MODS}, ${PHP70_MODS}, ${PHP71_MODS}, ${PHP72_MODS}, ${PHP73_MODS}, ${PHP74_MODS}, ${PHP80_MODS}, ${PHP81_MODS}, ${PHP82_MODS}" | sed 's/,/\n/g' | sed -e 's/^\s*//g' -e 's/\s*$//g' | sort -uf )"


###
### Get disabled modules
###
DISABLED=",blackfire,ioncube,phalcon,psr,$( grep -E '^PHP_MODULES_DISABLE=' "${DVLBOX_PATH}/env-example" | sed 's/.*=//g' ),"
#echo $DISABLED
B="✔"  # Enabled base modules (cannot be disabled)
E="🗸"  # Enabled mods modules (can be disabled)
D="d"  # Disabled modules (can be enabled)
U=" "  # Unavailable

echo "| Modules                       | <sup>PHP 5.2</sup> | <sup>PHP 5.3</sup> | <sup>PHP 5.4</sup> | <sup>PHP 5.5</sup> | <sup>PHP 5.6</sup> | <sup>PHP 7.0</sup> | <sup>PHP 7.1</sup> | <sup>PHP 7.2</sup> | <sup>PHP 7.3</sup> | <sup>PHP 7.4</sup> | <sup>PHP 8.0</sup> | <sup>PHP 8.1</sup> | <sup>PHP 8.2</sup> |"
echo "|-------------------------------|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|"
echo "${MODS}" | while read -r line; do
    # Print current module
	printf "| %-30s%s" "<sup>${line}</sup>" "|"

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

	# ---------- PHP 8.0 ----------#
	if echo ",${PHP80_MODS}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		if echo "${DISABLED}" | grep -Eq ",${line},"; then
			printf "    %s    |" "${D}"      # Currently disabled
		else
			if echo ",${PHP80_BASE}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
				printf "    %s    |" "${B}"  # Enabled, but cannot be disabled
			else
				printf "    %s    |" "${E}"  # Enabled, can be disabled
			fi
		fi
	else
		printf "    %s    |" "${U}"          # Not available
	fi

	# ---------- PHP 8.1 ----------#
	if echo ",${PHP81_MODS}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		if echo "${DISABLED}" | grep -Eq ",${line},"; then
			printf "    %s    |" "${D}"      # Currently disabled
		else
			if echo ",${PHP81_BASE}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
				printf "    %s    |" "${B}"  # Enabled, but cannot be disabled
			else
				printf "    %s    |" "${E}"  # Enabled, can be disabled
			fi
		fi
	else
		printf "    %s    |" "${U}"          # Not available
	fi

	# ---------- PHP 8.2 ----------#
	if echo ",${PHP82_MODS}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
		if echo "${DISABLED}" | grep -Eq ",${line},"; then
			printf "    %s    |" "${D}"      # Currently disabled
		else
			if echo ",${PHP82_BASE}," | sed 's/,\s/,/g' | grep -Eq ",${line},"; then
				printf "    %s    |" "${B}"  # Enabled, but cannot be disabled
			else
				printf "    %s    |" "${E}"  # Enabled, can be disabled
			fi
		fi
	else
		printf "    %s    |" "${U}"          # Not available
	fi

	printf "\\n"
done
