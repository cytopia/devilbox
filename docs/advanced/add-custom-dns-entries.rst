.. _add_custom_dns_entries:

**********************
Add custom DNS entries
**********************

This section explains how to connect from inside a Devilbox container to the host operating system.


**Table of Contents**

.. contents:: :local:

Make DNS available to the Devilbox
==================================

Inside each Devilbox Docker container you can already connect to all host ports (if they are bound
to all interfaces) by the above specified IP addresses or CNAME's. You can however also create a
custom DNS entry for convenience or if an external web server requires a special vhost name.

Adding extra hosts
------------------

Extra hosts (hostname and IP address mappings or hostname and CNAME mappings) can be set in the
``.env`` file.

.. seealso:: :ref:`env_extra_hosts`


Example
-------

Let's assume another Docker container is running on your host, which must be accessed by the exact
name of ``mywebserver.loc`` in order to respond by that virtual host name.


Mapping on Linux
^^^^^^^^^^^^^^^^

If you are running Linux as your host operating system you would use the IP address of the host
computer which was identified as ``172.16.238.1``.

.. code-block:: bash
   :caption: .env

   EXTRA_HOSTS=mywebserver.loc=172.16.238.1

or

.. code-block:: bash
   :caption: .env

   EXTRA_HOSTS=mywebserver.loc=docker.for.lin.host.internal

or

.. code-block:: bash
   :caption: .env

   EXTRA_HOSTS=mywebserver.loc=docker.for.lin.localhost


Mapping on MacOS
^^^^^^^^^^^^^^^^

If you are running MacOS as your host operating system you would use one of the identified CNAME's
(depending on your Docker version).

.. code-block:: bash
   :caption: .env

   EXTRA_HOSTS=mywebserver.loc=host.docker.internal

The CNAME ``host.docker.internal`` will be resolved to an IP address during startup and ``mywebserver.loc``
's DNS record will point to that IP address.


Mapping on Windows
^^^^^^^^^^^^^^^^^^

If you are running Windows as your host operating system you would use one of the identified CNAME's
(depending on your Docker version).

.. code-block:: bash
   :caption: .env

   EXTRA_HOSTS=mywebserver.loc=docker.for.win.host.internal

The CNAME ``docker.for.win.host.internal`` will be resolved to an IP address during startup and ``mywebserver.loc``
's DNS record will point to that IP address.


Auto DNS
--------

If you also turned on :ref:`setup_auto_dns` these extra hosts will then also be available
to your host operating system as well.


Further reading
===============

.. seealso::
   * :ref:`env_extra_hosts`
   * :ref:`setup_auto_dns`
