.. _troubleshooting:

***************
Troubleshooting
***************

This section will contain common problems and how to resolve them.
It will grow over time once there are more issues reported.

.. seealso:: :ref:`faq`


**Table of Contents**

.. contents:: :local:


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

This warning might occur when using :ref:`docker_toolbox` on Windows and trying to apply custom
MySQL configuration files. This will also result in the configuration file not being source
by the MySQL server.

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
