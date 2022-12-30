.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _custom_container_enable_meilisearch:

********************************
Enable and configure Meilisearch
********************************

This section will guide you through getting Meilisearch integrated into the Devilbox.

.. seealso::
   * |ext_lnk_meilisearch_github|
   * |ext_lnk_meilisearch_dockerhub|
   * :ref:`custom_container_enable_all_additional_container`
   * :ref:`docker_compose_override_yml_how_does_it_work`


**Table of Contents**

.. contents:: :local:


Overview
========

Available overwrites
--------------------

.. include:: /_includes/snippets/docker-compose-override-tree-view.rst


Meilisearch settings
--------------------

In case of Meilisearch, the file is ``compose/docker-compose.override.yml-meilisearch``. This file
must be copied into the root of the Devilbox git directory.

+-----------------------+--------------------------------------------------------------------------------------------------------+
| What                  | How and where                                                                                          |
+=======================+========================================================================================================+
| Example compose file  | ``compose/docker-compose.override.yml-all`` or |br| ``compose/docker-compose.override.yml-meilisearch``|
+-----------------------+--------------------------------------------------------------------------------------------------------+
| Container IP address  | ``172.16.238.203``                                                                                     |
+-----------------------+--------------------------------------------------------------------------------------------------------+
| Container host name   | ``meilisearch``                                                                                        |
+-----------------------+--------------------------------------------------------------------------------------------------------+
| Container name        | ``meilisearch``                                                                                        |
+-----------------------+--------------------------------------------------------------------------------------------------------+
| Mount points          | via Docker volumes                                                                                     |
+-----------------------+--------------------------------------------------------------------------------------------------------+
| Exposed port          | ``7700`` (can be changed via ``.env``)                                                                 |
+-----------------------+--------------------------------------------------------------------------------------------------------+
| Available at          | ``http://localhost:7700`` (API and Admin WebUI)                                                        |
+-----------------------+--------------------------------------------------------------------------------------------------------+
| Further configuration | none                                                                                                   |
+-----------------------+--------------------------------------------------------------------------------------------------------+

Meilisearch env variables
-------------------------

Additionally the following ``.env`` variables can be created for easy configuration:

+------------------------------+-------------------+----------------------------------------------------------------------------------+
| Variable                     | Default value     | Description                                                                      |
+==============================+===================+==================================================================================+
| ``HOST_PORT_MEILI``          | ``7700``          | Controls the host port on which Meilisearch API and WebUI will be available at.  |
+------------------------------+-------------------+----------------------------------------------------------------------------------+
| ``MEILI_SERVER``             | ``latest``        | Controls the Meilisearch version to use.                                         |
+------------------------------+-------------------+----------------------------------------------------------------------------------+
| ``MEILI_MASTER_KEY``         | none              | Default Meilisearch master key.                                                  |
+------------------------------+-------------------+----------------------------------------------------------------------------------+


Instructions
============

1. Copy docker-compose.override.yml
-----------------------------------

Copy the Meilisearch Docker Compose overwrite file into the root of the Devilbox git directory.
(It must be at the same level as the default ``docker-compose.yml`` file).

.. code-block:: bash

   host> cp compose/docker-compose.override.yml-meilisearch docker-compose.override.yml

.. seealso::
   * :ref:`docker_compose_override_yml`
   * :ref:`add_your_own_docker_image`
   * :ref:`overwrite_existing_docker_image`


2. Adjust ``.env`` settings (optional)
--------------------------------------

Meilisearch is using sane defaults, which can be changed by adding variables to the ``.env`` file
and assigning custom values.

Add the following variables to ``.env`` and adjust them to your needs:

.. code-block:: bash
   :caption: .env

   # Meilisearch version to choose
   #MEILI_SERVER=v0.26.0
   #MEILI_SERVER=v0.27.0
   #MEILI_SERVER=v0.28
   MEILI_SERVER=latest

   MEILI_MASTER_KEY=
   HOST_PORT_MEILI=7700

.. seealso:: :ref:`env_file`


3. Start the Devilbox
---------------------

The final step is to start the Devilbox with Meilisearch.

Let's assume you want to start ``php``, ``httpd``, ``bind``, ``meilisearch``.

.. code-block:: bash

   host> docker-compose up -d php httpd bind meilisearch

.. seealso:: :ref:`start_the_devilbox`


TL;DR
=====

For the lazy readers, here are all commands required to get you started.
Simply copy and paste the following block into your terminal from the root of your Devilbox git
directory:

.. code-block:: bash

   # Copy compose-override.yml into place
   cp compose/docker-compose.override.yml-meilisearch docker-compose.override.yml

   # Create .env variable
   echo "# Meilisearch version to choose"   >> .env
   echo "#MEILI_SERVER=v0.26.0"             >> .env
   echo "#MEILI_SERVER=v0.27.0"             >> .env
   echo "#MEILI_SERVER=v0.28"               >> .env
   echo "MEILI_SERVER=latest"               >> .env
   echo "MEILI_MASTER_KEY="                 >> .env
   echo "HOST_PORT_MEILI=7700"              >> .env

   # Start container
   docker-compose up -d php httpd bind meilisearch
