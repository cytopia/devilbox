.. _env_file:

*********
.env file
*********

All docker-compose configuration is done inside the ``.env`` file which simply defines key-value
variables parsed to docker-compose.yml.

.. note::
   what is the `.env <https://docs.docker.com/compose/env-file/>`_ file?


**Table of Contents**

.. contents:: :local:


Core settings
=============

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

* Relative path: relative to the devilbox git directory (Must start with ``.``)
* Absolute path: Full path (Must start with ``/``)

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

.. note::
   When using ``Docker Toolbox``, you must leave this variable empty, in order to have the exposed
   ports available on the external interface of the virtual machine.


TLD_SUFFIX
----------

This variable controls all of your projects domain suffix.

+----------------+------------------+---------------+
| Name           | Allowed values   | Default value |
+================+==================+===============+
| ``TLD_SUFFIX`` | alpha-num string | ``loc``       |
+----------------+------------------+---------------+

Your project domains are built together out of the project directory name and the ``TLD_SUFFIX``.
The formula is like this: ``http://<project-dir>.<TLD_SUFFIX>``.

You can even use official tld's and have your nameserver point to an internal LAN id, to make
this project visible to everyone in your corporate LAN.

**How does it look?**

+-------------+----------------+---------------------------+
| Project dir | ``TLD_SUFFIX`` | Project URL               |
+=============+================+===========================+
| my-test     | ``loc``        | ``http://my-test.loc``    |
+-------------+----------------+---------------------------+
| example     | ``loc``        | ``http://example.loc``    |
+-------------+----------------+---------------------------+
| www.test    | ``loc``        | ``http://www.test.loc``   |
+-------------+----------------+---------------------------+
| my-test     | ``local``      | ``http://my-test.local``  |
+-------------+----------------+---------------------------+
| example     | ``local``      | ``http://example.local``  |
+-------------+----------------+---------------------------+
| www.test    | ``local``      | ``http://www.test.local`` |
+-------------+----------------+---------------------------+
| my-test     | ``net``        | ``http://my-test.net``    |
+-------------+----------------+---------------------------+
| example     | ``com``        | ``http://example.com``    |
+-------------+----------------+---------------------------+
| www.test    | ``org``        | ``http://www.test.org``   |
+-------------+----------------+---------------------------+


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


Intranet settings
=================


DNS_CHECK_TIMEOUT
-----------------

The Devilbox intranet validates if every project has a corresponding DNS record (either an official
DNS record, one that came from its own Auto-DNS or an ``/etc/hosts`` entry). By doing so it queries
the DNS record based on ``<project-dir>.<TLD_SUFFIX>``. In case it does not exist, the query itself
might take a while and the intranet page will be unresponsive during that time. In order to avoid
long waiting times, you can set the DNS query time-out in seconds after which the query should stop
and report as unsuccessful. The default is ``1`` second, wich should be fairly sane for all use-cases.

+-----------------------+----------------+-------------------+
| Name                  | Allowed values | Default value     |
+=======================+================+===================+
| ``DNS_CHECK_TIMEOUT`` | integers       | ``1``             |
+-----------------------+----------------+-------------------+




DEVILBOX_UI_PROTECT
-------------------

By setting this variable to ``1``, the Devilbox intranet will be password protected.
This might be useful, if you share your running Devilbox instance accross a LAN, but do not want
everybody to have access to the intranet itself, just to the projects you actually provide.

+-------------------------+----------------+-------------------+
| Name                    | Allowed values | Default value     |
+=========================+================+===================+
| ``DEVILBOX_UI_PROTECT`` | ``0`` or ``1`` | ``0``             |
+-------------------------+----------------+-------------------+

.. note::
   Also pay attention to the next env var, which will control the password for the login:
   ``DEVILBOX_UI_PASSWORD``.


DEVILBOX_UI_PASSWORD
--------------------

When the devilbox intranet is password-protected via ``DEVILBOX_UI_PROTECT``, this is the actual
password by which it will be protected.

