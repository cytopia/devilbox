.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _custom_container_enable_python_flask:

*********************************
Enable and configure Python Flask
*********************************

This section will guide you through getting Python Flask integrated into the Devilbox.

.. seealso::
   * :ref:`example_setup_reverse_proxy_python_flask`
   * :ref:`custom_container_enable_all_additional_container`
   * :ref:`docker_compose_override_yml_how_does_it_work`


**Table of Contents**

.. contents:: :local:


Overview
========

Available overwrites
--------------------

.. include:: /_includes/snippets/docker-compose-override-tree-view.rst


Python Flask settings
---------------------

In case of Python Flask, the file is ``compose/docker-compose.override.yml-python-flask``. This file
must be copied into the root of the Devilbox git directory.

+-----------------------+----------------------------------------------------------------------------------------------------------+
| What                  | How and where                                                                                            |
+=======================+==========================================================================================================+
| Example compose file  | ``compose/docker-compose.override.yml-all`` or |br| ``compose/docker-compose.override.yml-python-flask`` |
+-----------------------+----------------------------------------------------------------------------------------------------------+
| Container IP address  | ``172.16.238.250``                                                                                       |
+-----------------------+----------------------------------------------------------------------------------------------------------+
| Container host name   | ``flask1``                                                                                               |
+-----------------------+----------------------------------------------------------------------------------------------------------+
| Container name        | ``flask1``                                                                                               |
+-----------------------+----------------------------------------------------------------------------------------------------------+
| Mount points          | ``data/www```                                                                                            |
+-----------------------+----------------------------------------------------------------------------------------------------------+
| Exposed port          | none                                                                                                     |
+-----------------------+----------------------------------------------------------------------------------------------------------+
| Available at          | Devilbox intranet via Reverse Proxy configuration                                                        |
+-----------------------+----------------------------------------------------------------------------------------------------------+
| Further configuration | :ref:`example_setup_reverse_proxy_python_flask`                                                          |
+-----------------------+----------------------------------------------------------------------------------------------------------+

Python Flask env variables
--------------------------

Additionally the following ``.env`` variables can be created for easy configuration:

+------------------------------+-------------------+----------------------------------------------------------------------------+
| Variable                     | Default value     | Description                                                                |
+==============================+===================+============================================================================+
| ``FLASK_PROJECT``            | none              | Specifies your Python Flask project dir in data/www.                       |
+------------------------------+-------------------+----------------------------------------------------------------------------+
| ``PYTHON_VERSION``           | ``3.8``           | Specifies the Python version to use for Flask.                             |
+------------------------------+-------------------+----------------------------------------------------------------------------+


Instructions
============

1. Copy docker-compose.override.yml
-----------------------------------

Copy the Python Flask Docker Compose overwrite file into the root of the Devilbox git directory.
(It must be at the same level as the default ``docker-compose.yml`` file).

.. code-block:: bash

   host> cp compose/docker-compose.override.yml-python-flask docker-compose.override.yml

.. seealso::
   * :ref:`docker_compose_override_yml`
   * :ref:`add_your_own_docker_image`
   * :ref:`overwrite_existing_docker_image`


2. Adjust ``.env`` settings (optional)
--------------------------------------

Python Flask is using sane defaults, which can be changed by adding variables to the ``.env`` file
and assigning custom values.

Add the following variables to ``.env`` and adjust them to your needs:

.. code-block:: bash
   :caption: .env

   # Project directory in data/www
   FLASK_PROJECT=my-flask

   # Python version to choose
   #PYTHON_VERSION=2.7
   #PYTHON_VERSION=3.5
   #PYTHON_VERSION=3.6
   #PYTHON_VERSION=3.7
   PYTHON_VERSION=3.8

.. seealso:: :ref:`env_file`


3. Configure Reverse Proxy
--------------------------

Before starting up the devilbox you will need to configure your python flask project and the
reverse proxy settings.

.. seealso:: :ref:`example_setup_reverse_proxy_python_flask`


TL;DR
=====

For the lazy readers, here are all commands required to get you started.
Simply copy and paste the following block into your terminal from the root of your Devilbox git
directory:

.. code-block:: bash

   # Copy compose-override.yml into place
   cp compose/docker-compose.override.yml-flask1 docker-compose.override.yml

   # Create .env variable
   echo "# Project directory in data/www"   > .env
   echo "FLASK_PROJECT=my-flask"           >> .env
   echo "# Python version to choose"       >> .env
   echo "#PYTHON_VERSION=2.7"              >> .env
   echo "#PYTHON_VERSION=3.5"              >> .env
   echo "#PYTHON_VERSION=3.6"              >> .env
   echo "#PYTHON_VERSION=3.7"              >> .env
   echo "PYTHON_VERSION=3.8"               >> .env

before starting up the devilbox you will need to configure your python flask project and the
reverse proxy settings.

.. seealso:: :ref:`example_setup_reverse_proxy_python_flask`
