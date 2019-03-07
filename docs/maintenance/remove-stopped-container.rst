.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _remove_stopped_container:

************************
Remove stopped container
************************

Why should I?
=============

If you simply ``docker-compose stop`` in order to stop all containers, they are still preserved
in the ``docker ps -a`` process list and still have state.

In case you change any path variables inside the ``.env`` file (or silently due to git updates),
you need to completely re-create the state.

This is done by first fully removing the container and then simply starting it again.


How to do it?
=============

.. code-block:: bash

   host> docker-compose stop
   host> docker-compose rm


When to do it?
==============

Whenever path values inside the ``.env`` file change.
