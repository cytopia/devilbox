# Custom Varnish configuration

Place your `*.vcl` files here to customize Varnish.

* This directory will be mounted into `/etc/varnish.d/` into the Varnish container.
* Ensure to point your `VARNISH_CONFIG` variable to `/etc/varnish.d/<my-file>.vcl` when you want to use it
* Ensure to set your Backend server to `httpd` in your custom varnish config
* Ensure to set your Backend port to `80` in your custom varnish config
