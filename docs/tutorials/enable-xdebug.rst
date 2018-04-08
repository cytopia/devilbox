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

    xdebug.remote_host=docker.for.mac.host.internal
    xdebug.remote_connect_back=0

Docker 17.06.0-ce+ and Docker compose 1.14.0+

.. code-block:: ini
    :name: xdebug.ini
    :caption: xdebug.ini
    :emphasize-lines: 1

    xdebug.remote_host=docker.for.mac.host.internal
    xdebug.remote_connect_back=0

If you have older versions, upgrade.

Windows (Docker Toolbox)
------------------------

.. warning::
    This is a legacy solution, upgrade to Docker for Windows
    https://docs.docker.com/toolbox


Configure your IDE
==================


Atom
----

Linux
^^^^^^

MacOS (Docker for Mac)
^^^^^^^^^^^^^^^^^^^^^^

MacOS (Docker Toolbox)
^^^^^^^^^^^^^^^^^^^^^^

Windows (Docker for Windows)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Windows (Docker Toolbox)
^^^^^^^^^^^^^^^^^^^^^^^^



PHPStorm
--------

Linux
^^^^^^

MacOS (Docker for Mac)
^^^^^^^^^^^^^^^^^^^^^^

MacOS (Docker Toolbox)
^^^^^^^^^^^^^^^^^^^^^^

Windows (Docker for Windows)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Windows (Docker Toolbox)
^^^^^^^^^^^^^^^^^^^^^^^^



Sublime Text 3
--------------

Linux
^^^^^^

1. Install `Xdebug Client <https://github.com/martomo/SublimeTextXdebug>`_ via the Sublime Package Control.
2. Configure `Xdebug.sublime-settings` (Tools -> Xdebug -> Settings - User)

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

There are two things you need to pay attention to.

path_mapping
""""""""""""

The ``path_mapping`` has the format of ``<server-path> : <host-path>``. The left hand side should
always be ``/shared/httpd``, because this is the path on the PHP-FPM server where all the projects
reside. The right hand side is for you to adjust which must be an absolute path to the files on
your host operating system.

Check your ``.env`` file and see where :ref:`env_httpd_datadir` points to. This is what you need
for the right hand side of the xdebug configuration for the path mapping. In case
:ref:`env_httpd_datadir` is using a relative path (starting with ``./``), you need to find out
the absolute path of it.

In case it is ``./data/www`` as it is by default, you need to prepend the path of the Devilbox
git directory and you have the absolute path.

ide_key
"""""""

The ``ide_key`` is what we have set at the very top for general Xdebug definition:
``xdebug.idekey="PHPSTORM"``. For this tutorial we have assumed ``PHPSTORM``, so that is the value
you will have to add to the xdebug configuration of your editor.


MacOS (Docker for Mac)
^^^^^^^^^^^^^^^^^^^^^^

MacOS (Docker Toolbox)
^^^^^^^^^^^^^^^^^^^^^^

Windows (Docker for Windows)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Windows (Docker Toolbox)
^^^^^^^^^^^^^^^^^^^^^^^^


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
