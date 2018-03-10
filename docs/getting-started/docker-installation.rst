**************
Install Docker
**************

.. _install_docker:

Install Docker
==============


.. _install_docker_compose:

Install Docker Compose
======================

Before going to proceed with the devilbox installation and setup, make sure you meet the requirements.

Requirements
============

Both ``Docker`` and ``Docker Compose`` is required for the devilbox to work. Whether you go with the
recommended or not recommended Docker version is up to you. Most important part is to pay attention to
the version number.

You **must** have at least the following versions or above:

* ``Docker``: 1.12.0
* ``Docker Compose``: 1.9.0

Additionally you require ``git`` in order to clone the devilbox project later on.


Recommended Docker versions
---------------------------

Do use native Docker and do not rely on Docker Toolbox whenever possible.

+--------------------+------------------------+----------------------------+------------------------+
|                    | |logo_lin|             | |logo_win|                 | |logo_osx|             |
+====================+========================+============================+========================+
| **Docker**         | `Docker`_ |br|         | `Docker for Windows`_ |br| | `Docker for Mac`_ |br| |
|                    | 1.12.0+                | 1.12.0+                    | 1.12.0+                |
+--------------------+------------------------+----------------------------+------------------------+
| **Docker Compose** | `Docker Compose`_ |br| | `Docker Compose`_ |br|     | `Docker Compose`_ |br| |
|                    | 1.9.0+                 | 1.9.0+                     | 1.9.0+                 |
+--------------------+------------------------+----------------------------+------------------------+

.. |logo_lin| image:: https://raw.githubusercontent.com/cytopia/icons/master/64x64/linux.png
.. |logo_osx| image:: https://raw.githubusercontent.com/cytopia/icons/master/64x64/osx.png
.. |logo_win| image:: https://raw.githubusercontent.com/cytopia/icons/master/64x64/windows.png
.. _Docker: https://docs.docker.com/install
.. _Docker for Windows: https://docs.docker.com/docker-for-windows/install
.. _Docker for Mac: https://docs.docker.com/docker-for-mac/install
.. _Docker Compose: https://docs.docker.com/compose/install/

Not recommended Docker versions
-------------------------------

In case you are not able to use a native Docker versions (such as on Windows 7), your only chance
is to install the ``Docker Toolbox``.

Validate installed versions
---------------------------

If you are going with the supported or unsupported Docker versions is up to you

Once you have installed Docker and Docker Compose, ensure you have the required minimum version.

.. code-block:: bash

    $ docker version


    Client:
     Version:       18.02.0-ce
     API version:   1.36
     Go version:    go1.9.3
     Git commit:    fc4de44
     Built: Wed Feb  7 21:16:24 2018
     OS/Arch:       linux/amd64
     Experimental:  false
     Orchestrator:  swarm

    Server:
     Engine:
      Version:      18.02.0-ce
      API version:  1.36 (minimum version 1.12)
      Go version:   go1.9.3
      Git commit:   fc4de44
      Built:        Wed Feb  7 21:14:54 2018
      OS/Arch:      linux/amd64
      Experimental: false

Supported versions
------------------


.. |br| raw:: html

   <br />
