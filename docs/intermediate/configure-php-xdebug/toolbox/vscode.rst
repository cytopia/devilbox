:orphan:

.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _configure_php_xdebug_toolbox_vscode:

*********************************************
Docker Toolbox: Xdebug for Visual Studio Code
*********************************************

Docker Toolbox regardless of running on MacOS or Windows requires an additional port-forward
from your host operating system to the Docker Toolbox machine.

**Table of Contents**

.. contents:: :local:


Prerequisites
=============

General
-------

Ensure you know how to customize ``php.ini`` values for the Devilbox and have a rough understanding
about common Xdebug options.

.. seealso::
   * :ref:`php_ini`
   * :ref:`configure_php_xdebug_options`

Docker Toolbox port-forward
---------------------------

Forward host port 9000 to Docker Toolbox machine:

   Your IDE/editor will open up port ``9000`` on your host operating system. PHP Xdebug requires
   this port to connect to in order to send Xdebug events.
   As Docker Toolbox itself runs in a virtual machine, you need to forward traffic from the same
   port in that virtual machine back to your host operating system.

   .. seealso::
      * :ref:`howto_ssh_port_forward_on_docker_toolbox_from_host`
      * :ref:`howto_ssh_port_forward_on_host_to_docker_toolbox`


Assumption
==========

For the sake of this example, we will assume the following settings and file system paths:

+------------------------------+------------------------------------------+
| Directory                    | Path                                     |
+==============================+==========================================+
| Devilbox git directory       | ``/home/cytopia/repo/devilbox``          |
+------------------------------+------------------------------------------+
| :ref:`env_httpd_datadir`     | ``./data/www``                           |
+------------------------------+------------------------------------------+
| Resulting local project path | ``/home/cytopia/repo/devilbox/data/www`` |
+------------------------------+------------------------------------------+
| Selected PHP version         | ``5.6``                                  |
+------------------------------+------------------------------------------+

The **Resulting local project path** is the path where all projects are stored locally on your
host operating system. No matter what this path is, the equivalent remote path (inside the Docker
container) is always ``/shared/httpd``.

.. important:: Remember this, when it comes to path mapping in your IDE/editor configuration.


Configuration
=============

Install vscode-php-debug for VSCode
-----------------------------------

Ensure you have ``vscode-php-debug`` installed for Visual Studio Code.

   .. seealso:: |ext_lnk_xdebug_ide_vscode_php_debug|

Configure VSCode
----------------

You will need to configure the path mapping in ``launch.json`` (VSCode configuration file):

   .. code-block:: json
      :caption: launch.json
      :emphasize-lines: 5,9,10

      {
          "version": "0.2.0",
          "configurations": [
              {
                  "name": "Xdebug for Project mytest",
                  "type": "php",
                  "request": "launch",
                  "port": 9000,
                  "pathMappings": {
                      "/shared/httpd/mytest/htdocs": "${workspaceFolder}/htdocs"
                  }
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
      Recall the path settings from the *Assumption* section and adjust if your configuration differs!

   .. important::
      The above example configures Xdebug for a single project **mytest**. Add more projects as you need.

   .. seealso::
      * https://go.microsoft.com/fwlink/?linkid=830387
      * https://github.com/cytopia/devilbox/issues/381

Configure php.ini
-----------------

.. note:: The following example show how to configure PHP Xdebug for PHP 5.6:

Create an ``xdebug.ini`` file (must end by ``.ini``):

   .. code-block:: bash

      # Navigate to the Devilbox git directory
      host> cd path/to/devilbox

      # Navigate to PHP 5.6 ini configuration directory
      host> cd cfg/php-ini-5.6/

      # Create and open debug.ini file
      host> vi xdebug.ini

Copy/paste all of the following lines into the above created ``xdebug.ini`` file:

   .. code-block:: ini
      :caption: xdebug.ini
      :emphasize-lines: 7,8,11

      ; Defaults
      xdebug.default_enable=1
      xdebug.remote_enable=1
      xdebug.remote_port=9000

      ; The Docker Toolbox way
      xdebug.remote_connect_back=0
      xdebug.remote_host=docker.for.lin.host.internal

      ; idekey value is specific to Visual Studio Code
      xdebug.idekey=VSCODE

      ; Optional: Set to true to always auto-start xdebug
      xdebug.remote_autostart=false

.. important:: Ensure the port-forward is in place as mentioned in the *Prerequisites* section.

.. note:: Host os and editor specific settings are highlighted in yellow and are worth googling to get a better understanding of the tools you use and to be more efficient at troubleshooting.


Restart the Devilbox
--------------------

Restarting the Devilbox is important in order for it to read the new PHP settings.
Note that the following example only starts up PHP, HTTPD and Bind.

.. code-block:: bash

   # Navigate to the Devilbox git directory
   host> cd path/to/devilbox

   # Stop, remove stopped container and start
   host> docker-compose stop
   host> docker-compose rm
   host> docker-compose up php httpd bind


.. seealso:: :ref:`start_the_devilbox_stop_and_restart` (Why do ``docker-compose rm``?)
