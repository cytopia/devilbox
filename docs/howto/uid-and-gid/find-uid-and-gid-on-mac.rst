:orphan:

.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _howto_find_uid_and_gid_on_mac:

***************************************
Find your user id and group id on MacOS
***************************************

**Table of Contents**

.. contents:: :local:


Docker for Mac vs Docker Toolbox
================================

Docker for Mac
--------------

On Docker for Mac (native Docker) you can open up any terminal you prefer, there are no other
requirements.

Docker Toolbox
--------------

On Docker Toolbox it is important that you open up a Docker environment prepared terminal window.

.. seealso::

   * :ref:`howto_open_terminal_on_mac`


Find uid and gid
================

Open the correct terminal as described above and type the following commands:

Find your user id (``uid``)
---------------------------

.. code-block:: bash

   host> id -u


Find your group id (``gid``)
----------------------------

.. code-block:: bash

   host> id -g
