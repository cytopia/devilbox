.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _example_setup_presta_shop:

****************
Setup PrestaShop
****************

This example will use ``git`` and ``composer`` to install PrestaShop from within the Devilbox
PHP container.

.. important::
   Using ``composer`` requires the underlying file system to support symlinks. If you
   use **Docker Toolbox** you need to explicitly allow/enable this.
   See below for instructions:

   * Docker Toolbox and :ref:`howto_docker_toolbox_and_the_devilbox_windows_symlinks`

After completing the below listed steps, you will have a working PrestaShop setup ready to be
served via http and https.

.. seealso:: |ext_lnk_example_presta_shop|


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+-------------------------------------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL                                     |
+==============+==========================+=============+============+=================================================+
| my-presta    | /shared/httpd/my-presta  | my_presta   | loc        | http://my-presta.loc |br| https://my-presta.loc |
+--------------+--------------------------+-------------+------------+-------------------------------------------------+

.. note::
   * Inside the Devilbox PHP container, projects are always in ``/shared/httpd/``.
   * On your host operating system, projects are by default in ``./data/www/`` inside the
     Devilbox git directory. This path can be changed via :ref:`env_httpd_datadir`.


Walk through
============

It will be ready in eight simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install PrestaShop via ``git`` and ``composer``
4. Symlink webroot directory
5. Add MySQL database
6. Configure datbase connection
7. Setup DNS record
8. Visit http://my-presta.loc in your browser


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

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-presta

.. seealso:: :ref:`env_tld_suffix`


3. Install PrestaShop
---------------------

Navigate into your newly created vhost directory and install PrestaShop with ``git`` and ``composer``.

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ cd my-presta

   # Download PrestaShop with git
   devilbox@php-7.0.20 in /shared/httpd/my-presta $ git clone https://github.com/PrestaShop/PrestaShop

   # Checkout the latest stable git tag
   devilbox@php-7.0.20 in /shared/httpd/my-presta $ cd PrestaShop
   devilbox@php-7.0.20 in /shared/httpd/my-presta $ git checkout 1.7.4.2

   # Install dependencies with Composer
   devilbox@php-7.0.20 in /shared/httpd/my-presta $ composer install

How does the directory structure look after installation:

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-presta $ tree -L 1
   .
   └── PrestaShop

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

   devilbox@php-7.0.20 in /shared/httpd/my-presta $ ln -s PrestaShop/ htdocs

How does the directory structure look after symlinking:

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-presta $ tree -L 1
   .
   ├── PrestaShop
   └── htdocs -> PrestaShop

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

   devilbox@php-7.0.20 in /shared/httpd/my-presta $ mysql -u root -h 127.0.0.1 -p -e 'CREATE DATABASE my_presta;'


6. DNS record
-------------

If you **have** Auto DNS configured already, you can skip this section, because DNS entries will
be available automatically by the bundled DNS server.

If you **don't have** Auto DNS configured, you will need to add the following line to your
host operating systems ``/etc/hosts`` file (or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-presta.loc

.. seealso::

   * :ref:`howto_add_project_hosts_entry_on_mac`
   * :ref:`howto_add_project_hosts_entry_on_win`
   * :ref:`setup_auto_dns`


7. Open your browser
--------------------

All set now, you can visit http://my-presta.loc or https://my-presta.loc in your browser and
follow the installation steps.


Next steps
==========

.. include:: /_includes/snippets/examples/next-steps.rst
