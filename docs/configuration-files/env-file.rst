.. _env_file:

*********
.env file
*********

All docker-compose configuration is done inside the ``.env`` file which simply defines key-value
variables parsed to docker-compose.yml.

.. note::
   what is the `.env <https://docs.docker.com/compose/env-file/>`_ file?


**Content**

.. contents:: :local:


General settings
================

DEBUG_COMPOSE_ENTRYPOINT
------------------------

This variable controls the docker-compose log verbosity during service startup.
When set to ``1`` verbose output as well as executed commands are shown.
When set to ``0`` only warnings and errors are shown.

+------------------------------+----------------+---------------+
| Name                         | Allowed values | Default value |
+==============================+================+===============+
| ``DEBUG_COMPOSE_ENTRYPOINT`` | ``0`` or ``1`` | ``1``         |
+------------------------------+----------------+---------------+


DEVILBOX_PATH
-------------

This specifies a relative or absolute path to the Devilbox git directory and will be used as a
prefix for all Docker mount paths.

The only reason you would ever want change this variable is when you are on MacOS and relocate
your project files onto an NFS volume due to performance issues.

.. warning::
   Whenever you change this value you have to stop the Devilbox and re-create your volumes via
   ``docker-compose rm -f``.

+-------------------+----------------+---------------+
| Name              | Allowed values | Default value |
+===================+================+===============+
| ``DEVILBOX_PATH`` | valid path     | ``.``         |
+-------------------+----------------+---------------+


LOCAL_LISTEN_ADDR
-----------------

This variable specifies you host computers listening IP address for exposed container ports.
If you leave this variable empty, all exposed ports will be bound to all network interfaces on
your host operating system, which is also the default behaviour.
If you only want the exposed container ports to be bound to a specific IP address (such as
``127.0.0.1``), you can add this IP address here, but note, in this case you must add a trailing
colon (``:``).


+-----------------------+----------------+---------------+
| Name                  | Allowed values | Default value |
+=======================+================+===============+
| ``LOCAL_LISTEN_ADDR`` | IP address     | empty         |
+-----------------------+----------------+---------------+

**Examples:**

+------------------+-------------------------------------------------------------------------------+
| Value            | Meaning                                                                       |
+==================+===============================================================================+
| ``127.0.0.1:``   | only expose ports on your host os on ``127.0.0.1``. Note the trailing ``:``   |
+------------------+-------------------------------------------------------------------------------+
| ``192.168.0.1:`` | only expose ports on your host os on ``192.168.0.1``. Note the trailing ``:`` |
+------------------+-------------------------------------------------------------------------------+
|                  | listen on all host computer interfaces / IP addresses                         |
+------------------+-------------------------------------------------------------------------------+


TLD_SUFFIX
----------



.. note::
   When using ``Docker Toolbox``, you must leave this variable empty, in order to have the exposed
   ports available on the external interface of the virtual machine.

.. _env_new_uid:

NEW_UID
-------

This setting controls one of the core concepts of the Devilbox. It overcomes the problem of
syncronizing file and directory permissions between the Docker container and your host operating
system.

You should set this value to the user id of your host operating systems user you actually work with.
How do you find out your user id?

.. code-block:: bash

    host> id -u
    1000

In most cases (on Linux and MacOS), this will be ``1000`` if you are the first and only user on
your system, however it could also be a different value.

+-----------------------+----------------+---------------+
| Name                  | Allowed values | Default value |
+=======================+================+===============+
| ``NEW_UID``           | valid uid      | ``1000``      |
+-----------------------+----------------+---------------+

The Devilbox own containers will then pick up this value during startup and change their internal
user id to the one specified. Services like PHP-FPM, Apache and Nginx will then do read and write
operation of files with this uid, so all files mounted will have permissions as your local user
and you do not have to fix permissions afterwards.

.. seealso::
   :ref:`syncronize_container_permissions`
      Read up more on the general problem of trying to have syncronized permissions between
      the host system and a running Docker container.


NEW_GID
-------

This is the equivalent to user id for groups and addresses the same concept. See :ref:`env_new_uid`.

How do you find out your group id?

.. code-block:: bash

    host> id -g
    1000

In most cases (on Linux and MacOS), this will be ``1000`` if you are the first and only user on
your system, however it could also be a different value.

+-----------------------+----------------+---------------+
| Name                  | Allowed values | Default value |
+=======================+================+===============+
| ``NEW_GID``           | valid gid      | ``1000``      |
+-----------------------+----------------+---------------+

.. seealso::
   :ref:`syncronize_container_permissions`
      Read up more on the general problem of trying to have syncronized permissions between
      the host system and a running Docker container.


TIMEZONE
--------

This variable controls the system as well as service timezone for the Devilbox's own containers.
This is especially useful to keep PHP and database timezones in sync.

+-----------------------+----------------+-------------------+
| Name                  | Allowed values | Default value     |
+=======================+================+===================+
| ``TIMEZONE``          | valid timezone | ``Europe/Berlin`` |
+-----------------------+----------------+-------------------+

Have a look at Wikipedia to get a list of valid timezones: https://en.wikipedia.org/wiki/List_of_tz_database_time_zones

.. note::
   It is always a good practice not to assume a specific timezone anyway and store all values
   in UTC (such as time types in MySQL).
