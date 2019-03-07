:orphan:

.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _howto_docker_toolbox_and_the_devilbox:

*******************************
Docker Toolbox and the Devilbox
*******************************

Docker Toolbox is a legacy solution to bring Docker to systems which don’t natively support Docker.
This is achieved by starting a virtualized Linux instance (e.g.: inside VirtualBox) and have
Docker run inside this machine.

You don’t have to take care about setting up the virtual machine, this is done automatically with
the provided setup file (Windows and MacOS).

However, there are a few stumbling blocks you need to pay attention to in order to use the Devilbox
at its full potential.

.. seealso::

   Docker Toolbox
     * |ext_lnk_install_docker_toolbox_win|
     * |ext_lnk_install_docker_toolbox_mac|
     * |ext_lnk_install_docker_toolbox_mac_native_vs_toolbox|
     * |ext_link_docker_machine|


**Table of Contents**

.. contents:: :local:


Devilbox listening address configuration
========================================

First thing you need to make sure is that the ``LOCAL_LISTEN_ADDR`` variable from your ``.env``
file is empty. When it is empty all services bind to all IP addresses inside the virtual machine
and thus being able to be seen from outside the virtual machine (your host operating system).

You can verifiy that the variable is actually empty by checking your ``.env`` file:

.. code-block:: bash

   host> grep ^LOCAL_LISTEN_ADDR .env

   LOCAL_LISTEN_ADDR=

.. important:: The variable should exist, but there should not be any value after the equal sign.

.. seealso:: :ref:`env_file`


Find the Docker Toolbox IP address
==================================

The Devilbox intranet will not be available under ``127.0.0.1`` or ``localhost`` as it does not run
on your host operating system, but on a virtualized Linux machine which has a different IP address.

To find out the IP address on which Docker Toolbox is running you have to use the
``docker-machine`` command. Open a terminal and type the following:

.. code-block:: bash

   host> docker-machine ip default
   192.168.99.100

The above example outputs ``192.168.99.100``, but this might be different on your machine.

In this example I would then paste ``http://192.168.99.100`` in the web browsers address bar to
reach the Devilbox intranet.

.. seealso::

   * :ref:`howto_open_terminal_on_mac`
   * :ref:`howto_open_terminal_on_win`
   * :ref:`howto_find_docker_toolbox_ip_address`


Project DNS record pitfalls
===========================

When creating manual DNS records per project, you have to keep in mind that you cannot use
``127.0.0.1`` for the IP address part. You have to use the IP address of the Docker Toolbox
virtual machine as was shown in the above example.


Assuming the Docker Toolbox IP address is: ``192.168.99.100``, you have to create DNS records as
follows:

.. code-block:: bash
   :caption: /etc/resolv.conf or C:\\Windows\\System32\\drivers\\etc

   192.168.99.100 project.loc

.. seealso::

   * :ref:`howto_add_project_hosts_entry_on_mac`
   * :ref:`howto_add_project_hosts_entry_on_win`
   * :ref:`howto_find_docker_toolbox_ip_address`


Auto-DNS via port forwarding
============================

In order to make Auto-DNS for projects work as it does for native Docker implementations you will
have to do some prior configuration.

How does Auto-DNS work?
-----------------------

Auto-DNS is a catch-all DNS resolver for your chosen :ref:`env_tld_suffix` that will redirect any
domain to ``127.0.0.1``. Unfortunately Docker Toolbox does not listen on that IP address.

How to fix it for Docker Toolbox
--------------------------------

To overcome this problem, you will have to create three port forwards on your host operating system
from the Docker machine IP address for ``DNS`` (port 53), ``http`` (port 80) and ``https``
(port 443) to ``127.0.0.1`` on your host os.

Assuming the Docker Toolbox IP address is ``192.168.99.100`` the three port forwards must be as
follows:

+----------------+-----------+-----------+---------+
| From IP        | From port | To IP     | To port |
+================+===========+===========+=========+
| 192.168.99.100 | 53        | 127.0.0.1 | 53      |
+----------------+-----------+-----------+---------+
| 192.168.99.100 | 80        | 127.0.0.1 | 80      |
+----------------+-----------+-----------+---------+
| 192.168.99.100 | 443       | 127.0.0.1 | 443     |
+----------------+-----------+-----------+---------+

.. seealso::

   * :ref:`howto_find_docker_toolbox_ip_address`
   * :ref:`howto_ssh_port_forward_on_docker_toolbox_from_host`
   * :ref:`setup_auto_dns`


Mount shared folders
====================

Docker Toolbox will automatically set up a shared directory between your host operating system and
the virtual Linux machine. Only files and directories within this shared directory can be used to
be mounted into Docker container. If you plan to mount files or directories outside of this default
path you have to create a new shared directory as described below.

MacOS
-----

When you want to have your projects reside not somewhere in the ``/Users`` directory, ensure you
have read, understood and applied the following:

    "By default, Toolbox only has access to the ``/Users`` directory and mounts it into the VMs at
    ``/Users``. If your project lives elsewhere or needs access to other directories on the host
    filesystem, you can add them."

.. seealso:: |ext_lnk_install_docker_toolbox_mac_shared_directory|

Windows
-------

When you want to have your projects reside not somewhere in the ``C:\Users`` directory, ensure you
have read, understood and applied the following:

    "By default, Toolbox only has access to the ``C:\Users`` directory and mounts it into the VMs
    at ``/c/Users``. If your project lives elsewhere or needs access to other directories on the
    host filesystem, you can add them, using the VirtualBox UI."


.. _howto_docker_toolbox_and_the_devilbox_windows_symlinks:

Symlinks
^^^^^^^^

VirtualBox might not allow symlinks by default on other directories. This can however be fixed
manually. Let's assume You've added a shared folder ``D:/`` to VirtualBox on ``d``, you will then
need to manually allow symlinks via ``VboxManage`` command:

First check if symlinks are disabled

.. code-block:: bash

   host> VboxManage getextradata default enumerate

   Key: VBoxInternal2/SharedFoldersEnableSymlinksCreate/d, Value: 0

The ``Value: 0`` indicates that symlinks are not allowed. To enable it, do the folllowing:


.. code-block:: bash

   VBoxManage setextradata default VBoxInternal2/SharedFoldersEnableSymlinksCreate/d 1

Now check again

.. code-block:: bash

   host> VboxManage getextradata default enumerate

   Key: VBoxInternal2/SharedFoldersEnableSymlinksCreate/d, Value: 1

The ``Value: 1`` now indicates that symlinks are allowed.

.. seealso::
   * |ext_lnk_install_docker_toolbox_win_shared_directory|
   * https://github.com/cytopia/devilbox/issues/479
   * https://www.virtualbox.org/ticket/10085
