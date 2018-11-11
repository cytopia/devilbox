:orphan:

.. include:: /_includes/all.rst

.. _configure_php_xdebug_win:

************************************
Configure Xdebug: Docker for Windows
************************************

Docker for Windows requires a well known IP address in order to connect to the host operating system.
This can be achieved with two different approaches described below.

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

Docker for Windows received many default CNAMEs throughout its versions. The most recent and active
one is ``host.docker.internal``. Use this CNAME as the remote address for Xdebug to connect to your
IDE/editor on your host os.

.. seealso::
   CNAME for :ref:`connect_to_host_os_docker_for_win`
     In case ``host.docker.internal`` is not resolvable, read on here for alternative CNAME's
     on Docker for Windows

.. important::

   Before you try this approach, verify that the PHP Docker container is actually able to resolve
   ``host.docker.internal``:

   .. code-block:: bash

      C:\> cd path\to\devilbox
      C:\> shell.bat
      php> ping host.docker.internal

   In case it is not resolvable, stick to the host alias address approach.

The following example show how to configure PHP Xdebug for PHP 5.6:

**1. Create xdebug.ini**

   1. Navigate to the Devilbox directory
   2. Navigate to ``cfg\php-ini-5.6\`` directory
   3. Create a new file named ``xdebug.ini``

   .. important::
      Pay attention that windows is not adding ``.txt`` as a file extension.

**2. Paste the following content into xdebug.ini**

   .. code-block:: ini
      :caption: xdebug.ini
      :emphasize-lines: 7-8,12

      ; Defaults
      xdebug.default_enable=1
      xdebug.remote_enable=1
      xdebug.remote_port=9000

      ; The Windows way (CNAME)
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


Configure php.ini: Get IP manually
==================================

**Option 2:** This is the most general option that should work with any Docker version on Windows.
it does however require a small manual step.

The following example show how to configure PHP Xdebug for PHP 5.6:

**1. Gather IP address for Xdebug**

   On Windows you will have to manually gather the IP address and add it to ``xdebug.remote_host``.

   1. Open command line
   2. Enter ``ipconfig``
   3. Look for the IP4 address in ``DockerNAT`` (e.g.: ``192.168.246.1``)

   .. seealso:: :ref:`howto_open_terminal_on_win`

   .. important::
      ``192.168.246.1`` is meant as an example and will eventually differ on your system.
      Ensure you substitute it with the correct IP address.

**2. Create xdebug.ini**

   1. Navigate to the Devilbox directory
   2. Navigate to ``cfg\php-ini-5.6\`` directory
   3. Create a new file named ``xdebug.ini``

   .. important::
      Pay attention that windows is not adding ``.txt`` as a file extension.

**3. Paste the following content into xdebug.ini**

   .. code-block:: ini
      :caption: xdebug.ini
      :emphasize-lines: 7-8,12

      ; Defaults
      xdebug.default_enable=1
      xdebug.remote_enable=1
      xdebug.remote_port=9000

      ; The Windows way (IP address)
      xdebug.remote_connect_back=0
      xdebug.remote_host=192.168.246.1

      ; idekey value is specific to each editor
      ; Verify IDE/editor documentation
      xdebug.idekey=PHPSTORM

      ; Optional: Set to true to auto-start xdebug
      xdebug.remote_autostart=false

   .. important::
      ``192.168.246.1`` is meant as an example and will eventually differ on your system.
      Ensure you substitute it with the correct IP address.

**4. Configure your IDE/editor**

   .. seealso::
      * :ref:`configure_php_xdebug_editor_atom`
      * :ref:`configure_php_xdebug_editor_phpstorm`
      * :ref:`configure_php_xdebug_editor_sublime3`
      * :ref:`configure_php_xdebug_editor_vscode`

   .. important::
      Depending on your IDE/editor, you might have to adjust ``xdebug.idekey`` in the above
      configured ``xdebug.ini``.

**5. Restart the Devilbox**

   Restarting the Devilbox is important in order for it to read the new PHP settings.
