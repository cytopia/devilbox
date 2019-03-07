:orphan:

.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _howto_host_address_alias_on_mac:

***************************
Host address alias on MacOS
***************************

In order for Xdebug to work on Docker for MacOS, the container needs a well known IP address
for its Xdebug remote host. This is achieved by adding an alias to the loopback device.

**Table of Contents**

.. contents:: :local:


One-time alias
==============

In order to create this alias for testing purposes, which does not survive reboots, you can
issue the command manually with ``sudo`` or root privileges.

.. code-block:: bash

   host> sudo ifconfig lo0 alias 10.254.254.254


Boot persistent alias
=====================

If you want to have this alias persistent across reboot, you need to download and enable a
``plist`` file:


.. code-block:: bash

   # Download the plist into the correct location
   host> sudo curl -o \
           /Library/LaunchDaemons/org.devilbox.docker_10254_alias.plist \
           https://raw.githubusercontent.com/devilbox/xdebug/master/osx/org.devilbox.docker_10254_alias.plist

   # Enable without reboot
   host> sudo launchctl load /Library/LaunchDaemons/org.devilbox.docker_10254_alias.plist

.. seealso::
   * :ref:`configure_php_xdebug`
   * |ext_lnk_github_devilbox_xdebug_on_mac|
   * |ext_lnk_github_original_xdebug_on_mac|
