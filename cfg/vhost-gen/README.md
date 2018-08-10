# Global vhost-gen configurations

Enabling templates in this directory will change the webserver vhost configuration globally for all projects which do not have their own specific vhost-gen template in place.

In order for template files to be picked up by the web server they must have the correct name.

Copy the example templates to a new file with the correct name.

| Web server     | Example template        | Template name  |
|----------------|-------------------------|----------------|
| Apache 2.2     |  `apache22.yml-example` | `apache22.yml` |
| Apache 2.4     |  `apache24.yml-example` | `apache24.yml` |
| Nginx stable   |  `nginx.yml-example`    | `nginx.yml`    |
| Nginx mainline |  `nginx.yml-example`    | `nginx.yml`    |
