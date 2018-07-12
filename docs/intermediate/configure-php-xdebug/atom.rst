:orphan:

.. include:: /_includes/all.rst

.. _configure_php_xdebug_atom:

*************************
Configure Xdebug for Atom
*************************

**Table of Contents**

.. contents:: :local:


Prerequisites
=============

Ensure you know how to customize ``php.ini`` values for the Devilbox and Xdebug is enabled.

.. seealso::
   * :ref:`php_ini`
   * :ref:`configure_php_xdebug_enable_xdebug`


Atom configuration
==================

1. Install `php-debug <https://atom.io/packages/php-debug>`_ for Atom
2. Configure path mapping in ``config.cson`` or ui.

   .. code-block:: js
      :caption: launch.json
      :emphasize-lines: 6

      "php-debug":
      {
         ServerPort: 9000
         PathMaps: [
           "remotepath;localpath"
           "/shared/httpd;/home/cytopia/repo/devilbox/data/www"
         ]
      }

   .. important::
      You need to adjust the path part on the right side, as it will most likely be different
      on your system.

      Also note that on Windows you have to use ``\\`` as directory separators.
      E.g.: ``C:\\Users\\projects``.


Xdebug configuration
====================

Xdebug configuration for the Devilbox will slightly vary depending on your host operating system
and your Docker version (native or Toolbox). Pick your system below and create correct
``xdebug.ini`` for the Devilbox.


Docker on Linux
---------------

.. code-block:: ini
   :caption: xdebug.ini
   :emphasize-lines: 6,10

   # Defaults
   xdebug.remote_enable=1
   xdebug.remote_port=9000

   # The Linux way
   xdebug.remote_connect_back=1

   # idekey value is specific to each editor
   # Verify with Atom documentation
   xdebug.idekey=xdebug-atom

   # Optional: Set to true to auto-start xdebug
   xdebug.remote_autostart=false


Docker for Mac
--------------

.. important::
   Ensure you have created an :ref:`howto_host_address_alias_on_mac` and
   ``10.254.254.254`` is aliased to your localhost.

.. code-block:: ini
   :caption: xdebug.ini
   :emphasize-lines: 6-7,11

   # Defaults
   xdebug.remote_enable=1
   xdebug.remote_port=9000

   # The MacOS way
   xdebug.remote_connect_back=0
   xdebug.remote_host=10.254.254.254

   # idekey value is specific to each editor
   # Verify with Atom documentation
   xdebug.idekey=xdebug-atom

   # Optional: Set to true to auto-start xdebug
   xdebug.remote_autostart=false


Docker for Windows
------------------

On Windows you will have to manually gather the IP address and add it to ``xdebug.remote_host``.

1. Open command line
2. Enter ``ipconfig``
3. Look for the IP4 address in ``DockerNAT`` (e.g.: ``192.168.246.1``)

.. seealso:: :ref:`howto_open_terminal_on_win`

.. important::
   The below listed ``xdebug.ini`` uses ``192.168.246.1``, you need to change this to whatever
   IP address came out on your system.

.. code-block:: ini
   :caption: xdebug.ini
   :emphasize-lines: 6-7,11

   # Defaults
   xdebug.remote_enable=1
   xdebug.remote_port=9000

   # The Windows way
   xdebug.remote_connect_back=0
   xdebug.remote_host=192.168.246.1

   # idekey value is specific to each editor
   # Verify with Atom documentation
   xdebug.idekey=xdebug-atom

   # Optional: Set to true to auto-start xdebug
   xdebug.remote_autostart=false


Docker Toolbox
--------------

.. note:: This applies for both, Docker Toolbox on MacOS and Docker Toolbox on Windows.


1. Forward host os port ``9000`` (Xdebug listening port of your IDE) to Docker Toolbox machine also on port ``9000``.
   (remote or local forward)

   .. seealso::
      * :ref:`howto_ssh_port_forward_on_docker_toolbox_from_host`
      * :ref:`howto_ssh_port_forward_on_host_to_docker_toolbox`

2. Add ``xdebug.php``

   .. code-block:: ini
      :caption: xdebug.ini
      :emphasize-lines: 6-7,11

      # Defaults
      xdebug.remote_enable=1
      xdebug.remote_port=9000

      # The Docker Toolbox way
      xdebug.remote_connect_back=0
      xdebug.remote_host=docker.for.lin.host.internal

      # idekey value is specific to each editor
      # Verify with Atom documentation
      xdebug.idekey=xdebug-atom

      # Optional: Set to true to auto-start xdebug
      xdebug.remote_autostart=false

   .. seealso::
      * CNAME for :ref:`connect_to_host_os_docker_on_linux`
