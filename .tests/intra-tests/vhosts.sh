#!/usr/bin/env bash

set -e
set -u
set -o pipefail


#
# NOTE: Parsing curl to tac to circumnvent "failed writing body"
# https://stackoverflow.com/questions/16703647/why-curl-return-and-error-23-failed-writing-body
#


VHOST="${1:-my-vhost}"

VHOST_GEN_URL="/info_vhostgen.php?name=${VHOST}"
VHOST_URL="/vhost.d/${VHOST}.conf"


###
### vhost.d config
###

printf "[TEST] vhost.d config link available for ${VHOST}"
# 1st Try
if ! curl -sS localhost/vhosts.php | tac | tac | grep "${VHOST}" | grep -q "${VHOST_URL}"; then
	# 2nd Try
	sleep 1
	if ! curl -sS localhost/vhosts.php | tac | tac | grep "${VHOST}" | grep -q "${VHOST_URL}"; then
		# 3rd Try
		sleep 1
		if ! curl -sS localhost/vhosts.php | tac | tac | grep "${VHOST}" | grep -q "${VHOST_URL}"; then
			printf "\r[FAIL] vhost.d config link available for ${VHOST}\n"
			curl -sS localhost/vhosts.php | tac | tac | grep -E "${VHOST}|vhostgen" || true
			exit 1
		else
			printf "\r[OK]   vhost.d config link available for ${VHOST} (3 rounds)\n"
		fi
	else
		printf "\r[OK]   vhost.d config link available for ${VHOST} (2 rounds)\n"
	fi
else
	printf "\r[OK]   vhost.d config link available for ${VHOST} (1 round)\n"
fi


###
### vhost-gen config
###

printf "[TEST] vhost-gen config link available for ${VHOST}"
# 1st Try
if ! curl -sS localhost/vhosts.php | tac | tac | grep "${VHOST}" | grep "vhost-gen" | grep -q "${VHOST_GEN_URL}"; then
	# 2nd Try
	sleep 1
	if ! curl -sS localhost/vhosts.php | tac | tac | grep "${VHOST}" | grep "vhost-gen" | grep -q "${VHOST_GEN_URL}"; then
		# 3rd Try
		sleep 1
		if ! curl -sS localhost/vhosts.php | tac | tac | grep "${VHOST}" | grep "vhost-gen" | grep -q "${VHOST_GEN_URL}"; then
			printf "\r[FAIL] vhost-gen config link available for ${VHOST}\n"
			curl -sS localhost/vhosts.php | tac | tac | grep -E "${VHOST}|vhostgen|vhost-gen" || true
			exit 1
		else
			printf "\r[OK]   vhost-gen config link available for ${VHOST} (3 rounds)\n"
		fi
	else
		printf "\r[OK]   vhost-gen config link available for ${VHOST} (2 rounds)\n"
	fi
else
	printf "\r[OK]   vhost-gen config link available for ${VHOST} (1 round)\n"
fi



###
### vhost.d config
###

printf "[TEST] vhost.d config available for ${VHOST}"
# 1st Try
if ! curl -sS localhost${VHOST_URL} | tac | tac | grep -q "${VHOST}-access.log";then
	# 2nd Try
	sleep 1
	if ! curl -sS localhost${VHOST_URL} | tac | tac | grep -q "${VHOST}-access.log";then
		# 3rd Try
		sleep 1
		if ! curl -sS localhost${VHOST_URL} | tac | tac | grep -q "${VHOST}-access.log";then
			printf "\r[FAIL] vhost.d config available for ${VHOST}\n"
			curl -sS localhost${VHOST_URL} || true
			exit 1
		else
			printf "\r[OK]   vhost.d config available for ${VHOST} (3 rounds)\n"
		fi
	else
		printf "\r[OK]   vhost.d config available for ${VHOST} (2 rounds)\n"
	fi
else
	printf "\r[OK]   vhost.d config available for ${VHOST} (1 round)\n"
fi



###
### vhostgen config
###

printf "[TEST] vhost-gen config available for ${VHOST}"
# 1st Try
if ! curl -sS localhost${VHOST_GEN_URL} | tac | tac | grep -q "/shared/httpd/${VHOST}";then
	# 2nd Try
	sleep 1
	if ! curl -sS localhost${VHOST_GEN_URL} | tac | tac | grep -q "/shared/httpd/${VHOST}";then
		# 3rd Try
		sleep 1
		if ! curl -sS localhost${VHOST_GEN_URL} | tac | tac | grep -q "/shared/httpd/${VHOST}";then
			printf "\r[FAIL] vhost-gen config available for ${VHOST}\n"
			curl -sS localhost${VHOST_GEN_URL} || true
			exit 1
		else
			printf "\r[OK]   vhost-gen config available for ${VHOST} (3 rounds)\n"
		fi
	else
		printf "\r[OK]   vhost-gen config available for ${VHOST} (2 rounds)\n"
	fi
else
	printf "\r[OK]   vhost-gen config available for ${VHOST} (1 round)\n"
fi

