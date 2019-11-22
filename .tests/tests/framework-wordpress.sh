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
DISABLED_VERSIONS=()


echo
echo "# --------------------------------------------------------------------------------------------------"
echo "# [Framework] Wordpress"
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
HOST_PORT_HTTPD="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HOST_PORT_HTTPD" )"
TLD_SUFFIX="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "TLD_SUFFIX" )"


###
### Custom variables
###
VHOST="my-wordpress"
DB_NAME="my_wp"
PROJECT_NAME="this-is-my-grepable-project-name"


# Setup Wordpress project
run "docker-compose exec --user devilbox -T php bash -c ' \
	rm -rf /shared/httpd/${VHOST} \
	&& mkdir -p /shared/httpd/${VHOST} \
	&& git clone https://github.com/WordPress/WordPress /shared/httpd/${VHOST}/wordpress \
	&& ln -sf wordpress /shared/httpd/${VHOST}/htdocs'" \
	"${RETRIES}" "${DVLBOX_PATH}"

# Setup Database
run "docker-compose exec --user devilbox -T php mysql -u root -h mysql --password=\"${MYSQL_ROOT_PASSWORD}\" -e \"DROP DATABASE IF EXISTS ${DB_NAME}; CREATE DATABASE ${DB_NAME};\"" "${RETRIES}" "${DVLBOX_PATH}"

# Configure Wordpress database settings
run "docker-compose exec --user devilbox -T php bash -c \"perl -pe 's/\\r$//' < /shared/httpd/${VHOST}/wordpress/wp-config-sample.php > /shared/httpd/${VHOST}/wordpress/wp-config.php\"" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php sed -i\"\" \"s/define(\\s*'DB_NAME.*/define('DB_NAME', '${DB_NAME}');/g\" /shared/httpd/${VHOST}/wordpress/wp-config.php" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php sed -i\"\" \"s/define(\\s*'DB_USER.*/define('DB_USER', 'root');/g\" /shared/httpd/${VHOST}/wordpress/wp-config.php" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php sed -i\"\" \"s/define(\\s*'DB_PASSWORD.*/define('DB_PASSWORD', '${MYSQL_ROOT_PASSWORD}');/g\" /shared/httpd/${VHOST}/wordpress/wp-config.php" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php sed -i\"\" \"s/define(\\s*'DB_HOST.*/define('DB_HOST', 'mysql');/g\" /shared/httpd/${VHOST}/wordpress/wp-config.php" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php sed -i\"\" \"s/define(\\s*'WP_DEBUG.*/define('WP_DEBUG', true);/g\" /shared/httpd/${VHOST}/wordpress/wp-config.php" "${RETRIES}" "${DVLBOX_PATH}"
run "docker-compose exec --user devilbox -T php php -l /shared/httpd/${VHOST}/wordpress/wp-config.php" "${RETRIES}" "${DVLBOX_PATH}"

# Install Wordpress
if ! run "docker-compose exec --user devilbox -T php curl -sS --fail -L -XPOST -c cookie.txt -b cookie.txt \
	'http://${VHOST}.${TLD_SUFFIX}:${HOST_PORT_HTTPD}/wp-admin/install.php?step=1'\
	--data 'language=1' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}"; then
	run "docker-compose exec --user devilbox -T php curl -sS --fail -L -XPOST -c cookie.txt -b cookie.txt \
			'http://${VHOST}.${TLD_SUFFIX}:${HOST_PORT_HTTPD}/wp-admin/install.php?step=1'\
			--data 'language=1' >/dev/null" "1" "${DVLBOX_PATH}"
	exit 1
fi

if ! run "docker-compose exec --user devilbox -T php curl -sS --fail -L -XPOST -c cookie.txt -b cookie.txt \
	'http://${VHOST}.${TLD_SUFFIX}:${HOST_PORT_HTTPD}/wp-admin/install.php?step=2' \
	--data 'weblog_title=${PROJECT_NAME}' \
	--data 'user_name=admin' \
	--data 'admin_password=password' \
	--data 'admin_password2=password' \
	--data 'pw_weak=on' \
	--data 'admin_email=test%40test.com' \
	--data 'blog_public=0' \
	--data 'Submit=Install+WordPress&language=' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}"; then
	run "docker-compose exec --user devilbox -T php curl -sS --fail -L -XPOST -c cookie.txt -b cookie.txt \
		'http://${VHOST}.${TLD_SUFFIX}:${HOST_PORT_HTTPD}/wp-admin/install.php?step=2' \
		--data 'weblog_title=${PROJECT_NAME}' \
		--data 'user_name=admin' \
		--data 'admin_password=password' \
		--data 'admin_password2=password' \
		--data 'pw_weak=on' \
		--data 'admin_email=test%40test.com' \
		--data 'blog_public=0' \
		--data 'Submit=Install+WordPress&language='" "1" "${DVLBOX_PATH}"
	exit 1
fi

# Test Wordpress
run "docker-compose exec --user devilbox -T php curl -sS --fail -L 'http://${VHOST}.${TLD_SUFFIX}:${HOST_PORT_HTTPD}/' | grep '${PROJECT_NAME}' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}"
run " curl -sS --fail -L --header 'host: ${VHOST}.${TLD_SUFFIX}' 'http://localhost:${HOST_PORT_HTTPD}/' | grep '${PROJECT_NAME}' >/dev/null" "${RETRIES}" "${DVLBOX_PATH}"
