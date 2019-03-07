.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _features:

********
Features
********

This section gives you a brief overview about the available features.


**Table of Contents**

.. contents:: :local:


Projects
========

Unlimited projects
^^^^^^^^^^^^^^^^^^
The number of projects you can add are so to speak unlimited. Simply add new project directories
and they become automatically available in no time.

Automated virtual hosts
^^^^^^^^^^^^^^^^^^^^^^^
Creating a new project is literally done by creating a new directory on the file system.
Everything else is automatically taken care of in the background. Virtual hosts are added
instantly without having to restart any services.

Automated SSL certificates
^^^^^^^^^^^^^^^^^^^^^^^^^^
Whenever a new project is created, SSL certificates are generated as well and assigned to that
virtual host. Those certificates are signed by the Devilbox certificate authority which can be
imported into your local browser to make all certificates valid and trusted.

Automated DNS records
^^^^^^^^^^^^^^^^^^^^^
The built-in DNS server will automatically make any DNS record available to your host system by
using a wild-card DNS record.  This removes the need to create developer DNS records
in ``/etc/hosts``.

Email catch-all
^^^^^^^^^^^^^^^
All outgoing emails originating from your projects are intercepted, stored locally and
can be viewed within the bundled intranet.

Log files
^^^^^^^^^
Log files for every service are available. Either in the form of Docker logs or as actual log files
mounted into the Devilbox git directory. The web and PHP server offer log files for each project
separetely.

Virtual host domains
^^^^^^^^^^^^^^^^^^^^
Each of your virtual host will have its own domain. TLD can be freely chosen, such as ``*.loc`` or
``*.local``. Be aware that some TLD's can cause problems. Read more here: :ref:`env_tld_suffix`.


Service and version choice
==========================

Selective start
^^^^^^^^^^^^^^^
Run only the Docker container you actually need, but be able to reload others on the fly once
they are needed. So you could first startup PHP and MySQL only and in case you would require
a Redis server you can attach it later to the Devilbox stack without having to restart anything.

Version choice
^^^^^^^^^^^^^^
Each provided service (such as PHP, MySQL, PostgreSQL, etc) comes in many different versions.
You can enable any combination that matches your perfect development stack.

LAMP and MEAN stack
^^^^^^^^^^^^^^^^^^^
Run a full LAMP stack with Apache or Nginx and even attach MEAN stack services such as MongoDB.


Configuration
=============

Global configuration
^^^^^^^^^^^^^^^^^^^^
All services can be configured globally by including your very own customized
``php.ini``, ``php-fpm.conf``, ``my.cnf``, ``nginx.conf``. ``apache.conf`` and other
configuration files.

Version specific configuration
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
Each version of PHP can have its own ``php.ini`` and ``php-fpm.conf`` files,
each version of MySQL, MariaDB or PerconaDB can have its own ``my.cnf`` files,
each Apache..., each Nginx... you get the idea.

Project specific configuration
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
Even down to projects, the Devilbox allows for full customization when it comes to virtual host
settings via |ext_lnk_project_vhost_gen|.


Intranet
========

Command & Control Center
^^^^^^^^^^^^^^^^^^^^^^^^
The intranet is your Command & Control Center showing you all applied settings, mount points,
port exposures, hostnames and any errors including how they can be resolved.

Third-party tools
^^^^^^^^^^^^^^^^^
Mandatory web projects are also shipped: |ext_lnk_tool_phpmyadmin|, |ext_lnk_tool_phppgadmin|,
|ext_lnk_tool_phpredmin|, |ext_lnk_tool_adminer| and |ext_lnk_tool_opcachegui| as well as a web GUI
to view all sent emails.


Dockerized
==========

Portable
^^^^^^^^
Docker container run on Linux, Windows and MacOS, so does the Devilbox. This ensures that no
matter what operating system you are currently on, you can always run your development stack.

Built nightly
^^^^^^^^^^^^^
Docker images (at least official Devilbox Docker images) are built nightly and pushed to
Dockerhub to ensure to always have the latest versions installed and be up-to-date with any
security patches that are available.

Ships popular development tools
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
The Devilbox is also designed to be a development environment offering many tools used for
everyday web development, no matter if frontend or backend.

Work inside the container
^^^^^^^^^^^^^^^^^^^^^^^^^
Instead of working on you host operating system, you can do everything inside the container.
This allows you to have all tools pre-installed and a working unix environment ready.

Work inside and outside the container interchangeably
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
No matter if you work on your host operating system or inside the Docker container. Special
mount points and port-forwards are already in place to make both look the same to you.


Others
======

Work offline
^^^^^^^^^^^^
The Devilbox only requires internet initially to pull the required Docker images, once this is done
you can work completely offline. No need for an active internet connection.

Hacking
^^^^^^^
Last but not least, the Devilbox is bascially just a ``docker-compose.yml`` file and you can
easily add any Docker images you are currently missing in the Devilbox setup.

