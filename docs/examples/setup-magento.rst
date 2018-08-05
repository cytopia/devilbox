.. include:: /_includes/all.rst

.. _example_setup_magento:

*************
Setup Magento
*************

This example will use ``git`` and ``composer`` to install Magento from within the Devilbox PHP container.

After completing the below listed steps, you will have a working Magento setup ready to be
served via http and https.

.. seealso:: |ext_lnk_example_magento_documentation|


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+---------------------------------------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL                                       |
+==============+==========================+=============+============+===================================================+
| my-magento   | /shared/httpd/my-magento | my_magento  | loc        | http://my-magento.loc |br| https://my-magento.loc |
+--------------+--------------------------+-------------+------------+---------------------------------------------------+

.. note::
   * Inside the Devilbox PHP container, projects are always in ``/shared/httpd/``.
   * On your host operating system, projects are by default in ``./data/www/`` inside the
     Devilbox git directory. This path can be changed via :ref:`env_httpd_datadir`.


Walk through
============

It will be ready in eight simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install Magento via ``git`` and ``composer``
4. Symlink webroot directory
5. Add MySQL database
6. Setup DNS record
7. Visit http://my-magento.loc in your browser


1. Enter the PHP container
--------------------------

All work will be done inside the PHP container as it provides you with all required command line
tools.

Navigate to the Devilbox git directory and execute ``shell.sh`` (or ``shell.bat`` on Windows) to
enter the running PHP container.

.. code-block:: bash

   host> ./shell.sh

.. seealso::
   * :ref:`enter_the_php_container`
   * :ref:`work_inside_the_php_container`
   * :ref:`available_tools`


2. Create new vhost directory
-----------------------------

The vhost directory defines the name under which your project will be available. |br|
( ``<vhost dir>.TLD_SUFFIX`` will be the final URL ).

.. code-block:: bash

   devilbox@php-7.1.20 in /shared/httpd $ mkdir my-magento

.. seealso:: :ref:`env_tld_suffix`


3. Install Magento
------------------

Navigate into your newly created vhost directory and install Magento with ``git``.

.. code-block:: bash

   devilbox@php-7.1.20 in /shared/httpd $ cd my-magento

   # Download Magento with git
   devilbox@php-7.1.20 in /shared/httpd/my-magento $ git clone https://github.com/magento/magento2

   # Checkout the latest stable git tag
   devilbox@php-7.1.20 in /shared/httpd/my-magento $ cd magento2
   devilbox@php-7.1.20 in /shared/httpd/my-magento $ git checkout 2.2.5

   # Install dependencies with Composer
   devilbox@php-7.1.20 in /shared/httpd/my-magento $ composer install

How does the directory structure look after installation:

.. code-block:: bash

   devilbox@php-7.1.20 in /shared/httpd/my-magento $ tree -L 1
   .
   └── magento2

   1 directory, 0 files


4. Symlink webroot
------------------

Symlinking the actual webroot directory to ``htdocs`` is important. The web server expects every
project's document root to be in ``<vhost dir>/htdocs/``. This is the path where it will serve
the files. This is also the path where your frameworks entrypoint (usually ``index.php``) should
be found.

Some frameworks however provide its actual content in nested directories of unknown levels.
This would be impossible to figure out by the web server, so you manually have to symlink it back
to its expected path.

.. code-block:: bash

   devilbox@php-7.1.20 in /shared/httpd/my-magento $ ln -s magento2/ htdocs

How does the directory structure look after symlinking:

.. code-block:: bash

   devilbox@php-7.1.20 in /shared/httpd/my-magento $ tree -L 1
   .
   ├── magento2
   └── htdocs -> magento2

   2 directories, 0 files

As you can see from the above directory structure, ``htdocs`` is available in its expected
path and points to the frameworks entrypoint.


5. Add MySQL Database
---------------------

.. code-block:: bash

   devilbox@php-7.1.20 in /shared/httpd/my-magento $ mysql -u root -h 127.0.0.1 -p -e 'CREATE DATABASE my_magento;'


7. DNS record
-------------

If you **have** Auto DNS configured already, you can skip this section, because DNS entries will
be available automatically by the bundled DNS server.

If you **don't have** Auto DNS configured, you will need to add the following line to your
host operating systems ``/etc/hosts`` file (or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-magento.loc

.. seealso::

   * :ref:`howto_add_project_hosts_entry_on_mac`
   * :ref:`howto_add_project_hosts_entry_on_win`
   * :ref:`setup_auto_dns`


8. Open your browser
--------------------

All set now, you can visit http://my-magento.loc or https://my-magento.loc in your browser
and follow the installation steps.

.. important::
   Use ``127.0.0.1`` for the MySQL database hostname.

.. seealso:: :ref:`setup_valid_https`
