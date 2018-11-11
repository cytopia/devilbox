:orphan:

.. include:: /_includes/all.rst

.. _configure_php_xdebug_docker_toolbox:

********************************
Configure Xdebug: Docker Toolbox
********************************

Docker Toolbox regardless of running on MacOS or Windows requires an additional port-forward
from your host operating system to the Docker Toolbox machine.

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

The following example show how to configure PHP Xdebug for PHP 5.6:

**1. Forward host port 9000 to Docker Toolbox machine**

   Your IDE/editor will open up port ``9000`` on your host operating system. PHP Xdebug requires
   this port to connect to in order to send Xdebug events.
   As Docker Toolbox itself runs in a virtual machine, you need to forward traffic from the same
   port in that virtual machine back to your host operating system.

   .. seealso::
      * :ref:`howto_ssh_port_forward_on_docker_toolbox_from_host`
      * :ref:`howto_ssh_port_forward_on_host_to_docker_toolbox`

**2. Create xdebug.ini**

   .. code-block:: bash

      # Navigate to the Devilbox git directory
      host> cd path/to/devilbox

      # Navigate to PHP 5.6 ini configuration directory
      host> cd cfg/php-ini-5.6/

      # Create and open debug.ini file
      host> vi xdebug.ini

**3. Paste the following content into xdebug.ini**

   Once the por-forward is up, the configuration matches the one for Docker on Linux

   .. seealso::
      * CNAME for :ref:`connect_to_host_os_docker_on_linux`

   .. code-block:: ini
      :caption: xdebug.ini
      :emphasize-lines: 6-7,11

      ; Defaults
      xdebug.remote_enable=1
      xdebug.remote_port=9000

      ; The Docker Toolbox way
      xdebug.remote_connect_back=0
      xdebug.remote_host=docker.for.lin.host.internal

      ; idekey value is specific to each editor
      ; Verify with your IDE/editor documentation
      xdebug.idekey=PHPSTORM

      ; Optional: Set to true to auto-start xdebug
      xdebug.remote_autostart=false

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
