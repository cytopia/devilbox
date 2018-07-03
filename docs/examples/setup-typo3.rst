.. _example_setup_typo3:

***********
Setup Typo3
***********

This example will use ``composer`` to install Typo3 from within the PHP container.

.. seealso:: `Official Typo3 Documentation <https://docs.typo3.org/typo3cms/InstallationGuide/Index.html>`_


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+-----------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL           |
+==============+==========================+=============+============+=======================+
| my-typo      | /shared/httpd/my-typo    | my_typo     | loc        | http://my-typo.loc    |
+--------------+--------------------------+-------------+------------+-----------------------+


Walk through
============

It will be ready in eight simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install Typo3 via ``composer``
4. Symlink webroot directory
5. Setup DNS record
6. Create ``FIRST_INSTALL`` file
7. Open your browser
8. Step through guided web installation


.. seealso:: :ref:`available_tools`


1. Enter the PHP container
--------------------------

.. code-block:: bash

   host> ./shell.sh

.. seealso:: :ref:`work_inside_the_php_container`


2. Create new vhost directory
-----------------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-typo


3. Install Typo3
----------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ cd my-typo
   devilbox@php-7.0.20 in /shared/httpd/my-typo $ composer create-project typo3/cms-base-distribution typo3


4. Symlink webroot
------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-typo $ ln -s typo3/public htdocs


5. DNS record
-------------

If you do not have :ref:`setup_auto_dns` configured, you will need to add the
following line to your host operating systems ``/etc/hosts`` file
(or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-typo.loc

.. seealso::

   * :ref:`howto_add_project_dns_entry_on_mac`
   * :ref:`howto_add_project_dns_entry_on_win`
   * :ref:`setup_auto_dns`


6. Create ``FIRST_INSTALL`` file
--------------------------------

To continue installing via the guided web install, you need to create a file called
``FIRST_INSTALL`` in the document root.

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-typo $ touch htdocs/FIRST_INSTALL


7. Open your browser
--------------------

Open your browser at http://my-typo.loc.


8. Step through guided web installation
---------------------------------------

1. Select database

   * Connection: Manually configured MySWQL TCP/IP connection
   * Username: root
   * Password
   * Host: 127.0.0.1
   * Port: 3306

2. Select database

   * Create a new database: ``typo3``

3. Create Administrative User / Specify Site Name

   * Username: admin
   * Password: choose a secure password
   * Site name: My Typo

4. Installation complete

   * Create empty starting page
