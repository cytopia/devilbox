.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _custom_scripts_globally:

***********************
Custom scripts globally
***********************

You can provide custom startup commands via bash scripts that are executed by all PHP container.
This may be useful to specify additional software to install or additional settings to apply during
the initial startup.


.. seealso::
   * :ref:`custom_scripts_per_php_version` (individually for different PHP versions)
   * :ref:`autostarting_nodejs_apps`


.. note::
   Global scripts are always executed **after** per PHP version scripts.


**Table of Contents**

.. contents:: :local:


General
=======

You can add shell scripts that are executed for all PHP container equally.

.. important::
   Provided scripts must end by the file extension ``.sh`` and should be executable.
   Anything not ending by ``.sh`` will be ignored.

.. important::
   Provided scripts will be executed by the ``root`` user within the PHP container.


Where
-----

Startup scripts can be added to ``autostart/``.

.. code-block:: bash
   :emphasize-lines: 3

   host> ls -l path/to/devilbox/

   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 autostart/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 backups/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 bash/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 ca/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 cfg/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 compose/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 data/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 docs/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 mail/
   drwxr-xr-x  2 cytopia cytopia 4096 Mar  5 21:53 mod/


Custom scripts are added by placing a file into ``autostart/``. The file must end by ``.sh`` in
order to be executed by the PHP container.

When
----

The scripts will be executed by the PHP container during initial startup. Whenever you change your
scripts, ensure to restart the Devilbox.

How
---

The scripts will always be executed inside the PHP container (Debian Linux) and will be run with
``root`` privileges. It is however possible to drop privileges within the script to have them
executed as a normal user.


Examples
========

Running commands as devilbox user
---------------------------------

As mentioned above, all scripts are run by the ``root`` user.
If you do need something to be executed as the normal user: ``devilbox``, you can simply ``su``
inside the shell script.

The following example will install ``grunt`` and start a NodeJS application as the devilbox user
for whatever PHP container has been started.

.. code-block:: bash
   :caption: autostart/myscript.sh

   # Install grunt as devilbox user
   su -c "npm install grunt" -l devilbox

   # Start a NodeJS application with pm2 as devilbox user
   su -c "cd /shared/httpd/my-node/src/; pm2 start index.js" -l devilbox
