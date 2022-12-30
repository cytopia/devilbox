#!/usr/bin/env bash

set -e
set -u
set -o pipefail


#--------------------------------------------------------------------------------------------------
# GLOBALS
#--------------------------------------------------------------------------------------------------
RET_CODE=0
MY_UID="$( id -u )"
MY_GID="$( id -g )"
DEBUG=0


#--------------------------------------------------------------------------------------------------
# Functions
#--------------------------------------------------------------------------------------------------

###
### Logger functions
###
log_err() {
	>&2 printf "\\e[1;31m[ERR]   %s\\e[0m\\n" "${1}"
}

log_note() {
	>&2 printf "\\e[1;33m[NOTE]  %s\\e[0m\\n" "${1}"
}

log_info() {
	printf "\\e[;34m[INFO]  %s\\e[0m\\n" "${1}"
}

log_ok() {
	printf "\\e[;32m[SUCC]  %s\\e[0m\\n" "${1}"
}
log_debug() {
	if [ "${DEBUG}" -eq "1" ]; then
		printf "[DEBUG] %s\\n" "${1}"
	fi
}

###
### Output functions
###
print_head_1() {
	printf "\\n# "
	printf "%0.s=" {1..78}
	printf "\\n"

	printf "# %s\\n" "${1}"

	printf "# "
	printf "%0.s=" {1..78}
	printf "\\n"
}

###
### File functions
###
file_get_uid() {
	if [ "$(uname)" = "Linux" ]; then
		stat --format '%u' "${1}"
	else
		stat -f '%u' "${1}"
	fi
}

file_get_gid() {
	if [ "$(uname)" = "Linux" ]; then
		stat --format '%g' "${1}"
	else
		stat -f '%g' "${1}"
	fi
}

# Returns 4-digit format
file_get_perm() {
	local perm
	local len

	if [ "$(uname)" = "Linux" ]; then
		# If no special permissions are set (no sticky bit...), linux will
		# only output the 3 digit number
		perm="$( stat --format '%a' "${1}" )"
	else
		perm="$( stat -f '%OLp' "${1}" )"
	fi

	# For special cases check the length and add a leading 0
	len="$(echo "${perm}" | awk '{ print length() }')"
	if [ "${len}" = "3" ]; then
		perm="0${perm}"
	fi

	echo "${perm}"
}

# Get path with '~' replace with correct home path
get_path() {
	echo "${1/#\~/${HOME}}"
}

# Returns sub directories by one level
# Also returns symlinks if they point to a directory
get_sub_dirs_level_1() {
	local dir="${1}"
	dir="${dir#./}"   # Remove leading './' if it exists
	dir="${dir%/}"    # Remove trailing '/' if it exists
	# shellcheck disable=SC2016
	find "${dir}" \
		| grep -Ev "^${dir}\$" \
		| grep -Ev "^${dir}/.+/" \
		| xargs -n1 sh -c 'if [ -d "${1}" ]; then echo "${1}"; fi'  -- \
		| sort
}

# Returns sub directories by two level
# Also returns symlinks if they point to a directory
get_sub_dirs_level_2() {
	local dir="${1}"
	dir="${dir#./}"   # Remove leading './' if it exists
	dir="${dir%/}"    # Remove trailing '/' if it exists
	# shellcheck disable=SC2016
	find "${dir}" \
		| grep -Ev "^${dir}\$" \
		| grep -Ev "^${dir}/.+/.+/" \
		| xargs -n1 sh -c 'if [ -d "${1}" ]; then echo "${1}"; fi'  -- \
		| sort
}

# Returns the value of .env var
get_env_value() {
	local val
	val="$( grep -E "^${1}=" .env )"
	echo "${val#*=}"
}

# Validate a DNS record
validate_dns() {
	ping -c1 "${1}" >/dev/null 2>&1
}



#--------------------------------------------------------------------------------------------------
# Check git
#--------------------------------------------------------------------------------------------------
print_head_1 "Checking git"

GIT_STATUS="$( git status -s )"
if [ -z "${GIT_STATUS}" ]; then
	log_ok "git is clean"
else
	log_err "git is unclean"
	echo "${GIT_STATUS}"
	RET_CODE=$(( RET_CODE + 1))
fi


#--------------------------------------------------------------------------------------------------
# Check env file
#--------------------------------------------------------------------------------------------------
print_head_1 "Checking .env file"

if [ -f .env ]; then
	log_ok ".env file exists"
else
	log_err ".env file does not exist"
	RET_CODE=$(( RET_CODE + 1))
	exit 1
fi
if [ -r .env ]; then
	log_ok ".env file is readable"
else
	log_err ".env file is not readable"
	RET_CODE=$(( RET_CODE + 1))
	exit 1
fi

