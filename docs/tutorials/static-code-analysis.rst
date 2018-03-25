.. _tutorial_static_code_analysis:

********************
Static Code Analysis
********************

This tutorial gives you a general overview how to do static code analysis from within the PHP
container.

.. seealso::
    * :ref:`available_tools`
    * :ref:`tutorial_work_inside_the_php_container`


**Table of Contents**

.. contents:: :local:


Awesome-ci
==========

Awesome-ci is a collection of tools for analysing your
workspace and its files. You can for example check for:

* git conflicts
* git ignored files that have not been removed from the git index
* trailing spaces and newlines
* non-utf8 files or utf8 files with bom
* windows line feeds
* null-byte characters
* empty files
* syntax errors for various languages
* inline css or js code
* customized regex

Some of the bundled tools even allow for automatic fixing.

.. seealso:: `awesome-ci <https://github.com/cytopia/awesome-ci>`_

.. code-block:: bash

    # 1. Enter your PHP container
    host> ./bash

    # 2. Go to your project folder
    devilbox@php-7.0.20 $ cd /shared/httpd/my-project

    # 3. Run the tools
    devilbox@php-7.0.20 $ git-conflicts --path=.
    devilbox@php-7.0.20 $ git-ignored --path=.
    devilbox@php-7.0.20 $ file-cr --path=.
    devilbox@php-7.0.20 $ file-crlf --path=.
    devilbox@php-7.0.20 $ file-empty --path=.

    # 4. Run tools with more options
    devilbox@php-7.0.20 $ syntax-php --path=. --extension=php
    devilbox@php-7.0.20 $ syntax-php --path=. --shebang=php


PHPCS
=====

PHPCS is a code style analyser for PHP.

.. seealso:: `PHPCS <https://github.com/squizlabs/PHP_CodeSniffer>`_

.. code-block:: bash

     # 1. Enter your PHP container
     host> ./bash

     # 2. Go to your project folder
     devilbox@php-7.0.20 $ cd /shared/httpd/my-project

     # 3. Run it
     devilbox@php-7.0.20 $ phpcs .


ESLint
======

ESLint is a Javascript static source code analyzer.

.. seealso:: `ESLint <http://eslint.org>`_

.. code-block:: bash

     # 1. Enter your PHP container
     host> ./bash

     # 2. Go to your project folder
     devilbox@php-7.0.20 $ cd /shared/httpd/my-project

     # 3. Run it
     devilbox@php-7.0.20 $ eslint .
