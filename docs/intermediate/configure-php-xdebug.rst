.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _configure_php_xdebug:

********************
Configure PHP Xdebug
********************

This section explains in depth how to enable and use PHP Xdebug with the Devilbox.


**Table of Contents**

.. contents:: :local:


Introduction
============

In order to have a working Xdebug, you need to ensure two things:

1. **PHP Xdebug** must be configured and enabled in PHP itself
2. Your **IDE/editor** must be configured and requires a way talk to PHP

Configuring PHP Xdebug will slightly differ when configuring it for a dockerized environment.
This is due to the fact that Docker versions on different host os have varying implementations of
how they connect back to the host.

Most IDE or editors will also require different configurations for how they talk to PHP Xdebug.
This is at least most likely the case for ``xdebug.idekey``.

.. seealso:: :ref:`configure_php_xdebug_options`


Configure Xdebug
================

Docker on Linux
---------------

Docker on Linux allows Xdebug to automatically connect back to the host system without the need
of an explicit IP address.

.. toctree::
   :glob:
   :maxdepth: 1
   :hidden:

   /intermediate/configure-php-xdebug/linux/*

.. seealso::

   * :ref:`configure_php_xdebug_lin_atom`
   * :ref:`configure_php_xdebug_lin_phpstorm`
   * :ref:`configure_php_xdebug_lin_sublime`
   * :ref:`configure_php_xdebug_lin_vscode`

Docker on MacOS
---------------

.. toctree::
   :glob:
   :maxdepth: 1
   :hidden:

   /intermediate/configure-php-xdebug/macos/*

.. seealso::

   * :ref:`configure_php_xdebug_mac_atom`
   * :ref:`configure_php_xdebug_mac_phpstorm`
   * :ref:`configure_php_xdebug_mac_sublime`
   * :ref:`configure_php_xdebug_mac_vscode`

Docker on Windows
-----------------

.. toctree::
   :glob:
   :maxdepth: 1
   :hidden:

   /intermediate/configure-php-xdebug/windows/*

.. seealso::

   * :ref:`configure_php_xdebug_win_atom`
   * :ref:`configure_php_xdebug_win_phpstorm`
   * :ref:`configure_php_xdebug_win_sublime`
   * :ref:`configure_php_xdebug_win_vscode`

Docker Toolbox
--------------

Docker Toolbox configuration is equal, no matter if it is started on MacOS or Windows, as both
use a Linux system inside VirtualBox.

.. toctree::
   :glob:
   :maxdepth: 1
   :hidden:

   /intermediate/configure-php-xdebug/toolbox/*

.. seealso::

   * :ref:`configure_php_xdebug_toolbox_atom`
   * :ref:`configure_php_xdebug_toolbox_phpstorm`
   * :ref:`configure_php_xdebug_toolbox_sublime`
   * :ref:`configure_php_xdebug_toolbox_vscode`
