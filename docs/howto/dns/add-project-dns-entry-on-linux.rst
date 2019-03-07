:orphan:

.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _howto_add_project_hosts_entry_on_linux:

********************************
Add project hosts entry on Linux
********************************

On Linux, custom DNS entries can be added to the ``/etc/hosts`` and will take precedence over the
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

Step by step
------------

When using Docker on Linux you can use ``127.0.0.1`` for the IP address.

1. Open ``/etc/hosts`` with root privileges or via ``sudo`` with your favorite editor

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
