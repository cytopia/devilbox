:orphan:

.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _howto_add_custom_dns_server_on_android:

********************************
Add custom DNS server on Android
********************************

Adding custom DNS server on Android works out of the box for each connected Wi-Fi network
separately. There is no need to install external Apps.


**Table of Contents**

.. contents:: :local:


Change DNS server in Android directly
=====================================

1. Navigate to **Settings -> Wi-Fi**
2. **Press and hold** on the Wi-Fi network you want to change
3. Choose **Modify network**

   .. include:: /_includes/figures/dns-server/android/android-wifi-list.rst

4. Scroll down and click on **Advanced options**

   .. include:: /_includes/figures/dns-server/android/android-wifi-advanced-options.rst

5. Scroll down and click on **DHCP**

   .. include:: /_includes/figures/dns-server/android/android-wifi-select-dhcp-options.rst

6. Click on **Static**

   .. include:: /_includes/figures/dns-server/android/android-wifi-select-dhcp-options-static.rst

7. Scroll down and change the DNS server IP for **DNS 1** (the first DNS server in the list)

   .. include:: /_includes/figures/dns-server/android/android-wifi-set-dns-server.rst


Change DNS server with Third-Party App
======================================

If the above does not work for you or you just want another App that makes it even easier to change
DNS settings, you can search the Playstore for many available DNS changer Apps. They also work
on non-rooted Androids.

.. seealso:: |ext_lnk_app_android_dns_changer|
