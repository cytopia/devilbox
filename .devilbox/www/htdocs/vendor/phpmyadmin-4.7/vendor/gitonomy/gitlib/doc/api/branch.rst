Branch
======

To access a *Branch*, starting from a repository object:

.. code-block:: php

    $repository = new Gitonomy\Git\Repository('/path/to/repository');
    $branch = $repository->getReferences()->getBranch('master');

You can check is the branch is a local or remote one:

.. code-block:: php

    $branch->isLocal();
    $branch->isRemote();
