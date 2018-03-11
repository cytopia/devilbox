*******************
Update the Devilbox
*******************

If you are in the initial install process, you can safely skip this section and come back once
you actually want to update the Devilbox.

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

   # Ensure containers are stopped
   host> docker-compse ps

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
   host> git checkout 0.12.1



Keep ``.env`` file in sync
--------------------------

.. warning::
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


Update Docker container
=======================

Updating the git branch shouldn't be needed to often, most changes are actually shipped via newer
``Docker images``, so you should frequently update those.

This is usually achieved by issueing a ``docker pull`` command with the correct image name and image
version. For your convenience there is a shell script in the Devilbox git directory: ``update-docker.sh``
which will update all available Docker images at once.

.. code-block:: bash

   # Update docker images
   host> cd path/to/devilbox
   host> ./update-docker.sh

.. note::

     The Devilbox own Docker images (Apache, Nginx, PHP and MySQL) are even built every night to ensure
     latest security patches and tool versions are applied.


Remove anonymous volumes
========================

The devilbox is not yet at a feature-ready stable release and volumes mounts might change from release to release until version 1.0 will be released. This can cause errors during startup. To solve those issues after updating, you should remove all anonymouse volumes with the following command:

.. code-block:: bash

   # Remove anonymous volumes
   host> cd path/to/devilbox
   host> docker-compose rm


Checklist git repository
========================

1. Ensure containers are stopped
2. Ensure desired branch, tag or commit is checked out or latest changes are pulled
3. Ensure ``.env`` file is in sync with ``env-example`` file
4. Ensure anonymous volumes are removed


Checklist Docker images
=======================

1. Ensure ``./update-docker.sh`` is executed
2. Ensure anonymous volumes are removed

