.. _docker_compose_override_yml:

***************************
docker-compose.override.yml
***************************

The ``docker-compose.override.yml`` is the configuration file where you can override existing settings from ``docker-compose.yml`` or even add completely new services.

By default, this file does not exist and you must create it. You can either copy the existing ``docker-compose.override.yml-example`` or create a new one.


**Table of Contents**

.. contents:: :local:


.. seealso:: Official Docker documentation: `Share Compose configurations between files and projects <https://docs.docker.com/compose/extends>`_


Create docker-compose.override.yml
==================================

Copy example file
-----------------

.. code-block:: bash

    host> cd path/to/devilbox
    host> cp docker-compose.override.yml-example docker-compose.override.yml


Create new file from scratch
----------------------------

1. Create an empty file within the Devilbox git directory named ``docker-compose.override.yml``
2. Retrieve the currently used version from the existing ``docker-compose.yml`` file
3. Copy this version line to your newly created ``docker-compose.override.yml`` at the very top

.. code-block:: bash

    # Create an empty file
    host> cd path/to/devilbox
    host> touch docker-compose.override.yml

    # Retrieve the current version
    host> grep ^version docker-compose.yml
    version: '2.1'

    # Add this version line to docker-compose.override.yml
    host> echo "version: '2.1'" > docker-compose.override.yml

Let's see again how this file should look like now:

.. code-block:: yaml
    :name: docker-compose.override.yml
    :caption: docker-compose.override.yml

    version: '2.1'



.. note::
    The documentation might be outdated and the version number might already be higher.
    Rely on the output of the ``grep`` command.


Further reading
===============

To dive deeper into this topic and see how to actually add new services or overwrite existing
services follow the below listed links:

.. seealso::
    * :ref:`add_your_own_docker_image`
    * :ref:`overwrite_existing_docker_image`
