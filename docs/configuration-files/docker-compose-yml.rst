.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _docker_compose_yml:

******************
docker-compose.yml
******************

This file is the core of the Devilbox and glues together all Docker images.

It is very tempting to just change this file in order to add new services to the already existing once.
However your git directory will become dirty and you will always have to stash your changes before pulling new features from remote. To overcome this Docker Compose offers a default override file (``docker-compose.override.yml``) that let's you specify custom changes as well as completely new services without having to touch the default ``docker-compose.yml``.

.. seealso::
   To find out more read :ref:`docker_compose_override_yml`
