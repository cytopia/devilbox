Hooks
=====

It's possible to define custom hooks on any repository with git. Those hooks
are located in the *.git/hooks* folder.

Those files need to be executable. For convenience, gitlib will set them to
*777*.

With *gitlib*, you can manage hooks over a repository using the *Hooks* object.

To access it from a repository, use the *getHooks* method on a *Repository*
object:

.. code-block:: php

    $hooks = $repository->getHooks();

Reading hooks
-------------

To read the content of a hook, use the *get* method like this:

.. code-block:: php

    $content = $hooks->get('pre-receive'); // returns a string

If the hook does not exist, an exception will be thrown (*InvalidArgumentException*).

You can test if a hook is present using the method *has*:

.. code-block:: php

    $hooks->has('pre-receive'); // a boolean indicating presence

Inserting hooks
---------------

You can modify a hook in two different ways: creating a new file or using a symlink.

To create the hook using a symlink:

.. code-block:: php

    $hooks->setSymlink('pre-receive', '/path/to/file-to-link');

If the hook already exist, a *LogicException* will be thrown. If an error occured
during symlink creation, a *RuntimeException* will be thrown.

If you want to directly create a new file in hooks directory, use the
method *set*. This method will create a new file, put content in it and make it
executable:

.. code-block:: php

    $content = <<<HOOK
    #!/bin/bash
    echo "Push is disabled"
    exit 1

    HOOK;

    // this hook will reject every push

    $hooks->set('pre-receive', $content);

If the hook already exists, a *LogicException* will be thrown.

Removing hooks
--------------

To remove a hook from a repository, use the function *remove*:

.. code-block:: php

    $hooks->remove('pre-receive');
