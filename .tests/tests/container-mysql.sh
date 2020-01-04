#!/usr/bin/env bash

# NOTE: Parsing curl to tac to circumnvent "failed writing body"
# https://stackoverflow.com/questions/16703647/why-curl-return-and-error-23-failed-writing-body

set -e
set -u
set -o pipefail

SCRIPT_PATH="$( cd "$(dirname "$0")" && pwd -P )"
DVLBOX_PATH="$( cd "${SCRIPT_PATH}/../.." && pwd -P )"
# shellcheck disable=SC1090
. "${SCRIPT_PATH}/../scripts/.lib.sh"

RETRIES=10
DISABLED_VERSIONS=("")


echo
echo "# --------------------------------------------------------------------------------------------------"
echo "# [Container] MySQL"
echo "# --------------------------------------------------------------------------------------------------"
echo


# -------------------------------------------------------------------------------------------------
# Pre-check
# -------------------------------------------------------------------------------------------------

PHP_VERSION="$( get_php_version "${DVLBOX_PATH}" )"
if [[ ${DISABLED_VERSIONS[*]} =~ ${PHP_VERSION} ]]; then
	printf "[SKIP] Skipping all checks for PHP %s\\n" "${PHP_VERSION}"
	exit 0
fi


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT
# -------------------------------------------------------------------------------------------------

###
### Get required env values
###
MYSQL_ROOT_PASSWORD="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "MYSQL_ROOT_PASSWORD" )"

DB_NAME="my_db"
TBL_NAME="my_table"

ROWS=2000      # how many insert statements
GROUPED=1000   # how many grouped inserts: INSERT INTO tbl VALUES ('1') ('2') ('3');
DATALEN=200    # Length of the data per value


# Install pipe viewer
run "docker-compose exec --user root     -T php bash -c 'apt update && apt install -y pv'" "${RETRIES}" "${DVLBOX_PATH}"

# Drop database
run "docker-compose exec --user devilbox -T php bash -c 'mysql --host=mysql --user=root --password='\\''${MYSQL_ROOT_PASSWORD}'\\'' -e '\\''DROP DATABASE IF EXISTS ${DB_NAME};'\\'''" "${RETRIES}" "${DVLBOX_PATH}"

# Delete mysql.sql file
run "docker-compose exec --user devilbox -T php bash -c 'rm -f /home/devilbox/mysql.sql'" "${RETRIES}" "${DVLBOX_PATH}"

# Create SQL File
run "docker-compose exec --user devilbox -T php bash -c '
(
	echo \"CREATE DATABASE ${DB_NAME} COLLATE '\\''utf8mb4_bin'\\'';\";
	echo \"USE ${DB_NAME};\";
	echo \"CREATE TABLE ${TBL_NAME} (
		id int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
		dt varchar(256) COLLATE '\\''utf8mb4_bin'\\'' NOT NULL
	);\";

	>&2 printf \"Creating random mysql.sql file: \";

	for i in \$(seq ${ROWS}); do

		MY_VAL=\$(openssl rand -hex ${DATALEN});
		MY_VAL=\${MY_VAL:$((DATALEN-1))};

		echo \"INSERT INTO ${TBL_NAME} (dt) VALUES\";
		for num in \$( seq $((GROUPED-1)) ); do
			echo \" ('\\''\${i}-\${num} \${MY_VAL}'\\''), \";
		done
		echo \" ('\\''\${i}-\${GROUPED} \${MY_VAL}'\\''); \";
		>&2 printf \".\";
	done;
) > /home/devilbox/mysql.sql
'" "${RETRIES}" "${DVLBOX_PATH}"
printf "\\n"
run "docker-compose exec --user devilbox -T php bash -c 'ls -lap /home/devilbox/mysql.sql'" "${RETRIES}" "${DVLBOX_PATH}"

# Import SQL file
run "docker-compose exec --user devilbox -T php bash -c 'pv -f -i 1 -p -t -e /home/devilbox/mysql.sql | mysql --host=mysql --user=root --password='\\''${MYSQL_ROOT_PASSWORD}'\\'''" "${RETRIES}" "${DVLBOX_PATH}"

# Compare inserted rows
COUNT="$( run "docker-compose exec --user devilbox -T php bash -c 'mysql --host=mysql --user=root --password='\\''${MYSQL_ROOT_PASSWORD}'\\'' -e '\\''SELECT COUNT(*) AS cnt FROM ${DB_NAME}.${TBL_NAME};'\\''' | grep -Ei '[0-9]+'" "1" "${DVLBOX_PATH}" )"
COUNT="$( echo "${COUNT}" | grep -Eo '[0-9]+' )"

if [ "${COUNT}" -ne "$(( ROWS * GROUPED ))" ]; then
	>&2 echo "Error, Expected rows $(( ROWS * GROUPED )), found rows: ${COUNT}"
	exit 1
fi
echo "Success, Expected rows $(( ROWS * GROUPED )), found rows: ${COUNT}"
