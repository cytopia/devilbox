.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _example_setup_photon_cms:

****************
Setup Photon CMS
****************

This example will use ``photon`` cli to install Photon CMS from within the Devilbox PHP container.

After completing the below listed steps, you will have a working Photon CMS setup ready to be
served via http and https.

.. seealso:: |ext_lnk_example_photon_cms|


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+-------------------------------------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL                                     |
+==============+==========================+=============+============+=================================================+
| my-photon    | /shared/httpd/my-photon  | blog        | loc        | http://my-photon.loc |br| https://my-photon.loc |
+--------------+--------------------------+-------------+------------+-------------------------------------------------+

.. note::
   * Inside the Devilbox PHP container, projects are always in ``/shared/httpd/``.
   * On your host operating system, projects are by default in ``./data/www/`` inside the
     Devilbox git directory. This path can be changed via :ref:`env_httpd_datadir`.


Walk through
============

It will be ready in six simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Install Photon
4. Symlink webroot directory
5. Setup DNS record
6. Visit http://my-photon.loc in your browser


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

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-photon

.. seealso:: :ref:`env_tld_suffix`


3. Install Photon
------------------

Navigate into your newly created vhost directory and install Photom CMS with ``photon`` cli.

.. note::
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

How does the directory structure look after installation:

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-photon $ tree -L 1
   .
   └── blog

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

   devilbox@php-7.0.20 in /shared/httpd/my-photon $ ln -s blog/public/ htdocs

How does the directory structure look after symlinking:

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-photon $ tree -L 1
   .
   ├── blog
   └── htdocs -> blog/public

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

   127.0.0.1 my-photon.loc

.. seealso::

   * :ref:`howto_add_project_hosts_entry_on_mac`
   * :ref:`howto_add_project_hosts_entry_on_win`
   * :ref:`setup_auto_dns`


6. Open your browser
--------------------

Open your browser at http://my-photon.loc or https://my-photon.loc


Next steps
==========

.. include:: /_includes/snippets/examples/next-steps.rst
