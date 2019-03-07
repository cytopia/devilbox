.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _shared_devilbox_server_in_lan:

*****************************
Shared Devilbox server in LAN
*****************************

Devilbox as a shared **development**, **staging** or **CI** server is setup in a similar way as
you would do locally. The only three important parts to take care of are:

1. Project access to deploy/update code
2. Handle DNS entries
3. Share Devilbox CA


**Table of Contents**

.. contents:: :local:


Prerequisites
=============

This walk-through will use the following example values:

+--------------------+------------------+-----------+---------------------------+
| LAN / Network      | Devilbox server  | TLD_SUFFX | LOCAL_LISTEN_ADDR         |
+====================+==================+===========+===========================+
| ``192.168.0.0/24`` | ``192.168.0.12`` | ``loc``   | ``192.168.0.12`` or empty |
+--------------------+------------------+-----------+---------------------------+

.. seealso::
   * :ref:`env_tld_suffix`
   * :ref:`env_local_listen_addr`


Project access
==============

SSH
---

Enable and start an SSH server and give access to whatever system or user requires it.
This can be done directly on the host system or via various other Docker container that offer
ssh server.

Copy via sftp
^^^^^^^^^^^^^
If your SSH server is setup, users can use their sftp clients to deploy code updates. This however
is not encouraged and you should use git or any other version control system.

Manually git pull/checkout
^^^^^^^^^^^^^^^^^^^^^^^^^^
When using git, users can directly ssh into the shared Devilbox server and ``git pull`` or
``git checkout <branch>`` on their projects.

Automated git pull/checkout
^^^^^^^^^^^^^^^^^^^^^^^^^^^
In case you are using a staging or CI server, use Jenkins jobs or other automation tools
(e.g. Ansible) to auto-deploy via SSH.

Samba
-----

For a shared development server, you could also setup Samba network shares for each projects
and have users deploy their code via Samba.


Handle DNS records
==================

There are multiple ways of having DNS records available accross the LAN.

Before you read on, have a quick look on the decision Matrix to find the best method for your
use-case.

+---------------------+------------------+--------------------------------------------------------------------------------------------------------+
| Method              | Sub-method       | Outcome                                                                                                |
+=====================+==================+========================================================================================================+
| Real domain         |                  | All network devices will have Auto DNS                                                                 |
+---------------------+------------------+--------------------------------------------------------------------------------------------------------+
| Own DNS server      |                  | All network devices will have Auto DNS                                                                 |
+---------------------+------------------+--------------------------------------------------------------------------------------------------------+
| Devilbox DNS server | Manual           | Every network device must configure its DNS settings                                                   |
|                     +------------------+--------------------------------------------------------------------------------------------------------+
|                     | DHCP distributed | All network devices will have Auto DNS                                                                 |
+---------------------+------------------+--------------------------------------------------------------------------------------------------------+
| Hosts entry         |                  | Every network device must manually set hosts entries |br| for each project. (Does not work for phones) |
+---------------------+------------------+--------------------------------------------------------------------------------------------------------+

.. |br| raw:: html

   <br />

.. important::
   When using a shared Devilbox server and another Devilbox setup on your local computer,
   ensure that you are using different :ref:`env_tld_suffix` in order to not confuse
   DNS records.

Use a real domain
-----------------

*(This will allow all devices on the network to have Auto-DNS)*

If you own a real domain, such as ``my-company.com``, you can create a wildcard DNS record for
a subdomain, such as ``*.dev.my-company.com`` which must point to ``192.168.0.12.``.
This should be done in your hosting provider's DNS configuration pannel.

You must then also change the ``TLD_SUFFIX`` to that subdomain.

.. code-block:: bash
   :caption: .env

   TLD_SUFFIX=dev.my-company.com

Handle DNS records in your own DNS server
-----------------------------------------

*(This will allow all devices on the network to have Auto-DNS)*

If your LAN already provides its own customizable DNS server, you can setup a new wildcard DNS
zone for ``*.loc`` which points to ``192.168.0.12``.

Run a second instance of the Devilbox DNS server
------------------------------------------------

If the above two methods for automated DNS records don't apply to you, you will need to run
a second stand-alone Docker container of the Devilbox DNS server.

Run this container permantently on the shared Devilbox server with the following command:

.. code-block:: bash

   host> docker run -d \
           --restart unless-stopped \
           -p 53:53/tcp \
           -p 53:53/udp \
           -e WILDCARD_DNS='loc=192.168.0.12' \
           -t cytopia/bind

.. seealso:: https://github.com/cytopia/docker-bind

Now there are two ways to consume the DNS records on your local machine:

1. Manual
2. DHCP distributed

Manual DNS settings
^^^^^^^^^^^^^^^^^^^
*(Each device on the network needs to manually set the DNS server)*

When using this approach, you have to manually add the DNS server (IP: ``192.168.0.12``) to your
host operating system.

.. important::
   Keep in mind that you have to do this for every machine within the network which wants to access
   the shared Devilbox server.

.. seealso::
   * :ref:`howto_add_custom_dns_server_on_linux`
   * :ref:`howto_add_custom_dns_server_on_mac`
   * :ref:`howto_add_custom_dns_server_on_win`
   * :ref:`howto_add_custom_dns_server_on_android`
   * :ref:`howto_add_custom_dns_server_on_iphone`

DHCP distributed
^^^^^^^^^^^^^^^^
*(This will allow all devices on the network to have Auto-DNS)*

This is the automated and more pain-free approach, as all devices within the network will be able
to access projects on the shared Devilbox server.


Self-managed DHCP server
""""""""""""""""""""""""
If you run your own DHCP server within a network, you probably know how to add other DNS servers.
The only thing you should keep in mind is, that the Devilbox DNS server should be the first in
the list.

DSL box / LAN or WIFI router
""""""""""""""""""""""""""""
Most `SOHO <https://en.wikipedia.org/wiki/Small_office/home_office>`_ networks probably use some
vendor router which has a web interface. Generally speaking, you need to find the DNS/DHCP settings
in its web interface and add the Devilbox DNS server as the first in the list (``192.168.0.12``).

.. seealso::
   * `Change DNS server in Fritzbox <https://en.avm.de/service/fritzbox/fritzbox-7390/knowledge-base/publication/show/165_Configuring-different-DNS-servers-in-the-FRITZ-Box/>`_


Add hosts entries for every project
-----------------------------------

*(Each device on the network needs to manually set the hosts entries for every single projcet)*

As you also do for the Devilbox locally when not using Auto-DNS, you can do as well for remote
computer. Just edit your local hosts file and add one DNS entry for every project on the shared
Devilbox server.

Keep in mind that this time you will have to use ``192.168.0.12`` instead of ``127.0.0.1``.

.. seealso::
   * :ref:`howto_add_project_hosts_entry_on_linux`
   * :ref:`howto_add_project_hosts_entry_on_mac`
   * :ref:`howto_add_project_hosts_entry_on_win`


Share Devilbox CA
=================

The last step to also have valid HTTPS connections on your shared Devilbox server is to copy
the CA onto your local machine and import it into your browser or system.

.. seealso:: :ref:`setup_valid_https`
