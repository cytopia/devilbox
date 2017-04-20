Create and access git repositories
==================================

gitlib provides methods to initialize new repositories.

Create a repository
-------------------

To initialize a new repository, use method ``Admin::init``.

.. code-block:: php

    // Initialize a bare repository
    $repository = Gitonomy\Git\Admin::init('/path/to/repository');

    // Initialize a non-bare repository
    $repository = Gitonomy\Git\Admin::init('/path/to/repository', false);

Default behavior is to create a bare repository. If you want to initialize a
repository with a working copy,pass ``false`` as third argument of Repository
constructor.

Cloning repositories
--------------------

You can clone a repository from an URL by doing:

.. code-block:: php

    // Clone to a bare repository
    $repository = Gitonomy\Git\Admin::cloneTo('/tmp/gitlib', 'https://github.com/gitonomy/gitlib.git');

    // Clone to a non-bare repository
    $repository = Gitonomy\Git\Admin::cloneTo('/tmp/gitlib', 'https://github.com/gitonomy/gitlib.git', false);

Default behavior is to clone in a bare repository.

You can also clone a repository and point it to a specific branch. In a non-bare repository, this branch will be checked out:

.. code-block:: php

    // Clone to a bare repository
    $repository = Gitonomy\Git\Admin::cloneBranchTo('/tmp/gitlib', 'https://github.com/gitonomy/gitlib.git', 'a-branch');

    // Clone to a non-bare repository
    $repository = Gitonomy\Git\Admin::cloneBranchTo('/tmp/gitlib', 'https://github.com/gitonomy/gitlib.git', 'a-branch' false);

Clone a Repository object
-------------------------

If you already have a Repository instance and want to clone it, you can use this shortcut:

.. code-block:: php

    $new = $repository->cloneTo('/tmp/clone');

Mirror a repository
-------------------

If you want to mirror fully a repository and all references, use the ``mirrorTo`` method. This method
takes only two arguments, where to mirror and what to mirror:

.. code-block:: php

    // Mirror to a bare repository
    $mirror = Gitonomy\Git\Admin::mirrorTo('/tmp/mirror', 'https://github.com/gitonomy/gitlib.git');

    // Mirror to a non-bare repository
    $mirror = Gitonomy\Git\Admin::mirrorTo('/tmp/mirror', 'https://github.com/gitonomy/gitlib.git', false);


References
::::::::::

* http://linux.die.net/man/1/git-init
* http://linux.die.net/man/1/git-clone
