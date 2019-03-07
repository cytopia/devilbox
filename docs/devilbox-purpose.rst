.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

****************
Devilbox purpose
****************

The Devilbox aims to provide you a universal zero-configuration LAMP and MEAN development
environment for any purpose which is setup in less than 5 minutes.

Its main intention is to support an unlimited number of projects for any framework or cms
and be portable accross all major operating systems, as well as providing any available php version
with whatever module you require.

To be portable, customizable and as leight weight as possible, the choice fell on a Dockerized
setup.


Why did I built this?
=====================

In one of my previous jobs I had to maintain around 30 different PHP projects. Many of them
utilized different versions and configuration, thus I had to switch between my local MacOS and
various Linux VMs on a frequent base in order to fullfill the current requirement.

Setting up new vhosts, local DNS entries, self-signed https certificates, installing other PHP
versions, ensuring I had all modules and lots of other initial configuration was always a pain to
me, so I decided to automate this.


Automation is key
=================

A few month after releasing it on Github I hit another problem: Tickets regarding outdated versions
as well as new major version requests accumulated and I spent a lot of time keeping up with
updating and creating Docker images and making them available.

That was the point when I decided to create a fully automated and generalized build infrastructure
for all custom Docker images.

The outcome was this:

* Docker images are generated and verified with Ansible
* Docker images have extensive CI tests
* Docker images are automatically built, tested and updated every night and pushed on success


Issues with Docker encountered
==============================

One of the major issues I have encountered with Docker is the syncronization of file and
directory permissions between local and Docker mounted directories.

This is due to the fact that the process of PHP or the web server usually run with a different
``uid`` and ``gid`` as the local user starting the Docker container. Whenever a new file is created
from inside the container, it will happen with the ``uid`` of the process running inside the
container, thus making it incompatible with your local user.

This problem has been finally addressed with the Devilbox and you can read up on it in much more
detail here: :ref:`syncronize_container_permissions`.


Today's state
=============

Honestly speaking, in the time I spent to build the Devilbox, I could have configured every
possible VM by now, but I would have missed the fun. I learned a lot and in the end it made my
work much more pleasent.


Tomorrow's state
================

I use the Devilbox on a daily base and together with other developers we find more and more edge
cases that are being resolved. As technology also advanced quickly, the Devilbox needs to keep up
with as well. Next major milestones will be to modularize it for easier customization of currently
not available Container, hardening for production usage and workflows for deployments in a CI/CD
landscape.

