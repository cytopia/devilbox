# Contributing

There is quite a lot todo and planned. If you like to contribute, pick any of the below topics or contact me directly.

Contributors will be credited within the intranet and on the github page.


## Roadmap

Please see [ROADMAP](https://github.com/cytopia/devilbox/issues/23) for what is planned.


## Documentation

* [ ] Improve documentation
* [ ] Remove all typos / wrong grammar

## Intranet

* [X] View emails sent/received within PHP dockers
* [ ] Better layout
* [ ] Better logos
* [ ] Try to remove as much vendor dependencies as possible

## Updating Vendors

#### phpMyAdmin

The following settings must be applied to `config.inc.php`:
```php
<?php
$cfg['TempDir'] = '/tmp';
$cfg['CheckConfigurationPermissions'] = false;

$cfg['blowfish_secret'] = 'add whatever value here';

$cfg['Servers'][$i]['host'] = 'mysql';
$cfg['Servers'][$i]['AllowNoPassword'] = true;
```
