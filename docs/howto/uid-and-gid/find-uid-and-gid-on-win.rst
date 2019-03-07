:orphan:

.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _howto_find_uid_and_gid_on_win:

*****************************************
Find your user id and group id on Windows
*****************************************

**Table of Contents**

.. contents:: :local:


Docker for Windows
==================

On Docker for Windows it is **not necessary** to change uid and gid in your ``.env`` file.

.. note::
   Docker for Windows is internally using network shares (SMB) to mount Docker volumes.
   This does not require to syncronize file and directoriy permissions via uid and gid.


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
