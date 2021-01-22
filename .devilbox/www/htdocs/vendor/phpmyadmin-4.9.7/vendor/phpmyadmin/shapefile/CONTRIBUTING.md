# Contributing to shapefile

## Reporting issues

Our issue tracker is hosted at GitHub:

https://github.com/phpmyadmin/shapefile/issues

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

You can use php-cs-fixer to fix the code to match our expectations:

```
php-cs-fixer fix .
```

## Testsuite

Our code comes with quite comprehensive testsuite, it is automatically executed
on every commit and pull request, you can also run it locally:

```
./vendor/bin/phpunit -c phpunit.xml
```
