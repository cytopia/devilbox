:orphan:

.. include:: /_includes/all.rst

.. _configure_php_xdebug_editor_vscode:

***************************************
Configure Xdebug for Visual Studio Code
***************************************

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

**1. Install vscode-php-debug**


   .. seealso:: |ext_lnk_xdebug_ide_vscode_php_debug|

**2. Configure launch.json**

   .. code-block:: json
      :caption: launch.json
      :emphasize-lines: 9,10

      {
          "version": "0.2.0",
          "configurations": [
              {
                  "name": "Listen for Xbebug",
                  "type": "php",
                  "request": "launch",
                  "port": 9000,
                  "serverSourceRoot": "/shared/httpd",
                  "localSourceRoot": "/home/cytopia/repo/devilbox/data/www"
              }, {
                  "name": "Launch currently open script",
                  "type": "php",
                  "request": "launch",
                  "program": "${file}",
                  "cwd": "${fileDirname}",
                  "port": 9000
              }
          ]
      }

   .. important::
      Recall the path mapping!
