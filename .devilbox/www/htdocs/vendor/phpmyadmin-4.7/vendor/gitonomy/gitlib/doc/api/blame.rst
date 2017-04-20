Blaming files
=============

Line-per-line iteration
-----------------------

To iterate on lines of a blame:

.. code-block:: php

    $blame = $repository->getBlame('master', 'README.md');

    foreach ($blame->getLines() as $lineNumber => $line) {
        $commit = $line->getCommit();
        echo $lineNumber.': '.$line->getContent()."    - ".$commit->getAuthorName()."\n";
    }

The *getLines* method returns an array indexed starting from 1.

As you can see, you can access the commit object related to the line you are iterating on.

If you want to access directly a line:

.. code-block:: php

    $line = $blame->getLine(32);

The Line object
---------------

LineObject represents an item of the blame file. It is composed of those informations:

.. code-block:: php

    $line->getCommit();  // returns a Commit
    $line->getContent(); // returns text

    // you can access author from commmit:
    $author = $line->getCommit()->getAuthorName();

Group reading by commit
-----------------------

If you plan to display it, you'll probably need a version where lines from same commit are grouped.

To do so, use the *getGroupedLines* method that will return an array like this:

.. code-block:: php

    $blame = array(
        array(Commit, array(1 => Line, 2 => Line, 3 => Line)),
        array(Commit, array(4 => Line)),
        array(Commit, array(5 => Line, 6 => Line))
    )
