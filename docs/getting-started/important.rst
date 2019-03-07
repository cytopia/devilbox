.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

*********
IMPORTANT
*********

The following is a collection of important **do's and don'ts** you should be aware of when starting
to use the Devilbox and Docker in general.

**Table of Contents**

.. contents:: :local:


Starting
========

Do not run via ``sudo`` or ``root``
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Do not start the Devilbox with ``sudo`` or as ``root`` user. If it complains about permissions when
starting it with your normal system user, it is probably due to the fact, that your user is not in
the ``docker`` group.

.. seealso:: **Ensure you have read and done the following:**

   * Add user to ``docker`` group: :ref:`prerequisites_docker_installation`
   * Synronize file permissions: :ref:`install_the_devilbox_set_uid_and_gid`

.. warning:: If you start the Devilbox with ``sudo`` or as ``root`` user, it will most likely mess with your file permissions.


Starting, Stopping and Restarting
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Whenever you want to stop the Devilbox, change configuration and start up again, do not forget to
remove stopped container.

.. code-block:: bash

   # Stop the Devilbox
   host> docker-compose stop

   # Remove stopped container
   host> docker-compose rm

   # Start the Devilbox
   host> docker-compose up

.. seealso:: :ref:`start_the_devilbox_stop_and_restart` (why do ``docker-compose rm``?)


Backups
=======

Ensure to do regular database backups! Better safe then sorry!

.. seealso::
   * :ref:`backup_and_restore_mysql`
   * :ref:`backup_and_restore_pgsql`
   * :ref:`backup_and_restore_mongo`
