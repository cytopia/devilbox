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
echo "# [Intranet] Email"
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

if ! command -v curl >/dev/null 2>&1; then
	>&2 echo "Error 'curl' binary not found, but required."
	exit 1
fi

if ! command -v tac >/dev/null 2>&1; then
	>&2 echo "Error 'tac' binary not found, but required."
	exit 1
fi


# -------------------------------------------------------------------------------------------------
# ENTRYPOINT
# -------------------------------------------------------------------------------------------------

###
### Get required env values
###
HOST_PORT_HTTPD="$( "${SCRIPT_PATH}/../scripts/env-getvar.sh" "HOST_PORT_HTTPD" )"


###
### Email variables
###
MY_MAIL="ci@devilbox.org"
MY_SUBJ="testing-ci-subject"
MY_MESS="testing-ci-message"


# Empty mails first
run "docker-compose exec --user devilbox -T php bash -c '> /var/mail/devilbox'" "${RETRIES}" "${DVLBOX_PATH}"

# Send a new mail
run "curl -sS --fail -XPOST 'http://localhost:${HOST_PORT_HTTPD}/mail.php' -d 'email=${MY_MAIL}&subject=${MY_SUBJ}&message=${MY_MESS}'" "${RETRIES}"

# Validate
run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/mail.php' | tac | tac | grep '${MY_MAIL}' >/dev/null" "${RETRIES}"
run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/mail.php' | tac | tac | grep '${MY_SUBJ}' >/dev/null" "${RETRIES}"
run "curl -sS --fail 'http://localhost:${HOST_PORT_HTTPD}/mail.php?get-body=1' | tac | tac | grep '${MY_MESS}' >/dev/null" "${RETRIES}"
