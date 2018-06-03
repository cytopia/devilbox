.. _example_setup_zend:

**********
Setup Zend
**********

This example will use ``composer`` to install Zend from within the PHP container.

.. seealso:: `Official Zend Documentation <https://docs.zendframework.com/tutorials/getting-started/skeleton-application/>`_


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+-----------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL           |
+==============+==========================+=============+============+=======================+
| my-zend      | /shared/httpd/my-zend    | n.a.        | loc        | http://my-zend.loc    |
+--------------+--------------------------+-------------+------------+-----------------------+


Walk through
============

It will be ready in six simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install Zend via ``composer``
4. Symlink webroot directory
5. Setup DNS record
6. Visit http://my-wp.loc in your browser


.. seealso:: :ref:`available_tools`


1. Enter the PHP container
--------------------------

.. code-block:: bash

   host> ./shell.sh

.. seealso:: :ref:`tutorial_work_inside_the_php_container`


2. Create new vhost directory
-----------------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-zend


3. Install Zend via ``composer``
--------------------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ cd my-zend
   devilbox@php-7.0.20 in /shared/httpd/my-zend $ composer create-project --prefer-dist zendframework/skeleton-application zend


4. Symlink webroot
------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-zend $ ln -s zend/public/ htdocs


5. DNS record
-------------

If you do not have :ref:`global_configuration_auto_dns` configured, you will need to add the
following line to your host operating systems ``/etc/hosts`` file
(or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-zend.loc

.. seealso::
   For in-depth info about adding DNS records on Linux, Windows or MacOS see:
   :ref:`project_configuration_dns_records` or :ref:`global_configuration_auto_dns`.


6. Open your browser
--------------------

Open your browser at http://my-zend.loc
