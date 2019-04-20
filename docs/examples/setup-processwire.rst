.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _example_setup_processwire:

*****************
Setup ProcessWire
*****************

This example will use ``composer`` to install ProcessWire from within the Devilbox PHP container.

.. important::
   Using ``composer`` requires the underlying file system to support symlinks. If you
   use **Docker Toolbox** you need to explicitly allow/enable this.
   See below for instructions:

   * Docker Toolbox and :ref:`howto_docker_toolbox_and_the_devilbox_windows_symlinks`

After completing the below listed steps, you will have a working ProcessWire setup ready to be
served via http and https.

.. seealso:: |ext_lnk_example_processwire_documentation|


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+---------------------------------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL                                 |
+==============+==========================+=============+============+=============================================+
| my-pw        | /shared/httpd/my-pw      | my_pw       | loc        | http://my-pw.loc |br| https://my-pw.loc     |
+--------------+--------------------------+-------------+------------+---------------------------------------------+

.. note::
   * Inside the Devilbox PHP container, projects are always in ``/shared/httpd/``.
   * On your host operating system, projects are by default in ``./data/www/`` inside the
     Devilbox git directory. This path can be changed via :ref:`env_httpd_datadir`.

The following Devilbox configuration is required:

+-----------+--------------+-----------------------------------------------------------------------------------------------------+
| Service   | Version      | Implications                                                                                        |
+===========+==============+=====================================================================================================+
| Webserver | Apache 2.4   | Apache is required instead of Nginx as ProcessWire provides default ``.htaccess`` files for routing |
+-----------+--------------+-----------------------------------------------------------------------------------------------------+
| PHP       | PHP-FPM 7.2  | Chosen for this example as it is the Devilbox default version                                       |
+-----------+--------------+-----------------------------------------------------------------------------------------------------+
| Database  | MariaDB 10.3 | Chosen for this example as it is the Devilbox default version                                       |
+-----------+--------------+-----------------------------------------------------------------------------------------------------+

.. note:: If you want to use Nginx instead, you will need to adjust the vhost congfiguration accordingly to ProcessWire requirements.


Walk through
============

It will be ready in eight simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install ProcessWire via ``composer``
4. Symlink webroot directory
5. Setup DNS record
6. Open your browser
7. Step through guided web installation


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

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-pw

.. seealso:: :ref:`env_tld_suffix`


3. Install ProcessWire
----------------------

Navigate into your newly created vhost directory and install ProcessWire with ``composer``.

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ cd my-pw
   devilbox@php-7.0.20 in /shared/httpd/my-pw $ composer create-project processwire/processwire

How does the directory structure look after installation:

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-pw $ tree -L 1
   .
   └── processwire

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

   devilbox@php-7.0.20 in /shared/httpd/my-pw $ ln -s ln -s processwire htdocs

How does the directory structure look after symlinking:

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-pw $ tree -L 1
   .
   ├── processwire
   └── htdocs -> processwire

   2 directories, 0 files

As you can see from the above directory structure, ``htdocs`` is available in its expected
path and points to the frameworks entrypoint.

.. important::
   When using **Docker Toolbox**, you need to **explicitly allow** the usage of **symlinks**.
   See below for instructions:

   * Docker Toolbox and :ref:`howto_docker_toolbox_and_the_devilbox_windows_symlinks`


5. DNS record
-------------

If you **have** Auto DNS configured already, you can skip this section, because DNS entries will
be available automatically by the bundled DNS server.

If you **don't have** Auto DNS configured, you will need to add the following line to your
host operating systems ``/etc/hosts`` file (or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-pw.loc

.. seealso::

   * :ref:`howto_add_project_hosts_entry_on_mac`
   * :ref:`howto_add_project_hosts_entry_on_win`
   * :ref:`setup_auto_dns`


6. Open your browser
--------------------

Open your browser at http://my-pw.loc or https://my-pw.loc.


7. Step through guided web installation
---------------------------------------

.. include:: /_includes/figures/examples/processwire/01-install-banner.rst

.. include:: /_includes/figures/examples/processwire/02-profile-choice.rst

.. include:: /_includes/figures/examples/processwire/03-default-profile.rst

.. include:: /_includes/figures/examples/processwire/04-compat-check.rst

.. include:: /_includes/figures/examples/processwire/05-general-setup.rst

.. include:: /_includes/figures/examples/processwire/06-admin-setup.rst

.. include:: /_includes/figures/examples/processwire/07-finished.rst


Next steps
==========

.. include:: /_includes/snippets/examples/next-steps.rst
