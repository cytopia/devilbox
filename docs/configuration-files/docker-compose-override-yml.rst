.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _docker_compose_override_yml:

***************************
docker-compose.override.yml
***************************

The ``docker-compose.override.yml`` is the configuration file where you can override existing settings from ``docker-compose.yml`` or even add completely new services.

By default, this file does not exist and you must create it. You can either copy the existing ``docker-compose.override.yml-example`` or create a new one.


**Table of Contents**

.. contents:: :local:


.. _docker_compose_override_yml_how_does_it_work:

How does docker-compose.override.yml work?
==========================================

When you run ``docker-compose up``, it searches for a file named ``docker-compose.yml`` and reads
all configured services, networks, volumes etc to create your Docker stack. If you also
additionally have a file named ``docker-compose.override.yml`` this will be read as well and used
as an override file to complement. It works in the following order:

1. All definitions from ``docker-compose.yml`` will be used
2. All definitions that are also defined in ``docker-compose.override.yml`` will automatically
   overwrite the settings from ``docker-compose.yml``
3. All definitions only available in ``docker-compose.override.yml`` will be added additionally.

For starting up your Docker Compose stack there are no additional steps or command line arguments
required. If both files exist, they will be read automatically.

.. seealso:: |ext_lnk_docker_compose_extends|


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
