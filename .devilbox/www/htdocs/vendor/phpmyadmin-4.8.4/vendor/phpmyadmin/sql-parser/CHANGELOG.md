# Change Log

## [4.2.5] - 2018-10-31

* Fix issue with CREATE OR REPLACE VIEW.

## [4.2.4] - 2017-12-06

* Fix parsing of CREATE TABLE with per field COLLATE.
* Improved Context::loadClosest to better deal with corner cases.
* Localizaton updates.

## [4.2.3] - 2017-10-10

* Make mbstring extension optional (though Symfony polyfill).
* Fixed build CREATE TABLE query with PARTITIONS having ENGINE but not VALUES.

## [4.2.2] - 2017-09-28

* Added support for binding parameters.

## [4.2.1] - 2017-09-08

* Fixed minor bug in Query::getFlags.
* Localizaton updates.

## [4.2.0] - 2017-08-30

* Initial support for MariaDB SQL contexts.
* Add support for MariaDB 10.3 INTERSECT and EXCEPT.

## [4.1.10] - 2017-08-21

* Use custom LoaderException for context loading errors.

## [4.1.9] - 2017-07-12

* Various code cleanups.
* Improved error handling of several invalid statements.

## [4.1.8] - 2017-07-09

* Fixed parsing SQL comment at the end of query.
* Improved handing of non utf-8 strings.
* Added query flag for SET queries.

## [4.1.7] - 2017-06-06

* Fixed setting combination SQL Modes.

## [4.1.6] - 2017-06-01

* Fixed building query with GROUP BY clause.

## [4.1.5] - 2017-05-15

* Fixed invalid lexing of queries with : in strings.
* Properly handle maximal length of delimiter.

## [4.1.4] - 2017-05-05

* Fixed wrong extract of string tokens with escaped characters.
* Properly handle lowercase begin statement.

## [4.1.3] - 2017-04-06

* Added suppport for DELETE ... JOIN clauses.
* Changed BufferedQuery to include comments in output.
* Fixed parsing of inline comments.

## [4.1.2] - 2017-02-20

* Coding style improvements.
* Chinese localization.
* Improved order validatin for JOIN clauses.
* Improved pretty printing of JOIN clauses.
* Added support for LOAD DATA statements.

## [4.1.1] - 2017-02-07

* Localization using phpmyadmin/motranslator is now optional.
* Improved testsuite.
* Better handling of non upper cased not reserved keywords.
* Minor performance and coding style improvements.

## [4.1.0] - 2017-01-23

* Use phpmyadmin/motranslator to localize messages.

## [4.0.1] - 2017-01-23

* Fixed CLI wrappers for new API.
* Fixed README for new API.

## [4.0.0] - 2017-01-23

* Added PhpMyAdmin namespace prefix to follow PSR-4.

## [3.4.17] - 2017-01-20

* Coding style fixes.
* Fixed indentation in HTML formatting.
* Fixed parsing of unterminated variables.
* Improved comments lexing.

## [3.4.16] - 2017-01-06

* Coding style fixes.
* Properly handle operators AND, NOT, OR, XOR, DIV, MOD

## [3.4.15] - 2017-01-02

* Fix return value of Formatter.toString() when type is text
* Fix parsing of FIELDS and LINES options in SELECT..INTO
* PHP 7.2 compatibility.
* Better parameter passing to query formatter.

## [3.4.14] - 2016-11-30

* Improved parsing of UNION queries.
* Recognize BINARY function.

## [3.4.13] - 2016-11-15

* Fix broken incorrect clause order detection for Joins.
* Add parsing of end options in Select statements.

## [3.4.12] - 2016-11-09

* Added verification order of SELECT statement clauses.

## [3.4.11] - 2016-10-25

* Fixed parsing of ON UPDATE option in field definition of TIMESTAMP type with precision
* Fixed parsing of NATURAL JOIN, CROSS JOIN and related joins.
* Fixed parsing of BEGIN/END labels.

## [3.4.10] - 2016-10-03

* Fixed API regression on DELETE statement

## [3.4.9] - 2016-10-03

* Added support for CASE expressions
* Support for parsing and building DELETE statement
* Support for parsing subqueries in FROM clause

## [3.4.8] - 2016-09-22

* No change release to sync GitHub releases with Packagist

## [3.4.7] - 2016-09-20

* Fix parsing of DEFINER without backquotes
* Fixed escaping HTML entities in HTML formatter
* Fixed escaping of control chars in CLI formatter

## [3.4.6] - 2016-09-13

* Fix parsing of REPLACE INTO ...
* Fix parsing of INSERT ... ON DUPLICATE KEY UPDATE ...
* Extended testsuite
* Re-enabled PHP 5.3 support

## [3.4.5] - 2016-09-13

* Fix parsing of INSERT...SELECT and INSERT...SET syntax
* Fix parsing of CREATE TABLE ... PARTITION
* Fix parsing of SET CHARACTER SET, CHARSET, NAMES
* Add Support for 'CREATE TABLE `table_copy` LIKE `table`

