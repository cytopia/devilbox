.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _customize_php_globally:

**********************
Customize PHP globally
**********************

PHP settings can be applied globally to all projects, but are bound to a specific PHP version.
This means every PHP version can have its own profile of customized settings.

.. note::
   By default, all PHP container use roughly the same settings. This might only differ if some
   options or modules do not exist in a specific container.

**Table of Contents**

.. contents:: :local:


Configure PHP settings globally
===============================

PHP settings can either be applied in its ``php.ini`` configuration file or through the
PHP-FPM configuration itself via ``php_value`` and ``php_flag``.

Settings in ``php.ini`` are also picked up by the PHP command line tool, whereas ``php_value``
and ``php_flag`` settings are only valid for requests over the webserver.

This means you can set different values, when executing command line tasks and when the
application is run through the browser.


Settings via php.ini
--------------------

To configure PHP globally via php.ini follow the provided link:

.. seealso:: :ref:`php_ini`

Settings via php-fpm.conf
-------------------------

To configure PHP globally via PHP-FPM follow the provided link:

.. seealso:: :ref:`php_fpm_conf`


Configure non-overwritable settings globally
============================================

Settings defined via ``php.ini``, ``php_value`` and ``php_flag`` are applied globally, however
they can still be overwritten by any project via the PHP function ``ini_set()``.

If you want to create PHP settings and force them, so no application can accidentally or on purpose
overwrite them, you need to use ``php_admin_value`` and ``php_admin_flag``.

.. important::
   Keep in mind that those settings are not picked up by the command line execution of PHP,
   but only through the browser.


To configure PHP globally and non-overwritable via PHP-FPM follow the provided link:

.. seealso:: :ref:`php_fpm_conf`


Configure loaded PHP modules
============================

The ``.env`` file offers the option to specify what PHP modules to enable or disable specifically.

.. seealso:: :ref:`enable_disable_php_modules`


Configure PHP-FPM service
=========================

You can also configure the PHP-FPM service itself. Settings can be applied for the core service
as well as for the pool. This is useful if you need to adjust performance settings such as number
of running child processes, file- and memory limits, timeouts and many more.

Be sure to read up on the PHP-FPM documentation to understand what you are doing.

.. seealso:: :ref:`php_fpm_conf`
