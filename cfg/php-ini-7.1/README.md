# PHP 7.1 ini directory

## General

* Add you custom php.ini files into this directory.
* Only files ending by `.ini` will be enabled
* Only files ending by `.ini` are ignored by git


## Example files

This directory also holds three example files:

| File                         | Description                             |
|------------------------------|-----------------------------------------|
| `devilbox-php.ini-blackfire` | Blackfire configuration                 |
| `devilbox-php.ini-default`   | Represents current PHP default settings |
| `devilbox-php.ini-xdebug `   | Example settings for Xdebug             |

* Do not edit these example files!
* Copy them to a new file (in case you want to use them)


## Overwriting

If multiple `.ini` files are present in this directory specifying different values for the
same settings, the last file (alphabetically by filename) will overwrite any previous values.