# Ensure all variables exist in .env file
ENV_VAR_MISSING=0
while read -r env_var; do
	if ! grep -E "^${env_var}=" .env >/dev/null; then
		log_err "Variable '${env_var}' missing in .env file"
		RET_CODE=$(( RET_CODE + 1))
		ENV_VAR_MISSING=1
	else
		log_debug "Variable '${env_var}' is present in '.env file"
	fi
done < <(grep -E '^[A-Z].+=' env-example  | awk -F'=' '{print $1}')
if [ "${ENV_VAR_MISSING}" = "0" ]; then
	log_ok "All variables are present in .env file"
fi

# Ensure variables are not duplicated in .env
ENV_VAR_DUPLICATED=0
while read -r env_var; do
	OCCURANCES="$( grep -Ec "^${env_var}=" .env )"
	if [ "${OCCURANCES}" != "1" ]; then
		log_err "Variable '${env_var}' should only be defined once. Occurances: ${OCCURANCES}"
		RET_CODE=$(( RET_CODE + 1))
		ENV_VAR_DUPLICATED=1
	else
		log_debug "Variable '${env_var}' is defined exactly once."
	fi
done < <(grep -E '^[A-Z].+=' env-example  | awk -F'=' '{print $1}')
if [ "${ENV_VAR_DUPLICATED}" = "0" ]; then
	log_ok "No variables is duplicated in .env file"
fi


#--------------------------------------------------------------------------------------------------
# Check env file values
#--------------------------------------------------------------------------------------------------
print_head_1 "Checking .env file values"

WRONG_ENV_FILES_VALUES=0

DEBUG_ENTRYPOINT="$( get_env_value "DEBUG_ENTRYPOINT" )"
if [ "${DEBUG_ENTRYPOINT}" != "0" ] && [ "${DEBUG_ENTRYPOINT}" != "1" ] && [ "${DEBUG_ENTRYPOINT}" != "2" ] && [ "${DEBUG_ENTRYPOINT}" != "3" ] && [ "${DEBUG_ENTRYPOINT}" != "3" ]; then
	log_err "Variable 'DEBUG_ENTRYPOINT' should be 0, 1 or 2. Has: ${DEBUG_ENTRYPOINT}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'DEBUG_ENTRYPOINT' has correct value: ${DEBUG_ENTRYPOINT}"
fi

DOCKER_LOGS="$( get_env_value "DOCKER_LOGS" )"
if [ "${DOCKER_LOGS}" != "0" ] && [ "${DOCKER_LOGS}" != "1" ]; then
	log_err "Variable 'DOCKER_LOGS' should be 0 or 1. Has: ${DOCKER_LOGS}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'DOCKER_LOGS' has correct value: ${DOCKER_LOGS}"
fi

DEVILBOX_PATH="$( get_env_value "DEVILBOX_PATH" )"
if [ ! -d "${DEVILBOX_PATH}" ]; then
	log_err "Variable 'DEVILBOX_PATH' directory does not exist: ${DEVILBOX_PATH}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'DEVILBOX_PATH' directory exists: ${DEVILBOX_PATH}"
fi

DEVILBOX_PATH_PERM="$( file_get_perm "${DEVILBOX_PATH}" )"
if [ "${DEVILBOX_PATH_PERM}" != "0755" ] && [ "${DEVILBOX_PATH_PERM}" != "0775" ] && [ "${DEVILBOX_PATH_PERM}" != "0777" ]; then
	log_err "Variable 'DEVILBOX_PATH' directory must be 0755, 0775 or 0777. Has: ${DEVILBOX_PATH_PERM}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'DEVILBOX_PATH' directory has correct permissions: ${DEVILBOX_PATH_PERM}"
fi

DEVILBOX_PATH_PERM="$( file_get_uid "${DEVILBOX_PATH}" )"
if [ "${DEVILBOX_PATH_PERM}" != "${MY_UID}" ]; then
	log_err "Variable 'DEVILBOX_PATH' directory uid must be ${MY_UID}. Has: ${DEVILBOX_PATH_PERM}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'DEVILBOX_PATH' diretory has correct uid: ${DEVILBOX_PATH_PERM}"
fi

DEVILBOX_PATH_PERM="$( file_get_gid "${DEVILBOX_PATH}" )"
if [ "${DEVILBOX_PATH_PERM}" != "${MY_GID}" ]; then
	log_err "Variable 'DEVILBOX_PATH' directory gid must be ${MY_GID}. Has: ${DEVILBOX_PATH_PERM}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'DEVILBOX_PATH' diretory has correct gid: ${DEVILBOX_PATH_PERM}"
fi

