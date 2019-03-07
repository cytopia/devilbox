.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _custom_scripts_per_php_version:

******************************
Custom scripts per PHP version
******************************

You can provide custom startup commands via bash scripts to each of the PHP container individually.
This may be useful to specify additional software to install or additional settings to apply during
the initial startup.


.. seealso::
   * :ref:`custom_scripts_globally` (equal for all PHP versions)
   * :ref:`autostarting_nodejs_apps`


.. note::
   Per PHP version scripts are always executed **before** global scripts.


**Table of Contents**

.. contents:: :local:


General
=======

You can add custom shell scripts for each PHP version separately.

.. important::
   Provided scripts must end by the file extension ``.sh`` and should be executable.
   Anything not ending by ``.sh`` will be ignored.

.. important::
   Provided scripts will be executed by the ``root`` user within the PHP container.


Where
-----

Startup scripts can be added to ``cfg/php-startup-X.Y/``.
See the directory structure for PHP startup script directories inside ``./cfg/`` directory:

.. code-block:: bash

   host> ls -l path/to/devilbox/cfg/ | grep 'php-startup'

   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-startup-5.2/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-startup-5.3/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-startup-5.4/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-startup-5.5/
   drwxr-xr-x  2 cytopia cytopia 4096 Apr  3 22:04 php-startup-5.6/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-startup-7.0/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-startup-7.1/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-startup-7.2/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-startup-7.3/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-startup-7.4/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-startup-8.0/


Custom scripts are added by placing a file into ``cfg/php-startup-X.X/`` (where ``X.X`` stands for
your PHP version).  The file must end by ``.sh`` in order to be executed by the PHP container.

Some of the PHP startup directories contain a few example files with the file suffix ``-example``.
If you want to use them, copy these files to a new name without the ``-example`` suffix and ensure
they end by ``.sh``.

When
----

The scripts will be executed by the PHP container during initial startup. Whenever you change your
scripts, ensure to restart the Devilbox.

How
---

The scripts will always be executed inside the PHP container (Debian Linux) and will be run with
``root`` privileges. It is however possible to drop privileges within the script to have them
executed as a normal user.


Examples
========

Installing Microsoft ODBC driver
--------------------------------

This example will add Microsofts ODBC driver to PHP 7.1. These drivers are required in order to
make the PHP modules ``pdo_sqlsrv`` and ``sqlsrv`` work. The two mentioned modules are already
available in the PHP container, but are explicitly disabled via :ref:`env_file_php_modules_disable`.

They won't work without the ODBC driver installed, which unfortunately cannot be bundled, as it
requires every user to accept a license/EULA by Microsoft.


.. code-block:: bash

   # Navigate to starup dir of PHP 7.1
   host> cd path/to/devilbox/cfg/php-startup-7.1

   # Create an .sh file
   host> touch ms-odbc.sh

   # Open the file in your favourite editor
   host> vi ms-odbc.sh

Paste the following into ``ms-obbc.sh`` and **ensure to accept the EULA** by changing
``ACCEPT_EULA=N`` to ``ACCEPT_EULA=Y``.

.. code-block:: bash
   :caption: cfg/php-startup-7.1/install-ms-odbc.sh
   :emphasize-lines: 18

   !/bin/bash
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
   MSODBC_URL="https://packages.microsoft.com/debian/8/prod/pool/main/m/msodbcsql17/"


   ###
   ### Pre-flight check
   ###
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


.. important::
   The script will not work, if you have not accepted the EULA.


Running commands as devilbox user
---------------------------------

As mentioned above, all scripts are run by the ``root`` user.
If you do need something to be executed as the normal user: ``devilbox``, you can simply ``su``
inside the shell script.

The following example will install ``grunt`` and start a NodeJS application as the devilbox user
for the PHP 7.1 Docker container only.

.. code-block:: bash
   :caption: cfg/php-startup-7.1/myscript.sh

   # Install grunt as devilbox user
   su -c "npm install grunt" -l devilbox

   # Start a NodeJS application with pm2 as devilbox user
   su -c "cd /shared/httpd/my-node/src/; pm2 start index.js" -l devilbox
