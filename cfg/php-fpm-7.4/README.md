# PHP-FPM 7.4 config directory

## General

* Add you custom php-fpm.conf files into this directory.
* Only files ending by `.conf` will be enabled
* Only files ending by `.conf` are ignored by git


## Example files

This directory also holds three example files:

| File                            | Description                                 |
|---------------------------------|---------------------------------------------|
| `devilbox-fpm.conf-default`     | Represents current PHP-FPM default settings |
| `devilbox-fpm.conf-pm_dynamic`  | Example settings for dynamic workers        |
| `devilbox-fpm.conf-pm_ondemand` | Example settings for ondemand workers       |

* Do not edit these example files!
* Copy them to a new file (in case you want to use them)


## Worker configuration

When changing worker processes or scheduler, the following commands will come in handy
to monitor number of processes and memory consumption.

```bash
# Show current PHP-FPM child memory consumption in MB
ps -ylC php-fpm --sort:rss | awk '!/RSS/ { s+=$8 } END { printf "%dM\n", s/1024 }'

# (repeatedly) show current PHP-FPM child memory consumption in MB
watch --interval=1 "ps -ylC php-fpm --sort:rss | awk '"'!'"/RSS/ { s+=\$8 } END { printf \"%dM\n\", s/1024 }'"

# (repeatedly) Current number of PHP-FPM childs
watch --interval=1 "ps auxw | grep -E 'php-(cgi|fpm)' | grep -vE 'grep|master' | wc -l"
```


## Overwriting

If multiple `.conf` files are present in this directory specifying different values for the
same settings, the last file (alphabetically by filename) will overwrite any previous values.


## Compatibility

**Note:**

PHP-FPM 5.2 uses XML-style configuration and does not allow includes.
If you want to change php-fpm.conf for PHP-FPM 5.2 you need to adjust the main configuration file.

See `php-fpm-5.2/` directory.
