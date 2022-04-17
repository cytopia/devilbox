.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _custom_container_enable_php_community:

**********************************
Enable and configure PHP Community
**********************************

This section will guide you through getting PHP community images integrated into the Devilbox.

.. seealso::
   * |ext_lnk_php_community_github|
   * |ext_lnk_php_community_dockerhub|
   * :ref:`custom_container_enable_all_additional_container`
   * :ref:`docker_compose_override_yml_how_does_it_work`


**Table of Contents**

.. contents:: :local:


Overview
========

Available overwrites
--------------------

.. include:: /_includes/snippets/docker-compose-override-tree-view.rst


PHP-FPM Community settings
--------------------------

In case of PHP-FPM Community, the file is ``compose/docker-compose.override.yml-php-community``. This file
must be copied into the root of the Devilbox git directory.

+-----------------------+-----------------------------------------------------------------------------------------------------------+
| What                  | How and where                                                                                             |
+=======================+===========================================================================================================+
| Example compose file  | ``compose/docker-compose.override.yml-all`` or |br| ``compose/docker-compose.override.yml-php-community`` |
+-----------------------+-----------------------------------------------------------------------------------------------------------+
| Container IP address  | ``172.16.238.10``                                                                                         |
+-----------------------+-----------------------------------------------------------------------------------------------------------+
| Container host name   | ``php``                                                                                                   |
+-----------------------+-----------------------------------------------------------------------------------------------------------+
| Container name        | ``php``                                                                                                   |
+-----------------------+-----------------------------------------------------------------------------------------------------------+
| Mount points          | Same as default php image                                                                                 |
+-----------------------+-----------------------------------------------------------------------------------------------------------+
| Exposed port          | Same as default php image                                                                                 |
+-----------------------+-----------------------------------------------------------------------------------------------------------+
| Available at          | n.a.                                                                                                      |
+-----------------------+-----------------------------------------------------------------------------------------------------------+
| Further configuration | ``PHP_COMMUNITY_FLAVOUR`` must be set via ``.env``                                                        |
+-----------------------+-----------------------------------------------------------------------------------------------------------+

PHP Community env variables
---------------------------

Additionally the following ``.env`` variables can be created for easy configuration:

+------------------------------+---------------+-------------------------------------------------------------------------+
| Variable                     | Default value | Description                                                             |
+==============================+===============+=========================================================================+
| ``PHP_COMMUNITY_FLAVOUR``    | ``devilbox``  | Controls the PHP Community flavour.                                     |
+------------------------------+---------------+-------------------------------------------------------------------------+


Instructions
============

1. Copy docker-compose.override.yml
-----------------------------------

Copy the PHP-FPM Community Docker Compose overwrite file into the root of the Devilbox git directory.
(It must be at the same level as the default ``docker-compose.yml`` file).

.. code-block:: bash

   host> cp compose/docker-compose.override.yml-php-community docker-compose.override.yml

.. seealso::
   * :ref:`docker_compose_override_yml`
   * :ref:`add_your_own_docker_image`
   * :ref:`overwrite_existing_docker_image`


2. Adjust ``env`` settings
--------------------------

By default PHP-FPM Community is using the Devilbox reference flavour ``devilbox``.
You can change this flavour via the ``.env`` variable ``PHP_COMMUNITY_FLAVOUR``.

.. code-block:: bash
   :caption: .env

   PHP_COMMUNITY_FLAVOUR=devilbox


.. seealso:: :ref:`env_file`


3. Start the Devilbox
---------------------

The final step is to start the Devilbox with the newly added PHP-FPM Community images.

Let's assume you want to start ``php``, ``httpd``, and ``bind``.

.. code-block:: bash

   host> docker-compose up -d php httpd bind

.. seealso:: :ref:`start_the_devilbox`


TL;DR
=====

For the lazy readers, here are all commands required to get you started.
Simply copy and paste the following block into your terminal from the root of your Devilbox git
directory:

.. code-block:: bash

   # Copy compose-override.yml into place
   cp compose/docker-compose.override.yml-php-community docker-compose.override.yml

   # Set Community flavour
   echo "PHP_COMMUNITY_FLAVOUR=devilbox" >> .env

   # Start container
   docker-compose up -d php httpd bind
