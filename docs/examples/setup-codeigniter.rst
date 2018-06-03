.. _example_setup_codeigniter:

*****************
Setup CodeIgniter
*****************


.. seealso:: `Official CodeIgniter Documentation <https://www.codeigniter.com/user_guide/installation/index.html>`_


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+-----------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL           |
+==============+==========================+=============+============+=======================+
| my-ci        | /shared/httpd/my-ci      | my_ci       | loc        | http://my-ci.loc      |
+--------------+--------------------------+-------------+------------+-----------------------+


Walk through
============

It will be ready in eight simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Download CodeIgniter
4. Symlink webroot directory
5. Add MySQL database
6. Configure datbase connection
7. Setup DNS record
8. Visit http://my-ci.loc in your browser


.. seealso:: :ref:`available_tools`


1. Enter the PHP container
--------------------------

.. code-block:: bash

   host> ./shell.sh

.. seealso:: :ref:`tutorial_work_inside_the_php_container`


2. Create new vhost directory
-----------------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-ci


3. Download CodeIgniter
-----------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ cd my-ci
   devilbox@php-7.0.20 in /shared/httpd/my-ci $ wget https://github.com/bcit-ci/CodeIgniter/archive/3.1.8.tar.gz
   devilbox@php-7.0.20 in /shared/httpd/my-ci $ tar xfvz 3.1.8.tar.gz
   devilbox@php-7.0.20 in /shared/httpd/my-ci $ ls -l
   total 7488
   drwxr-xr-x 5 devilbox devilbox    4096 Mar 22 15:48 CodeIgniter-3.1.8/
   -rw-r--r-- 1 devilbox devilbox 2205672 May 21 10:42 3.1.8.tar.gz


4. Symlink webroot
------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-ci $ ln -s CodeIgniter-3.1.8/ htdocs


5. Add MySQL Database
---------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-ci $ mysql -u root -h 127.0.0.1 -p -e 'CREATE DATABASE my_ci;'


6. Configure database connection
--------------------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-ci $ vi htdocs/application/config/database.php

.. code-block:: php
   :caption: htdocs/application/config/database.php
   :emphasize-lines: 4-7

   <?php
   $db['default'] = array(
           'dsn'   => '',
           'hostname' => '127.0.0.1',
           'username' => 'root',
           'password' => '',
           'database' => 'my_ci',
           'dbdriver' => 'mysqli',
           'dbprefix' => '',
           'pconnect' => FALSE,
           'db_debug' => (ENVIRONMENT !== 'production'),
           'cache_on' => FALSE,
           'cachedir' => '',
           'char_set' => 'utf8',
           'dbcollat' => 'utf8_general_ci',
           'swap_pre' => '',
           'encrypt' => FALSE,
           'compress' => FALSE,
           'stricton' => FALSE,
           'failover' => array(),
           'save_queries' => TRUE
   );


7. DNS record
-------------

If you do not have :ref:`global_configuration_auto_dns` configured, you will need to add the
following line to your host operating systems ``/etc/hosts`` file
(or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-ci.loc

.. seealso::
   For in-depth info about adding DNS records on Linux, Windows or MacOS see:
   :ref:`project_configuration_dns_records` or :ref:`global_configuration_auto_dns`.


8. Open your browser
--------------------

All set now, you can visit http://my-ci.loc in your browser.
