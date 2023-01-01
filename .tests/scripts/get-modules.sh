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
PHP_MOD="$( run "curl -sS 'https://raw.githubusercontent.com/devilbox/docker-php-fpm/${PHP_TAG}/doc/php-modules.md'" "${RETRIES}" )";


get_modules() {
	local php_version="${1}"
	local stage="${2}"
	local modules=
	local names=

	modules="$( \
		echo "${PHP_MOD}" \
		| grep -E "ext_${stage}_.+_${php_version}" \
		| grep -v '><' \
		| sed \
			-e "s|.*ext_${stage}_||g" \
			-e "s|_${php_version}.*||g" \
	)"
	# Ensure to fetch name with correct upper-/lower-case
	while read -r module; do
		name="$( \
			echo "${PHP_MOD}" \
			| grep -Eio ">${module}<" \
			| sed -e 's|>||g' -e 's|<||g' \
			| sort -u \
		)"
		names="$( printf "%s\n%s" "${names}" "${name}" )"
	done < <(echo "${modules}")

	# Remove leading and trailing newline
	names="$( echo "${names}" | grep -v '^$' )"

	# Output comma separated
	echo "${names}" | paste -d, -s
}


PHP52_BASE="$( get_modules "5.2" "base" )"
PHP53_BASE="$( get_modules "5.3" "base" )"
PHP54_BASE="$( get_modules "5.4" "base" )"
PHP55_BASE="$( get_modules "5.5" "base" )"
PHP56_BASE="$( get_modules "5.6" "base" )"
PHP70_BASE="$( get_modules "7.0" "base" )"
PHP71_BASE="$( get_modules "7.1" "base" )"
PHP72_BASE="$( get_modules "7.2" "base" )"
PHP73_BASE="$( get_modules "7.3" "base" )"
PHP74_BASE="$( get_modules "7.4" "base" )"
PHP80_BASE="$( get_modules "8.0" "base" )"
PHP81_BASE="$( get_modules "8.1" "base" )"
PHP82_BASE="$( get_modules "8.2" "base" )"

PHP52_MODS="$( get_modules "5.2" "mods" )"
PHP53_MODS="$( get_modules "5.3" "mods" )"
PHP54_MODS="$( get_modules "5.4" "mods" )"
PHP55_MODS="$( get_modules "5.5" "mods" )"
PHP56_MODS="$( get_modules "5.6" "mods" )"
PHP70_MODS="$( get_modules "7.0" "mods" )"
PHP71_MODS="$( get_modules "7.1" "mods" )"
PHP72_MODS="$( get_modules "7.2" "mods" )"
PHP73_MODS="$( get_modules "7.3" "mods" )"
PHP74_MODS="$( get_modules "7.4" "mods" )"
PHP80_MODS="$( get_modules "8.0" "mods" )"
PHP81_MODS="$( get_modules "8.1" "mods" )"
PHP82_MODS="$( get_modules "8.2" "mods" )"


###
### Todo: add ioncube
###
MODS="$( echo "${PHP52_MODS}, ${PHP53_MODS}, ${PHP54_MODS}, ${PHP55_MODS}, ${PHP56_MODS}, ${PHP70_MODS}, ${PHP71_MODS}, ${PHP72_MODS}, ${PHP73_MODS}, ${PHP74_MODS}, ${PHP80_MODS}, ${PHP81_MODS}, ${PHP82_MODS}" | sed 's/,/\n/g' | sed -e 's/^\s*//g' -e 's/\s*$//g' | sort -uf )"


###
### Get disabled modules
###
DISABLED=",blackfire,ioncube,phalcon,psr,xhprof,$( grep -E '^PHP_MODULES_DISABLE=' "${DVLBOX_PATH}/env-example" | sed 's/.*=//g' ),"
#echo $DISABLED
B="✔"  # Enabled base modules (cannot be disabled)
E="🗸"  # Enabled mods modules (can be disabled)
D="d"  # Disabled modules (can be enabled)
U=" "  # Unavailable

echo "| Modules                       | <sup>PHP 5.2</sup> | <sup>PHP 5.3</sup> | <sup>PHP 5.4</sup> | <sup>PHP 5.5</sup> | <sup>PHP 5.6</sup> | <sup>PHP 7.0</sup> | <sup>PHP 7.1</sup> | <sup>PHP 7.2</sup> | <sup>PHP 7.3</sup> | <sup>PHP 7.4</sup> | <sup>PHP 8.0</sup> | <sup>PHP 8.1</sup> | <sup>PHP 8.2</sup> |"
echo "|-------------------------------|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|:-------:|"
echo "${MODS}" | while read -r line; do
	# Ignore modules
	if [ "${line}" = "Core" ]; then
		continue
	fi

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
