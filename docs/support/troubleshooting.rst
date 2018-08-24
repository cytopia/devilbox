.. _troubleshooting:

***************
Troubleshooting
***************

This section will contain common problems and how to resolve them.
It will grow over time once there are more issues reported.

.. seealso::
   * :ref:`howto`
   * :ref:`faq`

.. important::

   :ref:`update_the_devilbox`
     Issues are constantly being fixed. Before attempting to spend too much time digging into
     your issue, make sure you are running the latest git changes and have pulled the latest
     Docker images.

     Also keep in mind that configuration files might change, so ensure to diff the default ones
     against your currently active ones for added, removed or changed values.


**Table of Contents**

.. contents:: :local:


General
=======

Sudden unexplained problems on Windows
--------------------------------------

In case something stopped working for no reason, check out other Docker container. If you
experience similar issues as well, check for any unattended Windows updates or
updates to Docker itself. If those exist, try to revert them and see if that was the cause.

I heard many bug stories from fellow Windows users so far.
A good contact point for that is the Docker forum itself: https://forums.docker.com/c/docker-for-windows

A few general things you should always do before attempting to open up issues are:

**1. Used default settings from env-example**

   Try using the exact settings from ``env-example`` as variables might have been updated in git.

   .. code-block:: bash

      # Ensure everything is stopped
      host> cp env-example .env

**2. Clean, updated and minimal start**

   .. code-block:: bash

      # Ensure everything is stopped
      host> docker-compose stop
      host> docker-compose kill
      host> docker-compose rm -f

      # Ensure everything is updated
      host> docker-compose pull

      # Start again
      host> docker-compose up php httpd bind

**3. Reset Docker credentials:**

   As it might sound strange, this fix might indeed solve a lot of problems on Windows.
   Go to your Docker settings and reset your credentials.

**4. Shared volumes:**

   Ensure all your Devilbox data (Devilbox directory and project directory) are within the volumes
   that are shared by Docker. If not add those in the Docker settings.


Address already in use
----------------------

One of the Docker container wants to bind to a port on the host system which is already taken.
Figure out what service is listening on your host system and shut it down or change the port
of the affected service in the Devilbox ``.env`` file.

Some examples of common error messages:

.. code-block:: bash

   Error starting userland proxy: Bind for 0.0.0.0:80: unexpected error (Failure EADDRINUSE)


Unable to finish Pulling as unauthorized: incorrect username or password
------------------------------------------------------------------------

This error might occur if you are already logged into a different Docker repository.
To fix this error, sign out of your currently logged in repository and try again.

.. seealso:: https://github.com/cytopia/devilbox/issues/223


localhost or 127.0.0.1 not found
--------------------------------

If you are using Docker Toolbox, the Devilbox intranet is not available on localhost or 127.0.0.1,
but rather on the IP address of the Docker Toolbox machine.

.. seealso:: :ref:`howto_find_docker_toolbox_ip_address`


ERROR: Version in "./docker-compose.yml" is unsupported
-------------------------------------------------------

This simply means your Docker and/or Docker Compose versions are outdated.

.. seealso:: :ref:`prerequisites`

DNS issues
==========

zone 'localhost': already exists previous definition
----------------------------------------------------

.. code-block:: bash

   bind_1 | /etc/bind/devilbox-wildcard_dns.localhost.conf:1:
   zone 'localhost': already exists previous definition:
   /etc/bind/named.conf.default-zones:10

This error occurs when using ``localhost`` as the :ref:`env_tld_suffix`.

.. seealso::

   * :ref:`env_tld_suffix`
   * https://github.com/cytopia/devilbox/issues/291


SSL issues
==========

unable to get local issuer certificate
--------------------------------------

.. code-block:: bash

   Errors occurred when trying to connect to www.example.com:
   cURL error 77: error setting certificate verify locations: CAfile: certificate ./ca/cacert.pem CApath: /etc/ssl/certs