## [3.4.4] - 2016-04-26

* Add support for FULL OUTER JOIN

## [3.4.3] - 2016-04-19

* Fix parsing of query with \

## [3.4.2] - 2016-04-07

* Recognize UNION DISTINCT
* Recognize REGEXP and RLIKE operators

## [3.4.1] - 2016-04-06

* Add FULLTEXT and SPATIAL keywords
* Properly parse CREATE TABLE [AS] SELECT
* Fix parsing of table with DEFAULT and COMMENT

## [3.4.0] - 2016-02-23

* Fix parsing DEFAULT value on CREATE
* Fix parsing of ALTER VIEW

## [3.3.1] - 2016-02-12

* Condition: Allow keyword `INTERVAL`.

## [3.3.0] - 2016-02-12

* Expression: Refactored parsing options.

## [3.2.0] - 2016-02-11

* Context: Added custom mode that avoids escaping when possible.

## [3.1.0] - 2016-02-10

* ArrayObj: Handle more complex expressions in arrays.
* BufferedQuery: Backslashes in comments escaped characters in comments.
* Condition: Allow `IF` in conditions.
* Context: Add `;` as operator.
* Context: Updated contexts to contain `BIT` data type.
* CreateStatement: The `DEFAULT` option may be an expression.
* DescribeStatement: Added `DESC` as alias for `DESCRIBE`.
* Expression: Rewrote expression parsing.
* Misc: Added PHPUnit's Code Coverage 3.0 as a dependency.
* Misc: Added support for PHP 5.4 back.
* Misc: Removed dependency to Ctype.
* Misc: Repository transfered from @udan11 to @phpMyAdmin.
* Misc: Updated `.gitignore` to ignore `composer.lock`.
* Misc: Updated Composer and Travis configuration for PHP 7 and PHPUnit 5.
* Tools: Documented tags in `ContextGenerator`.

## [3.0.8] - 2015-12-18

* Allow `NULL` in expressions.
* Downgraded PHPUnit to 4.8. Removed old PHP versions.
* Updated PHPUnit to 5.1 and fixed some of the tests.
* Added `UNION ALL` as a type of `UNION`.
* Expressions are permitted in `SET` operations.
* Added `STRAIGHT_JOIN` as a known type of join.
* Added missing definitions for `MATCH` and `AGAINST`.
* Added missing statement (`FLUSH` and `DEALLOCATE`).

## [3.0.7] - 2015-11-12

* Expressions may begin with a function that is also a reserved keyword (e.g. `IF`).

## [3.0.6] - 2015-11-12

* Fixed a bug where formatter split the function name and the parameters list.

## [3.0.5] - 2015-11-08

* Add GRANT as known statement.
* Use JOIN expressions for flag detection.
* Fix the order of clauses in SELECT statements involving UNIONs.
* Added dummy parsers for CREATE USER and SET PASSWORD statements.
* Accept NOT operator in conditions.
* Fixed DELIMITER statements in BufferedQuery.
* Added INSERT statement builder.

## [3.0.4] - 2015-10-21

* Fix error message in `SqlParser\Components\OptionsArray`.

## [3.0.3] - 2015-10-10

* Avoid building a field multiple times if clause has synonyms.

## [3.0.2] - 2015-10-10

* Add EXISTS as an acceptable keyword in conditions.

## [3.0.1] - 2015-10-06

* Handle backslashes separately for `SqlParser\Utils\BufferedQuery`. Fixes a bug where backslashes in combination with strings weren't handled properly.

## [3.0.0] - 2015-10-02

__Breaking changes:__

* `SqlParser\Components\Reference::$table` is now an instance of `SqlParser\Components\Expression` to support references from other tables.

## [2.1.3] - 2015-10-02

* Add definitions for all JOIN clauses.

## [2.1.2] - 2015-10-02

* Properly parse options when the value of the option is '='.

## [2.1.1] - 2015-09-30

* Only RANGE and LIST type partitions support VALUES.

## [2.1.0] - 2015-09-30

* Added utilities for handling tokens and tokens list.

## [2.0.3] - 2015-09-30

* Added missing NOT IN operator. This caused troubles when parsing conditions that contained the `NOT IN` operator.

## [2.0.2] - 2015-09-30

* Added support for `OUTER` as an optional keyword in joins.

## [2.0.1] - 2015-09-30

* Fixed a bug related to (sub)partitions options not being included in the built component. Also, the option `ENGINE` was unrecognized.

## [2.0.0] - 2015-09-25

* Better parsing for CREATE TABLE statements (related to breaking change 1).
* Added support for JSON data type.
* Refactoring and minor documentation improvements.

__Breaking changes:__
* `SqlParser\Components\Key::$columns` is now an array of arrays. Each array must contain a `name` key which represents the name of the column and an optional `length` key which represents the length of the column.

## [1.0.0] - 2015-08-20

* First release of this library.

