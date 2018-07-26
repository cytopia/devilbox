:orphan:

.. include:: /_includes/all.rst

.. _configure_php_xdebug_editor_sublime3:

***********************************
Configure Xdebug for Sublime Text 3
***********************************

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

**1. Install Xdebug Client**

   Use Sublime's Package Control to search for and install ``Xdebug Client``.

   .. seealso:: |ext_lnk_xdebug_ide_sublime_xdebug_client|

**2. Configure Xdebug.sublime-settings**

   * Navigate to ``Tools`` -> ``Xdebug`` -> ``Settings - User`` in the menu
   * This will open the configuration file in Sublime

   .. code-block:: json
      :caption: Xdebug-sublime-settings
      :emphasize-lines: 3,6

      {
          "path_mapping": {
              "/shared/httpd" : "/home/cytopia/repo/devilbox/data/www"
          },
          "url": "",
          "ide_key": "PHPSTORM",
          "host": "0.0.0.0",
          "port": 9000
      }

   .. important::
      Recall the path mapping!
