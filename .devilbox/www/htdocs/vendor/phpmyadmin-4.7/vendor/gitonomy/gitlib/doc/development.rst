Developing gitlib
=================

If you plan to contribute to gitlib, here are few things you should know:

Documentation generation
::::::::::::::::::::::::

Documentation is generated using Sphinx (restructured text). Configuration file
is located in https://github.com/gitonomy/website/blob/master/bin/conf.py

You will need to fetch vendor modules for PHP blocks especially. If you really
want to generate it, install the website project locally and hack into it.

Test against different git versions
:::::::::::::::::::::::::::::::::::

A script ``test-git-version.sh`` is available in repository to test gitlib against
many git versions.

This script is not usable on Travis-CI, they would hate me for this. It creates
a local cache to avoid fetching from Github and compiling if already compiled.

Use it at your own risk, it's still under experiment.
