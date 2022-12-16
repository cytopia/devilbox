.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _custom_scripts_per_project:

******************************
Custom scripts per PHP version
******************************

You can provide custom startup commands via bash scripts to each of the PHP container individually.
This may be useful to specify additional software to install or additional settings to apply during
the initial startup.


.. seealso::
   * :ref:`custom_scripts_per_php_version` (individually for different PHP versions)
   * :ref:`custom_scripts_globally` (equal for all PHP versions)
   * :ref:`autostarting_nodejs_apps`


.. note::
   Scripts are executed in the following order:
   1. per PHP version scripts
   2. global scripts
   3. per project scripts


**Table of Contents**

.. contents:: :local:


General
=======

.. important::
   Provided scripts must end by the file extension ``.sh`` and should be executable.
   Anything not ending by ``.sh`` will be ignored.

.. important::
   Provided scripts will be executed by the ``root`` user within the PHP container.


Where
-----

In your project folder, you will need to create a folder called ``.devilbox/autostart``
in which you can add custom shell scripts.

When
----

The scripts will be executed by the PHP container during initial startup. Whenever you change your
scripts, ensure to restart the Devilbox.

How
---

The scripts will always be executed inside the PHP container (Debian Linux) and will be run with
``root`` privileges. It is however possible to drop privileges within the script to have them
executed as a normal user.

Environment Variables
---------------------

The following environment variables will be available to your script:
   * ``DEVILBOX_PROJECT_NAME``: The name of your project folder
   * ``DEVILBOX_PROJECT_DIR``: The full path to your project folder (e.g. "/shared/httpd/$DEVILBOX_PROJECT_NAME")
   * ``DEVILBOX_PROJECT_DOCROOT``: the full path to your web root (e.g. "$DEVILBOX_PROJECT_DIR/htdocs")
   * All other variables that have been set in your ``.env`` file


Examples
========

Setting up a cron job
---------------------

Most CMS platforms and other website applications require or suggest to run a cron job from the
command line to perform e.g. long-running, repetitive or scheduled tasks.

With the helper function ``cron_add()`` it is easy to specify such a cron job:


.. code-block:: bash

    #!/usr/bin/env bash

    cron_add "* * * * * "$(which php)" "${DEVILBOX_PROJECT_DOCROOT}/cron.php" some arguments >/dev/null 2>&1"



Running commands as devilbox user
---------------------------------

As mentioned above, all scripts are run by the ``root`` user. If you do need something to be executed as the
normal user ``devilbox``, you can simply ``su -l devilbox -c "command"`` inside the shell script.

For further details see :ref:`custom_scripts_globally`'s example section.
