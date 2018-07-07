.. _example_setup_shopware:

**************
Setup Shopware
**************

This example will use ``git`` to install Shopware from within the PHP container.

.. seealso::
   * `Official Shopware Documentation <https://en-community.shopware.com/Shopware-5-Installer_detail_1351_730.html/sCoreId,bdd630e6d079964f3f697fccff83a987>`_
   * `Shopware Github repository <https://github.com/shopware/shopware>`_


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+-----------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL           |
+==============+==========================+=============+============+=======================+
| my-sw        | /shared/httpd/my-sw      | my_sw       | loc        | http://my-sw.loc      |
+--------------+--------------------------+-------------+------------+-----------------------+


Walk through
============

It will be ready in seven simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Download Shopware via ``git``
4. Symlink webroot directory
5. Add MySQL database
6. Setup DNS record
7. Follow installation steps in http://my-sw.loc in your browser


.. seealso:: :ref:`available_tools`


1. Enter the PHP container
--------------------------

.. code-block:: bash

   host> ./shell.sh

.. seealso:: :ref:`work_inside_the_php_container`


2. Create new vhost directory
-----------------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-sw


3. Download Shopware
--------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ cd my-sw
   devilbox@php-7.0.20 in /shared/httpd/my-sw $ git clone https://github.com/shopware/shopware


4. Symlink webroot
------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-sw $ ln -s shopware/ htdocs


5. Add MySQL Database
---------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-sw $ mysql -u root -h 127.0.0.1 -p -e 'CREATE DATABASE my_sw;'


6. DNS record
-------------

If you do not have :ref:`setup_auto_dns` configured, you will need to add the
following line to your host operating systems ``/etc/hosts`` file
(or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-sw.loc

.. seealso::

   * :ref:`howto_add_project_dns_entry_on_mac`
   * :ref:`howto_add_project_dns_entry_on_win`
   * :ref:`setup_auto_dns`


7. Follow install steps in your browser
---------------------------------------

All set now, you can visit http://my-sw.loc in your browser and follow the installation steps as
described in the `Official Shopware Documentation <https://en-community.shopware.com/Shopware-5-Installer_detail_1351_730.html/sCoreId,bdd630e6d079964f3f697fccff83a987>`_:

.. important::
   When setting up database connection use the following values:

   * Database server: ``127.0.0.1``
   * Database user: ``root`` (if you don't have a dedicated user already)
   * Database pass: by default the root password is empty
   * Database name: ``my_sw``


Encountered problems
====================

By the time of writing (2018-07-07) Shopware had loading issues with the combination of ``PHP 5.6``
and ``Apache 2.4``. Use any other combination.

.. seealso:: https://github.com/cytopia/devilbox/issues/300
