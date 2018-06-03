.. _example_setup_joomla:

************
Setup Joomla
************

This example will install Joomla from within the PHP container.

.. seealso:: `Official Joomla Documentation <https://docs.joomla.org/J3.x:Installing_Joomla>`_


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+-----------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL           |
+==============+==========================+=============+============+=======================+
| my-joomla    | /shared/httpd/my-joomla  | n.a.        | loc        | http://my-joomla.loc  |
+--------------+--------------------------+-------------+------------+-----------------------+


Walk through
============

It will be ready in six simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Download and extract Joomla
4. Symlink webroot directory
5. Setup DNS record
6. Visit http://my-joomla.loc in your browser


.. seealso:: :ref:`available_tools`


1. Enter the PHP container
--------------------------

.. code-block:: bash

   host> ./shell.sh

.. seealso:: :ref:`tutorial_work_inside_the_php_container`


2. Create new vhost directory
-----------------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-joomla


3. Download and extract Joomla
------------------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ cd my-joomla
   devilbox@php-7.0.20 in /shared/httpd/my-joomla $ wget -O joomla.tar.gz https://downloads.joomla.org/cms/joomla3/3-8-0/joomla_3-8-0-stable-full_package-tar-gz?format=gz
   devilbox@php-7.0.20 in /shared/httpd $ mkdir joomla
   devilbox@php-7.0.20 in /shared/httpd $ tar xvfz joomla.tar.gz -C joomla/


4. Symlink webroot
------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-joomla $ ln -s joomla/ htdocs


5. DNS record
-------------

If you do not have :ref:`global_configuration_auto_dns` configured, you will need to add the
following line to your host operating systems ``/etc/hosts`` file
(or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-joomla.loc

.. seealso::
   For in-depth info about adding DNS records on Linux, Windows or MacOS see:
   :ref:`project_configuration_dns_records` or :ref:`global_configuration_auto_dns`.


6. Open your browser
--------------------

Open your browser at http://my-joomla.loc
