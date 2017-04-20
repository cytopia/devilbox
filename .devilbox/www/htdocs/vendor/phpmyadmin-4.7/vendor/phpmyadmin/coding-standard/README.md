# phpMyAdmin Coding Standard

phpMyAdmin Coding Standard for PHP CodeSniffer.

## Installation & Usage

Before starting using our coding standard install [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer).

Clone or download this repo somewhere on your computer or install it with [Composer](http://getcomposer.org/).
To do so, add the dependency to your `composer.json` file by running `composer require --dev phpmyadmin/coding-standard`.

Add the standards directory to PHP_CodeSniffer installed paths:

```sh
$ phpcs --config-set installed_paths ./vendor/phpmyadmin/coding-standard
```

You can verify whether the standard can be found:

```sh
$ phpcs -i
The installed coding standards are MySource, PSR1, PHPCS, Zend, Squiz, PEAR, PSR2 and PMAStandard
```

Run CodeSniffer:

```sh
$ phpcs --standard=PMAStandard /path/to/code
```
## Using

PMAStandard has its tuned coding style checks, such as missing `@author`,
`@copyright`, `@link` or other tags. Let's take a look at the same example as
above, but now checked with PMAStandard:

```sh
$ phpcs --standard=PMAStandard /path/to/code/myfile.php
123 | WARNING | Line exceeds 85 characters; contains 139 characters
184 | ERROR   | Line indented incorrectly; expected at least 20 spaces, found
    |         | 16
272 | ERROR   | Closing parenthesis of a multi-line IF statement must be on a
    |         | new line
272 | ERROR   | Multi-line IF statement not indented correctly; expected 12
    |         | spaces but found 9
```
