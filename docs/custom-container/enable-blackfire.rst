.. include:: /_includes/all.rst

.. _custom_container_enable_blackfire:

******************************
Enable and configure Blackfire
******************************

This section will guide you through getting Blackfire integrated into the Devilbox.

.. seealso::
   * |ext_lnk_blackfire_github|
   * |ext_lnk_blackfire_dockerhub|
   * :ref:`custom_container_enable_all_additional_container`


**Table of Contents**

.. contents:: :local:


Overview
========

Available overwrites
--------------------

.. include:: /_includes/snippets/docker-compose-override-tree-view.rst


Blackfire settings
------------------

In case of Blackfire, the file is ``compose/docker-compose.override.yml-blackfire``. This file
must be copied into the root of the Devilbox git directory.

+-----------------------+-------------------------------------------------------------------------------------------------------+
| What                  | How and where                                                                                         |
+=======================+=======================================================================================================+
| Example compose file  | ``compose/docker-compose.override.yml-all`` or |br| ``compose/docker-compose.override.yml-blackfire`` |
+-----------------------+-------------------------------------------------------------------------------------------------------+
| Container IP address  | ``172.16.238.200``                                                                                    |
+-----------------------+-------------------------------------------------------------------------------------------------------+
| Container host name   | ``blackfire``                                                                                         |
+-----------------------+-------------------------------------------------------------------------------------------------------+
| Container name        | ``blackfire``                                                                                         |
+-----------------------+-------------------------------------------------------------------------------------------------------+
| Mount points          | none                                                                                                  |
+-----------------------+-------------------------------------------------------------------------------------------------------+
| Exposed port          | none                                                                                                  |
+-----------------------+-------------------------------------------------------------------------------------------------------+
| Available at          | n.a.                                                                                                  |
+-----------------------+-------------------------------------------------------------------------------------------------------+
| Further configuration | ``BLACKFIRE_SERVER_ID`` and ``BLACKFIRE_SERVER_TOKEN`` must be set via ``.env``                       |
+-----------------------+-------------------------------------------------------------------------------------------------------+

Blackfire env variables
-----------------------

Additionally the following ``.env`` variables can be created for easy configuration:

+------------------------------+---------------+---------------------------------------------------------------+
| Variable                     | Default value | Description                                                   |
+==============================+===============+===============================================================+
| ``BLACKFIRE_SERVER_ID``      | ``id``        | A valid server id is required in order to use blackfire.      |
+------------------------------+---------------+---------------------------------------------------------------+
| ``BLACKFIRE_SERVER_TOKEN``   | ``token``     | A valid server token is required in order to use blackfire.   |
+------------------------------+---------------+---------------------------------------------------------------+
| ``BLACKFIRE_SERVER``         | ``latest``    | Controls the Blackfire version to use.                        |
+------------------------------+---------------+---------------------------------------------------------------+


Instructions
============

1. Copy docker-compose.override.yml
-----------------------------------

Copy the Blackfire Docker Compose overwrite file into the root of the Devilbox git directory.
(It must be at the same level as the default ``docker-compose.yml`` file).

.. code-block:: bash

   host> cp compose/docker-compose.override.yml-blackfire docker-compose.override.yml

.. seealso::
   * :ref:`docker_compose_override_yml`
   * :ref:`add_your_own_docker_image`
   * :ref:`overwrite_existing_docker_image`


2. Adjust ``env`` settings (required)
-------------------------------------

By default Blackfire is using some dummy values for BLACKFIRE_SERVER_ID and BLACKFIRE_SERVER_TOKEN.
You must however aquire valid values and set the in your ``.env`` file in order for Blackfire
to properly start. Those values can be obtained at their official webpage.

.. code-block:: bash
   :caption: .env

   BLACKFIRE_SERVER_ID=<valid server id>
   BLACKFIRE_SERVER_TOKEN=<valid server token>

   #BLACKFIRE_SERVER=1.12.0
   #BLACKFIRE_SERVER=1.13.0
   #BLACKFIRE_SERVER=1.14.0
   #BLACKFIRE_SERVER=1.14.1
   #BLACKFIRE_SERVER=1.15.0
   #BLACKFIRE_SERVER=1.16.0
   #BLACKFIRE_SERVER=1.17.0
   #BLACKFIRE_SERVER=1.17.1
   #BLACKFIRE_SERVER=1.18.0
   BLACKFIRE_SERVER=latest

.. seealso:: :ref:`env_file`


3. Start the Devilbox
---------------------

The final step is to start the Devilbox with Blackfire.

Let's assume you want to start ``php``, ``httpd``, ``bind`` and ``blackfire``.

.. code-block:: bash

   host> docker-compose up -d php httpd bind blackfire

.. seealso:: :ref:`start_the_devilbox`


TL;DR
=====

For the lazy readers, here are all commands required to get you started.
Simply copy and paste the following block into your terminal from the root of your Devilbox git
directory:

.. code-block:: bash

   # Copy compose-override.yml into place
   cp compose/docker-compose.override.yml-blackfire docker-compose.override.yml

   # Create .env variable
   echo "BLACKFIRE_SERVER_ID=<valid server id>"       >> .env
   echo "BLACKFIRE_SERVER_TOKEN=<valid server token>" >> .env

   echo "#BLACKFIRE_SERVER=1.12.0"                    >> .env
   echo "#BLACKFIRE_SERVER=1.13.0"                    >> .env
   echo "#BLACKFIRE_SERVER=1.14.0"                    >> .env
   echo "#BLACKFIRE_SERVER=1.14.1"                    >> .env
   echo "#BLACKFIRE_SERVER=1.15.0"                    >> .env
   echo "#BLACKFIRE_SERVER=1.16.0"                    >> .env
   echo "#BLACKFIRE_SERVER=1.17.0"                    >> .env
   echo "#BLACKFIRE_SERVER=1.17.1"                    >> .env
   echo "#BLACKFIRE_SERVER=1.18.0"                    >> .env
   echo "BLACKFIRE_SERVER=latest"                     >> .env

   # Start container
   docker-compose up -d php httpd bind blackfire
