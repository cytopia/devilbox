.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _custom_container_enable_mailhog:

****************************
Enable and configure MailHog
****************************

This section will guide you through getting MailHog integrated into the Devilbox.

.. seealso::
   * |ext_lnk_mailhog_github|
   * |ext_lnk_mailhog_dockerhub|
   * :ref:`custom_container_enable_all_additional_container`
   * :ref:`docker_compose_override_yml_how_does_it_work`


**Table of Contents**

.. contents:: :local:


Overview
========

Available overwrites
--------------------

.. include:: /_includes/snippets/docker-compose-override-tree-view.rst


MailHog settings
----------------

In case of MailHog, the file is ``compose/docker-compose.override.yml-mailhog``. This file
must be copied into the root of the Devilbox git directory.

+-----------------------+-----------------------------------------------------------------------------------------------------+
| What                  | How and where                                                                                       |
+=======================+=====================================================================================================+
| Example compose file  | ``compose/docker-compose.override.yml-all`` or |br| ``compose/docker-compose.override.yml-mailhog`` |
+-----------------------+-----------------------------------------------------------------------------------------------------+
| Container IP address  | ``172.16.238.201``                                                                                  |
+-----------------------+-----------------------------------------------------------------------------------------------------+
| Container host name   | ``mailhog``                                                                                         |
+-----------------------+-----------------------------------------------------------------------------------------------------+
| Container name        | ``mailhog``                                                                                         |
+-----------------------+-----------------------------------------------------------------------------------------------------+
| Mount points          | none                                                                                                |
+-----------------------+-----------------------------------------------------------------------------------------------------+
| Exposed port          | ``8025`` (can be changed via ``.env``)                                                              |
+-----------------------+-----------------------------------------------------------------------------------------------------+
| Available at          | ``http://localhost:8025``                                                                           |
+-----------------------+-----------------------------------------------------------------------------------------------------+
| Further configuration | php.ini settings need to be applied per version                                                     |
+-----------------------+-----------------------------------------------------------------------------------------------------+

MailHog env variables
---------------------

Additionally the following ``.env`` variables can be created for easy configuration:

+------------------------------+---------------+---------------------------------------------------------------+
| Variable                     | Default value | Description                                                   |
+==============================+===============+===============================================================+
| ``HOST_PORT_MAILHOG``        | ``8025``      | Controls the host port on which MailHog will be available at. |
+------------------------------+---------------+---------------------------------------------------------------+
| ``MAILHOG_SERVER``           | ``latest``    | Controls the MailHog version to use.                          |
+------------------------------+---------------+---------------------------------------------------------------+



Instructions
============

1. Copy docker-compose.override.yml
-----------------------------------

Copy the MailHog Docker Compose overwrite file into the root of the Devilbox git directory.
(It must be at the same level as the default ``docker-compose.yml`` file).

.. code-block:: bash

   host> cp compose/docker-compose.override.yml-mailhog docker-compose.override.yml

.. seealso::
   * :ref:`docker_compose_override_yml`
   * :ref:`add_your_own_docker_image`
   * :ref:`overwrite_existing_docker_image`


2. Adjust PHP settings
----------------------

The next step is to tell PHP that it should use a different mail forwarder.

Let's assume you are using PHP 7.2.


.. code-block:: bash

   # Navigate to the PHP ini configuration directory of your chosen version
   host> cd cfg/php-ini-7.2

   # Create and open a new *.ini file
   host> vi mailhog.ini

Add the following content to the newly created ini file:

.. code-block:: ini
   :caption: mailhog.ini

   [mail function]
   sendmail_path = '/usr/local/bin/mhsendmail --smtp-addr="mailhog:1025"'

.. seealso:: :ref:`php_ini`


3. Adjust ``.env`` settings (optional)
--------------------------------------

By Default MailHog is using the host port ``8025``, this can be adjusted in the ``.env`` file.
Add ``HOST_PORT__MAILHOG`` to *.env* and customize its value.

Additionally also the MailHog version can be controlled via ``MAILHOG_SERVER``.

.. code-block:: bash
   :caption: .env

   HOST_PORT_MAILHOG=8025
   MAILHOG_SERVER=latest

.. seealso:: :ref:`env_file`


4. Start the Devilbox
---------------------

The final step is to start the Devilbox with MailHog.

Let's assume you want to start ``php``, ``httpd``, ``bind`` and ``mailhog``.

.. code-block:: bash

   host> docker-compose up -d php httpd bind mailhog

.. seealso:: :ref:`start_the_devilbox`


5. Start using it
-----------------

* Once the Devilbox is running, visit http://localhost:8025 in your browser.
* Any email send by any of the Devilbox managed projects will then appear in MailHog


TL;DR
=====

For the lazy readers, here are all commands required to get you started.
Simply copy and paste the following block into your terminal from the root of your Devilbox git
directory:

.. code-block:: bash

   # Copy compose-override.yml into place
   cp compose/docker-compose.override.yml-mailhog docker-compose.override.yml

   # Create php.ini
   echo "[mail function]" > cfg/php-ini-7.2/mailhog.ini
   echo "sendmail_path = '/usr/local/bin/mhsendmail --smtp-addr=\"mailhog:1025\"'" >> cfg/php-ini-7.2/mailhog.ini

   # Create .env variable
   echo "HOST_PORT_MAILHOG=8025" >> .env
   echo "MAILHOG_SERVER=latest" >> .env

   # Start container
   docker-compose up -d php httpd bind mailhog