LOCAL_LISTEN_ADDR="$( get_env_value "LOCAL_LISTEN_ADDR" )"
if [ -n "${LOCAL_LISTEN_ADDR}" ]; then
	if ! echo "${LOCAL_LISTEN_ADDR}" | grep -E ':$' >/dev/null; then
		log_err "Variable 'LOCAL_LISTEN_ADDR' is not empty and missing trailing ':'"
		RET_CODE=$(( RET_CODE + 1))
		WRONG_ENV_FILES_VALUES=1
	elif ! echo "${LOCAL_LISTEN_ADDR}" | grep -E '^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+:$' >/dev/null; then
		log_err "Variable 'LOCAL_LISTEN_ADDR' has wrong value: '${LOCAL_LISTEN_ADDR}'"
		RET_CODE=$(( RET_CODE + 1))
		WRONG_ENV_FILES_VALUES=1
	else
		log_debug "Variable 'LOCAL_LISTEN_ADDR' has correct value: ${LOCAL_LISTEN_ADDR}"
	fi
else
	log_debug "Variable 'LOCAL_LISTEN_ADDR' has correct value: ${LOCAL_LISTEN_ADDR}"
fi

HOST_PATH_HTTPD_DATADIR="$( get_path "$( get_env_value "HOST_PATH_HTTPD_DATADIR" )" )"
if [ ! -d "${HOST_PATH_HTTPD_DATADIR}" ]; then
	log_err "Variable 'HOST_PATH_HTTPD_DATADIR' directory does not exist: ${HOST_PATH_HTTPD_DATADIR}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'HOST_PATH_HTTPD_DATADIR' directory exists: ${HOST_PATH_HTTPD_DATADIR}"
fi

HOST_PATH_HTTPD_DATADIR_PERM="$( file_get_perm "${HOST_PATH_HTTPD_DATADIR}" )"
if [ "${HOST_PATH_HTTPD_DATADIR_PERM}" != "0755" ] && [ "${HOST_PATH_HTTPD_DATADIR_PERM}" != "0775" ] && [ "${HOST_PATH_HTTPD_DATADIR_PERM}" != "0777" ]; then
	log_err "Variable 'HOST_PATH_HTTPD_DATADIR' directory must be 0755, 0775 or 0777. Has: ${HOST_PATH_HTTPD_DATADIR_PERM}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'HOST_PATH_HTTPD_DATADIR' directory has correct permissions: ${HOST_PATH_HTTPD_DATADIR_PERM}"
fi

HOST_PATH_HTTPD_DATADIR_PERM="$( file_get_uid "${HOST_PATH_HTTPD_DATADIR}" )"
if [ "${HOST_PATH_HTTPD_DATADIR_PERM}" != "${MY_UID}" ]; then
	log_err "Variable 'HOST_PATH_HTTPD_DATADIR' directory uid must be ${MY_UID}. Has: ${HOST_PATH_HTTPD_DATADIR_PERM}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'HOST_PATH_HTTPD_DATADIR' directory has correct uid: ${HOST_PATH_HTTPD_DATADIR_PERM}"
fi

HOST_PATH_HTTPD_DATADIR_PERM="$( file_get_gid "${HOST_PATH_HTTPD_DATADIR}" )"
if [ "${HOST_PATH_HTTPD_DATADIR_PERM}" != "${MY_GID}" ]; then
	log_err "Variable 'HOST_PATH_HTTPD_DATADIR' directory gid must be ${MY_GID}. Has: ${HOST_PATH_HTTPD_DATADIR_PERM}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'HOST_PATH_HTTPD_DATADIR' directory has correct gid: ${HOST_PATH_HTTPD_DATADIR_PERM}"
fi

PHP_SERVER="$( get_env_value "PHP_SERVER" )"
if ! grep -E "^#?PHP_SERVER=${PHP_SERVER}\$" env-example >/dev/null; then
	log_err "Variable 'PHP_SERVER' has wrong value: ${PHP_SERVER}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'PHP_SERVER' has correct value: ${PHP_SERVER}"
fi

HTTPD_SERVER="$( get_env_value "HTTPD_SERVER" )"
if ! grep -E "^#?HTTPD_SERVER=${HTTPD_SERVER}\$" env-example >/dev/null; then
	log_err "Variable 'HTTPD_SERVER' has wrong value: ${HTTPD_SERVER}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'HTTPD_SERVER' has correct value: ${HTTPD_SERVER}"
fi

MYSQL_SERVER="$( get_env_value "MYSQL_SERVER" )"
if ! grep -E "^#?MYSQL_SERVER=${MYSQL_SERVER}\$" env-example >/dev/null; then
	log_err "Variable 'MYSQL_SERVER' has wrong value: ${MYSQL_SERVER}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'MYSQL_SERVER' has correct value: ${MYSQL_SERVER}"
fi

PGSQL_SERVER="$( get_env_value "PGSQL_SERVER" )"
if ! grep -E "^#?PGSQL_SERVER=${PGSQL_SERVER}\$" env-example >/dev/null; then
	log_err "Variable 'PGSQL_SERVER' has wrong value: ${PGSQL_SERVER}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'PGSQL_SERVER' has correct value: ${PGSQL_SERVER}"
