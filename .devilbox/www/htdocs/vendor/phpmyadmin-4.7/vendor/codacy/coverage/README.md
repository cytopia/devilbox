[![Codacy Badge](https://api.codacy.com/project/badge/grade/d992a862b1994805907ec277e16b0fda)](https://www.codacy.com/app/Codacy/php-codacy-coverage)
[![Codacy Badge](https://api.codacy.com/project/badge/coverage/d992a862b1994805907ec277e16b0fda)](https://www.codacy.com/app/Codacy/php-codacy-coverage)
[![Circle CI](https://circleci.com/gh/codacy/php-codacy-coverage.svg?style=shield&circle-token=:circle-token)](https://circleci.com/gh/codacy/php-codacy-coverage)
[![Latest Stable Version](https://poser.pugx.org/codacy/coverage/version)](https://packagist.org/packages/codacy/coverage)

# Codacy PHP Coverage Reporter
[Codacy](https://codacy.com/) coverage support for PHP. Get coverage reporting and code analysis for PHP from Codacy.

# Prerequisites

- PHP 5.3 or later
- One of the following coverage report formats
  - Clover XML (e.g. ```--coverage-clover``` in PHPUnit)
  - PHPUnit XML (e.g. ```--coverage-xml``` in PHPUnit)

# Installation

Setup codacy-coverage with Composer, just add the following to your composer.json:

```js
// composer.json
{
    "require-dev": {
        "codacy/coverage": "dev-master"
    }
}
```

Download the dependencies by running Composer in the directory of your `composer.json`:

```sh
# install
$ php composer.phar install --dev
# update
$ php composer.phar update codacy/coverage --dev
```

codacy-coverage library is available on [Packagist](https://packagist.org/packages/codacy/coverage).

Add the autoloader to your php script:

```php
require_once 'vendor/autoload.php';
```

> Note:
We have php5-curl dependency, if you have issues related to curl_init() please install it with:
```
sudo apt-get install php5-curl
```

## Updating Codacy

To update Codacy, you will need your project API token. You can find the token in Project -> Settings -> Integrations -> Project API.

Then set it in your terminal, replacing %Project_Token% with your own token:

```
export CODACY_PROJECT_TOKEN=%Project_Token%
```

> Note: You should keep your API token well **protected**, as it grants owner permissions to your projects.

> To send coverage in the enterprise version you should:
```
export CODACY_API_BASE_URL=<Codacy_instance_URL>:16006
```

# Usage

Run ```vendor/bin/codacycoverage``` to see a list of commands.

#### Basic usage for Clover format:

```vendor/bin/codacycoverage clover```

#### Basic usage for PHPUnit XML format:

```php vendor/bin/codacycoverage phpunit```

By default we assume that
- your Clover coverage report is saved in ```build/logs/clover.xml```
- your PHPUnit XML report is saved in the directory ```build/coverage-xml```

#### Optional parameters:

You can specify the path to your report with the second parameter:

- Clover XML
  - ```php vendor/bin/codacycoverage clover path/to/a-clover.xml```
- PHPUnit XML
  - ```php vendor/bin/codacycoverage phpunit directory/path/to/phpunitreport```

Even more control:

- ```--base-url=<OTHER_URL>``` defaults to http://codacy.com
- ```--git-commit=<COMMIT_HASH>``` defaults to the last commit hash

## Circle CI

This project sends its own coverage during the build in circleCI.
Feel free to check our `circle.yml`, and send your coverage as a step of your build process. 

## Travis CI

Add codacycoverage to your `.travis.yml`:

```yml
# .travis.yml
language: php
php:
  - 5.3
  - 5.4
  - 5.5
  - 5.6
  - hhvm

before_script:
  - curl -s http://getcomposer.org/installer | php
  - php composer.phar install -n

script:
  - php vendor/bin/phpunit

after_script:
  - php vendor/bin/codacycoverage clover path/to/clover.xml
```

## Troubleshooting

If you have a fatal error regarding curl_init():
```
PHP Fatal error:  Uncaught Error: Call to undefined function Codacy\Coverage\Util\curl_init() in /src/Codacy/Coverage/Util/CodacyApiClient.php:30
```
Run: ```sudo apt-get install php5-curl```

## What is Codacy?

[Codacy](https://www.codacy.com/) is an Automated Code Review Tool that monitors your technical debt, helps you improve your code quality, teaches best practices to your developers, and helps you save time in Code Reviews.

### Among Codacy’s features:

- Identify new Static Analysis issues
- Commit and Pull Request Analysis with GitHub, BitBucket/Stash, GitLab (and also direct git repositories)
- Auto-comments on Commits and Pull Requests
- Integrations with Slack, HipChat, Jira, YouTrack
- Track issues in Code Style, Security, Error Proneness, Performance, Unused Code and other categories

Codacy also helps keep track of Code Coverage, Code Duplication, and Code Complexity.

Codacy supports PHP, Python, Ruby, Java, JavaScript, and Scala, among others.

### Free for Open Source

Codacy is free for Open Source projects.

## License
[MIT](LICENSE)