+--------------------------+----------------+-------------------+
| Name                     | Allowed values | Default value     |
+==========================+================+===================+
| ``DEVILBOX_UI_PASSWORD`` | any string     | ``password``      |
+--------------------------+----------------+-------------------+


DEVILBOX_UI_DISABLE
-------------------

In case you want to completely disable the Devilbox intranet, such as when running it on production,
you need to set this variable to ``1``.

By disabling the intranet, the webserver will simply remove the default virtual host and redirect
all IP-based requests to the first available virtual host, which will be you first project when
ordering their names alphabetically.

+-------------------------+----------------+-------------------+
| Name                    | Allowed values | Default value     |
+=========================+================+===================+
| ``DEVILBOX_UI_DISABLE`` | ``0`` or ``1`` | ``0``             |
+-------------------------+----------------+-------------------+


Docker image versions
=====================

The following settings reflect one of the main goals of the Devilbox: being able to run any
combination of all container versions.

.. note::
   Any change for those settings requires a restart of the devilbox.


PHP_SERVER
----------

This variable choses your desired PHP-FPM version to be started.

+-------------------------+--------------------------------------------------------------------------------------------------------------------------+-----------------+
| Name                    | Allowed values                                                                                                           | Default value   |
+=========================+==========================================================================================================================+=================+
| ``PHP_SERVER``          | ``php-fpm-5.4`` |br| ``php-fpm-5.5`` |br| ``php-fpm-5.6`` |br| ``php-fpm-7.0`` |br| ``php-fpm-7.1`` |br| ``php-fpm-7.2`` | ``php-fpm-7.1`` |
+-------------------------+--------------------------------------------------------------------------------------------------------------------------+-----------------+

All values are already available in the ``.env`` file and just need to be commented or uncommented. If multiple values are uncommented, the last uncommented variable one takes precedences:

.. code-block:: bash
   :caption: .env
   :name: .env
   :emphasize-lines: 7

   host> grep PHP_SERVER .env

   #PHP_SERVER=php-fpm-5.4
   #PHP_SERVER=php-fpm-5.5
   #PHP_SERVER=php-fpm-5.6
   #PHP_SERVER=php-fpm-7.0
   PHP_SERVER=php-fpm-7.1
   #PHP_SERVER=php-fpm-7.2
   #PHP_SERVER=php-fpm-7.3
   #PHP_SERVER=hhvm-latest


HTTPD_SERVER
------------

This variable choses your desired web server version to be started.

+-------------------------+----------------------------------------------------------------------------------+------------------+
| Name                    | Allowed values                                                                   | Default value    |
+=========================+==================================================================================+==================+
| ``HTTPD_SERVER``        | ``apache-2.2`` |br| ``apache-2.4`` |br| ``nginx-stable`` |br| ``nginx-mainline`` | ``nginx-stable`` |
+-------------------------+----------------------------------------------------------------------------------+------------------+

All values are already available in the ``.env`` file and just need to be commented or uncommented. If multiple values are uncommented, the last uncommented variable one takes precedences:

.. code-block:: bash
   :caption: .env
   :name: .env
   :emphasize-lines: 5

   host> grep HTTPD_SERVER .env

   #HTTPD_SERVER=apache-2.2
   #HTTPD_SERVER=apache-2.4
   HTTPD_SERVER=nginx-stable
   #HTTPD_SERVER=nginx-mainline


MYSQL_SERVER
------------

This variable choses your desired MySQL server version to be started.

+-------------------------+------------------------------------------------------------------------------------------------+------------------+
| Name                    | Allowed values                                                                                 | Default value    |
+=========================+================================================================================================+==================+
| ``MYSQL_SERVER``        | ``mysql-5.5`` |br| ``mysql-5.6`` |br| ``mariadb-10.2`` |br| ``percona-5.7`` |br| and many more | ``mariadb-10.1`` |
+-------------------------+------------------------------------------------------------------------------------------------+------------------+

