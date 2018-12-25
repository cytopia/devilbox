# Custom startup scripts

Any script inside this directory ending by `.sh` will be executed during the PHP container startup.
This is useful to apply your custom settings such as installing software that usually requires
the user to accept a license or similar.

A few examples are given that do not end by `.sh` which won't be run. If you want to use the
provided examples, copy them to a file ending by `.sh`


## Important

All provided scripts will be executed with **root** permissions.
