.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _example_setup_expressionengine:

**********************
Setup ExpressionEngine
**********************

This example will use ``curl`` to install ExpressionEngine from within the Devilbox PHP container.

After completing the below listed steps, you will have a working ExpressionEngine setup ready to be
served via http and https.

.. seealso:: |ext_lnk_example_expressionengine_documentation|


**Table of Contents**

.. contents:: :local:


Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+-------------------------------------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL                                     |
+==============+==========================+=============+============+=================================================+
| my-ee        | /shared/httpd/my-ee      | my_ee       | loc        | http://my-ee.loc |br| https://my-ee.loc         |
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
3. Download and extract ExpressionEngine
4. Symlink webroot directory
5. Add MySQL Database
6. Setup DNS record
7. Install ExpressionEngine
8. View your site


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

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-ee

.. seealso:: :ref:`env_tld_suffix`


3. Download and extract ExpressionEngine
----------------------------------------

Navigate into your newly created vhost directory and install ExpressionEngine.

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ cd my-ee
   devilbox@php-7.0.20 in /shared/httpd/my-ee $ curl 'https://expressionengine.com/?ACT=243' -H 'Referer: https://expressionengine.com/' --compressed -o ee.zip
   devilbox@php-7.0.20 in /shared/httpd/my-ee $ mkdir ee
   devilbox@php-7.0.20 in /shared/httpd/my-ee $ unzip ee.zip -d ee

How does the directory structure look after installation:

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-ee $ tree -L 1
   .
   ├── ee
   └── ee.zip

   1 directory, 1 file


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

   devilbox@php-7.0.20 in /shared/httpd/my-ee $ ln -s ee/ htdocs

How does the directory structure look after symlinking it:

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-ee $ tree -L 1
   .
   ├── ee
   ├── ee.zip
   └── htdocs -> ee

   2 directories, 1 file

As you can see from the above directory structure, ``htdocs`` is available in its expected
path and points to the frameworks entrypoint.

.. important::
   When using **Docker Toolbox**, you need to **explicitly allow** the usage of **symlinks**.
   See below for instructions:

   * Docker Toolbox and :ref:`howto_docker_toolbox_and_the_devilbox_windows_symlinks`


5. Add MySQL Database
---------------------

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd/my-ee $ mysql -u root -h 127.0.0.1 -p -e 'CREATE DATABASE my_ee CHARACTER SET utf8 COLLATE utf8_unicode_ci;'

.. note::
   * Remember the database name you create here. It will be needed again during the installation process.

6. DNS record
-------------
If you **have** Auto DNS configured already, you can skip this section, because DNS entries will
be available automatically by the bundled DNS server.

If you **don't have** Auto DNS configured, you will need to add the following line to your
host operating systems ``/etc/hosts`` file (or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-ee.loc

.. seealso::

   * :ref:`howto_add_project_hosts_entry_on_mac`
   * :ref:`howto_add_project_hosts_entry_on_win`
   * :ref:`setup_auto_dns`


7. Install ExpressionEngine
---------------------------

Point your browser to http://my-ee.loc/admin.php or https://my-ee.loc/admin.php and follow the on-screen instructions to install ExpressionEngine.

.. important::
   Once the Installation Wizard is finished, you should rename or remove the system/ee/installer/ directory from your install directory if it was not done by the install wizard.

.. seealso:: |ext_lnk_example_expressionengine_instal_documentation|


8. View Your Site
-----------------

All set now, you can visit your site's homepage by opening http://my-ee.loc or https://my-ee.loc in your browser.

Your control panel will also be available by opening http://my-ee.loc/admin.php or https://my-ee.loc/admin.php in your browser.

.. note::
   * If you chose not to install the default theme, your site’s homepage will appear blank because no templates or content has been created yet.
   * If you’re new to ExpressionEngine, get started with the |ext_lnk_example_expressionengine_primer|


Next steps
==========

.. include:: /_includes/snippets/examples/next-steps.rst
