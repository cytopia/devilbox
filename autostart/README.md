# Custom startup scripts (global)

Any script inside this directory ending by `.sh` will be executed during the PHP container startup.
This is useful to apply your custom settings such as installing software that usually requires
the user to accept a license or similar.

A few examples are given that do not end by `.sh` which won't be run. If you want to use the
provided examples, copy them to a file ending by `.sh`


## Info

If you want to autostart NodeJS applications, you can use [pm2](https://github.com/Unitech/pm2).
Ensure you do this as user `devilbox`, as by default everything is run by root.

```bash
su -c 'cd /shared/httpd/node/node; pm2 start index.js' -l devilbox
```


## Note

This directory will startup commands for all PHP versions. If you want to selectively run commands
for a specific version, go to `cfg/php-startup-X.Y/`.


## Important

All provided scripts will be executed with **root** permissions.
