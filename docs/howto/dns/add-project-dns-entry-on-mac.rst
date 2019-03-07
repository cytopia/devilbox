:orphan:

.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _howto_add_project_hosts_entry_on_mac:

********************************
Add project hosts entry on MacOS
********************************

On MacOS, custom DNS entries can be added to the ``/etc/hosts`` and will take precedence over the
same entries provided by any DNS server.


**Table of Contents**

.. contents:: :local:


Assumption
==========

In order to better illustrate the process, we are going to use two projects as an example.
See the following table for project directories and :ref:`env_tld_suffix`.

+-------------------+------------+--------------------------+-----------------------+
| Project directory | TLD_SUFFIX | Project URL              | Required DNS name     |
+===================+============+==========================+=======================+
| project-1         | ``loc``    | http://project-1.loc     | ``project-1.loc``     |
+-------------------+------------+--------------------------+-----------------------+
| www.project-1     | ``loc``    | http://www.project-1.loc | ``www.project-1.loc`` |
+-------------------+------------+--------------------------+-----------------------+

Docker for Mac
--------------

When using Docker for Mac you can use ``127.0.0.1`` for the IP address.

1. Open ``/etc/hosts`` with admistrative privileges or via ``sudo`` with your favorite editor

   .. code-block:: bash

      host> sudo vi /etc/hosts

2. Add DNS records for the above listed examples:

   .. code-block:: bash
      :caption: /etc/hosts

      127.0.0.1  project-1.loc
      127.0.0.1  www.project-1.loc

3. Safe the file and verify the DNS entries with the ``ping`` command

   .. code-block:: bash

      host> ping -c1 project-1.loc

      PING project-1.loc (127.0.0.1) 56(84) bytes of data.
      64 bytes from localhost (127.0.0.1): icmp_seq=1 ttl=64 time=0.066 ms

   .. code-block:: bash

      host> ping -c1 www.project-1.loc

      PING www.project-1.loc (127.0.0.1) 56(84) bytes of data.
      64 bytes from localhost (127.0.0.1): icmp_seq=1 ttl=64 time=0.066 ms


Docker Toolbox
--------------

When using the Docker Toolbox, you cannot use ``127.0.0.1`` for DNS entries, but rather need to
use the IP address of the Docker Toolbox machine instead.

.. seealso:: :ref:`howto_find_docker_toolbox_ip_address`

For this example we will assume the Docker Toolbox IP address is ``192.168.99.100``.


1. Open ``/etc/hosts`` with admistrative privileges or via ``sudo`` with your favorite editor

   .. code-block:: bash

      host> sudo vi /etc/hosts

2. Add DNS records for the above listed examples:

   .. code-block:: bash
      :caption: /etc/hosts

      192.168.99.100  project-1.loc
      192.168.99.100  www.project-1.loc

3. Safe the file and verify the DNS entries with the ``ping`` command

   .. code-block:: bash

      host> ping -c1 project-1.loc

      PING project-1.loc (192.168.99.100) 56(84) bytes of data.
      64 bytes from localhost (192.168.99.100): icmp_seq=1 ttl=64 time=0.066 ms

   .. code-block:: bash

      host> ping -c1 www.project-1.loc

      PING www.project-1.loc (192.168.99.100) 56(84) bytes of data.
      64 bytes from localhost (192.168.99.100): icmp_seq=1 ttl=64 time=0.066 ms
