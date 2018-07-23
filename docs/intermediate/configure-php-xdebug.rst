.. _configure_php_xdebug:

********************
Configure PHP Xdebug
********************

This section explains in depth how to enable and use PHP Xdebug with the Devilbox.


**Table of Contents**

.. contents:: :local:


Introduction
============

In order to have a working Xdebug, you need to ensure two things:

1. **PHP Xdebug** must be configured and enabled in PHP itself
2. Your **IDE/editor** must be configured and requires a way talk to PHP

Configuring PHP Xdebug will slightly differ when configuring it for a dockerized environment.
This is due to the fact that Docker versions on different host os have varying implementations of
how they connect back to the host.

Most IDE or editors will also require different configurations for how they talk to PHP Xdebug.
This is at least most likely the case for ``xdebug.idekey``.


Configure PHP container for Xdebug
==================================

.. toctree::
   :glob:
   :maxdepth: 1
   :hidden:

   /intermediate/configure-php-xdebug/php-xdebug-options
   /intermediate/configure-php-xdebug/xdebug-*

The following gives you a step-by-step guide on how to setup PHP Xdebug for the Devilbox depending
on what host operating system you are using.

Be reminded that PHP configuration is always done per version, i.e. having it configured for
PHP 7.2, does not enable it for any other versions.

.. seealso::
   * :ref:`configure_php_xdebug_options`
   * :ref:`configure_php_xdebug_lin`
   * :ref:`configure_php_xdebug_mac`
   * :ref:`configure_php_xdebug_win`
   * :ref:`configure_php_xdebug_docker_toolbox` (Mac or Windows)


Configure your IDE/editor for Xdebug
====================================

After you have setup PHP Xdebug as referenced above, you can continue to configure your currently
used IDE/editor.

Most IDE/editors will usually be configured in a very similar way, which comes down to two main
settings;

Path mapping
------------

The path mapping is a mapping between the file path on your host operating system and the one
inside the PHP Docker container.

The path on your host operating system is the one you have set in :ref:`env_httpd_datadir`.
In case you have set a relative path in ``.env``, ensure to retrieve the absolute path of it when
setting up your IDE config.

The path inside the PHP Docker container is always ``/shared/httpd``.

.. important::
   Even though your path in ``.env`` for :ref:`env_httpd_datadir` might be relative (e.g. ``./data/www``),
   you need to get the actualy absolute path of it, when setting up your IDE.

IDE key
-------
This is the value you have set in ``xdebug.ini`` for ``xdebug.idekey``. In the example of this
tutorial, the value was set to ``PHPSTORM``.


.. toctree::
   :glob:
   :maxdepth: 1
   :hidden:

   /intermediate/configure-php-xdebug/editor-*

Configuration
-------------

.. seealso::
   * :ref:`configure_php_xdebug_editor_atom`
   * :ref:`configure_php_xdebug_editor_phpstorm`
   * :ref:`configure_php_xdebug_editor_sublime3`
   * :ref:`configure_php_xdebug_editor_vscode`
