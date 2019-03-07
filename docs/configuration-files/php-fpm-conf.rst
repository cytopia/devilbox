.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _php_fpm_conf:

************
php-fpm.conf
************


``php-fpm.conf`` changes are global to all projects, but will only affect the currently selected
PHP version.


**Table of Contents**

.. contents:: :local:


General
=======

You can set custom php-fpm.conf configuration options for each PHP version separately.
These changes affect the PHP-FPM process itself, global as well as pool specific configuration can
be set.

.. note::
   The default PHP-FPM pool is called ``www`` in case you want to make changes to it.


See the directory structure for PHP-FPM configuration directories inside ``./cfg/`` directory:

.. code-block:: bash

   host> ls -l path/to/devilbox/cfg/ | grep 'php-fpm'

   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-fpm-5.2/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-fpm-5.3/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-fpm-5.4/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-fpm-5.5/
   drwxr-xr-x  2 cytopia cytopia 4096 Apr  3 22:04 php-fpm-5.6/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-fpm-7.0/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-fpm-7.1/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-fpm-7.2/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-fpm-7.3/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-fpm-7.4/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-fpm-8.0/

Customization is achieved by placing a file into ``cfg/php-fpm-X.X/`` (where ``X.X`` stands for
your PHP version).  The file must end by ``.conf`` in order to be sourced by the PHP-FPM server.

Each of the PHP-FPM conf configuration directories already contains three example file:
``devilbox-fpm.conf-default``, ``devilbox-fpm.conf-pm_dynamic`` and ``devilbox-fpm.conf-pm_ondemand``.

**devilbox-fpm.conf-default**

This file holds the exact settings that are currently in place by each PHP-FPM container.
Copy it (do not simply rename it) to a different file ending by ``.conf`` and start adjusting it.

**devilbox-fpm.conf-pm_dynamic**

This file holds some sane example configuration to switch PHP-FPM scheduler to ``dynamic``
(The default is ``ondemand``).
Copy it (do not simply rename it) to a different file ending by ``.conf`` and start adjusting it.

**devilbox-fpm.conf-pm_ondemand**

This file holds the current default values for the PHP-FPM scheduler which is using ``ondemand``.
Copy it (do not simply rename it) to a different file ending by ``.conf`` and start adjusting it.

**How to apply the settings**

In order for the changes to be applied, you will have to restart the Devilbox.


.. seealso::
   To find out about all available PHP-FPM directives, global or pool specific have a look
   at its documentation: https://secure.php.net/manual/en/install.fpm.configuration.php


Examples
========

Change rlimit core for master process for PHP 7.1
-------------------------------------------------

The following examples shows you how to change the
`rlimit_core <https://secure.php.net/manual/en/install.fpm.configuration.php#rlimit-core-master>`_
of PHP-FPM 7.1 master process to 100.

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Navigate to PHP 7.1 config directory
   host> cd cfg/php-fpm-7.1

   # Create new conf file
   host> touch rlimit.conf

Now add the following content to the file:

.. code-block:: ini
   :caption: rlimit.conf

   [global]
   rlimit_core = 100

.. important:: Note the ``[global]`` section.

In order to apply the changes you need to restart the Devilbox.


Change child process on pool ``www`` for PHP 5.6
------------------------------------------------

The following examples shows you how to change the
`pm <https://secure.php.net/manual/en/install.fpm.configuration.php#pm>`_,
`pm.max_children <https://secure.php.net/manual/en/install.fpm.configuration.php#pm.max-children>`_,
`pm.start_servers <https://secure.php.net/manual/en/install.fpm.configuration.php#pm.start-servers>`_,
`pm.min_spare_servers <https://secure.php.net/manual/en/install.fpm.configuration.php#pm.min-spare-servers>`_
and
`pm.max_spare_servers <https://secure.php.net/manual/en/install.fpm.configuration.php#pm.max-spare-servers>`_
of PHP-FPM 5.6 on pool ``www``.

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Navigate to PHP 5.6 config directory
   host> cd cfg/php-fpm-5.6

   # Create new conf file
   host> touch www_server.conf

Now add the following content to the file:

.. code-block:: ini
   :caption: www_server.conf

   [www]
   ; Pool config
   pm = dynamic
   pm.max_children = 10
   pm.start_servers = 3
   pm.min_spare_servers = 2
   pm.max_spare_servers = 5

.. important:: Note the ``[www]`` section.

In order to apply the changes you need to restart the Devilbox.


Set non-overwritable php.ini values for PHP 7.0
-----------------------------------------------

You can also set ``php.ini`` values that cannot be overwritten by ``php.ini`` or the ``ini_set()``
function of PHP. This might be useful to make sure a specific value is enforced and will not be
changed by some PHP frameworks on-the-fly.

This is achieved by ``php_admin_flag`` and ``php_admin_value`` that are parsed directly to PHP-FPM.

.. seealso:: https://secure.php.net/manual/en/install.fpm.configuration.php

The following example will disable built-in PHP functions globally and non-overwriteable for PHP 7.0.

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Navigate to PHP 7.0 config directory
   host> cd cfg/php-fpm-7.0

   # Create new conf file
   host> touch admin.conf

Now add the following content to the file:

.. code-block:: ini
   :caption: admin.conf

   [www]
   php_admin_value[disable_functions] = link,symlink,popen,exec,system,shell_exec

.. important:: Note the ``[www]`` section.

.. important::
   This kind of setting only has affects PHP files served through PHP-FPM, when you run php
   on the command line, this setting will be ignored.

.. important::
   Be aware that none of your projects can use the above disabled functions anymore.
   They will simply not exist for PHP 7.0 after that configuration took affect.

In order to apply the changes you need to restart the Devilbox.



