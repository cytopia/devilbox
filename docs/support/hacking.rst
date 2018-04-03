.. _hacking:

*******
Hacking
*******

This section is all about customizing the Devilbox and its Docker images specifically to your needs.


**Table of Contents**

.. contents:: :local:



Rebuilding Docker images
========================

The Devilbox Docker images are rebuilt frequently and automatically pushed to Dockerhub.
However there might be cases in which you want to rebuild right away in order to use have custom
tools installed or of any other reason.


How to rebuild?
---------------

MySQL and Web server images
^^^^^^^^^^^^^^^^^^^^^^^^^^^

All MySQL (including MariaDB and PerconaDB) images as well as the web server images provide
shell scripts that makes rebuilding the image very simple.

Each of those repositores contain two shell scripts for building normally and rebuilding
without cache:

* ``build/docker-build.sh``
* ``build/docker-rebuild.sh``

So you basically just clone the corresponding repository to your computer, edit the ``Dockerfile``
to your needs and run one of the scripts.

The following shows an example for Apache 2.2

.. code-block:: bash

    # Clone the repository and enter its directory
    host> git clone https://github.com/devilbox/docker-apache-2.2
    host> cd docker-apache-2.2

    # Edit the Dockerfile to your needs
    host> vi Dockerfile

    # Build the Docker image
    host> ./build/docker-build.sh

PHP-FPM images
^^^^^^^^^^^^^^

The repository for PHP Docker images contains all version and hence multiple Dockerfiles.
The build procedure is slightly different as those Dockerfiles are generated via Ansible
build afterwards. Refer to its original repository for more detailed instructions.

A very basic description is as follows:

.. code-block:: bash

    # Clone the repository and enter its directory
    host> git https://github.com/devilbox/docker-php-fpm
    host> cd docker-php-fpm

    # Edit the Dockerfile template for the 'work' images
    host> vi build/ansible/DOCKERFILES/Dockerfile-work.j2

    # Generate the actual Dockerfiles
    host> make generate

    # Build one specific PHP version (in this case PHP 5.5)
    host> make build-work-55


How to use the rebuild images?
------------------------------

For the PHP-FPM images you do not have to do anything, as they are built with the image tag that
is already present in ``docker-compose.yml``. For all other images you might have to adjust
the image tag in ``docker-compose.yml`` as all images will be built with the ``latest`` tag by
default.

If you have built Apache 2.2 for example, open the ``docker-compose.yml`` file inside the Devilbox
git directory and ensure that the current image tag is replaced with ``latest``.

How it could look by default:

.. code-block:: yaml
    :caption: docker-compose.yml
    :name: docker-compose.yml
    :emphasize-lines: 2

    httpd:
      image: devilbox/${HTTPD_SERVER:-nginx-stable}:0.13

How it should look with latest tag:

.. code-block:: yaml
    :caption: docker-compose.yml
    :name: docker-compose.yml
    :emphasize-lines: 2

    httpd:
      image: devilbox/${HTTPD_SERVER:-nginx-stable}:latest


Adding your own Docker image
============================

The Devilbox is at its core just a ``docker-compose.yml`` file which easily gives you the option
to add other Docker images it is currently lacking.


What information do you need?
-----------------------------

1. A name, which you can use to refer in the ``docker-compose`` command
2. The Docker image name itself
3. The Docker image tag
4. An unused IP address from the devilbox network (found inside ``docker-compose.yml``)


How to add the image?
---------------------

General example
^^^^^^^^^^^^^^^

Open ``docker-compose.yml`` with your favourite editor and paste the following snippet
below the ``services:`` line with one level of indentation:

.. code-block:: yaml
    :caption: docker-compose.yml
    :name: docker-compose.yml
    :emphasize-lines: 3-4,7

    services:
      # Your custom Docker image here:
      <name>:
        image: <image-name>:<image-version>
        networks:
          app_net:
            ipv4_address: <unused-ip-address>
        # For ease of use always automatically start these:
        depends_on:
          - bind
          - php
          - httpd
      # End of custom Docker image

Cockroach DB example
^^^^^^^^^^^^^^^^^^^^

Gather the requirements for the `Cockroach DB <https://hub.docker.com/r/cockroachdb/cockroach/>`_
Docker image:

1. Name: ``cockroach``
2. Image: ``cockroachdb/cockroach``
3. Tag: ``latest``
4. IP: ``172.16.238.200``

Now add the information to ``docker-compose.yml`` just below the ``services:`` line:

.. code-block:: yaml
    :caption: docker-compose.yml
    :name: docker-compose.yml
    :emphasize-lines: 3-4,7

    services:
      # Your custom Docker image here:
      cockroach:
        image: cockroachdb/cockroach:latest
        networks:
          app_net:
            ipv4_address: 172.16.238.200
        # For ease of use always automatically start these:
        depends_on:
          - bind
          - php
          - httpd
      # End of custom Docker image


How to start the new service?
-----------------------------

The following will bring up your service including all of its dependent services,
as defined with ``depends_on`` (bind, php and httpd). You need to replace ``<name>`` with the
name you have chosen.

.. code-block:: bash

    host> docker-compose up <name>
