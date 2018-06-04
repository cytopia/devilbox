.. _example_setup_cakephp:

*************
Setup CakePHP
*************

This example will use ``composer`` to install CakePHP from within the PHP container.

.. seealso:: `Official CakePHP Documentation <https://book.cakephp.org/3.0/en/installation.html>`_


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+-----------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL           |
+==============+==========================+=============+============+=======================+
| my-cake      | /shared/httpd/my-cake    | my_cake     | loc        | http://my-cake.loc    |
+--------------+--------------------------+-------------+------------+-----------------------+


Walk through
============

It will be ready in eight simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install CakePHP via ``composer``
4. Symlink webroot directory
5. Add MySQL database
6. Configure datbase connection
7. Setup DNS record
8. Visit http://my-cake.loc in your browser


.. seealso:: :ref:`available_tools`


1. Enter the PHP container
--------------------------

.. code-block:: bash

   host> ./shell.sh

.. seealso:: :ref:`tutorial_work_inside_the_php_container`


2. Create new vhost directory
-----------------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-cake


3. Install CakePHP
------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ cd my-cake
   devilbox@php-7.0.20 in /shared/httpd/my-cake $ composer create-project --prefer-dist cakephp/app cakephp


4. Symlink webroot
------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-cake $ ln -s cakephp/webroot/ htdocs


5. Add MySQL Database
---------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-cake $ mysql -u root -h 127.0.0.1 -p -e 'CREATE DATABASE my_cake;'


6. Configure database connection
--------------------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-cake $ vi cakephp/config/app.php

.. code-block:: php
   :caption: cakephp/config/app.php
   :emphasize-lines: 7,14,15,16

   <?php
     'Datasources' => [
           'default' => [
               'className' => 'Cake\Database\Connection',
               'driver' => 'Cake\Database\Driver\Mysql',
               'persistent' => false,
               'host' => '127.0.0.1',
               /**
                * CakePHP will use the default DB port based on the driver selected
                * MySQL on MAMP uses port 8889, MAMP users will want to uncomment
                * the following line and set the port accordingly
                */
               //'port' => 'non_standard_port_number',
               'username' => 'root',
               'password' => 'secret',
               'database' => 'my_cake',
               'encoding' => 'utf8',
               'timezone' => 'UTC',
               'flags' => [],
               'cacheMetadata' => true,
   ?>


7. DNS record
-------------

If you do not have :ref:`global_configuration_auto_dns` configured, you will need to add the
following line to your host operating systems ``/etc/hosts`` file
(or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-cake.loc

.. seealso::
   For in-depth info about adding DNS records on Linux, Windows or MacOS see:
   :ref:`project_configuration_dns_records` or :ref:`global_configuration_auto_dns`.


8. Open your browser
--------------------

All set now, you can visit http://my-cake.loc in your browser.
