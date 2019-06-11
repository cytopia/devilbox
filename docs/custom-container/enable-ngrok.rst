.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _custom_container_enable_ngrok:

**************************
Enable and configure Ngrok
**************************

This section will guide you through getting Ngrok integrated into the Devilbox.

.. seealso::
   * |ext_lnk_ngrok_github|
   * |ext_lnk_ngrok_dockerhub|
   * :ref:`custom_container_enable_all_additional_container`
   * :ref:`docker_compose_override_yml_how_does_it_work`


**Table of Contents**

.. contents:: :local:


Overview
========

Available overwrites
--------------------

.. include:: /_includes/snippets/docker-compose-override-tree-view.rst


Ngrok settings
--------------

In case of Ngrok, the file is ``compose/docker-compose.override.yml-ngrok``. This file
must be copied into the root of the Devilbox git directory.

+-----------------------+-----------------------------------------------------------------------------------------------------+
| What                  | How and where                                                                                       |
+=======================+=====================================================================================================+
| Example compose file  | ``compose/docker-compose.override.yml-all`` or |br| ``compose/docker-compose.override.yml-ngrok``   |
+-----------------------+-----------------------------------------------------------------------------------------------------+
| Container IP address  | ``172.16.238.202``                                                                                  |
+-----------------------+-----------------------------------------------------------------------------------------------------+
| Container host name   | ``ngrok``                                                                                           |
+-----------------------+-----------------------------------------------------------------------------------------------------+
| Container name        | ``ngrok``                                                                                           |
+-----------------------+-----------------------------------------------------------------------------------------------------+
| Mount points          | none                                                                                                |
+-----------------------+-----------------------------------------------------------------------------------------------------+
| Exposed port          | ``4040`` (can be changed via ``.env``)                                                              |
+-----------------------+-----------------------------------------------------------------------------------------------------+
| Available at          | ``http://localhost:4040``                                                                           |
+-----------------------+-----------------------------------------------------------------------------------------------------+
| Further configuration | ``NGROK_HTTP_TUNNELS``, ``NGROK_AUTHTOKEN`` and ``NGROK_REGION``                                    |
+-----------------------+-----------------------------------------------------------------------------------------------------+

Ngrok env variables
-------------------

Additionally the following ``.env`` variables can be created for easy configuration:

+------------------------------+--------------------+----------------------------------------------------------------------------+
| Variable                     | Default value      | Description                                                                |
+==============================+====================+============================================================================+
| ``HOST_PORT_NGROK``          | ``4040``           | Controls the host port on which Ngrok admin UI will be available at.       |
+------------------------------+--------------------+----------------------------------------------------------------------------+
| ``NGROK_HTTP_TUNNELS``       | ``httpd:httpd:80`` | Defines one or more Ngrok tunnels (depending on your license)              |
+------------------------------+--------------------+----------------------------------------------------------------------------+
| ``NGROK_AUTHTOKEN``          | empty              | Free or paid license token for Ngrok (can also be empty)                   |
+------------------------------+--------------------+----------------------------------------------------------------------------+
| ``NGROK_REGION``             | ``us``             | Choose the region where the ngrok client will connect to host its tunnels. |
+------------------------------+--------------------+----------------------------------------------------------------------------+

NGROK_HTTP_TUNNELS
^^^^^^^^^^^^^^^^^^

Ngrok tunnel definitions can be in the form of:

* ``<domain.tld>:<addr>:<port>``
* ``<domain1.tld>:<addr>:<port>,<domain2.tld>:<addr>:<port>``

.. note::
   If you don't use a license you can only specify a single tunnel. If your license is pro enough,
   you can have multiple comma separated tunnels.

* ``<domain.tld>`` is the virtual hostname that you want to serve via Ngrok
* ``<addr>`` is the hostname or IP address of the web server
* ``<port>`` is the port on which the web server is reachable via HTTP

.. code-block:: bash

   # Make vhost "project1.loc" which runs on localhost:8080 available
   HTTP_TUNNELS=project1.loc:localhost:8080

   # Make two vhosts available which run on host apache:80
   HTTP_TUNNELS=project1.loc:apache:80,project2.loc:apache:80

   # Make two vhosts from two different web server addresses available
   HTTP_TUNNELS=project1.loc:localhost:8080,project2.loc:apache:80


Instructions
============

1. Copy docker-compose.override.yml
-----------------------------------

Copy the Ngrok Docker Compose overwrite file into the root of the Devilbox git directory.
(It must be at the same level as the default ``docker-compose.yml`` file).

.. code-block:: bash

   host> cp compose/docker-compose.override.yml-ngrok docker-compose.override.yml

.. seealso::
   * :ref:`docker_compose_override_yml`
   * :ref:`add_your_own_docker_image`
   * :ref:`overwrite_existing_docker_image`


2. Adjust ``.env`` settings (optional)
--------------------------------------

By Default Ngrok will forward the ``httpd`` domain, which is represents the default virtual host
(the Devilbox intranet) to your web server (also named ``httpd``) and makes the admin UI available
on port ``4040`` on your local machine.

You can of course change the domain as well as where to forward it to (e.g.: to Varnish or HAProxy
instead).

Additionally you can also specify a license token in order to allow for more tunnels via
``NGROK_AUTHTOKEN``.

.. code-block:: bash
   :caption: .env

   HOST_PORT_NGROK=4040
   # Share project1.loca over the internet
   NGROK_HTTP_TUNNELS=project1.loc:httpd:80
   # No license token specified
   NGROK_AUTHTOKEN=

.. seealso:: :ref:`env_file`


3. Start the Devilbox
---------------------

The final step is to start the Devilbox with Ngrok.

Let's assume you want to start ``php``, ``httpd``, ``bind`` and ``ngrok``.

.. code-block:: bash

   host> docker-compose up -d php httpd bind ngrok

.. seealso:: :ref:`start_the_devilbox`


4. Start using it
-----------------

* Once the Devilbox is running, visit http://localhost:4040 in your browser.
* Get URL for public available project


TL;DR
=====

For the lazy readers, here are all commands required to get you started.
Simply copy and paste the following block into your terminal from the root of your Devilbox git
directory:

.. code-block:: bash

   # Copy compose-override.yml into place
   cp compose/docker-compose.override.yml-ngrok docker-compose.override.yml

   # Create .env variable
   echo "HOST_PORT_NGROK=4040"                      >> .env
   echo "# Share project1.loca over the internet"   >> .env
   echo "NGROK_HTTP_TUNNELS=project1.loc:httpd:80"  >> .env
   echo "# No license token specified"              >> .env
   echo "NGROK_AUTHTOKEN="                          >> .env
   echo "NGROK_REGION=us"                           >> .env

   # Start container
   docker-compose up -d php httpd bind ngrok
