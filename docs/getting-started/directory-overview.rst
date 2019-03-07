.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _getting_started_directory_overview:

******************
Directory overview
******************

.. important::
     The directory overview only provides you some theoretical, but useful insights about how
     it all works together. You should at least read it once to be able to debug any problems you
     might encounter.

If you have read it already, jump directly to :ref:`getting_started_create_your_first_project`


**Table of Contents**

.. contents:: :local:


.. _getting_started_directory_overview_datadir:

Data directory
==============

By default all your projects must be created in the ``./data/www/`` directory which is inside in
your Devilbox git directory. This can be changed as well, but is outside the scope of this
*getting started tutorial*.

You can verifiy that the path is actually ``./data/www/`` by checking your ``.env`` file:

.. code-block:: bash

   host> grep HTTPD_DATADIR .env

   HOST_PATH_HTTPD_DATADIR=./data/www


.. _getting_started_directory_overview_project_directory:

Project directory
=================

The project directory is a directory directly within the data directory.

**This represents one project.**

By creating this directory, the web server will create a new virtual host for you. This
happens fully automated and there is nothing else required to do except just to create a directory.

The name of this directory will also be used to build up the final project url together with the
domain suffix: ``http://<project directory>.<domain suffix>``

Create as many project directories as you require.

.. _getting_started_directory_overview_docroot:

Docroot directory
=================

The docroot directory is a directory within each project directory from which the webserver will serve the files.

By default this directory must be named ``htdocs``. This can be changed as well, but is outside
the scope of this *getting started tutorial*.

You can verifiy that the docroot directory is actually ``htdocs`` by checking your ``.env`` file:

.. code-block:: bash

   host> grep DOCROOT_DIR .env


   HTTPD_DOCROOT_DIR=htdocs


Domain suffix
=============

The default domain suffix (``TLD_SUFFIX`` variable in ``.env`` file) is ``loc``. That means that
all your projects will be available under the following address: ``http://<project-directory>.loc``.
This can be changed as well, but is outside the scope of this *getting started tutorial*.

You can verifiy that the suffix is actually ``loc`` by checking your ``.env`` file:

.. code-block:: bash

   host> grep ^TLD_SUFFIX .env

   TLD_SUFFIX=loc


Making sense of it
==================

Ok, let's sum it up and make sense of the previously provided information. To better illustrate
the behaviour we are going to use ``project-1`` as our project directory name.

+---------------+---------------------------------+-------------------------------------------------------------+
| Item          | Example                         | Description                                                 |
+===============+=================================+=============================================================+
| data dir      | ``./data/www``                  | Where all of your projects reside.                          |
+---------------+---------------------------------+-------------------------------------------------------------+
| project dir   | ``./data/www/project-1``        | A single project. It's name will be used to create the url. |
+---------------+---------------------------------+-------------------------------------------------------------+
| docroot dir   | ``./data/www/project-1/htdocs`` | Where the webserver looks for files within your project.    |
+---------------+---------------------------------+-------------------------------------------------------------+
| domain suffix | ``loc``                         | Suffix to build up your project url.                        |
+---------------+---------------------------------+-------------------------------------------------------------+
| project url   | ``http://project-1.loc``        | Final resulting project url.                                |
+---------------+---------------------------------+-------------------------------------------------------------+

**data dir**

This directory is mounted into the ``httpd`` and ``php`` container, so that both know where all projects can be found. This is also the place where you create ``project directories`` for each of your projects.

**project dir**

Is your project and used to generate the virtual host together with the domain suffix.

**docroot dir**

A directory inside your ``project dir`` from where the webserver will actually serve your files.

**domain suffix**

Used as part of the project url.


Checklist
=========

1. You know what the data directory is
2. You know what the project directory is
3. You know what the docroot directory is
4. You know what the domain suffix is
5. You know how domains are constructed
