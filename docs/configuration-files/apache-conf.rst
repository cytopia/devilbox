.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _apache_conf:

***********
apache.conf
***********

Apache 2.2 and Apache 2.4 both come with their default vendor configuration. This might not be the
ideal setup for some people, so you have the chance to change any of those settings, by supplying
custom configurations.

.. seealso:: If you are rather using Nginx, have a look at: :ref:`nginx_conf`

.. important::
   You could actually also create virtual hosts here, but it is recommended to use the
   Devilbox Auto-vhost generation feature. If you want to custimize your current virtual hosts
   have a look at:

   * vhost-gen: :ref:`vhost_gen_virtual_host_templates`
   * vhost-gen: :ref:`vhost_gen_customize_all_virtual_hosts_globally`
   * vhost-gen: :ref:`vhost_gen_customize_specific_virtual_host`
   * vhost-gen: :ref:`vhost_gen_example_add_sub_domains`


**Table of Contents**

.. contents:: :local:


General
=======

You can set custom apache.conf configuration options for each Apache version separately.
See the directory structure for Apache configuration directories inside ``./cfg/`` directory:

.. code-block:: bash

   host> ls -l path/to/devilbox/cfg/ | grep 'apache'

   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 apache-2.2/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 apache-2.4/

Customization is achieved by placing a file into ``cfg/apache-X.X/`` (where ``X.X`` stands for
your Apache version).  The file must end by ``.conf`` in order to be sourced by the web server.

Each of the Apache configuration directories already contain an example file:
``devilbox-custom.conf-example``, that can simply be renamed to ``devilbox-custom.conf``.
This file holds some example values that can be adjusted or commented out.

In order for the changes to be applied, you will have to restart the Devilbox.


Examples
========

Adjust KeepAlive settings for Apache 2.2
----------------------------------------

The following examples shows you how to change the
`KeepAlive <https://httpd.apache.org/docs/2.2/mod/core.html#keepalive>`_, the
`MaxKeepAliveRequests <https://httpd.apache.org/docs/2.2/mod/core.html#maxkeepaliverequests>`_
as well as the
`KeepAliveTimeout <https://httpd.apache.org/docs/2.2/mod/core.html#keepalivetimeout>`_ values of
Apache 2.2.

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Navigate to Apache 2.2 config directory
   host> cd cfg/apache-2.2

   # Create new conf file
   host> touch keep_alive.conf

Now add the following content to the file:

.. code-block:: ini
   :caption: keep_alive.conf

   KeepAlive On
   KeepAliveTimeout 10
   MaxKeepAliveRequests 100

In order to apply the changes you need to restart the Devilbox.

.. note::
   The above is just an example demonstration, you probably need other values for your setup.
   So make sure to understand how to configure Apache, if you are going to change any of those
   settings.


Limit HTTP headers and GET size for Apache 2.4
----------------------------------------------

The following examples shows you how to limit the amount of headers the client can send to the
server as well as changing the maximum URL GET size by adjusting
`LimitRequestFields <http://httpd.apache.org/docs/current/mod/core.html#limitrequestfields>`_,
`LimitRequestFieldSize <http://httpd.apache.org/docs/current/mod/core.html#limitrequestfieldsize>`_
and
`LimitRequestLine <http://httpd.apache.org/docs/current/mod/core.html#limitrequestline>`_
for Apache 2.4.

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Navigate to Apache 2.4 config directory
   host> cd cfg/apache-2.4

   # Create new conf file
   host> touch limits.conf

Now add the following content to the file:

.. code-block:: ini
   :caption: limits.conf

   # Limit amount of HTTP headers a client can send to the server
   LimitRequestFields 20
   LimitRequestFieldSize 4094

   # URL GET size
   LimitRequestLine 2048

In order to apply the changes you need to restart the Devilbox.

.. note::
   The above is just an example demonstration, you probably need other values for your setup.
   So make sure to understand how to configure Apache, if you are going to change any of those
   settings.
