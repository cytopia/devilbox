.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _overwrite_existing_docker_image:

*******************************
Overwrite existing Docker image
*******************************

This section is all about customizing the Devilbox and its Docker images specifically to your needs.


**Table of Contents**

.. contents:: :local:


Prerequisites
=============

The new Docker image overwrite will be added to a file called ``docker-compose.override.yml``.
So before going any further, read the following section that shows you how to create this file
for the Devilbox as well as what pitfalls to watch out for.

.. seealso:: :ref:`docker_compose_override_yml`


What information do you need?
=============================

1. The service to overwrite


How to overwrite a service?
===========================

Generic steps
-------------

1. Copy the whole service definition from docker-compose.yml to docker-compose.override.yml
2. Remove anything unecessary
3. Adjust the values you need

Overwrite Docker image for the bind service
-------------------------------------------

The following example is using the ``bind`` service and overrides the Docker image
to illustrate how this is done :


First you simply copy the while definition of the bind service from ``docker-compose.yml`` to
``docker-compose.override.yml``:

.. code-block:: yaml
   :caption: docker-compose.override.yml

   version: '2.1'
   services:
     bind:
       image: cytopia/bind:0.11
       restart: always
       ports:
         # [local-machine:]local-port:docker-port
         - "${LOCAL_LISTEN_ADDR}${HOST_PORT_BIND:-1053}:53"
         - "${LOCAL_LISTEN_ADDR}${HOST_PORT_BIND:-1053}:53/udp"

       environment:
         ##
         ## Debug?
         ##
         - DEBUG_ENTRYPOINT=${DEBUG_ENTRYPOINT}
         - DOCKER_LOGS=1

         ##
         ## Bind settings
         ##
         - WILDCARD_ADDRESS=172.16.238.11
         - DNS_FORWARDER=${BIND_DNS_RESOLVER:-8.8.8.8,8.8.4.4}

       dns:
         - 127.0.0.1

       networks:
         app_net:
           ipv4_address: 172.16.238.100

The second step is to remove everything that you do not need to overwrite:

.. code-block:: yaml
   :caption: docker-compose.override.yml

   version: '2.1'
   services:
     bind:
       image: cytopia/bind:0.11

The last step is to actually adjust the value you want to change for the bind service:

.. code-block:: yaml
   :caption: docker-compose.override.yml
   :emphasize-lines: 4

   version: '2.1'
   services:
     bind:
       image: someother/bind:latest


Further reading
===============

.. seealso::
   * :ref:`docker_compose_override_yml`
   * :ref:`add_your_own_docker_image`
