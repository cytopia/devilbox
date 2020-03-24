.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _custom_container_enable_solr:

*************************
Enable and configure Solr
*************************

This section will guide you through getting Solr integrated into the Devilbox.

.. seealso::
   * |ext_lnk_solr_github|
   * |ext_lnk_solr_dockerhub|
   * :ref:`custom_container_enable_all_additional_container`
   * :ref:`docker_compose_override_yml_how_does_it_work`


**Table of Contents**

.. contents:: :local:


Overview
========

Available overwrites
--------------------

.. include:: /_includes/snippets/docker-compose-override-tree-view.rst


Solr settings
-------------

In case of Solr, the file is ``compose/docker-compose.override.yml-solr``. This file
must be copied into the root of the Devilbox git directory.

+-----------------------+------------------------------------------------------------------------------------------------------+
| What                  | How and where                                                                                        |
+=======================+======================================================================================================+
| Example compose file  | ``compose/docker-compose.override.yml-all`` or |br| ``compose/docker-compose.override.yml-solr``     |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container IP address  | ``172.16.238.220``                                                                                   |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container host name   | ``solr``                                                                                             |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container name        | ``solr``                                                                                             |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Mount points          | via Docker volumes                                                                                   |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Exposed port          | ``8983`` (can be changed via ``.env``)                                                               |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Available at          | ``http://localhost:8983`` (API and Admin WebUI)                                                      |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Further configuration | none                                                                                                 |
+-----------------------+------------------------------------------------------------------------------------------------------+

Solr env variables
------------------

Additionally the following ``.env`` variables can be created for easy configuration:

+------------------------------+-------------------+----------------------------------------------------------------------------+
| Variable                     | Default value     | Description                                                                |
+==============================+===================+============================================================================+
| ``HOST_PORT_SOLR``           | ``8983``          | Controls the host port on which Solr API and WebUIwill be available at.    |
+------------------------------+-------------------+----------------------------------------------------------------------------+
| ``SOLR_SERVER``              | ``latest``        | Controls the Solr version to use.                                          |
+------------------------------+-------------------+----------------------------------------------------------------------------+
| ``SOLR_CORE_NAME``           | ``devilbox``      | Default Solr core name                                                     |
+------------------------------+-------------------+----------------------------------------------------------------------------+


Instructions
============

1. Copy docker-compose.override.yml
-----------------------------------

Copy the Solr Docker Compose overwrite file into the root of the Devilbox git directory.
(It must be at the same level as the default ``docker-compose.yml`` file).

.. code-block:: bash

   host> cp compose/docker-compose.override.yml-solr docker-compose.override.yml

.. seealso::
   * :ref:`docker_compose_override_yml`
   * :ref:`add_your_own_docker_image`
   * :ref:`overwrite_existing_docker_image`


2. Adjust ``.env`` settings (optional)
--------------------------------------

Solr is using sane defaults, which can be changed by adding variables to the ``.env`` file
and assigning custom values.

Add the following variables to ``.env`` and adjust them to your needs:

.. code-block:: bash
   :caption: .env

   # Solr version to choose
   #SOLR_SERVER=5
   #SOLR_SERVER=6
   #SOLR_SERVER=7
   SOLR_SERVER=latest

   SOLR_CORE_NAME=devilbox
   HOST_PORT_SOLR=8983

.. seealso:: :ref:`env_file`


3. Start the Devilbox
---------------------

The final step is to start the Devilbox with Solr.

Let's assume you want to start ``php``, ``httpd``, ``bind``, ``solr``.

.. code-block:: bash

   host> docker-compose up -d php httpd bind solr

.. seealso:: :ref:`start_the_devilbox`


TL;DR
=====

For the lazy readers, here are all commands required to get you started.
Simply copy and paste the following block into your terminal from the root of your Devilbox git
directory:

.. code-block:: bash

   # Copy compose-override.yml into place
   cp compose/docker-compose.override.yml-solr docker-compose.override.yml

   # Create .env variable
   echo "# Solr version to choose"         >> .env
   echo "#SOLR_SERVER=5"                   >> .env
   echo "#SOLR_SERVER=6"                   >> .env
   echo "#SOLR_SERVER=7"                   >> .env
   echo "SOLR_SERVER=latest"               >> .env
   echo "SOLR_CORE_NAME=devilbox"          >> .env
   echo "HOST_PORT_SOLR=8983"              >> .env

   # Start container
   docker-compose up -d php httpd bind solr
