:orphan:

.. include:: /_includes/all.rst

.. _configure_php_xdebug_lin:

*********************************
Configure Xdebug: Docker on Linux
*********************************

Docker on Linux allows Xdebug to automatically connect back to the host system without the need
of an explicit IP address.

**Table of Contents**

.. contents:: :local:


Prerequisites
=============

Ensure you know how to customize ``php.ini`` values for the Devilbox.

.. seealso::
   * :ref:`php_ini`
   * :ref:`configure_php_xdebug_options`


Configure php.ini
=================

Configuring Xdebug for Docker on Linux is straight forward and you must only pay attention to
two variables highlighted below.

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
      :emphasize-lines: 7,11

      ; Defaults
      xdebug.default_enable=1
      xdebug.remote_enable=1
      xdebug.remote_port=9000

      ; The Linux way
      xdebug.remote_connect_back=1

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