All values are already available in the ``.env`` file and just need to be commented or uncommented. If multiple values are uncommented, the last uncommented variable one takes precedences:

.. code-block:: bash
   :caption: .env
   :name: .env
   :emphasize-lines: 9

   host> grep MYSQL_SERVER .env

   #MYSQL_SERVER=mysql-5.5
   #MYSQL_SERVER=mysql-5.6
   #MYSQL_SERVER=mysql-5.7
   #MYSQL_SERVER=mysql-8.0
   #MYSQL_SERVER=mariadb-5.5
   #MYSQL_SERVER=mariadb-10.0
   MYSQL_SERVER=mariadb-10.1
   #MYSQL_SERVER=mariadb-10.2
   #MYSQL_SERVER=mariadb-10.3
   #MYSQL_SERVER=percona-5.5
   #MYSQL_SERVER=percona-5.6
   #MYSQL_SERVER=percona-5.7


PGSQL_SERVER
------------

This variable choses your desired PostgreSQL server version to be started.

+-------------------------+-------------------------------------------------------------------+------------------+
| Name                    | Allowed values                                                    | Default value    |
+=========================+===================================================================+==================+
| ``PGSQL_SERVER``        | ``9.1`` |br| ``9.2`` |br| ``9.3`` |br| ``9.4`` |br| and many more | ``9.6``          |
+-------------------------+-------------------------------------------------------------------+------------------+

All values are already available in the ``.env`` file and just need to be commented or uncommented. If multiple values are uncommented, the last uncommented variable one takes precedences:

.. code-block:: bash
   :caption: .env
   :name: .env
   :emphasize-lines: 8

   host> grep PGSQL_SERVER .env

   #PGSQL_SERVER=9.1
   #PGSQL_SERVER=9.2
   #PGSQL_SERVER=9.3
   #PGSQL_SERVER=9.4
   #PGSQL_SERVER=9.5
   PGSQL_SERVER=9.6
   #PGSQL_SERVER=10.0

.. note::
   This is the official PostgreSQL server which might already have other tags available,
   check their official website for even more versions.
   https://hub.docker.com/_/postgres/


REDIS_SERVER
------------

This variable choses your desired Redis server version to be started.

+-------------------------+-------------------------------------------------------------------+------------------+
| Name                    | Allowed values                                                    | Default value    |
+=========================+===================================================================+==================+
| ``REDIS_SERVER``        | ``2.8`` |br| ``3.0`` |br| ``3.2`` |br| ``4.0`` |br| and many more | ``4.0``          |
+-------------------------+-------------------------------------------------------------------+------------------+

All values are already available in the ``.env`` file and just need to be commented or uncommented. If multiple values are uncommented, the last uncommented variable one takes precedences:

.. code-block:: bash
   :caption: .env
   :name: .env
   :emphasize-lines: 6

   host> grep REDIS_SERVER .env

   #REDIS_SERVER=2.8
   #REDIS_SERVER=3.0
   #REDIS_SERVER=3.2
   REDIS_SERVER=4.0

.. note::
   This is the official Redis server which might already have other tags available,
   check their official website for even more versions.
   https://hub.docker.com/_/redis/


MEMCD_SERVER
------------

This variable choses your desired Memcached server version to be started.

+-------------------------+-------------------------------------------------------------------------------+------------------+
| Name                    | Allowed values                                                                | Default value    |
+=========================+===============================================================================+==================+
| ``MEMCD_SERVER``        | ``1.4.21`` |br| ``1.4.22`` |br| ``1.4.23`` |br| ``1.4.24`` |br| and many more | ``1.5.2``        |
+-------------------------+-------------------------------------------------------------------------------+------------------+

All values are already available in the ``.env`` file and just need to be commented or uncommented. If multiple values are uncommented, the last uncommented variable one takes precedences:

