.. _enable_xdebug:

*************
Enable Xdebug
*************

This tutorial shows you how to enable and use Xdebug with the Devilbox.

.. seealso::
    If you are unsure of how to add custom ``*.ini`` files to the Devilbox,
    have a look at this section first: :ref:`php_ini`


**Table of Contents**

.. contents:: :local:

Enable Xdebug
=============

This section shows you the minimum required ``*.ini`` ini settings to get xdebug to work with
the Devilbox. It will also highlight the differences between operating system and Docker versions.

.. seealso:: See here for how to add the ``*.ini`` values to the Devilbox: :ref:`php_ini`.

Once you have configured Xdebug, you can verify it at the Devilbox intranet:
http://localhost/info_php.php


Required for all OS
-------------------

Additionally to the specific configurations for each operating system and Docker version you will
probably also want to add the following to your ini file:

.. code-block:: ini
    :name: xdebug.ini
    :caption: xdebug.ini

    xdebug.default_enable=1
    xdebug.remote_enable=1
    xdebug.remote_handler=dbgp
    xdebug.remote_port=9000
    xdebug.remote_autostart=1
    xdebug.idekey="PHPSTORM"
    xdebug.remote_log=/var/log/php/xdebug.log

.. seealso:: https://xdebug.org/docs/all_settings

default_enable
^^^^^^^^^^^^^^
By enabling this, stacktraces will be shown by default on an error event.
It is advisable to leave this setting set to 1.

remote_enable
^^^^^^^^^^^^^
This switch controls whether Xdebug should try to contact a debug client which is listening on the
host and port as set with the settings ``xdebug.remote_host`` and ``xdebug.remote_port``.
If a connection can not be established the script will just continue as if this setting was 0.

remote_handler
^^^^^^^^^^^^^^
Can be either ``'php3'`` which selects the old PHP 3 style debugger output, ``'gdb'`` which enables
the GDB like debugger interface or ``'dbgp'`` - the debugger protocol. The DBGp protocol is the only
supported protocol.

**Note:** Xdebug 2.1 and later only support ``'dbgp'`` as protocol.

remote_port
^^^^^^^^^^^
The port to which Xdebug tries to connect on the remote host. Port ``9000`` is the default for both
the client and the bundled debugclient. As many clients use this port number, it is best to leave
this setting unchanged.

remote_autostart
^^^^^^^^^^^^^^^^
Normally you need to use a specific HTTP GET/POST variable to start remote debugging (see
`Remote Debugging <https://xdebug.org/docs/remote#browser_session>`_). When this setting is set to
``1``, Xdebug will always attempt to start a remote debugging session and try to connect to a client,
even if the GET/POST/COOKIE variable was not present.

idekey
^^^^^^
Controls which IDE Key Xdebug should pass on to the DBGp debugger handler. The default is based on
environment settings. First the environment setting DBGP_IDEKEY is consulted, then USER and as last
USERNAME. The default is set to the first environment variable that is found. If none could be found
the setting has as default ''. If this setting is set, it always overrides the environment variables.

For the sake of this tutorial we are going to use ``PHPSTORM`` as an example value.

remote_log
^^^^^^^^^^
Keep the exact path of ``/var/log/php/xdebug.log``. You will then have the log file available
in the Devilbox log directory of the PHP version for which you have configured Xdebug.

.. important::
    You can set the value of ``xdebug.idekey`` to whatever you like, however it is important
    to remember what value you have set. Throughout the examples in this tutorial it is assumed,
    that the value is ``PHPSTORM``.


Linux
-----

.. code-block:: ini
    :name: xdebug.ini
    :caption: xdebug.ini
    :emphasize-lines: 1

    xdebug.remote_connect_back=1


MacOS (Docker for Mac)
----------------------

Docker 18.03.0-ce+ and Docker compose 1.20.1+

.. code-block:: ini
    :name: xdebug.ini
    :caption: xdebug.ini
    :emphasize-lines: 1

    xdebug.remote_host=host.docker.internal
    xdebug.remote_connect_back=0

Docker 17.12.0-ce+ and Docker compose 1.18.0+

.. code-block:: ini
    :name: xdebug.ini
    :caption: xdebug.ini
    :emphasize-lines: 1

    xdebug.remote_host=docker.for.mac.host.internal
    xdebug.remote_connect_back=0

Docker 17.06.0-ce+ and Docker compose 1.14.0+

.. code-block:: ini
    :name: xdebug.ini
    :caption: xdebug.ini
    :emphasize-lines: 1

    xdebug.remote_host=docker.for.mac.localhost
    xdebug.remote_connect_back=0

If you have older versions, upgrade.


MacOS (Docker Toolbox)
----------------------

.. warning::
    This is a legacy solution, upgrade to Docker for Mac
    https://docs.docker.com/toolbox


