*************************
Change container versions
*************************

One of the core concepts of the Devilbox is to easily change between different versions of a
specific service.


**Table of Contents**

.. contents:: :local:


Change PHP version
==================

Stop the Devilbox
-----------------

Shut down the Devilbox in case it is still running:

.. code-block:: bash

    # Navigate to the Devilbox directory
    host> /home/user/devilbox

    # Stop all container
    host> docker-compose stop


Edit the ``.env`` file
----------------------

Open the ``.env`` file with your favourite editor and navigate to the ``PHP_SERVER`` section.
It will look something like this:


.. code-block:: bash
   :name: .env
   :caption: .env
   :emphasize-lines: 5

    #PHP_SERVER=5.3
    #PHP_SERVER=5.4
    #PHP_SERVER=5.5
    #PHP_SERVER=5.6
    #PHP_SERVER=7.0
    PHP_SERVER=7.1
    #PHP_SERVER=7.2
    #PHP_SERVER=7.3

As you can see, all available values are already there, but commented. Only one is uncommented.
In this example it is ``7.1``, which is the PHP version that will be started, once the Devilbox
starts.

To change this, simply uncomment your version of choice and save this file. Do not forget to comment
(disable) any other version.

In order to enable PHP 5.5, you would change the ``.env`` file like this:

.. code-block:: bash
   :name: .env
   :caption: .env
   :emphasize-lines: 2

    #PHP_SERVER=5.3
    #PHP_SERVER=5.4
    PHP_SERVER=5.5
    #PHP_SERVER=5.6
    #PHP_SERVER=7.0
    #PHP_SERVER=7.1
    #PHP_SERVER=7.2
    #PHP_SERVER=7.3


Start the Devilbox
----------------------

Now save the file and you can start the Devilbox again.

.. code-block:: bash

    # Navigate to the Devilbox directory
    host> /home/user/devilbox

    # Stop all container
    host> docker-compose up php httpd bind

.. seealso:: :ref:`start_the_devilbox`


Gotchas
-------

If two versions are uncommented, always the last one takes precedence.

Consider this ``.env`` file:

.. code-block:: bash
   :name: .env
   :caption: .env
   :emphasize-lines: 2,4

    #PHP_SERVER=5.3
    #PHP_SERVER=5.4
    PHP_SERVER=5.5
    #PHP_SERVER=5.6
    PHP_SERVER=7.0
    #PHP_SERVER=7.1
    #PHP_SERVER=7.2
    #PHP_SERVER=7.3

Both, PHP 5.4 and PHP 7.0 are uncommented, however, when you start the Devilbox, it will use
PHP 7.0 as this value overwrites any previous ones.


Change whatever version
=======================

When you have read how to change the PHP version, it should be fairly simple to change any
service version. It behaves in the exact same way.

The variable names of all available services with changable versions are in the following format:
``<SERVICE>_SERVER``. Just look through the :ref:`env_file`.

.. seealso::
    The following variables control service versions:
      :ref:`env_php_server`, :ref:`env_httpd_server`, :ref:`env_mysql_server`, :ref:`env_pgsql_server`, :ref:`env_redis_server`, :ref:`env_memcd_server`, :ref:`env_mongo_server`



Checklist
=========

1. Stop the Devilbox
2. Uncomment version of choice in ``.env``
3. Start the Devilbox
