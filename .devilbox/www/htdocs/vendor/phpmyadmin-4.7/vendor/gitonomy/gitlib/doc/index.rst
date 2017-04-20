gitlib - library to manipulate git
==================================

gitlib requires PHP 5.3 and class autoloading (PSR-0) to work properly. Internally, it relies on ``git`` method calls
to fetch informations from repository.

.. code-block:: php

    use Gitonomy\Git\Repository;

    $repository = new Repository('/path/to/repository');

    foreach ($repository->getReferences()->getBranches() as $branch) {
        echo "- ".$branch->getName();
    }

    $repository->run('fetch', array('--all'));


Reference
---------

.. toctree::
   :maxdepth: 1

   api/admin
   api/repository
   api/hooks
   api/workingcopy
   api/commit
   api/blame
   api/blob
   api/branch
   api/tree
   api/log
   api/diff
   api/references
   api/revision


Documentation
-------------

.. toctree::
   :maxdepth: 2

   installation
   debug
   development

Missing features
----------------

Some major features are still missing from gitlib:

* Remotes
* Submodules

If you want to run git commands on repository, call method ``Repository::run`` with method and arguments.
