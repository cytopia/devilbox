.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _example_setup_phalcon:

*************
Setup Phalcon
*************

This example will use ``phalcon`` to install Phalcon from within the Devilbox PHP container.

After completing the below listed steps, you will have a working Phalcon setup ready to be
served via http and https.

.. seealso:: |ext_lnk_example_phalcon_documentation|


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+---------------------------------------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL                                       |
+==============+==========================+=============+============+===================================================+
| my-phalcon   | /shared/httpd/my-phalcon | n.a.        | loc        | http://my-phalcon.loc |br| https://my-phalcon.loc |
+--------------+--------------------------+-------------+------------+---------------------------------------------------+

.. note::
   * Inside the Devilbox PHP container, projects are always in ``/shared/httpd/``.
   * On your host operating system, projects are by default in ``./data/www/`` inside the
     Devilbox git directory. This path can be changed via :ref:`env_httpd_datadir`.


Walk through
============

It will be ready in six simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install Phalcon
4. Symlink webroot directory
5. Setup DNS record
6. Visit http://my-phalcon.loc in your browser
7. (Nginx) Create custom vhost config file


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

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-phalcon

.. seealso:: :ref:`env_tld_suffix`


3. Install Phalcon
------------------

Navigate into your newly created vhost directory and install Phalcon with ``phalcon`` cli.

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ cd my-phalcon
   devilbox@php-7.0.20 in /shared/httpd/my-phalcon $ phalcon project phalconphp

How does the directory structure look after installation:

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-phalcon $ tree -L 1
   .
   └── phalconphp

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

   devilbox@php-7.0.20 in /shared/httpd/my-phalcon $ ln -s phalconphp/public/ htdocs

How does the directory structure look after symlinking:

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-phalcon $ tree -L 1
   .
   ├── phalconphp
   └── htdocs -> phalconphp/public

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

   127.0.0.1 my-phalcon.loc

.. seealso::

   * :ref:`howto_add_project_hosts_entry_on_mac`
   * :ref:`howto_add_project_hosts_entry_on_win`
   * :ref:`setup_auto_dns`


6. Open your browser
--------------------

Open your browser at http://my-phalcon.loc or https://my-phalcon.loc


7. Create custom vhost config file (Nginx Only)
-----------------------------------------------

By default routes will not work if using Nginx. To fix this, you will need to create a custom vhost configuration.

In your project folder, you will need to create a folder called .devilbox unless you changed `HTTPD_TEMPLATE_DIR` in your .env

Copy the default nginx config from ./cfg/vhost-gen/nginx.yml-example-vhost to ./data/www/my-project/.devilbox/nginx.yml

Carefully edit the nginx.yml file and change:

``try_files $uri $uri/ /index.php$is_args$args;``
to
``try_files $uri $uri/ /index.php?_url=$uri&$args;``

and

``location ~ \.php?$ {``
to
``location ~ [^/]\.php(/|$) {``

save the file as nginx.yml and ensure not to use any tabs in the file or devilbox will not use the custom configuration. You can use ``yamllint nginx.yml`` whilst inside the Devilbox shell to check the file before restarting devilbox.


Next steps
==========

.. include:: /_includes/snippets/examples/next-steps.rst
