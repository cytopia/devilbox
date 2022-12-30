.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _enable_disable_php_modules:

**************************
Enable/disable PHP modules
**************************

**Table of Contents**

.. contents:: :local:

.. seealso::
   https://github.com/devilbox/docker-php-fpm/blob/master/doc/php-modules.md
     Follow the link to see all available PHP modules for each different PHP-FPM server version.


Enabled PHP modules
===================

At the moment all PHP modules are enabled by default except `ioncube <http://www.ioncube.com/>`_,
So this one is the only one you can currently enable. To do so follow the steps provided below:

1. Stop the Devilbox
2. Enable modules in ``.env`` under ``PHP_MODULES_ENABLE``

   .. code-block:: bash
      :caption: .env

      # Enable Ioncube
      PHP_MODULES_ENABLE=ioncube

3. Start the Devilbox

.. seealso:: :ref:`env_file_php_modules_enable`


Disable PHP modules
===================

If you feel there are currently too many modules loaded and you want to unload some of them by
default, you can do so via a comma separated list in ``.env``.

1. Stop the Devilbox
2. Disable modules in ``.env`` under ``PHP_MODULES_DISABLE``

   .. code-block:: bash
      :caption: .env

      # Disable Xdebug, Imagick and Swoole
      PHP_MODULES_DISABLE=xdebug,imagick,swoole

3. Start the Devilbox

.. seealso:: :ref:`env_file_php_modules_disable`


Roadmap
=======

.. todo::
   In order to create a performent, secure and sane default PHP-FPM server, only really required
   modules should be enabled by default. The rest is up to the user to enable others as needed.

   The current discussion about default modules can be found at the following Github issue.
   Please participate and give your ideas: https://github.com/cytopia/devilbox/issues/299
