.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _read_log_files:

**************
Read log files
**************

The logging behaviour is determined by the value of :ref:`env_docker_logs` inside your ``.env``
file. By default logs are mounted to the host operating system for convenient access.


**Table of Contents**

.. contents:: :local:


Mounted logs
============

By default log files for PHP, the webserver and the MySQL server are mounted to the host system
into your Devilbox git directory under ``./log/``. All logs are separated by service version
in the following format: ``./log/<service>-<version>/``

The log directory structure would look something like this:

.. code-block:: bash

   host> cd path/to/devilbox
   host> tree log

   log/
   ├── nginx-stable/
   │   ├── nginx-stable/
   │   ├── defaultlocalhost-access.log
   │   ├── defaultlocalhost-error.log
   │   ├── <project-name>-access.log    # Each project has its own access log
   │   ├── <project-name>-error.log     # Each project has its own error log
   ├── php-fpm-7.1/
   │   ├── php-fpm.access
   │   ├── php-fpm.error

Use your favorite tools to view log files such as ``tail``, ``less``, ``more``, ``cat`` or others.

.. important::
   Currently logs are only mounted for PHP, HTTPD and MYSQL container.
   All other services will log to Docker logs.


Docker logs
===========

You can also change the behaviour where logs are streamed by setting :ref:`env_docker_logs`
to ``1`` inside your ``.env`` file. When doing logs are sent to Docker logs.

When using this approach, you need to use the ``docker-compose logs`` command to view your log
files from within the Devilbox git directory.

.. code-block:: bash
   :emphasize-lines: 2

   host> cd path/to/devilbox
   host> docker-compose logs

When you want to continuously watch the log output (such as ``tail -f``), you need to append ``-f``
to the command.

.. code-block:: bash
   :emphasize-lines: 2

   host> cd path/to/devilbox
   host> docker-compose logs -f

When you only want to have logs displayed for a single service, you can also append the service
name (works with or without ``-f`` as well):

.. code-block:: bash
   :emphasize-lines: 2

   host> cd path/to/devilbox
   host> docker-compose logs php -f

.. important::
   This currently does not work for the MySQL container, which will always log to file.


Checklist
=========

1. You know how to switch between file and Docker logs
2. You know where log files are mounted
3. You know how to access Docker logs
