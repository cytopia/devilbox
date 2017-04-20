Working copy
============

Working copy is the folder associated to a git repository. In *gitlib*, you
can access this object using the *getWorkingCopy* on a *Repository* object:

.. code-block:: php

    $repo = new Repository('/path/to/working-dir');
    $wc = $repo->getWorkingCopy();

Checkout a revision
-------------------

You can checkout any revision using *checkout* method. You can also pass a
second argument, which will be passed as argument with ``-b``:

.. code-block:: php

    // git checkout master
    $wc->checkout('master');

    // git checkout origin/master -b master
    $wc->checkout('origin/master', 'master');

You can also pass a *Reference* or a *Commit*.

Staged modifications
--------------------

You can get a diff of modifications pending in staging area. To get the ``Diff`` object,
call method ``getDiffStaged()``:

.. code-block:: php

    $diff = $wc->getDiffStaged();

Pending modifications
---------------------

You can get pending modifications on tracked files by calling method ``getDiffPending()``:

.. code-block:: php

    $diff = $wc->getDiffPending();
