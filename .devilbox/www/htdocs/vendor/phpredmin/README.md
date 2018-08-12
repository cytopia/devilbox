PHPRedMin
=========

PHPRedMin is a simple web interface to manage and monitor your Redis.

## Technologies Used

[Gearman](https://gearman.org) application framework

[Nanophp](https://github.com/sasanrose/NanoPHP) framework

[Bootstrap](http://twitter.github.com/bootstrap) front-end framework

[JQuery](http://jquery.com/) JavaScript library

[Nvd3](https://github.com/novus/nvd3) reusable chart library for d3.JS

_Note:_ PHPRedmin is mostly compatible with [phpredis](https://github.com/nicolasff/phpredis) redis module for PHP

## Installation

### Docker

You can use docker to run PHPRedmin as a one-liner program:

```Bash
docker run -p 8080:80 -d --name phpredmin -e "PHPREDMIN_DATABASE_REDIS_0_HOST=192.168.1.6" -e "PHPREDMIN_AUTH_USERNAME=root" sasanrose/phpredmin
```
And then you can just easily point your broswer to http://localhost:8080

_Note:_ As you can see you can use ENV variables to override any configuration directive of PHPRedmin. For instance in the aforementioned command we changed the redis host and authentication username.

Moreover, you can just use docker compose to also setup a redis container:

```Yaml
version: '2'
services:
    phpredmin:
        image: sasanrose/phpredmin
        environment:
            - PHPREDMIN_DATABASE_REDIS_0_HOST=redis
        ports:
            - "8080:80"
        depends_on:
            - redis
    redis:
        image: redis
```

### Manual installation

Just drop phpredmin in your webserver's root directory and point your browser to it (You also need [phpredis](https://github.com/nicolasff/phpredis) installed)

Apache configuration example (/etc/httpd/conf.d/phpredmin.conf):

```ApacheConf
# phpredmin - Simple web interface to manage and monitor your Redis
#
# Allows only localhost by default

Alias /phpredmin /var/www/phpredmin/public

<Directory /var/www/phpredmin/>
   AllowOverride All

   <IfModule mod_authz_core.c>
     # Apache 2.4
     <RequireAny>
       Require ip localhost
       Require local
     </RequireAny>
   </IfModule>

   <IfModule !mod_authz_core.c>
     # Apache 2.2
     Order Deny,Allow
     Deny from All
     Allow from 127.0.0.1
     Allow from ::1
   </IfModule>
</Directory>
```

_Note:_ If your redis server is on an IP or port other than defaults (localhost:6379), you should edit config.php file

## Configuration

### Basic Authentication

By default, the dashboard is password protected using Basic Authentication Mechanism, with the default username and password set to ```admin```.

You can find the ```auth``` config section inside the ```config.dist.php``` file, below is the default configuration:

```
...
$config = array(
...
'auth' => array(
  'username' => 'admin',
  'password' => password_hash('admin', PASSWORD_DEFAULT)
),
...
);
...
```

Remove the ```auth``` section in the config file to disable the protection.

_Note:_ You should use the [password_hash](http://php.net/manual/en/function.password-hash.php) function with your desired password and store the result in the ```password``` key, instead of storing the plaintext password as in the code above.

## Features

### Multi-Server functionality

You can add as many redis servers as you want to your config.php file and choose between the defined servers from the menu available on the left side of all pages in PHPRedMin:

![](http://dl.dropbox.com/u/5413590/phpredmin/multiserver.png)

We must credit [Eugene Fidelin](https://github.com/eugef) for his great contributions to implement this feature

### Statistics

_Note:_ If you want this feature to work, you have to setup the cron to gather data from your redis server as follows:

```bash
* * * * * root cd /var/www/phpredmin/public && php index.php cron/index
```

#### Memory

![](http://dl.dropbox.com/u/5413590/phpredmin/memory.jpg)

#### CPU And Clients

![](http://dl.dropbox.com/u/5413590/phpredmin/cpu.jpg)

#### Keys and Connections

![](http://dl.dropbox.com/u/5413590/phpredmin/keyspace.jpg)

#### Databases

![](http://dl.dropbox.com/u/5413590/phpredmin/dbkeys.jpg)

### Console

PHPRedMin provides you with a web-base redis console. This functionality takes advantage of PHP's `exec` function. Although, all the commands are escaped for security, you can disable terminal from configuration file. In addition, you can set history limit or disable history by setting it to 0:

![](http://dl.dropbox.com/u/5413590/phpredmin/console.jpg)

### Info

Information about your redis setup

![](http://dl.dropbox.com/u/5413590/phpredmin/info.jpg)

### Configurations

View your redis runtime configurations

![](http://dl.dropbox.com/u/5413590/phpredmin/config.jpg)

### Slowlog

Find slow redis commands

_Note:_ PHPRedMin uses eval to fetch slow log. So to use this feature you need redis version >= 2.6.0

![](http://dl.dropbox.com/u/5413590/phpredmin/slowlog.jpg)

### Database Manipulation

You can easily switch between databases belonging to different servers easily:

![](http://dl.dropbox.com/u/5413590/phpredmin/multiserver.png)

You can flush selected database or all databases. You can also save database to a file on disk:

![](http://dl.dropbox.com/u/5413590/phpredmin/actions.jpg)

### Key-Value Manipulation

#### Search

The search box will let you to easily search keys in the selected database:
_Note:_ Becareful, since this still doesn't support pagination, try to limit your search otherwise if your search result is too long (e.g. *) then your browser might crash.

![](http://dl.dropbox.com/u/5413590/phpredmin/search.jpg)

The search results will be shown to you as a table. In this table besides the basic information about each key, PHPRedMin provides you with some actions:

* Expire (Sets TTL for a key)
* View (Shows keys' value/values and lets you manipulate it/them)
* Rename
* Move (Moves key to another database)
* Delete

![](http://dl.dropbox.com/u/5413590/phpredmin/results.jpg)

#### Add key-Value

From the main page of PHPRedMin you can add different types of key-values.

##### Strings

![](http://dl.dropbox.com/u/5413590/phpredmin/addstring.jpg)

##### Hashes

![](http://dl.dropbox.com/u/5413590/phpredmin/addhash.jpg)

##### Lists

![](http://dl.dropbox.com/u/5413590/phpredmin/addlist.jpg)

##### Sets

![](http://dl.dropbox.com/u/5413590/phpredmin/addset.jpg)

##### Sorted Sets

![](http://dl.dropbox.com/u/5413590/phpredmin/addzset.jpg)

### View keys' values

PHPRedMin makes it easier for you to manage your lists, hashes, sets and sorted sets. After searching for a special key, you can choose view action to see the contents of that key (According to its type) and manipulate them.

#### Lists

_Note:_ This supports pagination

![](http://dl.dropbox.com/u/5413590/phpredmin/listresult.jpg)

#### Hashes

![](http://dl.dropbox.com/u/5413590/phpredmin/hashresult.jpg)

#### Sets

_Note:_ This supports pagination

_Note:_ Thanks to [Ahmed Hamdy](https://github.com/ahmed-hamdy90) you can now edit members of a set

![](http://dl.dropbox.com/u/5413590/phpredmin/setresult.jpg)

#### Sorted Sets

_Note:_ This supports pagination

![](http://dl.dropbox.com/u/5413590/phpredmin/zsetresult.jpg)

### Bulk Actions

#### Bulk Delete

This feature lets you delete a key or a bunch of keys using wild cards

![](http://dl.dropbox.com/u/5413590/phpredmin/bulk-delete.png)

![](http://dl.dropbox.com/u/5413590/phpredmin/bulk-delete-progress.png)

_Note:_ This feature needs gearman. You have to both install gearman and php gearman extension

#### Gearman Worker

You can run gearman worker using the following command:

```bash
php index.php gearman/index
```
You can also setup a service for this command. I prefer supervisord to make it always running. Here is my config file:

```bash
[program:phpredmin]
directory=/var/www/phpredmin/public
command=php index.php gearman/index
process_name=%(program_name)s
numprocs=1
stdout_logfile=/var/log/supervisor/phpredmin.log
autostart=true
autorestart=true
user=sasan
```

## License

BSD 3-Clause License

Copyright © 2013, Sasan Rose

All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
* Neither the name of the PHPRedMin nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