.. code-block:: bash
   :caption: .env
   :name: .env
   :emphasize-lines: 24

   host> grep MEMCD_SERVER .env

   #MEMCD_SERVER=1.4.21
   #MEMCD_SERVER=1.4.22
   #MEMCD_SERVER=1.4.23
   #MEMCD_SERVER=1.4.24
   #MEMCD_SERVER=1.4.25
   #MEMCD_SERVER=1.4.26
   #MEMCD_SERVER=1.4.27
   #MEMCD_SERVER=1.4.28
   #MEMCD_SERVER=1.4.29
   #MEMCD_SERVER=1.4.30
   #MEMCD_SERVER=1.4.31
   #MEMCD_SERVER=1.4.32
   #MEMCD_SERVER=1.4.33
   #MEMCD_SERVER=1.4.34
   #MEMCD_SERVER=1.4.35
   #MEMCD_SERVER=1.4.36
   #MEMCD_SERVER=1.4.37
   #MEMCD_SERVER=1.4.38
   #MEMCD_SERVER=1.4.39
   #MEMCD_SERVER=1.5.0
   #MEMCD_SERVER=1.5.1
   MEMCD_SERVER=1.5.2
   #MEMCD_SERVER=latest

.. note::
   This is the official Memcached server which might already have other tags available,
   check their official website for even more versions.
   https://hub.docker.com/_/memcached/


MONGO_SERVER
------------

This variable choses your desired MongoDB server version to be started.

+-------------------------+-------------------------------------------------------------------+------------------+
| Name                    | Allowed values                                                    | Default value    |
+=========================+===================================================================+==================+
| ``MONGO_SERVER``        | ``2.8`` |br| ``3.0`` |br| ``3.2`` |br| ``3.4`` |br| and many more | ``3.4``          |
+-------------------------+-------------------------------------------------------------------+------------------+

All values are already available in the ``.env`` file and just need to be commented or uncommented. If multiple values are uncommented, the last uncommented variable one takes precedences:

.. code-block:: bash
   :caption: .env
   :name: .env
   :emphasize-lines: 6

   host> grep MONGO_SERVER .env

   #MONGO_SERVER=2.8
   #MONGO_SERVER=3.0
   #MONGO_SERVER=3.2
   MONGO_SERVER=3.4
   #MONGO_SERVER=3.5

.. note::
   This is the official MongoDB server which might already have other tags available,
   check their official website for even more versions.
   https://hub.docker.com/_/mongo/


Docker host mounts
==================

The Docker host mounts are directory paths on your host operating system that will be mounted into
the running Docker container. This makes data persistent accross restarts and let them be available
on both sides: Your host operating system as well as inside the container.

This also gives you the choice to edit data on your host operating system, such as with your
favourite IDE/editor and also inside the container, by using the bundled tools, such as
downloading libraries with ``composer`` and others.

Being able to do that on both sides, removes the need to install any development tools (except your
IDE/editor) on your host and have everything fully encapsulated into the containers itself.


HOST_PATH_HTTPD_DATADIR
-----------------------

This is an absolute or relative path (relative to Devilbox git directory) to your data directory.

.. seealso::
   :ref:`getting_started_directory_overview_datadir`

By default, all of your websites/projects will be stored in that directory. If however you want
to separate your data from the Devilbox git directory, do change the path to a place where you
want to store all of your projects on your host computer.

* Relative path: relative to the devilbox git directory (Must start with ``.``)
* Absolute path: Full path (Must start with ``/``)

+------------------------------+----------------+----------------+
| Name                         | Allowed values | Default value  |
+==============================+================+================+
| ``HOST_PATH_HTTPD_DATADIR``  | valid path     | ``./data/www`` |
+------------------------------+----------------+----------------+

Example
^^^^^^^

If you want to move all your projects to ``/home/myuser/workspace/web/`` for example, just set it
like this:

.. code-block:: bash
   :caption: .env
   :name: .env

    HOST_PATH_HTTPD_DATADIR=/home/myuser/workspace/web

Mapping
^^^^^^^

No matter what path you assign, inside the PHP and the web server container your data dir will
always be ``/shared/httpd/``.



.. |br| raw:: html

   <br />
