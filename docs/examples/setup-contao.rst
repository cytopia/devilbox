.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _example_setup_contao:

************
Setup Contao
************

.. important::
   **You can find a more up-to-date version in the official Contao Documentation:**
   |ext_lnk_example_contao_devilbox_documentation|

This example will use ``composer`` to install Contao CMS from within the Devilbox PHP container.

.. important::
   Using ``composer`` requires the underlying file system to support symlinks. If you
   use **Docker Toolbox** you need to explicitly allow/enable this.
   See below for instructions:

   * Docker Toolbox and :ref:`howto_docker_toolbox_and_the_devilbox_windows_symlinks`

After completing the below listed steps, you will have a working Contao CMS setup ready to be
served via http and https.

.. seealso::
   * |ext_lnk_example_contao_documentation|
   * |ext_lnk_example_contao_devilbox_documentation|


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+-------------------------------------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL                                     |
+==============+==========================+=============+============+=================================================+
| my-contao    | /shared/httpd/my-contao  | my_contao   | loc        | http://my-contao.loc |br| https://my-contao.loc |
+--------------+--------------------------+-------------+------------+-------------------------------------------------+

.. note::
   * Inside the Devilbox PHP container, projects are always in ``/shared/httpd/``.
   * On your host operating system, projects are by default in ``./data/www/`` inside the
     Devilbox git directory. This path can be changed via :ref:`env_httpd_datadir`.

The following Devilbox configuration is required:

+-----------+--------------+------------------------------------------------------------------------------------------------+
| Service   | Version      | Implications                                                                                   |
+===========+==============+================================================================================================+
| Webserver | Apache 2.4   | Apache is required instead of Nginx as Contao provides default ``.htaccess`` files for routing |
+-----------+--------------+------------------------------------------------------------------------------------------------+
| PHP       | PHP-FPM 7.2  | Chosen for this example as it is the Devilbox default version                                  |
+-----------+--------------+------------------------------------------------------------------------------------------------+
| Database  | MariaDB 10.3 | Chosen for this example as it is the Devilbox default version                                  |
+-----------+--------------+------------------------------------------------------------------------------------------------+

.. note:: If you want to use Nginx instead, you will need to adjust the vhost congfiguration accordingly to Contao CMS requirements.


Walk through
============

It will be ready in eight simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install Contao via ``composer``
4. Symlink webroot directory
5. Add MySQL database
6. Setup DNS record
7. Visit http://my-contao.loc in your browser


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

   devilbox@php-7.2.15 in /shared/httpd $ mkdir my-contao

.. seealso:: :ref:`env_tld_suffix`


3. Install Contao
-----------------

Navigate into your newly created vhost directory and install Contao with ``composer``.

.. code-block:: bash

   devilbox@php-7.2.15 in /shared/httpd $ cd my-contao
   devilbox@php-7.2.15 in /shared/httpd/my-contao $ composer create-project contao/managed-edition contao

How does the directory structure look after installation:

.. code-block:: bash

   devilbox@php-7.2.15 in /shared/httpd/my-contao $ tree -L 1
   .
   └── contao

   1 directory, 0 files


4. Symlink web
--------------

Symlinking the actual webroot directory to ``htdocs`` is important. The web server expects every
project's document root to be in ``<vhost dir>/htdocs/``. This is the path where it will serve
the files. This is also the path where your frameworks entrypoint (usually ``index.php``) should
be found.

Some frameworks however provide its actual content in nested directories of unknown levels.
This would be impossible to figure out by the web server, so you manually have to symlink it back
to its expected path.

.. code-block:: bash

   devilbox@php-7.2.15 in /shared/httpd/my-contao $ ln -s contao/web/ htdocs

How does the directory structure look after symlinking:

.. code-block:: bash

   devilbox@php-7.2.15 in /shared/httpd/my-contao $ tree -L 1
   .
   ├── contao
   └── htdocs -> contao/web

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

   devilbox@php-7.2.15 in /shared/httpd/my-contao $ mysql -u root -h mysql -p -e 'CREATE DATABASE my_contao;'


6. DNS record
-------------

If you **have** Auto DNS configured already, you can skip this section, because DNS entries will
be available automatically by the bundled DNS server.

If you **don't have** Auto DNS configured, you will need to add the following line to your
host operating systems ``/etc/hosts`` file (or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-contao.loc

.. seealso::

   * :ref:`howto_add_project_hosts_entry_on_mac`
   * :ref:`howto_add_project_hosts_entry_on_win`
   * :ref:`setup_auto_dns`


7. Open your browser
--------------------

Open your browser at http://my-contao.loc or https://my-contao.loc and follow the installation steps.

7.1 Frontend page
^^^^^^^^^^^^^^^^^

.. include:: /_includes/figures/examples/contao/01-frontend.rst

* Follow the presented instructions and go to:
    - either http://my-contao.loc/contao/install
    - or https://my-contao.loc/contao/install

7.2 Accept license
^^^^^^^^^^^^^^^^^^

Accept the license by clicking on ``Accept license``

.. include:: /_includes/figures/examples/contao/02-license.rst

7.3 Set install tool password
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Set a password for the install tool itself

.. include:: /_includes/figures/examples/contao/03-install-tool-password.rst

7.4 Database setup
^^^^^^^^^^^^^^^^^^

* Database host: ``mysql``
* Database port: ``3306``
* Database user: ``root``
* Database pass: empty (if not otherwise set during Devilbox configuration)

.. include:: /_includes/figures/examples/contao/04-database-setup.rst

7.5 Update database
^^^^^^^^^^^^^^^^^^^

Click on ``update database`` to populate the database.

.. include:: /_includes/figures/examples/contao/05-update-database.rst

7.6 Set admin user
^^^^^^^^^^^^^^^^^^

The admin user is required to setup Contao itself and to gain access to the backend.

.. include:: /_includes/figures/examples/contao/06-create-admin-user.rst

7.7 Finished
^^^^^^^^^^^^

Installation is done, click on the ``Contao back end`` to continue to setup the CMS itself.

.. include:: /_includes/figures/examples/contao/07-finished.rst

7.8 Login
^^^^^^^^^

Use the admin user credentials created earlier to login in.

.. include:: /_includes/figures/examples/contao/08-login-screen.rst


Next steps
==========

.. include:: /_includes/snippets/examples/next-steps.rst
