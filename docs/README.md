# Devilbox Documentation

[Home](https://github.com/cytopia/devilbox) |
Overview |
[Configuration](Configuration.md) |
[Usage](Usage.md) |
[Updating](Updating.md) |
[Info](Info.md) |
[PHP Projects](PHP_Projects.md) |
[Emails](Emails.md) |
[Logs](Logs.md) |
[Intranet](Intranet.md) |
[FAQ](FAQ.md)

----

## Overview

![Devilbox](img/devilbox-dash.png)

This is a brief overview to get you started as quick as possible. For in-depth documentation use the navigation above.


### Install, Configure and Start

```shell
# Get the soures
$ git clone https://github.com/cytopia/devilbox
$ cd devilbox

# Create and customize the config file
$ cp env-example .env
$ vim .env

# Start your container
$ docker-compose up
```

### Create projects

Inside the `.env` file you will find two important variables:
1. `HOST_PATH_HTTPD_DATADIR`
2. `TLD_SUFFIX`

The first one defines the root path for all your projects and the second one defines your desired domain suffix (default: `loc`). Inside the `HOST_PATH_HTTPD_DATADIR` folder you will have to create the following generic directory structure: `<project-dir>/htdocs`. Files from the `htdocs` folder are then served via `http://<project-dir>.<TLD_SUFFIX>`.

**TL;DR (easy)**  

  - Assuming `TLD_SUFFIX` equals `loc`
  - Assuming `HOST_PATH_HTTPD_DATADIR` equals `/shared/httpd/`
  - Assuming desired project name equals `my-new-project`
  - `mkdir -p /shared/httpd/my-new-project/htdocs`
  - `echo "127.0.0.1 my-new-project.loc" | sudo tee --append /etc/hosts`
  - `curl http://my-new-project.loc`
  -


**TL;DR (pro)**  

  - `export project=my-new-project`
  - `. .env`
  - `mkdir -p ${HOST_PATH_HTTPD_DATADIR}/${project}/htdocs`
  - `echo "127.0.0.1 ${project}.${TLD_SUFFIX}" | sudo tee --append /etc/hosts`
  - `curl http://${project}.${TLD_SUFFIX}`

Here is a more complete example for the directory structure:
```
  project1/
     htdocs/
  project2/
     htdocs/
  some-random-name/
     htdocs  -> ./some-dir/              # <-- symlinks are also possible
	 some-dir/
  my-website.com/
     htdocs  -> /shared/httpd/site.com/  # <-- symlinks are also possible
```

You will then have to extend `/etc/hosts` with your created foldernames plus the tld suffix:
```
127.0.0.1 project1.loc
127.0.0.1 project2.loc
127.0.0.1 some-random-name.loc
127.0.0.1 my-website.com.loc
```

Contents inside the `htdocs` folder will be server via the configured domain automatically. So in order to access project2's htdoc folder go to `http://project2.loc` (assuming `TLD_SUFFIX` was `loc`)
