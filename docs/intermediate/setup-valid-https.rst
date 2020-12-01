.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _setup_valid_https:

*****************
Setup valid HTTPS
*****************

This page shows you how to use the Devilbox on https and how to import the Certificate Authority
into your browser once, so that you always and automatically get valid SSL certificates for all new
projects.

SSL certificates are generated automatically and there is nothing to do from your side.

.. include:: /_includes/figures/https/https-ssl-address-bar.rst

**Table of Contents**

.. contents:: :local:


TL;DR
=====

Import the Certificate Authority into your browser and you are all set.


How does it work
================

Certificate Authority
---------------------

When the Devilbox starts up for the first time, it will generate a
|ext_lnk_ssl_certificate_authority| and will store its public and private key in ``./ca/`` within
the Devilbox git directory.

The keys are only generated if they don't exist and kept permanently if you don't delete them
manually, i.e. they are not overwritten.

.. code-block:: bash

   host> cd path/to/devilbox
   host> ls -l ca/
   -rw-r--r--  1 cytopia cytopia 1558 May  2 11:12 devilbox-ca.crt
   -rw-------  1 cytopia cytopia 1675 May  2 11:12 devilbox-ca.key
   -rw-r--r--  1 cytopia cytopia   17 May  4 08:35 devilbox-ca.srl


SSL Certificates
----------------

Whenever you create a new project directory, multiple things happen in the background:

1. A new virtual host is created
2. DNS is provided via :ref:`setup_auto_dns`
3. A new SSL certificate is generated for that vhost
4. **The SSL certificate is signed by the Devilbox Certificate Authority**

By having a SSL certificates signed by the provided CA, you will only have to import the CA
into your browser ones and all current projects and future projects will automatically have
valid and trusted SSL certificates without any further work.


Import the CA into your browser
===============================

.. important::
   Importing the CA into the browser is also recommended and required for the Devilbox
   intranet page to work properly.
   You may also import the CA into your Operating System's Keystore. Information on that
   is available at |ext_lnk_ssl_gfi_root_cert_guide|.

Chrome / Chromium
-----------------

Open Chrome settings, scroll down to the very bottom and click on ``Advanced`` to expand the
advanced settings.

.. include:: /_includes/figures/https/chrome-settings.rst

Find the setting ``Manage certificates`` and open it.

.. include:: /_includes/figures/https/chrome-advanced-settings.rst

Navigate to the tab setting ``AUTHORITIES`` and click on ``IMPORT``.

.. include:: /_includes/figures/https/chrome-manage-certificates.rst

Select ``devilbox-ca.crt`` from within the Devilbox ``./ca`` directory:

.. include:: /_includes/figures/https/file-manager-import-ca.rst

As the last step you are asked what permissions you want to grant the newly importat CA.
To make sure it works everywhere, check all options and proceed with ``OK``.

.. include:: /_includes/figures/https/chrome-set-trust.rst

Now you are all set and all generated SSL certificates will be valid from now on.

.. include:: /_includes/figures/https/https-ssl-address-bar.rst


Note: if you are on Chrome on Mac Big Sur and above, you won't find the above settings, you will have to go the "Keychain Access" application, click on ``System`` in the left hand corner and then drag in the ``devilbox-ca.crt``, it will ask for your password to complete the operation. once that is done, the cert will be listed but will not be trusted by default. Now right click on the imported cert, click on Info, an info dialog will open up and you can expand the 'Trust' accordian and set it to ``Trust All``. Now you are all set and all generated SSL certificates will be valid from now on.

Firefox
-------

Open Firefox settings and click on ``Privacy & Security``.

.. include:: /_includes/figures/https/firefox-preferences.rst

At the very bottom click on the button ``View Certificates``.

.. include:: /_includes/figures/https/firefox-privacy-and-security.rst

In the ``Authories`` tab, click on ``Import``.

.. include:: /_includes/figures/https/firefox-certificate-manager.rst

Select ``devilbox-ca.crt`` from within the Devilbox ``./ca`` directory:

.. include:: /_includes/figures/https/file-manager-import-ca.rst

As the last step you are asked what permissions you want to grant the newly importat CA.
To make sure it works everywhere, check all options and proceed with ``OK``.

.. include:: /_includes/figures/https/firefox-set-trust.rst

Now you are all set and all generated SSL certificates will be valid from now on.

.. include:: /_includes/figures/https/https-ssl-address-bar.rst


Further Reading
===============

.. seealso:: ``.env`` variable: :ref:`env_devilbox_ui_ssl_cn`
