*************************
Docker and Docker Compose
*************************

This section gives you some detail about installing ``Docker`` and ``Docker Compose`` on your
operating system.

.. contents:: :local:

.. _install_docker:

Install Docker
==============

.. seealso::
   :ref:`docker_toolbox`
      Note, this section refers to **native Docker**, which is the recommended version. If however,
      you need to install **Docker Toolbox** (such as on Windows 7 or older Macs), have a look at this page.

.. warning::
      The minimum required ``Docker`` version is ``1.12.0``. Make sure not to install older versions.


Docker on Linux
---------------

Refer to the official `Docker for Linux documentation <https://docs.docker.com/install/#supported-platforms>`_ for how to install ``Docker`` on your specific Linux distribution.

Docker on Windows
-----------------

Refer to the official `Docker for Windows documentation <https://docs.docker.com/docker-for-windows/install/>`_ for how to install ``Docker`` on Windows.

Docker on MacOS
---------------

Refer to the official `Docker for Mac documentation <https://docs.docker.com/docker-for-mac/install/>`_ for how to install ``Docker`` on MacOS.

``docker`` user group
---------------------

Docker itself requires super user privileges which is granted to a system wide group
called ``docker``. After having installed Docker on your system, ensure that your local
user is assigned to the ``docker`` group. Check this via ``groups`` or ``id`` command.


.. code-block:: bash

   host> id

   uid=1000(cytopia) gid=1000(cytopia) groups=1000(cytopia),999(docker)



.. _install_docker_compose:

Install Docker Compose
======================

.. warning::
      The minimum required ``Docker Compose`` version is ``1.9.0``. Make sure not to install older versions.

The Docker documentation provides various ways to install ``Docker Compose`` for all supported
operating systems and is quite extensive and straight forward.
Follow their steps here: `Install Docker Compose <https://docs.docker.com/compose/install/#install-compose>`_.

Checklist
=========

1. ``Docker`` is installed at the minimum required version
2. Your user is part of the ``docker`` group
3. ``Docker Compose`` is installed at the minimum required version
