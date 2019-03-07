.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _backup_and_restore_pgsql:

*****************************
Backup and restore PostgreSQL
*****************************

Backup and restore will be necessary when you are going to switch PostgreSQL versions.
Each version has its own data directory and is fully indepentend of other versions.
In case you want to switch to a different version, but still want to have your PostgreSQL databases
present, you must first backup the databases of your current version and import them into the
new version.

There are multiple ways to backup and restore. Chose the one which is most convenient for you.


**Table of Contents**

.. contents:: :local:


Backup
======

pg_dump
-------

|ext_lnk_tool_pg_dump| is bundled with each PHP container and reay to use.
To backup a database named ``my_db_name`` follow the below listed example:

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Enter the PHP container
   host> ./shell.sh

   # Run pg_dump
   devilbox@php-7.1.6 in /shared/httpd $ pg_dump -h pgsql -U postgres -W my_db_name > /shared/backups/pgsql/my_db_name.sql


phpPgAdmin
----------

If you do not like to use the command line for backups, you can use |ext_lnk_tool_phppgadmin|.
It comes bundled with the devilbox intranet.


Adminer
-------

If you do not like to use the command line for backups, you can use |ext_lnk_tool_adminer|.
It comes bundled with the devilbox intranet.


Restore
=======

psql
----

In order to restore or import PostgreSQL databases on the command line, you need to use
|ext_lnk_tool_pgsql_restore|.
Here are a few examples for different file types:

``*.sql`` file
^^^^^^^^^^^^^^

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Enter the PHP container
   host> ./shell.sh

   # Start the import
   devilbox@php-7.1.6 in /shared/httpd $ psql -h pgsql -U postgres -W my_db_name < /shared/backups/pgsql/my_db_name.sql

``*.sql.gz`` file
^^^^^^^^^^^^^^^^^^

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Enter the PHP container
   host> ./shell.sh

   # Start the import
   devilbox@php-7.1.6 in /shared/httpd $ zcat /shared/backups/pgsql/my_db_name.sql.gz | psql -h pgsql -U postgres -W my_db_name

``*.sql.tar.gz`` file
^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Enter the PHP container
   host> ./shell.sh

   # Start the import
   devilbox@php-7.1.6 in /shared/httpd $ tar xzOf /shared/backups/pgsql/my_db_name.sql.tar.gz | psql -h pgsql -U postgres -W my_db_name


phpPgAdmin
----------

|ext_lnk_tool_phppgadmin| supports importing many different formats out-of-the-box.
Simply select the compressed or uncompressed file and press ``Go`` in the import section of
the web interface.


Adminer
-------

|ext_lnk_tool_adminer| supports importing of plain (``*.sql``) or gzipped compressed
(``*.sql.gz``) files out-of-the-box. Simply select the compressed or uncompressed file and press
``Execute`` in the import section of the web interface.
