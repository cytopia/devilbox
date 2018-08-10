:orphan:

.. include:: /_includes/all.rst

.. _configure_php_xdebug_editor_atom:

*************************
Configure Xdebug for Atom
*************************

**Table of Contents**

.. contents:: :local:


Prerequisites
=============

Ensure that ``xdebug.idekey`` is set to ``xdebug.atom`` in your PHP Xdebug configuration.

.. seealso::
   * :ref:`configure_php_xdebug_lin`
   * :ref:`configure_php_xdebug_mac`
   * :ref:`configure_php_xdebug_win`
   * :ref:`configure_php_xdebug_docker_toolbox`


Assumption
==========

For the sake of this example, we will assume the following paths:

+------------------------------+------------------------------------------+
| Directory                    | Path                                     |
+==============================+==========================================+
| Devilbox git directory       | ``/home/cytopia/repo/devilbox``          |
+------------------------------+------------------------------------------+
| :ref:`env_httpd_datadir`     | ``./data/www``                           |
+------------------------------+------------------------------------------+
| Resulting local project path | ``/home/cytopia/repo/devilbox/data/www`` |
+------------------------------+------------------------------------------+

The **Resulting local project path** is the path where all projects are stored locally on your
host operating system. No matter what this path is, the equivalent remote path (inside the Docker
container) is always ``/shared/httpd``.

.. important:: Remember this, when it comes to path mapping in your IDE/editor configuration.


Configuration
=============

**1. Install php-debug for Atom**

   .. seealso:: |ext_lnk_xdebug_ide_atom_php_debug|

**2. Configure path mapping in config.cson or ui**

   .. code-block:: js
      :caption: config.cson
      :emphasize-lines: 6

      "php-debug":
      {
         ServerPort: 9000
         PathMaps: [
           "remotepath;localpath"
           "/shared/httpd;/home/cytopia/repo/devilbox/data/www"
         ]
      }

   .. important::
      On Windows you have to use ``\\`` as directory separators for the local path mapping.
      E.g.: ``C:\\Users\\projects``.
