.. _example_setup_photon_cms:

****************
Setup Photon CMS
****************

This example will use ``photon`` cli to install Photon CMS from within the PHP container.

.. seealso:: `Official Photon CMS Documentation <https://photoncms.com/resources/installing>`_


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+-----------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL           |
+==============+==========================+=============+============+=======================+
| my-photon    | /shared/httpd/my-photon  | blog        | loc        | http://my-photon.loc  |
+--------------+--------------------------+-------------+------------+-----------------------+

.. note:: The database is created automatically by ``photon`` cli.


Walk through
============

It will be ready in six simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install Photon
4. Symlink webroot directory
5. Setup DNS record
6. Visit http://my-photon.loc in your browser


.. seealso:: :ref:`available_tools`


1. Enter the PHP container
--------------------------

.. code-block:: bash

   host> ./shell.sh

.. seealso:: :ref:`tutorial_work_inside_the_php_container`


2. Create new vhost directory
-----------------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-photon


3. Install Photon
------------------

During the installation you will be asked for the MySQL hostname, username and password. Ensure
not to specify ``localhost``, but instead use ``127.0.0.1`` for the hostname.
Additionally, provide a pair of credentials that has permissions to create a database or create the database
itself beforehand.

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ cd my-photon
   devilbox@php-7.0.20 in /shared/httpd/my-photon $ photon new blog
   ...What is your mysql hostname? [localhost]  127.0.0.1
   ...What is your mysql username? [root]root
   ...What is your mysql password? []


4. Symlink webroot
------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-photon $ ln -s blog/public/ htdocs


5. DNS record
-------------

If you do not have :ref:`global_configuration_auto_dns` configured, you will need to add the
following line to your host operating systems ``/etc/hosts`` file
(or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-photon.loc

.. seealso::
   For in-depth info about adding DNS records on Linux, Windows or MacOS see:
   :ref:`project_configuration_dns_records` or :ref:`global_configuration_auto_dns`.


6. Open your browser
--------------------

Open your browser at http://my-photon.loc