fi

REDIS_SERVER="$( get_env_value "REDIS_SERVER" )"
if ! grep -E "^#?REDIS_SERVER=${REDIS_SERVER}\$" env-example >/dev/null; then
	log_err "Variable 'REDIS_SERVER' has wrong value: ${REDIS_SERVER}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'REDIS_SERVER' has correct value: ${REDIS_SERVER}"
fi

MEMCD_SERVER="$( get_env_value "MEMCD_SERVER" )"
if ! grep -E "^#?MEMCD_SERVER=${MEMCD_SERVER}\$" env-example >/dev/null; then
	log_err "Variable 'MEMCD_SERVER' has wrong value: ${MEMCD_SERVER}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'MEMCD_SERVER' has correct value: ${MEMCD_SERVER}"
fi

MONGO_SERVER="$( get_env_value "MONGO_SERVER" )"
if ! grep -E "^#?MONGO_SERVER=${MONGO_SERVER}\$" env-example >/dev/null; then
	log_err "Variable 'MONGO_SERVER' has wrong value: ${MONGO_SERVER}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'MONGO_SERVER' has correct value: ${MONGO_SERVER}"
fi

NEW_UID="$( get_env_value "NEW_UID" )"
if [ "${NEW_UID}" != "${MY_UID}" ]; then
	log_err "Variable 'NEW_UID' has wrong value: '${NEW_UID}'. Should have: ${MY_UID}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'NEW_UID' has correct value: '${NEW_UID}'"
fi

NEW_GID="$( get_env_value "NEW_GID" )"
if [ "${NEW_GID}" != "${MY_GID}" ]; then
	log_err "Variable 'NEW_GID' has wrong value: '${NEW_GID}'. Should have: ${MY_GID}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'NEW_GID' has correct value: '${NEW_GID}'"
fi

TLD_SUFFIX="$( get_env_value "TLD_SUFFIX" )"
TLD_SUFFIX_BLACKLIST="dev|com|org|net|int|edu|de"
if echo "${TLD_SUFFIX}" | grep -E "^(${TLD_SUFFIX_BLACKLIST})\$" >/dev/null; then
	log_err "Variable 'TLD_SUFFX' should not be set to '${TLD_SUFFIX}'. It is a real tld domain."
	log_err "All DNS requests will be intercepted to this tld domain and re-routed to the HTTP container."
	log_info "Consider using a subdomain value of e.g.: 'mydev.${TLD_SUFFIX}' instead."
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
elif [ "${TLD_SUFFIX}" = "localhost" ]; then
	log_err "Variable 'TLD_SUFFX' should not be set to '${TLD_SUFFIX}'. It is a loopback address."
	log_info "See: https://tools.ietf.org/html/draft-west-let-localhost-be-localhost-06"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
else
	log_debug "Variable 'TLD_SUFFIX' has correct value: '${TLD_SUFFIX}'"
fi

if [ "${WRONG_ENV_FILES_VALUES}" = "0" ]; then
	log_ok "All .env file variables have correct values"
fi


#--------------------------------------------------------------------------------------------------
# Ensure cfg/ and log/ directories exist
#--------------------------------------------------------------------------------------------------
print_head_1 "Checking required Devilbox core directories exist"

# /cfg/php-fpm-VERSION
DIR_MISSING=0
while read -r php_version; do
	if [ ! -d "cfg/php-fpm-${php_version}" ]; then
		log_err "Directory 'cfg/php-fpm-${php_version}' is missing"
		RET_CODE=$(( RET_CODE + 1))
		DIR_MISSING=1
	else
		log_debug "Directory 'cfg/php-fpm-${php_version}' is present"
	fi
done < <(grep -E '^#?PHP_SERVER=' env-example  | awk -F'=' '{print $2}')
if [ "${DIR_MISSING}" = "0" ]; then
	log_ok "All PHP cfg/ sub directories are present"
fi

# /log/php-fpm-VERSION
DIR_MISSING=0
while read -r php_version; do
	if [ ! -d "log/php-fpm-${php_version}" ]; then
		log_err "Directory 'log/php-fpm-${php_version}' is missing"
		RET_CODE=$(( RET_CODE + 1))
		DIR_MISSING=1
	else
		log_debug "Directory 'log/php-fpm-${php_version}' is present"
	fi
done < <(grep -E '^#?PHP_SERVER=' env-example  | awk -F'=' '{print $2}')
if [ "${DIR_MISSING}" = "0" ]; then
	log_ok "All PHP log/ sub directories are present"
fi

