.. _docker_toolbox:

**************
Docker Toolbox
**************

.. contents:: :local:


``Docker Toolbox`` is a legacy solution to bring ``Docker`` to systems which don't natively support
Docker itself. This is achieved by starting a virtualized Linux (e.g.: on VirtualBox) and have Docker
run inside.

You don't have to take care about setting up the virtual machine, this is done automatically with the provided
setup file (Windows and MacOS).

This however has several disadvantages as the forwarded Docker ports are only visible inside the
virtualized Linux and not on the host computer. Therefore the web server port cannot be reached on
your host machine and you are not able to view any projects.

This can be overcome by forwarding all ports from your virtual machine to your host computer.

.. warning::
      The minimum required ``Docker Toolbox`` version is ``1.12.0``. Make sure not to install older versions.

.. todo::

     This section needs more details.


Docker Toolbox on Windows
=========================

Refer to the official `Docker Toolbox on Windows documentation <https://docs.docker.com/toolbox/toolbox_install_windows/>`_ for how to install ``Docker Toolbox`` on Windows.


Docker Toolbox on MacOS
=======================

Refer to the official `Docker Toolbox on MacOS documentation <https://docs.docker.com/toolbox/toolbox_install_mac/>`_ for how to install ``Docker Toolbox`` on MacOS.

Docker Compose
==============

When installing ``Docker Compose``, make sure you do that **inside the virtual machine**.

.. seealso::
   :ref:`install_docker_compose`
      Have a look at this page to help you install ``Docker Compose`` for your operating system.

Checklist
=========

1. ``Docker Toolbox`` is installed at minimum required version
2. ``Docker Compose`` is installed inside the virtual machine at minimum required version
