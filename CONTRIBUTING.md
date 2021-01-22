# Contributing


**Abstract**

The Devilbox is currently being developed in my spare time and mostly reflects the features that I
am using for all the web projects I have to handle. In order to better present it to the majority
of other software developers I do require support to cope with all the feature requests.


So first of all, If the Devilbox makes your life easier, **star it on GitHub**!

**Table of Contents**

1. [How to contribute](#how-to-contribute)
    1. [Documentation](#documentation)
    2. [Docker Container](#docker-container)
    3. [New Features](#new-features)
    4. [Intranet](#intranet)
    5. [Vendors](#vendors)
    6. [Tests](#tests)
2. [Joining the Devilbox GitHub Organization](#joining-the-devilbox-github-organization)
3. [Important](#important)


## 1. How to contribute

There are various areas that need support. If you are willing to help, pick a topic below and start
contributing. If you are unclear about anything, let me know and I will clarify.

See the general [ROADMAP](https://github.com/cytopia/devilbox/issues/23) for what is planned.

### Documentation

**Required knowledge:** [Sphinx](http://www.sphinx-doc.org/en/stable/)

* General improvement of the documentation (typos, grammar, etc)
* Better documentation for setting up Xdebug
* More how to's on how to setup a specific framework or CMS
* General how to's and blog posts

### Docker Container

**Required knowledge:** Docker, [Ansible](https://www.ansible.com/), Apache, Nginx, MySQL, PHP-FPM

* Consolidate MySQL, PerconaDB and MariaDB into one repository for easier change management
* Consolidate Nginx and Apache into one repository for easier change management
* Performance improvements on Apache/Nginx and PHP-FPM
* Add new container to the stack

### New Features

**Required knowledge:** Various

Have a look at the GitHub issues and see if you can implement any features requested

### Intranet

**Required knowledge:** PHP, HTML, CSS and Javascript

* [ ] Fix email view: https://github.com/cytopia/devilbox/issues/337
* [ ] Better and more modern layout
* [ ] Try to remove as much vendor dependencies as possible


### Vendors

#### Upgrade Adminer

Adminer requires some adjustments to work with the Devilbox intranet. See below for files to adjust:

`adminer-x.y.z-en.php`
```diff
- login($_e,$E){if($E=="")return
+ login($_e,$E){return true;if($E=="")return
```

#### Upgrade phpMyAdmin

phpMyAdmin requires some adjustments to work with the Devilbox intranet. See below for files to adjust:

`config.inc.php`
```diff
+ error_reporting(0);

- $cfg['blowfish_secret'] = ''; /* YOU MUST FILL IN THIS FOR COOKIE AUTH! */
+ $cfg['TempDir'] = '/tmp';
+ $cfg['CheckConfigurationPermissions'] = false;
+ $cfg['blowfish_secret'] = 'a;guurOrep[[hoge7p[jgde7reouHoy5590hjgffuJ676FGd434&%*09UJHogfT%$#F64';



- /* Authentication type */
- $cfg['Servers'][$i]['auth_type'] = 'cookie';
- /* Server parameters */
- $cfg['Servers'][$i]['host'] = 'localhost';
- $cfg['Servers'][$i]['compress'] = false;
- $cfg['Servers'][$i]['AllowNoPassword'] = false;
+ /* Authentication type */
+ if (getenv('DEVILBOX_VENDOR_PHPMYADMIN_AUTOLOGIN') == 1) {
+     $cfg['Servers'][$i]['auth_type'] = 'config';
+     $cfg['Servers'][$i]['user'] = 'root';
+     $cfg['Servers'][$i]['password'] = getenv('MYSQL_ROOT_PASSWORD');
+ } else {
+ $cfg['Servers'][$i]['auth_type'] = 'cookie';
+ }
+ /* Server parameters */
+ $cfg['Servers'][$i]['host'] = 'mysql';
+ $cfg['Servers'][$i]['connect_type'] = 'tcp';
+ $cfg['Servers'][$i]['compress'] = false;
+ $cfg['Servers'][$i]['AllowNoPassword'] = true;

- //$cfg['SendErrorReports'] = 'always';
+ $cfg['SendErrorReports'] = 'never';
```

#### Upgrade phpRedmin

phpRedmin requires some adjustments to work with the Devilbox intranet. See below for files to adjust:

`config.dist.php`
```diff
+ // Check if redis is using a password
+ $REDIS_ROOT_PASSWORD = '';
+ 
+ $_REDIS_ARGS = getenv('REDIS_ARGS');
+ $_REDIS_PASS = preg_split("/--requirepass\s+/",  $_REDIS_ARGS);
+ if (is_array($_REDIS_PASS) && count($_REDIS_PASS)) {
+   // In case the option is specified multiple times, use the last effective one.
+   $_REDIS_PASS = $_REDIS_PASS[count($_REDIS_PASS)-1];
+   if (strlen($_REDIS_PASS) > 0) {
+     $REDIS_ROOT_PASSWORD = $_REDIS_PASS;
+   }
+ }

- 'database'  => array(
-     'driver' => 'redis',
-     'mysql'  => array(
-         'host'     => 'localhost',
-         'username' => 'root',
-         'password' => 'root'
-     ),
-     'redis' => array(
-         array(
-             'host'     => 'localhost',
-             'port'     => '6379',
-             'password' => null,
-             'database' => 0,
-             'max_databases' => 16, /* Manual configuration of max databases for Redis < 2.6 */
-             'stats'    => array(
-                 'enable'   => 1,
-                 'database' => 0,
-             ),
-             'dbNames' => array( /* Name databases. key should be database id and value is the name */
-             ),
-         ),
-     ),
- ),
+ 'database'  => array(
+     'driver' => 'redis',
+     'mysql'  => array(
+         'host'     => 'mysql',
+         'username' => 'root',
+         'password' => getenv('MYSQL_ROOT_PASSWORD')
+     ),
+     'redis' => array(
+         array(
+             'host'     => 'redis',
+             'port'     => '6379',
+             'password' => $REDIS_ROOT_PASSWORD,
+             'database' => 0,
+             'max_databases' => 16, /* Manual configuration of max databases for Redis < 2.6 */
+             'stats'    => array(
+                 'enable'   => 1,
+                 'database' => 0,
+             ),
+             'dbNames' => array( /* Name databases. key should be database id and value is the name */
+             ),
+         ),
+     ),
+ ),
```

`libraries/drivers/db/redis.php`
```diff
- if (isset($config['password'])) {
-     $this->auth($config['password']);
- }
+ if (isset($config['password']) && strlen($config['password'])>0) {
+     $this->auth($config['password']);
+ }
```

#### Upgrade phpPgAdmin

phpPgAdmin requires some adjustments to work with the Devilbox intranet. See below for files to adjust:

`conf/config.inc.php`
```diff
- $conf['servers'][0]['host'] = '';
+ $conf['servers'][0]['host'] = 'pgsql';

- $conf['extra_login_security'] = true;
+ $conf['extra_login_security'] = false;

+ // ---- Auto-login
+ if (getenv('DEVILBOX_VENDOR_PHPPGADMIN_AUTOLOGIN') == 1) {
+   $_REQUEST['server']= 'pgsql:5432:allow';
+   if(session_id() == ''){
+     //session has not started
+     session_name('PPA_ID');
+     session_start();
+   }
+   $_SESSION['sharedUsername'] = getenv('PGSQL_ROOT_USER');
+   $_SESSION['sharedPassword'] = getenv('PGSQL_ROOT_PASSWORD');
+ }
+ // ---- end of Auto-login
```
`libraries/lib.inc.php`
```diff
- error_reporting(E_ALL);
+ error_reporting(E_ERROR | E_WARNING | E_PARSE);

- if (!ini_get('session.auto_start')) {
-   session_name('PPA_ID');
-   session_start();
- }
+ if (!strlen(session_id()) > 0) {
+   session_name('PPA_ID');
+   session_start();
+ }
```
`libraries/adodb/drivers/adodb-postgres64.inc.php`
```diff
- function ADODB_postgres64()
+ function __construct()
```

`libraries/adodb/drivers/adodb-postgres7.inc.php`
```diff
- function ADODB_postgres7()
+ public function __construct()
     {
-        $this->ADODB_postgres64();
+        parent::__construct();
```

### Tests

**Required knowledge:** [Travis-CI](https://docs.travis-ci.com/)

* Shorten CI test time for faster releases
* Rewrite current tests, write new tests


## Joining the Devilbox GitHub Organization

If you want to contribute on a regular base and take care about major feature development you can
be invited to the GitHub organization.

This however requires some prerequisites:

1. Willing to dedicate a regular amount of time to invest in this project
2. Already spent a decent amount of time in improving the Devilbox
3. A good understanding about the Devilbox
4. A good understanding about the PHP-FPM container (and how it is built with Ansible)
