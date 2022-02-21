.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _setup_auto_dns:

**************
Setup Auto DNS
**************

If you don't want to add host records manually for every project, you can also use the bundled
DNS server and use it's DNS catch-all feature to have all DNS records automatically available.

.. important::
   By default, the DNS server is set to listen on ``1053`` to avoid port collisions during startup.
   You need to change it to ``53`` in ``.env`` via :ref:`env_host_port_bind`.


**Table of Contents**

.. contents:: :local:


Native Docker
=============

The webserver as well as the DNS server must be available on ``127.0.0.1`` or on all interfaces
on ``0.0.0.0``. Additionally the DNS server port must be set to ``53`` (it is not by default).

* Ensure :ref:`env_local_listen_addr` is set accordingly
* Ensure :ref:`env_host_port_bind` is set accordingly
* No other DNS resolver should listen on ``127.0.0.1:53``


Prerequisites
-------------

First ensure that :ref:`env_local_listen_addr` is either empty or listening on ``127.0.0.1``.

.. code-block:: bash
   :caption: .env
   :emphasize-lines: 3

   host> cd path/to/devilbox
   host> vi .env
   LOCAL_LISTEN_ADDR=

Then you need to ensure that :ref:`env_host_port_bind` is set to ``53``.

.. code-block:: bash
   :caption: .env
   :emphasize-lines: 3

   host> cd path/to/devilbox
   host> vi .env
   HOST_PORT_BIND=53

Before starting up the Devilbox, ensure that port ``53`` is not already used.

.. code-block:: bash
   :emphasize-lines: 2

   host> netstat -an | grep -E 'LISTEN\s*$'
   tcp        0      0 127.0.0.1:53            0.0.0.0:*               LISTEN
   tcp        0      0 127.0.0.1:43477         0.0.0.0:*               LISTEN
   tcp        0      0 127.0.0.1:50267         0.0.0.0:*               LISTEN

If you see port ``53`` already being used as in the above example, ensure to stop any
DNS resolver, otherwise it does not work.

The output should look like this (It is only important that there is no ``:53``.

.. code-block:: bash

   host> netstat -an | grep -E 'LISTEN\s*$'
   tcp        0      0 127.0.0.1:43477         0.0.0.0:*               LISTEN
   tcp        0      0 127.0.0.1:50267         0.0.0.0:*               LISTEN


Docker on Linux
---------------

Your DNS server IP address is ``127.0.0.1``.

.. seealso:: :ref:`howto_add_custom_dns_server_on_linux`


Docker for Mac
--------------

Your DNS server IP address is ``127.0.0.1``.

.. seealso:: :ref:`howto_add_custom_dns_server_on_mac`


Docker for Windows
------------------

Your DNS server IP address is ``127.0.0.1``.

.. seealso:: :ref:`howto_add_custom_dns_server_on_win`


Docker Toolbox
==============

.. seealso:: :ref:`howto_docker_toolbox_and_the_devilbox`

This part applies equally for Docker Toolbox on MacOS and on Windows:

Prerequisites
-------------

* :ref:`env_local_listen_addr` must be empty in order to listen on all interfaces
* :ref:`env_host_port_bind` must be set to ``53``

You need to create three port-forwards to make the DNS and web server available on your host os:

* Port ``80`` from the Docker Toolbox virtual machine must be port-forwarded to ``127.0.0.1:80`` on your host os
* Port ``443`` from the Docker Toolbox virtual machine must be port-forwarded to ``127.0.0.1:443`` on your host os
* Port ``53`` from the Docker Toolbox virtual machine must be port-forwarded to ``127.0.0.1:53`` on your host os

Assuming the Docker Toolbox IP is ``192.168.99.100`` your forwards must be as follows:

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
   * :ref:`howto_ssh_port_forward_on_docker_toolbox_from_host`
   * :ref:`howto_find_docker_toolbox_ip_address`


Actual setup
------------

.. important::
   After settings this up, follow the above guides for **Docker for Mac** or **Docker for Windows**
   to finish the setup.

..
   Access for other network devices

..
   seealso::
   * :ref:`access_devilbox_from_android`
   * :ref:`access_devilbox_from_iphone`
   * :ref:`access_colleagues_devilbox`
   * :ref:`shared_devilbox_server_in_lan`