# /cfg/apache|nginx-VERSION
DIR_MISSING=0
while read -r httpd_version; do
	if [ ! -d "cfg/${httpd_version}" ]; then
		log_err "Directory 'cfg/${httpd_version}' is missing"
		RET_CODE=$(( RET_CODE + 1))
		DIR_MISSING=1
	else
		log_debug "Directory 'cfg/${httpd_version}' is present"
	fi
done < <(grep -E '^#?HTTPD_SERVER=' env-example  | awk -F'=' '{print $2}')
if [ "${DIR_MISSING}" = "0" ]; then
	log_ok "All HTTPD cfg/ sub directories are present"
fi

# /log/apache|nginx-VERSION
DIR_MISSING=0
while read -r httpd_version; do
	if [ ! -d "log/${httpd_version}" ]; then
		log_err "Directory 'log/${httpd_version}' is missing"
		RET_CODE=$(( RET_CODE + 1))
		DIR_MISSING=1
	else
		log_debug "Directory 'log/${httpd_version}' is present"
	fi
done < <(grep -E '^#?HTTPD_SERVER=' env-example  | awk -F'=' '{print $2}')
if [ "${DIR_MISSING}" = "0" ]; then
	log_ok "All HTTPD log/ sub directories are present"
fi


#--------------------------------------------------------------------------------------------------
# Devilbox Directory permissions
#--------------------------------------------------------------------------------------------------
print_head_1 "Checking devilbox core directory permissions"

DEVILBOX_DIRS=(
	"autostart"
	"bash"
	"ca"
	"cfg"
	"compose"
	"log"
	"supervisor"
)

# Check allowed directory permissions: 0755 0775 0777
DEVILBOX_DIR_PERM_WRONG=0
for search_dir in "${DEVILBOX_DIRS[@]}"; do
	while read -r my_dir; do
		PERM="$( file_get_perm "${my_dir}" )"
		if [ "${PERM}" != "0755" ] && [ "${PERM}" != "0775" ] && [ "${PERM}" != "0777" ]; then
			log_err "Directory '${my_dir}' should have 0755, 0775 or 0777 permissions. Has: ${PERM} permissions"
			RET_CODE=$(( RET_CODE + 1))
			DEVILBOX_DIR_PERM_WRONG=1
		else
			log_debug "Directory '${my_dir}' has correct permissions: ${PERM}"
		fi
	done < <(find "${search_dir}" -type d)
done
if [ "${DEVILBOX_DIR_PERM_WRONG}" = "0" ]; then
	log_ok "All devilbox directories have correct permissions"
fi

# Check allowed uid
DEVILBOX_DIR_PERM_WRONG=0
for search_dir in "${DEVILBOX_DIRS[@]}"; do
	while read -r my_dir; do
		PERM="$( file_get_uid "${my_dir}" )"
		if [ "${PERM}" != "${MY_UID}" ]; then
			log_err "Directory '${my_dir}' should have uid '${MY_UID}' Has: '${PERM}'"
			RET_CODE=$(( RET_CODE + 1))
			DEVILBOX_DIR_PERM_WRONG=1
		else
			log_debug "Directory '${my_dir}' has correct uid: ${PERM}"
		fi
	done < <(find "${search_dir}" -type d)
done
if [ "${DEVILBOX_DIR_PERM_WRONG}" = "0" ]; then
	log_ok "All devilbox directories have correct uid"
fi

# Check allowed gid
DEVILBOX_DIR_PERM_WRONG=0
for search_dir in "${DEVILBOX_DIRS[@]}"; do
	while read -r my_dir; do
		PERM="$( file_get_gid "${my_dir}" )"
		if [ "${PERM}" != "${MY_GID}" ]; then
			log_err "Directory '${my_dir}' should have gid '${MY_GID}' Has: '${PERM}'"
			RET_CODE=$(( RET_CODE + 1))
			DEVILBOX_DIR_PERM_WRONG=1
		else
			log_debug "Directory '${my_dir}' has correct gid: ${PERM}"
		fi
	done < <(find "${search_dir}" -type d)
done
if [ "${DEVILBOX_DIR_PERM_WRONG}" = "0" ]; then
	log_ok "All devilbox directories have correct gid"
fi


#--------------------------------------------------------------------------------------------------
# Devilbox File permissions
#--------------------------------------------------------------------------------------------------
print_head_1 "Checking devilbox core file permissions"

DEVILBOX_DIRS=(
	"autostart"
	"ca"
	"cfg"
	"compose"
	"supervisor"
)

