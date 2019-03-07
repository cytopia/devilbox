.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _bashrc_sh:

*********
bashrc.sh
*********

Each PHP container is using bash as its default shell. If you do not like the way it is currently
configured, you can add your own configuration files to overwrite settings.

.. seealso:: :ref:`work_inside_the_php_container`


**Table of Contents**

.. contents:: :local:


Directory mapping
=================

Inside the Devilbox git directory you will find a directory called ``bash/``.
Every file inside this directory ending by ``*.sh`` will be source by your bash shell,
allowing for a customized bash configuration. All files not ending by ``*.sh`` will be ignored
and can be used to create config files for other programs.

The ``bash/`` directory will be mounted into the PHP container to ``/etc/bashrc-devilbox.d/``.

+--------------+-----------------------------+
| Host OS path | Docker path                 |
+==============+=============================+
| ``./bash/``  | ``/etc/bashrc-devilbox.d/`` |
+--------------+-----------------------------+


Examples
========

Custom aliases
--------------

Let's say you want to add some custom shell aliases. All you have to do is create any file ending
by ``.sh`` and place it into the ``./bash/`` directory:

.. code-block:: bash

   # Navigate to the Devilbox git directory
   host> cd path/to/devilbox

   # Create a new file
   host> touch ./bash/aliases.sh

   # Add some content to the file
   host> vi ./bash/aliases.sh

.. code-block:: bash
   :caption: ./bash/aliases.sh

   alias l='ls -a'
   alias ll='ls -al'
   alias www='cd /shared/httpd'


Custom vim configuration
------------------------

The ``.vimrc`` is usually place directly in the users home directory and the Devilbox does not
offer any mounts directly to that directory, however you can use a trick with shell aliases
to use ``vim`` with a different config file by default.

First of all, place your favorite ``.vimrc`` into the ``./bash/`` directory

.. code-block:: bash

   # Navigate to the Devilbox git directory
   host> cd path/to/devilbox

   # Copy your vim config to the ./bash directory
   host> cp ~/.vimrc bash/.vimrc

Right now, this is not going to do anything and as ``.vimrc`` is not ending by ``.sh`` it is also
ignored by the shell itself. What is now left to do, is make vim itself always use this config file.

As you can see from the above stated directory mapping, the ``.vimrc`` file will end up under:
``/etc/bashrc-devilbox.d/.vimrc`` inside the PHP container, so just create a shell alias for vim
that will always use this file:

.. code-block:: bash

   # Navigate to the Devilbox git directory
   host> cd path/to/devilbox

   # Create a new file
   host> touch ./bash/vim.sh

   # Add your vim alias
   host> vi ./bash/vim.sh

.. code-block:: bash
   :caption: ./bash/vim.sh

   alias vim='vim -u /etc/bashrc-devilbox.d/.vimrc'

Whenever you start ``vim`` inside any PHP container, it will automatically use the provided vim
configuration file.

This trick will work for all tools that require configuration files.
