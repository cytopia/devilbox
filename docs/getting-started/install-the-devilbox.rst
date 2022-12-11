.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _install_the_devilbox:

********************
Install the Devilbox
********************

.. important::
   Ensure you have read and followed the :ref:`prerequisites`


**Table of Contents**

.. contents:: :local:


Download the Devilbox
=====================

The Devilbox does not need to be installed. The only thing that is required is its git directory.
To download that, open a terminal and copy/paste the following command.

.. code-block:: bash

   host> git clone https://github.com/cytopia/devilbox

.. seealso::

   * :ref:`howto_open_terminal_on_mac`
   * :ref:`howto_open_terminal_on_win`
   * :ref:`checkout-different-devilbox-release`


Create ``.env`` file
====================

Inside the cloned Devilbox git directory, you will find a file called ``env-example``. This file
is the main configuration with sane defaults for Docker Compose. In order to use it, it must be
copied to a file named ``.env``. (Pay attention to the leading dot).

.. code-block:: bash

   host> cp env-example .env

The ``.env`` file does nothing else than providing environment variables for Docker Compose
and in this case it is used as the main configuration file for the Devilbox by providing all kinds
of settings (such as which version to start up).

.. seealso::
   * |ext_lnk_docker_compose_env_file|
   * :ref:`env_file`


.. _install_the_devilbox_set_uid_and_gid:

Set uid and gid
===============

To get you started, there are only two variables that need to be adjusted:

* ``NEW_UID``
* ``NEW_GID``

The values for those two variables refer to your local (on your host operating system) user id
and group id. To find out what the values are required in your case, issue the following commands
on a terminal:

Find your user id
-----------------

.. code-block:: bash

   host> id -u

Find your group id
------------------

.. code-block:: bash

   host> id -g

In most cases both values will be ``1000``, but for the sake of this example, let's assume a value
of ``1001`` for the user id and ``1002`` for the group id.

Open the ``.env`` file with your favorite text editor and adjust those values:

.. code-block:: bash
   :caption: .env
   :emphasize-lines: 3,4

   host> vi .env

   NEW_UID=1001
   NEW_GID=1002

.. seealso::
   * |ext_lnk_uid|
   * :ref:`howto_find_uid_and_gid_on_mac`
   * :ref:`howto_find_uid_and_gid_on_win`
   * :ref:`syncronize_container_permissions`


OS specific setup
=================

Linux: SELinux
--------------

If you have SELinux enabled, you will also have to adjust the :ref:`env_mount_options` to allow
shared mounts among multiple container:

.. code-block:: bash
   :caption: .env
   :emphasize-lines: 3

   host> vi .env

   MOUNT_OPTIONS=,z

.. seealso::
   * https://github.com/cytopia/devilbox/issues/255
   * :ref:`env_mount_options`
   * |ext_lnk_docker_selinux_label|
   * |ext_lnk_docker_mount_z_flag|

.. _install_the_devilbox_osx_performance:

OSX: Performance
----------------

Out of the box, Docker for Mac has some performance issues when it comes to mount directories with
a lot of files inside. To mitigate this issue, you can adjust the caching settings for mounted
directories.

To do so, you will want to adjust the :ref:`env_mount_options` to allow caching on mounts.

.. code-block:: bash
   :caption: .env
   :emphasize-lines: 3

   host> vi .env

   MOUNT_OPTIONS=,cached

Ensure to read the links below to understand why this problem exists and how the fix works.
The Docker documentation will also give you alternative caching options to consider.

.. seealso::
   * https://github.com/cytopia/devilbox/issues/105
   * https://forums.docker.com/t/file-access-in-mounted-volumes-extremely-slow-cpu-bound/8076/281
   * https://docs.docker.com/docker-for-mac/osxfs/
   * :ref:`env_mount_options`

Checklist
=========

1. Devilbox is cloned
2. ``.env`` file is created
3. User and group id have been set in ``.env`` file

That's it, you have finished the first section and have a working Devilbox ready to be started.

.. seealso:: :ref:`troubleshooting`