# Check allowed directory permissions: 0644 0664 0666
DEVILBOX_DIR_PERM_WRONG=0
for search_file in "${DEVILBOX_DIRS[@]}"; do
	while read -r my_file; do
		PERM="$( file_get_perm "${my_file}" )"
		# Private CA file
		if [ "${my_file}" = "ca/devilbox-ca.key" ]; then
			if [ "${PERM}" != "0600" ]; then
				log_err "File '${my_file}' should have 0600 permissions. Has: ${PERM} permissions"
				RET_CODE=$(( RET_CODE + 1))
				DEVILBOX_DIR_PERM_WRONG=1
			else
				log_debug "File '${my_file}' has correct permissions: ${PERM}"
			fi
		# Executable files
		elif echo "${my_file}" | grep -E '.+\.sh(-example)?$' >/dev/null; then
			if [ "${PERM}" != "0755" ] && [ "${PERM}" != "0775" ] && [ "${PERM}" != "0777" ]; then
				log_err "File '${my_file}' should have 0755, 0775 or 0777 permissions. Has: ${PERM} permissions"
				RET_CODE=$(( RET_CODE + 1))
				DEVILBOX_DIR_PERM_WRONG=1
			else
				log_debug "File '${my_file}' has correct permissions: ${PERM}"
			fi
		# All other files
		else
			if [ "${PERM}" != "0644" ] && [ "${PERM}" != "0664" ] && [ "${PERM}" != "0666" ]; then
				log_err "File '${my_file}' should have 0644, 0664 or 0666 permissions. Has: ${PERM} permissions"
				RET_CODE=$(( RET_CODE + 1))
				DEVILBOX_DIR_PERM_WRONG=1
			else
				log_debug "File '${my_file}' has correct permissions: ${PERM}"
			fi
		fi
	done < <(find "${search_file}" -type f)
done
if [ "${DEVILBOX_DIR_PERM_WRONG}" = "0" ]; then
	log_ok "All devilbox files have correct permissions"
fi

# Check allowed uid
DEVILBOX_DIR_PERM_WRONG=0
for search_file in "${DEVILBOX_DIRS[@]}"; do
	while read -r my_file; do
		PERM="$( file_get_uid "${my_file}" )"
		if [ "${PERM}" != "${MY_UID}" ]; then
			log_err "File '${my_file}' should have uid '${MY_UID}' Has: '${PERM}'"
			RET_CODE=$(( RET_CODE + 1))
			DEVILBOX_DIR_PERM_WRONG=1
		else
			log_debug "File '${my_file}' has correct uid: ${PERM}"
		fi
	done < <(find "${search_file}" -type f)
done
if [ "${DEVILBOX_DIR_PERM_WRONG}" = "0" ]; then
	log_ok "All devilbox files have correct uid"
fi

# Check allowed gid
DEVILBOX_DIR_PERM_WRONG=0
for search_file in "${DEVILBOX_DIRS[@]}"; do
	while read -r my_file; do
		PERM="$( file_get_gid "${my_file}" )"
		if [ "${PERM}" != "${MY_GID}" ]; then
			log_err "File '${my_file}' should have gid '${MY_GID}' Has: '${PERM}'"
			RET_CODE=$(( RET_CODE + 1))
			DEVILBOX_DIR_PERM_WRONG=1
		else
			log_debug "File '${my_file}' has correct gid: ${PERM}"
		fi
	done < <(find "${search_file}" -type f)
done
if [ "${DEVILBOX_DIR_PERM_WRONG}" = "0" ]; then
	log_ok "All devilbox files have correct gid"
fi


#--------------------------------------------------------------------------------------------------
# Check projects permissions
#--------------------------------------------------------------------------------------------------
print_head_1 "Checking projects permissions"

HOST_PATH_HTTPD_DATADIR="$( get_path "$( get_env_value "HOST_PATH_HTTPD_DATADIR" )" )"

DATA_DIR_PERM_WRONG=0
while read -r project; do
	PERM="$( file_get_perm "${project}" )"
	if [ "${PERM}" != "0755" ] && [ "${PERM}" != "0775" ] && [ "${PERM}" != "0777" ]; then
		log_err "Directory '${project}' should have 0755, 0775 or 0777 permissions. Has: ${PERM} permissions"
		RET_CODE=$(( RET_CODE + 1))
		DATA_DIR_PERM_WRONG=1
	else
		log_debug "Directory '${project}' has correct permissions: ${PERM}"
	fi
done < <(get_sub_dirs_level_1 "${HOST_PATH_HTTPD_DATADIR}")
if [ "${DATA_DIR_PERM_WRONG}" = "0" ]; then
	log_ok "All project dirs have correct permissions"
fi

DATA_DIR_PERM_WRONG=0
while read -r project; do
	PERM="$( file_get_uid "${project}" )"
	if [ "${PERM}" != "${MY_UID}" ]; then
		log_err "Directory '${project}' should have uid '${MY_UID}' Has: '${PERM}'"
		RET_CODE=$(( RET_CODE + 1))
		DATA_DIR_PERM_WRONG=1
	else
		log_debug "Directory '${project}' has correct uid: ${PERM}"
	fi
