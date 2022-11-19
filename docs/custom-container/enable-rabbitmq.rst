.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _custom_container_enable_rabbitmq:

*****************************
Enable and configure RabbitMQ
*****************************

This section will guide you through getting RabbitMQ integrated into the Devilbox.

.. seealso::
   * |ext_lnk_rabbitmq_github|
   * |ext_lnk_rabbitmq_dockerhub|
   * :ref:`custom_container_enable_all_additional_container`
   * :ref:`docker_compose_override_yml_how_does_it_work`


**Table of Contents**

.. contents:: :local:


Overview
========

Available overwrites
--------------------

.. include:: /_includes/snippets/docker-compose-override-tree-view.rst


RabbitMQ settings
-----------------

In case of RabbitMQ, the file is ``compose/docker-compose.override.yml-rabbitmq``. This file
must be copied into the root of the Devilbox git directory.

+-----------------------+------------------------------------------------------------------------------------------------------+
| What                  | How and where                                                                                        |
+=======================+======================================================================================================+
| Example compose file  | ``compose/docker-compose.override.yml-all`` or |br| ``compose/docker-compose.override.yml-rabbitmq`` |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container IP address  | ``172.16.238.210``                                                                                   |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container host name   | ``rabbit``                                                                                           |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container name        | ``rabbit``                                                                                           |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Mount points          | via Docker volumes                                                                                   |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Exposed port          | ``5672`` and ``15672`` (can be changed via ``.env``)                                                 |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Available at          | ``http://localhost:15672`` (Admin WebUI)                                                             |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Further configuration | none                                                                                                 |
+-----------------------+------------------------------------------------------------------------------------------------------+

RabbitMQ env variables
----------------------

Additionally the following ``.env`` variables can be created for easy configuration:

+------------------------------+-------------------+----------------------------------------------------------------------------+
| Variable                     | Default value     | Description                                                                |
+==============================+===================+============================================================================+
| ``HOST_PORT_RABBIT``         | ``5672``          | Controls the host port on which RabbitMQ API will be available at.         |
+------------------------------+-------------------+----------------------------------------------------------------------------+
| ``HOST_PORT_RABBIT_MGMT``    | ``15672``         | Controls the host port on which RabbitMQ Admin WebUI will be available at. |
+------------------------------+-------------------+----------------------------------------------------------------------------+
| ``RABBIT_SERVER``            | ``management``    | Controls the RabbitMQ version to use.                                      |
+------------------------------+-------------------+----------------------------------------------------------------------------+
| ``RABBIT_DEFAULT_VHOST``     | ``my_vhost``      | Default RabbitMQ vhost name. (not a webserver vhost name)                  |
+------------------------------+-------------------+----------------------------------------------------------------------------+
| ``RABBIT_DEFAULT_USER``      | ``guest``         | Default username for Admin WebUI.                                          |
+------------------------------+-------------------+----------------------------------------------------------------------------+
| ``RABBIT_DEFAULT_PASS``      | ``guest``         | Default password for Admin WebUI.                                          |
+------------------------------+-------------------+----------------------------------------------------------------------------+



Instructions
============

1. Copy docker-compose.override.yml
-----------------------------------

Copy the RabbitMQ Docker Compose overwrite file into the root of the Devilbox git directory.
(It must be at the same level as the default ``docker-compose.yml`` file).

.. code-block:: bash

   host> cp compose/docker-compose.override.yml-rabbitmq docker-compose.override.yml

.. seealso::
   * :ref:`docker_compose_override_yml`
   * :ref:`add_your_own_docker_image`
   * :ref:`overwrite_existing_docker_image`


2. Adjust ``.env`` settings (optional)
--------------------------------------

RabbitMQ is using sane defaults, which can be changed by adding variables to the ``.env`` file
and assigning custom values.

Add the following variables to ``.env`` and adjust them to your needs:

.. code-block:: bash
   :caption: .env

   # RabbitMQ version to choose
   #RABBIT_SERVER=3.6
   #RABBIT_SERVER=3.6-management
   #RABBIT_SERVER=3.7
   #RABBIT_SERVER=3.7-management
   #RABBIT_SERVER=latest
   RABBIT_SERVER=management

   RABBIT_DEFAULT_VHOST=my_vhost
   RABBIT_DEFAULT_USER=guest
   RABBIT_DEFAULT_PASS=guest

   HOST_PORT_RABBIT=5672
   HOST_PORT_RABBIT_MGMT=15672

.. seealso:: :ref:`env_file`


3. Start the Devilbox
---------------------

The final step is to start the Devilbox with RabbitMQ.

Let's assume you want to start ``php``, ``httpd``, ``bind``, ``rabbit``.

.. code-block:: bash

   host> docker-compose up -d php httpd bind rabbit

.. seealso:: :ref:`start_the_devilbox`


TL;DR
=====

For the lazy readers, here are all commands required to get you started.
Simply copy and paste the following block into your terminal from the root of your Devilbox git
directory:

.. code-block:: bash

   # Copy compose-override.yml into place
   cp compose/docker-compose.override.yml-rabbitmq docker-compose.override.yml

   # Create .env variable
   echo "# RabbitMQ version to choose"           >> .env
   echo "#RABBIT_SERVER=3.6"                     >> .env
   echo "#RABBIT_SERVER=3.6-management"          >> .env
   echo "#RABBIT_SERVER=3.7"                     >> .env
   echo "#RABBIT_SERVER=3.7-management"          >> .env
   echo "#RABBIT_SERVER=latest"                  >> .env
   echo "RABBIT_SERVER=management"               >> .env
   echo "RABBIT_DEFAULT_VHOST=my_vhost"          >> .env
   echo "RABBIT_DEFAULT_USER=guest"              >> .env
   echo "RABBIT_DEFAULT_PASS=guest"              >> .env
   echo "HOST_PORT_RABBIT=5672"                  >> .env
   echo "HOST_PORT_RABBIT_MGMT=15672"            >> .env

   # Start container
   docker-compose up -d php httpd bind rabbit
