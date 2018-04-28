.. _communicating_with_external_hosts:

*********************************
Communicating with external hosts
*********************************

This tutorial shows you how to connect the Devilbox to running Docker container outside the
Devilbox network, i.e. Docker container you have started separately.


**Table of Contents**

.. contents:: :local:


Prerequisites
=============

There are two things you need to make sure of are met beforehand:

1. The external Docker container must expose its ports on all interfaces on your host operating system
2. The IP by which the host is reachable from within the Devilbox container.

Host IP: Docker on Linux
------------------------

If you run Docker on Linux the host IP is always ``172.16.238.1``, which is the default gateway
IP address within the Devilbox bridge network (see ``docker-compose.yml``).

By default Docker on Linux does not have CNAME's of the host computer as for example with MacOS
or Windows, therefore two custom CNAME's have been added by the Devilbox in order to emulate the
same behaviour:

* CNAME: ``docker.for.lin.host.internal``
* CNAME: ``docker.for.lin.localhost``

Host IP: Docker for Mac
-----------------------

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


Host IP: Docker for Windows
----------------------------

If you run Docker for Windows, an IP address is not necessary as it already provides a CNAME which will
always point to the IP address of your host operating system. Depending on the Docker version this
CNAME will differ:

Docker 18.03.0-ce+ and Docker compose 1.20.1+
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

CNAME: ``docker.for.win.host.internal``

Docker 17.06.0-ce+ and Docker compose 1.14.0+
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

CNAME: ``docker.for.win.host.localhost``



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
    :name: .env
    :caption: .env

    EXTRA_HOSTS=mywebserver.loc=172.16.238.1

or

.. code-block:: bash
    :name: .env
    :caption: .env

    EXTRA_HOSTS=mywebserver.loc=docker.for.lin.host.internal

or

.. code-block:: bash
    :name: .env
    :caption: .env

    EXTRA_HOSTS=mywebserver.loc=docker.for.lin.localhost


Mapping on MacOS
^^^^^^^^^^^^^^^^

If you are running MacOS as your host operating system you would use one of the identified CNAME's
(depending on your Docker version).

.. code-block:: bash
    :name: .env
    :caption: .env

    EXTRA_HOSTS=mywebserver.loc=host.docker.internal

The CNAME ``host.docker.internal`` will be resolved to an IP address during startup and ``mywebserver.loc``
's DNS record will point to that IP address.


Mapping on Windows
^^^^^^^^^^^^^^^^^^

If you are running Windows as your host operating system you would use one of the identified CNAME's
(depending on your Docker version).

.. code-block:: bash
    :name: .env
    :caption: .env

    EXTRA_HOSTS=mywebserver.loc=docker.for.win.host.internal

The CNAME ``docker.for.win.host.internal`` will be resolved to an IP address during startup and ``mywebserver.loc``
's DNS record will point to that IP address.


Auto DNS
--------

If you also turned on :ref:`global_configuration_auto_dns` these extra hosts will then also be available
to your host operating system as well.


Further reading
===============

.. seealso::
    * :ref:`env_extra_hosts`
    * :ref:`global_configuration_auto_dns`
