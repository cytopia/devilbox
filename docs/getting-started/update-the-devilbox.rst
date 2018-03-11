*******************
Update the Devilbox
*******************

Update git repository
=====================

.. warning::
      Whenever you check out a different version, make sure that your ``.env`` file is up-to-date
      with the bundled ``env-example`` file. Different Devilbox releases might require different
      settings to be available inside the ``.env`` file.

You can also compare your current ``.env`` file with the provided ``env-example`` file by using
your favorite diff editor:

How to diff the ``.env`` file
-----------------------------

.. code-block:: console

   vimdiff .env env-example

.. code-block:: console

   diff .env env-example

.. code-block:: console

   meld .env env-example




Update Docker container
=======================
