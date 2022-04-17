# motranslator

Translation API for PHP using Gettext MO files.

![Test-suite](https://github.com/phpmyadmin/motranslator/workflows/Run%20tests/badge.svg?branch=master)
[![codecov.io](https://codecov.io/github/phpmyadmin/motranslator/coverage.svg?branch=master)](https://codecov.io/github/phpmyadmin/motranslator?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phpmyadmin/motranslator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phpmyadmin/motranslator/?branch=master)
[![Packagist](https://img.shields.io/packagist/dt/phpmyadmin/motranslator.svg)](https://packagist.org/packages/phpmyadmin/motranslator)

## Features

* All strings are stored in memory for fast lookup
* Fast loading of MO files
* Low level API for reading MO files
* Emulation of Gettext API
* No use of `eval()` for plural equation

## Limitations

* Not suitable for huge MO files which you don't want to store in memory
* Input and output encoding has to match (preferably UTF-8)

## Installation

Please use [Composer][1] to install:

```sh
composer require phpmyadmin/motranslator
```

## Documentation

The API documentation is available at <https://develdocs.phpmyadmin.net/motranslator/>.

## Object API usage

```php
// Create loader object
$loader = new PhpMyAdmin\MoTranslator\Loader();

// Set locale
$loader->setlocale('cs');

// Set default text domain
$loader->textdomain('domain');

// Set path where to look for a domain
$loader->bindtextdomain('domain', __DIR__ . '/data/locale/');

// Get translator
$translator = $loader->getTranslator();

// Now you can use Translator API (see below)
```

## Low level API usage

```php
// Directly load the mo file
// You can use null to not load a file and the use a setter to set the translations
$translator = new PhpMyAdmin\MoTranslator\Translator('./path/to/file.mo');

// Now you can use Translator API (see below)
```

## Translator API usage

```php
// Translate string
echo $translator->gettext('String');

// Translate plural string
echo $translator->ngettext('String', 'Plural string', $count);

// Translate string with context
echo $translator->pgettext('Context', 'String');

// Translate plural string with context
echo $translator->npgettext('Context', 'String', 'Plural string', $count);

// Get the translations
echo $translator->getTranslations();

// All getters and setters below are more to be used if you are using a manual loading mode
// Example: $translator = new PhpMyAdmin\MoTranslator\Translator(null);

// Set a translation
echo $translator->setTranslation('Test', 'Translation for "Test" key');

// Set translations
echo $translator->setTranslations([
  'Test' => 'Translation for "Test" key',
  'Test 2' => 'Translation for "Test 2" key',
]);

// Use the translation
echo $translator->gettext('Test 2'); // -> Translation for "Test 2" key
```

## Gettext compatibility usage

```php
// Load compatibility layer
PhpMyAdmin\MoTranslator\Loader::loadFunctions();

// Configure
_setlocale(LC_MESSAGES, 'cs');
_textdomain('phpmyadmin');
_bindtextdomain('phpmyadmin', __DIR__ . '/data/locale/');
_bind_textdomain_codeset('phpmyadmin', 'UTF-8');

// Use functions
echo _gettext('Type');
echo __('Type');

// It also support other Gettext functions
_dnpgettext($domain, $msgctxt, $msgid, $msgidPlural, $number);
_dngettext($domain, $msgid, $msgidPlural, $number);
_npgettext($msgctxt, $msgid, $msgidPlural, $number);
_ngettext($msgid, $msgidPlural, $number);
_dpgettext($domain, $msgctxt, $msgid);
_dgettext($domain, $msgid);
_pgettext($msgctxt, $msgid);
```

## History

This library is based on [php-gettext][2]. It adds some performance
improvements and ability to install using [Composer][1].

## Motivation

Motivation for this library includes:

* The [php-gettext][2] library is not maintained anymore
* It doesn't work with recent PHP version (phpMyAdmin has patched version)
* It relies on `eval()` function for plural equations what can have severe security implications, see CVE-2016-6175
* It's not possible to install it using [Composer][1]
* There was place for performance improvements in the library

### Why not to use native gettext in PHP?

We've tried that, but it's not a viable solution:

* You can not use locales not known to system, what is something you can not
  control from web application. This gets even more tricky with minimalist
  virtualisation containers.
* Changing the MO file usually leads to PHP segmentation fault. It (or rather
  Gettext library) caches headers of MO file and if it's content is changed
  (for example new version is uploaded to server) it tries to access new data
  with old references. This is bug known for ages:
  https://bugs.php.net/bug.php?id=45943

### Why use Gettext and not JSON, YAML or whatever?

We want translators to be able to use their favorite tools and we want us to be
able to use wide range of tools available with Gettext as well such as 
[web based translation using Weblate][3]. Using custom format usually adds
another barrier for translators and we want to make it easy for them to
contribute.

[1]:https://getcomposer.org/
[2]:https://launchpad.net/php-gettext
[3]:https://weblate.org/
