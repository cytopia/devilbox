# Contributing to SQL Parser

## Reporting issues

Our issue tracker is hosted at GitHub:

https://github.com/phpmyadmin/sql-parser/issues

Please search for existing issues before reporting new ones.

## Working with Git checkout

The dependencies are managed by Composer, to get them all installed (or update
on consequent runs) do:

```
composer update
```

## Submitting patches

Please submit your patches using GitHub pull requests, this allows us to review
them and to run automated tests on the code.

## Coding standards

We do follow PSR-1 and PSR-2 coding standards.

You can use phpcbf to fix the code to match our expectations:

```
./vendor/bin/phpcbf
```

## Testsuite

Our code comes with quite comprehensive testsuite, it is automatically executed
on every commit and pull request, you can also run it locally:

```
./vendor/bin/phpunit
```

The testsuite relies on fixtures of parser states, in case you need to
regenerate some of these there are helper scripts in tools directory:

```
# Remove file you want to regenerate
rm tests/data/parser/parse.out

# Run the generator located in the tools directory
./tools/run_generators.sh
```
