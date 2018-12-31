# Global vhost-gen configurations

Enabling templates in this directory will change the webserver vhost configuration globally for all projects which do not have their own specific vhost-gen template in place.

In order for template files to be picked up by the web server they must have the correct name.

Copy the example templates to a new file with the correct name either in this directory which
will then apply those settings for all vhosts or into the corresponding project directory to
only make the settings for a specific project.


## Normal virtual hosts

| Web server     | Example template               | Template name  |
|----------------|--------------------------------|----------------|
| Apache 2.2     |  `apache22.yml-example-vhost`  | `apache22.yml` |
| Apache 2.4     |  `apache24.yml-example-vhost`  | `apache24.yml` |
| Nginx stable   |  `nginx.yml-example-vhost`     | `nginx.yml`    |
| Nginx mainline |  `nginx.yml-example-vhost`     | `nginx.yml`    |


## Reverse proxy virtual hosts

**Do not apply the `*-rproxy` templates globally (in this directory), or everything will stop
working. Use them only for per project settings!**

| Web server     | Example template               | Template name  |
|----------------|--------------------------------|----------------|
| Apache 2.2     |  `apache22.yml-example-rproxy` | `apache22.yml` |
| Apache 2.4     |  `apache24.yml-example-rproxy` | `apache24.yml` |
| Nginx stable   |  `nginx.yml-example-rproxy`    | `nginx.yml`    |
| Nginx mainline |  `nginx.yml-example-rproxy`    | `nginx.yml`    |
