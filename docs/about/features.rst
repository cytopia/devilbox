.. _features:

********
Features
********

This section gives you a brief overview about the available features.


**Table of Contents**

.. contents:: :local:


Projects
========

**Unlimited projects**

The number of projects you can add are so to speak unlimited. Simply add new project directories
and they become available in no time.

**Automated virtual hosts**

Creating a new project is literally done by creating a new directory on the file system.
Everything else is automatically taken care of in the background. Virtual hosts are added
instantly without having to restart any services.

**Automated DNS records**

The built-in DNS server will automatically make any DNS record available to your host system by
using a wild-card DNS record.

**Email catch-all**

All outgoing emails originating from your projects are intercepted, stored locally and
can be viewed within the bundled intranet.


Service and version choice
==========================

**Selective start**

Run only the Docker container you actually need, but be able to reload others on the fly once
they are needed.

**Version choice**

Each provided service (such as PHP, MySQL, PostgreSQL, etc) comes in many different versions.
You can enable any combination that matches your perfect development stack.

**LAMP and MEAN stack**

Run a full LAMP stack with Apache or Nginx and even attach MEAN stack services such as MongoDB.


Configuration
=============

**Global configuration**

All services can be configured globally by including your very own customized
``php.ini``, ``my.cnf``, ``nginx.conf``. ``apache.conf`` and other configuration files.

**Version specific configuration**

Each version of PHP can have its own ``php.ini`` files, each version of MySQL, MariaDB or
PerconaDB can have its own ``my.cnf`` files, each Apache..., each Nginx... you get the idea.

**Project specific configuration**

Even down to projects, the Devilbox allows for full customization when it comes to virtual host
settings.


Intranet
========

**Command & Control Center**

The intranet is your Command & Control Center showing you all applied settings, mount points,
port exposures, hostnames and any errors including how they can be resolved.

**Third-party tools**

Mandatory web projects are also shipped: phpMyAdmin, Adminer and OpcacheGui.


Dockerized
==========

**Portable**

Docker container run on Linux, Windows and MacOS, so does the Devilbox. This ensures that no
matter what operating system you are currently on, you can always run your development stack.

**Built nightly**

Docker images (at least official Devilbox Docker images) are built nightly and pushed to
Dockerhub to ensure to always have the latest versions installed and be up-to-date with any
security patches that are available.

**Ships popular development tools**

The Devilbox is also designed to be a development environment offering many tools used for
everyday web development, no matter if frontend or backend.

**Work inside the container**

Instead of working on you host operating system, you can do everything inside the container.
This allows you to have all tools pre-installed and a working unix environment ready.

**Work inside and outside the container interchangeably**

No matter if you work on your host operating system or inside the Docker container. Special
mount points and port-forwards are already in place to make both look the same to you.


Customize
=========

Last but not least, the Devilbox is bascially just a ``docker-compose.yml`` file and you can
easily add any Docker images you are currently missing in the Devilbox setup.
