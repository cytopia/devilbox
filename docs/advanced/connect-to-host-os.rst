.. include:: ../_includes/global/links.rst

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


Docker for Mac
==============

If you run Docker for Mac, an IP address is not necessary as it already provides a CNAME which will
always point to the IP address of your host operating system. Depending on the Docker version this
CNAME will differ:

Docker 18.03.0-ce+ and Docker compose 1.20.1+
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

CNAME: ``host.docker.internal``

Docker 17.12.0-ce+ and Docker compose 1.18.0+
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

CNAME: ``docker.for.mac.host.internal``

Docker 17.06.0-ce+ and Docker compose 1.14.0+
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

CNAME: ``docker.for.mac.localhost``


Docker for Windows
==================

If you run Docker for Windows, an IP address is not necessary as it already provides a CNAME which will
always point to the IP address of your host operating system. Depending on the Docker version this
CNAME will differ:

.. important:: Ensure your firewall is not blocking Docker to host connections.

Docker 18.03.0-ce+ and Docker compose 1.20.1+
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

* CNAME: ``docker.for.win.host.internal``
* CNAME: ``host.docker.internal``


Docker 17.06.0-ce+ and Docker compose 1.14.0+
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

CNAME: ``docker.for.win.host.localhost``


Docker Toolbox
==============

.. note:: This section applies for both, Docker Toolbox on MacOS and Docker Toolbox on Windows.

Docker Toolbox behaves the same way as Docker on Linux, with one major difference.
The Devilbox IP address or the custom provided CNAMEs actually refer to the Docker Toolbox machine.

In order to connect from inside the Docker container inside Docker Toolbox to your host OS,
you need to create a remote port-forward from your host OS to the Docker Toolbox machine.
In other words, you need to make the service from your host OS available inside the Docker Toolbox
machine.

Let's assume you have a service on your host, listening on ``127.0.0.1`` on port ``7771`` and
want to forward that to port ``7772`` on the Docker Toolbox machine, so that the Docker container
can access port ``7772`` via the Devilbox bridge IP (``172.16.238.1``).

You will have to paste the following into a terminal on your host:

.. code-block:: bash

   # Change any of those three values
   LOCAL_ADDR=127.0.0.1     # On what IP address does the service bind to locally (on your MacOS)
   LOCAL_PORT=7771          # On what port does the service listen locally (on your MacOS)
   REMOTE_PORT=7772         # On what port it should listen in the Docker Toolbox machine

   # Fixed Devilbox network IP
   REMOTE_ADDR=172.16.238.1 # On what IP it should bind on the Docker Toolbox machine (Devilbox network IP)

   # Docker Toolbox defines
   USER=$(docker-machine inspect $docker_machine_name --format={{.Driver.SSHUser}})
   HOST=$(docker-machine active)
   PORT=$(docker-machine inspect $docker_machine_name --format={{.Driver.SSHPort}})
   KEY=$(docker-machine inspect $docker_machine_name --format={{.Driver.SSHKeyPath}})

   ssh -i ${KEY} -p ${PORT} \
       -R ${REMOTE_ADDR}:${REMOVE_PORT}:${LOCAL_HOST}:${LOCAL_PORT} \
       ${USER}@${HOST}

.. seealso::
   * :ref:`howto_ssh_into_docker_toolbox`
   * :ref:`howto_ssh_port_forward_from_docker_toolbox_to_host`
   * :ref:`howto_ssh_port_forward_from_host_to_docker_toolbox`
   * :ref:`howto_open_terminal_on_mac`
   * :ref:`howto_open_terminal_on_win`
   * |ext_lnk_ssh_tunnelling_for_fun_and_profit|
   * |ext_lnk_stackoverflow_ssh_into_docker_machine|
