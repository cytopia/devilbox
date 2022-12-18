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
DISABLED_VERSIONS=("5.3" "5.4" "5.5" "5.6" "7.0" "7.1" "7.2" "7.3" "7.4" "8.0" "8.1" "8.2")
DISABLED_MYSQL_VERSIONS=("mysql-8.0" "percona-8.0")


echo
echo "# --------------------------------------------------------------------------------------------------"
echo "# [Framework] Drupal 8.x-dev"
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

MYSQL_VERSION="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "MYSQL_SERVER" )"
if [[ ${DISABLED_MYSQL_VERSIONS[*]} =~ ${MYSQL_VERSION} ]]; then
	printf "[SKIP] Skipping all checks for MySQL %s\\n" "${MYSQL_VERSION}"
	exit 0
fi

DRUSH=
if run "docker-compose exec --user devilbox -T php bash -c 'command -v drush'" "1" "${DVLBOX_PATH}"; then
	DRUSH=drush
elif run "docker-compose exec --user devilbox -T php bash -c 'command -v drush10'" "1" "${DVLBOX_PATH}"; then
	DRUSH=drush10
elif run "docker-compose exec --user devilbox -T php bash -c 'command -v drush9'" "1" "${DVLBOX_PATH}"; then
	DRUSH=drush9
elif run "docker-compose exec --user devilbox -T php bash -c 'command -v drush8'" "1" "${DVLBOX_PATH}"; then
	DRUSH=drush8
elif run "docker-compose exec --user devilbox -T php bash -c 'command -v drush7'" "1" "${DVLBOX_PATH}"; then
	DRUSH=drush7
fi
if [ -z "${DRUSH}" ]; then
	>&2 echo "Error, no drush command found."
	exit 1
fi


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT
# -------------------------------------------------------------------------------------------------

###
### Get required env values
###
MYSQL_ROOT_PASSWORD="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "MYSQL_ROOT_PASSWORD" )"
TLD_SUFFIX="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "TLD_SUFFIX" )"
VHOST="my-drupal"

# Create vhost dir
create_vhost_dir "${VHOST}"


# Setup Drupal project
run "docker-compose exec --user devilbox -T php bash -c 'cd /shared/httpd/${VHOST}; composer-1 create-project --no-interaction --prefer-dist drupal-composer/drupal-project drupal 8.x-dev'" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php bash -c 'cd /shared/httpd/${VHOST}; ln -sf drupal/web htdocs'" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php mysql -u root -h mysql --password=\"${MYSQL_ROOT_PASSWORD}\" -e \"DROP DATABASE IF EXISTS my_drupal; CREATE DATABASE my_drupal;\"" "${RETRIES}" "${DVLBOX_PATH}"

# Configure Drupal
run "docker-compose exec --user devilbox -T php bash -c 'cd /shared/httpd/${VHOST}/htdocs/; ${DRUSH} site-install standard --db-url='mysql://root:${MYSQL_ROOT_PASSWORD}@mysql/my_drupal' --site-name=Example -y'" "${RETRIES}" "${DVLBOX_PATH}"

# Test Drupal
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}' | tac | tac | grep 'Welcome to Example'" "${RETRIES}" "${DVLBOX_PATH}"; then
	run "docker-compose exec --user devilbox -T php curl 'http://${VHOST}.${TLD_SUFFIX}' || true"
	exit 1
fi
