# Documentation

## Adding projects

As an example, we are going to add two projects.

**Prerequisites**

For this case let's assume your www root folder points to `~/www`. This means your projects will reside on your host computer in your home directory under www.

In order to achive this set `HOST_PATH_TO_WWW_DOCROOTS=~/www` in the `.env` file (If this file does not yet exist, copy `env-example` to `.env`).

`.env`:
```
...
HOST_PATH_TO_WWW_DOCROOTS=~/www
...
```

**Desired Projects**

| Project name | Document Root | URL |
|--------------|---------------|-----|
| devilbox     | ~/www/devilbox/htdocs | http://devilbox.loc |
| foo.bar      | ~/www/foo.bar/htdocs | http://foo.bar.loc |

`htdocs` can either be a folder or a symlink to a folder.

**Project: devilbox**

Setup projects folder and an `index.php` (on your host computer)

```shell
$ mkdir -p ~/www/devilbox/htdocs
$ vim ~/devilbox/htdocs/index.php
```

```php
<?php
echo 'hello world';
?>
```

Adjust your local (host computer) `/etc/hosts` and point `devilbox.loc` to your localhost address `127.0.0.1`

```shell
$ sudo vim /etc/hosts
```
```shell
127.0.0.1 devilbox.loc
```


**Project: foo.bar**

Setup projects folder and use existing github project to serve.

```shell
$ mkdir -p ~/www/foo.bar
$ cd ~/www/foo.bar

# Use an existing github project as your document root
$ git clone https://github.com/<user>/<some-project>

# Symlink the project to htdocs
$ ln -s <some-project> htdocs

$ ls -l
drwxr-xr-x 4 cytopia 1286676289  136 Oct 30 14:24 <some-project>/
lrwxr-xr-x 1 cytopia 1286676289  549 Nov  6 15:13 htdocs -> <some-project>/
```


Adjust your local (host computer) `/etc/hosts` and point `foo.bar.loc` to your localhost address `127.0.0.1`

```shell
$ sudo vim /etc/hosts
```
```shell
127.0.0.1 foo.bar.loc
```

