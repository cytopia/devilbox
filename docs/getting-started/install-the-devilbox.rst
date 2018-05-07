********************
Install the Devilbox
********************

.. important::
   :ref:`read_first`
     Ensure you have read this document to understand how this documentation works.


**Table of Contents**

.. contents:: :local:


Supported OS
============

The devilbox runs on all operating systems that provide ``Docker`` and ``Docker Compose``.

+------------+------------+------------+
| |logo_lin| | |logo_win| | |logo_osx| |
+------------+------------+------------+

.. |logo_lin| image:: https://raw.githubusercontent.com/cytopia/icons/master/64x64/linux.png
.. |logo_osx| image:: https://raw.githubusercontent.com/cytopia/icons/master/64x64/osx.png
.. |logo_win| image:: https://raw.githubusercontent.com/cytopia/icons/master/64x64/windows.png


Requirements
============

The only requirements for the devilbox is to have ``Docker`` and ``Docker Compose`` installed,
everything else is bundled and provided withing the Docker container.
The minimum required versions are listed below:

* ``Docker``: 1.12.0+
* ``Docker Compose``: 1.9.0+


Additionally you will require ``git`` in order to clone the devilbox project.

.. warning::
   :ref:`docker_toolbox`
      Use **native Docker** and do not use the **Docker Toolbox**. If you still have to use the
      Docker Toolbox (e.g. for Windows 7 or older Macs) read up on this section.

.. warning::
      Docker itself requires super user privileges which is granted to a system wide group
      called ``docker``. After having installed Docker on your system, ensure that your local
      user is assigned to the ``docker`` group. Check this via ``groups`` or ``id`` command.

.. seealso::
   :ref:`install_docker`
      Have a look at this page to help you install ``Docker`` for your operating system.
   :ref:`install_docker_compose`
      Have a look at this page to help you install ``Docker Compose`` for your operating system.


Download the devilbox
=====================

The devilbox does not need to be installed. The only thing that is required is its git directory.
To download that, open a terminal and copy/paste the following command.

.. code-block:: bash

   host> git clone https://github.com/cytopia/devilbox


Checkout a different release
----------------------------

You now have the devilbox downloaded at the latest version (``git master branch``). This is also recommended as it receives
bugfixes frequently. If you however want to stay on a stable release, you need to check out a
specific ``git tag``.

Lets say you want your devilbox setup to be at release ``0.12.1``, all you have to do is to check out
this specific git tag.

.. code-block:: bash

   host> cd path/to/devilbox
   host> git checkout 0.12.1


.. warning::
      Whenever you check out a different version, make sure that your ``.env`` file is up-to-date
      with the bundled ``env-example`` file. Different Devilbox releases might require different
      settings to be available inside the ``.env`` file. Refer to the next section for how to
      create the ``.env`` file.


Create ``.env`` file
====================

Inside the cloned devilbox git directory, you will find a file called ``env-example``. This file
acts as a template with sane defaults for ``Docker Compose``. In order to use it, it must be
copied to a file named ``.env``. (Note the leading dot).

.. code-block:: bash

   host> cp env-example .env

The ``.env`` file does nothing else then providing environment variables for ``Docker Compose``
and in this case it is used as the main configuration file for the devilbox by providing all kinds
of settings (such as which version to start up).

.. seealso::
   `Docker Compose env file <https://docs.docker.com/compose/env-file/>`_
     Official Docker documentation about the ``.env`` file
   :ref:`env_file`
     All available Devilbox ``.env`` values and their description


Adjust ``.env`` file
====================

To get you started, there are only two variables that need to be adjusted:

* ``NEW_UID``
* ``NEW_GID``

The values for those two variables refer to your local (on your host operating system) user id
and group id. To find out what the values are required in your case, issue the following commands
on a terminal:

Find your user id
-----------------

.. code-block:: bash

   host> id -u

Find your group id
------------------

.. code-block:: bash

   host> id -g

In most cases both values will be ``1000``, but for the sake of this example, let's assume a value
of ``1001`` for the user id and ``1002`` for the group id.

Open the ``.env`` file with your favorite text editor and adjust those values:

.. code-block:: bash
   :caption: .env
   :name: .env
   :emphasize-lines: 3,4

   host> vi .env

   NEW_UID=1001
   NEW_GID=1002

.. warning::
      Make sure that you use the values provided by ``id -u`` and ``id -g``.

.. seealso::
   :ref:`syncronize_container_permissions`
      Read up more on the general problem of trying to have syncronized permissions between
      the host system and a running Docker container.


Checklist
=========

1. ``Docker`` and ``Docker Compose`` are installed at minimum required version
2. Your user is part of the ``docker`` group
3. ``Devilbox`` is cloned
4. ``.env`` file is created
5. User and group id have been set in ``.env`` file

That's it, you have finished the first section and have a working Devilbox ready to be started.
