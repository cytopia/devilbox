.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _vhost_gen_customize_specific_virtual_host:

*******************************
Customize specific virtual host
*******************************


**Table of Contents**

.. contents:: :local:

vhost-gen
=========

What is vhost-gen
-----------------

``vhost-gen`` is a python script which is able to dynamically generate Apache 2.2, Apache 2.4 and
Nginx virtual host or reverse proxy configuration files.

It is intended to be used by other means of automation such as change of directories or change of
listening ports.

.. seealso::

   If you intend to use ``vhost-gen`` for your own projects, have a look at its project page and
   its sister projects:

   * |ext_lnk_project_vhost_gen|
   * |ext_lnk_project_watcherd|
   * |ext_lnk_project_watcherp|


Where do I find templates
-------------------------

The latest version of vhost-gen templates are shipped in the Devilbox git directory under
``cfg/vhost-gen/``.


How does it work
----------------

By default new virtual hosts are automatically generated and enabled by vhost-gen and watcherp
using the vanilla templates which are glued into the webserver Docker images. The used templates
are exactly the same as what you will find in ``cfg/vhost-gen/``.

This ensures to have equal and sane default virtual host for all of your projects.
If you want to have a different virtual host configuration for a specific project of yours,
you can copy a corresponding template into your project directory and adjust it to your needs.


How to apply templates to a specific project
--------------------------------------------

Customizing a virtual host via vhost-gen template is generally done in four steps:

1. Retrieve or set template directory value in ``.env``.
2. Copy webserver template to project template directory
3. Adjust template
4. Make Devilbox pick up those changes


Let's assume the following default values and one project named ``project-1``:

+-------------------------------+-------------------------------------------------------+
| Variable                      | Value                                                 |
+===============================+=======================================================+
| Devilbox path                 | ``/home/user/devilbox``                               |
+-------------------------------+-------------------------------------------------------+
| Templates to copy from        | ``/home/user/devilbox/cfg/vhost-gen``                 |
+-------------------------------+-------------------------------------------------------+
| Project name                  | ``project-1``                                         |
+-------------------------------+-------------------------------------------------------+
| :ref:`env_httpd_template_dir` | ``.devilbox`` (default value)                         |
+-------------------------------+-------------------------------------------------------+
| :ref:`env_httpd_datadir`      | ``./data/www`` (default value)                        |
+-------------------------------+-------------------------------------------------------+

Those assumed settings will result in the following directory paths which must be created by you:

+-------------------------------+-------------------------------------------------------+
| What                          | Path                                                  |
+===============================+=======================================================+
| Project directory path        | ``/home/user/devilbox/data/www/project-1/``           |
+-------------------------------+-------------------------------------------------------+
| Project template path         | ``/home/user/devilbox/data/www/project-1/.devilbox/`` |
+-------------------------------+-------------------------------------------------------+

1. Retrieve or set template directory value
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

By default the :ref:`env_httpd_template_dir` value is ``.devilbox``. This is defined in the
``.env`` file. Feel free to change it to whatever directory name you prefer, but keep in mind that
it will change the `Project template path` which you need to create yourself.

For this example we will keep the default value for the sake of simplicity: ``.devilbox``.

.. note::
   The :ref:`env_httpd_template_dir` value is a global setting and will affect all projects.


2. Copy webserver template to project template directory
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

First you need to ensure that the :ref:`env_httpd_template_dir` exists wihin you project.

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd /home/user/devilbox

   # Create template directory in your project
   host> mkdir ./data/www/project-1/.devilbox

Then you can copy the templates.

