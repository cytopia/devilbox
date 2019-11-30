#!/bin/bash
#
# This script will automatically install the Microsoft ODBC driver for MsSQL
# support for PHP during startup.
#
# In order for it to work, you must read and accept their License/EULA:
# https://odbceula.blob.core.windows.net/eula17/LICENSE172.TXT
#


# ------------------------------------------------------------------------------------------------
# EDIT THE VARIABLE BELOW TO ACCEPT THE EULA (If you agree to their terms)
# ------------------------------------------------------------------------------------------------

###
### Set this to "Y" (capital 'Y') if you accept the EULA.
###
ACCEPT_EULA=N



# ------------------------------------------------------------------------------------------------
# DO NOT EDIT BELOW THIS LINE
# ------------------------------------------------------------------------------------------------

###
### Where to retrieve the deb package
###
MSODBC_URL="https://packages.microsoft.com/debian/9/prod/pool/main/m/msodbcsql17/"


###
### Pre-flight check
###
if [ "${#}" = "1" ]; then
	if [ "${1}" = "ACCEPT_EULA=1" ]; then
		ACCEPT_EULA=Y
	fi
fi
if [ "${ACCEPT_EULA}" != "Y" ]; then
	echo "MS ODBC EULA not accepted. Aborting installation."
	exit 0
fi


###
### EULA accepted, so we can proceed
###

# Extract latest *.deb packate
MSODBC_DEB="$( curl -k -sS "${MSODBC_URL}" | grep -Eo 'msodbcsql[-._0-9]+?_amd64\.deb' | tail -1 )"

# Download to temporary location
curl -k -sS "${MSODBC_URL}${MSODBC_DEB}" > "/tmp/${MSODBC_DEB}"

# Install
ACCEPT_EULA="${ACCEPT_EULA}" dpkg -i "/tmp/${MSODBC_DEB}"

# Remove artifacts
rm -f "/tmp/${MSODBC_DEB}"
