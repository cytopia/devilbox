.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _add_your_own_docker_image:

*************************
Add your own Docker image
*************************

This section is all about customizing the Devilbox and its Docker images specifically to your needs.


**Table of Contents**

.. contents:: :local:


Prerequisites
=============

The new Docker image definition will be added to a file called ``docker-compose.override.yml``.
So before going any further, read the following section that shows you how to create this file
for the Devilbox as well as what pitfalls to watch out for.

.. seealso:: :ref:`docker_compose_override_yml`


What information do you need?
=============================

1. ``<name>`` - A name, which you can use to refer in the ``docker-compose`` command
2. ``<image-name>`` - The Docker image name itself
3. ``<image-version>`` - The Docker image tag
4. ``<unused-ip-address>`` - An unused IP address from the devilbox network (found inside ``docker-compose.yml``)


How to add a new service?
=========================

Generic example
---------------

A single new service
^^^^^^^^^^^^^^^^^^^^

Open ``docker-compose.override.yml`` with your favourite editor and paste the following snippets
into it.

.. code-block:: yaml
   :caption: docker-compose.override.yml
   :emphasize-lines: 4,5,8

   version: '2.1'
   services:
     # Your custom Docker image here:
     <name>:
       image: <image-name>:<image-version>
       networks:
         app_net:
           ipv4_address: <unused-ip-address>
       # For ease of use always automatically start these:
       depends_on:
         - bind
         - php
         - httpd
     # End of custom Docker image

.. note::
   * ``<name>`` has to be replaced with any name of your choice
   * ``<image-name>`` has to be replaced with the name of the Docker image
   * ``<image-version>`` has to be replaced with the tag of the Docker image
   * ``<unused-ip-address>`` has to be replaced with an unused IP address

Two new services
^^^^^^^^^^^^^^^^

.. code-block:: yaml
   :caption: docker-compose.override.yml
   :emphasize-lines: 4,5,8,16,17,20

   version: '2.1'
   services:
     # Your first custom Docker image here:
     <name1>:
       image: <image1-name>:<image1-version>
       networks:
         app_net:
           ipv4_address: <unused-ip-address1>
       # For ease of use always automatically start these:
       depends_on:
         - bind
         - php
         - httpd
     # End of first custom Docker image
     # Your second custom Docker image here:
     <name2>:
       image: <image2-name>:<image2-version>
       networks:
         app_net:
           ipv4_address: <unused-ip-address2>
       # For ease of use always automatically start these:
       depends_on:
         - bind
         - php
         - httpd
     # End of second custom Docker image

.. note::
   * ``<name1>`` has to be replaced with any name of your choice
   * ``<image1-name>`` has to be replaced with the name of the Docker image
   * ``<image1-version>`` has to be replaced with the tag of the Docker image
   * ``<unused-ip-address1>`` has to be replaced with an unused IP address

.. note::
   * ``<name2>`` has to be replaced with any name of your choice
   * ``<image2-name>`` has to be replaced with the name of the Docker image
   * ``<image2-version>`` has to be replaced with the tag of the Docker image
   * ``<unused-ip-address2>`` has to be replaced with an unused IP address


CockroachDB example
-------------------

Gather the requirements for the |ext_lnk_docker_image_cockroach|
Docker image:

1. Name: ``cockroach``
2. Image: ``cockroachdb/cockroach``
3. Tag: ``latest``
4. IP: ``172.16.238.240``

Now add the information to ``docker-compose.override.yml``:

.. code-block:: yaml
   :caption: docker-compose.override.yml
   :emphasize-lines: 4-5,9

   version: '2.1'
   services:
     # Your custom Docker image here:
     cockroach:
       image: cockroachdb/cockroach:latest
       command: start --insecure
       networks:
         app_net:
           ipv4_address: 172.16.238.240
       # For ease of use always automatically start these:
       depends_on:
         - bind
         - php
         - httpd
     # End of custom Docker image



How to start the new service?
=============================

The following will bring up your service including all of its dependent services,
as defined with ``depends_on`` (bind, php and httpd). You need to replace ``<name>`` with the
name you have chosen.

.. code-block:: bash

   host> docker-compose up <name>

In the example of Cockroach DB the command would look like this

.. code-block:: bash

   host> docker-compose up cockroach


Further reading
===============

.. seealso::
   * :ref:`docker_compose_override_yml`
   * :ref:`overwrite_existing_docker_image`
