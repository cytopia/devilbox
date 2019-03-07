.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

*****************************
Customize web server globally
*****************************

Web server settings can be applied globally, which will affect the web server behaviour itself,
but not the vhost configuration. Configuration can be done for each version separetely, which means
each web server can have its own profile of customized settings.

.. seealso::
   In order to customize the vhosts, have a look at the following links:

   * vhost-gen: :ref:`vhost_gen_virtual_host_templates`
   * vhost-gen: :ref:`vhost_gen_customize_all_virtual_hosts_globally`
   * vhost-gen: :ref:`vhost_gen_customize_specific_virtual_host`
   * vhost-gen: :ref:`vhost_gen_example_add_sub_domains`


**Table of Contents**

.. contents:: :local:


Configure Apache
================

All settings that usually go into the main ``httpd.conf`` or ``apache2.conf`` configuration file
can be overwritten or customized separately for Apache 2.2 and Apache 2.4.

.. seealso:: :ref:`apache_conf`

Configure Nginx
===============


All settings that usually go into the main ``nginx.conf`` configuration file
can be overwritten or customized separately for Nginx stable and Nginx mainline.

.. seealso:: :ref:`nginx_conf`


Devilbox specific settings
==========================

There are certain other settings that are directly managed by the Devilbox's ``.env`` file in order
to make other containers aware of those settings.

.. important:: Try to avoid to overwrite the ``.env`` settings via web server configuration files.

Use the following ``.env`` variables to customize this behaviour globally.

.. seealso::
   * :ref:`env_tld_suffix`
   * :ref:`env_host_port_httpd`
   * :ref:`env_host_port_httpd_ssl`
   * :ref:`env_httpd_template_dir`
   * :ref:`env_httpd_docroot_dir`
