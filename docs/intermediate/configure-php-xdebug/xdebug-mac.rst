:orphan:

.. include:: /_includes/all.rst

.. _configure_php_xdebug_mac:

********************************
Configure Xdebug: Docker for Mac
********************************

Docker for MacOS requires a well known IP address in order to connect to the host operating system.
This can be achieved with two different approaches described below.

.. seealso:: https://forums.docker.com/t/ip-address-for-xdebug/10460/32


**Table of Contents**

.. contents:: :local:


Prerequisites
=============

Ensure you know how to customize ``php.ini`` values for the Devilbox and Xdebug is enabled.

.. seealso::
   * :ref:`php_ini`
   * :ref:`configure_php_xdebug_options`


Configure php.ini: CNAME alias
==============================

**Option 1:** This option is the easiest to setup, but was also very fragile on many Docker versions.

Docker for Mac received many default CNAMEs throughout its versions. The most recent and active
one is ``host.docker.internal``. Use this CNAME as the remote address for Xdebug to connect to your
IDE/editor on your host os.

.. seealso::
   CNAME for :ref:`connect_to_host_os_docker_for_mac`
     In case ``host.docker.internal`` is not resolvable, read on here for alternative CNAME's
     on Docker for Mac

.. important::

   Before you try this approach, verify that the PHP Docker container is actually able to resolve
   ``host.docker.internal``:

   .. code-block:: bash

      host> cd path/to/devilbox
      host> ./shell.sh
      php> ping host.docker.internal

   In case it is not resolvable, stick to the host alias address approach.

The following example show how to configure PHP Xdebug for PHP 5.6:

**1. Create xdebug.ini**

   .. code-block:: bash

      # Navigate to the Devilbox git directory
      host> cd path/to/devilbox

      # Navigate to PHP 5.6 ini configuration directory
      host> cd cfg/php-ini-5.6/

      # Create and open debug.ini file
      host> vi xdebug.ini

**2. Paste the following content into xdebug.ini**

   .. code-block:: ini
      :caption: xdebug.ini
      :emphasize-lines: 7-8,12

      ; Defaults
      xdebug.default_enable=1
      xdebug.remote_enable=1
      xdebug.remote_port=9000

      ; The MacOS way (CNAME)
      xdebug.remote_connect_back=0
      xdebug.remote_host=host.docker.internal

      ; idekey value is specific to each editor
      ; Verify IDE/editor documentation
      xdebug.idekey=PHPSTORM

      ; Optional: Set to true to auto-start xdebug
      xdebug.remote_autostart=false

**3. Configure your IDE/editor**

   .. seealso::
      * :ref:`configure_php_xdebug_editor_atom`
      * :ref:`configure_php_xdebug_editor_phpstorm`
      * :ref:`configure_php_xdebug_editor_sublime3`
      * :ref:`configure_php_xdebug_editor_vscode`

   .. important::
      Depending on your IDE/editor, you might have to adjust ``xdebug.idekey`` in the above
      configured ``xdebug.ini``.


**4. Restart the Devilbox**

   Restarting the Devilbox is important in order for it to read the new PHP settings.

Configure php.ini: Host alias
=============================

**Option 2:** This is the most general option that should work with any Docker version on MacOS,
it does however require a few changes in your system.

.. important::
   Ensure you have created an :ref:`howto_host_address_alias_on_mac` and
   ``10.254.254.254`` is aliased to your localhost.

The following example show how to configure PHP Xdebug for PHP 5.6:

**1. Create xdebug.ini**

   .. code-block:: bash

      # Navigate to the Devilbox git directory
      host> cd path/to/devilbox

      # Navigate to PHP 5.6 ini configuration directory
      host> cd cfg/php-ini-5.6/

      # Create and open debug.ini file
      host> vi xdebug.ini

**2. Paste the following content into xdebug.ini**

   .. code-block:: ini
      :caption: xdebug.ini
      :emphasize-lines: 7-8,12

      ; Defaults
      xdebug.default_enable=1
      xdebug.remote_enable=1
      xdebug.remote_port=9000

      ; The MacOS way (host alias)
      xdebug.remote_connect_back=0
      xdebug.remote_host=10.254.254.254

      ; idekey value is specific to each editor
      ; Verify with your IDE/editor documentation
      xdebug.idekey=PHPSTORM

      ; Optional: Set to true to auto-start xdebug
      xdebug.remote_autostart=false

**3. Configure your IDE/editor**

   .. seealso::
      * :ref:`configure_php_xdebug_editor_atom`
      * :ref:`configure_php_xdebug_editor_phpstorm`
      * :ref:`configure_php_xdebug_editor_sublime3`
      * :ref:`configure_php_xdebug_editor_vscode`

   .. important::
      Depending on your IDE/editor, you might have to adjust ``xdebug.idekey`` in the above
      configured ``xdebug.ini``.


**4. Restart the Devilbox**

   Restarting the Devilbox is important in order for it to read the new PHP settings.
