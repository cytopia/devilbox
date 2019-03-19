.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _my_cnf:

******
my.cnf
******


``my.ini`` changes are global to all projects, but will only affect the currently selected
MySQL version.


.. important::
   When using :ref:`howto_docker_toolbox_and_the_devilbox` on Windows, ``*.cnf`` files must have read-only file
   permissions, otherwise they are not sourced by the MySQL server.

   Make sure to ``chmod 0444 *.cnf`` after adding your values.


**Table of Contents**

.. contents:: :local:


General
=======

You can set custom MySQL options via your own defined ``my.cnf`` files for each version separately.
See the directory structure for MySQL configuration directories inside ``./cfg/`` directory:

.. code-block:: bash

   host> ls -l path/to/devilbox/cfg/ | grep -E 'mysql|mariadb|percona'

   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 mariadb-5.5/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 mariadb-10.0/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 mariadb-10.1/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 mariadb-10.2/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 mariadb-10.3/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 mariadb-10.4/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 mysql-5.5/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 mysql-5.6/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 mysql-5.7/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 mysql-8.0/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 percona-5.5/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 percona-5.6/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 percona-5.7/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 percona-8.0/

Customization is achieved by placing a file into ``cfg/mysql-X.X/``, ``cfg/mariadb-X.X/`` or
``cfg/percona-X-X`` (where ``X.X`` stands for your MySQL version).
The file must end by ``.cnf`` in order to be sourced by the MySQL server.

Each of the MySQL cnf configuration directories already contain an example file:
``devilbox-custom.cnf-example``, that can simply be renamed to ``devilbox-custom.cnf``.
This file holds some example values that can be adjusted or commented out.

In order for the changes to be applied, you will have to restart the Devilbox.


Examples
========

Change key_buffer_size for MySQL 5.5
------------------------------------

The following examples shows you how to change the
`key_buffer_size <https://dev.mysql.com/doc/refman/5.7/en/server-system-variables.html#sysvar_key_buffer_size>`_
of MySQL 5.5 to 16 MB.

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Navigate to MySQL 5.5 config directory
   host> cd cfg/mysql-5.5

   # Create new cnf file
   host> touch key_buffer_size.cnf

Now add the following content to the file:

.. code-block:: ini
   :caption: memory_limit.cnf

   [mysqld]
   key_buffer_size=16M

In order to apply the changes you need to restart the Devilbox.
You can validate that the changes have taken place by visiting the Devilbox intranet MySQL info page.


Change timeout and packet size for PerconaDB 5.7
------------------------------------------------

The following examples shows you how to change the
`wait_timeout <https://dev.mysql.com/doc/refman/5.7/en/server-system-variables.html#sysvar_wait_timeout>`_
and
`max_allowed_packet <https://dev.mysql.com/doc/refman/5.7/en/server-system-variables.html#sysvar_max_allowed_packet>`_
of PerconaDB 5.7

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Navigate to PerconaDB 5.7 config directory
   host> cd cfg/percona-5.7

   # Create new ini file
   host> touch timeouts.cnf

Now add the following content to the file:

.. code-block:: ini
   :caption: timeouts.cnf

   [mysqld]
   max_allowed_packet=256M
   wait_timeout = 86400

In order to apply the changes you need to restart the Devilbox.
You can validate that the changes have taken place by visiting the Devilbox intranet MySQL info page.
