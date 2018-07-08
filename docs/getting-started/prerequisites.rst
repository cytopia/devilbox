.. include:: /_includes/all.rst

.. _prerequisites:

*************
Prerequisites
*************

.. important::
   :ref:`read_first`
     Ensure you have read this document to understand how this documentation works.


**Table of Contents**

.. contents:: :local:


Supported host OS
=================

The Devilbox runs on all major operating systems which provide ``Docker`` and ``Docker Compose``.
See the matrix below for supported versions:

+----------------+---------------------+---------------------+-------------+
| OS             | Version             | Type                | Recommended |
+================+=====================+=====================+=============+
| |img_logo_lin| | Any                 | |tbl_docker_lin|    | yes         |
+----------------+---------------------+---------------------+-------------+
|                |                     |                     |             |
+----------------+---------------------+---------------------+-------------+
| |img_logo_mac| | Any                 | |tbl_docker_mac|    | yes         |
|                |                     +---------------------+-------------+
|                |                     | |tbl_docker_tb_mac| |             |
+----------------+---------------------+---------------------+-------------+
|                |                     |                     |             |
+----------------+---------------------+---------------------+-------------+
| |img_logo_win| | Windows 7           | |tbl_docker_tb_win| |             |
|                +---------------------+---------------------+-------------+
|                | Windows 10          | |tbl_docker_win|    | yes         |
|                |                     +---------------------+-------------+
|                |                     | |tbl_docker_tb_win| |             |
|                +---------------------+---------------------+-------------+
|                | Windows Server 2016 | |tbl_docker_win_ee| | yes         |
+----------------+---------------------+---------------------+-------------+

.. |tbl_docker_lin| raw:: html

  <a target="_blank" href="https://docs.docker.com/install/#server">
    Docker <img src="/_static/img/icons/ext-link.svg" />
  </a>

.. |tbl_docker_mac| raw:: html

  <a target="_blank" href="https://docs.docker.com/docker-for-mac/install/">
    Docker for Mac <img src="/_static/img/icons/ext-link.svg" />
  </a>

.. |tbl_docker_tb_mac| raw:: html

  <a target="_blank" href="https://docs.docker.com/toolbox/toolbox_install_mac/">
    Docker Toolbox <img src="/_static/img/icons/ext-link.svg" />
  </a>

.. |tbl_docker_win| raw:: html

  <a target="_blank" href="https://docs.docker.com/docker-for-windows/install/">
    Docker for Windows <img src="/_static/img/icons/ext-link.svg" />
  </a>

.. |tbl_docker_tb_win| raw:: html

  <a target="_blank" href="https://docs.docker.com/toolbox/toolbox_install_windows/">
    Docker Toolbox <img src="/_static/img/icons/ext-link.svg" />
  </a>

.. |tbl_docker_win_ee| raw:: html

  <a target="_blank" href="https://www.docker.com/docker-windows-server">
    Docker EE <img src="/_static/img/icons/ext-link.svg" />
  </a>



Required software
=================

The only requirements for the Devilbox is to have ``Docker`` and ``Docker Compose`` installed,
everything else is bundled and provided withing the Docker container.
The minimum required versions are listed below:

* ``Docker``: 1.12.0+
* ``Docker Compose``: 1.9.0+


Additionally you will require ``git`` in order to clone the devilbox project.

.. seealso::

   * |ext_lnk_install_docker|
   * |ext_lnk_install_docker_compose|
   * :ref:`howto_find_docker_and_docker_compose_version`


Docker installation
===================

Linux
-----

|img_logo_lin|

Docker on Linux requires super user privileges which is granted to a system
wide group called ``docker``. After having installed Docker on your system,
ensure that your local user is a member of the ``docker`` group.

.. code-block:: bash

   host> id

   uid=1000(cytopia) gid=1000(cytopia) groups=1000(cytopia),999(docker)

.. seealso::

   * |ext_lnk_install_docker_centos|
   * |ext_lnk_install_docker_debian|
   * |ext_lnk_install_docker_fedora|
   * |ext_lnk_install_docker_ubuntu|
   * |ext_lnk_install_docker_linux_post_steps| (covers ``docker`` group)

Mac
---

|img_logo_mac|

On MacOS Docker is available in two different forms: **Docker for Mac**
and **Docker Toolbox**.

Docker for Mac
^^^^^^^^^^^^^^

Docker for Mac is the native and recommended version to choose when using the
Devilbox.

Docker for Mac requires super user privileges which is granted to a system
wide group called ``docker``. After having installed Docker on your system,
ensure that your local user is a member of the ``docker`` group.

.. code-block:: bash

   host> id

   uid=502(cytopia) gid=20(staff) groups=20(staff),999(docker)

.. seealso::

   Docker for Mac
     * |ext_lnk_install_docker_mac|
     * |ext_lnk_install_docker_mac_get_started|

Docker Toolbox
^^^^^^^^^^^^^^

If you still want to use Docker Toolbox, ensure you have read its
drawbacks in the below provided links.

.. seealso::

   Docker Toolbox
     * |ext_lnk_install_docker_toolbox_mac|
     * |ext_lnk_install_docker_toolbox_mac_native_vs_toolbox|
     * |ext_link_docker_machine|

.. important:: :ref:`howto_docker_toolbox_and_the_devilbox`

Windows
-------

|img_logo_win|

On Windows Docker is available in two different forms: **Docker for Windows**
and **Docker Toolbox**.

Docker for Windows
^^^^^^^^^^^^^^^^^^

Docker for Windows is the native and recommended version to choose when using
the Devilbox. This however is only available since **Windows 10**.

Docker for Windows requires administrative privileges which is granted to a system
wide group called ``docker-users``. After having installed Docker on your system,
ensure that your local user is a member of the ``docker-users`` group.

.. seealso::

   Docker for Windows
     * |ext_lnk_install_docker_win|
     * |ext_lnk_install_docker_win_get_started|

Docker Toolbox
^^^^^^^^^^^^^^

If you are on **Windows 7** or still want to use Docker Toolbox, ensure you
have read its drawbacks in the below provided links.

.. seealso::

   Docker Toolbox
     * |ext_lnk_install_docker_toolbox_win|
     * |ext_link_docker_machine|

.. important:: :ref:`howto_docker_toolbox_and_the_devilbox`


Post installation
=================

Read the Docker documentation carefully and follow all **install** and **post-install** steps.
Below are a few stumbling blocks to check that might or might not apply depending on your host
operating system and your Docker version.

.. seealso:: :ref:`troubleshooting`

User settings
-------------

Some versions of Docker require your local user to be in the ``docker`` group
(or ``docker-users`` on Windows).

Shared drives
-------------

Some versions of Docker require you to correctly setup shared drives. Ensure the desired locations
are being made available to Docker and the correct credentials are applied.

Network and firewall
--------------------

On Windows, ensure your firewall allows access to shared drives.

SE Linux
--------

Make sure to read any shortcomings when SE Linux is enabled.

General
-------

It could also help to do a full system restart after the installation has been finished.


Optional previous knowledge
===========================

In order to easily work with the Devilbox you should already be familiar with the following:

* Navigate on the command line
* Docker Compose commands (``up``, ``stop``, ``kill``, ``rm``, ``logs``, ``pull``)
* Docker Compose ``.env`` file
* Know how to use ``git``

.. seealso::

   * |ext_lnk_docker_cmpose_cmd_reference|
   * |ext_lnk_docker_cmpose_env_file|
   * :ref:`troubleshooting`
