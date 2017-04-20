Repository methods
==================

Creating a *Repository* object is possible, providing a *path* argument to the
constructor:

.. code-block:: php

    $repository = new Repository('/path/to/repo');

Repository options
------------------

The constructor of Repository takes an additional parameter: ``$options``.
This parameter can be used used to tune behavior of library.

Available options are:

* **debug** (default: true): Enables exception when edge cases are met
* **environment_variables**: (default: none) An array of environment variables to be set in sub-process
* **logger**: (default: none) Logger to use for reporting of execution (a ``Psr\Log\LoggerInterface``)
* **command**: (default: ``git``) Specify command to execute to run git
* **working_dir**: If you are using multiple working directories, this option is for you

An example:

.. code-block:: php

    $repository = new Repository('/path/to/repo', array(
        'debug'  => true,
        'logger' => new Monolog\Logger()
    ));

Test if a repository is bare
----------------------------

On a *Repository* object, you can call method *isBare* to test if your repository is bare or not:

.. code-block:: php

    $repository->isBare();

Compute size of a repository
----------------------------

To know how much size a repository is using on your drive, you can use ``getSize`` method on a *Repository* object.

.. warning:: This command was only tested with linux.

The returned size is in kilobytes:

.. code-block:: php

    $size = $repository->getSize();

    echo "Your repository size is ".$size."KB";

Access HEAD
-----------

``HEAD`` represents in git the version you are working on (in working tree).
Your ``HEAD`` can be attached (using a reference) or detached (using a commit).

.. code-block:: php

    $head = $repository->getHead(); // Commit or Reference
    $head = $repository->getHeadCommit(); // Commit

    if ($repository->isHeadDetached()) {
        echo "Sorry man\n";
    }

Options for repository
----------------------

Logger
......

If you are developing, you may appreciate to have a logger inside repository, telling you every executed command.

You call method ``setLogger`` as an option on repository creation:

.. code-block:: php

    $repository->setLogger(new Monolog\Logger('repository'));

    $repository->run('fetch', array('--all'));

You can also specify as an option on repository creation:

    $logger = new Monolog\Logger('repository');
    $repository = new Repository('/path/foo', array('logger' => $logger));

    $repository->run('fetch', array('--all'));

This will output:

.. code-block:: text

    info run command: fetch "--all"
    debug last command (fetch) duration: 23.24ms
    debug last command (fetch) return code: 0
    debug last command (fetch) output: Fetching origin

Disable debug-mode
..................

Gitlib throws an exception when something seems wrong. If a ``git` command returns a non-zero result, it will stop execution and throw an ``RuntimeException``.

If you want to prevent this, set ``debug`` option to ``false``. This will make Repository log errors and return empty data instead of throwing exceptions.

.. code-block:: php

    $repository = new Repository('/tmp/foo', array('debug' => false, 'logger' => $logger));

.. note:: if you plan to disable debug, you should rely on logger to keep a trace of edge failing cases.

Specify git command to use
..........................

You can pass option ``command`` to specify which command to use to run git calls. If you have a git binary
located somewhere else, use this option to specify to gitlib path to your git binary:

.. code-block:: php

    $repository = new Gitonomy\Git\Repository('/tmp/foo', array('command' => '/home/alice/bin/git'));

Environment variables
.....................

Now you want to set environment variables to use to run ``git`` commands. It might be useful.

.. code-block:: php

    $repository = new Gitonomy\Git\Repository('/tmp/foo', array('environment_variables' => array('GIT_')))
