:orphan:

.. _howto_find_uid_and_gid_on_win:

*****************************************
Find your user id and group id on Windows
*****************************************

**Table of Contents**

.. contents:: :local:


Docker for Windows
==================

.. todo::
   This section requires the actual information about how to get uid and gid.
   https://forums.docker.com/t/find-uid-and-gid-on-windows-for-mounted-directories/53320


Docker Toolbox
==============

On Docker Toolbox it is important that you open up a Docker environment prepared terminal window.

.. seealso::

   * :ref:`howto_open_terminal_on_win`


Find your user id (``uid``)
---------------------------

Type the following command to retrieve the correct ``uid``.

.. code-block:: bash

   host> id -u


Find your group id (``gid``)
----------------------------

Type the following command to retrieve the correct ``gid``.

.. code-block:: bash

   host> id -g