done < <(get_sub_dirs_level_1 "${HOST_PATH_HTTPD_DATADIR}")
if [ "${DATA_DIR_PERM_WRONG}" = "0" ]; then
	log_ok "All project dirs have correct uid"
fi

DATA_DIR_PERM_WRONG=0
while read -r project; do
	PERM="$( file_get_gid "${project}" )"
	if [ "${PERM}" != "${MY_GID}" ]; then
		log_err "Directory '${project}' should have gid '${MY_GID}' Has: '${PERM}'"
		RET_CODE=$(( RET_CODE + 1))
		DATA_DIR_PERM_WRONG=1
	else
		log_debug "Directory '${project}' has correct gid: ${PERM}"
	fi
done < <(get_sub_dirs_level_1 "${HOST_PATH_HTTPD_DATADIR}")
if [ "${DATA_DIR_PERM_WRONG}" = "0" ]; then
	log_ok "All project dirs have correct gid"
fi


#--------------------------------------------------------------------------------------------------
# Check projects settings
#--------------------------------------------------------------------------------------------------
print_head_1 "Checking projects settings"

HOST_PATH_HTTPD_DATADIR="$( get_path "$( get_env_value "HOST_PATH_HTTPD_DATADIR" )" )"

TLD_SUFFIX="$( get_env_value "TLD_SUFFIX" )"
DNS_RECORD_WRONG=0
while read -r project; do
	VHOST="$( basename "${project}" ).${TLD_SUFFIX}"
	if ! validate_dns "${VHOST}"; then
		log_err "Project '${VHOST}' has no valid DNS record"
		RET_CODE=$(( RET_CODE + 1))
		DNS_RECORD_WRONG=1
	else
		log_debug "Project '${VHOST}' has valid DNS record"
	fi
done < <(get_sub_dirs_level_1 "${HOST_PATH_HTTPD_DATADIR}")
if [ "${DNS_RECORD_WRONG}" = "0" ]; then
	log_ok "All projects have valid DNS records"
fi

HTTPD_DOCROOT_DIR="$( get_env_value "HTTPD_DOCROOT_DIR" )"
DOCROOT_WRONG=0
while read -r project; do
	if [ ! -d "${project}/${HTTPD_DOCROOT_DIR}" ]; then
		log_err "Missing HTTPD_DOCROOT_DIR '${HTTPD_DOCROOT_DIR}' in: ${project}"
		RET_CODE=$(( RET_CODE + 1))
		DOCROOT_WRONG=1
	else
		log_debug "HTTPD_DOCROOT_DIR '${HTTPD_DOCROOT_DIR}' present in: ${project}"
	fi
done < <(get_sub_dirs_level_1 "${HOST_PATH_HTTPD_DATADIR}")
if [ "${DOCROOT_WRONG}" = "0" ]; then
	log_ok "All projects have valid HTTPD_DOCROOT_DIR"
fi


#--------------------------------------------------------------------------------------------------
# Check Customizations
#--------------------------------------------------------------------------------------------------
print_head_1 "Checking customizations"

CUSTOMIZATIONS=0

# vhost-gen
HOST_PATH_HTTPD_DATADIR="$( get_path "$( get_env_value "HOST_PATH_HTTPD_DATADIR" )" )"
HTTPD_TEMPLATE_DIR="$( get_env_value "HTTPD_TEMPLATE_DIR" )"
while read -r project; do
	if [ -f "${project}/${HTTPD_TEMPLATE_DIR}/apache22.yml" ]; then
		log_note "[vhost-gen]  Custom Apache 2.2 vhost-gen config present in: ${project}/"
		CUSTOMIZATIONS=$(( CUSTOMIZATIONS + 1 ))
	elif [ -f "${project}/${HTTPD_TEMPLATE_DIR}/apache24.yml" ]; then
		log_note "[vhost-gen]  Custom Apache 2.4 vhost-gen config present in: ${project}/"
		CUSTOMIZATIONS=$(( CUSTOMIZATIONS + 1 ))
	elif [ -f "${project}/${HTTPD_TEMPLATE_DIR}/nginx.yml" ]; then
		log_note "[vhost-gen]  Custom Nginx vhost-gen config present in: ${project}/"
		CUSTOMIZATIONS=$(( CUSTOMIZATIONS + 1 ))
	else
		log_debug "[vhost-gen]  No custom configuration for: ${project}/"
	fi
done < <(get_sub_dirs_level_1 "${HOST_PATH_HTTPD_DATADIR}")

# docker-compose.override.yml
if [ -f "docker-compose.override.yml" ]; then
	log_note "[docker]     Custom docker-compose.override.yml present"
	CUSTOMIZATIONS=$(( CUSTOMIZATIONS + 1 ))
else
	log_debug "[docker]     No custom docker-compose.override.yml present"
fi

