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
# see framework-cakephp-php8.sh for PHP 8+ tests
DISABLED_VERSIONS=("5.2" "5.3" "5.4" "5.5" "5.6" "7.0" "7.1" "7.2" "7.3" "7.4" "8.0" "8.1" "8.2")

PHP_VERSION="$( get_php_version "${DVLBOX_PATH}" )"

###
### What CakePHP Version?
###
CAKE_PHP_VERSION=3.8
#if [ "${PHP_VERSION}" = "8.0" ] || [ "${PHP_VERSION}" = "8.1" ]; then
#	CAKE_PHP_VERSION=
#fi


echo
echo "# --------------------------------------------------------------------------------------------------"
echo "# [Framework] CakePHP ${CAKE_PHP_VERSION}"
echo "# --------------------------------------------------------------------------------------------------"
echo


# -------------------------------------------------------------------------------------------------
# Pre-check
# -------------------------------------------------------------------------------------------------

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
TLD_SUFFIX="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "TLD_SUFFIX" )"
VHOST="my-cakephp"

# Create vhost dir
create_vhost_dir "${VHOST}"


# Setup CakePHP project
run "docker-compose exec --user devilbox -T php bash -c 'cd /shared/httpd/${VHOST}; composer create-project --no-interaction --prefer-dist cakephp/app cakephp ${CAKE_PHP_VERSION}'" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php bash -c 'cd /shared/httpd/${VHOST}; ln -sf cakephp/webroot htdocs'" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php mysql -u root -h mysql --password=\"${MYSQL_ROOT_PASSWORD}\" -e \"DROP DATABASE IF EXISTS my_cake; CREATE DATABASE my_cake;\"" "${RETRIES}" "${DVLBOX_PATH}"

# Configure CakePHP database settings
run "docker-compose exec --user devilbox -T php sed -i\"\" \"s/'host' =>.*/'host' => 'mysql',/g\" /shared/httpd/${VHOST}/cakephp/config/app.php" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php sed -i\"\" \"s/'username' =>.*/'username' => 'root',/g\" /shared/httpd/${VHOST}/cakephp/config/app.php" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php sed -i\"\" \"s/'password' =>.*/'password' => '${MYSQL_ROOT_PASSWORD}',/g\" /shared/httpd/${VHOST}/cakephp/config/app.php" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php sed -i\"\" \"s/'database' =>.*/'database' => 'my_cake',/g\" /shared/httpd/${VHOST}/cakephp/config/app.php" "${RETRIES}" "${DVLBOX_PATH}"

# Test CakePHP
ERROR=0
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}' | tac | tac | grep '\"bullet success\"' | grep 'mbstring'" "${RETRIES}" "${DVLBOX_PATH}"; then
	ERROR=1
fi
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}' | tac | tac | grep '\"bullet success\"' | grep 'openssl'" "${RETRIES}" "${DVLBOX_PATH}"; then
	ERROR=1
fi
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}' | tac | tac | grep '\"bullet success\"' | grep 'intl'" "${RETRIES}" "${DVLBOX_PATH}"; then
	ERROR=1
fi
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}' | tac | tac | grep '\"bullet success\"' | grep 'tmp directory'" "${RETRIES}" "${DVLBOX_PATH}"; then
	ERROR=1
fi
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}' | tac | tac | grep '\"bullet success\"' | grep 'logs directory'" "${RETRIES}" "${DVLBOX_PATH}"; then
	ERROR=1
fi
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://${VHOST}.${TLD_SUFFIX}' | tac | tac | grep '\"bullet success\"' | grep 'connect to the database'" "${RETRIES}" "${DVLBOX_PATH}"; then
	ERROR=1
fi

if [ "${ERROR}" = "1" ]; then
	run "docker-compose exec --user devilbox -T php curl 'http://${VHOST}.${TLD_SUFFIX}' || true" "1" "${DVLBOX_PATH}"
	exit 1
fi
