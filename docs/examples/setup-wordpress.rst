.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _example_setup_wordpress:

***************
Setup WordPress
***************

This example will use ``git`` to install WordPress from within the Devilbox PHP container.

After completing the below listed steps, you will have a working WordPress setup ready to be
served via http and https.

.. seealso:: |ext_lnk_example_wordpress_documentation|


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+-----------------------------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL                             |
+==============+==========================+=============+============+=========================================+
| my-wp        | /shared/httpd/my-wp      | my_wp       | loc        | http://my-wp.loc |br| https://my-wp.loc |
+--------------+--------------------------+-------------+------------+-----------------------------------------+

.. note::
   * Inside the Devilbox PHP container, projects are always in ``/shared/httpd/``.
   * On your host operating system, projects are by default in ``./data/www/`` inside the
     Devilbox git directory. This path can be changed via :ref:`env_httpd_datadir`.


Walk through
============

It will be ready in seven simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Download WordPress via ``git``
4. Symlink webroot directory
5. Add MySQL database
6. Setup DNS record
7. Visit http://my-wp.loc in your browser


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

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-wp

.. seealso:: :ref:`env_tld_suffix`


3. Download WordPress via ``git``
---------------------------------

Navigate into your newly created vhost directory and install WordPress with ``git``.

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ cd my-wp
   devilbox@php-7.0.20 in /shared/httpd/my-wp $ git clone https://github.com/WordPress/WordPress wordpress.git

How does the directory structure look after installation:

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-wp $ tree -L 1
   .
   └── wordpress.git

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

   devilbox@php-7.0.20 in /shared/httpd/my-wp $ ln -s wordpress.git/ htdocs

How does the directory structure look after symlinking:

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-wp $ tree -L 1
   .
   ├── wordpress.git
   └── htdocs -> wordpress.git

   2 directories, 0 files

As you can see from the above directory structure, ``htdocs`` is available in its expected
path and points to the frameworks entrypoint.

.. important::
   When using **Docker Toolbox**, you need to **explicitly allow** the usage of **symlinks**.
   See below for instructions:

   * Docker Toolbox and :ref:`howto_docker_toolbox_and_the_devilbox_windows_symlinks`


5. Add MySQL Database
---------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-wp $ mysql -u root -h 127.0.0.1 -p -e 'CREATE DATABASE my_wp;'


6. DNS record
-------------

If you **have** Auto DNS configured already, you can skip this section, because DNS entries will
be available automatically by the bundled DNS server.

If you **don't have** Auto DNS configured, you will need to add the following line to your
host operating systems ``/etc/hosts`` file (or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-wp.loc

.. seealso::

   * :ref:`howto_add_project_hosts_entry_on_mac`
   * :ref:`howto_add_project_hosts_entry_on_win`
   * :ref:`setup_auto_dns`


7. Open your browser
--------------------

Open your browser at http://my-wp.loc or https://my-wp.loc and follow the installation steps.

**(1/7) Choose your desired WordPress language**

.. include:: /_includes/figures/examples/wordpress/01-choose-language.rst

**(2/7) Read pre-installation information**

.. include:: /_includes/figures/examples/wordpress/02-overview.rst

**(3/7) Setup database connection**

.. important:: Choose ``127.0.0.1`` as the database host

.. include:: /_includes/figures/examples/wordpress/03-setup-database.rst

**(4/7) Database setup post screen**

.. include:: /_includes/figures/examples/wordpress/04-finished-database.rst

**(5/7) Start WordPress installation**

.. include:: /_includes/figures/examples/wordpress/05-installation.rst

**(6/7) Installation success view**

.. include:: /_includes/figures/examples/wordpress/06-finished-installation.rst

**(7/7) Login to Admin panel**

.. include:: /_includes/figures/examples/wordpress/07-login.rst


Next steps
==========

.. include:: /_includes/snippets/examples/next-steps.rst