This issue might arise if you set :ref:`env_tld_suffix` to an official top level domain such as ``.com``.
What happens is that the bundled DNS server does a catch-all on the TLD and redirects all name
resolution to the Devilbox's PHP container IP address.

If you want to access ``https://www.example.com`` in that case, the request goes to the PHP
container which does not have a valid SSL certificate for that domain.

**Do not user official TLD's** for :ref:`env_tld_suffix`.

.. seealso::

   * :ref:`env_tld_suffix`
   * https://github.com/cytopia/devilbox/issues/275


Web server issues
=================

Warning: DocumentRoot [/var/www/default/htdocs/] does not exist
---------------------------------------------------------------

This error is most likely to only occur on Docker for Windows and is just a result of not working
volumes mounts.

.. seealso:: https://forums.docker.com/t/volume-mounts-in-windows-does-not-work/10693


403 forbidden
-------------

This error might occur for the Devilbox intranet or custom created projects.

File and directory permissions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

On of the cause could be wrongly set file and directory permissions.

First ensure the cloned git directory is readable for users, groups and others.

For the Devilbox intranet, ensure the ``.devilbox/`` directory is readable for users, groups and
others. Also check files and directories within.

For projects, ensure an ``index.php`` or ``index.html`` exists and that all files and directories
are readable for users, groups and others.

Shared volumes
^^^^^^^^^^^^^^

This might additionally occur on MacOS or Windows due to the Devilbox and/or its projects not
being in the standard location of Docker Shared volumes.

Check your Docker settings to allow shared volumes for the path of the Devilbox and its projects.


504 Gateway timeout
-------------------

This error occurs when the upstream PHP-FPM server takes longer to execute a script,
than the timeout value set in the web server for PHP-FPM to answer.

For that to fix one must increase the PHP-FPM/Proxy timeout settings in the ``.env`` file.
:ref:`env_httpd_timeout_to_php_fpm`

.. seealso::

   * :ref:`env_httpd_timeout_to_php_fpm`
   * https://github.com/cytopia/devilbox/issues/280
   * https://github.com/cytopia/devilbox/issues/234


PHP issues
==========

Fatal error: Cannot redeclare go()
----------------------------------

If you encounter this error, it is most likely that your current project declares the
PHP function ``go()`` and that you have enabled the ``swoole`` module which also provides
an implementation of that function.

To mitigate that issue, make sure that the ``swoole`` module is disabled in ``.env``.


.. seealso::

   * :ref:`env_file_php_modules_disable`
   * https://github.com/getkirby/kirby/issues/643


Database issues
===============

Invalid bind mount spec
-----------------------

This error might occure after changing the path of MySQL, PgSQL, Mongo or any other data directory.

When you change any paths inside ``.env`` that affect Docker mountpoints, the container need to be
removed and re-created during the next startup.
Removing the container is sufficient as they will always be created during run if they don't exist.

In order to remove the container do the following:

.. code-block:: bash

   host> cd path/to/devilbox
   host> docker-compose stop

   # Remove the stopped container (IMPORTANT!)
   # After the removal it will be re-created during next run
   host> docker-compose rm -f

.. seealso:: :ref:`remove_stopped_container`

[Warning] World-writable config file '/etc/mysql/docker-default.d/my.cnf' is ignored
------------------------------------------------------------------------------------

This warning might occur when using :ref:`howto_docker_toolbox_and_the_devilbox` on Windows and
trying to apply custom MySQL configuration files. This will also result in the configuration file
not being source by the MySQL server.

To fix this issue, you will have to change the file permission of your custom configuration files
to read-only by applying the following ``chmod`` command.

.. code-block:: bash

   # Nagivate to devilbox git directory
   host> cd path/to/devilbox

   # Navigate to the MySQL config directory (e.g.: MySQL 5.5)
   host> cd cfg/mysql-5.5

   # Make cnf files read only
   host> chmod 0444 *.cnf

.. seealso::
   * :ref:`my_cnf`
   * https://github.com/cytopia/devilbox/issues/212
