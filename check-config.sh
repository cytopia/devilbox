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


#--------------------------------------------------------------------------------------------------
# Functions
#--------------------------------------------------------------------------------------------------

###
### Logger functions
###
log_err() {
	>&2 printf "\\e[1;31m[ERR]  %s\\e[0m\\n" "${1}"
}

log_info() {
	printf "\\e[;34m[INFO] %s\\e[0m\\n" "${1}"
}

log_ok() {
	printf "\\e[;32m[SUCC] %s\\e[0m\\n" "${1}"
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
	stat -c '%u' "${1}"
}

file_get_gid() {
	stat -c '%g' "${1}"
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


#--------------------------------------------------------------------------------------------------
# Check git
#--------------------------------------------------------------------------------------------------
print_head_1 "Checking git"

GIT_STATUS="$( git status -s )"
if [ -z "${GIT_STATUS}" ]; then
	log_info "git is clean"
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
fi
if [ -r .env ]; then
	log_ok ".env file is readable"
else
	log_err ".env file is not readable"
	RET_CODE=$(( RET_CODE + 1))
fi

# Ensure all variables exist in .env file
ENV_VAR_MISSING=0
while read -r env_var; do
	if ! grep -E "^${env_var}=" .env >/dev/null; then
		log_err "Variable '${env_var}' missing in .env file"
		RET_CODE=$(( RET_CODE + 1))
		ENV_VAR_MISSING=1
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
DEBUG_COMPOSE_ENTRYPOINT="$( grep -E '^DEBUG_COMPOSE_ENTRYPOINT=' .env | awk -F'=' '{print $2}' )"
if [ "${DEBUG_COMPOSE_ENTRYPOINT}" != "0" ] && [ "${DEBUG_COMPOSE_ENTRYPOINT}" != "1" ] && [ "${DEBUG_COMPOSE_ENTRYPOINT}" != "2" ]; then
	log_err "Variable 'DEBUG_COMPOSE_ENTRYPOINT' should be 0, 1 or 2. Has: ${DEBUG_COMPOSE_ENTRYPOINT}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi

DOCKER_LOGS="$( grep -E '^DOCKER_LOGS=' .env | awk -F'=' '{print $2}' )"
if [ "${DOCKER_LOGS}" != "0" ] && [ "${DOCKER_LOGS}" != "1" ]; then
	log_err "Variable 'DOCKER_LOGS' should be 0 or 1. Has: ${DOCKER_LOGS}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi

DEVILBOX_PATH="$( grep -E '^DEVILBOX_PATH=' .env | awk -F'=' '{print $2}' )"
if [ ! -d "${DEVILBOX_PATH}" ]; then
	log_err "Variable 'DEVILBOX_PATH' directory does not exist: ${DEVILBOX_PATH}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi
DEVILBOX_PATH_PERM="$( file_get_perm "${DEVILBOX_PATH}" )"
if [ "${DEVILBOX_PATH_PERM}" != "0755" ] && [ "${DEVILBOX_PATH_PERM}" != "0775" ] && [ "${DEVILBOX_PATH_PERM}" != "0777" ]; then
	log_err "Variable 'DEVILBOX_PATH' directory must be 0755, 0775 or 0777. Has: ${DEVILBOX_PATH_PERM}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi
DEVILBOX_PATH_PERM="$( file_get_uid "${DEVILBOX_PATH}" )"
if [ "${DEVILBOX_PATH_PERM}" != "${MY_UID}" ]; then
	log_err "Variable 'DEVILBOX_PATH' directory uid must be ${MY_UID}. Has: ${DEVILBOX_PATH_PERM}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi
DEVILBOX_PATH_PERM="$( file_get_gid "${DEVILBOX_PATH}" )"
if [ "${DEVILBOX_PATH_PERM}" != "${MY_GID}" ]; then
	log_err "Variable 'DEVILBOX_PATH' directory gid must be ${MY_GID}. Has: ${DEVILBOX_PATH_PERM}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi

if [ "${WRONG_ENV_FILES_VALUES}" = "0" ]; then
	log_ok "All .env file variables have correct values"
fi

HOST_PATH_HTTPD_DATADIR="$( grep -E '^HOST_PATH_HTTPD_DATADIR=' .env | awk -F'=' '{print $2}' )"
if [ ! -d "${HOST_PATH_HTTPD_DATADIR}" ]; then
	log_err "Variable 'HOST_PATH_HTTPD_DATADIR' directory does not exist: ${HOST_PATH_HTTPD_DATADIR}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi
HOST_PATH_HTTPD_DATADIR_PERM="$( file_get_perm "${HOST_PATH_HTTPD_DATADIR}" )"
if [ "${HOST_PATH_HTTPD_DATADIR_PERM}" != "0755" ] && [ "${HOST_PATH_HTTPD_DATADIR_PERM}" != "0775" ] && [ "${HOST_PATH_HTTPD_DATADIR_PERM}" != "0777" ]; then
	log_err "Variable 'HOST_PATH_HTTPD_DATADIR' directory must be 0755, 0775 or 0777. Has: ${HOST_PATH_HTTPD_DATADIR_PERM}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi
HOST_PATH_HTTPD_DATADIR_PERM="$( file_get_uid "${HOST_PATH_HTTPD_DATADIR}" )"
if [ "${HOST_PATH_HTTPD_DATADIR_PERM}" != "${MY_UID}" ]; then
	log_err "Variable 'HOST_PATH_HTTPD_DATADIR' directory uid must be ${MY_UID}. Has: ${HOST_PATH_HTTPD_DATADIR_PERM}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi
HOST_PATH_HTTPD_DATADIR_PERM="$( file_get_gid "${HOST_PATH_HTTPD_DATADIR}" )"
if [ "${HOST_PATH_HTTPD_DATADIR_PERM}" != "${MY_GID}" ]; then
	log_err "Variable 'HOST_PATH_HTTPD_DATADIR' directory gid must be ${MY_GID}. Has: ${HOST_PATH_HTTPD_DATADIR_PERM}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi

PHP_SERVER="$( grep -E '^PHP_SERVER=' .env | awk -F'=' '{print $2}' )"
if ! grep -E "^#?PHP_SERVER=${PHP_SERVER}\$" env-example >/dev/null; then
	log_err "Variable 'PHP_SERVER' has wrong value: ${PHP_SERVER}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi
HTTPD_SERVER="$( grep -E '^HTTPD_SERVER=' .env | awk -F'=' '{print $2}' )"
if ! grep -E "^#?HTTPD_SERVER=${HTTPD_SERVER}\$" env-example >/dev/null; then
	log_err "Variable 'HTTPD_SERVER' has wrong value: ${HTTPD_SERVER}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi
MYSQL_SERVER="$( grep -E '^MYSQL_SERVER=' .env | awk -F'=' '{print $2}' )"
if ! grep -E "^#?MYSQL_SERVER=${MYSQL_SERVER}\$" env-example >/dev/null; then
	log_err "Variable 'MYSQL_SERVER' has wrong value: ${MYSQL_SERVER}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi
PGSQL_SERVER="$( grep -E '^PGSQL_SERVER=' .env | awk -F'=' '{print $2}' )"
if ! grep -E "^#?PGSQL_SERVER=${PGSQL_SERVER}\$" env-example >/dev/null; then
	log_err "Variable 'PGSQL_SERVER' has wrong value: ${PGSQL_SERVER}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi
REDIS_SERVER="$( grep -E '^REDIS_SERVER=' .env | awk -F'=' '{print $2}' )"
if ! grep -E "^#?REDIS_SERVER=${REDIS_SERVER}\$" env-example >/dev/null; then
	log_err "Variable 'REDIS_SERVER' has wrong value: ${REDIS_SERVER}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi
MEMCD_SERVER="$( grep -E '^MEMCD_SERVER=' .env | awk -F'=' '{print $2}' )"
if ! grep -E "^#?MEMCD_SERVER=${MEMCD_SERVER}\$" env-example >/dev/null; then
	log_err "Variable 'MEMCD_SERVER' has wrong value: ${MEMCD_SERVER}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi
MONGO_SERVER="$( grep -E '^MONGO_SERVER=' .env | awk -F'=' '{print $2}' )"
if ! grep -E "^#?MONGO_SERVER=${MONGO_SERVER}\$" env-example >/dev/null; then
	log_err "Variable 'MONGO_SERVER' has wrong value: ${MONGO_SERVER}"
	RET_CODE=$(( RET_CODE + 1))
	WRONG_ENV_FILES_VALUES=1
fi

if [ "${WRONG_ENV_FILES_VALUES}" = "0" ]; then
	log_ok "All .env file variables have correct values"
fi



#--------------------------------------------------------------------------------------------------
# Ensure cfg/, mod/ and log/ directories exist
#--------------------------------------------------------------------------------------------------
print_head_1 "Checking required directories"

# /cfg/php-fpm-VERSION
DIR_MISSING=0
while read -r php_version; do
	if [ ! -d "cfg/php-fpm-${php_version}" ]; then
		log_err "Directory 'cfg/php-fpm-${php_version}' is missing"
		RET_CODE=$(( RET_CODE + 1))
		DIR_MISSING=1
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
	fi
done < <(grep -E '^#?PHP_SERVER=' env-example  | awk -F'=' '{print $2}')
if [ "${DIR_MISSING}" = "0" ]; then
	log_ok "All PHP log/ sub directories are present"
fi

# /mod/php-fpm-VERSION
DIR_MISSING=0
while read -r php_version; do
	if [ ! -d "mod/php-fpm-${php_version}" ]; then
		log_err "Directory 'mod/php-fpm-${php_version}' is missing"
		RET_CODE=$(( RET_CODE + 1))
		DIR_MISSING=1
	fi
done < <(grep -E '^#?PHP_SERVER=' env-example  | awk -F'=' '{print $2}')
if [ "${DIR_MISSING}" = "0" ]; then
	log_ok "All PHP mod/ sub directories are present"
fi

# /cfg/apache|nginx-VERSION
DIR_MISSING=0
while read -r httpd_version; do
	if [ ! -d "cfg/${httpd_version}" ]; then
		log_err "Directory 'cfg/${httpd_version}' is missing"
		RET_CODE=$(( RET_CODE + 1))
		DIR_MISSING=1
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
	fi
done < <(grep -E '^#?HTTPD_SERVER=' env-example  | awk -F'=' '{print $2}')
if [ "${DIR_MISSING}" = "0" ]; then
	log_ok "All HTTPD log/ sub directories are present"
fi


#--------------------------------------------------------------------------------------------------
# Directory permissions
#--------------------------------------------------------------------------------------------------
print_head_1 "Checking directory permissions"

DEVILBOX_DIRS=(
	"autostart"
	"backups"
	"bash"
	"ca"
	"cfg"
	"compose"
	"log"
	"mod"
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
		fi
	done < <(find "${search_dir}" -type d)
done
if [ "${DEVILBOX_DIR_PERM_WRONG}" = "0" ]; then
	log_ok "All devilbox directories have correct gid"
fi


#--------------------------------------------------------------------------------------------------
# File permissions
#--------------------------------------------------------------------------------------------------
print_head_1 "Checking file permissions"

DEVILBOX_DIRS=(
	"autostart"
	"backups"
	"ca"
	"cfg"
	"compose"
	"mod"
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
			fi
		# Executable files
		elif echo "${my_file}" | grep -E '.+\.sh(-example)?$' >/dev/null; then
			if [ "${PERM}" != "0755" ] && [ "${PERM}" != "0775" ] && [ "${PERM}" != "0777" ]; then
				log_err "File '${my_file}' should have 0755, 0775 or 0777 permissions. Has: ${PERM} permissions"
				RET_CODE=$(( RET_CODE + 1))
				DEVILBOX_DIR_PERM_WRONG=1
			fi
		# All other files
		else
			if [ "${PERM}" != "0644" ] && [ "${PERM}" != "0664" ] && [ "${PERM}" != "0666" ]; then
				log_err "File '${my_file}' should have 0644, 0664 or 0666 permissions. Has: ${PERM} permissions"
				RET_CODE=$(( RET_CODE + 1))
				DEVILBOX_DIR_PERM_WRONG=1
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
		fi
	done < <(find "${search_file}" -type f)
done
if [ "${DEVILBOX_DIR_PERM_WRONG}" = "0" ]; then
	log_ok "All devilbox files have correct gid"
fi


#--------------------------------------------------------------------------------------------------
# Check projects
#--------------------------------------------------------------------------------------------------
print_head_1 "Checking projects"

HOST_PATH_HTTPD_DATADIR="$( grep -E '^HOST_PATH_HTTPD_DATADIR=' .env | awk -F'=' '{print $2}' )"

DATA_DIR_PERM_WRONG=0
while read -r project; do
	PERM="$( file_get_perm "${project}" )"
	if [ "${PERM}" != "0755" ] && [ "${PERM}" != "0775" ] && [ "${PERM}" != "0777" ]; then
		log_err "Directory '${project}' should have 0755, 0775 or 0777 permissions. Has: ${PERM} permissions"
		RET_CODE=$(( RET_CODE + 1))
		DATA_DIR_PERM_WRONG=1
	fi
done < <(find "${HOST_PATH_HTTPD_DATADIR}" -type d | grep -Ev "${HOST_PATH_HTTPD_DATADIR}/.+/.+")
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
	fi
done < <(find "${HOST_PATH_HTTPD_DATADIR}" -type d | grep -Ev "${HOST_PATH_HTTPD_DATADIR}/.+/.+")
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
	fi
done < <(find "${HOST_PATH_HTTPD_DATADIR}" -type d | grep -Ev "${HOST_PATH_HTTPD_DATADIR}/.+/.+")
if [ "${DATA_DIR_PERM_WRONG}" = "0" ]; then
	log_ok "All project dirs have correct gid"
fi


#--------------------------------------------------------------------------------------------------
# Summary
#--------------------------------------------------------------------------------------------------
print_head_1 "SUMMARY"

if [ "${RET_CODE}" -gt "0" ]; then
	log_err "Found ${RET_CODE} error(s)"
	log_err "Devilbox might not work properly"
	log_err "Fix the issues before submitting a bug report"
	log_info "Ensure to run 'docker-compose stop; docker-compose rm -f' on changes in .env"
	exit 1
else
	log_ok "Found no errors"
	log_info "Ensure to run 'docker-compose stop; docker-compose rm -f' when .env was changed"
	exit 0
fi
