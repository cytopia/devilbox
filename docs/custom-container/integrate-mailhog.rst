.. include:: /_includes/all.rst

.. _custom_container_ingegrate_mailhog:

*****************
Integrate Mailhog
*****************

This section will guide you through getting Mailhog integrated into the Devilbox.

.. seealso:: |ext_lnk_container_mailhog|


**Table of Contents**

.. contents:: :local:


Overview
========

The Devilbox ships various example configurations to overwrite the default stack. Those files are
located under ``compose/`` in the Devilbox git directory.

In case of Mailhog, the file is ``compose/docker-compose.override.yml-mailhog``. This file
must be copied into the root of the Devilbox git directory.

+-----------------------+-------------------------------------------------+
| What                  | How and where                                   |
+=======================+=================================================+
| Example compose file  | ``compose/docker-compose.override.yml-mailhog`` |
+-----------------------+-------------------------------------------------+
| Container IP address  | ``172.16.238.201``                              |
+-----------------------+-------------------------------------------------+
| Exposed port          | ``8025``                                        |
+-----------------------+-------------------------------------------------+
| Available at          | ``http://localhost:8025``                       |
+-----------------------+-------------------------------------------------+
| Container name        | ``mailhog``                                     |
+-----------------------+-------------------------------------------------+
| Further configuration | php.ini settings need to be applied per version |
+-----------------------+-------------------------------------------------+


Instructions
============

Copy example docker-compose.override.yml
----------------------------------------

Copy the mailhog Docker Compose overwrite file into the root of the Devilbox git directory.

.. code-block:: bash

   host> cp compose/docker-compose.override.yml-mailhog docker-compose.override.yml

.. seealso::
   * :ref:`docker_compose_override_yml`
   * :ref:`add_your_own_docker_image`
   * :ref:`overwrite_existing_docker_image`


Adjust PHP settings
-------------------

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


Start the Devilbox
------------------

The final step is to start the Devilbox with mailhog.

Let's assume you want to start ``php``, ``httpd``, ``bind``, ``mysql`` and ``mailhog``.

.. code-block:: bash

   host> docker-compose up -d php httpd bind mysql mailhog

.. seealso:: :ref:`start_the_devilbox`


Functionality
-------------

* Once the Devilbox is running, visit http://localhost:8025 in your browser.
* Any email send by any of the Devilbox managed projects will then appear in Mailhog
