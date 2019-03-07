.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _connect_to_host_os:

******************
Connect to host OS
******************

This section explains how to connect from inside a Devilbox container to the host operating system.


**Table of Contents**

.. contents:: :local:


Prerequisites
=============

When you want to connect from inside a Docker container to a port on your host operating system,
ensure the host service is listening on all interfaces for simplicity.

The following sections will give you the IP address and/or the CNAME where the host os can be
reached from within a container.


.. _connect_to_host_os_docker_on_linux:

Docker on Linux
===============

If you run Docker on Linux the host IP is always ``172.16.238.1``, which is the default gateway
IP address within the Devilbox bridge network (see ``docker-compose.yml``).

.. important:: Ensure services on the host listen on that IP address or on all interfaces.

By default Docker on Linux does not have CNAME's of the host computer as for example with MacOS
or Windows, therefore two custom CNAME's have been added by the Devilbox in order to emulate the
same behaviour:

* CNAME: ``docker.for.lin.host.internal``
* CNAME: ``docker.for.lin.localhost``


.. _connect_to_host_os_docker_for_mac:

Docker for Mac
==============

If you run Docker for Mac, an IP address is not necessary as it already provides a CNAME which will
always point to the IP address of your host operating system. Depending on the Docker version this
CNAME will differ:

Docker 18.03.0-ce+ and Docker compose 1.20.1+
---------------------------------------------

CNAME: ``host.docker.internal``

Docker 17.12.0-ce+ and Docker compose 1.18.0+
---------------------------------------------

CNAME: ``docker.for.mac.host.internal``

Docker 17.06.0-ce+ and Docker compose 1.14.0+
---------------------------------------------

CNAME: ``docker.for.mac.localhost``


.. _connect_to_host_os_docker_for_win:

Docker for Windows
==================

If you run Docker for Windows, an IP address is not necessary as it already provides a CNAME which will
always point to the IP address of your host operating system. Depending on the Docker version this
CNAME will differ:

.. important:: Ensure your firewall is not blocking Docker to host connections.

Docker 18.03.0-ce+ and Docker compose 1.20.1+
---------------------------------------------

* CNAME: ``docker.for.win.host.internal``
* CNAME: ``host.docker.internal``


Docker 17.06.0-ce+ and Docker compose 1.14.0+
---------------------------------------------

CNAME: ``docker.for.win.host.localhost``


Docker Toolbox
==============

.. note:: This section applies for both, Docker Toolbox on MacOS and Docker Toolbox on Windows.

Docker Toolbox behaves the same way as Docker on Linux, with one major difference.
The Devilbox IP address or the custom provided CNAMEs actually refer to the Docker Toolbox machine.

In order to connect from inside the Docker container (which is inside the Docker Toolbox machine)
to your host os, you need to create:

1. either a **local** port-forward on the **Docker Toolbox** machine (``ssh -L``)
2. or a **remote** port-forward on your **host os** (``ssh -R``)

.. seealso:: |ext_lnk_ssh_tunnelling_for_fun_and_profit|


For both examples we assume the following:

* MySQL database exists on your host os and listens on ``127.0.0.1`` on port ``3306``
* Docker Toolbox IP address is ``192.168.99.100``
* Host IP address where SSH is listening on ``172.16.0.1``
* Host SSH username is ``user``
* Devilbox Docker container wants to access MySQL on host os


Local port forward on Docker Toolbox
------------------------------------

.. important::
   For that to work, your host operating system requires an SSH server to be up and running.

+----------------+----------------+--------------+--------------------+--------------+
| Initiator      | From host      | From port    | To host            | To port      |
+================+================+==============+====================+==============+
| Docker Toolbox | ``127.0.0.1``  | ``3306``     | ``192.168.99.100`` | ``3306``     |
+----------------+----------------+--------------+--------------------+--------------+

.. code-block:: bash

   # From Docker Toolbox forward port 3306 (on host 172.16.0.1) to myself (192.168.99.100)
   toolbox> ssh -L 3306:127.0.0.1:3306 user@172.16.0.1

.. seealso::
   * :ref:`howto_find_docker_toolbox_ip_address`
   * :ref:`howto_ssh_into_docker_toolbox`
   * :ref:`howto_ssh_port_forward_on_docker_toolbox_from_host`

Remote port-forward on host os
------------------------------

.. important::
   For that to work, your host operating system requires an SSH client (``ssh`` binary).

+----------------+----------------+--------------+--------------------+--------------+
| Initiator      | From host      | From port    | To host            | To port      |
+================+================+==============+====================+==============+
| Host os        | ``127.0.0.1``  | ``3306``     | ``192.168.99.100`` | ``3306``     |
+----------------+----------------+--------------+--------------------+--------------+

.. code-block:: bash

   # From host os forward port 3306 (from loopback 127.0.0.1) to Docker Toolbox (192.168.99.100)
   host> ssh -R 3306:127.0.0.1:3306 docker@192.168.99.100

.. seealso::
   * :ref:`howto_find_docker_toolbox_ip_address`
   * :ref:`howto_ssh_into_docker_toolbox`
   * :ref:`howto_ssh_port_forward_on_host_to_docker_toolbox`

Post steps
----------

With either of the above you have achieved the exact behaviour as
:ref:`connect_to_host_os_docker_on_linux` for one single service/port (MySQL port 3306).

You must now follow the steps for :ref:`connect_to_host_os_docker_on_linux` to actually connect
to that service from within the Devilbox Docker container.
