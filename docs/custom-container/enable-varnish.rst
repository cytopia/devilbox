.. include:: /_includes/all.rst

.. _custom_container_enable_varnish:

****************************
Enable and configure Varnish
****************************

This section will guide you through getting Varnish integrated into the Devilbox.

.. seealso::
   * |ext_lnk_varnish_github|
   * |ext_lnk_varnish_dockerhub|
   * :ref:`custom_container_enable_all_additional_container`
   * :ref:`docker_compose_override_yml_how_does_it_work`


**Table of Contents**

.. contents:: :local:


Overview
========

Available overwrites
--------------------

.. include:: /_includes/snippets/docker-compose-override-tree-view.rst


Varnish settings
----------------

In case of Varnish, the file is ``compose/docker-compose.override.yml-varnish``. This file
must be copied into the root of the Devilbox git directory.

+-----------------------+------------------------------------------------------------------------------------------------------+
| What                  | How and where                                                                                        |
+=======================+======================================================================================================+
| Example compose file  | ``compose/docker-compose.override.yml-all`` or |br| ``compose/docker-compose.override.yml-varnish``  |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container IP address  | ``172.16.238.230``                                                                                   |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container host name   | ``varnish``                                                                                          |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Container name        | ``varnish``                                                                                          |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Mount points          | none                                                                                                 |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Exposed port          | ``6081`` (can be changed via ``.env``)                                                               |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Available at          | ``http://localhost:6081`` (or via ``http:<project>.<TLD>:6081``)                                     |
+-----------------------+------------------------------------------------------------------------------------------------------+
| Further configuration | none                                                                                                 |
+-----------------------+------------------------------------------------------------------------------------------------------+

Varnish env variables
---------------------

Additionally the following ``.env`` variables can be created for easy configuration:

+------------------------------+-----------------------------------------------+--------------------------------------------------------------------+
| Variable                     | Default value                                 | Description                                                        |
+==============================+===============================================+====================================================================+
| ``HOST_PORT_VARNISH``        | ``6081``                                      | Controls the host port on which Varnish will be available at.      |
+------------------------------+-----------------------------------------------+--------------------------------------------------------------------+
| ``VARNISH_SERVER``           | ``6``                                         | Controls the Varnish version to use.                               |
+------------------------------+-----------------------------------------------+--------------------------------------------------------------------+
| ``VARNISH_CONFIG``           | ``/etc/varnish/default.vcl``                  | Path to Varnish configuration file (custom config can be mounted). |
+------------------------------+-----------------------------------------------+--------------------------------------------------------------------+
| ``VARNICS_CACHE_SIZE``       | ``128m``                                      | Varnish Cache size.                                                |
+------------------------------+-----------------------------------------------+--------------------------------------------------------------------+
| ``VARNISH_PARAMS``           | ``-p default_ttl=3600 -p default_grace=3600`` | Additional Varnish startup parameter.                              |
+------------------------------+-----------------------------------------------+--------------------------------------------------------------------+


Instructions
============

1. Copy docker-compose.override.yml
-----------------------------------

Copy the Varnish Docker Compose overwrite file into the root of the Devilbox git directory.
(It must be at the same level as the default ``docker-compose.yml`` file).

.. code-block:: bash

   host> cp compose/docker-compose.override.yml-varnish docker-compose.override.yml

.. seealso::
   * :ref:`docker_compose_override_yml`
   * :ref:`add_your_own_docker_image`
   * :ref:`overwrite_existing_docker_image`


2. Adjust ``.env`` settings (optional)
--------------------------------------

Varnish is using sane defaults, which can be changed by adding variables to the ``.env`` file
and assigning custom values.

Add the following variables to ``.env`` and adjust them to your needs:

.. code-block:: bash
   :caption: .env

   # Varnish version to choose
   #VARNISH_SERVER=4
   #VARNISH_SERVER=5
   VARNISH_SERVER=6

   # Varnish settings
   VARNICS_CACHE_SIZE=128m
   VARNISH_PARAMS=-p default_ttl=3600 -p default_grace=3600
   HOST_PORT_VARNISH=6081

.. seealso:: :ref:`env_file`


4. Start the Devilbox
---------------------

The final step is to start the Devilbox with Varnish.

Let's assume you want to start ``php``, ``httpd``, ``bind``, ``varnish``.

.. code-block:: bash

   host> docker-compose up -d php httpd bind varnish

.. seealso:: :ref:`start_the_devilbox`


TL;DR
=====

For the lazy readers, here are all commands required to get you started.
Simply copy and paste the following block into your terminal from the root of your Devilbox git
directory:

.. code-block:: bash

   # Copy compose-override.yml into place
   cp compose/docker-compose.override.yml-varnish docker-compose.override.yml

   # Create .env variable
   echo "# Varnish version to choose"                               >> .env
   echo "#VARNISH_SERVER=4"                                         >> .env
   echo "#VARNISH_SERVER=5"                                         >> .env
   echo "VARNISH_SERVER=6"                                          >> .env
   echo "# Varnish settings"                                        >> .env
   echo "VARNICS_CACHE_SIZE=128m"                                   >> .env
   echo "VARNISH_PARAMS=-p default_ttl=3600 -p default_grace=3600"  >> .env
   echo "HOST_PORT_VARNISH=6081"                                    >> .env

   # Start container
   docker-compose up -d php httpd bind varnish
