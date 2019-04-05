.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _update_the_devilbox:

*******************
Update the Devilbox
*******************

If you are in the initial install process, you can safely skip this section and come back once
you actually want to update the Devilbox.


**Table of Contents**

.. contents:: :local:


Update git repository
=====================

Stop container
--------------

Before updating your git branch or checking out a different tag or commit, make sure to properly
stop all devilbox containers:

.. code-block:: bash

   # Stop containers
   host> cd path/to/devilbox
   host> docker-compose stop
   # Remove stopped container (required)
   host> docker-compose rm -f

   # Ensure containers are stopped
   host> docker-compose ps

Case 1: Update master branch
----------------------------

If you simply want to update the master branch, do a ``git pull origin master``:

.. code-block:: bash

   # Update master branch
   host> cd path/to/devilbox
   host> git pull origin master


Case 2: Checkout release tag
----------------------------

If you want to checkout a specific release tag (such as ``0.12.1``), do a ``git checkout 0.12.1``:

.. code-block:: bash

   # Checkout release
   host> cd path/to/devilbox
   # Ensure you have latest from remote
   host> git fetch
   host> git checkout v1.0.1



Keep ``.env`` file in sync
--------------------------

.. important::
   Whenever you check out a different version, make sure that your ``.env`` file is up-to-date
   with the bundled ``env-example`` file. Different Devilbox releases might require different
   settings to be available inside the ``.env`` file.

You can also compare your current ``.env`` file with the provided ``env-example`` file by using
your favorite diff editor:

.. code-block:: bash

   host> vimdiff .env env-example

.. code-block:: bash

   host> diff .env env-example

.. code-block:: bash

   host> meld .env env-example

Keep ``vhost-gen`` templates in sync
------------------------------------

.. important::
   Whenever you check out a different version, make sure that the vhost-gen templates that have
   been copied to any virtual hosts are up-to-date with the templates provided in
   ``cfg/vhost-gen/``.


Recreate container
------------------

Whenever the path of a volume changes (either due to upstream changes in git or due to you changing
it manually in the ``.env`` file) you need to remove the stopped container and have them fully
recreated during the next start.

.. code-block:: bash

   # Remove anonymous volumes
   host> cd path/to/devilbox
   host> docker-compose rm -f

.. seealso::
   :ref:`remove_stopped_container`


.. _update_the_devilbox_update_the_docker_images:

Update Docker images
====================

Updating the git branch shouldn't be needed to often, most changes are actually shipped via newer
``Docker images``, so you should frequently update those.

This is usually achieved by issueing a ``docker pull`` command with the correct image name and image
version or ``docker-compose pull`` for all currently selected images in ``.env`` file.
For your convenience there is a shell script in the Devilbox git directory: ``update-docker.sh``
which will update all available Docker images at once for every version.

.. note::

     The Devilbox own Docker images (Apache, Nginx, PHP and MySQL) are even built every night to ensure
     latest security patches and tool versions are applied.


Update one Docker image
-----------------------

Updating or pulling a single Docker image is accomplished by ``docker pull <image>:<tag>``.
This is not very handy as it is quite troublesome to do it separately per Docker image.

You first need to find out the image name and then also the currently used image tag.

.. code-block:: bash

   host> grep 'image:' docker-compose.yml

   image: cytopia/bind:0.11
   image: devilbox/php-fpm:${PHP_SERVER}-work
   image: devilbox/${HTTPD_SERVER}:0.13
   image: devilbox/mysql-${MYSQL_SERVER}
   image: postgres:${PGSQL_SERVER}
   image: redis:${REDIS_SERVER}
   image: memcached:${MEMCD_SERVER}
   image: mongo:${MONGO_SERVER}

After having found the possible candidates, you will still have to find the corresponding value
inside the ``..env`` file. Let's do it for the PHP image:

.. code-block:: bash

   host> grep '^PHP_SERVER' .env

   PHP_SERVER=7.2

So now you can substitute the ``${PHP_SERVER}`` variable from the first command with ``7.2`` and
finally pull a newer version:

.. code-block:: bash

   host> docker pull devilbox/php-fpm:7.2-work

Not very efficient.


Update all currently set Docker images
--------------------------------------

This approach is using ``docker-compose pull`` to update all images, but only for the versions
that are actually set in ``.env``.

.. code-block:: bash

   host> docker-compose pull

   Pulling bind (cytopia/bind:0.11)...
   Pulling php (devilbox/php-fpm:5.6-work)...
   Pulling httpd (devilbox/apache-2.2:0.13)...
   Pulling mysql (cytopia/mysql-5.7:latest)...
   Pulling pgsql (postgres:9.6)...
   Pulling redis (redis:4.0)...
   Pulling memcd (memcached:1.5.2)...
   Pulling mongo (mongo:3.0)...

This is most likely the variant you want.


Update all available Docker images for all versions
---------------------------------------------------

In case you also want to pull/update every single of every available Devilbox image, you can
use the provided shell script, which has all versions hardcoded and pulls them for you:

.. code-block:: bash

   host> ./update-docker.sh


Checklist git repository
========================

1. Ensure containers are stopped and removed/recreated (``docker-compose stop && docker-compose rm``)
2. Ensure desired branch, tag or commit is checked out or latest changes are pulled
3. Ensure ``.env`` file is in sync with ``env-example`` file
4. Ensure all of your custom applied vhost-gen templates are in sync with the default templates


Checklist Docker images
=======================

1. Ensure ``docker-compose pull`` or ``./update-docker.sh`` is executed
2. Ensure ``docker-compose rm -f`` is executed after stopping the Devilbox

.. seealso:: **Troubleshooting:** :ref:`troubleshooting_what_to_do_first`
