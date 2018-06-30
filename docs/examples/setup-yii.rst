.. _example_setup_yii:

*********
Setup Yii
*********

This example will use ``composer`` to install Yii from within the PHP container.

.. seealso:: `Official Yii Documentation <https://www.yiiframework.com/doc/guide/2.0/en/start-installation>`_


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+-----------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL           |
+==============+==========================+=============+============+=======================+
| my-yii       | /shared/httpd/my-yii     | n.a.        | loc        | http://my-yii.loc     |
+--------------+--------------------------+-------------+------------+-----------------------+


Walk through
============

It will be ready in six simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install Yii2 via ``composer``
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

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-yii


3. Install Yii2 via ``composer``
--------------------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ cd my-yii
   devilbox@php-7.0.20 in /shared/httpd/my-yii $ composer create-project --prefer-dist --stability=dev yiisoft/yii2-app-basic yii2-dev


4. Symlink webroot
------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-yii $ ln -s yii2-dev/web/ htdocs


5. DNS record
-------------

If you do not have :ref:`global_configuration_auto_dns` configured, you will need to add the
following line to your host operating systems ``/etc/hosts`` file
(or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-yii.loc

.. seealso::
   For in-depth info about adding DNS records on Linux, Windows or MacOS see:
   :ref:`project_configuration_dns_records` or :ref:`global_configuration_auto_dns`.


6. Open your browser
--------------------

Open your browser at http://my-yii.loc
