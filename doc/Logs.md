# Documentation

[Home](https://github.com/cytopia/devilbox) |
[Overview](README.md) |
[Configuration](Configuration.md) |
[Usage](Usage.md) |
[Updating](Updating.md) |
[Info](Info.md) |
[PHP Projects](PHP_Projects.md) |
[Emails](Emails.md) |
Logs |
[Intranet](Intranet.md) |
[FAQ](FAQ.md)

----

## Logs

Log information is available in two forms.

1. Mounted log directories
2. Docker logs

### Mounted log directories

Inside the devilbox directory you will find a folder `log/`.
This itself will contain subdirectories in the form `<service>-<version>` which will then hold all available log files.

**Example:**

```
devilbox/
  log/
    apache-2.2/
      access_log
      error_log
      localhost-access.log
      localhost-error.log
      other-error.log
    mariadb-10.3/
      error.log
      query.log
      slow.log
    php-fpm-7.0/
      php-fpm.err
      www-access.log
      www-error.log
```

### Docker logs

All output that is produced to stdout or stderr by the started service will be available in `docker logs`. In order to view them constantly in a terminal session use:

```shell
docker-compose logs -f
```
