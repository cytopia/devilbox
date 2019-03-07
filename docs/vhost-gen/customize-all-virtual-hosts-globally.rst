.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _vhost_gen_customize_all_virtual_hosts_globally:

************************************
Customize all virtual hosts globally
************************************


**Table of Contents**

.. contents:: :local:


Prerequisite
============

Ensure you have read and understood how vhost-gen templates work and where to find them

.. seealso:: :ref:`vhost_gen_virtual_host_templates`


Apply templates globally to all vhosts
======================================


When applying those templates, you do it globally for all projects. The only exception is
if you have already a specific vhost template for a project in place.

.. seealso:: :ref:`vhost_gen_customize_specific_virtual_host`


In order for template files to be picked up by the web server they must be copied to their correct
filename.

+----------------+--------------------------------+------------------+
| Web server     | Example template               | Template name    |
+================+================================+==================+
| Apache 2.2     | ``apache22.yml-example-vhost`` | ``apache22.yml`` |
+----------------+--------------------------------+------------------+
| Apache 2.4     | ``apache24.yml-example-vhost`` | ``apache24.yml`` |
+----------------+--------------------------------+------------------+
| Nginx stable   | ``nginx.yml-example-vhost``    | ``nginx.yml``    |
+----------------+--------------------------------+------------------+
| Nginx mainline | ``nginx.yml-example-vhost``    | ``nginx.yml``    |
+----------------+--------------------------------+------------------+

.. important::
   Do not use ``*.yml-example-rproxy`` templates for global configuration. These are only
   intended to be used on a per project base.

.. note::
   If you simply copy the files to their corresponding template file name, nothing will change
   as those templates reflect the same values the web servers are using.


Apache 2.2
----------

1. Navigate to ``cfg/vhost-gen/`` inside the Devilbox directory
2. Copy ``apache22.yml-example-vhost`` to ``apache22.yml`` and restart the Devilbox
3. Whenever you adjust ``apache22.yml``, you need to restart the Devilbox


Apache 2.4
----------

1. Navigate to ``cfg/vhost-gen/`` inside the Devilbox directory
2. Copy ``apache24.yml-example-vhost`` to ``apache24.yml`` and restart the Devilbox
3. Whenever you adjust ``apache24.yml``, you need to restart the Devilbox


Nginx stable and Nginx mainline
-------------------------------

1. Navigate to ``cfg/vhost-gen/`` inside the Devilbox directory
2. Copy ``nginx.yml-example-vhost`` to ``nginx.yml`` and restart the Devilbox
3. Whenever you adjust ``nginx.yml``, you need to restart the Devilbox
