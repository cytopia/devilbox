.. _best_practice:

*************
Best practice
*************

If you have already operate the Devilbox, this guide is a must have. It will cover common
best-practice topics as well as some tips and tricks you will want to apply.


**Table of Contents**

.. contents:: :local:


Move data out of Devilbox directory
===================================

One thing you should take into serious consideration is to move data such as your projects as well
as persistent data of databases out of the Devilbox git directory.

The Devilbox git directory should be something that can be safely deleted and re-created without
having to worry about loosing any project data. There could also be the case that you have a
dedicated hard-disk to store your projects or you have your own idea about a directory structure
where you want to store your projects.


Projects
--------

.. seealso::
   :ref:`howto_move_projects_to_a_different_directory`
     Follow this guide to keep your projects separated from the Devilbox git directory.


Version control ``.env`` file
-----------------------------

The ``.env`` file is ignored by git, because this is *your* file to customize and it should be
*your* responsibility to make sure to backup or version controlled.

One concept you can apply here is to have a separate **dotfiles** git repository.
This is a repository that holds all of your configuration files such as vim, bash, zsh, xinit
and many more. Those files are usually stored inside this repository and then symlinked to the
correct location. By having all configuration files in one place, you can see and track changes
easily as well as bein able to jump back to previous configurations.

In case of the Devilbox ``.env`` file, just store this file in your repository and symlink it to
the Devilbox git directiry. This way you make sure that you keep your file, even when the Devilbox
git directory is deleted and you also have a means of keeping track about changes you made.

You could also go further and have several ``.env`` files available somewhere. Each of those files
holds different configurations e.g. for different projects or customers.

* ``env-customer1``
* ``env-php55``
* ``env-project3``

You would then simply symlink one of those files to the Devilbox git directory.


Version control service config files
------------------------------------

.. todo:: This will require some changes on the Devilbox and will be implemented shortly.

* Symlink and have your own git directory
* Separate data partition, backups


PHP project hostname settings
=============================

When configuring your PHP projects to use MySQL, PostgreSQL, Redis, Mongo and other services,
make sure to set the hostname of each of those services to ``127.0.0.1``.

**Why is that?**

The PHP container port-forwards each service port to its own listen address on ``127.0.0.1``.
The Devilbox also exposes each of those service ports to the host operating system on ``127.0.0.1``.

This allows you to keep your project configuration unchanged and have the same behaviour inside the
PHP container and on your host operating system.

.. important::
   Do not mix up ``localhost`` with ``127.0.0.1``. They behave differently!
   Use ``127.0.0.1`` and do not use ``localhost``.

As an example, if you want to access the MySQL database from within the PHP container, you do the
following:

.. code-block:: bash
   :emphasize-lines: 8

   # Navigate to Devilbox git directory
   host> cd path/to/devilbox

   # Enter the PHP container
   host> ./shell.sh

   # Enter the MySQL console
   php> mysql -u root -h 127.0.0.1 -p
   mysql>

The very same command applies to access the MySQL database from your host operating system:

.. code-block:: bash
   :emphasize-lines: 2

   # Enter the MySQL console
   host> mysql -u root -h 127.0.0.1 -p
   mysql>

So no matter if you use the Devilbox or have another LAMP stack installed locally on your host
operating system, you do not have to change your configuration files if you stick to this tip.

So any of your projects php files that configure MySQL as an example should point the hostname
or IP address of the MySQL server to ``127.0.0.1``:

.. code-block:: php

   <?php
   // MySQL server connection in your project configuration
   mysql_host = '127.0.0.1';
   mysql_port = '3306';
   mysql_user = 'someusername';
   mysql_pass = 'somepassword';
   ?>

.. seealso:: :ref:`work_inside_the_php_container`


Timezone
========

The :ref:`env_timezone` value will affect PHP, web server and MySQL container equally. It does
however not affect any other official Docker container that are used within the Devilbox. This is
an issue that is currently still being worked on.

Feel free to change this to any timezone you require for PHP and MySQL, but keep in mind that
timezone values for databases can be painful, once you want to switch to a different timezone.

A good practice is to always use ``UTC`` on databases and have your front-end application calculate
the correct time for the user. This way you will be more independent of any changes.
