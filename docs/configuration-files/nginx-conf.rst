.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _nginx_conf:

**********
nginx.conf
**********

Nginx stable and Nginx mainline both come with their default vendor configuration. This might not
be the ideal setup for some people, so you have the chance to change any of those settings, by
supplying custom configurations.

.. seealso:: If you are rather using Apache, have a look at: :ref:`apache_conf`

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

You can set custom nginx.conf configuration options for each Nginx version separately.
See the directory structure for Nginx configuration directories inside ``./cfg/`` directory:

.. code-block:: bash

   host> ls -l path/to/devilbox/cfg/ | grep 'nginx'

   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 nginx-mainline/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 nginx-stable/

Customization is achieved by placing a file into ``cfg/nginx-X/`` (where ``X`` stands for
your Nginx flavoour).  The file must end by ``.conf`` in order to be sourced by the web server.

Each of the Nginx configuration directories already contain an example file:
``devilbox-custom.conf-example``, that can simply be renamed to ``devilbox-custom.conf``.
This file holds some example values that can be adjusted or commented out.

In order for the changes to be applied, you will have to restart the Devilbox.


Examples
========

Adjust KeepAlive settings for Nginx stable
------------------------------------------

The following examples shows you how to change the
`keepalive <http://nginx.org/en/docs/http/ngx_http_upstream_module.html#keepalive>`_, the
`keepalive_requests <https://nginx.org/en/docs/http/ngx_http_core_module.html#keepalive_requests>`_
as well as the
`keepalive_timeout <https://nginx.org/en/docs/http/ngx_http_core_module.html#keepalive_timeout>`_
values of Nginx stable.

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Navigate to Nginx stable config directory
   host> cd cfg/nginx-stable

   # Create new conf file
   host> touch keep_alive.conf

Now add the following content to the file:

.. code-block:: ini
   :caption: keep_alive.conf

   keepalive 10;
   keepalive_timeout 10s;
   keepalive_requests 100;

In order to apply the changes you need to restart the Devilbox.

.. note::
   The above is just an example demonstration, you probably need other values for your setup.
   So make sure to understand how to configure Nginx, if you are going to change any of those
   settings.


Adjust timeout settings for Nginx mainline
------------------------------------------

The following examples shows you how to adjust various timeout settings for Nginx mainline by
adjusting
`client_body_timeout <https://nginx.org/en/docs/http/ngx_http_core_module.html#client_body_timeout>`_,
`client_header_timeout <https://nginx.org/en/docs/http/ngx_http_core_module.html#client_header_timeout>`_
and
`send_timeout <https://nginx.org/en/docs/http/ngx_http_core_module.html#send_timeout>`_ directives.

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Navigate to Nginx mainline config directory
   host> cd cfg/nginx-mainline

   # Create new conf file
   host> touch timeouts.conf

Now add the following content to the file:

.. code-block:: ini
   :caption: timeouts.conf

   client_body_timeout 60s;
   client_header_timeout 60s;
   send_timeout 60s;

In order to apply the changes you need to restart the Devilbox.

.. note::
   The above is just an example demonstration, you probably need other values for your setup.
   So make sure to understand how to configure Nginx, if you are going to change any of those
   settings.
