.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _syncronize_container_permissions:

********************************
Syncronize container permissions
********************************

One main problem with a running Docker container is to **synchronize the ownership of files in a
mounted volume** in order to preserve security (Not having to use ``chmod 0777`` or root user).

This problem will be addressed below by using a PHP-FPM docker image as an example.

Unsyncronized permissions
=========================

Consider the following directory structure of a mounted volume. Your hosts computer uid/gid are
``1000`` which does not have a corresponding user/group within the container. Fortunately the
``tmp/`` directory allows everybody to create new files in it, because its permissions are
``0777``.

.. code-block:: bash

                     [Host]                   |             [Container]
   ------------------------------------------------------------------------------------------
    $ ls -l                                   | $ ls -l
    -rw-r--r-- user group index.php           | -rw-r--r-- 1000 1000 index.php
    drwxrwxrwx user group tmp/                | drwxrwxrwx 1000 1000 tmp/

Your web application might now have created some temporary files (via the PHP-FPM process) inside
the ``tmp/`` directory:

.. code-block:: bash

                     [Host]                   |             [Container]
   ------------------------------------------------------------------------------------------
    $ ls -l tmp/                              | $ ls -l tmp/
    -rw-r--r-- 96 96 _tmp_cache01.php         | -rw-r--r-- www www _tmp_cache01.php
    -rw-r--r-- 96 96 _tmp_cache02.php         | -rw-r--r-- www www _tmp_cache01.php

On the Docker container side everything is still fine, but on your host computers side, those
files now show a user id and group id of ``96``, which is in fact the uid/gid of the PHP-FPM
process running inside the container. On the host side you have just lost write/delete access to
those files and will now have to use ``sudo`` in order to delete/edit those files.


It gets even worse
==================

Consider your had created the ``tmp/`` directory on your host only with ``0775`` permissions:

.. code-block:: bash

                     [Host]                   |             [Container]
   ------------------------------------------------------------------------------------------
    $ ls -l                                   | $ ls -l
    -rw-r--r-- user group index.php           | -rw-r--r-- 1000 1000 index.php
    drwxrwxr-x user group tmp/                | drwxrwxr-x 1000 1000 tmp/

If your web application now wants to create some temporary files (via the PHP-FPM process) inside
the ``tmp/`` directory, it will fail due to lack of permissions.


The solution
============

To overcome this problem, it must be made sure that the PHP-FPM process inside the container runs
under the same uid/gid as your local user that mouns the volumes and also wants to work on those
files locally. However, you never know during Image build time what user id this would be.
Therefore it must be something that can be changed during startup of the container.

This is achieved in the Devilbox's containers by two environment variables that can be provided
during startup in order to change the uid/gid of the PHP-FPM user prior starting up PHP-FPM process.

.. code-block:: bash

   $ docker run -e NEW_UID=1000 -e NEW_GID=1000 -it devilbox/php-fpm:7.2-work
   [INFO] Changing user 'devilbox' uid to: 1000
   root $ usermod -u 1000 devilbox
   [INFO] Changing group 'devilbox' gid to: 1000
   root $ groupmod -g 1000 devilbox
   [INFO] Starting PHP 7.2.0 (fpm-fcgi) (built: Oct 30 2017 12:05:19)


When ``NEW_UID`` and ``NEW_GID`` are provided to the startup command, the container will do a
``usermod`` and ``groupmod`` prior starting up in order to assign new uid/gid to the PHP-FPM user.
When the PHP-FPM process finally starts up it actually runs with your local system user and making
sure permissions will be in sync from now on.

.. note::
   To tackle this on the PHP-FPM side is only half a solution to the problem. The same applies to a web server Docker container when you offer **file uploads**. They will be uploaded and created by the web servers uid/gid. Therefore the web server itself must also provide the same kind of solution.

