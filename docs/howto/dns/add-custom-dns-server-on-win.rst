:orphan:

.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _howto_add_custom_dns_server_on_win:

********************************
Add custom DNS server on Windows
********************************

**Table of Contents**

.. contents:: :local:


Assumption
==========

This tutorial is using ``127.0.0.1`` as the DNS server IP address, as it is the method to setup
Auto DNS for your local Devilbox.


Network preferences
===================

On Windows, you need to change your active network adapter. See the following screenshots
for how to do it.

.. include:: /_includes/figures/dns-server/windows//win-network-connections.rst
.. include:: /_includes/figures/dns-server/windows//win-ethernet-properties.rst
.. include:: /_includes/figures/dns-server/windows//win-internet-protocol-properties.rst

In the last screenshot, you will have to add ``127.0.0.1`` as your ``Preferred DNS server``.
