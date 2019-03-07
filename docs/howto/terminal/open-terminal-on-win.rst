:orphan:

.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _howto_open_terminal_on_win:

**************************
Open a terminal on Windows
**************************

.. seealso:: :ref:`howto_open_terminal_on_mac`


**Table of Contents**

.. contents:: :local:


Docker for Windows
==================

Docker for Windows (the native Docker implementation) does not have any special requirements for
initial environment variable setup. Simply open either

* Command Prompt
* PowerShell


.. important:: Do not open **PowerShell ISE**

.. seealso:: |ext_lnk_install_docker_win|


Docker Toolbox
==============

1. On your Desktop, find the Docker QuickStart Terminal icon.

.. include:: /_includes/figures/terminal/docker-toolbox-terminal-win-quickstart-shortcut.rst

2. Click the Docker QuickStart icon to launch a pre-configured Docker Toolbox terminal.

   If the system displays a **User Account Control** prompt to allow VirtualBox to make changes to
   your computer. Choose **Yes**.

.. include:: /_includes/figures/terminal/docker-toolbox-terminal-win-quickstart-terminal.rst

..

   The terminal runs a special bash environment instead of the standard Windows command prompt.
   The bash environment is required by Docker.


You can now use this terminal window to apply all your Docker and Devilbox related commands.

.. seealso:: |ext_lnk_install_docker_toolbox_win|
