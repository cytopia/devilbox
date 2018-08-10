:orphan:

.. include:: /_includes/all.rst

.. _configure_php_xdebug_editor_phpstorm:

*****************************
Configure Xdebug for PhpStorm
*****************************

**Table of Contents**

.. contents:: :local:


Prerequisites
=============

Ensure that ``xdebug.idekey`` is set to ``PHPSTORM`` in your PHP Xdebug configuration.

.. seealso::
   * :ref:`configure_php_xdebug_lin`
   * :ref:`configure_php_xdebug_mac`
   * :ref:`configure_php_xdebug_win`
   * :ref:`configure_php_xdebug_docker_toolbox`


Assumption
==========

For the sake of this example, we will assume the following paths:

+------------------------------+------------------------------------------+
| Directory                    | Path                                     |
+==============================+==========================================+
| Devilbox git directory       | ``/home/cytopia/repo/devilbox``          |
+------------------------------+------------------------------------------+
| :ref:`env_httpd_datadir`     | ``./data/www``                           |
+------------------------------+------------------------------------------+
| Resulting local project path | ``/home/cytopia/repo/devilbox/data/www`` |
+------------------------------+------------------------------------------+

The **Resulting local project path** is the path where all projects are stored locally on your
host operating system. No matter what this path is, the equivalent remote path (inside the Docker
container) is always ``/shared/httpd``.

.. important:: Remember this, when it comes to path mapping in your IDE/editor configuration.


Configuration
=============

**1. Ensure Xdebug port is set to 9000**

   .. include:: /_includes/figures/xdebug/phpstorm-settings.rst

**2. Set path mapping**

   Create a new PHP server and set a path mapping. This tutorial assumes your local Devilbox projects
   to be in ``./data/www`` of the Devilbox git directory:

   .. include:: /_includes/figures/xdebug/phpstorm-path-mapping.rst

   .. important::
      Recall the path mapping!

**3. Ensure DBGp proxy settings are configured**

   .. include:: /_includes/figures/xdebug/phpstorm-dbgp-proxy.rst