.. code-block:: bash

   host> cp cfg/vhost-gen/*.yml-example-* ./data/www/project-1/.devilbox

.. note::
   You actually only need to copy the template of your chosen webserver (either Apache 2.2,
   Apache 2.4 or Nginx), however it is good practice to copy all templates and also adjust
   all templates synchronously. This allows you to change web server versions and still
   keep your virtual host settings.

3. Adjust template
^^^^^^^^^^^^^^^^^^

At this stage you can start adjusting the template. Either do that for the webserver version you
have enabled via :ref:`env_httpd_server`:
``/home/user/devilbox/data/www/project-1/.devilbox/apache22.yml``.
``/home/user/devilbox/data/www/project-1/.devilbox/apache24.yml``,
``/home/user/devilbox/data/www/project-1/.devilbox/nginx.yml`` or do it for all of them
synchronously.

.. note:: What exactly to change will be explained later.

4. Make Devilbox pick up those changes
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Whenever you change a project vhost template or the :ref:`env_httpd_template_dir` value,
you need to restart the Devilbox.

.. note:: It is also possible to do it without a restart which will be explained later.


Templates explained
===================

Before the templates are explained, have a look at the following table to find out what template
needs to be in place for what webserver version.

+----------------+------------------+
| Webserver      | Template         |
+================+==================+
| Apache 2.2     | ``apache22.yml`` |
+----------------+------------------+
| Apache 2.4     | ``apache24.yml`` |
+----------------+------------------+
| Nginx stable   | ``nginx.yml``    |
+----------------+------------------+
| Nginx mainline | ``nginx.yml``    |
+----------------+------------------+

.. note::
   Nginx stable and mainline share the same template as their syntax has no special
   differences, whereas Apache 2.2 and Apache 2.4 have slight differences in syntax and therefore
   require two different templates.


Ensure yaml files are valid
---------------------------

.. warning::
   Pay close attention that you do not use TAB (``\t``) characters for indenting the vhost-gen
   yaml files. Some editors might automatically indent using TABs, so ensure they are replaced
   with spaces. If TAB characters are present, those files become invalid and won't work.
   https://github.com/cytopia/devilbox/issues/142

   You can use the bundled ``yamllint`` binary inside the container to validate your config.


.. code-block:: bash
   :emphasize-lines: 13-16

   # Navigate to the Devilbox directory
   host> cd /home/user/devilbox

   # Enter the PHP container
   host> ./shell.sh

   # Go to your project's template directory
   devilbox@php-7.0.19 in /shared/httpd $ cd project-1/.devilbox

   # Check the syntax of apache22.yml
   devilbox@php-7.0.19 in /shared/httpd/project-1/.devilbox $ yamllint apache22.yml

   108:81    error    line too long (90 > 80 characters)  (line-length)
   139:81    error    line too long (100 > 80 characters)  (line-length)
   140:81    error    line too long (84 > 80 characters)  (line-length)
   142:81    error    line too long (137 > 80 characters)  (line-length)

Long line errors can safely be ignored.


Template variables
------------------

Every uppercase string which begins with ``__`` and ends by ``__`` (such as ``__PORT__``) is a
variable that will be replaced by a value. Variables can contain a string, a multi-line string or
can also be replaced to an empty value.

Global variables
^^^^^^^^^^^^^^^^

There are `global variables` that are determined by the command line arguments of ``vhost-gen``
itself or are elsewhere replaced by the Devilbox webserver container such as:

* ``__PORT__``
* ``__DEFAULT_VHOST__``
* ``__VHOST_NAME__``
* ``__ACCESS_LOG__``
* ``__ERROR_LOG__``

vHost type variable
^^^^^^^^^^^^^^^^^^^

There are also two variables that will be replaced according to the type of the vhost - either
a normal vhost or a reverse proxy vhost.

* ``__VHOST_DOCROOT__``
* ``__VHOST_PROXY__``

The Devilbox always uses a normal vhost by default, so the ``__VHOST_DOCROOT__`` variable will be
replaced by what the ``vhost_type.docroot`` section provides.
The ``vhost_type.rproxy`` will be ignored and ``__VHOST_PROXY__`` will be replaced by an empty
string.

Feature variables
^^^^^^^^^^^^^^^^^

All other variables will be replaced by what is provided in the ``features:`` section.
All subsections of ``features:`` have corresponding variables in the following form:

+-------------------+-----------------------+
| Feature directive | Variable name pattern |
+===================+=======================+
| ``lower_case:``   | ``__UPPER_CASE__``    |
+-------------------+-----------------------+

As an example, the contents of the ``features.php_fpm:`` section will be replaced into the
``__PHP_FPM__`` variable.

Template structure
------------------

Each vhost-gen template has three main yaml directives:

1. ``vhost:``
2. ``vhost_type:``
3. ``features:``

1. ``vhost:``
^^^^^^^^^^^^^

The ``vhost:`` directive will contain the final resulting virtual host configuration that will
be applied by the webserver. Each of its containing variables will be substituted and its content
will be copied to a webserver configuration file.

By default the ``vhost:`` section has variables from global scope, from the ``vhost_type:``
section and from the ``features:`` section.

You can also fully hard-code your webserver configuration without any variables. This way you
can specify a fully self-brewed webserver configuration. An example for Apache 2.2 could
look like this:

.. code-block:: yaml

   vhost: |
     <VirtualHost *:80>
         ServerName   example.com

         CustomLog  "/var/log/apache/access.log" combined
         ErrorLog   "/var/log/apache/error.log"

         DocumentRoot "/shared/httpd/project-1/htdocs"
         <Directory "/shared/httpd/project-1/htdocs">
             DirectoryIndex index.php

             AllowOverride All
             Options All

             RewriteEngine on
             RewriteBase /

             Order allow,deny
             Allow from all
         </Directory>

         ProxyPassMatch ^/(.*\.php(/.*)?)$ fcgi://127.0.0.1:9000/shared/httpd/project-1/htdocs/$1
     </VirtualHost>

2. ``vhost_type:``
^^^^^^^^^^^^^^^^^^

The ``vhost_type:`` contains ``docroot`` and ``rproxy``. The Devilbox only makes use of ``docroot``
which holds the definition of a normal vhost. Its content will be replaced into the
``__VHOST_DOCROOT__`` variable.

The ``rproxy`` section will be ignored and the ``__VHOST_RPROXY__`` variable will contain an empty
value.

+----------------------+------------------------------+
| vHost Type section   | Variable to be replaced into |
+======================+==============================+
| ``docroot:``         | ``__VHOST_DOCROOT__``        |
+----------------------+------------------------------+
| ``rproxy:``          | ``__VHOST_RPROXY__`` (empty) |
+----------------------+------------------------------+


3. ``features:``
^^^^^^^^^^^^^^^^

This section contains directives that will all be replaced into ``vhost:`` variables.

+----------------------+------------------------------+
| Feature section      | Variable to be replaced into |
+======================+==============================+
| ``php_fpm:``         | ``__PHP_FPM__``              |
+----------------------+------------------------------+
| ``alias:``           | ``__ALIASES__``              |
+----------------------+------------------------------+
| ``deny:``            | ``__DENIES__``               |
+----------------------+------------------------------+
| ``server_status:``   | ``__SERVER_STATUS__``        |
+----------------------+------------------------------+
| ``xdomain_request:`` | ``__XDOMAIN_REQ__``          |
+----------------------+------------------------------+


.. _custom_vhost_apply_vhost_gen_changes:

Apply Changes
=============

After having edited your vhost-gen template files, you still need to apply these changes.
This can be achieved in two ways:

1. Rename your project directory back and forth
2. Restart the Devilbox


Rename project directory
------------------------

.. code-block:: bash

   # Navigate to the data directory
   host> /home/user/devilbox/data/www

   # Rename your project to something else
   host> mv project-1 project-1.tmp

   # Rename your project to its original name
   host> mv project-1.tmp project-1

If you want to understand what is going on right now, check the docker logs for the web server.

.. code-block:: bash

   # Navigate to the devilbox directory
   host> /home/user/devilbox

   # Check docker logs
   host> docker-compose logs httpd

   httpd_1  | vhostgen: [2018-03-18 11:46:52] Adding: project-1.tmp.loc
   httpd_1  | watcherd: [2018-03-18 11:46:52] [OK]  ADD: succeeded: /shared/httpd/project-1.tmp
   httpd_1  | watcherd: [2018-03-18 11:46:52] [OK]  DEL: succeeded: /shared/httpd/project-1
   httpd_1  | watcherd: [2018-03-18 11:46:52] [OK]  TRIGGER succeeded: /usr/local/apache2/bin/httpd -k restart

   httpd_1  | vhostgen: [2018-03-18 11:46:52] Adding: project-1loc
   httpd_1  | watcherd: [2018-03-18 11:46:52] [OK]  ADD: succeeded: /shared/httpd/project-1
   httpd_1  | watcherd: [2018-03-18 11:46:52] [OK]  DEL: succeeded: /shared/httpd/project-1.tmp
   httpd_1  | watcherd: [2018-03-18 11:46:52] [OK]  TRIGGER succeeded: /usr/local/apache2/bin/httpd -k restart

**What happened?**

The directory changes have been noticed and a new virtual host has been created. This time however
your new vhost-gen template has been read and the changes have applied.

.. note::
   Renaming a project directory will only affect a single project. In case your change the
   value of :ref:`env_httpd_template_dir` it will affect all projects and you would have to
   rename all project directories. In this case it is much faster to just restart the Devilbox.


Restart the Devilbox
--------------------

Stop the Devilbox and start it up again.


Further readings
================

.. seealso::
   Have a look at the following examples which involve customizing vhost-gen templates:

   * :ref:`vhost_gen_virtual_host_templates`
   * :ref:`vhost_gen_example_add_sub_domains`
