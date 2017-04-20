Debug-mode
==========

gitlib offers a debug mode, to make you see edge-cases of your usage. This is called
debug-mode.

Debug-mode is enabled by default. If you disable it, gitlib will behave differently:

* when an error is met during execution, gitlib will try to minimize it, to not block
  execution flow. Errors will still be reporter in logger.
* logs will be more verbose. They will contain every output, every return code, every
  possible information to ease debugging.

If you want to disable exceptions and try to minimize as much as possible errors, pass
``false`` when construction a repository:

.. code-block:: php

    $repository = new Gitonomy\Git\Repository($path'/tmp/repo', $debug = false)

``$debug`` argument should be available in every method you can use to create a
repository.