# cfg/HTTPD/
while read -r httpd; do
	if find "cfg/${httpd}" | grep -E '\.conf$' >/dev/null; then
		log_note "[httpd]      Custom config present in cfg/${httpd}/"
		CUSTOMIZATIONS=$(( CUSTOMIZATIONS + 1 ))
	else
		log_debug "[httpd]      No custom config present in cfg/${httpd}/"
	fi
done < <(grep -E '^#?HTTPD_SERVER=' env-example  | awk -F'=' '{print $2}')

# cfg/php-ini-${version}/
while read -r php_version; do
	if find "cfg/php-ini-${php_version}" | grep -E '\.ini$' >/dev/null; then
		log_note "[php.ini]    Custom config present in cfg/php-ini-${php_version}/"
		CUSTOMIZATIONS=$(( CUSTOMIZATIONS + 1 ))
	else
		log_debug "[php.ini]    No custom config present in cfg/php-ini-${php_version}/"
	fi
done < <(grep -E '^#?PHP_SERVER=' env-example  | awk -F'=' '{print $2}')

# cfg/php-fpm-${version}/
while read -r php_version; do
	if find "cfg/php-fpm-${php_version}" | grep -E '\.conf$' >/dev/null; then
		log_note "[php-fpm]    Custom config present in cfg/php-fpm-${php_version}/"
		CUSTOMIZATIONS=$(( CUSTOMIZATIONS + 1 ))
	else
		log_debug "[php-fpm]    No custom config present in cfg/php-fpm-${php_version}/"
	fi
done < <(grep -E '^#?PHP_SERVER=' env-example  | awk -F'=' '{print $2}')

# cfg/MYSQL/
while read -r mysql; do
	if find "cfg/${mysql}" | grep -E '\.cnf$' >/dev/null; then
		log_note "[mysql]      Custom config present in cfg/${mysql}/"
		CUSTOMIZATIONS=$(( CUSTOMIZATIONS + 1 ))
	else
		log_debug "[mysql]      No custom config present in cfg/${mysql}/"
	fi
done < <(grep -E '^#?MYSQL_SERVER=' env-example  | awk -F'=' '{print $2}')

# cfg/php-startup-${version}/
while read -r php_version; do
	if find "cfg/php-startup-${php_version}" | grep -E '\.sh$' >/dev/null; then
		log_note "[startup]    Custom script present in cfg/php-startup-${php_version}/"
		CUSTOMIZATIONS=$(( CUSTOMIZATIONS + 1 ))
	else
		log_debug "[startup]    No custom script present in cfg/php-startup-${php_version}/"
	fi
done < <(grep -E '^#?PHP_SERVER=' env-example  | awk -F'=' '{print $2}')

# autostart/
if find "autostart" | grep -E '\.sh$' >/dev/null; then
	log_note "[startup]    Custom script present in autostart/"
	CUSTOMIZATIONS=$(( CUSTOMIZATIONS + 1 ))
else
	log_debug "[startup]    No custom script present in autostart/"
fi

# supervisor/
if find "supervisor" | grep -E '\.conf$' >/dev/null; then
	log_note "[supervisor] Custom config present in supervisor/"
	CUSTOMIZATIONS=$(( CUSTOMIZATIONS + 1 ))
else
	log_debug "[supervisor] No custom config present in supervisor/"
fi

# bash/
if find "bash" | grep -E '\.sh$' >/dev/null; then
	log_note "[bash]      Custom script present in bash/"
	CUSTOMIZATIONS=$(( CUSTOMIZATIONS + 1 ))
else
	log_debug "[bash]      No custom script present in bash/"
fi

# Total?
if [ "${CUSTOMIZATIONS}" = "0" ]; then
	log_info "No custom configurations applied"
fi


#--------------------------------------------------------------------------------------------------
# Summary
#--------------------------------------------------------------------------------------------------
print_head_1 "SUMMARY"

if [ "${RET_CODE}" -gt "0" ]; then
	log_err "Found ${RET_CODE} error(s)"
	log_err "Devilbox might not work properly"
	log_err "Fix the issues before submitting a bug report"
	if [ "${CUSTOMIZATIONS}" -gt "0" ]; then
		log_note "${CUSTOMIZATIONS} custom configurations applied. If you encounter issues, reset them first."
	else
		log_info "No custom configurations applied"
	fi
	log_info "Ensure to run 'docker-compose stop; docker-compose rm -f' on .env changes or custom configs"
	exit 1
else
	log_ok "Found no errors"
	if [ "${CUSTOMIZATIONS}" -gt "0" ]; then
		log_note "${CUSTOMIZATIONS} custom configurations applied. If you encounter issues, reset them first."
	else
		log_info "No custom configurations applied"
	fi
	log_info "Ensure to run 'docker-compose stop; docker-compose rm -f' on .env changes or custom configs"
	exit 0
fi
