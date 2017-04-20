Tree and files
==============

To organize folders, git uses trees. In gitlib, those trees are represented
via *Tree* object.

To get the root tree associated to a commit, use the *getTree* method on the
commit object:

.. code-block:: php

    $tree = $commit->getTree();

This tree is the entry point of all of your files.

The main method for a tree is the *getEntries* method. This method will
return an array, indexed by name. Each of those elements will be the entry mode
and the entry object.

Let's understand how it works with a concrete example:

.. code-block:: php

    function displayTree(Tree $tree, $indent = 0)
    {
        $indent = str_repeat(' ', $indent);
        foreach ($tree->getEntries() as $name => $data) {
            list($mode, $entry) = $data;
            if ($entry instanceof Tree) {
                echo $indent.$name."/\n";
                displayTree($tree, $indent + 1);
            } else {
                echo $indent.$name."\n";
            }
        }
    }

    displayTree($commit->getTree());

This method will recursively display all entries of a tree.

Resolve a path
--------------

To access directly a sub-file, the easier is probably to use the *resolvePath*
method.

An example:

.. code-block:: php

    $source = $tree->resolvePath('src/Gitonomy/Git');

    $source instanceof Tree;
