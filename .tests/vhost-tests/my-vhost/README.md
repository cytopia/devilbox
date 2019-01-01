# Virtual host tests

Tests in this directory are run by a virtual host as well as executed on the cli inside the
PHP container.

Every single PHP file must echo out `OK` and only `OK` if it succeeded.
The tests are looking for the following regex string in the complete output: `/^OK$/`.
