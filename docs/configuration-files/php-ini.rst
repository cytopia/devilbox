.. _php_ini:

*******
php.ini
*******


``php.ini`` changes are global to all projects, but will only affect the currently selected
PHP version.


**Table of Contents**

.. contents:: :local:


General
=======

You can set custom php.ini configuration options for each PHP version separately.
See the directory structure for PHP configuration directories inside ``./cfg/`` directory:

.. code-block:: bash

   host> ls -l path/to/devilbox/cfg/ | grep 'php-ini'

   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-ini-5.3/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-ini-5.4/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-ini-5.5/
   drwxr-xr-x  2 cytopia cytopia 4096 Apr  3 22:04 php-ini-5.6/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-ini-7.0/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-ini-7.1/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-ini-7.2/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 php-ini-7.3/

Customization is achieved by placing a file into ``cfg/php-ini-X.X/`` (where ``X.X`` stands for
your PHP version).  The file must end by ``.ini`` in order to be sourced by the PHP-FPM server.

Each of the PHP ini configuration directories already contain an example file:
``devilbox-custom.ini-example``, that can simply be renamed to ``devilbox-custom.ini``.
This file holds some example values that can be adjusted or commented out.

In order for the changes to be applied, you will have to restart the Devilbox.


Examples
========

Change memory_limit for PHP 7.1
-------------------------------

The following examples shows you how to change the
`memory_limit <https://secure.php.net/manual/en/ini.core.php#ini.memory-limit>`_ of PHP 7.1 to
4096 MB.

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Navigate to PHP 7.1 config directory
   host> cd cfg/php-ini-7.1

   # Create new ini file
   host> touch memory_limit.ini

Now add the following content to the file:

.. code-block:: ini
   :caption: memory_limit.ini

   [PHP]
   memory_limit = 4096M

In order to apply the changes you need to restart the Devilbox.
You can validate that the changes have taken place by visiting the Devilbox intranet phpinfo page.


Change timeout values for PHP 5.6
---------------------------------

The following examples shows you how to change the
`max_execution_time <https://secure.php.net/manual/en/info.configuration.php#ini.max-execution-time>`_
and `max_input_time <https://secure.php.net/manual/en/info.configuration.php#ini.max-input-time>`_
of PHP 5.6.

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Navigate to PHP 5.6 config directory
   host> cd cfg/php-ini-5.6

   # Create new ini file
   host> touch timeouts.ini

Now add the following content to the file:

.. code-block:: ini
   :caption: timeouts.ini

   [PHP]
   max_execution_time = 180
   max_input_time     = 180

In order to apply the changes you need to restart the Devilbox.
You can validate that the changes have taken place by visiting the Devilbox intranet phpinfo page.
