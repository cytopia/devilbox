Commit
======

To access a *Commit*, starting from a repository object:

.. code-block:: php

    $repository = new Gitonomy\Git\Repository('/path/to/repository');
    $commit = $repository->getCommit('a7c8d2b4');

Browsing parents
----------------

A *Commit* can have a natural number of parents:

* **no parent**: it's an initial commit, the root of a tree
* **one parent**: it means it's not a merge, just a regular commit
* **many parents**: it's a merge-commit

You have 2 methods available for accessing parents:

.. code-block:: php

    // Access parent hashes
    $hashes = $commit->getParentHashes();

    // Access parent commit objects
    $commits = $commit->getParents();

For example, if you want to display all parents, starting from a commit:

.. code-block:: php

    function displayLog(Gitonomy\Git\Commit $commit) {
        echo '- '.$commit->getShortMessage()."\n";
        foreach ($commit->getParents() as $parent) {
            displayLog($parent);
        }
    }

Notice that this function will first display all commits from first merged
branch and then display all commits from next branch, and so on.

Accessing tree
--------------

The tree object contains the reference to the files associated to a given
commit. Every commit has one and only one tree, referencing all files and
folders of a given state for a project. For more informations about the tree,
see the chapter dedicated to it.

To access a tree starting from a commit:

.. code-block:: php

    // Returns the tree hash
    $tree = $commit->getTreeHash();

    // Returns the tree object
    $tree = $commit->getTree();

Author & Committer informations
-------------------------------

Each commit has two authoring informations: an author and a committer. The
author is the creator of the modification, authoring a modification in the
repository. The committer is responsible of introducing this modification to
the repository.

You can access informations from author and committer using those methods:

.. code-block:: php

    // Author
    $commit->getAuthorName();
    $commit->getAuthorEmail();
    $commit->getAuthorDate(); // returns a DateTime object

    // Committer
    $commit->getCommitterName();
    $commit->getCommitterEmail();
    $commit->getCommitterDate(); // returns a DateTime object

Commit message and short message
--------------------------------

Each commit also has a message, associated to the modification. This message
can be multilined.

To access the message, you can use the *getMessage* method:

.. code-block:: php

    $commit->getMessage();

For your convenience, this library provides a shortcut method to keep only the
first line or first 50 characters if the first line is too long:

.. code-block:: php

    $commit->getShortMessage();

You can customize it like this:

.. code-block:: php

    $commit->getShortMessage(45, true, '.');

* The first parameter is the max length of the message.
* The second parameter determine if the last word should be cut or preserved
* The third parameter is the separator

There are also two other methods for your convenience:

.. code-block:: php

    // The first line
    $commit->getSubjectMessage();

    // The body (rest of the message)
    $commit->getBodyMessage();

Diff of a commit
----------------

You can check the modifications introduced by a commit using the *getDiff*
method. When you request a diff for a commit, depending of the number of
parents, the strategy will be different:

* If you have *no parent*, the diff will be the content of the tree
* If you only have *one parent*, the diff will be between the commit and his
  parent
* If you have *multiple parents*, the diff will be the difference between the
  commit and the first common ancestor of all parents

For more informations about the diff API, read the related chapter.

To access the *Diff* object of a commit, use the method *getDiff*:

.. code-block:: php

    $diff = $commit->getDiff();

Last modification of a file
---------------------------

To know the last modification of a file, you can use the *getLastModification*
method on a commit.

Here is a very straightforward example:

.. code-block:: php

    $last = $commit->getLastModification('README');

    echo "Last README modification:\n";
    echo"  Author: ".$last->getAuthorName()."\n";
    echo"    Date: ".$last->getAuthorDate()->format('d/m/Y')."\n";
    echo" Message: ".$last->getMessage();

Find every branches containing a commit
---------------------------------------

.. code-block:: php

    $branches       = $commit->getIncludingBranches($includeLocalBranches, $includeRemoteBranches);
    $localBranches  = $commit->getIncludingBranches(true, false);
    $remoteBranches = $commit->getIncludingBranches(false, true);
