.. _global_configuration_auto_dns:

********
Auto-DNS
********

If you don't want to add DNS records manually for every project, you can also use the bundled
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

Before starting up the Devilbox, ensure that port ``53`` is not already used on ``127.0.0.1``.

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


Linux
-----

On Linux the DNS settings can be controlled by various different methods. Two of them are via
Network Manager and systemd-resolved. Choose on of the methods depending on your local setup.

Network Manager
^^^^^^^^^^^^^^^

If the prerequisites are met, you can edit ``/etc/dhcp/dhclient.conf`` with root or sudo privileges
and add an instruction, which tells your local DHCP client that whenever any of your DNS servers
are changed, you always want to have an additional entry, which is the one from the Devilbox.

Add the following line to to the very beginning to ``/etc/dhcp/dhclient.conf``:

.. code-block:: bash
   :caption: /etc/dhcp/dhclient.conf

   prepend domain-name-servers 127.0.0.1;

When you do that for the first time, you need to restart the ``network-manager`` service.

.. code-block:: bash

   # Via service command
   host> sudo service network-manager restart

   # Or the systemd way
   host> sudo systemctl restart network-manager

This will make sure that whenever your /etc/resolv.conf is deployed, you will have ``127.0.0.1``
as the first entry and also make use of any other DNS server which are deployed via the LAN's DHCP server.

If the Devilbox DNS server is not running, it does not affect the name resolution, because you will
still have other entries in ``/etc/resolv.conf``.


systemd-resolved
^^^^^^^^^^^^^^^^

In case you are using systemd-resolved instead of NetworkManager, add the following line to
the very beginning to ``/etc/resolv.conf.head``:

.. code-block:: bash
   :caption: /etc/resolv.conf.head

   nameserver 127.0.0.1

Prevent NetworkManager from modifying ``/etc/resolv.conf`` and leave everything to
systemd-resolved by adding the following line under the ``[main]`` section of
``/etc/NetworkManager/NetworkManager.conf``

.. code-block:: bash
   :caption: /etc/NetworkManager/NetworkManager.conf

   dns=none

As a last step you will have to restart ``systemd-resolved``.

.. code-block:: bash

   host> sudo systemctl stop systemd-resolved
   host> sudo systemctl start systemd-resolved

Once done, you can verify if the new DNS settings are effective:

.. code-block:: bash

   host> systemd-resolve --status

.. seealso:: `Archlinux Wiki: resolv.conf <https://wiki.archlinux.org/index.php/Resolv.conf#Modify_the_dhcpcd_config>`_


MacOS
-----

Modifying ``/etc/resolv.conf`` does not work on MacOS, you need to make changes in your
System Preferences:

1. Open System Preferences
2. Go to Network
3. Select your connected interface
4. Click on ``DNS`` tab
5. Add new DNS server by clicking the ``+`` sign
6. Add ``127.0.0.1``

.. image:: /_static/img/auto-dns-macos-dns.png


Windows
-------

On Windows, you need to change your active network adapter. See the following screenshots
for how to do it.

.. image:: /_static/img/auto-dns-windows-dns-01.jpg
.. image:: /_static/img/auto-dns-windows-dns-02.jpg
.. image:: /_static/img/auto-dns-windows-dns-03.jpg

In the last screenshot, you will have to add ``127.0.0.1`` as your ``Preferred DNS server``.


Docker Toolbox
==============

.. seealso:: :ref:`docker_toolbox`

MacOS
-----

* :ref:`env_local_listen_addr` must be empty in order to listen on all interfaces
* :ref:`env_host_port_bind` must be set to ``53``
* Port ``80`` from the Docker Toolbox virtual machine must be port-forwarded to ``127.0.0.1:80`` on your host os
* Port ``53`` from the Docker Toolbox virtual machine must be port-forwarded to ``127.0.0.1:53`` on your host os

.. todo:: This section needs further proof and information.


Windows
--------

* :ref:`env_local_listen_addr` must be empty in order to listen on all interfaces
* :ref:`env_host_port_bind` must be set to ``53``
* Port ``80`` from the Docker Toolbox virtual machine must be port-forwarded to ``127.0.0.1:80`` on your host os
* Port ``53`` from the Docker Toolbox virtual machine must be port-forwarded to ``127.0.0.1:53`` on your host os

.. todo:: This section needs further proof and information.
