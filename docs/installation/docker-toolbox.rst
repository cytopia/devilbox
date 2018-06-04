.. _docker_toolbox:

**************
Docker Toolbox
**************

.. contents:: :local:


Installation
============

.. warning::
      The minimum required ``Docker Toolbox`` version is ``1.12.0``. Make sure not to install older versions.

Docker Toolbox on Windows
-------------------------

Refer to the official `Docker Toolbox on Windows documentation <https://docs.docker.com/toolbox/toolbox_install_windows/>`_ for how to install ``Docker Toolbox`` on Windows.

Docker Toolbox on MacOS
-------------------------

Refer to the official `Docker Toolbox on MacOS documentation <https://docs.docker.com/toolbox/toolbox_install_mac/>`_ for how to install ``Docker Toolbox`` on MacOS.

Docker Compose
--------------

When installing ``Docker Compose``, make sure you do that **inside the virtual machine**.

.. seealso::
   :ref:`install_docker_compose`
      Have a look at this page to help you install ``Docker Compose`` for your operating system.


Additional steps
================

``Docker Toolbox`` is a legacy solution to bring ``Docker`` to systems which don't natively support
Docker itself. This is achieved by starting a virtualized Linux (e.g.: on VirtualBox) and have Docker
run inside.

You don't have to take care about setting up the virtual machine, this is done automatically with the provided
setup file (Windows and MacOS).

This however has several disadvantages as the forwarded Docker ports are only visible inside the
virtualized Linux and not on the host computer. Therefore the web server port cannot be reached on
your host machine and you are not able to view any projects.

Listening address
-----------------

First thing you need to make sure is that the ``LOCAL_LISTEN_ADDR`` variable from your ``.env`` file is
empty. When it is empty all services bind to all IP addresses inside the virtual machine and thus
being able to be seen from outside (your host operating system).


You can verifiy that the variable is actually empty by checking your ``.env`` file:

.. code-block:: bash

   host> grep ^LOCAL_LISTEN_ADDR .env

   LOCAL_LISTEN_ADDR=

Port forwarding
---------------

Additionally I would suggest that you port-forward the virtual machines port 80 (which itself
points to the docker container inside) to your host computers ``127.0.0.1`` address. This way you
can reach the devilbox via ``http://127.0.0.1`` or ``http://localhost``.

If you do not port-forward it to your host machines localhost, you will have to adjust all project
DNS entries that are described in this documentation to go to ``127.0.0.1`` to the IP address of
your virtual machine.

.. _docker_toolbox_auto_dns:

Auto-DNS
--------

I am currently not aware that Auto-DNS will work with ``Docker Toolbox``. If you are willing to
spent some time there, let me know. There is currently an open ticket which is addressing this:
https://github.com/cytopia/devilbox/issues/101



Checklist
=========

1. ``Docker Toolbox`` is installed at minimum required version
2. ``Docker Compose`` is installed inside the virtual machine at minimum required version
3. ``LOCAL_LISTEN_ADDR`` is empty in the ``.env`` file
