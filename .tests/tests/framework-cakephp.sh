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
DISABLED_VERSIONS=("5.2" "5.3" "5.4" "5.5" "8.0")


echo
echo "# --------------------------------------------------------------------------------------------------"
echo "# [Framework] CakePHP 3.8.0"
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
TLD_SUFFIX="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "TLD_SUFFIX" )"


# Setup CakePHP project
run "docker-compose exec --user devilbox -T php bash -c 'mkdir -p /shared/httpd/cakephp'" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php bash -c 'cd /shared/httpd/cakephp; rm -rf cakephp; composer create-project --no-interaction --prefer-dist cakephp/app cakephp 3.8'" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php bash -c 'cd /shared/httpd/cakephp; ln -sf cakephp/webroot htdocs'" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php mysql -u root -h mysql --password=\"${MYSQL_ROOT_PASSWORD}\" -e \"DROP DATABASE IF EXISTS my_cake; CREATE DATABASE my_cake;\"" "${RETRIES}" "${DVLBOX_PATH}"

# Configure CakePHP database settings
run "docker-compose exec --user devilbox -T php sed -i\"\" \"s/'host' =>.*/'host' => 'mysql',/g\" /shared/httpd/cakephp/cakephp/config/app.php" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php sed -i\"\" \"s/'username' =>.*/'username' => 'root',/g\" /shared/httpd/cakephp/cakephp/config/app.php" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php sed -i\"\" \"s/'password' =>.*/'password' => '${MYSQL_ROOT_PASSWORD}',/g\" /shared/httpd/cakephp/cakephp/config/app.php" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php sed -i\"\" \"s/'database' =>.*/'database' => 'my_cake',/g\" /shared/httpd/cakephp/cakephp/config/app.php" "${RETRIES}" "${DVLBOX_PATH}"

# Test CakePHP
ERROR=0
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://cakephp.${TLD_SUFFIX}' | tac | tac | grep '\"bullet success\"' | grep 'mbstring'" "${RETRIES}" "${DVLBOX_PATH}"; then
	ERROR=1
fi
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://cakephp.${TLD_SUFFIX}' | tac | tac | grep '\"bullet success\"' | grep 'openssl'" "${RETRIES}" "${DVLBOX_PATH}"; then
	ERROR=1
fi
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://cakephp.${TLD_SUFFIX}' | tac | tac | grep '\"bullet success\"' | grep 'intl'" "${RETRIES}" "${DVLBOX_PATH}"; then
	ERROR=1
fi
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://cakephp.${TLD_SUFFIX}' | tac | tac | grep '\"bullet success\"' | grep 'tmp directory'" "${RETRIES}" "${DVLBOX_PATH}"; then
	ERROR=1
fi
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://cakephp.${TLD_SUFFIX}' | tac | tac | grep '\"bullet success\"' | grep 'logs directory'" "${RETRIES}" "${DVLBOX_PATH}"; then
	ERROR=1
fi
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail 'http://cakephp.${TLD_SUFFIX}' | tac | tac | grep '\"bullet success\"' | grep 'connect to the database'" "${RETRIES}" "${DVLBOX_PATH}"; then
	ERROR=1
fi

if [ "${ERROR}" = "1" ]; then
	run "docker-compose exec --user devilbox -T php curl 'http://cakephp.${TLD_SUFFIX}' || true" "1" "${DVLBOX_PATH}"
	exit 1
fi
