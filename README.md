<p align="center">
    <h1 align="center">The Devilbox</h1>
</p>

![Devilbox](docs/img/banner.png)

**[Usage](#usage)** |
**[Community](#community)** |
**[Features](#feature-overview)** |
**[Intranet](#intranet-overview)** |
**[Screenshots](#screenshots)** |
**[Contributing](#contributing-)** |
**[Logos](#logos)** |
**[License](#license)**

![Devilbox](docs/_includes/figures/devilbox/devilbox-intranet-dash-all.png)

[![Build Status](https://travis-ci.org/cytopia/devilbox.svg?branch=master)](https://travis-ci.org/cytopia/devilbox)
[![Documentation Status](https://readthedocs.org/projects/devilbox/badge/?version=latest)](https://devilbox.readthedocs.io)
![Tag](https://img.shields.io/github/tag/cytopia/devilbox.svg)
[![type](https://img.shields.io/badge/type-Docker-red.svg)](https://www.docker.com/)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://opensource.org/licenses/MIT)

<img width="200" style="width:200px;" src="docs/_includes/figures/https/https-ssl-address-bar.png" />

The Devilbox is a modern and highly customisable **dockerized PHP stack** supporting full **LAMP** and **MEAN** and running on all major platforms. The main goal is to easily switch and combine any version required for local development. It supports an **unlimited number of projects** for which **vhosts**, **SSL certificates** and **DNS records** are created automatically. Email catch-all and popular development tools will be at your service as well. Configuration is not necessary, as everything is already pre-setup.

Furthermore, the Devilbox provides an **identical** and **reproducable development environment** for different host operating systems.

**Requirements**

![Linux](https://raw.githubusercontent.com/cytopia/icons/master/64x64/linux.png)
![OSX](https://raw.githubusercontent.com/cytopia/icons/master/64x64/osx.png)
![Windows](https://raw.githubusercontent.com/cytopia/icons/master/64x64/windows.png)
![Plus](https://raw.githubusercontent.com/cytopia/icons/master/64x64/plus.png)
![Docker](https://raw.githubusercontent.com/cytopia/icons/master/64x64/docker.png)

* [Docker Engine 1.12.0+](https://docs.docker.com/compose/compose-file/compose-versioning/#version-21)
* [Docker Compose 1.9.0+](https://docs.docker.com/compose/compose-file/compose-versioning/#version-21)


## Community

Find documentation and help here

<table width="100%" style="width:100%; display:table;">
 <thead>
  <tr>
   <th width="33%" style="width:33%;"><h3><a href="https://devilbox.readthedocs.io" >Documentation</a></h3></th>
   <th width="33%" style="width:33%;"><h3><a href="https://gitter.im/devilbox/Lobby" >Chat</a></h3></th>
   <th width="33%" style="width:33%;"><h3>Forum</h3></th>
  </tr>
 </thead>
 <tbody style="vertical-align: middle; text-align: center;">
  <tr>
   <td>
    <a href="https://devilbox.readthedocs.io">
	 <img title="Documentation" name="Documentation" src="https://raw.githubusercontent.com/cytopia/icons/master/400x400/readthedocs.png" />
    </a>
   </td>
   <td>
    <a href="https://gitter.im/devilbox/Lobby">
     <img title="Chat on Gitter" name="Chat on Gitter" src="https://raw.githubusercontent.com/cytopia/icons/master/400x400/gitter.png" />
    </a>
   </td>
   <td>
    <img title="Forum" name="Forum" src="https://raw.githubusercontent.com/cytopia/icons/master/400x400/discourse.png" />
   </td>
  </tr>
  <tr>
  <td><a href="https://devilbox.readthedocs.io">devilbox.readthedocs.io</a></td>
  <td><a href="https://gitter.im/devilbox/Lobby">gitter.im/devilbox</a></td>
  <td>coming soon</td>
  </tr>
 </tbody>
</table>



## Usage

#### Quick start

<table width="100%" style="width:100%; display:table;">
 <thead>
  <tr>
   <th width="50%" style="width:33%;">Linux and MacOS</th>
   <th width="50%" style="width:33%;">Windows</th>
  </tr>
 </thead>
 <tbody style="vertical-align: bottom;">
  <tr>
   <td>
    <pre># Get the Devilbox
$ git clone https://github.com/cytopia/devilbox</pre>
<pre># Create docker-compose environment file
$ cd devilbox
$ cp env-example .env</pre>
<pre># Edit your configuration
$ vim .env</pre>
<pre># Start all container
$ docker-compose up</pre>
   </td>
   <td>
    1. Clone <code>https://github.com/cytopia/devilbox</code> to <code>C:\devilbox</code> with <a href="https://git-scm.com/downloads">Git for Windows</a><br/><br/>
    2. Copy <code>C:\devilbox\env-example</code> to <code>C:\devilbox\.env</code><br/><br/>
    3. Edit <code>C:\devilbox\.env</code><br/><br/>
    4. <a href="https://devilbox.readthedocs.io/en/latest/howto/terminal/open-terminal-on-win.html">Open a terminal on Windows</a> and type:<br/><br/><br/>
    <pre># Start all container
C:\devilbox> docker-compose up</pre></div>
   </td>
  </tr>
 </tbody>
</table>

> **Documentation:**
> [Install the Devilbox](https://devilbox.readthedocs.io/en/latest/getting-started/install-the-devilbox.html) |
> [Start the Devilbox](https://devilbox.readthedocs.io/en/latest/getting-started/start-the-devilbox.html) |
> [.env file](https://devilbox.readthedocs.io/en/latest/configuration-files/env-file.html)


#### Selective start

The above will start all containers, you can however also just start the containers you actually need. This is achieved by simply specifying them in the docker-compose command.

```bash
$ docker-compose up httpd php mysql redis
```
> **Documentation:**
> [Start only some container](https://devilbox.readthedocs.io/en/latest/getting-started/start-the-devilbox.html#start-some-container)


![Devilbox](docs/img/devilbox-dash-selective.png)

#### Run different versions

Every single attachable container comes with many different versions. In order to select the desired version for a container, simply edit the `.env` file and uncomment the version of choice. Any combination is possible.

<table>
  <thead>
    <tr>
      <th>Apache</th>
      <th>Nginx</th>
      <th>PHP</th>
      <th>MySQL</th>
      <th>MariaDB</th>
      <th>Percona</th>
      <th>PgSQL</th>
      <th>Redis</th>
      <th>Memcached</th>
      <th>MongoDB</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><a target="_blank" title="Apache 2.2"       href="https://github.com/devilbox/docker-apache-2.2">2.2</a></td>
      <td><a target="_blank" title="Nginx stable"     href="https://github.com/devilbox/docker-nginx-stable">stable</a></td>
      <td><a target="_blank" title="PHP 5.2"          href="https://github.com/devilbox/docker-php-fpm">5.2</a><sup>[1]</sup></td>
      <td><a target="_blank" title="MySQL 5.5"        href="https://github.com/cytopia/docker-mysql-5.5">5.5</a></td>
      <td><a target="_blank" title="MariaDB 5.5"      href="https://github.com/cytopia/docker-mariadb-5.5">5.5</a></td>
      <td><a target="_blank" title="PerconaDB 5.5"    href="https://github.com/cytopia/docker-percona-5.5">5.5</a></td>
      <td><a target="_blank" title="PgSQL 9.1"        href="https://github.com/docker-library/postgres">9.1</a></td>
      <td><a target="_blank" title="Redis 2.8"        href="https://github.com/docker-library/redis">2.8</a></td>
      <td><a target="_blank" title="Memcached 1.4.21" href="https://github.com/docker-library/memcached">1.4.21</a></td>
      <td><a target="_blank" title="MongoDB 2.8"      href="https://github.com/docker-library/mongo">2.8</a></td>
    </tr>
    <tr>
      <td><a target="_blank" title="Apache 2.4"       href="https://github.com/devilbox/docker-apache-2.4">2.4</a></td>
      <td><a target="_blank" title="Nginx mainline"   href="https://github.com/devilbox/docker-nginx-mainline">mainline</a></td>
      <td><a target="_blank" title="PHP 5.3"          href="https://github.com/devilbox/docker-php-fpm">5.3</a></td>
      <td><a target="_blank" title="MySQL 5.6"        href="https://github.com/cytopia/docker-mysql-5.6">5.6</a></td>
      <td><a target="_blank" title="MariaDB 10.0"     href="https://github.com/cytopia/docker-mariadb-10.0">10.0</a></td>
      <td><a target="_blank" title="PerconaDB 5.6"    href="https://github.com/cytopia/docker-percona-5.6">5.6</a></td>
      <td><a target="_blank" title="PgSQL 9.2"        href="https://github.com/docker-library/postgres">9.2</a></td>
      <td><a target="_blank" title="Redis 3.0"        href="https://github.com/docker-library/redis">3.0</a></td>
      <td><a target="_blank" title="Memcached 1.4.22" href="https://github.com/docker-library/memcached">1.4.22</a></td>
      <td><a target="_blank" title="MongoDB 3.0"      href="https://github.com/docker-library/mongo">3.0</a></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><a target="_blank" title="PHP 5.4"          href="https://github.com/devilbox/docker-php-fpm">5.4</a></td>
      <td><a target="_blank" title="MySQL 5.7"        href="https://github.com/cytopia/docker-mysql-5.7">5.7</a></td>
      <td><a target="_blank" title="MariaDB 10.1"     href="https://github.com/cytopia/docker-mariadb-10.1">10.1</a></td>
      <td><a target="_blank" title="PerconaDB 5.7"    href="https://github.com/cytopia/docker-percona-5.7">5.7</a></td>
      <td><a target="_blank" title="PgSQL 9.3"        href="https://github.com/docker-library/postgres">9.3</a></td>
      <td><a target="_blank" title="Redis 3.2"        href="https://github.com/docker-library/redis">3.2</a></td>
      <td><a target="_blank" title="Memcached 1.4.23" href="https://github.com/docker-library/memcached">1.4.23</a></td>
      <td><a target="_blank" title="MongoDB 3.2"      href="https://github.com/docker-library/mongo">3.2</a></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><a target="_blank" title="PHP 5.5"          href="https://github.com/devilbox/docker-php-fpm">5.5</a></td>
      <td><a target="_blank" title="MySQL 8.0"        href="https://github.com/cytopia/docker-mysql-8.0">8.0</a></td>
      <td><a target="_blank" title="MariaDB 10.2"     href="https://github.com/cytopia/docker-mariadb-10.2">10.2</a></td>
      <td></td>
      <td><a target="_blank" title="PgSQL 9.4"        href="https://github.com/docker-library/postgres">9.4</a></td>
      <td><a target="_blank" title="Redis 4.0"        href="https://github.com/docker-library/redis">4.0</a></td>
      <td><a target="_blank" title="Memcached 1.4.23" href="https://github.com/docker-library/memcached">1.4.24</a></td>
      <td><a target="_blank" title="MongoDB 3.4"      href="https://github.com/docker-library/mongo">3.4</a></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><a target="_blank" title="PHP 5.6"          href="https://github.com/devilbox/docker-php-fpm">5.6</a></td>
      <td></td>
      <td><a target="_blank" title="MariaDB 10.3"     href="https://github.com/cytopia/docker-mariadb-10.3">10.3</a></td>
      <td></td>
      <td><a target="_blank" title="PgSQL 9.5"        href="https://github.com/docker-library/postgres">9.5</a></td>
      <td></td>
      <td>...</td>
      <td><a target="_blank" title="MongoDB 3.5"      href="https://github.com/docker-library/mongo">3.5</a></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><a target="_blank" title="PHP 7.0"          href="https://github.com/devilbox/docker-php-fpm">7.0</a></td>
      <td></td>
      <td></td>
      <td></td>
      <td><a target="_blank" title="PgSQL 9.6"        href="https://github.com/docker-library/postgres">9.6</a></td>
      <td></td>
      <td><a target="_blank" title="Memcached 1.5.2"  href="https://github.com/docker-library/memcached">1.5.2</a></td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><a target="_blank" title="PHP 7.1"          href="https://github.com/devilbox/docker-php-fpm">7.1</a></td>
      <td></td>
      <td></td>
      <td></td>
      <td><a target="_blank" title="PgSQL 10.0"       href="https://github.com/docker-library/postgres">10.0</a></td>
      <td></td>
      <td><a target="_blank" title="Memcached latest" href="https://github.com/docker-library/memcached">latest</a></td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><a target="_blank" title="PHP 7.2"          href="https://github.com/devilbox/docker-php-fpm">7.2</a></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><a target="_blank" title="PHP 7.3"          href="https://github.com/devilbox/docker-php-fpm">7.3</a></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </tbody>
</table>

<strong><sup>[1]</sup></strong> <strong>PHP 5.2</strong> is available to use, but it is not officially supported. The Devilbox intranet does not work with this version as PHP 5.2 does not support namespaces.
Furthermore PHP 5.2 does only work with Apache 2.4, Nginx stable and Nginx mainline. It does not work with Apache 2.2. Use at your own risk.

> **Documentation:**
> [Change container versions](https://devilbox.readthedocs.io/en/latest/getting-started/change-container-versions.html)


#### Enter the container

You can also work directly inside the php container. Simply use the bundled scripts `shell.sh` (or `shell.bat` for Windows).
The `PS1` will automatically be populated with current chosen php version.
Navigate the the Devilbox directory and type the below listed command:

<table width="100%" style="width:100%; display:table;">
 <thead>
  <tr>
   <th width="50%" style="width:33%;">Linux and MacOS</th>
   <th width="50%" style="width:33%;">Windows</th>
  </tr>
 </thead>
 <tbody style="vertical-align: bottom;">
  <tr>
   <td>
    <pre>host> ./shell.sh
devilbox@php-7.0.19 in /shared/httpd $</pre>
   </td>
   <td>
    <pre>C:\devilbox> shell.bat
devilbox@php-7.0.19 in /shared/httpd $</pre>
   </td>
  </tr>
 </tbody>
</table>

Your projects can be found in `/shared/httpd`. DNS records are automatically available inside the php container. Also every other service will be available on `127.0.0.1` inside the php container (tricky socat port-forwarding).

> **Documentation:**
> [Work inside the PHP container](https://devilbox.readthedocs.io/en/latest/intermediate/work-inside-the-php-container.html) |
> [Directory overview](https://devilbox.readthedocs.io/en/latest/getting-started/directory-overview.html)


#### Quick Video intro

[![Devilbox setup and workflow](docs/img/devilbox_01-setup-and-workflow.png "devilbox - setup and workflow")](https://www.youtube.com/watch?v=reyZMyt2Zzo)
[![Devilbox email catch-all](docs/img/devilbox_02-email-catch-all.png "devilbox - email catch-all")](https://www.youtube.com/watch?v=e-U-C5WhxGY)


## Feature overview

The Devilbox has everything setup for you. The only thing you will have to install is [Docker](https://docs.docker.com/engine/installation/) and [Docker Compose](https://docs.docker.com/compose/install/). Virtual hosts and DNS entries will be created automatically, just by adding new project folders.

> **Documentation:**
> [Devilbox Prerequisites](https://devilbox.readthedocs.io/en/latest/getting-started/prerequisites.html)

#### Features

<table>
<tbody>
  <tr>
    <td width="220" style="width:220px;">:star: Auto virtual hosts</td>
    <td>New virtual hosts are created automatically and instantly whenever you add a project directory. This is done internally via <a href="https://travis-ci.org/devilbox/vhost-gen">vhost-gen</a> and <a href="https://github.com/devilbox/watcherd">watcherd</a>.</td>
  </tr>
  <tr>
    <td>:star: Automated SSL certificate generation</td>
    <td>SSL certificates are automatically created for each vhost and signed by the Devilbox CA.</td>
  </tr>
  <tr>
    <td>:star: Unlimited vhosts</td>
    <td>Run as many projects as you need with a single instance of the Devilbox.</td>
  </tr>
  <tr>
    <td>:star: Custom vhosts</td>
    <td>You can overwrite and customise the default applied vhost configuration for every single vhost.</td>
  </tr>
  <tr>
    <td>:star: Custom domains</td>
    <td>Choose whatever development domain you desire: <code>*.loc</code>, <code>*.local</code>, <code>*.dev</code> or use real domains as well: <code>*.example.com</code></td>
  </tr>
  <tr>
    <td>:star: Auto DNS</td>
    <td>An integraded BIND server is able to create DNS entries automatically for your chosen domains.</td>
  </tr>
  <tr>
    <td>:star: Custom PHP config</td>
    <td>Overwrite any setting for PHP.</td>
  </tr>
  <tr>
    <td>:star: Custom PHP modules</td>
    <td>Load custom PHP modules on the fly.</td>
  </tr>
  <tr>
    <td>:star: Email catch-all</td>
    <td>All outgoing emails are catched and will be presented in the included intranet.</td>
  </tr>
  <tr>
    <td>:star: Self-validation</td>
    <td>Projects and configuration options are validated and marked in the intranet.</td>
  </tr>
  <tr>
    <td>:star: Xdebug</td>
    <td>Xdebug and a full blown PHP-FPM server is ready to serve.</td>
  </tr>
  <tr>
    <td>:star: Many more</td>
    <td>See Documentation for all available features.</td>
  </tr>
</tbody>
</table>

> **Documentation:**
> [Setup Auto DNS](https://devilbox.readthedocs.io/en/latest/intermediate/setup-auto-dns.html) |
> [Setup valid HTTPS](https://devilbox.readthedocs.io/en/latest/intermediate/setup-valid-https.html) |
> [Configure Xdebug](https://devilbox.readthedocs.io/en/latest/intermediate/configure-php-xdebug.html) |
> [Customize PHP](https://devilbox.readthedocs.io/en/latest/advanced/customize-php-globally.html)

#### Batteries

<table>
<tbody>
  <tr>
    <td>:open_file_folder:</td>
    <td><a href="https://www.phpmyadmin.net">phpMyAdmin</a></td>
  </tr>
  <tr>
    <td>:open_file_folder:</td>
    <td><a href="https://www.adminer.org">Adminer</a></td>
  </tr>
  <tr>
    <td>:bar_chart:</td>
    <td><a href="https://github.com/PeeHaa/OpCacheGUI">OpCache GUI</a></td>
  </tr>
  <tr>
    <td>:email:</td>
    <td>Mail viewer</td>
  </tr>
</tbody>
</table>


#### Tools

The following tools will assist you on creating new projects easily as well as helping you check your code against guidelines.

<table>
<tbody>
  <tr>
    <td width="220" style="width:220px;">:wrench: <a href="https://github.com/cytopia/awesome-ci">awesome-ci</a></td>
    <td>A set of tools for static code analysis:<br/><br/><code>file-cr</code>, <code>file-crlf</code>, <code>file-empty</code>, <code>file-nullbyte-char</code>, <code>file-trailing-newline</code>, <code>file-trailing-single-newline</code>, <code>file-trailing-space</code>, <code>file-utf8</code>, <code>file-utf8-bom</code>, <code>git-conflicts</code>, <code>git-ignored</code>, <code>inline-css</code>, <code>inline-js</code>, <code>regex-grep</code>, <code>regex-perl</code>, <code>syntax-bash</code>, <code>syntax-css</code>, <code>syntax-js</code>, <code>syntax-json</code>, <code>syntax-markdown</code>, <code>syntax-perl</code>, <code>syntax-php</code>, <code>syntax-python</code>, <code>syntax-ruby</code>, <code>syntax-scss</code>, <code>syntax-sh</code></td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://github.com/nvie/gitflow">git flow</a></td>
    <td><code>git-flow</code> is a Git extensions to provide high-level repository operations for Vincent Driessen's branching model.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://github.com/zaach/jsonlint">json lint</a></td>
    <td><code>jsonlint</code> is a command line linter for JSON files.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://github.com/laravel/installer">laravel installer</a></td>
    <td><code>laravel</code> is a command line tool that lets you easily install the Laravel framework.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://github.com/cytopia/linkcheck">linkcheck</a></td>
    <td><code>linkcheck</code> is a command line tool that searches for URLs in files (optionally limited by extension) and validates their HTTP status code.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://linuxbrew.sh/">linuxbrew</a></td>
    <td><code>brew</code> is a MacOS Homenbrew clone for Linux.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://github.com/DavidAnson/markdownlint">markdownlint</a></td>
    <td><code>markdownlint</code> is a markdown linter.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://github.com/markdownlint/markdownlint">mdl</a></td>
    <td><code>mdl</code> is a markdown linter.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://github.com/phalcon/phalcon-devtools">phalcon devtools</a></td>
    <td><code>phalcon</code> is a command line tool that lets you easily install the PhalconPHP framework.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://github.com/photoncms/installer">photon installer</a></td>
    <td><code>photon</code> is a command line tool that lets you easily install the PhotonCMS.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://github.com/squizlabs/PHP_CodeSniffer">php code sniffer</a></td>
    <td><code>phpcs</code> is a command line tool that tokenizes PHP, JavaScript and CSS files and detects violations of a defined set of coding standards.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://github.com/squizlabs/PHP_CodeSniffer">php code beautifier</a></td>
    <td><code>phpcbf</code> is a command line tool that automatically correct coding standard violations.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://github.com/brigade/scss-lint/">scss-lint</a></td>
    <td><code>scss-lint</code> is a css/scss linter.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://github.com/symfony/symfony-installer">symfony installer</a></td>
    <td><code>symfony</code> is a command line tool that lets you easily install the Symfony framework.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://github.com/jonas/tig">tig</a></td>
    <td><code>tig</code> is a text-mode interface for git.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://wp-cli.org">wp-cli</a></td>
    <td><code>wp</code> is a command line tool that lets you easily install Wordpress.</td>
  </tr>
  <tr>
    <td>:wrench: <a href="https://github.com/adrienverge/yamllint">yamllint</a></td>
    <td><code>yamllint</code> is a linter for yaml files.</td>
  </tr>
</tbody>
</table>

Well-known and popular tools will be at your service as well:

<a target="_blank" title="Ansible" href="https://www.ansible.com/"><img width="64" style="width:64px" src="docs/img/logo_tools/ansible.png" alt="Devilbox"/></a>
<a target="_blank" title="CodeCeption" href="https://codeception.com/"><img width="64" style="width:64px" src="docs/img/logo_tools/codeception.png" alt="Devilbox"/></a>
<a target="_blank" title="Composer" href="https://getcomposer.org"><img width="64" style="width:64px" src="docs/img/logo_tools/composer.png" alt="Devilbox"/></a>
<a target="_blank" title="Drupal Console" href="https://drupalconsole.com"><img width="64" style="battery" src="docs/img/logo_tools/drupal-console.png" alt="Devilbox"/></a>
<a target="_blank" title="Drush" href="https://www.drupal.org/project/drush"><img width="64" style="width:64px;" src="docs/img/logo_tools/drush.png" alt="Devilbox"/></a>
<a target="_blank" title="ESLint" href="https://eslint.org/"><img width="64" style="width:64px;" src="docs/img/logo_tools/eslint.png" alt="Devilbox"/></a>
<a target="_blank" title="Git" href="https://git-scm.com"><img width="64" style="width:64px;" src="docs/img/logo_tools/git.png" alt="Devilbox"/></a>
<a target="_blank" title="Gulp" href="https://gulpjs.com/"><img width="64" style="width:64px;" src="docs/img/logo_tools/gulp.png" alt="Devilbox"/></a>
<a target="_blank" title="Grunt" href="https://gruntjs.com/"><img width="64" style="width:64px;" src="docs/img/logo_tools/grunt.png" alt="Devilbox"/></a>
<a target="_blank" title="mysqldump-secure" href="https://mysqldump-secure.org"><img width="64" style="width:64px;" src="docs/img/logo_tools/mysqldump-secure.png" alt="Devilbox"/></a>
<a target="_blank" title="NodeJS" href="https://nodejs.org"><img width="64" style="width:64px;" src="docs/img/logo_tools/nodejs.png" alt="Devilbox"/></a>
<a target="_blank" title="NPM" href="https://www.npmjs.com"><img width="64" style="width:64px;" src="docs/img/logo_tools/npm.png" alt="Devilbox"/></a>
<a target="_blank" title="PHPUnit" href="https://phpunit.de/"><img width="64" style="width:64px;" src="docs/img/logo_tools/phpunit.png" alt="Devilbox"/></a>
<a target="_blank" title="Sass" href="https://sass-lang.com/"><img width="64" style="width:64px;" src="docs/img/logo_tools/sass.png" alt="Devilbox"/></a>
<a target="_blank" title="Webpack" href="https://webpack.js.org/"><img width="64" style="width:64px;" src="docs/img/logo_tools/webpack.png" alt="Devilbox"/></a>
<a target="_blank" title="Yarn" href="https://yarnpkg.com/en/"><img width="64" style="width:64px;" src="docs/img/logo_tools/yarn.png" alt="Devilbox"/></a>


#### Available PHP Modules

The Devilbox is a development stack, so it is made sure that a lot of PHP modules are available out of the box in order to work with many different frameworks.

> *amqp, apcu, bcmath, bz2, calendar, Core, ctype, curl, date, dba, dom, enchant, exif, fileinfo, filter, ftp, gd, gettext, gmp, hash, iconv, igbinary, imagick, imap, interbase, intl, json, ldap, libxml, mbstring, mcrypt, memcache, memcached, mongodb, msgpack, mysqli, mysqlnd, openssl, pcntl, pcre, PDO, pdo_dblib, PDO_Firebird, pdo_mysql, pdo_pgsql, pdo_sqlite, pgsql, phalcon, Phar, posix, pspell, readline, recode, redis, Reflection, session, shmop, SimpleXML, snmp, soap, sockets, sodium, SPL, sqlite3, standard, swoole, sysvmsg, sysvsem, sysvshm, tidy, tokenizer, uploadprogress, wddx, xdebug, xml, xmlreader, xmlrpc, xmlwriter, xsl, Zend OPcache, zip, zlib*

PHP modules can be enabled or disabled on demand to reflect the state of your target environment.

> **Documentation:**
> [Enable/disable PHP modules](https://devilbox.readthedocs.io/en/latest/intermediate/enable-disable-php-modules.html)


#### Custom PHP Modules

You can also copy any custom modules into `mod/(php-fpm)-<VERSION>` and add a custom `*.ini` file to load them.

#### Supported Frameworks

As far as tested there are no limitations and you can use any Framework or CMS just as you would on your live environment. Below are a few examples of extensively tested Frameworks and CMS:

<a target="_blank" title="CakePHP" href="https://cakephp.org"><img width="64" style="width:64px" src="docs/img/logo_fw/cake.png" alt="Devilbox"/></a>
<a target="_blank" title="CodeIgniter" href="https://www.codeigniter.com"><img width="64" style="width:64px" src="docs/img/logo_fw/codeigniter.png" alt="Devilbox"/></a>
<a target="_blank" title="CraftCMS" href="https://craftcms.com/"><img width="64" style="width:64px" src="docs/img/logo_fw/craftcms.png" alt="Devilbox"/></a>
<a target="_blank" title="Drupal" href="https://www.drupal.org"><img width="64" style="width:64px" src="docs/img/logo_fw/drupal.png" alt="Devilbox"/></a>
<a target="_blank" title="Joomla" href="https://www.joomla.org"><img width="64" style="width:64px" src="docs/img/logo_fw/joomla.png" alt="Devilbox"/></a>
<a target="_blank" title="Laravel" href="https://laravel.com"><img width="64" style="width:64px" src="docs/img/logo_fw/laravel.png" alt="Devilbox"/></a>
<a target="_blank" title="Magento" href="https://magento.com"><img width="64" style="width:64px" src="docs/img/logo_fw/magento.png" alt="Devilbox"/></a>
<a target="_blank" title="PhalconPHP" href="https://phalconphp.com"><img width="64" style="width:64px" src="docs/img/logo_fw/phalcon.png" alt="Devilbox"/></a>
<a target="_blank" title="PhotonCMS" href="https://photoncms.com"><img width="64" style="width:64px" src="docs/img/logo_fw/photoncms.png" alt="Devilbox"/></a>
<a target="_blank" title="PrestaShop" href="https://www.prestashop.com/en"><img width="64" style="width:64px" src="docs/img/logo_fw/prestashop.png" alt="Devilbox"/></a>
<a target="_blank" title="Shopware" href="https://en.shopware.com"><img width="64" style="width:64px" src="docs/img/logo_fw/shopware.png" alt="Devilbox"/></a>
<a target="_blank" title="Symfony" href="https://symfony.com"><img width="64" style="width:64px" src="docs/img/logo_fw/symfony.png" alt="Devilbox"/></a>
<a target="_blank" title="Typo3" href="https://typo3.org"><img width="64" style="width:64px" src="docs/img/logo_fw/typo3.png" alt="Devilbox"/></a>
<a target="_blank" title="Wordpress" href="https://wordpress.org"><img width="64" style="width:64px" src="docs/img/logo_fw/wordpress.png" alt="Devilbox"/></a>
<a target="_blank" title="Yii" href="http://www.yiiframework.com"><img width="64" style="width:64px" src="docs/img/logo_fw/yii.png" alt="Devilbox"/></a>
<a target="_blank" title="Zend" href="https://framework.zend.com"><img width="64" style="width:64px" src="docs/img/logo_fw/zend.png" alt="Devilbox"/></a>

> **Documentation:**<br/>
> [Setup CakePHP](https://devilbox.readthedocs.io/en/latest/examples/setup-cakephp.html) |
> [Setup CodeIgniter](https://devilbox.readthedocs.io/en/latest/examples/setup-codeigniter.html) |
> [Setup CraftCMS](https://devilbox.readthedocs.io/en/latest/examples/setup-craftcms.html) |
> [Setup Drupal](https://devilbox.readthedocs.io/en/latest/examples/setup-drupal.html) |
> [Setup Joomla](https://devilbox.readthedocs.io/en/latest/examples/setup-joomla.html) |
> [Setup Laravel](https://devilbox.readthedocs.io/en/latest/examples/setup-laravel.html) |
> [Setup Magento](https://devilbox.readthedocs.io/en/latest/examples/setup-magento.html) |
> [Setup PhalconPHP](https://devilbox.readthedocs.io/en/latest/examples/setup-phalcon.html) |
> [Setup PhotonCMS](https://devilbox.readthedocs.io/en/latest/examples/setup-photon-cms.html) |
> [Setup PrestaShop](https://devilbox.readthedocs.io/en/latest/examples/setup-presta-shop.html) |
> [Setup Shopware](https://devilbox.readthedocs.io/en/latest/examples/setup-shopware.html) |
> [Setup Symfony](https://devilbox.readthedocs.io/en/latest/examples/setup-symfony.html) |
> [Setup Typo3](https://devilbox.readthedocs.io/en/latest/examples/setup-typo3.html) |
> [Setup Wordpress](https://devilbox.readthedocs.io/en/latest/examples/setup-wordpress.html) |
> [Setup Yii](https://devilbox.readthedocs.io/en/latest/examples/setup-yii.html) |
> [Setup Zend](https://devilbox.readthedocs.io/en/latest/examples/setup-zend.html)


## Intranet overview

The Devilbox comes with a pre-configured intranet on `http://localhost` and `https://localhost`. This can be explicitly disabled or password-protected. The intranet will not only show you, the chosen configuration, but also validate the status of the current configuration, such as if **DNS records** exists (on host and container), are directories properly set-up. Additionally it provides external tools to let you interact with databases and emails.

* **Virtual Host overview** (validates directories and DNS)
* **Database overview** (MySQL, PgSQL, Redis, Memcache, ...)
* **Email overview**
* **Info pages** (Httpd, MySQL, PgSQL, Redis, Memcache, ...)
* **[phpMyAdmin](https://www.phpmyadmin.net)**
* **[Adminer](https://www.adminer.org)**
* **[OpcacheGUI](https://github.com/PeeHaa/OpCacheGUI)**

> **Documentation:**
> [Devilbox Intranet](https://devilbox.readthedocs.io/en/latest/getting-started/devilbox-intranet.html)


## Screenshots

A few examples of how the built-in intranet looks like.

<table>
<tbody>
 <tr>
  <td rowspan="2">
   <a href="docs/img/screenshots/01_intranet_home.png"><img style="width:250px;" width="250" src="docs/img/screenshots/01_intranet_home.png" /></a>
  </td>
  <td>
   <a href="docs/img/screenshots/02_intranet_vhosts.png"><img style="width:250px" width="250" src="docs/img/screenshots/02_intranet_vhosts.png" /></a>
  </td>
  <td>
   <a href="docs/img/screenshots/03_intranet_databases.png"><img style="width:250px;" width="250" src="docs/img/screenshots/03_intranet_databases.png" /></a>
  </td>
 </tr>
 <tr>
  <td>
   <a href="docs/img/screenshots/04_intranet_emails.png"><img style="width:250px;" width="250" src="docs/img/screenshots/04_intranet_emails.png" /></a>
  </td>
  <td></td>
 </tr>
</table>


## Contributing [![Open Source Helpers](https://www.codetriage.com/cytopia/devilbox/badges/users.svg)](https://www.codetriage.com/cytopia/devilbox)

The Devilbox is still a young project with a long roadmap of features to come. Features are
decided by you - **the community**, so any kind of contribution is welcome.

To increase visibility and bug-free operation:

* Star this project
* Open up issues for bugs and feature requests
* Clone this project and submit fixes or features

Additionally you can [subscribe to Devilbox on CodeTriage](https://www.codetriage.com/cytopia/devilbox),
read up on [CONTRIBUTING.md](CONTRIBUTING.md) and check the [ROADMAP](https://github.com/cytopia/devilbox/issues/23) about what is already planned for the near future.


## Logos

Logos and banners can be found at **[devilbox/artwork](https://github.com/devilbox/artwork)**. Feel free to use or modify them by the terms of their license.

<img style="width:64px; height:64px;" width="64" height="64" src="https://github.com/devilbox/artwork/blob/master/submissions_logo/cytopia/01/png/logo_128_trans.png?raw=true" /> <img style="width:64px; height:64px;" width="64" height="64" src="https://github.com/devilbox/artwork/blob/master/submissions_logo/cytopia/02/png/logo_128_trans.png?raw=true" /> <img style="height:64px;" height="64" src="https://github.com/devilbox/artwork/blob/master/submissions_banner/cytopia/01/png/banner_128_trans.png?raw=true" />

## License

[MIT License](LICENSE.md)

Copyright (c) 2016 [cytopia](https://github.com/cytopia)
