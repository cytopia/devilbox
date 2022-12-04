.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

*************************
Change container versions
*************************

One of the core concepts of the Devilbox is to easily change between different versions of a
specific service.


**Table of Contents**

.. contents:: :local:


Implications
============

Configuration changes
---------------------

Be aware that every version has its own configuration files in the ``cfg/`` directory.
If you switch to a different version, you might end up with a different custom configuration.
This however only applies, if you have already customized the configuration for your current
service.

.. seealso::

   * :ref:`php_ini`
   * :ref:`php_fpm_conf`
   * :ref:`apache_conf`
   * :ref:`nginx_conf`
   * :ref:`my_cnf`

Data changes
------------

You also have to be aware that all database services (e.g.: MySQL, PostgreSQL, MongoDB, etc) use
a per version data directory. If you change the database version you might find that you have an
empty database when starting another version.

This is simply a pre-caution to prevent newer versions from upgrading the database files and
accidentally making them incompatible for older versions.

If you want to take your data along, do a backup before switching the version and then re-import
after the switch:

.. seealso::

   * :ref:`backup_and_restore_mysql`
   * :ref:`backup_and_restore_pgsql`
   * :ref:`backup_and_restore_mongo`


Examples
========

Change PHP version
------------------

Stop the Devilbox
^^^^^^^^^^^^^^^^^

Shut down the Devilbox in case it is still running:

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Stop all container
   host> docker-compose stop

Edit the ``.env`` file
^^^^^^^^^^^^^^^^^^^^^^

Open the ``.env`` file with your favourite editor and navigate to the ``PHP_SERVER`` section.
It will look something like this:

.. code-block:: bash
   :caption: .env
   :emphasize-lines: 7

   #PHP_SERVER=5.2
   #PHP_SERVER=5.3
   #PHP_SERVER=5.4
   #PHP_SERVER=5.5
   #PHP_SERVER=5.6
   #PHP_SERVER=7.0
   PHP_SERVER=7.1
   #PHP_SERVER=7.2
   #PHP_SERVER=7.3
   #PHP_SERVER=7.4
   #PHP_SERVER=8.0
   #PHP_SERVER=8.1
   #PHP_SERVER=8.2

As you can see, all available values are already there, but commented. Only one is uncommented.
In this example it is ``7.1``, which is the PHP version that will be started, once the Devilbox
starts.

To change this, simply uncomment your version of choice and save this file. Do not forget to comment
(disable) any other version.

In order to enable PHP 5.5, you would change the ``.env`` file like this:

.. code-block:: bash
   :caption: .env
   :emphasize-lines: 4

   #PHP_SERVER=5.2
   #PHP_SERVER=5.3
   #PHP_SERVER=5.4
   PHP_SERVER=5.5
   #PHP_SERVER=5.6
   #PHP_SERVER=7.0
   #PHP_SERVER=7.1
   #PHP_SERVER=7.2
   #PHP_SERVER=7.3
   #PHP_SERVER=7.4
   #PHP_SERVER=8.0
   #PHP_SERVER=8.1
   #PHP_SERVER=8.2

Start the Devilbox
^^^^^^^^^^^^^^^^^^

Now save the file and you can start the Devilbox again.

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Start all container
   host> docker-compose up php httpd bind

.. seealso:: :ref:`start_the_devilbox`


Change web server version
-------------------------

Stop the Devilbox
^^^^^^^^^^^^^^^^^

Shut down the Devilbox in case it is still running:

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Stop all container
   host> docker-compose stop

Edit the ``.env`` file
^^^^^^^^^^^^^^^^^^^^^^

Open the ``.env`` file with your favourite editor and navigate to the ``HTTPD_SERVER`` section.
It will look something like this:

.. code-block:: bash
   :caption: .env
   :emphasize-lines: 3

   #HTTPD_SERVER=apache-2.2
   #HTTPD_SERVER=apache-2.4
   HTTPD_SERVER=nginx-stable
   #HTTPD_SERVER=nginx-mainline

As you can see, all available values are already there, but commented. Only one is uncommented.
In this example it is ``nginx-stable``, which is the web server version that will be started,
once the Devilbox starts.

To change this, simply uncomment your version of choice and save this file. Do not forget to comment
(disable) any other version.

In order to enable Apache 2.2, you would change the ``.env`` file like this:

.. code-block:: bash
   :caption: .env
   :emphasize-lines: 1

   HTTPD_SERVER=apache-2.2
   #HTTPD_SERVER=apache-2.4
   #HTTPD_SERVER=nginx-stable
   #HTTPD_SERVER=nginx-mainline

Start the Devilbox
^^^^^^^^^^^^^^^^^^

Now save the file and you can start the Devilbox again.

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Start all container
   host> docker-compose up php httpd bind

.. seealso:: :ref:`start_the_devilbox`


Change whatever version
-----------------------

When you have read how to change the PHP or web server version, it should be fairly simple to
change any service version. It behaves in the exact same way.

The variable names of all available services with changable versions are in the following format:
``<SERVICE>_SERVER``. Just look through the :ref:`env_file`.

.. seealso::
   The following variables control service versions:
      :ref:`env_php_server`, :ref:`env_httpd_server`,
      :ref:`env_mysql_server`, :ref:`env_pgsql_server`, :ref:`env_redis_server`,
      :ref:`env_memcd_server`, :ref:`env_mongo_server`


Gotchas
=======

If two versions are uncommented at the same time, always the last one takes precedence.

Consider this ``.env`` file:

.. code-block:: bash
   :caption: .env
   :emphasize-lines: 4,6

   #PHP_SERVER=5.2
   #PHP_SERVER=5.3
   #PHP_SERVER=5.4
   PHP_SERVER=5.5
   #PHP_SERVER=5.6
   PHP_SERVER=7.0
   #PHP_SERVER=7.1
   #PHP_SERVER=7.2
   #PHP_SERVER=7.3
   #PHP_SERVER=7.4
   #PHP_SERVER=8.0
   #PHP_SERVER=8.1
   #PHP_SERVER=8.2

Both, PHP 5.5 and PHP 7.0 are uncommented, however, when you start the Devilbox, it will use
PHP 7.0 as this value overwrites any previous ones.


Checklist
=========

1. Stop the Devilbox
2. Uncomment version of choice in ``.env``
3. Start the Devilbox

.. seealso:: :ref:`troubleshooting`
