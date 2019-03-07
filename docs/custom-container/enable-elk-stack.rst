.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _custom_container_enable_elk_stack:

******************************
Enable and configure ELK Stack
******************************

This section will guide you through getting ELK stack (Elastic Search, Logstash and Kibana)
integrated into the Devilbox.

.. seealso::
   * |ext_lnk_elk_elastic_github|
   * |ext_lnk_elk_logstash_github|
   * |ext_lnk_elk_kibana_github|
   * |ext_lnk_elk_docker_registry|
   * :ref:`custom_container_enable_all_additional_container`
   * :ref:`docker_compose_override_yml_how_does_it_work`


**Table of Contents**

.. contents:: :local:


Overview
========

Available overwrites
--------------------

.. include:: /_includes/snippets/docker-compose-override-tree-view.rst


ELK settings
------------

In case of ELK stack, the file is ``compose/docker-compose.override.yml-elk``. This file
must be copied into the root of the Devilbox git directory.

+-----------------------+------------------------------------------------------------------------------------------------------+
| What                  | How and where                                                                                        |
+=======================+======================================================================================================+
| Example compose file  | ``compose/docker-compose.override.yml-all`` or |br| ``compose/docker-compose.override.yml-elk``      |
+-----------------------+------------------------------------------------------------------------------------------------------+

Elastic Search
^^^^^^^^^^^^^^

+-----------------------+------------------------------------------------------------------------------------------------------+
| What                  | How and where                                                                                        |
+=======================+======================================================================================================+
| Container IP address  | ``172.16.238.240``                                                                                   |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container host name   | ``elastic``                                                                                          |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container name        | ``elastic``                                                                                          |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Mount points          | none                                                                                                 |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Exposed port          | ``9200`` (can be changed via ``.env``)                                                               |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Available at          | ``http://localhost:9200``                                                                            |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Further configuration | ``.env`` vars                                                                                        |
+-----------------------+------------------------------------------------------------------------------------------------------+

Logstash
^^^^^^^^

+-----------------------+------------------------------------------------------------------------------------------------------+
| What                  | How and where                                                                                        |
+=======================+======================================================================================================+
| Container IP address  | ``172.16.238.241``                                                                                   |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container host name   | ``logstash``                                                                                         |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container name        | ``logstash``                                                                                         |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Mount points          | none                                                                                                 |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Exposed port          | ``9600`` (can be changed via ``.env``)                                                               |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Available at          | ``tcp://localhost:9600``                                                                             |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Further configuration | ``.env`` vars                                                                                        |
+-----------------------+------------------------------------------------------------------------------------------------------+

kibana
^^^^^^

+-----------------------+------------------------------------------------------------------------------------------------------+
| What                  | How and where                                                                                        |
+=======================+======================================================================================================+
| Container IP address  | ``172.16.238.242``                                                                                   |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container host name   | ``kibana``                                                                                           |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container name        | ``kibana``                                                                                           |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Mount points          | none                                                                                                 |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Exposed port          | ``9600`` (can be changed via ``.env``)                                                               |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Available at          | ``tcp://localhost:9600``                                                                             |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Further configuration | ``.env`` vars                                                                                        |
+-----------------------+------------------------------------------------------------------------------------------------------+


ELK env variables
-----------------

Additionally the following ``.env`` variables can be created for easy configuration:

Elastic Search
^^^^^^^^^^^^^^

+------------------------------+-----------------------------------------------+----------------------------------------------------------------------+
| Variable                     | Default value                                 | Description                                                          |
+==============================+===============================================+======================================================================+
| ``HOST_PORT_ELK_ELASTIC``    | ``9200``                                      | Controls the host port on which Elastic Search will be available at. |
+------------------------------+-----------------------------------------------+----------------------------------------------------------------------+
| ``ELK_SERVER``               | ``6.6.1``                                     | Controls the ELK stack version to use.                               |
+------------------------------+-----------------------------------------------+----------------------------------------------------------------------+
| ``TIMEZONE``                 | ``UTC``                                       | Timezone for Elastic Search (already available in ``.env``).         |
+------------------------------+-----------------------------------------------+----------------------------------------------------------------------+

Logstash
^^^^^^^^

