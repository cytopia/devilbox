# PHP-FPM 5.2 config directory

**This is different from all other PHP-FPM versions**


PHP-FPM 5.2 uses XML-style configuration and does not allow includes.
If you want to change PHP-FPM settings for PHP-FPM 5.2 you need to adjust the main configuration file.

The currently enabled configuration file is bundled in this directory: `php-fpm.xml-default`.

In order to make adjustments, copy it to `php-fpm.xml` and change values.


## How to enable

Settings are only enabled if a file named `php-fpm.xml` is present.
All other files are ignoed.


## Important

Do not simply add anything in that file. You must copy php-fpm.xml-default and adjust values
step by step.

The `php-fpm.xml` will completely overwrite PHP-FPM configuration.
