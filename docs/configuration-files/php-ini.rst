.. _php_ini:

*******
php.ini
*******


PHP.ini changes are a global option and will affect all projects simultaneously.


**Table of Contents**

.. contents:: :local:


General
=======

You can set custom php.ini configuration options for each PHP version separately.

This is achieved by placing a file into ``cfg/php-fpm-X.X/`` (where ``X.X`` stands for your PHP version).
The file must end by ``.ini`` in order to be sourced by the PHP-FPM server.

Each of the PHP ini configuration directories already contain an example file:
``devilbox-custom.ini-example``, that can simply be renamed to ``devilbox-custom.ini``.
This file holds same example values that can be adjusted or commented out.

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
    host> cd cfg/php-fpm/7.1

    # Create new ini file
    host> touch memory_limit.ini

Now add the following content to the file:

.. code-block:: ini
    :name: memory_limit.ini
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
    host> cd cfg/php-fpm/5.6

    # Create new ini file
    host> touch timeouts.ini

Now add the following content to the file:

.. code-block:: ini
    :name: timeouts.ini
    :caption: timeouts.ini

    [PHP]
    max_execution_time = 180
    max_input_time     = 180

In order to apply the changes you need to restart the Devilbox.
You can validate that the changes have taken place by visiting the Devilbox intranet phpinfo page.