+------------------------------+-----------------------------------------------+----------------------------------------------------------------------+
| Variable                     | Default value                                 | Description                                                          |
+==============================+===============================================+======================================================================+
| ``HOST_PORT_ELK_LOGSTASH``   | ``9600``                                      | Controls the host port on which Logstash will be available at.       |
+------------------------------+-----------------------------------------------+----------------------------------------------------------------------+
| ``ELK_SERVER``               | ``6.6.1``                                     | Controls the ELK stack version to use.                               |
+------------------------------+-----------------------------------------------+----------------------------------------------------------------------+
| ``TIMEZONE``                 | ``UTC``                                       | Timezone for Logstash (already available in ``.env``).               |
+------------------------------+-----------------------------------------------+----------------------------------------------------------------------+

Kibana
^^^^^^

+------------------------------+-----------------------------------------------+----------------------------------------------------------------------+
| Variable                     | Default value                                 | Description                                                          |
+==============================+===============================================+======================================================================+
| ``HOST_PORT_ELK_KIBANA``     | ``5601``                                      | Controls the host port on which Kibana will be available at.         |
+------------------------------+-----------------------------------------------+----------------------------------------------------------------------+
| ``ELK_SERVER``               | ``6.6.1``                                     | Controls the ELK stack version to use.                               |
+------------------------------+-----------------------------------------------+----------------------------------------------------------------------+
| ``TIMEZONE``                 | ``UTC``                                       | Timezone for Kibana (already available in ``.env``).                 |
+------------------------------+-----------------------------------------------+----------------------------------------------------------------------+


Instructions
============

1. Copy docker-compose.override.yml
-----------------------------------

Copy the ELK stack Docker Compose overwrite file into the root of the Devilbox git directory.
(It must be at the same level as the default ``docker-compose.yml`` file).

.. code-block:: bash

   host> cp compose/docker-compose.override.yml-elk docker-compose.override.yml

.. seealso::
   * :ref:`docker_compose_override_yml`
   * :ref:`add_your_own_docker_image`
   * :ref:`overwrite_existing_docker_image`


2. Adjust ``.env`` settings (optional)
--------------------------------------

The ELK stack is using sane defaults, which can be changed by adding variables to the ``.env`` file
and assigning custom values.

Add the following variables to ``.env`` and adjust them to your needs:

.. code-block:: bash
   :caption: .env

   # ELK stack general
   # See here for all versions: https://www.docker.elastic.co/
   #ELK_SERVER=6.1.4
   #ELK_SERVER=6.2.4
   #ELK_SERVER=6.3.2
   #ELK_SERVER=6.4.3
   #ELK_SERVER=6.5.4
   ELK_SERVER=6.6.1

   # Elastic Search settings
   HOST_PORT_ELK_ELASTIC=9200

   # Logstash settings
   HOST_PORT_ELK_LOGSTASH=9600

   # Kibana settings
   HOST_PORT_ELK_KIBANA=5601

.. seealso:: :ref:`env_file`


3. Start the Devilbox
---------------------

The final step is to start the Devilbox with ELK stack.

Let's assume you want to start ``php``, ``httpd``, ``bind``, ``elastic``, ``logstash``, ``kibana``.

.. code-block:: bash

   host> docker-compose up -d php httpd bind elastic logstash kibana

.. seealso:: :ref:`start_the_devilbox`


TL;DR
=====

For the lazy readers, here are all commands required to get you started.
Simply copy and paste the following block into your terminal from the root of your Devilbox git
directory:

.. code-block:: bash

   # Copy compose-override.yml into place
   cp compose/docker-compose.override.yml-elk docker-compose.override.yml

   # Create .env variable
   echo "# ELK stack general"               >> .env
   echo "# See here for all versions:"      >> .env
   echo "# https://www.docker.elastic.co/"  >> .env
   echo "#ELK_SERVER=6.1.4"                 >> .env
   echo "#ELK_SERVER=6.2.4"                 >> .env
   echo "#ELK_SERVER=6.3.2"                 >> .env
   echo "#ELK_SERVER=6.4.3"                 >> .env
   echo "#ELK_SERVER=6.5.4"                 >> .env
   echo "ELK_SERVER=6.6.1"                  >> .env
   echo "# Elastic Search settings"         >> .env
   echo "HOST_PORT_ELK_ELASTIC=9200"        >> .env
   echo "# Logstash settings"               >> .env
   echo "HOST_PORT_ELK_LOGSTASH=9600"       >> .env
   echo "# Kibana settings"                 >> .env
   echo "HOST_PORT_ELK_KIBANA=5601"         >> .env

   # Start container
   docker-compose up -d php httpd bind elastic logstash kibana
