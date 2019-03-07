.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _custom_container_enable_blackfire:

******************************
Enable and configure Blackfire
******************************

This section will guide you through getting Blackfire integrated into the Devilbox.

.. seealso::
   * |ext_lnk_blackfire_github|
   * |ext_lnk_blackfire_dockerhub|
   * :ref:`custom_container_enable_all_additional_container`
   * :ref:`docker_compose_override_yml_how_does_it_work`


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

+------------------------------+---------------+-------------------------------------------------------------------------+
| Variable                     | Default value | Description                                                             |
+==============================+===============+=========================================================================+
| ``BLACKFIRE_SERVER``         | ``latest``    | Controls the Blackfire version to use.                                  |
+------------------------------+---------------+-------------------------------------------------------------------------+
| ``BLACKFIRE_SERVER_ID``      | ``id``        | A valid server id is required in order to start ``blackfire-agent``.    |
+------------------------------+---------------+-------------------------------------------------------------------------+
| ``BLACKFIRE_SERVER_TOKEN``   | ``token``     | A valid server token is required in order to start ``blackfire-agent``. |
+------------------------------+---------------+-------------------------------------------------------------------------+
| ``BLACKFIRE_CLIENT_ID``      | ``id``        | A valid client id is required in order to use ``blackfire`` cli.        |
+------------------------------+---------------+-------------------------------------------------------------------------+
| ``BLACKFIRE_CLIENT_TOKEN``   | ``token``     | A valid client token is required in order to use ``blackfire`` cli.     |
+------------------------------+---------------+-------------------------------------------------------------------------+


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


2. Adjust ``env`` settings
--------------------------

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

You must also explicitly enable the PHP ``blackfire`` module and disable ``xdebug`` via ``.env``

.. code-block:: bash
   :caption: .env

   PHP_MODULES_ENABLE=blackfire
   PHP_MODULES_DISABLE=xdebug

.. seealso:: :ref:`env_file`


3. Copy blackfire php.ini template
----------------------------------

In order for the PHP ``blackfire`` module to know where the ``blackfire-agent`` is listening,
we must configure its PHP settings. There is already a default template that you can simply copy.

.. code-block:: bash

   host> cp cfg/php-ini-7.2/devilbox-php.ini-blackfire cfg/php-ini-7.2/blackfire.ini

.. seealso::
   The above example shows the procedure for PHP 7.2, if you are using a different version,
   you must navigate to its corresponding configuration directory.

   Read more here: :ref:`php_ini`


4. Configure blackfire cli (optional)
-------------------------------------

If you want to use the ``blackfire`` cli from within the PHP container, its configuration must be
configured. There is already a startup template which does it for you.

You first need to add the Blackfire client id and token to ``.env``:

.. code-block:: bash
   :caption: .env

   BLACKFIRE_CLIENT_ID=<valid client id>
   BLACKFIRE_CLIENT_TOKEN=<valid client token>

Then all that's left to do is to copy the startup script which configures the blackfire cli for you.

.. code-block:: bash

   host> cp autostart/configure-blackfire-cli.sh-example autostart/configure-blackfire-cli.sh

.. seealso::
   * :ref:`custom_scripts_globally`


5. Start the Devilbox
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

   # Copy php.ini into place
   cp cfg/php-ini-7.2/devilbox-php.ini-blackfire cfg/php-ini-7.2/blackfire.ini

   # Set Blackfire server id and token
   echo "BLACKFIRE_SERVER_ID=<valid server id>"       >> .env
   echo "BLACKFIRE_SERVER_TOKEN=<valid server token>" >> .env

   # Set Blackfire client id and token
   echo "BLACKFIRE_CLIENT_ID=<valid client id>"       >> .env
   echo "BLACKFIRE_CLIENT_TOKEN=<valid client token>" >> .env

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

   # Ensure blackfire is enabled and xdebug is disabled
   # IMPORTANT: This replacement is only an example and will overwrite
   #            all other enabled/disabled modules.
   #            Do not do it this way.
   sed -i'' 's/^PHP_MODULES_ENABLE=.*/PHP_MODULES_ENABLE=blackfire/g' .env
   sed -i'' 's/^PHP_MODULES_DISABLE=.*/PHP_MODULES_ENABLE=xdebug/g' .env

   # Start container
   docker-compose up -d php httpd bind blackfire
