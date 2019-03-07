:orphan:

.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _howto_find_docker_toolbox_ip_address:

******************************
Find Docker Toolbox IP address
******************************

**Table of Contents**

.. contents:: :local:


Get IP address
==============

1. Open an environment prepared Terminal

2. Enter the following command to get the IP address of the Docker Toolbox virtual machine:

   .. code-block:: bash

      host> docker-machine ip default

      192.168.99.100

The above example outputs ``192.168.99.100``, but this might be a different IP address on your
machine.

.. seealso::

   * :ref:`howto_open_terminal_on_mac`
   * :ref:`howto_open_terminal_on_win`


What to do with it
==================

The Docker Toolbox IP address is the address where the Devilbox intranet as well as all of its
projects will be available at.

* Use it to access the intranet via your browser (``http://192.168.99.100`` in this example)
* Use it for manual DNS entries

.. seealso::

   * :ref:`howto_add_project_hosts_entry_on_mac`
   * :ref:`howto_add_project_hosts_entry_on_win`
