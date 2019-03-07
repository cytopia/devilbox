:orphan:

.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _configure_php_xdebug_lin_sublime:

******************************************
Docker on Linux: Xdebug for Sublime Text 3
******************************************

Docker on Linux allows Xdebug to automatically connect back to the host system without the need
of an explicit IP address.

**Table of Contents**

.. contents:: :local:


Prerequisites
=============

Ensure you know how to customize ``php.ini`` values for the Devilbox and have a rough understanding
about common Xdebug options.

.. seealso::
   * :ref:`php_ini`
   * :ref:`configure_php_xdebug_options`


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

Install Xdebug Client for Sublime
---------------------------------

Use Sublime's Package Control to search for and install ``Xdebug Client``.

   .. seealso:: |ext_lnk_xdebug_ide_sublime_xdebug_client|

Configure Sublime
-----------------

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
          "ide_key": "sublime.xdebug",
          "host": "0.0.0.0",
          "port": 9000
      }

   .. important::
      Recall the path settings from the *Assumption* section and adjust if your configuration differs!

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
      :emphasize-lines: 7,10

      ; Defaults
      xdebug.default_enable=1
      xdebug.remote_enable=1
      xdebug.remote_port=9000

      ; The Linux way
      xdebug.remote_connect_back=1

      ; idekey value is specific to Sublime
      xdebug.idekey=sublime.xdebug

      ; Optional: Set to true to always auto-start xdebug
      xdebug.remote_autostart=false

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