Windows (Docker for Windows)
----------------------------

Docker 18.03.0-ce+ and Docker compose 1.20.1+

.. code-block:: ini
    :name: xdebug.ini
    :caption: xdebug.ini
    :emphasize-lines: 1

    xdebug.remote_host=docker.for.win.host.internal
    xdebug.remote_connect_back=0

Docker 17.06.0-ce+ and Docker compose 1.14.0+

.. code-block:: ini
    :name: xdebug.ini
    :caption: xdebug.ini
    :emphasize-lines: 1

    xdebug.remote_host=docker.for.win.host.localhost
    xdebug.remote_connect_back=0

If you have older versions, upgrade.


Windows (Docker Toolbox)
------------------------

.. warning::
    This is a legacy solution, upgrade to Docker for Windows
    https://docs.docker.com/toolbox


Configure your IDE
==================

Required for all IDE
--------------------

Path mapping
^^^^^^^^^^^^
The path mapping is a mapping between the file path on your host operating system and the one
inside the PHP Docker container.

The path on your host operating system is the one you have set in :ref:`env_httpd_datadir`.
In case you have set a relative path in ``.env``, ensure to retrieve the absolute path of it when
setting up your IDE config.

The path inside the PHP Docker container is always ``/shared/httpd``.

.. important::
    Even though your path in ``.env`` for :ref:`env_httpd_datadir` might be relative (e.g. ``./data/www``),
    you need to get the actualy absolute path of it, when setting up your IDE.

IDE key
^^^^^^^
This is the value you have set in ``xdebug.ini`` for ``xdebug.idekey``. In the example of this
tutorial, the value was set to ``PHPSTORM``.

Port
^^^^
This is the value you have set in ``xdebug.ini`` for ``xdebug.remote_port``. In the example of this
tutorial, the value was set to ``9000``.


Atom
----

1. Install `php-debug <https://atom.io/packages/php-debug>`_
2. Configure ``config.cson`` (File -> Config...)
3. Adjust your ``xdebug.ini``

For Atom, you need to provide a different ``xdebug.idekey`` in your php.ini file ``xdebug.ini``:

.. code-block:: ini
    :name: xdebug.ini
    :caption: xdebug.ini

    xdebug.idekey=xdebug.atom


Linux
^^^^^^
.. code-block:: json
    :name: launch.json
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

MacOS (Docker for Mac)
^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.

MacOS (Docker Toolbox)
^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.

Windows (Docker for Windows)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.

Windows (Docker Toolbox)
^^^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.


PHPStorm
--------

Linux
^^^^^^

Enable Xdebug for the port set in ``xdebug.ini``:

.. image:: /_static/img/tutorials/xdebug_phpstorm_settings.png

Create a new PHP server and set a path mapping. This tutorial assumes your local Devilbox projects
to be in ``./data/www`` of the Devilbox git directory:

.. image:: /_static/img/tutorials/xdebug_phpstorm_path_mapping.png

Set DBGp proxy settings:

.. image:: /_static/img/tutorials/xdebug_phpstorm_proxy.png


MacOS (Docker for Mac)
^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.

MacOS (Docker Toolbox)
^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.

Windows (Docker for Windows)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.

Windows (Docker Toolbox)
^^^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.


Sublime Text 3
--------------

1. Install `Xdebug Client <https://github.com/martomo/SublimeTextXdebug>`_ via the Sublime Package Control.
2. Configure ``Xdebug.sublime-settings`` (Tools -> Xdebug -> Settings - User)

Linux
^^^^^^
.. code-block:: json
    :name: Xdebug-sublime-settings
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


MacOS (Docker for Mac)
^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.

MacOS (Docker Toolbox)
^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.

Windows (Docker for Windows)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.

Windows (Docker Toolbox)
^^^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.


Visual Studio Code
------------------

1. Install `vscode-php-debug <https://github.com/felixfbecker/vscode-php-debug>`_
2. Configure ``launch.json``

Linux
^^^^^^
.. code-block:: json
    :name: launch.json
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


MacOS (Docker for Mac)
^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.

MacOS (Docker Toolbox)
^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.

Windows (Docker for Windows)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.

Windows (Docker Toolbox)
^^^^^^^^^^^^^^^^^^^^^^^^
.. todo:: Help needed. Please provide your config.


..
  MacOS videos
  https://serversforhackers.com/c/getting-xdebug-working
  https://serversforhackers.com/c/auto-config

  https://www.arroyolabs.com/2016/10/docker-xdebug/

  https://medium.com/@yuliakostrikova/configure-remote-debugging-with-xdebug-for-php-docker-container-on-macos-8edbc01dc373

  https://github.com/petronetto/php7-alpine

  #old
  docker.for.mac.localhost
  #new
  docker.for.mac.host.internal

  docker.for.win.localhost
